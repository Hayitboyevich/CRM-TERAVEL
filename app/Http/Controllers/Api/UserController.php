<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public final function findUser(Request $request): JsonResponse
    {
        $user = User::query()
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select(['users.id as id', 'users.name as name'])
            ->where(['roles.name' => 'client'])
            ->where(['users.company_id' => company()->id])
            ->where(['users.mobile' => $request->input('mobile')])->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }
}
