<?php
/**
 * Created by Synida Pry.
 * Copyright © 2020. All rights reserved.
 */

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Login API
     *
     * @param Request $request
     * @return JsonResponse
     * @author Synida Pry
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email ?? '', 'password' => $request->password ?? ''])) {
            /** @var User $user */
            $user = Auth::user();
            $success['token'] = $user->createToken('LaraPassport')->accessToken;
        }

        return response()->json(
            [
                'status' => isset($success) ? 'success' : 'error',
                'data' => $success ?? 'Unauthorized Access'
            ]
        );
    }
}
