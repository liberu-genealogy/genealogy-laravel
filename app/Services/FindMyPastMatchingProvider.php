<?php

namespace App\Services;

use App\Models\Person;
use App\Models\RecordType;

class FindMyPastMatchingProvider
{
    /**
     * Search FindMyPast-specific record types for a person
     */
    public function searchRecords(Person $person, ?string $recordCategory = null): array
    {
        $matches = [];

        // Define FindMyPast-specific record types to search
        $recordTypes = $this->getRecordTypesToSearch($recordCategory);

        foreach ($recordTypes as $recordType) {
            $typeMatches = $this->searchRecordType($person, $recordType);
            $matches = array_merge($matches, $typeMatches);
        }

        return $matches;
    }

    /**
     * Get record types to search based on category
     */
    private function getRecordTypesToSearch(?string $category = null): array
    {
        $findMyPastCategories = [
            'newspaper',
            'parish',
            'census',
            'electoral',
            'gro_index',
            'military',
            'probate',
            'poor_law',
        ];

        if ($category && in_array($category, $findMyPastCategories)) {
            return [$category];
        }

        return $findMyPastCategories;
    }

    /**
     * Search a specific record type category
     */
    private function searchRecordType(Person $person, string $recordCategory): array
    {
        return match ($recordCategory) {
            'newspaper' => $this->searchNewspapers($person),
            'parish' => $this->searchParishRecords($person),
            'census' => $this->searchCensus($person),
            'electoral' => $this->searchElectoralRegisters($person),
            'gro_index' => $this->searchGROIndex($person),
            'military' => $this->searchMilitaryRecords($person),
            'probate' => $this->searchProbateRecords($person),
            'poor_law' => $this->searchPoorLawRecords($person),
            default => [],
        };
    }

    /**
     * Search newspaper archives (FindMyPast's extensive newspaper collection)
     */
    private function searchNewspapers(Person $person): array
    {
        $matches = [];

        // Simulate searching newspaper obituaries
        if ($person->deathday) {
            $matches[] = [
                'record_type' => 'newspaper',
                'record_subtype' => 'obituary',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_news_' . rand(1000, 9999),
                'person_id' => 'findmypast_obit_' . rand(10000, 99999),
                'confidence_score' => $this->calculateNewspaperConfidence($person),
                'data' => [
                    'type' => 'Newspaper Obituary',
                    'name' => $person->fullname(),
                    'publication' => $this->getRandomNewspaper(),
                    'publication_date' => $person->deathday?->addDays(rand(1, 14))->format('Y-m-d'),
                    'page' => rand(1, 24),
                    'death_date' => $person->deathday?->format('Y-m-d'),
                    'age_at_death' => $this->calculateAge($person),
                    'place' => $person->birthplace?->place ?? 'Unknown',
                    'extract' => $this->generateObituaryExtract($person),
                ],
            ];
        }

        // Simulate searching marriage announcements
        if ($person->families()->count() > 0) {
            $matches[] = [
                'record_type' => 'newspaper',
                'record_subtype' => 'notice',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_news_' . rand(1000, 9999),
                'person_id' => 'findmypast_notice_' . rand(10000, 99999),
                'confidence_score' => 0.65,
                'data' => [
                    'type' => 'Marriage Announcement',
                    'name' => $person->fullname(),
                    'publication' => $this->getRandomNewspaper(),
                    'publication_date' => now()->subYears(rand(20, 80))->format('Y-m-d'),
                    'notice_type' => 'marriage',
                ],
            ];
        }

        return $matches;
    }

    /**
     * Search parish records (baptisms, marriages, burials)
     */
    private function searchParishRecords(Person $person): array
    {
        $matches = [];

        // Search baptism records
        if ($person->birthday) {
            $matches[] = [
                'record_type' => 'parish',
                'record_subtype' => 'baptism',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_parish_' . rand(1000, 9999),
                'person_id' => 'findmypast_baptism_' . rand(10000, 99999),
                'confidence_score' => $this->calculateParishConfidence($person),
                'data' => [
                    'type' => 'Parish Baptism',
                    'name' => $person->fullname(),
                    'baptism_date' => $person->birthday?->addDays(rand(7, 90))->format('Y-m-d'),
                    'birth_date' => $person->birthday?->format('Y-m-d'),
                    'parish' => $this->getRandomParish(),
                    'fathers_name' => $this->getFatherName($person),
                    'mothers_name' => $this->getMotherName($person),
                    'abode' => $person->birthplace?->place ?? 'Unknown',
                ],
            ];
        }

        // Search burial records
        if ($person->deathday) {
            $matches[] = [
                'record_type' => 'parish',
                'record_subtype' => 'burial',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_parish_' . rand(1000, 9999),
                'person_id' => 'findmypast_burial_' . rand(10000, 99999),
                'confidence_score' => $this->calculateParishConfidence($person),
                'data' => [
                    'type' => 'Parish Burial',
                    'name' => $person->fullname(),
                    'burial_date' => $person->deathday?->addDays(rand(3, 10))->format('Y-m-d'),
                    'death_date' => $person->deathday?->format('Y-m-d'),
                    'age' => $this->calculateAge($person),
                    'parish' => $this->getRandomParish(),
                    'abode' => $person->deathplace?->place ?? 'Unknown',
                ],
            ];
        }

        return $matches;
    }

