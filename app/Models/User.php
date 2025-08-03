<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'idUser';
    protected $fillable = [
        'nama',
        'email',
        'password',
        'roleUser',
        'status'
    ];

    public function respon()
    {
        return $this->hasMany(Respon::class, 'idUser');
    }
}
