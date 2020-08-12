<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the user_owner input and output HTML forms, except the:
//  - i0m and i1m file for listing existing records (and giving the option to start a new record)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();


// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_owner_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get current-user information...
$User = $Zfpf->current_user_info_1c();

// SPECIAL CASE put left-hand contents and new-user code first.
if (isset($_POST['user_owner_o1']) or isset($_POST['user_owner_o1_from']) or isset($_POST['user_owner_i0n']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c5p_owner' => 'Privileges'
    );
$RunUserCode = FALSE;
if (isset($_POST['user_i0n']) or isset($_POST['user_i2']) or isset($_POST['user_undo_confirm_post_1e']) or isset($_POST['user_modify_confirm_post_1e']))
    $RunUserCode = TRUE;
// Add a user code
if ($RunUserCode or isset($_POST['user_yes_confirm_post_1e'])) {
    if (!isset($_SESSION['StatePicked']['t0owner']) or !isset($_SESSION['t0user_owner']) or strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user'])) < strlen(MID_PRIVILEGES_ZFPF) or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
    $UserZfpf = new UserZfpf;
    if (isset($_POST['user_i0n']) or isset($_POST['user_undo_confirm_post_1e']))
        $UserZfpf->user_i0n($Zfpf, 'user_owner_io03.php', 'user_owner_i0m.php');
    elseif (isset($_POST['user_modify_confirm_post_1e'])) {
        if (!isset($_SESSION['Selected']['k2username_hash']) or !isset($_SESSION['Scratch']['SelectDisplay']) or !isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        $UserZfpf->user_i1($Zfpf, 'user_owner_io03.php', 'user_owner_i0m.php', 'NotNeeded', $Display);
    }
    elseif (isset($_POST['user_i2']))
        $UserZfpf->user_i2($Zfpf, 'user_owner_io03.php', 'user_owner_io03.php');
    elseif (isset($_POST['user_yes_confirm_post_1e'])) { // set by user_i2. The user_i3 code is run with the user_owner i3 code, after user confirms the user_owner info.
        // i0n code for user_owner record
        if (!isset($_SESSION['Selected']['k2username_hash']) or !isset($_SESSION['Scratch']['SelectDisplay']) or !isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // Save the confirmed t0user record information on the new user.
        $_SESSION['Scratch']['t0user']['Selected'] = $_SESSION['Selected'];
        $_SESSION['Scratch']['t0user']['ModifiedValues'] = $_SESSION['Scratch']['ModifiedValues'];
        $_SESSION['Scratch']['NewUserNameEmployer'] = $Zfpf->encrypt_1c($Zfpf->full_name_1c($Zfpf->changes_from_post_1c()).', '.$User['Employer']); // Current user has same employer as new user. changes_from_post_1c() formats HTML post as needed for full_name_1c()
        unset($_SESSION['Scratch']['SelectDisplay']);
        unset($_SESSION['Post']);
	    // Initialize $_SESSION['Selected'] and overwrite old one.
        $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
        $_SESSION['Selected'] = array(
            'k0user_owner' => time().mt_rand(1000000, 9999999),
            'k0user' => $_SESSION['Scratch']['t0user']['Selected']['k0user'],
            'k0owner' => $_SESSION['t0user_owner']['k0owner'],
            'c5job_title' => $EncryptedNothing,
            'c5employee_number' => $EncryptedNothing,
            'c5work_email' => $EncryptedNothing,
            'c5work_phone_mobile' => $EncryptedNothing,
            'c5work_phone_desk' => $EncryptedNothing,
            'c5work_phone_pager' => $EncryptedNothing,
            'c5p_owner' => $EncryptedNothing,
            'c5p_user' => $EncryptedNothing,
            'c5p_contractor' => $EncryptedNothing,
            'c5p_facility' => $EncryptedNothing,
            'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
        );
        $_POST['user_owner_i0n'] = TRUE; // Set this here (and don't exit) so i1 code will run with typical i0n format.
    }
}

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'k0user' => array('Employee (the user, in this context)', ''),
    'c5job_title' => array('Job title', REQUIRED_FIELD_ZFPF),
    'c5employee_number' => array('Employee number', REQUIRED_FIELD_ZFPF),
    'c5work_email' => array('Work email', REQUIRED_FIELD_ZFPF),
    'c5work_phone_mobile' => array('Work mobile phone', ''),
    'c5work_phone_desk' => array('Work desk phone', ''),
    'c5work_phone_pager' => array('Work pager', ''),
    'c5p_owner' => array('<a id="c5p_owner"></a><b>Privileges with this Owner/Operator (aka owner)</b>. These cannot be higher than the user\'s global database management system (DBMS) privileges.<br /><br /> Full privileges to insert new and update owner-wide compliance-practice records and to update the owner summary. (Only app admins can insert and delete owners)', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MAX_PRIVILEGE_OPTIONS_ZFPF']),
    'c5p_user' => array('User, user-owner, and user-practice records. "'.MID_PRIVILEGES_ZFPF.'" to insert new users, associate them with PSM <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a>, and unlock logon credentials of a user (for example, after they mistyped a password multiple times). Full privileges to also update user-practice and user-owner records. (Only users can update their own user record. Only the owner '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader can terminate access for a typical user with this owner.)', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MID_PRIVILEGE_OPTIONS_ZFPF']),
    'c5p_contractor' => array('Contractor and owner-contractor records. "'.MID_PRIVILEGES_ZFPF.'" to insert new contractor-organization records and associate them the owner. Full privileges to also update or delete contractor records', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MID_PRIVILEGE_OPTIONS_ZFPF']),
    'c5p_facility' => array('Facility and owner-facility records. Full privileges to insert new, update, and delete facility and owner-facility records', '', C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['MIN_MAX_PRIVILEGE_OPTIONS_ZFPF'])
);

// history_o1 code
if (isset($_POST['user_owner_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0user_owner']) or !isset($_SESSION['t0user_owner']) or $_SESSION['t0user_owner']['k0owner'] != $_SESSION['Selected']['k0owner'] or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0user_owner', $_SESSION['Selected']['k0user_owner']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one user-owner record', 'user_owner_io03.php', 'user_owner_o1'); // This echos and exits.
}

// History of what a user has done. Accessible here if a current owner employee. For separated users, see user_h_o1.php
if (isset($_POST['by_user_h_o1'])) {
    if (!isset($_SESSION['Selected']['k0user_owner']) or !isset($_SESSION['t0user_owner']) or $_SESSION['t0user_owner']['k0owner'] != $_SESSION['Selected']['k0owner'] or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->by_user_h($Zfpf, $_SESSION['Selected']['k0user']); // No owner, facility, or process conditions needed because the user is an employee of the owner, so all of the user's acts must relate to the owner's facilities and processes.
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of what user has done via this app, as an employee of the owner', 'user_owner_io03.php', 'user_owner_o1'); // This echos and exits.
}

// o1 code
if (isset($_POST['user_owner_o1'])) {
	// Additional security check.
    if (!isset($_SESSION['Selected']['k0user_owner']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0user_owner'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (!isset($_SESSION['Selected']['k0user_owner'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0user_owner'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0user_owner'][$CheckedPost];
        unset($_SESSION['SelectResults']);
    }
    $Zfpf->clear_edit_lock_1c();
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
	// Handle k0 field(s)
	if (!isset($SelectedUser))
        $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']); // Refresh in case the database was updated.
    $Display['k0user'] = $SelectedUser['NameTitleEmployerWorkEmail'];
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
    $UserZfpf = new UserZfpf;
	echo $Zfpf->xhtml_contents_header_1c().'<h1>
    '.$SelectedUser['Employer'].' (the <a class="toc" href="glossary.php#owner_operator" target="_blank">Owner/Operator</a>, aka owner)</h1><h2>
    User-Owner Record</h2>
    <form action="user_io03.php" method="post"><p>
        <input type="submit" name="user_o1" value="View emergency and personal contacts" /></p>
    </form>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'user_owner_io03.php', $_SESSION['Selected'], $Display).'<p>
    <b>Global Database Management System (DBMS) Privileges:</b><br />
    '.$Zfpf->decrypt_1c($SelectedUser['t0user']['c5p_global_dbms']).'</p><p>
    <b>All privileges of '.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p>
    '.$UserZfpf->display_privileges($Zfpf, $SelectedUser, FALSE);
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    elseif (isset($_SESSION['t0user_owner']) and $_SESSION['t0user_owner']['k0owner'] == $_SESSION['Selected']['k0owner'] and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) == MAX_PRIVILEGES_ZFPF and $User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF) {
        echo '
        <form action="user_owner_io03.php" method="post"><p>
            <input type="submit" name="user_owner_o1_from" value="Update user-owner record" /></p><p>
            Update owner-wide user-practice privileges<br />
            <input type="submit" name="u_p_i1" value="Update privileges" /></p>';
        if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user'])
            echo '<p>
            End selected user\'s access via this app to information of the owner, its facilities, their processes, and all of their practices.<br />
            <input type="submit" name="separate_user_1" value="End information access" /></p>';
        else
            echo'<p><b>
                No Self Privilege-Change Notice</b>: Users cannot end their own privileges to access all owner information.</p>';
        echo'<p>
            <input type="submit" name="user_owner_history_o1" value="History of this record" /></p><p>
            <input type="submit" name="by_user_h_o1" value="Done by user history" /></p>
        </form>
        <form action="user_facility_i0m.php" method="post"><p>
            Update (if allowed) facility or process privileges<br />
            <input type="submit" value="Update privileges" /></p>
        </form>';
    }
    else {
        echo '<p><b>
        User-Update Privileges Notice</b>: You don\'t have user-update privileges for this Owner/Operator, if you need to update any user records, please contact your supervisor or an app administrator.</p>';
        if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to update PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '
    <form action="user_owner_i0m.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// user_practice, separate_user, i1, i2, i3 code
if (isset($_SESSION['Selected']['k0user_owner'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0user_owner', '=', $_SESSION['Selected']['k0user_owner']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0user_owner', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing == '[A new database row is being created.]') {
        // Additional security check
        if (!isset($_SESSION['StatePicked']['t0owner']) or !isset($_SESSION['t0user_owner']) or strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user'])) < strlen(MID_PRIVILEGES_ZFPF) or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
            $Zfpf->send_to_contents_1c(); // Don't eject -- repeat of security check under if ($RunUserCode...
        $ModifiedValues = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['t0user']['ModifiedValues']);
        if (isset($ModifiedValues['c5p_global_dbms']))
            $SelectedUserGlobalDBMSPriv = $ModifiedValues['c5p_global_dbms'];
        else
            $SelectedUserGlobalDBMSPriv = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0user']['Selected']['c5p_global_dbms']);
    }
    else {
        // Additional security check
        // Check if current user is with the same owner as the selected employee and if so check current user's privileges.
        if (!isset($_SESSION['StatePicked']['t0owner']) or !isset($_SESSION['t0user_owner']) or $_SESSION['t0user_owner']['k0owner'] != $_SESSION['Selected']['k0owner'] or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            $Zfpf->send_to_contents_1c(); // Don't eject
        $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']); // Won't work in i0n context because no t0user_owner record.
        $SelectedUserGlobalDBMSPriv = $Zfpf->decrypt_1c($SelectedUser['t0user']['c5p_global_dbms']);
    }

    if (isset($_POST['u_p_i1']) or isset($_POST['u_p_none_view']) or isset($_POST['u_p_all_view']) or isset($_POST['u_p_all_edit']) or isset($_POST['u_p_modify_confirm_post_1e']) or isset($_POST['u_p_undo_confirm_post_1e'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        if (!isset($_POST['u_p_modify_confirm_post_1e']))
            $_SESSION['Scratch']['EUP'] = $UPZfpf->eup_arrays($Zfpf, $_SESSION['Selected']['k0user'], 'owner', $_SESSION['Selected']['k0owner']);
        $Scope = 'with <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> of:<br />
        * owner '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        $HTML = $UPZfpf->u_p_i1($Zfpf, $SelectedUser, 'owner', $Scope, $_SESSION['Scratch']['EUP']); // Call before header echo to set left-hand contents in $_SESSION
        echo $Zfpf->xhtml_contents_header_1c().$HTML.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['u_p_i2'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        $Scope = 'with practices of:<br />
        * owner '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        $HTML = $UPZfpf->u_p_i2($Zfpf, $SelectedUser, 'owner', $Scope, $_SESSION['Scratch']['EUP']);
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
        <form action="user_owner_io03.php" method="post"><p>
            <input type="submit" name="user_owner_o1" value="Back to user-owner record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['u_p_history'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserPracticeZfpf.php';
        $UPZfpf = new UserPracticeZfpf;
        $EntityArray = array('owner' => $_SESSION['Selected']['k0owner']);
        $EUPH = $UPZfpf->eup_history_array($Zfpf, $_SESSION['Selected']['k0user'], $EntityArray);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
        $HistoryGetZfpf = new HistoryGetZfpf;
        $Heading = 'History of user-practice privileges';
        $Scope = '<p>
        Privileges of user:<br />
        * '.$SelectedUser['NameTitleEmployerWorkEmail'].'<br />
        with <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> of:<br />
        * owner '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).'</p>';
        $HistoryGetZfpf->selected_changes_html_h($Zfpf, $EUPH, count($EUPH), $Heading, 'user_owner_io03.php', 'u_p_i1', $Scope); // This echos and exits.
    }

    if (isset($_POST['separate_user_1'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        End selected user\'s access to '.$SelectedUser['Employer'].' information via this app.</h2><p>
        End access for <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b>, via this app, to the information of '.$SelectedUser['Employer'].', its facilities, its processes, and its practices.</p><p>
        The app cannot assign a new '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader. You need to do this, if you plan to end the access of a '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for an owner, facility, process, or any ongoing PHA or HIRA, incident investigation, or PSM audit.</p><p>
        The above user has the following privileges (which they will lose) and leadership roles (which will need to be filled).</p>
        '.$UserZfpf->display_privileges($Zfpf, $SelectedUser).'
        <form action="user_owner_io03.php" method="post"><p>
            <input type="submit" name="separate_user_2" value="End information access" /></p><p>
            <input type="submit" name="user_owner_o1" value="Take no action -- go back" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['separate_user_2'])) {
        if ($who_is_editing == '[A new database row is being created.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
        $LeadersNeeded = $UserZfpf->separate_user($Zfpf, 'owner');
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Done.</h2><p>
        The app attempted to end access via this app:<br />
        - for '.$SelectedUser['NameTitleEmployerWorkEmail'].'<br />
        - to the information of '.$SelectedUser['Employer'].', its facilities, its processes, and its practices.</p>';
        if ($LeadersNeeded)
            echo '<p>
            '.$LeadersNeeded.'</p><p>
            '.$SelectedUser['Name'].' remains the recorded '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for these.</p>';
        echo '<p>
        You can confirm this via the user-history records.</p>
        <form action="user_h_o1.php" method="post"><p>
            <input type="submit" value="User history" /></p>
        </form>
        <form action="user_owner_i0m.php" method="post"><p>
            <input type="submit" value="Back to employee list" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // i1, i2, i3 code
    // Reduce privileges dropdown menu options based on global DBMS privileges.
    if (strlen($SelectedUserGlobalDBMSPriv) < strlen(MAX_PRIVILEGES_ZFPF)) {
        $htmlFormArray['c5p_owner'] = array('<a id="c5p_owner"></a><b>Privileges with this Owner/Operator (aka owner)</b>. These cannot be higher than the user\'s global database management system (DBMS) privileges.<br /><br /> Insert new and update owner-wide compliance-practice records or update the owner summary. <b>None allowed</b> because user\'s global DBMS privileges are less than '.MAX_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
        $htmlFormArray['c5p_user'][4] = array(NO_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF);
        $htmlFormArray['c5p_contractor'][4] = array(NO_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF);
        $htmlFormArray['c5p_facility'] = array('Facility and owner-facility records. Insert new, update, and delete facility and owner-facility records. <b>None allowed</b> because user\'s global DBMS privileges are less than '.MAX_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
    }
    if ($SelectedUserGlobalDBMSPriv == LOW_PRIVILEGES_ZFPF) {
        $htmlFormArray['c5p_user'] = array('User, user-owner, and user-practice records privileges. <b>None allowed</b> because user\'s global DBMS privileges are less than '.MID_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
        $htmlFormArray['c5p_contractor'] = array('Contractor and owner-contractor records privileges. <b>None allowed</b> because user\'s global DBMS privileges are less than '.MID_PRIVILEGES_ZFPF, '', C5_MAX_BYTES_ZFPF, 'dropdown', array(NO_PRIVILEGES_ZFPF));
    }
    if (isset($_POST['user_owner_o1_from']))
        $Zfpf->edit_lock_1c('user_owner'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error.
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['user_owner_i0n']) or isset($_POST['user_owner_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        if ($who_is_editing != '[A new database row is being created.]')
            $Display['k0user'] = $SelectedUser['NameTitleEmployerWorkEmail'];
        else
            $Display['k0user'] = $Zfpf->decrypt_1c($_SESSION['Scratch']['NewUserNameEmployer']);
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
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
        '.$User['Employer'].' (the <a class="toc" href="glossary.php#owner_operator" target="_blank">Owner/Operator</a>, aka owner)</h1><h2>
        User-Owner Record</h2>
        <form action="user_owner_io03.php" method="post" enctype="multipart/form-data" >';
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
        <b>Global Database Management System (DBMS) Privileges:</b><br />
        '.$SelectedUserGlobalDBMSPriv.'</p><p>
            <input type="submit" name="user_owner_i2" value="Review what you typed into form" /></p>
        </form>';
        if ($who_is_editing == '[A new database row is being created.]')
            echo '
            <form action="user_owner_i0m.php" method="post"><p>
                <input type="submit" value="Discard what you just typed" /></p>
            </form>';
        else
            echo '
            <form action="user_owner_io03.php" method="post"><p>
                <input type="submit" name="user_owner_o1" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['user_owner_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('user_owner_io03.php', 'user_owner_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]')
            $Zfpf->insert_sql_1s($DBMSresource, 't0user_owner', $ChangedRow);
        else {
            $Conditions[0] = array('k0user_owner', '=', $_SESSION['Selected']['k0user_owner']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_owner', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        // Save any changes to t0user row for employee
        if (isset($_SESSION['Scratch']['t0user']['Selected']) and isset($_SESSION['Scratch']['t0user']['ModifiedValues'])) {
            require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
            $UserZfpf = new UserZfpf;
            $Finished = $UserZfpf->user_i3($Zfpf, $_SESSION['Scratch']['t0user']['Selected'], $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['t0user']['ModifiedValues']));
            unset($_SESSION['Scratch']['t0user']);
        }
        else
            $Finished['Message'] = '';
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Record(s) Updated</h2><p>
        The user-owner information you input and reviewed has been recorded.</p>
        '.$Finished['Message'].'
        <form action="user_owner_io03.php" method="post"><p>
            <input type="submit" name="user_owner_o1" value="Back to record" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, i3 code

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

