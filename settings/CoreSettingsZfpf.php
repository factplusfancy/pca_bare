<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// TO DO FOR PRODUCTION VERSION -- PRE-SETUP INSTRUCTIONS:
// ** VERY IMPORTANT FOR SECURITY **
// Ensure this file, or the entire /settings/ directory is safe and secure, and not accessible to the public, for example,
//  - by placing it outside the document root in a safe and secure directory or
//  - by limiting access to only the app's server, such as with .htaccess or app.yaml files, and
//    - by encrypting it so that only the app has access.
// Replace all cases of ChangeThisDefault below with strong passwords
// Review "options variables" below.
// Change the other constant definitions below as needed.
// Example: define('CONSTANT_NAME', '[you can change this parameter]');
// Contact your app supplier for additional assistance.

// 1.0 TYPICALLY CHANGED CONSTANTS

// See also:
// .../pca/directory_path_settings.php, which defines:
//    APP_DIRECTORY_PATH_ZFPF
//    SETTINGS_DIRECTORY_PATH_ZFPF
//    INCLUDES_DIRECTORY_PATH_ZFPF


// TO DO FOR PRODUCTION VERSION -- options variables: uncomment one per variable -- START

$OptionFiles = 'local';
// $OptionFiles = 'GAE_PHP5';

$OptionZipDownloadsWorks = FALSE;
// $OptionZipDownloadsWorks = TRUE;

// $OptionHazSub = 'generic';
$OptionHazSub = 'anhydrous_ammonia_refrigeration';

// TO DO FOR PRODUCTION VERSION -- options variables -- END 

// Time zone options are listed at https://www.php.net/manual/en/timezones.php
define('DEFAULT_TIME_ZONE_ZFPF', 'America/Chicago');
// Put this here to ensure it gets called, for example, in logon.php.
if (date_default_timezone_get() != DEFAULT_TIME_ZONE_ZFPF)
    date_default_timezone_set(DEFAULT_TIME_ZONE_ZFPF);

// define('USER_FILES_DIRECTORY_PATH_ZFPF', realpath(dirname(__FILE__)) . '/user_files'); // Would put user_files directory in app directory, better elsewhere. See security notes in .../pca/directory_path_settings.php.
if ($OptionFiles == 'local') // Development machine. Works on Ubuntu.
    define('USER_FILES_DIRECTORY_PATH_ZFPF', '/var/www/html/user_files');
elseif ($OptionFiles == 'GAE_PHP5') // Google Cloud Storage, bucket name: fpf-static-1.appspot.com
    define('USER_FILES_DIRECTORY_PATH_ZFPF', 'gs://fpf-static-1.appspot.com/pcaNAME/user_files');
// TO DO FOR PRODUCTION VERSION -- keep outside served-document root if possible; otherwise, keep in a safe and secure directory.

// Option ZIP: allow, if possible, downloading multiple files in zip folder versus allow user to only download one file at a time.
define('ZIP_DOWNLOAD_WORKS_ZFPF', $OptionZipDownloadsWorks);
// Define a "temporary" directory where plain text (not encrypted) files will be stored to make a zip file. Though they will be "deleted" after a few seconds, the memory may not be overwritten for months. Best to use RAM type memory for this, like Option FILES B.
if (ZIP_DOWNLOAD_WORKS_ZFPF) { // If TRUE, ZIP_TEMP_DIRECTORY_ZFPF must be defined.
    if ($OptionFiles == 'local')
        define('ZIP_TEMP_DIRECTORY_ZFPF', USER_FILES_DIRECTORY_PATH_ZFPF.'/'.mt_rand(0, 9999999).time().'/');
    elseif ($OptionFiles == 'GAE_PHP5')
        define('ZIP_TEMP_DIRECTORY_ZFPF', sys_get_temp_dir()); // See https://cloud.google.com/appengine/docs/standard/php7/runtime#tempnam_and_sys_get_temp_dir_support
}

// App permissions on file system. Used in FilesZfpf.php
if ($OptionFiles == 'local') 
    define('CHMOD_DIRECTORY_PERMISSIONS_ZFPF', 0700); // On Ubuntu, chmod 0700 is the minimum permissions that will work. At 0600 $ZipArchive->close() and rmdir fail. Windows ignores chmod argument in this function, See http://php.net/manual/en/function.chmod.php and http://php.net/manual/en/function.mkdir.php