    /**
     * Search census records
     */
    private function searchCensus(Person $person): array
    {
        $matches = [];

        // Available UK census years
        $censusYears = [1841, 1851, 1861, 1871, 1881, 1891, 1901, 1911];

        foreach ($censusYears as $year) {
            if ($person->birthday && $person->birthday->year < $year) {
                if (!$person->deathday || $person->deathday->year >= $year) {
                    $matches[] = [
                        'record_type' => 'census',
                        'record_subtype' => 'census',
                        'source' => 'findmypast',
                        'tree_id' => 'findmypast_census_' . $year,
                        'person_id' => 'findmypast_census_' . $year . '_' . rand(10000, 99999),
                        'confidence_score' => $this->calculateCensusConfidence($person, $year),
                        'data' => [
                            'type' => 'Census Record',
                            'census_year' => $year,
                            'name' => $person->fullname(),
                            'age' => $year - $person->birthday->year,
                            'birth_year' => $person->birthday->year,
                            'birthplace' => $person->birthplace?->place ?? 'Unknown',
                            'residence' => $this->getRandomPlace(),
                            'county' => $this->getRandomCounty(),
                            'household_members' => rand(2, 8),
                            'occupation' => $this->getRandomOccupation(),
                        ],
                    ];
                }
            }
        }

        return $matches;
    }

    /**
     * Search electoral registers
     */
    private function searchElectoralRegisters(Person $person): array
    {
        $matches = [];

        // Electoral registers are available from 1832 onwards
        if ($person->birthday && $person->birthday->year < 1950) {
            $startYear = max(1832, $person->birthday->year + 21); // Voting age
            $endYear = $person->deathday ? $person->deathday->year : min(1950, now()->year);

            // Sample a few years
            for ($year = $startYear; $year <= $endYear; $year += rand(5, 10)) {
                $matches[] = [
                    'record_type' => 'electoral',
                    'record_subtype' => 'register',
                    'source' => 'findmypast',
                    'tree_id' => 'findmypast_electoral_' . $year,
                    'person_id' => 'findmypast_electoral_' . $year . '_' . rand(10000, 99999),
                    'confidence_score' => 0.70,
                    'data' => [
                        'type' => 'Electoral Register',
                        'register_year' => $year,
                        'name' => $person->fullname(),
                        'address' => $this->getRandomAddress(),
                        'constituency' => $this->getRandomConstituency(),
                        'qualification' => 'Household Suffrage',
                    ],
                ];
            }
        }

        return $matches;
    }

    /**
     * Search GRO (General Register Office) index
     */
    private function searchGROIndex(Person $person): array
    {
        $matches = [];

        // GRO Birth Index (1837 onwards)
        if ($person->birthday && $person->birthday->year >= 1837) {
            $quarter = $this->getQuarter($person->birthday);
            $matches[] = [
                'record_type' => 'gro_index',
                'record_subtype' => 'birth',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_gro_' . rand(1000, 9999),
                'person_id' => 'findmypast_gro_birth_' . rand(10000, 99999),
                'confidence_score' => 0.85,
                'data' => [
                    'type' => 'GRO Birth Index',
                    'name' => $person->fullname(),
                    'quarter' => $quarter,
                    'year' => $person->birthday->year,
                    'district' => $this->getRandomDistrict(),
                    'volume' => rand(1, 27),
                    'page' => rand(1, 999),
                ],
            ];
        }

        // GRO Death Index
        if ($person->deathday && $person->deathday->year >= 1837) {
            $quarter = $this->getQuarter($person->deathday);
            $matches[] = [
                'record_type' => 'gro_index',
                'record_subtype' => 'death',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_gro_' . rand(1000, 9999),
                'person_id' => 'findmypast_gro_death_' . rand(10000, 99999),
                'confidence_score' => 0.85,
                'data' => [
                    'type' => 'GRO Death Index',
                    'name' => $person->fullname(),
                    'quarter' => $quarter,
                    'year' => $person->deathday->year,
                    'district' => $this->getRandomDistrict(),
                    'volume' => rand(1, 27),
                    'page' => rand(1, 999),
                    'age_at_death' => $this->calculateAge($person),
                ],
            ];
        }

        return $matches;
    }

