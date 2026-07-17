<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'starting_price',
        'status'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
