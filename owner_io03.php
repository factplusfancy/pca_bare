<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the owner summary input and output HTML forms, except the:
//  - i0m & i1m files for listing existing records

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// Handle user clicked link to this file from left-hand contents
if (!$_POST and isset($_SESSION['StatePicked']['t0owner'])) {
    $Zfpf->session_cleanup_1c(); // This calls CoreZfpf::clear_edit_lock_1c and unsets $_SESSION['Scratch'] -- ok here.
    // Refresh $_SESSION['StatePicked']['t0owner'] in case it changed since the user logged in.
    $Conditions[0] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0owner', $Conditions);
    if ($RR != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'owner_i1m.php';
    $_SESSION['StatePicked']['t0owner'] = $SR[0]; // Refresh
    $_SESSION['Selected'] = $_SESSION['StatePicked']['t0owner'];
    $FromLinkWithoutPost = TRUE;
}

// General security check
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'owner_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get useful variables.
$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('<a id="c5name"></a>Owner/Operator (aka owner) name, the exact legal name often works best', REQUIRED_FIELD_ZFPF),
    'c6description' => array('Description', '', C6SHORT_MAX_BYTES_ZFPF),
    'c5chief_officer_name' => array('<a id="c5chief_officer_name"></a>Chief officer or others holding overall responsibility for this entity. If more than one person jointly (and perhaps severally) hold overall responsibility, list all of their names, or reference a document where this information is kept up to date', REQUIRED_FIELD_ZFPF),
    'k0user_of_leader' => array('PSM Leader', ''),
    'c5phone' => array('<a id="c5phone"></a>Main Telephone Number', REQUIRED_FIELD_ZFPF),
    'c5street1' => array('<a id="c5street1"></a>Address', REQUIRED_FIELD_ZFPF),
    'c5street2' => array('Address (extra line)', ''),
    'c5city' => array('City', REQUIRED_FIELD_ZFPF),
    'c5state_province' => array('State or Province', ''),
    'c5postal_code' => array('Postal Code', ''),
    'c5country' => array('Country', REQUIRED_FIELD_ZFPF),
    'c5website' => array('<a id="c5website"></a>Website', '')
);

