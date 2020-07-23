<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the user_process input and output HTML forms, except the:
//  - i0m file for listing existing records (and giving the option to start a new record)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();
    
// General security check.
// An owner and facility must be must be selected if a process is selected.
// A contractor will have set $_SESSION['StatePicked']['t0owner'] but not $_SESSION['t0user_owner']
if (!isset($_SESSION['t0user_facility']) or !isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['StatePicked']['t0owner']) or !isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_process_i0m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// This cascades up to facility-wide, owner-wide, or contractor-wide privileges as well as process-wide practices.
// Contractor-wide practice privileges are included here, but not in the user_practice (u_p) code below
// to allowed a user, with privileges, to view a contractor individual's contractor-wide practice privileges, but not to change them here.
// They may be changed by a contractor admin, from the contractor administrative options.
function display_privileges_practice($Zfpf, $DBMSresource, $SelectedUser, $ConditionsUP = FALSE) {
    $PrivilegesText = '<p><b>
        The selected user\'s process-wide';
    if (!$ConditionsUP) {
        $ConditionsUP[] = array('k0user', '=', $_SESSION['Selected']['k0user'], 'AND (');
        if (isset($SelectedUser['t0employer']['k0contractor'])) {
            $ConditionsUP[] = array('k0contractor', '=', $SelectedUser['t0employer']['k0contractor'], 'OR');
            $PrivilegesText .= ', facility-wide, owner-wide, and contractor-wide';
        }
        else
            $PrivilegesText .= ', facility-wide, and owner-wide';
        $ConditionsUP[] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner'], 'OR');
        $ConditionsUP[] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility'], 'OR');
        $ConditionsUP[] = array('k0process', '=', $_SESSION['t0user_process']['k0process'], ')');
    }
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
        $PrivilegesText .= ' practice privileges, at the selected process:</b>';
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
        foreach ($SRUPractice as $KUPractice => $VUPractice)
            $PrivilegesText .= '<br />
            - '.$PracticeName[$KUPractice].': '.$UserZfpf->raw_practice_priv_means($Zfpf, $Zfpf->decrypt_1c($VUPractice['c5p_practice']), $SelectedUser);
        $PrivilegesText .= '</p>';
    }
    else
        $PrivilegesText = '';
    return $PrivilegesText;
}

// Cleanup
if (isset($_SESSION['Scratch']['OldSelected']))
    unset($_SESSION['Scratch']['OldSelected']);

// Get current-user information...
$CurrentUser = $Zfpf->current_user_info_1c();

