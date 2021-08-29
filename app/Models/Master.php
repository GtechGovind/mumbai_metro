<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
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
    public $master_acc_id;
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
    public $ticket_count;
    /**
     * @var mixed
     */
    public $total_fare;
    /**
     * @var mixed
     */
    public $travel_date;
    /**
     * @var mixed
     */
    public $master_expiry;
    /**
     * @var mixed
     */
    public $grace_expiry;
    /**
     * @var mixed
     */
    public $record_date;

}
