<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// Populate t0fragment_practice
$fragment_practice = array(
    1 => array(
        'k0fragment' => $cap_fragments[52]['k0fragment'],
        'k0practice' => $practices[2]['k0practice'] // Start with $practices[2] because $practices[1] is the action register, which is accessed from the left-hand contents, so is not associated with any rule fragments.
    ),
    2 => array(
        'k0fragment' => $cap_fragments[52]['k0fragment'],
        'k0practice' => $practices[3]['k0practice']
    ),
    3 => array(
        'k0fragment' => $cap_fragments[52]['k0fragment'],
        'k0practice' => $practices[4]['k0practice']
    ),
    4 => array(
        'k0fragment' => $psm_fragments[14]['k0fragment'],
        'k0practice' => $practices[5]['k0practice']
    ),
    5 => array(
        'k0fragment' => $psm_fragments[14]['k0fragment'],
        'k0practice' => $practices[6]['k0practice']
    ),
    6 => array(
        'k0fragment' => $psm_fragments[14]['k0fragment'],
        'k0practice' => $practices[7]['k0practice']
    ),
    7 => array(
        'k0fragment' => $psm_fragments[14]['k0fragment'],
        'k0practice' => $practices[8]['k0practice']
    ),
    8 => array(
        'k0fragment' => $psm_fragments[14]['k0fragment'],
        'k0practice' => $practices[9]['k0practice']
    ),
    9 => array(
        'k0fragment' => $psm_fragments[14]['k0fragment'],
        'k0practice' => $practices[10]['k0practice']
    ),
    10 => array(
        'k0fragment' => $psm_fragments[14]['k0fragment'],
        'k0practice' => $practices[11]['k0practice']
    ),
    11 => array(
        'k0fragment' => $psm_fragments[15]['k0fragment'],
        'k0practice' => $practices[8]['k0practice']
    ),
    12 => array(
        'k0fragment' => $psm_fragments[15]['k0fragment'],
        'k0practice' => $practices[9]['k0practice']
    ),
    13 => array(
        'k0fragment' => $psm_fragments[15]['k0fragment'],
        'k0practice' => $practices[10]['k0practice']
    ),
    14 => array(
        'k0fragment' => $psm_fragments[15]['k0fragment'],
        'k0practice' => $practices[11]['k0practice']
    ),
    15 => array(
        'k0fragment' => $psm_fragments[16]['k0fragment'],
        'k0practice' => $practices[6]['k0practice']
    ),
    16 => array(
        'k0fragment' => $psm_fragments[16]['k0fragment'],
        'k0practice' => $practices[7]['k0practice']
    ),
    17 => array(
        'k0fragment' => $psm_fragments[85]['k0fragment'],
        'k0practice' => $practices[13]['k0practice']
    ),
    18 => array(
        'k0fragment' => $psm_fragments[85]['k0fragment'],
        'k0practice' => $practices[13]['k0practice']
    ),
    19 => array(
        'k0fragment' => $psm_fragments[86]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    20 => array(
        'k0fragment' => $psm_fragments[97]['k0fragment'], // Mechanical Integrity -- new construction quality assurance
        'k0practice' => $practices[14]['k0practice']
    ),
    21 => array(
        'k0fragment' => $psm_fragments[100]['k0fragment'],
        'k0practice' => $practices[13]['k0practice']
    ),
    22 => array(
        'k0fragment' => $psm_fragments[100]['k0fragment'],
        'k0practice' => $practices[13]['k0practice']
    ),
    23 => array(
        'k0fragment' => $psm_fragments[101]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    24 => array(
        'k0fragment' => $psm_fragments[102]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    25 => array(
        'k0fragment' => $psm_fragments[103]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    26 => array(
        'k0fragment' => $psm_fragments[104]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    27 => array(
        'k0fragment' => $psm_fragments[37]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    28 => array(
        'k0fragment' => $psm_fragments[38]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    29 => array(
        'k0fragment' => $psm_fragments[39]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    30 => array(
        'k0fragment' => $psm_fragments[40]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    31 => array(
        'k0fragment' => $psm_fragments[41]['k0fragment'],
        'k0practice' => $practices[19]['k0practice']
    ),
    32 => array(
        'k0fragment' => $psm_fragments[42]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    33 => array(
        'k0fragment' => $psm_fragments[43]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    34 => array( // Include "Issue PHA or HIRA" with fragments associated with "View or Revise PHA or HIRA"
        'k0fragment' => $psm_fragments[38]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    35 => array( // Include "Issue PHA or HIRA" with fragments associated with "View or Revise PHA or HIRA"
        'k0fragment' => $psm_fragments[39]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    36 => array( // Include "Issue PHA or HIRA" with fragments associated with "View or Revise PHA or HIRA"
        'k0fragment' => $psm_fragments[40]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    37 => array( // Include "Issue PHA or HIRA" with fragments associated with "View or Revise PHA or HIRA"
        'k0fragment' => $psm_fragments[42]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    38 => array( // $practices[2] to $practices[4] are already associated with $cap_fragments[52] above.
        'k0fragment' => $cap_fragments[155]['k0fragment'],
        'k0practice' => $practices[5]['k0practice']
    ),
    39 => array(
        'k0fragment' => $cap_fragments[155]['k0fragment'],
        'k0practice' => $practices[6]['k0practice']
    ),
    40 => array(
        'k0fragment' => $cap_fragments[157]['k0fragment'],
        'k0practice' => $practices[6]['k0practice']
    ),
    41 => array(
        'k0fragment' => $cap_fragments[155]['k0fragment'],
        'k0practice' => $practices[7]['k0practice']
    ),
    42 => array(
        'k0fragment' => $cap_fragments[157]['k0fragment'],
        'k0practice' => $practices[7]['k0practice']
    ),
    43 => array(
        'k0fragment' => $cap_fragments[155]['k0fragment'],
        'k0practice' => $practices[8]['k0practice']
    ),
    44 => array(
        'k0fragment' => $cap_fragments[156]['k0fragment'],
        'k0practice' => $practices[8]['k0practice']
    ),
    45 => array(
        'k0fragment' => $cap_fragments[155]['k0fragment'],
        'k0practice' => $practices[9]['k0practice']
    ),
    46 => array(
        'k0fragment' => $cap_fragments[156]['k0fragment'],
        'k0practice' => $practices[9]['k0practice']
    ),
    47 => array(
        'k0fragment' => $cap_fragments[155]['k0fragment'],
        'k0practice' => $practices[10]['k0practice']
    ),
    48 => array(
        'k0fragment' => $cap_fragments[156]['k0fragment'],
        'k0practice' => $practices[10]['k0practice']
    ),
    49 => array(
        'k0fragment' => $cap_fragments[155]['k0fragment'],
        'k0practice' => $practices[11]['k0practice']
    ),
    50 => array(
        'k0fragment' => $cap_fragments[156]['k0fragment'],
        'k0practice' => $practices[11]['k0practice']
    ),
    51 => array(
        'k0fragment' => $cap_fragments[136]['k0fragment'],
        'k0practice' => $practices[13]['k0practice']
    ),
    52 => array(
        'k0fragment' => $cap_fragments[141]['k0fragment'],
        'k0practice' => $practices[13]['k0practice']
    ),
    53 => array(
        'k0fragment' => $cap_fragments[136]['k0fragment'],
        'k0practice' => $practices[13]['k0practice']
    ),
    54 => array(
        'k0fragment' => $cap_fragments[141]['k0fragment'],
        'k0practice' => $practices[13]['k0practice']
    ),
    55 => array(
        'k0fragment' => $cap_fragments[137]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    56 => array(
        'k0fragment' => $cap_fragments[138]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    57 => array(
        'k0fragment' => $cap_fragments[139]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    58 => array(
        'k0fragment' => $cap_fragments[140]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    59 => array(
        'k0fragment' => $cap_fragments[142]['k0fragment'],
        'k0practice' => $practices[14]['k0practice']
    ),
    60 => array(
        'k0fragment' => $cap_fragments[92]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    61 => array(
        'k0fragment' => $cap_fragments[93]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    62 => array(
        'k0fragment' => $cap_fragments[94]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    63 => array(
        'k0fragment' => $cap_fragments[95]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    64 => array(
        'k0fragment' => $cap_fragments[97]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    65 => array( // Include "Issue PHA or HIRA" with fragments associated with "View or Revise PHA or HIRA"
        'k0fragment' => $cap_fragments[93]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    66 => array( // Include "Issue PHA or HIRA" with fragments associated with "View or Revise PHA or HIRA"
        'k0fragment' => $cap_fragments[94]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    67 => array( // Include "Issue PHA or HIRA" with fragments associated with "View or Revise PHA or HIRA"
        'k0fragment' => $cap_fragments[95]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    68 => array( // Include "Issue PHA or HIRA" with fragments associated with "View or Revise PHA or HIRA"
        'k0fragment' => $cap_fragments[97]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    69 => array(
        'k0fragment' => $cap_fragments[98]['k0fragment'],
        'k0practice' => $practices[16]['k0practice']
    ),
    70 => array(
        'k0fragment' => $cap_fragments[96]['k0fragment'],
        'k0practice' => $practices[19]['k0practice']
    ),
    71 => array(
        'k0fragment' => $cap_fragments[134]['k0fragment'], // Mechanical Integrity -- new construction quality assurance
        'k0practice' => $practices[14]['k0practice']
    ),
    72 => array(
        'k0fragment' => $psm_fragments[115]['k0fragment'], // OSHA PSM - compliance audit - start
        'k0practice' => $practices[21]['k0practice']
    ),
    73 => array(
        'k0fragment' => $psm_fragments[116]['k0fragment'],
        'k0practice' => $practices[21]['k0practice']
    ),
    74 => array(
        'k0fragment' => $psm_fragments[117]['k0fragment'],
        'k0practice' => $practices[21]['k0practice']
    ),
    75 => array(
        'k0fragment' => $psm_fragments[118]['k0fragment'],
        'k0practice' => $practices[24]['k0practice']
    ),
    76 => array(
        'k0fragment' => $psm_fragments[119]['k0fragment'], // OSHA PSM - compliance audit - end
        'k0practice' => $practices[21]['k0practice']
    ),
    77 => array(
        'k0fragment' => $cap_fragments[143]['k0fragment'], // EPA CAP - compliance audit - start
        'k0practice' => $practices[21]['k0practice']
    ),
    78 => array(
        'k0fragment' => $cap_fragments[144]['k0fragment'],
        'k0practice' => $practices[21]['k0practice']
    ),
    79 => array(
        'k0fragment' => $cap_fragments[145]['k0fragment'],
        'k0practice' => $practices[21]['k0practice']
    ),
    80 => array(
        'k0fragment' => $cap_fragments[146]['k0fragment'],
        'k0practice' => $practices[24]['k0practice']
    ),
    81 => array(
        'k0fragment' => $cap_fragments[147]['k0fragment'], // EPA CAP - compliance audit - end
        'k0practice' => $practices[21]['k0practice']
    ),
    82 => array(
        'k0fragment' => $psm_fragments[105]['k0fragment'], // OSHA PSM - incident investigation - start
        'k0practice' => $practices[26]['k0practice']
    ),
    83 => array(
        'k0fragment' => $psm_fragments[106]['k0fragment'],
        'k0practice' => $practices[26]['k0practice']
    ),
    84 => array(
        'k0fragment' => $psm_fragments[107]['k0fragment'],
        'k0practice' => $practices[27]['k0practice']
    ),
    85 => array(
        'k0fragment' => $psm_fragments[108]['k0fragment'],
        'k0practice' => $practices[27]['k0practice']
    ),
    86 => array(
        'k0fragment' => $psm_fragments[108]['k0fragment'],
        'k0practice' => $practices[28]['k0practice']
    ),
    87 => array(
        'k0fragment' => $psm_fragments[109]['k0fragment'],
        'k0practice' => $practices[29]['k0practice']
    ),
    88 => array(
        'k0fragment' => $psm_fragments[110]['k0fragment'],
        'k0practice' => $practices[30]['k0practice']
    ),
    89 => array(
        'k0fragment' => $psm_fragments[111]['k0fragment'], // OSHA PSM - incident investigation - end
        'k0practice' => $practices[31]['k0practice']
    ),
    90 => array(
        'k0fragment' => $cap_fragments[148]['k0fragment'], // EPA CAP - incident investigation - start
        'k0practice' => $practices[26]['k0practice']
    ),
    91 => array(
        'k0fragment' => $cap_fragments[149]['k0fragment'],
        'k0practice' => $practices[26]['k0practice']
    ),
    92 => array(
        'k0fragment' => $cap_fragments[150]['k0fragment'],
        'k0practice' => $practices[27]['k0practice']
    ),
    93 => array(
        'k0fragment' => $cap_fragments[151]['k0fragment'],
        'k0practice' => $practices[27]['k0practice']
    ),
    94 => array(
        'k0fragment' => $cap_fragments[151]['k0fragment'],
        'k0practice' => $practices[28]['k0practice']
    ),
    95 => array(
        'k0fragment' => $cap_fragments[152]['k0fragment'],
        'k0practice' => $practices[29]['k0practice']
    ),
    96 => array(
        'k0fragment' => $cap_fragments[153]['k0fragment'],
        'k0practice' => $practices[30]['k0practice']
    ),
    97 => array(
        'k0fragment' => $cap_fragments[154]['k0fragment'], // EPA CAP - incident investigation - end
        'k0practice' => $practices[31]['k0practice']
    ),
    98 => array(
        'k0fragment' => $psm_fragments[66]['k0fragment'], // OSHA PSM - procedures and SWP training - start
        'k0practice' => $practices[32]['k0practice']
    ),
    99 => array(
        'k0fragment' => $psm_fragments[67]['k0fragment'],
        'k0practice' => $practices[32]['k0practice']
    ),
    100 => array(
        'k0fragment' => $psm_fragments[68]['k0fragment'],
        'k0practice' => $practices[33]['k0practice']
    ),
    101 => array(
        'k0fragment' => $psm_fragments[69]['k0fragment'],
        'k0practice' => $practices[33]['k0practice']
    ),
    102 => array(
        'k0fragment' => $psm_fragments[70]['k0fragment'], // OSHA PSM - procedures and SWP training - end
        'k0practice' => $practices[32]['k0practice']
    ),
    103 => array(
        'k0fragment' => $cap_fragments[121]['k0fragment'], // EPA CAP - procedures and SWP training - start
        'k0practice' => $practices[32]['k0practice']
    ),
    104 => array(
        'k0fragment' => $cap_fragments[122]['k0fragment'],
        'k0practice' => $practices[32]['k0practice']
    ),
    105 => array(
        'k0fragment' => $cap_fragments[123]['k0fragment'],
        'k0practice' => $practices[33]['k0practice']
    ),
    106 => array(
        'k0fragment' => $cap_fragments[124]['k0fragment'],
        'k0practice' => $practices[33]['k0practice']
    ),
    107 => array(
        'k0fragment' => $cap_fragments[125]['k0fragment'], // EPA CAP - procedures and SWP training - end
        'k0practice' => $practices[32]['k0practice']
    ),
    108 => array(
        'k0fragment' => $psm_fragments[71]['k0fragment'], // OSHA PSM - contractors - start
        'k0practice' => $practices[35]['k0practice']
    ),
    109 => array(
        'k0fragment' => $psm_fragments[72]['k0fragment'],
        'k0practice' => $practices[35]['k0practice']
    ),
    110 => array(
        'k0fragment' => $psm_fragments[72]['k0fragment'],
        'k0practice' => $practices[36]['k0practice']
    ),
    111 => array(
        'k0fragment' => $psm_fragments[72]['k0fragment'],
        'k0practice' => $practices[37]['k0practice']
    ),
    112 => array(
        'k0fragment' => $psm_fragments[72]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    113 => array(
        'k0fragment' => $psm_fragments[72]['k0fragment'],
        'k0practice' => $practices[40]['k0practice']
    ),
    114 => array(
        'k0fragment' => $psm_fragments[72]['k0fragment'],
        'k0practice' => $practices[41]['k0practice']
    ),
    115 => array(
        'k0fragment' => $psm_fragments[73]['k0fragment'],
        'k0practice' => $practices[36]['k0practice']
    ),
    116 => array(
        'k0fragment' => $psm_fragments[74]['k0fragment'],
        'k0practice' => $practices[37]['k0practice']
    ),
    117 => array(
        'k0fragment' => $psm_fragments[75]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    118 => array(
        'k0fragment' => $psm_fragments[76]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    119 => array(
        'k0fragment' => $psm_fragments[77]['k0fragment'],
        'k0practice' => $practices[40]['k0practice']
    ),
    120 => array(
        'k0fragment' => $psm_fragments[78]['k0fragment'],
        'k0practice' => $practices[41]['k0practice']
    ),
    121 => array(
        'k0fragment' => $psm_fragments[79]['k0fragment'],
        'k0practice' => $practices[36]['k0practice']
    ),
    122 => array(
        'k0fragment' => $psm_fragments[79]['k0fragment'],
        'k0practice' => $practices[38]['k0practice']
    ),
    123 => array(
        'k0fragment' => $psm_fragments[79]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    124 => array(
        'k0fragment' => $psm_fragments[79]['k0fragment'],
        'k0practice' => $practices[40]['k0practice']
    ),
    125 => array(
        'k0fragment' => $psm_fragments[79]['k0fragment'],
        'k0practice' => $practices[41]['k0practice']
    ),
    126 => array(
        'k0fragment' => $psm_fragments[80]['k0fragment'],
        'k0practice' => $practices[39]['k0practice'] // Contractor employees shall not access Facility without owner approving training documentation submitted by contractor.
    ),
    127 => array(
        'k0fragment' => $psm_fragments[81]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']  // Contractor employees shall not access Facility without owner approving emergency-plans training documentation.
    ),
    128 => array(
        'k0fragment' => $psm_fragments[82]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']  // Contractor employees shall not access Facility without owner approving adequacy of training documentation.
    ),
    129 => array(
        'k0fragment' => $psm_fragments[83]['k0fragment'],
        'k0practice' => $practices[40]['k0practice']
    ),
    130 => array(
        'k0fragment' => $psm_fragments[84]['k0fragment'], // OSHA PSM - contractors - end
        'k0practice' => $practices[38]['k0practice']
    ),
    131 => array(
        'k0fragment' => $cap_fragments[159]['k0fragment'], // EPA CAP - procedures and SWP training - start
        'k0practice' => $practices[35]['k0practice']
    ),
    132 => array(
        'k0fragment' => $cap_fragments[160]['k0fragment'],
        'k0practice' => $practices[35]['k0practice']
    ),
    133 => array(
        'k0fragment' => $cap_fragments[160]['k0fragment'],
        'k0practice' => $practices[36]['k0practice']
    ),
    134 => array(
        'k0fragment' => $cap_fragments[160]['k0fragment'],
        'k0practice' => $practices[37]['k0practice']
    ),
    135 => array(
        'k0fragment' => $cap_fragments[160]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    136 => array(
        'k0fragment' => $cap_fragments[160]['k0fragment'],
        'k0practice' => $practices[40]['k0practice']
    ),
    137 => array(
        'k0fragment' => $cap_fragments[161]['k0fragment'],
        'k0practice' => $practices[36]['k0practice']
    ),
    138 => array(
        'k0fragment' => $cap_fragments[162]['k0fragment'],
        'k0practice' => $practices[37]['k0practice']
    ),
    139 => array(
        'k0fragment' => $cap_fragments[163]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    140 => array(
        'k0fragment' => $cap_fragments[164]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    141 => array(
        'k0fragment' => $cap_fragments[165]['k0fragment'],
        'k0practice' => $practices[40]['k0practice']
    ),
    142 => array(
        'k0fragment' => $cap_fragments[166]['k0fragment'],
        'k0practice' => $practices[36]['k0practice']
    ),
    143 => array(
        'k0fragment' => $cap_fragments[166]['k0fragment'],
        'k0practice' => $practices[38]['k0practice']
    ),
    144 => array(
        'k0fragment' => $cap_fragments[166]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    145 => array(
        'k0fragment' => $cap_fragments[166]['k0fragment'],
        'k0practice' => $practices[40]['k0practice']
    ),
    146 => array(
        'k0fragment' => $cap_fragments[167]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    147 => array(
        'k0fragment' => $cap_fragments[168]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    148 => array(
        'k0fragment' => $cap_fragments[169]['k0fragment'],
        'k0practice' => $practices[39]['k0practice']
    ),
    149 => array(
        'k0fragment' => $cap_fragments[170]['k0fragment'],
        'k0practice' => $practices[40]['k0practice']
    ),
    150 => array(
        'k0fragment' => $cap_fragments[171]['k0fragment'], // EPA CAP - procedures and SWP training - end
        'k0practice' => $practices[38]['k0practice']
    ),
    151 => array(
        'k0fragment' => $psm_fragments[18]['k0fragment'], // OSHA PSM - PSI - Safety Data Sheet
        'k0practice' => $practices[42]['k0practice']
    ),
    152 => array(
        'k0fragment' => $cap_fragments[73]['k0fragment'], // EPA CAP - PSI - Safety Data Sheet
        'k0practice' => $practices[42]['k0practice']
    ),
    153 => array(
        'k0fragment' => $psm_fragments[20]['k0fragment'], // OSHA PSM - PSI - Flow Diagram
        'k0practice' => $practices[43]['k0practice']
    ),
    154 => array(
        'k0fragment' => $cap_fragments[75]['k0fragment'], // EPA CAP - PSI - Flow Diagram
        'k0practice' => $practices[43]['k0practice']
    ),
    155 => array(
        'k0fragment' => $psm_fragments[21]['k0fragment'], // OSHA PSM - PSI - Process Chemistry
        'k0practice' => $practices[44]['k0practice']
    ),
    156 => array(
        'k0fragment' => $cap_fragments[76]['k0fragment'], // EPA CAP - PSI - Process Chemistry
        'k0practice' => $practices[44]['k0practice']
    ),
    157 => array(
        'k0fragment' => $psm_fragments[22]['k0fragment'], // OSHA PSM - PSI - Process-Materials Inventories
        'k0practice' => $practices[45]['k0practice']
    ),
    158 => array(
        'k0fragment' => $cap_fragments[77]['k0fragment'], // EPA CAP - PSI - Process-Materials Inventories
        'k0practice' => $practices[45]['k0practice']
    ),
    159 => array(
        'k0fragment' => $psm_fragments[23]['k0fragment'], // OSHA PSM - PSI - Operating Limits
        'k0practice' => $practices[46]['k0practice']
    ),
    160 => array(
        'k0fragment' => $cap_fragments[78]['k0fragment'], // EPA CAP - PSI - Operating Limits
        'k0practice' => $practices[46]['k0practice']
    ),
    161 => array(
        'k0fragment' => $psm_fragments[24]['k0fragment'], // OSHA PSM - PSI - Consequences of Deviation (same practice as Operating Limits)
        'k0practice' => $practices[46]['k0practice']
    ),
    162 => array(
        'k0fragment' => $cap_fragments[79]['k0fragment'], // EPA CAP - PSI - Consequences of Deviation
        'k0practice' => $practices[46]['k0practice']
    ),
    163 => array(
        'k0fragment' => $psm_fragments[27]['k0fragment'], // OSHA PSM - PSI - Materials of Construction
        'k0practice' => $practices[47]['k0practice']
    ),
    164 => array(
        'k0fragment' => $cap_fragments[82]['k0fragment'], // EPA CAP - PSI - Materials of Construction
        'k0practice' => $practices[47]['k0practice']
    ),
    165 => array(
        'k0fragment' => $psm_fragments[28]['k0fragment'], // OSHA PSM - PSI - P&ID
        'k0practice' => $practices[48]['k0practice']
    ),
    166 => array(
        'k0fragment' => $cap_fragments[83]['k0fragment'], // EPA CAP - PSI - P&ID
        'k0practice' => $practices[48]['k0practice']
    ),
    167 => array(
        'k0fragment' => $psm_fragments[29]['k0fragment'], // OSHA PSM - PSI - Electrical Classification
        'k0practice' => $practices[49]['k0practice']
    ),
    168 => array(
        'k0fragment' => $cap_fragments[84]['k0fragment'], // EPA CAP - PSI - Electrical Classification
        'k0practice' => $practices[49]['k0practice']
    ),
    169 => array(
        'k0fragment' => $psm_fragments[30]['k0fragment'], // OSHA PSM - PSI - Relief Systems
        'k0practice' => $practices[50]['k0practice']
    ),
    170 => array(
        'k0fragment' => $cap_fragments[85]['k0fragment'], // EPA CAP - PSI - Relief Systems
        'k0practice' => $practices[50]['k0practice']
    ),
    171 => array(
        'k0fragment' => $psm_fragments[31]['k0fragment'], // OSHA PSM - PSI - Ventilation Systems
        'k0practice' => $practices[50]['k0practice']
    ),
    172 => array(
        'k0fragment' => $cap_fragments[86]['k0fragment'], // EPA CAP - PSI - Ventilation Systems
        'k0practice' => $practices[50]['k0practice']
    ),
    173 => array(
        'k0fragment' => $psm_fragments[32]['k0fragment'], // OSHA PSM - PSI - Design codes and standards employed
        'k0practice' => $practices[51]['k0practice']
    ),
    174 => array(
        'k0fragment' => $cap_fragments[87]['k0fragment'], // EPA CAP - PSI - Design codes and standards employed
        'k0practice' => $practices[51]['k0practice']
    ),
    175 => array(
        'k0fragment' => $psm_fragments[33]['k0fragment'], // OSHA PSM - PSI - Material and energy balances
        'k0practice' => $practices[52]['k0practice']
    ),
    176 => array(
        'k0fragment' => $cap_fragments[88]['k0fragment'], // EPA CAP - PSI - Material and energy balances
        'k0practice' => $practices[52]['k0practice']
    ),
    177 => array(
        'k0fragment' => $psm_fragments[34]['k0fragment'], // OSHA PSM - PSI - Safety systems (e.g. interlocks, detection or suppression systems)
        'k0practice' => $practices[50]['k0practice']
    ),
    178 => array(
        'k0fragment' => $cap_fragments[89]['k0fragment'], // EPA CAP - PSI - Safety systems (e.g. interlocks, detection or suppression systems)
        'k0practice' => $practices[50]['k0practice']
    ),
    179 => array(
        'k0fragment' => $psm_fragments[35]['k0fragment'], // OSHA PSM - PSI - Good Practices
        'k0practice' => $practices[53]['k0practice']
    ),
    180 => array(
        'k0fragment' => $cap_fragments[90]['k0fragment'], // EPA CAP - PSI - Good Practices
        'k0practice' => $practices[53]['k0practice']
    ),
    181 => array(
        'k0fragment' => $psm_fragments[36]['k0fragment'], // OSHA PSM - PSI - no longer in general use
        'k0practice' => $practices[54]['k0practice']
    ),
    182 => array(
        'k0fragment' => $cap_fragments[91]['k0fragment'], // EPA CAP - PSI - no longer in general use
        'k0practice' => $practices[54]['k0practice']
    ),
    183 => array(
        'k0fragment' => $psm_fragments[25]['k0fragment'], // OSHA PSM - PSI - original technical information no longer exists
        'k0practice' => $practices[54]['k0practice']
    ),
    184 => array(
        'k0fragment' => $cap_fragments[80]['k0fragment'], // EPA CAP - PSI - original technical information no longer exists
        'k0practice' => $practices[54]['k0practice']
    ),
    185 => array(
        'k0fragment' => $cap_fragments[190]['k0fragment'], // EPA CAP - 68.200, Recordkeeping ... five years unless otherwise provided
        'k0practice' => $practices[9]['k0practice'] // Consulting on PSM Development
    ),
    186 => array(
        'k0fragment' => $cap_fragments[190]['k0fragment'], // EPA CAP - 68.200, Recordkeeping ... five years unless otherwise provided
        'k0practice' => $practices[14]['k0practice'] // Change Management System
    ),
    187 => array(
        'k0fragment' => $cap_fragments[190]['k0fragment'], // EPA CAP - 68.200, Recordkeeping ... five years unless otherwise provided
        'k0practice' => $practices[31]['k0practice']  // Incident Investigation > Retention
    ),
    188 => array(
        'k0fragment' => $cap_fragments[62]['k0fragment'], // EPA CAP - 40 CFR 68.42, Five-year accident history
        'k0practice' => $practices[27]['k0practice']  // Incident Investigation Form and Records
    ),
    189 => array(
        'k0fragment' => $cap_fragments[72]['k0fragment'], // EPA CAP - 40 CFR 68.65(a) - Timing (before PHA or HIRA) and Purpose [PSI fragment handled only by PHAs]
        'k0practice' => $practices[16]['k0practice']  // PHA or HIRA -- View or Create First, Update, and Issue
    ),
    190 => array(
        'k0fragment' => $psm_fragments[17]['k0fragment'], // OHSA PSM - 29 CFR 1910.119(d) - Timing (before PHA or HIRA) and Purpose [PSI fragment handled only by PHAs]
        'k0practice' => $practices[16]['k0practice']  // PHA or HIRA -- View or Create First, Update, and Issue
    ),
    191 => array(
        'k0fragment' => $cap_fragments[182]['k0fragment'], // EPA CAP - 40 CFR 68.168, Reporting in RMP the five-year accident history
        'k0practice' => $practices[27]['k0practice']  // Incident Investigation Form and Records
    ),
    192 => array(
        'k0fragment' => $cap_fragments[188]['k0fragment'], // EPA CAP - 40 CFR 68.195, Correcting in RMP the five-year accident history
        'k0practice' => $practices[27]['k0practice']  // Incident Investigation Form and Records
    ),
    193 => array(
        'k0fragment' => $cap_fragments[191]['k0fragment'], // EPA CAP - 40 CFR 68.210, public meetings for five-year accident history incidents
        'k0practice' => $practices[27]['k0practice']  // Incident Investigation Form and Records
    )
);
// Keys less than 100000 are reserved for templates.
foreach ($fragment_practice as $K => $V) {
    $V['k0fragment_practice'] = $K;
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0fragment_practice', $V);
}

