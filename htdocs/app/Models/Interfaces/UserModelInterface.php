<?php

namespace App\Models\Interfaces;

interface UserModelInterface
{
    public function setId(int $id);

    public function getId() : int;

    public function setName(string $name);

    public function getName() : string;

    public function setCpf(string $cpf);

    public function getCpf() : string;

    public function setRg(string $rg);

    public function getRg() : string;

    public function setDob(string $dob);

    public function getDob() : string;

    public function setPhone(string $phone);

    public function getPhone() : string;

    public function setBalance(float $balance);
    
    public function getBalance() : float;

    public function setToken(string $token);

    public function getToken() : string;

    public function setAccNumber(string $accNumber);

    public function getAccNumber() : string;

    public function setAccPw(string $accPw);

    public function getAccPw() : string;
}