elseif ($OptionFiles == 'GAE_PHP5')
   define('CHMOD_DIRECTORY_PERMISSIONS_ZFPF', 0600);

// This low-security salt and hash iterations are used for CoreZfpf::hash_1c and below, but NOT for storing user passwords.
// hash_1c is used for hashing app usernames and, via CoreZfpf:credentials_1s, the four app-to-DBMS passwords below.
define('HASH_SALT_ZFPF', 'ChangeThisDefaultSalt');
define('HASH_ITERATIONS_ZFPF', 200); // Depending on server, 200 make take 1 millisecond for hash_1c to run. Remember, it runs once per connection to DBMS.

// The encryption key for OpenSSL aec-256-cbc must be 32 bytes (256 bits) long and can contain any characters or bit pattern.
// Use a binary key rather than a key written in a character set, for added security.
// PHP function hash_pbkdf2 is used to generate the binary key, pending a better option.
// Change 'ChangeThisDefaultPassword' to the password you will use to generate the binary key. Use a strong password.
// Option, replace this with a "keyring" system
// You may also increase the number of iterations to greater than 4000.
define('BINARY_KEY_ZFPF', hash_pbkdf2('sha512', 'ChangeThisDefaultPassword', HASH_SALT_ZFPF, HASH_ITERATIONS_ZFPF, 32, TRUE));

// The database management system (DBMS) instance and the database instance are two separate things and may have different names.
// The database instance is the named collection of tables, typically implemented by the DBMS storage engine within a directory, aka folder, on a computer file system.
// The DBMS instance is the DBMS software, such as MySQL or Google Cloud SQL, installed on a computer.

// You may change the database-instance name. The default name, below, is psmcapmanual
// Our strongly recommended naming rules for DBMS portability are that this name be 
// lowercase, start with a letter, contain only numbers, letters, or underscores, and have a maximum 
// length of 27 characters. This app's setup tool (setup.php) requires you to follow these rules, 
// so to break them you will have to use an alternate means to name the database instance on your DBMS.
define('DATABASE_INSTANCE_ZFPF', 'psmcapmanual');

// DBMS host specification (hostspec) must define the correct DBMS path relative to the app server, including port and socket if applicable.
define('DBMS_PORT_ZFPF', NULL);
if ($OptionFiles == 'local') { // PHP to MySQL running locally, development machines.
    define('DBMS_HOSTPATH_ZFPF', 'localhost');
    define('DBMS_SOCKET_ZFPF', NULL);
}
elseif ($OptionFiles == 'GAE_PHP5') {// Google App Engine to Cloud SQL. See https://cloud.google.com/sql/docs/mysql/connect-app-engine and https://cloud.google.com/sql/docs/mysql/create-manage-databases
    define('DBMS_HOSTPATH_ZFPF', NULL); // Only the PHP type NULL works.
    define('DBMS_SOCKET_ZFPF', '/cloudsql/fpf-static-1:us-central1:fpf1'); //  /cloudsql/[GOOGLE_CLOUD_PROJECT_NAME]:[GOOGLE_CLOUD_REGION]:[CLOUD_SQL_DBMS_INSTANCE_NAME]
}

// The following DBMS names are used by CoreZfpf.php::data_type_1s() as precise substitutes for DBMS brand names.
//     MDB2, mssql, mysqli, oci8
// Modify CoreZfpf.php::data_type_1s() as needed to use other DBMS.
define('DBMS_NAME_ZFPF', 'mysqli');

// The usernames and passwords below are used by the app to access the DBMS. We highly 
// recommend you use a strong password, such as at least 10 characters long and at least one 
// non-alphanumeric character; however, this app's setup tool does not check the DBMS 
// usernames and passwords supplied below.
// Supply usernames and passwords for the global privilege levels listed below. 
// Later, when PSM-CAP App administrators insert new users, they will select which privilege level to assign them to.
// 
// MAX_PRIVILEGES_ZFPF privileges on all tables in the app's database. See MAX_PRIVILEGES_ZFPF constant definition below.
define('USERNAME1_ZFPF', 'ChangeThisDefaultUsername1');
define('PASSWORD1_ZFPF', 'ChangeThisDefaultPassword%+#1');
//
// MID_PRIVILEGES_ZFPF privileges on all database tables. See MID_PRIVILEGES_ZFPF constant definition below.
define('USERNAME2_ZFPF', 'ChangeThisDefaultUsername2');
define('PASSWORD2_ZFPF', 'ChangeThisDefaultPassword%+#2');
//
// 'logon maintenance' privileges for app log on, log off, user-self editing, and similar use only.
    //      SELECT privileges on only the t0user table and
    //      UPDATE privileges on only several user-information columns in the t0user table
