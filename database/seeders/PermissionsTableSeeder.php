<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'view-any Activation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:55',
                'updated_at' => '2024-08-20 07:25:55',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'view-any Activation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:55',
                'updated_at' => '2024-08-20 07:25:55',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'view Activation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:55',
                'updated_at' => '2024-08-20 07:25:55',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'view Activation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:55',
                'updated_at' => '2024-08-20 07:25:55',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'create Activation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:56',
                'updated_at' => '2024-08-20 07:25:56',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'create Activation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:56',
                'updated_at' => '2024-08-20 07:25:56',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'update Activation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:56',
                'updated_at' => '2024-08-20 07:25:56',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'update Activation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:56',
                'updated_at' => '2024-08-20 07:25:56',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'delete Activation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:56',
                'updated_at' => '2024-08-20 07:25:56',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'delete Activation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:57',
                'updated_at' => '2024-08-20 07:25:57',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'restore Activation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:57',
                'updated_at' => '2024-08-20 07:25:57',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'restore Activation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:57',
                'updated_at' => '2024-08-20 07:25:57',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'force-delete Activation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:57',
                'updated_at' => '2024-08-20 07:25:57',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'force-delete Activation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:58',
                'updated_at' => '2024-08-20 07:25:58',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'replicate Activation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:58',
                'updated_at' => '2024-08-20 07:25:58',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'replicate Activation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:58',
                'updated_at' => '2024-08-20 07:25:58',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'reorder Activation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:58',
                'updated_at' => '2024-08-20 07:25:58',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'reorder Activation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:58',
                'updated_at' => '2024-08-20 07:25:58',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'view-any Addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:59',
                'updated_at' => '2024-08-20 07:25:59',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'view-any Addr',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:59',
                'updated_at' => '2024-08-20 07:25:59',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'view Addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:59',
                'updated_at' => '2024-08-20 07:25:59',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'view Addr',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:59',
                'updated_at' => '2024-08-20 07:25:59',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'create Addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:25:59',
                'updated_at' => '2024-08-20 07:25:59',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'create Addr',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:25:59',
                'updated_at' => '2024-08-20 07:25:59',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'update Addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:00',
                'updated_at' => '2024-08-20 07:26:00',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'update Addr',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:00',
                'updated_at' => '2024-08-20 07:26:00',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'delete Addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:00',
                'updated_at' => '2024-08-20 07:26:00',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'delete Addr',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:00',
                'updated_at' => '2024-08-20 07:26:00',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'restore Addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:00',
                'updated_at' => '2024-08-20 07:26:00',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'restore Addr',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:00',
                'updated_at' => '2024-08-20 07:26:00',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'force-delete Addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:01',
                'updated_at' => '2024-08-20 07:26:01',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'force-delete Addr',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:01',
                'updated_at' => '2024-08-20 07:26:01',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'replicate Addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:01',
                'updated_at' => '2024-08-20 07:26:01',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'replicate Addr',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:01',
                'updated_at' => '2024-08-20 07:26:01',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'reorder Addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:01',
                'updated_at' => '2024-08-20 07:26:01',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'reorder Addr',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:01',
                'updated_at' => '2024-08-20 07:26:01',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'view-any Author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:01',
                'updated_at' => '2024-08-20 07:26:01',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'view-any Author',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:02',
                'updated_at' => '2024-08-20 07:26:02',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'view Author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:02',
                'updated_at' => '2024-08-20 07:26:02',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'view Author',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:02',
                'updated_at' => '2024-08-20 07:26:02',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'create Author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:02',
                'updated_at' => '2024-08-20 07:26:02',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'create Author',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:02',
                'updated_at' => '2024-08-20 07:26:02',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'update Author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:02',
                'updated_at' => '2024-08-20 07:26:02',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'update Author',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:02',
                'updated_at' => '2024-08-20 07:26:02',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'delete Author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:03',
                'updated_at' => '2024-08-20 07:26:03',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'delete Author',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:03',
                'updated_at' => '2024-08-20 07:26:03',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'restore Author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:03',
                'updated_at' => '2024-08-20 07:26:03',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'restore Author',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:03',
                'updated_at' => '2024-08-20 07:26:03',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'force-delete Author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:03',
                'updated_at' => '2024-08-20 07:26:03',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'force-delete Author',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:03',
                'updated_at' => '2024-08-20 07:26:03',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'replicate Author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:03',
                'updated_at' => '2024-08-20 07:26:03',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'replicate Author',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:04',
                'updated_at' => '2024-08-20 07:26:04',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'reorder Author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:04',
                'updated_at' => '2024-08-20 07:26:04',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'reorder Author',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:04',
                'updated_at' => '2024-08-20 07:26:04',
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'view-any BatchData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:04',
                'updated_at' => '2024-08-20 07:26:04',
            ),
            55 => 
            array (
                'id' => 56,
                'name' => 'view-any BatchData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:04',
                'updated_at' => '2024-08-20 07:26:04',
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'view BatchData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:04',
                'updated_at' => '2024-08-20 07:26:04',
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'view BatchData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:05',
                'updated_at' => '2024-08-20 07:26:05',
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'create BatchData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:05',
                'updated_at' => '2024-08-20 07:26:05',
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'create BatchData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:05',
                'updated_at' => '2024-08-20 07:26:05',
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'update BatchData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:05',
                'updated_at' => '2024-08-20 07:26:05',
            ),
            61 => 
            array (
                'id' => 62,
                'name' => 'update BatchData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:05',
                'updated_at' => '2024-08-20 07:26:05',
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'delete BatchData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:05',
                'updated_at' => '2024-08-20 07:26:05',
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'delete BatchData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:06',
                'updated_at' => '2024-08-20 07:26:06',
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'restore BatchData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:06',
                'updated_at' => '2024-08-20 07:26:06',
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'restore BatchData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:06',
                'updated_at' => '2024-08-20 07:26:06',
            ),
            66 => 
            array (
                'id' => 67,
                'name' => 'force-delete BatchData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:06',
                'updated_at' => '2024-08-20 07:26:06',
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'force-delete BatchData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:07',
                'updated_at' => '2024-08-20 07:26:07',
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'replicate BatchData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:07',
                'updated_at' => '2024-08-20 07:26:07',
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'replicate BatchData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:07',
                'updated_at' => '2024-08-20 07:26:07',
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'reorder BatchData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:07',
                'updated_at' => '2024-08-20 07:26:07',
            ),
            71 => 
            array (
                'id' => 72,
                'name' => 'reorder BatchData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:07',
                'updated_at' => '2024-08-20 07:26:07',
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'view-any Category',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:07',
                'updated_at' => '2024-08-20 07:26:07',
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'view-any Category',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:08',
                'updated_at' => '2024-08-20 07:26:08',
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'view Category',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:08',
                'updated_at' => '2024-08-20 07:26:08',
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'view Category',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:08',
                'updated_at' => '2024-08-20 07:26:08',
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'create Category',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:08',
                'updated_at' => '2024-08-20 07:26:08',
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'create Category',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:08',
                'updated_at' => '2024-08-20 07:26:08',
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'update Category',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:08',
                'updated_at' => '2024-08-20 07:26:08',
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'update Category',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:09',
                'updated_at' => '2024-08-20 07:26:09',
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'delete Category',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:09',
                'updated_at' => '2024-08-20 07:26:09',
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'delete Category',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:10',
                'updated_at' => '2024-08-20 07:26:10',
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'restore Category',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:10',
                'updated_at' => '2024-08-20 07:26:10',
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'restore Category',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:10',
                'updated_at' => '2024-08-20 07:26:10',
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'force-delete Category',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:10',
                'updated_at' => '2024-08-20 07:26:10',
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'force-delete Category',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:11',
                'updated_at' => '2024-08-20 07:26:11',
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'replicate Category',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:11',
                'updated_at' => '2024-08-20 07:26:11',
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'replicate Category',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:11',
                'updated_at' => '2024-08-20 07:26:11',
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'reorder Category',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:11',
                'updated_at' => '2024-08-20 07:26:11',
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'reorder Category',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:11',
                'updated_at' => '2024-08-20 07:26:11',
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'view-any Chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:11',
                'updated_at' => '2024-08-20 07:26:11',
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'view-any Chan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:12',
                'updated_at' => '2024-08-20 07:26:12',
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'view Chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:12',
                'updated_at' => '2024-08-20 07:26:12',
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'view Chan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:12',
                'updated_at' => '2024-08-20 07:26:12',
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'create Chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:12',
                'updated_at' => '2024-08-20 07:26:12',
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'create Chan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:12',
                'updated_at' => '2024-08-20 07:26:12',
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'update Chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:13',
                'updated_at' => '2024-08-20 07:26:13',
            ),
            97 => 
            array (
                'id' => 98,
                'name' => 'update Chan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:13',
                'updated_at' => '2024-08-20 07:26:13',
            ),
            98 => 
            array (
                'id' => 99,
                'name' => 'delete Chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:13',
                'updated_at' => '2024-08-20 07:26:13',
            ),
            99 => 
            array (
                'id' => 100,
                'name' => 'delete Chan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:13',
                'updated_at' => '2024-08-20 07:26:13',
            ),
            100 => 
            array (
                'id' => 101,
                'name' => 'restore Chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:13',
                'updated_at' => '2024-08-20 07:26:13',
            ),
            101 => 
            array (
                'id' => 102,
                'name' => 'restore Chan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:14',
                'updated_at' => '2024-08-20 07:26:14',
            ),
            102 => 
            array (
                'id' => 103,
                'name' => 'force-delete Chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:14',
                'updated_at' => '2024-08-20 07:26:14',
            ),
            103 => 
            array (
                'id' => 104,
                'name' => 'force-delete Chan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:14',
                'updated_at' => '2024-08-20 07:26:14',
            ),
            104 => 
            array (
                'id' => 105,
                'name' => 'replicate Chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:14',
                'updated_at' => '2024-08-20 07:26:14',
            ),
            105 => 
            array (
                'id' => 106,
                'name' => 'replicate Chan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:14',
                'updated_at' => '2024-08-20 07:26:14',
            ),
            106 => 
            array (
                'id' => 107,
                'name' => 'reorder Chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:14',
                'updated_at' => '2024-08-20 07:26:14',
            ),
            107 => 
            array (
                'id' => 108,
                'name' => 'reorder Chan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:15',
                'updated_at' => '2024-08-20 07:26:15',
            ),
            108 => 
            array (
                'id' => 109,
                'name' => 'view-any Citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:15',
                'updated_at' => '2024-08-20 07:26:15',
            ),
            109 => 
            array (
                'id' => 110,
                'name' => 'view-any Citation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:15',
                'updated_at' => '2024-08-20 07:26:15',
            ),
            110 => 
            array (
                'id' => 111,
                'name' => 'view Citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:15',
                'updated_at' => '2024-08-20 07:26:15',
            ),
            111 => 
            array (
                'id' => 112,
                'name' => 'view Citation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:15',
                'updated_at' => '2024-08-20 07:26:15',
            ),
            112 => 
            array (
                'id' => 113,
                'name' => 'create Citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:16',
                'updated_at' => '2024-08-20 07:26:16',
            ),
            113 => 
            array (
                'id' => 114,
                'name' => 'create Citation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:16',
                'updated_at' => '2024-08-20 07:26:16',
            ),
            114 => 
            array (
                'id' => 115,
                'name' => 'update Citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:16',
                'updated_at' => '2024-08-20 07:26:16',
            ),
            115 => 
            array (
                'id' => 116,
                'name' => 'update Citation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:16',
                'updated_at' => '2024-08-20 07:26:16',
            ),
            116 => 
            array (
                'id' => 117,
                'name' => 'delete Citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:16',
                'updated_at' => '2024-08-20 07:26:16',
            ),
            117 => 
            array (
                'id' => 118,
                'name' => 'delete Citation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:17',
                'updated_at' => '2024-08-20 07:26:17',
            ),
            118 => 
            array (
                'id' => 119,
                'name' => 'restore Citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:17',
                'updated_at' => '2024-08-20 07:26:17',
            ),
            119 => 
            array (
                'id' => 120,
                'name' => 'restore Citation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:17',
                'updated_at' => '2024-08-20 07:26:17',
            ),
            120 => 
            array (
                'id' => 121,
                'name' => 'force-delete Citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:17',
                'updated_at' => '2024-08-20 07:26:17',
            ),
            121 => 
            array (
                'id' => 122,
                'name' => 'force-delete Citation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:17',
                'updated_at' => '2024-08-20 07:26:17',
            ),
            122 => 
            array (
                'id' => 123,
                'name' => 'replicate Citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:18',
                'updated_at' => '2024-08-20 07:26:18',
            ),
            123 => 
            array (
                'id' => 124,
                'name' => 'replicate Citation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:18',
                'updated_at' => '2024-08-20 07:26:18',
            ),
            124 => 
            array (
                'id' => 125,
                'name' => 'reorder Citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:18',
                'updated_at' => '2024-08-20 07:26:18',
            ),
            125 => 
            array (
                'id' => 126,
                'name' => 'reorder Citation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:18',
                'updated_at' => '2024-08-20 07:26:18',
            ),
            126 => 
            array (
                'id' => 127,
                'name' => 'view-any Company',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:18',
                'updated_at' => '2024-08-20 07:26:18',
            ),
            127 => 
            array (
                'id' => 128,
                'name' => 'view-any Company',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:18',
                'updated_at' => '2024-08-20 07:26:18',
            ),
            128 => 
            array (
                'id' => 129,
                'name' => 'view Company',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:18',
                'updated_at' => '2024-08-20 07:26:18',
            ),
            129 => 
            array (
                'id' => 130,
                'name' => 'view Company',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:19',
                'updated_at' => '2024-08-20 07:26:19',
            ),
            130 => 
            array (
                'id' => 131,
                'name' => 'create Company',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:19',
                'updated_at' => '2024-08-20 07:26:19',
            ),
            131 => 
            array (
                'id' => 132,
                'name' => 'create Company',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:19',
                'updated_at' => '2024-08-20 07:26:19',
            ),
            132 => 
            array (
                'id' => 133,
                'name' => 'update Company',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:19',
                'updated_at' => '2024-08-20 07:26:19',
            ),
            133 => 
            array (
                'id' => 134,
                'name' => 'update Company',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:19',
                'updated_at' => '2024-08-20 07:26:19',
            ),
            134 => 
            array (
                'id' => 135,
                'name' => 'delete Company',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:20',
                'updated_at' => '2024-08-20 07:26:20',
            ),
            135 => 
            array (
                'id' => 136,
                'name' => 'delete Company',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:20',
                'updated_at' => '2024-08-20 07:26:20',
            ),
            136 => 
            array (
                'id' => 137,
                'name' => 'restore Company',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:20',
                'updated_at' => '2024-08-20 07:26:20',
            ),
            137 => 
            array (
                'id' => 138,
                'name' => 'restore Company',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:20',
                'updated_at' => '2024-08-20 07:26:20',
            ),
            138 => 
            array (
                'id' => 139,
                'name' => 'force-delete Company',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:20',
                'updated_at' => '2024-08-20 07:26:20',
            ),
            139 => 
            array (
                'id' => 140,
                'name' => 'force-delete Company',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:20',
                'updated_at' => '2024-08-20 07:26:20',
            ),
            140 => 
            array (
                'id' => 141,
                'name' => 'replicate Company',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:20',
                'updated_at' => '2024-08-20 07:26:20',
            ),
            141 => 
            array (
                'id' => 142,
                'name' => 'replicate Company',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:21',
                'updated_at' => '2024-08-20 07:26:21',
            ),
            142 => 
            array (
                'id' => 143,
                'name' => 'reorder Company',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:21',
                'updated_at' => '2024-08-20 07:26:21',
            ),
            143 => 
            array (
                'id' => 144,
                'name' => 'reorder Company',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:21',
                'updated_at' => '2024-08-20 07:26:21',
            ),
            144 => 
            array (
                'id' => 145,
                'name' => 'view-any ConnectedAccount',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:21',
                'updated_at' => '2024-08-20 07:26:21',
            ),
            145 => 
            array (
                'id' => 146,
                'name' => 'view-any ConnectedAccount',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:21',
                'updated_at' => '2024-08-20 07:26:21',
            ),
            146 => 
            array (
                'id' => 147,
                'name' => 'view ConnectedAccount',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:22',
                'updated_at' => '2024-08-20 07:26:22',
            ),
            147 => 
            array (
                'id' => 148,
                'name' => 'view ConnectedAccount',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:22',
                'updated_at' => '2024-08-20 07:26:22',
            ),
            148 => 
            array (
                'id' => 149,
                'name' => 'create ConnectedAccount',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:22',
                'updated_at' => '2024-08-20 07:26:22',
            ),
            149 => 
            array (
                'id' => 150,
                'name' => 'create ConnectedAccount',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:22',
                'updated_at' => '2024-08-20 07:26:22',
            ),
            150 => 
            array (
                'id' => 151,
                'name' => 'update ConnectedAccount',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:22',
                'updated_at' => '2024-08-20 07:26:22',
            ),
            151 => 
            array (
                'id' => 152,
                'name' => 'update ConnectedAccount',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:23',
                'updated_at' => '2024-08-20 07:26:23',
            ),
            152 => 
            array (
                'id' => 153,
                'name' => 'delete ConnectedAccount',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:23',
                'updated_at' => '2024-08-20 07:26:23',
            ),
            153 => 
            array (
                'id' => 154,
                'name' => 'delete ConnectedAccount',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:23',
                'updated_at' => '2024-08-20 07:26:23',
            ),
            154 => 
            array (
                'id' => 155,
                'name' => 'restore ConnectedAccount',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:23',
                'updated_at' => '2024-08-20 07:26:23',
            ),
            155 => 
            array (
                'id' => 156,
                'name' => 'restore ConnectedAccount',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:23',
                'updated_at' => '2024-08-20 07:26:23',
            ),
            156 => 
            array (
                'id' => 157,
                'name' => 'force-delete ConnectedAccount',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:23',
                'updated_at' => '2024-08-20 07:26:23',
            ),
            157 => 
            array (
                'id' => 158,
                'name' => 'force-delete ConnectedAccount',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:24',
                'updated_at' => '2024-08-20 07:26:24',
            ),
            158 => 
            array (
                'id' => 159,
                'name' => 'replicate ConnectedAccount',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:24',
                'updated_at' => '2024-08-20 07:26:24',
            ),
            159 => 
            array (
                'id' => 160,
                'name' => 'replicate ConnectedAccount',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:24',
                'updated_at' => '2024-08-20 07:26:24',
            ),
            160 => 
            array (
                'id' => 161,
                'name' => 'reorder ConnectedAccount',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:24',
                'updated_at' => '2024-08-20 07:26:24',
            ),
            161 => 
            array (
                'id' => 162,
                'name' => 'reorder ConnectedAccount',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:24',
                'updated_at' => '2024-08-20 07:26:24',
            ),
            162 => 
            array (
                'id' => 163,
                'name' => 'view-any Conversation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:24',
                'updated_at' => '2024-08-20 07:26:24',
            ),
            163 => 
            array (
                'id' => 164,
                'name' => 'view-any Conversation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:25',
                'updated_at' => '2024-08-20 07:26:25',
            ),
            164 => 
            array (
                'id' => 165,
                'name' => 'view Conversation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:25',
                'updated_at' => '2024-08-20 07:26:25',
            ),
            165 => 
            array (
                'id' => 166,
                'name' => 'view Conversation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:25',
                'updated_at' => '2024-08-20 07:26:25',
            ),
            166 => 
            array (
                'id' => 167,
                'name' => 'create Conversation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:25',
                'updated_at' => '2024-08-20 07:26:25',
            ),
            167 => 
            array (
                'id' => 168,
                'name' => 'create Conversation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:25',
                'updated_at' => '2024-08-20 07:26:25',
            ),
            168 => 
            array (
                'id' => 169,
                'name' => 'update Conversation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:26',
                'updated_at' => '2024-08-20 07:26:26',
            ),
            169 => 
            array (
                'id' => 170,
                'name' => 'update Conversation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:26',
                'updated_at' => '2024-08-20 07:26:26',
            ),
            170 => 
            array (
                'id' => 171,
                'name' => 'delete Conversation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:26',
                'updated_at' => '2024-08-20 07:26:26',
            ),
            171 => 
            array (
                'id' => 172,
                'name' => 'delete Conversation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:26',
                'updated_at' => '2024-08-20 07:26:26',
            ),
            172 => 
            array (
                'id' => 173,
                'name' => 'restore Conversation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:26',
                'updated_at' => '2024-08-20 07:26:26',
            ),
            173 => 
            array (
                'id' => 174,
                'name' => 'restore Conversation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:26',
                'updated_at' => '2024-08-20 07:26:26',
            ),
            174 => 
            array (
                'id' => 175,
                'name' => 'force-delete Conversation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:27',
                'updated_at' => '2024-08-20 07:26:27',
            ),
            175 => 
            array (
                'id' => 176,
                'name' => 'force-delete Conversation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:27',
                'updated_at' => '2024-08-20 07:26:27',
            ),
            176 => 
            array (
                'id' => 177,
                'name' => 'replicate Conversation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:27',
                'updated_at' => '2024-08-20 07:26:27',
            ),
            177 => 
            array (
                'id' => 178,
                'name' => 'replicate Conversation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:27',
                'updated_at' => '2024-08-20 07:26:27',
            ),
            178 => 
            array (
                'id' => 179,
                'name' => 'reorder Conversation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:27',
                'updated_at' => '2024-08-20 07:26:27',
            ),
            179 => 
            array (
                'id' => 180,
                'name' => 'reorder Conversation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:27',
                'updated_at' => '2024-08-20 07:26:27',
            ),
            180 => 
            array (
                'id' => 181,
                'name' => 'view-any Dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:28',
                'updated_at' => '2024-08-20 07:26:28',
            ),
            181 => 
            array (
                'id' => 182,
                'name' => 'view-any Dna',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:28',
                'updated_at' => '2024-08-20 07:26:28',
            ),
            182 => 
            array (
                'id' => 183,
                'name' => 'view Dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:28',
                'updated_at' => '2024-08-20 07:26:28',
            ),
            183 => 
            array (
                'id' => 184,
                'name' => 'view Dna',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:28',
                'updated_at' => '2024-08-20 07:26:28',
            ),
            184 => 
            array (
                'id' => 185,
                'name' => 'create Dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:28',
                'updated_at' => '2024-08-20 07:26:28',
            ),
            185 => 
            array (
                'id' => 186,
                'name' => 'create Dna',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:29',
                'updated_at' => '2024-08-20 07:26:29',
            ),
            186 => 
            array (
                'id' => 187,
                'name' => 'update Dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:29',
                'updated_at' => '2024-08-20 07:26:29',
            ),
            187 => 
            array (
                'id' => 188,
                'name' => 'update Dna',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:29',
                'updated_at' => '2024-08-20 07:26:29',
            ),
            188 => 
            array (
                'id' => 189,
                'name' => 'delete Dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:29',
                'updated_at' => '2024-08-20 07:26:29',
            ),
            189 => 
            array (
                'id' => 190,
                'name' => 'delete Dna',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:29',
                'updated_at' => '2024-08-20 07:26:29',
            ),
            190 => 
            array (
                'id' => 191,
                'name' => 'restore Dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:29',
                'updated_at' => '2024-08-20 07:26:29',
            ),
            191 => 
            array (
                'id' => 192,
                'name' => 'restore Dna',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:29',
                'updated_at' => '2024-08-20 07:26:29',
            ),
            192 => 
            array (
                'id' => 193,
                'name' => 'force-delete Dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:30',
                'updated_at' => '2024-08-20 07:26:30',
            ),
            193 => 
            array (
                'id' => 194,
                'name' => 'force-delete Dna',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:30',
                'updated_at' => '2024-08-20 07:26:30',
            ),
            194 => 
            array (
                'id' => 195,
                'name' => 'replicate Dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:30',
                'updated_at' => '2024-08-20 07:26:30',
            ),
            195 => 
            array (
                'id' => 196,
                'name' => 'replicate Dna',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:30',
                'updated_at' => '2024-08-20 07:26:30',
            ),
            196 => 
            array (
                'id' => 197,
                'name' => 'reorder Dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:30',
                'updated_at' => '2024-08-20 07:26:30',
            ),
            197 => 
            array (
                'id' => 198,
                'name' => 'reorder Dna',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:31',
                'updated_at' => '2024-08-20 07:26:31',
            ),
            198 => 
            array (
                'id' => 199,
                'name' => 'view-any DnaMatching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:31',
                'updated_at' => '2024-08-20 07:26:31',
            ),
            199 => 
            array (
                'id' => 200,
                'name' => 'view-any DnaMatching',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:31',
                'updated_at' => '2024-08-20 07:26:31',
            ),
            200 => 
            array (
                'id' => 201,
                'name' => 'view DnaMatching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:31',
                'updated_at' => '2024-08-20 07:26:31',
            ),
            201 => 
            array (
                'id' => 202,
                'name' => 'view DnaMatching',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:31',
                'updated_at' => '2024-08-20 07:26:31',
            ),
            202 => 
            array (
                'id' => 203,
                'name' => 'create DnaMatching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:31',
                'updated_at' => '2024-08-20 07:26:31',
            ),
            203 => 
            array (
                'id' => 204,
                'name' => 'create DnaMatching',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:32',
                'updated_at' => '2024-08-20 07:26:32',
            ),
            204 => 
            array (
                'id' => 205,
                'name' => 'update DnaMatching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:32',
                'updated_at' => '2024-08-20 07:26:32',
            ),
            205 => 
            array (
                'id' => 206,
                'name' => 'update DnaMatching',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:32',
                'updated_at' => '2024-08-20 07:26:32',
            ),
            206 => 
            array (
                'id' => 207,
                'name' => 'delete DnaMatching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:32',
                'updated_at' => '2024-08-20 07:26:32',
            ),
            207 => 
            array (
                'id' => 208,
                'name' => 'delete DnaMatching',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:32',
                'updated_at' => '2024-08-20 07:26:32',
            ),
            208 => 
            array (
                'id' => 209,
                'name' => 'restore DnaMatching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:32',
                'updated_at' => '2024-08-20 07:26:32',
            ),
            209 => 
            array (
                'id' => 210,
                'name' => 'restore DnaMatching',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:33',
                'updated_at' => '2024-08-20 07:26:33',
            ),
            210 => 
            array (
                'id' => 211,
                'name' => 'force-delete DnaMatching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:33',
                'updated_at' => '2024-08-20 07:26:33',
            ),
            211 => 
            array (
                'id' => 212,
                'name' => 'force-delete DnaMatching',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:33',
                'updated_at' => '2024-08-20 07:26:33',
            ),
            212 => 
            array (
                'id' => 213,
                'name' => 'replicate DnaMatching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:33',
                'updated_at' => '2024-08-20 07:26:33',
            ),
            213 => 
            array (
                'id' => 214,
                'name' => 'replicate DnaMatching',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:33',
                'updated_at' => '2024-08-20 07:26:33',
            ),
            214 => 
            array (
                'id' => 215,
                'name' => 'reorder DnaMatching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:33',
                'updated_at' => '2024-08-20 07:26:33',
            ),
            215 => 
            array (
                'id' => 216,
                'name' => 'reorder DnaMatching',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:33',
                'updated_at' => '2024-08-20 07:26:33',
            ),
            216 => 
            array (
                'id' => 217,
                'name' => 'view-any Family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:34',
                'updated_at' => '2024-08-20 07:26:34',
            ),
            217 => 
            array (
                'id' => 218,
                'name' => 'view-any Family',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:34',
                'updated_at' => '2024-08-20 07:26:34',
            ),
            218 => 
            array (
                'id' => 219,
                'name' => 'view Family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:34',
                'updated_at' => '2024-08-20 07:26:34',
            ),
            219 => 
            array (
                'id' => 220,
                'name' => 'view Family',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:35',
                'updated_at' => '2024-08-20 07:26:35',
            ),
            220 => 
            array (
                'id' => 221,
                'name' => 'create Family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:35',
                'updated_at' => '2024-08-20 07:26:35',
            ),
            221 => 
            array (
                'id' => 222,
                'name' => 'create Family',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:35',
                'updated_at' => '2024-08-20 07:26:35',
            ),
            222 => 
            array (
                'id' => 223,
                'name' => 'update Family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:35',
                'updated_at' => '2024-08-20 07:26:35',
            ),
            223 => 
            array (
                'id' => 224,
                'name' => 'update Family',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:35',
                'updated_at' => '2024-08-20 07:26:35',
            ),
            224 => 
            array (
                'id' => 225,
                'name' => 'delete Family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:35',
                'updated_at' => '2024-08-20 07:26:35',
            ),
            225 => 
            array (
                'id' => 226,
                'name' => 'delete Family',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:35',
                'updated_at' => '2024-08-20 07:26:35',
            ),
            226 => 
            array (
                'id' => 227,
                'name' => 'restore Family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:36',
                'updated_at' => '2024-08-20 07:26:36',
            ),
            227 => 
            array (
                'id' => 228,
                'name' => 'restore Family',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:36',
                'updated_at' => '2024-08-20 07:26:36',
            ),
            228 => 
            array (
                'id' => 229,
                'name' => 'force-delete Family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:36',
                'updated_at' => '2024-08-20 07:26:36',
            ),
            229 => 
            array (
                'id' => 230,
                'name' => 'force-delete Family',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:36',
                'updated_at' => '2024-08-20 07:26:36',
            ),
            230 => 
            array (
                'id' => 231,
                'name' => 'replicate Family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:36',
                'updated_at' => '2024-08-20 07:26:36',
            ),
            231 => 
            array (
                'id' => 232,
                'name' => 'replicate Family',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:36',
                'updated_at' => '2024-08-20 07:26:36',
            ),
            232 => 
            array (
                'id' => 233,
                'name' => 'reorder Family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:37',
                'updated_at' => '2024-08-20 07:26:37',
            ),
            233 => 
            array (
                'id' => 234,
                'name' => 'reorder Family',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:37',
                'updated_at' => '2024-08-20 07:26:37',
            ),
            234 => 
            array (
                'id' => 235,
                'name' => 'view-any FamilyEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:37',
                'updated_at' => '2024-08-20 07:26:37',
            ),
            235 => 
            array (
                'id' => 236,
                'name' => 'view-any FamilyEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:37',
                'updated_at' => '2024-08-20 07:26:37',
            ),
            236 => 
            array (
                'id' => 237,
                'name' => 'view FamilyEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:37',
                'updated_at' => '2024-08-20 07:26:37',
            ),
            237 => 
            array (
                'id' => 238,
                'name' => 'view FamilyEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:37',
                'updated_at' => '2024-08-20 07:26:37',
            ),
            238 => 
            array (
                'id' => 239,
                'name' => 'create FamilyEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:38',
                'updated_at' => '2024-08-20 07:26:38',
            ),
            239 => 
            array (
                'id' => 240,
                'name' => 'create FamilyEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:38',
                'updated_at' => '2024-08-20 07:26:38',
            ),
            240 => 
            array (
                'id' => 241,
                'name' => 'update FamilyEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:38',
                'updated_at' => '2024-08-20 07:26:38',
            ),
            241 => 
            array (
                'id' => 242,
                'name' => 'update FamilyEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:38',
                'updated_at' => '2024-08-20 07:26:38',
            ),
            242 => 
            array (
                'id' => 243,
                'name' => 'delete FamilyEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:38',
                'updated_at' => '2024-08-20 07:26:38',
            ),
            243 => 
            array (
                'id' => 244,
                'name' => 'delete FamilyEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:38',
                'updated_at' => '2024-08-20 07:26:38',
            ),
            244 => 
            array (
                'id' => 245,
                'name' => 'restore FamilyEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:39',
                'updated_at' => '2024-08-20 07:26:39',
            ),
            245 => 
            array (
                'id' => 246,
                'name' => 'restore FamilyEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:39',
                'updated_at' => '2024-08-20 07:26:39',
            ),
            246 => 
            array (
                'id' => 247,
                'name' => 'force-delete FamilyEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:39',
                'updated_at' => '2024-08-20 07:26:39',
            ),
            247 => 
            array (
                'id' => 248,
                'name' => 'force-delete FamilyEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:39',
                'updated_at' => '2024-08-20 07:26:39',
            ),
            248 => 
            array (
                'id' => 249,
                'name' => 'replicate FamilyEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:39',
                'updated_at' => '2024-08-20 07:26:39',
            ),
            249 => 
            array (
                'id' => 250,
                'name' => 'replicate FamilyEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:40',
                'updated_at' => '2024-08-20 07:26:40',
            ),
            250 => 
            array (
                'id' => 251,
                'name' => 'reorder FamilyEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:40',
                'updated_at' => '2024-08-20 07:26:40',
            ),
            251 => 
            array (
                'id' => 252,
                'name' => 'reorder FamilyEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:40',
                'updated_at' => '2024-08-20 07:26:40',
            ),
            252 => 
            array (
                'id' => 253,
                'name' => 'view-any FamilySlgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:40',
                'updated_at' => '2024-08-20 07:26:40',
            ),
            253 => 
            array (
                'id' => 254,
                'name' => 'view-any FamilySlgs',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:40',
                'updated_at' => '2024-08-20 07:26:40',
            ),
            254 => 
            array (
                'id' => 255,
                'name' => 'view FamilySlgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:41',
                'updated_at' => '2024-08-20 07:26:41',
            ),
            255 => 
            array (
                'id' => 256,
                'name' => 'view FamilySlgs',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:41',
                'updated_at' => '2024-08-20 07:26:41',
            ),
            256 => 
            array (
                'id' => 257,
                'name' => 'create FamilySlgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:41',
                'updated_at' => '2024-08-20 07:26:41',
            ),
            257 => 
            array (
                'id' => 258,
                'name' => 'create FamilySlgs',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:41',
                'updated_at' => '2024-08-20 07:26:41',
            ),
            258 => 
            array (
                'id' => 259,
                'name' => 'update FamilySlgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:41',
                'updated_at' => '2024-08-20 07:26:41',
            ),
            259 => 
            array (
                'id' => 260,
                'name' => 'update FamilySlgs',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:41',
                'updated_at' => '2024-08-20 07:26:41',
            ),
            260 => 
            array (
                'id' => 261,
                'name' => 'delete FamilySlgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:41',
                'updated_at' => '2024-08-20 07:26:41',
            ),
            261 => 
            array (
                'id' => 262,
                'name' => 'delete FamilySlgs',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:41',
                'updated_at' => '2024-08-20 07:26:41',
            ),
            262 => 
            array (
                'id' => 263,
                'name' => 'restore FamilySlgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:42',
                'updated_at' => '2024-08-20 07:26:42',
            ),
            263 => 
            array (
                'id' => 264,
                'name' => 'restore FamilySlgs',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:42',
                'updated_at' => '2024-08-20 07:26:42',
            ),
            264 => 
            array (
                'id' => 265,
                'name' => 'force-delete FamilySlgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:42',
                'updated_at' => '2024-08-20 07:26:42',
            ),
            265 => 
            array (
                'id' => 266,
                'name' => 'force-delete FamilySlgs',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:42',
                'updated_at' => '2024-08-20 07:26:42',
            ),
            266 => 
            array (
                'id' => 267,
                'name' => 'replicate FamilySlgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:42',
                'updated_at' => '2024-08-20 07:26:42',
            ),
            267 => 
            array (
                'id' => 268,
                'name' => 'replicate FamilySlgs',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:42',
                'updated_at' => '2024-08-20 07:26:42',
            ),
            268 => 
            array (
                'id' => 269,
                'name' => 'reorder FamilySlgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:42',
                'updated_at' => '2024-08-20 07:26:42',
            ),
            269 => 
            array (
                'id' => 270,
                'name' => 'reorder FamilySlgs',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:43',
                'updated_at' => '2024-08-20 07:26:43',
            ),
            270 => 
            array (
                'id' => 271,
                'name' => 'view-any Gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:43',
                'updated_at' => '2024-08-20 07:26:43',
            ),
            271 => 
            array (
                'id' => 272,
                'name' => 'view-any Gedcom',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:43',
                'updated_at' => '2024-08-20 07:26:43',
            ),
            272 => 
            array (
                'id' => 273,
                'name' => 'view Gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:43',
                'updated_at' => '2024-08-20 07:26:43',
            ),
            273 => 
            array (
                'id' => 274,
                'name' => 'view Gedcom',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:43',
                'updated_at' => '2024-08-20 07:26:43',
            ),
            274 => 
            array (
                'id' => 275,
                'name' => 'create Gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:43',
                'updated_at' => '2024-08-20 07:26:43',
            ),
            275 => 
            array (
                'id' => 276,
                'name' => 'create Gedcom',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:44',
                'updated_at' => '2024-08-20 07:26:44',
            ),
            276 => 
            array (
                'id' => 277,
                'name' => 'update Gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:44',
                'updated_at' => '2024-08-20 07:26:44',
            ),
            277 => 
            array (
                'id' => 278,
                'name' => 'update Gedcom',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:44',
                'updated_at' => '2024-08-20 07:26:44',
            ),
            278 => 
            array (
                'id' => 279,
                'name' => 'delete Gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:44',
                'updated_at' => '2024-08-20 07:26:44',
            ),
            279 => 
            array (
                'id' => 280,
                'name' => 'delete Gedcom',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:44',
                'updated_at' => '2024-08-20 07:26:44',
            ),
            280 => 
            array (
                'id' => 281,
                'name' => 'restore Gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:44',
                'updated_at' => '2024-08-20 07:26:44',
            ),
            281 => 
            array (
                'id' => 282,
                'name' => 'restore Gedcom',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:45',
                'updated_at' => '2024-08-20 07:26:45',
            ),
            282 => 
            array (
                'id' => 283,
                'name' => 'force-delete Gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:45',
                'updated_at' => '2024-08-20 07:26:45',
            ),
            283 => 
            array (
                'id' => 284,
                'name' => 'force-delete Gedcom',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:45',
                'updated_at' => '2024-08-20 07:26:45',
            ),
            284 => 
            array (
                'id' => 285,
                'name' => 'replicate Gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:45',
                'updated_at' => '2024-08-20 07:26:45',
            ),
            285 => 
            array (
                'id' => 286,
                'name' => 'replicate Gedcom',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:45',
                'updated_at' => '2024-08-20 07:26:45',
            ),
            286 => 
            array (
                'id' => 287,
                'name' => 'reorder Gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:45',
                'updated_at' => '2024-08-20 07:26:45',
            ),
            287 => 
            array (
                'id' => 288,
                'name' => 'reorder Gedcom',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:45',
                'updated_at' => '2024-08-20 07:26:45',
            ),
            288 => 
            array (
                'id' => 289,
                'name' => 'view-any Geneanum',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:46',
                'updated_at' => '2024-08-20 07:26:46',
            ),
            289 => 
            array (
                'id' => 290,
                'name' => 'view-any Geneanum',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:46',
                'updated_at' => '2024-08-20 07:26:46',
            ),
            290 => 
            array (
                'id' => 291,
                'name' => 'view Geneanum',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:46',
                'updated_at' => '2024-08-20 07:26:46',
            ),
            291 => 
            array (
                'id' => 292,
                'name' => 'view Geneanum',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:46',
                'updated_at' => '2024-08-20 07:26:46',
            ),
            292 => 
            array (
                'id' => 293,
                'name' => 'create Geneanum',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:46',
                'updated_at' => '2024-08-20 07:26:46',
            ),
            293 => 
            array (
                'id' => 294,
                'name' => 'create Geneanum',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:46',
                'updated_at' => '2024-08-20 07:26:46',
            ),
            294 => 
            array (
                'id' => 295,
                'name' => 'update Geneanum',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:46',
                'updated_at' => '2024-08-20 07:26:46',
            ),
            295 => 
            array (
                'id' => 296,
                'name' => 'update Geneanum',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:47',
                'updated_at' => '2024-08-20 07:26:47',
            ),
            296 => 
            array (
                'id' => 297,
                'name' => 'delete Geneanum',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:47',
                'updated_at' => '2024-08-20 07:26:47',
            ),
            297 => 
            array (
                'id' => 298,
                'name' => 'delete Geneanum',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:47',
                'updated_at' => '2024-08-20 07:26:47',
            ),
            298 => 
            array (
                'id' => 299,
                'name' => 'restore Geneanum',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:47',
                'updated_at' => '2024-08-20 07:26:47',
            ),
            299 => 
            array (
                'id' => 300,
                'name' => 'restore Geneanum',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:47',
                'updated_at' => '2024-08-20 07:26:47',
            ),
            300 => 
            array (
                'id' => 301,
                'name' => 'force-delete Geneanum',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:47',
                'updated_at' => '2024-08-20 07:26:47',
            ),
            301 => 
            array (
                'id' => 302,
                'name' => 'force-delete Geneanum',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:47',
                'updated_at' => '2024-08-20 07:26:47',
            ),
            302 => 
            array (
                'id' => 303,
                'name' => 'replicate Geneanum',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:48',
                'updated_at' => '2024-08-20 07:26:48',
            ),
            303 => 
            array (
                'id' => 304,
                'name' => 'replicate Geneanum',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:48',
                'updated_at' => '2024-08-20 07:26:48',
            ),
            304 => 
            array (
                'id' => 305,
                'name' => 'reorder Geneanum',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:48',
                'updated_at' => '2024-08-20 07:26:48',
            ),
            305 => 
            array (
                'id' => 306,
                'name' => 'reorder Geneanum',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:48',
                'updated_at' => '2024-08-20 07:26:48',
            ),
            306 => 
            array (
                'id' => 307,
                'name' => 'view-any ImportJob',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:48',
                'updated_at' => '2024-08-20 07:26:48',
            ),
            307 => 
            array (
                'id' => 308,
                'name' => 'view-any ImportJob',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:48',
                'updated_at' => '2024-08-20 07:26:48',
            ),
            308 => 
            array (
                'id' => 309,
                'name' => 'view ImportJob',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:48',
                'updated_at' => '2024-08-20 07:26:48',
            ),
            309 => 
            array (
                'id' => 310,
                'name' => 'view ImportJob',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:49',
                'updated_at' => '2024-08-20 07:26:49',
            ),
            310 => 
            array (
                'id' => 311,
                'name' => 'create ImportJob',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:49',
                'updated_at' => '2024-08-20 07:26:49',
            ),
            311 => 
            array (
                'id' => 312,
                'name' => 'create ImportJob',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:49',
                'updated_at' => '2024-08-20 07:26:49',
            ),
            312 => 
            array (
                'id' => 313,
                'name' => 'update ImportJob',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:49',
                'updated_at' => '2024-08-20 07:26:49',
            ),
            313 => 
            array (
                'id' => 314,
                'name' => 'update ImportJob',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:49',
                'updated_at' => '2024-08-20 07:26:49',
            ),
            314 => 
            array (
                'id' => 315,
                'name' => 'delete ImportJob',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:49',
                'updated_at' => '2024-08-20 07:26:49',
            ),
            315 => 
            array (
                'id' => 316,
                'name' => 'delete ImportJob',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:49',
                'updated_at' => '2024-08-20 07:26:49',
            ),
            316 => 
            array (
                'id' => 317,
                'name' => 'restore ImportJob',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:50',
                'updated_at' => '2024-08-20 07:26:50',
            ),
            317 => 
            array (
                'id' => 318,
                'name' => 'restore ImportJob',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:50',
                'updated_at' => '2024-08-20 07:26:50',
            ),
            318 => 
            array (
                'id' => 319,
                'name' => 'force-delete ImportJob',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:50',
                'updated_at' => '2024-08-20 07:26:50',
            ),
            319 => 
            array (
                'id' => 320,
                'name' => 'force-delete ImportJob',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:50',
                'updated_at' => '2024-08-20 07:26:50',
            ),
            320 => 
            array (
                'id' => 321,
                'name' => 'replicate ImportJob',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:50',
                'updated_at' => '2024-08-20 07:26:50',
            ),
            321 => 
            array (
                'id' => 322,
                'name' => 'replicate ImportJob',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:50',
                'updated_at' => '2024-08-20 07:26:50',
            ),
            322 => 
            array (
                'id' => 323,
                'name' => 'reorder ImportJob',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:50',
                'updated_at' => '2024-08-20 07:26:50',
            ),
            323 => 
            array (
                'id' => 324,
                'name' => 'reorder ImportJob',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:51',
                'updated_at' => '2024-08-20 07:26:51',
            ),
            324 => 
            array (
                'id' => 325,
                'name' => 'view-any MediaObject',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:51',
                'updated_at' => '2024-08-20 07:26:51',
            ),
            325 => 
            array (
                'id' => 326,
                'name' => 'view-any MediaObject',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:51',
                'updated_at' => '2024-08-20 07:26:51',
            ),
            326 => 
            array (
                'id' => 327,
                'name' => 'view MediaObject',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:51',
                'updated_at' => '2024-08-20 07:26:51',
            ),
            327 => 
            array (
                'id' => 328,
                'name' => 'view MediaObject',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:51',
                'updated_at' => '2024-08-20 07:26:51',
            ),
            328 => 
            array (
                'id' => 329,
                'name' => 'create MediaObject',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:51',
                'updated_at' => '2024-08-20 07:26:51',
            ),
            329 => 
            array (
                'id' => 330,
                'name' => 'create MediaObject',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:51',
                'updated_at' => '2024-08-20 07:26:51',
            ),
            330 => 
            array (
                'id' => 331,
                'name' => 'update MediaObject',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:52',
                'updated_at' => '2024-08-20 07:26:52',
            ),
            331 => 
            array (
                'id' => 332,
                'name' => 'update MediaObject',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:52',
                'updated_at' => '2024-08-20 07:26:52',
            ),
            332 => 
            array (
                'id' => 333,
                'name' => 'delete MediaObject',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:52',
                'updated_at' => '2024-08-20 07:26:52',
            ),
            333 => 
            array (
                'id' => 334,
                'name' => 'delete MediaObject',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:52',
                'updated_at' => '2024-08-20 07:26:52',
            ),
            334 => 
            array (
                'id' => 335,
                'name' => 'restore MediaObject',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:52',
                'updated_at' => '2024-08-20 07:26:52',
            ),
            335 => 
            array (
                'id' => 336,
                'name' => 'restore MediaObject',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:52',
                'updated_at' => '2024-08-20 07:26:52',
            ),
            336 => 
            array (
                'id' => 337,
                'name' => 'force-delete MediaObject',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:52',
                'updated_at' => '2024-08-20 07:26:52',
            ),
            337 => 
            array (
                'id' => 338,
                'name' => 'force-delete MediaObject',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:53',
                'updated_at' => '2024-08-20 07:26:53',
            ),
            338 => 
            array (
                'id' => 339,
                'name' => 'replicate MediaObject',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:53',
                'updated_at' => '2024-08-20 07:26:53',
            ),
            339 => 
            array (
                'id' => 340,
                'name' => 'replicate MediaObject',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:53',
                'updated_at' => '2024-08-20 07:26:53',
            ),
            340 => 
            array (
                'id' => 341,
                'name' => 'reorder MediaObject',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:53',
                'updated_at' => '2024-08-20 07:26:53',
            ),
            341 => 
            array (
                'id' => 342,
                'name' => 'reorder MediaObject',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:53',
                'updated_at' => '2024-08-20 07:26:53',
            ),
            342 => 
            array (
                'id' => 343,
                'name' => 'view-any MediaObjeectFile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:53',
                'updated_at' => '2024-08-20 07:26:53',
            ),
            343 => 
            array (
                'id' => 344,
                'name' => 'view-any MediaObjeectFile',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:53',
                'updated_at' => '2024-08-20 07:26:53',
            ),
            344 => 
            array (
                'id' => 345,
                'name' => 'view MediaObjeectFile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:54',
                'updated_at' => '2024-08-20 07:26:54',
            ),
            345 => 
            array (
                'id' => 346,
                'name' => 'view MediaObjeectFile',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:54',
                'updated_at' => '2024-08-20 07:26:54',
            ),
            346 => 
            array (
                'id' => 347,
                'name' => 'create MediaObjeectFile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:54',
                'updated_at' => '2024-08-20 07:26:54',
            ),
            347 => 
            array (
                'id' => 348,
                'name' => 'create MediaObjeectFile',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:54',
                'updated_at' => '2024-08-20 07:26:54',
            ),
            348 => 
            array (
                'id' => 349,
                'name' => 'update MediaObjeectFile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:54',
                'updated_at' => '2024-08-20 07:26:54',
            ),
            349 => 
            array (
                'id' => 350,
                'name' => 'update MediaObjeectFile',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:54',
                'updated_at' => '2024-08-20 07:26:54',
            ),
            350 => 
            array (
                'id' => 351,
                'name' => 'delete MediaObjeectFile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:55',
                'updated_at' => '2024-08-20 07:26:55',
            ),
            351 => 
            array (
                'id' => 352,
                'name' => 'delete MediaObjeectFile',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:55',
                'updated_at' => '2024-08-20 07:26:55',
            ),
            352 => 
            array (
                'id' => 353,
                'name' => 'restore MediaObjeectFile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:55',
                'updated_at' => '2024-08-20 07:26:55',
            ),
            353 => 
            array (
                'id' => 354,
                'name' => 'restore MediaObjeectFile',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:55',
                'updated_at' => '2024-08-20 07:26:55',
            ),
            354 => 
            array (
                'id' => 355,
                'name' => 'force-delete MediaObjeectFile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:55',
                'updated_at' => '2024-08-20 07:26:55',
            ),
            355 => 
            array (
                'id' => 356,
                'name' => 'force-delete MediaObjeectFile',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:55',
                'updated_at' => '2024-08-20 07:26:55',
            ),
            356 => 
            array (
                'id' => 357,
                'name' => 'replicate MediaObjeectFile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:55',
                'updated_at' => '2024-08-20 07:26:55',
            ),
            357 => 
            array (
                'id' => 358,
                'name' => 'replicate MediaObjeectFile',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:55',
                'updated_at' => '2024-08-20 07:26:55',
            ),
            358 => 
            array (
                'id' => 359,
                'name' => 'reorder MediaObjeectFile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:56',
                'updated_at' => '2024-08-20 07:26:56',
            ),
            359 => 
            array (
                'id' => 360,
                'name' => 'reorder MediaObjeectFile',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:56',
                'updated_at' => '2024-08-20 07:26:56',
            ),
            360 => 
            array (
                'id' => 361,
                'name' => 'view-any Membership',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:56',
                'updated_at' => '2024-08-20 07:26:56',
            ),
            361 => 
            array (
                'id' => 362,
                'name' => 'view-any Membership',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:56',
                'updated_at' => '2024-08-20 07:26:56',
            ),
            362 => 
            array (
                'id' => 363,
                'name' => 'view Membership',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:57',
                'updated_at' => '2024-08-20 07:26:57',
            ),
            363 => 
            array (
                'id' => 364,
                'name' => 'view Membership',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:57',
                'updated_at' => '2024-08-20 07:26:57',
            ),
            364 => 
            array (
                'id' => 365,
                'name' => 'create Membership',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:57',
                'updated_at' => '2024-08-20 07:26:57',
            ),
            365 => 
            array (
                'id' => 366,
                'name' => 'create Membership',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:57',
                'updated_at' => '2024-08-20 07:26:57',
            ),
            366 => 
            array (
                'id' => 367,
                'name' => 'update Membership',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:57',
                'updated_at' => '2024-08-20 07:26:57',
            ),
            367 => 
            array (
                'id' => 368,
                'name' => 'update Membership',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:57',
                'updated_at' => '2024-08-20 07:26:57',
            ),
            368 => 
            array (
                'id' => 369,
                'name' => 'delete Membership',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:57',
                'updated_at' => '2024-08-20 07:26:57',
            ),
            369 => 
            array (
                'id' => 370,
                'name' => 'delete Membership',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:57',
                'updated_at' => '2024-08-20 07:26:57',
            ),
            370 => 
            array (
                'id' => 371,
                'name' => 'restore Membership',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:58',
                'updated_at' => '2024-08-20 07:26:58',
            ),
            371 => 
            array (
                'id' => 372,
                'name' => 'restore Membership',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:58',
                'updated_at' => '2024-08-20 07:26:58',
            ),
            372 => 
            array (
                'id' => 373,
                'name' => 'force-delete Membership',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:58',
                'updated_at' => '2024-08-20 07:26:58',
            ),
            373 => 
            array (
                'id' => 374,
                'name' => 'force-delete Membership',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:58',
                'updated_at' => '2024-08-20 07:26:58',
            ),
            374 => 
            array (
                'id' => 375,
                'name' => 'replicate Membership',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:58',
                'updated_at' => '2024-08-20 07:26:58',
            ),
            375 => 
            array (
                'id' => 376,
                'name' => 'replicate Membership',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:59',
                'updated_at' => '2024-08-20 07:26:59',
            ),
            376 => 
            array (
                'id' => 377,
                'name' => 'reorder Membership',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:59',
                'updated_at' => '2024-08-20 07:26:59',
            ),
            377 => 
            array (
                'id' => 378,
                'name' => 'reorder Membership',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:59',
                'updated_at' => '2024-08-20 07:26:59',
            ),
            378 => 
            array (
                'id' => 379,
                'name' => 'view-any Menu',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:59',
                'updated_at' => '2024-08-20 07:26:59',
            ),
            379 => 
            array (
                'id' => 380,
                'name' => 'view-any Menu',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:26:59',
                'updated_at' => '2024-08-20 07:26:59',
            ),
            380 => 
            array (
                'id' => 381,
                'name' => 'view Menu',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:26:59',
                'updated_at' => '2024-08-20 07:26:59',
            ),
            381 => 
            array (
                'id' => 382,
                'name' => 'view Menu',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:00',
                'updated_at' => '2024-08-20 07:27:00',
            ),
            382 => 
            array (
                'id' => 383,
                'name' => 'create Menu',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:00',
                'updated_at' => '2024-08-20 07:27:00',
            ),
            383 => 
            array (
                'id' => 384,
                'name' => 'create Menu',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:00',
                'updated_at' => '2024-08-20 07:27:00',
            ),
            384 => 
            array (
                'id' => 385,
                'name' => 'update Menu',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:00',
                'updated_at' => '2024-08-20 07:27:00',
            ),
            385 => 
            array (
                'id' => 386,
                'name' => 'update Menu',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:00',
                'updated_at' => '2024-08-20 07:27:00',
            ),
            386 => 
            array (
                'id' => 387,
                'name' => 'delete Menu',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:00',
                'updated_at' => '2024-08-20 07:27:00',
            ),
            387 => 
            array (
                'id' => 388,
                'name' => 'delete Menu',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:00',
                'updated_at' => '2024-08-20 07:27:00',
            ),
            388 => 
            array (
                'id' => 389,
                'name' => 'restore Menu',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:00',
                'updated_at' => '2024-08-20 07:27:00',
            ),
            389 => 
            array (
                'id' => 390,
                'name' => 'restore Menu',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:01',
                'updated_at' => '2024-08-20 07:27:01',
            ),
            390 => 
            array (
                'id' => 391,
                'name' => 'force-delete Menu',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:01',
                'updated_at' => '2024-08-20 07:27:01',
            ),
            391 => 
            array (
                'id' => 392,
                'name' => 'force-delete Menu',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:01',
                'updated_at' => '2024-08-20 07:27:01',
            ),
            392 => 
            array (
                'id' => 393,
                'name' => 'replicate Menu',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:01',
                'updated_at' => '2024-08-20 07:27:01',
            ),
            393 => 
            array (
                'id' => 394,
                'name' => 'replicate Menu',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:01',
                'updated_at' => '2024-08-20 07:27:01',
            ),
            394 => 
            array (
                'id' => 395,
                'name' => 'reorder Menu',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:01',
                'updated_at' => '2024-08-20 07:27:01',
            ),
            395 => 
            array (
                'id' => 396,
                'name' => 'reorder Menu',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:02',
                'updated_at' => '2024-08-20 07:27:02',
            ),
            396 => 
            array (
                'id' => 397,
                'name' => 'view-any Message',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:02',
                'updated_at' => '2024-08-20 07:27:02',
            ),
            397 => 
            array (
                'id' => 398,
                'name' => 'view-any Message',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:02',
                'updated_at' => '2024-08-20 07:27:02',
            ),
            398 => 
            array (
                'id' => 399,
                'name' => 'view Message',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:02',
                'updated_at' => '2024-08-20 07:27:02',
            ),
            399 => 
            array (
                'id' => 400,
                'name' => 'view Message',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:02',
                'updated_at' => '2024-08-20 07:27:02',
            ),
            400 => 
            array (
                'id' => 401,
                'name' => 'create Message',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:02',
                'updated_at' => '2024-08-20 07:27:02',
            ),
            401 => 
            array (
                'id' => 402,
                'name' => 'create Message',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:02',
                'updated_at' => '2024-08-20 07:27:02',
            ),
            402 => 
            array (
                'id' => 403,
                'name' => 'update Message',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:02',
                'updated_at' => '2024-08-20 07:27:02',
            ),
            403 => 
            array (
                'id' => 404,
                'name' => 'update Message',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:03',
                'updated_at' => '2024-08-20 07:27:03',
            ),
            404 => 
            array (
                'id' => 405,
                'name' => 'delete Message',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:03',
                'updated_at' => '2024-08-20 07:27:03',
            ),
            405 => 
            array (
                'id' => 406,
                'name' => 'delete Message',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:03',
                'updated_at' => '2024-08-20 07:27:03',
            ),
            406 => 
            array (
                'id' => 407,
                'name' => 'restore Message',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:03',
                'updated_at' => '2024-08-20 07:27:03',
            ),
            407 => 
            array (
                'id' => 408,
                'name' => 'restore Message',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:03',
                'updated_at' => '2024-08-20 07:27:03',
            ),
            408 => 
            array (
                'id' => 409,
                'name' => 'force-delete Message',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:04',
                'updated_at' => '2024-08-20 07:27:04',
            ),
            409 => 
            array (
                'id' => 410,
                'name' => 'force-delete Message',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:04',
                'updated_at' => '2024-08-20 07:27:04',
            ),
            410 => 
            array (
                'id' => 411,
                'name' => 'replicate Message',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:04',
                'updated_at' => '2024-08-20 07:27:04',
            ),
            411 => 
            array (
                'id' => 412,
                'name' => 'replicate Message',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:04',
                'updated_at' => '2024-08-20 07:27:04',
            ),
            412 => 
            array (
                'id' => 413,
                'name' => 'reorder Message',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:04',
                'updated_at' => '2024-08-20 07:27:04',
            ),
            413 => 
            array (
                'id' => 414,
                'name' => 'reorder Message',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:04',
                'updated_at' => '2024-08-20 07:27:04',
            ),
            414 => 
            array (
                'id' => 415,
                'name' => 'view-any Note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:04',
                'updated_at' => '2024-08-20 07:27:04',
            ),
            415 => 
            array (
                'id' => 416,
                'name' => 'view-any Note',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:05',
                'updated_at' => '2024-08-20 07:27:05',
            ),
            416 => 
            array (
                'id' => 417,
                'name' => 'view Note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:05',
                'updated_at' => '2024-08-20 07:27:05',
            ),
            417 => 
            array (
                'id' => 418,
                'name' => 'view Note',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:05',
                'updated_at' => '2024-08-20 07:27:05',
            ),
            418 => 
            array (
                'id' => 419,
                'name' => 'create Note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:05',
                'updated_at' => '2024-08-20 07:27:05',
            ),
            419 => 
            array (
                'id' => 420,
                'name' => 'create Note',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:06',
                'updated_at' => '2024-08-20 07:27:06',
            ),
            420 => 
            array (
                'id' => 421,
                'name' => 'update Note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:06',
                'updated_at' => '2024-08-20 07:27:06',
            ),
            421 => 
            array (
                'id' => 422,
                'name' => 'update Note',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:06',
                'updated_at' => '2024-08-20 07:27:06',
            ),
            422 => 
            array (
                'id' => 423,
                'name' => 'delete Note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:06',
                'updated_at' => '2024-08-20 07:27:06',
            ),
            423 => 
            array (
                'id' => 424,
                'name' => 'delete Note',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:06',
                'updated_at' => '2024-08-20 07:27:06',
            ),
            424 => 
            array (
                'id' => 425,
                'name' => 'restore Note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:06',
                'updated_at' => '2024-08-20 07:27:06',
            ),
            425 => 
            array (
                'id' => 426,
                'name' => 'restore Note',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:06',
                'updated_at' => '2024-08-20 07:27:06',
            ),
            426 => 
            array (
                'id' => 427,
                'name' => 'force-delete Note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:07',
                'updated_at' => '2024-08-20 07:27:07',
            ),
            427 => 
            array (
                'id' => 428,
                'name' => 'force-delete Note',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:07',
                'updated_at' => '2024-08-20 07:27:07',
            ),
            428 => 
            array (
                'id' => 429,
                'name' => 'replicate Note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:07',
                'updated_at' => '2024-08-20 07:27:07',
            ),
            429 => 
            array (
                'id' => 430,
                'name' => 'replicate Note',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:07',
                'updated_at' => '2024-08-20 07:27:07',
            ),
            430 => 
            array (
                'id' => 431,
                'name' => 'reorder Note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:07',
                'updated_at' => '2024-08-20 07:27:07',
            ),
            431 => 
            array (
                'id' => 432,
                'name' => 'reorder Note',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:07',
                'updated_at' => '2024-08-20 07:27:07',
            ),
            432 => 
            array (
                'id' => 433,
                'name' => 'view-any PaypalPlan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:07',
                'updated_at' => '2024-08-20 07:27:07',
            ),
            433 => 
            array (
                'id' => 434,
                'name' => 'view-any PaypalPlan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:08',
                'updated_at' => '2024-08-20 07:27:08',
            ),
            434 => 
            array (
                'id' => 435,
                'name' => 'view PaypalPlan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:08',
                'updated_at' => '2024-08-20 07:27:08',
            ),
            435 => 
            array (
                'id' => 436,
                'name' => 'view PaypalPlan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:08',
                'updated_at' => '2024-08-20 07:27:08',
            ),
            436 => 
            array (
                'id' => 437,
                'name' => 'create PaypalPlan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:08',
                'updated_at' => '2024-08-20 07:27:08',
            ),
            437 => 
            array (
                'id' => 438,
                'name' => 'create PaypalPlan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:08',
                'updated_at' => '2024-08-20 07:27:08',
            ),
            438 => 
            array (
                'id' => 439,
                'name' => 'update PaypalPlan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:08',
                'updated_at' => '2024-08-20 07:27:08',
            ),
            439 => 
            array (
                'id' => 440,
                'name' => 'update PaypalPlan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:08',
                'updated_at' => '2024-08-20 07:27:08',
            ),
            440 => 
            array (
                'id' => 441,
                'name' => 'delete PaypalPlan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:08',
                'updated_at' => '2024-08-20 07:27:08',
            ),
            441 => 
            array (
                'id' => 442,
                'name' => 'delete PaypalPlan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:09',
                'updated_at' => '2024-08-20 07:27:09',
            ),
            442 => 
            array (
                'id' => 443,
                'name' => 'restore PaypalPlan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:09',
                'updated_at' => '2024-08-20 07:27:09',
            ),
            443 => 
            array (
                'id' => 444,
                'name' => 'restore PaypalPlan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:09',
                'updated_at' => '2024-08-20 07:27:09',
            ),
            444 => 
            array (
                'id' => 445,
                'name' => 'force-delete PaypalPlan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:09',
                'updated_at' => '2024-08-20 07:27:09',
            ),
            445 => 
            array (
                'id' => 446,
                'name' => 'force-delete PaypalPlan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:09',
                'updated_at' => '2024-08-20 07:27:09',
            ),
            446 => 
            array (
                'id' => 447,
                'name' => 'replicate PaypalPlan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:09',
                'updated_at' => '2024-08-20 07:27:09',
            ),
            447 => 
            array (
                'id' => 448,
                'name' => 'replicate PaypalPlan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:10',
                'updated_at' => '2024-08-20 07:27:10',
            ),
            448 => 
            array (
                'id' => 449,
                'name' => 'reorder PaypalPlan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:10',
                'updated_at' => '2024-08-20 07:27:10',
            ),
            449 => 
            array (
                'id' => 450,
                'name' => 'reorder PaypalPlan',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:10',
                'updated_at' => '2024-08-20 07:27:10',
            ),
            450 => 
            array (
                'id' => 451,
                'name' => 'view-any PaypalProduct',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:10',
                'updated_at' => '2024-08-20 07:27:10',
            ),
            451 => 
            array (
                'id' => 452,
                'name' => 'view-any PaypalProduct',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:10',
                'updated_at' => '2024-08-20 07:27:10',
            ),
            452 => 
            array (
                'id' => 453,
                'name' => 'view PaypalProduct',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:10',
                'updated_at' => '2024-08-20 07:27:10',
            ),
            453 => 
            array (
                'id' => 454,
                'name' => 'view PaypalProduct',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:11',
                'updated_at' => '2024-08-20 07:27:11',
            ),
            454 => 
            array (
                'id' => 455,
                'name' => 'create PaypalProduct',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:11',
                'updated_at' => '2024-08-20 07:27:11',
            ),
            455 => 
            array (
                'id' => 456,
                'name' => 'create PaypalProduct',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:11',
                'updated_at' => '2024-08-20 07:27:11',
            ),
            456 => 
            array (
                'id' => 457,
                'name' => 'update PaypalProduct',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:11',
                'updated_at' => '2024-08-20 07:27:11',
            ),
            457 => 
            array (
                'id' => 458,
                'name' => 'update PaypalProduct',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:12',
                'updated_at' => '2024-08-20 07:27:12',
            ),
            458 => 
            array (
                'id' => 459,
                'name' => 'delete PaypalProduct',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:12',
                'updated_at' => '2024-08-20 07:27:12',
            ),
            459 => 
            array (
                'id' => 460,
                'name' => 'delete PaypalProduct',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:12',
                'updated_at' => '2024-08-20 07:27:12',
            ),
            460 => 
            array (
                'id' => 461,
                'name' => 'restore PaypalProduct',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:12',
                'updated_at' => '2024-08-20 07:27:12',
            ),
            461 => 
            array (
                'id' => 462,
                'name' => 'restore PaypalProduct',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:12',
                'updated_at' => '2024-08-20 07:27:12',
            ),
            462 => 
            array (
                'id' => 463,
                'name' => 'force-delete PaypalProduct',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:12',
                'updated_at' => '2024-08-20 07:27:12',
            ),
            463 => 
            array (
                'id' => 464,
                'name' => 'force-delete PaypalProduct',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:13',
                'updated_at' => '2024-08-20 07:27:13',
            ),
            464 => 
            array (
                'id' => 465,
                'name' => 'replicate PaypalProduct',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:13',
                'updated_at' => '2024-08-20 07:27:13',
            ),
            465 => 
            array (
                'id' => 466,
                'name' => 'replicate PaypalProduct',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:13',
                'updated_at' => '2024-08-20 07:27:13',
            ),
            466 => 
            array (
                'id' => 467,
                'name' => 'reorder PaypalProduct',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:13',
                'updated_at' => '2024-08-20 07:27:13',
            ),
            467 => 
            array (
                'id' => 468,
                'name' => 'reorder PaypalProduct',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:13',
                'updated_at' => '2024-08-20 07:27:13',
            ),
            468 => 
            array (
                'id' => 469,
                'name' => 'view-any PaypalSubscription',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:13',
                'updated_at' => '2024-08-20 07:27:13',
            ),
            469 => 
            array (
                'id' => 470,
                'name' => 'view-any PaypalSubscription',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:14',
                'updated_at' => '2024-08-20 07:27:14',
            ),
            470 => 
            array (
                'id' => 471,
                'name' => 'view PaypalSubscription',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:14',
                'updated_at' => '2024-08-20 07:27:14',
            ),
            471 => 
            array (
                'id' => 472,
                'name' => 'view PaypalSubscription',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:14',
                'updated_at' => '2024-08-20 07:27:14',
            ),
            472 => 
            array (
                'id' => 473,
                'name' => 'create PaypalSubscription',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:14',
                'updated_at' => '2024-08-20 07:27:14',
            ),
            473 => 
            array (
                'id' => 474,
                'name' => 'create PaypalSubscription',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:14',
                'updated_at' => '2024-08-20 07:27:14',
            ),
            474 => 
            array (
                'id' => 475,
                'name' => 'update PaypalSubscription',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:14',
                'updated_at' => '2024-08-20 07:27:14',
            ),
            475 => 
            array (
                'id' => 476,
                'name' => 'update PaypalSubscription',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:14',
                'updated_at' => '2024-08-20 07:27:14',
            ),
            476 => 
            array (
                'id' => 477,
                'name' => 'delete PaypalSubscription',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:15',
                'updated_at' => '2024-08-20 07:27:15',
            ),
            477 => 
            array (
                'id' => 478,
                'name' => 'delete PaypalSubscription',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:15',
                'updated_at' => '2024-08-20 07:27:15',
            ),
            478 => 
            array (
                'id' => 479,
                'name' => 'restore PaypalSubscription',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:15',
                'updated_at' => '2024-08-20 07:27:15',
            ),
            479 => 
            array (
                'id' => 480,
                'name' => 'restore PaypalSubscription',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:15',
                'updated_at' => '2024-08-20 07:27:15',
            ),
            480 => 
            array (
                'id' => 481,
                'name' => 'force-delete PaypalSubscription',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:15',
                'updated_at' => '2024-08-20 07:27:15',
            ),
            481 => 
            array (
                'id' => 482,
                'name' => 'force-delete PaypalSubscription',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:15',
                'updated_at' => '2024-08-20 07:27:15',
            ),
            482 => 
            array (
                'id' => 483,
                'name' => 'replicate PaypalSubscription',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:15',
                'updated_at' => '2024-08-20 07:27:15',
            ),
            483 => 
            array (
                'id' => 484,
                'name' => 'replicate PaypalSubscription',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:16',
                'updated_at' => '2024-08-20 07:27:16',
            ),
            484 => 
            array (
                'id' => 485,
                'name' => 'reorder PaypalSubscription',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:16',
                'updated_at' => '2024-08-20 07:27:16',
            ),
            485 => 
            array (
                'id' => 486,
                'name' => 'reorder PaypalSubscription',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:16',
                'updated_at' => '2024-08-20 07:27:16',
            ),
            486 => 
            array (
                'id' => 487,
                'name' => 'view-any Person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:16',
                'updated_at' => '2024-08-20 07:27:16',
            ),
            487 => 
            array (
                'id' => 488,
                'name' => 'view-any Person',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:16',
                'updated_at' => '2024-08-20 07:27:16',
            ),
            488 => 
            array (
                'id' => 489,
                'name' => 'view Person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:16',
                'updated_at' => '2024-08-20 07:27:16',
            ),
            489 => 
            array (
                'id' => 490,
                'name' => 'view Person',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:17',
                'updated_at' => '2024-08-20 07:27:17',
            ),
            490 => 
            array (
                'id' => 491,
                'name' => 'create Person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:17',
                'updated_at' => '2024-08-20 07:27:17',
            ),
            491 => 
            array (
                'id' => 492,
                'name' => 'create Person',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:17',
                'updated_at' => '2024-08-20 07:27:17',
            ),
            492 => 
            array (
                'id' => 493,
                'name' => 'update Person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:17',
                'updated_at' => '2024-08-20 07:27:17',
            ),
            493 => 
            array (
                'id' => 494,
                'name' => 'update Person',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:17',
                'updated_at' => '2024-08-20 07:27:17',
            ),
            494 => 
            array (
                'id' => 495,
                'name' => 'delete Person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:17',
                'updated_at' => '2024-08-20 07:27:17',
            ),
            495 => 
            array (
                'id' => 496,
                'name' => 'delete Person',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:17',
                'updated_at' => '2024-08-20 07:27:17',
            ),
            496 => 
            array (
                'id' => 497,
                'name' => 'restore Person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:18',
                'updated_at' => '2024-08-20 07:27:18',
            ),
            497 => 
            array (
                'id' => 498,
                'name' => 'restore Person',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:18',
                'updated_at' => '2024-08-20 07:27:18',
            ),
            498 => 
            array (
                'id' => 499,
                'name' => 'force-delete Person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:18',
                'updated_at' => '2024-08-20 07:27:18',
            ),
            499 => 
            array (
                'id' => 500,
                'name' => 'force-delete Person',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:18',
                'updated_at' => '2024-08-20 07:27:18',
            ),
        ));
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 501,
                'name' => 'replicate Person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:18',
                'updated_at' => '2024-08-20 07:27:18',
            ),
            1 => 
            array (
                'id' => 502,
                'name' => 'replicate Person',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:18',
                'updated_at' => '2024-08-20 07:27:18',
            ),
            2 => 
            array (
                'id' => 503,
                'name' => 'reorder Person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:18',
                'updated_at' => '2024-08-20 07:27:18',
            ),
            3 => 
            array (
                'id' => 504,
                'name' => 'reorder Person',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:18',
                'updated_at' => '2024-08-20 07:27:18',
            ),
            4 => 
            array (
                'id' => 505,
                'name' => 'view-any PersonAlia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:19',
                'updated_at' => '2024-08-20 07:27:19',
            ),
            5 => 
            array (
                'id' => 506,
                'name' => 'view-any PersonAlia',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:19',
                'updated_at' => '2024-08-20 07:27:19',
            ),
            6 => 
            array (
                'id' => 507,
                'name' => 'view PersonAlia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:19',
                'updated_at' => '2024-08-20 07:27:19',
            ),
            7 => 
            array (
                'id' => 508,
                'name' => 'view PersonAlia',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:19',
                'updated_at' => '2024-08-20 07:27:19',
            ),
            8 => 
            array (
                'id' => 509,
                'name' => 'create PersonAlia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:19',
                'updated_at' => '2024-08-20 07:27:19',
            ),
            9 => 
            array (
                'id' => 510,
                'name' => 'create PersonAlia',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:19',
                'updated_at' => '2024-08-20 07:27:19',
            ),
            10 => 
            array (
                'id' => 511,
                'name' => 'update PersonAlia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:19',
                'updated_at' => '2024-08-20 07:27:19',
            ),
            11 => 
            array (
                'id' => 512,
                'name' => 'update PersonAlia',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:20',
                'updated_at' => '2024-08-20 07:27:20',
            ),
            12 => 
            array (
                'id' => 513,
                'name' => 'delete PersonAlia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:20',
                'updated_at' => '2024-08-20 07:27:20',
            ),
            13 => 
            array (
                'id' => 514,
                'name' => 'delete PersonAlia',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:20',
                'updated_at' => '2024-08-20 07:27:20',
            ),
            14 => 
            array (
                'id' => 515,
                'name' => 'restore PersonAlia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:20',
                'updated_at' => '2024-08-20 07:27:20',
            ),
            15 => 
            array (
                'id' => 516,
                'name' => 'restore PersonAlia',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:20',
                'updated_at' => '2024-08-20 07:27:20',
            ),
            16 => 
            array (
                'id' => 517,
                'name' => 'force-delete PersonAlia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:20',
                'updated_at' => '2024-08-20 07:27:20',
            ),
            17 => 
            array (
                'id' => 518,
                'name' => 'force-delete PersonAlia',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:20',
                'updated_at' => '2024-08-20 07:27:20',
            ),
            18 => 
            array (
                'id' => 519,
                'name' => 'replicate PersonAlia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:20',
                'updated_at' => '2024-08-20 07:27:20',
            ),
            19 => 
            array (
                'id' => 520,
                'name' => 'replicate PersonAlia',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:20',
                'updated_at' => '2024-08-20 07:27:20',
            ),
            20 => 
            array (
                'id' => 521,
                'name' => 'reorder PersonAlia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:21',
                'updated_at' => '2024-08-20 07:27:21',
            ),
            21 => 
            array (
                'id' => 522,
                'name' => 'reorder PersonAlia',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:21',
                'updated_at' => '2024-08-20 07:27:21',
            ),
            22 => 
            array (
                'id' => 523,
                'name' => 'view-any PersonAnci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:21',
                'updated_at' => '2024-08-20 07:27:21',
            ),
            23 => 
            array (
                'id' => 524,
                'name' => 'view-any PersonAnci',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:21',
                'updated_at' => '2024-08-20 07:27:21',
            ),
            24 => 
            array (
                'id' => 525,
                'name' => 'view PersonAnci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:21',
                'updated_at' => '2024-08-20 07:27:21',
            ),
            25 => 
            array (
                'id' => 526,
                'name' => 'view PersonAnci',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:21',
                'updated_at' => '2024-08-20 07:27:21',
            ),
            26 => 
            array (
                'id' => 527,
                'name' => 'create PersonAnci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:22',
                'updated_at' => '2024-08-20 07:27:22',
            ),
            27 => 
            array (
                'id' => 528,
                'name' => 'create PersonAnci',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:22',
                'updated_at' => '2024-08-20 07:27:22',
            ),
            28 => 
            array (
                'id' => 529,
                'name' => 'update PersonAnci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:22',
                'updated_at' => '2024-08-20 07:27:22',
            ),
            29 => 
            array (
                'id' => 530,
                'name' => 'update PersonAnci',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:22',
                'updated_at' => '2024-08-20 07:27:22',
            ),
            30 => 
            array (
                'id' => 531,
                'name' => 'delete PersonAnci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:22',
                'updated_at' => '2024-08-20 07:27:22',
            ),
            31 => 
            array (
                'id' => 532,
                'name' => 'delete PersonAnci',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:22',
                'updated_at' => '2024-08-20 07:27:22',
            ),
            32 => 
            array (
                'id' => 533,
                'name' => 'restore PersonAnci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:22',
                'updated_at' => '2024-08-20 07:27:22',
            ),
            33 => 
            array (
                'id' => 534,
                'name' => 'restore PersonAnci',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:23',
                'updated_at' => '2024-08-20 07:27:23',
            ),
            34 => 
            array (
                'id' => 535,
                'name' => 'force-delete PersonAnci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:23',
                'updated_at' => '2024-08-20 07:27:23',
            ),
            35 => 
            array (
                'id' => 536,
                'name' => 'force-delete PersonAnci',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:23',
                'updated_at' => '2024-08-20 07:27:23',
            ),
            36 => 
            array (
                'id' => 537,
                'name' => 'replicate PersonAnci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:23',
                'updated_at' => '2024-08-20 07:27:23',
            ),
            37 => 
            array (
                'id' => 538,
                'name' => 'replicate PersonAnci',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:23',
                'updated_at' => '2024-08-20 07:27:23',
            ),
            38 => 
            array (
                'id' => 539,
                'name' => 'reorder PersonAnci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:23',
                'updated_at' => '2024-08-20 07:27:23',
            ),
            39 => 
            array (
                'id' => 540,
                'name' => 'reorder PersonAnci',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:23',
                'updated_at' => '2024-08-20 07:27:23',
            ),
            40 => 
            array (
                'id' => 541,
                'name' => 'view-any PersonAsso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:23',
                'updated_at' => '2024-08-20 07:27:23',
            ),
            41 => 
            array (
                'id' => 542,
                'name' => 'view-any PersonAsso',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:24',
                'updated_at' => '2024-08-20 07:27:24',
            ),
            42 => 
            array (
                'id' => 543,
                'name' => 'view PersonAsso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:24',
                'updated_at' => '2024-08-20 07:27:24',
            ),
            43 => 
            array (
                'id' => 544,
                'name' => 'view PersonAsso',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:24',
                'updated_at' => '2024-08-20 07:27:24',
            ),
            44 => 
            array (
                'id' => 545,
                'name' => 'create PersonAsso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:24',
                'updated_at' => '2024-08-20 07:27:24',
            ),
            45 => 
            array (
                'id' => 546,
                'name' => 'create PersonAsso',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:24',
                'updated_at' => '2024-08-20 07:27:24',
            ),
            46 => 
            array (
                'id' => 547,
                'name' => 'update PersonAsso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:24',
                'updated_at' => '2024-08-20 07:27:24',
            ),
            47 => 
            array (
                'id' => 548,
                'name' => 'update PersonAsso',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:24',
                'updated_at' => '2024-08-20 07:27:24',
            ),
            48 => 
            array (
                'id' => 549,
                'name' => 'delete PersonAsso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:24',
                'updated_at' => '2024-08-20 07:27:24',
            ),
            49 => 
            array (
                'id' => 550,
                'name' => 'delete PersonAsso',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:24',
                'updated_at' => '2024-08-20 07:27:24',
            ),
            50 => 
            array (
                'id' => 551,
                'name' => 'restore PersonAsso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:25',
                'updated_at' => '2024-08-20 07:27:25',
            ),
            51 => 
            array (
                'id' => 552,
                'name' => 'restore PersonAsso',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:25',
                'updated_at' => '2024-08-20 07:27:25',
            ),
            52 => 
            array (
                'id' => 553,
                'name' => 'force-delete PersonAsso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:25',
                'updated_at' => '2024-08-20 07:27:25',
            ),
            53 => 
            array (
                'id' => 554,
                'name' => 'force-delete PersonAsso',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:25',
                'updated_at' => '2024-08-20 07:27:25',
            ),
            54 => 
            array (
                'id' => 555,
                'name' => 'replicate PersonAsso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:25',
                'updated_at' => '2024-08-20 07:27:25',
            ),
            55 => 
            array (
                'id' => 556,
                'name' => 'replicate PersonAsso',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:25',
                'updated_at' => '2024-08-20 07:27:25',
            ),
            56 => 
            array (
                'id' => 557,
                'name' => 'reorder PersonAsso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:25',
                'updated_at' => '2024-08-20 07:27:25',
            ),
            57 => 
            array (
                'id' => 558,
                'name' => 'reorder PersonAsso',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:26',
                'updated_at' => '2024-08-20 07:27:26',
            ),
            58 => 
            array (
                'id' => 559,
                'name' => 'view-any PersonEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:26',
                'updated_at' => '2024-08-20 07:27:26',
            ),
            59 => 
            array (
                'id' => 560,
                'name' => 'view-any PersonEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:26',
                'updated_at' => '2024-08-20 07:27:26',
            ),
            60 => 
            array (
                'id' => 561,
                'name' => 'view PersonEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:26',
                'updated_at' => '2024-08-20 07:27:26',
            ),
            61 => 
            array (
                'id' => 562,
                'name' => 'view PersonEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:26',
                'updated_at' => '2024-08-20 07:27:26',
            ),
            62 => 
            array (
                'id' => 563,
                'name' => 'create PersonEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:26',
                'updated_at' => '2024-08-20 07:27:26',
            ),
            63 => 
            array (
                'id' => 564,
                'name' => 'create PersonEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:26',
                'updated_at' => '2024-08-20 07:27:26',
            ),
            64 => 
            array (
                'id' => 565,
                'name' => 'update PersonEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:26',
                'updated_at' => '2024-08-20 07:27:26',
            ),
            65 => 
            array (
                'id' => 566,
                'name' => 'update PersonEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:27',
                'updated_at' => '2024-08-20 07:27:27',
            ),
            66 => 
            array (
                'id' => 567,
                'name' => 'delete PersonEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:27',
                'updated_at' => '2024-08-20 07:27:27',
            ),
            67 => 
            array (
                'id' => 568,
                'name' => 'delete PersonEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:27',
                'updated_at' => '2024-08-20 07:27:27',
            ),
            68 => 
            array (
                'id' => 569,
                'name' => 'restore PersonEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:27',
                'updated_at' => '2024-08-20 07:27:27',
            ),
            69 => 
            array (
                'id' => 570,
                'name' => 'restore PersonEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:27',
                'updated_at' => '2024-08-20 07:27:27',
            ),
            70 => 
            array (
                'id' => 571,
                'name' => 'force-delete PersonEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:27',
                'updated_at' => '2024-08-20 07:27:27',
            ),
            71 => 
            array (
                'id' => 572,
                'name' => 'force-delete PersonEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:28',
                'updated_at' => '2024-08-20 07:27:28',
            ),
            72 => 
            array (
                'id' => 573,
                'name' => 'replicate PersonEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:28',
                'updated_at' => '2024-08-20 07:27:28',
            ),
            73 => 
            array (
                'id' => 574,
                'name' => 'replicate PersonEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:28',
                'updated_at' => '2024-08-20 07:27:28',
            ),
            74 => 
            array (
                'id' => 575,
                'name' => 'reorder PersonEvent',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:28',
                'updated_at' => '2024-08-20 07:27:28',
            ),
            75 => 
            array (
                'id' => 576,
                'name' => 'reorder PersonEvent',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:28',
                'updated_at' => '2024-08-20 07:27:28',
            ),
            76 => 
            array (
                'id' => 577,
                'name' => 'view-any PersonLds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:29',
                'updated_at' => '2024-08-20 07:27:29',
            ),
            77 => 
            array (
                'id' => 578,
                'name' => 'view-any PersonLds',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:29',
                'updated_at' => '2024-08-20 07:27:29',
            ),
            78 => 
            array (
                'id' => 579,
                'name' => 'view PersonLds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:29',
                'updated_at' => '2024-08-20 07:27:29',
            ),
            79 => 
            array (
                'id' => 580,
                'name' => 'view PersonLds',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:29',
                'updated_at' => '2024-08-20 07:27:29',
            ),
            80 => 
            array (
                'id' => 581,
                'name' => 'create PersonLds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:29',
                'updated_at' => '2024-08-20 07:27:29',
            ),
            81 => 
            array (
                'id' => 582,
                'name' => 'create PersonLds',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:29',
                'updated_at' => '2024-08-20 07:27:29',
            ),
            82 => 
            array (
                'id' => 583,
                'name' => 'update PersonLds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:30',
                'updated_at' => '2024-08-20 07:27:30',
            ),
            83 => 
            array (
                'id' => 584,
                'name' => 'update PersonLds',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:30',
                'updated_at' => '2024-08-20 07:27:30',
            ),
            84 => 
            array (
                'id' => 585,
                'name' => 'delete PersonLds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:30',
                'updated_at' => '2024-08-20 07:27:30',
            ),
            85 => 
            array (
                'id' => 586,
                'name' => 'delete PersonLds',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:30',
                'updated_at' => '2024-08-20 07:27:30',
            ),
            86 => 
            array (
                'id' => 587,
                'name' => 'restore PersonLds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:30',
                'updated_at' => '2024-08-20 07:27:30',
            ),
            87 => 
            array (
                'id' => 588,
                'name' => 'restore PersonLds',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:30',
                'updated_at' => '2024-08-20 07:27:30',
            ),
            88 => 
            array (
                'id' => 589,
                'name' => 'force-delete PersonLds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:30',
                'updated_at' => '2024-08-20 07:27:30',
            ),
            89 => 
            array (
                'id' => 590,
                'name' => 'force-delete PersonLds',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:30',
                'updated_at' => '2024-08-20 07:27:30',
            ),
            90 => 
            array (
                'id' => 591,
                'name' => 'replicate PersonLds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:31',
                'updated_at' => '2024-08-20 07:27:31',
            ),
            91 => 
            array (
                'id' => 592,
                'name' => 'replicate PersonLds',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:31',
                'updated_at' => '2024-08-20 07:27:31',
            ),
            92 => 
            array (
                'id' => 593,
                'name' => 'reorder PersonLds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:31',
                'updated_at' => '2024-08-20 07:27:31',
            ),
            93 => 
            array (
                'id' => 594,
                'name' => 'reorder PersonLds',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:31',
                'updated_at' => '2024-08-20 07:27:31',
            ),
            94 => 
            array (
                'id' => 595,
                'name' => 'view-any PersonName',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:31',
                'updated_at' => '2024-08-20 07:27:31',
            ),
            95 => 
            array (
                'id' => 596,
                'name' => 'view-any PersonName',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:31',
                'updated_at' => '2024-08-20 07:27:31',
            ),
            96 => 
            array (
                'id' => 597,
                'name' => 'view PersonName',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:32',
                'updated_at' => '2024-08-20 07:27:32',
            ),
            97 => 
            array (
                'id' => 598,
                'name' => 'view PersonName',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:32',
                'updated_at' => '2024-08-20 07:27:32',
            ),
            98 => 
            array (
                'id' => 599,
                'name' => 'create PersonName',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:32',
                'updated_at' => '2024-08-20 07:27:32',
            ),
            99 => 
            array (
                'id' => 600,
                'name' => 'create PersonName',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:32',
                'updated_at' => '2024-08-20 07:27:32',
            ),
            100 => 
            array (
                'id' => 601,
                'name' => 'update PersonName',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:32',
                'updated_at' => '2024-08-20 07:27:32',
            ),
            101 => 
            array (
                'id' => 602,
                'name' => 'update PersonName',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:32',
                'updated_at' => '2024-08-20 07:27:32',
            ),
            102 => 
            array (
                'id' => 603,
                'name' => 'delete PersonName',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:33',
                'updated_at' => '2024-08-20 07:27:33',
            ),
            103 => 
            array (
                'id' => 604,
                'name' => 'delete PersonName',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:33',
                'updated_at' => '2024-08-20 07:27:33',
            ),
            104 => 
            array (
                'id' => 605,
                'name' => 'restore PersonName',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:33',
                'updated_at' => '2024-08-20 07:27:33',
            ),
            105 => 
            array (
                'id' => 606,
                'name' => 'restore PersonName',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:33',
                'updated_at' => '2024-08-20 07:27:33',
            ),
            106 => 
            array (
                'id' => 607,
                'name' => 'force-delete PersonName',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:33',
                'updated_at' => '2024-08-20 07:27:33',
            ),
            107 => 
            array (
                'id' => 608,
                'name' => 'force-delete PersonName',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:33',
                'updated_at' => '2024-08-20 07:27:33',
            ),
            108 => 
            array (
                'id' => 609,
                'name' => 'replicate PersonName',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:33',
                'updated_at' => '2024-08-20 07:27:33',
            ),
            109 => 
            array (
                'id' => 610,
                'name' => 'replicate PersonName',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:34',
                'updated_at' => '2024-08-20 07:27:34',
            ),
            110 => 
            array (
                'id' => 611,
                'name' => 'reorder PersonName',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:34',
                'updated_at' => '2024-08-20 07:27:34',
            ),
            111 => 
            array (
                'id' => 612,
                'name' => 'reorder PersonName',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:34',
                'updated_at' => '2024-08-20 07:27:34',
            ),
            112 => 
            array (
                'id' => 613,
                'name' => 'view-any PersonNameFone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:34',
                'updated_at' => '2024-08-20 07:27:34',
            ),
            113 => 
            array (
                'id' => 614,
                'name' => 'view-any PersonNameFone',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:34',
                'updated_at' => '2024-08-20 07:27:34',
            ),
            114 => 
            array (
                'id' => 615,
                'name' => 'view PersonNameFone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:34',
                'updated_at' => '2024-08-20 07:27:34',
            ),
            115 => 
            array (
                'id' => 616,
                'name' => 'view PersonNameFone',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:34',
                'updated_at' => '2024-08-20 07:27:34',
            ),
            116 => 
            array (
                'id' => 617,
                'name' => 'create PersonNameFone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:35',
                'updated_at' => '2024-08-20 07:27:35',
            ),
            117 => 
            array (
                'id' => 618,
                'name' => 'create PersonNameFone',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:35',
                'updated_at' => '2024-08-20 07:27:35',
            ),
            118 => 
            array (
                'id' => 619,
                'name' => 'update PersonNameFone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:35',
                'updated_at' => '2024-08-20 07:27:35',
            ),
            119 => 
            array (
                'id' => 620,
                'name' => 'update PersonNameFone',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:35',
                'updated_at' => '2024-08-20 07:27:35',
            ),
            120 => 
            array (
                'id' => 621,
                'name' => 'delete PersonNameFone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:35',
                'updated_at' => '2024-08-20 07:27:35',
            ),
            121 => 
            array (
                'id' => 622,
                'name' => 'delete PersonNameFone',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:35',
                'updated_at' => '2024-08-20 07:27:35',
            ),
            122 => 
            array (
                'id' => 623,
                'name' => 'restore PersonNameFone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:35',
                'updated_at' => '2024-08-20 07:27:35',
            ),
            123 => 
            array (
                'id' => 624,
                'name' => 'restore PersonNameFone',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:36',
                'updated_at' => '2024-08-20 07:27:36',
            ),
            124 => 
            array (
                'id' => 625,
                'name' => 'force-delete PersonNameFone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:36',
                'updated_at' => '2024-08-20 07:27:36',
            ),
            125 => 
            array (
                'id' => 626,
                'name' => 'force-delete PersonNameFone',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:36',
                'updated_at' => '2024-08-20 07:27:36',
            ),
            126 => 
            array (
                'id' => 627,
                'name' => 'replicate PersonNameFone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:36',
                'updated_at' => '2024-08-20 07:27:36',
            ),
            127 => 
            array (
                'id' => 628,
                'name' => 'replicate PersonNameFone',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:36',
                'updated_at' => '2024-08-20 07:27:36',
            ),
            128 => 
            array (
                'id' => 629,
                'name' => 'reorder PersonNameFone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:37',
                'updated_at' => '2024-08-20 07:27:37',
            ),
            129 => 
            array (
                'id' => 630,
                'name' => 'reorder PersonNameFone',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:37',
                'updated_at' => '2024-08-20 07:27:37',
            ),
            130 => 
            array (
                'id' => 631,
                'name' => 'view-any PersonNameRomn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:37',
                'updated_at' => '2024-08-20 07:27:37',
            ),
            131 => 
            array (
                'id' => 632,
                'name' => 'view-any PersonNameRomn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:37',
                'updated_at' => '2024-08-20 07:27:37',
            ),
            132 => 
            array (
                'id' => 633,
                'name' => 'view PersonNameRomn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:37',
                'updated_at' => '2024-08-20 07:27:37',
            ),
            133 => 
            array (
                'id' => 634,
                'name' => 'view PersonNameRomn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:37',
                'updated_at' => '2024-08-20 07:27:37',
            ),
            134 => 
            array (
                'id' => 635,
                'name' => 'create PersonNameRomn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:37',
                'updated_at' => '2024-08-20 07:27:37',
            ),
            135 => 
            array (
                'id' => 636,
                'name' => 'create PersonNameRomn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:38',
                'updated_at' => '2024-08-20 07:27:38',
            ),
            136 => 
            array (
                'id' => 637,
                'name' => 'update PersonNameRomn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:38',
                'updated_at' => '2024-08-20 07:27:38',
            ),
            137 => 
            array (
                'id' => 638,
                'name' => 'update PersonNameRomn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:38',
                'updated_at' => '2024-08-20 07:27:38',
            ),
            138 => 
            array (
                'id' => 639,
                'name' => 'delete PersonNameRomn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:38',
                'updated_at' => '2024-08-20 07:27:38',
            ),
            139 => 
            array (
                'id' => 640,
                'name' => 'delete PersonNameRomn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:38',
                'updated_at' => '2024-08-20 07:27:38',
            ),
            140 => 
            array (
                'id' => 641,
                'name' => 'restore PersonNameRomn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:38',
                'updated_at' => '2024-08-20 07:27:38',
            ),
            141 => 
            array (
                'id' => 642,
                'name' => 'restore PersonNameRomn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:39',
                'updated_at' => '2024-08-20 07:27:39',
            ),
            142 => 
            array (
                'id' => 643,
                'name' => 'force-delete PersonNameRomn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:39',
                'updated_at' => '2024-08-20 07:27:39',
            ),
            143 => 
            array (
                'id' => 644,
                'name' => 'force-delete PersonNameRomn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:39',
                'updated_at' => '2024-08-20 07:27:39',
            ),
            144 => 
            array (
                'id' => 645,
                'name' => 'replicate PersonNameRomn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:39',
                'updated_at' => '2024-08-20 07:27:39',
            ),
            145 => 
            array (
                'id' => 646,
                'name' => 'replicate PersonNameRomn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:39',
                'updated_at' => '2024-08-20 07:27:39',
            ),
            146 => 
            array (
                'id' => 647,
                'name' => 'reorder PersonNameRomn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:39',
                'updated_at' => '2024-08-20 07:27:39',
            ),
            147 => 
            array (
                'id' => 648,
                'name' => 'reorder PersonNameRomn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:39',
                'updated_at' => '2024-08-20 07:27:39',
            ),
            148 => 
            array (
                'id' => 649,
                'name' => 'view-any PersonSubm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:40',
                'updated_at' => '2024-08-20 07:27:40',
            ),
            149 => 
            array (
                'id' => 650,
                'name' => 'view-any PersonSubm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:40',
                'updated_at' => '2024-08-20 07:27:40',
            ),
            150 => 
            array (
                'id' => 651,
                'name' => 'view PersonSubm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:40',
                'updated_at' => '2024-08-20 07:27:40',
            ),
            151 => 
            array (
                'id' => 652,
                'name' => 'view PersonSubm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:40',
                'updated_at' => '2024-08-20 07:27:40',
            ),
            152 => 
            array (
                'id' => 653,
                'name' => 'create PersonSubm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:40',
                'updated_at' => '2024-08-20 07:27:40',
            ),
            153 => 
            array (
                'id' => 654,
                'name' => 'create PersonSubm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:40',
                'updated_at' => '2024-08-20 07:27:40',
            ),
            154 => 
            array (
                'id' => 655,
                'name' => 'update PersonSubm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:41',
                'updated_at' => '2024-08-20 07:27:41',
            ),
            155 => 
            array (
                'id' => 656,
                'name' => 'update PersonSubm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:41',
                'updated_at' => '2024-08-20 07:27:41',
            ),
            156 => 
            array (
                'id' => 657,
                'name' => 'delete PersonSubm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:41',
                'updated_at' => '2024-08-20 07:27:41',
            ),
            157 => 
            array (
                'id' => 658,
                'name' => 'delete PersonSubm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:41',
                'updated_at' => '2024-08-20 07:27:41',
            ),
            158 => 
            array (
                'id' => 659,
                'name' => 'restore PersonSubm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:42',
                'updated_at' => '2024-08-20 07:27:42',
            ),
            159 => 
            array (
                'id' => 660,
                'name' => 'restore PersonSubm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:42',
                'updated_at' => '2024-08-20 07:27:42',
            ),
            160 => 
            array (
                'id' => 661,
                'name' => 'force-delete PersonSubm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:42',
                'updated_at' => '2024-08-20 07:27:42',
            ),
            161 => 
            array (
                'id' => 662,
                'name' => 'force-delete PersonSubm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:42',
                'updated_at' => '2024-08-20 07:27:42',
            ),
            162 => 
            array (
                'id' => 663,
                'name' => 'replicate PersonSubm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:42',
                'updated_at' => '2024-08-20 07:27:42',
            ),
            163 => 
            array (
                'id' => 664,
                'name' => 'replicate PersonSubm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:43',
                'updated_at' => '2024-08-20 07:27:43',
            ),
            164 => 
            array (
                'id' => 665,
                'name' => 'reorder PersonSubm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:43',
                'updated_at' => '2024-08-20 07:27:43',
            ),
            165 => 
            array (
                'id' => 666,
                'name' => 'reorder PersonSubm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:43',
                'updated_at' => '2024-08-20 07:27:43',
            ),
            166 => 
            array (
                'id' => 667,
                'name' => 'view-any Place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:43',
                'updated_at' => '2024-08-20 07:27:43',
            ),
            167 => 
            array (
                'id' => 668,
                'name' => 'view-any Place',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:43',
                'updated_at' => '2024-08-20 07:27:43',
            ),
            168 => 
            array (
                'id' => 669,
                'name' => 'view Place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:43',
                'updated_at' => '2024-08-20 07:27:43',
            ),
            169 => 
            array (
                'id' => 670,
                'name' => 'view Place',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:43',
                'updated_at' => '2024-08-20 07:27:43',
            ),
            170 => 
            array (
                'id' => 671,
                'name' => 'create Place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:44',
                'updated_at' => '2024-08-20 07:27:44',
            ),
            171 => 
            array (
                'id' => 672,
                'name' => 'create Place',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:44',
                'updated_at' => '2024-08-20 07:27:44',
            ),
            172 => 
            array (
                'id' => 673,
                'name' => 'update Place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:44',
                'updated_at' => '2024-08-20 07:27:44',
            ),
            173 => 
            array (
                'id' => 674,
                'name' => 'update Place',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:44',
                'updated_at' => '2024-08-20 07:27:44',
            ),
            174 => 
            array (
                'id' => 675,
                'name' => 'delete Place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:44',
                'updated_at' => '2024-08-20 07:27:44',
            ),
            175 => 
            array (
                'id' => 676,
                'name' => 'delete Place',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:44',
                'updated_at' => '2024-08-20 07:27:44',
            ),
            176 => 
            array (
                'id' => 677,
                'name' => 'restore Place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:45',
                'updated_at' => '2024-08-20 07:27:45',
            ),
            177 => 
            array (
                'id' => 678,
                'name' => 'restore Place',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:45',
                'updated_at' => '2024-08-20 07:27:45',
            ),
            178 => 
            array (
                'id' => 679,
                'name' => 'force-delete Place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:45',
                'updated_at' => '2024-08-20 07:27:45',
            ),
            179 => 
            array (
                'id' => 680,
                'name' => 'force-delete Place',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:45',
                'updated_at' => '2024-08-20 07:27:45',
            ),
            180 => 
            array (
                'id' => 681,
                'name' => 'replicate Place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:45',
                'updated_at' => '2024-08-20 07:27:45',
            ),
            181 => 
            array (
                'id' => 682,
                'name' => 'replicate Place',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:45',
                'updated_at' => '2024-08-20 07:27:45',
            ),
            182 => 
            array (
                'id' => 683,
                'name' => 'reorder Place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:45',
                'updated_at' => '2024-08-20 07:27:45',
            ),
            183 => 
            array (
                'id' => 684,
                'name' => 'reorder Place',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:46',
                'updated_at' => '2024-08-20 07:27:46',
            ),
            184 => 
            array (
                'id' => 685,
                'name' => 'view-any Publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:46',
                'updated_at' => '2024-08-20 07:27:46',
            ),
            185 => 
            array (
                'id' => 686,
                'name' => 'view-any Publication',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:46',
                'updated_at' => '2024-08-20 07:27:46',
            ),
            186 => 
            array (
                'id' => 687,
                'name' => 'view Publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:46',
                'updated_at' => '2024-08-20 07:27:46',
            ),
            187 => 
            array (
                'id' => 688,
                'name' => 'view Publication',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:46',
                'updated_at' => '2024-08-20 07:27:46',
            ),
            188 => 
            array (
                'id' => 689,
                'name' => 'create Publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:46',
                'updated_at' => '2024-08-20 07:27:46',
            ),
            189 => 
            array (
                'id' => 690,
                'name' => 'create Publication',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:46',
                'updated_at' => '2024-08-20 07:27:46',
            ),
            190 => 
            array (
                'id' => 691,
                'name' => 'update Publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:47',
                'updated_at' => '2024-08-20 07:27:47',
            ),
            191 => 
            array (
                'id' => 692,
                'name' => 'update Publication',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:47',
                'updated_at' => '2024-08-20 07:27:47',
            ),
            192 => 
            array (
                'id' => 693,
                'name' => 'delete Publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:47',
                'updated_at' => '2024-08-20 07:27:47',
            ),
            193 => 
            array (
                'id' => 694,
                'name' => 'delete Publication',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:47',
                'updated_at' => '2024-08-20 07:27:47',
            ),
            194 => 
            array (
                'id' => 695,
                'name' => 'restore Publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:47',
                'updated_at' => '2024-08-20 07:27:47',
            ),
            195 => 
            array (
                'id' => 696,
                'name' => 'restore Publication',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:47',
                'updated_at' => '2024-08-20 07:27:47',
            ),
            196 => 
            array (
                'id' => 697,
                'name' => 'force-delete Publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:48',
                'updated_at' => '2024-08-20 07:27:48',
            ),
            197 => 
            array (
                'id' => 698,
                'name' => 'force-delete Publication',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:48',
                'updated_at' => '2024-08-20 07:27:48',
            ),
            198 => 
            array (
                'id' => 699,
                'name' => 'replicate Publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:48',
                'updated_at' => '2024-08-20 07:27:48',
            ),
            199 => 
            array (
                'id' => 700,
                'name' => 'replicate Publication',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:48',
                'updated_at' => '2024-08-20 07:27:48',
            ),
            200 => 
            array (
                'id' => 701,
                'name' => 'reorder Publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:48',
                'updated_at' => '2024-08-20 07:27:48',
            ),
            201 => 
            array (
                'id' => 702,
                'name' => 'reorder Publication',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:48',
                'updated_at' => '2024-08-20 07:27:48',
            ),
            202 => 
            array (
                'id' => 703,
                'name' => 'view-any Refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:48',
                'updated_at' => '2024-08-20 07:27:48',
            ),
            203 => 
            array (
                'id' => 704,
                'name' => 'view-any Refn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:49',
                'updated_at' => '2024-08-20 07:27:49',
            ),
            204 => 
            array (
                'id' => 705,
                'name' => 'view Refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:49',
                'updated_at' => '2024-08-20 07:27:49',
            ),
            205 => 
            array (
                'id' => 706,
                'name' => 'view Refn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:49',
                'updated_at' => '2024-08-20 07:27:49',
            ),
            206 => 
            array (
                'id' => 707,
                'name' => 'create Refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:49',
                'updated_at' => '2024-08-20 07:27:49',
            ),
            207 => 
            array (
                'id' => 708,
                'name' => 'create Refn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:49',
                'updated_at' => '2024-08-20 07:27:49',
            ),
            208 => 
            array (
                'id' => 709,
                'name' => 'update Refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:49',
                'updated_at' => '2024-08-20 07:27:49',
            ),
            209 => 
            array (
                'id' => 710,
                'name' => 'update Refn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:49',
                'updated_at' => '2024-08-20 07:27:49',
            ),
            210 => 
            array (
                'id' => 711,
                'name' => 'delete Refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:50',
                'updated_at' => '2024-08-20 07:27:50',
            ),
            211 => 
            array (
                'id' => 712,
                'name' => 'delete Refn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:50',
                'updated_at' => '2024-08-20 07:27:50',
            ),
            212 => 
            array (
                'id' => 713,
                'name' => 'restore Refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:50',
                'updated_at' => '2024-08-20 07:27:50',
            ),
            213 => 
            array (
                'id' => 714,
                'name' => 'restore Refn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:50',
                'updated_at' => '2024-08-20 07:27:50',
            ),
            214 => 
            array (
                'id' => 715,
                'name' => 'force-delete Refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:50',
                'updated_at' => '2024-08-20 07:27:50',
            ),
            215 => 
            array (
                'id' => 716,
                'name' => 'force-delete Refn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:50',
                'updated_at' => '2024-08-20 07:27:50',
            ),
            216 => 
            array (
                'id' => 717,
                'name' => 'replicate Refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:51',
                'updated_at' => '2024-08-20 07:27:51',
            ),
            217 => 
            array (
                'id' => 718,
                'name' => 'replicate Refn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:51',
                'updated_at' => '2024-08-20 07:27:51',
            ),
            218 => 
            array (
                'id' => 719,
                'name' => 'reorder Refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:51',
                'updated_at' => '2024-08-20 07:27:51',
            ),
            219 => 
            array (
                'id' => 720,
                'name' => 'reorder Refn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:51',
                'updated_at' => '2024-08-20 07:27:51',
            ),
            220 => 
            array (
                'id' => 721,
                'name' => 'view-any Repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:51',
                'updated_at' => '2024-08-20 07:27:51',
            ),
            221 => 
            array (
                'id' => 722,
                'name' => 'view-any Repository',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:51',
                'updated_at' => '2024-08-20 07:27:51',
            ),
            222 => 
            array (
                'id' => 723,
                'name' => 'view Repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:51',
                'updated_at' => '2024-08-20 07:27:51',
            ),
            223 => 
            array (
                'id' => 724,
                'name' => 'view Repository',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:52',
                'updated_at' => '2024-08-20 07:27:52',
            ),
            224 => 
            array (
                'id' => 725,
                'name' => 'create Repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:52',
                'updated_at' => '2024-08-20 07:27:52',
            ),
            225 => 
            array (
                'id' => 726,
                'name' => 'create Repository',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:52',
                'updated_at' => '2024-08-20 07:27:52',
            ),
            226 => 
            array (
                'id' => 727,
                'name' => 'update Repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:52',
                'updated_at' => '2024-08-20 07:27:52',
            ),
            227 => 
            array (
                'id' => 728,
                'name' => 'update Repository',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:52',
                'updated_at' => '2024-08-20 07:27:52',
            ),
            228 => 
            array (
                'id' => 729,
                'name' => 'delete Repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:52',
                'updated_at' => '2024-08-20 07:27:52',
            ),
            229 => 
            array (
                'id' => 730,
                'name' => 'delete Repository',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:52',
                'updated_at' => '2024-08-20 07:27:52',
            ),
            230 => 
            array (
                'id' => 731,
                'name' => 'restore Repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:53',
                'updated_at' => '2024-08-20 07:27:53',
            ),
            231 => 
            array (
                'id' => 732,
                'name' => 'restore Repository',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:53',
                'updated_at' => '2024-08-20 07:27:53',
            ),
            232 => 
            array (
                'id' => 733,
                'name' => 'force-delete Repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:53',
                'updated_at' => '2024-08-20 07:27:53',
            ),
            233 => 
            array (
                'id' => 734,
                'name' => 'force-delete Repository',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:53',
                'updated_at' => '2024-08-20 07:27:53',
            ),
            234 => 
            array (
                'id' => 735,
                'name' => 'replicate Repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:53',
                'updated_at' => '2024-08-20 07:27:53',
            ),
            235 => 
            array (
                'id' => 736,
                'name' => 'replicate Repository',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:53',
                'updated_at' => '2024-08-20 07:27:53',
            ),
            236 => 
            array (
                'id' => 737,
                'name' => 'reorder Repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:53',
                'updated_at' => '2024-08-20 07:27:53',
            ),
            237 => 
            array (
                'id' => 738,
                'name' => 'reorder Repository',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:54',
                'updated_at' => '2024-08-20 07:27:54',
            ),
            238 => 
            array (
                'id' => 739,
                'name' => 'view-any Role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:54',
                'updated_at' => '2024-08-20 07:27:54',
            ),
            239 => 
            array (
                'id' => 740,
                'name' => 'view-any Role',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:54',
                'updated_at' => '2024-08-20 07:27:54',
            ),
            240 => 
            array (
                'id' => 741,
                'name' => 'view Role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:54',
                'updated_at' => '2024-08-20 07:27:54',
            ),
            241 => 
            array (
                'id' => 742,
                'name' => 'view Role',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:54',
                'updated_at' => '2024-08-20 07:27:54',
            ),
            242 => 
            array (
                'id' => 743,
                'name' => 'create Role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:54',
                'updated_at' => '2024-08-20 07:27:54',
            ),
            243 => 
            array (
                'id' => 744,
                'name' => 'create Role',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:54',
                'updated_at' => '2024-08-20 07:27:54',
            ),
            244 => 
            array (
                'id' => 745,
                'name' => 'update Role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:54',
                'updated_at' => '2024-08-20 07:27:54',
            ),
            245 => 
            array (
                'id' => 746,
                'name' => 'update Role',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:54',
                'updated_at' => '2024-08-20 07:27:54',
            ),
            246 => 
            array (
                'id' => 747,
                'name' => 'delete Role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:55',
                'updated_at' => '2024-08-20 07:27:55',
            ),
            247 => 
            array (
                'id' => 748,
                'name' => 'delete Role',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:55',
                'updated_at' => '2024-08-20 07:27:55',
            ),
            248 => 
            array (
                'id' => 749,
                'name' => 'restore Role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:55',
                'updated_at' => '2024-08-20 07:27:55',
            ),
            249 => 
            array (
                'id' => 750,
                'name' => 'restore Role',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:55',
                'updated_at' => '2024-08-20 07:27:55',
            ),
            250 => 
            array (
                'id' => 751,
                'name' => 'force-delete Role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:55',
                'updated_at' => '2024-08-20 07:27:55',
            ),
            251 => 
            array (
                'id' => 752,
                'name' => 'force-delete Role',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:56',
                'updated_at' => '2024-08-20 07:27:56',
            ),
            252 => 
            array (
                'id' => 753,
                'name' => 'replicate Role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:56',
                'updated_at' => '2024-08-20 07:27:56',
            ),
            253 => 
            array (
                'id' => 754,
                'name' => 'replicate Role',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:56',
                'updated_at' => '2024-08-20 07:27:56',
            ),
            254 => 
            array (
                'id' => 755,
                'name' => 'reorder Role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:56',
                'updated_at' => '2024-08-20 07:27:56',
            ),
            255 => 
            array (
                'id' => 756,
                'name' => 'reorder Role',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:56',
                'updated_at' => '2024-08-20 07:27:56',
            ),
            256 => 
            array (
                'id' => 757,
                'name' => 'view-any Source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:56',
                'updated_at' => '2024-08-20 07:27:56',
            ),
            257 => 
            array (
                'id' => 758,
                'name' => 'view-any Source',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:56',
                'updated_at' => '2024-08-20 07:27:56',
            ),
            258 => 
            array (
                'id' => 759,
                'name' => 'view Source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:56',
                'updated_at' => '2024-08-20 07:27:56',
            ),
            259 => 
            array (
                'id' => 760,
                'name' => 'view Source',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:57',
                'updated_at' => '2024-08-20 07:27:57',
            ),
            260 => 
            array (
                'id' => 761,
                'name' => 'create Source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:57',
                'updated_at' => '2024-08-20 07:27:57',
            ),
            261 => 
            array (
                'id' => 762,
                'name' => 'create Source',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:57',
                'updated_at' => '2024-08-20 07:27:57',
            ),
            262 => 
            array (
                'id' => 763,
                'name' => 'update Source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:57',
                'updated_at' => '2024-08-20 07:27:57',
            ),
            263 => 
            array (
                'id' => 764,
                'name' => 'update Source',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:57',
                'updated_at' => '2024-08-20 07:27:57',
            ),
            264 => 
            array (
                'id' => 765,
                'name' => 'delete Source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:57',
                'updated_at' => '2024-08-20 07:27:57',
            ),
            265 => 
            array (
                'id' => 766,
                'name' => 'delete Source',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:58',
                'updated_at' => '2024-08-20 07:27:58',
            ),
            266 => 
            array (
                'id' => 767,
                'name' => 'restore Source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:58',
                'updated_at' => '2024-08-20 07:27:58',
            ),
            267 => 
            array (
                'id' => 768,
                'name' => 'restore Source',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:58',
                'updated_at' => '2024-08-20 07:27:58',
            ),
            268 => 
            array (
                'id' => 769,
                'name' => 'force-delete Source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:58',
                'updated_at' => '2024-08-20 07:27:58',
            ),
            269 => 
            array (
                'id' => 770,
                'name' => 'force-delete Source',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:58',
                'updated_at' => '2024-08-20 07:27:58',
            ),
            270 => 
            array (
                'id' => 771,
                'name' => 'replicate Source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:58',
                'updated_at' => '2024-08-20 07:27:58',
            ),
            271 => 
            array (
                'id' => 772,
                'name' => 'replicate Source',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:59',
                'updated_at' => '2024-08-20 07:27:59',
            ),
            272 => 
            array (
                'id' => 773,
                'name' => 'reorder Source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:59',
                'updated_at' => '2024-08-20 07:27:59',
            ),
            273 => 
            array (
                'id' => 774,
                'name' => 'reorder Source',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:59',
                'updated_at' => '2024-08-20 07:27:59',
            ),
            274 => 
            array (
                'id' => 775,
                'name' => 'view-any SourceData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:59',
                'updated_at' => '2024-08-20 07:27:59',
            ),
            275 => 
            array (
                'id' => 776,
                'name' => 'view-any SourceData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:59',
                'updated_at' => '2024-08-20 07:27:59',
            ),
            276 => 
            array (
                'id' => 777,
                'name' => 'view SourceData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:27:59',
                'updated_at' => '2024-08-20 07:27:59',
            ),
            277 => 
            array (
                'id' => 778,
                'name' => 'view SourceData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:27:59',
                'updated_at' => '2024-08-20 07:27:59',
            ),
            278 => 
            array (
                'id' => 779,
                'name' => 'create SourceData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:00',
                'updated_at' => '2024-08-20 07:28:00',
            ),
            279 => 
            array (
                'id' => 780,
                'name' => 'create SourceData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:00',
                'updated_at' => '2024-08-20 07:28:00',
            ),
            280 => 
            array (
                'id' => 781,
                'name' => 'update SourceData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:00',
                'updated_at' => '2024-08-20 07:28:00',
            ),
            281 => 
            array (
                'id' => 782,
                'name' => 'update SourceData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:00',
                'updated_at' => '2024-08-20 07:28:00',
            ),
            282 => 
            array (
                'id' => 783,
                'name' => 'delete SourceData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:00',
                'updated_at' => '2024-08-20 07:28:00',
            ),
            283 => 
            array (
                'id' => 784,
                'name' => 'delete SourceData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:00',
                'updated_at' => '2024-08-20 07:28:00',
            ),
            284 => 
            array (
                'id' => 785,
                'name' => 'restore SourceData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:01',
                'updated_at' => '2024-08-20 07:28:01',
            ),
            285 => 
            array (
                'id' => 786,
                'name' => 'restore SourceData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:01',
                'updated_at' => '2024-08-20 07:28:01',
            ),
            286 => 
            array (
                'id' => 787,
                'name' => 'force-delete SourceData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:01',
                'updated_at' => '2024-08-20 07:28:01',
            ),
            287 => 
            array (
                'id' => 788,
                'name' => 'force-delete SourceData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:01',
                'updated_at' => '2024-08-20 07:28:01',
            ),
            288 => 
            array (
                'id' => 789,
                'name' => 'replicate SourceData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:01',
                'updated_at' => '2024-08-20 07:28:01',
            ),
            289 => 
            array (
                'id' => 790,
                'name' => 'replicate SourceData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:01',
                'updated_at' => '2024-08-20 07:28:01',
            ),
            290 => 
            array (
                'id' => 791,
                'name' => 'reorder SourceData',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:01',
                'updated_at' => '2024-08-20 07:28:01',
            ),
            291 => 
            array (
                'id' => 792,
                'name' => 'reorder SourceData',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:02',
                'updated_at' => '2024-08-20 07:28:02',
            ),
            292 => 
            array (
                'id' => 793,
                'name' => 'view-any SourceDataEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:02',
                'updated_at' => '2024-08-20 07:28:02',
            ),
            293 => 
            array (
                'id' => 794,
                'name' => 'view-any SourceDataEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:02',
                'updated_at' => '2024-08-20 07:28:02',
            ),
            294 => 
            array (
                'id' => 795,
                'name' => 'view SourceDataEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:02',
                'updated_at' => '2024-08-20 07:28:02',
            ),
            295 => 
            array (
                'id' => 796,
                'name' => 'view SourceDataEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:02',
                'updated_at' => '2024-08-20 07:28:02',
            ),
            296 => 
            array (
                'id' => 797,
                'name' => 'create SourceDataEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:02',
                'updated_at' => '2024-08-20 07:28:02',
            ),
            297 => 
            array (
                'id' => 798,
                'name' => 'create SourceDataEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:02',
                'updated_at' => '2024-08-20 07:28:02',
            ),
            298 => 
            array (
                'id' => 799,
                'name' => 'update SourceDataEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:02',
                'updated_at' => '2024-08-20 07:28:02',
            ),
            299 => 
            array (
                'id' => 800,
                'name' => 'update SourceDataEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:03',
                'updated_at' => '2024-08-20 07:28:03',
            ),
            300 => 
            array (
                'id' => 801,
                'name' => 'delete SourceDataEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:03',
                'updated_at' => '2024-08-20 07:28:03',
            ),
            301 => 
            array (
                'id' => 802,
                'name' => 'delete SourceDataEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:03',
                'updated_at' => '2024-08-20 07:28:03',
            ),
            302 => 
            array (
                'id' => 803,
                'name' => 'restore SourceDataEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:03',
                'updated_at' => '2024-08-20 07:28:03',
            ),
            303 => 
            array (
                'id' => 804,
                'name' => 'restore SourceDataEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:03',
                'updated_at' => '2024-08-20 07:28:03',
            ),
            304 => 
            array (
                'id' => 805,
                'name' => 'force-delete SourceDataEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:03',
                'updated_at' => '2024-08-20 07:28:03',
            ),
            305 => 
            array (
                'id' => 806,
                'name' => 'force-delete SourceDataEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:03',
                'updated_at' => '2024-08-20 07:28:03',
            ),
            306 => 
            array (
                'id' => 807,
                'name' => 'replicate SourceDataEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:04',
                'updated_at' => '2024-08-20 07:28:04',
            ),
            307 => 
            array (
                'id' => 808,
                'name' => 'replicate SourceDataEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:04',
                'updated_at' => '2024-08-20 07:28:04',
            ),
            308 => 
            array (
                'id' => 809,
                'name' => 'reorder SourceDataEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:04',
                'updated_at' => '2024-08-20 07:28:04',
            ),
            309 => 
            array (
                'id' => 810,
                'name' => 'reorder SourceDataEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:04',
                'updated_at' => '2024-08-20 07:28:04',
            ),
            310 => 
            array (
                'id' => 811,
                'name' => 'view-any SourceRef',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:04',
                'updated_at' => '2024-08-20 07:28:04',
            ),
            311 => 
            array (
                'id' => 812,
                'name' => 'view-any SourceRef',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:04',
                'updated_at' => '2024-08-20 07:28:04',
            ),
            312 => 
            array (
                'id' => 813,
                'name' => 'view SourceRef',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:04',
                'updated_at' => '2024-08-20 07:28:04',
            ),
            313 => 
            array (
                'id' => 814,
                'name' => 'view SourceRef',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:05',
                'updated_at' => '2024-08-20 07:28:05',
            ),
            314 => 
            array (
                'id' => 815,
                'name' => 'create SourceRef',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:05',
                'updated_at' => '2024-08-20 07:28:05',
            ),
            315 => 
            array (
                'id' => 816,
                'name' => 'create SourceRef',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:05',
                'updated_at' => '2024-08-20 07:28:05',
            ),
            316 => 
            array (
                'id' => 817,
                'name' => 'update SourceRef',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:05',
                'updated_at' => '2024-08-20 07:28:05',
            ),
            317 => 
            array (
                'id' => 818,
                'name' => 'update SourceRef',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:05',
                'updated_at' => '2024-08-20 07:28:05',
            ),
            318 => 
            array (
                'id' => 819,
                'name' => 'delete SourceRef',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:05',
                'updated_at' => '2024-08-20 07:28:05',
            ),
            319 => 
            array (
                'id' => 820,
                'name' => 'delete SourceRef',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:05',
                'updated_at' => '2024-08-20 07:28:05',
            ),
            320 => 
            array (
                'id' => 821,
                'name' => 'restore SourceRef',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:06',
                'updated_at' => '2024-08-20 07:28:06',
            ),
            321 => 
            array (
                'id' => 822,
                'name' => 'restore SourceRef',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:06',
                'updated_at' => '2024-08-20 07:28:06',
            ),
            322 => 
            array (
                'id' => 823,
                'name' => 'force-delete SourceRef',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:06',
                'updated_at' => '2024-08-20 07:28:06',
            ),
            323 => 
            array (
                'id' => 824,
                'name' => 'force-delete SourceRef',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:06',
                'updated_at' => '2024-08-20 07:28:06',
            ),
            324 => 
            array (
                'id' => 825,
                'name' => 'replicate SourceRef',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:06',
                'updated_at' => '2024-08-20 07:28:06',
            ),
            325 => 
            array (
                'id' => 826,
                'name' => 'replicate SourceRef',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:06',
                'updated_at' => '2024-08-20 07:28:06',
            ),
            326 => 
            array (
                'id' => 827,
                'name' => 'reorder SourceRef',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:06',
                'updated_at' => '2024-08-20 07:28:06',
            ),
            327 => 
            array (
                'id' => 828,
                'name' => 'reorder SourceRef',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:06',
                'updated_at' => '2024-08-20 07:28:06',
            ),
            328 => 
            array (
                'id' => 829,
                'name' => 'view-any SourceRefEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:07',
                'updated_at' => '2024-08-20 07:28:07',
            ),
            329 => 
            array (
                'id' => 830,
                'name' => 'view-any SourceRefEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:07',
                'updated_at' => '2024-08-20 07:28:07',
            ),
            330 => 
            array (
                'id' => 831,
                'name' => 'view SourceRefEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:07',
                'updated_at' => '2024-08-20 07:28:07',
            ),
            331 => 
            array (
                'id' => 832,
                'name' => 'view SourceRefEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:07',
                'updated_at' => '2024-08-20 07:28:07',
            ),
            332 => 
            array (
                'id' => 833,
                'name' => 'create SourceRefEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:07',
                'updated_at' => '2024-08-20 07:28:07',
            ),
            333 => 
            array (
                'id' => 834,
                'name' => 'create SourceRefEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:07',
                'updated_at' => '2024-08-20 07:28:07',
            ),
            334 => 
            array (
                'id' => 835,
                'name' => 'update SourceRefEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:07',
                'updated_at' => '2024-08-20 07:28:07',
            ),
            335 => 
            array (
                'id' => 836,
                'name' => 'update SourceRefEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:08',
                'updated_at' => '2024-08-20 07:28:08',
            ),
            336 => 
            array (
                'id' => 837,
                'name' => 'delete SourceRefEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:08',
                'updated_at' => '2024-08-20 07:28:08',
            ),
            337 => 
            array (
                'id' => 838,
                'name' => 'delete SourceRefEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:08',
                'updated_at' => '2024-08-20 07:28:08',
            ),
            338 => 
            array (
                'id' => 839,
                'name' => 'restore SourceRefEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:08',
                'updated_at' => '2024-08-20 07:28:08',
            ),
            339 => 
            array (
                'id' => 840,
                'name' => 'restore SourceRefEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:08',
                'updated_at' => '2024-08-20 07:28:08',
            ),
            340 => 
            array (
                'id' => 841,
                'name' => 'force-delete SourceRefEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:08',
                'updated_at' => '2024-08-20 07:28:08',
            ),
            341 => 
            array (
                'id' => 842,
                'name' => 'force-delete SourceRefEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:08',
                'updated_at' => '2024-08-20 07:28:08',
            ),
            342 => 
            array (
                'id' => 843,
                'name' => 'replicate SourceRefEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:09',
                'updated_at' => '2024-08-20 07:28:09',
            ),
            343 => 
            array (
                'id' => 844,
                'name' => 'replicate SourceRefEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:09',
                'updated_at' => '2024-08-20 07:28:09',
            ),
            344 => 
            array (
                'id' => 845,
                'name' => 'reorder SourceRefEven',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:09',
                'updated_at' => '2024-08-20 07:28:09',
            ),
            345 => 
            array (
                'id' => 846,
                'name' => 'reorder SourceRefEven',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:09',
                'updated_at' => '2024-08-20 07:28:09',
            ),
            346 => 
            array (
                'id' => 847,
                'name' => 'view-any SourceRepo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:09',
                'updated_at' => '2024-08-20 07:28:09',
            ),
            347 => 
            array (
                'id' => 848,
                'name' => 'view-any SourceRepo',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:09',
                'updated_at' => '2024-08-20 07:28:09',
            ),
            348 => 
            array (
                'id' => 849,
                'name' => 'view SourceRepo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:09',
                'updated_at' => '2024-08-20 07:28:09',
            ),
            349 => 
            array (
                'id' => 850,
                'name' => 'view SourceRepo',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:09',
                'updated_at' => '2024-08-20 07:28:09',
            ),
            350 => 
            array (
                'id' => 851,
                'name' => 'create SourceRepo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:10',
                'updated_at' => '2024-08-20 07:28:10',
            ),
            351 => 
            array (
                'id' => 852,
                'name' => 'create SourceRepo',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:10',
                'updated_at' => '2024-08-20 07:28:10',
            ),
            352 => 
            array (
                'id' => 853,
                'name' => 'update SourceRepo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:10',
                'updated_at' => '2024-08-20 07:28:10',
            ),
            353 => 
            array (
                'id' => 854,
                'name' => 'update SourceRepo',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:10',
                'updated_at' => '2024-08-20 07:28:10',
            ),
            354 => 
            array (
                'id' => 855,
                'name' => 'delete SourceRepo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:10',
                'updated_at' => '2024-08-20 07:28:10',
            ),
            355 => 
            array (
                'id' => 856,
                'name' => 'delete SourceRepo',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:10',
                'updated_at' => '2024-08-20 07:28:10',
            ),
            356 => 
            array (
                'id' => 857,
                'name' => 'restore SourceRepo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:10',
                'updated_at' => '2024-08-20 07:28:10',
            ),
            357 => 
            array (
                'id' => 858,
                'name' => 'restore SourceRepo',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:11',
                'updated_at' => '2024-08-20 07:28:11',
            ),
            358 => 
            array (
                'id' => 859,
                'name' => 'force-delete SourceRepo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:11',
                'updated_at' => '2024-08-20 07:28:11',
            ),
            359 => 
            array (
                'id' => 860,
                'name' => 'force-delete SourceRepo',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:11',
                'updated_at' => '2024-08-20 07:28:11',
            ),
            360 => 
            array (
                'id' => 861,
                'name' => 'replicate SourceRepo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:11',
                'updated_at' => '2024-08-20 07:28:11',
            ),
            361 => 
            array (
                'id' => 862,
                'name' => 'replicate SourceRepo',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:11',
                'updated_at' => '2024-08-20 07:28:11',
            ),
            362 => 
            array (
                'id' => 863,
                'name' => 'reorder SourceRepo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:11',
                'updated_at' => '2024-08-20 07:28:11',
            ),
            363 => 
            array (
                'id' => 864,
                'name' => 'reorder SourceRepo',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:11',
                'updated_at' => '2024-08-20 07:28:11',
            ),
            364 => 
            array (
                'id' => 865,
                'name' => 'view-any Subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:12',
                'updated_at' => '2024-08-20 07:28:12',
            ),
            365 => 
            array (
                'id' => 866,
                'name' => 'view-any Subm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:12',
                'updated_at' => '2024-08-20 07:28:12',
            ),
            366 => 
            array (
                'id' => 867,
                'name' => 'view Subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:12',
                'updated_at' => '2024-08-20 07:28:12',
            ),
            367 => 
            array (
                'id' => 868,
                'name' => 'view Subm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:12',
                'updated_at' => '2024-08-20 07:28:12',
            ),
            368 => 
            array (
                'id' => 869,
                'name' => 'create Subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:12',
                'updated_at' => '2024-08-20 07:28:12',
            ),
            369 => 
            array (
                'id' => 870,
                'name' => 'create Subm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:12',
                'updated_at' => '2024-08-20 07:28:12',
            ),
            370 => 
            array (
                'id' => 871,
                'name' => 'update Subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:13',
                'updated_at' => '2024-08-20 07:28:13',
            ),
            371 => 
            array (
                'id' => 872,
                'name' => 'update Subm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:13',
                'updated_at' => '2024-08-20 07:28:13',
            ),
            372 => 
            array (
                'id' => 873,
                'name' => 'delete Subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:13',
                'updated_at' => '2024-08-20 07:28:13',
            ),
            373 => 
            array (
                'id' => 874,
                'name' => 'delete Subm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:13',
                'updated_at' => '2024-08-20 07:28:13',
            ),
            374 => 
            array (
                'id' => 875,
                'name' => 'restore Subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:14',
                'updated_at' => '2024-08-20 07:28:14',
            ),
            375 => 
            array (
                'id' => 876,
                'name' => 'restore Subm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:14',
                'updated_at' => '2024-08-20 07:28:14',
            ),
            376 => 
            array (
                'id' => 877,
                'name' => 'force-delete Subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:14',
                'updated_at' => '2024-08-20 07:28:14',
            ),
            377 => 
            array (
                'id' => 878,
                'name' => 'force-delete Subm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:14',
                'updated_at' => '2024-08-20 07:28:14',
            ),
            378 => 
            array (
                'id' => 879,
                'name' => 'replicate Subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:14',
                'updated_at' => '2024-08-20 07:28:14',
            ),
            379 => 
            array (
                'id' => 880,
                'name' => 'replicate Subm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:14',
                'updated_at' => '2024-08-20 07:28:14',
            ),
            380 => 
            array (
                'id' => 881,
                'name' => 'reorder Subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:14',
                'updated_at' => '2024-08-20 07:28:14',
            ),
            381 => 
            array (
                'id' => 882,
                'name' => 'reorder Subm',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:15',
                'updated_at' => '2024-08-20 07:28:15',
            ),
            382 => 
            array (
                'id' => 883,
                'name' => 'view-any Subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:15',
                'updated_at' => '2024-08-20 07:28:15',
            ),
            383 => 
            array (
                'id' => 884,
                'name' => 'view-any Subn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:15',
                'updated_at' => '2024-08-20 07:28:15',
            ),
            384 => 
            array (
                'id' => 885,
                'name' => 'view Subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:15',
                'updated_at' => '2024-08-20 07:28:15',
            ),
            385 => 
            array (
                'id' => 886,
                'name' => 'view Subn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:15',
                'updated_at' => '2024-08-20 07:28:15',
            ),
            386 => 
            array (
                'id' => 887,
                'name' => 'create Subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:15',
                'updated_at' => '2024-08-20 07:28:15',
            ),
            387 => 
            array (
                'id' => 888,
                'name' => 'create Subn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:15',
                'updated_at' => '2024-08-20 07:28:15',
            ),
            388 => 
            array (
                'id' => 889,
                'name' => 'update Subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:16',
                'updated_at' => '2024-08-20 07:28:16',
            ),
            389 => 
            array (
                'id' => 890,
                'name' => 'update Subn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:16',
                'updated_at' => '2024-08-20 07:28:16',
            ),
            390 => 
            array (
                'id' => 891,
                'name' => 'delete Subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:16',
                'updated_at' => '2024-08-20 07:28:16',
            ),
            391 => 
            array (
                'id' => 892,
                'name' => 'delete Subn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:16',
                'updated_at' => '2024-08-20 07:28:16',
            ),
            392 => 
            array (
                'id' => 893,
                'name' => 'restore Subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:16',
                'updated_at' => '2024-08-20 07:28:16',
            ),
            393 => 
            array (
                'id' => 894,
                'name' => 'restore Subn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:16',
                'updated_at' => '2024-08-20 07:28:16',
            ),
            394 => 
            array (
                'id' => 895,
                'name' => 'force-delete Subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:16',
                'updated_at' => '2024-08-20 07:28:16',
            ),
            395 => 
            array (
                'id' => 896,
                'name' => 'force-delete Subn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:17',
                'updated_at' => '2024-08-20 07:28:17',
            ),
            396 => 
            array (
                'id' => 897,
                'name' => 'replicate Subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:17',
                'updated_at' => '2024-08-20 07:28:17',
            ),
            397 => 
            array (
                'id' => 898,
                'name' => 'replicate Subn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:17',
                'updated_at' => '2024-08-20 07:28:17',
            ),
            398 => 
            array (
                'id' => 899,
                'name' => 'reorder Subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:17',
                'updated_at' => '2024-08-20 07:28:17',
            ),
            399 => 
            array (
                'id' => 900,
                'name' => 'reorder Subn',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:17',
                'updated_at' => '2024-08-20 07:28:17',
            ),
            400 => 
            array (
                'id' => 901,
                'name' => 'view-any Team',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:17',
                'updated_at' => '2024-08-20 07:28:17',
            ),
            401 => 
            array (
                'id' => 902,
                'name' => 'view-any Team',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:18',
                'updated_at' => '2024-08-20 07:28:18',
            ),
            402 => 
            array (
                'id' => 903,
                'name' => 'view Team',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:18',
                'updated_at' => '2024-08-20 07:28:18',
            ),
            403 => 
            array (
                'id' => 904,
                'name' => 'view Team',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:18',
                'updated_at' => '2024-08-20 07:28:18',
            ),
            404 => 
            array (
                'id' => 905,
                'name' => 'create Team',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:18',
                'updated_at' => '2024-08-20 07:28:18',
            ),
            405 => 
            array (
                'id' => 906,
                'name' => 'create Team',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:18',
                'updated_at' => '2024-08-20 07:28:18',
            ),
            406 => 
            array (
                'id' => 907,
                'name' => 'update Team',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:18',
                'updated_at' => '2024-08-20 07:28:18',
            ),
            407 => 
            array (
                'id' => 908,
                'name' => 'update Team',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:18',
                'updated_at' => '2024-08-20 07:28:18',
            ),
            408 => 
            array (
                'id' => 909,
                'name' => 'delete Team',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:19',
                'updated_at' => '2024-08-20 07:28:19',
            ),
            409 => 
            array (
                'id' => 910,
                'name' => 'delete Team',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:19',
                'updated_at' => '2024-08-20 07:28:19',
            ),
            410 => 
            array (
                'id' => 911,
                'name' => 'restore Team',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:19',
                'updated_at' => '2024-08-20 07:28:19',
            ),
            411 => 
            array (
                'id' => 912,
                'name' => 'restore Team',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:19',
                'updated_at' => '2024-08-20 07:28:19',
            ),
            412 => 
            array (
                'id' => 913,
                'name' => 'force-delete Team',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:19',
                'updated_at' => '2024-08-20 07:28:19',
            ),
            413 => 
            array (
                'id' => 914,
                'name' => 'force-delete Team',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:19',
                'updated_at' => '2024-08-20 07:28:19',
            ),
            414 => 
            array (
                'id' => 915,
                'name' => 'replicate Team',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:20',
                'updated_at' => '2024-08-20 07:28:20',
            ),
            415 => 
            array (
                'id' => 916,
                'name' => 'replicate Team',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:20',
                'updated_at' => '2024-08-20 07:28:20',
            ),
            416 => 
            array (
                'id' => 917,
                'name' => 'reorder Team',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:20',
                'updated_at' => '2024-08-20 07:28:20',
            ),
            417 => 
            array (
                'id' => 918,
                'name' => 'reorder Team',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:20',
                'updated_at' => '2024-08-20 07:28:20',
            ),
            418 => 
            array (
                'id' => 919,
                'name' => 'view-any TeamInvitation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:20',
                'updated_at' => '2024-08-20 07:28:20',
            ),
            419 => 
            array (
                'id' => 920,
                'name' => 'view-any TeamInvitation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:20',
                'updated_at' => '2024-08-20 07:28:20',
            ),
            420 => 
            array (
                'id' => 921,
                'name' => 'view TeamInvitation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:21',
                'updated_at' => '2024-08-20 07:28:21',
            ),
            421 => 
            array (
                'id' => 922,
                'name' => 'view TeamInvitation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:21',
                'updated_at' => '2024-08-20 07:28:21',
            ),
            422 => 
            array (
                'id' => 923,
                'name' => 'create TeamInvitation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:21',
                'updated_at' => '2024-08-20 07:28:21',
            ),
            423 => 
            array (
                'id' => 924,
                'name' => 'create TeamInvitation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:21',
                'updated_at' => '2024-08-20 07:28:21',
            ),
            424 => 
            array (
                'id' => 925,
                'name' => 'update TeamInvitation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:21',
                'updated_at' => '2024-08-20 07:28:21',
            ),
            425 => 
            array (
                'id' => 926,
                'name' => 'update TeamInvitation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:21',
                'updated_at' => '2024-08-20 07:28:21',
            ),
            426 => 
            array (
                'id' => 927,
                'name' => 'delete TeamInvitation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:22',
                'updated_at' => '2024-08-20 07:28:22',
            ),
            427 => 
            array (
                'id' => 928,
                'name' => 'delete TeamInvitation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:22',
                'updated_at' => '2024-08-20 07:28:22',
            ),
            428 => 
            array (
                'id' => 929,
                'name' => 'restore TeamInvitation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:22',
                'updated_at' => '2024-08-20 07:28:22',
            ),
            429 => 
            array (
                'id' => 930,
                'name' => 'restore TeamInvitation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:22',
                'updated_at' => '2024-08-20 07:28:22',
            ),
            430 => 
            array (
                'id' => 931,
                'name' => 'force-delete TeamInvitation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:22',
                'updated_at' => '2024-08-20 07:28:22',
            ),
            431 => 
            array (
                'id' => 932,
                'name' => 'force-delete TeamInvitation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:22',
                'updated_at' => '2024-08-20 07:28:22',
            ),
            432 => 
            array (
                'id' => 933,
                'name' => 'replicate TeamInvitation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:23',
                'updated_at' => '2024-08-20 07:28:23',
            ),
            433 => 
            array (
                'id' => 934,
                'name' => 'replicate TeamInvitation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:23',
                'updated_at' => '2024-08-20 07:28:23',
            ),
            434 => 
            array (
                'id' => 935,
                'name' => 'reorder TeamInvitation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:23',
                'updated_at' => '2024-08-20 07:28:23',
            ),
            435 => 
            array (
                'id' => 936,
                'name' => 'reorder TeamInvitation',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:23',
                'updated_at' => '2024-08-20 07:28:23',
            ),
            436 => 
            array (
                'id' => 937,
                'name' => 'view-any Tree',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:23',
                'updated_at' => '2024-08-20 07:28:23',
            ),
            437 => 
            array (
                'id' => 938,
                'name' => 'view-any Tree',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:23',
                'updated_at' => '2024-08-20 07:28:23',
            ),
            438 => 
            array (
                'id' => 939,
                'name' => 'view Tree',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:24',
                'updated_at' => '2024-08-20 07:28:24',
            ),
            439 => 
            array (
                'id' => 940,
                'name' => 'view Tree',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:24',
                'updated_at' => '2024-08-20 07:28:24',
            ),
            440 => 
            array (
                'id' => 941,
                'name' => 'create Tree',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:24',
                'updated_at' => '2024-08-20 07:28:24',
            ),
            441 => 
            array (
                'id' => 942,
                'name' => 'create Tree',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:24',
                'updated_at' => '2024-08-20 07:28:24',
            ),
            442 => 
            array (
                'id' => 943,
                'name' => 'update Tree',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:24',
                'updated_at' => '2024-08-20 07:28:24',
            ),
            443 => 
            array (
                'id' => 944,
                'name' => 'update Tree',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:24',
                'updated_at' => '2024-08-20 07:28:24',
            ),
            444 => 
            array (
                'id' => 945,
                'name' => 'delete Tree',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:25',
                'updated_at' => '2024-08-20 07:28:25',
            ),
            445 => 
            array (
                'id' => 946,
                'name' => 'delete Tree',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:25',
                'updated_at' => '2024-08-20 07:28:25',
            ),
            446 => 
            array (
                'id' => 947,
                'name' => 'restore Tree',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:25',
                'updated_at' => '2024-08-20 07:28:25',
            ),
            447 => 
            array (
                'id' => 948,
                'name' => 'restore Tree',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:25',
                'updated_at' => '2024-08-20 07:28:25',
            ),
            448 => 
            array (
                'id' => 949,
                'name' => 'force-delete Tree',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:25',
                'updated_at' => '2024-08-20 07:28:25',
            ),
            449 => 
            array (
                'id' => 950,
                'name' => 'force-delete Tree',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:25',
                'updated_at' => '2024-08-20 07:28:25',
            ),
            450 => 
            array (
                'id' => 951,
                'name' => 'replicate Tree',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:25',
                'updated_at' => '2024-08-20 07:28:25',
            ),
            451 => 
            array (
                'id' => 952,
                'name' => 'replicate Tree',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:26',
                'updated_at' => '2024-08-20 07:28:26',
            ),
            452 => 
            array (
                'id' => 953,
                'name' => 'reorder Tree',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:26',
                'updated_at' => '2024-08-20 07:28:26',
            ),
            453 => 
            array (
                'id' => 954,
                'name' => 'reorder Tree',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:26',
                'updated_at' => '2024-08-20 07:28:26',
            ),
            454 => 
            array (
                'id' => 955,
                'name' => 'view-any Type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:26',
                'updated_at' => '2024-08-20 07:28:26',
            ),
            455 => 
            array (
                'id' => 956,
                'name' => 'view-any Type',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:26',
                'updated_at' => '2024-08-20 07:28:26',
            ),
            456 => 
            array (
                'id' => 957,
                'name' => 'view Type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:26',
                'updated_at' => '2024-08-20 07:28:26',
            ),
            457 => 
            array (
                'id' => 958,
                'name' => 'view Type',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:27',
                'updated_at' => '2024-08-20 07:28:27',
            ),
            458 => 
            array (
                'id' => 959,
                'name' => 'create Type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:27',
                'updated_at' => '2024-08-20 07:28:27',
            ),
            459 => 
            array (
                'id' => 960,
                'name' => 'create Type',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:27',
                'updated_at' => '2024-08-20 07:28:27',
            ),
            460 => 
            array (
                'id' => 961,
                'name' => 'update Type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:27',
                'updated_at' => '2024-08-20 07:28:27',
            ),
            461 => 
            array (
                'id' => 962,
                'name' => 'update Type',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:27',
                'updated_at' => '2024-08-20 07:28:27',
            ),
            462 => 
            array (
                'id' => 963,
                'name' => 'delete Type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:27',
                'updated_at' => '2024-08-20 07:28:27',
            ),
            463 => 
            array (
                'id' => 964,
                'name' => 'delete Type',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:28',
                'updated_at' => '2024-08-20 07:28:28',
            ),
            464 => 
            array (
                'id' => 965,
                'name' => 'restore Type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:28',
                'updated_at' => '2024-08-20 07:28:28',
            ),
            465 => 
            array (
                'id' => 966,
                'name' => 'restore Type',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:28',
                'updated_at' => '2024-08-20 07:28:28',
            ),
            466 => 
            array (
                'id' => 967,
                'name' => 'force-delete Type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:28',
                'updated_at' => '2024-08-20 07:28:28',
            ),
            467 => 
            array (
                'id' => 968,
                'name' => 'force-delete Type',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:28',
                'updated_at' => '2024-08-20 07:28:28',
            ),
            468 => 
            array (
                'id' => 969,
                'name' => 'replicate Type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:28',
                'updated_at' => '2024-08-20 07:28:28',
            ),
            469 => 
            array (
                'id' => 970,
                'name' => 'replicate Type',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:29',
                'updated_at' => '2024-08-20 07:28:29',
            ),
            470 => 
            array (
                'id' => 971,
                'name' => 'reorder Type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:29',
                'updated_at' => '2024-08-20 07:28:29',
            ),
            471 => 
            array (
                'id' => 972,
                'name' => 'reorder Type',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:29',
                'updated_at' => '2024-08-20 07:28:29',
            ),
            472 => 
            array (
                'id' => 973,
                'name' => 'view-any User',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:29',
                'updated_at' => '2024-08-20 07:28:29',
            ),
            473 => 
            array (
                'id' => 974,
                'name' => 'view-any User',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:29',
                'updated_at' => '2024-08-20 07:28:29',
            ),
            474 => 
            array (
                'id' => 975,
                'name' => 'view User',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:29',
                'updated_at' => '2024-08-20 07:28:29',
            ),
            475 => 
            array (
                'id' => 976,
                'name' => 'view User',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:30',
                'updated_at' => '2024-08-20 07:28:30',
            ),
            476 => 
            array (
                'id' => 977,
                'name' => 'create User',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:30',
                'updated_at' => '2024-08-20 07:28:30',
            ),
            477 => 
            array (
                'id' => 978,
                'name' => 'create User',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:30',
                'updated_at' => '2024-08-20 07:28:30',
            ),
            478 => 
            array (
                'id' => 979,
                'name' => 'update User',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:30',
                'updated_at' => '2024-08-20 07:28:30',
            ),
            479 => 
            array (
                'id' => 980,
                'name' => 'update User',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:30',
                'updated_at' => '2024-08-20 07:28:30',
            ),
            480 => 
            array (
                'id' => 981,
                'name' => 'delete User',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:30',
                'updated_at' => '2024-08-20 07:28:30',
            ),
            481 => 
            array (
                'id' => 982,
                'name' => 'delete User',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:30',
                'updated_at' => '2024-08-20 07:28:30',
            ),
            482 => 
            array (
                'id' => 983,
                'name' => 'restore User',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:30',
                'updated_at' => '2024-08-20 07:28:30',
            ),
            483 => 
            array (
                'id' => 984,
                'name' => 'restore User',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:31',
                'updated_at' => '2024-08-20 07:28:31',
            ),
            484 => 
            array (
                'id' => 985,
                'name' => 'force-delete User',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:31',
                'updated_at' => '2024-08-20 07:28:31',
            ),
            485 => 
            array (
                'id' => 986,
                'name' => 'force-delete User',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:31',
                'updated_at' => '2024-08-20 07:28:31',
            ),
            486 => 
            array (
                'id' => 987,
                'name' => 'replicate User',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:31',
                'updated_at' => '2024-08-20 07:28:31',
            ),
            487 => 
            array (
                'id' => 988,
                'name' => 'replicate User',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:31',
                'updated_at' => '2024-08-20 07:28:31',
            ),
            488 => 
            array (
                'id' => 989,
                'name' => 'reorder User',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:31',
                'updated_at' => '2024-08-20 07:28:31',
            ),
            489 => 
            array (
                'id' => 990,
                'name' => 'reorder User',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:32',
                'updated_at' => '2024-08-20 07:28:32',
            ),
            490 => 
            array (
                'id' => 991,
                'name' => 'view-any UserSocial',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:32',
                'updated_at' => '2024-08-20 07:28:32',
            ),
            491 => 
            array (
                'id' => 992,
                'name' => 'view-any UserSocial',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:32',
                'updated_at' => '2024-08-20 07:28:32',
            ),
            492 => 
            array (
                'id' => 993,
                'name' => 'view UserSocial',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:32',
                'updated_at' => '2024-08-20 07:28:32',
            ),
            493 => 
            array (
                'id' => 994,
                'name' => 'view UserSocial',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:32',
                'updated_at' => '2024-08-20 07:28:32',
            ),
            494 => 
            array (
                'id' => 995,
                'name' => 'create UserSocial',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:32',
                'updated_at' => '2024-08-20 07:28:32',
            ),
            495 => 
            array (
                'id' => 996,
                'name' => 'create UserSocial',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:33',
                'updated_at' => '2024-08-20 07:28:33',
            ),
            496 => 
            array (
                'id' => 997,
                'name' => 'update UserSocial',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:33',
                'updated_at' => '2024-08-20 07:28:33',
            ),
            497 => 
            array (
                'id' => 998,
                'name' => 'update UserSocial',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:33',
                'updated_at' => '2024-08-20 07:28:33',
            ),
            498 => 
            array (
                'id' => 999,
                'name' => 'delete UserSocial',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:33',
                'updated_at' => '2024-08-20 07:28:33',
            ),
            499 => 
            array (
                'id' => 1000,
                'name' => 'delete UserSocial',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:33',
                'updated_at' => '2024-08-20 07:28:33',
            ),
        ));
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1001,
                'name' => 'restore UserSocial',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:33',
                'updated_at' => '2024-08-20 07:28:33',
            ),
            1 => 
            array (
                'id' => 1002,
                'name' => 'restore UserSocial',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:33',
                'updated_at' => '2024-08-20 07:28:33',
            ),
            2 => 
            array (
                'id' => 1003,
                'name' => 'force-delete UserSocial',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:33',
                'updated_at' => '2024-08-20 07:28:33',
            ),
            3 => 
            array (
                'id' => 1004,
                'name' => 'force-delete UserSocial',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:33',
                'updated_at' => '2024-08-20 07:28:33',
            ),
            4 => 
            array (
                'id' => 1005,
                'name' => 'replicate UserSocial',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:34',
                'updated_at' => '2024-08-20 07:28:34',
            ),
            5 => 
            array (
                'id' => 1006,
                'name' => 'replicate UserSocial',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:34',
                'updated_at' => '2024-08-20 07:28:34',
            ),
            6 => 
            array (
                'id' => 1007,
                'name' => 'reorder UserSocial',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:28:34',
                'updated_at' => '2024-08-20 07:28:34',
            ),
            7 => 
            array (
                'id' => 1008,
                'name' => 'reorder UserSocial',
                'guard_name' => 'api',
                'created_at' => '2024-08-20 07:28:34',
                'updated_at' => '2024-08-20 07:28:34',
            ),
            8 => 
            array (
                'id' => 1009,
                'name' => 'view_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:49',
                'updated_at' => '2024-08-20 07:31:49',
            ),
            9 => 
            array (
                'id' => 1010,
                'name' => 'view_any_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:49',
                'updated_at' => '2024-08-20 07:31:49',
            ),
            10 => 
            array (
                'id' => 1011,
                'name' => 'create_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:49',
                'updated_at' => '2024-08-20 07:31:49',
            ),
            11 => 
            array (
                'id' => 1012,
                'name' => 'update_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:49',
                'updated_at' => '2024-08-20 07:31:49',
            ),
            12 => 
            array (
                'id' => 1013,
                'name' => 'restore_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:50',
                'updated_at' => '2024-08-20 07:31:50',
            ),
            13 => 
            array (
                'id' => 1014,
                'name' => 'restore_any_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:50',
                'updated_at' => '2024-08-20 07:31:50',
            ),
            14 => 
            array (
                'id' => 1015,
                'name' => 'replicate_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:50',
                'updated_at' => '2024-08-20 07:31:50',
            ),
            15 => 
            array (
                'id' => 1016,
                'name' => 'reorder_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:50',
                'updated_at' => '2024-08-20 07:31:50',
            ),
            16 => 
            array (
                'id' => 1017,
                'name' => 'delete_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:50',
                'updated_at' => '2024-08-20 07:31:50',
            ),
            17 => 
            array (
                'id' => 1018,
                'name' => 'delete_any_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:50',
                'updated_at' => '2024-08-20 07:31:50',
            ),
            18 => 
            array (
                'id' => 1019,
                'name' => 'force_delete_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:50',
                'updated_at' => '2024-08-20 07:31:50',
            ),
            19 => 
            array (
                'id' => 1020,
                'name' => 'force_delete_any_addr',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:51',
                'updated_at' => '2024-08-20 07:31:51',
            ),
            20 => 
            array (
                'id' => 1021,
                'name' => 'view_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:51',
                'updated_at' => '2024-08-20 07:31:51',
            ),
            21 => 
            array (
                'id' => 1022,
                'name' => 'view_any_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:51',
                'updated_at' => '2024-08-20 07:31:51',
            ),
            22 => 
            array (
                'id' => 1023,
                'name' => 'create_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:51',
                'updated_at' => '2024-08-20 07:31:51',
            ),
            23 => 
            array (
                'id' => 1024,
                'name' => 'update_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:52',
                'updated_at' => '2024-08-20 07:31:52',
            ),
            24 => 
            array (
                'id' => 1025,
                'name' => 'restore_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:52',
                'updated_at' => '2024-08-20 07:31:52',
            ),
            25 => 
            array (
                'id' => 1026,
                'name' => 'restore_any_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:52',
                'updated_at' => '2024-08-20 07:31:52',
            ),
            26 => 
            array (
                'id' => 1027,
                'name' => 'replicate_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:52',
                'updated_at' => '2024-08-20 07:31:52',
            ),
            27 => 
            array (
                'id' => 1028,
                'name' => 'reorder_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:52',
                'updated_at' => '2024-08-20 07:31:52',
            ),
            28 => 
            array (
                'id' => 1029,
                'name' => 'delete_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:52',
                'updated_at' => '2024-08-20 07:31:52',
            ),
            29 => 
            array (
                'id' => 1030,
                'name' => 'delete_any_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:53',
                'updated_at' => '2024-08-20 07:31:53',
            ),
            30 => 
            array (
                'id' => 1031,
                'name' => 'force_delete_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:53',
                'updated_at' => '2024-08-20 07:31:53',
            ),
            31 => 
            array (
                'id' => 1032,
                'name' => 'force_delete_any_author',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:53',
                'updated_at' => '2024-08-20 07:31:53',
            ),
            32 => 
            array (
                'id' => 1033,
                'name' => 'view_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:53',
                'updated_at' => '2024-08-20 07:31:53',
            ),
            33 => 
            array (
                'id' => 1034,
                'name' => 'view_any_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:53',
                'updated_at' => '2024-08-20 07:31:53',
            ),
            34 => 
            array (
                'id' => 1035,
                'name' => 'create_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:53',
                'updated_at' => '2024-08-20 07:31:53',
            ),
            35 => 
            array (
                'id' => 1036,
                'name' => 'update_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:54',
                'updated_at' => '2024-08-20 07:31:54',
            ),
            36 => 
            array (
                'id' => 1037,
                'name' => 'restore_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:54',
                'updated_at' => '2024-08-20 07:31:54',
            ),
            37 => 
            array (
                'id' => 1038,
                'name' => 'restore_any_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:54',
                'updated_at' => '2024-08-20 07:31:54',
            ),
            38 => 
            array (
                'id' => 1039,
                'name' => 'replicate_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:54',
                'updated_at' => '2024-08-20 07:31:54',
            ),
            39 => 
            array (
                'id' => 1040,
                'name' => 'reorder_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:54',
                'updated_at' => '2024-08-20 07:31:54',
            ),
            40 => 
            array (
                'id' => 1041,
                'name' => 'delete_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:54',
                'updated_at' => '2024-08-20 07:31:54',
            ),
            41 => 
            array (
                'id' => 1042,
                'name' => 'delete_any_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:55',
                'updated_at' => '2024-08-20 07:31:55',
            ),
            42 => 
            array (
                'id' => 1043,
                'name' => 'force_delete_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:55',
                'updated_at' => '2024-08-20 07:31:55',
            ),
            43 => 
            array (
                'id' => 1044,
                'name' => 'force_delete_any_chan',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:55',
                'updated_at' => '2024-08-20 07:31:55',
            ),
            44 => 
            array (
                'id' => 1045,
                'name' => 'view_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:55',
                'updated_at' => '2024-08-20 07:31:55',
            ),
            45 => 
            array (
                'id' => 1046,
                'name' => 'view_any_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:55',
                'updated_at' => '2024-08-20 07:31:55',
            ),
            46 => 
            array (
                'id' => 1047,
                'name' => 'create_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:56',
                'updated_at' => '2024-08-20 07:31:56',
            ),
            47 => 
            array (
                'id' => 1048,
                'name' => 'update_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:56',
                'updated_at' => '2024-08-20 07:31:56',
            ),
            48 => 
            array (
                'id' => 1049,
                'name' => 'restore_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:56',
                'updated_at' => '2024-08-20 07:31:56',
            ),
            49 => 
            array (
                'id' => 1050,
                'name' => 'restore_any_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:56',
                'updated_at' => '2024-08-20 07:31:56',
            ),
            50 => 
            array (
                'id' => 1051,
                'name' => 'replicate_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:56',
                'updated_at' => '2024-08-20 07:31:56',
            ),
            51 => 
            array (
                'id' => 1052,
                'name' => 'reorder_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:56',
                'updated_at' => '2024-08-20 07:31:56',
            ),
            52 => 
            array (
                'id' => 1053,
                'name' => 'delete_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:56',
                'updated_at' => '2024-08-20 07:31:56',
            ),
            53 => 
            array (
                'id' => 1054,
                'name' => 'delete_any_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:57',
                'updated_at' => '2024-08-20 07:31:57',
            ),
            54 => 
            array (
                'id' => 1055,
                'name' => 'force_delete_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:57',
                'updated_at' => '2024-08-20 07:31:57',
            ),
            55 => 
            array (
                'id' => 1056,
                'name' => 'force_delete_any_citation',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:57',
                'updated_at' => '2024-08-20 07:31:57',
            ),
            56 => 
            array (
                'id' => 1057,
                'name' => 'view_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:57',
                'updated_at' => '2024-08-20 07:31:57',
            ),
            57 => 
            array (
                'id' => 1058,
                'name' => 'view_any_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:58',
                'updated_at' => '2024-08-20 07:31:58',
            ),
            58 => 
            array (
                'id' => 1059,
                'name' => 'create_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:58',
                'updated_at' => '2024-08-20 07:31:58',
            ),
            59 => 
            array (
                'id' => 1060,
                'name' => 'update_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:58',
                'updated_at' => '2024-08-20 07:31:58',
            ),
            60 => 
            array (
                'id' => 1061,
                'name' => 'restore_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:58',
                'updated_at' => '2024-08-20 07:31:58',
            ),
            61 => 
            array (
                'id' => 1062,
                'name' => 'restore_any_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:58',
                'updated_at' => '2024-08-20 07:31:58',
            ),
            62 => 
            array (
                'id' => 1063,
                'name' => 'replicate_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:58',
                'updated_at' => '2024-08-20 07:31:58',
            ),
            63 => 
            array (
                'id' => 1064,
                'name' => 'reorder_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:59',
                'updated_at' => '2024-08-20 07:31:59',
            ),
            64 => 
            array (
                'id' => 1065,
                'name' => 'delete_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:59',
                'updated_at' => '2024-08-20 07:31:59',
            ),
            65 => 
            array (
                'id' => 1066,
                'name' => 'delete_any_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:59',
                'updated_at' => '2024-08-20 07:31:59',
            ),
            66 => 
            array (
                'id' => 1067,
                'name' => 'force_delete_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:59',
                'updated_at' => '2024-08-20 07:31:59',
            ),
            67 => 
            array (
                'id' => 1068,
                'name' => 'force_delete_any_dna',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:59',
                'updated_at' => '2024-08-20 07:31:59',
            ),
            68 => 
            array (
                'id' => 1069,
                'name' => 'view_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:59',
                'updated_at' => '2024-08-20 07:31:59',
            ),
            69 => 
            array (
                'id' => 1070,
                'name' => 'view_any_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:31:59',
                'updated_at' => '2024-08-20 07:31:59',
            ),
            70 => 
            array (
                'id' => 1071,
                'name' => 'create_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:00',
                'updated_at' => '2024-08-20 07:32:00',
            ),
            71 => 
            array (
                'id' => 1072,
                'name' => 'update_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:00',
                'updated_at' => '2024-08-20 07:32:00',
            ),
            72 => 
            array (
                'id' => 1073,
                'name' => 'restore_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:00',
                'updated_at' => '2024-08-20 07:32:00',
            ),
            73 => 
            array (
                'id' => 1074,
                'name' => 'restore_any_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:00',
                'updated_at' => '2024-08-20 07:32:00',
            ),
            74 => 
            array (
                'id' => 1075,
                'name' => 'replicate_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:00',
                'updated_at' => '2024-08-20 07:32:00',
            ),
            75 => 
            array (
                'id' => 1076,
                'name' => 'reorder_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:00',
                'updated_at' => '2024-08-20 07:32:00',
            ),
            76 => 
            array (
                'id' => 1077,
                'name' => 'delete_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:00',
                'updated_at' => '2024-08-20 07:32:00',
            ),
            77 => 
            array (
                'id' => 1078,
                'name' => 'delete_any_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:01',
                'updated_at' => '2024-08-20 07:32:01',
            ),
            78 => 
            array (
                'id' => 1079,
                'name' => 'force_delete_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:01',
                'updated_at' => '2024-08-20 07:32:01',
            ),
            79 => 
            array (
                'id' => 1080,
                'name' => 'force_delete_any_dna::matching',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:01',
                'updated_at' => '2024-08-20 07:32:01',
            ),
            80 => 
            array (
                'id' => 1081,
                'name' => 'view_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:01',
                'updated_at' => '2024-08-20 07:32:01',
            ),
            81 => 
            array (
                'id' => 1082,
                'name' => 'view_any_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:01',
                'updated_at' => '2024-08-20 07:32:01',
            ),
            82 => 
            array (
                'id' => 1083,
                'name' => 'create_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:02',
                'updated_at' => '2024-08-20 07:32:02',
            ),
            83 => 
            array (
                'id' => 1084,
                'name' => 'update_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:02',
                'updated_at' => '2024-08-20 07:32:02',
            ),
            84 => 
            array (
                'id' => 1085,
                'name' => 'restore_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:02',
                'updated_at' => '2024-08-20 07:32:02',
            ),
            85 => 
            array (
                'id' => 1086,
                'name' => 'restore_any_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:03',
                'updated_at' => '2024-08-20 07:32:03',
            ),
            86 => 
            array (
                'id' => 1087,
                'name' => 'replicate_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:03',
                'updated_at' => '2024-08-20 07:32:03',
            ),
            87 => 
            array (
                'id' => 1088,
                'name' => 'reorder_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:03',
                'updated_at' => '2024-08-20 07:32:03',
            ),
            88 => 
            array (
                'id' => 1089,
                'name' => 'delete_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:03',
                'updated_at' => '2024-08-20 07:32:03',
            ),
            89 => 
            array (
                'id' => 1090,
                'name' => 'delete_any_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:03',
                'updated_at' => '2024-08-20 07:32:03',
            ),
            90 => 
            array (
                'id' => 1091,
                'name' => 'force_delete_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:03',
                'updated_at' => '2024-08-20 07:32:03',
            ),
            91 => 
            array (
                'id' => 1092,
                'name' => 'force_delete_any_family',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:04',
                'updated_at' => '2024-08-20 07:32:04',
            ),
            92 => 
            array (
                'id' => 1093,
                'name' => 'view_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:04',
                'updated_at' => '2024-08-20 07:32:04',
            ),
            93 => 
            array (
                'id' => 1094,
                'name' => 'view_any_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:04',
                'updated_at' => '2024-08-20 07:32:04',
            ),
            94 => 
            array (
                'id' => 1095,
                'name' => 'create_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:04',
                'updated_at' => '2024-08-20 07:32:04',
            ),
            95 => 
            array (
                'id' => 1096,
                'name' => 'update_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:05',
                'updated_at' => '2024-08-20 07:32:05',
            ),
            96 => 
            array (
                'id' => 1097,
                'name' => 'restore_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:05',
                'updated_at' => '2024-08-20 07:32:05',
            ),
            97 => 
            array (
                'id' => 1098,
                'name' => 'restore_any_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:05',
                'updated_at' => '2024-08-20 07:32:05',
            ),
            98 => 
            array (
                'id' => 1099,
                'name' => 'replicate_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:05',
                'updated_at' => '2024-08-20 07:32:05',
            ),
            99 => 
            array (
                'id' => 1100,
                'name' => 'reorder_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:05',
                'updated_at' => '2024-08-20 07:32:05',
            ),
            100 => 
            array (
                'id' => 1101,
                'name' => 'delete_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:05',
                'updated_at' => '2024-08-20 07:32:05',
            ),
            101 => 
            array (
                'id' => 1102,
                'name' => 'delete_any_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:05',
                'updated_at' => '2024-08-20 07:32:05',
            ),
            102 => 
            array (
                'id' => 1103,
                'name' => 'force_delete_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:05',
                'updated_at' => '2024-08-20 07:32:05',
            ),
            103 => 
            array (
                'id' => 1104,
                'name' => 'force_delete_any_family::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:06',
                'updated_at' => '2024-08-20 07:32:06',
            ),
            104 => 
            array (
                'id' => 1105,
                'name' => 'view_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:06',
                'updated_at' => '2024-08-20 07:32:06',
            ),
            105 => 
            array (
                'id' => 1106,
                'name' => 'view_any_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:06',
                'updated_at' => '2024-08-20 07:32:06',
            ),
            106 => 
            array (
                'id' => 1107,
                'name' => 'create_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:06',
                'updated_at' => '2024-08-20 07:32:06',
            ),
            107 => 
            array (
                'id' => 1108,
                'name' => 'update_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:06',
                'updated_at' => '2024-08-20 07:32:06',
            ),
            108 => 
            array (
                'id' => 1109,
                'name' => 'restore_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:07',
                'updated_at' => '2024-08-20 07:32:07',
            ),
            109 => 
            array (
                'id' => 1110,
                'name' => 'restore_any_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:07',
                'updated_at' => '2024-08-20 07:32:07',
            ),
            110 => 
            array (
                'id' => 1111,
                'name' => 'replicate_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:07',
                'updated_at' => '2024-08-20 07:32:07',
            ),
            111 => 
            array (
                'id' => 1112,
                'name' => 'reorder_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:07',
                'updated_at' => '2024-08-20 07:32:07',
            ),
            112 => 
            array (
                'id' => 1113,
                'name' => 'delete_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:07',
                'updated_at' => '2024-08-20 07:32:07',
            ),
            113 => 
            array (
                'id' => 1114,
                'name' => 'delete_any_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:07',
                'updated_at' => '2024-08-20 07:32:07',
            ),
            114 => 
            array (
                'id' => 1115,
                'name' => 'force_delete_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:07',
                'updated_at' => '2024-08-20 07:32:07',
            ),
            115 => 
            array (
                'id' => 1116,
                'name' => 'force_delete_any_family::slgs',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:07',
                'updated_at' => '2024-08-20 07:32:07',
            ),
            116 => 
            array (
                'id' => 1117,
                'name' => 'view_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:08',
                'updated_at' => '2024-08-20 07:32:08',
            ),
            117 => 
            array (
                'id' => 1118,
                'name' => 'view_any_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:08',
                'updated_at' => '2024-08-20 07:32:08',
            ),
            118 => 
            array (
                'id' => 1119,
                'name' => 'create_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:08',
                'updated_at' => '2024-08-20 07:32:08',
            ),
            119 => 
            array (
                'id' => 1120,
                'name' => 'update_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:08',
                'updated_at' => '2024-08-20 07:32:08',
            ),
            120 => 
            array (
                'id' => 1121,
                'name' => 'restore_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:08',
                'updated_at' => '2024-08-20 07:32:08',
            ),
            121 => 
            array (
                'id' => 1122,
                'name' => 'restore_any_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:09',
                'updated_at' => '2024-08-20 07:32:09',
            ),
            122 => 
            array (
                'id' => 1123,
                'name' => 'replicate_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:09',
                'updated_at' => '2024-08-20 07:32:09',
            ),
            123 => 
            array (
                'id' => 1124,
                'name' => 'reorder_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:09',
                'updated_at' => '2024-08-20 07:32:09',
            ),
            124 => 
            array (
                'id' => 1125,
                'name' => 'delete_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:09',
                'updated_at' => '2024-08-20 07:32:09',
            ),
            125 => 
            array (
                'id' => 1126,
                'name' => 'delete_any_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:09',
                'updated_at' => '2024-08-20 07:32:09',
            ),
            126 => 
            array (
                'id' => 1127,
                'name' => 'force_delete_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:09',
                'updated_at' => '2024-08-20 07:32:09',
            ),
            127 => 
            array (
                'id' => 1128,
                'name' => 'force_delete_any_gedcom',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:09',
                'updated_at' => '2024-08-20 07:32:09',
            ),
            128 => 
            array (
                'id' => 1129,
                'name' => 'view_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:10',
                'updated_at' => '2024-08-20 07:32:10',
            ),
            129 => 
            array (
                'id' => 1130,
                'name' => 'view_any_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:10',
                'updated_at' => '2024-08-20 07:32:10',
            ),
            130 => 
            array (
                'id' => 1131,
                'name' => 'create_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:10',
                'updated_at' => '2024-08-20 07:32:10',
            ),
            131 => 
            array (
                'id' => 1132,
                'name' => 'update_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:10',
                'updated_at' => '2024-08-20 07:32:10',
            ),
            132 => 
            array (
                'id' => 1133,
                'name' => 'restore_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:10',
                'updated_at' => '2024-08-20 07:32:10',
            ),
            133 => 
            array (
                'id' => 1134,
                'name' => 'restore_any_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:10',
                'updated_at' => '2024-08-20 07:32:10',
            ),
            134 => 
            array (
                'id' => 1135,
                'name' => 'replicate_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:10',
                'updated_at' => '2024-08-20 07:32:10',
            ),
            135 => 
            array (
                'id' => 1136,
                'name' => 'reorder_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:11',
                'updated_at' => '2024-08-20 07:32:11',
            ),
            136 => 
            array (
                'id' => 1137,
                'name' => 'delete_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:11',
                'updated_at' => '2024-08-20 07:32:11',
            ),
            137 => 
            array (
                'id' => 1138,
                'name' => 'delete_any_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:11',
                'updated_at' => '2024-08-20 07:32:11',
            ),
            138 => 
            array (
                'id' => 1139,
                'name' => 'force_delete_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:11',
                'updated_at' => '2024-08-20 07:32:11',
            ),
            139 => 
            array (
                'id' => 1140,
                'name' => 'force_delete_any_media::object',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:11',
                'updated_at' => '2024-08-20 07:32:11',
            ),
            140 => 
            array (
                'id' => 1141,
                'name' => 'view_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:11',
                'updated_at' => '2024-08-20 07:32:11',
            ),
            141 => 
            array (
                'id' => 1142,
                'name' => 'view_any_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:11',
                'updated_at' => '2024-08-20 07:32:11',
            ),
            142 => 
            array (
                'id' => 1143,
                'name' => 'create_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:12',
                'updated_at' => '2024-08-20 07:32:12',
            ),
            143 => 
            array (
                'id' => 1144,
                'name' => 'update_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:12',
                'updated_at' => '2024-08-20 07:32:12',
            ),
            144 => 
            array (
                'id' => 1145,
                'name' => 'restore_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:12',
                'updated_at' => '2024-08-20 07:32:12',
            ),
            145 => 
            array (
                'id' => 1146,
                'name' => 'restore_any_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:12',
                'updated_at' => '2024-08-20 07:32:12',
            ),
            146 => 
            array (
                'id' => 1147,
                'name' => 'replicate_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:12',
                'updated_at' => '2024-08-20 07:32:12',
            ),
            147 => 
            array (
                'id' => 1148,
                'name' => 'reorder_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:12',
                'updated_at' => '2024-08-20 07:32:12',
            ),
            148 => 
            array (
                'id' => 1149,
                'name' => 'delete_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:12',
                'updated_at' => '2024-08-20 07:32:12',
            ),
            149 => 
            array (
                'id' => 1150,
                'name' => 'delete_any_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:13',
                'updated_at' => '2024-08-20 07:32:13',
            ),
            150 => 
            array (
                'id' => 1151,
                'name' => 'force_delete_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:13',
                'updated_at' => '2024-08-20 07:32:13',
            ),
            151 => 
            array (
                'id' => 1152,
                'name' => 'force_delete_any_note',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:13',
                'updated_at' => '2024-08-20 07:32:13',
            ),
            152 => 
            array (
                'id' => 1153,
                'name' => 'view_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:13',
                'updated_at' => '2024-08-20 07:32:13',
            ),
            153 => 
            array (
                'id' => 1154,
                'name' => 'view_any_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:13',
                'updated_at' => '2024-08-20 07:32:13',
            ),
            154 => 
            array (
                'id' => 1155,
                'name' => 'create_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:13',
                'updated_at' => '2024-08-20 07:32:13',
            ),
            155 => 
            array (
                'id' => 1156,
                'name' => 'update_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:13',
                'updated_at' => '2024-08-20 07:32:13',
            ),
            156 => 
            array (
                'id' => 1157,
                'name' => 'restore_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:14',
                'updated_at' => '2024-08-20 07:32:14',
            ),
            157 => 
            array (
                'id' => 1158,
                'name' => 'restore_any_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:14',
                'updated_at' => '2024-08-20 07:32:14',
            ),
            158 => 
            array (
                'id' => 1159,
                'name' => 'replicate_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:14',
                'updated_at' => '2024-08-20 07:32:14',
            ),
            159 => 
            array (
                'id' => 1160,
                'name' => 'reorder_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:14',
                'updated_at' => '2024-08-20 07:32:14',
            ),
            160 => 
            array (
                'id' => 1161,
                'name' => 'delete_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:14',
                'updated_at' => '2024-08-20 07:32:14',
            ),
            161 => 
            array (
                'id' => 1162,
                'name' => 'delete_any_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:14',
                'updated_at' => '2024-08-20 07:32:14',
            ),
            162 => 
            array (
                'id' => 1163,
                'name' => 'force_delete_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:15',
                'updated_at' => '2024-08-20 07:32:15',
            ),
            163 => 
            array (
                'id' => 1164,
                'name' => 'force_delete_any_person',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:15',
                'updated_at' => '2024-08-20 07:32:15',
            ),
            164 => 
            array (
                'id' => 1165,
                'name' => 'view_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:15',
                'updated_at' => '2024-08-20 07:32:15',
            ),
            165 => 
            array (
                'id' => 1166,
                'name' => 'view_any_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:15',
                'updated_at' => '2024-08-20 07:32:15',
            ),
            166 => 
            array (
                'id' => 1167,
                'name' => 'create_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:15',
                'updated_at' => '2024-08-20 07:32:15',
            ),
            167 => 
            array (
                'id' => 1168,
                'name' => 'update_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:15',
                'updated_at' => '2024-08-20 07:32:15',
            ),
            168 => 
            array (
                'id' => 1169,
                'name' => 'restore_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:16',
                'updated_at' => '2024-08-20 07:32:16',
            ),
            169 => 
            array (
                'id' => 1170,
                'name' => 'restore_any_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:16',
                'updated_at' => '2024-08-20 07:32:16',
            ),
            170 => 
            array (
                'id' => 1171,
                'name' => 'replicate_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:16',
                'updated_at' => '2024-08-20 07:32:16',
            ),
            171 => 
            array (
                'id' => 1172,
                'name' => 'reorder_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:16',
                'updated_at' => '2024-08-20 07:32:16',
            ),
            172 => 
            array (
                'id' => 1173,
                'name' => 'delete_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:16',
                'updated_at' => '2024-08-20 07:32:16',
            ),
            173 => 
            array (
                'id' => 1174,
                'name' => 'delete_any_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:16',
                'updated_at' => '2024-08-20 07:32:16',
            ),
            174 => 
            array (
                'id' => 1175,
                'name' => 'force_delete_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:16',
                'updated_at' => '2024-08-20 07:32:16',
            ),
            175 => 
            array (
                'id' => 1176,
                'name' => 'force_delete_any_person::alia',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:16',
                'updated_at' => '2024-08-20 07:32:16',
            ),
            176 => 
            array (
                'id' => 1177,
                'name' => 'view_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:17',
                'updated_at' => '2024-08-20 07:32:17',
            ),
            177 => 
            array (
                'id' => 1178,
                'name' => 'view_any_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:17',
                'updated_at' => '2024-08-20 07:32:17',
            ),
            178 => 
            array (
                'id' => 1179,
                'name' => 'create_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:17',
                'updated_at' => '2024-08-20 07:32:17',
            ),
            179 => 
            array (
                'id' => 1180,
                'name' => 'update_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:17',
                'updated_at' => '2024-08-20 07:32:17',
            ),
            180 => 
            array (
                'id' => 1181,
                'name' => 'restore_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:17',
                'updated_at' => '2024-08-20 07:32:17',
            ),
            181 => 
            array (
                'id' => 1182,
                'name' => 'restore_any_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:17',
                'updated_at' => '2024-08-20 07:32:17',
            ),
            182 => 
            array (
                'id' => 1183,
                'name' => 'replicate_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:17',
                'updated_at' => '2024-08-20 07:32:17',
            ),
            183 => 
            array (
                'id' => 1184,
                'name' => 'reorder_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:18',
                'updated_at' => '2024-08-20 07:32:18',
            ),
            184 => 
            array (
                'id' => 1185,
                'name' => 'delete_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:18',
                'updated_at' => '2024-08-20 07:32:18',
            ),
            185 => 
            array (
                'id' => 1186,
                'name' => 'delete_any_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:18',
                'updated_at' => '2024-08-20 07:32:18',
            ),
            186 => 
            array (
                'id' => 1187,
                'name' => 'force_delete_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:18',
                'updated_at' => '2024-08-20 07:32:18',
            ),
            187 => 
            array (
                'id' => 1188,
                'name' => 'force_delete_any_person::anci',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:18',
                'updated_at' => '2024-08-20 07:32:18',
            ),
            188 => 
            array (
                'id' => 1189,
                'name' => 'view_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:19',
                'updated_at' => '2024-08-20 07:32:19',
            ),
            189 => 
            array (
                'id' => 1190,
                'name' => 'view_any_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:19',
                'updated_at' => '2024-08-20 07:32:19',
            ),
            190 => 
            array (
                'id' => 1191,
                'name' => 'create_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:19',
                'updated_at' => '2024-08-20 07:32:19',
            ),
            191 => 
            array (
                'id' => 1192,
                'name' => 'update_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:19',
                'updated_at' => '2024-08-20 07:32:19',
            ),
            192 => 
            array (
                'id' => 1193,
                'name' => 'restore_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:19',
                'updated_at' => '2024-08-20 07:32:19',
            ),
            193 => 
            array (
                'id' => 1194,
                'name' => 'restore_any_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:19',
                'updated_at' => '2024-08-20 07:32:19',
            ),
            194 => 
            array (
                'id' => 1195,
                'name' => 'replicate_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:19',
                'updated_at' => '2024-08-20 07:32:19',
            ),
            195 => 
            array (
                'id' => 1196,
                'name' => 'reorder_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:20',
                'updated_at' => '2024-08-20 07:32:20',
            ),
            196 => 
            array (
                'id' => 1197,
                'name' => 'delete_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:20',
                'updated_at' => '2024-08-20 07:32:20',
            ),
            197 => 
            array (
                'id' => 1198,
                'name' => 'delete_any_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:20',
                'updated_at' => '2024-08-20 07:32:20',
            ),
            198 => 
            array (
                'id' => 1199,
                'name' => 'force_delete_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:20',
                'updated_at' => '2024-08-20 07:32:20',
            ),
            199 => 
            array (
                'id' => 1200,
                'name' => 'force_delete_any_person::asso',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:20',
                'updated_at' => '2024-08-20 07:32:20',
            ),
            200 => 
            array (
                'id' => 1201,
                'name' => 'view_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:20',
                'updated_at' => '2024-08-20 07:32:20',
            ),
            201 => 
            array (
                'id' => 1202,
                'name' => 'view_any_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:20',
                'updated_at' => '2024-08-20 07:32:20',
            ),
            202 => 
            array (
                'id' => 1203,
                'name' => 'create_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:21',
                'updated_at' => '2024-08-20 07:32:21',
            ),
            203 => 
            array (
                'id' => 1204,
                'name' => 'update_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:21',
                'updated_at' => '2024-08-20 07:32:21',
            ),
            204 => 
            array (
                'id' => 1205,
                'name' => 'restore_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:21',
                'updated_at' => '2024-08-20 07:32:21',
            ),
            205 => 
            array (
                'id' => 1206,
                'name' => 'restore_any_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:21',
                'updated_at' => '2024-08-20 07:32:21',
            ),
            206 => 
            array (
                'id' => 1207,
                'name' => 'replicate_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:21',
                'updated_at' => '2024-08-20 07:32:21',
            ),
            207 => 
            array (
                'id' => 1208,
                'name' => 'reorder_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:21',
                'updated_at' => '2024-08-20 07:32:21',
            ),
            208 => 
            array (
                'id' => 1209,
                'name' => 'delete_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:21',
                'updated_at' => '2024-08-20 07:32:21',
            ),
            209 => 
            array (
                'id' => 1210,
                'name' => 'delete_any_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:22',
                'updated_at' => '2024-08-20 07:32:22',
            ),
            210 => 
            array (
                'id' => 1211,
                'name' => 'force_delete_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:22',
                'updated_at' => '2024-08-20 07:32:22',
            ),
            211 => 
            array (
                'id' => 1212,
                'name' => 'force_delete_any_person::event',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:22',
                'updated_at' => '2024-08-20 07:32:22',
            ),
            212 => 
            array (
                'id' => 1213,
                'name' => 'view_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:22',
                'updated_at' => '2024-08-20 07:32:22',
            ),
            213 => 
            array (
                'id' => 1214,
                'name' => 'view_any_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:22',
                'updated_at' => '2024-08-20 07:32:22',
            ),
            214 => 
            array (
                'id' => 1215,
                'name' => 'create_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:23',
                'updated_at' => '2024-08-20 07:32:23',
            ),
            215 => 
            array (
                'id' => 1216,
                'name' => 'update_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:23',
                'updated_at' => '2024-08-20 07:32:23',
            ),
            216 => 
            array (
                'id' => 1217,
                'name' => 'restore_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:23',
                'updated_at' => '2024-08-20 07:32:23',
            ),
            217 => 
            array (
                'id' => 1218,
                'name' => 'restore_any_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:23',
                'updated_at' => '2024-08-20 07:32:23',
            ),
            218 => 
            array (
                'id' => 1219,
                'name' => 'replicate_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:23',
                'updated_at' => '2024-08-20 07:32:23',
            ),
            219 => 
            array (
                'id' => 1220,
                'name' => 'reorder_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:23',
                'updated_at' => '2024-08-20 07:32:23',
            ),
            220 => 
            array (
                'id' => 1221,
                'name' => 'delete_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:23',
                'updated_at' => '2024-08-20 07:32:23',
            ),
            221 => 
            array (
                'id' => 1222,
                'name' => 'delete_any_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:24',
                'updated_at' => '2024-08-20 07:32:24',
            ),
            222 => 
            array (
                'id' => 1223,
                'name' => 'force_delete_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:24',
                'updated_at' => '2024-08-20 07:32:24',
            ),
            223 => 
            array (
                'id' => 1224,
                'name' => 'force_delete_any_person::lds',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:24',
                'updated_at' => '2024-08-20 07:32:24',
            ),
            224 => 
            array (
                'id' => 1225,
                'name' => 'view_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:24',
                'updated_at' => '2024-08-20 07:32:24',
            ),
            225 => 
            array (
                'id' => 1226,
                'name' => 'view_any_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:24',
                'updated_at' => '2024-08-20 07:32:24',
            ),
            226 => 
            array (
                'id' => 1227,
                'name' => 'create_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:25',
                'updated_at' => '2024-08-20 07:32:25',
            ),
            227 => 
            array (
                'id' => 1228,
                'name' => 'update_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:25',
                'updated_at' => '2024-08-20 07:32:25',
            ),
            228 => 
            array (
                'id' => 1229,
                'name' => 'restore_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:25',
                'updated_at' => '2024-08-20 07:32:25',
            ),
            229 => 
            array (
                'id' => 1230,
                'name' => 'restore_any_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:25',
                'updated_at' => '2024-08-20 07:32:25',
            ),
            230 => 
            array (
                'id' => 1231,
                'name' => 'replicate_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:25',
                'updated_at' => '2024-08-20 07:32:25',
            ),
            231 => 
            array (
                'id' => 1232,
                'name' => 'reorder_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:26',
                'updated_at' => '2024-08-20 07:32:26',
            ),
            232 => 
            array (
                'id' => 1233,
                'name' => 'delete_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:26',
                'updated_at' => '2024-08-20 07:32:26',
            ),
            233 => 
            array (
                'id' => 1234,
                'name' => 'delete_any_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:26',
                'updated_at' => '2024-08-20 07:32:26',
            ),
            234 => 
            array (
                'id' => 1235,
                'name' => 'force_delete_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:26',
                'updated_at' => '2024-08-20 07:32:26',
            ),
            235 => 
            array (
                'id' => 1236,
                'name' => 'force_delete_any_person::name',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:26',
                'updated_at' => '2024-08-20 07:32:26',
            ),
            236 => 
            array (
                'id' => 1237,
                'name' => 'view_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:26',
                'updated_at' => '2024-08-20 07:32:26',
            ),
            237 => 
            array (
                'id' => 1238,
                'name' => 'view_any_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:26',
                'updated_at' => '2024-08-20 07:32:26',
            ),
            238 => 
            array (
                'id' => 1239,
                'name' => 'create_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:27',
                'updated_at' => '2024-08-20 07:32:27',
            ),
            239 => 
            array (
                'id' => 1240,
                'name' => 'update_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:27',
                'updated_at' => '2024-08-20 07:32:27',
            ),
            240 => 
            array (
                'id' => 1241,
                'name' => 'restore_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:27',
                'updated_at' => '2024-08-20 07:32:27',
            ),
            241 => 
            array (
                'id' => 1242,
                'name' => 'restore_any_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:27',
                'updated_at' => '2024-08-20 07:32:27',
            ),
            242 => 
            array (
                'id' => 1243,
                'name' => 'replicate_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:27',
                'updated_at' => '2024-08-20 07:32:27',
            ),
            243 => 
            array (
                'id' => 1244,
                'name' => 'reorder_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:27',
                'updated_at' => '2024-08-20 07:32:27',
            ),
            244 => 
            array (
                'id' => 1245,
                'name' => 'delete_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:27',
                'updated_at' => '2024-08-20 07:32:27',
            ),
            245 => 
            array (
                'id' => 1246,
                'name' => 'delete_any_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:28',
                'updated_at' => '2024-08-20 07:32:28',
            ),
            246 => 
            array (
                'id' => 1247,
                'name' => 'force_delete_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:28',
                'updated_at' => '2024-08-20 07:32:28',
            ),
            247 => 
            array (
                'id' => 1248,
                'name' => 'force_delete_any_person::name::fone',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:28',
                'updated_at' => '2024-08-20 07:32:28',
            ),
            248 => 
            array (
                'id' => 1249,
                'name' => 'view_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:28',
                'updated_at' => '2024-08-20 07:32:28',
            ),
            249 => 
            array (
                'id' => 1250,
                'name' => 'view_any_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:28',
                'updated_at' => '2024-08-20 07:32:28',
            ),
            250 => 
            array (
                'id' => 1251,
                'name' => 'create_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:28',
                'updated_at' => '2024-08-20 07:32:28',
            ),
            251 => 
            array (
                'id' => 1252,
                'name' => 'update_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:29',
                'updated_at' => '2024-08-20 07:32:29',
            ),
            252 => 
            array (
                'id' => 1253,
                'name' => 'restore_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:29',
                'updated_at' => '2024-08-20 07:32:29',
            ),
            253 => 
            array (
                'id' => 1254,
                'name' => 'restore_any_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:29',
                'updated_at' => '2024-08-20 07:32:29',
            ),
            254 => 
            array (
                'id' => 1255,
                'name' => 'replicate_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:29',
                'updated_at' => '2024-08-20 07:32:29',
            ),
            255 => 
            array (
                'id' => 1256,
                'name' => 'reorder_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:29',
                'updated_at' => '2024-08-20 07:32:29',
            ),
            256 => 
            array (
                'id' => 1257,
                'name' => 'delete_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:29',
                'updated_at' => '2024-08-20 07:32:29',
            ),
            257 => 
            array (
                'id' => 1258,
                'name' => 'delete_any_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:29',
                'updated_at' => '2024-08-20 07:32:29',
            ),
            258 => 
            array (
                'id' => 1259,
                'name' => 'force_delete_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:29',
                'updated_at' => '2024-08-20 07:32:29',
            ),
            259 => 
            array (
                'id' => 1260,
                'name' => 'force_delete_any_person::name::romn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:30',
                'updated_at' => '2024-08-20 07:32:30',
            ),
            260 => 
            array (
                'id' => 1261,
                'name' => 'view_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:30',
                'updated_at' => '2024-08-20 07:32:30',
            ),
            261 => 
            array (
                'id' => 1262,
                'name' => 'view_any_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:30',
                'updated_at' => '2024-08-20 07:32:30',
            ),
            262 => 
            array (
                'id' => 1263,
                'name' => 'create_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:30',
                'updated_at' => '2024-08-20 07:32:30',
            ),
            263 => 
            array (
                'id' => 1264,
                'name' => 'update_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:30',
                'updated_at' => '2024-08-20 07:32:30',
            ),
            264 => 
            array (
                'id' => 1265,
                'name' => 'restore_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:31',
                'updated_at' => '2024-08-20 07:32:31',
            ),
            265 => 
            array (
                'id' => 1266,
                'name' => 'restore_any_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:31',
                'updated_at' => '2024-08-20 07:32:31',
            ),
            266 => 
            array (
                'id' => 1267,
                'name' => 'replicate_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:31',
                'updated_at' => '2024-08-20 07:32:31',
            ),
            267 => 
            array (
                'id' => 1268,
                'name' => 'reorder_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:31',
                'updated_at' => '2024-08-20 07:32:31',
            ),
            268 => 
            array (
                'id' => 1269,
                'name' => 'delete_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:31',
                'updated_at' => '2024-08-20 07:32:31',
            ),
            269 => 
            array (
                'id' => 1270,
                'name' => 'delete_any_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:31',
                'updated_at' => '2024-08-20 07:32:31',
            ),
            270 => 
            array (
                'id' => 1271,
                'name' => 'force_delete_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:31',
                'updated_at' => '2024-08-20 07:32:31',
            ),
            271 => 
            array (
                'id' => 1272,
                'name' => 'force_delete_any_person::subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:32',
                'updated_at' => '2024-08-20 07:32:32',
            ),
            272 => 
            array (
                'id' => 1273,
                'name' => 'view_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:32',
                'updated_at' => '2024-08-20 07:32:32',
            ),
            273 => 
            array (
                'id' => 1274,
                'name' => 'view_any_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:32',
                'updated_at' => '2024-08-20 07:32:32',
            ),
            274 => 
            array (
                'id' => 1275,
                'name' => 'create_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:32',
                'updated_at' => '2024-08-20 07:32:32',
            ),
            275 => 
            array (
                'id' => 1276,
                'name' => 'update_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:33',
                'updated_at' => '2024-08-20 07:32:33',
            ),
            276 => 
            array (
                'id' => 1277,
                'name' => 'restore_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:33',
                'updated_at' => '2024-08-20 07:32:33',
            ),
            277 => 
            array (
                'id' => 1278,
                'name' => 'restore_any_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:33',
                'updated_at' => '2024-08-20 07:32:33',
            ),
            278 => 
            array (
                'id' => 1279,
                'name' => 'replicate_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:33',
                'updated_at' => '2024-08-20 07:32:33',
            ),
            279 => 
            array (
                'id' => 1280,
                'name' => 'reorder_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:33',
                'updated_at' => '2024-08-20 07:32:33',
            ),
            280 => 
            array (
                'id' => 1281,
                'name' => 'delete_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:33',
                'updated_at' => '2024-08-20 07:32:33',
            ),
            281 => 
            array (
                'id' => 1282,
                'name' => 'delete_any_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:34',
                'updated_at' => '2024-08-20 07:32:34',
            ),
            282 => 
            array (
                'id' => 1283,
                'name' => 'force_delete_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:34',
                'updated_at' => '2024-08-20 07:32:34',
            ),
            283 => 
            array (
                'id' => 1284,
                'name' => 'force_delete_any_place',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:34',
                'updated_at' => '2024-08-20 07:32:34',
            ),
            284 => 
            array (
                'id' => 1285,
                'name' => 'view_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:34',
                'updated_at' => '2024-08-20 07:32:34',
            ),
            285 => 
            array (
                'id' => 1286,
                'name' => 'view_any_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:35',
                'updated_at' => '2024-08-20 07:32:35',
            ),
            286 => 
            array (
                'id' => 1287,
                'name' => 'create_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:35',
                'updated_at' => '2024-08-20 07:32:35',
            ),
            287 => 
            array (
                'id' => 1288,
                'name' => 'update_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:35',
                'updated_at' => '2024-08-20 07:32:35',
            ),
            288 => 
            array (
                'id' => 1289,
                'name' => 'restore_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:35',
                'updated_at' => '2024-08-20 07:32:35',
            ),
            289 => 
            array (
                'id' => 1290,
                'name' => 'restore_any_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:35',
                'updated_at' => '2024-08-20 07:32:35',
            ),
            290 => 
            array (
                'id' => 1291,
                'name' => 'replicate_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:35',
                'updated_at' => '2024-08-20 07:32:35',
            ),
            291 => 
            array (
                'id' => 1292,
                'name' => 'reorder_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:35',
                'updated_at' => '2024-08-20 07:32:35',
            ),
            292 => 
            array (
                'id' => 1293,
                'name' => 'delete_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:36',
                'updated_at' => '2024-08-20 07:32:36',
            ),
            293 => 
            array (
                'id' => 1294,
                'name' => 'delete_any_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:36',
                'updated_at' => '2024-08-20 07:32:36',
            ),
            294 => 
            array (
                'id' => 1295,
                'name' => 'force_delete_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:36',
                'updated_at' => '2024-08-20 07:32:36',
            ),
            295 => 
            array (
                'id' => 1296,
                'name' => 'force_delete_any_publication',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:36',
                'updated_at' => '2024-08-20 07:32:36',
            ),
            296 => 
            array (
                'id' => 1297,
                'name' => 'view_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:36',
                'updated_at' => '2024-08-20 07:32:36',
            ),
            297 => 
            array (
                'id' => 1298,
                'name' => 'view_any_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:37',
                'updated_at' => '2024-08-20 07:32:37',
            ),
            298 => 
            array (
                'id' => 1299,
                'name' => 'create_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:37',
                'updated_at' => '2024-08-20 07:32:37',
            ),
            299 => 
            array (
                'id' => 1300,
                'name' => 'update_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:37',
                'updated_at' => '2024-08-20 07:32:37',
            ),
            300 => 
            array (
                'id' => 1301,
                'name' => 'restore_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:37',
                'updated_at' => '2024-08-20 07:32:37',
            ),
            301 => 
            array (
                'id' => 1302,
                'name' => 'restore_any_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:37',
                'updated_at' => '2024-08-20 07:32:37',
            ),
            302 => 
            array (
                'id' => 1303,
                'name' => 'replicate_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:37',
                'updated_at' => '2024-08-20 07:32:37',
            ),
            303 => 
            array (
                'id' => 1304,
                'name' => 'reorder_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:37',
                'updated_at' => '2024-08-20 07:32:37',
            ),
            304 => 
            array (
                'id' => 1305,
                'name' => 'delete_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:37',
                'updated_at' => '2024-08-20 07:32:37',
            ),
            305 => 
            array (
                'id' => 1306,
                'name' => 'delete_any_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:38',
                'updated_at' => '2024-08-20 07:32:38',
            ),
            306 => 
            array (
                'id' => 1307,
                'name' => 'force_delete_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:38',
                'updated_at' => '2024-08-20 07:32:38',
            ),
            307 => 
            array (
                'id' => 1308,
                'name' => 'force_delete_any_refn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:38',
                'updated_at' => '2024-08-20 07:32:38',
            ),
            308 => 
            array (
                'id' => 1309,
                'name' => 'view_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:38',
                'updated_at' => '2024-08-20 07:32:38',
            ),
            309 => 
            array (
                'id' => 1310,
                'name' => 'view_any_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:38',
                'updated_at' => '2024-08-20 07:32:38',
            ),
            310 => 
            array (
                'id' => 1311,
                'name' => 'create_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:39',
                'updated_at' => '2024-08-20 07:32:39',
            ),
            311 => 
            array (
                'id' => 1312,
                'name' => 'update_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:39',
                'updated_at' => '2024-08-20 07:32:39',
            ),
            312 => 
            array (
                'id' => 1313,
                'name' => 'restore_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:39',
                'updated_at' => '2024-08-20 07:32:39',
            ),
            313 => 
            array (
                'id' => 1314,
                'name' => 'restore_any_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:39',
                'updated_at' => '2024-08-20 07:32:39',
            ),
            314 => 
            array (
                'id' => 1315,
                'name' => 'replicate_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:39',
                'updated_at' => '2024-08-20 07:32:39',
            ),
            315 => 
            array (
                'id' => 1316,
                'name' => 'reorder_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:39',
                'updated_at' => '2024-08-20 07:32:39',
            ),
            316 => 
            array (
                'id' => 1317,
                'name' => 'delete_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:39',
                'updated_at' => '2024-08-20 07:32:39',
            ),
            317 => 
            array (
                'id' => 1318,
                'name' => 'delete_any_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:39',
                'updated_at' => '2024-08-20 07:32:39',
            ),
            318 => 
            array (
                'id' => 1319,
                'name' => 'force_delete_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:40',
                'updated_at' => '2024-08-20 07:32:40',
            ),
            319 => 
            array (
                'id' => 1320,
                'name' => 'force_delete_any_repository',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:40',
                'updated_at' => '2024-08-20 07:32:40',
            ),
            320 => 
            array (
                'id' => 1321,
                'name' => 'view_role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:40',
                'updated_at' => '2024-08-20 07:32:40',
            ),
            321 => 
            array (
                'id' => 1322,
                'name' => 'view_any_role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:40',
                'updated_at' => '2024-08-20 07:32:40',
            ),
            322 => 
            array (
                'id' => 1323,
                'name' => 'create_role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:40',
                'updated_at' => '2024-08-20 07:32:40',
            ),
            323 => 
            array (
                'id' => 1324,
                'name' => 'update_role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:40',
                'updated_at' => '2024-08-20 07:32:40',
            ),
            324 => 
            array (
                'id' => 1325,
                'name' => 'delete_role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:40',
                'updated_at' => '2024-08-20 07:32:40',
            ),
            325 => 
            array (
                'id' => 1326,
                'name' => 'delete_any_role',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:41',
                'updated_at' => '2024-08-20 07:32:41',
            ),
            326 => 
            array (
                'id' => 1327,
                'name' => 'view_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:41',
                'updated_at' => '2024-08-20 07:32:41',
            ),
            327 => 
            array (
                'id' => 1328,
                'name' => 'view_any_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:41',
                'updated_at' => '2024-08-20 07:32:41',
            ),
            328 => 
            array (
                'id' => 1329,
                'name' => 'create_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:41',
                'updated_at' => '2024-08-20 07:32:41',
            ),
            329 => 
            array (
                'id' => 1330,
                'name' => 'update_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:41',
                'updated_at' => '2024-08-20 07:32:41',
            ),
            330 => 
            array (
                'id' => 1331,
                'name' => 'restore_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:41',
                'updated_at' => '2024-08-20 07:32:41',
            ),
            331 => 
            array (
                'id' => 1332,
                'name' => 'restore_any_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:41',
                'updated_at' => '2024-08-20 07:32:41',
            ),
            332 => 
            array (
                'id' => 1333,
                'name' => 'replicate_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:42',
                'updated_at' => '2024-08-20 07:32:42',
            ),
            333 => 
            array (
                'id' => 1334,
                'name' => 'reorder_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:42',
                'updated_at' => '2024-08-20 07:32:42',
            ),
            334 => 
            array (
                'id' => 1335,
                'name' => 'delete_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:42',
                'updated_at' => '2024-08-20 07:32:42',
            ),
            335 => 
            array (
                'id' => 1336,
                'name' => 'delete_any_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:42',
                'updated_at' => '2024-08-20 07:32:42',
            ),
            336 => 
            array (
                'id' => 1337,
                'name' => 'force_delete_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:42',
                'updated_at' => '2024-08-20 07:32:42',
            ),
            337 => 
            array (
                'id' => 1338,
                'name' => 'force_delete_any_source',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:42',
                'updated_at' => '2024-08-20 07:32:42',
            ),
            338 => 
            array (
                'id' => 1339,
                'name' => 'view_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:43',
                'updated_at' => '2024-08-20 07:32:43',
            ),
            339 => 
            array (
                'id' => 1340,
                'name' => 'view_any_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:43',
                'updated_at' => '2024-08-20 07:32:43',
            ),
            340 => 
            array (
                'id' => 1341,
                'name' => 'create_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:43',
                'updated_at' => '2024-08-20 07:32:43',
            ),
            341 => 
            array (
                'id' => 1342,
                'name' => 'update_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:43',
                'updated_at' => '2024-08-20 07:32:43',
            ),
            342 => 
            array (
                'id' => 1343,
                'name' => 'restore_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:43',
                'updated_at' => '2024-08-20 07:32:43',
            ),
            343 => 
            array (
                'id' => 1344,
                'name' => 'restore_any_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:43',
                'updated_at' => '2024-08-20 07:32:43',
            ),
            344 => 
            array (
                'id' => 1345,
                'name' => 'replicate_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:44',
                'updated_at' => '2024-08-20 07:32:44',
            ),
            345 => 
            array (
                'id' => 1346,
                'name' => 'reorder_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:44',
                'updated_at' => '2024-08-20 07:32:44',
            ),
            346 => 
            array (
                'id' => 1347,
                'name' => 'delete_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:44',
                'updated_at' => '2024-08-20 07:32:44',
            ),
            347 => 
            array (
                'id' => 1348,
                'name' => 'delete_any_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:44',
                'updated_at' => '2024-08-20 07:32:44',
            ),
            348 => 
            array (
                'id' => 1349,
                'name' => 'force_delete_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:44',
                'updated_at' => '2024-08-20 07:32:44',
            ),
            349 => 
            array (
                'id' => 1350,
                'name' => 'force_delete_any_source::data',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:44',
                'updated_at' => '2024-08-20 07:32:44',
            ),
            350 => 
            array (
                'id' => 1351,
                'name' => 'view_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:44',
                'updated_at' => '2024-08-20 07:32:44',
            ),
            351 => 
            array (
                'id' => 1352,
                'name' => 'view_any_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:45',
                'updated_at' => '2024-08-20 07:32:45',
            ),
            352 => 
            array (
                'id' => 1353,
                'name' => 'create_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:45',
                'updated_at' => '2024-08-20 07:32:45',
            ),
            353 => 
            array (
                'id' => 1354,
                'name' => 'update_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:45',
                'updated_at' => '2024-08-20 07:32:45',
            ),
            354 => 
            array (
                'id' => 1355,
                'name' => 'restore_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:45',
                'updated_at' => '2024-08-20 07:32:45',
            ),
            355 => 
            array (
                'id' => 1356,
                'name' => 'restore_any_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:45',
                'updated_at' => '2024-08-20 07:32:45',
            ),
            356 => 
            array (
                'id' => 1357,
                'name' => 'replicate_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:45',
                'updated_at' => '2024-08-20 07:32:45',
            ),
            357 => 
            array (
                'id' => 1358,
                'name' => 'reorder_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:46',
                'updated_at' => '2024-08-20 07:32:46',
            ),
            358 => 
            array (
                'id' => 1359,
                'name' => 'delete_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:46',
                'updated_at' => '2024-08-20 07:32:46',
            ),
            359 => 
            array (
                'id' => 1360,
                'name' => 'delete_any_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:46',
                'updated_at' => '2024-08-20 07:32:46',
            ),
            360 => 
            array (
                'id' => 1361,
                'name' => 'force_delete_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:46',
                'updated_at' => '2024-08-20 07:32:46',
            ),
            361 => 
            array (
                'id' => 1362,
                'name' => 'force_delete_any_source::data::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:46',
                'updated_at' => '2024-08-20 07:32:46',
            ),
            362 => 
            array (
                'id' => 1363,
                'name' => 'view_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:46',
                'updated_at' => '2024-08-20 07:32:46',
            ),
            363 => 
            array (
                'id' => 1364,
                'name' => 'view_any_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:47',
                'updated_at' => '2024-08-20 07:32:47',
            ),
            364 => 
            array (
                'id' => 1365,
                'name' => 'create_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:47',
                'updated_at' => '2024-08-20 07:32:47',
            ),
            365 => 
            array (
                'id' => 1366,
                'name' => 'update_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:47',
                'updated_at' => '2024-08-20 07:32:47',
            ),
            366 => 
            array (
                'id' => 1367,
                'name' => 'restore_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:47',
                'updated_at' => '2024-08-20 07:32:47',
            ),
            367 => 
            array (
                'id' => 1368,
                'name' => 'restore_any_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:47',
                'updated_at' => '2024-08-20 07:32:47',
            ),
            368 => 
            array (
                'id' => 1369,
                'name' => 'replicate_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:47',
                'updated_at' => '2024-08-20 07:32:47',
            ),
            369 => 
            array (
                'id' => 1370,
                'name' => 'reorder_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:47',
                'updated_at' => '2024-08-20 07:32:47',
            ),
            370 => 
            array (
                'id' => 1371,
                'name' => 'delete_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:48',
                'updated_at' => '2024-08-20 07:32:48',
            ),
            371 => 
            array (
                'id' => 1372,
                'name' => 'delete_any_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:48',
                'updated_at' => '2024-08-20 07:32:48',
            ),
            372 => 
            array (
                'id' => 1373,
                'name' => 'force_delete_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:48',
                'updated_at' => '2024-08-20 07:32:48',
            ),
            373 => 
            array (
                'id' => 1374,
                'name' => 'force_delete_any_source::ref',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:48',
                'updated_at' => '2024-08-20 07:32:48',
            ),
            374 => 
            array (
                'id' => 1375,
                'name' => 'view_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:49',
                'updated_at' => '2024-08-20 07:32:49',
            ),
            375 => 
            array (
                'id' => 1376,
                'name' => 'view_any_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:49',
                'updated_at' => '2024-08-20 07:32:49',
            ),
            376 => 
            array (
                'id' => 1377,
                'name' => 'create_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:49',
                'updated_at' => '2024-08-20 07:32:49',
            ),
            377 => 
            array (
                'id' => 1378,
                'name' => 'update_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:49',
                'updated_at' => '2024-08-20 07:32:49',
            ),
            378 => 
            array (
                'id' => 1379,
                'name' => 'restore_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:49',
                'updated_at' => '2024-08-20 07:32:49',
            ),
            379 => 
            array (
                'id' => 1380,
                'name' => 'restore_any_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:49',
                'updated_at' => '2024-08-20 07:32:49',
            ),
            380 => 
            array (
                'id' => 1381,
                'name' => 'replicate_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:49',
                'updated_at' => '2024-08-20 07:32:49',
            ),
            381 => 
            array (
                'id' => 1382,
                'name' => 'reorder_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:49',
                'updated_at' => '2024-08-20 07:32:49',
            ),
            382 => 
            array (
                'id' => 1383,
                'name' => 'delete_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:49',
                'updated_at' => '2024-08-20 07:32:49',
            ),
            383 => 
            array (
                'id' => 1384,
                'name' => 'delete_any_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:50',
                'updated_at' => '2024-08-20 07:32:50',
            ),
            384 => 
            array (
                'id' => 1385,
                'name' => 'force_delete_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:50',
                'updated_at' => '2024-08-20 07:32:50',
            ),
            385 => 
            array (
                'id' => 1386,
                'name' => 'force_delete_any_source::ref::even',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:50',
                'updated_at' => '2024-08-20 07:32:50',
            ),
            386 => 
            array (
                'id' => 1387,
                'name' => 'view_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:50',
                'updated_at' => '2024-08-20 07:32:50',
            ),
            387 => 
            array (
                'id' => 1388,
                'name' => 'view_any_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:50',
                'updated_at' => '2024-08-20 07:32:50',
            ),
            388 => 
            array (
                'id' => 1389,
                'name' => 'create_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:51',
                'updated_at' => '2024-08-20 07:32:51',
            ),
            389 => 
            array (
                'id' => 1390,
                'name' => 'update_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:51',
                'updated_at' => '2024-08-20 07:32:51',
            ),
            390 => 
            array (
                'id' => 1391,
                'name' => 'restore_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:51',
                'updated_at' => '2024-08-20 07:32:51',
            ),
            391 => 
            array (
                'id' => 1392,
                'name' => 'restore_any_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:51',
                'updated_at' => '2024-08-20 07:32:51',
            ),
            392 => 
            array (
                'id' => 1393,
                'name' => 'replicate_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:51',
                'updated_at' => '2024-08-20 07:32:51',
            ),
            393 => 
            array (
                'id' => 1394,
                'name' => 'reorder_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:51',
                'updated_at' => '2024-08-20 07:32:51',
            ),
            394 => 
            array (
                'id' => 1395,
                'name' => 'delete_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:51',
                'updated_at' => '2024-08-20 07:32:51',
            ),
            395 => 
            array (
                'id' => 1396,
                'name' => 'delete_any_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:51',
                'updated_at' => '2024-08-20 07:32:51',
            ),
            396 => 
            array (
                'id' => 1397,
                'name' => 'force_delete_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:52',
                'updated_at' => '2024-08-20 07:32:52',
            ),
            397 => 
            array (
                'id' => 1398,
                'name' => 'force_delete_any_source::repo',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:52',
                'updated_at' => '2024-08-20 07:32:52',
            ),
            398 => 
            array (
                'id' => 1399,
                'name' => 'view_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:52',
                'updated_at' => '2024-08-20 07:32:52',
            ),
            399 => 
            array (
                'id' => 1400,
                'name' => 'view_any_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:52',
                'updated_at' => '2024-08-20 07:32:52',
            ),
            400 => 
            array (
                'id' => 1401,
                'name' => 'create_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:52',
                'updated_at' => '2024-08-20 07:32:52',
            ),
            401 => 
            array (
                'id' => 1402,
                'name' => 'update_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:52',
                'updated_at' => '2024-08-20 07:32:52',
            ),
            402 => 
            array (
                'id' => 1403,
                'name' => 'restore_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:52',
                'updated_at' => '2024-08-20 07:32:52',
            ),
            403 => 
            array (
                'id' => 1404,
                'name' => 'restore_any_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:52',
                'updated_at' => '2024-08-20 07:32:52',
            ),
            404 => 
            array (
                'id' => 1405,
                'name' => 'replicate_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:53',
                'updated_at' => '2024-08-20 07:32:53',
            ),
            405 => 
            array (
                'id' => 1406,
                'name' => 'reorder_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:53',
                'updated_at' => '2024-08-20 07:32:53',
            ),
            406 => 
            array (
                'id' => 1407,
                'name' => 'delete_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:53',
                'updated_at' => '2024-08-20 07:32:53',
            ),
            407 => 
            array (
                'id' => 1408,
                'name' => 'delete_any_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:53',
                'updated_at' => '2024-08-20 07:32:53',
            ),
            408 => 
            array (
                'id' => 1409,
                'name' => 'force_delete_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:53',
                'updated_at' => '2024-08-20 07:32:53',
            ),
            409 => 
            array (
                'id' => 1410,
                'name' => 'force_delete_any_subm',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:53',
                'updated_at' => '2024-08-20 07:32:53',
            ),
            410 => 
            array (
                'id' => 1411,
                'name' => 'view_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:53',
                'updated_at' => '2024-08-20 07:32:53',
            ),
            411 => 
            array (
                'id' => 1412,
                'name' => 'view_any_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:54',
                'updated_at' => '2024-08-20 07:32:54',
            ),
            412 => 
            array (
                'id' => 1413,
                'name' => 'create_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:54',
                'updated_at' => '2024-08-20 07:32:54',
            ),
            413 => 
            array (
                'id' => 1414,
                'name' => 'update_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:54',
                'updated_at' => '2024-08-20 07:32:54',
            ),
            414 => 
            array (
                'id' => 1415,
                'name' => 'restore_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:54',
                'updated_at' => '2024-08-20 07:32:54',
            ),
            415 => 
            array (
                'id' => 1416,
                'name' => 'restore_any_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:54',
                'updated_at' => '2024-08-20 07:32:54',
            ),
            416 => 
            array (
                'id' => 1417,
                'name' => 'replicate_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:54',
                'updated_at' => '2024-08-20 07:32:54',
            ),
            417 => 
            array (
                'id' => 1418,
                'name' => 'reorder_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:54',
                'updated_at' => '2024-08-20 07:32:54',
            ),
            418 => 
            array (
                'id' => 1419,
                'name' => 'delete_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:55',
                'updated_at' => '2024-08-20 07:32:55',
            ),
            419 => 
            array (
                'id' => 1420,
                'name' => 'delete_any_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:55',
                'updated_at' => '2024-08-20 07:32:55',
            ),
            420 => 
            array (
                'id' => 1421,
                'name' => 'force_delete_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:55',
                'updated_at' => '2024-08-20 07:32:55',
            ),
            421 => 
            array (
                'id' => 1422,
                'name' => 'force_delete_any_subn',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:55',
                'updated_at' => '2024-08-20 07:32:55',
            ),
            422 => 
            array (
                'id' => 1423,
                'name' => 'view_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:55',
                'updated_at' => '2024-08-20 07:32:55',
            ),
            423 => 
            array (
                'id' => 1424,
                'name' => 'view_any_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:55',
                'updated_at' => '2024-08-20 07:32:55',
            ),
            424 => 
            array (
                'id' => 1425,
                'name' => 'create_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:56',
                'updated_at' => '2024-08-20 07:32:56',
            ),
            425 => 
            array (
                'id' => 1426,
                'name' => 'update_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:56',
                'updated_at' => '2024-08-20 07:32:56',
            ),
            426 => 
            array (
                'id' => 1427,
                'name' => 'restore_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:56',
                'updated_at' => '2024-08-20 07:32:56',
            ),
            427 => 
            array (
                'id' => 1428,
                'name' => 'restore_any_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:56',
                'updated_at' => '2024-08-20 07:32:56',
            ),
            428 => 
            array (
                'id' => 1429,
                'name' => 'replicate_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:56',
                'updated_at' => '2024-08-20 07:32:56',
            ),
            429 => 
            array (
                'id' => 1430,
                'name' => 'reorder_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:56',
                'updated_at' => '2024-08-20 07:32:56',
            ),
            430 => 
            array (
                'id' => 1431,
                'name' => 'delete_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:56',
                'updated_at' => '2024-08-20 07:32:56',
            ),
            431 => 
            array (
                'id' => 1432,
                'name' => 'delete_any_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:56',
                'updated_at' => '2024-08-20 07:32:56',
            ),
            432 => 
            array (
                'id' => 1433,
                'name' => 'force_delete_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:57',
                'updated_at' => '2024-08-20 07:32:57',
            ),
            433 => 
            array (
                'id' => 1434,
                'name' => 'force_delete_any_type',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:57',
                'updated_at' => '2024-08-20 07:32:57',
            ),
            434 => 
            array (
                'id' => 1435,
                'name' => 'page_DabovilleReportPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:57',
                'updated_at' => '2024-08-20 07:32:57',
            ),
            435 => 
            array (
                'id' => 1436,
                'name' => 'page_DeVilliersReportPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:58',
                'updated_at' => '2024-08-20 07:32:58',
            ),
            436 => 
            array (
                'id' => 1437,
                'name' => 'page_DescendantChartPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:58',
                'updated_at' => '2024-08-20 07:32:58',
            ),
            437 => 
            array (
                'id' => 1438,
                'name' => 'page_EditProfile',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:59',
                'updated_at' => '2024-08-20 07:32:59',
            ),
            438 => 
            array (
                'id' => 1439,
                'name' => 'page_FanChartPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:32:59',
                'updated_at' => '2024-08-20 07:32:59',
            ),
            439 => 
            array (
                'id' => 1440,
                'name' => 'page_HenryReportPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:00',
                'updated_at' => '2024-08-20 07:33:00',
            ),
            440 => 
            array (
                'id' => 1441,
                'name' => 'page_PedigreeChartPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:00',
                'updated_at' => '2024-08-20 07:33:00',
            ),
            441 => 
            array (
                'id' => 1442,
                'name' => 'page_PeopleDashboard',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:01',
                'updated_at' => '2024-08-20 07:33:01',
            ),
            442 => 
            array (
                'id' => 1443,
                'name' => 'page_PersonalAccessTokensPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:01',
                'updated_at' => '2024-08-20 07:33:01',
            ),
            443 => 
            array (
                'id' => 1444,
                'name' => 'page_PrivateMessagingPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:01',
                'updated_at' => '2024-08-20 07:33:01',
            ),
            444 => 
            array (
                'id' => 1445,
                'name' => 'page_TwoFactorAuthenticationPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:02',
                'updated_at' => '2024-08-20 07:33:02',
            ),
            445 => 
            array (
                'id' => 1446,
                'name' => 'page_UpdatePasswordPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:03',
                'updated_at' => '2024-08-20 07:33:03',
            ),
            446 => 
            array (
                'id' => 1447,
                'name' => 'page_UpdateProfileInformationPage',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:03',
                'updated_at' => '2024-08-20 07:33:03',
            ),
            447 => 
            array (
                'id' => 1448,
                'name' => 'widget_FanChartWidget',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:04',
                'updated_at' => '2024-08-20 07:33:04',
            ),
            448 => 
            array (
                'id' => 1449,
                'name' => 'widget_PeopleWidget',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:05',
                'updated_at' => '2024-08-20 07:33:05',
            ),
            449 => 
            array (
                'id' => 1450,
                'name' => 'widget_SocialLinksWidget',
                'guard_name' => 'web',
                'created_at' => '2024-08-20 07:33:06',
                'updated_at' => '2024-08-20 07:33:06',
            ),
        ));
        
        
    }
}