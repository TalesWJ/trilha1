<?php

declare(strict_types=1);

namespace App\Controllers\API;

use App\Models\UserModel;
use App\Models\UserAddressModel;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use \Helper;


class UserApiController
{
    public const CHAR_LIM = 'Limite de caractéres atingido no campo: ';

    /**
     * @var UserModel
     */
    private UserModel $user;

    /**
     * @var UserAddressModel
     */
    private UserAddressModel $address;

    /**
     * UserApiController constructor.
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct()
    {
        $this->user = Helper::getContainer('UserModel');
    }

    /**
     * Searches All Users
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function searchUsers() : void
    {
        $allUsers = $this->user::selectAllData();
        $this->address = Helper::getContainer('UserAddressModel');
        $length = 0;
        foreach ($allUsers as $user) {
            $data[$length] = ['user' => $user];
            $length++;
        }
        Helper::apiResponse(
            $this->user::SEARCH_SUCCESS,
            'users',
            $data
        );
    }

    /**
     * @param string $accNumber
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function searchUserByAccNumber(string $accNumber) : void
    {
        $user = $this->user::selectDataByColumn('acc_number', $accNumber);

        if ($user === false) {
            http_response_code(404);
            $message = $this->user::USER_NOT_FOUND . ' (Conta: ' . $accNumber . ')';
        } else {
            $message = $this->user::USER_FOUND . ' (Conta: ' . $accNumber . ')';
        }

        $this->address = Helper::getContainer('UserAddressModel');
        $data = [
            'user' => $user[0]
        ];
        Helper::apiResponse(
            $message,
            'search_result',
            $data
        );
    }

    /**
     * Checks Length of data inserted
     * @param $data
     * @return string
     */
    private function checkLength($data) : string
    {
        $message = '';
        foreach ($data as $column => $value) {
            if (strlen($value) > 255) {
                http_response_code(400);
                $message = self::CHAR_LIM . $column . '.';
            }
        }
        return $message;
    }

    /**
     * Creates user based on post params
     */
    public function createUser() : void
    {
        $request_content = json_decode(file_get_contents('php://input'));
        $allUsers = $this->user::selectAllData();

        try {
            // Checking length of inserted data, max: 255 characters
            $message = $this->checkLength($request_content->user);

            if (!empty($message)) {
                throw new Exception($message);
            }

            $message = $this->checkLength($request_content->address);
            if (!empty($message)) {
                throw new Exception($message);
            }

            $accNumber = md5(openssl_random_pseudo_bytes(4));
            $uniqueAttributes = [
                $accNumber,
                $request_content->user->cpf,
                $request_content->user->rg
            ];

            if (!empty($allUsers)) {
                foreach ($uniqueAttributes as $uniqueAttribute) {
                    $isValid = $this->validateUniqueData($uniqueAttribute, $allUsers);

                    if($isValid !== $this->user::UNIQUE_DATA) {
                        http_response_code(400);
                        throw new Exception($isValid);
                    }
                }
            }

            $pw = password_hash($request_content->user->password, PASSWORD_ARGON2I);

            $this->user->setName($request_content->user->name);
            $this->user->setCpf($request_content->user->cpf);
            $this->user->setRg($request_content->user->rg);
            $this->user->setDob($request_content->user->dob);
            $this->user->setPhone($request_content->user->phone);
            $this->user->setBalance(0.0);
            $this->user->setAccNumber($accNumber);
            $this->user->setAccPw($pw);

            $userData = [
                'name' => $this->user->getName(),
                'cpf' => $this->user->getCpf(),
                'rg' => $this->user->getRg(),
                'dob' => $this->user->getDob(),
                'phone' => $this->user->getPhone(),
                'balance' => $this->user->getBalance(),
                'token' => '',
                'acc_number' => $this->user->getAccNumber(),
                'acc_pw' => $this->user->getAccPw()
            ];

            $this->address = Helper::getContainer('UserAddressModel');
            $this->address->setZipCode($request_content->address->zipcode);
            $this->address->setCountry($request_content->address->country);
            $this->address->setState($request_content->address->state);
            $this->address->setCity($request_content->address->city);
            $this->address->setStreet($request_content->address->street);
            $this->address->setNumber($request_content->address->number);
            $this->address->setComplement($request_content->address->complement);
            $this->address->setAccNumber($accNumber);

            $addressData = [
                'zipcode' => $this->address->getZipCode(),
                'country' => $this->address->getCountry(),
                'state' => $this->address->getState(),
                'city' => $this->address->getCity(),
                'street' => $this->address->getStreet(),
                'number' => $this->address->getNumber(),
                'complement' => $this->address->getComplement(),
                'acc_number' => $this->address->getAccNumber()
            ];

            $this->user::insertData($userData);
            $this->address::insertData($addressData);
            http_response_code(201);
            $message = $this->user::USER_CREATED;
        } catch (Exception $e) {
            Helper::apiResponse($e->getMessage());
        }

        Helper::apiResponse(
            $message,
            'acc_number',
            $this->user->getAccNumber()
        );
    }

