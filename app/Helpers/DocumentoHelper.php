<?php

namespace App\Helpers;

class DocumentoHelper
{
    public static function cnpj(?string $value): string
    {
        $cnpj = preg_replace('/\D+/', '', $value ?? '');

        if (strlen($cnpj) !== 14) {
            return (string) $value;
        }

        return substr($cnpj, 0, 2) . '.' .
            substr($cnpj, 2, 3) . '.' .
            substr($cnpj, 5, 3) . '/' .
            substr($cnpj, 8, 4) . '-' .
            substr($cnpj, 12, 2);
    }
}
