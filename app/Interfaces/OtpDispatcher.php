<?php

namespace App\Interfaces;

interface OtpDispatcher
{
    public function dispatch(): bool;
}
