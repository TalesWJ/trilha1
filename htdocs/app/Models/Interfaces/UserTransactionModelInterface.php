<?php

namespace App\Models\Interfaces;

interface UserTransactionModelInterface
{
    public function setTransactionId(int $id);

    public function getTransactionId() : int;
    
    public function setBalance(float $balance);

    public function getBalance() : float;

    public function setAmount(float $amount);

    public function getAmount() : float;

    public function setType(string $type);

    public function getType() : string;

    public function setCreatedAt(string $createdAt);

    public function getCreatedAt() : string;

    public function setFromAcc(string $fromAcc);

    public function getFromAcc() : string;

    public function setToAcc(string $toAcc);

    public function getToAcc() : string;

    public function setAccNumber(string $accNumber);

    public function getAccNumber() : string;

}