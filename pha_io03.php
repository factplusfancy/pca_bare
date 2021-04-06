<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the PHA input and output HTML forms, except the:
//  - i1m file for listing existing records (and giving the option to start a new record) and

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'pha_i1m.php' or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// SPECIAL CASE -- cleanup if user hit "go back" from subprocess, scenario...
if (isset($_SESSION['Scratch']['t0subprocess']))
    unset($_SESSION['Scratch']['t0subprocess']);
if (isset($_SESSION['Scratch']['t0scenario']))
    unset($_SESSION['Scratch']['t0scenario']);
if (isset($_SESSION['Scratch']['t0cause']))
    unset($_SESSION['Scratch']['t0cause']);
if (isset($_SESSION['Scratch']['t0consequence']))
    unset($_SESSION['Scratch']['t0consequence']);
if (isset($_SESSION['Scratch']['t0safeguard']))
    unset($_SESSION['Scratch']['t0safeguard']);
if (isset($_SESSION['Scratch']['t0action']))
    unset($_SESSION['Scratch']['t0action']);

// Get current-user information...
$User = $Zfpf->current_user_info_1c();
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$EditAuth = FALSE;
if (isset($_SESSION['Selected']['k0pha']) and
    $User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and 
        (
            ($_SESSION['Selected']['k0pha'] >= 100000 and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF) or 
            ($_SESSION['Selected']['k0pha'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes')
        )
   )
    $EditAuth = TRUE;
$EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
$UserIsProcessPSMLeader = FALSE; // Caution, record not yet selected, need to reset to FALSE for templates.
if (isset($_SESSION['StatePicked']['t0process']['k0process'])) { // This app requires PHAs, except templates, to be associated with a process.
    $Process = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['StatePicked']['t0process']['k0process']);
    if ($_SESSION['t0user']['k0user'] == $Process['AELeader_k0user'])
        $UserIsProcessPSMLeader = TRUE;
}

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'k0user_of_leader' => array('<a id="k0user_of_leader"></a>Team leader', ''), // Intentionally out of order in schema.
    'c6bfn_act_notice' => array('<a id="c6bfn_act_notice"></a>Activity notice(s) posted', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
    'c6bfn_attendance' => array('<a id="c6bfn_attendance"></a>Team attendance records (required) and any other supporting materials', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
    'c6background' => array('<a id="c6background"></a>Background on the process, applicable PHA rules, and any prior PHA or revisions history', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF),
    'c6team_qualifications' => array('<a id="c6team_qualifications"></a>Team qualifications', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF),
    'c6method' => array('<a id="c6method"></a>Method', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF),
    'c6prior_incident_id' => array('<a id="c6prior_incident_id"></a>Identification of applicable previous incidents', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF)
);

//Left hand Table of contents
if (!isset($_POST['pha_i2']) and !isset($_POST['team_leader_approval_2']) and !isset($_POST['change_team_leader_1']) and !isset($_GET['change_team_leader_1']) and !isset($_POST['change_team_leader_2']) and !isset($_POST['change_team_leader_3']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'k0user_of_leader' => 'Team leader',
        'c6bfn_act_notice' => 'Activity notice',
        'c6bfn_attendance' => 'Attendance',
        'c6background' => 'Background', 
        'c6team_qualifications' => 'Qualifications',
        'c6method' => 'Method',
        'c6prior_incident_id' => 'Previous incidents'
    );

// The if clauses below determine which HTML button the user pressed.

// generate activity notice code, put before i0n initialization of $_SESSION['Selected'], for security check below.
if (isset($_GET['act_notice_1'])) {
    if (!isset($_SESSION['Selected']['k0pha']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/ActNoticeZfpf.php';
    $ActNoticeZfpf = new ActNoticeZfpf;
    echo $ActNoticeZfpf->act_notice_generate($Zfpf, 'pha');
    $Zfpf->save_and_exit_1c();
}

// Import a template PHA from a JSON file.
if (isset($_POST['template_pha_import'])) {
    $Zfpf->files_array_check_1e('pha_json_file', 'practice_o1.php'); // This function echos and exits if there is a problem.
    if (!isset($_FILES['file_1_pha_json_file']['tmp_name']))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    $ArrayPHA = $Zfpf->read_file_1e($_FILES['file_1_pha_json_file']['tmp_name']); // Returns empty string (false) on failure.
    if ($ArrayPHA) {
        $ArrayPHA = json_decode($ArrayPHA, TRUE); // The second parameter must be TRUE to return an array.
        $ProblemMessage = '';
        if (!isset($ArrayPHA['t0pha']))
            $ProblemMessage .= '<p>A t0pha field was not found in the JSON file you selected.</p>';
        elseif (count($ArrayPHA['t0pha']) != 1)
            $ProblemMessage .= '<p>More than one t0pha fields were found in the JSON file you selected. Currently, only one PHA can be imported per JSON file.</p>';
        if ($ProblemMessage) {
            echo $Zfpf->xhtml_contents_header_1c().'<h2>
            Problem importing a template PHA from a JSON file</h2>
            '.$ProblemMessage.'
            <p>
            Contact your supervisor or an app admin for assistance.</p>
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
        // t0pha
        $ImportFields = array('c6team_qualifications', 'c6background', 'c6method', 'c6prior_incident_id');
        $Uploader = $Zfpf->current_user_info_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $HighestTemplatePHAKey = $Zfpf->get_highest_in_table($DBMSresource, 'k0pha', 't0pha', 99999);
        foreach ($ArrayPHA['t0pha'] as $Vr) { // r stands for row, of a database table. Only one value in this t0pha array, with key the imported k0pha, but we don't know this k0pha.
            $PHARow['k0pha'] = ++$HighestTemplatePHAKey; // Assign a new k0pha that is less than 10,000 but otherwise the highest k0pha.
            $PHARow['k0process'] = 0; // See schema -- 0 for template PHAs.
            $PHARow['c6bfn_act_notice'] = $EncryptedNothing;
            $PHARow['c6bfn_attendance'] = $EncryptedNothing;
            $PHARow['c6bfn_pha_as_issued'] = $EncryptedNothing;
            $PHARow['k0user_of_leader'] = 0; // See schema.
            $PHARow['c5ts_leader'] = $EncryptedNothing;
            $PHARow['c6nymd_leader'] = $Zfpf->encrypt_1c('Template PHA imported '.$Zfpf->timestamp_to_display_1c(time()).' by '.$Uploader['NameTitle'].', '.$Uploader['Employer'].' '.$Uploader['WorkEmail']); // See schema.
            $PHARow['c5who_is_editing'] = $EncryptedNobody;
            foreach ($ImportFields as $Vfn) { // fn stands for field name -- aka database table column name.
                if (isset($Vr[$Vfn]))
                    $PHARow[$Vfn] = $Zfpf->encrypt_1c($Vr[$Vfn]);
                else
                    $PHARow[$Vfn] = $EncryptedNothing;
            }
            $Zfpf->insert_sql_1s($DBMSresource, 't0pha', $PHARow, FALSE); // No history insert for templates.
        }
        $SpCount = 0;
        $ScCount = 0;
        $ImportFields = array('c5name', 'c5type', 'c5severity', 'c5likelihood'); // for t0scenario, nested in t0subprocess
        // t0subprocess
        if (isset($ArrayPHA['t0subprocess'])) foreach ($ArrayPHA['t0subprocess'] as $Ksp => $Vsp) { // $Ksp has the key from the JSON source file, same as $Vsp['k0subprocess'].
            $SpRow['k0subprocess'] = time().mt_rand(100, 999).$SpCount++; // Allows up to 10,000 subprocesses (aka subsystems) while avoiding conflicts for these inserts.
            $SpRow['k0pha'] = $PHARow['k0pha']; // Only one PHA row allowed for imports.
            if (isset($Vsp['c5name']))
                $SpRow['c5name'] = $Zfpf->encrypt_1c($Vsp['c5name']);
            else // Required if input via app, but don't fail if not in JSON file.
                $SpRow['c5name'] = $EncryptedNothing;
            $SpRow['c5who_is_editing'] = $EncryptedNobody;
            $Zfpf->insert_sql_1s($DBMSresource, 't0subprocess', $SpRow, FALSE);
            // t0scenario
            if (isset($ArrayPHA['t0scenario'])) foreach ($ArrayPHA['t0scenario'] as $Ksc => $Vsc) {
                if (isset($Vsc['k0subprocess']) and $Vsc['k0subprocess'] == $Ksp) { // The scenario is in this subprocess.
                    $NewRow['k0scenario'] = time().mt_rand(100, 999).$ScCount++;
                    $ScMap[$Ksc] = $NewRow['k0scenario']; // $Ksc is the old k0scenario, $NewRow['k0scenario'] is the new k0scenario.
                    $NewRow['k0subprocess'] = $SpRow['k0subprocess']; // The new k0subprocess.
                    $NewRow['c5who_is_editing'] = $EncryptedNobody;
                    foreach ($ImportFields as $Vfn) {
                        if (isset($Vsc[$Vfn]))
                            $NewRow[$Vfn] = $Zfpf->encrypt_1c($Vsc[$Vfn]);
                        else
                            $NewRow[$Vfn] = $EncryptedNothing;
                    }
                    $Zfpf->insert_sql_1s($DBMSresource, 't0scenario', $NewRow, FALSE);
                }
            }
        }
        // t0cause
        unset($NewRow);
        $NewRow['c5who_is_editing'] = $EncryptedNobody;
        $Count = 0;
        $ImportFields = array('c5name', 'c6description');
        if (isset($ArrayPHA['t0cause'])) foreach ($ArrayPHA['t0cause'] as $Kx => $Vx) {
            $NewRow['k0cause'] = time().mt_rand(100, 999).$Count++;
            $ccsaMap['cause'][$Kx] = $NewRow['k0cause'];
            foreach ($ImportFields as $Vfn) {
                if (isset($Vx[$Vfn]))
                    $NewRow[$Vfn] = $Zfpf->encrypt_1c($Vx[$Vfn]);
                else
                    $NewRow[$Vfn] = $EncryptedNothing;
            }
            $Zfpf->insert_sql_1s($DBMSresource, 't0cause', $NewRow, FALSE);
        }
        // t0consequence
        unset($NewRow);
        $NewRow['c5who_is_editing'] = $EncryptedNobody;
        $Count = 0;
        $ImportFields = array('c5name', 'c6description');
        if (isset($ArrayPHA['t0consequence'])) foreach ($ArrayPHA['t0consequence'] as $Kx => $Vx) {
            $NewRow['k0consequence'] = time().mt_rand(100, 999).$Count++;
            $ccsaMap['consequence'][$Kx] = $NewRow['k0consequence'];
            foreach ($ImportFields as $Vfn) {
                if (isset($Vx[$Vfn]))
                    $NewRow[$Vfn] = $Zfpf->encrypt_1c($Vx[$Vfn]);
                else
                    $NewRow[$Vfn] = $EncryptedNothing;
            }
            $Zfpf->insert_sql_1s($DBMSresource, 't0consequence', $NewRow, FALSE);
        }
        // t0safeguard
        unset($NewRow);
        $NewRow['c5who_is_editing'] = $EncryptedNobody;
        $Count = 0;
        $ImportFields = array('c5name', 'c5hierarchy', 'c6description');
        if (isset($ArrayPHA['t0safeguard'])) foreach ($ArrayPHA['t0safeguard'] as $Kx => $Vx) {
            $NewRow['k0safeguard'] = time().mt_rand(100, 999).$Count++;
            $ccsaMap['safeguard'][$Kx] = $NewRow['k0safeguard'];
            foreach ($ImportFields as $Vfn) {
                if (isset($Vx[$Vfn]))
                    $NewRow[$Vfn] = $Zfpf->encrypt_1c($Vx[$Vfn]);
                else
                    $NewRow[$Vfn] = $EncryptedNothing;
            }
            $Zfpf->insert_sql_1s($DBMSresource, 't0safeguard', $NewRow, FALSE);
        }
        // t0action -- typically not in templates, but need to import a customized PHA that someone wants to use as a template.
        unset($NewRow);
        $NewRow['c5who_is_editing'] = $EncryptedNobody;
        $Count = 0;
        $ImportFields = array('c5name', 'c5priority', 'c6deficiency', 'c6details');
        if (isset($ArrayPHA['t0action'])) foreach ($ArrayPHA['t0action'] as $Kx => $Vx) {
            $NewRow['k0action'] = time().mt_rand(100, 999).$Count++;
            $ccsaMap['action'][$Kx] = $NewRow['k0action'];
            $NewRow['c5status'] = $Zfpf->encrypt_1c('Draft proposed action');
            $NewRow['c5affected_entity'] = $EncryptedNothing; // Would have to be input later, if/when the action is first edited.
            $NewRow['k0affected_entity'] = 0;
            $NewRow['c5ts_target'] = $EncryptedNothing;
            $NewRow['k0user_of_leader'] = 0;
            $NewRow['c5ts_leader'] = $EncryptedNothing;
            $NewRow['c6nymd_leader'] = $EncryptedNothing;
            $NewRow['c6notes'] = $EncryptedNothing;
            $NewRow['c6bfn_supporting'] = $EncryptedNothing;
            $NewRow['k0user_of_ae_leader'] = -2;
            $NewRow['c5ts_ae_leader'] = $EncryptedNothing;
            $NewRow['c6nymd_ae_leader'] = $EncryptedNothing;
            foreach ($ImportFields as $Vfn) {
                if (isset($Vx[$Vfn]))
                    $NewRow[$Vfn] = $Zfpf->encrypt_1c($Vx[$Vfn]);
                else
                    $NewRow[$Vfn] = $EncryptedNothing;
            }
            $Zfpf->insert_sql_1s($DBMSresource, 't0action', $NewRow, FALSE);
        }
        // Juntion tables: t0scenario_cause, t0scenario_consequence, t0scenario_safeguard, and t0scenario_action
        $Types = array('cause', 'consequence', 'safeguard', 'action');
        foreach ($Types as $ccsa) {
            unset($NewRow);
            $NewRow['c5who_is_editing'] = $EncryptedNobody;
            $Count = 0;
            if (isset($ArrayPHA['t0scenario_'.$ccsa])) foreach ($ArrayPHA['t0scenario_'.$ccsa] as $Vx) {
                $ScKeyOld = $Vx['k0scenario'];
                $ccsaKeyOld = $Vx['k0'.$ccsa];
                if (isset($ScMap[$ScKeyOld]) and isset($ccsaMap[$ccsa][$ccsaKeyOld])) {
                    $NewRow['k0scenario_'.$ccsa] = time().mt_rand(100, 999).$Count++;
                    $NewRow['k0scenario'] = $ScMap[$ScKeyOld];
                    $NewRow['k0'.$ccsa] = $ccsaMap[$ccsa][$ccsaKeyOld];
                    $Zfpf->insert_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $NewRow, FALSE);
                }
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
    }
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Importing template PHA from a JSON file complete.</h2>
    <p>
    Review the PHA to verify this import was complete and correct.</p>
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i0n and SPECIAL CASE pha_template code
if (isset($_POST['pha_i0n']) or isset($_POST['pha_template'])) {
    // Additional security check.
    if ($User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or !isset($_SESSION['StatePicked']['t0process']['k0process']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // Initialize $_SESSION['Selected']
    $_SESSION['Selected'] = array(
        'k0pha' => time().mt_rand(1000000, 9999999),
        'k0process' => $_SESSION['StatePicked']['t0process']['k0process'],
        'c6bfn_act_notice' => $EncryptedNothing,
        'c6team_qualifications' => $EncryptedNothing,
        'c6bfn_attendance' => $EncryptedNothing,
        'c6background' => $EncryptedNothing,
        'c6method' => $EncryptedNothing,
        'c6prior_incident_id' => $EncryptedNothing,
        'c6bfn_pha_as_issued' => $EncryptedNothing,
        'k0user_of_leader' => $_SESSION['t0user']['k0user'], // Default leader is the user who creates the record here. Can change later.
        'c5ts_leader' => $EncryptedNothing,
        'c6nymd_leader' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
    if (isset($_POST['pha_template'])) {
        // Additional security check.
        if (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0pha']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0pha'][$CheckedPost]['k0pha']) or $_SESSION['SelectResults']['t0pha'][$CheckedPost]['k0pha'] >= 100000) // Only a hacker could access this code with a non-template PHA selected. See pha_i1m.php
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // Overwrite the four array elements below with their template values. Other elements remain as initialized by i0n code. User adds template subprocesses later.
        $_SESSION['Selected']['c6team_qualifications'] = $_SESSION['SelectResults']['t0pha'][$CheckedPost]['c6team_qualifications'];
        $_SESSION['Selected']['c6background'] = $_SESSION['SelectResults']['t0pha'][$CheckedPost]['c6background'];
        $_SESSION['Selected']['c6method'] = $_SESSION['SelectResults']['t0pha'][$CheckedPost]['c6method'];
        $_SESSION['Selected']['c6prior_incident_id'] = $_SESSION['SelectResults']['t0pha'][$CheckedPost]['c6prior_incident_id'];
        $_SESSION['Selected']['c6nymd_leader'] = $Zfpf->encrypt_1c($_SESSION['SelectResults']['t0pha'][$CheckedPost]['k0pha']); // c6nymd_leader holds, encrypted, the source-template k0pha until issued.
        $_POST['pha_i0n'] = TRUE; // Going forward, same handling as i0n code.
    }
}

// history_o1 code
if (isset($_GET['pha_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0pha']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0pha', $_SESSION['Selected']['k0pha']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one PHA record', 'pha_io03.php', 'pha_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0pha']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // Update $htmlFormArray if PHA has been issued.
    if ($Zfpf->decrypt_1c($_SESSION['Selected']['c6bfn_pha_as_issued']) != '[Nothing has been recorded in this field.]')
        $htmlFormArray['c6bfn_pha_as_issued'] = array('<a id="c6bfn_pha_as_issued"></a>As-issued copy', '', MAX_FILE_SIZE_ZFPF, 'upload_files');
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'pha_io03.php', 'pha_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'pha_io03.php', 'pha_o1');
    $_POST['pha_o1'] = 1; // Only needed as catch, after minor stuff, like user forgot to select a file before downloading, or password check before download.
}

// Clear subprocess edit_lock(s) if user hits "Go back" button output by team_leader_approval_1 code.
if (isset($_POST['from_team_leader_approval_1'])) {
    if (!isset($_SESSION['Selected']['k0pha']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
    list($SRSp, $RRSp) = $Zfpf->one_shot_select_1s('t0subprocess', $Conditions);
    if ($RRSp) foreach ($SRSp as $V)
        $Zfpf->clear_edit_lock_1c($V);
    $_POST['pha_o1'] = 1;
}

// o1 code
if (isset($_POST['pha_o1'])) {
    // Additional security check.
    if (!isset($_SESSION['Selected']['k0pha']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0pha'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (!isset($_SESSION['Selected']['k0pha'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0pha'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0pha'][$CheckedPost];
        // SPECIAL CASE unset($_SESSION['SelectResults']); in if clause below to clean up after "Go back" from subprocess or scenario
    }
    $Zfpf->clear_edit_lock_1c();
    if (isset($_SESSION['SelectResults']))
        unset($_SESSION['SelectResults']);
    // Check if $EditAuth now that $_SESSION['Selected'] is set.
    if ($User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and 
            (
                ($_SESSION['Selected']['k0pha'] >= 100000 and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF) or 
                ($_SESSION['Selected']['k0pha'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes')
            )
       )
        $EditAuth = TRUE;
    // Display as-issued PHA, if applicable. 
    // c6bfn_pha_as_issued holds encrypted and encoded array or '[Nothing has been recorded in this field.]'
    if ($Zfpf->decrypt_1c($_SESSION['Selected']['c6bfn_pha_as_issued']) != '[Nothing has been recorded in this field.]') {
        $htmlFormArray['c6bfn_pha_as_issued'] = array('<a id="c6bfn_pha_as_issued"></a>As-issued copy', '', MAX_FILE_SIZE_ZFPF, 'upload_files'); // Due to permanent edit lock on issued PHAs... nothing can be uploaded, just downloaded.
        $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors']['c6bfn_pha_as_issued'] = 'As-issued copy';
    }
    // Cannot add "Generate an activity notice" option, in o1 display, because these go back to i1, ejecting user if not draft...
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Selected'], TRUE);
    // Handle k0 field(s)
    if ($_SESSION['Selected']['k0pha'] < 100000) { // Template PHAs don't have a team leader and are not associated with a process.
        $Display['k0user_of_leader'] = 'None, because this is a template.';
        $Process['AEFullDescription'] = 'Not associated with a process because this is a template.';
        $UserIsProcessPSMLeader = FALSE;
    }
    else {
        if (!isset($_SESSION['StatePicked']['t0process']['k0process']) or $_SESSION['Selected']['k0process'] != $_SESSION['StatePicked']['t0process']['k0process'])
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $TeamLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
        $Display['k0user_of_leader'] = $TeamLeader['NameTitleEmployerWorkEmail'];
    }
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
    $ccsaZfpf = new ccsaZfpf;
    echo $Zfpf->xhtml_contents_header_1c('PHA').'<h2>
    <a class="toc" href="glossary.php#pha" target="_blank">Process-hazard analysis (PHA)</a> introductory text for<br />
    '.$Process['AEFullDescription'].'</h2>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'pha_io03.php', $_SESSION['Selected'], $Display).'<p>
    <a class="toc" href="risk_rank_matrix.php" target="_blank">Risk-ranking matrix</a></p><p>
    <a class="toc" href="subprocess_io03.php?subprocess_i1m">View subsystems and scenarios</a></p>';
    // Check if anyone else is editing the selected row and check user privileges. See messages to the user below regarding privileges.
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]') {
        if (substr($who_is_editing, 0, 19) != 'PERMANENTLY LOCKED:')
            echo '<p><b>'.$who_is_editing.' is editing the PHA record you selected.</b><br />
            If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
        else
            echo '<p>
            <b>Issued by: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']).'</b><br />
            This is not a draft PHA record. Once a PHA has been issued by its team leader, the issued version cannot be changed. You may revise the working draft, and, when ready, issue that as the latest PHA.</p><p>
            '.$who_is_editing.'</p>'; // This should echo the permanent-lock message. This also prevents an issued PHA from getting to next elseif clause.
        if ($EditAuth) // Allow exporting an edit-locked or issued PHA.
            echo '<p>
            <a class="toc" href="pha_io03.php?export_sc_csv_1">Export scenarios to CSV file</a><br /><br />
            <a class="toc" href="pha_io03.php?export_ccsa_csv_1">Export causes, consequences, safeguards, and recommended actions to CSV file</a><br /><br />
            <a class="toc" href="pha_io03.php?export_pha_json_1">Export PHA to JSON file</a>
            </p>';
    }
    elseif ($EditAuth) {
        // Edit button for anyone meeting above criteria plus issue button for team leader and change team leader button for AE-Leader.
        echo '<p>
            <a class="toc" href="pha_io03.php?pha_o1_from">Update introductory text</a><br /><br />
            <a class="toc" href="pha_io03.php?export_sc_csv_1">Export scenarios to CSV file</a><br /><br />
            <a class="toc" href="pha_io03.php?export_ccsa_csv_1">Export causes, consequences, safeguards, and recommended actions to CSV file</a><br /><br />
            <a class="toc" href="pha_io03.php?export_pha_json_1">Export PHA to JSON file</a>';
        if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'] and $_SESSION['Selected']['k0pha'] >= 100000) {
            if ($Zfpf->decrypt_1c($_SESSION['Selected']['c6bfn_attendance']) != '[Nothing has been recorded in this field.]')
                echo '<br /><br />
                <a class="toc" href="pha_io03.php?team_leader_approval_1">Issue this PHA</a>';
            else
                echo '<br /><br />
                To issue this PHA, first upload its team-attendance records';
        }
        if ($_SESSION['Selected']['k0pha'] < 100000)
            echo '<br /><br />
            This is a template PHA. Templates cannot be issued, only edited.';
        elseif ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'])
            echo '<br /><br />
            A PHA can only be issued by its team leader. You are not the recorded team leader.';
        if ($UserIsProcessPSMLeader)
            echo '<br /><br />
            <a class="toc" href="pha_io03.php?change_team_leader_1">Change team leader</a>';
        echo '</p>';
    }
    else {
        if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Practice Privileges Notice</b>: You have neither update nor export privileges for this practice. To change this, contact your supervisor or an app admin.</p>';
        if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '<p>
    <a class="toc" href="pha_io03.php?pha_history_o1">History of this record</a></p><p>
    <a class="toc" href="practice_o1.php?">Go back</a></p>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, i3, and approval code
if (isset($_SESSION['Selected']['k0pha'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0pha', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    // Additional security check.
    if (($who_is_editing != '[A new database row is being created.]' and (!$EditAuth or ($_SESSION['Selected']['k0pha'] >= 100000 and (!isset($_SESSION['StatePicked']['t0process']['k0process']) or $_SESSION['Selected']['k0process'] != $_SESSION['StatePicked']['t0process']['k0process'])) or ($_SESSION['Selected']['k0pha'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes'))) or ($who_is_editing == '[A new database row is being created.]' and $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    if (isset($_GET['pha_o1_from']) or isset($_GET['team_leader_approval_1']))
        $Zfpf->edit_lock_1c('pha', 'PHA introductory text'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error. Edit lock for change_team_leader_1 is done in change_team_leader_2, so it can be cleared in change_team_leader_1.
    // Get useful information
    if ($_SESSION['Selected']['k0pha'] < 100000)
        $Process['AEFullDescription'] = 'Not associated with a process because this is a template.';
    if ($_SESSION['Selected']['k0pha'] >= 100000)  // Template PHAs don't have a team leader.
        $TeamLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
    $Issued = $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_leader']);
    if ($Issued == '[Nothing has been recorded in this field.]')
        $Issued = FALSE;
    
    // Export to CSV or JSON
    if (isset($_GET['export_sc_csv_1']) or isset($_GET['export_ccsa_csv_1']) or isset($_GET['export_pha_json_1'])) {
        // t0pha can be viewed by issuing draft report. A CSV action list can be downloaded from the action register. 
        // So only included the scenarios in the CSV file.
        $Types = array('cause', 'consequence', 'safeguard', 'action'); // A definition of "ccsa"
        $DisplayType = array('cause' => 'Possible Cause', 'consequence' => 'Possible Consequence', 'safeguard' => 'Existing Safeguard', 'action' => 'Recommended Actions');
        $FileAsString = '';
        $PHAKey = $_SESSION['Selected']['k0pha'];  // Use database-table keys as array keys to avoid conflicts, especially with ccsa and their juntion tables (jt).
        $ArrayPHA['t0pha'][$PHAKey] = $_SESSION['Selected'];
        // Select all subprocesses, all scenarios within each, and their ccsa. Build arrays for CSV and JSON encoding.
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
        list($SRSp, $RRSp) = $Zfpf->select_sql_1s($DBMSresource, 't0subprocess', $Conditions);
        if ($RRSp) {
            foreach ($SRSp as $Vsp)
                $ArrayofSpNames[] = $Zfpf->decrypt_1c($Vsp['c5name']);
            array_multisort($ArrayofSpNames, $SRSp); // Sort alphabetically by name.
        }
        if ($RRSp) foreach ($SRSp as $Vsp) {
            $SubprocessName = $Zfpf->decrypt_1c($Vsp['c5name']);
            $SpKey = $Vsp['k0subprocess'];
            $ArrayPHA['t0subprocess'][$SpKey] = $Vsp;
            $Conditions[0] = array('k0subprocess', '=', $Vsp['k0subprocess']);
            list($SRSc, $RRSc) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $Conditions);
            if ($RRSc) { 
                if (isset($ScenarioName))
                    unset($ScenarioName);
                foreach ($SRSc as $Vsc)
                    $ScenarioName[] = $Zfpf->decrypt_1c($Vsc['c5name']);
                array_multisort($ScenarioName, $SRSc); // Sort alphabetically by name.
            }
            if ($RRSc) foreach ($SRSc as $Ksc => $Vsc) {
                if (!$FileAsString and isset($_GET['export_sc_csv_1'])) // Only create CSV file if there is at least one scenario in the PHA.
                    $FileAsString = '"Subsystem"|"Scenario"|"Name (what-if...)"|"Type"|"Severity with existing safeguards in place"|"Likelihood of this severity with existing safeguards in place"|"Risk Ranking"|"Possible Causes"|"Possible Consequences"|"Existing Safeguards"|"Recommended Actions"
'; // First row/line for scenario CSV file. Call "actions ", the "a" in ccsa, "Recommended Actions" for the CSV file, following PHA conventions.
                $Severity = $Zfpf->decrypt_1c($Vsc['c5severity']);
                $Likelihood = $Zfpf->decrypt_1c($Vsc['c5likelihood']);
                $RiskRank = $Zfpf->risk_rank_1c($Severity, $Likelihood);
                if (isset($_GET['export_sc_csv_1']))
                    $FileAsString .= '"'.$SubprocessName.'"|"'.++$Ksc.'"|"'.$Zfpf->decrypt_1c($Vsc['c5name']).'"|"'.$Zfpf->decrypt_1c($Vsc['c5type']).'"|"'.$Severity.'"|"'.$Likelihood.'"|"'.$RiskRank.'"'; // Start scenario CSV row, ccsa names added below.
                $ScKey = $Vsc['k0scenario'];
                $ArrayPHA['t0scenario'][$ScKey] = $Vsc;
                foreach ($Types as $ccsa) {
                    if (isset($ccsaArray)) {
                        unset($ccsaName);
                        unset($ccsaArray);
                    }
                    if (isset($_GET['export_sc_csv_1']))
                        $FileAsString .= '|"'; // Start ccsa CSV field.
                    $Conditions[0] = array('k0scenario', '=', $Vsc['k0scenario']);
                    list($SRjt, $RRjt) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $Conditions);
                    if ($RRjt) foreach ($SRjt as $Vjt) {
                        $jtKey = $Vjt['k0scenario_'.$ccsa];
                        $ArrayPHA['t0scenario_'.$ccsa][$jtKey] = $Vjt;
                        $ccsaKey = $Vjt['k0'.$ccsa];
                        if (!isset($ArrayPHA['t0'.$ccsa][$ccsaKey])) { // Otherwise the ccsa is already in $ArrayPHA.
                            $Conditions[0] = array('k0'.$ccsa, '=', $ccsaKey);
                            list($SRccsa, $RRccsa) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$ccsa, $Conditions);
                            if ($RRccsa != 1)
                                error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. '.@$RRccsa.' t0'.@$ccsa.' rows returned for k0'.@$ccsa.' = '.@$ccsaKey);
                            elseif ($RRccsa)
                                $ArrayPHA['t0'.$ccsa][$ccsaKey] = $SRccsa[0];
                        }
                        if (isset($_GET['export_sc_csv_1'])) {
                            $ccsaName[$ccsaKey] = $Zfpf->decrypt_1c($ArrayPHA['t0'.$ccsa][$ccsaKey]['c5name']); // Need to sort by name, alphabetically.
                            $ccsaArray[$ccsaKey] = $ArrayPHA['t0'.$ccsa][$ccsaKey]; // Limit to ccsa in $SRjt
                        }
                        elseif (isset($_GET['export_ccsa_csv_1']) and !isset($AllccsaArray[$ccsa][$ccsaKey])) {  // Otherwise the ccsa is already in $AllccsaArray.)
                            $AllccsaName[$ccsa][$ccsaKey] = $Zfpf->decrypt_1c($ArrayPHA['t0'.$ccsa][$ccsaKey]['c5name']); // Need to sort by name, alphabetically.
                            $AllccsaArray[$ccsa][$ccsaKey] = $ArrayPHA['t0'.$ccsa][$ccsaKey];
                        }
                    }
                    if (isset($ccsaArray)) { // export_sc_csv_1 case
                        array_multisort($ccsaName, $ccsaArray); // ccsa: sort alphabetically by name.
                        foreach ($ccsaArray as $Kccsa => $Vccsa) { // $ccsaArray is the ccsa in the juntion table, after sorting alphabetically.
                            if ($Kccsa)
                                $FileAsString .= '
'; // Start each numbered ccsa within a field on a new line, except the first one.
                            $FileAsString .= '('.++$Kccsa.') '.$Zfpf->decrypt_1c($Vccsa['c5name']); // All four ccsa types have a c5name field.
                        }
                    }
                    if (isset($_GET['export_sc_csv_1']))
                        $FileAsString .= '"'; // End ccsa CSV field. If no ccsa recorded for a ccsa field, leave an empty string in that field.
                }
                if (isset($_GET['export_sc_csv_1']))
                    $FileAsString .= '
'; // End scenario CSV row.  New line, outside double quotes, marks end of a CSV row.
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        // Generate ccsa table CSV file
        if (isset($_GET['export_ccsa_csv_1'])) {
            $FileAsString = '"Type"|"Name"|"Status"|"Risk Ranking"|"Affected Entity"|"Deficiency"|"Details or Resolution Options"
'; // First row/line for ccsa CSV file.
            foreach ($Types as $ccsa) {
                if (isset($AllccsaArray[$ccsa])) { // Templates have no actions.
                    array_multisort($AllccsaName[$ccsa], $AllccsaArray[$ccsa]); // ccsa: sort alphabetically by name.
                    foreach ($AllccsaArray[$ccsa] as $Vccsa) {
                        $FileAsString .= '"'.$DisplayType[$ccsa].'"|"'.$Zfpf->decrypt_1c($Vccsa['c5name']).'"|"';
                        if ($ccsa == 'action')
                            $FileAsString .= $Zfpf->decrypt_1c($Vccsa['c5status']).'"|"'.$Zfpf->decrypt_1c($Vccsa['c5priority']).'"|"'.$Zfpf->decrypt_1c($Vccsa['c5affected_entity']).'"|"'.$Zfpf->decrypt_1c($Vccsa['c6deficiency']).'"|"'.$Zfpf->decrypt_1c($Vccsa['c6details']).'"
'; // End scenario CSV row. New line, outside double quotes, marks end of a CSV row.
                        else {
                            $FileAsString .= '[Not applicable]"|"[Not applicable]"|"[Not applicable]"|"[Not applicable]"|"';
                            if ($ccsa == 'safeguard')
                                $FileAsString .= 'Safeguard Type: '.$Zfpf->decrypt_1c($Vccsa['c5hierarchy']).'
'; // First line of description cell is the safeguard hierarchy-of-controls type
                            $FileAsString .= $Zfpf->decrypt_1c($Vccsa['c6description']).'"
'; // End scenario CSV row. New line, outside double quotes, marks end of a CSV row.
                        }
                    }
                }
            }
        }
        if (isset($_GET['export_sc_csv_1']) or isset($_GET['export_ccsa_csv_1'])) {
            if (!$FileAsString) {
                $FileAsString = 'No scenarios were found in the PHA, so a CSV file was not created.';
                $LocalName = 'pha_had_no_scenarios_at_'.time().'.txt';
            }
            else {
                $FileAsString = $Zfpf->csv_safe_xss_prevent_decode_1c($FileAsString); // See CoreZfpf.php
                if (isset($_GET['export_sc_csv_1']))
                    $LocalName = 'pha_'.$_SESSION['Selected']['k0pha'].'_scenarios_downloaded_at_'.time().'.csv';
                if (isset($_GET['export_ccsa_csv_1']))
                    $LocalName = 'pha_'.$_SESSION['Selected']['k0pha'].'_ccsa_downloaded_at_'.time().'.csv';
            }
            $Zfpf->download_one_file($FileAsString, $LocalName); // FilesZfpf::download_one_file echos and exits.
        }
        else { // export_pha_json_1 case
            $ArrayPHA = json_encode($Zfpf->decrypt_array_1c($ArrayPHA)); // Must decrypt before JSON encoding. json_encode returns false on failure.
            if (!$ArrayPHA) {
                $ArrayPHA = 'JSON encoding the PHA failed.';
                $LocalName = 'pha_json_encoding_failed_at_'.time().'.txt';
            }
            else
                $LocalName = 'pha_'.$_SESSION['Selected']['k0pha'].'_downloaded_at_'.time().'.json';
            $Zfpf->download_one_file($ArrayPHA, $LocalName); // FilesZfpf::download_one_file echos and exits.
        }
    }
        
    // Team leader issuing PHA 1
    if (isset($_GET['team_leader_approval_1'])) {
        // Additional security check.
        if (!$EditAuth or $Issued or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] or $Zfpf->decrypt_1c($_SESSION['Selected']['c6bfn_attendance']) == '[Nothing has been recorded in this field.]' or $_SESSION['Selected']['k0pha'] < 100000)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
        $ccsaZfpf = new ccsaZfpf;
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/arZfpf.php';
        $arZfpf = new arZfpf;
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_leader'] = $TeamLeader['NameTitleEmployerWorkEmail'];
        unset($htmlFormArray['c6background']); // Unset the customized fields below
        unset($htmlFormArray['c6team_qualifications']);
        unset($htmlFormArray['c6method']);
        unset($htmlFormArray['c6prior_incident_id']);
        $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
            'c6background' => 'Background', 
            'c6team_qualifications' => 'Qualifications and information reviewed',
            'c6method' => 'Method',
            'c6prior_incident_id' => 'Previous incidents',
            'risk_ranking_matrix' => 'Risk-ranking matrix'
        );
        $ApprovalText = '<h1>
        Full Report</h1><h2>
        Process-hazard analysis (PHA) for<br/>
        '.$Process['AEFullDescription'].'</h2>
        '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'
        <a id="c6background"></a>
        <h2>
        Background on the process, applicable PHA rules, and past PHA or revisions history</h2>
        <p>'.$Display['c6background'].'</p>
        <a id="c6team_qualifications"></a>
        <h2>
        Team qualifications and information reviewed</h2>
        <p>'.$Display['c6team_qualifications'].'</p>
        <a id="c6method"></a>
        <h2>
        Method</h2>
        <p>'.$Display['c6method'].'</p>
        <a id="c6prior_incident_id"></a>
        <h2>
        Identification of applicable previous incidents</h2>
        <p>'.$Display['c6prior_incident_id'].'</p>
        '.'<p style="page-break-before: always"></p>
        <a id="risk_ranking_matrix"></a>'.$ccsaZfpf->risk_rank_matrix($Zfpf);
        // Select all subprocesses and echo with links
        $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        list($SRSp, $RRSp) = $Zfpf->select_sql_1s($DBMSresource, 't0subprocess', $Conditions);
        if (!$RRSp) {
            echo $Zfpf->xhtml_contents_header_1c('Not complete').'<h1>
            No subsystems were found for this PHA</h1><p>
            It cannot be issued without any subsystems. Go back to add new subsystems or create a PHA from a template.
            <form action="pha_io03.php" method="post"><p>
                <input type="submit" name="pha_o1" value="Go back" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        foreach ($SRSp as $K => $V) {
            $SubprocessName[$K] = $Zfpf->decrypt_1c($V['c5name']);
            $Zfpf->edit_lock_1c('subprocess', 'PHA subsystem '.$SubprocessName[$K], $V); // Check and edit_lock nested subprocesses.
        }
        array_multisort($SubprocessName, $SRSp); // Sort alphabetically by name.
        $ApprovalText .= '<p><b><a id="subsystems_list"></a>
        Subsystems List</b><br />';
        foreach ($SubprocessName as $K => $V)
            $ApprovalText .= '<a class="toc" href="#subprocess_'.$K.'">'.$V.'</a><br />';
        $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors']['subsystems_list'] = 'Subsystems List';
        $ApprovalText .= '</p>';
        // Select all scenarios and echo with links, grouped by subprocess
        $Types = array('cause' => 'Possible Causes', 'consequence' => 'Possible Consequences', 'safeguard' => 'Existing Safeguards', 'action' => 'Recommended Actions');
        foreach ($SRSp as $KA => $VA) {
            $ApprovalText .= '<p><a id="subprocess_'.$KA.'"></a><b>Scenario list for subsystem '.$SubprocessName[$KA].'</b><br />';
            $Conditions[0] = array('k0subprocess', '=', $VA['k0subprocess']);
            list($SRSc, $RRSc) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $Conditions);
            if (!$RRSc)
                $ApprovalText .= 'No scenarios found </p>';
            else {
                if (isset($ScenarioName))
                    unset($ScenarioName);
                foreach ($SRSc as $VB)
                    $ScenarioName[] = $Zfpf->decrypt_1c($VB['c5name']);
                array_multisort($ScenarioName, $SRSc); // Sort alphabetically by name.
                foreach ($ScenarioName as $KB => $VB)
                    $ApprovalText .= '<a class="toc" href="#scenario_'.$KA.'_'.$KB.'">'.$VB.'</a><br />';
                $ApprovalText .= '</p>';
                foreach ($SRSc as $KB => $VB) {
                    $htmlFormArray = $ccsaZfpf->html_form_array('scenario_'.$KA.'_'.$KB, $Zfpf, 'scenario', $arZfpf);
                    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $VB);
                    $ApprovalText .= $Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $VB, $Display).'<p>
                    <i>Risk Ranking:</i><br />
                    '.$Zfpf->risk_rank_1c($Zfpf->decrypt_1c($VB['c5severity']), $Zfpf->decrypt_1c($VB['c5likelihood'])).'</p>
                    '.$ccsaZfpf->scenario_CCSA_Zfpf($VB, $Issued, $User, $UserPracticePrivileges, $Zfpf, FALSE, 'scenario', $Types).'<p class="bottomborder"> </p>';
                }
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        foreach ($Types as $KA => $VA) {
            $htmlFormArray = $ccsaZfpf->html_form_array($KA, $Zfpf, 'scenario', $arZfpf);
            list($SRccsa, $Message) = $ccsaZfpf->other_ccsa_in_pha($KA, $Zfpf, $_SESSION['Selected']['k0pha']);
            if ($SRccsa) {
                $ApprovalText .= '<a id="CCSA_'.$KA.'"></a><h2 class="topborder" style="page-break-before: always">'.$VA.'</h2>';
                $ApprovalText .= '<p>';
                foreach ($SRccsa as $KB => $VB)
                    $ApprovalText .= '<a class="toc" href="#ccsa_'.$KA.'_'.$KB.'">'.$Zfpf->decrypt_1c($VB['c5name']).'</a><br />';
                $ApprovalText .= '</p>';
                $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors']['CCSA_'.$KA] = $VA;
                foreach ($SRccsa as $KB => $VB) {
                    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $VB);
                    if (isset($Display['c5status'])) // Recorded $ApprovalText needs to show all t0action:c5status as below, but only update if issued, via team_leader_approval_2.
                        $Display['c5status'] = 'Needs resolution';
                    $ApprovalText .= '<a id="ccsa_'.$KA.'_'.$KB.'"></a>'.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $VB, $Display, ' class="topborder"');
                }
            }
        }
        $CurrentDate = date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j', time());
        $ApprovalText .='<p class="topborder">
        <b>BE CAREFUL:</b> Once a PHA report has been issued by its team leader, it cannot be changed. You may revise the working draft, and issue that as the latest PHA. <b>IN ADDITION</b>, approving this report permanently logs its proposed actions in this app\'s action register, allowing other reports to reference them. You may cancel your approval of this report later, but doing this will <b>not</b> remove its proposed actions from the action register. The action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p><p>
        <b>By clicking "Issue PHA report" below, as the leader of the team that conducted the PHA described by this report, for '.$Process['AEFullDescription'].', I am confirming that:<br />
         - I was, during the time period when the PHA team completed the analyses that this report documents, qualified to lead this PHA team,<br />
         - I am qualified to approve this report, and<br />
         - I approve this PHA report.</b></p><p>
        <b>Approved By:</b><br />
        Name: <b>'.$User['Name'].'</b><br />
        Job Title: <b>'.$User['Title'].'</b><br />
        Employer<b>: '.$User['Employer'].'</b><br />
        Email Address<b>: '.$User['WorkEmail'].'</b><br />
        Date: <b>'.$CurrentDate.'</b></p>';
        // SPECIAL CASE to clear_edit_lock from subprocesses by suppressing links that won't do this from left-hand contents.
        $FixedLeftContents = '<p>'.$User['Name'].'<br />'.$User['Title'].'<br />'.$User['Employer'].'<br />';
        foreach ($_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] as $K => $V)
            $FixedLeftContents .= '<br />
            | <a class="toc" href="#'.$K.'">'.$V.'</a>'; // $K is ID and $V is description, of anchors.
        unset($_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors']);
        // Next line must be before "Go back" button added to fixed left contents.
        $_SESSION['Scratch']['AsIssued'] = $Zfpf->encrypt_1c($Zfpf->xhtml_contents_header_1c('Confirm Approval', FALSE, $FixedLeftContents, FALSE, TRUE, FALSE).$ApprovalText.$Zfpf->xhtml_footer_1c()); // Same as confirmation page HTML, except without HTML buttons.
        $FixedLeftContents .= '</p>
        <form action="pha_io03.php" method="post"><p>
            <input type="submit" name="from_team_leader_approval_1" value="Go back" /></p>
        </form>';
        echo $Zfpf->xhtml_contents_header_1c('Confirm Approval', TRUE, $FixedLeftContents).$ApprovalText.'
        <form action="pha_io03.php" method="post"><p>
            <input type="submit" name="team_leader_approval_2" value="Issue PHA report" /><br />
            <input type="submit" name="from_team_leader_approval_1" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText); // Used for email, no header or footer.
        $_SESSION['Scratch']['c6nymd_leader'] = $Zfpf->encrypt_1c($User['NameTitleEmployerWorkEmail'].' on '.$CurrentDate);
        $Zfpf->save_and_exit_1c();
    }

    // Team leader issuing PHA 2
    if (isset($_POST['team_leader_approval_2'])) {
        // Additional security check.
        if (!$EditAuth or $Issued or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] or $Zfpf->decrypt_1c($_SESSION['Selected']['c6bfn_attendance']) == '[Nothing has been recorded in this field.]' or $_SESSION['Selected']['k0pha'] < 100000)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        list($SRSp, $RRSp) = $Zfpf->select_sql_1s($DBMSresource, 't0subprocess', $Conditions);
        if (!$RRSp) // Verified at least one subprocess then edit locked in team_leader_approval_1 code above.
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRSp);
        $BaseFileName = 'pha_issued_'.time().'.htm'; // No need for FilesZfpf::xss_prevent_1c() because this name doesn't have < > or &.
        $BytesWritten = $Zfpf->write_file_1e($_SESSION['Scratch']['AsIssued'], $BaseFileName);
        $BytesAttempted = strlen($_SESSION['Scratch']['AsIssued']);
        $BytesWrittenErrorMessage = '';
        if ($BytesWritten < $BytesAttempted) {
            $BytesWrittenErrorMessage = ' <br />BUT, TAKE NOTE: <b>only part of</b> the "as-issued" PHA may have been successfully saved (what was written to the computer file was reportedly <b>smaller than</b> the PHA). You may be able to recover a complete copy from the email sent to you upon issuing the PHA. Otherwise, you may have to reissue the PHA.';
            error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. PHA truncated when written to computer file.');
        }
        if ($BytesWritten > $BytesAttempted) {
            $BytesWrittenErrorMessage = ' <br />BUT, TAKE NOTE: the "as-issued" PHA may <b>not</b> have been properly saved (what was written to the computer file was reportedly <b>larger than</b> the PHA). You may be able to recover a complete copy from the email sent to you upon issuing the PHA. Otherwise, you may have to reissue the PHA.';
            error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. PHA as written to computer file was longer than actual PHA.');
        }
        $c6bfn_array = array(
            $BaseFileName => array(
                $BaseFileName,
                $User['NameTitleEmployerWorkEmail']
            )
        );
        $EncryptedTime = $Zfpf->encrypt_1c(time());
        $Changes['c6bfn_pha_as_issued'] = $Zfpf->encode_encrypt_1c($c6bfn_array);
        $Changes['c5ts_leader'] = $EncryptedTime;
        $Changes['c6nymd_leader'] = $_SESSION['Scratch']['c6nymd_leader'];
        // SPECIAL CASE: Don't clear_edit_lock_1c() via any method. The approved PHA and its subprocesses will remain locked.
        $Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('PERMANENTLY LOCKED: This is an approved PHA, so it cannot be edited.');
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0pha', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        $NewPHA = $_SESSION['Selected']; // Set before updating $_SESSION['Selected'].
        // Update $_SESSION['Selected']
        $_SESSION['Selected']['c6bfn_pha_as_issued'] = $Changes['c6bfn_pha_as_issued'];
        $_SESSION['Selected']['c5ts_leader'] = $Changes['c5ts_leader'];
        $_SESSION['Selected']['c6nymd_leader'] = $Changes['c6nymd_leader'];
        $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
        // Insert current working draft PHA ($NewPHA)
        $NewPHA['k0pha'] = time().mt_rand(1000000, 9999999);
        $NewPHA['c6bfn_act_notice'] = $EncryptedNothing; // Clear this field
        $NewPHA['c6bfn_attendance'] = $EncryptedNothing; // Clear this field, leave others as were before issuing.
        $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
        $NewPHA['c5who_is_editing'] = $EncryptedNobody;
        $Zfpf->insert_sql_1s($DBMSresource, 't0pha', $NewPHA, TRUE, $htmlFormArray);
        // Update all t0action:c5status to 'Needs resolution' and also
        // Insert issued-PHA subprocesses for $NewPHA, then permanently edit lock issued-PHA subprocesses.
        $Hs = 0;
        $Ai = 0;
        $Ah = 0;
        $Bi = 0;
        $Bh = 0;
        foreach ($SRSp as $KA => $VA) {
            $NewSubproces = $VA;
            $NewSubproces['k0subprocess'] = time().$KA.mt_rand(1000, 9999);
            $NewSubproces['k0pha'] = $NewPHA['k0pha'];
            $NewSubproces['c5who_is_editing'] = $EncryptedNobody;
            $Zfpf->insert_sql_1s($DBMSresource, 't0subprocess', $NewSubproces);
            $Conditions[0] = array('k0subprocess', '=', $VA['k0subprocess']);
            list($SRSc, $RRSc) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $Conditions);
            if ($RRSc) foreach ($SRSc as $KB => $VB) {
                $ConditionsScenario[0] = array('k0scenario', '=', $VB['k0scenario']); // Define before $VB is redefined below. 
                $VB['k0scenario'] = time().$Ai++.mt_rand(100, 999);
                $VB['k0subprocess'] = $NewSubproces['k0subprocess'];
                $Zfpf->insert_sql_1s($DBMSresource, 't0scenario', $VB);
                $Types = array('cause', 'consequence', 'safeguard', 'action');
                foreach ($Types as $ccsa) {
                    list($SRSc_ccsa, $RRSc_ccsa) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $ConditionsScenario);
                    if ($RRSc_ccsa) foreach ($SRSc_ccsa as $KC => $VC) {
                        if ($ccsa == 'action') {
                            $ConditionsAction[0] = array('k0action', '=', $VC['k0action']);
                            list($SRAct, $RRAct) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $ConditionsAction);
                            if ($RRAct != 1)
                                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRAct);
                            if ($SRAct[0]['k0user_of_ae_leader'] == -2) { // See the app schema. Only update proposed actions created by this report, not already opened actions (in ar) that it references.
                                $NewStatus['c5status'] = $Zfpf->encrypt_1c('Needs resolution');
                                $NewStatus['k0user_of_ae_leader'] = 0; // See the app schema.
                                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0action', $NewStatus, $ConditionsAction, FALSE); // SPECIAL CASE: Don't record this UPDATE in the history table because it is well known that all actions updated as above when PHA is issued.
                                if ($Affected != 1)
                                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                            }
                        }
                        $VC['k0scenario_'.$ccsa] = time().$Bi++.mt_rand(100, 999);
                        $VC['k0scenario'] = $VB['k0scenario'];
                        $Zfpf->insert_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $VC);
                    }
                }
            }
            // Now, permanently edit lock issued-PHA subprocesses.
            $VA['c5who_is_editing'] = $Zfpf->encrypt_1c('PERMANENTLY LOCKED: This is an approved PHA, so its subsystems cannot be edited.');
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0subprocess', $VA, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the team leader and the process, facility, and owner leaders.
        $Chain = $Zfpf->up_the_chain_1c();
        $Chain['EmailAddresses'][] = $TeamLeader['WorkEmail'];
        $Chain['DistributionList'] .= '<br />
        PHA team leader: '.$TeamLeader['NameTitleEmployerWorkEmail'].'</p>';
        $Subject = 'PSM-CAP: PHA issued by team leader';
        $Body = '<p>
        The team leader -- '.$TeamLeader['NameTitle'].', '.$TeamLeader['Employer'].' -- issued the PHA report for:<br />
        * '.$Process['AEFullDescription'].'<br />
        The app attempted to save an "as-issued" copy, which, when this email was sent, matched the current working draft.
        '.$BytesWrittenErrorMessage.'</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $Chain['DistributionList']);
        $EmailSent = $Zfpf->send_email_1c($Chain['EmailAddresses'], $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('PHA').'<h1>
        App attempted to issue PHA</h1><p>
        The app attempted to save an "as-issued" copy, which, when this email was sent, matched the current working draft.
        '.$BytesWrittenErrorMessage.'</p>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="pha_io03.php" method="post"><p>
            <input type="submit" name="pha_o1" value="View just-issued PHA" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Change team leader code
    if (isset($_POST['change_team_leader_1']) or isset($_GET['change_team_leader_1'])) {
        if (!$EditAuth or $Issued or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Zfpf->clear_edit_lock_1c(); // In case arrived here by canceling from change_team_leader_2
        echo $Zfpf->xhtml_contents_header_1c('Lookup User');
        $Zfpf->lookup_user_1c('pha_io03.php', 'pha_io03.php', 'change_team_leader_2', 'pha_o1');
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['change_team_leader_2'])) {
        if (!$EditAuth or $Issued or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Zfpf->edit_lock_1c('pha');
        $Conditions1[0] = array('k0process', '=', $_SESSION['Selected']['k0process']);
        $SpecialText = '<p><b>
        Pick team leader</b></p><p>
        The current leader, if any, will be replaced by the user you pick above.</p>';
        $Zfpf->lookup_user_wrap_2c(
            't0user_process', // $TableNameUserEntity
            $Conditions1,
            'pha_io03.php', // $SubmitFile
            'pha_io03.php', // $TryAgainFile
            array('k0user'), // $Columns1
            'pha_io03.php', // $CancelFile
            $SpecialText,
            'Assign team leader', // $SpecialSubmitButton
            'change_team_leader_3', // $SubmitButtonName
            'change_team_leader_1', // $TryAgainButtonName
            'pha_o1', // $CancelButtonName
            'c5ts_logon_revoked', // $FilterColumnName
            '[Nothing has been recorded in this field.]' // $Filter
            ); // This function echos and exits.
    }
    if (isset($_POST['change_team_leader_3'])) {
        if (!$EditAuth or $Issued or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        // Check user-input radio-button selection.
        // The user not selecting a radio button is OK in this case.
        if (isset($_POST['Selected'])) {
            $Selected = $Zfpf->post_length_1c('Selected');
            if (isset($_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]))
                $k0user = $_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]; // This is the k0user of the newly selected user.
            else
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        }
        else {
            echo $Zfpf->xhtml_contents_header_1c('Nobody New').'<p>
            It appears you did not select anyone.</p>
            <form action="pha_io03.php" method="post"><p>
                <input type="submit" name="change_team_leader_1" value="Try again" /><br />
                <input type="submit" name="pha_o1" value="Cancel" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            unset($_SESSION['Scratch']['PlainText']['lookup_user']);
            $Zfpf->save_and_exit_1c();
        }
        unset($_SESSION['Scratch']['PlainText']['lookup_user']);
        // Update database with $k0user
        $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
        $Changes['k0user_of_leader'] = $k0user;
        $Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
        // Update $_SESSION['Selected']
        $_SESSION['Selected']['k0user_of_leader'] = $Changes['k0user_of_leader'];
        $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0pha', $Changes, $Conditions, TRUE, $htmlFormArray);
        // $Affected should not be zero because we confirmed that a new user was selected above.
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the any former leader, the newly-assigned leader, the process leader (who should be the current user).
        $NewLeader = $Zfpf->user_job_info_1c($k0user);
        $EmailAddresses = array($Process['AELeaderWorkEmail'], $NewLeader['WorkEmail']);
    	$Subject = 'PSM-CAP: PHA team leader assigned to '.$NewLeader['NameTitle'];
        $Body = '<p>'.$NewLeader['NameTitle'].', '.$NewLeader['Employer'].' was assigned the PHA team leader by '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' (the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader).</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        New PHA team leader : '.$NewLeader['NameTitleEmployerWorkEmail'].'<br />
        Process '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$Process['AELeaderNameTitleEmployerWorkEmail'];
        if ($TeamLeader) { // This is set above, and hold the former team leader info.
            $EmailAddresses[] = $TeamLeader['WorkEmail'];
            $DistributionList .= '<br />
            Former PHA team leader: '.$TeamLeader['NameTitleEmployerWorkEmail'];
        }
        $DistributionList .= '</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], '', $DistributionList);
        $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Done').'<h2>
        You just assigned a new PHA team leader.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="pha_io03.php" method="post"><p>
            <input type="submit" name="pha_o1" value="Back to viewing record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Additional security check for i1, i2, and i3 code
    if ($Issued)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['pha_i0n']) or isset($_GET['pha_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles & privileges.
        if ($_SESSION['Selected']['k0pha'] < 100000) // Template PHAs don't have a team leader.
            $Display['k0user_of_leader'] = 'None, because this is a template.';
        else
            $Display['k0user_of_leader'] = $TeamLeader['NameTitleEmployerWorkEmail'];
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
    elseif (isset($_SESSION['Post']) and !isset($_POST['pha_i2'])) { // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        else {
            $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            foreach ($htmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Selected'][$K]);
                    // FilesZfpf::user_files_directory_1e has been checked, it supports t0pha
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, $K, 'pha_io03.php');
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
        echo $Zfpf->xhtml_contents_header_1c('PHA').'<h2>
        <a class="toc" href="glossary.php#pha" target="_blank">PHA</a> Introductory Text for<br />
        '.$Process['AEFullDescription'].'</h2>
        <form action="pha_io03.php" method="post" enctype="multipart/form-data">'; // upload_files special case 2 of 3. To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        // Add "Generate an activity notice" option, for i1 display, if not a new record. Do here to keep out of history table.
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        if ($who_is_editing != '[A new database row is being created.]')
            $htmlFormArray['c6bfn_act_notice'][0] .= '<br />
            <a class="toc" href="pha_io03.php?act_notice_1">[Generate an activity notice]</a>';
        echo $Zfpf->make_html_form_1e($htmlFormArray, $Display);
        echo '<p>
            <input type="submit" name="pha_i2" value="Review what you typed into form" /><br />
            If you only wanted to upload files, you are done. Click on "Go back"</p>
        </form>'; // upload_files special case 3 of 3.
        if ($who_is_editing == '[A new database row is being created.]')
            echo '
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>';
        else
            echo '
            <form action="pha_io03.php" method="post"><p>
                <input type="submit" name="pha_o1" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['pha_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('pha_io03.php', 'pha_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') 
            $Zfpf->insert_sql_1s($DBMSresource, 't0pha', $ChangedRow);
        else {
            $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0pha', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c('PHA').'
        <p>
        The draft document you were editing has been updated with your changes. This document will remain a draft until it is issued by the team leader.</p>
        <form action="pha_io03.php" method="post"><p>
            <input type="submit" name="pha_o1" value="Back to record" /></p>
        </form>
        <form action="practice_o1.php" method="post"><p>
            <input type="submit" value="Back to records list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, i3, and approval code

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

