<?php
// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This administration file displays options customized based on the users privileges.
// Options range from changing your own password to creating a new facility owner entry, adding users, etc.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

$Zfpf->session_cleanup_1c();
$FullGlobalP = FALSE;
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF)
    $FullGlobalP = TRUE;

$Message = $Zfpf->xhtml_contents_header_1c('Admin').'<h1>
Administration</h1><p>
<b>Only options your privileges allow are listed below.</b> Contact a PSM leader, an app admin, or your supervisor if you don\'t see what you need.</p>';

/////////////////////////////////// User self-administration (admin) options ///////////////////////////////////
// All users can change their password, username, as well as personal information, emergency contacts, and challenge questions.
// Only users can change this information, so they (or a hacker) are responsible for any changes that show-up in the user personal-information history.
// Usernames in this web-app function like a supplement to the password -- they can be anything the user wants.
// Usernames are not used by admins for looking up users; current and prior names and personal phone numbers are used for that.
// Admins can look up users via t0user_history using any prior name or phone number, and see all the changes a user (or hacker) made to their name, etc.
$Message .= '<h2>
Self-Admin Options</h2><p>
<a class="toc" href="username_password_i13bu.php">- Change password or username.</a><br />
<a class="toc" href="user_o0.php">- Update personal information, emergency contacts, and challenge questions.</a></p>'; // bu means "by user" 

/////////////////////////////////// app admin options ///////////////////////////////////
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes' and $FullGlobalP)
    $Message .= '<h2>
    App-Admin Options</h2><p>
    <a class="toc" href="user_i0m.php?app_admin">- Lookup a user to:</a><br />
    &nbsp;&nbsp;&nbsp;&nbsp;* change password or username (lock or unlock user account),<br />
    &nbsp;&nbsp;&nbsp;&nbsp;* app-admin privileges -- give or remove, and<br />
    &nbsp;&nbsp;&nbsp;&nbsp;* view user history.<br />
    <a class="toc" href="owner_i0n.php">- Owner/Operator -- insert new record.</a><br />
    <a class="toc" href="practice_io03.php?practice_templates_i1m">- Standard compliance practices -- update.</a><br />
    <a class="toc" href="who_is_editing_unlock.php">- Who is editing unlock -- view records that are currently locked for editing and unlock.</a>
    </p>';

