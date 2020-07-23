<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// TO DO finish populating this template.
$consequence = array(
    0 => array(
        'c5name' => $Zfpf->encrypt_1c('Hinders operations.'), // required
        'c6description' => $EncryptedNothing // optional
    ),
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('Hinders (or increases need for) maintenance.'),
        'c6description' => $EncryptedNothing
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('Hinders emergency actions (communications, move-to-safety, headcount, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('Hinders emergency response (leak isolation, firefighting, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    4 => array(
        'c5name' => $Zfpf->encrypt_1c('Leak into a room with sensors, alarms, and emergency ventilation (machinery room, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    5 => array(
        'c5name' => $Zfpf->encrypt_1c('Leak into a room with sensors, alarms, and normal ventilation.'),
        'c6description' => $EncryptedNothing
    ),
    6 => array(
        'c5name' => $Zfpf->encrypt_1c('Leak near ventilation fresh-air intakes or directly into a room with normal ventilation (but no sensors).'),
        'c6description' => $EncryptedNothing
    ),
    7 => array(
        'c5name' => $Zfpf->encrypt_1c('Leak into a room with no ventilation (but with sensors and alarms).'),
        'c6description' => $EncryptedNothing
    ),
    8 => array(
        'c5name' => $Zfpf->encrypt_1c('Leak into a room with neither ventilation nor sensors.'),
        'c6description' => $EncryptedNothing
    ),
    9 => array(
        'c5name' => $Zfpf->encrypt_1c('Leak on roof that will probably stay onsite.'),
        'c6description' => $EncryptedNothing
    ),
    10 => array(
        'c5name' => $Zfpf->encrypt_1c('Leak outside near ground level (parking lots, walkways, etc.) that will probably stay onsite.'),
        'c6description' => $EncryptedNothing
    ),
    11 => array(
        'c5name' => $Zfpf->encrypt_1c('Leak that will probably go offsite.'),
        'c6description' => $EncryptedNothing
    ),
    12 => array(
        'c5name' => $Zfpf->encrypt_1c('Fire in room with sprinklers.'),
        'c6description' => $EncryptedNothing
    ),
    13 => array(
        'c5name' => $Zfpf->encrypt_1c('Fire in room without sprinklers.'),
        'c6description' => $EncryptedNothing
    ),
    14 => array(
        'c5name' => $Zfpf->encrypt_1c('Explosion.'), // Evaluate subsequent consequences of fires and explosions with separate scenarios.
        'c6description' => $EncryptedNothing
    ),
    15 => array(
        'c5name' => $Zfpf->encrypt_1c('Human errors become more likely.'),
        'c6description' => $EncryptedNothing
    ),
    16 => array(
        'c5name' => $Zfpf->encrypt_1c('Pressure higher than design pressure.'),
        'c6description' => $EncryptedNothing
    ),
    17 => array(
        'c5name' => $Zfpf->encrypt_1c('Temperature higher or lower than design temperature.'),
        'c6description' => $EncryptedNothing
    ),
    18 => array(
        'c5name' => $Zfpf->encrypt_1c('Level higher than safe operating range.'),
        'c6description' => $EncryptedNothing
    ),
    19 => array(
        'c5name' => $Zfpf->encrypt_1c('Level lower than safe operating range.'),
        'c6description' => $EncryptedNothing
    ),
    20 => array(
        'c5name' => $Zfpf->encrypt_1c('Damage due to rubbing or flow (abrading, erosion, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    21 => array(
        'c5name' => $Zfpf->encrypt_1c('Damage due to vibrations or shocks (cracking, fatigue, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    22 => array(
        'c5name' => $Zfpf->encrypt_1c('Damage due loads (weight, torque, seismic etc.) exceeding design.'),
        'c6description' => $EncryptedNothing
    ),
    23 => array(
        'c5name' => $Zfpf->encrypt_1c('Corrosion of primary-containment envelope (piping, vessels, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    24 => array(
        'c5name' => $Zfpf->encrypt_1c('Lubricant degradation (in compressors, pumps, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    25 => array(
        'c5name' => $Zfpf->encrypt_1c('Hinders personal safety (cuts, slip, trips, and falls, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    26 => array(
        'c5name' => $Zfpf->encrypt_1c('Hinders security.'),
        'c6description' => $EncryptedNothing
    )
);
foreach ($consequence as $K => $V) {
    $V['k0consequence'] = $K + 1;
    $consequence[$K]['k0consequence'] = $V['k0consequence'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0consequence', $V);
}

