<?php

namespace App\Models\ApiModels;

class Trips
{

    private $qrCodeData;
    private $expiryTime;
    private $entryScanTime;
    private $issueTime;
    private $tokenStatus;
    private $qrCodeId;
    private $transactionId;
    private $type;
    private $extendedDestination;
    private $scans;

    /**
     * @param $qrCodeData
     * @param $expiryTime
     * @param $entryScanTime
     * @param $issueTime
     * @param $tokenStatus
     * @param $qrCodeId
     * @param $transactionId
     * @param $type
     * @param $extendedDestination
     * @param $scans
     */
    public function __construct($qrCodeData, $expiryTime, $entryScanTime, $issueTime, $tokenStatus, $qrCodeId, $transactionId, $type, $extendedDestination, $scans)
    {
        $this->qrCodeData = $qrCodeData;
        $this->expiryTime = $expiryTime;
        $this->entryScanTime = $entryScanTime;
        $this->issueTime = $issueTime;
        $this->tokenStatus = $tokenStatus;
        $this->qrCodeId = $qrCodeId;
        $this->transactionId = $transactionId;
        $this->type = $type;
        $this->extendedDestination = $extendedDestination;
        $this->scans = $scans;
    }

    /**
     * @return mixed
     */
    public function getQrCodeData()
    {
        return $this->qrCodeData;
    }

    /**
     * @param mixed $qrCodeData
     */
    public function setQrCodeData($qrCodeData): void
    {
        $this->qrCodeData = $qrCodeData;
    }

    /**
     * @return mixed
     */
    public function getExpiryTime()
    {
        return $this->expiryTime;
    }

    /**
     * @param mixed $expiryTime
     */
    public function setExpiryTime($expiryTime): void
    {
        $this->expiryTime = $expiryTime;
    }

    /**
     * @return mixed
     */
    public function getEntryScanTime()
    {
        return $this->entryScanTime;
    }

    /**
     * @param mixed $entryScanTime
     */
    public function setEntryScanTime($entryScanTime): void
    {
        $this->entryScanTime = $entryScanTime;
    }

    /**
     * @return mixed
     */
    public function getIssueTime()
    {
        return $this->issueTime;
    }

    /**
     * @param mixed $issueTime
     */
    public function setIssueTime($issueTime): void
    {
        $this->issueTime = $issueTime;
    }

    /**
     * @return mixed
     */
    public function getTokenStatus()
    {
        return $this->tokenStatus;
    }

    /**
     * @param mixed $tokenStatus
     */
    public function setTokenStatus($tokenStatus): void
    {
        $this->tokenStatus = $tokenStatus;
    }

    /**
     * @return mixed
     */
    public function getQrCodeId()
    {
        return $this->qrCodeId;
    }

    /**
     * @param mixed $qrCodeId
     */
    public function setQrCodeId($qrCodeId): void
    {
        $this->qrCodeId = $qrCodeId;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getExtendedDestination()
    {
        return $this->extendedDestination;
    }

    /**
     * @param mixed $extendedDestination
     */
    public function setExtendedDestination($extendedDestination): void
    {
        $this->extendedDestination = $extendedDestination;
    }

    /**
     * @return mixed
     */
    public function getScans()
    {
        return $this->scans;
    }

    /**
     * @param mixed $scans
     */
    public function setScans($scans): void
    {
        $this->scans = $scans;
    }

}
