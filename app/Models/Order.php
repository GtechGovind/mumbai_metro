<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'phone_number',
        'pg_order_id',
        'source_id',
        'destination_id',
        'ticket_count',
        'ticket_type',
        'total_fare',
        'pg_id',
        'order_status',
        'order_flag'
    ];

}
