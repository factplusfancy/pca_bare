<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the contractor_priv input and output HTML forms, except the:
//  - i1m file for listing existing records (and giving the option to start a new record) 

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'contractor_priv_i1m.php' or !isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get current-user information...
$User = $Zfpf->current_user_info_1c();
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
	'k0user_facility' => array(
       '<a class="toc" href="glossary.php#contractor" target="_blank">Contractor</a> individual being granted privileges at Facility',
       ''
    ),
    'c6bfn_facility_training' => array(
        '<a id="c6bfn_facility_training"></a><b>Facility-Specific Training Records.</b><br />
        Every record shall include the:<br />
        - name and employer of the contractor individual,<br />
        - date of the training, and<br />
        - means used to verify that the contractor individual understood the training.<br />
        Either facility-specific training (recorded here) or general training (recorded with the user-contractor record) shall cover:<br />
        (1) facility emergency-plan training, appropriate for where and what this individual is authorized to go and do, at a minimum:<br />
        (1.1) how to call for help in emergencies,<br />
        (1.2) recognizing facility alarms,<br />
        (1.3) moving to safety (evacuation routes, shelter-in-place locations...)<br />
        (2) facility sign-in/sign-out requirements and any other access controls,<br />
        (3) known fire, explosion, toxic, or unusual hazards related to assigned tasks and the process,<br />
        (4) work practices necessary to safely perform assigned tasks',
        '',
        MAX_FILE_SIZE_ZFPF,
        'upload_files'
    ),
    'c6bfn_facility_agreements' => array(
        '<a id="c6bfn_facility_agreements"></a><b>Facility Agreements for Contractor Individuals.</b> Agreements or acknowledgments that the Owner/Operator may require each contractor individual to approve, such as on safety, hygiene, confidentiality, photography, recording devices, smart phones, etc., before they may enter the facility',
        '',
        MAX_FILE_SIZE_ZFPF,
        'upload_files'
    ),
    'c6bfn_injury_records' => array(
        '<a id="c6bfn_injury_records"></a><b>Injury or illness records.</b> For any injuries or illnesses related to a contractor individual\'s work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.' that required medical treatment beyond first aid, the Owner/Operator shall either make a record or shall obtain and check the quality of the record the contractor made. Often, both Owner/Operator and contractor policies describe their thresholds for injury or illness recordkeeping, which may include near misses, and which may be stored in separate medical-records systems. Unless prohibited by medical-records confidentiality rules, you may upload injury or illness reports here, or upload a reference to a separate medical-records system where this information is located',
        '',
        MAX_FILE_SIZE_ZFPF,
        'upload_files'
    ),
    'c5process_priv' => array(
        '<a id="c5process_priv"></a><b>Process Privileges.</b> Optional description of specific process areas that this contractor individual may enter',
        ''
    ),
	'c5job_site_priv' => array(
        '<a id="c5job_site_priv"></a><b>Job Site Privileges.</b> Optional description of specific job sites that this contractor individual may enter',
        ''
    ),
	'c6notes' => array(
        '<a id="c6notes"></a><b>Notes about the relationship or history between the facility and the contractor individual</b>',
        '',
        C6SHORT_MAX_BYTES_ZFPF
    )
);

