<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['fee_id', 'amount', 'transaction_id', 'date', 'method'];

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
}
