<?php

declare(strict_types=1);

namespace App\Controllers\API;

use App\Models\UserTransactionsModel;
use Helper;
use Exception;

class UserTransactionApiController
{
    public const USER_NOT_FOUND = 'Usuário não encontrado.';
    /**
     * @var UserTransactionsModel|mixed
     */
    private UserTransactionsModel $transaction;

    /**
     * UserTransactionApiController constructor.
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct()
    {
        $this->transaction = Helper::getContainer('UserTransactionsModel');
    }

    /**
     * Withdraw transaction {acc_number and amount} needed on the body.
     */
    public function withdraw() : void
    {
        $request_content = json_decode(file_get_contents('php://input'));

        $withdrawData = [
            'acc_number' => $request_content->acc_number,
            'amount' => $request_content->amount
        ];
        try {
            $balance = (Helper::apiRequest(
                '/users/getBalance',
                ['acc_number' => $withdrawData['acc_number']],
                true
            ))->balance;
            $newBalance = $balance - $withdrawData['amount'];

            if ($withdrawData['amount'] < 0) {
                throw new Exception($this->transaction::TRANS_WITHDRAW_INV_AMT);
            }

            if ($newBalance < 0) {
                throw new Exception($this->transaction::TRANS_INSUFFICIENT_BAL);
            }

            $this->setTransInfo(
                $this->formatBalance((float)$newBalance),
                $withdrawData['amount'],
                'withdraw',
                $withdrawData['acc_number'],
                $withdrawData['acc_number']
            );

            $transData = $this->getTransInfo();

            $this->transaction::insertData($transData);

            Helper::apiRequest('/users/updateBalance', [
                'acc_number' => $withdrawData['acc_number'],
                'balance' => $this->formatBalance((float)$newBalance)
            ], false);

            $message = $this->transaction::TRANS_WITHDRAW_OK;

            Helper::apiResponse(
                $message,
                'transaction_data',
                $transData
            );
        } catch (Exception $e) {
            $message = $e->getMessage();
            http_response_code(400);
            Helper::apiResponse(
                $message
            );
        }
    }

    /**
     * Deposit transaction {acc_number and amount} needed on the body.
     */
    public function deposit() : void
    {
        $request_content = json_decode(file_get_contents('php://input'));

        $depositData = [
            'acc_number' => $request_content->acc_number,
            'amount' => $request_content->amount
        ];
        try {
            $balance = (Helper::apiRequest(
                '/users/getBalance',
                ['acc_number' => $depositData['acc_number']],
                true
            ))->balance;
            $newBalance = $balance + $depositData['amount'];

            if ($depositData['amount'] < 0) {
                throw new Exception($this->transaction::TRANS_DEPOSIT_INV_AMT);
            }

            $this->setTransInfo(
                $this->formatBalance((float)$newBalance),
                $depositData['amount'],
                'deposit',
                $depositData['acc_number'],
                $depositData['acc_number']
            );

            $transData = $this->getTransInfo();

            Helper::apiRequest('/users/updateBalance', [
                'acc_number' => $depositData['acc_number'],
                'balance' => $this->formatBalance((float)$newBalance)
            ], false);

            $this->transaction::insertData($transData);

            $message = $this->transaction::TRANS_DEPOSIT_OK;

            Helper::apiResponse(
                $message,
                'transaction_data',
                $transData
            );
        } catch (Exception $e) {
            $message = $e->getMessage();
            http_response_code(400);
            Helper::apiResponse(
                $message
            );
        }
    }

