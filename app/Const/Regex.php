<?php

namespace App\Constants;

class Regex
{
    // số điện thoại
    public const PHONE_NUMBER = '/^(03|05|07|08|09|01[2|6|8|9])([0-9]{8})$/';

    // ít nhất 8 ký tự, có ít nhất 1 chữ hoa, 1 chữ thường, 1 số
    public const PASSWORD = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
}
