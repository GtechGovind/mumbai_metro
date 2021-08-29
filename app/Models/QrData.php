<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrData extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'master_qr_code',
        'slave_qr_code',
        'slave_acc_id',
        'phone_number',
        'source',
        'destination',
        'ticket_type',
        'qr_direction',
        'qr_code_data',
        'qr_status',
        'record_date',
        'slave_expiry_date',
    ];

}
