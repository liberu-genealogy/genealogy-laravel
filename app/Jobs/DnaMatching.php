<?php

namespace App\Jobs;

use Exception;
use App\Models\Dna;
use App\Models\DnaMatching as DM;
use App\Models\User;
use App\Services\AdvancedDnaMatchingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DnaMatching implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 3600; // Changed from 0 to 1 hour
    public int $tries = 1;

    protected AdvancedDnaMatchingService $advancedDnaMatchingService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected $current_user, protected $var_name, protected $file_name)
    {
        $this->advancedDnaMatchingService = app(AdvancedDnaMatchingService::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->current_user;
        $dnas = Dna::where('variable_name', '!=', $this->var_name)->get();
        $mpath = app_path();

        foreach ($dnas as $dna) {
            try {
                Log::info('Processing DNA match between ' . $this->var_name . ' and ' . $dna->variable_name);

                // Use advanced DNA matching service
                $matchResult = $this->advancedDnaMatchingService->performAdvancedMatching(
                    $this->var_name,
                    $this->file_name,
                    $dna->variable_name,
                    $dna->file_name
                );

                // Get match name
                $match_name_user = User::with('person')->find($dna->user_id);
                $match_name = $match_name_user->person->name ?? 'Unknown';

                // Create DNA matching record for current user
                $dm = new DM();
                $dm->user_id = $user->id;
                $dm->match_id = $dna->user_id;
                $dm->match_name = $match_name;
                $dm->image = env('APP_URL').'/storage/dna/output/shared_dna_'.$this->var_name.'_'.$dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.png';
                $dm->file1 = 'discordant_snps_'.$this->var_name.'_'.$dna->variable_name.'_GRCh37.csv';
                $dm->file2 = 'shared_dna_one_chrom_'.$this->var_name.'_'.$dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.csv';

                // Store advanced matching results
                $dm->total_shared_cm = $matchResult['total_cms'];
                $dm->largest_cm_segment = $matchResult['largest_cm'];
                $dm->confidence_level = $matchResult['confidence_level'] ?? null;
                $dm->predicted_relationship = $matchResult['predicted_relationship'] ?? null;
                $dm->shared_segments_count = $matchResult['shared_segments_count'] ?? null;
                $dm->match_quality_score = $matchResult['match_quality_score'] ?? null;
                $dm->detailed_report = $matchResult['detailed_report'] ?? null;
                $dm->chromosome_breakdown = $matchResult['chromosome_breakdown'] ?? null;
                $dm->analysis_date = now();

                $dm->save();

                // Create reciprocal record for the matched user (if different)
                if ($dna->user_id !== $user->id) {
                    $current_user_name = User::with('person')->find($user->id);
                    $current_name = $current_user_name->person->name ?? 'Unknown';

                    $dm2 = new DM();
                    $dm2->user_id = $dna->user_id;
                    $dm2->match_id = $user->id;
                    $dm2->match_name = $current_name;
                    $dm2->image = env('APP_URL').'/storage/dna/output/shared_dna_'.$this->var_name.'_'.$dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.png';
                    $dm2->file1 = 'discordant_snps_'.$this->var_name.'_'.$dna->variable_name.'_GRCh37.csv';
                    $dm2->file2 = 'shared_dna_one_chrom_'.$this->var_name.'_'.$dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.csv';

                    // Store same advanced matching results
                    $dm2->total_shared_cm = $matchResult['total_cms'];
                    $dm2->largest_cm_segment = $matchResult['largest_cm'];
                    $dm2->confidence_level = $matchResult['confidence_level'] ?? null;
                    $dm2->predicted_relationship = $matchResult['predicted_relationship'] ?? null;
                    $dm2->shared_segments_count = $matchResult['shared_segments_count'] ?? null;
                    $dm2->match_quality_score = $matchResult['match_quality_score'] ?? null;
                    $dm2->detailed_report = $matchResult['detailed_report'] ?? null;
                    $dm2->chromosome_breakdown = $matchResult['chromosome_breakdown'] ?? null;
                    $dm2->analysis_date = now();

                    $dm2->save();
                }

                Log::info('Successfully processed DNA match with advanced algorithms');
                Log::info('Match result: ' . json_encode([
                    'total_cms' => $matchResult['total_cms'],
                    'largest_cm' => $matchResult['largest_cm'],
                    'confidence' => $matchResult['confidence_level'] ?? 'N/A',
                    'relationship' => $matchResult['predicted_relationship'] ?? 'N/A'
                ]));

                // $data = readCSV(storage_path('app'.DIRECTORY_SEPARATOR.'dna'.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$dm->file1), ',');
                // array_shift($data);
                // $data = writeCSV(storage_path('app'.DIRECTORY_SEPARATOR.'dna'.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$dm->file1), $data);

                // $data = readCSV(storage_path('app'.DIRECTORY_SEPARATOR.'dna'.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$dm->file2), ',');
                // array_shift($data);

                // $temp_data = $data;
                // array_shift($temp_data);
                // array_shift($temp_data);
                // $total_cms = 0;
                // $largest_cm = 0;
                // foreach ($temp_data as $line) {
                //     if ($line[4] >= $largest_cm) {
                //         $largest_cm = $line[4];
                //     }
                //     $total_cms = $total_cms + $line[4];
                // }
                // $dm->user_id = $user->id;
                // $dm->total_shared_cm = $total_cms;
                // $dm->largest_cm_segment = round($largest_cm, 2);
                // $dm->save();

                // $data = writeCSV(storage_path('app'.DIRECTORY_SEPARATOR.'dna'.DIRECTORY_SEPARATOR.'output'.DIRECTORY_SEPARATOR.$dm->file2), $data);
            } catch (Exception $e) {
                Log::error('Error in DNA matching job: ' . $e->getMessage());
                continue; // Skip to next DNA record on error
            }
        }
    }
}
