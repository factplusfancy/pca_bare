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
    Setup anhydrous-ammonia mechanical-refrigeration templates</h2>
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
    Inserting into the database instance...<br />';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/SetupZfpf.php';
    $SetupZfpf = new SetupZfpf;
    // TO DO FOR PRODUCTION VERSION  Comment out any already-installed templates before running this file or uncomment all if re-installing after dropping database. 
    // TO DO FOR PRODUCTION VERSION  Run in batches if server times out.
    echo 'anhydrous-ammonia mechanical-refrigeration what-if/checklist PHA template<br />';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_pha.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_subprocess.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_scenario.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_cause.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_scenario_cause.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_consequence.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_scenario_consequence.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_safeguard.php';
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/nh3r_scenario_safeguard.php';
    $Zfpf->close_connection_1s($DBMSresource);
    unset($_SESSION['t0user']); // Avoid interference with next logon.
    echo '...done.</p><p>
    <b>Important! Delete setup files</b> or move them to a secure folder, not accessible to anyone but the database administrators and not in the PHP include path.</p>
    '.$Zfpf->xhtml_footer_1c();
}

exit; // To stop front_controller.php from continuing to default case. See comment in front_controller.php
// Don't save and exit.