// Left hand Table of contents
if (isset($_POST['owner_o1']) or isset($_POST['owner_i0n']) or isset($_POST['owner_o1_from']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
	    'c5name' => 'Owner name',
	    'c5chief_officer_name' => 'Chief officer', 
	    'c5phone' => 'Telephone number',  
	    'c5street1' => 'Address',
	    'c5website' => 'Website'
    );

// i0n code
if (isset($_POST['owner_i0n'])) {
    // Additional security check.
    if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes' or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $EncryptedMaxPriv = $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF);
    $EncryptedZero = $Zfpf->encrypt_1c(0);
    $EncryptedNewDatabaseRow = $Zfpf->encrypt_1c('[A new database row is being created.]');
    $_SESSION['Selected'] = array(
        'k0owner' => time().mt_rand(1000000, 9999999),
        'k0user_of_leader' => time().mt_rand(1000000, 9999999), // Make this initial admin the owner PSM leader with full global and user_owner c5p_ privileges.
        'c5name' => $EncryptedNothing,
        'c6description' => $EncryptedNothing,
        'c5chief_officer_name' => $EncryptedNothing,
        'c5phone' => $EncryptedNothing,
        'c5street1' => $EncryptedNothing,
        'c5street2' => $EncryptedNothing,
        'c5city' => $EncryptedNothing,
        'c5state_province' => $EncryptedNothing,
        'c5postal_code' => $EncryptedNothing,
        'c5country' => $EncryptedNothing,
        'c5website' => $EncryptedNothing,
        'c5who_is_editing' => $EncryptedNewDatabaseRow
    );
    // $_SESSION['Selected'] equivilents for junction tables.
    $_SESSION['Scratch']['S_t0user_owner'] = array(
        'k0user_owner' => time().mt_rand(1000000, 9999999),
        'k0user' => $_SESSION['Selected']['k0user_of_leader'],
        'k0owner' => $_SESSION['Selected']['k0owner'],
        'c5job_title' => $EncryptedNothing,
        'c5employee_number' => $EncryptedNothing,
        'c5work_email' => $EncryptedNothing,
        'c5work_phone_mobile' => $EncryptedNothing,
        'c5work_phone_desk' => $EncryptedNothing,
        'c5work_phone_pager' => $EncryptedNothing,
        'c5p_owner' => $EncryptedMaxPriv,
        'c5p_user' => $EncryptedMaxPriv,
        'c5p_contractor' => $EncryptedMaxPriv,
        'c5p_facility' => $EncryptedMaxPriv,
        'c5who_is_editing' => $EncryptedNewDatabaseRow
    );
    $_SESSION['Scratch']['S_t0user'] = array(
        'k0user' => $_SESSION['Selected']['k0user_of_leader'],
        'k2username_hash' => '[Nothing has been recorded in this field.]',
        's5password_hash' => $EncryptedNothing,
        'c5ts_password' => $EncryptedZero,
        'c5p_global_dbms' => $EncryptedMaxPriv,
        'c5app_admin' => $EncryptedNothing,
        'c5ts_created' => $Zfpf->encrypt_1c(time()),
        'c5ts_logon_revoked' => $EncryptedNothing,
        'c5ts_last_logon' => $EncryptedZero,
        'c5attempts' => $EncryptedZero,
        'c6active_sessions' => $Zfpf->encode_encrypt_1c(array()),
        'c5name_family' => $Zfpf->encrypt_1c('[Initial owner admin -- replace on first logon]'),
        'c5name_given1' => $EncryptedNothing,
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
        'c5who_is_editing' => $EncryptedNewDatabaseRow
    );
    // Set additional htmlFormArrays (HFA) for junction tables only when its really handy if current user provides this information now.
    $HFA_t0user_owner = array(
        // For this setup HTML form, don't allow editing privileges of the initial user.
        'c5job_title' => array('<a id="c5job_title"></a>Job Title', REQUIRED_FIELD_ZFPF),
        'c5work_email' => array('Work Email', REQUIRED_FIELD_ZFPF),
        'c5work_phone_mobile' => array('Work Mobile Phone', ''),
        'c5work_phone_desk' => array('Work Desk Phone', '')
    );
}

// history_o1 code
if (isset($_POST['owner_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0owner']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0owner', $_SESSION['Selected']['k0owner']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one owner record', 'owner_io03.php', 'owner_o1'); // This echos and exits.
}

// o1 code
// SPECIAL CASE: isset($FromLinkWithoutPost) handles user clicked link to this file from left-hand contents
if (isset($_POST['owner_o1']) or isset($FromLinkWithoutPost)) {
    if (isset($_POST['owner_o1'])) {
        // Additional security check
        if (!isset($_SESSION['Selected']['k0owner']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0owner'])))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // $_SESSION cleanup
        if (isset($_SESSION['Scratch']['htmlFormArray']))
            unset($_SESSION['Scratch']['htmlFormArray']);
        if (isset($_SESSION['Post']))
            unset($_SESSION['Post']);
        if (!isset($_SESSION['Selected']['k0owner'])) {
            // Additional security check
            if (!isset($_SESSION['StatePicked']['t0contractor']) or !isset($_SESSION['t0user_contractor'])) // Same privileges needed to get link from administer1.php.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $CheckedPost = $Zfpf->post_length_blank_1c('selected'); // Get user selection.
            if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0owner'][$CheckedPost]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['Selected'] = $_SESSION['SelectResults']['t0owner'][$CheckedPost];
            unset($_SESSION['SelectResults']);
        }
        $Zfpf->clear_edit_lock_1c();
    }
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
	// Handle k0 field(s)
    $Display['k0user_of_leader'] = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader'])['NameTitleEmployerWorkEmail'];
    if (($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'] or $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes') and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF) // Current user is owner PSM leader or app admin.
        $Display['k0user_of_leader'] .= '<br />
        <input type="submit" name="change_psm_leader_1" value="Change PSM leader" />';
    echo $Zfpf->xhtml_contents_header_1c().'
    <h2>
    Owner Summary</h2>
    <form action="owner_io03.php" method="post">
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'owner_io03.php', $_SESSION['Selected'], $Display); // SPECIAL CASE: start form before to include button in $Display -- except when o1 code includes a download button, see ConfirmZfpf::select_to_o1_html_1e
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    else {
        if (isset($_SESSION['t0user_owner']) and $_SESSION['t0user_owner']['k0owner'] == $_SESSION['Selected']['k0owner'] and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_owner']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF) 
            echo '<p>
            <input type="submit" name="owner_o1_from" value="Update owner summary" /></p>';
        else
            echo '<p><b>
            Update Privileges Notice</b>: You don\'t have update privileges on this record. Only an owner\'s employee with adequate privileges may update this record.</p>';
        if ($UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to update or delete PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '<p>
        <input type="submit" name="owner_history_o1" value="History of this record" /></p>
    </form>';
    if (isset($_SESSION['t0user_contractor']) and $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_contractor']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF)
        echo '
        <form action="contractor_io03.php" method="post"><p>
            Upload contractor-to-owner notices and view other owner-contractor records.<br />
            <input type="submit" name="owner_contractor_o1" value="Upload and view notices" /></p>
        </form>';
	if (isset($_SESSION['t0user_contractor']))
	    echo '
        <form action="owner_i0m.php" method="post"><p>
            <input type="submit" value="Back to owners list" /></p>
        </form>';
	else
		echo '
        <form action="administer1.php" method="post"><p>
            <input type="submit" value="Back to administration" /></p>
        </form>';
    echo $Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// change_psm_leader, i1, i2, i3 code
if (isset($_SESSION['Selected']['k0owner'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0owner', '=', $_SESSION['Selected']['k0owner']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0owner', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);

    // No edit lock because only PSM leader or an app admin can change the PSM leader.
    if (isset($_POST['change_psm_leader_1'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        echo $Zfpf->xhtml_contents_header_1c('Lookup User');
        $Zfpf->lookup_user_1c('owner_io03.php', 'owner_io03.php', 'change_psm_leader_2', 'owner_o1');
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['change_psm_leader_2'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        $TableNameUserEntity = 't0user_owner';
        $Conditions1[0] = array('k0owner', '=', $_SESSION['Selected']['k0owner']);
        $SpecialText = '<p><b>
        Pick PSM Leader</b></p><p>
        The current PSM leader will be replaced by the user you pick above.</p>';
        $Zfpf->lookup_user_wrap_2c(
            $TableNameUserEntity,
            $Conditions1,
            'owner_io03.php', // $SubmitFile
            'owner_io03.php', // $TryAgainFile
            array('k0user'), // $Columns1
            'owner_io03.php', // $CancelFile
            $SpecialText,
            'Assign PSM Leader', // $SpecialSubmitButton
            'change_psm_leader_3', // $SubmitButtonName
            'change_psm_leader_1', // $TryAgainButtonName
            'owner_o1', // $CancelButtonName
            'c5ts_logon_revoked', // $FilterColumnName
            '[Nothing has been recorded in this field.]' // $Filter
        ); // This function echos and exits.
    }
    if (isset($_POST['change_psm_leader_3'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        // Check user-input radio-button selection.
        // The user not selecting a radio button is OK in this case.
        if (isset($_POST['Selected'])) {
            $Selected = $Zfpf->post_length_1c('Selected');
            if (isset($_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]))
                $Changes['k0user_of_leader'] = $_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]; // This is the k0user of the newly selected user.
            else
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        }
        unset($_SESSION['Scratch']['PlainText']['lookup_user']);
        if (!isset($Changes['k0user_of_leader']) or $Changes['k0user_of_leader'] == $_SESSION['Selected']['k0user_of_leader']) {
            echo $Zfpf->xhtml_contents_header_1c('Nobody Selected').'<h2>
            You did not select a someone different.</h2>
            <form action="owner_io03.php" method="post"><p>
                <input type="submit" name="change_psm_leader_1" value="Try again" /></p><p>
                <input type="submit" name="owner_o1" value="Back to owner summary" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        $Conditions[0] = array('k0owner', '=', $_SESSION['Selected']['k0owner']);
        $ShtmlFormArray['k0user_of_leader'] = $htmlFormArray['k0user_of_leader'];
        $Affected = $Zfpf->one_shot_update_1s('t0owner', $Changes, $Conditions, TRUE, $ShtmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        // Email the current and former PSM leaders and, if applicable, current user, who must be an app admin.
        $AEFullDescription = 'owner '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
        $FormerLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
        $NewLeader = $Zfpf->user_job_info_1c($Changes['k0user_of_leader']);
        $EmailAddresses = array($FormerLeader['WorkEmail'], $NewLeader['WorkEmail']);
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found):</b><br />
        Former PSM Leader: '.$FormerLeader['NameTitleEmployerWorkEmail'].'<br />
        New PSM Leader: '.$NewLeader['NameTitleEmployerWorkEmail'];
    	$Message = '<p>';
        if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader']) {
            $CurrentUserAppAdmin = $Zfpf->current_user_info_1c();
            $EmailAddresses[] = $CurrentUserAppAdmin['WorkEmail'];
            $DistributionList .= '<br />
            App admin who changed the PSM leader: '.$CurrentUserAppAdmin['NameTitleEmployerWorkEmail'];
            $Message .= $CurrentUserAppAdmin['NameTitleEmployerWorkEmail'];
        }
        else
            $Message .= $FormerLeader['NameTitleEmployerWorkEmail'];
        $DistributionList .= '</p>';
        $Message .= ' changed the PSM leader for '.$AEFullDescription.'<br/><br/>
        to: '.$NewLeader['NameTitleEmployerWorkEmail'].'</p>';
        $_SESSION['Selected']['k0user_of_leader'] = $Changes['k0user_of_leader'];
        if (isset($_SESSION['StatePicked']['t0owner']))
            $_SESSION['StatePicked']['t0owner']['k0user_of_leader'] = $Changes['k0user_of_leader'];
    	$Subject = 'PSM-CAP: Changed PSM leader for '.$AEFullDescription;
        $Body = $Zfpf->email_body_append_1c($Message, $AEFullDescription, '', $DistributionList);
        $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Done</h2>
        '.$Message;
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="owner_io03.php" method="post"><p>
            <input type="submit" name="owner_o1" value="Back to viewing record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // i1, i2, i3 code
    if ($UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF or ($who_is_editing != '[A new database row is being created.]' and (!isset($_SESSION['t0user_owner']) or $_SESSION['t0user_owner']['k0owner'] != $_SESSION['Selected']['k0owner'] or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_owner']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)))
        $Zfpf->send_to_contents_1c(); // Don't eject
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['owner_i0n']) or isset($_POST['owner_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        $Display['k0user_of_leader'] = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader'])['NameTitleEmployerWorkEmail'];
        if ($who_is_editing == '[A new database row is being created.]') { // Update $Display and $htmlFormArray for all i0n database tables.
            $Display = array_merge($Display, $Zfpf->select_to_display_1e($HFA_t0user_owner, $_SESSION['Scratch']['S_t0user_owner'], FALSE, TRUE));
            $htmlFormArray = array_merge($htmlFormArray, $HFA_t0user_owner);
        }
        if (isset($_POST['owner_o1_from'])) {
            $Zfpf->edit_lock_1c('owner', $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).' owner summary'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. Not needed for i1n.
        }
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
    if ($who_is_editing == '[A new database row is being created.]') {
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
    }
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
		Owner Summary</h1>
        <form action="owner_io03.php" method="post">';
	    echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
	    if ($who_is_editing == '[A new database row is being created.]')
            echo '
            '.$UserZfpf->username_password_html_form('k2username_hash', 's5password_hash');
        echo '<p>
		    <input type="submit" name="owner_i2" value="Review what you typed into form" /></p>
		</form>';
		if ($who_is_editing == '[A new database row is being created.]')
		    echo '
            <form action="administer1.php" method="post"><p>
                <input type="submit" value="Back to administration" /></p>
            </form>';
		else
    		echo '
		    <form action="owner_io03.php" method="post"><p>
		        <input type="submit" name="owner_o1" value="Back to viewing record" /></p>
		    </form>';
		echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['owner_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        if ($who_is_editing == '[A new database row is being created.]') {
            $NewCredentials = $UserZfpf->username_password_check($Zfpf, FALSE, 'k2username_hash', 's5password_hash');
            if ($NewCredentials['Message']) {
                $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay); // Save any user-posted info before exiting.
                echo $Zfpf->xhtml_contents_header_1c('Error').$NewCredentials['Message'].'
                <form action="owner_io03.php" method="post"><p>
                    <input type="submit" name="modify_confirm_post_1e" value="Go back" /></p>
                </form>'.$Zfpf->xhtml_footer_1c();
                $Zfpf->save_and_exit_1c();
            }
            // Update variables below with username and password information, for i2 and i3 code.
            $PostDisplay['k2username_hash'] = $NewCredentials['Username'];
            $PostDisplay['s5password_hash'] = $NewCredentials['Password']; // Display the new temporary password to the admin creating it.
            $HFA_t0user = array(
                'k2username_hash' => array('Username (the future user may change this later)', REQUIRED_FIELD_ZFPF),
                's5password_hash' => array('Temporary Password (the future user must change this on first logon)', REQUIRED_FIELD_ZFPF)
            );
            $SelectDisplay = array_merge($SelectDisplay, $Zfpf->select_to_display_1e($HFA_t0user, $_SESSION['Scratch']['S_t0user'], FALSE, TRUE));
            $htmlFormArray = array_merge($htmlFormArray, $HFA_t0user);
        }
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay); // Save user-posted info after checked username and password included.
	    echo $Zfpf->post_select_required_compare_confirm_1e('owner_io03.php', 'owner_io03.php', $htmlFormArray, $PostDisplay, $SelectDisplay);
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') {
            // Additional security check
            if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes' or !isset($_SESSION['Scratch']['S_t0user_owner']))
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Zfpf->insert_sql_1s($DBMSresource, 't0owner', $ChangedRow);
            // Insert first owner admin
            $Zfpf->insert_sql_1s($DBMSresource, 't0user_owner', $Zfpf->changes_from_post_1c($_SESSION['Scratch']['S_t0user_owner']));
            $Zfpf->insert_sql_1s($DBMSresource, 't0user', $Zfpf->changes_from_post_1c($_SESSION['Scratch']['S_t0user'])); // Handles k2 and s5 fields
            unset($_SESSION['Scratch']['S_t0user_owner']); // Just to trip above security check, if hacker.
            // Insert Owner Standard Practices into t0owner_practice and t0user_practice for first owner admin.
            $Conditions[0] = array('c2standardized', '=', 'Owner Standard Practice');
            list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions);
            $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
            if ($RR > 0) {
                foreach ($SR as $K => $V) {
                    $TemplateOwnerPractice[] = array(
                        'k0owner_practice' => time().$K.mt_rand(1000, 9999), // $K needed because time() may return the same value each pass.
                        'k0owner' => $_SESSION['Selected']['k0owner'],
                        'k0practice' => $V['k0practice'],
                        'c5who_is_editing' => $EncryptedNobody
                    );
                    $TemplateUserPractice[] = array(
                        'k0user_practice' => time().$K.mt_rand(1000, 9999),
                        'k0user' => $_SESSION['Scratch']['S_t0user']['k0user'],
                        'k0practice' => $V['k0practice'],
                        'k0owner' => $_SESSION['Selected']['k0owner'],
                        'k0contractor' => 0,
                        'k0facility' => 0,
                        'k0process' => 0,
                        'c5p_practice' => $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF),
                        'c5who_is_editing' => $EncryptedNobody
                    );
                }
                foreach ($TemplateOwnerPractice as $V)
                    $Zfpf->insert_sql_1s($DBMSresource, 't0owner_practice', $V);
                foreach ($TemplateUserPractice as $V)
                    $Zfpf->insert_sql_1s($DBMSresource, 't0user_practice', $V);
            }
            // Associate current user, who created this owner record, with the owner, so that the owner has at least two admins.
            $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
            $EncryptedNoPriv = $Zfpf->encrypt_1c(NO_PRIVILEGES_ZFPF);
            $NewRow = array(
                'k0user_owner' => time().mt_rand(1000000, 9999999),
                'k0user' => $_SESSION['t0user']['k0user'],
                'k0owner' => $_SESSION['Selected']['k0owner'],
                'c5job_title' => $Zfpf->encrypt_1c('[App admin who created the owner record.]'),
                'c5employee_number' => $EncryptedNothing,
                'c5work_email' => $EncryptedNothing,
                'c5work_phone_mobile' => $EncryptedNothing,
                'c5work_phone_desk' => $EncryptedNothing,
                'c5work_phone_pager' => $EncryptedNothing,
                'c5p_owner' => $EncryptedNoPriv,
                'c5p_user' => $EncryptedNoPriv,
                'c5p_contractor' => $EncryptedNoPriv,
                'c5p_facility' => $EncryptedNoPriv,
                'c5who_is_editing' => $EncryptedNobody
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0user_owner', $NewRow);
        }
        else {
            // Additional security check
            if (!isset($_SESSION['t0user_owner']) or $_SESSION['t0user_owner']['k0owner'] != $_SESSION['Selected']['k0owner'] or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_owner']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Conditions[0] = array('k0owner', '=', $_SESSION['Selected']['k0owner']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0owner', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Record(s) Updated</h2><p>
        The owner information you input and reviewed has been recorded.</p>
        <form action="owner_io03.php" method="post"><p>
            <input type="submit" name="owner_o1" value="Back to record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // Ends change_psm_leader, i1, i2, i3 code

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

