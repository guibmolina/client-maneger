<?php

declare(strict_types=1);

namespace Domain\Client\UseCases\CreateClient;

use Domain\Client\Entities\ClientEntity;

class Response
{

    public ClientEntity $entity;

    public function __construct(ClientEntity $entity)
    {
        $this->entity = $entity;
    }

    public function response(): array
    {
        return [
            'name' => $this->entity->name,
            'cpf' => $this->entity->cpf,
            'birth_date' => $this->entity->birthDateFomated(),
            'phone' => $this->entity->phone,
        ];
    }
}
