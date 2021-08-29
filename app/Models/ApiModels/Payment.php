<?php

namespace App\Models\ApiModels;

class Payment
{
    private $pass_price;
    private $pgId;

    /**
     * @param $pass_price
     * @param $pgId
     */
    public function __construct($pass_price, $pgId)
    {
        $this->pass_price = $pass_price;
        $this->pgId = $pgId;
    }

    /**
     * @return mixed
     */
    public function getPassPrice()
    {
        return $this->pass_price;
    }

    /**
     * @param mixed $pass_price
     */
    public function setPassPrice($pass_price): void
    {
        $this->pass_price = $pass_price;
    }

    /**
     * @return mixed
     */
    public function getPgId()
    {
        return $this->pgId;
    }

    /**
     * @param mixed $pgId
     */
    public function setPgId($pgId): void
    {
        $this->pgId = $pgId;
    }

}
