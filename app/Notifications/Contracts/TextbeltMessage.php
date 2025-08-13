<?php

namespace App\Notifications\Contracts;

interface TextbeltMessage
{
    public function toTextbelt(object $notifiable): array;
}
