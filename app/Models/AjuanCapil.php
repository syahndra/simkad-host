<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjuanCapil extends Model
{
    use HasFactory;

    protected $table = 'ajuancapil';
    protected $primaryKey = 'idCapil';

    protected $fillable = [
        'idOpdes',
        'idLayanan',
        'tanggalAjuan',
        'noAkta',
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
        return $this->hasOne(Respon::class, 'idAjuan')->where('jenis', 'capil');
    }

    public function FinalDokumen()
    {
        return $this->hasOne(FinalDokumen::class, 'idAjuan')->where('jenis', 'capil');
    }
}
