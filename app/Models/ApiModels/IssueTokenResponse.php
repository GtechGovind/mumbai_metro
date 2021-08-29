<?php

namespace App\Models\ApiModels;

class IssueTokenResponse
{
    private string $status;
    private int $code;
    private string $message;
    private DataResponse $data;

    /**
     * @param string $status
     * @param int $code
     * @param string $message
     * @param DataResponse $data
     */
    public function __construct(string $status, int $code, string $message, DataResponse $data)
    {
        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return DataResponse
     */
    public function getData(): DataResponse
    {
        return $this->data;
    }

    /**
     * @param DataResponse $data
     */
    public function setData(DataResponse $data): void
    {
        $this->data = $data;
    }

}
