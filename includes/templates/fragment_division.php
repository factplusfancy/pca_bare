<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// Populate t0fragment_division.
$fragment_division = array(
    1 => array(
        'k0fragment' => $cap_fragments[52]['k0fragment'], // Management System
        'k0division' => $divisions[1]['k0division'], // $divisions[1] to $divisions[13] is the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('AAA111') // Numbering must be unique for each rule/division method (but not between rules.)
    ),
    2 => array( // $psm_fragments[3] to [12] were intentionally skipped -- introduction and definitions. Key definitions are included in square brackets with fragments. The entire PSM standard can also be viewed via the PSM division order.
        'k0fragment' => $psm_fragments[14]['k0fragment'],
        'k0division' => $divisions[2]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB111')
    ),
    3 => array(
        'k0fragment' => $psm_fragments[15]['k0fragment'],
        'k0division' => $divisions[2]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB222')
    ),
    4 => array(
        'k0fragment' => $psm_fragments[16]['k0fragment'],
        'k0division' => $divisions[2]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB333')
    ),
    5 => array( // For Cheesehead division method, $psm_fragments[17] is at  k0fragment_division 438, below. It was intentionally skipped in early draft.
        'k0fragment' => $psm_fragments[18]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC111')
    ),
    6 => array( // $psm_fragments[19] intentionally skipped (headings, no longer relevant, verbiage...)
        'k0fragment' => $psm_fragments[20]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC122')
    ),
    7 => array(
        'k0fragment' => $psm_fragments[21]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC133')
    ),
    8 => array(
        'k0fragment' => $psm_fragments[22]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC144')
    ),
    9 => array(
        'k0fragment' => $psm_fragments[23]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC155')
    ),
    10 => array(
        'k0fragment' => $psm_fragments[24]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC166')
    ),
    11 => array(
        'k0fragment' => $psm_fragments[25]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC177')
    ),
    12 => array(  // $psm_fragments[26] intentionally skipped (headings, no longer relevant, verbiage...)
        'k0fragment' => $psm_fragments[27]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC188')
    ),
    13 => array(
        'k0fragment' => $psm_fragments[28]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC199')
    ),
    14 => array(
        'k0fragment' => $psm_fragments[29]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC211')
    ),
    15 => array(
        'k0fragment' => $psm_fragments[30]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC222')
    ),
    16 => array(
        'k0fragment' => $psm_fragments[31]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC233')
    ),
    17 => array(
        'k0fragment' => $psm_fragments[32]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC244')
    ),
    18 => array(
        'k0fragment' => $psm_fragments[33]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC255')
    ),
    19 => array(
        'k0fragment' => $psm_fragments[34]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC266')
    ),
    20 => array(
        'k0fragment' => $psm_fragments[35]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC277')
    ),
    21 => array(
        'k0fragment' => $psm_fragments[36]['k0fragment'],
        'k0division' => $divisions[3]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC288')
    ),
    22 => array(
        'k0fragment' => $psm_fragments[37]['k0fragment'],
        'k0division' => $divisions[4]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD111')
    ),
    23 => array(
        'k0fragment' => $psm_fragments[38]['k0fragment'],
        'k0division' => $divisions[4]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD222')
    ),
    24 => array(
        'k0fragment' => $psm_fragments[39]['k0fragment'],
        'k0division' => $divisions[4]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD333')
    ),
    25 => array(
        'k0fragment' => $psm_fragments[40]['k0fragment'],
        'k0division' => $divisions[4]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD444')
    ),
    26 => array(
        'k0fragment' => $psm_fragments[41]['k0fragment'],
        'k0division' => $divisions[4]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD555')
    ),
    27 => array(
        'k0fragment' => $psm_fragments[42]['k0fragment'],
        'k0division' => $divisions[4]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD666')
    ),
    28 => array(
        'k0fragment' => $psm_fragments[43]['k0fragment'],
        'k0division' => $divisions[4]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD777')
    ),
    29 => array(
        'k0fragment' => $psm_fragments[44]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE111')
    ),
    30 => array( // $psm_fragments[45] intentionally skipped (headings, no longer relevant, verbiage...)
        'k0fragment' => $psm_fragments[46]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE122')
    ),
    31 => array(
        'k0fragment' => $psm_fragments[47]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE133')
    ),
    32 => array(
        'k0fragment' => $psm_fragments[48]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE144')
    ),
    33 => array(
        'k0fragment' => $psm_fragments[49]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE155')
    ),
    34 => array(
        'k0fragment' => $psm_fragments[50]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE166')
    ),
    35 => array(
        'k0fragment' => $psm_fragments[51]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE177')
    ),
    36 => array(
        'k0fragment' => $psm_fragments[52]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE188')
    ),
    37 => array(
        'k0fragment' => $psm_fragments[53]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE199')
    ),
    38 => array( // $psm_fragments[54] intentionally skipped (headings, no longer relevant, verbiage...)
        'k0fragment' => $psm_fragments[55]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE211')
    ),
    39 => array(
        'k0fragment' => $psm_fragments[56]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE222')
    ),
    40 => array(
        'k0fragment' => $psm_fragments[57]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE233')
    ),
    41 => array(
        'k0fragment' => $psm_fragments[58]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE244')
    ),
    42 => array(
        'k0fragment' => $psm_fragments[59]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE255')
    ),
    43 => array(
        'k0fragment' => $psm_fragments[60]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE266')
    ),
    44 => array(
        'k0fragment' => $psm_fragments[61]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE277')
    ),
    45 => array(
        'k0fragment' => $psm_fragments[62]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE288')
    ),
    46 => array(
        'k0fragment' => $psm_fragments[63]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE299')
    ),
    47 => array(
        'k0fragment' => $psm_fragments[64]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE311')
    ),
    48 => array(
        'k0fragment' => $psm_fragments[65]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE322')
    ),
    49 => array(
        'k0fragment' => $psm_fragments[66]['k0fragment'],
        'k0division' => $divisions[6]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF111')
    ),
    50 => array( // $psm_fragments[67] intentionally skipped (headings, no longer relevant, verbiage...)
        'k0fragment' => $psm_fragments[68]['k0fragment'],
        'k0division' => $divisions[6]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF222')
    ),
    51 => array(
        'k0fragment' => $psm_fragments[69]['k0fragment'],
        'k0division' => $divisions[6]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF333')
    ),
    52 => array(
        'k0fragment' => $psm_fragments[70]['k0fragment'],
        'k0division' => $divisions[6]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF444')
    ),
    53 => array(
        'k0fragment' => $psm_fragments[71]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG111')
    ),
    54 => array( // $psm_fragments[72] intentionally skipped (headings, no longer relevant, verbiage...)
        'k0fragment' => $psm_fragments[73]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG122')
    ),
    55 => array(
        'k0fragment' => $psm_fragments[74]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG133')
    ),
    56 => array(
        'k0fragment' => $psm_fragments[75]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG144')
    ),
    57 => array(
        'k0fragment' => $psm_fragments[76]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG155')
    ),
    58 => array(
        'k0fragment' => $psm_fragments[77]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG166')
    ),
    59 => array(
        'k0fragment' => $psm_fragments[78]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG177')
    ),
    60 => array( // $psm_fragments[79] intentionally skipped (headings, no longer relevant, verbiage...)
        'k0fragment' => $psm_fragments[80]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG188')
    ),
    61 => array(
        'k0fragment' => $psm_fragments[81]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG199')
    ),
    62 => array(
        'k0fragment' => $psm_fragments[82]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG211')
    ),
    63 => array(
        'k0fragment' => $psm_fragments[83]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG222')
    ),
    64 => array(
        'k0fragment' => $psm_fragments[84]['k0fragment'],
        'k0division' => $divisions[7]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG233')
    ), // $psm_fragments[85] and [85] fall under Change Management
    65 => array(
        'k0fragment' => $psm_fragments[87]['k0fragment'],
        'k0division' => $divisions[8]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH111')
    ),
    66 => array(
        'k0fragment' => $psm_fragments[88]['k0fragment'],
        'k0division' => $divisions[8]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH122')
    ),
    67 => array(
        'k0fragment' => $psm_fragments[89]['k0fragment'],
        'k0division' => $divisions[8]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH133')
    ),
    68 => array( 
        'k0fragment' => $psm_fragments[90]['k0fragment'],
        'k0division' => $divisions[8]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH144')
    ), /* $psm_fragments[91] to [93] combined into [89]
    69 => array(
        'k0fragment' => $psm_fragments[92]['k0fragment'],
        'k0division' => $divisions[8]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH155')
    ),
    70 => array(
        'k0fragment' => $psm_fragments[93]['k0fragment'],
        'k0division' => $divisions[8]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH166')
    ),
    71 => array(
        'k0fragment' => $psm_fragments[94]['k0fragment'],
        'k0division' => $divisions[8]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH177')
    ),*/
    72 => array(
        'k0fragment' => $psm_fragments[95]['k0fragment'],
        'k0division' => $divisions[8]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH188')
    ),
    73 => array( // $psm_fragments[96] intentionally skipped. $psm_fragments[97] falls under Change Management
        'k0fragment' => $psm_fragments[98]['k0fragment'],
        'k0division' => $divisions[8]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH199')
    ),
    74 => array( // Hot Work Permit grouped with Safe Work Practices in Cheesehead division order.
        'k0fragment' => $psm_fragments[99]['k0fragment'],
        'k0division' => $divisions[5]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE333')
    ),
    75 => array( // MOC Applicability
        'k0fragment' => $psm_fragments[100]['k0fragment'],
        'k0division' => $divisions[9]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III111')
    ),
    76 => array( // PSR Applicability
        'k0fragment' => $psm_fragments[85]['k0fragment'],
        'k0division' => $divisions[9]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III122')
    ),
    77 => array( // MOC Procedural Requirements
        'k0fragment' => $psm_fragments[101]['k0fragment'],
        'k0division' => $divisions[9]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III133')
    ),
    78 => array( // PSI Update
        'k0fragment' => $psm_fragments[103]['k0fragment'],
        'k0division' => $divisions[9]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III144')
    ),
    79 => array( // Procedures Update
        'k0fragment' => $psm_fragments[104]['k0fragment'],
        'k0division' => $divisions[9]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III155')
    ),
    80 => array( // Pre-Startup Training
        'k0fragment' => $psm_fragments[102]['k0fragment'],
        'k0division' => $divisions[9]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III166')
    ),
    81 => array( // PSR Requirements
        'k0fragment' => $psm_fragments[86]['k0fragment'],
        'k0division' => $divisions[9]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III177')
    ),
    82 => array( // MI - Design and Installtion Good Practices
        'k0fragment' => $psm_fragments[97]['k0fragment'],
        'k0division' => $divisions[9]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III188')
    ),
    83 => array(
        'k0fragment' => $psm_fragments[105]['k0fragment'],
        'k0division' => $divisions[10]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ111')
    ),
    84 => array(
        'k0fragment' => $psm_fragments[106]['k0fragment'],
        'k0division' => $divisions[10]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ222')
    ),
    85 => array(
        'k0fragment' => $psm_fragments[107]['k0fragment'],
        'k0division' => $divisions[10]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ333')
    ),
    86 => array(
        'k0fragment' => $psm_fragments[108]['k0fragment'],
        'k0division' => $divisions[10]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ444')
    ),
    87 => array(
        'k0fragment' => $psm_fragments[109]['k0fragment'],
        'k0division' => $divisions[10]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ555')
    ),
    88 => array(
        'k0fragment' => $psm_fragments[110]['k0fragment'],
        'k0division' => $divisions[10]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ666')
    ),
    89 => array(
        'k0fragment' => $psm_fragments[111]['k0fragment'],
        'k0division' => $divisions[10]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ777')
    ),
    90 => array(
        'k0fragment' => $psm_fragments[112]['k0fragment'],
        'k0division' => $divisions[12]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL111')
    ),
    91 => array(
        'k0fragment' => $psm_fragments[113]['k0fragment'],
        'k0division' => $divisions[12]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL222')
    ),
    92 => array(
        'k0fragment' => $psm_fragments[114]['k0fragment'],
        'k0division' => $divisions[12]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL333')
    ),
    93 => array(
        'k0fragment' => $psm_fragments[115]['k0fragment'],
        'k0division' => $divisions[11]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK111')
    ),
    94 => array(
        'k0fragment' => $psm_fragments[116]['k0fragment'],
        'k0division' => $divisions[11]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK222')
    ),
    95 => array(
        'k0fragment' => $psm_fragments[117]['k0fragment'],
        'k0division' => $divisions[11]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK333')
    ),
    96 => array(
        'k0fragment' => $psm_fragments[118]['k0fragment'],
        'k0division' => $divisions[11]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK444')
    ),
    97 => array(
        'k0fragment' => $psm_fragments[119]['k0fragment'],
        'k0division' => $divisions[11]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK555')
    ),
    98 => array(
        'k0fragment' => $cap_fragments[54]['k0fragment'],
        'k0division' => $divisions[13]['k0division'], // $divisions[13] is Offsite-hazard assessment and reporting for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('MMM111')
    ),
    99 => array(
        'k0fragment' => $cap_fragments[55]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM122')
    ),
    100 => array(
        'k0fragment' => $cap_fragments[56]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM133')
    ),
    101 => array(
        'k0fragment' => $cap_fragments[57]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM144')
    ),
    102 => array(
        'k0fragment' => $cap_fragments[58]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM155')
    ),
    103 => array(
        'k0fragment' => $cap_fragments[59]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM166')
    ),
    104 => array(
        'k0fragment' => $cap_fragments[60]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM177')
    ),
    105 => array(
        'k0fragment' => $cap_fragments[61]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM188')
    ),
    106 => array(
        'k0fragment' => $cap_fragments[62]['k0fragment'],
        'k0division' => $divisions[13]['k0division'], // $divisions[13] is Offsite-hazard assessment and reporting for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('MMM199')
    ),
    107 => array(
        'k0fragment' => $cap_fragments[173]['k0fragment'],
        'k0division' => $divisions[12]['k0division'], // $divisions[12] is Emergency Planning for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('LLL444')
    ),
    108 => array(
        'k0fragment' => $cap_fragments[174]['k0fragment'],
        'k0division' => $divisions[12]['k0division'], // $divisions[12] is Emergency Planning for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('LLL555')
    ),
    109 => array(
        'k0fragment' => $cap_fragments[178]['k0fragment'],
        'k0division' => $divisions[13]['k0division'], // $divisions[13] is Offsite-hazard assessment and reporting for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('MMM211')
    ),
    110 => array(
        'k0fragment' => $cap_fragments[179]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM222')
    ),
    111 => array(
        'k0fragment' => $cap_fragments[180]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM233')
    ),
    112 => array(
        'k0fragment' => $cap_fragments[181]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM244')
    ),
    113 => array(
        'k0fragment' => $cap_fragments[182]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM255')
    ),
    114 => array(
        'k0fragment' => $cap_fragments[183]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM266')
    ),
    115 => array(
        'k0fragment' => $cap_fragments[184]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM277')
    ),
    116 => array(
        'k0fragment' => $cap_fragments[185]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM288')
    ),
    117 => array(
        'k0fragment' => $cap_fragments[186]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM299')
    ),
    118 => array(
        'k0fragment' => $cap_fragments[187]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM311')
    ),
    119 => array(
        'k0fragment' => $cap_fragments[188]['k0fragment'],
        'k0division' => $divisions[13]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM322')
    ),
    120 => array(
        'k0fragment' => $cap_fragments[190]['k0fragment'], // Recordkeeping
        'k0division' => $divisions[1]['k0division'], // $divisions[1] is Management System for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('AAA222')
    ),
    121 => array(
        'k0fragment' => $cap_fragments[192]['k0fragment'], // Affect on Air Operating Permits
        'k0division' => $divisions[13]['k0division'], // $divisions[13] is Offsite-hazard assessment and reporting for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('MMM344') // MMM333 is used for $cap_fragments[191], far below.
    ),
    122 => array(
        'k0fragment' => $psm_fragments[1]['k0fragment'],
        'k0division' => $divisions[14]['k0division'], // $divisions[14] to $divisions[29] and $divisions[49] to $divisions[50] is the OSHA PSM division method.
        'c5number' => $Zfpf->encrypt_1c('AAA111')
    ),
    123 => array(
        'k0fragment' => $psm_fragments[2]['k0fragment'],
        'k0division' => $divisions[14]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA222')
    ),
    124 => array(
        'k0fragment' => $psm_fragments[3]['k0fragment'],
        'k0division' => $divisions[14]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA333')
    ),
    125 => array(
        'k0fragment' => $psm_fragments[4]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB111')
    ),
    126 => array(
        'k0fragment' => $psm_fragments[5]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB122')
    ),
    127 => array(
        'k0fragment' => $psm_fragments[6]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB133')
    ),
    128 => array(
        'k0fragment' => $psm_fragments[7]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB144')
    ),
    129 => array(
        'k0fragment' => $psm_fragments[8]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB155')
    ),
    130 => array(
        'k0fragment' => $psm_fragments[9]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB166')
    ),
    131 => array(
        'k0fragment' => $psm_fragments[10]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB177')
    ),
    132 => array(
        'k0fragment' => $psm_fragments[11]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB188')
    ),
    133 => array(
        'k0fragment' => $psm_fragments[12]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB199')
    ),
    134 => array(
        'k0fragment' => $psm_fragments[13]['k0fragment'],
        'k0division' => $divisions[15]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB211')
    ),
    135 => array(
        'k0fragment' => $psm_fragments[14]['k0fragment'],
        'k0division' => $divisions[16]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC111')
    ),
    136 => array(
        'k0fragment' => $psm_fragments[15]['k0fragment'],
        'k0division' => $divisions[16]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC222')
    ),
    137 => array(
        'k0fragment' => $psm_fragments[16]['k0fragment'],
        'k0division' => $divisions[16]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC333')
    ),
    138 => array(
        'k0fragment' => $psm_fragments[17]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD111')
    ),
    139 => array(
        'k0fragment' => $psm_fragments[18]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD122')
    ),
    140 => array(
        'k0fragment' => $psm_fragments[19]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD133')
    ),
    141 => array(
        'k0fragment' => $psm_fragments[20]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD144')
    ),
    142 => array(
        'k0fragment' => $psm_fragments[21]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD155')
    ),
    143 => array(
        'k0fragment' => $psm_fragments[22]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD166')
    ),
    144 => array(
        'k0fragment' => $psm_fragments[23]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD177')
    ),
    145 => array(
        'k0fragment' => $psm_fragments[24]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD188')
    ),
    146 => array(
        'k0fragment' => $psm_fragments[25]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD199')
    ),
    147 => array(
        'k0fragment' => $psm_fragments[26]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD211')
    ),
    148 => array(
        'k0fragment' => $psm_fragments[27]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD222')
    ),
    149 => array(
        'k0fragment' => $psm_fragments[28]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD233')
    ),
    150 => array(
        'k0fragment' => $psm_fragments[29]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD244')
    ),
    151 => array(
        'k0fragment' => $psm_fragments[30]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD255')
    ),
    152 => array(
        'k0fragment' => $psm_fragments[31]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD266')
    ),
    153 => array(
        'k0fragment' => $psm_fragments[32]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD277')
    ),
    154 => array(
        'k0fragment' => $psm_fragments[33]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD288')
    ),
    155 => array(
        'k0fragment' => $psm_fragments[34]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD299')
    ),
    156 => array(
        'k0fragment' => $psm_fragments[35]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD311')
    ),
    157 => array(
        'k0fragment' => $psm_fragments[36]['k0fragment'],
        'k0division' => $divisions[17]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD322')
    ),
    158 => array(
        'k0fragment' => $psm_fragments[37]['k0fragment'],
        'k0division' => $divisions[18]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE111')
    ),
    159 => array(
        'k0fragment' => $psm_fragments[38]['k0fragment'],
        'k0division' => $divisions[18]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE222')
    ),
    160 => array(
        'k0fragment' => $psm_fragments[39]['k0fragment'],
        'k0division' => $divisions[18]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE333')
    ),
    161 => array(
        'k0fragment' => $psm_fragments[40]['k0fragment'],
        'k0division' => $divisions[18]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE444')
    ),
    162 => array(
        'k0fragment' => $psm_fragments[41]['k0fragment'],
        'k0division' => $divisions[18]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE555')
    ),
    163 => array(
        'k0fragment' => $psm_fragments[42]['k0fragment'],
        'k0division' => $divisions[18]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE666')
    ),
    164 => array(
        'k0fragment' => $psm_fragments[43]['k0fragment'],
        'k0division' => $divisions[18]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE777')
    ),
    165 => array(
        'k0fragment' => $psm_fragments[44]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF111')
    ),
    166 => array(
        'k0fragment' => $psm_fragments[45]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF122')
    ),
    167 => array(
        'k0fragment' => $psm_fragments[46]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF133')
    ),
    168 => array(
        'k0fragment' => $psm_fragments[47]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF144')
    ),
    169 => array(
        'k0fragment' => $psm_fragments[48]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF155')
    ),
    170 => array(
        'k0fragment' => $psm_fragments[49]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF166')
    ),
    171 => array(
        'k0fragment' => $psm_fragments[50]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF177')
    ),
    172 => array(
        'k0fragment' => $psm_fragments[51]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF188')
    ),
    173 => array(
        'k0fragment' => $psm_fragments[52]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF199')
    ),
    174 => array(
        'k0fragment' => $psm_fragments[53]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF211')
    ),
    175 => array(
        'k0fragment' => $psm_fragments[54]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF222')
    ),
    176 => array(
        'k0fragment' => $psm_fragments[55]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF233')
    ),
    177 => array(
        'k0fragment' => $psm_fragments[56]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF244')
    ),
    178 => array(
        'k0fragment' => $psm_fragments[57]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF255')
    ),
    179 => array(
        'k0fragment' => $psm_fragments[58]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF266')
    ),
    180 => array(
        'k0fragment' => $psm_fragments[59]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF277')
    ),
    181 => array(
        'k0fragment' => $psm_fragments[60]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF288')
    ),
    182 => array(
        'k0fragment' => $psm_fragments[61]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF299')
    ),
    183 => array(
        'k0fragment' => $psm_fragments[62]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF311')
    ),
    184 => array(
        'k0fragment' => $psm_fragments[63]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF322')
    ),
    185 => array(
        'k0fragment' => $psm_fragments[64]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF333')
    ),
    186 => array(
        'k0fragment' => $psm_fragments[65]['k0fragment'],
        'k0division' => $divisions[19]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF344')
    ),
    187 => array(
        'k0fragment' => $psm_fragments[66]['k0fragment'],
        'k0division' => $divisions[20]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG111')
    ),
    188 => array(
        'k0fragment' => $psm_fragments[67]['k0fragment'],
        'k0division' => $divisions[20]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG222')
    ),
    189 => array(
        'k0fragment' => $psm_fragments[68]['k0fragment'],
        'k0division' => $divisions[20]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG333')
    ),
    190 => array(
        'k0fragment' => $psm_fragments[69]['k0fragment'],
        'k0division' => $divisions[20]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG444')
    ),
    191 => array(
        'k0fragment' => $psm_fragments[70]['k0fragment'],
        'k0division' => $divisions[20]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG555')
    ),
    192 => array(
        'k0fragment' => $psm_fragments[71]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH111')
    ),
    193 => array(
        'k0fragment' => $psm_fragments[72]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH122')
    ),
    194 => array(
        'k0fragment' => $psm_fragments[73]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH133')
    ),
    195 => array(
        'k0fragment' => $psm_fragments[74]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH144')
    ),
    196 => array(
        'k0fragment' => $psm_fragments[75]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH155')
    ),
    197 => array(
        'k0fragment' => $psm_fragments[76]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH166')
    ),
    198 => array(
        'k0fragment' => $psm_fragments[77]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH177')
    ),
    199 => array(
        'k0fragment' => $psm_fragments[78]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH188')
    ),
    200 => array(
        'k0fragment' => $psm_fragments[79]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH199')
    ),
    201 => array(
        'k0fragment' => $psm_fragments[80]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH211')
    ),
    202 => array(
        'k0fragment' => $psm_fragments[81]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH222')
    ),
    203 => array(
        'k0fragment' => $psm_fragments[82]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH233')
    ),
    204 => array(
        'k0fragment' => $psm_fragments[83]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH244')
    ),
    205 => array(
        'k0fragment' => $psm_fragments[84]['k0fragment'],
        'k0division' => $divisions[21]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH255')
    ),
    206 => array(
        'k0fragment' => $psm_fragments[85]['k0fragment'],
        'k0division' => $divisions[22]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III111')
    ),
    207 => array(
        'k0fragment' => $psm_fragments[86]['k0fragment'],
        'k0division' => $divisions[22]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III222')
    ),
    208 => array(
        'k0fragment' => $psm_fragments[87]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ111')
    ),
    209 => array(
        'k0fragment' => $psm_fragments[88]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ122')
    ),
    210 => array(
        'k0fragment' => $psm_fragments[89]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ133')
    ),
    211 => array(
        'k0fragment' => $psm_fragments[90]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ144')
    ), /* $psm_fragments[91] to [93] combined into [89]
    212 => array(
        'k0fragment' => $psm_fragments[91]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ155')
    ),
    213 => array(
        'k0fragment' => $psm_fragments[92]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ166')
    ),
    214 => array(
        'k0fragment' => $psm_fragments[93]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ177')
    ),
    215 => array(
        'k0fragment' => $psm_fragments[94]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ188')
    ),*/
    216 => array(
        'k0fragment' => $psm_fragments[95]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ199')
    ),
    217 => array(
        'k0fragment' => $psm_fragments[96]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ211')
    ),
    218 => array(
        'k0fragment' => $psm_fragments[97]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ222')
    ),
    219 => array(
        'k0fragment' => $psm_fragments[98]['k0fragment'],
        'k0division' => $divisions[23]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ233')
    ),
    220 => array(
        'k0fragment' => $psm_fragments[99]['k0fragment'],
        'k0division' => $divisions[24]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK111')
    ),
    221 => array(
        'k0fragment' => $psm_fragments[100]['k0fragment'],
        'k0division' => $divisions[25]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL111')
    ),
    222 => array(
        'k0fragment' => $psm_fragments[101]['k0fragment'],
        'k0division' => $divisions[25]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL222')
    ),
    223 => array(
        'k0fragment' => $psm_fragments[102]['k0fragment'],
        'k0division' => $divisions[25]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL333')
    ),
    224 => array(
        'k0fragment' => $psm_fragments[103]['k0fragment'],
        'k0division' => $divisions[25]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL444')
    ),
    225 => array(
        'k0fragment' => $psm_fragments[104]['k0fragment'],
        'k0division' => $divisions[25]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL555')
    ),
    226 => array(
        'k0fragment' => $psm_fragments[105]['k0fragment'],
        'k0division' => $divisions[26]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM111')
    ),
    227 => array(
        'k0fragment' => $psm_fragments[106]['k0fragment'],
        'k0division' => $divisions[26]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM222')
    ),
    228 => array(
        'k0fragment' => $psm_fragments[107]['k0fragment'],
        'k0division' => $divisions[26]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM333')
    ),
    229 => array(
        'k0fragment' => $psm_fragments[108]['k0fragment'],
        'k0division' => $divisions[26]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM444')
    ),
    230 => array(
        'k0fragment' => $psm_fragments[109]['k0fragment'],
        'k0division' => $divisions[26]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM555')
    ),
    231 => array(
        'k0fragment' => $psm_fragments[110]['k0fragment'],
        'k0division' => $divisions[26]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM666')
    ),
    232 => array(
        'k0fragment' => $psm_fragments[111]['k0fragment'],
        'k0division' => $divisions[26]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM777')
    ),
    233 => array(
        'k0fragment' => $psm_fragments[112]['k0fragment'],
        'k0division' => $divisions[27]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN111')
    ),
    234 => array(
        'k0fragment' => $psm_fragments[113]['k0fragment'],
        'k0division' => $divisions[27]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN222')
    ),
    235 => array(
        'k0fragment' => $psm_fragments[114]['k0fragment'],
        'k0division' => $divisions[27]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN333')
    ),
    236 => array(
        'k0fragment' => $psm_fragments[115]['k0fragment'],
        'k0division' => $divisions[28]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO111')
    ),
    237 => array(
        'k0fragment' => $psm_fragments[116]['k0fragment'],
        'k0division' => $divisions[28]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO222')
    ),
    238 => array(
        'k0fragment' => $psm_fragments[117]['k0fragment'],
        'k0division' => $divisions[28]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO333')
    ),
    239 => array(
        'k0fragment' => $psm_fragments[118]['k0fragment'],
        'k0division' => $divisions[28]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO444')
    ),
    240 => array(
        'k0fragment' => $psm_fragments[119]['k0fragment'],
        'k0division' => $divisions[28]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO555')
    ),
    241 => array(
        'k0fragment' => $psm_fragments[120]['k0fragment'],
        'k0division' => $divisions[29]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('PPP111')
    ),
    242 => array(
        'k0fragment' => $psm_fragments[121]['k0fragment'],
        'k0division' => $divisions[29]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('PPP222')
    ),
    243 => array(
        'k0fragment' => $psm_fragments[122]['k0fragment'],
        'k0division' => $divisions[29]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('PPP333')
    ),
    244 => array(
        'k0fragment' => $psm_fragments[123]['k0fragment'],
        'k0division' => $divisions[49]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ111')
    ),
    245 => array(
        'k0fragment' => $psm_fragments[124]['k0fragment'],
        'k0division' => $divisions[50]['k0division'],  // $divisions[14] to $divisions[29] and $divisions[49] to $divisions[50] is the OSHA PSM division method.
        'c5number' => $Zfpf->encrypt_1c('RRR111')
    ),
    246 => array(
        'k0fragment' => $cap_fragments[0]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],  // $divisions[30] to $divisions[48] is the EPA CAP (Program 3 only) division method.
        'c5number' => $Zfpf->encrypt_1c('AAA111')
    ),
    247 => array(
        'k0fragment' => $cap_fragments[1]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA122')
    ),
    248 => array(
        'k0fragment' => $cap_fragments[2]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA133')
    ),
    249 => array(
        'k0fragment' => $cap_fragments[3]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA144')
    ),
    250 => array(
        'k0fragment' => $cap_fragments[4]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA155')
    ),
    251 => array(
        'k0fragment' => $cap_fragments[5]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA166')
    ),
    252 => array(
        'k0fragment' => $cap_fragments[6]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA177')
    ),
    253 => array(
        'k0fragment' => $cap_fragments[7]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA188')
    ),
    254 => array(
        'k0fragment' => $cap_fragments[8]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA199')
    ),
    255 => array(
        'k0fragment' => $cap_fragments[9]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA211')
    ),
    256 => array(
        'k0fragment' => $cap_fragments[10]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA222')
    ),
    257 => array(
        'k0fragment' => $cap_fragments[11]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA233')
    ),
    258 => array(
        'k0fragment' => $cap_fragments[12]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA244')
    ),
    259 => array(
        'k0fragment' => $cap_fragments[13]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA255')
    ),
    260 => array(
        'k0fragment' => $cap_fragments[14]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA266')
    ),
    261 => array(
        'k0fragment' => $cap_fragments[15]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA277')
    ),
    262 => array(
        'k0fragment' => $cap_fragments[16]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA288')
    ),
    263 => array(
        'k0fragment' => $cap_fragments[17]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA299')
    ),
    264 => array(
        'k0fragment' => $cap_fragments[18]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA311')
    ),
    265 => array(
        'k0fragment' => $cap_fragments[19]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA322')
    ),
    266 => array(
        'k0fragment' => $cap_fragments[20]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA333')
    ),
    267 => array(
        'k0fragment' => $cap_fragments[21]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA344')
    ),
    268 => array(
        'k0fragment' => $cap_fragments[22]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA355')
    ),
    269 => array(
        'k0fragment' => $cap_fragments[23]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA366')
    ),
    270 => array(
        'k0fragment' => $cap_fragments[24]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA377')
    ),
    271 => array(
        'k0fragment' => $cap_fragments[25]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA388')
    ),
    272 => array(
        'k0fragment' => $cap_fragments[26]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA399')
    ),
    273 => array(
        'k0fragment' => $cap_fragments[27]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA411')
    ),
    274 => array(
        'k0fragment' => $cap_fragments[28]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA422')
    ),
    275 => array(
        'k0fragment' => $cap_fragments[29]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA433')
    ),
    276 => array(
        'k0fragment' => $cap_fragments[30]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA444')
    ),
    277 => array(
        'k0fragment' => $cap_fragments[31]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA455')
    ),
    278 => array(
        'k0fragment' => $cap_fragments[32]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA466')
    ),
    279 => array(
        'k0fragment' => $cap_fragments[33]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA477')
    ),
    280 => array(
        'k0fragment' => $cap_fragments[34]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA488')
    ),
    281 => array(
        'k0fragment' => $cap_fragments[35]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA499')
    ),
    282 => array(
        'k0fragment' => $cap_fragments[36]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA511')
    ),
    283 => array(
        'k0fragment' => $cap_fragments[37]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA522')
    ),
    284 => array(
        'k0fragment' => $cap_fragments[38]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA533')
    ),
    285 => array(
        'k0fragment' => $cap_fragments[39]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA544')
    ),
    286 => array(
        'k0fragment' => $cap_fragments[40]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA555')
    ),
    287 => array(
        'k0fragment' => $cap_fragments[41]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA566')
    ),
    288 => array(
        'k0fragment' => $cap_fragments[42]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA577')
    ),
    289 => array(
        'k0fragment' => $cap_fragments[43]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA588')
    ),
    290 => array(
        'k0fragment' => $cap_fragments[45]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA599')
    ),
    291 => array(
        'k0fragment' => $cap_fragments[46]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA611')
    ),
    292 => array(
        'k0fragment' => $cap_fragments[47]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA622')
    ),
    293 => array(
        'k0fragment' => $cap_fragments[48]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA633')
    ),
    294 => array(
        'k0fragment' => $cap_fragments[49]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA644')
    ),
    295 => array(
        'k0fragment' => $cap_fragments[50]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA655')
    ),
    296 => array(
        'k0fragment' => $cap_fragments[51]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA666')
    ),
    297 => array(
        'k0fragment' => $cap_fragments[52]['k0fragment'],
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA677')
    ),
    298 => array(
        'k0fragment' => $cap_fragments[53]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB111')
    ),
    299 => array(
        'k0fragment' => $cap_fragments[54]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB122')
    ),
    300 => array(
        'k0fragment' => $cap_fragments[55]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB133')
    ),
    301 => array(
        'k0fragment' => $cap_fragments[56]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB144')
    ),
    302 => array(
        'k0fragment' => $cap_fragments[57]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB155')
    ),
    303 => array(
        'k0fragment' => $cap_fragments[58]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB166')
    ),
    304 => array(
        'k0fragment' => $cap_fragments[59]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB177')
    ),
    305 => array(
        'k0fragment' => $cap_fragments[60]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB188')
    ),
    306 => array(
        'k0fragment' => $cap_fragments[61]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB199')
    ),
    307 => array(
        'k0fragment' => $cap_fragments[62]['k0fragment'],
        'k0division' => $divisions[31]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('BBB211')
    ),
    308 => array(
        'k0fragment' => $cap_fragments[71]['k0fragment'], // $cap_fragments[63] to $cap_fragments[70] is the Program 2 Prevention Program, which is not included in this division.
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC111')
    ),
    309 => array(
        'k0fragment' => $cap_fragments[72]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC122')
    ),
    310 => array(
        'k0fragment' => $cap_fragments[73]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC133')
    ),
    311 => array(
        'k0fragment' => $cap_fragments[74]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC144')
    ),
    312 => array(
        'k0fragment' => $cap_fragments[75]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC155')
    ),
    313 => array(
        'k0fragment' => $cap_fragments[76]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC166')
    ),
    314 => array(
        'k0fragment' => $cap_fragments[77]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC177')
    ),
    315 => array(
        'k0fragment' => $cap_fragments[78]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC188')
    ),
    316 => array(
        'k0fragment' => $cap_fragments[79]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC199')
    ),
    317 => array(
        'k0fragment' => $cap_fragments[80]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC211')
    ),
    318 => array(
        'k0fragment' => $cap_fragments[81]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC222')
    ),
    319 => array(
        'k0fragment' => $cap_fragments[82]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC233')
    ),
    320 => array(
        'k0fragment' => $cap_fragments[83]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC244')
    ),
    321 => array(
        'k0fragment' => $cap_fragments[84]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC255')
    ),
    322 => array(
        'k0fragment' => $cap_fragments[85]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC266')
    ),
    323 => array(
        'k0fragment' => $cap_fragments[86]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC277')
    ),
    324 => array(
        'k0fragment' => $cap_fragments[87]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC288')
    ),
    325 => array(
        'k0fragment' => $cap_fragments[88]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC299')
    ),
    326 => array(
        'k0fragment' => $cap_fragments[89]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC311')
    ),
    327 => array(
        'k0fragment' => $cap_fragments[90]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC322')
    ),
    328 => array(
        'k0fragment' => $cap_fragments[91]['k0fragment'],
        'k0division' => $divisions[32]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('CCC333')
    ),
    329 => array(
        'k0fragment' => $cap_fragments[92]['k0fragment'],
        'k0division' => $divisions[33]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD111')
    ),
    330 => array(
        'k0fragment' => $cap_fragments[93]['k0fragment'],
        'k0division' => $divisions[33]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD222')
    ),
    331 => array(
        'k0fragment' => $cap_fragments[94]['k0fragment'],
        'k0division' => $divisions[33]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD333')
    ),
    332 => array(
        'k0fragment' => $cap_fragments[95]['k0fragment'],
        'k0division' => $divisions[33]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD444')
    ),
    333 => array(
        'k0fragment' => $cap_fragments[96]['k0fragment'],
        'k0division' => $divisions[33]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD555')
    ),
    334 => array(
        'k0fragment' => $cap_fragments[97]['k0fragment'],
        'k0division' => $divisions[33]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD666')
    ),
    335 => array(
        'k0fragment' => $cap_fragments[98]['k0fragment'],
        'k0division' => $divisions[33]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('DDD777')
    ),
    336 => array(
        'k0fragment' => $cap_fragments[99]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE111')
    ),
    337 => array(
        'k0fragment' => $cap_fragments[100]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE122')
    ),
    338 => array(
        'k0fragment' => $cap_fragments[101]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE133')
    ),
    339 => array(
        'k0fragment' => $cap_fragments[102]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE144')
    ),
    340 => array(
        'k0fragment' => $cap_fragments[103]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE155')
    ),
    341 => array(
        'k0fragment' => $cap_fragments[104]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE166')
    ),
    342 => array(
        'k0fragment' => $cap_fragments[105]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE177')
    ),
    343 => array(
        'k0fragment' => $cap_fragments[106]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE188')
    ),
    344 => array(
        'k0fragment' => $cap_fragments[107]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE199')
    ),
    345 => array(
        'k0fragment' => $cap_fragments[108]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE211')
    ),
    346 => array(
        'k0fragment' => $cap_fragments[109]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE222')
    ),
    347 => array(
        'k0fragment' => $cap_fragments[110]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE233')
    ),
    348 => array(
        'k0fragment' => $cap_fragments[111]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE244')
    ),
    349 => array(
        'k0fragment' => $cap_fragments[112]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE255')
    ),
    350 => array(
        'k0fragment' => $cap_fragments[113]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE266')
    ),
    351 => array(
        'k0fragment' => $cap_fragments[114]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE277')
    ),
    352 => array(
        'k0fragment' => $cap_fragments[115]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE288')
    ),
    353 => array(
        'k0fragment' => $cap_fragments[116]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE299')
    ),
    354 => array(
        'k0fragment' => $cap_fragments[117]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE311')
    ),
    355 => array(
        'k0fragment' => $cap_fragments[118]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE322')
    ),
    356 => array(
        'k0fragment' => $cap_fragments[119]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE333')
    ),
    357 => array(
        'k0fragment' => $cap_fragments[120]['k0fragment'],
        'k0division' => $divisions[34]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('EEE344')
    ),
    358 => array(
        'k0fragment' => $cap_fragments[121]['k0fragment'],
        'k0division' => $divisions[35]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF111')
    ),
    359 => array(
        'k0fragment' => $cap_fragments[122]['k0fragment'],
        'k0division' => $divisions[35]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF222')
    ),
    360 => array(
        'k0fragment' => $cap_fragments[123]['k0fragment'],
        'k0division' => $divisions[35]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF333')
    ),
    361 => array(
        'k0fragment' => $cap_fragments[124]['k0fragment'],
        'k0division' => $divisions[35]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF444')
    ),
    362 => array(
        'k0fragment' => $cap_fragments[125]['k0fragment'],
        'k0division' => $divisions[35]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('FFF555')
    ),
    363 => array(
        'k0fragment' => $cap_fragments[126]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG111')
    ),
    364 => array(
        'k0fragment' => $cap_fragments[127]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG122')
    ),
    365 => array(
        'k0fragment' => $cap_fragments[128]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG133')
    ),
    366 => array(
        'k0fragment' => $cap_fragments[129]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG144')
    ), /* $cap_fragments[129] to [132] combined into [129]
    367 => array(
        'k0fragment' => $cap_fragments[130]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG155')
    ),
    368 => array(
        'k0fragment' => $cap_fragments[131]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG166')
    ),
    369 => array(
        'k0fragment' => $cap_fragments[132]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG177')
    ),*/
    370 => array(
        'k0fragment' => $cap_fragments[133]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG188')
    ),
    371 => array(
        'k0fragment' => $cap_fragments[134]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG199')
    ),
    372 => array(
        'k0fragment' => $cap_fragments[135]['k0fragment'],
        'k0division' => $divisions[36]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('GGG211')
    ),
    373 => array(
        'k0fragment' => $cap_fragments[136]['k0fragment'],
        'k0division' => $divisions[37]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH111')
    ),
    374 => array(
        'k0fragment' => $cap_fragments[137]['k0fragment'],
        'k0division' => $divisions[37]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH222')
    ),
    375 => array(
        'k0fragment' => $cap_fragments[138]['k0fragment'],
        'k0division' => $divisions[37]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH333')
    ),
    376 => array(
        'k0fragment' => $cap_fragments[139]['k0fragment'],
        'k0division' => $divisions[37]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH444')
    ),
    377 => array(
        'k0fragment' => $cap_fragments[140]['k0fragment'],
        'k0division' => $divisions[37]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('HHH555')
    ),
    378 => array(
        'k0fragment' => $cap_fragments[141]['k0fragment'],
        'k0division' => $divisions[38]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III111')
    ),
    379 => array(
        'k0fragment' => $cap_fragments[142]['k0fragment'],
        'k0division' => $divisions[38]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('III222')
    ),
    380 => array(
        'k0fragment' => $cap_fragments[143]['k0fragment'],
        'k0division' => $divisions[39]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ111')
    ),
    381 => array(
        'k0fragment' => $cap_fragments[144]['k0fragment'],
        'k0division' => $divisions[39]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ222')
    ),
    382 => array(
        'k0fragment' => $cap_fragments[145]['k0fragment'],
        'k0division' => $divisions[39]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ333')
    ),
    383 => array(
        'k0fragment' => $cap_fragments[146]['k0fragment'],
        'k0division' => $divisions[39]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ444')
    ),
    384 => array(
        'k0fragment' => $cap_fragments[147]['k0fragment'],
        'k0division' => $divisions[39]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('JJJ555')
    ),
    385 => array(
        'k0fragment' => $cap_fragments[148]['k0fragment'],
        'k0division' => $divisions[40]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK111')
    ),
    386 => array(
        'k0fragment' => $cap_fragments[149]['k0fragment'],
        'k0division' => $divisions[40]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK222')
    ),
    387 => array(
        'k0fragment' => $cap_fragments[150]['k0fragment'],
        'k0division' => $divisions[40]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK333')
    ),
    388 => array(
        'k0fragment' => $cap_fragments[151]['k0fragment'],
        'k0division' => $divisions[40]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK444')
    ),
    389 => array(
        'k0fragment' => $cap_fragments[152]['k0fragment'],
        'k0division' => $divisions[40]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK555')
    ),
    390 => array(
        'k0fragment' => $cap_fragments[153]['k0fragment'],
        'k0division' => $divisions[40]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK666')
    ),
    391 => array(
        'k0fragment' => $cap_fragments[154]['k0fragment'],
        'k0division' => $divisions[40]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('KKK777')
    ),
    392 => array(
        'k0fragment' => $cap_fragments[155]['k0fragment'],
        'k0division' => $divisions[41]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL111')
    ),
    393 => array(
        'k0fragment' => $cap_fragments[156]['k0fragment'],
        'k0division' => $divisions[41]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL222')
    ),
    394 => array(
        'k0fragment' => $cap_fragments[157]['k0fragment'],
        'k0division' => $divisions[41]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('LLL333')
    ),
    395 => array(
        'k0fragment' => $cap_fragments[158]['k0fragment'],
        'k0division' => $divisions[42]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('MMM111')
    ),
    396 => array(
        'k0fragment' => $cap_fragments[159]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN111')
    ),
    397 => array(
        'k0fragment' => $cap_fragments[160]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN122')
    ),
    398 => array(
        'k0fragment' => $cap_fragments[161]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN133')
    ),
    399 => array(
        'k0fragment' => $cap_fragments[162]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN144')
    ),
    400 => array(
        'k0fragment' => $cap_fragments[163]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN155')
    ),
    401 => array(
        'k0fragment' => $cap_fragments[164]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN166')
    ),
    402 => array(
        'k0fragment' => $cap_fragments[165]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN177')
    ),
    403 => array(
        'k0fragment' => $cap_fragments[166]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN188')
    ),
    404 => array(
        'k0fragment' => $cap_fragments[167]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN199')
    ),
    405 => array(
        'k0fragment' => $cap_fragments[168]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN211')
    ),
    406 => array(
        'k0fragment' => $cap_fragments[169]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN222')
    ),
    407 => array(
        'k0fragment' => $cap_fragments[170]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN233')
    ),
    408 => array(
        'k0fragment' => $cap_fragments[171]['k0fragment'],
        'k0division' => $divisions[43]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('NNN244')
    ),
    409 => array(
        'k0fragment' => $cap_fragments[172]['k0fragment'],
        'k0division' => $divisions[44]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO111')
    ),
    410 => array(
        'k0fragment' => $cap_fragments[173]['k0fragment'],
        'k0division' => $divisions[44]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO222')
    ),
    411 => array(
        'k0fragment' => $cap_fragments[174]['k0fragment'],
        'k0division' => $divisions[44]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO333')
    ),
    412 => array(
        'k0fragment' => $cap_fragments[175]['k0fragment'],
        'k0division' => $divisions[45]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('PPP111')
    ),
    413 => array(
        'k0fragment' => $cap_fragments[176]['k0fragment'],
        'k0division' => $divisions[45]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('PPP222')
    ),
    414 => array(
        'k0fragment' => $cap_fragments[177]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ111')
    ),
    415 => array(
        'k0fragment' => $cap_fragments[178]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ122')
    ),
    416 => array(
        'k0fragment' => $cap_fragments[179]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ133')
    ),
    417 => array(
        'k0fragment' => $cap_fragments[180]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ144')
    ),
    418 => array(
        'k0fragment' => $cap_fragments[181]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ155')
    ),
    419 => array(
        'k0fragment' => $cap_fragments[182]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ166')
    ),
    420 => array(
        'k0fragment' => $cap_fragments[183]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ177')
    ),
    421 => array(
        'k0fragment' => $cap_fragments[184]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ188')
    ),
    422 => array(
        'k0fragment' => $cap_fragments[185]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ199')
    ),
    423 => array(
        'k0fragment' => $cap_fragments[186]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ211')
    ),
    424 => array(
        'k0fragment' => $cap_fragments[187]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ222')
    ),
    425 => array(
        'k0fragment' => $cap_fragments[188]['k0fragment'],
        'k0division' => $divisions[46]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('QQQ233')
    ),
    426 => array(
        'k0fragment' => $cap_fragments[189]['k0fragment'],
        'k0division' => $divisions[47]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('RRR111')
    ),
    427 => array(
        'k0fragment' => $cap_fragments[190]['k0fragment'],
        'k0division' => $divisions[47]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('RRR222')
    ),
    428 => array(
        'k0fragment' => $cap_fragments[191]['k0fragment'],
        'k0division' => $divisions[47]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('RRR333')
    ),
    429 => array(
        'k0fragment' => $cap_fragments[192]['k0fragment'],
        'k0division' => $divisions[47]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('RRR444')
    ),
    430 => array(
        'k0fragment' => $cap_fragments[193]['k0fragment'],
        'k0division' => $divisions[47]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('RRR555')
    ),
    431 => array(
        'k0fragment' => $cap_fragments[194]['k0fragment'],
        'k0division' => $divisions[48]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('SSS111')
    ),
    432 => array(
        'k0fragment' => $cap_fragments[195]['k0fragment'], // 2017-2019 Emergency response coordination activities
        'k0division' => $divisions[44]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO280')
    ),
    433 => array(
        'k0fragment' => $cap_fragments[196]['k0fragment'], // 2017-2019 Emergency response exercises
        'k0division' => $divisions[44]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('OOO444')
    ),
    434 => array(
        'k0fragment' => $cap_fragments[197]['k0fragment'], // 2017 CBI definition
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA260')
    ),
    435 => array(
        'k0fragment' => $cap_fragments[198]['k0fragment'], // 2017 LEPC definition
        'k0division' => $divisions[30]['k0division'],
        'c5number' => $Zfpf->encrypt_1c('AAA382')
    ),
    436 => array(
        'k0fragment' => $cap_fragments[195]['k0fragment'], // 2017-2019 Emergency response coordination activities
        'k0division' => $divisions[12]['k0division'], // $divisions[12] is Emergency Planning for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('LLL480')
    ),
    437 => array(
        'k0fragment' => $cap_fragments[196]['k0fragment'], // 2017-2019 Emergency response exercises
        'k0division' => $divisions[12]['k0division'], // $divisions[12] is Emergency Planning for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('LLL590')
    ),
    438 => array(
        'k0fragment' => $psm_fragments[17]['k0fragment'], // This fragment was intentionally skipped in early draft.
        'k0division' => $divisions[3]['k0division'], // Cheesehead division method, PSI division
        'c5number' => $Zfpf->encrypt_1c('CCC065')
    ),
    439 => array(
        'k0fragment' => $cap_fragments[191]['k0fragment'], // Public Access to RMP Information (This was skipped in early draft.)
        'k0division' => $divisions[13]['k0division'], // $divisions[13] is Offsite-hazard assessment and reporting for the Cheesehead division method.
        'c5number' => $Zfpf->encrypt_1c('MMM333')
    )
);
// Keys less than 100000 are reserved for templates.
foreach ($fragment_division as $K => $V) {
    $V['k0fragment_division'] = $K;
    $fragment_division[$K]['k0fragment_division'] = $V['k0fragment_division'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0fragment_division', $V);
}

