<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file may be called by setup files that require templates.

class SetupZfpf {
    // This function returns the highest value in column $ColumnName of table $TableName
    // For what is meant by "highest", see https://www.php.net/manual/en/function.max.php 
    // It may be used to get the highest primary key in a database table.
    function get_highest_in_table($Zfpf, $DBMSresource, $ColumnName, $TableName) {
        list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, $TableName, 'No Condition -- All Rows Included', array($ColumnName));
        if ($RR) {
             foreach ($SR as $V)
                $ColumnValues[] = $V[$ColumnName];
            return max($ColumnValues);
        }
        else
            return 0; // Case when table has no rows.
    }
}
// TO DO -- lower priority  Make variable below constants, in the SetupZfpf class above, and update template files.
$Nothing = '[Nothing has been recorded in this field.]'; // Do NOT change variables here without changing in required files
$EncryptedNothing = $Zfpf->encrypt_1c($Nothing); // Do NOT change variables here without changing in required files
$EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
$EncryptedCurrentTime = $Zfpf->encrypt_1c(time());
$EncryptedFullPrivileges = $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF);
$EncryptedNone = $Zfpf->encrypt_1c(NO_PRIVILEGES_ZFPF);
$EncryptedNotApplicable = $Zfpf->encrypt_1c('Not Applicable');
$EncryptedLowPrivileges = $Zfpf->encrypt_1c(LOW_PRIVILEGES_ZFPF);
$Encrypted_document_i1m_php = $Zfpf->encrypt_1c('document_i1m.php');

$_SESSION['t0user'] = array( // Put in session because templates call CoreZfpf::insert_sql_1s, which calls CoreZfpf::record_in_history_1c.
    'k0user' => time().mt_rand(1000000, 9999999),
    'k2username_hash' => $EncryptedNothing,
    's5password_hash' => $EncryptedNothing,
    'c5ts_password' => $Zfpf->encrypt_1c(0),
    'c5p_global_dbms' => $EncryptedFullPrivileges,
    'c5app_admin' => $Zfpf->encrypt_1c('Yes'),
    'c5ts_created' => $EncryptedCurrentTime,
    'c5ts_logon_revoked' => $EncryptedNothing,
    'c5ts_last_logon' => $Zfpf->encrypt_1c(0),
    'c5attempts' => $Zfpf->encrypt_1c(0),
    'c6active_sessions' => $Zfpf->encode_encrypt_1c(array()),
    'c5name_family' => $EncryptedNothing,
    'c5name_given1' => $Zfpf->encrypt_1c('Setup admin'),
    'c5name_given2' => $EncryptedNothing,
    'c5personal_phone_mobile' => $EncryptedNothing,
    'c5personal_phone_home' => $EncryptedNothing,
    'c5e_contact_name' => $EncryptedNothing,
    'c5e_contact_phone' => $EncryptedNothing,
    'c5challenge_question1' => $EncryptedNothing,
    's5cq_answer_hash1' => $EncryptedNothing,
    'c5challenge_question2' => $EncryptedNothing,
    's5cq_answer_hash2' => $EncryptedNothing,
    'c5challenge_question3' => $EncryptedNothing,
    's5cq_answer_hash3' => $EncryptedNothing,
    'c5who_is_editing' => $EncryptedNobody
);


