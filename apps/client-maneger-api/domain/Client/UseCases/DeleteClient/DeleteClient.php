<?php

declare(strict_types=1);

namespace Domain\Client\UseCases\DeleteClient;

use Domain\Client\Exceptions\NotFoundClientExceptoin;
use Domain\Client\Repositories\ClientRepository;

class DeleteClient
{
    public ClientRepository $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DTO $DTO): void
    {
        $client = $this->repository->findClientById($DTO->clientId);

        if (!$client) {
            throw new NotFoundClientExceptoin();
        }

        $this->repository->delete($client);
    }
}