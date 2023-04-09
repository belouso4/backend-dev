<?php

namespace App\Repositories\Contracts;

interface IUserRoles
{
    public function getUserWhereHasRoles();
    public function search($query);
}
