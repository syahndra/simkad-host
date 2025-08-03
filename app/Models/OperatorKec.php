<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorKec extends Model
{
    use SoftDeletes;
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
