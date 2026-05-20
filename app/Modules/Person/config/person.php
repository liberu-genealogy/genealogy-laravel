<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Person Module Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings specific to the Person module.
    |
    */

    'display' => [
        /*
        |--------------------------------------------------------------------------
        | Display Settings
        |--------------------------------------------------------------------------
        */
        'name_format' => '{givn} {surn}',
        'show_living_indicator' => true,
        'show_age_at_death' => true,
        'default_avatar' => 'default-person.png',
    ],

    'validation' => [
        /*
        |--------------------------------------------------------------------------
        | Validation Rules
        |--------------------------------------------------------------------------
        */
        'required_fields' => ['givn', 'surn'],
        'max_name_length' => 100,
        'allowed_sex_values' => ['M', 'F', 'U'],
        'min_birth_year' => 1000,
        'max_birth_year' => null, // null = current year
    ],

    'search' => [
        /*
        |--------------------------------------------------------------------------
        | Search Configuration
        |--------------------------------------------------------------------------
        */
        'enable_fuzzy_search' => true,
        'search_fields' => ['givn', 'surn', 'name', 'description'],
        'min_search_length' => 2,
        'max_results' => 100,
    ],

    'export' => [
        /*
        |--------------------------------------------------------------------------
        | Export Settings
        |--------------------------------------------------------------------------
        */
        'formats' => ['json', 'csv', 'gedcom'],
        'include_events' => true,
        'include_relationships' => true,
        'include_private_data' => false,
    ],

    'privacy' => [
        /*
        |--------------------------------------------------------------------------
        | Privacy Settings
        |--------------------------------------------------------------------------
        */
        'hide_living_persons' => false,
        'living_threshold_years' => 100,
        'require_auth_for_details' => false,
        'mask_private_events' => true,
    ],

    'relationships' => [
        /*
        |--------------------------------------------------------------------------
        | Relationship Settings
        |--------------------------------------------------------------------------
        */
        'max_relationship_depth' => 10,
        'enable_relationship_calculator' => true,
        'show_relationship_paths' => true,
    ],

    'events' => [
        /*
        |--------------------------------------------------------------------------
        | Event Configuration
        |--------------------------------------------------------------------------
        */
        'default_events' => ['BIRT', 'DEAT', 'MARR', 'BURI'],
        'allow_custom_events' => true,
        'require_event_dates' => false,
        'validate_event_dates' => true,
    ],

    'cache' => [
        /*
        |--------------------------------------------------------------------------
        | Cache Settings
        |--------------------------------------------------------------------------
        */
        'enable_person_cache' => true,
        'cache_ttl' => 3600, // 1 hour
        'cache_relationships' => true,
        'cache_statistics' => true,
    ],
];