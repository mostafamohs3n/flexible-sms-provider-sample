<?php

namespace App\Interfaces;

interface SmsProvider
{
    /**
     * @param  string  $message SMS Text
     * @param  string  $phoneNumber Recipient Phone Number
     * @return bool returns true on success
     */
    public function send(string $message, string $phoneNumber): bool;
}
