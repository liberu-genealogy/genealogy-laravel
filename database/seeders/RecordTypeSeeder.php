<?php

namespace Database\Seeders;

use App\Models\RecordType;
use Illuminate\Database\Seeder;

class RecordTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recordTypes = [
            // Vital Records
            [
                'name' => 'Birth Certificate',
                'slug' => 'birth-certificate',
                'category' => 'vital',
                'description' => 'Official birth registration documents',
                'icon' => 'heroicon-o-cake',
                'color' => 'success',
                'metadata_schema' => [
                    'fields' => [
                        'registration_district' => 'string',
                        'registration_number' => 'string',
                        'quarter' => 'string',
                        'year' => 'integer',
                    ],
                ],
                'sort_order' => 10,
            ],
            [
                'name' => 'Marriage Certificate',
                'slug' => 'marriage-certificate',
                'category' => 'vital',
                'description' => 'Official marriage registration documents',
                'icon' => 'heroicon-o-heart',
                'color' => 'danger',
                'metadata_schema' => [
                    'fields' => [
                        'registration_district' => 'string',
                        'registration_number' => 'string',
                        'quarter' => 'string',
                        'year' => 'integer',
                    ],
                ],
                'sort_order' => 20,
            ],
            [
                'name' => 'Death Certificate',
                'slug' => 'death-certificate',
                'category' => 'vital',
                'description' => 'Official death registration documents',
                'icon' => 'heroicon-o-x-circle',
                'color' => 'gray',
                'metadata_schema' => [
                    'fields' => [
                        'registration_district' => 'string',
                        'registration_number' => 'string',
                        'quarter' => 'string',
                        'year' => 'integer',
                        'cause_of_death' => 'text',
                    ],
                ],
                'sort_order' => 30,
            ],

            // Census Records
            [
                'name' => 'Census Record',
                'slug' => 'census-record',
                'category' => 'census',
                'description' => 'Population census enumeration records',
                'icon' => 'heroicon-o-users',
                'color' => 'primary',
                'metadata_schema' => [
                    'fields' => [
                        'census_year' => 'integer',
                        'county' => 'string',
                        'district' => 'string',
                        'sub_district' => 'string',
                        'enumeration_district' => 'string',
                        'piece_number' => 'string',
                        'folio_number' => 'string',
                        'page_number' => 'string',
                        'schedule_number' => 'string',
                        'household_number' => 'string',
                        'address' => 'text',
                    ],
                ],
                'sort_order' => 100,
            ],
            [
                'name' => '1939 Register',
                'slug' => '1939-register',
                'category' => 'census',
                'description' => 'UK National Registration Act 1939 (wartime census substitute)',
                'icon' => 'heroicon-o-identification',
                'color' => 'warning',
                'metadata_schema' => [
                    'fields' => [
                        'schedule_number' => 'string',
                        'address' => 'text',
                        'occupation' => 'string',
                        'employer' => 'string',
                        'marital_status' => 'string',
                    ],
                ],
                'sort_order' => 110,
            ],

            // Newspaper Records
            [
                'name' => 'Newspaper Article',
                'slug' => 'newspaper-article',
                'category' => 'newspaper',
                'description' => 'Newspaper articles, announcements, and stories',
                'icon' => 'heroicon-o-newspaper',
                'color' => 'info',
                'metadata_schema' => [
                    'fields' => [
                        'publication_name' => 'string',
                        'publication_date' => 'date',
                        'page_number' => 'string',
                        'column' => 'string',
                        'article_type' => 'string', // obituary, marriage, birth, notice, etc.
                        'headline' => 'string',
                    ],
                ],
                'sort_order' => 200,
            ],
            [
                'name' => 'Newspaper Obituary',
                'slug' => 'newspaper-obituary',
                'category' => 'newspaper',
                'description' => 'Newspaper death notices and obituaries',
                'icon' => 'heroicon-o-newspaper',
                'color' => 'gray',
                'metadata_schema' => [
                    'fields' => [
                        'publication_name' => 'string',
                        'publication_date' => 'date',
                        'page_number' => 'string',
                        'deceased_name' => 'string',
                        'death_date' => 'date',
                        'funeral_details' => 'text',
                    ],
                ],
                'sort_order' => 210,
            ],
            [
                'name' => 'Newspaper Notice',
                'slug' => 'newspaper-notice',
                'category' => 'newspaper',
                'description' => 'Marriage announcements, birth notices, and other announcements',
                'icon' => 'heroicon-o-megaphone',
                'color' => 'success',
                'metadata_schema' => [
                    'fields' => [
                        'publication_name' => 'string',
                        'publication_date' => 'date',
                        'page_number' => 'string',
                        'notice_type' => 'string', // marriage, birth, engagement, etc.
                    ],
                ],
                'sort_order' => 220,
            ],

            // Parish Records
            [
                'name' => 'Parish Baptism',
                'slug' => 'parish-baptism',
                'category' => 'parish',
                'description' => 'Church baptism and christening records',
                'icon' => 'heroicon-o-building-library',
                'color' => 'primary',
                'metadata_schema' => [
                    'fields' => [
                        'parish_name' => 'string',
                        'church_name' => 'string',
                        'diocese' => 'string',
                        'baptism_date' => 'date',
                        'birth_date' => 'date',
                        'fathers_name' => 'string',
                        'mothers_name' => 'string',
                        'mothers_maiden_name' => 'string',
                        'abode' => 'string',
                        'fathers_occupation' => 'string',
                    ],
                ],
                'sort_order' => 300,
            ],
            [
                'name' => 'Parish Marriage',
                'slug' => 'parish-marriage',
                'category' => 'parish',
                'description' => 'Church marriage and banns records',
                'icon' => 'heroicon-o-heart',
                'color' => 'danger',
                'metadata_schema' => [
                    'fields' => [
                        'parish_name' => 'string',
                        'church_name' => 'string',
                        'diocese' => 'string',
                        'marriage_date' => 'date',
                        'groom_name' => 'string',
                        'groom_age' => 'string',
                        'groom_status' => 'string',
                        'groom_occupation' => 'string',
                        'groom_residence' => 'string',
                        'groom_father' => 'string',
                        'bride_name' => 'string',
                        'bride_age' => 'string',
                        'bride_status' => 'string',
                        'bride_occupation' => 'string',
                        'bride_residence' => 'string',
                        'bride_father' => 'string',
                        'witnesses' => 'text',
                    ],
                ],
                'sort_order' => 310,
            ],
            [
                'name' => 'Parish Burial',
                'slug' => 'parish-burial',
                'category' => 'parish',
                'description' => 'Church burial records',
                'icon' => 'heroicon-o-building-library',
                'color' => 'gray',
                'metadata_schema' => [
                    'fields' => [
                        'parish_name' => 'string',
                        'church_name' => 'string',
                        'diocese' => 'string',
                        'burial_date' => 'date',
                        'death_date' => 'date',
                        'age' => 'string',
                        'abode' => 'string',
                        'occupation' => 'string',
                    ],
                ],
                'sort_order' => 320,
            ],

            // Electoral Records
            [
                'name' => 'Electoral Register',
                'slug' => 'electoral-register',
                'category' => 'electoral',
                'description' => 'Voter registration and electoral roll records',
                'icon' => 'heroicon-o-clipboard-document-check',
                'color' => 'warning',
                'metadata_schema' => [
                    'fields' => [
                        'register_year' => 'integer',
                        'constituency' => 'string',
                        'polling_district' => 'string',
                        'address' => 'text',
                        'qualification' => 'string',
                    ],
                ],
                'sort_order' => 400,
            ],

            // Military Records
            [
                'name' => 'Military Service Record',
                'slug' => 'military-service-record',
                'category' => 'military',
                'description' => 'Military service, attestation, and discharge papers',
                'icon' => 'heroicon-o-shield-check',
                'color' => 'danger',
                'metadata_schema' => [
                    'fields' => [
                        'service_number' => 'string',
                        'regiment' => 'string',
                        'battalion' => 'string',
                        'rank' => 'string',
                        'enlistment_date' => 'date',
                        'discharge_date' => 'date',
                        'medals' => 'text',
                        'campaigns' => 'text',
                    ],
                ],
                'sort_order' => 500,
            ],
            [
                'name' => 'War Grave Record',
                'slug' => 'war-grave-record',
                'category' => 'military',
                'description' => 'Commonwealth War Graves Commission and military cemetery records',
                'icon' => 'heroicon-o-map-pin',
                'color' => 'gray',
                'metadata_schema' => [
                    'fields' => [
                        'cemetery_name' => 'string',
                        'grave_reference' => 'string',
                        'regiment' => 'string',
                        'rank' => 'string',
                        'death_date' => 'date',
                        'age' => 'integer',
                    ],
                ],
                'sort_order' => 510,
            ],

            // Probate Records
            [
                'name' => 'Will',
                'slug' => 'will',
                'category' => 'probate',
                'description' => 'Last will and testament documents',
                'icon' => 'heroicon-o-document-text',
                'color' => 'warning',
                'metadata_schema' => [
                    'fields' => [
                        'probate_date' => 'date',
                        'probate_court' => 'string',
                        'estate_value' => 'string',
                        'executors' => 'text',
                        'beneficiaries' => 'text',
                    ],
                ],
                'sort_order' => 600,
            ],
            [
                'name' => 'Probate Record',
                'slug' => 'probate-record',
                'category' => 'probate',
                'description' => 'Probate grant and administration records',
                'icon' => 'heroicon-o-scale',
                'color' => 'info',
                'metadata_schema' => [
                    'fields' => [
                        'probate_date' => 'date',
                        'probate_court' => 'string',
                        'grant_type' => 'string',
                        'estate_value' => 'string',
                    ],
                ],
                'sort_order' => 610,
            ],

            // GRO Index (UK-specific)
            [
                'name' => 'GRO Birth Index',
                'slug' => 'gro-birth-index',
                'category' => 'gro_index',
                'description' => 'General Register Office birth index entries',
                'icon' => 'heroicon-o-book-open',
                'color' => 'success',
                'metadata_schema' => [
                    'fields' => [
                        'quarter' => 'string',
                        'year' => 'integer',
                        'district' => 'string',
                        'volume' => 'string',
                        'page' => 'string',
                        'mothers_maiden_name' => 'string',
                    ],
                ],
                'sort_order' => 700,
            ],
            [
                'name' => 'GRO Marriage Index',
                'slug' => 'gro-marriage-index',
                'category' => 'gro_index',
                'description' => 'General Register Office marriage index entries',
                'icon' => 'heroicon-o-book-open',
                'color' => 'danger',
                'metadata_schema' => [
                    'fields' => [
                        'quarter' => 'string',
                        'year' => 'integer',
                        'district' => 'string',
                        'volume' => 'string',
                        'page' => 'string',
                        'spouse_surname' => 'string',
                    ],
                ],
                'sort_order' => 710,
            ],
            [
                'name' => 'GRO Death Index',
                'slug' => 'gro-death-index',
                'category' => 'gro_index',
                'description' => 'General Register Office death index entries',
                'icon' => 'heroicon-o-book-open',
                'color' => 'gray',
                'metadata_schema' => [
                    'fields' => [
                        'quarter' => 'string',
                        'year' => 'integer',
                        'district' => 'string',
                        'volume' => 'string',
                        'page' => 'string',
                        'age_at_death' => 'integer',
                    ],
                ],
                'sort_order' => 720,
            ],

            // Immigration Records
            [
                'name' => 'Passenger List',
                'slug' => 'passenger-list',
                'category' => 'immigration',
                'description' => 'Ship passenger lists and immigration records',
                'icon' => 'heroicon-o-globe-alt',
                'color' => 'primary',
                'metadata_schema' => [
                    'fields' => [
                        'ship_name' => 'string',
                        'departure_port' => 'string',
                        'arrival_port' => 'string',
                        'departure_date' => 'date',
                        'arrival_date' => 'date',
                        'age' => 'integer',
                        'occupation' => 'string',
                        'nationality' => 'string',
                    ],
                ],
                'sort_order' => 800,
            ],

            // Land and Property
            [
                'name' => 'Land Record',
                'slug' => 'land-record',
                'category' => 'land',
                'description' => 'Land ownership, deeds, and property records',
                'icon' => 'heroicon-o-map',
                'color' => 'success',
                'metadata_schema' => [
                    'fields' => [
                        'property_description' => 'text',
                        'transaction_date' => 'date',
                        'transaction_type' => 'string',
                        'acreage' => 'string',
                        'value' => 'string',
                    ],
                ],
                'sort_order' => 900,
            ],

            // Poor Law and Workhouse (UK-specific FindMyPast feature)
            [
                'name' => 'Workhouse Record',
                'slug' => 'workhouse-record',
                'category' => 'poor_law',
                'description' => 'Workhouse admission and poor law union records',
                'icon' => 'heroicon-o-home',
                'color' => 'gray',
                'metadata_schema' => [
                    'fields' => [
                        'workhouse_name' => 'string',
                        'union' => 'string',
                        'admission_date' => 'date',
                        'discharge_date' => 'date',
                        'reason' => 'text',
                        'age' => 'integer',
                        'occupation' => 'string',
                    ],
                ],
                'sort_order' => 1000,
            ],

            // Court Records
            [
                'name' => 'Court Record',
                'slug' => 'court-record',
                'category' => 'court',
                'description' => 'Court proceedings, criminal records, and legal documents',
                'icon' => 'heroicon-o-building-office',
                'color' => 'danger',
                'metadata_schema' => [
                    'fields' => [
                        'court_name' => 'string',
                        'case_number' => 'string',
                        'hearing_date' => 'date',
                        'case_type' => 'string',
                        'verdict' => 'string',
                        'sentence' => 'text',
                    ],
                ],
                'sort_order' => 1100,
            ],
        ];

        foreach ($recordTypes as $recordType) {
            RecordType::create($recordType);
        }
    }
}
