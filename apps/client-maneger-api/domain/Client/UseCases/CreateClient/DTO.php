<?php

declare(strict_types=1);

namespace Domain\Client\UseCases\CreateClient;

class DTO
{
    public string $name;

    public string $cpf;

    public string $birthDate;
    
    public ?string $phone = null;

    public function __construct(string $name, string $cpf, string $birthDate, ?string $phone)
    {
        $this->name = $name;

        $this->cpf = $cpf;

        $this->birthDate = $birthDate;

        $this->phone = $phone;
    }
}