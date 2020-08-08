<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

$divisions = array(
    1 => array( // AAA -- For the Cheesehead division method only, these letters are the prefixes used for numbering practices, see templates/practices.php and schema t0practice:c5number. For the OSHA PSM and EPA CAP rules, they are the prefixes for numbering fragments within divisions, see templates/fragment_division.php and schema t0fragment_division:c5number.
        'k0rule' => $rules[1]['k0rule'], // $rules[1] is the Cheesehead division method.
        'c5name' => $Zfpf->encrypt_1c('Management system'),
        'c5citation' => $EncryptedNothing
    ),
    2 => array( // BBB
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Employee participation'),
        'c5citation' => $EncryptedNothing
    ),
    3 => array( // CCC
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Process safety information (PSI) covering design, materials, fabrication, construction, and installation'),
        'c5citation' => $EncryptedNothing
    ),
    4 => array( // DDD
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Process hazard analysis (PHA) and any other hazard identification and risk analyses (HIRA)'),
        'c5citation' => $EncryptedNothing
    ),
    5 => array( // EEE
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Hazardous-substance procedures and safe-work practices'),
        'c5citation' => $EncryptedNothing
    ),
    6 => array( // FFF
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Training on hazardous-substance procedures and safe-work practices'),
        'c5citation' => $EncryptedNothing
    ),
    7 => array( // GGG
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Contractors (on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.')'),
        'c5citation' => $EncryptedNothing
    ),
    8 => array( // HHH
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Inspection, testing, and maintenance (ITM) for safe operation and mechanical integrity'),
        'c5citation' => $EncryptedNothing
    ),
    9 => array( // III
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Change management (MOC-PSR)'),
        'c5citation' => $EncryptedNothing
    ),
    10 => array( // JJJ
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Incident investigation'),
        'c5citation' => $EncryptedNothing
    ),
    11 => array( // KKK
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('PSM audits'),
        'c5citation' => $EncryptedNothing
    ),
    12 => array( // LLL
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Emergency planning'),
        'c5citation' => $EncryptedNothing
    ),
    13 => array( // MMM
        'k0rule' => $rules[1]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Offsite hazard assessment and reporting'),
        'c5citation' => $EncryptedNothing
    ),
    14 => array( // AAA
        'k0rule' => $rules[2]['k0rule'], // $rules[2] is the OSHA PSM division method.
        'c5name' => $Zfpf->encrypt_1c('Name, purpose, and application'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119 to 1910.119(a)')
    ),
    15 => array( // BBB
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Definitions'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)')
    ),
    16 => array( // CCC
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Employee participation'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(c)')
    ),
    17 => array( // DDD
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Process safety information'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)')
    ),
    18 => array( // EEE
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Process hazard analysis'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(e)')
    ),
    19 => array( // FFF
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Operating procedures'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)')
    ),
    20 => array( // GGG
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Training'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(g)')
    ),
    21 => array( // HHH
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Contractors'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)')
    ),
    22 => array( // III
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Pre-startup safety review'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(i)')
    ),
    23 => array( // JJJ
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Mechanical integrity'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)')
    ),
    24 => array( // KKK
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Hot work permit'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(k)')
    ),
    25 => array( // LLL
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Management of change'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(l)')
    ),
    26 => array( // MMM
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Incident investigation'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(m)')
    ),
    27 => array( // NNN
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Emergency planning and response'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(n)')
    ),
    28 => array( // OOO
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Compliance Audits'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(o)')
    ),
    29 => array( // PPP
        'k0rule' => $rules[2]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Trade secrets'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(p)')
    ),
    30 => array( // AAA
        'k0rule' => $rules[3]['k0rule'], // $rules[3] is the EPA CAP (Program 3 only) division method.
        'c5name' => $Zfpf->encrypt_1c('Name, scope, definitions, applicability, and management system'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68 to 68.15')
    ),
    31 => array( // BBB
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Hazard Assessment'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.20 to 68.42')
    ),
    32 => array( // CCC
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Process safety information'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65')
    ),
    33 => array( // DDD
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Process hazard analysis'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.67')
    ),
    34 => array( // EEE
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Operating procedures'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69')
    ),
    35 => array( // FFF
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Training'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.71')
    ),
    36 => array( // GGG
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Mechanical integrity'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73')
    ),
    37 => array( // HHH
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Management of change'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.75')
    ),
    38 => array( // III
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Pre-startup review'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.77')
    ),
    39 => array( // JJJ
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Compliance audits'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.79')
    ),
    40 => array( // KKK
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Incident investigation'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.81')
    ),
    41 => array( // LLL
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Employee participation'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.83')
    ),
    42 => array( // MMM
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Hot work permit'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.85')
    ),
    43 => array( // NNN
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Contractors'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87')
    ),
    44 => array( // OOO
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Emergency response'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.90 to 68.96')
    ),
    45 => array( // PPP
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Regulated substances for accidental release prevention'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.100 to 130')
    ),
    46 => array( // QQQ
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Risk management plan'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.150 to 68.195')
    ),
    47 => array( // RRR
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Recordkeeping and other'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.200 to 68.220')
    ),
    48 => array( // SSS
        'k0rule' => $rules[3]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Toxic endpoints table'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Appendix A')
    ),
    49 => array( // QQQ
        'k0rule' => $rules[2]['k0rule'], // $rules[2] is the OSHA PSM division method.
        'c5name' => $Zfpf->encrypt_1c('List of hazardous highly chemicals, toxics and reactives (mandatory)'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119, Appendix A')
    ),
    50 => array( // RRR
        'k0rule' => $rules[2]['k0rule'], // $rules[2] is the OSHA PSM division method.
        'c5name' => $Zfpf->encrypt_1c('Appendices B to D: non-mandatory guidance'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119')
    )
);
// Keys less than 100000 are reserved for templates.
foreach ($divisions as $K => $V) {
    $V['k0division'] = $K;
    $divisions[$K]['k0division'] = $V['k0division'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0division', $V);
}

