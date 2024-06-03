<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'obat_id',
        'satuan_id',
        'jumlah',
        'harga',
        'diskon_id',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function diskon()
    {
        return $this->belongsTo(Diskon::class);
    }
}
