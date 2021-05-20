<?php

namespace App\Rules;

use Illuminate\Support\Facades\Http;

class Authorization
{
    public static function authorize(): bool
    {
        $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        $authorize = json_decode($response->body());

        if ($authorize->message === "Autorizado") {
            return true;
        }

        return false;
    }

    public static function notify(): bool
    {
        $response = Http::get('http://o4d9z.mocklab.io/notify');
         $authorize = json_decode($response->body());

        if ($authorize->message === 'Success') {
            return true;
        }

        return false;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  Model  $userService
     * @param  int  $user
     * @param  float  $value
     * @return bool
     */
    public static function wallet($userService, int $user, float $value): bool
    {
        $user = $userService->find($user);

        return $user ? $user['money'] >= $value : false;
    }
}