<?php

namespace Modules\TelegramHelper\Services;


use App\Models\Integration;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Role;
use App\Models\User;
use DOMDocument;
use Exception;
use Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException;
use Froiden\RestAPI\Exceptions\ResourceNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use QL\QueryList;

class HistoryParser
{
    public function htmlToArray(Request $request)
    {
        $html = file_get_contents(storage_path('app/public/telegram.html'));
        $dom = new DOMDocument();

        $dom->loadHTML($html);
//        128147
        $elementId = 'div';
//        126380 125080
        for ($i = $request->from; $i < $request->to; $i++) {
            $divElement = $dom->getElementById('sms' . $i);
            if ($divElement && $text = $divElement->getElementsByTagName('div')->item(3)) {
                try {
                    $text = $divElement->getElementsByTagName('div')->item(3)->textContent;

                    $pattern = '/^(\d{2}:\d{2})/m';
                    preg_match($pattern, $text, $matches);

                    $pattern = '/\b([A-Za-z]+Bot)\b/';
                    preg_match($pattern, $text, $matches);

                    $pattern = '/Ismingiz: ([^T]+)/';
                    preg_match($pattern, $text, $matches);
                    $ismingiz = Arr::get($matches, 1);

                    $pattern = '/Telefon raqamingiz: (\d+)/';
                    preg_match($pattern, $text, $matches);
                    $telefon = Arr::get($matches, 1);

                    $pattern = '/Odam soni: (\d+)/';
                    preg_match($pattern, $text, $matches);
                    $odamSoni = Arr::get($matches, 1);

                    $pattern = '/Tur: ([^\n]+)/';
                    preg_match($pattern, $text, $matches);
                    $tur = Arr::get($matches, 1);

                    $telefon = $this->validateAndNormalizeNumber($telefon);
                    if (!$telefon) {
                        continue;
                    }
                    $this->store($telefon, $ismingiz);

//                    echo $fromName . "\n";
                    echo $ismingiz . "\n";
                    echo $telefon . "\n";
                    echo $odamSoni . "\n";
//                    echo $tur . "\n";


                } catch (Exception $exception) {
                    Log::error($exception->getMessage());
//                    continue;
                    dd($exception->getMessage());
                }
                Log::info('success');
            }
        }
    }

    function validateAndNormalizeNumber($number)
    {
        if (strpos($number, "+998") === 0) {
            // Remove the "+998" prefix
            $cleanedNumber = substr($number, 4);

            // Check if the remaining number is in the expected format
            if (preg_match('/^\d{9}$/', $cleanedNumber)) {
                return $cleanedNumber;
            } else {
                return null;
            }
        } else {
            return null;

        }
    }

    /**
     * @throws ResourceNotFoundException
     * @throws RelatedResourceNotFoundException
     */
    public function store($telefon, $ismingiz, $country, $people)
    {

        DB::beginTransaction();
        try {
            $user = User::query()->where(['mobile' => $telefon])->first();
            if (!$user) {
                $user = new User();
                $user->mobile = $this->validateAndNormalizeNumber($telefon) ?? $telefon;
                $nameParts = explode(" ", $ismingiz);
                $user->name = $ismingiz;
                $user->locale = 'ru';
                $user->company_id = 1;
                $user->country_phonecode = '+998';
                $user->firstname = $nameParts[0];

                $response = Http::get('https://api.genderize.io/?name=' . $nameParts[0]);
                $gender = json_decode($response, true);

                $user->gender = $gender['gender'] ?? 'male';
                $user->lastname = Arr::get($nameParts, 1);
                $user->password = Hash::make($telefon);
                $user->save();

                $user->clientDetails()->create();

                $role = Role::query()->where('name', 'client')->select('id')->first();
                if ($role) {
                    $user->attachRole($role);
                    $user->assignUserRolePermission($role->id);
                }
            }


            $integration = new Integration();
            $integration->user_id = $user->id;
            $integration->adults_count = $people;
            $integration->save();

            $lead_data['integration_id'] = $integration?->id ?? 1;
            $lead_data['client_id'] = $user?->id ?? 2;
            $lead_data['status_id'] = LeadStatus::query()
                ->where(['company_id' => 1])
                ->where(['priority' => 1])
                ->first()
                ->id;
            $lead_data['source_id'] = 26;

            $lead_data['company_id'] = 1;
            $lead_data['note'] = $country;

            $lead_data['client_name'] = $user->firstname . ' ' . $user->lastname;
            $lead_data['mobile'] = $user->mobile;
            $lead_data['added_by'] = 1;
            $lead_data['currency_id'] = 17;

            $lead = new Lead();
            $lead->fill($lead_data);
            $lead->save();

        } catch (Exception $exception) {
            DB::rollback();
            Log::info($exception->getMessage() . 'FILE' . $exception->getFile() . 'LINE ' . $exception->getLine());
            throw $exception;
        }
        DB::commit();

    }

    /**
     * @throws ResourceNotFoundException
     * @throws RelatedResourceNotFoundException
     */
    public function parse($text)
    {
        $countryPattern = '/Страна:\s+(.+)/i';
        preg_match($countryPattern, $text, $countryMatches);
        $country = isset($countryMatches[1]) ? trim($countryMatches[1]) : '';

        // Parse number of people
        $peoplePattern = '/Количество человек:\s+(\d+)/i';
        preg_match($peoplePattern, $text, $peopleMatches);
        $people = isset($peopleMatches[1]) ? intval($peopleMatches[1]) : 0;

        // Parse name
        $namePattern = '/Имя:\s+(.+)/i';
        preg_match($namePattern, $text, $nameMatches);
        $name = isset($nameMatches[1]) ? trim($nameMatches[1]) : '';

        // Parse number
        $pattern = '/\+998\d{9}\b/';
        preg_match_all($pattern, $text, $matches);
        $number = $matches[0][0];
        $number = $this->validateAndNormalizeNumber($number);
        if (!$number)
            throw new Exception('number not found' . $number . '   ' . $matches[0][0]);

        // Output the parsed information
        $this->store($number, $name, $country, $people);

    }
}
