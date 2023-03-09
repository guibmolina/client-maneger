<?php

declare(strict_types=1);

namespace Domain\Client\Entities;

use DateTimeImmutable;
use Domain\Client\ValueObjects\Cpf;
use Domain\Client\ValueObjects\Phone;

class ClientEntity
{
    public ?int $id; 

    public string $name;

    public Cpf $cpf;

    public DateTimeImmutable $birthDate;

    public ?Phone $phone;

    public function __construct(?int $id, string $name, Cpf $cpf, DateTimeImmutable $birthDate, ?Phone $phone)
    {
        $this->id = $id;

        $this->name = $name;

        $this->cpf = $cpf;

        $this->birthDate = $birthDate;

        $this->phone = $phone;
    }

    public function birthDateFomated(): string
    {
        return $this->birthDate->format('Y-m-d');
    }

    public function cpf(): string
    {
        return $this->cpf->cpf; 
    }

    public function phone(): ?string
    {
        return $this->phone->phone;
    }
}
