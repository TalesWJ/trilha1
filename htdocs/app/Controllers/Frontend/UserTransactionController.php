<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Models\UserTransactionsModel;
use Helper;
use Jenssegers\Blade\Blade;

class UserTransactionController
{

    /**
     * @var UserTransactionsModel|mixed
     */
    private UserTransactionsModel $transaction;
    /**
     * @var Blade
     */
    private Blade $view;

    /**
     * UserTransactionController constructor.
     */
    public function __construct()
    {
        $this->transaction = Helper::getContainer('UserTransactionsModel');
        $this->view = Helper::getContainer('ViewManager')->getViewObject();
    }

    /**
     * Transfer a certain amount between 2 accounts
     */
    public function transfer()
    {
        $data = [
            'acc_number' => $_SESSION['acc_number'],
            'to_acc' => filter_input(INPUT_POST, 'to_acc', FILTER_SANITIZE_STRING),
            'amount' => filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING)
        ];

        $transfer = Helper::apiRequest('/users/transactions/transfer', $data);

        if ($transfer->message === $this->transaction::TRANS_TRANSF_OK) {
            $this->renderDashboard($transfer->message, (string)$transfer->transaction_data->sender_acc->new_balance);
        } else {
            $this->renderDashboard($transfer->message);
        }
    }

    /**
     * Renders the user dashboard
     *
     * @param string|null $message
     * @param string|null $newBalance
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function renderDashboard(?string $message = null,?string $newBalance = null) : void
    {
        $balance = Helper::userBalance();
        $name = Helper::userName();
        if (!empty($newBalance) && !empty($message)) {
            echo $this->view->render(
                'pages/dashboard', [
                    'balance' => $newBalance,
                    'message' => $message,
                    'name' => $name
                ]);
        } elseif (!empty($message) && empty($newBalance)) {
            echo $this->view->render(
                'pages/dashboard', [
                    'balance' => $balance,
                    'message' => $message,
                    'name' => $name
                ]);
        } else {
            echo $this->view->render(
                'pages/dashboard', [
                    'balance' => $balance,
                    'name' => $name
                ]
            );
        }
    }

    /**
     * Deposit the amount of money inserted
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function deposit()
    {
        $data = [
            'acc_number' => $_SESSION['acc_number'],
            'amount' => filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING)
        ];

        $deposit = Helper::apiRequest('/users/transactions/deposit', $data);
        if ($deposit->message == $this->transaction::TRANS_DEPOSIT_OK) {
            $this->renderDashboard($deposit->message, (string)$deposit->transaction_data->balance);
        } else {
            $this->renderDashboard($deposit->message);
        }
    }

    /**
     * Withdraw amount set on field
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function withdraw()
    {
        $data = [
            'acc_number' => $_SESSION['acc_number'],
            'amount' => filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING)
        ];

        $withdraw = Helper::apiRequest('/users/transactions/withdraw', $data);
        if ($withdraw->message == $this->transaction::TRANS_WITHDRAW_OK) {
            $this->renderDashboard($withdraw->message, (string)$withdraw->transaction_data->balance);
        } else {
            $this->renderDashboard($withdraw->message);
        }
    }

    /**
     * Shows all transactions made by the user
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function extract()
    {
        $extract = $this->transaction::selectDataByColumn('acc_number', $_SESSION['acc_number']);
        $balance = Helper::userBalance();
        if (!empty($extract)) {
            echo $this->view->render(
                'pages/extract', [
                    'extract' => $extract,
                    'balance' => $balance
                ]
            );
        } else {
            $this->renderDashboard('Nenhuma transação encontrada');
        }
    }
}