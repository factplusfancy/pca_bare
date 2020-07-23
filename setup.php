<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file creates a database instance, grants privileges to the credentials used by the PSM-CAP App, 
// creates the database tables, and adds the first user (and any template information) to the database.
// SECURITY NOTE: Table t0user is currently setup for the DBMS to authenticate the PSM-CAP App 
// and the PSM-CAP App to authenticate each user.  So, the PSM-CAP App files saved on the application server 
// contain a constant defining at least one DBMS username and password, which can be changed 
// periodically.  Different roles are assigned to different groups of users, so more than one
// DBMS username and password are saved in file .../settings/CoreSettingsZfpf.php

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;
require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
$UserZfpf = new UserZfpf;

// Initial setup form.
if (!$_POST) {
    $FixedLeftContents = '
    <ul>
        <li class="toca">PSM-CAP App</li>
        <li class="toca">Create New Database and First User</li>
    </ul>';
    echo $Zfpf->xhtml_contents_header_1c('PSM-CAP Setup', FALSE, $FixedLeftContents).'<h1>
    PSM-CAP Application (App) Setup</h1><h2>
    Instructions for database management system (DBMS) administrator (you) on setting up the PSM-CAP App.</h2><p>
    (1) For security, once the app is setup, setup.php needs to be deleted. <b>Check the PSM-CAP App directory (aka folder) and delete setup.php, if not done automatically, after setup is complete.</b></p><p>
    (2) Open all files in the settings directory (for example settings/CoreSettingsZfpf.php) using a text editor, like Notepad, gedit, or EditPlus.  Read the instructions in these files, and then edit the definitions.</p>
    <form method="post"><p>
    Supply credentials that allow you to log on, with CREATE DATABASE, CREATE TABLE, and GRANT privileges, to the DBMS on the host computer where you want the PSM-CAP App database instance created. (The default MySQL username is "root".)<br />
    DBMS Existing Administrator Username: <input type="text" name="dbms_admin_username" class="screenwidth" /><br />
    DBMS Existing Administrator Password: <input type="password" name="dbms_admin_password" class="screenwidth" /></p><p>
    Use the form below to create the first PSM-CAP App administrator, who will have '.MAX_PRIVILEGES_ZFPF.' privileges on all tables in the PSM-CAP App database instance.</p><p>
    The password you input below will have to be reset on first login.</p>
    '.$UserZfpf->username_password_html_form().'<p>
    To create a new PSM-CAP App database instance, schema, and first user -- press the button below. This may take a few minutes.<br />
    <br />
        <input type="submit" name="setup" value="Create New Database and First User" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
}