/////////////////////////////////// Owner admin options ///////////////////////////////////
if (isset($_SESSION['StatePicked']['t0owner']) and isset($_SESSION['t0user_owner'])) {
    $Message .= '<h2>
    '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).' <a class="toc" href="glossary.php#owner_operator" target="_blank">Owner/Operator</a> Options</h2><p>';
    // c5p_user
    $Message .= '
    <b>Employees of the Owner/Operator (aka the owner):</b><br />';
    if ($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP) {
        $Message .= '
        <a class="toc" href="username_password_i03.php">- Change password or username (lock or unlock user account).</a><br />
        <a class="toc" href="user_owner_i0m.php">- User, user-owner, and user-practice records -- insert new, update, or view current and history.</a> These provide an employee with PSM-CAP App access, set privileges, and record some personal and employment information. <b>From a user-owner record</b> you may:<br />
        &nbsp;&nbsp;&nbsp;&nbsp;* revoke a user\'s access to information of the owner and its facilities, processes, and <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>.<br />';
        if ($_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0owner']['k0user_of_leader']) // Current user is owner PSM leader
            $Message .= '
            Because you are the owner PSM leader, <b>from a user record</b> you may:<br />
            &nbsp;&nbsp;&nbsp;&nbsp;* change a user\'s PSM-CAP app global privileges and<br />
            &nbsp;&nbsp;&nbsp;&nbsp;* revoke a user\'s logon credentials to this PSM-CAP app.<br />';
        $Message .= '
        <a class="toc" href="user_h_o1.php">- User-history records.</a> Use this to find former users and every record involving a user.<br />';
    }
    else
        $Message .= '
        <a class="toc" href="user_owner_i0m.php">- User, user-owner, and user-practice records -- view current and history.</a> These provide an employee with PSM-CAP App access, show privileges, and record some personal and employment information.<br />';
    // c5p_contractor. Any user with t0user-owner record can view contractors associated with the owner, other privileges checked downstream.
    $Message .= '<br />
    <b><a class="toc" href="glossary.php#contractor" target="_blank">Contractors:</b></a><br />
    <a class="toc" href="contractor_i0m.php">- Contractor and owner-contractor records -- insert new, update, or view current and history.</a><br />
    See also "Contractors (on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.')" in the <a class="toc" href="contents0.php">Contents</a><br/>';
    // c5p_facility
    $Message .= '<br />
    <b><a class="toc" href="glossary.php#facility" target="_blank">Facilities:</a></b><br />';
    if ($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_facility']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP)
        $Message .= '
        <a class="toc" href="facility_i0m.php">- Facility summary -- insert new, update, or view current and history, including local emergency-planning committee/entity (LEPC) information.</a><br />';
    // TO DO optional future admin capabilities:
    // TO DO <a class="toc" href="___">- Transfer between owners and so forth.</a><br />
    // TO DO <a class="toc" href="___">- Removed from PSM service -- indicate that a facility no longer needs PSM.</a><br />
        
    else
        $Message .= '
        <a class="toc" href="facility_i0m.php">- Facility records -- View current and history.</a><br />';
    // c5p_owner
    $Message .= '<br />
    <b>Owner-wide <a class="toc" href="glossary.php#practices" target="_blank">Compliance practices</a> and owner summary:</b><br />';
    if ($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_owner']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP)
        $Message .= '
        <a class="toc" href="practice_io03.php?owner">- Compliance practices -- insert new, update, or view current and history (owner-wide only).</a><br />
        <a class="toc" href="owner_io03.php">- Owner summary -- update or view current and history.</a>';
    else
        $Message .= '
        <a class="toc" href="practice_io03.php?owner">- Compliance practices -- view current and history (owner-wide only).</a><br />
        <a class="toc" href="owner_io03.php">- Owner summary -- view current and history.</a>';
    $Message .= '
    </p>';
}

/////////////////////////////////// Contractor admin options ///////////////////////////////////
if (isset($_SESSION['StatePicked']['t0contractor']) and isset($_SESSION['t0user_contractor'])) {
    $Message .= '<h2>
    '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']).' <a class="toc" href="glossary.php#contractor" target="_blank">Contractor</a> Options</h2><p>
    <b>Contractor Individuals (employees of the contractor or its subcontractors):</b><br />';
    // c5p_user
    if ($Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_user']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP) {
        $Message .= '
        <a class="toc" href="username_password_i03.php">- Change password or username (lock or unlock user account).</a><br />
        <a class="toc" href="user_contractor_i0m.php">- User, user-contractor, and user-practice records -- insert new, update, or view current and history.</a> These provide an employee with PSM-CAP App access, set privileges, and record some personal and employment information.<br />
        From a user-contractor record you may:<br />
        &nbsp;&nbsp;&nbsp;&nbsp;* revoke a user\'s access to information of the contractor and its client owners, facilities, processes, and <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>.<br />';
        if ($_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0contractor']['k0user_of_leader']) // Current user is contractor PSM leader
            $Message .= '
            From a user record, because you are the contractor PSM leader, you may:<br />
            &nbsp;&nbsp;&nbsp;&nbsp;* change a user\'s PSM-CAP app global privileges and<br />
            &nbsp;&nbsp;&nbsp;&nbsp;* revoke a user\'s logon credentials to this PSM-CAP app.<br />';
        $Message .= '
        <a class="toc" href="user_h_o1.php">- User-history records.</a> Use this to find former users and every record involving a user.<br />';
    }
    else
        $Message .= '
        <a class="toc" href="user_contractor_i0m.php">- User, user-contractor, and user-practice records -- view current and history.</a> These provide an employee with PSM-CAP App access, show privileges, and record some personal and employment information.<br />';
    // c5p_contractor
    $Message .= '<br />
    <b>Contractor-wide <a class="toc" href="glossary.php#practices" target="_blank">Compliance practices</a> and contractor summary:</b><br />';
    if ($Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_contractor']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP)
        $Message .= '
        <a class="toc" href="practice_io03.php?contractor">- Compliance practices -- insert new, update, or view current and history (contractor-wide only).</a><br />
        <a class="toc" href="contractor_io03.php">- Contractor summary -- update or view current and history.</a><br />';
    else
        $Message .= '
        <a class="toc" href="practice_io03.php?contractor">- Compliance practices -- view current and history (contractor-wide only).</a><br />
        <a class="toc" href="contractor_io03.php">- Contractor summary -- view current and history.</a><br />';
    // Clients
    $Message .= '<br />
        <b>Clients</b><br />
        <a class="toc" href="owner_i0m.php">- View PSM Owner/Operators (aka owners) associated with the contractor via this app</a>
    </p>';
}

/////////////////////////////////// Facility admin options ///////////////////////////////////
if (isset($_SESSION['StatePicked']['t0facility']) and isset($_SESSION['t0user_facility'])) {
    $Message .= '<h2>
    '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).' <a class="toc" href="glossary.php#facility" target="_blank">Facility</a> Options</h2><p>';
    // c5p_user
    if ($Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP)
        $Message .= '
        <a class="toc" href="user_facility_i0m.php">- User-facility and user-practice records -- insert new, update, or view current and history.</a> These associate a user with a facility, set privileges, and record any union (collective bargaining) representation.<br />
        <a class="toc" href="user_h_o1.php?facility">- User-facility history records.</a> Use this to find former users and every record involving a user.<br />';
    // c5p_union
    if ($Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_union']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP)
        $Message .= '
        <a class="toc" href="union_i0m.php">- Union (collective-bargaining representative) records -- associate with facility, insert new, update, or view current and history</a>.<br />'; // Don't delete t0union rows. Just mark "dissolved" or something under c6description
    // c5p_process
    if ($Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_process']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP)
        $Message .= '
        <a class="toc" href="process_i0m.php">- Process records -- insert new, update, or view current and history.</a><br />';
    else
        $Message .= '
        <a class="toc" href="process_i0m.php">- Process records -- View current and history.</a><br />';
    // c5p_facility
    if ($Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP)
        $Message .= '
        <a class="toc" href="practice_io03.php?facility">- Compliance practices -- insert new, update, or view current and history (facility-wide only).</a><br />
        <a class="toc" href="facility_io03.php">- Facility summary -- update or view current and history.</a>';
    else
        $Message .= '
        <a class="toc" href="practice_io03.php?facility">- Compliance practices -- view current and history (facility-wide only).</a><br />
        <a class="toc" href="facility_io03.php">- Facility summary -- view current and history.</a>';
    $Message .= '
    </p>';
}

/////////////////////////////////// Process admin options ///////////////////////////////////
if (isset($_SESSION['StatePicked']['t0process']) and isset($_SESSION['t0user_process'])) {
    $Message .= '<h2>
    '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0process']['c5name']).' <a class="toc" href="glossary.php#process" target="_blank">Process</a> Options</h2><p>';
    // c5p_user
    if ($Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_user']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP)
        $Message .= '
        <a class="toc" href="user_process_i0m.php">- User-process and user-practice records -- insert new, update, or view current and history.</a> These associate a user with a process, set privileges, and allow for a name and description of the user\'s responsibilities for this process.<br />
        <a class="toc" href="user_h_o1.php?process">- User-process history records.</a> Use this to find former users and every record involving a user.<br />';
    // c5p_process
    if ($Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_process']) == MAX_PRIVILEGES_ZFPF and $FullGlobalP)
        $Message .= '
        <a class="toc" href="practice_io03.php?process">- Compliance practices -- insert new, update, or view current and history (process-wide only).</a><br />
        <a class="toc" href="process_io03.php">- Process summary -- update or view current and history.</a>';
    else
        $Message .= '
        <a class="toc" href="practice_io03.php?process">- Compliance practices -- view current and history (process-wide only).</a><br />
        <a class="toc" href="process_io03.php">- Process summary -- view current and history.</a>';
    $Message .= '
    </p>';
}

// Logging on with a temporary password gives the user the option of going to administer1.php before contents1.php, which gets them here.
if (!isset($_SESSION['StatePicked']['t0owner']) and !isset($_SESSION['StatePicked']['t0contractor']))
    $Message .= '<p>
    To be shown information about owners and contractors you have authority to administer, if any, you must first go to the <a class="toc" href="contents0.php">Contents</a> page.</p>';

echo $Message.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

