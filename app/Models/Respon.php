<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Respon extends Model
{
    use HasFactory;

    protected $table = 'respon';
    protected $primaryKey = 'idRespon';
    public $timestamps = true;

    protected $fillable = [
        'idUser',
        'idAjuan',
        'jenis',
        'respon',
    ];

    // Relasi ke user (yang memberi respon)
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    // Optional: method dinamis untuk ambil ajuan berdasarkan jenis
    public function ajuan()
    {
        if ($this->jenis === 'capil') {
            return AjuanCapil::find($this->idAjuan);
        } elseif ($this->jenis === 'dafduk') {
            return AjuanDafduk::find($this->idAjuan);
        }

        return null;
    }
}
