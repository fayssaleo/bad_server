<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'matricule',
        'firstname',
        'lastname',
        'isactive',
        'password',
        'shift',
    ];
}
