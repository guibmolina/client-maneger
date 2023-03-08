<?php

declare(strict_types=1);

namespace Domain\Client\UseCases\DeleteClient;

class DTO
{
    public int $clientId;

    public function __construct(int $clientId)
    {
        $this->clientId = $clientId;
    }
}