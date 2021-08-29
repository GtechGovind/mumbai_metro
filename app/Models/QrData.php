<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrData extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
    public $order_no;
    /**
     * @var mixed
     */
    public $master_qr_code;
    /**
     * @var mixed
     */
    public $slave_qr_code;
    /**
     * @var mixed
     */
    public $slave_acc_id;
    /**
     * @var mixed
     */
    public $phone_number;
    /**
     * @var mixed
     */
    public $source;
    /**
     * @var mixed
     */
    public $destination;
    /**
     * @var mixed
     */
    public $ticket_type;
    /**
     * @var mixed
     */
    public $qr_direction;
    /**
     * @var mixed
     */
    public $qr_code_data;
    /**
     * @var mixed
     */
    public $qr_status;
    /**
     * @var mixed
     */
    public $record_date;
    /**
     * @var mixed
     */
    public $slave_expiry_date;
}
