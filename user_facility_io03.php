<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the user_facility input and output HTML forms, except the:
//  - i0m file for listing existing records (and giving the option to start a new record)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

// General security check.
// An owner must be must be selected if a facility is selected.
// A contractor will have set $_SESSION['StatePicked']['t0owner'] but not $_SESSION['t0user_owner']
if (!isset($_SESSION['StatePicked']['t0owner']) or !isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_facility_i0m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

function display_privileges_p($Zfpf, $DBMSresource, $SelectedUser, $CascadeUp = FALSE) {
    $PrivilegesText = '';
    if ($CascadeUp) {
        $ConditionsUP[] = array('k0user', '=', $_SESSION['Selected']['k0user'], 'AND (');
        if (isset($SelectedUser['t0employer']['k0contractor']))
            $ConditionsUP[] = array('k0contractor', '=', $SelectedUser['t0employer']['k0contractor'], 'OR');
        $ConditionsUP[] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner'], 'OR');
        $ConditionsUP[] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility'], 'OR');
    }
    else {
        $ConditionsUP[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
        $ConditionsUP[1] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility'], 'OR', 'AND (');
    }
    $Conditions[0] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility']);
    list($SRFP, $RRFP) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_process', $Conditions);
    if ($RRFP) foreach ($SRFP as $VFP) {
        $ConditionsUP[] = array('k0process', '=', $VFP['k0process'], 'OR');
        $Conditions[0] = array('k0process', '=', $VFP['k0process']);
        list($SRP, $RRP) = $Zfpf->select_sql_1s($DBMSresource, 't0process', $Conditions, array('k0user_of_leader', 'c5name', 'c6description'));
        if ($RRP != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRP);
        $Conditions[1] = array('k0user', '=', $_SESSION['Selected']['k0user'], '', 'AND');
        list($SRUP, $RRUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions, array('c5p_process', 'c5p_user'));
        unset($Conditions);
        if ($RRUP > 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUP);
        if ($RRUP == 1) {
            if ($_SESSION['Selected']['k0user'] == $SRP[0]['k0user_of_leader'])
                $IsLeader = 'Yes';
            else
                $IsLeader = 'No';
            $NameDescription = $Zfpf->entity_name_description_1c($SRP[0], 100, FALSE);
            $PrivilegesText .= '<p>
            '.$NameDescription.':<br />
            - PSM Leader: '.$IsLeader.'<br />
            - Process: '.$Zfpf->decrypt_1c($SRUP[0]['c5p_process']).'<br />
            - User-process (and applicable facility and owner <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>): '.$Zfpf->decrypt_1c($SRUP[0]['c5p_user']).'</p>';
        }
    }
    $CountCUP = count($ConditionsUP);
    $LastArrayKey = $CountCUP - 1;
    if (!$CascadeUp and $CountCUP == 2) {
            $ConditionsUP[1][3] = '';
            $ConditionsUP[1][4] = 'AND';
        }
    else
        $ConditionsUP[$LastArrayKey][3] = ')';
    list($SRUPractice, $RRUPractice) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $ConditionsUP, array('k0practice', 'c5p_practice'));
    if ($RRUPractice) {
        foreach ($SRUPractice as $KUPractice => $VUPractice) {
            $Conditions[0] = array('k0practice', '=', $VUPractice['k0practice']);
            list($SRPractice, $RRPractice) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions, array('c5name', 'c5number'));
            if ($RRPractice != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRPractice);
            $PracticeNumber[$KUPractice] = $Zfpf->decrypt_1c($SRPractice[0]['c5number']);
            $PracticeName[$KUPractice] = $Zfpf->decrypt_1c($SRPractice[0]['c5name']);
        }
        array_multisort($PracticeNumber, $PracticeName, $SRUPractice); // sort by t0practice:c5number
        $PrivilegesText .= '<p>
        The selected user\'s process-wide';
        if ($CascadeUp)
            $PrivilegesText .= ', facility-wide, owner-wide, and any contractor-wide';
        else
            $PrivilegesText .= ' and facility-wide';
        $PrivilegesText .= ' privileges at the selected facility and its processes, listed above:';
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
        foreach ($SRUPractice as $KUPractice => $VUPractice)
            $PrivilegesText .= '<br />
            - '.$PracticeName[$KUPractice].': '.$UserZfpf->raw_practice_priv_means($Zfpf, $Zfpf->decrypt_1c($VUPractice['c5p_practice']), $SelectedUser);
        $PrivilegesText .= '</p>';
    }
    return $PrivilegesText;
}

// Cleanup
if (isset($_SESSION['Scratch']['OldSelected']))
    unset($_SESSION['Scratch']['OldSelected']);

// Get current-user information...
$CurrentUser = $Zfpf->current_user_info_1c();

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'k0user' => array('User (the individual accessing facility information)', ''),
    'k0union' => array('Union or other collective-bargaining representative of the user, if any', ''),
    'c5p_facility' => array('<a id="c5p_facility"></a><b>Privileges with this Facility</b>. These cannot be higher than the user\'s global database management system (DBMS) privileges.<br /><br />Facility-wide <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>, facility summary, and community/local emergency-planning committees/organization (LEPC). Full privileges to update these and insert new practices. (Only owner admins can insert and delete a facility)', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MAX_PRIVILEGE_OPTIONS_ZFPF']),
    'c5p_union' => array('Union records. Full privileges to insert new and update', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MAX_PRIVILEGE_OPTIONS_ZFPF']),
    'c5p_user' => array('User-facility and user-practice records. Full privileges to associate users with (and separate them from) this facility and all PSM <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> applicable to it, including owner-wide, facility-wide, and (for processes at the facility) process-wide compliance practices', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MAX_PRIVILEGE_OPTIONS_ZFPF']),
    'c5p_process' => array('Process records. Full privileges to insert new and update process descriptions, associate processes with the facility, and separate them from the facility', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MAX_PRIVILEGE_OPTIONS_ZFPF'])
);

//Left hand Table of contents
if (isset($_POST['user_facility_o1']) or isset($_POST['user_facility_o1_from']) or isset($_POST['user_facility_i0n']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c5p_facility' => 'Privileges'
    );

if (isset($_POST['user_facility_i0n0'])) {
	// Additional security check.
    if (!isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['t0user_facility']) or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $CurrentUser['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF) // Same criteria as for displaying i0n button in user_facility_i0m.php
        $Zfpf->send_to_contents_1c(); // Don't eject
    $RadioButtons = '';
    $PotentialUsersArray = array();
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    if (isset($_SESSION['t0user_owner'])) {
        // Get employees not already associated with facility
        $Conditions[0] = array('k0owner', '=', $_SESSION['t0user_owner']['k0owner']);
        list($SRUO, $RRUO) = $Zfpf->select_sql_1s($DBMSresource, 't0user_owner', $Conditions, array('k0user'));
        list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions, array('k0contractor'));
        $Counter = 0;
        $Conditions[0] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility'], 'AND');
        if ($RRUO) foreach ($SRUO as $VUO) {
            $Conditions[1] = array('k0user', '=', $VUO['k0user']);
            list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions, array('k0user'));
            if (!$RRUF) {
                $PotentialUsersArray[] = $VUO['k0user'];
                $PotentialUserInfo = $Zfpf->user_job_info_1c($VUO['k0user']);
                if (!$Counter)
                    $RadioButtons .= '<p>
                    <b>'.$PotentialUserInfo['Employer'].'</b><br />';
                $_SESSION['Scratch']['PlainText']['PotentialUser'][$Counter] = $VUO['k0user'];
                $RadioButtons .= '<input type="radio" name="potential_user" value="'.$Counter++.'" />'.$PotentialUserInfo['NameTitle'].', '.$PotentialUserInfo['WorkEmail'].'<br />';
            }
        }
        // Get contractor individuals not already associated with facility
        if ($RROC) foreach ($SROC as $VOC) {
            unset($Conditions);
            $Conditions[0] = array('k0contractor', '=', $VOC['k0contractor']);
            list($SRUC, $RRUC) = $Zfpf->select_sql_1s($DBMSresource, 't0user_contractor', $Conditions, array('k0user'));
            $i = 0;
            $Conditions[0] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility'], 'AND');
            if ($RRUC) foreach ($SRUC as $VUC) {
                $Conditions[1] = array('k0user', '=', $VUC['k0user']);
                list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions, array('k0user'));
                if (!$RRUF and !in_array($VUC['k0user'], $PotentialUsersArray)) {
                    $PotentialUsersArray[] = $VUC['k0user'];
                    $PotentialUserInfo = $Zfpf->user_job_info_1c($VUC['k0user']);
                    if (!$i)
                        $RadioButtons .= '<p>
                        <b>'.$PotentialUserInfo['Employer'].'</b><br />';
                    $_SESSION['Scratch']['PlainText']['PotentialUser'][$Counter] = $VUC['k0user'];
                    $RadioButtons .= '<input type="radio" name="potential_user" value="'.$Counter++.'" />'.$PotentialUserInfo['NameTitle'].', '.$PotentialUserInfo['WorkEmail'].'<br />';
                    $i++;
                }
            }
        }
    }
    elseif (isset($_SESSION['t0user_contractor'])) {
        $Conditions[0] = array('k0contractor', '=', $_SESSION['t0user_contractor']['k0contractor']);
        list($SRUC, $RRUC) = $Zfpf->select_sql_1s($DBMSresource, 't0user_contractor', $Conditions, array('k0user'));
        $Counter = 0;
        $Conditions[0] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility'], 'AND');
        if ($RRUC) foreach ($SRUC as $VUC) {
            $Conditions[1] = array('k0user', '=', $VUC['k0user']);
            list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions, array('k0user'));
            if (!$RRUF) {
                $PotentialUserInfo = $Zfpf->user_job_info_1c($VUC['k0user']);
                if (!$Counter)
                    $RadioButtons .= '<p>
                    <b>'.$PotentialUserInfo['Employer'].'</b><br />';
                $_SESSION['Scratch']['PlainText']['PotentialUser'][$Counter] = $VUC['k0user'];
                $RadioButtons .= '<input type="radio" name="potential_user" value="'.$Counter++.'" />'.$PotentialUserInfo['NameTitle'].', '.$PotentialUserInfo['WorkEmail'].'<br />';
            }
        }
    }
    else // Cannot be app admin looking for all users here.
        $Zfpf->send_to_contents_1c(); // Don't eject
    $Zfpf->close_connection_1s($DBMSresource);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Users not associated with the facility, who are employees of the facility\'s owner or one of its contractors</h2><p>
    <b>Selected facility:</b> '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5country']).'</p>';
    if ($RadioButtons)
        echo '
        <form action="user_facility_io03.php" method="post">
            '.$RadioButtons.'</p><p>
            <input type="submit" name="user_facility_i0n" value="Associate user with facility" /></p>
        </form>';
    else
        echo '<p>
    None found.</p>';
    echo '
    <form action="user_facility_i0m.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_POST['user_facility_i0n'])) {
	// Additional security check.
    if (!isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['t0user_facility']) or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $CurrentUser['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF) // Same criteria as for displaying i0n button in user_facility_i0m.php
        $Zfpf->send_to_contents_1c(); // Don't eject
    $CheckedPost = $Zfpf->post_length_blank_1c('potential_user');
    if (!is_numeric($CheckedPost) or !isset($_SESSION['Scratch']['PlainText']['PotentialUser'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // Initialize $_SESSION['Selected']
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $_SESSION['Selected'] = array(
        'k0user_facility' => time().mt_rand(1000000, 9999999),
        'k0user' => $_SESSION['Scratch']['PlainText']['PotentialUser'][$CheckedPost],
        'k0facility' => $_SESSION['t0user_facility']['k0facility'],
        'k0union' => 0,
        'c5p_facility' => $EncryptedNothing,
        'c5p_union' => $EncryptedNothing,
        'c5p_user' => $EncryptedNothing,
        'c5p_process' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
    unset($_SESSION['Scratch']['PlainText']['PotentialUser']);
}

// history_o1 code
if (isset($_POST['user_facility_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0user_facility']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0user_facility', $_SESSION['Selected']['k0user_facility']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one user-facility record', 'user_facility_io03.php', 'user_facility_o1'); // This echos and exits.
}

// o1 code
if (isset($_POST['user_facility_o1'])) {
	// Additional security check.
    if (!isset($_SESSION['Selected']['k0user_facility']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0user_facility'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    if (!isset($_SESSION['Selected']['k0user_facility'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0user_facility'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0user_facility'][$CheckedPost];
        unset($_SESSION['SelectResults']);
        // Verify current user has t0user_facility entry for selected facility. Only a concern in this if clause, see user_facility_i0m.php.
        $Conditions[0] = array('k0facility', '=', $_SESSION['Selected']['k0facility'], 'AND');
        $Conditions[1] = array('k0user', '=', $_SESSION['t0user']['k0user']);
        list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions);
        unset($Conditions);
        if ($RRUF != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // Below may not be set in some cases from user_facility_i0m.php
        if (!isset($_SESSION['t0user_facility']))
            $_SESSION['t0user_facility'] = $SRUF[0];
        if (!isset($_SESSION['StatePicked']['t0facility'])) {
            $Conditions[0] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility']);
            list($SRF, $RRF) = $Zfpf->select_sql_1s($DBMSresource, 't0facility', $Conditions);
            if ($RRF != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['StatePicked']['t0facility'] = $SRF[0];
        }
    }
    $Zfpf->clear_edit_lock_1c();
    $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']); // Refresh in case the database was updated.
    // Verify the current user and the selected user both have the same selected facility.
    if ($_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'])
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
    // Check c5p_user privileges with selected owner and facility. Owner-wide user-update privileges cascade down to facility.
    $UpdateUser = FALSE;
    if (((isset($_SESSION['t0user_owner']) and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) == MAX_PRIVILEGES_ZFPF) or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) == MAX_PRIVILEGES_ZFPF) and $CurrentUser['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF)
        $UpdateUser = TRUE;
	// Handle k0 field(s)
    $Display['k0user'] = $SelectedUser['NameTitleEmployerWorkEmail'];
    if ($_SESSION['Selected']['k0union']) {
        $Conditions[0] = array('k0union', '=', $_SESSION['Selected']['k0union']);
        list($SRUnion, $RRUnion) = $Zfpf->select_sql_1s($DBMSresource, 't0union', $Conditions);
        if ($RRUnion != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Display['k0union'] = $Zfpf->entity_name_description_1c($SRUnion[0], 100, FALSE); // Don't bold union name.
    }
    else
        $Display['k0union'] = '[Nothing has been recorded in this field.]';
    if ($UpdateUser)
        $Display['k0union'] .= '<br />
        <input type="submit" name="change_union_info_1" value="Change user-union relationship" />';
	echo $Zfpf->xhtml_contents_header_1c().'<h2>
    User-facility Record</h2><p>
    <b>Selected <a class="toc" href="glossary.php#facility" target="_blank">facility</a>:</b> '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5country']).'</p>
    <form action="user_facility_io03.php" method="post">
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'user_facility_io03.php', $_SESSION['Selected'], $Display).'<p>
    <b>Global Database Management System (DBMS) Privileges:</b><br />
    '.$Zfpf->decrypt_1c($SelectedUser['t0user']['c5p_global_dbms']).'</p>';
    $PrivilegesText = display_privileges_p($Zfpf, $DBMSresource, $SelectedUser, TRUE);
    $Zfpf->close_connection_1s($DBMSresource);
    if ($PrivilegesText)
        echo '<p>
        <b>Process and compliance-practice privileges at the selected facility.</b></p>
        '.$PrivilegesText;
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    elseif ($UpdateUser) {
        echo '<p>
            <input type="submit" name="user_facility_o1_from" value="Update user-facility record" /></p><p>
            Update facility-wide user-practice privileges<br />
            <input type="submit" name="u_p_i1" value="Update privileges" /></p>';
        if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user'])
            echo '<p>
            End selected user\'s access via this app to information of the facility, its processes, and their <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>.<br />
            <input type="submit" name="separate_user_1" value="End information access" /></p>';
        else
            echo'<p><b>
            No Self Privilege-Change Notice</b>: Users cannot end their own privileges to access all facility information.</p>';
    }
    else {
        echo '<p><b>
        User-Update Privileges Notice</b>: You don\'t have user-update privileges for this facility, if you need to edit these user records, please contact your supervisor or an app administrator.</p>';
        if ($CurrentUser['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '
    </form>
    <form action="user_process_i0m.php" method="post"><p>
        Update (if allowed) process privileges<br />
        <input type="submit" value="Update privileges" /></p>
    </form>
    <form action="user_facility_io03.php" method="post"><p>
        <input type="submit" name="user_facility_history_o1" value="History of this record" /></p>
    </form>
    <form action="user_facility_i0m.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// user_practice, separate_user, i1, i2, i3 code
if (isset($_SESSION['Selected']['k0user_facility'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0user_facility', '=', $_SESSION['Selected']['k0user_facility']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0user_facility', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    // Additional security check
    // Check if current user is with the same facility as the selected employee and if so check current user's privileges.
    if (!isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['t0user_facility']) or $_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'] or ((!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF) and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) != MAX_PRIVILEGES_ZFPF) or $CurrentUser['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject

    // Get useful variables.
    $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
    $SelectedUserGlobalDBMSPriv = $Zfpf->decrypt_1c($SelectedUser['t0user']['c5p_global_dbms']);
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);

    if (isset($_POST['u_p_i1']) or isset($_POST['u_p_none_view']) or isset($_POST['u_p_all_view']) or isset($_POST['u_p_all_edit']) or isset($_POST['u_p_modify_confirm_post_1e']) or isset($_POST['u_p_undo_confirm_post_1e'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        if (!isset($_POST['u_p_modify_confirm_post_1e']))
            $_SESSION['Scratch']['EUP'] = $UPZfpf->eup_arrays($Zfpf, $_SESSION['Selected']['k0user'], 'facility', $_SESSION['Selected']['k0facility']);
        $Scope = 'with <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> of:<br />
        * facility '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']);
        $HTML = $UPZfpf->u_p_i1($Zfpf, $SelectedUser, 'facility', $Scope, $_SESSION['Scratch']['EUP']); // Call before header echo to set left-hand contents in $_SESSION
        echo $Zfpf->xhtml_contents_header_1c().$HTML.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['u_p_i2'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        $Scope = 'with <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> of:<br />
        * facility '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']);
        $HTML = $UPZfpf->u_p_i2($Zfpf, $SelectedUser, 'facility', $Scope, $_SESSION['Scratch']['EUP']);
        echo $Zfpf->xhtml_contents_header_1c().$HTML.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['u_p_i3'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        $UPZfpf->u_p_i3($Zfpf);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Privileges Updated</h2><p>
        The user-practice privileges you input and reviewed have been recorded.</p>
        <form action="user_facility_io03.php" method="post"><p>
            <input type="submit" name="user_facility_o1" value="Back to user-facility record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['u_p_history'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        $EntityArray = array('facility' => $_SESSION['Selected']['k0facility']);
        $EUPH = $UPZfpf->eup_history_array($Zfpf, $_SESSION['Selected']['k0user'], $EntityArray);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
        $HistoryGetZfpf = new HistoryGetZfpf;
        $Heading = 'History of user-practice privileges';
        $Scope = '<p>
        Privileges of user:<br />
        * '.$SelectedUser['NameTitleEmployerWorkEmail'].'<br />
        with <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> of:<br />
        * facility '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).'</p>';
        $HistoryGetZfpf->selected_changes_html_h($Zfpf, $EUPH, count($EUPH), $Heading, 'user_facility_io03.php', 'u_p_i1', $Scope); // This echos and exits.
    }

    if (isset($_POST['separate_user_1'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        if ($_SESSION['Selected']['k0user'] == $_SESSION['StatePicked']['t0facility']['k0user_of_leader'])
            $IsLeader = 'Yes';
        else
            $IsLeader = 'No';
        $PrivilegesText = '<p>
        At selected facility:<br />
        - PSM Leader: '.$IsLeader.'<br />
        - Facility: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5p_facility']).'<br />
        - Union: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5p_union']).'<br />
        - User-facility (and subordinate <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>): '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5p_user']).'<br />
        - Process: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5p_process']).'</p>';
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $PrivilegesText .= display_privileges_p($Zfpf, $DBMSresource, $SelectedUser);
        $Zfpf->close_connection_1s($DBMSresource);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        End selected user\'s access to facility information via this app.</h2><p>
        <b>Selected <a class="toc" href="glossary.php#facility" target="_blank">facility</a>:</b> '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5country']).'</p><p>
        End access for <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b>, via this app, to the information of the selected facility, its processes, and their <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>.</p><p>
        The app cannot assign a new PSM leader. You need to do this, if you plan to end the access of a PSM leader for a facility, a process, or any ongoing PHA or HIRA, incident investigation, or PSM audit.</p><p>
        The above user has the following privileges (which they will lose) and leadership roles (which will need to be filled).</p>
        '.$PrivilegesText.'
        <form action="user_facility_io03.php" method="post"><p>
            <input type="submit" name="separate_user_2" value="End information access" /></p><p>
            <input type="submit" name="user_facility_o1" value="Take no action -- go back" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['separate_user_2'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
        $LeadersNeeded = $UserZfpf->separate_user($Zfpf, 'facility');
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Confirmation.</h2><p>
        The app attempted to end access, via this app, to the selected user\'s access to the information of the selected facility, its processes, and their <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>.</p><p>
        <b>Selected <a class="toc" href="glossary.php#facility" target="_blank">facility</a>:</b> '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5country']).'</p><p>
        <b>Selected user:</b> '.$SelectedUser['NameTitleEmployerWorkEmail'].'</p>';
        if ($LeadersNeeded)
            echo '<p>
            '.$LeadersNeeded.'</p><p>
            '.$SelectedUser['Name'].' remains the recorded PSM leader for these.</p>';
        echo '<p>
        You can confirm this via the user history records.</p>
        <form action="user_h_o1.php?facility" method="post"><p>
            <input type="submit" value="User-facility history" /></p>
        </form>
        <form action="user_facility_i0m.php" method="post"><p>
            <input type="submit" value="Back to user list" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    if (isset($_POST['change_union_info_1'])) {
        $RadioButtons = '';
        $Conditions[0] = array('k0facility', '=', $_SESSION['Selected']['k0facility']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        list($SRFUnion, $RRFUnion) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_union', $Conditions);
        if ($RRFUnion) foreach ($SRFUnion as $K => $V) {
            $Conditions[0] = array('k0union', '=', $V['k0union']);
            list($SRUnion, $RRUnion) = $Zfpf->select_sql_1s($DBMSresource, 't0union', $Conditions);
            if ($RRUnion != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $NameDescription = $Zfpf->entity_name_description_1c($SRUnion[0]);
            $_SESSION['Scratch']['PlainText']['Unions'][$K] = $V['k0union'];
            $RadioButtons .= '<input type="radio" name="unions" value="'.$K.'" ';
            if (!$K)
                $RadioButtons .= 'checked="checked" '; // Select the first radio button by default
            $RadioButtons .= '/>'.$NameDescription.'<br />';
        }
        $Zfpf->close_connection_1s($DBMSresource);
        $ExtraMessage = '<p>
        A facility admin with update-union privileges may create or associate a union record with the facility under <a class="toc" href="administer1.php">Administration</a> -- Facility Options -- Union (collective-bargaining representative) records.</p>';
        $Message = '<p>
        <b>Unions (collective-bargaining representatives) associated with the facility.</b></p>';
        if ($RadioButtons)
            $Message .= '
            <form action="user_facility_io03.php" method="post"><p>
                '.$RadioButtons.'</p>
                '.$ExtraMessage.'<p>
                <input type="submit" name="change_union_info_2" value="Change user-union relationship" /></p>
            </form>';
        else
            $Message .= '<p>
            None found.</p>
            '.$ExtraMessage;
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Change User-Union Relationship</h2>
        '.$Message.'<p>
        <form action="user_facility_io03.php" method="post"><p>
            <input type="submit" name="user_facility_o1" value="Take no action -- go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['change_union_info_2'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('unions');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['Scratch']['PlainText']['Unions'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Changes['k0union'] = $_SESSION['Scratch']['PlainText']['Unions'][$CheckedPost];
        if ($Changes['k0union'] == $_SESSION['Selected']['k0union'])
            $Message = '<p>
            You didn\'t make a change. You selected the currently recorded user-union relationship.</p>
            <form action="user_facility_io03.php" method="post"><p>
                <input type="submit" name="change_union_info_1" value="Try again" /></p>
            </form>';
        else {
            $Conditions[0] = array('k0user_facility', '=', $_SESSION['Selected']['k0user_facility']);
            $ShtmlFormArray['k0union'] = $htmlFormArray['k0union']; // A shortened htmlFormArray with only the field being changed, for t0history.
            $Affected = $Zfpf->one_shot_update_1s('t0user_facility', $Changes, $Conditions, TRUE, $ShtmlFormArray);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
            $_SESSION['Selected']['k0union'] = $Changes['k0union'];
            $Message = '<p>
            The app attempted to update the user-union relationship.</p>';
        }
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Result</h2>
        '.$Message.'
        <form action="user_facility_io03.php" method="post"><p>
            <input type="submit" name="user_facility_o1" value="Back to user-facility record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
}

    // i1, i2, i3 code
    // Reduce privileges dropdown menu options based on global DBMS privileges.
    if ($SelectedUserGlobalDBMSPriv != MAX_PRIVILEGES_ZFPF) {
        $htmlFormArray['c5p_facility'] = array('<b>Privileges with this Facility</b>. These cannot be higher than the user\'s global database management system (DBMS) privileges.<br /><br />Facility-wide <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>, facility summary, and community/local emergency-planning committees/organization (LEPC). <b>None allowed</b> because user\'s global DBMS privileges are less than '.MAX_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
        $htmlFormArray['c5p_union'] = array('Union records. <b>None allowed</b> because user\'s global DBMS privileges are less than '.MAX_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
        $htmlFormArray['c5p_user'] = array('User-facility and user-practice records. <b>None allowed</b> because user\'s global DBMS privileges are less than '.MAX_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
        $htmlFormArray['c5p_process'] = array('Process records. <b>None allowed</b> because user\'s global DBMS privileges are less than '.MAX_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
    }
    if (isset($_POST['user_facility_o1_from']))
        $Zfpf->edit_lock_1c('user_facility'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error.
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['user_facility_i0n']) or isset($_POST['user_facility_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        $Display['k0user'] = $SelectedUser['NameTitleEmployerWorkEmail'];
        if ($_SESSION['Selected']['k0union']) {
            $Conditions[0] = array('k0union', '=', $_SESSION['Selected']['k0union']);
            list($SRUnion, $RRUnion) = $Zfpf->one_shot_select_1s('t0union', $Conditions);
            if ($RRUnion != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $Display['k0union'] = $Zfpf->entity_name_description_1c($SRUnion[0], 100, FALSE); // Don't bold union name.
        }
        else
            $Display['k0union'] = '[Nothing has been recorded in this field.]';
        // To standardize, always save possibly modified htmlFormArray, SelectDisplay, and (to simplify code below) placeholder for user's post.
        $_SESSION['Scratch']['htmlFormArray'] = $Zfpf->encode_encrypt_1c($htmlFormArray);
        $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($Display);
        $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
    }
    // 1.2 $_SESSION['Scratch']['SelectDisplay'] is source of $Display, the version generated from the database record.
    elseif (isset($_POST['undo_confirm_post_1e']) and isset($_SESSION['Scratch']['SelectDisplay'])) {
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
    }
    // 1.3 $_SESSION['Post'] is source of $Display, the latest user-modified version.
    elseif (isset($_SESSION['Post']) and isset($_POST['modify_confirm_post_1e']))
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        // Create HTML form
        // To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        User-facility Record</h2><p>
        <b>Selected <a class="toc" href="glossary.php#facility" target="_blank">facility</a>:</b> '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5country']).'</p>
        <form action="user_facility_io03.php" method="post" enctype="multipart/form-data" >';
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
        <b>Global Database Management System (DBMS) Privileges:</b><br />
        '.$SelectedUserGlobalDBMSPriv.'</p><p>
            <input type="submit" name="user_facility_i2" value="Review what you typed into form" /></p>
        </form>';
        if ($who_is_editing == '[A new database row is being created.]')
            echo '
            <form action="user_facility_i0m.php" method="post"><p>
                <input type="submit" value="Discard what you just typed" /></p>
            </form>';
        else
            echo '
            <form action="user_facility_io03.php" method="post"><p>
                <input type="submit" name="user_facility_o1" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['user_facility_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('user_facility_io03.php', 'user_facility_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]')
            $Zfpf->insert_sql_1s($DBMSresource, 't0user_facility', $ChangedRow);
        else {
            $Conditions[0] = array('k0user_facility', '=', $_SESSION['Selected']['k0user_facility']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_facility', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Record(s) Updated</h2><p>
        The user-facility information you input and reviewed has been recorded.</p>
        <form action="user_facility_io03.php" method="post"><p>
            <input type="submit" name="user_facility_o1" value="Back to record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends user_practice, separate_user, i1, i2, i3 code

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

