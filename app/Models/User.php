<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'user';

    protected $primary_key = 'id';

    protected $fillable = [
        'username',
        'password',
        'email',
        'phone_number',
    ];

    protected $dates = ['deleted_at'];
}
