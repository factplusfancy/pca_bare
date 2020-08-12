<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the process-summary input and output HTML forms, except the:
//  - i0m & i1m files for listing existing records

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// Handle user clicked link to this file from left-hand contents or administer1.php
if (!$_POST and isset($_SESSION['StatePicked']['t0process']) and isset($_SESSION['t0user_process'])) {
    $Zfpf->session_cleanup_1c(); // This calls CoreZfpf::clear_edit_lock_1c and unsets $_SESSION['Scratch'] -- ok here.
    // Refresh $_SESSION['StatePicked']['t0process'] in case it changed since the user logged in.
    $Conditions[0] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0process', $Conditions);
    if ($RR != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'process_i1m.php';
    $_SESSION['StatePicked']['t0process'] = $SR[0]; // Refresh
    $_SESSION['Selected'] = $_SESSION['StatePicked']['t0process'];
    $FromLinkWithoutPost = TRUE;
}

// General security check
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'process_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get useful variables.
$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('<a id="c5name"></a>Process name (what most people call it often works well)', REQUIRED_FIELD_ZFPF),
    'c6description' => array('Description', '', C6SHORT_MAX_BYTES_ZFPF),
    'k0user_of_leader' => array('The '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader', ''),
    'c5industry_code' => array('Industry type, such as latest North American Industrial Classification (NAICS) code', REQUIRED_FIELD_ZFPF)
);

// Left hand Table of contents
if (isset($_POST['process_o1']) or isset($_POST['process_i0n']) or isset($_POST['process_o1_from']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
    	'c5name' => 'Process name'
    );

// i0n code
if (isset($_POST['process_i0n'])) {
    // Additional security check.
    if (!isset($_SESSION['t0user_facility']) or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_process']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $_SESSION['Selected'] = array (
        'k0process' => time().mt_rand(1000000, 9999999),
        'k0user_of_leader' => $_SESSION['t0user']['k0user'],
        'c5name' => $EncryptedNothing,
        'c6description' => $EncryptedNothing,
        'c5industry_code' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['process_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0process']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0process', $_SESSION['Selected']['k0process']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one process record', 'process_io03.php', 'process_o1'); // This echos and exits.
}

// o1 code
// SPECIAL CASE: isset($FromLinkWithoutPost) handles user clicked link to this file from left-hand contents
if (isset($_POST['process_o1']) or isset($FromLinkWithoutPost)) {
    if (isset($_POST['process_o1'])) {
        // Additional security check
        if (!isset($_SESSION['Selected']['k0process']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0process'])))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // $_SESSION cleanup
        if (isset($_SESSION['Scratch']['htmlFormArray']))
            unset($_SESSION['Scratch']['htmlFormArray']);
        if (isset($_SESSION['Post']))
            unset($_SESSION['Post']);
        if (!isset($_SESSION['Selected']['k0process'])) {
            // Additional security check
            if (!isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['t0user_facility'])) // Same privileges needed to get link from administer1.php.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $CheckedPost = $Zfpf->post_length_blank_1c('selected'); // Get user selection.
            if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0process'][$CheckedPost]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['Selected'] = $_SESSION['SelectResults']['t0process'][$CheckedPost];
            unset($_SESSION['SelectResults']);
        }
        $Zfpf->clear_edit_lock_1c();
    }
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
	// Handle k0 field(s)
    $Display['k0user_of_leader'] = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader'])['NameTitleEmployerWorkEmail'];
    if (($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'] or $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes') and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF) // Current user is process leader or app admin.
        $Display['k0user_of_leader'] .= '<br />
        <input type="submit" name="change_psm_leader_1" value="Change '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader" />';
    echo $Zfpf->xhtml_contents_header_1c().'
    <h2>
    Process Summary</h2>
    <form action="process_io03.php" method="post">
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'process_io03.php', $_SESSION['Selected'], $Display); // SPECIAL CASE: start form before to include button in $Display -- except when o1 code includes a download button, see ConfirmZfpf::select_to_o1_html_1e
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    else {
        if (isset($_SESSION['t0user_process']) and $_SESSION['t0user_process']['k0process'] == $_SESSION['Selected']['k0process'] and $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_process']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF)
            echo '<p>
            <input type="submit" name="process_o1_from" value="Update process summary" /></p>';
        else
            echo '<p><b>
            Update Privileges Notice</b>: You don\'t have update privileges on this record. Only a process\'s employee with adequate privileges may update this record.</p>';
        if ($UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to update or delete PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '<p>
        <input type="submit" name="process_history_o1" value="History of this record" /></p>
    </form>';
	if (isset($_SESSION['t0user_facility']))
	    echo '
        <form action="process_i0m.php" method="post"><p>
            <input type="submit" value="Back to processes list" /></p>
        </form>';
    echo $Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// change_psm_leader, i1, i2, i3 code
if (isset($_SESSION['Selected']['k0process'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0process', '=', $_SESSION['Selected']['k0process']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0process', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);

    // No edit lock because only the leader or an app admin can change the leader.
    if (isset($_POST['change_psm_leader_1'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        echo $Zfpf->xhtml_contents_header_1c('Lookup User');
        $Zfpf->lookup_user_1c('process_io03.php', 'process_io03.php', 'change_psm_leader_2', 'process_o1');
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['change_psm_leader_2'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        $TableNameUserEntity = 't0user_process';
        $Conditions1[0] = array('k0process', '=', $_SESSION['Selected']['k0process']);
        $SpecialText = '<p><b>
        Pick '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader</b></p><p>
        The current '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader will be replaced by the user you pick above.</p>';
        $Zfpf->lookup_user_wrap_2c(
            $TableNameUserEntity,
            $Conditions1,
            'process_io03.php', // $SubmitFile
            'process_io03.php', // $TryAgainFile
            array('k0user'), // $Columns1
            'process_io03.php', // $CancelFile
            $SpecialText,
            'Assign '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader', // $SpecialSubmitButton
            'change_psm_leader_3', // $SubmitButtonName
            'change_psm_leader_1', // $TryAgainButtonName
            'process_o1', // $CancelButtonName
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
            <form action="process_io03.php" method="post"><p>
                <input type="submit" name="change_psm_leader_1" value="Try again" /></p><p>
                <input type="submit" name="process_o1" value="Back to process summary" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        $Conditions[0] = array('k0process', '=', $_SESSION['Selected']['k0process']);
        $ShtmlFormArray['k0user_of_leader'] = $htmlFormArray['k0user_of_leader'];
        $Affected = $Zfpf->one_shot_update_1s('t0process', $Changes, $Conditions, TRUE, $ShtmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        // Email the current and former leaders and, if applicable, current user, who must be an app admin.
        $AEFullDescription = 'process '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
        $FormerLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
        $NewLeader = $Zfpf->user_job_info_1c($Changes['k0user_of_leader']);
        $EmailAddresses = array($FormerLeader['WorkEmail'], $NewLeader['WorkEmail']);
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found):</b><br />
        Former '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$FormerLeader['NameTitleEmployerWorkEmail'].'<br />
        New '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$NewLeader['NameTitleEmployerWorkEmail'];
    	$Message = '<p>';
        if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader']) {
            $CurrentUserAppAdmin = $Zfpf->current_user_info_1c();
            $EmailAddresses[] = $CurrentUserAppAdmin['WorkEmail'];
            $DistributionList .= '<br />
            App admin who changed the '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$CurrentUserAppAdmin['NameTitleEmployerWorkEmail'];
            $Message .= $CurrentUserAppAdmin['NameTitleEmployerWorkEmail'];
        }
        else
            $Message .= $FormerLeader['NameTitleEmployerWorkEmail'];
        $DistributionList .= '</p>';
        $Message .= ' changed the '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for '.$AEFullDescription.'<br/><br/>
        to: '.$NewLeader['NameTitleEmployerWorkEmail'].'</p>';
        $_SESSION['Selected']['k0user_of_leader'] = $Changes['k0user_of_leader'];
        if (isset($_SESSION['StatePicked']['t0process']))
            $_SESSION['StatePicked']['t0process']['k0user_of_leader'] = $Changes['k0user_of_leader'];
    	$Subject = 'PSM-CAP: Changed '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for '.$AEFullDescription;
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
        <form action="process_io03.php" method="post"><p>
            <input type="submit" name="process_o1" value="Back to viewing record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // i1, i2, i3 code
    if ($UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF or ($who_is_editing != '[A new database row is being created.]' and (!isset($_SESSION['t0user_process']) or $_SESSION['t0user_process']['k0process'] != $_SESSION['Selected']['k0process'] or $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_process']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)))
        $Zfpf->send_to_contents_1c(); // Don't eject
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['process_i0n']) or isset($_POST['process_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        $Display['k0user_of_leader'] = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader'])['NameTitleEmployerWorkEmail'];
        if (isset($_POST['process_o1_from'])) {
            $Zfpf->edit_lock_1c('process', $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).' process summary'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. Not needed for i1n.
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
    // 1.3 $_SESSION['Post'] is source of $Display, the latest user-modified version.  Also use for upload_files.
    elseif (isset($_SESSION['Post']) and isset($_POST['modify_confirm_post_1e']))
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
		Process Summary</h1>
        <form action="process_io03.php" method="post">';
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
		    <input type="submit" name="process_i2" value="Review what you typed into form" /></p>
		</form>';
		if ($who_is_editing == '[A new database row is being created.]')
		    echo '
            <form action="process_i0m.php" method="post"><p>
                <input type="submit" value="Back to processes list" /></p>
            </form>';
		else
    		echo '
		    <form action="process_io03.php" method="post"><p>
		        <input type="submit" name="process_o1" value="Back to viewing record" /></p>
		    </form>';
		echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['process_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay); // Save user-posted info after checked username and password included.
	    echo $Zfpf->post_select_required_compare_confirm_1e('process_io03.php', 'process_io03.php', $htmlFormArray, $PostDisplay, $SelectDisplay);
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
            if (!isset($_SESSION['t0user_facility']) or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_process']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Zfpf->insert_sql_1s($DBMSresource, 't0process', $ChangedRow);
            // Insert first process admin
            $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
            $EncryptedMaxPriv = $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF);
            $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
            $Changes['t0user_process'] = array(
                'k0user_process' => time().mt_rand(1000000, 9999999),
                'k0user' => $_SESSION['Selected']['k0user_of_leader'],
                'k0process' => $_SESSION['Selected']['k0process'],
                'c5name' => $EncryptedNothing,
                'c6description' => $EncryptedNothing,
                'c5p_process' => $EncryptedMaxPriv,
                'c5p_user' => $EncryptedMaxPriv,
                'c5who_is_editing' => $EncryptedNobody    
            );
            $Changes['t0facility_process'] = array(
                'k0facility_process' => time().mt_rand(1000000, 9999999),
                'k0facility' => $_SESSION['t0user_facility']['k0facility'],
                'k0process' => $_SESSION['Selected']['k0process'],
                'c5who_is_editing' => $EncryptedNobody
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0user_process', $Changes['t0user_process']);
            $Zfpf->insert_sql_1s($DBMSresource, 't0facility_process', $Changes['t0facility_process']);
            // Insert Process Standard Practices into t0process_practice and t0user_practice for first process admin.
            $Conditions[0] = array('c2standardized', '=', 'Process Standard Practice');
            list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions);
            if ($RR > 0) {
                foreach ($SR as $K => $V) {
                    $TemplateProcessPractice[] = array(
                        'k0process_practice' => time().$K.mt_rand(1000, 9999), // $K needed because time() may return the same value each pass.
                        'k0process' => $_SESSION['Selected']['k0process'],
                        'k0practice' => $V['k0practice'],
                        'c5who_is_editing' => $EncryptedNobody
                    );
                    $TemplateUserPractice[] = array(
                        'k0user_practice' => time().$K.mt_rand(1000, 9999),
                        'k0user' => $_SESSION['t0user']['k0user'],
                        'k0practice' => $V['k0practice'],
                        'k0owner' => 0,
                        'k0contractor' => 0,
                        'k0facility' => 0,
                        'k0process' => $_SESSION['Selected']['k0process'],
                        'c5p_practice' => $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF),
                        'c5who_is_editing' => $EncryptedNobody
                    );
                }
                foreach ($TemplateProcessPractice as $V)
                    $Zfpf->insert_sql_1s($DBMSresource, 't0process_practice', $V);
                foreach ($TemplateUserPractice as $V)
                    $Zfpf->insert_sql_1s($DBMSresource, 't0user_practice', $V);
            }
        }
        else {
            // Additional security check
            if (!isset($_SESSION['t0user_process']) or $_SESSION['t0user_process']['k0process'] != $_SESSION['Selected']['k0process'] or $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_process']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Conditions[0] = array('k0process', '=', $_SESSION['Selected']['k0process']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0process', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Record(s) Updated</h2><p>
        The information about the '.HAZSUB_PROCESS_NAME_ZFPF.' that you input and reviewed has been recorded.</p>
        <form action="process_io03.php" method="post"><p>
            <input type="submit" name="process_o1" value="Back to record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // Ends change_psm_leader, i1, i2, i3 code

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

