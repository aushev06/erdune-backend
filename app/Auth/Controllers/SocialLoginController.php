<?php


namespace App\Auth\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function callbackVK(Request $request)
    {
        $responseUser = Socialite::driver('vkontakte')->user();

        /**
         * @var User $user
         */
        $user = User::query()
            ->where('social_id', $responseUser->getId())
            ->orWhere('email', $responseUser->getEmail())
            ->first();

        if ($user !== null) {
            User::createIfNotExistAndAuth($user);

            return redirect()->to(session('redirect_to') ? session('redirect_to').'auth?token=' . $user->createToken('auth_token')->plainTextToken : config('app.frontend_url') .'/auth?token=' . $user->createToken('auth_token')->plainTextToken);
        }

        $newUser = [
            'name'      => $responseUser->getName(),
            'avatar'    => $responseUser->getAvatar(),
            'country'   => $responseUser->user['country'] ?? '',
            'email'     => $responseUser->email ?? '',
            'social_id' => $responseUser->getId(),
            'role'      => 'user',
            'network'   => 'vk',
            'ip'        => $request->ip(),
            'password'  => Hash::make(rand(0, 1000)),
            'links' => []
        ];

        $user = User::createIfNotExistAndAuth(userFields:$newUser);

        return redirect()->to(session('redirect_to') ? session('redirect_to').'auth?token=' . $user->createToken('auth_token')->plainTextToken : config('app.frontend_url') .'/auth?token=' . $user->createToken('auth_token')->plainTextToken);
    }

    public function facebook()
    {
        $responseUser = Socialite::driver('vkontakte')->user();
        $user         = User::where('email', $responseUser->accessTokenResponseBody['email'])->first();
    }
}
