<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// Populate t0practice_division. 
// This file only includes divisions with practices in templates/practices.php. 
// The process-specific practices, like in templates/nh3r... files, populate t0practice_division separately.
$practice_division = array(
    1 => array(
        'k0practice' => $practices[2]['k0practice'], // Start with $practices[2] because $practices[1] is the Action Register, which is accessed from the left-hand contents, so not associated with a particular division.
        'k0division' => $divisions[1]['k0division'] // The Cheesehead division method is $divisions[1] to $divisions[13].
    ),
    2 => array(
        'k0practice' => $practices[3]['k0practice'],
        'k0division' => $divisions[1]['k0division']
    ),
    3 => array(
        'k0practice' => $practices[4]['k0practice'],
        'k0division' => $divisions[1]['k0division']
    ),
    4 => array(
        'k0practice' => $practices[5]['k0practice'],
        'k0division' => $divisions[2]['k0division']
    ),
    5 => array(
        'k0practice' => $practices[6]['k0practice'],
        'k0division' => $divisions[2]['k0division']
    ),
    6 => array(
        'k0practice' => $practices[7]['k0practice'],
        'k0division' => $divisions[2]['k0division']
    ),
    7 => array(
        'k0practice' => $practices[8]['k0practice'],
        'k0division' => $divisions[2]['k0division']
    ),
    8 => array(
        'k0practice' => $practices[9]['k0practice'],
        'k0division' => $divisions[2]['k0division']
    ),
    9 => array(
        'k0practice' => $practices[10]['k0practice'],
        'k0division' => $divisions[2]['k0division']
    ),
    10 => array(
        'k0practice' => $practices[11]['k0practice'],
        'k0division' => $divisions[2]['k0division']
    ),/* removed
    11 => array(
        'k0practice' => $practices[12]['k0practice'],
        'k0division' => $divisions[9]['k0division']
    ),*/
    12 => array(
        'k0practice' => $practices[13]['k0practice'],
        'k0division' => $divisions[9]['k0division']
    ),
    13 => array(
        'k0practice' => $practices[14]['k0practice'],
        'k0division' => $divisions[9]['k0division']
    ),
    14 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[4]['k0division']
    ),/*
    15 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[4]['k0division']
    ),
    16 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[4]['k0division']
    ),
    17 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[4]['k0division']
    ),*/
    18 => array(
        'k0practice' => $practices[19]['k0practice'],
        'k0division' => $divisions[4]['k0division']
    ),
    19 => array(
        'k0practice' => $practices[2]['k0practice'], // OSHA PSM does not require a Managment System, but these tasks are sometimes required included under $divisions[25], Management of Change, in particular, Management of Organizational Change.
        'k0division' => $divisions[25]['k0division']
    ),
    20 => array(
        'k0practice' => $practices[3]['k0practice'],
        'k0division' => $divisions[25]['k0division']
    ),
    21 => array(
        'k0practice' => $practices[4]['k0practice'],
        'k0division' => $divisions[25]['k0division']
    ),
    22 => array(
        'k0practice' => $practices[5]['k0practice'],
        'k0division' => $divisions[16]['k0division']
    ),
    23 => array(
        'k0practice' => $practices[6]['k0practice'],
        'k0division' => $divisions[16]['k0division']
    ),
    24 => array(
        'k0practice' => $practices[7]['k0practice'],
        'k0division' => $divisions[16]['k0division']
    ),
    25 => array(
        'k0practice' => $practices[8]['k0practice'],
        'k0division' => $divisions[16]['k0division']
    ),
    26 => array(
        'k0practice' => $practices[9]['k0practice'],
        'k0division' => $divisions[16]['k0division']
    ),
    27 => array(
        'k0practice' => $practices[10]['k0practice'],
        'k0division' => $divisions[16]['k0division']
    ),
    28 => array(
        'k0practice' => $practices[11]['k0practice'],
        'k0division' => $divisions[16]['k0division']
    ),/* removed
    29 => array(
        'k0practice' => $practices[12]['k0practice'],
        'k0division' => $divisions[25]['k0division']
    ),*/
    30 => array(
        'k0practice' => $practices[13]['k0practice'],
        'k0division' => $divisions[25]['k0division']
    ),
    31 => array(
        'k0practice' => $practices[14]['k0practice'],
        'k0division' => $divisions[25]['k0division']
    ),
    32 => array(
        'k0practice' => $practices[14]['k0practice'], // $practices[14] is also under the OSHA PSM Pre-startup safety review division.
        'k0division' => $divisions[22]['k0division']
    ),
    33 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[18]['k0division']
    ),/*
    34 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[18]['k0division']
    ),
    35 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[18]['k0division']
    ),
    36 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[18]['k0division']
    ),*/
    37 => array(
        'k0practice' => $practices[19]['k0practice'],
        'k0division' => $divisions[18]['k0division']
    ),
    38 => array(
        'k0practice' => $practices[2]['k0practice'],
        'k0division' => $divisions[30]['k0division']  // The EPA CAP (Program 3 only) division-method is $divisions[30] to $divisions[48].
    ),
    39 => array(
        'k0practice' => $practices[3]['k0practice'],
        'k0division' => $divisions[30]['k0division']
    ),
    40 => array(
        'k0practice' => $practices[4]['k0practice'],
        'k0division' => $divisions[30]['k0division']
    ),
    41 => array(
        'k0practice' => $practices[5]['k0practice'],
        'k0division' => $divisions[41]['k0division']
    ),
    42 => array(
        'k0practice' => $practices[6]['k0practice'],
        'k0division' => $divisions[41]['k0division']
    ),
    43 => array(
        'k0practice' => $practices[7]['k0practice'],
        'k0division' => $divisions[41]['k0division']
    ),
    44 => array(
        'k0practice' => $practices[8]['k0practice'],
        'k0division' => $divisions[41]['k0division']
    ),
    45 => array(
        'k0practice' => $practices[9]['k0practice'],
        'k0division' => $divisions[41]['k0division']
    ),
    46 => array(
        'k0practice' => $practices[10]['k0practice'],
        'k0division' => $divisions[41]['k0division']
    ),
    47 => array(
        'k0practice' => $practices[11]['k0practice'],
        'k0division' => $divisions[41]['k0division']
    ),/* removed
    48 => array(
        'k0practice' => $practices[12]['k0practice'],
        'k0division' => $divisions[37]['k0division']
    ),*/
    49 => array(
        'k0practice' => $practices[13]['k0practice'],
        'k0division' => $divisions[37]['k0division']
    ),
    50 => array(
        'k0practice' => $practices[14]['k0practice'],
        'k0division' => $divisions[37]['k0division']
    ),
    51 => array(
        'k0practice' => $practices[14]['k0practice'], // $practices[14] is also under the EPA CAP Pre-startup review division.
        'k0division' => $divisions[38]['k0division']
    ),
    52 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[33]['k0division']
    ),/*
    53 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[33]['k0division']
    ),
    54 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[33]['k0division']
    ),
    55 => array(
        'k0practice' => $practices[16]['k0practice'],
        'k0division' => $divisions[33]['k0division']
    ),*/
    56 => array(
        'k0practice' => $practices[19]['k0practice'],
        'k0division' => $divisions[33]['k0division']
    ),
    57 => array(
        'k0practice' => $practices[21]['k0practice'], // Compliance audit - start
        'k0division' => $divisions[11]['k0division'] // Cheesehead division method - start
    ),/*
    58 => array(
        'k0practice' => $practices[21]['k0practice'],
        'k0division' => $divisions[11]['k0division']
    ),
    59 => array(
        'k0practice' => $practices[21]['k0practice'],
        'k0division' => $divisions[11]['k0division']
    ),
    60 => array(
        'k0practice' => $practices[21]['k0practice'],
        'k0division' => $divisions[11]['k0division']
    ),*/
    61 => array(
        'k0practice' => $practices[24]['k0practice'],
        'k0division' => $divisions[11]['k0division']
    ),/*
    62 => array(
        'k0practice' => $practices[21]['k0practice'], // Compliance audit - continued
        'k0division' => $divisions[11]['k0division'] // Cheesehead division method - end
    ),*/
    63 => array(
        'k0practice' => $practices[21]['k0practice'], // Compliance audit - continued
        'k0division' => $divisions[28]['k0division'] // OSHA PSM division method - start
    ),/*
    64 => array(
        'k0practice' => $practices[21]['k0practice'],
        'k0division' => $divisions[28]['k0division']
    ),
    65 => array(
        'k0practice' => $practices[21]['k0practice'],
        'k0division' => $divisions[28]['k0division']
    ),
    66 => array(
        'k0practice' => $practices[21]['k0practice'],
        'k0division' => $divisions[28]['k0division']
    ),*/
    67 => array(
        'k0practice' => $practices[24]['k0practice'],
        'k0division' => $divisions[28]['k0division']
    ),/*
    68 => array(
        'k0practice' => $practices[21]['k0practice'], // Compliance audit - continued
        'k0division' => $divisions[28]['k0division'] // OSHA PSM division method - end
    ),*/
    69 => array(
        'k0practice' => $practices[21]['k0practice'], // Compliance audit - continued
        'k0division' => $divisions[39]['k0division'] // EPA CAP division method - start
    ),/*
    70 => array(
        'k0practice' => $practices[21]['k0practice'],
        'k0division' => $divisions[39]['k0division']
    ),
    71 => array(
        'k0practice' => $practices[21]['k0practice'],
        'k0division' => $divisions[39]['k0division']
    ),
    72 => array(
        'k0practice' => $practices[21]['k0practice'],
        'k0division' => $divisions[39]['k0division']
    ),*/
    73 => array(
        'k0practice' => $practices[24]['k0practice'],
        'k0division' => $divisions[39]['k0division']
    ),/*
    74 => array(
        'k0practice' => $practices[21]['k0practice'], // Compliance audit - end
        'k0division' => $divisions[39]['k0division'] // EPA CAP division method - end
    ),*/
    75 => array(
        'k0practice' => $practices[26]['k0practice'], // Incident Investigation - start
        'k0division' => $divisions[10]['k0division'] // Cheesehead division method - start
    ),
    76 => array(
        'k0practice' => $practices[27]['k0practice'],
        'k0division' => $divisions[10]['k0division']
    ),
    77 => array(
        'k0practice' => $practices[28]['k0practice'],
        'k0division' => $divisions[10]['k0division']
    ),
    78 => array(
        'k0practice' => $practices[29]['k0practice'],
        'k0division' => $divisions[10]['k0division']
    ),
    79 => array(
        'k0practice' => $practices[30]['k0practice'],
        'k0division' => $divisions[10]['k0division']
    ),
    80 => array(
        'k0practice' => $practices[31]['k0practice'], // Incident Investigation - continued
        'k0division' => $divisions[10]['k0division'] // Cheesehead division method - end
    ),
    81 => array(
        'k0practice' => $practices[26]['k0practice'], // Incident Investigation - continued
        'k0division' => $divisions[26]['k0division'] // OSHA PSM division method - start
    ),
    82 => array(
        'k0practice' => $practices[27]['k0practice'],
        'k0division' => $divisions[26]['k0division']
    ),
    83 => array(
        'k0practice' => $practices[28]['k0practice'],
        'k0division' => $divisions[26]['k0division']
    ),
    84 => array(
        'k0practice' => $practices[29]['k0practice'],
        'k0division' => $divisions[26]['k0division']
    ),
    85 => array(
        'k0practice' => $practices[30]['k0practice'],
        'k0division' => $divisions[26]['k0division']
    ),
    86 => array(
        'k0practice' => $practices[31]['k0practice'], // Incident Investigation - continued
        'k0division' => $divisions[26]['k0division'] // OSHA PSM division method - end
    ),
    87 => array(
        'k0practice' => $practices[26]['k0practice'], // Incident Investigation - continued
        'k0division' => $divisions[40]['k0division'] // EPA CAP division method - start
    ),
    88 => array(
        'k0practice' => $practices[27]['k0practice'],
        'k0division' => $divisions[40]['k0division']
    ),
    89 => array(
        'k0practice' => $practices[28]['k0practice'],
        'k0division' => $divisions[40]['k0division']
    ),
    90 => array(
        'k0practice' => $practices[29]['k0practice'],
        'k0division' => $divisions[40]['k0division']
    ),
    91 => array(
        'k0practice' => $practices[30]['k0practice'],
        'k0division' => $divisions[40]['k0division']
    ),
    92 => array(
        'k0practice' => $practices[31]['k0practice'], // Incident Investigation - end
        'k0division' => $divisions[40]['k0division'] // EPA CAP division method - end
    ),
    93 => array(
        'k0practice' => $practices[32]['k0practice'], // Training - start
        'k0division' => $divisions[6]['k0division'] // Cheesehead division method - start
    ),
    94 => array(
        'k0practice' => $practices[33]['k0practice'],
        'k0division' => $divisions[6]['k0division']
    ), /* JDH 2019-12-20 practice 33 removed.
    95 => array(
        'k0practice' => $practices[34]['k0practice'], // Training - continued
        'k0division' => $divisions[6]['k0division'] // Cheesehead division method - end
    ), */
    96 => array(
        'k0practice' => $practices[32]['k0practice'], // Training - continued
        'k0division' => $divisions[20]['k0division'] // OSHA PSM division method - start
    ),
    97 => array(
        'k0practice' => $practices[33]['k0practice'],
        'k0division' => $divisions[20]['k0division']
    ), /*
    98 => array(
        'k0practice' => $practices[34]['k0practice'], // Training - continued
        'k0division' => $divisions[20]['k0division'] // OSHA PSM division method - end
    ), */
    99 => array(
        'k0practice' => $practices[32]['k0practice'], // Training - continued
        'k0division' => $divisions[35]['k0division'] // EPA CAP division method - start
    ),
    100 => array(
        'k0practice' => $practices[33]['k0practice'],
        'k0division' => $divisions[35]['k0division']
    ), /*
    101 => array(
        'k0practice' => $practices[34]['k0practice'], // Training - end
        'k0division' => $divisions[35]['k0division'] // EPA CAP division method - end
    ), */
    102 => array(
        'k0practice' => $practices[35]['k0practice'], // Contractors - start
        'k0division' => $divisions[7]['k0division'] // Cheesehead division method - start
    ),
    103 => array(
        'k0practice' => $practices[36]['k0practice'],
        'k0division' => $divisions[7]['k0division']
    ),
    104 => array(
        'k0practice' => $practices[37]['k0practice'],
        'k0division' => $divisions[7]['k0division']
    ),
    105 => array(
        'k0practice' => $practices[38]['k0practice'],
        'k0division' => $divisions[7]['k0division']
    ),
    106 => array(
        'k0practice' => $practices[39]['k0practice'],
        'k0division' => $divisions[7]['k0division']
    ),
    107 => array(
        'k0practice' => $practices[40]['k0practice'],
        'k0division' => $divisions[7]['k0division']
    ),
    108 => array(
        'k0practice' => $practices[41]['k0practice'], // Contractors - continued
        'k0division' => $divisions[7]['k0division'] // Cheesehead division method - end
    ),
    109 => array(
        'k0practice' => $practices[35]['k0practice'], // Contractors - continued
        'k0division' => $divisions[21]['k0division'] // OSHA PSM division method - start
    ),
    110 => array(
        'k0practice' => $practices[36]['k0practice'],
        'k0division' => $divisions[21]['k0division']
    ),
    111 => array(
        'k0practice' => $practices[37]['k0practice'],
        'k0division' => $divisions[21]['k0division']
    ),
    112 => array(
        'k0practice' => $practices[38]['k0practice'],
        'k0division' => $divisions[21]['k0division']
    ),
    113 => array(
        'k0practice' => $practices[39]['k0practice'],
        'k0division' => $divisions[21]['k0division']
    ),
    114 => array(
        'k0practice' => $practices[40]['k0practice'],
        'k0division' => $divisions[21]['k0division']
    ),
    115 => array(
        'k0practice' => $practices[41]['k0practice'], // Contractors - continued
        'k0division' => $divisions[21]['k0division'] // OSHA PSM division method - end
    ),
    116 => array(
        'k0practice' => $practices[35]['k0practice'], // Contractors - continued
        'k0division' => $divisions[43]['k0division'] // EPA CAP division method - start
    ),
    117 => array(
        'k0practice' => $practices[36]['k0practice'],
        'k0division' => $divisions[43]['k0division']
    ),
    118 => array(
        'k0practice' => $practices[37]['k0practice'],
        'k0division' => $divisions[43]['k0division']
    ),
    119 => array(
        'k0practice' => $practices[38]['k0practice'],
        'k0division' => $divisions[43]['k0division']
    ),
    120 => array(
        'k0practice' => $practices[39]['k0practice'],
        'k0division' => $divisions[43]['k0division']
    ),
    121 => array(
        'k0practice' => $practices[40]['k0practice'],
        'k0division' => $divisions[43]['k0division']
    ),
    122 => array(
        'k0practice' => $practices[41]['k0practice'], // Contractors - end
        'k0division' => $divisions[43]['k0division'] // EPA CAP division method - end
    ),
    123 => array(
        'k0practice' => $practices[42]['k0practice'], // Process safety information - start
        'k0division' => $divisions[3]['k0division'] // Cheesehead division method - start
    ),
    124 => array(
        'k0practice' => $practices[43]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    125 => array(
        'k0practice' => $practices[44]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    126 => array(
        'k0practice' => $practices[45]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    127 => array(
        'k0practice' => $practices[46]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    128 => array(
        'k0practice' => $practices[47]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    129 => array(
        'k0practice' => $practices[48]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    130 => array(
        'k0practice' => $practices[49]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    131 => array(
        'k0practice' => $practices[50]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    132 => array(
        'k0practice' => $practices[51]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    133 => array(
        'k0practice' => $practices[52]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    134 => array(
        'k0practice' => $practices[53]['k0practice'],
        'k0division' => $divisions[3]['k0division']
    ),
    135 => array(
        'k0practice' => $practices[54]['k0practice'], // Process safety information - continued
        'k0division' => $divisions[3]['k0division'] // Cheesehead division method - end
    ),
    136 => array(
        'k0practice' => $practices[42]['k0practice'], // Process safety information - continued
        'k0division' => $divisions[17]['k0division'] // OSHA PSM division method - start
    ),
    137 => array(
        'k0practice' => $practices[43]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    138 => array(
        'k0practice' => $practices[44]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    139 => array(
        'k0practice' => $practices[45]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    140 => array(
        'k0practice' => $practices[46]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    141 => array(
        'k0practice' => $practices[47]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    142 => array(
        'k0practice' => $practices[48]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    143 => array(
        'k0practice' => $practices[49]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    144 => array(
        'k0practice' => $practices[50]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    145 => array(
        'k0practice' => $practices[51]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    146 => array(
        'k0practice' => $practices[52]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    147 => array(
        'k0practice' => $practices[53]['k0practice'],
        'k0division' => $divisions[17]['k0division']
    ),
    148 => array(
        'k0practice' => $practices[54]['k0practice'], // Process safety information - continued
        'k0division' => $divisions[17]['k0division'] // OSHA PSM division method - end
    ),
    149 => array(
        'k0practice' => $practices[42]['k0practice'], // Process safety information - continued
        'k0division' => $divisions[32]['k0division'] // EPA CAP division method - start
    ),
    150 => array(
        'k0practice' => $practices[43]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    151 => array(
        'k0practice' => $practices[44]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    152 => array(
        'k0practice' => $practices[45]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    153 => array(
        'k0practice' => $practices[46]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    154 => array(
        'k0practice' => $practices[47]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    155 => array(
        'k0practice' => $practices[48]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    156 => array(
        'k0practice' => $practices[49]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    157 => array(
        'k0practice' => $practices[50]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    158 => array(
        'k0practice' => $practices[51]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    159 => array(
        'k0practice' => $practices[52]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    160 => array(
        'k0practice' => $practices[53]['k0practice'],
        'k0division' => $divisions[32]['k0division']
    ),
    161 => array(
        'k0practice' => $practices[54]['k0practice'], // Process safety information - end
        'k0division' => $divisions[32]['k0division'] // EPA CAP division method - end
    ),
    162 => array(
        'k0practice' => $practices[27]['k0practice'], // Incident Investigation Form and Records (covers Five-year accident history)
        'k0division' => $divisions[13]['k0division'] // Cheesehead division method - Offsite-hazard assessment and reporting
    ),
    163 => array(
        'k0practice' => $practices[27]['k0practice'], // Incident Investigation Form and Records (covers Five-year accident history)
        'k0division' => $divisions[31]['k0division'] // EPA CAP division method - Hazard Assessment
    )
);
foreach ($practice_division as $K => $V) {
    $V['k0practice_division'] = $K;
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0practice_division', $V);
}

