<?php

declare(strict_types=1);

namespace Domain\Client\UseCases\GetClients;

class DTO
{
    public ?string $cpf;

    public ?string $name;

    public function __construct(?array $cpf = null, ?int $name   = null)
    {
        $this->cpf = $cpf ?? [];

        $this->name = $name;
    }
}