define('USERNAME3_ZFPF', 'ChangeThisDefaultUsername3');
define('PASSWORD3_ZFPF', 'ChangeThisDefaultPassword%+#3');
//
// LOW_PRIVILEGES_ZFPF privileges on all database tables. See LOW_PRIVILEGES_ZFPF constant definition below.
define('USERNAME4_ZFPF', 'ChangeThisDefaultUsername4');
define('PASSWORD4_ZFPF', 'ChangeThisDefaultPassword%+#4');

// These define the time, in seconds, for each session timeout.  You may change the default values to fit your security policies.
// Session Timeout 1 of 3 - normal log off of user if inactive for a long period of time. Default seconds: 1200
define('TIMEOUT_INACTIVE_LOG_OFF_ZFPF', 1200);
// Session Timeout 2 of 3 - checks user password if inactive for a shorter period of time. Default seconds: 240
define('TIMEOUT_INACTIVE_PASSWORD_CHECK_ZFPF', 240);
// Session Timeout 3 of 3 - checks user password if this has not been done for a period of time.  Default seconds: 1200
define('TIMEOUT_ACTIVE_PASSWORD_CHECK_ZFPF', 1800);

// Time that two-step authentication security code will work, in seconds
define('ALLOWED_TWO_STEP_SECONDS', 420); // Default 420 seconds is 7 minutes.

// Password expiration time. 15552000 seconds is 180 days
define('PASSWORD_EXPIRATION_ZFPF', 15552000);
// Username and password minimum length. Used in class UzerZfpf.
define('USERNAME_MIN_BYTES_ZFPF', 10);
define('PASSWORD_MIN_BYTES_ZFPF', 12);
// Password minimum special characters. Used in class UzerZfpf.
define('PASSWORD_MIN_SPECIAL_CHAR_ZFPF', 3);

// Number of active sessions allowed. That's how many devices one username can log into at once.
// 1 allowed is typically the most secure, unless it would cause someone to have to logon in public places.
// 2 allowed would allow someone to stay logged on from smartphone most of time, and logon from office computer sometimes.
define('MAX_SESSIONS_ZFPF', 2);

// Email addresses that will be used in "From" and "Reply-To" fields when the PSM-CAP App sends emails.
define('EMAIL_FROM_ZFPF', 'james.hadley@factplusfancy.com');
define('EMAIL_REPLY_TO_ZFPF', 'james.hadley@factplusfancy.com');

// Constants below used in .../templates/practices.php, nh3r_hspswp_ep_usa.php, nh3r_itm.php and possibly elsewhere.
// Changes in templates won't affect deployed app.
define('DOC_WHERE_KEPT_ZFPF', ' As needed, describe where kept or upload in a practice document here.');
if ($OptionHazSub == 'generic') {
    define('HAZSUB_NAME_ADJECTIVE_ZFPF', 'hazardous-substance');
    define('HAZSUB_PROCESS_NAME_ZFPF', 'hazardous-substance process');
    define('HAZSUB_SAFETY_NOTICE_NAME_ZFPF', 'Hazardous-Substance Safety Notice');
    define('HAZSUB_DESCRIPTION_ZFPF', ''); // Needs leading space, unless blank.
    define('HAZSUB_LEAK_FIRST_STEPS_ZFPF', 'Alert your supervisor and move to safety if you notice a hazardous-substance leak.');
}
elseif ($OptionHazSub == 'anhydrous_ammonia_refrigeration') {
    define('HAZSUB_NAME_ADJECTIVE_ZFPF', 'anhydrous-ammonia');
    define('HAZSUB_PROCESS_NAME_ZFPF', 'ammonia-refrigeration system');
    define('HAZSUB_SAFETY_NOTICE_NAME_ZFPF', 'Ammonia-Refrigeration Safety Notice');
    define('HAZSUB_DESCRIPTION_ZFPF', ' Anhydrous ammonia is a colorless liquid or gas with a strong pungent odor. Most people can smell ammonia at concentrations lower than the harmful concentrations. If someone doesn\'t know what ammonia smells like, or thinks they cannot smell it, ask a qualified safety professional to find a household window cleaner or similar that contains ammonia, to see if they can smell it at safely low concentrations. Ammonia is dangerous to life and health at high concentrations. So is hot lubricating oil and anything at high pressures and temperatures. Liquid ammonia expands when it warms and can rupture piping and equipment if trapped.'); // Needs leading space.
    define('HAZSUB_LEAK_FIRST_STEPS_ZFPF', 'Alert your supervisor if you smell ammonia and move to safety if the odor becomes strong or irritating.');
}

