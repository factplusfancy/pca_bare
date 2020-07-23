<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

$rules = array(
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('Cheesehead division method'),
        'c5citation' => $EncryptedNothing
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('OSHA Process Safety Management (PSM)'),
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119')
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('EPA Chemical Accident Prevention (CAP), including only the Program 3 (PSM) Prevention Program'),
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68')
    )
);
foreach ($rules as $K => $V) {
    $V['k0rule'] = $K;
    $rules[$K]['k0rule'] = $V['k0rule'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0rule', $V);
}

