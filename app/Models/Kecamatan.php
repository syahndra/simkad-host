<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kecamatan extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'idKec';
    protected $table = "kecamatan";
    protected $fillable = ['namaKec'];
    public $timestamps = false;

    public function desa()
    {
        return $this->hasMany(Desa::class, 'idKec');
    }

    public function operatorKec()
    {
        return $this->hasMany(OperatorKec::class, 'idKec');
    }
}
