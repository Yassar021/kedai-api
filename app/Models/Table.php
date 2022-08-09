<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'status'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
