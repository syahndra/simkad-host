<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorKec extends Model
{
    protected $table = 'operatorkec';
    protected $primaryKey = 'idOpkec';
    public $timestamps = false;

    protected $fillable = [
        'idUser',
        'idKec',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'idKec', 'idKec');
    }
}
