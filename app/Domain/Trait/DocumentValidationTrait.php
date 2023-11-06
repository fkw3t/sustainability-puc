<?php

declare(strict_types=1);

namespace App\Domain\Trait;

use App\Domain\Exception\InvalidDocumentException;

trait DocumentValidationTrait
{
    /**
     * @throws InvalidDocumentException
     */
    public function validate(string $document): string
    {
        return match (strlen($document)) {
            11      => $this->validateCPF($document),
            14      => $this->validateCNPJ($document),
            default => throw new InvalidDocumentException('Invalid document length'),
        };
    }

    /**
     * @throws InvalidDocumentException
     */
    private function validateCPF(string $cpf): string
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            throw new InvalidDocumentException('Invalid document length');
        }

        foreach (range(9, 10) as $i) {
            $sum = 0;
            for ($j = 0; $j < $i; ++$j) {
                $sum += intval($cpf[$j]) * (($i + 1) - $j);
            }
            $remainder = $sum % 11;
            $digit = ($remainder < 2) ? 0 : 11 - $remainder;

            if ($cpf[$i] != $digit) {
                throw new InvalidDocumentException('Invalid document ' . $cpf);
            }
        }

        return $cpf;
    }

    /**
     * @throws InvalidDocumentException
     */
    private function validateCNPJ(string $cnpj): string
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            throw new InvalidDocumentException('Invalid document length');
        }

        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        foreach ([12, 13] as $i) {
            $sum = 0;
            foreach (range(0, 11) as $j) {
                $sum += intval($cnpj[$j]) * $weights[$j];
            }
            $remainder = $sum % 11;
            $digit = ($remainder < 2) ? 0 : 11 - $remainder;

            if ($cnpj[$i] != $digit) {
                throw new InvalidDocumentException('Invalid document ' . $cnpj);
            }
            array_shift($weights);
        }

        return $cnpj;
    }
}
