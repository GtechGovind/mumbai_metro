<?php

namespace App\Models\ApiModels;

class Data
{
    // REQUEST
    private $activationTime;
    private $destination;
    private $email;
    private $fare;
    private $mobile;
    private $name;
    private $operationTypeId;
    private $operatorId;
    private $operatorTransactionId;
    private $qrType;
    private $source;
    private $supportType;
    private $tokenType;
    private $trips;

    /**
     * @param $activationTime
     * @param $destination
     * @param $email
     * @param $fare
     * @param $mobile
     * @param $name
     * @param $operationTypeId
     * @param $operatorId
     * @param $operatorTransactionId
     * @param $qrType
     * @param $source
     * @param $supportType
     * @param $tokenType
     * @param $trips
     */
    public function __construct($activationTime, $destination, $email, $fare, $mobile, $name, $operationTypeId, $operatorId, $operatorTransactionId, $qrType, $source, $supportType, $tokenType, $trips)
    {
        $this->activationTime = $activationTime;
        $this->destination = $destination;
        $this->email = $email;
        $this->fare = $fare;
        $this->mobile = $mobile;
        $this->name = $name;
        $this->operationTypeId = $operationTypeId;
        $this->operatorId = $operatorId;
        $this->operatorTransactionId = $operatorTransactionId;
        $this->qrType = $qrType;
        $this->source = $source;
        $this->supportType = $supportType;
        $this->tokenType = $tokenType;
        $this->trips = $trips;
    }

    /**
     * @return mixed
     */
    public function getActivationTime()
    {
        return $this->activationTime;
    }

    /**
     * @param mixed $activationTime
     */
    public function setActivationTime($activationTime): void
    {
        $this->activationTime = $activationTime;
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param mixed $destination
     */
    public function setDestination($destination): void
    {
        $this->destination = $destination;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getFare()
    {
        return $this->fare;
    }

    /**
     * @param mixed $fare
     */
    public function setFare($fare): void
    {
        $this->fare = $fare;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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
    public function getOperatorTransactionId()
    {
        return $this->operatorTransactionId;
    }

    /**
     * @param mixed $operatorTransactionId
     */
    public function setOperatorTransactionId($operatorTransactionId): void
    {
        $this->operatorTransactionId = $operatorTransactionId;
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
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source): void
    {
        $this->source = $source;
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
    public function getTrips()
    {
        return $this->trips;
    }

    /**
     * @param mixed $trips
     */
    public function setTrips($trips): void
    {
        $this->trips = $trips;
    }



}
