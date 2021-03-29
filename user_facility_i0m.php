<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i0m file outputs an HTML form to select an existing record or create a new one.
// Called via a link in administer1.php. 
// Also called by user_owner_io03.php and user_contractor_io03.php

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

// General security check
if (!isset($_SESSION['t0user_owner']) and !isset($_SESSION['t0user_contractor']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

function facilities_list($Zfpf, $DBMSresource, $k0owner, $Counter = 0) {
    $RadioButtons = '';
    $Message = '';
    $Conditions[0] = array('k0owner', '=', $k0owner);
    list($SROF, $RROF) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_facility', $Conditions, array('k0facility'));
    if ($RROF) foreach ($SROF as $VOF) {
        $Conditions[0] = array('k0facility', '=', $VOF['k0facility']);
        list($SRF, $RRF) = $Zfpf->select_sql_1s($DBMSresource, 't0facility', $Conditions, array('c5name', 'c5city', 'c5state_province', 'c5country'));
        if ($RRF != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRF);
        $Conditions[1] = array('k0user', '=', $_SESSION['Selected']['k0user'], '', 'AND');
        list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions);
        if ($RRUF > 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUF);
        if ($RRUF == 1) {
            $Conditions[1] = array('k0user', '=', $_SESSION['t0user']['k0user'], '', 'AND');
            list($SRCUF, $RRCUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions, array('k0user_facility', 'c5p_user'));
            if ($RRCUF > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRCUF);
            if ($RRCUF == 1) {
                if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF and ((isset($_SESSION['t0user_owner']) and $_SESSION['t0user_owner']['k0owner'] == $_SESSION['Selected']['k0owner'] and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) == MAX_PRIVILEGES_ZFPF) or $Zfpf->decrypt_1c($SRCUF[0]['c5p_user']) == MAX_PRIVILEGES_ZFPF)) {
                    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_facility_i0m.php';
                    $_SESSION['SelectResults']['t0user_facility'][$Counter] = $SRUF[0];
                    $RadioButtons .= '<input type="radio" name="selected" value="'.$Counter++.'" />'.$Zfpf->decrypt_1c($SRF[0]['c5name']).', '.$Zfpf->decrypt_1c($SRF[0]['c5city']).', '.$Zfpf->decrypt_1c($SRF[0]['c5state_province']).', '.$Zfpf->decrypt_1c($SRF[0]['c5country']).'<br />';
                }
                else
                    $Message .= '<p>
                    You don\'t have privileges to view or change user-facility records there, but the selected user is associated with facility: '.$Zfpf->decrypt_1c($SRF[0]['c5name']).', '.$Zfpf->decrypt_1c($SRF[0]['c5city']).', '.$Zfpf->decrypt_1c($SRF[0]['c5state_province']).', '.$Zfpf->decrypt_1c($SRF[0]['c5country']).'</p>';
            }
            else
                $Message .= '<p>
                The selected user is, but you are not, associated with facility: '.$Zfpf->decrypt_1c($SRF[0]['c5name']).', '.$Zfpf->decrypt_1c($SRF[0]['c5city']).', '.$Zfpf->decrypt_1c($SRF[0]['c5state_province']).', '.$Zfpf->decrypt_1c($SRF[0]['c5country']).'</p>';
        }
    }
    return array('RadioButtons' => $RadioButtons, 'Message' => $Message, 'Counter' => $Counter);
}

if (isset($_POST['clear_facility'])) {
    if (!isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['t0user_facility']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_SESSION['Scratch']['OldSelected'])) {
        $_SESSION['Selected'] = $_SESSION['Scratch']['OldSelected'];
        unset($_SESSION['Scratch']['OldSelected']);
    }
    unset($_SESSION['StatePicked']['t0facility']);
    unset($_SESSION['t0user_facility']);
    if (isset($_SESSION['StatePicked']['t0process']))
        unset($_SESSION['StatePicked']['t0process']);
    if (isset($_SESSION['t0user_process']))
        unset($_SESSION['t0user_process']);
}

