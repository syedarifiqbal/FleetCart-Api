<?php

namespace Arif\FleetCartApi\Entities;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\User\Entities\User as BaseUser;

class User extends BaseUser
{
    use HasApiTokens, Notifiable;
}
