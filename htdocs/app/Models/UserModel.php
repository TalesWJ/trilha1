<?php

namespace App\Models;

use App\Interfaces\UserModelInterface;

class UserModel extends CoreModel implements UserModelInterface
{
    public const USER_NOT_FOUND = 'Usuário não encontrado.';
    public const USER_FOUND = 'Usuário encontrado com sucesso.';
    public const USER_ACCNUMBER_EXISTS = 'Número de conta informado já cadastrado.';
    public const SEARCH_SUCCESS = 'Sucesso na Busca.';
    public const USER_CPF_EXISTS = 'CPF informado já cadastrado.';
    public const USER_RG_EXISTS = 'RG informado já cadastrado.';
    public const UNIQUE_DATA = 'Dados inseridos OK.';
    public const USER_CREATED = 'Usuário criado com sucesso.';
    public const USER_WRONG_PW = 'Senha incorreta.';
    public const USER_PW_AUTH_OK = 'Senha autenticada com sucesso';


    private int $id;
    private string $name;
    private string $cpf;
    private string $rg;
    private string $dob;
    private string $phone;
    private float $balance;
    private string $token;
    private string $accNumber;
    private string $accPw;

    public function __construct()
    {
        self::setAttributes('users',
            [
                "id_users",
                "name",
                "cpf",
                "rg",
                "dob",
                "phone",
                "balance",
                "token",
                "acc_number",
                "acc_pw"
            ]);
    }

    /**
     * Sets the user ID
     * @param int $id
     */
    public function setId(int $id) : void
    {
        $this->id = $id;
    }

    /**
     * Gets user ID
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the user's name
     * @param string $name
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * Gets the user's name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the user's cpf/cnpj doc
     * @param string $cpf
     */
    public function setCpf(string $cpf) : void
    {
        $this->cpf = $cpf;
    }

    /**
     * Gets the user's cpf/cnpj doc
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /**
     * Sets the user's RG document
     * @param string $rg
     */
    public function setRg(string $rg) : void
    {
        $this->rg = $rg;
    }

    /**
     * Gets the user's RG document
     * @return string
     */
    public function getRg(): string
    {
        return $this->rg;
    }

    /**
     * Sets user's Date of Birth
     * @param string $dob
     */
    public function setDob(string $dob) : void
    {
        $this->dob = $dob;
    }

    /**
     * Gets user's Date of Birth
     * @return string
     */
    public function getDob(): string
    {
        return $this->dob;
    }

    /**
     * Sets the user's phone
     * @param string $phone
     */
    public function setPhone(string $phone) : void
    {
        $this->phone = $phone;
    }

    /**
     * Gets user's phone
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Sets user's balance
     * @param float $balance
     */
    public function setBalance(float $balance) : void
    {
        $this->balance = $balance;
    }

    /**
     * Gets user's balance
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Sets user's auth token
     * @param string $token
     */
    public function setToken(string $token) : void
    {
        $this->token = $token;
    }

    /**
     * Gets user's auth token
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Sets user's account number
     * @param string $accNumber
     */
    public function setAccNumber(string $accNumber) : void
    {
        $this->accNumber = $accNumber;
    }

    /**
     * Gets user's account number
     * @return string
     */
    public function getAccNumber(): string
    {
        return $this->accNumber;
    }

    /**
     * Sets user's account password
     * @param string $accPw
     */
    public function setAccPw(string $accPw) : void
    {
        $this->accPw = $accPw;
    }

    /**
     * Gets user's account password
     * @return string
     */
    public function getAccPw(): string
    {
        return $this->accPw;
    }

    /**
     * Authenticates user password
     *
     * @param string $inputPW
     * @param string $regPW
     * @return string
     */
    public function pwAuth(string $inputPW, string $regPW) : string
    {
        $pwAuth = password_verify($inputPW, $regPW);

        if (!$pwAuth) {
            return self::USER_WRONG_PW;
        }

        return self::USER_PW_AUTH_OK;
    }
}