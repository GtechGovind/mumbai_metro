<?php

namespace App\Models\IssueToken;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueTokenDataModel extends Model
{
    use HasFactory;

    public $activationTime;
    public $destination;
    public $email;
    public $fare;
    public $mobile;
    public $name;
    public $operationTypeId;
    public $operatorId;
    public $operatorTransactionId;
    public $qrType;
    public $source;
    public $supportType;
    public $tokenType;
    public $trips;

}
