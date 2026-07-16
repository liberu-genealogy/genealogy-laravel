<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Dna;

use App\Services\Dna\DnaFileVault;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Extends Tests\TestCase so the app boots (Crypt/Storage facades need it).
 */
class DnaFileVaultTest extends TestCase
{
    public function test_encrypt_then_decrypt_round_trips(): void
    {
        $vault = new DnaFileVault();
        $raw = "# 23andMe\nrs1\t1\t82154\tAG\n";

        $this->assertSame($raw, $vault->decrypt($vault->encrypt($raw)));
    }

    public function test_decrypt_returns_legacy_plaintext_unchanged(): void
    {
        // A pre-encryption file is not valid ciphertext → returned as-is.
        $vault = new DnaFileVault();
        $legacy = "rsid\tchromosome\tposition\tgenotype\n";

        $this->assertSame($legacy, $vault->decrypt($legacy));
    }

    public function test_store_encrypts_on_disk_and_read_round_trips(): void
    {
        Storage::fake('private');
        $vault = new DnaFileVault();
        $raw = "rs1\t1\t82154\tAG\n";
        $path = 'dna/kit.txt';

        $vault->store($raw, $path);

        // read() decrypts back to the original...
        $this->assertSame($raw, $vault->read($path));
        // ...but the bytes on disk are ciphertext, not the plaintext.
        $this->assertNotSame($raw, Storage::disk('private')->get($path));
    }

    public function test_read_missing_file_returns_empty_string(): void
    {
        Storage::fake('private');

        $this->assertSame('', (new DnaFileVault())->read('dna/nope.txt'));
    }
}
