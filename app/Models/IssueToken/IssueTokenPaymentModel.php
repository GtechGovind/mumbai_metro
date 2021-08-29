<?php

namespace App\Models\IssueToken;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueTokenPaymentModel extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
    public $pass_price;
    /**
     * @var mixed
     */
    public $pgId;
}
