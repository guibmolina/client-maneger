<?php

declare(strict_types=1);

namespace Domain\Client\ValueObjects;

use Domain\Client\Exceptions\InvalidPhoneException;

class Phone
{
    public ?string $phone;

    public function __construct(?string $phone)
    {
        $this->definePhone($phone);
    }

    private function definePhone(?string $phone): void
    {
        if (!$phone) {
            $this->phone = null;
            return;
        }

        $phone = preg_replace('/[()]/', '', $phone);
        if (preg_match('/^(?:(?:\+|00)?(55)\s?)?(?:\(?([0-0]?[0-9]{1}[0-9]{1})\)?\s?)??(?:((?:9\d|[2-9])\d{3}\-?\d{4}))$/', $phone, $matches) == false) {
            throw new InvalidPhoneException();
        }
    
        $ddi = $matches[1] ?? '';
        $ddd = preg_replace('/^0/', '', $matches[2] ?? '');
        $number = $matches[3] ?? '';
        $number = preg_replace('/-/', '', $number);
        
        $this->phone = $ddi . $ddd . $number;
    }
}