//Left hand Table of contents
if (!isset($_POST['contractor_priv_i2']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c6bfn_facility_training' => 'Facility training', 
        'c6bfn_facility_agreements' => 'Facility agreements', 
        'c6bfn_injury_records' => 'Injury records', 
        'c5process_priv' => 'Process privileges', 
        'c5job_site_priv' => 'Job-site privileges', 
        'c6notes' => 'Notes'
    );

// The if clauses below determine which HTML button the user pressed.

// i0n code 
if (isset($_POST['contractor_priv_i0n'])) {
	// Additional security check.
    if (!isset($_SESSION['Scratch']['t0user_facility']) or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
	// Initialize $_SESSION['Selected']
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $_SESSION['Selected'] = array(
		'k0contractor_priv' => time().mt_rand(1000000, 9999999),
        'k0user_facility' => $_SESSION['Scratch']['t0user_facility']['k0user_facility'],
        'c6bfn_facility_training' => $EncryptedNothing,
	    'c6bfn_facility_agreements' => $EncryptedNothing,
	    'c6bfn_injury_records' => $EncryptedNothing,
	    'c5process_priv' => $EncryptedNothing,
        'c5job_site_priv' => $EncryptedNothing,
        'c6notes' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
	);
}

// history_o1 code
if (isset($_POST['contractor_priv_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0contractor_priv']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0contractor_priv', $_SESSION['Selected']['k0contractor_priv']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of Entrance Privileges and Records of a Contractor Individual', 'contractor_priv_io03.php', 'contractor_priv_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0contractor_priv']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'contractor_priv_io03.php', 'contractor_priv_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'contractor_priv_io03.php', 'contractor_priv_o1');
    $_POST['contractor_priv_o1'] = 1;
}

// o1 code
if (isset($_POST['contractor_priv_o1'])) {
	// Additional security check.
    if ((!isset($_SESSION['Selected']['k0contractor_priv']) or !isset($_SESSION['Scratch']['t0user_facility'])) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0user_facility'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (!isset($_SESSION['Selected']['k0contractor_priv'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0user_facility'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0user_facility'] = $_SESSION['SelectResults']['t0user_facility'][$CheckedPost];
        $_SESSION['Scratch']['k0contractor'] = $_SESSION['SelectResults']['Key'][$CheckedPost]['k0contractor'];
        unset($_SESSION['SelectResults']);
        $Conditions[0] = array('k0user_facility', '=', $_SESSION['Scratch']['t0user_facility']['k0user_facility']);
        list($SR, $RR) = $Zfpf->one_shot_select_1s('t0contractor_priv', $Conditions);
        if ($RR > 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows returned: '.@$RR);
        elseif ($RR == 1)
            $_SESSION['Selected'] = $SR[0];
        else { // Echo HTML with i0n button
            echo $Zfpf->xhtml_contents_header_1c().'<h2>
            Entrance Privileges and Records of a Contractor Individual</h2><p>
            <b>None found.</b> No record on this was found, for the contractor individual and facility that you selected.</p>';
            if ($User['GlobalDBMSPrivileges'] != LOW_PRIVILEGES_ZFPF) // Need at least INSERT global privileges to start a new record.
                echo '<p>
                You may start a record because the contractor individual has been associated with the facility in this app.</p>
                <form action="contractor_priv_io03.php" method="post"><p>
                    <input type="submit" name="contractor_priv_i0n" value="Start new record" /></p>
                </form>';
            else
                echo '<p><b>
                Global Privileges Notice</b>: You have privileges to neither create nor edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
            echo '
                <form action="practice_o1.php" method="post"><p>
                    <input type="submit" value="Go back" /></p>
                </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
    }
    $Zfpf->clear_edit_lock_1c();
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
	// Handle k0 field(s)
    $ContractorIndividual = $Zfpf->user_job_info_1c($_SESSION['Scratch']['t0user_facility']['k0user']);
    $Display['k0user_facility'] = $ContractorIndividual['NameTitleEmployerWorkEmail'];
	echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Entrance Privileges and Records of a Contractor Individual</h2>
    <form action="user_io03.php" method="post"><p>
        <input type="submit" name="user_o1" value="View emergency and personal contacts" /></p>
    </form>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'contractor_priv_io03.php', $_SESSION['Selected'], $Display).'
    <form action="user_contractor_io03.php" method="post"><p>
        View general-training records for this contractor individual.<br />
        <input type="submit" name="user_contractor_o1" value="View general-training records" /></p>
    </form>';
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    elseif ($User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF) 
        echo '
        <form action="contractor_priv_io03.php" method="post"><p>
            <input type="submit" name="contractor_priv_o1_from" value="Update this record" /></p>
        </form>';
    else {
        if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
        if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '
    <form action="contractor_priv_io03.php" method="post"><p>
        <input type="submit" name="contractor_priv_history_o1" value="History of this record" /></p>
    </form>
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, i3 code
if (isset($_SESSION['Selected']['k0contractor_priv'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0contractor_priv', '=', $_SESSION['Selected']['k0contractor_priv']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0contractor_priv', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    // Additional security check
    if (!isset($_SESSION['Scratch']['t0user_facility']) or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or ($who_is_editing != '[A new database row is being created.]' and $User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF or $UserPracticePrivileges != MAX_PRIVILEGES_ZFPF))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    if (isset($_POST['contractor_priv_o1_from']))
        $Zfpf->edit_lock_1c('contractor_priv'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error.
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['contractor_priv_i0n']) or isset($_POST['contractor_priv_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        $ContractorIndividual = $Zfpf->user_job_info_1c($_SESSION['Scratch']['t0user_facility']['k0user']);
        $Display['k0user_facility'] = $ContractorIndividual['NameTitleEmployerWorkEmail'];
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
    // START upload_files special case 1 of 3.
    elseif (isset($_SESSION['Post']) and !isset($_POST['contractor_priv_i2'])) { // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        else {
            $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            foreach ($htmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Selected'][$K]);
                    // FilesZfpf::user_files_directory_1e has been checked, it supports t0contractor_priv
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, $K, 'contractor_priv_io03.php');
                    // FilesZfpf::6bfn_files_upload_1e updates $_SESSION['Selected'] and the database. 
                    // Or, it echos an error page an user has option to press $_POST['problem_c6bfn_files_upload_1e'] button.
                    $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
                    $SelectDisplay[$K] = $Zfpf->html_uploaded_files_1e($K); // Update the modified select display
                    $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($SelectDisplay);
                    $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($Zfpf->post_to_display_1e($htmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Post']), $UploadResults['count'], $K));
                    // $_SESSION['Post'] now holds $Display holding $_POST updated with $_SESSION['Selected']['c6bfn_...'] information.
                    header("Location: #$K"); // AN ANCHOR MUST BE SET FOR ALL upload_files FIELDS
                    $Zfpf->save_and_exit_1c();
                }
        }
    }
    if (!$_POST and isset($_SESSION['Post']))
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // This re-defines $Display after upload_files header() redirect above.
    // END upload_files special case 1 of 3.
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        // Create HTML form
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Entrance Privileges and Records of a Contractor Individual</h2>
        <form action="contractor_priv_io03.php" method="post" enctype="multipart/form-data" >'; // upload_files special case 2 of 3. To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
            <input type="submit" name="contractor_priv_i2" value="Review what you typed into form" /><br />
            If you only wanted to upload files, you are done. Click on "Go Back"</p>
        </form>'; // upload_files special case 3 of 3.
        if ($who_is_editing == '[A new database row is being created.]')
            echo '
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>';
        else
            echo '
            <form action="contractor_priv_io03.php" method="post"><p>
                <input type="submit" name="contractor_priv_o1" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['contractor_priv_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('contractor_priv_io03.php', 'contractor_priv_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']), '', '', $who_is_editing);
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]')
            $Zfpf->insert_sql_1s($DBMSresource, 't0contractor_priv', $ChangedRow);
        else {
            $Conditions[0] = array('k0contractor_priv', '=', $_SESSION['Selected']['k0contractor_priv']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0contractor_priv', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Entrance Privileges and Records of a Contractor Individual</h2><p>
        The information you input and reviewed has been recorded in the contractor-individual\'s record for the currently selected facility.</p>
        <form action="contractor_priv_io03.php" method="post"><p>
            <input type="submit" name="contractor_priv_o1" value="Back to record" /></p>
        </form>
        <form action="practice_o1.php" method="post"><p>
            <input type="submit" value="Back to records list" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, i3, and approval code

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

