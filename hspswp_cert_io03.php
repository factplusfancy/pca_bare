<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the hspswp_cert input and output HTML forms, except the:
//  - i1m file for listing existing records (and giving the option to start a new record) and

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'hspswp_cert_i1m.php' or !isset($_SESSION['StatePicked']['t0owner']) or !isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['StatePicked']['t0process']) or !isset($_SESSION['t0user_practice'])) // Process must be selected, so facility and owner must too.
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get information needed for most cases.
$Process = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['StatePicked']['t0process']['k0process']); // The PSM-CAP App requires hspswp_cert to be associated with a process. See above general security check.

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
// SPECIAL CASE, no $htmlFormArray, except in downloads and o1 code, for as certified field. Instead need:
$Scope = '<p>
This certification covers only hazardous-substance procedures and safe-work practices for tasks people do involving hazardous substances at:<br />
* '.$Process['AEFullDescription'].'<p>';
$Instructions = '</p>
Before making this certification, either yourself or with a qualified team:<br />
(1) review the PSM-CAP App compliance practices describing the hazardous-substance procedures and safe-work practices for the above process (the HSPSWP),<br />
(2) verify that the PSM-CAP App compliance practices have linked to them (via a practice document) or accurately describe the location of the HSPSWP,<br />
(3) retrieve and review the latest copies of the HSPSWP,<br />
(4) verify that the HSPSWP are current and accurate. Reviewing change-management documentation and any P&amp;ID revisions since the last certification may help with this.</p>';

//Left hand Table of contents
// SPECIAL CASE: none

// The if clauses below determine which HTML button the user pressed.

// i0n code
// SPECIAL CASE: moved below o1 code

// history_o1 code
if (isset($_POST['hspswp_cert_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0certify']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0certify', $_SESSION['Selected']['k0certify']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one certification record', 'hspswp_cert_io03.php', 'hspswp_cert_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0certify']) or $Zfpf->decrypt_1c($_SESSION['Selected']['c6bfn_as_certified']) == '[Nothing has been recorded in this field.]') // SPECIAL CASE: Only download is c6bfn_as_certified, which must be present in o1 output. c6bfn fields hold, encrypted, and encoded array or '[Nothing has been recorded in this field.]'
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // Create $htmlFormArray for c6bfn_as_certified. Only need the c6bfn fields for the download files functions below.
    $htmlFormArray['c6bfn_as_certified'] = array('As-certified copy', '', MAX_FILE_SIZE_ZFPF, 'upload_files'); // Nothing can be uploaded, just downloaded.
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'hspswp_cert_io03.php', 'hspswp_cert_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'hspswp_cert_io03.php', 'hspswp_cert_o1');
    $_POST['hspswp_cert_o1'] = 1; // Only needed as catch, after minor stuff, like user forgot to select a file before downloading, or password check before download.
}

