<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Genealogy Configuration
    |--------------------------------------------------------------------------
    |
    | Core configuration settings for the genealogy application.
    |
    */

    'tree' => [
        /*
        |--------------------------------------------------------------------------
        | Default Tree Settings
        |--------------------------------------------------------------------------
        */
        'default_generations' => 4,
        'max_generations' => 10,
        'enable_living_filter' => true,
        'privacy_years' => 100, // Years after which records become public
    ],

    'gedcom' => [
        /*
        |--------------------------------------------------------------------------
        | GEDCOM Import/Export Settings
        |--------------------------------------------------------------------------
        */
        'version' => '5.5.1',
        'charset' => 'UTF-8',
        'max_file_size' => '10M',
        'chunk_size' => 1000, // Records to process per chunk
    ],

    'person' => [
        /*
        |--------------------------------------------------------------------------
        | Person Model Settings
        |--------------------------------------------------------------------------
        */
        'name_format' => '{givn} {surn}',
        'display_format' => '{name} ({birth_year}-{death_year})',
        'required_fields' => ['givn', 'surn'],
        'optional_fields' => ['sex', 'birthday', 'deathday', 'description'],
    ],

    'family' => [
        /*
        |--------------------------------------------------------------------------
        | Family Model Settings
        |--------------------------------------------------------------------------
        */
        'allow_same_sex' => true,
        'require_marriage_date' => false,
        'max_children' => null,
    ],

    'events' => [
        /*
        |--------------------------------------------------------------------------
        | Event Types
        |--------------------------------------------------------------------------
        */
        'types' => [
            'BIRT' => 'Birth',
            'DEAT' => 'Death',
            'MARR' => 'Marriage',
            'DIV' => 'Divorce',
            'BURI' => 'Burial',
            'BAPM' => 'Baptism',
            'CONF' => 'Confirmation',
            'GRAD' => 'Graduation',
            'OCCU' => 'Occupation',
            'RESI' => 'Residence',
            'EMIG' => 'Emigration',
            'IMMI' => 'Immigration',
        ],
        'date_formats' => [
            'Y-m-d',
            'd M Y',
            'M Y',
            'Y',
        ],
    ],

    'places' => [
        /*
        |--------------------------------------------------------------------------
        | Place Settings
        |--------------------------------------------------------------------------
        */
        'hierarchy_separator' => ', ',
        'enable_coordinates' => true,
        'default_country' => null,
    ],

    'privacy' => [
        /*
        |--------------------------------------------------------------------------
        | Privacy Settings
        |--------------------------------------------------------------------------
        */
        'hide_living' => true,
        'living_years_threshold' => 100,
        'require_authentication' => false,
        'admin_bypass' => true,
    ],

    'search' => [
        /*
        |--------------------------------------------------------------------------
        | Search Configuration
        |--------------------------------------------------------------------------
        */
        'enable_full_text' => true,
        'min_search_length' => 2,
        'max_results' => 100,
        'fuzzy_matching' => true,
    ],

    'cache' => [
        /*
        |--------------------------------------------------------------------------
        | Cache Settings
        |--------------------------------------------------------------------------
        */
        'enable' => true,
        'ttl' => 3600, // 1 hour
        'tags' => ['genealogy', 'persons', 'families'],
    ],
];