// 2.0 TYPICALLY UN-CHANGED CONSTANTS

// See 0read_me_psm_cap_app_standards.txt for background on privileges management in fpf apps.
// The privilege constants must be VALID IN SQL GRANT COMMAND for setup.php to work, otherwise, map to valid grant-command SQL.
// The string length of the privileges below is used to compare actual to require privileges, for example, in pca/practice_o1.php
define('MAX_PRIVILEGES_ZFPF', 'SELECT, INSERT, UPDATE, DELETE'); // If changed ensure PHP strlen() longer than mid privileges.
define('MID_PRIVILEGES_ZFPF', 'SELECT, INSERT'); // If changed ensure PHP strlen() longer than low privileges...
define('LOW_PRIVILEGES_ZFPF', 'SELECT'); // If changed ensure PHP strlen() longer than no privileges...
define('NO_PRIVILEGES_ZFPF', 'None'); // If changed, see above.
/*
define('ALL_PRIVILEGE_OPTIONS_ZFPF', array(NO_PRIVILEGES_ZFPF, LOW_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF, MAX_PRIVILEGES_ZFPF));
define('MIN_MID_PRIVILEGE_OPTIONS_ZFPF', array(NO_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF, MAX_PRIVILEGES_ZFPF)); // Can still have NO_PRIVILEGES_ZFPF
define('MIN_MAX_PRIVILEGE_OPTIONS_ZFPF', array(NO_PRIVILEGES_ZFPF, MAX_PRIVILEGES_ZFPF)); // Can still have NO_PRIVILEGES_ZFPF;
define('GLOBAL_DBMS_OPTIONS_ZFPF', array(LOW_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF, MAX_PRIVILEGES_ZFPF)); // With NO_PRIVILEGES_ZFPF user couldn't log on or view anything. So, != LOW_PRIVILEGES_ZFPF means has at least INSERT global privileges.
*/
// TO DO FOR PRODUCTION VERSION -- 2019-05-29 Can replace below with above constants, once no need for pre- PHP 7 compatibility.
// For pre- PHP 7.0 compatibility, the arrays below are saved in the session variable and used like constants.
$_SESSION['PlainText']['ALL_PRIVILEGE_OPTIONS_ZFPF'] = array(NO_PRIVILEGES_ZFPF, LOW_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF, MAX_PRIVILEGES_ZFPF);
$_SESSION['PlainText']['MIN_MID_PRIVILEGE_OPTIONS_ZFPF'] = array(NO_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF, MAX_PRIVILEGES_ZFPF); // Can still have NO_PRIVILEGES_ZFPF
$_SESSION['PlainText']['MIN_MAX_PRIVILEGE_OPTIONS_ZFPF'] = array(NO_PRIVILEGES_ZFPF, MAX_PRIVILEGES_ZFPF); // Can still have NO_PRIVILEGES_ZFPF;
$_SESSION['PlainText']['GLOBAL_DBMS_OPTIONS_ZFPF'] = array(LOW_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF, MAX_PRIVILEGES_ZFPF); // With NO_PRIVILEGES_ZFPF user couldn't log on or view anything. So, != LOW_PRIVILEGES_ZFPF means has at least INSERT global privileges.

// HTML for required HTML form fields.
define('REQUIRED_FIELD_ZFPF', ' <span class="red">[Required]</span>');

