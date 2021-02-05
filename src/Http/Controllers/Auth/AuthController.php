<?php

namespace Arif\FleetCartApi\Http\Controllers\Auth;

use Arif\FleetCartApi\Providers\Entities\User;
use Arif\FleetCartApi\Providers\Http\Controllers\Auth\BaseAuthController;
use Arif\FleetCartApi\Providers\Http\Requests\LoginRequest;
use Arif\FleetCartApi\Providers\Http\Requests\RegisterRequest;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\User\Contracts\Authentication;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Modules\User\Mail\Welcome;

class AuthController extends BaseAuthController
{
    /**
     * The Authentication instance.
     *
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;

//        $this->middleware('api:guest')->except('getLogout');
    }
    //
    protected function redirectTo()
    {
        // TODO: Implement redirectTo() method.
    }

    protected function loginUrl()
    {
        // TODO: Implement loginUrl() method.
    }

    public function getLogin()
    {
        // TODO: Implement getLogin() method.
    }

    /**
     * Register a user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function postRegister(RegisterRequest $request): JsonResponse
    {
        $data = $request->only(['first_name', 'last_name', 'email', 'password']);

        $user = User::create($data);

        $this->assignCustomerRole($user);

        if (setting('welcome_email')) {
            Mail::to($request->email)
                ->send(new Welcome($request->first_name));
        }

        $token = $user->createToken('Web Token')->accessToken;

        return response()->json([
            'message' => trans('fleetcart_api::messages.account_created'),
            'token' => $token,
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    /**
     * Login a user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function postLogin(LoginRequest $request): JsonResponse
    {

        $user = User::whereEmail($request->email)->first();

        if(!Hash::check($request->password, $user->password))
        {
            return response()->json([
                'message' => trans('fleetcart_api::validation.auth.invalid_password')
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $token = $user->createToken('Web Token')->accessToken;
            return response()
                ->json([
                'token' => $token,
                'user' => $user
            ]);
        } catch (NotActivatedException $e) {
            return response()
                ->json([
                    'message' => trans('fleetcart_api::validation.auth.account_not_activated')
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ThrottlingException $e) {
            return response()->json([
                'message' => trans('fleetcart_api::validation.auth.account_is_blocked', ['delay' => $e->getDelay()])
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function me(Request $request){
        $user = auth()->user();

        $extend_me = config('fleetcart_api.extend_me');

        if(!$extend_me){
            return $user;
        }

        list($class, $method) = explode('@', $extend_me);

        $extend_me = new $class();

        return $extend_me->{$method}($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse|Response
     */
    public function update_me(UpdateProfileRequest $request)
    {
        $request->bcryptPassword();

        auth()->user()->update($request->all());

        return \response()->json(['message' => trans('account::messages.profile_updated')]);
    }

    public function getReset()
    {
        // TODO: Implement getReset() method.
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $token = $request->user()->token();
        $token->revoke();
        return response(['message' => 'You have been successfully logged out!'], 200);
    }

    protected function resetCompleteRoute($user, $code)
    {
        // TODO: Implement resetCompleteRoute() method.
    }

    protected function resetCompleteView()
    {
        // TODO: Implement resetCompleteView() method.
    }
}
