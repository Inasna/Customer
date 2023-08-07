<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'reference_id',
    ];

    public function custref()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function chainReference()
    {
        return $this->belongsTo(User::class, 'reference_id');
    }
}
