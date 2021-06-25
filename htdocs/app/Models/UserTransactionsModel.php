<?php

namespace App\Models;

use App\Models\Interfaces\UserTransactionModelInterface;
use App\Models\CoreModel;

class UserTransactionsModel extends CoreModel implements UserTransactionModelInterface
{
    private int $transactionId;
    private float $balance;
    private float $amount;
    private string $type;
    private string $createdAt;
    private string $fromAcc;
    private string $toAcc;
    private string $accNumber;

    public function __construct()
    {
        parent::__construct('user_transactions',
        [
            "id_transaction",
            "balance",
            "amount",
            "type",
            "created_at",
            "from_acc",
            "to_acc",
            "acc_number"
        ]);
    }

    /**
     * Sets Transaction ID
     * @param int $id
     * @return void
     */
    public function setTransactionId(int $id) : void
    {
        $this->transactionId = $id;
    }

    /**
     * Gets Transaction ID
     * @return int
     */
    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    /**
     * Sets account balance
     * @param float $balance
     * @return void
     */
    public function setBalance(float $balance) : void
    {
        $this->balance = $balance;
    }

    /**
     * Gets account balance
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Sets transaction amount
     * @param float $amount
     * @return void
     */
    public function setAmount(float $amount) : void
    {
        $this->amount = $amount;
    }

    /**
     * Gets transaction amount
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Sets transaction type
     * @param string $type
     * @return void
     */
    public function setType(string $type) : void
    {
        $this->type = $type;
    }

    /**
     * Gets transaction type
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets transaction's createdAt
     * @param string $createdAt
     * @return void
     */
    public function setCreatedAt(string $createdAt) : void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Gets transaction's createAt
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * Sets transactions fromAcc
     * @param string $fromAcc
     * @return void
     */
    public function setFromAcc(string $fromAcc) : void
    {
        $this->fromAcc = $fromAcc;
    }

    /**
     * Gets transactions fromAcc
     * @return string
     */
    public function getFromAcc(): string
    {
        return $this->fromAcc;
    }

    /**
     * Sets transactions toAcc
     * @param string $toAcc
     * @return void
     */
    public function setToAcc(string $toAcc) : void
    {
        $this->toAcc = $toAcc;
    }

    /**
     * Gets transactions fromAcc
     * @return string
     */
    public function getToAcc(): string
    {
        return $this->toAcc;
    }

    /**
     * Sets account number
     * @param string $accNumber
     */
    public function setAccNumber(string $accNumber) : void
    {
        $this->accNumber = $accNumber;
    }

    /**
     * Gets account number
     * @return string
     */
    public function getAccNumber(): string
    {
        return $this->accNumber;
    }
}