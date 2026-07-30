<?php

namespace App\Services;

use Illuminate\Support\Str;

class NikService
{
    /**
     * Normalize NIK to exactly 16 numeric characters.
     */
    public function normalize(string $nik): string
    {
        return preg_replace('/[^0-9]/', '', $nik);
    }

    /**
     * Validate that a (normalized) NIK is exactly 16 digits.
     */
    public function isValid(string $nik): bool
    {
        return (bool) preg_match('/^\d{16}$/', $this->normalize($nik));
    }

    /**
     * Hash NIK for duplicate detection and fast lookup.
     */
    public function hash(string $nik): string
    {
        return hash('sha256', $this->normalize($nik));
    }

    /**
     * Encrypt NIK for authorized admin storage.
     */
    public function encrypt(string $nik): string
    {
        return encrypt($this->normalize($nik));
    }

    /**
     * Decrypt NIK for authorized admin display.
     */
    public function decrypt(string $encrypted): string
    {
        return decrypt($encrypted);
    }

    /**
     * Mask NIK for public display: 3201****1234.
     */
    public function mask(string $nik): string
    {
        $nik = $this->normalize($nik);

        if (strlen($nik) !== 16) {
            return str_repeat('*', strlen($nik));
        }

        return substr($nik, 0, 4).'****'.substr($nik, -4);
    }

    /**
     * Build the standard array of nik_hash, nik_encrypted, nik_masked from raw NIK.
     *
     * @return array{nik_hash: string, nik_encrypted: string, nik_masked: string}
     */
    public function buildFields(string $rawNik): array
    {
        $normalized = $this->normalize($rawNik);

        return [
            'nik_hash' => $this->hash($normalized),
            'nik_encrypted' => $this->encrypt($normalized),
            'nik_masked' => $this->mask($normalized),
        ];
    }
}
