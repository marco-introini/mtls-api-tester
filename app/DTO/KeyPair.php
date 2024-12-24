<?php

namespace App\DTO;

readonly final class KeyPair
{
    public function __construct(
        public string $privateKey,
        public string $certificate
    ) {
    }
}
