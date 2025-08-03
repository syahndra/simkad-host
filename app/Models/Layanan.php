<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Layanan extends Model
{
    use SoftDeletes;
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
