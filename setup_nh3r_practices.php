<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// The PHP file inserts anhydrous-ammonia refrigeration (nh3r) template practices into database.
// pcm/setup.php must be run before it.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;

$FixedLeftContents = '
<ul>
    <li class="toca">PSM-CAP App</li>
    <li class="toca">Setup</li>
</ul>';

// Initial form to get DBMS admin credentials.
if (!$_POST) {
    echo $Zfpf->xhtml_contents_header_1c('PSM-CAP', FALSE, $FixedLeftContents).'<h2>
    Setup anhydrous-ammonia refrigeration standard practices</h2>
    <form method="post"><p>
    Supply credentials that allow you to log on, with SELECT and INSERT privileges, to the DBMS on the host computer that serves the PSM-CAP App database.</p><p>
    DBMS Existing Administrator Username: <input type="text" name="dbms_admin_username" class="screenwidth" /></p><p>
    DBMS Existing Administrator Password: <input type="password" name="dbms_admin_password" class="screenwidth" /></p><p>
        <input type="submit" name="setup_more" value="Create Practices" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
}

// setup
if (isset($_POST['setup_more'])) {
    $DBMSresource = $Zfpf->connect_instance_1s($_POST['dbms_admin_username'], $_POST['dbms_admin_password']);
    echo $Zfpf->xhtml_contents_header_1c('PSM-CAP', FALSE, $FixedLeftContents).'<h2>
    Setup anhydrous-ammonia refrigeration standard practices</h2><p>
    Inserting into the database instance...';
    $Nothing = '[Nothing has been recorded in this field.]'; // Do NOT change variables here without changing in required files
    $EncryptedNothing = $Zfpf->encrypt_1c($Nothing); // Do NOT change variables here without changing in required files
    $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
    $EncryptedCurrentTime = $Zfpf->encrypt_1c(time());
    $EncryptedFullPrivileges = $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF);
    $EncryptedNone = $Zfpf->encrypt_1c(NO_PRIVILEGES_ZFPF);
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
    // Function used by required files below.
    // Gets the highest value in column $ColumnName of table $TableName
    // For what is meant by "highest", see https://www.php.net/manual/en/function.max.php 
    function get_highest_in_table($Zfpf, $DBMSresource, $ColumnName, $TableName) {
        list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, $TableName, 'No Condition -- All Rows Included', array($ColumnName));
        if ($RR) {
             foreach ($SR as $V)
                $k0array[] = $V[$ColumnName];
            return max($k0array);
        }
        else
            return 0; // Case when table has no rows.
    }
    $EncryptedLowPrivileges = $Zfpf->encrypt_1c(LOW_PRIVILEGES_ZFPF);
    $Encrypted_document_i1m_php = $Zfpf->encrypt_1c('document_i1m.php');
    // TO DO FOR PRODUCTION VERSION  Comment out any already-installed templates before running this file.
    // TO DO FOR PRODUCTION VERSION  or uncomment all if re-installing after dropping database. 
    // TO DO FOR PRODUCTION VERSION  Run in batches if server times out.
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_hspswp_ep_usa.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_itm.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_general_duty.php'; // Must run after nh3r_hspswp_ep_usa.php and nh3r_itm.php, to include their practices.
    $Zfpf->close_connection_1s($DBMSresource);
    unset($_SESSION['t0user']); // Avoid interference with next logon.
    echo ' done.</p><p>
    <b>Important! Delete setup files</b> or move them to a secure folder, not accessible to anyone but the database administrators and not in the PHP include path.</p>
    '.$Zfpf->xhtml_footer_1c();
}

exit; // To stop front_controller.php from continuing to default case. See comment in front_controller.php
// Don't save and exit.

