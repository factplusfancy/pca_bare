<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// TO DO finish populating this template.
$cause = array(
    0 => array(
        'c5name' => $Zfpf->encrypt_1c('Valve-stem seal failure (packing damage, not tight enough, etc.)'), // required
        'c6description' => $EncryptedNothing // optional
    ),
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('Valve-seat seal failure (seat-gasket degraded, debris on seat, not tight enough, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('Coupling or fitting, flanged, loose or degraded gasket.'),
        'c6description' => $EncryptedNothing
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('Coupling or fitting, threaded, loose or degraded threads.'),
        'c6description' => $EncryptedNothing
    ),
    4 => array(
        'c5name' => $Zfpf->encrypt_1c('Operator error.'),
        'c6description' => $EncryptedNothing
    ),
    5 => array(
        'c5name' => $Zfpf->encrypt_1c('Maintenance shortcoming or error.'),
        'c6description' => $EncryptedNothing
    ),
    6 => array(
        'c5name' => $Zfpf->encrypt_1c('Design, fabrication, construction, or installation shortcoming or error.'),
        'c6description' => $EncryptedNothing
    ),
    7 => array(
        'c5name' => $Zfpf->encrypt_1c('Temperature changes.'),
        'c6description' => $EncryptedNothing
    ),
    8 => array(
        'c5name' => $Zfpf->encrypt_1c('Labeling inadequate (never labeled, fades in sun, painted over, falls off, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    9 => array(
        'c5name' => $Zfpf->encrypt_1c('Level-float failure (stuck, loses buoyancy, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    10 => array(
        'c5name' => $Zfpf->encrypt_1c('External impact or force (pulling hard with long wrench, vehicle collision, wind- or explosion-driven projectile, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    11 => array(
        'c5name' => $Zfpf->encrypt_1c('Spring, mechanical, failure (corrosion, fatigue-life exceeded, etc.)'),
        'c6description' => $EncryptedNothing
    ),
    12 => array(
        'c5name' => $Zfpf->encrypt_1c('Mechanical jam (moving part stuck, etc.)'),
        'c6description' => $EncryptedNothing
    )
);
foreach ($cause as $K => $V) {
    $V['k0cause'] = $K + 1;
    $cause[$K]['k0cause'] = $V['k0cause'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0cause', $V);
}

