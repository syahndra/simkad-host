<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Desa;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorDesa extends Model
{
    use SoftDeletes;

    protected $table = 'operatordesa';
    protected $primaryKey = 'idOpdes';
    public $timestamps = false;

    protected $fillable = [
        'idUser',
        'idDesa',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    // Relasi ke desa
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'idDesa', 'idDesa');
    }

    // Shortcut relasi ke kecamatan (via desa)
    public function kecamatan()
    {
        return $this->desa->kecamatan ?? null;
    }

    public function ajuanDafduk()
    {
        return $this->hasMany(AjuanDafduk::class, 'idOpdes');
    }

    public function ajuanCapil()
    {
        return $this->hasMany(AjuanCapil::class, 'idOpdes');
    }
}
