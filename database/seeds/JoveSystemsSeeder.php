<?php

namespace Database\Seeders;

use App\Models\JoveSystems;
use App\Models\SDE\SolarSystem;
use Illuminate\Database\Seeder;

class JoveSystemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systems = [
            // Delve
            'F-9PXR',
            'IP6V-X',
            'HM-XR2',
            'Z3V-1W',
            'YZ9-F6',
            'D-W7F0',
            'M-SRKS',
            '8F-TK3',
            'QY6-RK',
            'KBAK-I',
            'ZXB-VC',

            // Fountain

            'G-UTHL',
            'V6-NY1',
            'PXF-RF',
            'A-HZYL',
            'Q-XEB3',
            '7X-02R',
            'SPLE-Y',
            'H-S80W',
            'I-CUVX',
            '87XQ-0',
            'JGOW-Y',
            'KCT-0A',
            'R3W-XU',
            'AC2E-3',
            'IGE-RI',
            '15U-JY',
            'CHA2-Q',
            '87XQ-0',
            'Z-YN5Y',
            'HMF-9D',
            '7BX-6F',
            '6F-H3W',
            'L-1SW8',
            'P5-EFH',
            'YRNJ-8',
            'Z9PP-H',

            // Querious

            'E-VKJV',
            'A-BO4V',
            'MKD-O8',
            'O3L-95',
            'A3-LOG',
            'UVHO-F',
            'OGY-6D',
            '8B-SAJ',
            'W6V-VM',
            'UYU-VV',
            'KEJY-U',
            'TV8-HS',
            'ED-L9T',
            'Z-UZZN',
            'RF-CN3',
            '3D5K-R',

            // Aridia

            'Huna',
            'Nouta',
            'Pserz',
            'Esubara',
            'Soza',
            'Defsunun',
            'Nema',
            'Fihrneh',
            'Hoseen',
            'Chibi',
            'Bazadod',
            'Isid',

            // Period Basis

            'GR-J8B',
            'TCAG-3',
            'VQE-CN',
            '08S-39',
            'E-DOF2',
            'RYQC-I',
            'Z-M5A1',

            // Paragon Soul

            'GQ2S-8',
            'H8-ZTO',
            'ARBX-9',
            'O-MCZR',
            'H8-ZTO',
            '3PPT-9',
            'LD-2VL',
            'O-N589',

            // Esoteria

            'G2-INZ',
            'D-FVI7',
            'JAUD-V',
            'G-YZUX',
            '6EK-BV',
            'SN9S-N',
            'PE-H02',
            'HHE5-L',
            'G-4H4C',
            'O-MCZR',
            'YRV-MZ',
            'R-ARKN',
            'P9F-ZG',
            'C-PEWN',
            'Q1-R7K',
            'NIZJ-0',
            'F-UVBV',
            'CR-0E5',

            // Stain

            'O-CT8N',
            'XFBE-T',
            '2B-3M4',
            'LGL-SD',
            'O-CT8N',
            'L6B-0N',
            'UKYS-5',
            'CJF-1P',
            'JU-UYK',
            '4XW2-D',
            '373Z-7',
            'TL-T9Z',
            'B-G1LG',
            'X5O1-L',
            '42-UOW',
            '6QBH-S',
            'J-AYLV',
            'A-GPTM',
            '37S-KO',
            'FV-SE8',
            'WEQT-K',
            'G-ME2K',
            'WHG2-7',
            'LGK-VP',
            '2IGP-1',

            // Impass

            '01TG-J',
            '4OIV-X',
            '6E-MOW',
            'DDI-B7',
            'DY-40Z',
            'E7VE-V',
            'GU-9F4',
            'PZMA-E',
            'X-0CKQ',
            'Z-N9IP',

            // Feythabolis

            '2-F3OE',
            '2UK4-N',
            '3-BADZ',
            '6O-XIO',
            'CL-J9W',
            'DUU1-K',
            'I9-ZQZ',
            'K-X5AX',
            'OXC-UL',
            'P8-BKO',
            'PO-3QW',
            'QK-CDG',
            'U-BXU9',
            'UB5Z-3',
            'UD-VZW',
            'X6-J6R',
            'Z-PNIA',
            'ZID-LE',

        ];

        JoveSystems::truncate();

        foreach ($systems as $system) {
            $this->command->info($system);
            $info = SolarSystem::where('name', $system)->first();

            $insert = new JoveSystems();
            $insert->system_id = $info->system_id;
            $insert->drifter = 1;
            $insert->save();
        }
    }
}