// o1 code
if (isset($_POST['hspswp_cert_o1'])) {
    // Additional security check.
    if (!isset($_SESSION['Selected']['k0certify']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0certify'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    // $Zfpf->clear_edit_lock_1c(); // SPECIAL CASE: No editing, only the certification.
    if (!isset($_SESSION['Selected']['k0certify'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0certify'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0certify'][$CheckedPost];
        unset($_SESSION['SelectResults']);
    }
    if ($_SESSION['Selected']['k0user_of_ae_leader'] == 0 or $Zfpf->decrypt_1c($_SESSION['Selected']['c6bfn_as_certified']) == '[Nothing has been recorded in this field.]') // SPECIAL CASE: see comments above, under Download files.
        $Zfpf->send_to_contents_1c(); // Don't eject
    $htmlFormArray['c6bfn_as_certified'] = array('As-certified copy', '', MAX_FILE_SIZE_ZFPF, 'upload_files');
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Selected'], TRUE);
    // Handle k0 field(s)
    $htmlFormArray['k0user_of_ae_leader'] = array('<b>Current and accurate certification for hazardous-substance procedures and safe-work practices done by</b>', '');
    $Display['k0user_of_ae_leader'] = $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_ae_leader']);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Current and accurate certification for hazardous-substance procedures and safe-work practices</h2>
    '.$Scope.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'hspswp_cert_io03.php', $_SESSION['Selected'], $Display).'
    <form action="hspswp_cert_io03.php" method="post"><p>
        <input type="submit" name="hspswp_cert_history_o1" value="History of this record" /></p>
    </form>
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// Process leader certifying 1
// SPECIAL CASE: all i0n code here with certification confirmation page.
if (isset($_POST['hspswp_cert_i0n'])) {
    // Additional security check.
    if ($_SESSION['t0user']['k0user'] != $Process['AELeader_k0user'] or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    // Get useful information.
    $CurrentDate = date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j', time());    
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    // Initialize $_SESSION['Selected']
    $_SESSION['Selected'] = array(
        'k0certify' => time().mt_rand(1000000, 9999999),
        'k0process' => $_SESSION['StatePicked']['t0process']['k0process'],
        'k0user_of_ae_leader' => 0,
        'c5ts_ae_leader' => $EncryptedNothing,
        'c6nymd_ae_leader' => $EncryptedNothing,
        'c6bfn_as_certified' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
    $ApprovalText = '<h2>
    Current and accurate certification for hazardous-substance procedures and safe-work practices</h2>
    '.$Scope.$Instructions.'<p>
    <b>This certification cannot be canceled.</b> You may correct errors in hazardous-substance procedures and safe-work practices later and re-certify. Complete change management for corrections that alter tasks people do. For minor corrections or clarifications, you may skip the change-management documentation and instead only document training of affected employees or contractors on the latest, corrected, hazardous-substance procedures and safe-work practices.</p><p>
    <b>By clicking "certify" below, as the '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for '.$Process['AEFullDescription'].', I am confirming that:<br />
     - the hazardous-substance procedures and safe-work practices, for the the above process, are current and accurate,<br />
     - I verified, myself or through others whose qualifications I determined are adequate, that everything needed to safely make this certification has been completed, and<br />
     - I am qualified to do this.</b></p><p>
    <b>Approved By:</b><br />
    Name: <b>'.$Process['AELeaderName'].'</b><br />
    Job Title: <b>'.$Process['AELeaderTitle'].'</b><br />
    Employer<b>: '.$Process['AELeaderEmployer'].'</b><br />
    Email Address<b>: '.$Process['AELeaderWorkEmail'].'</b><br />
    Date: <b>'.$CurrentDate.'</b></p>';
    echo $Zfpf->xhtml_contents_header_1c().$ApprovalText.'
    <form action="hspswp_cert_io03.php" method="post"><p>
        <input type="submit" name="psm_leader_approval_2" value="Certify" /></p>
    </form>
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Take no action -- go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
    $_SESSION['Scratch']['c6nymd_ae_leader'] = $Zfpf->encrypt_1c($Process['AELeaderNameTitleEmployerWorkEmail'].' on '.$CurrentDate);
    $Zfpf->save_and_exit_1c();
}

// Process leader certifying 2
if (isset($_POST['psm_leader_approval_2'])) {
    // Additional security check.
    if (!isset($_SESSION['Selected']['k0certify']) or $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) != '[A new database row is being created.]' or $_SESSION['t0user']['k0user'] != $Process['AELeader_k0user'] or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    $BaseFileName = 'psm_hspswp_certification'.time().'.htm'; // No need for FilesZfpf::xss_prevent_1c() because this name doesn't have < > or &.
    $BytesWritten = $Zfpf->write_file_1e($_SESSION['Scratch']['ApprovalText'], $BaseFileName);
    $BytesAttempted = strlen($_SESSION['Scratch']['ApprovalText']);
    $BytesWrittenErrorMessage = '';
    if ($BytesWritten < $BytesAttempted) {
        $BytesWrittenErrorMessage = ' <br />BUT, TAKE NOTE: <b>only part of</b> the certification may have been successfully saved. You may be able to recover a complete copy from the email sent to you upon certification. Otherwise, you may have to re-certify.';
        error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. Certification truncated when written to computer file.');
    }
    if ($BytesWritten > $BytesAttempted) {
        $BytesWrittenErrorMessage = ' <br />BUT, TAKE NOTE: the certification may <b>not</b> have been properly saved (what was written to the computer file was reportedly <b>larger than</b> the certification). You may be able to recover a complete copy from the email sent to you upon certification. Otherwise, you may have to re-certify.';
        error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. Certification as written to computer file was longer than actual certification.');
    }
    $c6bfn_array = array(
        $BaseFileName => array(
            $BaseFileName,
            $Process['AELeaderNameTitleEmployerWorkEmail']
        )
    );
    $_SESSION['Selected']['k0user_of_ae_leader'] = $_SESSION['t0user']['k0user'];
    $_SESSION['Selected']['c5ts_ae_leader'] = $Zfpf->encrypt_1c(time());    
    $_SESSION['Selected']['c6nymd_ae_leader'] = $_SESSION['Scratch']['c6nymd_ae_leader'];
    $_SESSION['Selected']['c6bfn_as_certified'] = $Zfpf->encode_encrypt_1c($c6bfn_array);
    $_SESSION['Selected']['c5who_is_editing'] = $Zfpf->encrypt_1c('PERMANENTLY LOCKED: This is an approved certification, so it cannot be edited.');
    $Zfpf->one_shot_insert_1s('t0certify', $_SESSION['Selected']);
    // Email the the process, facility, and owner leaders.
    $Chain = $Zfpf->up_the_chain_1c();
    $Subject = 'PSM-CAP: Current and accurate certification for hazardous-substance procedures and safe-work practices';
    $Body = '<p>
    The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader made the:<br />
    * current and accurate certification for hazardous-substance procedures and safe-work practices<br />
    * for '.$Process['AEFullDescription'].'<br />
    The app attempted to save copy of the approved certification document.
    '.$BytesWrittenErrorMessage.'</p>';
    $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $Chain['DistributionList']);
    $EmailSent = $Zfpf->send_email_1c($Chain['EmailAddresses'], $Subject, $Body);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Current and accurate certification for hazardous-substance procedures and safe-work practices</h2><p>
    You just made the:<br />
    * current and accurate certification for hazardous-substance procedures and safe-work practices<br />
    * for '.$Process['AEFullDescription'].'<br />
    The app attempted to save copy of the approved certification document.
    '.$BytesWrittenErrorMessage.'</p>';
    if ($EmailSent)
        echo '<p>You and others involved should soon receive an email confirming this.</p>';
    else
        echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
    echo '
    <form action="hspswp_cert_io03.php" method="post"><p>
        <input type="submit" name="hspswp_cert_o1" value="View certification" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

$Zfpf->catch_all_1c('practice_o1.php');
$Zfpf->save_and_exit_1c();

