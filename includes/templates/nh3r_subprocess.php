<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

$subprocess = array(
    0 => array(
        'c5name' => $Zfpf->encrypt_1c('A1. Equipment and Piping in General')
    ),
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('A2. Human Factors in General')
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('A3. Siting, Location, Surroundings, and Emergency Plans')
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('A4. System Charging')
    ),
    4 => array(
        'c5name' => $Zfpf->encrypt_1c('B1. Compressors')
    ),
    5 => array(
        'c5name' => $Zfpf->encrypt_1c('B2. Condensers')
    ),
    6 => array(
        'c5name' => $Zfpf->encrypt_1c('B3. Purger')
    ),
    7 => array(
        'c5name' => $Zfpf->encrypt_1c('C1. Vessels at Condensing Pressure (Receivers, etc.)')
    ),
    8 => array(
        'c5name' => $Zfpf->encrypt_1c('C2. Vessels at Suction Pressure(s) and Any Pumps (Accumulators, Recirculators, etc.)')
    ),
    9 => array(
        'c5name' => $Zfpf->encrypt_1c('C3. Transfer Systems')
    ),
    10 => array(
        'c5name' => $Zfpf->encrypt_1c('D1. Air Units (including any defrost and dehumidification)')
    ),
    11 => array(
        'c5name' => $Zfpf->encrypt_1c('D2. Plate-and-Frame Heat Exchangers')
    ),
    12 => array(
        'c5name' => $Zfpf->encrypt_1c('D3. Shell-and-Tube Heat Exchangers')
    ),
    13 => array(
        'c5name' => $Zfpf->encrypt_1c('D4. Tanks and Silos with Cooling Jackets')
    ),
    14 => array(
        'c5name' => $Zfpf->encrypt_1c('E1. Freezer Underfloor Heaters')
    )
);
foreach ($subprocess as $K => $V) {
    $V['k0subprocess'] = $K + 1;
    $subprocess[$K]['k0subprocess'] = $V['k0subprocess']; // Used in scenario.php
    if ($K < 50) // Assumes $subprocess 0 to 49 are associated with the k0pha of the template PHA in $pha[0] in file ...includes/templates/pha.php
        $V['k0pha'] = $pha[0]['k0pha'];
    // To insert another template PHA put another case here to catch the keys for its subprocesses. Also add scenarios, junction tables, causes, etc. for that template.
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0subprocess', $V);
}