$htmlFormArray = array(
    'k0user' => array('User (the individual accessing process information)', ''),
    'c5name' => array('Role at this processes, summary', '', C5_MAX_BYTES_ZFPF),
    'c6description' => array('Role at this processes, details', '', C6SHORT_MAX_BYTES_ZFPF),
    'c5p_process' => array('<a id="c5p_process"></a><b>Privileges with this process</b>. These cannot be higher than the user\'s global database management system (DBMS) privileges.<br /><br />Process-wide <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> and process summary. Full privileges to update both and insert new practices. (Only facility admins can insert and delete a process)', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MAX_PRIVILEGE_OPTIONS_ZFPF']),
    'c5p_user' => array('User-process and user-practice records. Full privileges to associate users with (and separate them from) this process and all PSM <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> applicable to it, including owner-wide, facility-wide, and process-wide practices', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MAX_PRIVILEGE_OPTIONS_ZFPF'])
);

// Left-hand contents
if (isset($_POST['user_process_o1']) or isset($_POST['user_process_o1_from']) or isset($_POST['user_process_i0n']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c5p_process' => 'Privileges'
    );

if (isset($_POST['user_process_i0n0'])) {
	// Additional security check.
	if (!isset($_SESSION['StatePicked']['t0process']) or !isset($_SESSION['t0user_process']) or ((!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF) and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) != MAX_PRIVILEGES_ZFPF and $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_user']) != MAX_PRIVILEGES_ZFPF) or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF) // Minimum criteria for displaying i0n0 button in user_process_i0m.php
        $Zfpf->send_to_contents_1c(); // Don't eject
    $RadioButtons = '';
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    // Get users, associated with the facility, but not yet associated with the process.
    $Conditions[0] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility']);
    list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions, array('k0user'));
    $Counter = 0;
    $Conditions[0] = array('k0process', '=', $_SESSION['t0user_process']['k0process'], 'AND');
    if ($RRUF) foreach ($SRUF as $VUF) {
        $Conditions[1] = array('k0user', '=', $VUF['k0user']);
        list($SRUP, $RRUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions, array('k0user'));
        if (!$RRUP) {
            $PotentialUserInfo = $Zfpf->user_job_info_1c($VUF['k0user']);
            if (!$Counter)
                $RadioButtons .= '<p>
                <b>'.$PotentialUserInfo['Employer'].'</b><br />';
            $_SESSION['Scratch']['PlainText']['PotentialUser'][$Counter] = $VUF['k0user'];
            $RadioButtons .= '<input type="radio" name="potential_user" value="'.$Counter++.'" />'.$PotentialUserInfo['NameTitle'].', '.$PotentialUserInfo['WorkEmail'].'<br />';
        }
    }
    $Zfpf->close_connection_1s($DBMSresource);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Users associated with the facility but not with the process</h2><p>
    <b>Selected process:</b> '.$Zfpf->entity_name_description_1c($_SESSION['StatePicked']['t0process'], 100, FALSE).'</p>';
    if ($RadioButtons)
        echo '
        <form action="user_process_io03.php" method="post">
            '.$RadioButtons.'</p><p>
            Associate user with process by making a new user-process record<br />
            <input type="submit" name="user_process_i0n" value="Associate user with process" /></p>
        </form>';
    else
        echo '<p>
    None found.</p>';
    echo '
    <form action="user_process_i0m.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_POST['user_process_i0n'])) {
	// Additional security check.
    if (!isset($_SESSION['StatePicked']['t0process']) or !isset($_SESSION['t0user_process']) or ((!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF) and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) != MAX_PRIVILEGES_ZFPF and $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_user']) != MAX_PRIVILEGES_ZFPF) or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF) // Same criteria as user_process_i0n0
        $Zfpf->send_to_contents_1c(); // Don't eject
    $CheckedPost = $Zfpf->post_length_blank_1c('potential_user');
    if (!is_numeric($CheckedPost) or !isset($_SESSION['Scratch']['PlainText']['PotentialUser'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // Initialize $_SESSION['Selected']
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $_SESSION['Selected'] = array(
        'k0user_process' => time().mt_rand(1000000, 9999999),
        'k0user' => $_SESSION['Scratch']['PlainText']['PotentialUser'][$CheckedPost],
        'k0process' => $_SESSION['t0user_process']['k0process'],
        'c5name' => $EncryptedNothing,
        'c6description' => $EncryptedNothing,
        'c5p_process' => $EncryptedNothing,
        'c5p_user' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
    unset($_SESSION['Scratch']['PlainText']['PotentialUser']);
}

// history_o1 code
if (isset($_POST['user_process_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0user_process']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0user_process', $_SESSION['Selected']['k0user_process']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one user-process record', 'user_process_io03.php', 'user_process_o1'); // This echos and exits.
}

// o1 code
if (isset($_POST['user_process_o1'])) {
	// Additional security check.
    if (!isset($_SESSION['Selected']['k0user_process']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0user_process'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    if (!isset($_SESSION['Selected']['k0user_process'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0user_process'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0user_process'][$CheckedPost];
        unset($_SESSION['SelectResults']);
        // Verify current user has t0user_process entry for selected process. Only a concern in this if clause, see user_process_i0m.php.
        $Conditions[0] = array('k0process', '=', $_SESSION['Selected']['k0process'], 'AND');
        $Conditions[1] = array('k0user', '=', $_SESSION['t0user']['k0user']);
        list($SRUP, $RRUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions);
        unset($Conditions);
        if ($RRUP != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // Below may not be set in some cases from user_process_i0m.php
        if (!isset($_SESSION['t0user_process']))
            $_SESSION['t0user_process'] = $SRUP[0];
        if (!isset($_SESSION['StatePicked']['t0process'])) {
            $Conditions[0] = array('k0process', '=', $_SESSION['t0user_process']['k0process']);
            list($SRP, $RRP) = $Zfpf->select_sql_1s($DBMSresource, 't0process', $Conditions);
            if ($RRP != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['StatePicked']['t0process'] = $SRP[0];
        }
    }
    $Zfpf->clear_edit_lock_1c();
    $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']); // Refresh in case the database was updated.
    // Verify the current user and the selected user both have the same selected process.
    if ($_SESSION['t0user_process']['k0process'] != $_SESSION['Selected']['k0process'])
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
	// Handle k0 field(s)
    $Display['k0user'] = $SelectedUser['NameTitleEmployerWorkEmail'];
    // Check c5p_user privileges; owner-wide and facility-wide user-update privileges cascade down to process.
    $UpdateUser = FALSE;
    if (((isset($_SESSION['t0user_owner']) and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) == MAX_PRIVILEGES_ZFPF) or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) == MAX_PRIVILEGES_ZFPF or $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_user']) == MAX_PRIVILEGES_ZFPF) and $CurrentUser['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF)
        $UpdateUser = TRUE;
	echo $Zfpf->xhtml_contents_header_1c().'<h2>
        User-Process Record</h2><p>
        <b>Selected <a class="toc" href="glossary.php#process" target="_blank">process</a>:</b> '.$Zfpf->entity_name_description_1c($_SESSION['StatePicked']['t0process'], 100, FALSE).'</p>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'user_process_io03.php', $_SESSION['Selected'], $Display).'<p>
    <b>Global Database Management System (DBMS) Privileges:</b><br />
    '.$Zfpf->decrypt_1c($SelectedUser['t0user']['c5p_global_dbms']).'</p>';
    $PrivilegesText = display_privileges_practice($Zfpf, $DBMSresource, $SelectedUser);
    $Zfpf->close_connection_1s($DBMSresource);
    if ($PrivilegesText)
        echo '
        '.$PrivilegesText;
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    elseif ($UpdateUser) {
        echo '
        <form action="user_process_io03.php" method="post"><p>
            <input type="submit" name="user_process_o1_from" value="Update user-process record" /></p><p>
            Update all user-practice privileges applicable to the process, including owner-wide, facility-wide, and process-wide <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>.<br />
            <input type="submit" name="u_p_i1" value="Update practice privileges" /></p>';
        if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user'])
            echo '<p>
            End selected user\'s access via this app to information of the process and its <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>.<br />
            <input type="submit" name="separate_user_1" value="End information access" /></p>';
        else
            echo'<p><b>
                No Self Privilege-Change Notice</b>: Users cannot end their own privileges to access all process information.</p>';
        echo'
        </form>';
    }
    else {
        echo '<p><b>
        User-Update Privileges Notice</b>: You don\'t have user-update privileges for this process, if you need to update these user records, please contact your supervisor or an app administrator.</p>';
        if ($CurrentUser['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to update PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '
    <form action="user_process_io03.php" method="post"><p>
        <input type="submit" name="user_process_history_o1" value="History of this record" /></p>
    </form>
    <form action="user_process_i0m.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// user_practice, separate_user, i1, i2, i3 code
if (isset($_SESSION['Selected']['k0user_process'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0user_process', '=', $_SESSION['Selected']['k0user_process']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0user_process', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    // Additional security check
    // Check if current user is with the same process as the selected employee and if so check current user's privileges.
    if (!isset($_SESSION['StatePicked']['t0process']) or !isset($_SESSION['t0user_process']) or $_SESSION['t0user_process']['k0process'] != $_SESSION['Selected']['k0process'] or ((!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF) and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) != MAX_PRIVILEGES_ZFPF and $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_user']) != MAX_PRIVILEGES_ZFPF) or $CurrentUser['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    // Get useful variables.
    $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
    $SelectedUserGlobalDBMSPriv = $Zfpf->decrypt_1c($SelectedUser['t0user']['c5p_global_dbms']);
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    $ProcessNameDescription = $Zfpf->entity_name_description_1c($_SESSION['StatePicked']['t0process'], 100, FALSE);

    if (isset($_POST['u_p_i1']) or isset($_POST['u_p_none_view']) or isset($_POST['u_p_all_view']) or isset($_POST['u_p_all_edit']) or isset($_POST['u_p_modify_confirm_post_1e']) or isset($_POST['u_p_undo_confirm_post_1e'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        if (!isset($_POST['u_p_modify_confirm_post_1e'])) {
            $Process['EUP'] = $UPZfpf->eup_arrays($Zfpf, $_SESSION['Selected']['k0user'], 'process', $_SESSION['Selected']['k0process']);
            // SPECIAL CASE: get all user-practice privileges applicable to the process (except contractor-wide practices, which only a contractor admin can change.)
            $Facility['EUP'] = $UPZfpf->eup_arrays($Zfpf, $_SESSION['Selected']['k0user'], 'facility', $_SESSION['StatePicked']['t0facility']['k0facility']);
            $Owner['EUP'] = $UPZfpf->eup_arrays($Zfpf, $_SESSION['Selected']['k0user'], 'owner', $_SESSION['StatePicked']['t0owner']['k0owner']);
            $Practices = array_merge($Process['EUP']['P'], $Facility['EUP']['P'], $Owner['EUP']['P']);
            $UserPractices = array_merge($Process['EUP']['UP'], $Facility['EUP']['UP'], $Owner['EUP']['UP']);
            foreach ($Practices as $K => $V)
                $PracticeNumber[$K] = $Zfpf->decrypt_1c($V['c5number']);
            array_multisort($PracticeNumber, $Practices, $UserPractices); // sort by t0practice:c5number
            $_SESSION['Scratch']['EUP'] = array('P' => $Practices, 'UP' => $UserPractices);
        }
        $Scope = 'with <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> of:<br />
        * process '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0process']['c5name']).',<br />
        * facility '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', and<br />
        * owner '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        $HTML = $UPZfpf->u_p_i1($Zfpf, $SelectedUser, 'process', $Scope, $_SESSION['Scratch']['EUP']); // Call before header echo to set left-hand contents in $_SESSION
        echo $Zfpf->xhtml_contents_header_1c().$HTML.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['u_p_i2'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        $Scope = 'with <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> of:<br />
        * process '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0process']['c5name']).',<br />
        * facility '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', and<br />
        * owner '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        $HTML = $UPZfpf->u_p_i2($Zfpf, $SelectedUser, 'process', $Scope, $_SESSION['Scratch']['EUP']);
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
        <form action="user_process_io03.php" method="post"><p>
            <input type="submit" name="user_process_o1" value="Back to user-process record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['u_p_history'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        $EntityArray = array(
            'process' => $_SESSION['Selected']['k0process'],
            'facility' => $_SESSION['StatePicked']['t0facility']['k0facility'],
            'owner' => $_SESSION['StatePicked']['t0owner']['k0owner']
        );
        $EUPH = $UPZfpf->eup_history_array($Zfpf, $_SESSION['Selected']['k0user'], $EntityArray);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
        $HistoryGetZfpf = new HistoryGetZfpf;
        $Heading = 'History of user-practice privileges';
        $Scope = '<p>
        Privileges of user:<br />
        * '.$SelectedUser['NameTitleEmployerWorkEmail'].'<br />
        with <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> of:<br />
        * process '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0process']['c5name']).',<br />
        * facility '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', and<br />
        * owner '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).'</p>';
        $HistoryGetZfpf->selected_changes_html_h($Zfpf, $EUPH, count($EUPH), $Heading, 'user_process_io03.php', 'u_p_i1', $Scope); // This echos and exits.
    }
    
    if (isset($_POST['separate_user_1'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        if ($_SESSION['Selected']['k0user'] == $_SESSION['StatePicked']['t0process']['k0user_of_leader'])
            $IsLeader = 'Yes';
        else
            $IsLeader = 'No';
        $PrivilegesText = '<p>
        At selected process:<br />
        - PSM Leader: '.$IsLeader.'<br />
        - Process: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5p_process']).'<br />
        - User-Process (and subordinate <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>): '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5p_user']).'</p>';
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $ConditionsUP[] = array('k0user', '=', $_SESSION['Selected']['k0user'], 'AND'); 
        $ConditionsUP[] = array('k0process', '=', $_SESSION['t0user_process']['k0process']);
        $PrivilegesText .= display_privileges_practice($Zfpf, $DBMSresource, $SelectedUser, $ConditionsUP);
        $Zfpf->close_connection_1s($DBMSresource);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        End selected user\'s access to process information via this app.</h2><p>
        <b>Selected <a class="toc" href="glossary.php#process" target="_blank">process</a>:</b> '.$ProcessNameDescription.'</p><p>
        End access for <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b>, via this app, to the information of the selected process and its <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>.</p><p>
        The app cannot assign a new PSM leader. You need to do this, if you plan to end the access of a PSM leader for a process or any ongoing PHA or HIRA, incident investigation, or PSM audit.</p><p>
        The above user has the following privileges (which they will lose) and leadership roles (which will need to be filled).</p>
        '.$PrivilegesText.'
        <form action="user_process_io03.php" method="post"><p>
            <input type="submit" name="separate_user_2" value="End information access" /></p><p>
            <input type="submit" name="user_process_o1" value="Take no action -- go back" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['separate_user_2'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
        $LeadersNeeded = $UserZfpf->separate_user($Zfpf, 'process');
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Confirmation.</h2><p>
        The app attempted to end access, via this app, to the selected user\'s access to the information of the selected process and its <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>.</p><p>
        <b>Selected <a class="toc" href="glossary.php#process" target="_blank">process</a>:</b> '.$ProcessNameDescription.'</p><p>
        <b>Selected user:</b> '.$SelectedUser['NameTitleEmployerWorkEmail'].'</p>';
        if ($LeadersNeeded)
            echo '<p>
            '.$LeadersNeeded.'</p><p>
            '.$SelectedUser['Name'].' remains the recorded PSM leader for these.</p>';
        echo '<p>
        You can confirm this via the user history records.</p>
        <form action="user_h_o1.php?process" method="post"><p>
            <input type="submit" value="User-process history" /></p>
        </form>
        <form action="user_process_i0m.php" method="post"><p>
            <input type="submit" value="Back to user list" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // i1, i2, i3 code
    // Reduce privileges dropdown menu options based on global DBMS privileges.
    if ($SelectedUserGlobalDBMSPriv != MAX_PRIVILEGES_ZFPF) {
        $htmlFormArray['c5p_process'] = array('<b>Privileges with this process</b>. These cannot be higher than the user\'s global database management system (DBMS) privileges.<br /><br />Process-wide <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> and process summary. <b>None allowed</b> because user\'s global DBMS privileges are less than '.MAX_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
        $htmlFormArray['c5p_user'] = array('User-process and user-practice records. <b>None allowed</b> because user\'s global DBMS privileges are less than '.MAX_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
    }
    if (isset($_POST['user_process_o1_from']))
        $Zfpf->edit_lock_1c('user_process'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error.
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['user_process_i0n']) or isset($_POST['user_process_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        $Display['k0user'] = $SelectedUser['NameTitleEmployerWorkEmail'];
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
        User-Process Record</h2><p>
        <b>Selected <a class="toc" href="glossary.php#process" target="_blank">process</a>:</b> '.$ProcessNameDescription.'</p>
        <form action="user_process_io03.php" method="post" enctype="multipart/form-data" >';
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
        <b>Global Database Management System (DBMS) Privileges:</b><br />
        '.$SelectedUserGlobalDBMSPriv.'</p><p>
            <input type="submit" name="user_process_i2" value="Review what you typed into form" /></p>
        </form>';
        if ($who_is_editing == '[A new database row is being created.]')
            echo '
            <form action="user_process_i0m.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>';
        else
            echo '
            <form action="user_process_io03.php" method="post"><p>
                <input type="submit" name="user_process_o1" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['user_process_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('user_process_io03.php', 'user_process_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]')
            $Zfpf->insert_sql_1s($DBMSresource, 't0user_process', $ChangedRow);
        else {
            $Conditions[0] = array('k0user_process', '=', $_SESSION['Selected']['k0user_process']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_process', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Record(s) Updated</h2><p>
        The user-process information you input and reviewed has been recorded.</p>
        <form action="user_process_io03.php" method="post"><p>
            <input type="submit" name="user_process_o1" value="Back to record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends user_practice, separate_user, i1, i2, i3 code

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

