<?php

declare(strict_types=1);

namespace Domain\Client\UseCases\GetClients;

use Domain\Client\List\ClientList;

class Response
{
    public ClientList $clients;

    public function __construct(ClientList $clients)
    {
        $this->clients = $clients;
    }

    public function response(): array
    {
        $response = [];
        foreach($this->clients as $client) {
            $response[] = [
                'id' => $client->id,
                'name' => $client->name,
                'cpf' => $client->cpf,
                'birth_date' => $client->birthDateFomated(),
                'phone' => $client->phone,
            ];
        }

        return $response;
    }
}
