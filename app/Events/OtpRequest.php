<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     * @param  User  $user
     * @param  string  $otpType
     */
    public function __construct(private readonly User $user, private readonly string $otpType)
    {
        //
    }

    /**
     * @return string
     */
    public function getOtpType(): string
    {
        return $this->otpType;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
