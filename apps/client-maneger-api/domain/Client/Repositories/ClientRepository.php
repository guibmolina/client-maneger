<?php

declare(strict_types=1);

namespace Domain\Client\Repositories;

use Domain\Client\Entities\ClientEntity;
use Domain\Client\List\ClientList;

interface ClientRepository
{
    public function store(ClientEntity $entity): ClientEntity;

    public function findClientById(int $id): ClientEntity;

    public function findClientBy(?string $cpf, ?string $name): ClientList;

    public function delete(ClientEntity $clientEntity): void;
}