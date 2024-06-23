<?php

namespace App\Actions\Fortify;

use App\Http\Controllers\AccountBaseController;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewCustomer;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{

    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array $input
     * @return User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => [
                'required',
                'string',
                'max:9',
                Rule::unique(User::class),
            ],
            'password' => 'required|min:8',
        ])->validate();

        // Checking is google recaptcha is valid
        if (global_setting()->google_recaptcha_status == 'active') {
            $gRecaptchaResponseInput = global_setting()->google_recaptcha_v3_status == 'active' ? 'g_recaptcha' : 'g-recaptcha-response';
            $gRecaptchaResponse = $input[$gRecaptchaResponseInput];
            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);

            if (!$validateRecaptcha) {
                abort(403, __('auth.recaptchaFailed'));
            }
        }

        // Is worksuite
//        $company = Company::first();
        $company = Company::query()
            ->firstOrCreate(['company_name' => $input['company_name']], [
                'company_name' => $input['company_name'],
                'company_phone' => $input['mobile'],
                'company_email' => 'example@example.com',
                'address' => 'Your Company address here',
                'locale' => 'ru',
                'timezone' => 'Asia/Tashkent',
                'website' => 'http://example.com',
                'app_name' => $input['company_name'],
                'subdomain' => $input['subdomain'],
            ]);

        session(['company' => $company]);

        $user = User::create([
            'firstname' => $input['name'],
            'company_id' => $company->id,
            'name' => $input['name'],
            'mobile' => $input['mobile'],
            'locale' => 'ru',
            'password' => Hash::make($input['password']),
            'admin_approval' => !$company->admin_client_signup_approval,
        ]);
        $user->employeeDetail()->create([
            'company_id' => $company->id,
        ]);
        $data = $input;
        $data['email_notifications'] = 1;
        $user->clientDetails()->create(['company_name' => $company->company_name]);

        $role = Role::query()->where('company_id', $company->id)
            ->where('name', 'admin')->select('id')->first();
        $user->attachRole($role->id);

        $user->assignUserRolePermission($role->id);

        $log = new AccountBaseController();

        // Log search
        $log->logSearchEntry($user->id, $user->name, 'clients.show', 'client');

        if (!is_null($user->email)) {
            $log->logSearchEntry($user->id, $user->email, 'clients.show', 'client');
        }

        if (!is_null($user->clientDetails->company_name)) {
            $log->logSearchEntry($user->id, $user->clientDetails->company_name, 'clients.show', 'client');
        }

        Notification::send(User::allAdmins($user->company->id), new NewCustomer($user));

        return $user;

    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse)
    {
        $secret = global_setting()->google_recaptcha_v2_status == 'active' ? global_setting()->google_recaptcha_v2_secret_key : global_setting()->google_recaptcha_v3_secret_key;

        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => $secret,
                    'response' => $googleRecaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]
        );

        $body = json_decode((string)$response->getBody());

        return $body->success;
    }

}
