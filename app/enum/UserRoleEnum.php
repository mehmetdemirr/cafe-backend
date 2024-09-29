<?php

namespace App\enum;

enum UserRoleEnum :String 
{
    public const SUPERADMIN = 'super-admin';
    public const ADMIN = "admin";
    public const USER = 'user';
    public const BUSINESS = 'business';
    public const CALLCENTER = 'call-center';
}
