<?php

namespace Arif\FleetCartApi\Http\Requests;

use Modules\Core\Http\Requests\Request;

class LoginRequest extends Request
{
    /**
     * Available attributes for users.
     *
     * @var string
     */
    protected $availableAttributes = 'user::attributes.users';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => trans('fleetcart_api::validation.auth.invalid_email')
        ];
    }
}
