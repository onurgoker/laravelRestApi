<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'renewed_at', 'expired_at'];
    public $timestamps = false;

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
