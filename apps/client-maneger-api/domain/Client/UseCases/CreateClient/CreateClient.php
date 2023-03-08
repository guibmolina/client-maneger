<?php

declare(strict_types=1);

namespace Domain\Client\UseCases\CreateClient;

use DateTimeImmutable;
use Domain\Client\Entities\ClientEntity;
use Domain\Client\Repositories\ClientRepository;
use Domain\Client\ValueObjects\Cpf;
use Domain\Client\ValueObjects\Phone;

class CreateClient
{
    public ClientRepository $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): Response
    {
        $client = new ClientEntity(
            null,
            $DTO->name,
            new Cpf($DTO->cpf),
            new DateTimeImmutable($DTO->birthDate),
            new Phone($DTO->phone)
        );

        $clientSaved = $this->repository->store($client);

        return new Response($client);
    }
}