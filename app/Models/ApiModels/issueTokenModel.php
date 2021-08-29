<?php

namespace App\Models\ApiModels;

class issueTokenModel
{
    // REQUEST
    private Data $data;
    private Payment $payment;

    /**
     * @param Data $data
     * @param Payment $payment
     */
    public function __construct(Data $data, Payment $payment)
    {
        $this->data = $data;
        $this->payment = $payment;
    }

    /**
     * @return Data
     */
    public function getData(): Data
    {
        return $this->data;
    }

    /**
     * @param Data $data
     */
    public function setData(Data $data): void
    {
        $this->data = $data;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment): void
    {
        $this->payment = $payment;
    }

}
