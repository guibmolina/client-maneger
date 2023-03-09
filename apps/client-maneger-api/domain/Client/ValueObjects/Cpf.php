<?php

declare(strict_types=1);

namespace Domain\Client\ValueObjects;

use Domain\Client\Exceptions\InvalidCpfException;

class Cpf
{
    public string $cpf;

    public function __construct(string $cpf)
    {
        $this->defineCpf($cpf);
    }

    private function defineCpf(string $cpf): void
    {
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
            
        if (strlen($cpf) != 11) {
            throw new InvalidCpfException();
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            throw new InvalidCpfException();
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                throw new InvalidCpfException();
            }
        }
        $this->cpf = $cpf;
    }
}