if (isset($_SESSION['Selected']['k0user_owner']) or isset($_SESSION['Selected']['k0user_contractor'])) { // User selected, coming from user_owner or user_contractor_io03.php
    if (isset($_SESSION['Selected']['k0user_owner'])) {
        if (!isset($_SESSION['t0user_owner']) or $_SESSION['t0user_owner']['k0owner'] != $_SESSION['Selected']['k0owner'] or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__); // eject here & below; extra security for user relationships.
        $OwnerOrContractor = 'owner';
        $GoBackAction = 'user_owner_i0m.php';
    }
    else {
        if (!isset($_SESSION['t0user_contractor']) or $_SESSION['t0user_contractor']['k0contractor'] != $_SESSION['Selected']['k0contractor'] or $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $OwnerOrContractor = 'contractor';
        $GoBackAction = 'user_contractor_i0m.php';
    }
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    if (!isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['t0user_facility'])) { // Need to select facility
        if (isset($_SESSION['t0user_owner']) and isset($_SESSION['Selected']['k0user_owner']) and $_SESSION['t0user_owner']['k0owner'] == $_SESSION['Selected']['k0owner'])
            $FacilitiesList = facilities_list($Zfpf, $DBMSresource, $_SESSION['t0user_owner']['k0owner']);
        elseif (isset($_SESSION['t0user_contractor']) and isset($_SESSION['Selected']['k0user_contractor']) and $_SESSION['t0user_contractor']['k0contractor'] == $_SESSION['Selected']['k0contractor']) {
            $Conditions[0] = array('k0contractor', '=', $_SESSION['t0user_contractor']['k0contractor']);
            list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions, array('k0owner'));
            $FacilitiesList['RadioButtons'] = '';
            $FacilitiesList['Message'] = '';
            $Counter = 0;
            if ($RROC) foreach ($SROC as $VOC) {
                $PartialFacilitiesList = facilities_list($Zfpf, $DBMSresource, $VOC['k0owner'], $Counter);
                $FacilitiesList['RadioButtons'] .= $PartialFacilitiesList['RadioButtons'];
                $FacilitiesList['Message'] .= $PartialFacilitiesList['Message'];
                $Counter += $PartialFacilitiesList['Counter'];
            }
        }
        else
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        if ($FacilitiesList['RadioButtons'])
            $Message = '
            <form action="user_facility_io03.php" method="post">
                <p><b>Select facility:</b><br /><br />
                '.$FacilitiesList['RadioButtons'].'</p><p>
                <input type="submit" name="user_facility_o1" value="View user-facility record" /></p>
            </form>'.$FacilitiesList['Message'];
        else
            $Message = '<p>
            No facilities were found associated with you, the selected user, and the selected '.$OwnerOrContractor.'</p>'.$FacilitiesList['Message'];
    }
    else { // Both user and facility have been selected.
        if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF and ((isset($_SESSION['t0user_owner']) and $_SESSION['t0user_owner']['k0owner'] == $_SESSION['Selected']['k0owner'] and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) == MAX_PRIVILEGES_ZFPF) or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) == MAX_PRIVILEGES_ZFPF)) {
            $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_facility_i0m.php';
            $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user'], 'AND');
            $Conditions[1] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility']);
            list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions);
            if ($RRUF > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUF);
            if ($RRUF == 1) {
                $_SESSION['Scratch']['OldSelected'] = $_SESSION['Selected'];
                $_SESSION['Selected'] = $SRUF[0];
                $Message = '<p>
                <b>View user privileges with the selected facility.</b></p>
                <form action="user_facility_io03.php" method="post"><p>
                    <input type="submit" name="user_facility_o1" value="View user-facility record" /></p>
                </form>';
            }
            else
                $Message = '<p>
                <b>The selected user is not associated with the selected facility.</b></p>
                <form action="user_facility_io03.php" method="post"><p>
                    <input type="submit" name="user_facility_i0n0" value="Associate user with facility" /></p>
                </form>';
        }
        else
            $Message = '<p>
            You don\'t have privileges to view or change user-facility records at the selected facility.</p>';
        $Message = '<p>
        <b>Selected <a class="toc" href="glossary.php#facility" target="_blank">facility</a>:</b> '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5country']).'</p><p>
        '.$Message.'
        <form action="user_facility_i0m.php" method="post"><p>
            <input type="submit" name="clear_facility" value="Select a different facility" /></p>
        </form>';
    }
    $Zfpf->close_connection_1s($DBMSresource);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    User-Facility Records</h2><p>
    <b>Selected user:</b> '.$Zfpf->user_job_info_1c($_SESSION['Selected']['k0user'])['NameTitleEmployerWorkEmail'].'</p>
    '.$Message.'
    <form action="'.$GoBackAction.'" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
