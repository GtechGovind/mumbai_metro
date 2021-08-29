<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'master_qr_code',
        'master_acc_id',
        'phone_number',
        'source',
        'destination',
        'ticket_type',
        'ticket_count',
        'total_fare',
        'travel_date',
        'master_expiry',
        'grace_expiry',
        'record_date',
    ];

}
