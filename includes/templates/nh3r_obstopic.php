<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

$NH3ROt = array(
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('People with Management Responsibilities') // nh3r PSM audit -- START
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('People with Emergency Responsibilities')
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('People with Hazardous-Substance Procedures and Safe-Work Practices Responsibilities')
    ),
    4 => array(
        'c5name' => $Zfpf->encrypt_1c('People with Inspection, Testing, and Maintenance Responsibilities')
    ),
    5 => array(
        'c5name' => $Zfpf->encrypt_1c('People Working Near Hazardous Substances')
    ),
    6 => array(
        'c5name' => $Zfpf->encrypt_1c('People Driving Forklifts and Similar Near Hazardous Substances')
    ),
    7 => array(
        'c5name' => $Zfpf->encrypt_1c('Compliance-Practice Descriptions and Miscellaneous Records')
    ),
    8 => array(
        'c5name' => $Zfpf->encrypt_1c('Employee-Participation Plan and Records')
    ),
    9 => array(
        'c5name' => $Zfpf->encrypt_1c('Process Safety Information')
    ),
    10 => array(
        'c5name' => $Zfpf->encrypt_1c('Process Hazard Analysis Documents')
    ),
    11 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazardous-Substance Procedures and Safe-Work Practices Descriptions and Records') // includes "Leak Mitigation and Emergency Shutdown" and "Small-Leak..."
    ),
    12 => array(
        'c5name' => $Zfpf->encrypt_1c('Training on Hazardous-Substance Procedures and Safe-Work Practices Program and Records')
    ),
    13 => array(
        'c5name' => $Zfpf->encrypt_1c('Contractor Program and Records')
    ),
    14 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection, Testing, and Maintenance Program and Records')
    ),
    15 => array(
        'c5name' => $Zfpf->encrypt_1c('Training Records on Inspection, Testing, and Maintenance')
    ),
    16 => array(
        'c5name' => $Zfpf->encrypt_1c('Change-Management Compliance Practices and Records')
    ),
    17 => array(
        'c5name' => $Zfpf->encrypt_1c('Incident-Investigation Compliance Practices and Records')
    ),
    18 => array(
        'c5name' => $Zfpf->encrypt_1c('Audit and Hazard Review Documents')
    ),
    19 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Action Plan and Coordination with Community Compliance Practices and Records')
    ),
    20 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Response') // if any, including plans, training, equipment quality and maintenance, and scheduling and recordkeeping.
    ),
    21 => array(
        'c5name' => $Zfpf->encrypt_1c('Tour of Refrigerating-Machinery Rooms')
    ),
    22 => array(
        'c5name' => $Zfpf->encrypt_1c('Tour of Industrial Occupancies')
    ),
    23 => array(
        'c5name' => $Zfpf->encrypt_1c('Tour of Roofs and Outside') // nh3r PSM audit -- END   nh3r_psm-audit_obstopic.php relies on these k0obstpoic values
    ),
    24 => array(
        'c5name' => $Zfpf->encrypt_1c('Compressors') // TO DO 2020-07-22 placeholder for ITM obsmethods
    ),
    25 => array(
        'c5name' => $Zfpf->encrypt_1c('Condensers') // TO DO 2020-07-22 placeholder for ITM obsmethods
    )/*,
     => array(
        'c5name' => $Zfpf->encrypt_1c('')
    ),*/
);
foreach ($NH3ROt as $K => $V) {
    $V['k0obstopic'] = $K;
    $NH3ROt[$K]['k0obstopic'] = $V['k0obstopic']; // Used for subsequently required files
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0obstopic', $V);
}