else { // Coming from administer1.php facility menu: selected facility but not user. Typical i1m code is below.
    // Additional security check. SPECIAL CASE set scurity tolken here, because called via administer1.php link to user_facility_i0m.php.
    if (isset($_SESSION['StatePicked']['t0facility']) and isset($_SESSION['t0user_facility']) and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) == MAX_PRIVILEGES_ZFPF and $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF) // Same privileges needed to get user_facility_i0m.php link from administer1.php.
        $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_facility_i0m.php';
    else
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_SESSION['Selected']))
        unset($_SESSION['Selected']);
    $Conditions[0] = array('k0facility', '=', $_SESSION['StatePicked']['t0facility']['k0facility']);
    list($_SESSION['SelectResults']['t0user_facility'], $RR['t0user_facility']) = $Zfpf->one_shot_select_1s('t0user_facility', $Conditions);
    if ($RR['t0user_facility'] > 0) foreach ($_SESSION['SelectResults']['t0user_facility'] as $K => $V) {
        $SelectedUserJobInfo = $Zfpf->user_job_info_1c($V['k0user']);
        if (isset($_POST['LogonRevokedUsersOnly'])) {
            if ($SelectedUserJobInfo['TimeLogonRevoked'] == '[Nothing has been recorded in this field.]')
                unset($_SESSION['SelectResults']['t0user_facility'][$K]);
            else {
                $DisplayUserInfo[$K] = $SelectedUserJobInfo['NameTitle'].', '.$SelectedUserJobInfo['WorkEmail'];
                $SortUserInfo[$K] = $Zfpf->to_lower_case_1c($DisplayUserInfo[$K]);
            }
        }
        else {
            if ($SelectedUserJobInfo['TimeLogonRevoked'] != '[Nothing has been recorded in this field.]')
                unset($_SESSION['SelectResults']['t0user_facility'][$K]);
            else {
                $DisplayUserInfo[$K] = $SelectedUserJobInfo['NameTitle'].', '.$SelectedUserJobInfo['WorkEmail'];
                $SortUserInfo[$K] = $Zfpf->to_lower_case_1c($DisplayUserInfo[$K]);
            }
        }
    }
    if (isset($_POST['LogonRevokedUsersOnly']))
        $Message = '<h2>
        Users associated with the facility with revoked logon credentials.</h2>';
    else
        $Message = '<h2>
        Users associated with the facility and with active logon credentials.</h2>';
    if (isset($SortUserInfo)) {
        array_multisort($SortUserInfo, $DisplayUserInfo, $_SESSION['SelectResults']['t0user_facility']);
        $Message .= '
        <form action="user_facility_io03.php" method="post"><p>';
        foreach ($_SESSION['SelectResults']['t0user_facility'] as $K => $V) {
            if ($K)
                $Message .= '<br />';
            $Message .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if (!$K)
                $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
            $Message .= '/>'.$DisplayUserInfo[$K];
        }
        $Message .= '</p><p>
            <input type="submit" name="user_facility_o1" value="View user-facility record" /></p>
        </form>';
    }
    else
        $Message .= '<p><b>
        None found</b> for the current facility. Please contact your supervisor if this seems amiss.</p>';
    if (!isset($_POST['LogonRevokedUsersOnly']))
        $Message .= '
        <form action="user_facility_io03.php" method="post"><p>
            Click below to associate a user with the selected facility. This creates a new user-facility record.<br />
            <input type="submit" name="user_facility_i0n0" value="New user-facility record" /></p>
        </form>
        <form action="user_facility_i0m.php" method="post"><p>
            <input type="submit" name="LogonRevokedUsersOnly" value="Revoked logon-credetials users" /></p>
        </form>';
    else
        $Message .= '
        <form action="user_facility_i0m.php" method="post"><p>
            <input type="submit" value="Active logon-credetials users" /></p>
        </form>';
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).'</h1>
    '.$Message.'
    <form action="administer1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

$Zfpf->catch_all_1c('administer1.php'); // Shouldn't be needed here, keep as safeguard against future mistakes.

$Zfpf->save_and_exit_1c();
    
