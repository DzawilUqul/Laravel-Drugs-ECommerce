<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisObat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jenis_obat',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