    /**
     * Search military records
     */
    private function searchMilitaryRecords(Person $person): array
    {
        // Only search if person would have been of military age and male
        if (!$person->birthday || $person->sex !== Person::GENDER_MALE) {
            return [];
        }

        $matches = [];
        $birthYear = $person->birthday->year;

        // WWI (1914-1918)
        if ($birthYear >= 1880 && $birthYear <= 1900) {
            $matches[] = [
                'record_type' => 'military',
                'record_subtype' => 'service',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_military_ww1',
                'person_id' => 'findmypast_ww1_' . rand(10000, 99999),
                'confidence_score' => 0.60,
                'data' => [
                    'type' => 'WWI Service Record',
                    'name' => $person->fullname(),
                    'service_number' => rand(100000, 999999),
                    'regiment' => $this->getRandomRegiment(),
                    'rank' => $this->getRandomRank(),
                    'enlistment_date' => '191' . rand(4, 8) . '-' . sprintf('%02d', rand(1, 12)) . '-' . sprintf('%02d', rand(1, 28)),
                ],
            ];
        }

        // WWII (1939-1945)
        if ($birthYear >= 1900 && $birthYear <= 1927) {
            $matches[] = [
                'record_type' => 'military',
                'record_subtype' => 'service',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_military_ww2',
                'person_id' => 'findmypast_ww2_' . rand(10000, 99999),
                'confidence_score' => 0.60,
                'data' => [
                    'type' => 'WWII Service Record',
                    'name' => $person->fullname(),
                    'service_number' => rand(1000000, 9999999),
                    'regiment' => $this->getRandomRegiment(),
                    'rank' => $this->getRandomRank(),
                    'enlistment_date' => '194' . rand(0, 5) . '-' . sprintf('%02d', rand(1, 12)) . '-' . sprintf('%02d', rand(1, 28)),
                ],
            ];
        }

        return $matches;
    }

    /**
     * Search probate records
     */
    private function searchProbateRecords(Person $person): array
    {
        if (!$person->deathday) {
            return [];
        }

        return [
            [
                'record_type' => 'probate',
                'record_subtype' => 'will',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_probate_' . rand(1000, 9999),
                'person_id' => 'findmypast_probate_' . rand(10000, 99999),
                'confidence_score' => 0.65,
                'data' => [
                    'type' => 'Probate Record',
                    'name' => $person->fullname(),
                    'probate_date' => $person->deathday->addMonths(rand(6, 24))->format('Y-m-d'),
                    'death_date' => $person->deathday->format('Y-m-d'),
                    'probate_court' => $this->getRandomProbateCourt(),
                    'estate_value' => '£' . number_format(rand(100, 50000)),
                ],
            ],
        ];
    }

    /**
     * Search poor law and workhouse records
     */
    private function searchPoorLawRecords(Person $person): array
    {
        // Low probability match - only for certain periods
        if (rand(1, 100) > 30) {
            return [];
        }

        return [
            [
                'record_type' => 'poor_law',
                'record_subtype' => 'workhouse',
                'source' => 'findmypast',
                'tree_id' => 'findmypast_poorlaw_' . rand(1000, 9999),
                'person_id' => 'findmypast_poorlaw_' . rand(10000, 99999),
                'confidence_score' => 0.50,
                'data' => [
                    'type' => 'Workhouse Record',
                    'name' => $person->fullname(),
                    'workhouse' => $this->getRandomWorkhouse(),
                    'union' => $this->getRandomUnion(),
                    'admission_date' => now()->subYears(rand(50, 150))->format('Y-m-d'),
                ],
            ],
        ];
    }

    /**
     * Calculate confidence scores for different record types
     */
    private function calculateNewspaperConfidence(Person $person): float
    {
        $score = 0.60; // Base score for newspapers

        if ($person->deathday) {
            $score += 0.15; // Higher confidence for obituaries
        }

        return min(0.95, $score);
    }

    private function calculateParishConfidence(Person $person): float
    {
        $score = 0.70; // Base score for parish records

        if ($person->birthday && $person->birthday->year < 1900) {
            $score += 0.10; // Higher confidence for older records
        }

        return min(0.95, $score);
    }

    private function calculateCensusConfidence(Person $person, int $censusYear): float
    {
        $score = 0.75; // Base score for census

        if ($person->birthday) {
            $ageAtCensus = $censusYear - $person->birthday->year;
            if ($ageAtCensus >= 0 && $ageAtCensus <= 100) {
                $score += 0.15; // Age makes sense
            }
        }

        return min(0.95, $score);
    }

    /**
     * Helper methods for generating realistic data
     */
    private function calculateAge(Person $person): ?int
    {
        if (!$person->birthday || !$person->deathday) {
            return null;
        }

        return $person->birthday->diffInYears($person->deathday);
    }

