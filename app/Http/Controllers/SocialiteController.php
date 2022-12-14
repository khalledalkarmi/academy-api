<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;

class SocialiteController extends Controller
{
    public function handleProviderCallback(Request $request)
    {
        $validator = Validator::make($request->only('provider', 'access_provider_token'), [
            'provider' => ['required', 'string'],
            'access_provider_token' => ['required', 'string']
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);
        $provider = $request->provider;
        $validated = $this->validateProvider($provider);
        if (!is_null($validated))
            return $validated;
        $providerUser = Socialite::driver($provider)->userFromToken($request->access_provider_token);
        $userName = explode(' ', $providerUser->getName(), 2);

        $user = User::firstOrCreate(
            [
                'email' => $providerUser->getEmail()
            ],
            [
                'first_name' => $userName[0],
                'last_name' => $userName[1],
            ],
        );
        $data = [
            'user_info' => $user,
            'token' => $user->createToken('Sanctom+Socialite')->plainTextToken,
            'token_type' => 'Bearer',

        ];
        return response()->json($data, 200);
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google'])) {
            return response()->json(["message" => 'You can only login via google account'], 400);
        }
    }
}
