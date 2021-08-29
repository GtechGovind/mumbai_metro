<?php

namespace App\Models\ApiModels;

class DataResponse
{
    private $masterTxnId;
    private $transactionId;
    private $operatorId;
    private $amount;
    private $registrationFee;
    private $balance;
    private $balanceTrip;
    private $supportType;
    private $qrType;
    private $tokenType;
    private $operationTypeId;
    private $timestamp;
    private $travelDate;
    private $masterExpiry;
    private $graceExpiry;
    private Trips $trips;

    /**
     * @param $masterTxnId
     * @param $transactionId
     * @param $operatorId
     * @param $amount
     * @param $registrationFee
     * @param $balance
     * @param $balanceTrip
     * @param $supportType
     * @param $qrType
     * @param $tokenType
     * @param $operationTypeId
     * @param $timestamp
     * @param $travelDate
     * @param $masterExpiry
     * @param $graceExpiry
     * @param Trips $trips
     */
    public function __construct($masterTxnId, $transactionId, $operatorId, $amount, $registrationFee, $balance, $balanceTrip, $supportType, $qrType, $tokenType, $operationTypeId, $timestamp, $travelDate, $masterExpiry, $graceExpiry, Trips $trips)
    {
        $this->masterTxnId = $masterTxnId;
        $this->transactionId = $transactionId;
        $this->operatorId = $operatorId;
        $this->amount = $amount;
        $this->registrationFee = $registrationFee;
        $this->balance = $balance;
        $this->balanceTrip = $balanceTrip;
        $this->supportType = $supportType;
        $this->qrType = $qrType;
        $this->tokenType = $tokenType;
        $this->operationTypeId = $operationTypeId;
        $this->timestamp = $timestamp;
        $this->travelDate = $travelDate;
        $this->masterExpiry = $masterExpiry;
        $this->graceExpiry = $graceExpiry;
        $this->trips = $trips;
    }

    /**
     * @return mixed
     */
    public function getMasterTxnId()
    {
        return $this->masterTxnId;
    }

    /**
     * @param mixed $masterTxnId
     */
    public function setMasterTxnId($masterTxnId): void
    {
        $this->masterTxnId = $masterTxnId;
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param mixed $transactionId
     */
    public function setTransactionId($transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return mixed
     */
    public function getOperatorId()
    {
        return $this->operatorId;
    }

    /**
     * @param mixed $operatorId
     */
    public function setOperatorId($operatorId): void
    {
        $this->operatorId = $operatorId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getRegistrationFee()
    {
        return $this->registrationFee;
    }

    /**
     * @param mixed $registrationFee
     */
    public function setRegistrationFee($registrationFee): void
    {
        $this->registrationFee = $registrationFee;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     */
    public function setBalance($balance): void
    {
        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getBalanceTrip()
    {
        return $this->balanceTrip;
    }

    /**
     * @param mixed $balanceTrip
     */
    public function setBalanceTrip($balanceTrip): void
    {
        $this->balanceTrip = $balanceTrip;
    }

    /**
     * @return mixed
     */
    public function getSupportType()
    {
        return $this->supportType;
    }

    /**
     * @param mixed $supportType
     */
    public function setSupportType($supportType): void
    {
        $this->supportType = $supportType;
    }

    /**
     * @return mixed
     */
    public function getQrType()
    {
        return $this->qrType;
    }

    /**
     * @param mixed $qrType
     */
    public function setQrType($qrType): void
    {
        $this->qrType = $qrType;
    }

    /**
     * @return mixed
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param mixed $tokenType
     */
    public function setTokenType($tokenType): void
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return mixed
     */
    public function getOperationTypeId()
    {
        return $this->operationTypeId;
    }

    /**
     * @param mixed $operationTypeId
     */
    public function setOperationTypeId($operationTypeId): void
    {
        $this->operationTypeId = $operationTypeId;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getTravelDate()
    {
        return $this->travelDate;
    }

    /**
     * @param mixed $travelDate
     */
    public function setTravelDate($travelDate): void
    {
        $this->travelDate = $travelDate;
    }

    /**
     * @return mixed
     */
    public function getMasterExpiry()
    {
        return $this->masterExpiry;
    }

    /**
     * @param mixed $masterExpiry
     */
    public function setMasterExpiry($masterExpiry): void
    {
        $this->masterExpiry = $masterExpiry;
    }

    /**
     * @return mixed
     */
    public function getGraceExpiry()
    {
        return $this->graceExpiry;
    }

    /**
     * @param mixed $graceExpiry
     */
    public function setGraceExpiry($graceExpiry): void
    {
        $this->graceExpiry = $graceExpiry;
    }

    /**
     * @return Trips
     */
    public function getTrips(): Trips
    {
        return $this->trips;
    }

    /**
     * @param Trips $trips
     */
    public function setTrips(Trips $trips): void
    {
        $this->trips = $trips;
    }

}
