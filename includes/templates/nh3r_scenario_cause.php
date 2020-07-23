<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// Populate template junction tables for template "PHA 101". 101 is the k0pha key for all template PHA scenarios being inserted here.
$scenario_cause = array(
    0 => array(
        'k0scenario' => $scenario[0]['k0scenario'],
        'k0cause' => $cause[0]['k0cause']
    ),
    1 => array(
        'k0scenario' => $scenario[0]['k0scenario'],
        'k0cause' => $cause[2]['k0cause']
    ),
    2 => array(
        'k0scenario' => $scenario[0]['k0scenario'],
        'k0cause' => $cause[3]['k0cause']
    ),
    3 => array(
        'k0scenario' => $scenario[0]['k0scenario'],
        'k0cause' => $cause[7]['k0cause']
    ),
    4 => array(
        'k0scenario' => $scenario[0]['k0scenario'],
        'k0cause' => $cause[10]['k0cause']
    ),
    5 => array(
        'k0scenario' => $scenario[1]['k0scenario'],
        'k0cause' => $cause[1]['k0cause']
    ),
    6 => array(
        'k0scenario' => $scenario[1]['k0scenario'],
        'k0cause' => $cause[7]['k0cause']
    ),
    7 => array(
        'k0scenario' => $scenario[2]['k0scenario'],
        'k0cause' => $cause[4]['k0cause']
    ),
    8 => array(
        'k0scenario' => $scenario[2]['k0scenario'],
        'k0cause' => $cause[5]['k0cause']
    ),
    9 => array(
        'k0scenario' => $scenario[2]['k0scenario'],
        'k0cause' => $cause[6]['k0cause']
    ),
    10 => array(
        'k0scenario' => $scenario[3]['k0scenario'],
        'k0cause' => $cause[0]['k0cause']
    ),
    11 => array(
        'k0scenario' => $scenario[3]['k0scenario'],
        'k0cause' => $cause[2]['k0cause']
    ),
    12 => array(
        'k0scenario' => $scenario[3]['k0scenario'],
        'k0cause' => $cause[3]['k0cause']
    ),
    13 => array(
        'k0scenario' => $scenario[3]['k0scenario'],
        'k0cause' => $cause[7]['k0cause']
    ),
    14 => array(
        'k0scenario' => $scenario[3]['k0scenario'],
        'k0cause' => $cause[10]['k0cause']
    ),
    15 => array(
        'k0scenario' => $scenario[4]['k0scenario'],
        'k0cause' => $cause[1]['k0cause']
    ),
    16 => array(
        'k0scenario' => $scenario[4]['k0scenario'],
        'k0cause' => $cause[11]['k0cause']
    ),
    17 => array(
        'k0scenario' => $scenario[5]['k0scenario'],
        'k0cause' => $cause[11]['k0cause']
    ),
    18 => array(
        'k0scenario' => $scenario[5]['k0scenario'],
        'k0cause' => $cause[12]['k0cause']
    ),
    19 => array(
        'k0scenario' => $scenario[6]['k0scenario'],
        'k0cause' => $cause[6]['k0cause']
    ),
    20 => array(
        'k0scenario' => $scenario[7]['k0scenario'],
        'k0cause' => $cause[6]['k0cause']
    ),
    21 => array(
        'k0scenario' => $scenario[8]['k0scenario'],
        'k0cause' => $cause[6]['k0cause']
    ),
    22 => array(
        'k0scenario' => $scenario[9]['k0scenario'],
        'k0cause' => $cause[4]['k0cause']
    ),
    23 => array(
        'k0scenario' => $scenario[9]['k0scenario'],
        'k0cause' => $cause[6]['k0cause']
    ),
    24 => array(
        'k0scenario' => $scenario[10]['k0scenario'],
        'k0cause' => $cause[4]['k0cause']
    ),
    25 => array(
        'k0scenario' => $scenario[11]['k0scenario'],
        'k0cause' => $cause[2]['k0cause']
    ),
    26 => array(
        'k0scenario' => $scenario[11]['k0scenario'],
        'k0cause' => $cause[3]['k0cause']
    ),
    27 => array(
        'k0scenario' => $scenario[11]['k0scenario'],
        'k0cause' => $cause[5]['k0cause']
    ),
    28 => array(
        'k0scenario' => $scenario[11]['k0scenario'],
        'k0cause' => $cause[6]['k0cause']
    ),
    29 => array(
        'k0scenario' => $scenario[11]['k0scenario'],
        'k0cause' => $cause[7]['k0cause']
    ),
    30 => array(
        'k0scenario' => $scenario[11]['k0scenario'],
        'k0cause' => $cause[10]['k0cause']
    )
);
foreach ($scenario_cause as $K => $V) {
    $V['k0scenario_cause'] = $K + 1;
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0scenario_cause', $V);
}

