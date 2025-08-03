<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AjuanDafduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ajuandafduk';
    protected $primaryKey = 'idDafduk';

    protected $fillable = [
        'idOpdes',
        'idLayanan',
        'tanggalAjuan',
        'noKK',
        'nik',
        'nama',
        'keterangan',
        'statAjuan',
        'linkBerkas',
        'token',
        'rt',
        'rw',
        'email'
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'idLayanan');
    }

    public function operatorDesa()
    {
        return $this->belongsTo(OperatorDesa::class, 'idOpdes');
    }

    public function respon()
    {
        return $this->hasOne(Respon::class, 'idAjuan')->where('jenis', 'dafduk');
    }

    public function finalDokumen()
    {
        return $this->hasOne(FinalDokumen::class, 'idAjuan')->where('jenis', 'dafduk');
    }
}