// Standard maximum lengths, in bytes
// These are used to check or truncate user input from text or textarea fields.
// See Encryption Max-Length Note. $MaxLength... in 0read_me_psm_cap_app_standards.txt
// See also CoreZfpf::max_length_1c in file CoreZfpf.php
define('C5_MAX_BYTES_ZFPF', 192);
define('C6SHORT_MAX_BYTES_ZFPF', 8000);
define('C6LONG_MAX_BYTES_ZFPF', 40000);
define('HTML_MAX_CHAR_DIVISOR_ZFPF', 1.5);
    // The above max lengths are divided by this HTML_MAX_CHAR_DIVISOR_ZFPF constant to give the HTML form maxlength attribute for text and textarea fields.
    // Used in ConfirmZfpf::html_form_field_1e
    // If C5_MAX_BYTES_ZFPF = 192, then allowed values are: 1, 1.2, 1.5, 2, 3, or 4 -- to give HTML maxlengths of 192, 160, 128, 96, 64, or 32 characters.
    // Use HTML_MAX_CHAR_DIVISOR_ZFPF = 3 if 3-byte UTF-8 characters (Chinese characters, etc.) will be input.
    // Otherwise, HTML_MAX_CHAR_DIVISOR_ZFPF = 1.5 provides a 64-byte safety factor for use of  <  >  &  
    // which are encoded as the multi-byte HTML character entities by the app:  &lt;  &gt;  &amp;  
    // If a max length is exceeded, the user input is truncated to the max length - 3 (plus ...), after the above HTML character encoding. 
    // This is done in function ConfirmZfpf::post_to_display_1e by functions CoreZfpf::max_length_1c and CoreZfpf::xss_prevent_1c. 
    // The truncation is logged in the error log, but 
    // the user only notified by the addition of the ellipses '...' at the end of the truncated text, which they may notice on the confirmation page.
define('MAX_FILE_SIZE_ZFPF', 20000000);
    // HTML form max file size for file uploads -- c6bfn fields. 
    // The honest user is warned if this is exceeded as a convenience.  
    // The max file-upload size and post size are set in php.ini and 
    // FilesZfpf::c6bfn_files_upload_1e ejects the user if the upload_max_filesize directive in php.ini is exceeded.

// Priority Definitions for PSM-CAP App
// For a PHA, severity and likelihood determine the priority (aka risk ranking, RR). 
// Constants in PHP cannot start with a number, and it's better to have the number towards that front: thus the leading underscore.
define('_1_SEVERITY_ZFPF', 'Little or none');
define('_2_SEVERITY_ZFPF', 'Nuisance on-site');
define('_3_SEVERITY_ZFPF', 'Room evacuation or off-site nuisance');
define('_4_SEVERITY_ZFPF', 'Injury on-site (needing treatment beyond first aid) or small off-site evacuation/shelter-in-place');
define('_5_SEVERITY_ZFPF', 'Multiple serious injuries, fatalities, catastrophic...');
define('_1_LIKELIHOOD_ZFPF', 'Very unlikely (less than every 50 years)');
define('_2_LIKELIHOOD_ZFPF', '2% to 5% per year (every 20 to 50 years or so)');
define('_3_LIKELIHOOD_ZFPF', '5% to 20% per year (every 5 to 20 years or so)');
define('_4_LIKELIHOOD_ZFPF', '20% to 90% per year (every couple-few years)');
define('_5_LIKELIHOOD_ZFPF', 'Greater than 90% per year (almost every year or more often)');
// For audits, incident investigations, etc., a user should assign one of the priorities below to a finding.
// The priorities (risk ranks) below MUST start with ## -- a two-digit number -- so that the PHP less-than comparison operator  <
// will work in ccsaZfpf::risk_rank_priority_update ... $AffectedActions[$k0action] < $QSPriority
define('_00_PRIORITY_ZFPF', '00. No action needed');
define('_01_PRIORITY_ZFPF', '01. Lower priority (track any recommendations on action register, and resolve if convenient)');
define('_02_PRIORITY_ZFPF', '02. Lower priority (track any recommendations on action register, and resolve if convenient)');
define('_03_PRIORITY_ZFPF', '03. Lower priority (track any recommendations on action register, and resolve if convenient)');
define('_04_PRIORITY_ZFPF', '04. Attention needed (review status at least twice per year)');
define('_05_PRIORITY_ZFPF', '05. Attention needed (review status at least quarterly)');
define('_06_PRIORITY_ZFPF', '06. Attention needed (review status at least monthly)');
define('_07_PRIORITY_ZFPF', '07. Prompt attention needed (gather assessment team within 3 days)');
define('_08_PRIORITY_ZFPF', '08. Prompt attention needed (gather assessment team within 24 hours)');
define('_09_PRIORITY_ZFPF', '09. Prompt attention needed (gather assessment team within 4 hours)');
define('_10_PRIORITY_ZFPF', '10. Immediate action needed (gather assessment team within 15 minutes)');

