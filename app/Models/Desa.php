<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $table = 'desa';
    protected $primaryKey = 'idDesa';
    public $timestamps = false;

    protected $fillable = ['namaDesa', 'idKec'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'idKec', 'idKec');
    }
}
