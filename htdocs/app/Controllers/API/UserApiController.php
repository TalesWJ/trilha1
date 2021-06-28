<?php

namespace App\Controllers\API;

use App\Models\UserModel;
use App\Models\UserAddressModel;
use \Helper;

class UserApiController
{
    private UserModel $user;
    private UserAddressModel $address;

    public function __construct()
    {
        $this->user = Helper::getContainer('UserModel');
    }

    public function searchUsers() : void
    {
        $allUsers = $this->user->selectAllData();
        $this->address = Helper::getContainer('UserAddressModel');
        $length = 0;
        foreach ($allUsers as $user) {
            $data[$length] = ['user' => $user];
            $length++;
        }
        Helper::apiResponse(
            'Sucesso na Busca',
            'users',
            $data
        );
    }
}