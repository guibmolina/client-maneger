<?php

declare(strict_types=1);

namespace Infra\Client\Repositories;

use App\Models\Client;
use DateTimeImmutable;
use Domain\Client\Entities\ClientEntity;
use Domain\Client\List\ClientList;
use Domain\Client\Repositories\ClientRepository as ClientRepositoryInterface;
use Domain\Client\ValueObjects\Cpf;
use Domain\Client\ValueObjects\Phone;
use Exception;

class ClientRepository implements ClientRepositoryInterface
{
    public function store(ClientEntity $entity): ClientEntity
    {
        $client = new Client();
        $client->name = $entity->name;
        $client->cpf = $entity->cpf();
        $client->birth_date = $entity->birthDateFomated();
        $client->phone = $entity->phone();

        try {
            $client->save();
        } catch (Exception $e) {
            throw new Exception("Error to save client: {$entity->name}");
        }

        return $this->mapClientEntityDomain($client);
    }

    public function findClientById(int $id): ?ClientEntity
    {
        $client = Client::find($id);

        if (!$client) {
            return null;
        }

        return $this->mapClientEntityDomain($client);
    }

    public function findClientBy(?string $cpf, ?string $name): ClientList
    {
        $query = Client::select();

        if ($cpf) {
            $query->where('cpf', 'like' , "%{$cpf}%");
        }

        if ($name) {
            $query->where('name', 'like' , "%{$name}%");
        }

        $clients = $query->get();

        $clientList = new ClientList();

        foreach ($clients as $client) {
            $clientList->add($this->mapClientEntityDomain($client));
        }

        return $clientList;
    }

    public function delete(ClientEntity $clientEntity): void
    {
        $client = Client::find($clientEntity->id);

        try {
            $client->delete();
        } catch (Exception $e) {
            throw new Exception("Error to delete product {$clientEntity->id}");
        }
    }

    private function mapClientEntityDomain(Client $client): ClientEntity
    {
        $clientEntity = new ClientEntity(
            $client->id,
            $client->name,
            new Cpf($client->cpf),
            new DateTimeImmutable($client->birth_date),
            new Phone($client->phone)
        );

        return $clientEntity;
    }
}
