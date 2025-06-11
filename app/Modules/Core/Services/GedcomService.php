<?php

namespace App\Modules\Core\Services;

use App\Models\Person;
use App\Models\Family;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class GedcomService
{
    /**
     * Export genealogy data to GEDCOM format.
     */
    public function exportToGedcom(Collection $persons = null, Collection $families = null): string
    {
        $persons = $persons ?? Person::all();
        $families = $families ?? Family::all();

        $gedcom = $this->generateGedcomHeader();
        $gedcom .= $this->generatePersonRecords($persons);
        $gedcom .= $this->generateFamilyRecords($families);
        $gedcom .= $this->generateGedcomTrailer();

        return $gedcom;
    }

    /**
     * Import GEDCOM data.
     */
    public function importFromGedcom(string $gedcomContent): array
    {
        $lines = explode("\n", $gedcomContent);
        $records = $this->parseGedcomLines($lines);
        
        $imported = [
            'persons' => 0,
            'families' => 0,
            'errors' => [],
        ];

        foreach ($records as $record) {
            try {
                if ($record['type'] === 'INDI') {
                    $this->importPersonRecord($record);
                    $imported['persons']++;
                } elseif ($record['type'] === 'FAM') {
                    $this->importFamilyRecord($record);
                    $imported['families']++;
                }
            } catch (\Exception $e) {
                $imported['errors'][] = "Error importing {$record['type']} {$record['id']}: " . $e->getMessage();
            }
        }

        return $imported;
    }

    /**
     * Generate GEDCOM header.
     */
    protected function generateGedcomHeader(): string
    {
        return "0 HEAD\n" .
               "1 SOUR " . config('app.name', 'Genealogy') . "\n" .
               "2 VERS 1.0\n" .
               "1 GEDC\n" .
               "2 VERS 5.5.1\n" .
               "2 FORM LINEAGE-LINKED\n" .
               "1 CHAR UTF-8\n" .
               "1 DATE " . now()->format('d M Y') . "\n" .
               "2 TIME " . now()->format('H:i:s') . "\n";
    }

    /**
     * Generate person records in GEDCOM format.
     */
    protected function generatePersonRecords(Collection $persons): string
    {
        $gedcom = '';
        
        foreach ($persons as $person) {
            $gedcom .= "0 @I{$person->id}@ INDI\n";
            $gedcom .= "1 NAME {$person->givn} /{$person->surn}/\n";
            $gedcom .= "2 GIVN {$person->givn}\n";
            $gedcom .= "2 SURN {$person->surn}\n";
            $gedcom .= "1 SEX {$person->sex}\n";
            
            if ($person->birthday) {
                $gedcom .= "1 BIRT\n";
                $gedcom .= "2 DATE " . $person->birthday->format('d M Y') . "\n";
            }
            
            if ($person->deathday) {
                $gedcom .= "1 DEAT\n";
                $gedcom .= "2 DATE " . $person->deathday->format('d M Y') . "\n";
            }
            
            if ($person->child_in_family_id) {
                $gedcom .= "1 FAMC @F{$person->child_in_family_id}@\n";
            }
            
            // Add family spouse relationships
            $spouseFamilies = $person->familiesAsHusband->merge($person->familiesAsWife);
            foreach ($spouseFamilies as $family) {
                $gedcom .= "1 FAMS @F{$family->id}@\n";
            }
        }
        
        return $gedcom;
    }

    /**
     * Generate family records in GEDCOM format.
     */
    protected function generateFamilyRecords(Collection $families): string
    {
        $gedcom = '';
        
        foreach ($families as $family) {
            $gedcom .= "0 @F{$family->id}@ FAM\n";
            
            if ($family->husband_id) {
                $gedcom .= "1 HUSB @I{$family->husband_id}@\n";
            }
            
            if ($family->wife_id) {
                $gedcom .= "1 WIFE @I{$family->wife_id}@\n";
            }
            
            // Add children
            $children = Person::where('child_in_family_id', $family->id)->get();
            foreach ($children as $child) {
                $gedcom .= "1 CHIL @I{$child->id}@\n";
            }
        }
        
        return $gedcom;
    }

    /**
     * Generate GEDCOM trailer.
     */
    protected function generateGedcomTrailer(): string
    {
        return "0 TRLR\n";
    }

    /**
     * Parse GEDCOM lines into structured records.
     */
    protected function parseGedcomLines(array $lines): array
    {
        $records = [];
        $currentRecord = null;
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $parts = explode(' ', $line, 3);
            $level = (int) $parts[0];
            
            if ($level === 0 && isset($parts[2])) {
                // Start of new record
                if ($currentRecord) {
                    $records[] = $currentRecord;
                }
                
                $currentRecord = [
                    'level' => $level,
                    'id' => trim($parts[1], '@'),
                    'type' => $parts[2],
                    'data' => [],
                ];
            } elseif ($currentRecord) {
                // Add data to current record
                $currentRecord['data'][] = [
                    'level' => $level,
                    'tag' => $parts[1] ?? '',
                    'value' => $parts[2] ?? '',
                ];
            }
        }
        
        if ($currentRecord) {
            $records[] = $currentRecord;
        }
        
        return $records;
    }

    /**
     * Import person record from GEDCOM data.
     */
    protected function importPersonRecord(array $record): Person
    {
        $personData = [
            'gid' => $record['id'],
        ];
        
        foreach ($record['data'] as $data) {
            switch ($data['tag']) {
                case 'NAME':
                    $this->parsePersonName($data['value'], $personData);
                    break;
                case 'SEX':
                    $personData['sex'] = $data['value'];
                    break;
                case 'BIRT':
                    // Handle birth date in subsequent data
                    break;
                case 'DEAT':
                    // Handle death date in subsequent data
                    break;
            }
        }
        
        return Person::updateOrCreate(['gid' => $record['id']], $personData);
    }

    /**
     * Import family record from GEDCOM data.
     */
    protected function importFamilyRecord(array $record): Family
    {
        $familyData = [
            'gid' => $record['id'],
        ];
        
        foreach ($record['data'] as $data) {
            switch ($data['tag']) {
                case 'HUSB':
                    $husbandGid = trim($data['value'], '@');
                    $husband = Person::where('gid', $husbandGid)->first();
                    if ($husband) {
                        $familyData['husband_id'] = $husband->id;
                    }
                    break;
                case 'WIFE':
                    $wifeGid = trim($data['value'], '@');
                    $wife = Person::where('gid', $wifeGid)->first();
                    if ($wife) {
                        $familyData['wife_id'] = $wife->id;
                    }
                    break;
            }
        }
        
        return Family::updateOrCreate(['gid' => $record['id']], $familyData);
    }

    /**
     * Parse person name from GEDCOM format.
     */
    protected function parsePersonName(string $name, array &$personData): void
    {
        if (preg_match('/^(.+?)\s*\/(.+?)\//', $name, $matches)) {
            $personData['givn'] = trim($matches[1]);
            $personData['surn'] = trim($matches[2]);
            $personData['name'] = $personData['givn'] . ' ' . $personData['surn'];
        } else {
            $personData['name'] = $name;
            $personData['givn'] = $name;
        }
    }
}