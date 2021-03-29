<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

$union = array(
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('None (use for owner/operator employees who are not in a union that represents facility employees)'),
        'c6description' => $Zfpf->encrypt_1c('If there is a union that represents owner/operator employees at a facility, the Employee Participation element of the PSM and CAP rules requires consultation with these employee representatives. Use the Administration page to create records for unions and associate them with owner/operator employees.')
    ),
    2 => array( 
        'c5name' => $Zfpf->encrypt_1c('Contractor individual (so tracking union status is optional)'),
        'c6description' => $Zfpf->encrypt_1c('This is a placeholder for users who are contractor employees, whose union status PSM facility owner/operators are not required to track, unless the contractor is the operator of the facility.')
    )
);
foreach ($union as $K => $V) {
    $V['k0union'] = $K; // Keys less than 100000 are reserved for templates.
    $union[$K]['k0union'] = $V['k0union'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0union', $V);
}

