<?php

namespace Arif\FleetCartApi\Http\Requests;

use Modules\Core\Http\Requests\Request;

class RegisterRequest extends Request
{
    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = 'user::attributes.users';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $roles = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ];

        if(config('fleetcart_api.should_required_privacy_policy')){
            $roles['privacy_policy'] = 'accepted';
        }

        return $roles;
    }
}
