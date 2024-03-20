<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dna;
use App\Models\DnaMatching;

class MatchKitsCommand extends Command
{
    protected $signature = 'dna:match {varName1} {fileName1} {varName2} {fileName2}';
    protected $description = 'Matches two DNA kits and updates the database with the results.';

    public function handle()
    {
        $varName1 = $this->argument('varName1');
        $fileName1 = $this->argument('fileName1');
        $varName2 = $this->argument('varName2');
        $fileName2 = $this->argument('fileName2');

        $dna1 = Dna::where('variable_name', $varName1)->where('file_name', $fileName1)->first();
        $dna2 = Dna::where('variable_name', $varName2)->where('file_name', $fileName2)->first();

        if (!$dna1 || !$dna2) {
            $this->error('One or both DNA kits not found.');
            return;
        }

        $totalSharedCm = rand(1, 100); // Simulated DNA match result
        $largestCmSegment = rand(1, $totalSharedCm); // Simulated DNA match result

        DnaMatching::create([
            'file1' => $fileName1,
            'file2' => $fileName2,
            'image' => 'path/to/match/image.png', // Simulated path to match image
            'total_shared_cm' => $totalSharedCm,
            'largest_cm_segment' => $largestCmSegment,
            'match_id' => $dna2->user_id,
        ]);

        $this->info('DNA kits matched successfully.');
    }
}
