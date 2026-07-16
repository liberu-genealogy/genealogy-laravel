<?php

declare(strict_types=1);

namespace App\Services\Dna;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

/**
 * At-rest encryption for raw DNA file contents.
 *
 * Raw kit files are sensitive genetic data; we keep them encrypted on the
 * `private` disk with Laravel's Crypt (AES-256, keyed by APP_KEY) instead of
 * as plaintext. store()/read() are the disk-facing pair; encrypt()/decrypt()
 * are exposed for callers that already hold the bytes (e.g. the matcher
 * decrypting content it then parses in memory).
 */
class DnaFileVault
{
    public function encrypt(string $plaintext): string
    {
        return Crypt::encryptString($plaintext);
    }

    /**
     * Decrypt ciphertext produced by encrypt().
     *
     * Backward-compat: files stored before encryption was introduced are
     * plaintext and are NOT valid ciphertext. Rather than fail on them, we
     * catch DecryptException and return the input unchanged, so legacy kits
     * still read. New writes always go through encrypt(), so this fallback
     * only ever fires on pre-encryption data.
     */
    public function decrypt(string $ciphertext): string
    {
        try {
            return Crypt::decryptString($ciphertext);
        } catch (DecryptException) {
            return $ciphertext;
        }
    }

    public function store(string $plaintext, string $path, string $disk = 'private'): void
    {
        Storage::disk($disk)->put($path, $this->encrypt($plaintext));
    }

    /**
     * Read and decrypt a stored file. Missing file → '' (never throws).
     */
    public function read(string $path, string $disk = 'private'): string
    {
        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return '';
        }

        return $this->decrypt((string) $storage->get($path));
    }
}
