<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the contractor-summary input and output HTML forms, except the:
//  - i0m & i1m files for listing existing records (and giving the option to start a new record) 

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// Handle user clicked link to this file from left-hand contents or arrived from owner_io03.php
if ((!$_POST or (isset($_POST['owner_contractor_o1']) and isset($_SESSION['Scratch']['PlainText']['SecurityToken']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] == 'owner_i1m.php')) and isset($_SESSION['StatePicked']['t0contractor']) and isset($_SESSION['t0user_contractor'])) {
    $Zfpf->session_cleanup_1c(); // This calls CoreZfpf::clear_edit_lock_1c and unsets $_SESSION['Scratch'] -- ok here.
    // Refresh $_SESSION['StatePicked']['t0contractor'] in case it changed since the user logged in.
    $Conditions[0] = array('k0contractor', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor']);
    list($SRC, $RRC) = $Zfpf->one_shot_select_1s('t0contractor', $Conditions);
    if ($RRC != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'contractor_i1m.php';
    $_SESSION['StatePicked']['t0contractor'] = $SRC[0]; // Refresh
    $_SESSION['Selected'] = $_SESSION['StatePicked']['t0contractor'];
    if (!$_POST)
        $FromLinkWithoutPost = TRUE;
}

// General security check
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'contractor_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get useful variables.
$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('<a id="c5name"></a>Contractor Name (the exact legal name typically works best)', REQUIRED_FIELD_ZFPF),
    'c6description' => array('Description', '', C6SHORT_MAX_BYTES_ZFPF),
    'c5chief_officer_name' => array('<a id="c5chief_officer_name"></a>Chief officer or others holding overall responsibility for this entity. If more than one person jointly (and perhaps severally) hold overall responsibility, list all of their names, or reference a document where this information is kept up to date', REQUIRED_FIELD_ZFPF),
    'k0user_of_leader' => array('The '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader', ''),
    'c5phone' => array('<a id="c5phone"></a>Main Telephone Number', REQUIRED_FIELD_ZFPF),
    'c5street1' => array('<a id="c5street1"></a>Address', REQUIRED_FIELD_ZFPF),
    'c5street2' => array('Address (extra line)', ''),
    'c5city' => array('City', REQUIRED_FIELD_ZFPF),
    'c5state_province' => array('State or Province', ''),
    'c5postal_code' => array('Postal Code', ''),
    'c5country' => array('Country', REQUIRED_FIELD_ZFPF),
    'c5website' => array('<a id="c5website"></a>Website', '')
);
$OChtmlFormArray = array(
    'k0owner' => array('Owner/Operator (aka owner)', ''),
    'k0contractor' => array('Contractor', ''),
    'c5ts_first_work_awarded' => array('Work first awarded', ''),
    'c5ts_qual_evaluated' => array('Contractor qualifications last evaluated by owner representative and recorded in app', ''),
    'c6bfn_owner_notices' => array('Owner-to-contractor notices. The Owner/Operator gives notices before a contractor first starts work and about once per year for ongoing work, for contractors\' work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.' (their work), notifying the contractor organization about: (1) known fire, explosion, or toxic release hazards related to their work and the '.HAZSUB_PROCESS_NAME_ZFPF.', (2) applicable portions of the facility Emergency Action Plan, (3) the contractor organization\'s PSM responsibilities related to their work, and (4) any other needed notices', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
    'c6bfn_contractor_notices' => array('Contractor-to-owner notices. Contractor organizations, who perform work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.' (their work), give notices as needed to inform the Owner/Operator about: (1) any unique hazards presented by their work, (2) hazards of any sort discovered at the facility, including by their work, (3) any injuries or illnesses of contractor individuals related to their work, and (4) any other relevant information, including providing Safety Data Sheets (SDS) for any materials the contractor will bring within the facility boundaries', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
    'c6bfn_job_site_audits' => array('Job-site audits or similar', '', MAX_FILE_SIZE_ZFPF, 'upload_files')
);

// Left hand Table of contents
if (isset($_POST['contractor_o1']) or isset($_POST['contractor_i0n']) or isset($_POST['contractor_o1_from']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
	    'c5name' => 'Contractor name',
	    'c5chief_officer_name' => 'Chief officer', 
	    'c5phone' => 'Telephone number',  
	    'c5street1' => 'Address',
	    'c5website' => 'Website'
    );

// No edit lock because associating records even if they are being edited.
if (isset($_POST['contractor_associate_1'])) {
    if (!isset($_SESSION['t0user_owner']) or !isset($_SESSION['StatePicked']['t0owner']) or strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor'])) < strlen(MID_PRIVILEGES_ZFPF) or $UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    if (isset($_SESSION['SelectResults']))
        unset($_SESSION['SelectResults']);
    list($SRC, $RRC) = $Zfpf->one_shot_select_1s('t0contractor', 'No Condition -- All Rows Included', array('k0contractor', 'k0user_of_leader', 'c5name', 'c6description'));
    if ($RRC) foreach ($SRC as $K => $V) {
        if (!isset($_SESSION['Scratch']['PlainText']['AssociatedContractorPrimaryKeys']) or !in_array($V['k0contractor'], $_SESSION['Scratch']['PlainText']['AssociatedContractorPrimaryKeys'])) {
            $DisplayInfo[$K] = $Zfpf->entity_name_description_1c($V);
            $SortInfo[$K] = $Zfpf->to_lower_case_1c($DisplayInfo[$K]);
            $_SESSION['SelectResults']['t0contractor'][$K] = $V;
        }
    }
    $Message = '<h2>
    Associate a contractor with '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).', the Owner/Operator</h2>
    <form action="contractor_io03.php" method="post">';
    if (isset($SortInfo)) {
        array_multisort($SortInfo, $DisplayInfo, $_SESSION['SelectResults']['t0contractor']);
        $Message .= '<p>';
        foreach ($_SESSION['SelectResults']['t0contractor'] as $K => $V) {
            if ($K)
                $Message .= '<br />';
            $Message .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if (!$K)
                $Message .= 'checked="checked" ';
            $Message .= '/>'.$DisplayInfo[$K];
        }
        $Message .= '</p><p>
        <input type="submit" name="contractor_associate_2" value="Associate contractor with owner" /></p>';
    }
    else
        $Message .= '<p>
        <b>None found</b>. No contractor records were found, in the PSM-CAP App, that are not already associated with the Owner/Operator.</p>';
    $Message .= '<p>
    Create records for a new contractor and its first admin. This involves creating new contractor, user, user-contractor, owner-contractor, and, if currently selected, user-facility, and user-process records<br />
    <input type="submit" name="contractor_i0n" value="Create records for a new contractor" /></p>';
    if (isset($_SESSION['Scratch']['PlainText']['AssociatedContractorPrimaryKeys']))
        unset($_SESSION['Scratch']['PlainText']['AssociatedContractorPrimaryKeys']);
    echo $Zfpf->xhtml_contents_header_1c().$Message.'
    </form>
    <form action="contractor_i0m.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
if (isset($_POST['contractor_associate_2'])) {
    if (!isset($_SESSION['t0user_owner']) or !isset($_SESSION['StatePicked']['t0owner']) or strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor'])) < strlen(MID_PRIVILEGES_ZFPF) or $UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    $CheckedPost = $Zfpf->post_length_blank_1c('selected'); // Get user selection.
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0contractor'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $NewRow = array(
        'k0owner_contractor' => time().mt_rand(1000000, 9999999),
        'k0owner' => $_SESSION['t0user_owner']['k0owner'],
        'k0contractor' => $_SESSION['SelectResults']['t0contractor'][$CheckedPost]['k0contractor'],
        'c5ts_first_work_awarded' => $EncryptedNothing,
        'c5ts_qual_evaluated' => $EncryptedNothing,
        'c6bfn_owner_notices' => $EncryptedNothing,
        'c6bfn_contractor_notices' => $EncryptedNothing,
        'c6bfn_job_site_audits' => $EncryptedNothing,
        'c5who_is_editing' => $EncryptedNothing
    );
    $Zfpf->one_shot_insert_1s('t0owner_contractor', $NewRow, TRUE, $OChtmlFormArray);
    // Email the contractor leader, the owner leader, and, if different, the current user.
    $ContractorName = $Zfpf->decrypt_1c($_SESSION['SelectResults']['t0contractor'][$CheckedPost]['c5name']);
    $AEFullDescription = 'contractor '.$ContractorName;
    $ContractorLeader = $Zfpf->user_job_info_1c($_SESSION['SelectResults']['t0contractor'][$CheckedPost]['k0user_of_leader']);
    $OwnerLeader = $Zfpf->user_job_info_1c($_SESSION['StatePicked']['t0owner']['k0user_of_leader']);
    $OwnerName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
    unset($_SESSION['SelectResults']);
    $EmailAddresses = array($ContractorLeader['WorkEmail'], $OwnerLeader['WorkEmail']);
    $DistributionList = '<p>
    <b>Distributed To (if an email address was found):</b><br />
    Contractor '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$ContractorLeader['NameTitleEmployerWorkEmail'].'<br />
    Owner '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$OwnerLeader['NameTitleEmployerWorkEmail'];
	$Message = '<p>';
    if ($_SESSION['t0user']['k0user'] != $_SESSION['StatePicked']['t0owner']['k0user_of_leader']) {
        $CurrentUser = $Zfpf->current_user_info_1c();
        $EmailAddresses[] = $CurrentUser['WorkEmail'];
        $DistributionList .= '<br />
        Owner admin who associated the contractor: '.$CurrentUser['NameTitleEmployerWorkEmail'];
        $Message .= $CurrentUser['NameTitleEmployerWorkEmail'];
    }
    else
        $Message .= $OwnerLeader['NameTitleEmployerWorkEmail'];
    $DistributionList .= '</p>';
    $Message .= ' associated:<br />
    - '.$ContractorName.', the contractor,<br />
    - with '.$OwnerName.', the Owner/Operator (aka the owner)</p>';
	$Subject = 'PSM-CAP: '.$AEFullDescription.' associated with owner '.$OwnerName;
    $Body = $Zfpf->email_body_append_1c($Message, $AEFullDescription, '', $DistributionList);
    $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Done</h2>'.$Message.'
    <form action="contractor_i0m.php" method="post"><p>
        <input type="submit" value="Back to contractor list" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i0n code
if (isset($_POST['contractor_i0n'])) {
    // Additional security check.
    if (!isset($_SESSION['t0user_owner']) or strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor'])) < strlen(MID_PRIVILEGES_ZFPF) or $UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $EncryptedMaxPriv = $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF);
    $EncryptedZero = $Zfpf->encrypt_1c(0);
    $EncryptedNewDatabaseRow = $Zfpf->encrypt_1c('[A new database row is being created.]');
    $_SESSION['Selected'] = array(
        'k0contractor' => time().mt_rand(1000000, 9999999),
        'k0user_of_leader' => time().mt_rand(1000000, 9999999), // Make this initial admin the contractor leader with full global and user_contractor c5p_ privileges.
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
    $_SESSION['Scratch']['S_t0owner_contractor'] = array(
        'k0owner_contractor' => time().mt_rand(1000000, 9999999),
        'k0owner' => $_SESSION['t0user_owner']['k0owner'],
        'k0contractor' => $_SESSION['Selected']['k0contractor'],
        'c5ts_first_work_awarded' => $EncryptedNothing,
        'c5ts_qual_evaluated' => $EncryptedNothing,
        'c6bfn_owner_notices' => $EncryptedNothing,
        'c6bfn_contractor_notices' => $EncryptedNothing,
        'c6bfn_job_site_audits' => $EncryptedNothing,
        'c5who_is_editing' => $EncryptedNewDatabaseRow
    );
    $_SESSION['Scratch']['S_t0user_contractor'] = array(
        'k0user_contractor' => time().mt_rand(1000000, 9999999),
        'k0user' => $_SESSION['Selected']['k0user_of_leader'],
        'k0contractor' => $_SESSION['Selected']['k0contractor'],
        'c5job_title' => $EncryptedNothing,
        'c5employee_number' => $EncryptedNothing,
        'c5work_email' => $EncryptedNothing,
        'c5work_phone_mobile' => $EncryptedNothing,
        'c5work_phone_desk' => $EncryptedNothing,
        'c5work_phone_pager' => $EncryptedNothing,
        'c5p_contractor' => $EncryptedMaxPriv,
        'c5p_user' => $EncryptedMaxPriv,
        'c6bfn_general_training' => $EncryptedNothing,
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
        'c5name_family' => $Zfpf->encrypt_1c('[Initial contractor admin -- replace on first logon]'),
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
    // Can only associate this first contractor admin with a facility or process if the current user is associated with one.
    if (isset($_SESSION['t0user_facility'])) {
        $_SESSION['Scratch']['S_t0user_facility'] = array(
            'k0user_facility' => time().mt_rand(1000000, 9999999),
            'k0user' => $_SESSION['Selected']['k0user_of_leader'],
            'k0facility' => $_SESSION['t0user_facility']['k0facility'],
            'k0union' => $_SESSION['t0user_facility']['k0union'], // Initially assign to same as current user, probably correct, can be changed later.
            'c5p_facility' => $EncryptedNothing,
            'c5p_union' => $EncryptedNothing, // Don't give contractor admin any facility or process privileges on setup.
            'c5p_user' => $EncryptedNothing,
            'c5p_process' => $EncryptedNothing,
            'c5who_is_editing' => $EncryptedNewDatabaseRow
        );
    }
    if (isset($_SESSION['t0user_process'])) {
        $_SESSION['Scratch']['S_t0user_process'] = array(
            'k0user_process' => time().mt_rand(1000000, 9999999),
            'k0user' => $_SESSION['Selected']['k0user_of_leader'],
            'k0process' => $_SESSION['t0user_process']['k0process'],
            'c5name' => $EncryptedNothing,
            'c6description' => $EncryptedNothing,
            'c5p_process' => $EncryptedNothing,
            'c5p_user' => $EncryptedNothing,
            'c5who_is_editing' => $EncryptedNewDatabaseRow
        );
    }
    // Set additional htmlFormArrays (HFA) for junction tables only when its really handy if current user provides this information now.
    $HFA_t0owner_contractor = array(
        'c5ts_first_work_awarded' => array('<a id="c5ts_first_work_awarded"></a>Date the Contractor was first awarded work by the Owner', ''),
        'c5ts_qual_evaluated' => array('Date the owner last evaluated the Contractor\'s qualification record (and recorded this in app)', '')
    );
    $HFA_t0user_contractor = array(
        // For this setup HTML form, don't allow editing privileges of the initial user.
        'c5job_title' => array('<a id="c5job_title"></a>Job Title', REQUIRED_FIELD_ZFPF),
        'c5work_email' => array('Work Email', REQUIRED_FIELD_ZFPF),
        'c5work_phone_mobile' => array('Work Mobile Phone', ''),
        'c5work_phone_desk' => array('Work Desk Phone', '')
    );
}

// history_o1 code
if (isset($_POST['contractor_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0contractor']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0contractor', $_SESSION['Selected']['k0contractor']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one contractor record', 'contractor_io03.php', 'contractor_o1'); // This echos and exits.
}

// o1 code
// SPECIAL CASE: isset($FromLinkWithoutPost) handles user clicked link to this file from left-hand contents
if (isset($_POST['contractor_o1']) or isset($FromLinkWithoutPost)) {
    if (isset($_POST['contractor_o1'])) {
        // Additional security check
        if (!isset($_SESSION['Selected']['k0contractor']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0contractor'])))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // $_SESSION cleanup
        if (isset($_SESSION['Scratch']['htmlFormArray']))
            unset($_SESSION['Scratch']['htmlFormArray']);
        if (isset($_SESSION['Post']))
            unset($_SESSION['Post']);
        if (isset($_SESSION['Scratch']['OC'])) {
            $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['OC']['Selected']);
            unset($_SESSION['Scratch']['OC']);
        }
        if (!isset($_SESSION['Selected']['k0contractor'])) {
            // Additional security check
            if (!isset($_SESSION['StatePicked']['t0owner']))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $CheckedPost = $Zfpf->post_length_blank_1c('selected'); // Get user selection.
            if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0contractor'][$CheckedPost]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['Selected'] = $_SESSION['SelectResults']['t0contractor'][$CheckedPost];
            unset($_SESSION['SelectResults']);
        }
        $Zfpf->clear_edit_lock_1c();
    }
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
	// Handle k0 field(s)
    $Display['k0user_of_leader'] = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader'])['NameTitleEmployerWorkEmail'];
    if (($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'] or $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes') and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF) // Current user is contractor leader or app admin.
        $Display['k0user_of_leader'] .= '<br />
        <input type="submit" name="change_psm_leader_1" value="Change '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader" />';
    echo $Zfpf->xhtml_contents_header_1c().'
    <h2>
    Contractor summary</h2>
    <form action="contractor_io03.php" method="post">
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'contractor_io03.php', $_SESSION['Selected'], $Display); // SPECIAL CASE: start form before to include button in $Display
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    else {
        if (isset($_SESSION['t0user_contractor']) and $_SESSION['t0user_contractor']['k0contractor'] == $_SESSION['Selected']['k0contractor'] and $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_contractor']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF) 
            echo '<p>
            <input type="submit" name="contractor_o1_from" value="Update contractor summary" /></p>';
        else
            echo '<p><b>
            Update Privileges Notice</b>: You don\'t have update privileges on this record. Only a contractor\'s employee with adequate privileges may update this record.</p>';
        if (isset($_SESSION['StatePicked']['t0owner'])) {
            echo '<p>
            View notices, job-site audits, qualifications-evaluation history, and other owner-contractor records.<br />
            <input type="submit" name="owner_contractor_o1" value="View owner-contractor records" /></p>';
            if (isset($_SESSION['t0user_owner']) and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF)
                echo '<p>
                Separate contractor from owner, and end access to owner information.<br />
                <input type="submit" name="contractor_separate_1" value="Separate contractor" /></p>';
        }
        else
            echo '<p><b>
            Update and Separate Contractor Notice</b>: You don\'t have privileges to update or delete an owner-contractor record. Separating (deleting) also ends access to the information of an Owner/Operator, its facilities, and its processes, from all the contractor\'s employees and subcontractors.</p>';
        if ($UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to update or delete PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '<p>
        <input type="submit" name="contractor_history_o1" value="History of this record" /></p>
    </form>';    
	if (isset($_SESSION['t0user_owner']))
	    echo '
        <form action="contractor_i0m.php" method="post"><p>
            <input type="submit" value="Back to contractor list" /></p>
        </form>';
	else
		echo '
        <form action="administer1.php" method="post"><p>
            <input type="submit" value="Back to administration" /></p>
        </form>';
    echo $Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// owner_contractor, contractor_separate, change_psm_leader, owner_contractor i1, i2, i3 and contractor i1, i2, i3 code
if (isset($_SESSION['Selected']['k0contractor'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0contractor', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);

    // t0owner_contractor history_o1 code
    if (isset($_POST['owner_contractor_history_o1'])) {
        if (!isset($_SESSION['Scratch']['OC']['Selected']['k0owner_contractor']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
        $HistoryGetZfpf = new HistoryGetZfpf;
        list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0owner_contractor', $_SESSION['Scratch']['OC']['Selected']['k0owner_contractor']);
        $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one owner-contractor record', 'contractor_io03.php', 'owner_contractor_o1'); // This echos and exits.
    }

    // t0owner_contractor download files -- works because t0contractor has no c6bfn files.
    if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
        if (!isset($_SESSION['Scratch']['OC']['Selected']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        if (isset($_POST['download_selected']))
            $Zfpf->download_selected_files_1e($OChtmlFormArray, 'contractor_io03.php', 'owner_contractor_o1', $_SESSION['Scratch']['OC']['Selected']);
        if (isset($_POST['download_all']))
            $Zfpf->download_all_files_1e($OChtmlFormArray, 'contractor_io03.php', 'owner_contractor_o1', $_SESSION['Scratch']['OC']['Selected']);
        $_POST['owner_contractor_o1'] = 1;
    }

    // t0owner_contractor o1 code
    // SPECIAL CASE: (isset($_SESSION['t0user_contractor']) and isset($_POST['problem_c6bfn_files_upload_1e'])) handles a contractor-to-owner notice-upload problem
    if (isset($_POST['owner_contractor_o1']) or (isset($_SESSION['t0user_contractor']) and isset($_POST['problem_c6bfn_files_upload_1e']))) {
        if (!isset($_SESSION['StatePicked']['t0owner']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        if (isset($_SESSION['Scratch']['OC'])) {
            $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['OC']['Selected']);
            unset($_SESSION['Scratch']['OC']);
        }
        $Conditions[0] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor'], 'AND');
        $Conditions[1] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner']);
        list($SROC, $RROC) = $Zfpf->one_shot_select_1s('t0owner_contractor', $Conditions);
        if ($RROC != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['OC']['Selected'] = $SROC[0];
        $Display = $Zfpf->select_to_display_1e($OChtmlFormArray, $_SESSION['Scratch']['OC']['Selected'], TRUE);
        $Display['k0owner'] = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        $Display['k0contractor'] = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
        $Message = '<h2>
        Owner-contractor record</h2>
        '.$Zfpf->select_to_o1_html_1e($OChtmlFormArray, 'contractor_io03.php', $_SESSION['Scratch']['OC']['Selected'], $Display).'
        <form action="contractor_io03.php" method="post">';
        if (isset($_SESSION['t0user_owner']) and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF)
            $Message .= '<p>
            <input type="submit" name="owner_contractor_o1_from" value="Update this record" /></p>';
        elseif (isset($_SESSION['t0user_contractor']) and $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_contractor']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF)
            $Message .= '<p>
            <input type="submit" name="contractor_to_owner_notices_1" value="Upload a contractor-to-owner notice" /></p>';
        else
            $Message .= '<p><b>
            Update Notice</b>: You don\'t have privileges to update this owner-contractor record.</p>';
        $Message .= '<p>
            <input type="submit" name="owner_contractor_history_o1" value="History of this record" /></p><p>
            <input type="submit" name="contractor_o1" value="Back to contractor summary" /></p>
        </form>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // contractor-to-owner notice uploading
    if (isset($_POST['contractor_to_owner_notices_1'])) {
        if (!isset($_SESSION['t0user_contractor']) or !isset($_SESSION['StatePicked']['t0owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_contractor']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]' or !isset($_SESSION['Scratch']['OC']['Selected']))
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $ShtmlFormArray = array('c6bfn_contractor_notices' => array('Contractor-to-owner notices', '', MAX_FILE_SIZE_ZFPF, 'upload_files'));
        $Display['c6bfn_contractor_notices'] = $Zfpf->html_uploaded_files_1e('c6bfn_contractor_notices', 0, $_SESSION['Scratch']['OC']['Selected']);
        // Create HTML form
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Upload contractor-to-owner notices</h2>
        <form action="contractor_io03.php" method="post" enctype="multipart/form-data" >';
        echo $Zfpf->make_html_form_1e($ShtmlFormArray, $Display);
        echo '<p>
            <input type="submit" name="owner_contractor_o1" value="Back to owner-contractor record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['upload_c6bfn_contractor_notices'])) {
        if (!isset($_SESSION['t0user_contractor']) or !isset($_SESSION['StatePicked']['t0owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_contractor']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]' or !isset($_SESSION['Scratch']['OC']['Selected']))
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Directory = $Zfpf->user_files_directory_1e($_SESSION['Scratch']['OC']['Selected']); // $SelectedRow passed in
        $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Scratch']['OC']['Selected']['c6bfn_contractor_notices']);
        $UploadResults = $Zfpf->c6bfn_files_upload_1e($Directory, $c6bfn_array, 'c6bfn_contractor_notices', 'contractor_io03.php', $_SESSION['Scratch']['OC']['Selected']);
        $_SESSION['Scratch']['OC']['Selected']['c6bfn_contractor_notices'] = $UploadResults['new_c6bfn']; // Update with uploaded file information.
        // Email the contractor leader, the owner leader, and, if different, the current user.
        $ContractorLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
        $ContractorName = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
        $OwnerLeader = $Zfpf->user_job_info_1c($_SESSION['StatePicked']['t0owner']['k0user_of_leader']);
        $OwnerName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        $EmailAddresses = array($ContractorLeader['WorkEmail'], $OwnerLeader['WorkEmail']);
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found):</b><br />
        Contractor '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$ContractorLeader['NameTitleEmployerWorkEmail'].'<br />
        Owner '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$OwnerLeader['NameTitleEmployerWorkEmail'];
    	$Message = '<p>';
        if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader']) {
            $CurrentUser = $Zfpf->current_user_info_1c();
            $EmailAddresses[] = $CurrentUser['WorkEmail'];
            $DistributionList .= '<br />
            User who uploaded the notice: '.$CurrentUser['NameTitleEmployerWorkEmail'];
            $Message .= $CurrentUser['NameTitleEmployerWorkEmail'];
        }
        else
            $Message .= $ContractorLeader['NameTitleEmployerWorkEmail'];
        $DistributionList .= '</p>';
        $Message .= ' uploaded a contractor-to-owner notice:<br />
        - from '.$ContractorName.', the contractor,<br />
        - to '.$OwnerName.', the Owner/Operator (aka the owner).</p><p>
        Log onto the PSM-CAP App to review.</p>';
    	$Subject = 'PSM-CAP: contractor-to-owner notice uploaded';
        $Body = $Zfpf->email_body_append_1c($Message, 'contractor '.$ContractorName, '', $DistributionList);
        $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
            Notice uploaded</h2><p>
            The contractor-to-owner notice(s) you selected have been uploaded. You may verify this on the owner-contractor record.</p>
            <form action="contractor_io03.php" method="post"><p>
                <input type="submit" name="owner_contractor_o1" value="Back to owner-contractor record" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // t0owner_contractor i1, i2, i3 code
    if (isset($_POST['owner_contractor_o1_from']) or isset($_POST['oc_undo_confirm_post_1e']) or isset($_POST['oc_modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']) or isset($_POST['upload_c6bfn_owner_notices']) or isset($_POST['upload_c6bfn_job_site_audits']) or (!$_POST and isset($_SESSION['Scratch']['OC']['Post'])) or isset($_POST['owner_contractor_i2']) or isset($_POST['oc_yes_confirm_post_1e'])) {
        if (!isset($_SESSION['t0user_owner']) or !isset($_SESSION['StatePicked']['t0owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]' or !isset($_SESSION['Scratch']['OC']['Selected']))
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $OChtmlFormArray['c6bfn_contractor_notices'] = array('Contractor-to-owner notices', '', C5_MAX_BYTES_ZFPF, 'app_assigned');
        // i1 code -- SPECIAL CASES THROUGH i2 code
        // 1.1 $_SESSION['Scratch']['OC']['Selected'] is only source of $Display.
        if (isset($_POST['owner_contractor_o1_from'])) {
            $_SESSION['Scratch']['OC']['Selected'] = $Zfpf->edit_lock_1c('owner_contractor', 'owner-contractor record', $_SESSION['Scratch']['OC']['Selected']);
            $Display = $Zfpf->select_to_display_1e($OChtmlFormArray, $_SESSION['Scratch']['OC']['Selected'], FALSE, TRUE);
            $Display['k0owner'] = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
            $Display['k0contractor'] = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']); // $_SESSION['Selected'] holds t0contractor row still.
            $Display['c6bfn_contractor_notices'] = 'Only contractors can upload these';
            $_SESSION['Scratch']['OC']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($Display);
            $_SESSION['Scratch']['OC']['Post'] = $_SESSION['Scratch']['OC']['SelectDisplay'];
        }
        // 1.2 $_SESSION['Scratch']['OC']['SelectDisplay'] is source of $Display, the version generated from the database record.
        elseif (isset($_POST['oc_undo_confirm_post_1e'])) {
            if (!isset($_SESSION['Scratch']['OC']['SelectDisplay']))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['OC']['SelectDisplay']);
            $_SESSION['Scratch']['OC']['Post'] = $_SESSION['Scratch']['OC']['SelectDisplay'];
        }
        // 1.3 $_SESSION['Scratch']['OC']['Post'] is source of $Display, the latest user-modified version.  Also used after upload_files.
        elseif (isset($_POST['oc_modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e'])) {
            if (!isset($_SESSION['Scratch']['OC']['Post']))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['OC']['Post']);
        } // START upload_files special case.
        elseif (isset($_POST['upload_c6bfn_owner_notices']) or isset($_POST['upload_c6bfn_job_site_audits'])) {
            if (!isset($_SESSION['Scratch']['OC']['Post']))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            foreach ($OChtmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $Directory = $Zfpf->user_files_directory_1e($_SESSION['Scratch']['OC']['Selected']); // $SelectedRow passed in
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Scratch']['OC']['Selected'][$K]);
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Directory, $c6bfn_array, $K, 'contractor_io03.php', $_SESSION['Scratch']['OC']['Selected']);
                    $_SESSION['Scratch']['OC']['Selected'][$K] = $UploadResults['new_c6bfn']; // Update with uploaded file information.
                    $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['OC']['SelectDisplay']);
                    $SelectDisplay[$K] = $Zfpf->html_uploaded_files_1e($K, 0, $_SESSION['Scratch']['OC']['Selected']); // Update the modified select display
                    $_SESSION['Scratch']['OC']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($SelectDisplay);
                    $_SESSION['Scratch']['OC']['Post'] = $Zfpf->encode_encrypt_1c($Zfpf->post_to_display_1e($OChtmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['OC']['Post']), $UploadResults['count'], $K, $_SESSION['Scratch']['OC']['Selected'])); 
                    // $_SESSION['Scratch']['OC']['Post'] now holds $Display holding $_POST updated with $_SESSION['Scratch']['OC']['Selected']['c6bfn_...'] information.
                    // Email the contractor leader, the owner leader, and, if different, the current user.
                    $ContractorLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
                    $ContractorName = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
                    $OwnerLeader = $Zfpf->user_job_info_1c($_SESSION['StatePicked']['t0owner']['k0user_of_leader']);
                    $OwnerName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
                    $EmailAddresses = array($ContractorLeader['WorkEmail'], $OwnerLeader['WorkEmail']);
                    $DistributionList = '<p>
                    <b>Distributed To (if an email address was found):</b><br />
                    Contractor '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$ContractorLeader['NameTitleEmployerWorkEmail'].'<br />
                    Owner '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$OwnerLeader['NameTitleEmployerWorkEmail'];
                	$Message = '<p>';
                    if ($_SESSION['t0user']['k0user'] != $_SESSION['StatePicked']['t0owner']['k0user_of_leader']) {
                        $CurrentUser = $Zfpf->current_user_info_1c();
                        $EmailAddresses[] = $CurrentUser['WorkEmail'];
                        $DistributionList .= '<br />
                        User who uploaded the notice: '.$CurrentUser['NameTitleEmployerWorkEmail'];
                        $Message .= $CurrentUser['NameTitleEmployerWorkEmail'];
                    }
                    else
                        $Message .= $OwnerLeader['NameTitleEmployerWorkEmail'];
                    $DistributionList .= '</p>';
                    $Message .= ' uploaded an owner-to-contractor notice:<br />
                    - from '.$OwnerName.', the Owner/Operator (aka the owner),<br />
                    - to '.$ContractorName.', the contractor.</p><p>
                    Log onto the PSM-CAP App to review.</p>';
                	$Subject = 'PSM-CAP: owner-to-contractor notice uploaded';
                    $Body = $Zfpf->email_body_append_1c($Message, 'Owner/Operator '.$OwnerName, '', $DistributionList);
                    $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
                    header("Location: #bottom"); // SPECIAL CASE
                    $Zfpf->save_and_exit_1c();
                }
        }
        if (!$_POST) {
            if (!isset($_SESSION['Scratch']['OC']['Post']))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['OC']['Post']); // This re-defines $Display after upload_files header() redirect above.
        } // END uploads_files special case.
        if (isset($Display)) { // This is simplification instead of repeating above $_POST cases or nesting within them.
            // Create HTML form
            echo $Zfpf->xhtml_contents_header_1c().'<h2>
            Owner-contractor record</h2>
            <form action="contractor_io03.php" method="post" enctype="multipart/form-data" >';
            echo $Zfpf->make_html_form_1e($OChtmlFormArray, $Display);
            echo '<p>
                <input type="submit" name="owner_contractor_i2" value="Review what you typed into form" /> If you only wanted to upload files, you are done. Click on "Go Back"</p><p>
                <input type="submit" name="owner_contractor_o1" value="Go back" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        // i2 code, implements the review and confirmation HTML page.
        elseif (isset($_POST['owner_contractor_i2']) and isset($_SESSION['Scratch']['OC']['Post'])) {
            $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['OC']['Post']); // Whitelist from $_SESSION['Scratch']['OC']['Post'] for "add fields" cases.
            $PostDisplay = $Zfpf->post_to_display_1e($OChtmlFormArray, $LastDisplay, 0, FALSE, $_SESSION['Scratch']['OC']['Selected']);
            $_SESSION['Scratch']['OC']['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
            echo $Zfpf->post_select_required_compare_confirm_1e('contractor_io03.php', 'contractor_io03.php', $OChtmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['OC']['SelectDisplay']), 'oc_', 'oc_');
            $Zfpf->save_and_exit_1c();
        }
        // i3 code
        elseif (isset($_POST['oc_yes_confirm_post_1e'])) {
            if (!isset($_SESSION['Scratch']['OC']['Post']) or !isset($_SESSION['Scratch']['ModifiedValues'])) // ...ModifiedValues set by ConfirmZfpf::post_select_required_compare_confirm_1e
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $ChangedRow = $Zfpf->changes_from_post_1c($_SESSION['Scratch']['OC']['Selected']);
            $Conditions[0] = array('k0owner_contractor', '=', $_SESSION['Scratch']['OC']['Selected']['k0owner_contractor']);
            $Affected = $Zfpf->one_shot_update_1s('t0owner_contractor', $ChangedRow, $Conditions, TRUE, $OChtmlFormArray);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
            $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['OC']['Selected']);
            unset($_SESSION['Scratch']['OC']); // SPECIAL CASE unset by owner_contractor_o1 code regardless.
            echo $Zfpf->xhtml_contents_header_1c().'<h2>
            Record updated</h2><p>
            The owner-contractor information you input and reviewed has been recorded.</p>
            <form action="contractor_io03.php" method="post"><p>
                <input type="submit" name="owner_contractor_o1" value="Back to owner-contractor record" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
    }

    // No edit lock because deleting records even if they are being edited. May eject an editing user, but more important no to delay separation.
    if (isset($_POST['contractor_separate_1'])) {
        if (!isset($_SESSION['t0user_owner']) or !isset($_SESSION['StatePicked']['t0owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Separate contractor and end access for all the contractor\'s users to the owner\'s information on this app</h2><p>
        Confirm separation: <br />
        - of '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).', the contractor<br />
        - from '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).', the Owner/Operator (aka the owner).</p><p>
        This causes the app to attempt to delete the owner-contractor record as well as the user-facility, user-process, and user-practice records of all users associated with the contractor, for facilities, processes, and practices associated with the owner. The app should archive all deleted records in its history records.</p>
        <form action="contractor_io03.php" method="post"><p>
            Separate contractor and end access for all the contractor\'s users to the owner\'s information on this app.<br />
            <input type="submit" name="contractor_separate_2" value="Separate contractor" /></p><p>
            <input type="submit" name="contractor_o1" value="Take no action -- go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['contractor_separate_2'])) {
        if (!isset($_SESSION['t0user_owner']) or !isset($_SESSION['StatePicked']['t0owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor']);
        list($SRUC, $RRUC) = $Zfpf->select_sql_1s($DBMSresource, 't0user_contractor', $Conditions);
        $Conditions[1] = array('k0owner', '=', $_SESSION['t0user_owner']['k0owner'], '', 'AND');
        list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions, array('k0owner_contractor'));
        if ($RROC != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        unset($Conditions);
        $LeadersNeeded = '';
        $ContractorName = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']); // Get now, before $_SESSION['Selected'] is changed below.
        $AEFullDescription = 'contractor '.$ContractorName;
        $ContractorLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
        $OwnerLeader = $Zfpf->user_job_info_1c($_SESSION['StatePicked']['t0owner']['k0user_of_leader']);
        $OwnerName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        if ($RRUC) {
            require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
            $UserZfpf = new UserZfpf;
            foreach ($SRUC as $V) {
                $_SESSION['Selected'] = $V; // Needed for separate_user_fp
                $ConditionsUP[] = array('k0user', '=', $V['k0user']);
                $ConditionsUP[] = array('k0owner', '=', $_SESSION['t0user_owner']['k0owner'], 'OR', 'AND ('); // Contractors may do Owner Standard Practices.
                list($LeadersNeededFP, $ConditionsUPFromFP) = $UserZfpf->separate_user_fp($Zfpf, $DBMSresource, $_SESSION['t0user_owner']['k0owner']);
                $LeadersNeeded .= $LeadersNeededFP;
                $ConditionsUP = array_merge($ConditionsUP, $ConditionsUPFromFP);
                // Separate contractor's user from practices.
                $CountCUP = count($ConditionsUP);
                if ($CountCUP == 2) {
                    $ConditionsUP[1][3] = '';
                    $ConditionsUP[1][4] = 'AND';
                }
                if ($CountCUP > 2) {
                    $LastArrayKey = --$CountCUP;
                    $ConditionsUP[$LastArrayKey][3] = ')';
                }
                list($SRUPractice, $RRUPractice) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $ConditionsUP, array('k0user_practice'));
                if ($RRUPractice) foreach ($SRUPractice as $VUPractice) {
                    $Conditions[0] = array('k0user_practice', '=', $VUPractice['k0user_practice']);
                    $Zfpf->delete_sql_1s($DBMSresource, 't0user_practice', $Conditions); // Error checking done by function.
                }
                unset($ConditionsUP);
            }
        }
        $Conditions[0] = array('k0owner_contractor', '=', $SROC[0]['k0owner_contractor']);
        $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0owner_contractor', $Conditions); // Don't delete until after above user separations.
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Selected']);
        // Email the contractor leader, the owner leader, and, if different, the current user.
        $EmailAddresses = array($ContractorLeader['WorkEmail'], $OwnerLeader['WorkEmail']);
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found):</b><br />
        Contractor '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$ContractorLeader['NameTitleEmployerWorkEmail'].'<br />
        Owner '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$OwnerLeader['NameTitleEmployerWorkEmail'];
    	$Message = '<p>';
        if ($_SESSION['t0user']['k0user'] != $_SESSION['StatePicked']['t0owner']['k0user_of_leader']) {
            $CurrentUser = $Zfpf->current_user_info_1c();
            $EmailAddresses[] = $CurrentUser['WorkEmail'];
            $DistributionList .= '<br />
            Owner admin who separated the contractor: '.$CurrentUser['NameTitleEmployerWorkEmail'];
            $Message .= $CurrentUser['NameTitleEmployerWorkEmail'];
        }
        else
            $Message .= $OwnerLeader['NameTitleEmployerWorkEmail'];
        $DistributionList .= '</p>';
        $Message .= ' ordered the separation of:<br />
        - '.$ContractorName.', the contractor,<br />
        - from '.$OwnerName.', the Owner/Operator (aka the owner),<br />
        and ordered ending access for all the contractor\'s users to the owner\'s information on this app.</p><p>
        So, the app attempted to do this by deleting the owner-contractor record as well as the user-facility, user-process, and user-practice records of all users associated with the contractor, for facilities, processes, and practices associated with the owner. The app attempted to archive all deleted records in its history records.</p>';
        if ($LeadersNeeded)
            $Message .= '<p>
            '.$LeadersNeeded.'</p><p>
            A user associated with the separated contractor remains the recorded '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for these.</p>';
        $Message .= '<p>
        You can confirm this via the <a href="user_h_o1.php?facility">user-facility history records</a>.</p>';
    	$Subject = 'PSM-CAP: '.$AEFullDescription.' separated from owner '.$OwnerName;
        $Body = $Zfpf->email_body_append_1c($Message, $AEFullDescription, '', $DistributionList);
        $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Done</h2>'.$Message;
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="contractor_i0m.php" method="post"><p>
            <input type="submit" value="Back to contractor list" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // No edit lock because only PSMeader on an app admin can change the leader.
    if (isset($_POST['change_psm_leader_1'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        echo $Zfpf->xhtml_contents_header_1c('Lookup User');
        $Zfpf->lookup_user_1c('contractor_io03.php', 'contractor_io03.php', 'change_psm_leader_2', 'contractor_o1');
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['change_psm_leader_2'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $TableNameUserEntity = 't0user_contractor';
        $Conditions1[0] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor']);
        $SpecialText = '<p><b>
        Pick '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader</b></p><p>
        The current '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader will be replaced by the user you pick above.</p>';
        $Zfpf->lookup_user_wrap_2c(
            $TableNameUserEntity,
            $Conditions1,
            'contractor_io03.php', // $SubmitFile
            'contractor_io03.php', // $TryAgainFile
            array('k0user'), // $Columns1
            'contractor_io03.php', // $CancelFile
            $SpecialText,
            'Assign '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader', // $SpecialSubmitButton
            'change_psm_leader_3', // $SubmitButtonName
            'change_psm_leader_1', // $TryAgainButtonName
            'contractor_o1', // $CancelButtonName
            'c5ts_logon_revoked', // $FilterColumnName
            '[Nothing has been recorded in this field.]' // $Filter
        ); // This function echos and exits.
    }
    if (isset($_POST['change_psm_leader_3'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
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
            You did not select a someone different</h2>
            <form action="contractor_io03.php" method="post"><p>
                <input type="submit" name="change_psm_leader_1" value="Try again" /></p><p>
                <input type="submit" name="contractor_o1" value="Back to contractor summary" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        $Conditions[0] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor']);
        $ShtmlFormArray['k0user_of_leader'] = $htmlFormArray['k0user_of_leader'];
        $Affected = $Zfpf->one_shot_update_1s('t0contractor', $Changes, $Conditions, TRUE, $ShtmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        // Email the current and former leaders and, if applicable, current user, who must be an app admin.
        $AEFullDescription = 'contractor '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
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
        if (isset($_SESSION['StatePicked']['t0contractor']))
            $_SESSION['StatePicked']['t0contractor']['k0user_of_leader'] = $Changes['k0user_of_leader'];
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
        <form action="contractor_io03.php" method="post"><p>
            <input type="submit" name="contractor_o1" value="Back to viewing record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // t0contractor i1, i2, i3 code
    if ($UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF or ($who_is_editing != '[A new database row is being created.]' and (!isset($_SESSION['t0user_contractor']) or $_SESSION['t0user_contractor']['k0contractor'] != $_SESSION['Selected']['k0contractor'] or $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_contractor']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['contractor_i0n']) or isset($_POST['contractor_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        $Display['k0user_of_leader'] = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader'])['NameTitleEmployerWorkEmail'];
        if ($who_is_editing == '[A new database row is being created.]') { // Update $Display and $htmlFormArray for all i0n database tables.
            $Display = array_merge($Display, $Zfpf->select_to_display_1e($HFA_t0owner_contractor, $_SESSION['Scratch']['S_t0owner_contractor'], FALSE, TRUE));
            $Display = array_merge($Display, $Zfpf->select_to_display_1e($HFA_t0user_contractor, $_SESSION['Scratch']['S_t0user_contractor'], FALSE, TRUE));
            $htmlFormArray = array_merge($htmlFormArray, $HFA_t0owner_contractor, $HFA_t0user_contractor);
        }
        if (isset($_POST['contractor_o1_from'])) {
            $Zfpf->edit_lock_1c('contractor', $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).' contractor summary'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. Not needed for i1n.
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
    if ($who_is_editing == '[A new database row is being created.]') {
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
    }
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
		Contractor Summary</h1>
        <form action="contractor_io03.php" method="post">';
	    echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
	    if ($who_is_editing == '[A new database row is being created.]')
            echo '
            '.$UserZfpf->username_password_html_form('k2username_hash', 's5password_hash');
        echo '<p>
		    <input type="submit" name="contractor_i2" value="Review what you typed into form" /></p>
		</form>';
		if ($who_is_editing == '[A new database row is being created.]')
		    echo '
            <form action="contractor_i0m.php" method="post"><p>
                <input type="submit" value="Back to contractor list" /></p>
            </form>';
		else
    		echo '
		    <form action="contractor_io03.php" method="post"><p>
		        <input type="submit" name="contractor_o1" value="Back to viewing record" /></p>
		    </form>';
		echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['contractor_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        if ($who_is_editing == '[A new database row is being created.]') {
            $NewCredentials = $UserZfpf->username_password_check($Zfpf, FALSE, 'k2username_hash', 's5password_hash');
            if ($NewCredentials['Message']) {
                $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay); // Save any user-posted info before exiting.
                echo $Zfpf->xhtml_contents_header_1c('Error').$NewCredentials['Message'].'
                <form action="contractor_io03.php" method="post"><p>
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
	    echo $Zfpf->post_select_required_compare_confirm_1e('contractor_io03.php', 'contractor_io03.php', $htmlFormArray, $PostDisplay, $SelectDisplay);
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c(); // Oldstuff, the first paramter, defaults to $_SESSION['Selected']. Only outputs fields in OldStuff.
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') {
            // Additional security check
            if (!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor']) != MAX_PRIVILEGES_ZFPF or !isset($_SESSION['Scratch']['S_t0owner_contractor']))
                $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
            $Zfpf->insert_sql_1s($DBMSresource, 't0contractor', $ChangedRow);
            $Zfpf->insert_sql_1s($DBMSresource, 't0owner_contractor', $Zfpf->changes_from_post_1c($_SESSION['Scratch']['S_t0owner_contractor']));
            $Zfpf->insert_sql_1s($DBMSresource, 't0user_contractor', $Zfpf->changes_from_post_1c($_SESSION['Scratch']['S_t0user_contractor']));
            $Zfpf->insert_sql_1s($DBMSresource, 't0user', $Zfpf->changes_from_post_1c($_SESSION['Scratch']['S_t0user'])); // Handles k2 and s5 fields
            if (isset($_SESSION['Scratch']['S_t0user_facility']))
                $Zfpf->insert_sql_1s($DBMSresource, 't0user_facility', $Zfpf->changes_from_post_1c($_SESSION['Scratch']['S_t0user_facility']));
            if (isset($_SESSION['Scratch']['S_t0user_process']))
                $Zfpf->insert_sql_1s($DBMSresource, 't0user_process', $Zfpf->changes_from_post_1c($_SESSION['Scratch']['S_t0user_process']));
            unset($_SESSION['Scratch']['S_t0owner_contractor']); // Just to trip above security check, if hacker.
            // INSERT Contractor Standard Practices into junction table.
            $Conditions[0] = array('c2standardized', '=', 'Contractor Standard Practice');
            list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions);
            if ($RR) {
                $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
                foreach ($SR as $K => $V) {
                    $TemplateContractorPractice[] = array(
                        'k0contractor_practice' => time().$K.mt_rand(1000, 9999), // $K needed because time() may return the same value each pass.
                        'k0contractor' => $_SESSION['Selected']['k0contractor'],
                        'k0practice' => $V['k0practice'],
                        'c5who_is_editing' => $EncryptedNobody
                    );
                    $TemplateUserPractice[] = array(
                        'k0user_practice' => time().$K.mt_rand(1000, 9999),
                        'k0user' => $_SESSION['Scratch']['S_t0user']['k0user'],
                        'k0practice' => $V['k0practice'],
                        'k0owner' => 0,
                        'k0contractor' => $_SESSION['Selected']['k0contractor'],
                        'k0facility' => 0,
                        'k0process' => 0,
                        'c5p_practice' => $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF),
                        'c5who_is_editing' => $EncryptedNobody
                    );
                }
                foreach ($TemplateContractorPractice as $V)
                    $Zfpf->insert_sql_1s($DBMSresource, 't0contractor_practice', $V);
                foreach ($TemplateUserPractice as $V)
                    $Zfpf->insert_sql_1s($DBMSresource, 't0user_practice', $V);
            }
        }
        else {
            // Additional security check
            if (!isset($_SESSION['t0user_contractor']) or $_SESSION['t0user_contractor']['k0contractor'] != $_SESSION['Selected']['k0contractor'] or $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_contractor']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
            $Conditions[0] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0contractor', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Record(s) updated</h2><p>
        The contractor information you input and reviewed has been recorded.</p>
        <form action="contractor_io03.php" method="post"><p>
            <input type="submit" name="contractor_o1" value="Back to record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // Ends owner_contractor, contractor_separate, change_psm_leader, owner_contractor i1, i2, i3 and contractor i1, i2, i3 code

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