// App setup
if (isset($_POST['setup'])) {
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/schema.php';
    // Check constant DATABASE_INSTANCE_ZFPF, supplied in file settings/CoreSettingsZfpf.php
    if (strlen(DATABASE_INSTANCE_ZFPF) > 27)
        exit('<p>The database instance name you defined in settings/CoreSettingsZfpf.php is more than 27 characters long. Please define a shorter name and then press the back button on your web browser to run setup again. Please contact your PSM-CAP App supplier for assistance if you want to change this length requirement.</p>');
    if (preg_replace('~[^a-zA-Z0-9_]~', '', DATABASE_INSTANCE_ZFPF) != DATABASE_INSTANCE_ZFPF)
        exit('<p>The database instance name you defined in settings/CoreSettingsZfpf.php contains characters that are not: a to z, A to Z, 0 to 9, or _<br />This PSM-CAP App currently permits only the above 63 characters in the database instance name. Please define a database instance name that conforms to this and then press the back button on your web browser to run setup again. Otherwise, please contact your PSM-CAP App supplier for assistance if you want to change this naming requirement.</p>');
    if (ctype_digit(substr(DATABASE_INSTANCE_ZFPF, 0, 1)))
        exit('<p>The database instance name you defined in settings/CoreSettingsZfpf.php starts with a numeric character (a digit), such as 0 to 9. Please define a name that does not start with a digit and then press the back button on your web browser to run setup again. Please contact your PSM-CAP App supplier for assistance if you want to change this requirement.</p>');
    // Check first app login credentials
    $AppAdminCredentials = $UserZfpf->username_password_check($Zfpf, 'FALSE', 'new_username', 'new_password', TRUE, TRUE); // Last parameter must be TRUE because database not setup yet.
    if ($AppAdminCredentials['Message'])
        exit($AppAdminCredentials['Message']);
    // Connect to DBMS using the existing administrator username, password, and FALSE for the database instance.
    echo $Zfpf->xhtml_contents_header_1c('Setup', FALSE, FALSE).'<p>
    Connecting to database using the existing administrator username and password...';
    if (!$_POST['dbms_admin_username'] or !$_POST['dbms_admin_password'] or strlen($_POST['dbms_admin_username']) > C5_MAX_BYTES_ZFPF  or strlen($_POST['dbms_admin_password']) > C5_MAX_BYTES_ZFPF) {
        echo ' you didn\'t provide a DMBS username or password (or they were too connect_instance_1slong).</p>
        '.$Zfpf->xhtml_footer_1c();
        exit; // Don't save and exit.
    }
    $DBMSresource = $Zfpf->connect_instance_1s($_POST['dbms_admin_username'], $_POST['dbms_admin_password'], FALSE); // FALSE here indicates no database instance.
    echo ' done.<br />';
    // TO DO FOR PRODUCTION VERSION - START - determine if this needed based on server defaults.
    // This is MySQL specific and requires MyQSL SUPER privilege, which Google Cloud SQL -- the != /cloudsql/ case below -- doesn't allow.
    // But Google Cloud SQL already uses innodb with Barracuda... app works there.
    // If applicable, ensure MySQL global variables are set to allow tables with many columns (to avoid "Row size too large (> 8126)" MySQL error).
    if (DBMS_NAME_ZFPF == 'mysqli' and substr(DBMS_SOCKET_ZFPF, 0, 10)  != '/cloudsql/') {
        if (!$Zfpf->query_1s($DBMSresource, 'SET GLOBAL innodb_file_format = Barracuda')) exit; // Don't save and exit.
        if (!$Zfpf->query_1s($DBMSresource, 'SET GLOBAL innodb_file_format_max = Barracuda')) exit; // Don't save and exit.
        if (!$Zfpf->query_1s($DBMSresource, 'SET GLOBAL innodb_file_per_table = ON')) exit; // Don't save and exit.
    }
    // TO DO FOR PRODUCTION VERSION - END - determine if this needed based on server defaults.
    $CreateDatabaseInstance = TRUE; // TO DO FOR PRODUCTION VERSION if database instance already created, change to FALSE.
    if ($CreateDatabaseInstance) {
        // Create new database
        echo 'Creating new database instance...';
        $Query = 'CREATE DATABASE '.DATABASE_INSTANCE_ZFPF;
        if (!$Zfpf->query_1s($DBMSresource, $Query)) exit; // Don't save and exit.
        echo ' done.<br />';
    }
    $Zfpf->close_connection_1s($DBMSresource);
    // Create all tables in above schema
    echo 'Creating new database schema...';
    $DBMSresource = $Zfpf->connect_instance_1s($_POST['dbms_admin_username'], $_POST['dbms_admin_password']);
    $Zfpf->create_table_sql_1s($DBMSresource, $Schema, DBMS_NAME_ZFPF);
    echo ' done.<br />';
    // Create DBMS "users" with the four types of privileges that the DBMS will grant the PSM-CAP App.
    // DBMS SPECIFIC SQL - some DBMS may not provide the GRANT command. (?)
    echo 'Granting privileges to the four new DBMS usernames used by the PSM-CAP App...';
    list($DBMSUsername, $DBMSPassword) = $Zfpf->credentials_1s(MAX_PRIVILEGES_ZFPF);
    $GrantSQL = 'GRANT '.MAX_PRIVILEGES_ZFPF.' ON '.DATABASE_INSTANCE_ZFPF.'.* TO \''.$DBMSUsername.'\'@\''.DBMS_HOSTPATH_ZFPF.'\' IDENTIFIED BY \''.$DBMSPassword.'\'';
    if (!$Zfpf->query_1s($DBMSresource, $GrantSQL)) exit; // Don't save and exit.
    list($DBMSUsername, $DBMSPassword) = $Zfpf->credentials_1s(MID_PRIVILEGES_ZFPF);
    $GrantSQL = 'GRANT '.MID_PRIVILEGES_ZFPF.' ON '.DATABASE_INSTANCE_ZFPF.'.* TO \''.$DBMSUsername.'\'@\''.DBMS_HOSTPATH_ZFPF.'\' IDENTIFIED BY \''.$DBMSPassword.'\'';
    if (!$Zfpf->query_1s($DBMSresource, $GrantSQL)) exit; // Don't save and exit.
    list($DBMSUsername, $DBMSPassword) = $Zfpf->credentials_1s('logon maintenance');
    $GrantSQL = 'GRANT SELECT, UPDATE (k2username_hash, s5password_hash, c5ts_password, c5ts_last_logon, c5attempts, c6active_sessions, c5name_family, c5name_given1, c5name_given2, c5personal_phone_mobile, c5personal_phone_home, c5e_contact_name, c5e_contact_phone, c5challenge_question1, s5cq_answer_hash1, c5challenge_question2, s5cq_answer_hash2, c5challenge_question3, s5cq_answer_hash3, c5who_is_editing) ON '.DATABASE_INSTANCE_ZFPF.'.t0user TO \''.$DBMSUsername.'\'@\''.DBMS_HOSTPATH_ZFPF.'\' IDENTIFIED BY \''.$DBMSPassword.'\'';
    if (!$Zfpf->query_1s($DBMSresource, $GrantSQL)) exit; // Don't save and exit.
    $GrantSQL = 'GRANT SELECT, INSERT ON '.DATABASE_INSTANCE_ZFPF.'.t0history TO \''.$DBMSUsername.'\'@\''.DBMS_HOSTPATH_ZFPF.'\' IDENTIFIED BY \''.$DBMSPassword.'\''; // 'logon maintenance' DBMS user-privileges also needs to be able to SELECT, INSERT on t0history
    if (!$Zfpf->query_1s($DBMSresource, $GrantSQL)) exit; // Don't save and exit.
    $GrantSQL = 'GRANT SELECT, INSERT, DELETE ON '.DATABASE_INSTANCE_ZFPF.'.t0session_ids TO \''.$DBMSUsername.'\'@\''.DBMS_HOSTPATH_ZFPF.'\' IDENTIFIED BY \''.$DBMSPassword.'\''; // 'logon maintenance' DBMS user-privileges also needs to be able to SELECT, INSERT, DELETE on t0session_ids
    if (!$Zfpf->query_1s($DBMSresource, $GrantSQL)) exit; // Don't save and exit.
    list($DBMSUsername, $DBMSPassword) = $Zfpf->credentials_1s(LOW_PRIVILEGES_ZFPF);
    $GrantSQL = 'GRANT '.LOW_PRIVILEGES_ZFPF.' ON '.DATABASE_INSTANCE_ZFPF.'.* TO \''.$DBMSUsername.'\'@\''.DBMS_HOSTPATH_ZFPF.'\' IDENTIFIED BY \''.$DBMSPassword.'\'';
    if (!$Zfpf->query_1s($DBMSresource, $GrantSQL)) exit; // Don't save and exit.
    echo ' done.<br />';
    // Insert First PSM-CAP App Administrator
    // with PSM-CAP App administrator privileges.
    // Insert 0 in c5ts_password column to require new password on next logon.
    // Also insert time user created, modified, and last logon as the current time.
    // k0user is the primary key, see fpf data type specifications for an explanation of why an 
    // auto-incremented integer is not used.
    echo 'Inserting new First PSM-CAP App Administrator into the new database instance...';
    // Use the MDB2 data type "text" for binary database fields
    // k0user and k2username_hash are not encrypted so that they can be used as keys.
    // Do NOT change variables below without changing in required files.
    $Nothing = '[Nothing has been recorded in this field.]';
    $EncryptedNothing = $Zfpf->encrypt_1c($Nothing);
    $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
    $EncryptedCurrentTime = $Zfpf->encrypt_1c(time());
    $EncryptedFullPrivileges = $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF);
    $EncryptedNone = $Zfpf->encrypt_1c(NO_PRIVILEGES_ZFPF);
    $EncryptedNotApplicable = $Zfpf->encrypt_1c('Not Applicable');
    $_SESSION['t0user'] = array( // Put in session because templates call CoreZfpf::insert_sql_1s, which calls CoreZfpf::record_in_history_1c.
        'k0user' => time().mt_rand(1000000, 9999999),
        'k2username_hash' => $AppAdminCredentials['UsernameHash'],
        's5password_hash' => $AppAdminCredentials['PasswordHashEncrypted'],
        'c5ts_password' => $Zfpf->encrypt_1c(0),
        'c5p_global_dbms' => $EncryptedFullPrivileges,
        'c5app_admin' => $Zfpf->encrypt_1c('Yes'),
        'c5ts_created' => $EncryptedCurrentTime,
        'c5ts_logon_revoked' => $EncryptedNothing,
        'c5ts_last_logon' => $Zfpf->encrypt_1c(0),
        'c5attempts' => $Zfpf->encrypt_1c(0),
        'c6active_sessions' => $Zfpf->encode_encrypt_1c(array()),
        'c5name_family' => $EncryptedNothing,
        'c5name_given1' => $Zfpf->encrypt_1c('Setup Admin'),
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
    $Zfpf->insert_sql_1s($DBMSresource, 't0user', $_SESSION['t0user']);
    echo ' done.<br />';
    // INSERT PSM-CAP App template information. Order of require files below matters, later depend on earlier.
    echo 'Inserting PSM-CAP App template information into the new database instance...';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/unions.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/rules.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/divisions.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/psm_fragments.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/cap_fragments.php'; // cap_fragments extends the numbering of psm_fragments, to avoid identical keys inserted into fragments
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/fragment_division.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/practices.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/practice_division.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/fragment_practice.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_pha.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_subprocess.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_scenario.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_cause.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_scenario_cause.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_consequence.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_scenario_consequence.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_safeguard.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_scenario_safeguard.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_psm-audit.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/psm-audit_fragment.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_obstopic.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_psm-audit_obstopic.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_obsmethod.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_obstopic_obsmethod.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/psm-audit_f_nh3r_om.php';
    $Zfpf->close_connection_1s($DBMSresource);
    unset($_SESSION['t0user']); // Avoid interference with next logon.
    echo ' done.<br /></p>
    <p>Success! PSM-CAP App database instance and first user created.<br />Please <a href="logon.php">log on</a> again with the credentials you supplied.</p><p>
    <b>Important! Delete file setup.php</b> or move it to a secure folder, not accessible to anyone but the database administrators and not in the PHP include path.</p>
    '.$Zfpf->xhtml_footer_1c();
}

exit; // To stop front_controller.php from continuing to default case, if setup.php found. See comment in front_controller.php
// Don't save and exit.

