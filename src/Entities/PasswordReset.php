<?php

namespace Arif\FleetCartApi\Entities;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = ['token', 'email'];
}
