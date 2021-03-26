<?php

namespace App\domain\enums\Permission;

abstract class PermissionTypes
{
    const VIEW = 0;
    const CREATE = 1;
    const EDIT = 2;
    const DELETE = 3;
}