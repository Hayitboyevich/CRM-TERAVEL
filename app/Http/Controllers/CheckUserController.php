<?php

namespace App\Http\Controllers;

use App\Models\IntegrationCredential;
use App\Models\IntegrationPartner;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityHistory\Entities\ActivityHistory;

class CheckUserController extends Controller
{
    public function check(Request $request)
    {
        $rules = [
            'login' => 'required',
            'password' => 'required',
            'type' => 'required|exists:integration_credentials,type'
        ];

        $request->validate($rules);

        $credentials['mobile'] = $request->login;
        $credentials['password'] = $request->password;

        if (!Auth::attempt($credentials)) {
            return response()->json('Unable to login!', 403);
        }
        $user = User::query()->where(['mobile' => $request->login])->first();
        ActivityHistory::query()->create([
            'info' => $user->name . '  extension orqali tizimga kirdi ' . $request->type,
            'module_name' => ActivityHistory::LOGIN_MODULE_NAME,
            'ip' => $request->ip(),

        ]);
        $data = IntegrationPartner::query()->where(['type' => $request->type])->first();

        if (!$data) {
            return response()->json('Not found!', 404);
        }

        return response()->json($data);
    }

    public function list(): JsonResponse
    {
        $data = IntegrationCredential::query()->get('type')->pluck('type');
        return response()->json($data);
    }
}
