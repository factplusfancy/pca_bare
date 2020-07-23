<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// TO DO finish populating template scenarios.
$scenario = array(
    0 => array(
        'k0subprocess' => $subprocess[0]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Valve, Stop, leaks out into building or outside'),
        'c5type' => $Zfpf->encrypt_1c('Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    1 => array(
        'k0subprocess' => $subprocess[0]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Valve, Stop, leak-by in either direction'),
        'c5type' => $Zfpf->encrypt_1c('Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    2 => array(
        'k0subprocess' => $subprocess[0]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Valve, Stop, stuck in normal position (typically open, sometimes closed)'),
        'c5type' => $Zfpf->encrypt_1c('Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    3 => array(
        'k0subprocess' => $subprocess[0]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Valve, Control, leaks out into building or outside'),
        'c5type' => $Zfpf->encrypt_1c('Human Factors and Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    4 => array(
        'k0subprocess' => $subprocess[0]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Valve, Check, leak-by in reverse direction'),
        'c5type' => $Zfpf->encrypt_1c('Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    5 => array(
        'k0subprocess' => $subprocess[0]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Valve, Solenoid, fails mostly to fully open'),
        'c5type' => $Zfpf->encrypt_1c('Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    6 => array(
        'k0subprocess' => $subprocess[2]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Discharge location of a pressure-relief valve could contaminate breathing air'),
        'c5type' => $Zfpf->encrypt_1c('Facility Siting and Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    7 => array(
        'k0subprocess' => $subprocess[2]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Discharge location of emergency-ventilation could contaminate breathing air'),
        'c5type' => $Zfpf->encrypt_1c('Facility Siting and Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    8 => array(
        'k0subprocess' => $subprocess[2]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Ammonia release not vented from room where it occurs'),
        'c5type' => $Zfpf->encrypt_1c('Facility Siting and Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    9 => array(
        'k0subprocess' => $subprocess[0]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Threaded bonnet loosened when opening stop valve or control valve manual-opening stem'),
        'c5type' => $Zfpf->encrypt_1c('Human Factors and Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    10 => array(
        'k0subprocess' => $subprocess[0]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Valve stem overtightened (open or closed) or excessive force used on stuck valve stem.'),
        'c5type' => $Zfpf->encrypt_1c('Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    ),
    11 => array(
        'k0subprocess' => $subprocess[0]['k0subprocess'],
        'c5name' => $Zfpf->encrypt_1c('Instrumentation connection, leaks out into building or outside'),
        'c5type' => $Zfpf->encrypt_1c('Human Factors and Prior Incident'),
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing
    )
);
foreach ($scenario as $K => $V) {
    $V['k0scenario'] = $K + 1;
    $scenario[$K]['k0scenario'] = $V['k0scenario']; // Used in scenario_cause.php, scenario_consequence.php, and scenario_safeguard.php
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0scenario', $V);
}

