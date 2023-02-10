<?php

namespace App\Support;

class DataResponse
{

    private array $data;
    private string $dataLabel;
    private array $error;

    public function __construct(string $dataLabel = "data")
    {
        $this->data = [];
        $this->error = [];
        $this->dataLabel = $dataLabel;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setError(int $code, string $message): void
    {
        $this->error = [
            "code" => $code,
            "message" => $message
        ];
    }

    public function getError(): array
    {
        return $this->error;
    }

    public function toJson(): string
    {
        $fullData = [
            $this->dataLabel => $this->data,
            "error" => $this->error
        ];

        return json_encode($fullData);
    }

    public function isEmpty(): bool
    {
        return empty($this->data); 
    }

}