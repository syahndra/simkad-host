<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $primaryKey = 'idKec';
    protected $table = "kecamatan";
    protected $fillable = ['namaKec'];
    public $timestamps = false;
}
