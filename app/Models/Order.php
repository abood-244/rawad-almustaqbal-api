<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_name',
        'phone',
        'location',
        'service_id',
        'description',
        'status'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
