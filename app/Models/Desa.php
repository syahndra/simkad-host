<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desa extends Model
{
    use SoftDeletes;
    protected $table = 'desa';
    protected $primaryKey = 'idDesa';
    public $timestamps = false;

    protected $fillable = ['namaDesa', 'idKec'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'idKec');
    }
    public function operatorDesa()
    {
        return $this->hasMany(OperatorDesa::class, 'idDesa');
    }
}
