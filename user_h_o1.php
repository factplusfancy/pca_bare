<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This o1 file outputs an HTML form to select an user associated with an entity and then display, if applicable:
// what the user has done to the entity's records in the app or 
// what was done to the user's records by others.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

// General security check.
// Same privileges needed to get link from administer1.php, user_owner_io03.php, user_contractor..., user_facility..., or user_process...
// Also, discern between an privileges of current user and give them the choice with the maximum information.
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
    $Zfpf->send_to_contents_1c(); // Don't eject
if (isset($_GET['process']) or (isset($_SESSION['Scratch']['PlainText']['SecurityToken']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] == 'user_h_o1.php?process')) {
    if (!isset($_SESSION['t0user_facility']) or !isset($_SESSION['t0user_process']) or ((!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF) and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) != MAX_PRIVILEGES_ZFPF and $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_user']) != MAX_PRIVILEGES_ZFPF))
        $Zfpf->send_to_contents_1c(); // Don't eject
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_h_o1.php?process';
    $EntityName = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['t0user_process']['k0process'])['AEFullDescription'];
    if (!$_POST)
        $Conditions = array(
            0 => array('k0_3rd_in_row_affected', '=', $_SESSION['t0user_process']['k0process'], 'AND'),
            1 => array('c2table_name', '=', 't0user_process')
        );
}
elseif (isset($_GET['facility']) or (isset($_SESSION['Scratch']['PlainText']['SecurityToken']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] == 'user_h_o1.php?facility')) {
    if (!isset($_SESSION['t0user_facility']) or ((!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF) and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) != MAX_PRIVILEGES_ZFPF))
        $Zfpf->send_to_contents_1c(); // Don't eject
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_h_o1.php?facility';
    $EntityName = $Zfpf->affected_entity_info_1c('Facility-wide', $_SESSION['t0user_facility']['k0facility'])['AEFullDescription'];
    if (!$_POST)
        $Conditions = array(
            0 => array('k0_3rd_in_row_affected', '=', $_SESSION['t0user_facility']['k0facility'], 'AND'),
            1 => array('c2table_name', '=', 't0user_facility')
        );
}
elseif (isset($_SESSION['t0user_owner'])) {
    if ($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    $EntityName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
    if (!$_POST)
        $Conditions = array(
            0 => array('k0_3rd_in_row_affected', '=', $_SESSION['t0user_owner']['k0owner'], 'AND'),
            1 => array('c2table_name', '=', 't0user_owner')
        );
}
elseif (isset($_SESSION['t0user_contractor'])) { 
    if ($Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_user']) != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    $EntityName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']);
    if (!$_POST)
        $Conditions = array(
            0 => array('k0_3rd_in_row_affected', '=', $_SESSION['t0user_contractor']['k0contractor'], 'AND'),
            1 => array('c2table_name', '=', 't0user_contractor')
        );
}
else
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// i0m equivalent code
if (!$_POST) {
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0history', $Conditions);
    if ($RR > 0) {
        $_SESSION['SelectResults']['PlainText'] = array(); // Will be numeric array of k0user keys, initialize for in_array() search below.
        $i = 0;
        foreach ($SR as $V) {
            if (!in_array($V['k0_2nd_in_row_affected'], $_SESSION['SelectResults']['PlainText'])) {
                $_SESSION['SelectResults']['PlainText'][$i] = $V['k0_2nd_in_row_affected'];
                $UserJobInfo = $Zfpf->user_job_info_1c($V['k0_2nd_in_row_affected']); // k0user is 2nd k0 field in t0user_owner and t0user_contractor
                if ($UserJobInfo['t0user_employer']) // Evaluates true if the user is currently associated with an owner or a contractor, and false otherwise.
                    $DisplayUserInfo[$i] = $UserJobInfo['NameTitle'].', '.$UserJobInfo['WorkEmail']; // Logon revoked time appended to name by CoreZfpf::user_job_info_1c which calls full_name_1c.
                else
                    $DisplayUserInfo[$i] = $UserJobInfo['Name'].', separated from '.$EntityName;
                $SortUserInfo[$i] = $Zfpf->to_lower_case_1c($DisplayUserInfo[$i]);
                $i++;
            }
        }
    }
    $Message = '<h2>
    Users with a current or past association with '.$EntityName.'</h2>';
    if (isset($SortUserInfo)) {
        array_multisort($SortUserInfo, $DisplayUserInfo, $_SESSION['SelectResults']['PlainText']);
        $Message .= '
        <form action="user_h_o1.php" method="post"><p>';
        foreach ($_SESSION['SelectResults']['PlainText'] as $K => $V) {
            if ($K)
                $Message .= '<br />';
            $Message .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if (!$K)
                $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
            $Message .= '/>'.$DisplayUserInfo[$K];
        }
        $Message .= '</p>';
        if ($Conditions[1][2] == 't0user_owner' or $Conditions[1][2] == 't0user_contractor')
            $Message .= '<p>
            <input type="submit" name="by_user_h_o1" value="By user to all records" /></p><p>
            <input type="submit" name="to_user_h_o1" value="To user\'s records, by others" /></p>';
        elseif ($Conditions[1][2] == 't0user_facility')
            $Message .= '<p>
            <input type="submit" name="by_user_facility_h_o1" value="By user to facility records" /></p>';
        elseif ($Conditions[1][2] == 't0user_process')
            $Message .= '<p>
            <input type="submit" name="by_user_process_h_o1" value="By user to process records" /></p>';
        $Message .= '
        </form>';
    }
    else
        $Message .= '<p>
        <b>None found</b> for '.$EntityName.'. Please contact your supervisor if this seems amiss.</p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.'
    <form action="administer1.php" method="post"><p>
        <input type="submit" value="Back to administration" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_SESSION['SelectResults']['PlainText'])) {
    $CheckedPost = $Zfpf->post_length_blank_1c('selected');
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['PlainText'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $k0user = $_SESSION['SelectResults']['PlainText'][$CheckedPost];
    unset($_SESSION['SelectResults']);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    if (isset($_POST['by_user_h_o1'])) {
        if ((!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF) and 
            (!isset($_SESSION['t0user_contractor']) or $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_user']) != MAX_PRIVILEGES_ZFPF))
            $Zfpf->send_to_contents_1c(); // Don't eject
        list($SR, $RR) = $HistoryGetZfpf->by_user_h($Zfpf, $k0user);
        $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'By user -- history of one user\'s actions in app', 'user_h_o1.php'); // This echos and exits.
    }
    elseif (isset($_POST['to_user_h_o1'])) {
        if ((!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF) and 
            (!isset($_SESSION['t0user_contractor']) or $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_user']) != MAX_PRIVILEGES_ZFPF))
            $Zfpf->send_to_contents_1c(); // Don't eject
        list($SR, $RR) = $HistoryGetZfpf->to_user_h($Zfpf, $k0user);
        $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'To user -- history of changes to one user\'s records and privileges in app', 'user_h_o1.php'); // This echos and exits.
    }
    if (isset($_POST['by_user_facility_h_o1'])) {
        if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_h_o1.php?facility')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        list($SR, $RR) = $HistoryGetZfpf->by_user_facility_h($Zfpf, $k0user, $_SESSION['t0user_facility']['k0facility'], $_SESSION['StatePicked']['t0facility']['k0lepc']);
        $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'By user to facility records -- history of one user\'s actions', 'user_h_o1.php'); // This echos and exits.
    }
    elseif (isset($_POST['by_user_process_h_o1'])) {
        if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_h_o1.php?process')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        list($SR, $RR) = $HistoryGetZfpf->by_user_process_h($Zfpf, $k0user, $_SESSION['t0user_process']['k0process']);
        $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'By user to process records -- history of one user\'s actions', 'user_h_o1.php'); // This echos and exits.
    }
}

// User should have exited before reaching here. Use for extra security, compared to catch_all_1c, for protecting user history information.
$Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

$Zfpf->save_and_exit_1c();