    /**
     * Transfer transaction {acc_number, to_acc, amount} needed on the body
     */
    public function transfer() : void
    {
        $request_content = json_decode(file_get_contents('php://input'));
        $transferenceData = [
            'acc_number' => $request_content->acc_number,
            'to_acc' => $request_content->to_acc,
            'amount' => $request_content->amount
        ];

        try {
            if ($transferenceData['amount'] < 0) {
                throw new Exception($this->transaction::TRANS_TRANSF_INV_AMT);
            }

            if ($transferenceData['acc_number'] === $transferenceData['to_acc']) {
                throw new Exception($this->transaction::TRANS_TRANSF_INV);
            }

            $balanceFrom = (Helper::apiRequest(
                '/users/getBalance',
                ['acc_number' => $transferenceData['acc_number']],
                true
            ));

            $balanceTo = (Helper::apiRequest(
                '/users/getBalance',
                ['acc_number' => $transferenceData['to_acc']],
                true
            ));

            if (
                $balanceFrom->message == self::USER_NOT_FOUND ||
                $balanceTo->message == self::USER_NOT_FOUND
            ) {
                throw new Exception(self::USER_NOT_FOUND);
            }

            $balanceTo = $balanceTo->balance;
            $balanceFrom = $balanceFrom->balance;

            $newBalanceFrom = $balanceFrom - $transferenceData['amount'];

            if ($newBalanceFrom < 0) {
                throw new Exception($this->transaction::TRANS_INSUFFICIENT_BAL);
            }

            $newBalanceTo = $balanceTo + $transferenceData['amount'];

            $this->setTransInfo(
                $newBalanceFrom,
                $transferenceData['amount'],
                'transference',
                $transferenceData['acc_number'],
                $transferenceData['to_acc']
            );

            $transData = $this->getTransInfo();

            $this->transaction::insertData($transData);

            Helper::apiRequest('/users/updateBalance', [
                'acc_number' => $transferenceData['acc_number'],
                'balance' => $this->formatBalance((float)$newBalanceFrom)
            ], false);

            $this->transaction::insertData($transData);

            Helper::apiRequest('/users/updateBalance', [
                'acc_number' => $transferenceData['to_acc'],
                'balance' => $this->formatBalance((float)$newBalanceTo)
            ], false);

            $message = $this->transaction::TRANS_TRANSF_OK;
            $responseData = [
                'sender_acc' => [
                    'acc_number' => $transferenceData['acc_number'],
                    'new_balance' => $newBalanceFrom
                ],
                'recipient_acc' => [
                    'acc_number' => $transferenceData['to_acc'],
                    'new_balance' => $newBalanceTo
                ]
            ];

            Helper::apiResponse(
                $message,
                'transaction_data',
                $responseData
            );
        } catch (Exception $e) {
            http_response_code(400);
            Helper::apiResponse(
                $e->getMessage()
            );
        }
    }

    /**
     * Sets transference data
     *
     * @param $balance
     * @param $amount
     * @param $type
     * @param $fromAcc
     * @param $toAcc
     */
    public function setTransInfo($balance, $amount, $type, $fromAcc, $toAcc) : void
    {
        $this->transaction->setBalance($balance);
        $this->transaction->setAmount((float)$amount);
        $this->transaction->setType($type);
        $this->transaction->setCreatedAt(date('Y-m-d H:i:s'));
        $this->transaction->setFromAcc($fromAcc);
        $this->transaction->setToAcc($toAcc);
        $this->transaction->setAccNumber($fromAcc);
    }

    /**
     * Returns Transference information
     *
     * @return array
     */
    public function getTransInfo() : array
    {
        return [
            'balance' => $this->transaction->getBalance(),
            'amount' => $this->transaction->getAmount(),
            'type' => $this->transaction->getType(),
            'created_at' => $this->transaction->getCreatedAt(),
            'from_acc' => $this->transaction->getFromAcc(),
            'to_acc' => $this->transaction->getToAcc(),
            'acc_number' => $this->transaction->getAccNumber()
        ];
    }

    /**
     * Formats to two decimal points
     *
     * @param float $balance
     * @return float
     */
    public function formatBalance(float $balance) : float
    {
        return (float)number_format(
            $balance,
            2,
            '.',
            '');
    }
}