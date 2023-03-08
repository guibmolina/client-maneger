<?php

declare(strict_types=1);

namespace Domain\Client\UseCases\GetClients;

use Domain\Client\Repositories\ClientRepository;

class GetClients
{
    public ClientRepository $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): Response
    {
        $tasks = $this->repository->findClientBy($DTO->cpf, $DTO->name);

        return new Response($tasks);
    }
}