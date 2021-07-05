<?php

declare(strict_types=1);

namespace App\Controllers\API;

use App\Models\UserTransactionsModel;
use Helper;
use \Exception;

class UserTransactionApiController
{
    private UserTransactionsModel $transaction;

    public function __construct()
    {
        $this->transaction = Helper::getContainer('UserTransactionsModel');
    }


    public function withdraw()
    {
        $request_content = json_decode(file_get_contents('php://input'));

        $withdrawData = [
            'acc_number' => $request_content->acc_number,
            'amount' => $request_content->amount
        ];
        try {
            $balance = (Helper::apiRequest('/users/getBalance', ['acc_number' => $withdrawData['acc_number']]))->balance;
            $newBalance = $balance - $withdrawData['amount'];

            if ($withdrawData['amount'] < 0) {
                throw new Exception($this->transaction::TRANS_WITHDRAW_INV_AMT);
            }

            if ($newBalance < 0) {
                throw new Exception($this->transaction::TRANS_INSUFFICIENT_BAL);
            }

            $this->transaction->setBalance($newBalance);
            $this->transaction->setAmount((float)$withdrawData['amount']);
            $this->transaction->setType('withdraw');
            $this->transaction->setCreatedAt(date('Y-m-d H:i:s'));
            $this->transaction->setFromAcc($withdrawData['acc_number']);
            $this->transaction->setToAcc($withdrawData['acc_number']);
            $this->transaction->setAccNumber($withdrawData['acc_number']);


            $transData = [
                'balance' => $this->transaction->getBalance(),
                'amount' => $this->transaction->getAmount(),
                'type' => $this->transaction->getType(),
                'created_at' => $this->transaction->getCreatedAt(),
                'from_acc' => $this->transaction->getFromAcc(),
                'to_acc' => $this->transaction->getToAcc(),
                'acc_number' => $this->transaction->getAccNumber()
            ];

            $this->transaction::insertData($transData);

            Helper::apiRequest('/users/updateBalance', [
                'acc_number' => $withdrawData['acc_number'],
                'balance' => $newBalance
            ], false);

            $message = $this->transaction::TRANS_WITHDRAW_OK;

            Helper::apiResponse(
                $message,
                'transaction_data',
                $transData
            );
        } catch (\Exception $e) {
            $message = $e->getMessage();
            http_response_code(400);
            Helper::apiResponse(
                $message
            );
        }
    }

    public function deposit()
    {
        $request_content = json_decode(file_get_contents('php://input'));

        $depositData = [
            'acc_number' => $request_content->acc_number,
            'amount' => $request_content->amount
        ];
        try {
            $balance = (Helper::apiRequest('/users/getBalance', ['acc_number' => $depositData['acc_number']]))->balance;
            $newBalance = $balance + $depositData['amount'];

            if ($depositData['amount'] < 0) {
                throw new Exception($this->transaction::TRANS_DEPOSIT_INV_AMT);
            }

            $this->transaction->setBalance($newBalance);
            $this->transaction->setAmount((float)$depositData['amount']);
            $this->transaction->setType('deposit');
            $this->transaction->setCreatedAt(date('Y-m-d H:i:s'));
            $this->transaction->setFromAcc($depositData['acc_number']);
            $this->transaction->setToAcc($depositData['acc_number']);
            $this->transaction->setAccNumber($depositData['acc_number']);

            $transData = [
                'balance' => $this->transaction->getBalance(),
                'amount' => $this->transaction->getAmount(),
                'type' => $this->transaction->getType(),
                'created_at' => $this->transaction->getCreatedAt(),
                'from_acc' => $this->transaction->getFromAcc(),
                'to_acc' => $this->transaction->getToAcc(),
                'acc_number' => $this->transaction->getAccNumber()
            ];

            $this->transaction::insertData($transData);

            Helper::apiRequest('/users/updateBalance', [
                'acc_number' => $depositData['acc_number'],
                'balance' => $newBalance
            ], false);

            $message = $this->transaction::TRANS_DEPOSIT_OK;

            Helper::apiResponse(
                $message,
                'transaction_data',
                $transData
            );
        } catch (\Exception $e) {
            $message = $e->getMessage();
            http_response_code(400);
            Helper::apiResponse(
                $message
            );
        }
    }
}