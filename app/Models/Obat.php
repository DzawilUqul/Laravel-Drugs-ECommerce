<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_obat',
        'harga',
        'stok',
        'jenis_obat_id',
        'diskon_id',
        'satuan_id',
    ];

    public function jenisObat()
    {
        return $this->hasOne(JenisObat::class);
    }

    public function diskon()
    {
        return $this->hasOne(Diskon::class);
    }

    public function satuan()
    {
        return $this->hasOne(Satuan::class);
    }

    public function cartItems(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cart_items', 'obat_id', 'user_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