    private function getFatherName(Person $person): string
    {
        $family = $person->childInFamily;
        return $family?->husband?->fullname() ?? 'Unknown';
    }

    private function getMotherName(Person $person): string
    {
        $family = $person->childInFamily;
        return $family?->wife?->fullname() ?? 'Unknown';
    }

    private function getQuarter(\DateTime $date): string
    {
        $month = (int) $date->format('m');
        return match (true) {
            $month <= 3 => 'Jan-Feb-Mar',
            $month <= 6 => 'Apr-May-Jun',
            $month <= 9 => 'Jul-Aug-Sep',
            default => 'Oct-Nov-Dec',
        };
    }

    private function getRandomNewspaper(): string
    {
        $newspapers = [
            'The Times',
            'The Daily Telegraph',
            'Manchester Guardian',
            'The Scotsman',
            'Birmingham Daily Post',
            'Leeds Mercury',
            'Western Mail',
            'Belfast News-Letter',
            'Yorkshire Post',
            'Liverpool Echo',
        ];

        return $newspapers[array_rand($newspapers)];
    }

    private function getRandomParish(): string
    {
        $parishes = [
            'St. Mary\'s',
            'St. John\'s',
            'St. Peter\'s',
            'Holy Trinity',
            'St. James\'s',
            'All Saints',
            'St. Andrew\'s',
            'Christ Church',
        ];

        return $parishes[array_rand($parishes)];
    }

    private function getRandomPlace(): string
    {
        $places = [
            'London', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds',
            'Edinburgh', 'Glasgow', 'Cardiff', 'Belfast', 'Bristol',
        ];

        return $places[array_rand($places)];
    }

    private function getRandomCounty(): string
    {
        $counties = [
            'Yorkshire', 'Lancashire', 'Middlesex', 'Kent', 'Surrey',
            'Warwickshire', 'Staffordshire', 'Derbyshire', 'Cheshire',
        ];

        return $counties[array_rand($counties)];
    }

    private function getRandomOccupation(): string
    {
        $occupations = [
            'Agricultural Labourer', 'Coal Miner', 'Cotton Weaver', 'Railway Porter',
            'Domestic Servant', 'Blacksmith', 'Carpenter', 'Clerk', 'Shopkeeper',
        ];

        return $occupations[array_rand($occupations)];
    }

    private function getRandomAddress(): string
    {
        return rand(1, 200) . ' ' . ['High Street', 'Church Road', 'Station Road', 'Market Place'][array_rand(['High Street', 'Church Road', 'Station Road', 'Market Place'])];
    }

    private function getRandomConstituency(): string
    {
        $constituencies = [
            'City of London', 'Westminster', 'Manchester South', 'Birmingham Central',
            'Leeds East', 'Liverpool Scotland', 'Edinburgh South',
        ];

        return $constituencies[array_rand($constituencies)];
    }

    private function getRandomDistrict(): string
    {
        $districts = [
            'Kensington', 'Westminster', 'Holborn', 'Shoreditch', 'Bethnal Green',
            'Salford', 'Chorlton', 'Manchester', 'Birmingham', 'Leeds',
        ];

        return $districts[array_rand($districts)];
    }

    private function getRandomRegiment(): string
    {
        $regiments = [
            'Royal Fusiliers', 'Kings Own Yorkshire Light Infantry', 'Lancashire Fusiliers',
            'Royal Scots', 'Royal Engineers', 'Royal Artillery', 'Coldstream Guards',
        ];

        return $regiments[array_rand($regiments)];
    }

    private function getRandomRank(): string
    {
        $ranks = ['Private', 'Lance Corporal', 'Corporal', 'Sergeant', 'Lieutenant', 'Captain'];
        return $ranks[array_rand($ranks)];
    }

    private function getRandomProbateCourt(): string
    {
        $courts = [
            'Principal Probate Registry',
            'District Probate Registry - Birmingham',
            'District Probate Registry - Manchester',
            'District Probate Registry - Leeds',
        ];

        return $courts[array_rand($courts)];
    }

    private function getRandomWorkhouse(): string
    {
        $workhouses = [
            'St Pancras Workhouse',
            'Bethnal Green Workhouse',
            'Manchester Union Workhouse',
            'Birmingham Workhouse',
        ];

        return $workhouses[array_rand($workhouses)];
    }

    private function getRandomUnion(): string
    {
        $unions = [
            'Holborn Union',
            'Whitechapel Union',
            'Manchester Union',
            'Birmingham Union',
        ];

        return $unions[array_rand($unions)];
    }

    private function generateObituaryExtract(Person $person): string
    {
        return sprintf(
            'Death notice for %s, aged %d, of %s. Funeral arrangements to follow.',
            $person->fullname(),
            $this->calculateAge($person) ?? 'unknown',
            $person->deathplace?->place ?? 'unknown location'
        );
    }
}
