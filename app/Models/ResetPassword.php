<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'idToken';
    protected $fillable = [
        'email',
        'otp_code',
        'otp_expires_at'
    ];
    public $timestamps = false;
}