    /**
     * Validates if Data inserted is Unique
     *
     * @param $uniqueAttribute
     * @param $allUsers
     * @return string
     */
    private function validateUniqueData(
        $uniqueAttribute,
        $allUsers
    ) : string {
        $message = '';

        foreach ($allUsers as $user) {
            switch ($uniqueAttribute) {
                case $user->acc_number:
                    return $this->user::USER_ACCNUMBER_EXISTS;
                    break;
                case $user->cpf:
                    return $this->user::USER_CPF_EXISTS;
                    break;
                case $user->rg:
                    return $this->user::USER_RG_EXISTS;
                    break;
                default:
                    $message = $this->user::UNIQUE_DATA;
                    break;
            }
        }

        return $message;
    }

    /**
     * Authenticates user login attempt
     * @throws Exception
     */
    public function login()
    {
        $request_content = json_decode(file_get_contents('php://input'));

        $loginData = [
            'acc_number' => $request_content->acc_number,
            'acc_pw' => $request_content->password
        ];

        $user = $this->user::selectDataByColumn('acc_number', $loginData['acc_number']);
        if (!empty($user[0])) {
            $pwAuth = $this->user->pwAuth($loginData['acc_pw'], $user[0]->acc_pw);
            switch ($pwAuth) {
                case $this->user::USER_WRONG_PW:
                    http_response_code(401);
                    $message = $pwAuth;
                    break;
                case $this->user::USER_PW_AUTH_OK:
                    $message = $pwAuth;
                    $this->user->setToken(base64_encode(random_bytes(16)));
                    break;
            }
        } else {
            http_response_code(404);
            $message = $this->user::USER_NOT_FOUND;
        }

        $data = [
            'token' => $this->user->getToken()
        ];

        if (isset($data['token'])) {
            $this->user::updateData($data, 'acc_number', $loginData['acc_number']);
            Helper::apiResponse(
                $message,
                'token',
                $data['token']
            );
        } else {
            Helper::apiResponse(
                $message
            );
        }
    }

    /**
     * Gets account balance from
     */
    public function getBalance()
    {
        $request_content = json_decode(file_get_contents('php://input'));
        $user = $this->user::selectDataByColumn('acc_number', $request_content->acc_number);

        if (isset($user[0])) {
            $balance = $user[0]->balance;
            $message = 'Balanço obtido com sucesso';
            Helper::apiResponse(
                $message,
                'balance',
                $balance
            );
        } else {
            http_response_code(404);
            $message = 'Usuário não encontrado';
            Helper::apiResponse(
                $message
            );
        }
    }

    /**
     * Updates the account's balance
     */
    public function updateBalance()
    {
        $request_content = json_decode(file_get_contents('php://input'));
        $this->user->setBalance($request_content->balance);
        $newBalance = [
            'balance' => $this->user->getBalance()
        ];
        if ($this->user::updateData($newBalance, 'acc_number', $request_content->acc_number)) {
            Helper::apiResponse(
                'Saldo atualizado com sucesso'
            );
        } else {
            http_response_code(404);
            Helper::apiResponse(
                'Conta informada não encontrada/Saldo inserido igual ao atual'
            );
        }
    }
}