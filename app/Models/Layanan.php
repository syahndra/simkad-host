<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'idLayanan';
    public $timestamps = false;

    protected $fillable = ['namaLayanan', 'jenis', 'aksesVer'];

    public function ajuanDafduk()
    {
        return $this->hasMany(AjuanDafduk::class, 'idLayanan');
    }

    public function ajuanCapil()
    {
        return $this->hasMany(AjuanCapil::class, 'idLayanan');
    }
}
