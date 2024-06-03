<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_diskon',
        'persentase_diskon',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
