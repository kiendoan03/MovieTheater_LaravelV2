<?php

namespace App\Enums;

enum BookingStatus: int
{
    case Available = 0;
    case Booked = 1;
    case Reserved = 2;
}
