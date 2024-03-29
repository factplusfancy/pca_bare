<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all input and output HTML forms, except the:
//  - i1m file for listing existing records (and giving the option to start a new record) and

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'audit_i1m.php' or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// SPECIAL CASE -- cleanup if user hit "go back" from...
if (isset($_SESSION['SR']))
    unset($_SESSION['SR']);
if (isset($_SESSION['Scratch']['t0obstopic']))
    unset($_SESSION['Scratch']['t0obstopic']);
if (isset($_SESSION['Scratch']['t0obsmethod']))
    unset($_SESSION['Scratch']['t0obsmethod']);
if (isset($_SESSION['Scratch']['t0obsresult']))
    unset($_SESSION['Scratch']['t0obsresult']);
if (isset($_SESSION['Scratch']['t0fragment']))
    unset($_SESSION['Scratch']['t0fragment']);
if (isset($_SESSION['Scratch']['t0audit_fragment']))
    unset($_SESSION['Scratch']['t0audit_fragment']);
if (isset($_SESSION['Scratch']['t0action']))
    unset($_SESSION['Scratch']['t0action']);

// Get useful information...
$User = $Zfpf->current_user_info_1c();
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$EditAuth = FALSE;
if ($User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
    $EditAuth = TRUE;
$EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
$UserIsProcessPSMLeader = FALSE; // Caution, record not yet selected, need to reset to FALSE for templates.
if (isset($_SESSION['StatePicked']['t0process']['k0process'])) { // This app requires these reports, except templates, to be associated with a process.
    $Process = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['StatePicked']['t0process']['k0process']);
    if ($_SESSION['t0user']['k0user'] == $Process['AELeader_k0user'])
        $UserIsProcessPSMLeader = TRUE;
}

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('<a id="c5name"></a>Report type. Used in report headings', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF),
    'c5ts_as_of' => array('<a id="c5ts_as_of"></a>"As of" date and time, approximately marking when observations were made, such as when the report leader, below, finished giving a preliminary spoken or written report to the reponsible Owner/Operator represenative', ''), // This field isn\'t required because the "as of" date may not be known initially; for example, a user may start a report before the exit meeting.
    'k0user_of_leader' => array('<a id="k0user_of_leader"></a>Leader for this report and the observations and conclusions it describes (the report leader)', ''),
    'c6audit_scope' => array('<a id="c6audit_scope"></a>Scope', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF),
    'c6bfn_act_notice' => array('<a id="c6bfn_act_notice"></a>Activity notice(s) posted', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
    'c6howtoinstructions' => array('<a id="c6howtoinstructions"></a>Instructions on resolution, retention, any certification, and next deadline', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF),
    'c6background' => array('<a id="c6background"></a>Background', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF),
    'c6audit_method' => array('<a id="c6audit_method"></a>Method', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF),
    'c6auditor_qualifications' => array('<a id="c6auditor_qualifications"></a>Qualifications of the report leader and any others on team', REQUIRED_FIELD_ZFPF, C6SHORT_MAX_BYTES_ZFPF),
    'c6bfn_auditor_notes' => array('<a id="c6bfn_auditor_notes"></a>Supporting documents (such as any entrance and exit meeting attendance; see also documents for each observation result)', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
    'c6suggestions' => array('<a id="c6suggestions"></a>Suggestions and Comments', '', C6LONG_MAX_BYTES_ZFPF)
);

// Left-hand contents
if (isset($_POST['audit_i0n']) or isset($_POST['audit_template']) or isset($_POST['audit_o1']) or isset($_GET['audit_o1']) or isset($_GET['audit_o1_from']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']) or isset($_GET['leader_approval_1']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c5name' => 'Type of report',
        'c5ts_as_of' => '"As of" date and time',
        'k0user_of_leader' => 'Leader',
        'c6audit_scope' => 'Scope',
        'c6bfn_act_notice' => 'Activity notice', 
        'c6howtoinstructions' => 'Instructions',
        'c6background' => 'Background',
        'c6audit_method' => 'Method',
        'c6auditor_qualifications' => 'Qualifications',
        'c6bfn_auditor_notes' => 'Supporting documents', 
        'c6suggestions' => 'Suggestions'
    );

// The if clauses below determine which HTML button the user pressed.

// Import a draft report from a JSON file.
// TO DO IF NEEDED IN THE FUTURE -- START
    // The audit_json_import code below assumes that k0fragment, k0obsmethod, and k0obstopic in the imported JSON 
    // are numbered exactly like in the PSM-CAP App template files and point to the same content. In other words, 
    // the code below assumes that there have been neither primary key (k0) changes nor unwanted content changes 
    // to the template files below since the report, that the JSON was exported from, was first created.
    // includes/templates/cap_fragments.php
    // includes/templates/nh3r_general_duty.php (the general duty rule fragments)
    // includes/templates/psm_fragments.php
    // includes/templates/nh3r_obsmethod.php (these are sample observation methods)
    // includes/templates/nh3r_obstopic.php
    //
    // The export_audit_json_1 code below exports all t0fragment, t0obsmethod, and t0obstopic data, so JSON files created by it
    // could be used to import the rule fragments, sample observation methods, and observation topics in the JSON file,
    // but code for this additional importing would need to be written.
// TO DO IF NEEDED IN FUTURE -- END
if (isset($_POST['audit_json_import'])) {
    // Additional security check
    if (!isset($_SESSION['StatePicked']['t0process']['k0process']) or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or $UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__);
    $ArrayAudit = '';
    $ProblemMessage = '';
    if (isset($_FILES['file_1_audit_json']['tmp_name'])) {
        $Zfpf->files_array_check_1e('audit_json', 'practice_o1.php'); // This function echos and exits if there is a problem.
        $ArrayAudit = $Zfpf->read_file_1e($_FILES['file_1_audit_json']['tmp_name']); // Returns empty string (false) on failure.
    }
    if (!$ArrayAudit)
        $ProblemMessage = '<p>The app couldn\'t read the JSON file you selected.</p>'; // Most errors, like no file selected, are caught above by FilesZfpf::files_array_check_1e
    $ArrayAudit = json_decode($ArrayAudit, TRUE); // The second parameter must be TRUE to return an array.
    if (!isset($ArrayAudit['t0audit']))
        $ProblemMessage .= '<p>A t0audit field was not found in the JSON file you selected.</p>';
    elseif (count($ArrayAudit['t0audit']) != 1)
        $ProblemMessage .= '<p>More than one t0audit fields were found in the JSON file you selected. Currently, only one report can be imported per JSON file.</p>';
    if ($ProblemMessage) {
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Problem importing draft report from JSON file</h2>
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
    // t0audit
    $ImportFields = array('c5name', 'c6howtoinstructions', 'c6background', 'c6audit_scope', 'c6audit_method');
    $ImportedAuditKey = key($ArrayAudit['t0audit']); // Verified above that $ArrayAudit['t0audit'] holds exactly one value.
    $Uploader = $Zfpf->current_user_info_1c();
    $AuditRow = array(
        'k0audit' => time().mt_rand(1000000, 9999999),
        'k0process' => $_SESSION['StatePicked']['t0process']['k0process'],
        'c5name' => $EncryptedNothing,
        'c5ts_as_of' => $EncryptedNothing,
        'k0user_of_leader' => $_SESSION['t0user']['k0user'], // Default leader is the user who creates the record here. Can change later.
        'c6bfn_act_notice' => $EncryptedNothing,
        'c5ts_leader' => $EncryptedNothing,
        'c6nymd_leader' => $EncryptedNothing,
        'k0user_of_certifier' => 0,
        'c5ts_certifier' => $EncryptedNothing,
        'c6nymd_certifier' => $EncryptedNothing,
        'c6howtoinstructions' => $EncryptedNothing,
        'c6background' => $EncryptedNothing,
        'c6audit_scope' => $EncryptedNothing,
        'c6audit_method' => $EncryptedNothing,
        'c6auditor_qualifications' => $EncryptedNothing,
        'c6bfn_auditor_notes' => $EncryptedNothing,
        'c6suggestions' => $EncryptedNothing,
        'c5who_is_editing' => $EncryptedNobody
    );
    foreach ($ImportFields as $Vfn) { // fn stands for field name -- aka database table column name.
        if (isset($ArrayAudit['t0audit'][$ImportedAuditKey][$Vfn]))
            $AuditRow[$Vfn] = $Zfpf->encrypt_1c($ArrayAudit['t0audit'][$ImportedAuditKey][$Vfn]); // $ArrayAudit was decoded above from a JSON file, whose elements cannot be encrypted, so encrypt now. (If the entire JSON file was encrypted, it would have needed to be decrypted before uploading (importing) to the app. A purpose of JSON file export/import is to create a file that stands alone, independent from the app or a particular deployment of the app, so the app's encryption cannot be used for it.)
    }
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Zfpf->insert_sql_1s($DBMSresource, 't0audit', $AuditRow);
    // t0audit_obstopic
    $NewRow['c5who_is_editing'] = $EncryptedNobody;
    $Count = 0;
    if (isset($ArrayAudit['t0audit_obstopic'])) foreach ($ArrayAudit['t0audit_obstopic'] as $V) {
        if (isset($V['k0obstopic'])) { // Don't bother inserting unless this is set.
            $NewRow['k0audit_obstopic'] = time().mt_rand(100, 999).$Count++; // Allows up to 10,000 database-table rows while avoiding conflicts for these inserts.
            $NewRow['k0audit'] = $AuditRow['k0audit'];
            $NewRow['k0obstopic'] = $V['k0obstopic'];
            $Zfpf->insert_sql_1s($DBMSresource, 't0audit_obstopic', $NewRow, FALSE); // False means don't record in history because this is done for t0audit, which is adequate.
        }
    }
    unset($NewRow);
    // t0audit_fragment
    $NewRow['c5who_is_editing'] = $EncryptedNobody;
    $Count = 0;
    if (isset($ArrayAudit['t0audit_fragment'])) foreach ($ArrayAudit['t0audit_fragment'] as $K => $V) {
        if (isset($V['k0fragment'])) { // Don't bother inserting unless this is set.
            $NewRow['k0audit_fragment'] = time().mt_rand(100, 999).$Count++;
            $AuFMap[$K] = $NewRow['k0audit_fragment']; // $K is the old k0audit_fragment, $NewRow['k0audit_fragment'] is the new k0audit_fragment.
            $NewRow['k0audit'] = $AuditRow['k0audit'];
            $NewRow['k0fragment'] = $V['k0fragment'];
            $Zfpf->insert_sql_1s($DBMSresource, 't0audit_fragment', $NewRow, FALSE);
        }
    }
    unset($NewRow);
    // t0audit_fragment_obsmethod
    $NewRow['c5who_is_editing'] = $EncryptedNobody;
    $Count = 0;
    if (isset($ArrayAudit['t0audit_fragment_obsmethod'])) foreach ($ArrayAudit['t0audit_fragment_obsmethod'] as $V) {
        $AuFKeyOld = $V['k0audit_fragment'];
        // $ActionKeyOld = $V['k0action'];
        if (isset($AuFMap[$AuFKeyOld]) and isset($V['k0obsmethod'])) {
            $NewRow['k0audit_fragment_obsmethod'] = time().mt_rand(100, 999).$Count++;
            $NewRow['k0audit_fragment'] = $AuFMap[$AuFKeyOld];
            $NewRow['k0obsmethod'] = $V['k0obsmethod'];
            $Zfpf->insert_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $NewRow, FALSE);
        }
    }
    unset($NewRow);
    // t0obsresult
    $NewRow['c5who_is_editing'] = $EncryptedNobody;
    $Count = 0;
    $ImportFields = array('c5_obstopic_id', 'c6obsmethod_as_done', 'c6obsresult', 'c6bfn_supporting');
    if (isset($ArrayAudit['t0obsresult'])) foreach ($ArrayAudit['t0obsresult'] as $K => $V) {
        if (isset($V['k0obsmethod'])) { // Don't bother inserting unless this is set.
            $NewRow['k0obsresult'] = time().mt_rand(100, 999).$Count++;
            $OrMap[$K] = $NewRow['k0obsresult']; // $K is the old k0obsresult, $NewRow['k0obsresult'] is the new k0obsresult.
            $NewRow['k0audit'] = $AuditRow['k0audit'];
            $NewRow['k0obsmethod'] = $V['k0obsmethod'];
            foreach ($ImportFields as $Vfn) {
                if (isset($V[$Vfn]))
                    $NewRow[$Vfn] = $Zfpf->encrypt_1c($V[$Vfn]);
                else
                    $NewRow[$Vfn] = $EncryptedNothing;
            }
            $Zfpf->insert_sql_1s($DBMSresource, 't0obsresult', $NewRow, FALSE);
        }
    }
    unset($NewRow);
    // t0action -- typically not in templates, but needed to import an existing report into a new deployment...
    $NewRow['c5who_is_editing'] = $EncryptedNobody;
    $Count = 0;
    $ImportFields = array('c5name', 'c5priority', 'c5affected_entity', 'c6deficiency', 'c6details');
    if (isset($ArrayAudit['t0action'])) foreach ($ArrayAudit['t0action'] as $K => $V) {
        $NewRow['k0action'] = time().mt_rand(100, 999).$Count++;
        $ActionMap[$K] = $NewRow['k0action'];
        $NewRow['c5status'] = $Zfpf->encrypt_1c('Draft proposed action');
        $NewRow['k0affected_entity'] = 0; // Update below, if c5affected_entity is imported for an action.
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
            if (isset($V[$Vfn])) {
                $NewRow[$Vfn] = $Zfpf->encrypt_1c($V[$Vfn]);
                if ($Vfn == 'c5affected_entity') {
                    if ($V[$Vfn] == 'Owner-wide')
                        $NewRow['k0affected_entity'] = $_SESSION['StatePicked']['t0owner']['k0owner'];
                    if ($V[$Vfn] == 'Facility-wide')
                        $NewRow['k0affected_entity'] = $_SESSION['StatePicked']['t0facility']['k0facility'];
                    if ($V[$Vfn] == 'Process-wide')
                        $NewRow['k0affected_entity'] = $_SESSION['StatePicked']['t0process']['k0process'];
                }
            }
            else
                $NewRow[$Vfn] = $EncryptedNothing;
        }
        $Zfpf->insert_sql_1s($DBMSresource, 't0action', $NewRow, FALSE);
    }
    unset($NewRow);
    // t0obsresult_action
    $NewRow['c5who_is_editing'] = $EncryptedNobody;
    $Count = 0;
    if (isset($ArrayAudit['t0obsresult_action'])) foreach ($ArrayAudit['t0obsresult_action'] as $V) {
        $OrKeyOld = $V['k0obsresult'];
        $ActionKeyOld = $V['k0action'];
        if (isset($OrMap[$OrKeyOld]) and isset($ActionMap[$ActionKeyOld])) {
            $NewRow['k0obsresult_action'] = time().mt_rand(100, 999).$Count++;
            $NewRow['k0obsresult'] = $OrMap[$OrKeyOld];
            $NewRow['k0action'] = $ActionMap[$ActionKeyOld];
            $Zfpf->insert_sql_1s($DBMSresource, 't0obsresult_action', $NewRow, FALSE);
        }
    }
    $Zfpf->close_connection_1s($DBMSresource);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Importing report from a JSON file complete.</h2>
    <p>
    Review the report to verify this import was complete and correct.</p>
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// generate activity notice code, put before i0n initialization of $_SESSION['Selected'], for security check below.
if (isset($_GET['act_notice_1'])) {
    if (!isset($_SESSION['Selected']['k0audit']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/ActNoticeZfpf.php';
    $ActNoticeZfpf = new ActNoticeZfpf;
    echo $ActNoticeZfpf->act_notice_generate($Zfpf, 'audit');
    $Zfpf->save_and_exit_1c();
}

// i0n and SPECIAL CASE audit_template code
if (isset($_POST['audit_i0n']) or isset($_POST['audit_template'])) {
    // Additional security check.
    if (!isset($_SESSION['StatePicked']['t0process']) or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or $UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // Initialize $_SESSION['Selected']
    $_SESSION['Selected'] = array(
        'k0audit' => time().mt_rand(1000000, 9999999),
        'k0process' => $_SESSION['StatePicked']['t0process']['k0process'],
        'c5name' => $EncryptedNothing,
        'c5ts_as_of' => $EncryptedNothing,
        'k0user_of_leader' => $_SESSION['t0user']['k0user'], // Default leader is the user who creates the record here. Can change later.
        'c6bfn_act_notice' => $EncryptedNothing,
        'c5ts_leader' => $EncryptedNothing,
        'c6nymd_leader' => $EncryptedNothing,
        'k0user_of_certifier' => 0,
        'c5ts_certifier' => $EncryptedNothing,
        'c6nymd_certifier' => $EncryptedNothing,
        'c6howtoinstructions' => $EncryptedNothing,
        'c6background' => $EncryptedNothing,
        'c6audit_scope' => $EncryptedNothing,
        'c6audit_method' => $EncryptedNothing,
        'c6auditor_qualifications' => $EncryptedNothing,
        'c6bfn_auditor_notes' => $EncryptedNothing,
        'c6suggestions' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
    if (isset($_POST['audit_template'])) {
        // Additional security check.
        if (!isset($_POST['selected']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0audit'][$CheckedPost]['k0audit'])) // See audit_i1m.php
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        if ($_SESSION['SelectResults']['t0audit'][$CheckedPost]['k0user_of_certifier'] == 0 and $_SESSION['SelectResults']['t0audit'][$CheckedPost]['k0audit'] >= 100000) { // See schema
            unset($_SESSION['Selected']);
            echo $Zfpf->xhtml_contents_header_1c().'<h2>
            You selected a draft audit report</h2>
            <p>
            Draft audit reports may be created from issued or template audit reports, but not from draft reports.</p>
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        // Overwrite the array elements below with their template values. Other elements remain as initialized by i0n code.
        $_SESSION['Selected']['c5name'] = $_SESSION['SelectResults']['t0audit'][$CheckedPost]['c5name'];
        $_SESSION['Selected']['c6nymd_leader'] = $Zfpf->encrypt_1c($_SESSION['SelectResults']['t0audit'][$CheckedPost]['k0audit']); // See schema and $SourceTemplateKey below.
        $_SESSION['Selected']['c6howtoinstructions'] = $_SESSION['SelectResults']['t0audit'][$CheckedPost]['c6howtoinstructions'];
        $_SESSION['Selected']['c6background'] = $_SESSION['SelectResults']['t0audit'][$CheckedPost]['c6background'];
        $_SESSION['Selected']['c6audit_scope'] = $_SESSION['SelectResults']['t0audit'][$CheckedPost]['c6audit_scope'];
        $_SESSION['Selected']['c6audit_method'] = $_SESSION['SelectResults']['t0audit'][$CheckedPost]['c6audit_method'];
        $_SESSION['Selected']['c6auditor_qualifications'] = $_SESSION['SelectResults']['t0audit'][$CheckedPost]['c6auditor_qualifications'];
        $_SESSION['Selected']['c6suggestions'] = $_SESSION['SelectResults']['t0audit'][$CheckedPost]['c6suggestions'];
        unset($_SESSION['SelectResults']);
        // Template juntion-table records (audit_fragment, etc.) are associated in i3 code, in case user cancels at i1 or i2 stage.
        $_POST['audit_i0n'] = TRUE; // Going forward, same handling as i0n code.
    }
}

// Set $ReportType here, so its set in i0n and audit_template cases.
if (isset($_SESSION['Selected']['k0audit'])) {
    $ReportType = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
    if ($ReportType != '[Nothing has been recorded in this field.]')
        $ReportType = $ReportType.' '; // Trailing space so no-effect if blank.
    else
        $ReportType = '';
}

// history_o1 code
if (isset($_GET['audit_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0audit']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0audit', $_SESSION['Selected']['k0audit']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one record', 'audit_io03.php', 'audit_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0audit']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'audit_io03.php', 'audit_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'audit_io03.php', 'audit_o1');
    $_POST['audit_o1'] = 1; // Only needed as catch, after minor stuff, like user forgot to select a file before downloading, or password check before download.
}

// o1 code
if (isset($_POST['audit_o1']) or isset($_GET['audit_o1'])) {
    // Additional security check.
    if (!isset($_SESSION['Selected']['k0audit']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0audit'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (!isset($_SESSION['Selected']['k0audit'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0audit'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0audit'][$CheckedPost];
        // SPECIAL CASE unset($_SESSION['SelectResults']); in if clause below to clean up after "Go back" from downstream
    }
    $Zfpf->clear_edit_lock_1c();
    if (isset($_SESSION['SelectResults']))
        unset($_SESSION['SelectResults']);
    // Cannot add "Generate an activity notice" option, in o1 display, because these go back to i1, ejecting user if not draft...
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Selected'], TRUE);
    // Handle k0 field(s)
    if ($_SESSION['Selected']['k0audit'] < 100000) { // Templates don't have a report leader and are not associated with a process.
        $Display['k0user_of_leader'] = 'None, because this is a template.';
        $Process['AEFullDescription'] = 'Not associated with a process because this is a template.';
        $UserIsProcessPSMLeader = FALSE;
    }
    else {
        if (!isset($_SESSION['StatePicked']['t0process']) or $_SESSION['Selected']['k0process'] != $_SESSION['StatePicked']['t0process']['k0process'])
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ReportLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
        $Display['k0user_of_leader'] = $ReportLeader['NameTitle'].', '.$ReportLeader['Employer'];
    }
    // Have to set $ReportType here, because until above $_SESSION['Selected'] was not set.
    $ReportType = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
    if ($ReportType != '[Nothing has been recorded in this field.]')
        $ReportType = $ReportType.' '; // Trailing space so no-effect if blank.
    else
        $ReportType = '';
    // Extra left-hand contents only for o1 code
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors']['acvo_buttons'] = 'Actions, compliance verifications, and observations';
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    Report for<br />
    '.$Process['AEFullDescription'].'</h2>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'audit_io03.php', $_SESSION['Selected'], $Display);
    $Message .= '<p><a id="acvo_buttons"></a>';
    if ($_SESSION['Selected']['k0audit'] >= 100000) // Templates cannot have actions.
        $Message .= '
        <a class="toc" href="audit_io03.php?view_audit_actions"><b>Actions, proposed or referenced, view.</b></a><br />
        <a href="glossary.php#actions" target="_blank">Actions</a> may include observed deficiencies and resolution options.</p><p>';
    $Message .= '
        <a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m=1"><b>Compliance verifications, view.</b></a><br />
        Lists <a href="glossary.php#fragment" target="_blank">rule fragments</a> and observations made to check compliance with them.</p><p>
        <a class="toc" href="obsresult_io03.php?obsresult_i1m"><b>Observations, view or input.</b></a><br />
        Potentially needed and any actually made <a href="glossary.php#obstopic" target="_blank">observations topics, sample methods, as-done methods, and results</a></p>';
    // Check if anyone else is editing the selected row and check user privileges. See messages to the user below regarding privileges.
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        $Message .= '<p><b>'.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    elseif ($EditAuth) {
        if ($_SESSION['Selected']['k0user_of_certifier']) { // Means the report has been issued; see app schema.
            if ($_SESSION['Selected']['k0audit'] < 100000) // Template reports may not be issued.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            if ($_SESSION['Selected']['k0user_of_certifier'] == 1) {
                if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
                    $Message .= '<p>
                    <a class="toc" href="audit_io03.php?leader_approval_c1">Retract this report, including its observations.</a></p>';
                if ($UserIsProcessPSMLeader)
                    $Message .= '<p>
                    <a class="toc" href="audit_io03.php?certifier_approval_1">Owner/Operator certification</a></p>';
            }
            else {
                $Message .= $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_certifier']);
                if ($UserIsProcessPSMLeader)
                    $Message .= '<p>
                    <a class="toc" href="audit_io03.php?certifier_approval_c1">Cancel above certification.</a></p>';
            }
        }
        else {
            if ($_SESSION['Selected']['k0audit'] >= 100000 or $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes') // Only app admins can edit templates.
                $Message .= '<p>
                <a class="toc" href="audit_io03.php?audit_o1_from">Update introductory text.</a></p>';
            if ($_SESSION['Selected']['k0audit'] < 100000) // A web_app_admin viewing a template.
                $Message .= '<p>
                This is a template report. It cannot have actions. Templates cannot be issued, and only app admins can edit or export them.</p>';
            elseif ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'] or $UserIsProcessPSMLeader) {
                if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
                    $Message .= '<p>
                    <a class="toc" href="audit_io03.php?leader_approval_1">Issue this report, including its observations and proposed actions, and permanently log the proposed actions in this app\'s action register.</a></p>';
                else
                    $Message .= '<p>
                    An audit report can only be issued by its report leader. You are not the recorded report leader.</p>';
                if ($UserIsProcessPSMLeader)
                    $Message .= '<p>
                    <a class="toc" href="audit_io03.php?change_leader_1">Change the report leader, who can issue an audit report or retract one that hasn\'t been certified.</a></p>';
                $Message .= '<p>
                <a class="toc" href="audit_io03.php?discard_draft_audit_1">Discard this draft report, including its observations.</a></p>';
            }
        }
        if ($_SESSION['Selected']['k0audit'] >= 100000 or $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes') // Only app admins can export templates. 
            $Message .= '<p>
            <a class="toc" href="audit_io03.php?export_audit_json_1">Export audit to JSON file</a></p>';
    }
    elseif (!$_SESSION['Selected']['k0user_of_certifier']) {
        if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
            $Message .= '<p><b>
            Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
        if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            $Message .= '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    if ($_SESSION['Selected']['k0user_of_certifier'])
        $Message .= '<p>
        <b>Issued by: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']).'</b><br />
        This is not a draft report. Once a report has been issued by its report leader, the issued version cannot be changed. The report leader may retract the report and then it may be edited. Or, the action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p>';
    $Message .= '<p>
    <a class="toc" href="audit_io03.php?audit_history_o1">History of this record</a></p><p>
    <a class="toc" href="practice_o1.php">Go back</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// view_audit_actions, discard_draft_audit, leader_approval, certifier_approval, change_leader code, and i1, i2, i3 code
if (isset($_SESSION['Selected']['k0audit'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0audit', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    // Additional security check 1.
    if ($_SESSION['Selected']['k0audit'] >= 100000 and (!isset($_SESSION['StatePicked']['t0process']) or $_SESSION['Selected']['k0process'] != $_SESSION['StatePicked']['t0process']['k0process']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_GET['audit_o1_from']) or isset($_GET['leader_approval_1']))
        $Zfpf->edit_lock_1c('audit', 'this report or one of its supporting records'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error. Edit lock for change_leader_1 is done in change_leader_2, so it can be cleared in change_leader_1. Not needed for leader_approval_c1
    // Get useful information
    if ($_SESSION['Selected']['k0audit'] < 100000)
        $Process['AEFullDescription'] = 'Not associated with a process because this is a template.';
    if ($_SESSION['Selected']['k0audit'] >= 100000)  // Templates don't have a report leader.
        $ReportLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);

    // view_audit_actions code
    if (isset($_GET['view_audit_actions']) or isset($_POST['view_audit_actions'])) {
        // Additional security check -- none possible: privileges to view audit also allow viewing list of its actions.
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Report for<br />
        '.$Process['AEFullDescription'].'</h2><p>
        <b>Actions, proposed or referenced.</b><br />
        If a deficiency, found by the observations, can be resolved by completing an open action, already in this app\'s action register, this open action should be referenced. Otherwise, a proposed action should be drafted, which this app logs in its action register once this report is issued by its report leader.</p>';
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
        $ccsaZfpf = new ccsaZfpf;
        list($_SESSION['SR']['t0action'], $NotNeededMessage) = $ccsaZfpf->other_actions_in_audit($Zfpf); // Returns array with all actions in report because $_SESSION['Scratch']['t0obsresult'] isn't set.
        if ($_SESSION['SR']['t0action']) {
            $Message .= '<p>';
            foreach ($_SESSION['SR']['t0action'] as $KA => $VA) {
                if ($KA) // No line break for first pass, when $KA ==0.
                    $Message .= '<br />';
                $Message .= '<a class="toc" href="ar_io03.php?ar_o1='.$KA.'">'.$Zfpf->decrypt_1c($VA['c5name']).'</a>';
            }
            $Message .= '</p>';
        }
        else
            $Message .= '<p>
            <b>None found.</b> No actions, proposed or referenced, were found in this report. Actions are added when documenting observations that find deficiencies, by users with proper privileges.</p>';
        $Message .= '<p>
        <a class="toc" href="audit_io03.php?audit_o1#bottom">Go back</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Additional security check 2. Must be after the above view_audit_actions, so users without $EditAuth can view audit actions.
    if (($who_is_editing != '[A new database row is being created.]' and (!$EditAuth or ($_SESSION['Selected']['k0audit'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes'))) or ($who_is_editing == '[A new database row is being created.]' and $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject

    // Export to JSON
    if (isset($_GET['export_audit_json_1'])) {
        $OmKeys = array();
        $FKeys = array();
        $AKeys = array();
        $AuditKey = $_SESSION['Selected']['k0audit'];  // Use database-table keys as array keys to avoid conflicts
        $ArrayAudit['t0audit'][$AuditKey] = $_SESSION['Selected'];
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $AuConditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        list($SRAuOt, $RRAuOt) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_obstopic', $AuConditions);
        if ($RRAuOt) foreach ($SRAuOt as $VAuOt) {
            $AuOtKey = $VAuOt['k0audit_obstopic'];
            $ArrayAudit['t0audit_obstopic'][$AuOtKey] = $VAuOt;
            $Conditions[0] = array('k0obstopic', '=', $VAuOt['k0obstopic']);
            list($SROt, $RROt) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic', $Conditions);
            if ($RROt != 1)
                error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. '.@$RROt.' t0obstopic rows returned for k0obstopic = '.@$VAuOt['k0obstopic']);
            if ($RROt) { // Regardless of error above, add first observation topic (Ot) to array that will be JSON encoded.
                $OtKey = $SROt[0]['k0obstopic'];
                $ArrayAudit['t0obstopic'][$OtKey] = $SROt[0];
            }
            list($SROtOm, $RROtOm) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic_obsmethod', $Conditions);
            if ($RROtOm) foreach ($SROtOm as $VOtOm) {
                $OtOmKey = $VOtOm['k0obstopic_obsmethod'];
                $ArrayAudit['t0obstopic_obsmethod'][$OtOmKey] = $VOtOm;
                if (!in_array($VOtOm['k0obsmethod'], $OmKeys)) {
                    $OmKey = $VOtOm['k0obsmethod'];
                    $OmKeys[] = $OmKey;
                    $Conditions[0] = array('k0obsmethod', '=', $OmKey);
                    list($SROm, $RROm) = $Zfpf->select_sql_1s($DBMSresource, 't0obsmethod', $Conditions);
                    if ($RROm != 1)
                        error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. '.@$RROm.' t0obsmethod rows returned for k0obsmethod = '.@$OmKey);
                    if ($RROm)
                        $ArrayAudit['t0obsmethod'][$OmKey] = $SROm[0];
                }
            }
        }
        list($SRAuF, $RRAuF) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment', $AuConditions);
        if ($RRAuF) foreach ($SRAuF as $VAuF) {
            $AuFKey = $VAuF['k0audit_fragment'];
            $ArrayAudit['t0audit_fragment'][$AuFKey] = $VAuF;
            $Conditions[0] = array('k0audit_fragment', '=', $VAuF['k0audit_fragment']);
            list($SRAuFOm, $RRAuFOm) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $Conditions);
            if ($RRAuFOm) foreach ($SRAuFOm as $VAuFOm) {
                $AuFOmKey = $VAuFOm['k0audit_fragment_obsmethod'];
                $ArrayAudit['t0audit_fragment_obsmethod'][$AuFOmKey] = $VAuFOm;
            }
            if (!in_array($VAuF['k0fragment'], $FKeys)) {
                $FKey = $VAuF['k0fragment'];
                $FKeys[] = $FKey;
                $Conditions[0] = array('k0fragment', '=', $FKey);
                list($SRF, $RRF) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment', $Conditions);
                if ($RRF != 1)
                    error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. '.@$RRF.' t0fragment rows returned for k0fragment = '.@$FKey);
                if ($RRF)
                    $ArrayAudit['t0fragment'][$FKey] = $SRF[0];
            }
        }
        list($SROr, $RROr) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $AuConditions);
        if ($RROr) foreach ($SROr as $VOr) {
            $OrKey = $VOr['k0obsresult'];
            $ArrayAudit['t0obsresult'][$OrKey] = $VOr;
            $Conditions[0] = array('k0obsresult', '=', $VOr['k0obsresult']);
            list($SROrA, $RROrA) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult_action', $Conditions);
            if ($RROrA) foreach ($SROrA as $VOrA) {
                $OrAKey = $VOrA['k0obsresult_action'];
                $ArrayAudit['t0obsresult_action'][$OrAKey] = $VOrA;
                if (!in_array($VOrA['k0action'], $AKeys)) {
                    $AKey = $VOrA['k0action'];
                    $AKeys[] = $AKey;
                    $Conditions[0] = array('k0action', '=', $AKey);
                    list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $Conditions);
                    if ($RRA != 1)
                        error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. '.@$RRA.' t0action rows returned for k0action = '.@$AKey);
                    if ($RRA)
                        $ArrayAudit['t0action'][$AKey] = $SRA[0];
                }
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        $ArrayAudit = json_encode($Zfpf->decrypt_array_1c($ArrayAudit)); // Must decrypt before JSON encoding. json_encode returns false on failure.
        if (!$ArrayAudit) {
            $ArrayAudit = 'JSON encoding the process-safety audit failed.';
            $LocalName = 'audit_json_encoding_failed_at_'.time().'.txt';
        }
        else
            $LocalName = 'audit_'.$AuditKey.'_downloaded_at_'.time().'.json';
        $Zfpf->download_one_file($ArrayAudit, $LocalName); // FilesZfpf::download_one_file echos and exits.
    }

    // discard_draft_audit code
    if (isset($_GET['discard_draft_audit_1'])) {
        // Additional security check.
        if (!$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] or $_SESSION['Selected']['k0audit'] < 100000)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Discard the draft report you were just viewing?</h2><p>
        The information in the draft report will remain in this app\'s history tables, until they are purged per the Owner/Operator\'s policies.</p><p>
        A draft report may only be discarded by its report leader or the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader.</p>
        <form action="audit_io03.php" method="post"><p>
            <input type="submit" name="discard_draft_audit_2" value="Discard draft report" /></p>
        </form><p>
        <a class="toc" href="audit_io03.php?audit_o1#bottom">Go back</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // discard_draft_audit_2 code
    if (isset($_POST['discard_draft_audit_2'])) {
        // Additional security check.
        if (!$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] or $_SESSION['Selected']['k0audit'] < 100000)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        $ErrorLog = '';
        $Count = 0;
        $EncryptedTime = $Zfpf->encrypt_1c(time());
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit', $Conditions);
        if ($Affected != 1)
            $ErrorLog .= 't0audit rows deleted: '.$Affected;        
        list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_obstopic', $Conditions);
        if ($RR) foreach ($SR as $V) {
            $Conditions[0] = array('k0audit_obstopic', '=', $V['k0audit_obstopic']);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit_obstopic', $Conditions);
            if ($Affected != 1)
                $ErrorLog .= ' t0audit_obstopic rows deleted: '.$Affected;
        }
        $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment', $Conditions);
        if ($RR) foreach ($SR as $VAuF) {
            $Conditions[0] = array('k0audit_fragment', '=', $VAuF['k0audit_fragment']);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit_fragment', $Conditions);
            if ($Affected != 1)
                $ErrorLog .= ' t0audit_fragment rows deleted: '.$Affected;
            list($SRAuFOm, $RRAuFOm) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $Conditions);
            if ($RRAuFOm) foreach ($SRAuFOm as $VAuFOm) {
                $Conditions[0] = array('k0audit_fragment_obsmethod', '=', $VAuFOm['k0audit_fragment_obsmethod']);
                $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $Conditions);
                if ($Affected != 1)
                    $ErrorLog .= ' t0audit_fragment_obsmethod rows deleted: '.$Affected;
            }
        }
        $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
        if ($RR) foreach ($SR as $VOr) {
            $Conditions[0] = array('k0obsresult', '=', $VOr['k0obsresult']);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0obsresult', $Conditions);
            if ($Affected != 1)
                $ErrorLog .= ' t0obsresult rows deleted: '.$Affected;
            list($SROrA, $RROrA) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult_action', $Conditions);
            if ($RROrA) foreach ($SROrA as $VOrA) {
                $Conditions[0] = array('k0obsresult_action', '=', $VOrA['k0obsresult_action']);
                $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0obsresult_action', $Conditions);
                if ($Affected != 1)
                    $ErrorLog .= ' t0audit_fragment_obsmethod rows deleted: '.$Affected;
                $Conditions[0] = array('k0action', '=', $VOrA['k0action']);
                list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $Conditions);
                if ($RRA and $SRA[0]['k0user_of_ae_leader'] == -2) { // Action may already have been deleted. See app schema.
                    $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0action', $Conditions);
                    if ($Affected != 1)
                        $ErrorLog .= ' t0action rows deleted: '.$Affected;
                }
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Selected']);
        if ($ErrorLog) // Don't eject user here, probably not hacking. Log errors after running above report-removal code.
            error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' '.@$ErrorLog);
        echo $Zfpf->xhtml_contents_header_1c().'<p>
        The app attempted to discard the draft report you had selected, including, if any, its observations.</p>
        <form action="practice_o1.php" method="post"><p>
            <input type="submit" value="Back to reports list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // leader_approval_1 and leader_approval_c1 code
    if (isset($_GET['leader_approval_1']) or isset($_GET['leader_approval_c1'])) {
        // Additional security check.
        if (!$EditAuth or (isset($_GET['leader_approval_1']) and $_SESSION['Selected']['k0user_of_certifier']) or (isset($_GET['leader_approval_c1']) and $_SESSION['Selected']['k0user_of_certifier'] != 1) or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] or $_SESSION['Selected']['k0audit'] < 100000)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        // Set variables with applicable text.
        if (isset($_GET['leader_approval_1'])) {
            $AsOf = $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_as_of']);
            if (!is_numeric($AsOf) or $AsOf > time() or $AsOf < time()-31700000) { // Cannot be in the future or more than a year ago.
                echo $Zfpf->xhtml_contents_header_1c().'<h2>
                Invalid "as of" date and time</h2><p>
                The "as of" date and time is currently recorded as:<br />
                '.$Zfpf->timestamp_to_display_1c($AsOf).'</p><p>
                The "as of" date and time approximately marks the evaluation period, and the app requires that these be recorded. You may input a date and time in most common formats, which the app can typically convert into a timestamp. This cannot be an unreasonable date, such as in the future or more than a year ago.</p><p>
                <a class="toc" href="audit_io03.php?audit_o1#bottom">Go back</a></p>
                '.$Zfpf->xhtml_footer_1c();
                $Zfpf->save_and_exit_1c();
            }
            $ConfirmationButtonName = 'leader_approval_2';
            $ConfirmationButtonValue = 'Issue report';
            $ConfirmationText ='<p class="topborder">
            <b>BE CAREFUL:</b> Approving this report permanently logs its proposed actions in this app\'s action register, allowing other reports to reference them. You may retract this report later, but doing this will <b>not</b> remove its proposed actions from the action register. The action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p><p>
            <b>By clicking "'.$ConfirmationButtonValue.'" below, as the leader for this report and the observations and conclusions it describes (the report leader), I am confirming that:<br />
             - I verified, myself or through others whose qualifications I determined are adequate, that the observations described above were completed by the methods described above,<br />
             - I am qualified to serve as the report leader and to make this approval, and<br />
             - I approve the report shown above.</b></p><p>
            <b>Approved by:</b><br />';
        }
        else {
            $ConfirmationButtonName = 'leader_approval_c2';
            $ConfirmationButtonValue = 'Retract report';
            $ConfirmationText ='<p>
            Retracting this report will <b>not</b> remove its proposed actions from the action register. The action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p><p>
            <b>By clicking "'.$ConfirmationButtonValue.'" below, as the leader for this report and the observations and conclusions it describes (the report leader):<br />
             - I cancel the report leader\'s the approval of this report and<br />
             - I retract this report.</b></p><p>
            <b>Approval canceled by:</b><br />';
        }
        // Typical o1 code for introductory text fields, defined by $htmlFormArray here.
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_leader'] = $ReportLeader['NameTitle'].', '.$ReportLeader['Employer'];
        $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors']['obsresult'] = 'Observation results';
        $ApprovalText = '<h1>';
        if ($ReportType)
            $ApprovalText .= $ReportType.'<br />';
        $ApprovalText .= '
        Report for<br />
        '.$Process['AEFullDescription'].'</h1>
        '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'<h1><a id="obsresult"></a>
        <a class="toc" href="glossary.php#obstopic" target="_blank">Observation results</a></h1><p>
        Sorted by sample observation methods, truncated, which are the subheadings below.</p>';
        // Get observation topics associated with the selected report.
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        list($SROr, $RROr) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
        if (!$RROr) {
            echo $Zfpf->xhtml_contents_header_1c().'<h2>
            No observation results found</h2><p>
            An audit report cannot be issued without any observation results.</p><p>
            <a class="toc" href="audit_io03.php?audit_o1#bottom">Go back</a></p>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
        $ccsaZfpf = new ccsaZfpf;
        $htmlFormArray = $ccsaZfpf->html_form_array('obsresult', $Zfpf);
        $Types = array('action' => 'Actions, proposed or referenced');
        foreach ($SROr as $VOr)
            $OmKeys[] = $VOr['k0obsmethod'];
        array_multisort($SROr, $OmKeys); // Sample observation methods have keys ordered roughly following the PSM standard, see file includes/templates/nh3r_obsmethod.php
        foreach  ($SROr as $VOr) {
            if (!isset($OmKey) or $OmKey != $VOr['k0obsmethod']) {
                $OmKey = $VOr['k0obsmethod'];
                $Conditions[0] = array('k0obsmethod', '=', $VOr['k0obsmethod']);
                list($SROm, $RROm) = $Zfpf->select_sql_1s($DBMSresource, 't0obsmethod', $Conditions);
                if ($RROm != 1)
                    $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching observation methods to results. Contact app admin.</p>');
                $ApprovalText .= '<h2 class="topborder">
                <b>'.substr($Zfpf->decrypt_1c($SROm[0]['c6obsmethod']), 0, 105).'</b>...'.'</h2>';
                list($SRAuFOm, $RRAuFOm) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $Conditions);
                list($SROtOm, $RROtOm) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic_obsmethod', $Conditions);
                if ($RRAuFOm) {
                    $ApprovalText .= '<p>
                    <i>Related rule fragments:</i>';
                    foreach ($SRAuFOm as $VAuFOm) {
                        $Conditions[0] = array('k0audit_fragment', '=', $VAuFOm['k0audit_fragment'], 'AND');
                        $Conditions[1] = array('k0audit', '=', $_SESSION['Selected']['k0audit']); // k0audit_fragment for other audits are included in $SRAuFOm
                        list($SRAuF, $RRAuF) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment', $Conditions);
                        unset($Conditions);
                        if ($RRAuF) {
                            if ($RRAuF > 1)
                                $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching a sample observation method to its audit-rule fragments associations. Contact app admin.</p>');
                            $Conditions[0] = array('k0fragment', '=', $SRAuF[0]['k0fragment']);
                            list($SRF, $RRF) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment', $Conditions);
                            if ($RRF != 1)
                                $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching a sample observation method to rule fragments. Contact app admin.</p>');
                                $Quote = $Zfpf->decrypt_1c($SRF[0]['c6quote']);
                            $ApprovalText .= '<br />
                            '.$Zfpf->decrypt_1c($SRF[0]['c5name']).', '.$Zfpf->decrypt_1c($SRF[0]['c5citation']).', ';
                            if (strlen($Quote) > 100)
                                $ApprovalText .= substr($Quote, 0, 100).'...';
                            else
                                $ApprovalText .= $Quote;
                        }
                    }
                    $ApprovalText .= '</p>';
                }
                if ($RROtOm) {
                    $ApprovalText .= '<p>
                    <i>Related observation topics:</i>';
                    foreach ($SROtOm as $VOtOm) {
                        $Conditions[0] = array('k0obstopic', '=', $VOtOm['k0obstopic']);
                        list($SROt, $RROt) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic', $Conditions);
                        if ($RROt != 1)
                            $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching a sample observation method to observation topics. Contact app admin.</p>');
                        $ApprovalText .= '<br />
                        '.$Zfpf->decrypt_1c($SROt[0]['c5name']);
                    }
                    $ApprovalText .= '</p>';
                }
            }
            $Display = $Zfpf->select_to_display_1e($htmlFormArray, $VOr);
            $ApprovalText .= $Zfpf->select_to_o1_html_1e($htmlFormArray, 'obsresult_io03.php', $VOr, $Display, ' class="topborder"');
            $ApprovalText .= $ccsaZfpf->scenario_CCSA_Zfpf($VOr, $_SESSION['Selected']['k0user_of_certifier'], $User, $UserPracticePrivileges, $Zfpf, FALSE, 'obsresult', $Types, FALSE);
        }
        $Zfpf->close_connection_1s($DBMSresource);
        // Get list of all actions in report and show the details of each.
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/arZfpf.php';
        $arZfpf = new arZfpf;
        $htmlFormArray = $arZfpf->ar_html_form_array('obsresult');
        list($ActionsInReport, $Message) = $ccsaZfpf->other_actions_in_audit($Zfpf);
        if ($ActionsInReport) {
            $ApprovalText .= '<h2 class="topborder"><a id="actions_list"></a>Actions, proposed or referenced</h2>';
            $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors']['actions_list'] = 'Actions';
            foreach ($ActionsInReport as $V) {
                $Display = $Zfpf->select_to_display_1e($htmlFormArray, $V);
                if (isset($Display['c5status']) and $Display['c5status'] == 'Draft proposed action') // Recorded $ApprovalText needs to show all t0action:c5status as below, but only update database if issued, via leader_approval_2.
                    $Display['c5status'] = 'Needs resolution';
                $ApprovalText .= $Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $V, $Display, ' class="topborder"');
            }
        }
        $CurrentDate = date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j', time());
        $ApprovalText .= $ConfirmationText.'
        Name: <b>'.$User['Name'].'</b><br />
        Job Title: <b>'.$User['Title'].'</b><br />
        Employer<b>: '.$User['Employer'].'</b><br />
        Email Address<b>: '.$User['WorkEmail'].'</b><br />
        Date: <b>'.$CurrentDate.'</b></p>';
        $FixedLeftContents = '<p>'.$User['Name'].'<br />'.$User['Title'].'<br />'.$User['Employer'].'<br />';
        foreach ($_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] as $K => $V)
            $FixedLeftContents .= '<br />
            | <a class="toc" href="#'.$K.'">'.$V.'</a>'; // $K is Id and $V is description, of anchors.
        unset($_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors']);
        $FixedLeftContents .= '</p><p>
        <a class="toc" href="audit_io03.php?audit_o1#bottom">Go back</a></p>';
        // The second FALSE below, in function CoreZfpf::xhtml_contents_header_1c, means no log-off button, encourage "go back" to clear edit locks.
        echo $Zfpf->xhtml_contents_header_1c('Confirm Approval', FALSE, $FixedLeftContents).$ApprovalText.'
        <form action="audit_io03.php" method="post"><p>
            <input type="submit" name="'.$ConfirmationButtonName.'" value="'.$ConfirmationButtonValue.'" /></p>
        </form><p>
        <a class="toc" href="audit_io03.php?audit_o1#bottom">Go back</a></p>
        '.$Zfpf->xhtml_footer_1c();
        $_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
        $_SESSION['Scratch']['c6nymd_leader'] = $Zfpf->encrypt_1c($User['NameTitle'].', '. $User['Employer'].' '.$User['WorkEmail'].' on '.$CurrentDate);
        $Zfpf->save_and_exit_1c();
    }

    // leader_approval_2 and leader_approval_c2 code
    if (isset($_POST['leader_approval_2']) or isset($_POST['leader_approval_c2'])) {
        // Additional security check.
        if (!$EditAuth or (isset($_POST['leader_approval_2']) and $_SESSION['Selected']['k0user_of_certifier']) or (isset($_POST['leader_approval_c2']) and $_SESSION['Selected']['k0user_of_certifier'] != 1) or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] or $_SESSION['Selected']['k0audit'] < 100000)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        list($SROr, $RROr) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
        $EncryptedTime = $Zfpf->encrypt_1c(time());
        if (isset($_POST['leader_approval_2'])) {
            $Changes['c5ts_leader'] = $EncryptedTime;
            $Changes['c6nymd_leader'] = $_SESSION['Scratch']['c6nymd_leader'];
            $Changes['k0user_of_certifier'] = 1; // See schema.
            $Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
            $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing']; // edit lock only on approval, not necessary for canceling approval below.
            $IssuedRetracted = 'issued';
            $ActionsNotRetracted = '';
        }
        else {
            $Changes['c5ts_leader'] = $EncryptedNothing;
            $Changes['c6nymd_leader'] = $EncryptedNothing;
            $Changes['k0user_of_certifier'] = 0; // See schema.
            $IssuedRetracted = 'retracted';
            $ActionsNotRetracted = '<p>This did <b>not</b> remove proposed actions in the previously issued report from the action register.  The action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p>';
        }
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0audit', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        // Update $_SESSION['Selected']
        $_SESSION['Selected']['c5ts_leader'] = $Changes['c5ts_leader'];
        $_SESSION['Selected']['c6nymd_leader'] = $Changes['c6nymd_leader'];
        $_SESSION['Selected']['k0user_of_certifier'] = $Changes['k0user_of_certifier'];
        if (isset($_POST['leader_approval_2']) and $RROr) foreach ($SROr as $VOr) {
            // Update all t0action:c5status to 'Needs resolution'. This is not "undone" by leader_approval_c2, see "be careful" warning under leader_approval_1.
            $Conditions[0] = array('k0obsresult', '=', $VOr['k0obsresult']);
            list($SROrA, $RROrA) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult_action', $Conditions);
            if ($RROrA) foreach ($SROrA as $VOrA) {
                $Conditions[0] = array('k0action', '=', $VOrA['k0action']);
                list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $Conditions);
                if ($RRA != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRA);
                if ($SRA[0]['k0user_of_ae_leader'] == -2) {
                    // See the app schema. Only update actions created by this report, not already opened actions (in ar) that it references.
                    $NewStatus['c5status'] = $Zfpf->encrypt_1c('Needs resolution');
                    $NewStatus['k0user_of_ae_leader'] = 0; // See the app schema.
                    $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0action', $NewStatus, $Conditions);
                    if ($Affected != 1)
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                }
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the report leader and the process, facility, and owner leaders.
        $Chain = $Zfpf->up_the_chain_1c();
        $Chain['EmailAddresses'][] = $ReportLeader['WorkEmail'];
        $Chain['DistributionList'] .= '<br />
        Report leader: '.$ReportLeader['NameTitle'].', '.$ReportLeader['Employer'].' '.$ReportLeader['WorkEmail'].'</p>';
        $Subject = 'PSM-CAP: '.$ReportType.'report '.$IssuedRetracted;
        $Body = '<p>
        The report leader -- '.$ReportLeader['NameTitle'].', '.$ReportLeader['Employer'].' -- '.$IssuedRetracted.' the '.$ReportType.'report for:<br />
        * '.$Process['AEFullDescription'].'</p>
        '.$ActionsNotRetracted;
        $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $Chain['DistributionList']);
        $EmailSent = $Zfpf->send_email_1c($Chain['EmailAddresses'], $Subject, $Body);
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Report '.$IssuedRetracted.' for<br />
        '.$Process['AEFullDescription'].'</h2>
        '.$ActionsNotRetracted;
        if ($EmailSent)
            $Message .= '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            $Message .= '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        $Message .= '
        <form action="audit_io03.php" method="post"><p>
            <input type="submit" name="audit_o1" value="View report" /></p>
        </form>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // certifier_approval_1 and certifier_approval_c1 code
    if (isset($_GET['certifier_approval_1']) or isset($_GET['certifier_approval_c1'])) {
        // Additional security check.
        if (!$EditAuth or (isset($_GET['certifier_approval_1']) and $_SESSION['Selected']['k0user_of_certifier'] != 1) or (isset($_GET['certifier_approval_c1']) and $_SESSION['Selected']['k0user_of_certifier'] <= 1) or !$UserIsProcessPSMLeader or $_SESSION['Selected']['k0audit'] < 100000)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        if (isset($_GET['certifier_approval_1'])) {
            $ConfirmationButtonName = 'certifier_approval_2';
            $ConfirmationButtonValue = 'Approve certification statement';
            $ApprovalText = '<h2>
            Owner/Operator Certification</h2><p>
            On behalf of '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).', the Owner/Operator,<br />
            I certify that, on or about '.$Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_as_of'])).' (the "as of" date),<br />
            the Owner/Operator evaluated<br />
            '.$Process['AEFullDescription'].'<br />
            for compliance with the rules described in the scope (the rules) of the<br />
            '.$ReportType.'report, first communicated, in spoken or written form, to me on the "as of" date (the report),<br />
            to verify that the procedures and practices developed under the rules are adequate and are being followed,<br />
            except as described in the findings of the report or related action registers, resolution worksheets, or similar.<br />
            Note: you are not certifying the '.$ReportType.'report itself, so its text is not reproduced here.</p>';
        }
        else {
            $ConfirmationButtonName = 'certifier_approval_c2';
            $ConfirmationButtonValue = 'Cancel certification';
            $ApprovalText = '<h2>
            Cancel Owner/Operator Certification</h2><p>
            On behalf of the Owner/Operator, I <b>cancel</b> the most recent certification that as of '.$Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_as_of'])).' the Owner/Operator had evaluated '.$Process['AEFullDescription'].' for compliance as described in the most recent certification.</p>';
        }
        $CurrentDate = date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j', time());
        $ApprovalText .= '
        Name: <b>'.$User['Name'].'</b><br />
        Job Title: <b>'.$User['Title'].'</b><br />
        Employer<b>: '.$User['Employer'].'</b><br />
        Email Address<b>: '.$User['WorkEmail'].'</b><br />
        Date: <b>'.$CurrentDate.'</b></p>';
        echo $Zfpf->xhtml_contents_header_1c().$ApprovalText.'
        <form action="audit_io03.php" method="post"><p>
            '.$ConfirmationButtonValue.' by clicking the button below.<br />
            <input type="submit" name="'.$ConfirmationButtonName.'" value="'.$ConfirmationButtonValue.'" /></p>
        </form><p>
        <a class="toc" href="audit_io03.php?audit_o1#bottom">Go back</a></p>
        '.$Zfpf->xhtml_footer_1c();
        $_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
        $Zfpf->save_and_exit_1c();
    }

    // certifier_approval_2, certifier_approval_c2 code
    if (isset($_POST['certifier_approval_2']) or isset($_POST['certifier_approval_c2'])) {
        // Additional security check.
        if (!$EditAuth or (isset($_POST['certifier_approval_2']) and $_SESSION['Selected']['k0user_of_certifier'] != 1) or (isset($_POST['certifier_approval_c2']) and $_SESSION['Selected']['k0user_of_certifier'] <= 1) or !$UserIsProcessPSMLeader or $_SESSION['Selected']['k0audit'] < 100000)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $EncryptedTime = $Zfpf->encrypt_1c(time());
        if (isset($_POST['certifier_approval_2'])) {
            $Changes['k0user_of_certifier'] = $_SESSION['t0user']['k0user'];
            $Changes['c5ts_certifier'] = $EncryptedTime;
            $Changes['c6nymd_certifier'] = $_SESSION['Scratch']['ApprovalText']; // SPECIAL CASE: also record approval text here.
            $ApprovedCanceled = 'approved';
        }
        else {
            $Changes['k0user_of_certifier'] = 1;
            $Changes['c5ts_certifier'] = $EncryptedNothing;
            $Changes['c6nymd_certifier'] = $EncryptedNothing;
            $ApprovedCanceled = 'canceled';
        }
        $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0audit', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        // Update $_SESSION['Selected']
        $_SESSION['Selected']['c5ts_certifier'] = $Changes['c5ts_certifier'];
        $_SESSION['Selected']['c6nymd_certifier'] = $Changes['c6nymd_certifier'];
        $_SESSION['Selected']['k0user_of_certifier'] = $Changes['k0user_of_certifier'];
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the report leader and the process, facility, and owner leaders.
        $Chain = $Zfpf->up_the_chain_1c();
        $Chain['EmailAddresses'][] = $ReportLeader['WorkEmail'];
        $Chain['DistributionList'] .= '<br />
        Report leader: '.$ReportLeader['NameTitle'].', '.$ReportLeader['Employer'].' '.$ReportLeader['WorkEmail'].'</p>';
        $Subject = 'PSM-CAP: '.$ReportType.'certification '.$ApprovedCanceled;
        $Body = '<p>
        The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader -- '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' -- '.$ApprovedCanceled.' a '.$ReportType.'certification for:<br />
        * '.$Process['AEFullDescription'].'</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $Chain['DistributionList']);
        $EmailSent = $Zfpf->send_email_1c($Chain['EmailAddresses'], $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        '.$ReportType.'certification '.$ApprovedCanceled.'</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="audit_io03.php" method="post"><p>
            <input type="submit" name="audit_o1" value="View report" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    
    // Change the report leader code
    if (isset($_POST['change_leader_1']) or isset($_GET['change_leader_1'])) {
        if (!$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Zfpf->clear_edit_lock_1c(); // In case arrived here by canceling from change_leader_2
        echo $Zfpf->xhtml_contents_header_1c('Lookup User');
        $Zfpf->lookup_user_1c('audit_io03.php', 'audit_io03.php', 'change_leader_2', 'audit_o1');
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['change_leader_2'])) {
        if (!$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Zfpf->edit_lock_1c();
        $Conditions1[0] = array('k0process', '=', $_SESSION['Selected']['k0process']);
        $SpecialText = '<p><b>
        Pick the report leader</b></p><p>
        The current report leader will be replaced by the user you pick above.</p>';
        $Zfpf->lookup_user_wrap_2c(
            't0user_process', // $TableNameUserEntity
            $Conditions1,
            'audit_io03.php', // $SubmitFile
            'audit_io03.php', // $TryAgainFile
            array('k0user'), // $Columns1
            'audit_io03.php', // $CancelFile
            $SpecialText,
            'Assign report leader', // $SpecialSubmitButton
            'change_leader_3', // $SubmitButtonName
            'change_leader_1', // $TryAgainButtonName
            'audit_o1', // $CancelButtonName
            'c5ts_logon_revoked', // $FilterColumnName
            '[Nothing has been recorded in this field.]' // $Filter
            ); // This function echos and exits.
    }
    if (isset($_POST['change_leader_3'])) {
        if (!$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or !$UserIsProcessPSMLeader)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // Check user-input radio-button selection.
        // The user not selecting a radio button is OK in this case.
        if (isset($_POST['Selected'])) {
            $Selected = $Zfpf->post_length_blank_1c('Selected');
            if (isset($_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]))
                $k0user = $_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]; // This is the k0user of the newly selected user.
            else
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        }
        else {
            echo $Zfpf->xhtml_contents_header_1c('Nobody New').'<p>
            It appears you did not select anyone.</p>
            <form action="audit_io03.php" method="post"><p>
                <input type="submit" name="change_leader_1" value="Try again" /></p><p>
                <input type="submit" name="audit_o1" value="Cancel" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            unset($_SESSION['Scratch']['PlainText']['lookup_user']);
            $Zfpf->save_and_exit_1c();
        }
        unset($_SESSION['Scratch']['PlainText']['lookup_user']);
        // Update database with $k0user
        $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        $Changes['k0user_of_leader'] = $k0user;
        $Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
        // Update $_SESSION['Selected']
        $_SESSION['Selected']['k0user_of_leader'] = $Changes['k0user_of_leader'];
        $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0audit', $Changes, $Conditions, TRUE, $htmlFormArray); 
        // $Affected should not be zero because we confirmed that a new user was selected above.
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the any former report leader, the newly-assigned report leader, the process leader (who should be the current user).
        $NewLeader = $Zfpf->user_job_info_1c($k0user);
        $EmailAddresses = array($Process['AELeaderWorkEmail'], $NewLeader['WorkEmail']);
    	$Subject = 'PSM-CAP: report leader assigned to '.$NewLeader['NameTitle'];
        $Body = '<p> For '.$Process['AEFullDescription'].', '.$NewLeader['NameTitle'].', '.$NewLeader['Employer'].' was designated as the report leader by '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' (the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader).</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        New report leader: '.$NewLeader['NameTitle'].', '.$NewLeader['Employer'].' '.$NewLeader['WorkEmail'].'<br />
        Process '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' '.$Process['AELeaderWorkEmail'];
        if ($ReportLeader) { // This is set above, and holds the former report leader info.
            $EmailAddresses[] = $ReportLeader['WorkEmail'];
            $DistributionList .= '<br />
            Former report leader: '.$ReportLeader['NameTitle'].', '.$ReportLeader['Employer'].' '.$ReportLeader['WorkEmail'];
        }
        $DistributionList .= '</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], '', $DistributionList);
        $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Done').'<h2>
        You just assigned a new report leader.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="audit_io03.php" method="post"><p>
            <input type="submit" name="audit_o1" value="Back to viewing record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Additional security check for i1, i2, and i3 code
    if ($_SESSION['Selected']['k0user_of_certifier'])
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['audit_i0n']) or isset($_GET['audit_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        if ($_SESSION['Selected']['k0audit'] < 100000) // Templates don't have a report leader.
            $Display['k0user_of_leader'] = 'None, because this is a template.';
        else
            $Display['k0user_of_leader'] = $ReportLeader['NameTitle'].', '.$ReportLeader['Employer'];
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
    elseif (isset($_SESSION['Post']) and !isset($_POST['audit_i2'])) { // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        else {
            $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            foreach ($htmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Selected'][$K]);
                    // FilesZfpf::user_files_directory_1e has been checked, it supports t0audit
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, $K, 'audit_io03.php');
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
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Report for<br />
        '.$Process['AEFullDescription'].'</h2>
        <form action="audit_io03.php" method="post" enctype="multipart/form-data" >'; // upload_files special case 2 of 3. To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        // Add "Generate an activity notice" option, for i1 display, if not a new record. Do here to keep out of history table.
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        if ($who_is_editing != '[A new database row is being created.]') 
            $htmlFormArray['c6bfn_act_notice'][0] .= '<br />
            <a class="toc" href="audit_io03.php?act_notice_1">[Generate an activity notice]</a>';
        $Message .= $Zfpf->make_html_form_1e($htmlFormArray, $Display);
        $Message .= '<p>
            <input type="submit" name="audit_i2" value="Review what you typed into form" /><br />
            If you only wanted to upload files, you are done. Click on "Go back"</p>
        </form>'; // upload_files special case 3 of 3.
        if ($who_is_editing == '[A new database row is being created.]')
            $Message .= '<p>
            <a class="toc" href="practice_o1.php">Go back</a></p>';
        else
            $Message .= '<p>
            <a class="toc" href="audit_io03.php?audit_o1#bottom">Go back</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['audit_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        // SPECIAL CASE: start. Handle c5ts_as_of. See comment above, where $htmlFormArray is defined.
        $FormActionConfirm = 'audit_io03.php';
        if ($PostDisplay['c5ts_as_of'] != '[Nothing has been recorded in this field.]') { // Only quality check if user posts something.
            $AsOf = $Zfpf->text_to_timestamp_1c($PostDisplay['c5ts_as_of']);
            if (!is_numeric($AsOf) or $AsOf > time() or $AsOf < time()-31700000 or $_SESSION['Selected']['k0audit'] < 100000) {
                $FormActionConfirm = FALSE; // This suppresses confirm button in ConfirmZfpf::post_select_required_compare_confirm_1e
                if (!is_numeric($AsOf))
                    $htmlFormArray['c5ts_as_of'][0] .= '.<br /><b>Invalid "as of" date and time -- app couldn\'t determine a date and time from what you entered</b>';
                elseif ($AsOf > time())
                    $htmlFormArray['c5ts_as_of'][0] .= '.<br /><b>Invalid "as of" date and time -- cannot be in the future</b>';
                elseif ($AsOf < time()-31700000)
                    $htmlFormArray['c5ts_as_of'][0] .= '.<br /><b>Invalid "as of" date and time -- cannot be more than a year ago</b>';
                elseif ($_SESSION['Selected']['k0audit'] < 100000)
                    $htmlFormArray['c5ts_as_of'][0] .= '.<br /><b>Invalid entry -- template reports cannot have an "as of" date and time</b>';
           }
        }
        // SPECIAL CASE: end
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e($FormActionConfirm, 'audit_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        // SPECIAL CASE: start -- redundant with $FormActionConfirm = FALSE above.
        if (!$FormActionConfirm and isset($_SESSION['Scratch']['ModifiedValues'])) {
            $NewStuff = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['ModifiedValues']);
            if (isset($NewStuff['c5ts_as_of']))
                $NewStuff['c5ts_as_of'] = '[Nothing has been recorded in this field.]'; // Would force later reentry of invalid "as of" date and time.
            $_SESSION['Scratch']['ModifiedValues'] = $Zfpf->encode_encrypt_1c($NewStuff);
        }
        // SPECIAL CASE: end
        $Zfpf->save_and_exit_1c();
    }
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') {
            $Zfpf->insert_sql_1s($DBMSresource, 't0audit', $ChangedRow);
            // Insert any template t0audit_obstopic, t0audit_fragment, and t0audit_fragment_obsmethod records associated with a new report created from a template.
            $SourceTemplateKey = $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']); // See schema.
            if ($SourceTemplateKey != '[Nothing has been recorded in this field.]' and is_numeric($SourceTemplateKey)) { // cannot use $SourceTemplateKey < 100000 because this may hold prior report k0audit.
                $i = 0;
                $Conditions[0] = array('k0audit', '=', $SourceTemplateKey);
                list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_obstopic', $Conditions);
                if ($RR) foreach ($SR as $V) {
                    $V['k0audit_obstopic'] = time().$i++.mt_rand(100, 999);
                    $V['k0audit'] = $_SESSION['Selected']['k0audit'];
                    $Zfpf->insert_sql_1s($DBMSresource, 't0audit_obstopic', $V);
                }
                list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment', $Conditions);
                if ($RR) foreach ($SR as $VAuF) {
                    $Conditions[0] = array('k0audit_fragment', '=', $VAuF['k0audit_fragment']); // The template k0audit_fragment, set before altering $VAuF['k0audit_fragment'] below
                    $VAuF['k0audit_fragment'] = time().$i++.mt_rand(100, 999);
                    $VAuF['k0audit'] = $_SESSION['Selected']['k0audit'];
                    $Zfpf->insert_sql_1s($DBMSresource, 't0audit_fragment', $VAuF);
                    list($SRAuFOm, $RRAuFOm) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $Conditions);
                    if ($RRAuFOm) foreach ($SRAuFOm as $VAuFOm) {
                        $VAuFOm['k0audit_fragment_obsmethod'] = time().$i++.mt_rand(100, 999);
                        $VAuFOm['k0audit_fragment'] = $VAuF['k0audit_fragment']; // The k0audit_fragment linked to the newly inserted k0audit
                        $Zfpf->insert_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $VAuFOm);
                    }
                }
            // Observations and proposed actions are not brought over from a prior issued report used as a template. The actions would already be in the action register.
            }
        }
        else {
            $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0audit', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'
        <p>
        The draft report you were editing has been updated with your changes. It will remain a draft until it is issued by its report leader.</p>
        <form action="audit_io03.php" method="post"><p>
            <input type="submit" name="audit_o1" value="Back to record" /></p>
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

