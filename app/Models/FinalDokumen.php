<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalDokumen extends Model
{
    use HasFactory;
    protected $table = 'finaldokumen';
    protected $primaryKey = 'idFinDok';

    protected $fillable = [
        'idAjuan',
        'jenis',
        'filename',
        'filePath',
    ];

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
