<?php

namespace App\Models\IssueToken;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueTokenModel extends Model
{
    use HasFactory;

    /**
     * @var IssueTokenDataModel|mixed
     */
    public $data;
    /**
     * @var IssueTokenPaymentModel|mixed
     */
    public $payment;
}
