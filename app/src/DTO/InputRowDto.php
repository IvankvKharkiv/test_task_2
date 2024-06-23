<?php

namespace App\DTO;

class InputRowDto
{
    private int $bin;
    private float $amount;
    private string $currency;

    public function getBin(): int
    {
        return $this->bin;
    }

    public function setBin(int $bin): void
    {
        $this->bin = $bin;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

}