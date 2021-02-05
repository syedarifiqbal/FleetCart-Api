<?php

namespace Arif\FleetCartApi\Http\Controllers\Auth;

use Arif\FleetCartApi\Providers\Entities\PasswordReset;
use App\Notifications\PasswordResetSuccess;
use Arif\FleetCartApi\Providers\Entities\User;
use Arif\FleetCartApi\Providers\Http\Controllers\BaseController;
use Arif\FleetCartApi\Providers\Http\Requests\PasswordRequest;
use Arif\FleetCartApi\Providers\Http\Requests\PasswordResetRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends BaseController
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(PasswordRequest $request)
    {
        $request->validate([
            'email' => 'required|string|email|exists:users',
        ]);

        $user = User::where('email', $request->email)->first();

        $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email], [ 'email' => $user->email, 'token' => rand(100000, 999999)]);

        if ($user && $passwordReset){
            $notification = config('fleetcart_api.reset_request_notification');
            $user->notify(new $notification($passwordReset));
        }

        return response()->json(['message' => 'We have e-mailed your password reset link!']);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function validateToken($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset)
            return $this->responseWithError('Unprocessable entities.', ['token' => ['Invalid Token']]);

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return $this->responseWithError('Unprocessable entities.', ['token' => ['This password reset token is invalid.']]);
        }

        return $passwordReset;
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(PasswordResetRequest $request)
    {
        $passwordReset = PasswordReset::where(['token' => $request->token, 'email' => $request->email])->first();

        if (!$passwordReset)
            return $this->responseWithError("Unprocessable Entities", ['token' => [trans('fleetcart_api::validation.invalid_token')]]);

        $user = User::where('email', $passwordReset->email)->first();

        $user->password = bcrypt($request->password);

        $user->save();

//        $passwordReset->delete();

        $notification = config('fleetcart_api.reset_success_notification');
        $user->notify(new $notification($passwordReset));

        return response()->json([
            "message" => trans('fleetcart_api::validation.reset_success_message')
        ]);
    }
}
