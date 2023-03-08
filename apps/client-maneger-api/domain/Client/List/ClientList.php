<?php

declare(strict_types=1);

namespace Domain\Client\List;

use ArrayIterator;
use Domain\Client\Entities\ClientEntity;
use IteratorAggregate;
use Traversable;

/** @implements IteratorAggregate<ClientEntity> */
class ClientList implements IteratorAggregate
{
    /** @var array<ClientEntity> */
    private array $clients;

    public function __construct()
    {
        $this->clients = [];
    }

    public function add(ClientEntity $client): void
    {
        $this->clients[] = $client;
    }

    /** @return array<ClientEntity> */
    public function clients(): array
    {
        return $this->clients;
    }

    /** @return Traversable<ClientEntity> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->clients);
    }
}
