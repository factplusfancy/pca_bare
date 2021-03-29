<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i0m file outputs an HTML form to select an existing record or create a new one.
// Called via a link in administer1.php. 
// Also called by button in user_facility_io03.php

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

// General security check
if (!isset($_SESSION['t0user_facility']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

function processes_list($Zfpf, $DBMSresource, $k0facility, $Counter = 0) {
    $RadioButtons = '';
    $Message = '';
    $Conditions[0] = array('k0facility', '=', $k0facility);
    list($SRFP, $RRFP) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_process', $Conditions, array('k0process'));
    if ($RRFP) foreach ($SRFP as $VFP) {
        $Conditions[0] = array('k0process', '=', $VFP['k0process']);
        list($SRP, $RRP) = $Zfpf->select_sql_1s($DBMSresource, 't0process', $Conditions, array('c5name', 'c6description'));
        if ($RRP != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRP);
        $ProcessNameDescription =  $Zfpf->entity_name_description_1c($SRP[0], 100, FALSE); // Don't bold name.
        $Conditions[1] = array('k0user', '=', $_SESSION['Selected']['k0user'], '', 'AND');
        list($SRUP, $RRUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions);
        if ($RRUP > 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUP);
        if ($RRUP == 1) {
            $Conditions[1] = array('k0user', '=', $_SESSION['t0user']['k0user'], '', 'AND');
            list($SRCUP, $RRCUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions, array('k0user_process', 'c5p_user'));
            if ($RRCUP > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRCUP);
            if ($RRCUP == 1) {
                if ($Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) == MAX_PRIVILEGES_ZFPF or $Zfpf->decrypt_1c($SRCUP[0]['c5p_user']) == MAX_PRIVILEGES_ZFPF) {
                    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_process_i0m.php';
                    $_SESSION['SelectResults']['t0user_process'][$Counter] = $SRUP[0];
                    $RadioButtons .= '<input type="radio" name="selected" value="'.$Counter++.'" />'.$ProcessNameDescription.'<br />';
                }
                else
                    $Message .= '<p>
                    You don\'t have privileges to view or change user-process records there, but the selected user is associated with process: '.$ProcessNameDescription.'</p>';
            }
            else
                $Message .= '<p>
                The selected user is, but you are not, associated with process: '.$ProcessNameDescription.'</p>';
        }
    }
    return array('RadioButtons' => $RadioButtons, 'Message' => $Message, 'Counter' => $Counter);
}

if (isset($_POST['clear_process'])) {
    if (!isset($_SESSION['StatePicked']['t0process']) or !isset($_SESSION['t0user_process']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_SESSION['Scratch']['OldSelected'])) {
        $_SESSION['Selected'] = $_SESSION['Scratch']['OldSelected'];
        unset($_SESSION['Scratch']['OldSelected']);
    }
    unset($_SESSION['StatePicked']['t0process']);
    unset($_SESSION['t0user_process']);
}

if (isset($_SESSION['Selected']['k0user_facility'])) { // User selected, coming from user_facility_io03.php
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    if (!isset($_SESSION['StatePicked']['t0process']) or !isset($_SESSION['t0user_process'])) { // Need to select process
        if ($_SESSION['Selected']['k0facility'] != $_SESSION['t0user_facility']['k0facility'])
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ProcessesList = processes_list($Zfpf, $DBMSresource, $_SESSION['t0user_facility']['k0facility']);
        if ($ProcessesList['RadioButtons'])
            $Message = '
            <form action="user_process_io03.php" method="post">
                <p><b>Select process:</b><br /><br />
                '.$ProcessesList['RadioButtons'].'</p><p>
                <input type="submit" name="user_process_o1" value="View user-process record" /></p>
            </form>'.$ProcessesList['Message'];
        else
            $Message = '<p>
            No processes were found associated with you, the selected user, and the selected facility.</p>'.$ProcessesList['Message'];
    }
    else { // Both user and process have been selected.
        if (((isset($_SESSION['t0user_owner']) and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) == MAX_PRIVILEGES_ZFPF) or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) == MAX_PRIVILEGES_ZFPF or $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_user']) == MAX_PRIVILEGES_ZFPF) and $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF) {
            $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_process_i0m.php';
            $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user'], 'AND');
            $Conditions[1] = array('k0process', '=', $_SESSION['t0user_process']['k0process']);
            list($SRUP, $RRUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions);
            if ($RRUP > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUP);
            if ($RRUP == 1) {
                $_SESSION['Scratch']['OldSelected'] = $_SESSION['Selected'];
                $_SESSION['Selected'] = $SRUP[0];
                $Message = '<p>
                <b>View user privileges with the selected process.</b></p>
                <form action="user_process_io03.php" method="post"><p>
                    <input type="submit" name="user_process_o1" value="View user-process record" /></p>
                </form>';
            }
            else
                $Message = '<p>
                <b>The selected user is not associated with the selected process.</b></p>
                <form action="user_process_io03.php" method="post"><p>
                    <input type="submit" name="user_process_i0n0" value="Associate user with process" /></p>
                </form>';
        }
        else
            $Message = '<p>
            You don\'t have privileges to view or change user-process records at the selected process.</p>';
        $ProcessNameDescription = $Zfpf->entity_name_description_1c($_SESSION['StatePicked']['t0process'], 100, FALSE); // Don't bold name.
        $Message = '<p>
        <b>Selected <a class="toc" href="glossary.php#process" target="_blank">process</a>:</b> '.$ProcessNameDescription.'</p><p>
        '.$Message.'
        <form action="user_process_i0m.php" method="post"><p>
            <input type="submit" name="clear_process" value="Select a different process" /></p>
        </form>';
    }
    $Zfpf->close_connection_1s($DBMSresource);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    User-Process Records</h2><p>
    <b>Selected user:</b> '.$Zfpf->user_job_info_1c($_SESSION['Selected']['k0user'])['NameTitleEmployerWorkEmail'].'</p>
    '.$Message.'
    <form action="user_facility_i0m.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
else { // Coming from administer1.php process menu: selected process but not user. Typical i1m code is below.
    // Additional security check. SPECIAL CASE set scurity tolken here, because called via administer1.php link to user_process_i0m.php.
    if (isset($_SESSION['StatePicked']['t0process']) and isset($_SESSION['t0user_process']) and $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_user']) == MAX_PRIVILEGES_ZFPF and $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF) // Same privileges needed to get user_process_i0m.php link from administer1.php.
        $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_process_i0m.php';
    else
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_SESSION['Selected']))
        unset($_SESSION['Selected']);
    $Conditions[0] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process']);
    list($_SESSION['SelectResults']['t0user_process'], $RR['t0user_process']) = $Zfpf->one_shot_select_1s('t0user_process', $Conditions);
    if ($RR['t0user_process'] > 0) foreach ($_SESSION['SelectResults']['t0user_process'] as $K => $V) {
        $SelectedUserJobInfo = $Zfpf->user_job_info_1c($V['k0user']);
        if (isset($_POST['LogonRevokedUsersOnly'])) {
            if ($SelectedUserJobInfo['TimeLogonRevoked'] == '[Nothing has been recorded in this field.]')
                unset($_SESSION['SelectResults']['t0user_process'][$K]);
            else {
                $DisplayUserInfo[$K] = $SelectedUserJobInfo['NameTitle'].', '.$SelectedUserJobInfo['WorkEmail'];
                $SortUserInfo[$K] = $Zfpf->to_lower_case_1c($DisplayUserInfo[$K]);
            }
        }
        else {
            if ($SelectedUserJobInfo['TimeLogonRevoked'] != '[Nothing has been recorded in this field.]')
                unset($_SESSION['SelectResults']['t0user_process'][$K]);
            else {
                $DisplayUserInfo[$K] = $SelectedUserJobInfo['NameTitle'].', '.$SelectedUserJobInfo['WorkEmail'];
                $SortUserInfo[$K] = $Zfpf->to_lower_case_1c($DisplayUserInfo[$K]);
            }
        }
    }
    if (isset($_POST['LogonRevokedUsersOnly']))
        $Message = '<h2>
        Users associated with the process with revoked logon credentials.</h2>';
    else
        $Message = '<h2>
        Users associated with the process and with active logon credentials.</h2>';
    if (isset($SortUserInfo)) {
        array_multisort($SortUserInfo, $DisplayUserInfo, $_SESSION['SelectResults']['t0user_process']);
        $Message .= '
        <form action="user_process_io03.php" method="post"><p>';
        foreach ($_SESSION['SelectResults']['t0user_process'] as $K => $V) {
            if ($K)
                $Message .= '<br />';
            $Message .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if (!$K)
                $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
            $Message .= '/>'.$DisplayUserInfo[$K];
        }
        $Message .= '</p><p>
            <input type="submit" name="user_process_o1" value="View user-process record" /></p>
        </form>';
    }
    else
        $Message .= '<p><b>
        None found</b> for the current process. Please contact your supervisor if this seems amiss.</p>';
    if (!isset($_POST['LogonRevokedUsersOnly']))
        $Message .= '
        <form action="user_process_io03.php" method="post"><p>
            Click below to associate a user, who is already associated with the facility, with the selected process. This creates a new user-process record.<br />
            <input type="submit" name="user_process_i0n0" value="New user-process record" /></p>
        </form>
        <form action="user_process_i0m.php" method="post"><p>
            <input type="submit" name="LogonRevokedUsersOnly" value="Revoked logon-credetials users" /></p>
        </form>';
    else
        $Message .= '
        <form action="user_process_i0m.php" method="post"><p>
            <input type="submit" value="Active logon-credetials users" /></p>
        </form>';
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0process']['c5name']).'</h1>
    '.$Message.'
    <form action="administer1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

$Zfpf->catch_all_1c('administer1.php'); // Shouldn't be needed here, keep as safeguard against future mistakes.

$Zfpf->save_and_exit_1c();

