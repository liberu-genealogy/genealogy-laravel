<?php

namespace App\Jobs;

use App\Models\Dna;
use App\Models\DnaMatching as DM;
use App\Models\User;
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

    public int $timeout = 0;
    public int $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected $current_user, protected $var_name, protected $file_name)
    {
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
            Log::info(json_encode($dna, JSON_THROW_ON_ERROR));
            Log::info(storage_path('/app/public/dna/output/shared_dna_'.$this->var_name.'_'.$dna->variable_name.'_'.$this->file_name.'_'.$dna->file_name));
            //            system('/usr/bin/python3 /home/genealogia/public_html/dna.py ' . $this->var_name . ' ' . $dna->variable_name . ' ' . '/home/genealogia/public_html/storage/app/dna/'. $this->file_name . ' ' . '/home/genealogia/public_html/storage/app/dna/'. $dna->file_name);
            // chdir('/home/familytree365/domains/api.familytree365.com/genealogy/app');
            chdir($mpath);
            $result = \Illuminate\Support\Facades\Artisan::call('dna:match', [
                'varName1'  => $this->var_name,
                'fileName1' => $this->file_name,
                'varName2'  => $dna->variable_name,
                'fileName2' => $dna->file_name,
            ]);
            $resultData = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
//            $resultData = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
            $resultData = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
            // chmod(storage_path('/app/public/dna/output/shared_dna_'.$this->var_name.'_'.$dna->variable_name.'_'.$this->file_name.'_'.$dna->file_name), 0777);

            $dm = new DM();
            $dm->user_id = $user->id;
            $dm->match_id = $dna->user_id;
            $match_name_user = User::with('person')->find($dna->user_id);
            $match_name = $match_name_user->person->name;
            $dm->match_name = $match_name;
            // $dm->image = 'shared_dna_'.$this->var_name.'_'.$dna->variable_name.'.png';
            // $dm->image = 'shared_dna_'.$this->var_name.'_'.$dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.png';
            $dm->image = env('APP_URL').'/storage/dna/output/shared_dna_'.$this->var_name.'_'.
                    $dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.png';
            // $dm->file1 = 'discordant_snps_'.$this->var_name.'_'.$dna->variable_name.'_GRCh37.csv';
            $dm->file1 = 'discordant_snps_'.$this->var_name.'_'.$dna->variable_name.'_GRCh37.csv';
            // $dm->file2 = 'shared_dna_one_chrom_'.$this->var_name.'_'.$dna->variable_name.'_GRCh37.csv';
            $dm->file2 = 'shared_dna_one_chrom_'.$this->var_name.'_'.$dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.csv';

            $dm->total_shared_cm = round($resultData['total_cms'], 2);
            $dm->largest_cm_segment = round($resultData['largest_cm'], 2);

            $dm->save();
            if ($dna->user_id !== $user->id) {
                $dm2 = new DM();
                $dm2->user_id = $dna->user_id;
                $dm2->match_id = $user->id;
                $match_name_user = User::with('person')->find($dna->user_id);
                $dm2->match_name = $match_name_user->person->name;
                // $dm->image = 'shared_dna_'.$this->var_name.'_'.$dna->variable_name.'.png';
                // $dm->image = 'shared_dna_'.$this->var_name.'_'.$dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.png';
                $dm2->image = env('APP_URL').'/storage/dna/output/shared_dna_'.$this->var_name.'_'.$dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.png';
                // $dm->file1 = 'discordant_snps_'.$this->var_name.'_'.$dna->variable_name.'_GRCh37.csv';
                $dm2->file1 = 'discordant_snps_'.$this->var_name.'_'.$dna->variable_name.'_GRCh37.csv';
                // $dm->file2 = 'shared_dna_one_chrom_'.$this->var_name.'_'.$dna->variable_name.'_GRCh37.csv';
                $dm2->file2 = 'shared_dna_one_chrom_'.$this->var_name.'_'.$dna->variable_name.'_0p75cM_1100snps_GRCh37_HapMap2.csv';

                $dm2->total_shared_cm = round($resultData['total_cms'], 2);
                $dm2->largest_cm_segment = round($resultData['largest_cm'], 2);

                $dm2->save();
            }
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
        }
    }
}
