<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

$i = 1;
if (isset($V)) // Likely still set from prior foreach loop.
    unset($V);
while ($i < 24) {
    $V['k0audit_obstopic'] = $i;
    $V['k0audit'] = 1; // 1 is the k0audit of the ammonia-refrigeration (nh3r) audit template
    $V['k0obstopic'] = $i++; // k0obstopic 1 to 23 become part of nh3r audit scope. See $NH3ROt array in ...template/nh3r_obstopic.php
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0audit_obstopic', $V);
}

