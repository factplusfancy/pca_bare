<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the action register (ar) input and output HTML forms, except:
//  - SPECIAL CASE: i0m file that just requires the i1m file, to allow calling directly from left-hand contents.
//  - i1m file for listing existing records (and giving the option to start a new record)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check.
// Cannot screen for process being set because Action Register can apply contractor-, owner-, facility-, or process-wide.
// User may arrive here with any of below security tolkens
if (
    !isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or 
    (
        $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'ar_i1m.php' and
        $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'audit_i1m.php' and 
        $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'incident_i1m.php' and
        $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'pha_i1m.php'
    ) or
    !isset($_SESSION['t0user_practice'])
)
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get useful information.
$User = $Zfpf->current_user_info_1c();
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$Nothing = '[Nothing has been recorded in this field.]';
$EncryptedNothing = $Zfpf->encrypt_1c($Nothing);
$EditAuth = FALSE;
if ($User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
    $EditAuth = TRUE;

require INCLUDES_DIRECTORY_PATH_ZFPF.'/arZfpf.php';
$arZfpf = new arZfpf;
$htmlFormArray = $arZfpf->ar_html_form_array();

//Left hand Table of contents
if (isset($_POST['ar_i0n']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c5name' => 'Name',
        'c5status' => 'Status',
        'c5priority' => 'Priority',
        'c5affected_entity' => 'Affected entity',
        'c6deficiency' => 'Deficiency',
        'c6details' => 'Additional information',
    );
if (isset($_POST['ar_o1']) or isset($_POST['ar_o1_from']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c5name' => 'Name',
        'c5status' => 'Status',
        'c5priority' => 'Priority',
        'c5affected_entity' => 'Affected entity',
        'c6deficiency' => 'Deficiency',
        'c6details' => 'Additional information',
        'c5ts_target' => 'Target resolution timing',
        'k0user_of_leader' => 'Resolution assigned to',
        'c6notes' => 'Resolution method',
        'c6bfn_supporting' => 'Supporting documents'
    );

// The if clauses below determine which HTML button the user pressed.

// Download currently listed actions as a comma delimited file, aka comma-separated values (csv).
if (isset($_GET['ar_download_csv'])) {
    // Generate the CSV string in $FileAsString
    $Fields = array('c5name', 'c5status', 'c5priority', 'c5affected_entity', 'c6deficiency', 'c6details', 'c6notes', 'c6bfn_supporting', 'c6nymd_leader', 'c6nymd_ae_leader');
    $FileAsString = '"Name"|"Status"|"Priority"|"Affected Entity"|"Deficiencies"|"Details or Resolution Options"|"Resolution Methods and Any Deficiency Modifications"|"Supporting Documents Uploaded"|"Assigned-To Leader Approval"|"Affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader approval"
'; // First line of CSV file, holding column headings of future spreadsheet. Enclose everything but delimiter in quotes so delimiter can be in text.
    if (isset($_SESSION['Scratch']['ActionRows'])) foreach ($_SESSION['Scratch']['ActionRows'] as $VA) {
        foreach ($Fields as $VB) {
            if (substr($VB, 0, 6) == 'c6bfn_') {
                if ($Zfpf->decrypt_1c($VA[$VB]) == $Nothing)
                    $FileAsString .= '"No"|';
                else
                    $FileAsString .= '"Yes"|';
            }
            else
                $FileAsString .= '"'.$Zfpf->decrypt_1c($VA[$VB]).'"|';
        }
        $FileAsString = substr($FileAsString, 0, -1).'
'; // replace final delineator, like |, with newline
    }
    $LocalName = 'psm_cap_actions_downloaded_'.time().'.csv';
    $Zfpf->download_one_file($FileAsString, $LocalName); // FilesZfpf::download_one_file echos and exits.
}

// i0m_... codes
if (isset($_GET['ar_i1m_draft_unassociated']) or isset($_GET['ar_i1m_all']) or isset($_GET['ar_i1m_age']) or isset($_POST['ar_i1m_age']) or isset($_GET['ar_i1m_rfa']) or isset($_GET['ar_i1m_priority']) or isset($_GET['ar_i1m_audit']) or isset($_GET['ar_i1m_incident']) or isset($_GET['ar_i1m_pha']) or isset($_GET['ar_i1m_process']) or isset($_GET['ar_i1m_facility']) or isset($_GET['ar_i1m_owner']) or isset($_GET['ar_i1m_contractor'])) { // ar_i1m_age is special case, called from buttons ($_POST) and links ($_GET)
    if (isset($_SESSION['SelectResults']['t0action']))
        unset($_SESSION['SelectResults']['t0action']);
    if (isset($_SESSION['Selected']) and !isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']))
        unset($_SESSION['Selected']);
}
if (isset($_GET['ar_i1m_draft_unassociated'])) {
    $SpecialConditions = array('k0user_of_ae_leader', '=', -1); // See app schema.
    $Conditions = $arZfpf->conditions_state_picked($SpecialConditions);
    list($_SESSION['SelectResults']['t0action'], $RowsReturned) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
    echo $arZfpf->actions_list($Zfpf, $RowsReturned, 'Draft proposed actions that are unassociated with a recommending report');
    $Zfpf->save_and_exit_1c();
}
elseif (isset($_GET['ar_i1m_all'])) {
    if ($_SESSION['Scratch']['PlainText']['SecurityToken'] != 'ar_i1m.php') // Others cases (audit, incident, PHA) cannot view closed actions.
        $Zfpf->send_to_contents_1c(); // Don't eject
    $SpecialConditions = array('k0user_of_ae_leader', '>=', 0); // See app schema.
    $Conditions = $arZfpf->conditions_state_picked($SpecialConditions);
    list($_SESSION['SelectResults']['t0action'], $RowsReturned) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
    echo $arZfpf->actions_list($Zfpf, $RowsReturned, 'All open and closed actions');
    $Zfpf->save_and_exit_1c();
}
elseif (isset($_GET['ar_i1m_rfa'])) { // rfa means recommended for approval
    $SpecialConditions = array('k0user_of_ae_leader', '=', 1); // Means c5status holds, encrypted, 'Needs resolution...' See app schema. Same as .../includes/ar_i1m.php
    $Conditions = $arZfpf->conditions_state_picked($SpecialConditions);
    list($_SESSION['SelectResults']['t0action'], $RowsReturned) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
    echo $arZfpf->actions_list($Zfpf, $RowsReturned, 'Marked resolved actions');
    $Zfpf->save_and_exit_1c();
}
elseif (isset($_GET['ar_i1m_age']) or isset($_POST['ar_i1m_age'])) {
    $SpecialConditions = array('k0user_of_ae_leader', '=', 0); // Means c5status holds, encrypted, 'Needs resolution...' See app schema. Same as .../includes/ar_i1m.php
    $Conditions = $arZfpf->conditions_state_picked($SpecialConditions);
    list($_SESSION['SelectResults']['t0action'], $RowsReturned) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
    echo $arZfpf->actions_list($Zfpf, $RowsReturned, 'Unresolved actions');
    $Zfpf->save_and_exit_1c();
}
elseif (isset($_GET['ar_i1m_priority'])) {
    $SpecialConditions = array('k0user_of_ae_leader', '=', 0);
    $Conditions = $arZfpf->conditions_state_picked($SpecialConditions);
    list($_SESSION['SelectResults']['t0action'], $RowsReturned) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
    echo $arZfpf->actions_list($Zfpf, $RowsReturned, 'Unresolved actions (sorted by priority)', 'c5priority', SORT_DESC);
    $Zfpf->save_and_exit_1c();
}
elseif (isset($_GET['ar_i1m_audit']) or isset($_GET['ar_i1m_incident']) or isset($_GET['ar_i1m_pha']) or isset($_GET['ar_i1m_process'])) {
    if (!isset($_SESSION['StatePicked']['t0process']['k0process']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $NumberFound = 0;
    $KeysArray = FALSE;
    // This function is only used three times below, and nowhere else.
    function get_actions_ar_io03Zfpf($Zfpf, $DBMSresource, $NumberFound, $KeysArray, $JuntionTableSR) {
        foreach ($JuntionTableSR as $V)
            if (!$KeysArray or !in_array($V['k0action'], $KeysArray)) {
                $Conditions[0] = array('k0action', '=', $V['k0action'], 'AND');
                $Conditions[1] = array('k0user_of_ae_leader', '=', 0); // Only selects open actions. See above and app schema.
                list($SelectResults['t0action'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $Conditions);
                if ($RowsReturned > 1)
                    error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. t0action rows returned: '.$RowsReturned);
                elseif ($RowsReturned == 1) {
                    $KeysArray[] = $SelectResults['t0action'][0]['k0action'];
                    $_SESSION['SelectResults']['t0action'][] = $SelectResults['t0action'][0];
                    ++$NumberFound;
                }
            }
        return array($NumberFound, $KeysArray);
    }
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Conditions[0] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process']);
    if (isset($_GET['ar_i1m_audit'])) {
        list($SelectResults['t0audit'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0audit', $Conditions);
        if ($RowsReturned > 0)
            foreach ($SelectResults['t0audit'] as $VA) {
                $Conditions[0] = array('k0audit', '=', $VA['k0audit']);
                list($SelectResults['t0obsresult'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
                if ($RowsReturned > 0)
                    foreach ($SelectResults['t0obsresult'] as $VB) {
                        $Conditions[0] = array('k0obsresult', '=', $VB['k0obsresult']);
                        list($SelectResults['t0obsresult_action'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult_action', $Conditions);
                        if ($RowsReturned > 0)
                            list($NumberFound, $KeysArray) = get_actions_ar_io03Zfpf($Zfpf, $DBMSresource, $NumberFound, $KeysArray, $SelectResults['t0obsresult_action']);
                    }
            }
        $Description = 'Findings from audits, hazard reviews...';
    }
    elseif (isset($_GET['ar_i1m_incident'])) {
        list($SelectResults['t0incident'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0incident', $Conditions);
        if ($RowsReturned > 0)
            foreach ($SelectResults['t0incident'] as $VA) {
                $Conditions[0] = array('k0incident', '=', $VA['k0incident']);
                list($SelectResults['t0incident_action'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0incident_action', $Conditions);
                if ($RowsReturned > 0)
                    list($NumberFound, $KeysArray) = get_actions_ar_io03Zfpf($Zfpf, $DBMSresource, $NumberFound, $KeysArray, $SelectResults['t0incident_action']);
            }
        $Description = 'Incident investigation';
    }
    elseif (isset($_GET['ar_i1m_pha'])) {
        list($SelectResults['t0pha'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0pha', $Conditions);
        if ($RowsReturned > 0)
            foreach ($SelectResults['t0pha'] as $VA) {
                $Conditions[0] = array('k0pha', '=', $VA['k0pha']);
                list($SelectResults['t0subprocess'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0subprocess', $Conditions);
                if ($RowsReturned > 0)
                    foreach ($SelectResults['t0subprocess'] as $VB) {
                        $Conditions[0] = array('k0subprocess', '=', $VB['k0subprocess']);
                        list($SelectResults['t0scenario'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $Conditions);
                        if ($RowsReturned > 0)
                            foreach ($SelectResults['t0scenario'] as $VC) {
                                $Conditions[0] = array('k0scenario', '=', $VC['k0scenario']);
                                list($SelectResults['t0scenario_action'], $RowsReturned) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_action', $Conditions);
                                if ($RowsReturned > 0)
                                    list($NumberFound, $KeysArray) = get_actions_ar_io03Zfpf($Zfpf, $DBMSresource, $NumberFound, $KeysArray, $SelectResults['t0scenario_action']);
                            }
                    }
            }
        $Description = 'PHA or HIRA';
    }
    elseif (isset($_GET['ar_i1m_process'])) {
        $Conditions[0] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0process']['k0process'], 'AND');
        $Conditions[1] = array('k0user_of_ae_leader', '=', 0); // Only selects open actions. See above and app schema.
        list($_SESSION['SelectResults']['t0action'], $NumberFound) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $Conditions);
        $Description = 'Process';
    }
    $Zfpf->close_connection_1s($DBMSresource);
    echo $arZfpf->actions_list($Zfpf, $NumberFound, $Description.' unresolved actions');
    $Zfpf->save_and_exit_1c();
}
elseif (isset($_GET['ar_i1m_facility'])) {
    if (!isset($_SESSION['StatePicked']['t0facility']['k0facility']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Conditions[0] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0facility']['k0facility'], 'AND');
    $Conditions[1] = array('k0user_of_ae_leader', '=', 0); // Only selects open actions. See above and app schema.
    list($_SESSION['SelectResults']['t0action'], $RowsReturned) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
    echo $arZfpf->actions_list($Zfpf, $RowsReturned, 'Facility unresolved actions');
    $Zfpf->save_and_exit_1c();
}
elseif (isset($_GET['ar_i1m_owner'])) {
    if (!isset($_SESSION['StatePicked']['t0owner']['k0owner']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Conditions[0] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0owner']['k0owner'], 'AND');
    $Conditions[1] = array('k0user_of_ae_leader', '=', 0); // Only selects open actions. See above and app schema.
    list($_SESSION['SelectResults']['t0action'], $RowsReturned) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
    echo $arZfpf->actions_list($Zfpf, $RowsReturned, 'Owner unresolved actions');
    $Zfpf->save_and_exit_1c();
}
elseif (isset($_GET['ar_i1m_contractor'])) {
    if (!isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Conditions[0] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], 'AND');
    $Conditions[1] = array('k0user_of_ae_leader', '=', 0); // Only selects open actions. See above and app schema.
    list($_SESSION['SelectResults']['t0action'], $RowsReturned) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
    echo $arZfpf->actions_list($Zfpf, $RowsReturned, 'Contractor unresolved actions');
    $Zfpf->save_and_exit_1c();
}

// i0n code
if (isset($_POST['ar_i0n'])) {
    // Additional security check. Handle inadequate global privileges. Otherwise, any user associated with this practice can start a new record.
    if ($User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    // Initialize $_SESSION['Selected']
    $_SESSION['Selected'] = array(
        'k0action' => time().mt_rand(1000000, 9999999),
        'c5name' => $EncryptedNothing,
        'c5status' => $Zfpf->encrypt_1c('Draft proposed action'), 
        'c5priority' => $EncryptedNothing,
        'c5affected_entity' => $EncryptedNothing,
        'k0affected_entity' => 0,
        'c6deficiency' => $EncryptedNothing,
        'c6details' => $EncryptedNothing,
        'c5ts_target' => $EncryptedNothing,
        'k0user_of_leader' => 0,
        'c5ts_leader' => $EncryptedNothing,
        'c6nymd_leader' => $EncryptedNothing,
        'c6notes' => $EncryptedNothing,
        'c6bfn_supporting' => $EncryptedNothing,
        'k0user_of_ae_leader' => -1, // -1 means: Draft proposed action unassociated with a recommending report. See app schema.
        'c5ts_ae_leader' => $EncryptedNothing,
        'c6nymd_ae_leader' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['ar_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0action']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0action', $_SESSION['Selected']['k0action']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one action record', 'ar_io03.php', 'ar_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0action']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'ar_io03.php', 'ar_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'ar_io03.php', 'ar_o1');
    $_POST['ar_o1'] = 1;
}

// o1 code
if (isset($_POST['ar_o1']) or isset($_GET['ar_o1'])) {
    // Additional security check.
    if (!isset($_SESSION['Selected']['k0action']) and !isset($_SESSION['SelectResults']['t0action']) and !isset($_SESSION['SR']['t0action']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (isset($_SESSION['Scratch']['PlainText']['open_unassociated_action_k0user_of_ae_leader']))
        unset($_SESSION['Scratch']['PlainText']['open_unassociated_action_k0user_of_ae_leader']);
    if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar'])) { // Cases for selecting open actions here.
        // Save calling file's $_SESSION['Selected'] temporarily in $_SESSION['Scratch']
        if ($_SESSION['Scratch']['PlainText']['action_ifrom_ar'] == 'scenario') { // The calling file's $_SESSION['Selected'] holds a t0pha row.
            $SessionSelectedTableRoot = 'pha';
            $GoBackTableRoot = 'scenario';
            $RecordDescription = 'scenario';
        }
        elseif ($_SESSION['Scratch']['PlainText']['action_ifrom_ar'] == 'obsresult') { // The calling file's $_SESSION['Selected'] holds a t0audit row.
            $SessionSelectedTableRoot = 'audit';
            $GoBackTableRoot = 'obsresult';
            $RecordDescription = 'observation result';
        }
        else { // Single layer situation, like "incident", where record description is same as session-selected table root and go-back filename root.
            $SessionSelectedTableRoot = $_SESSION['Scratch']['PlainText']['action_ifrom_ar'];
            $GoBackTableRoot = $_SESSION['Scratch']['PlainText']['action_ifrom_ar'];
            $RecordDescription = $_SESSION['Scratch']['PlainText']['action_ifrom_ar'];
        }
        $_SESSION['Scratch']['t0'.$SessionSelectedTableRoot] = $_SESSION['Selected'];
    } // Above cases should go into "if (isset($_POST['ar_o1']))" below.
    if (!isset($_SESSION['Selected']['k0action'])) {
        if (isset($_POST['ar_o1'])) {
            $CheckedPost = $Zfpf->post_length_blank_1c('selected');
            if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0action'][$CheckedPost]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['Selected'] = $_SESSION['SelectResults']['t0action'][$CheckedPost];
            unset($_SESSION['SelectResults']);
        }
        elseif (isset($_GET['ar_o1'])) {
            if (!is_numeric($_GET['ar_o1']) or strlen($_GET['ar_o1']) > 9 or !isset($_SESSION['Selected']['k0audit'])) // $_GET['ar_o1'] ibky set by files with $_SESSION['Selected']['k0audit'] also set.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $iA = $_GET['ar_o1'];
            if (!isset($_SESSION['SR']['t0action'][$iA]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $SessionSelectedTableRoot = 'audit'; // $_GET['ar_o1'] only set in cases where $_SESSION['Selected'] holds a t0audit row.
            if (isset($_SESSION['Scratch']['t0audit_fragment'])) // Case 1: user coming from audit_fragment_io03.php,
                $GoBackTableRoot = 'audit_fragment';
            else {                                               // Case 2: user coming from audit_io03.php:view_audit_actions
                $GoBackTableRoot = 'audit';
                $GoBackName = 'view_audit_actions';
            }
            $_SESSION['Scratch']['t0'.$SessionSelectedTableRoot] = $_SESSION['Selected'];
            $_SESSION['Selected'] = $_SESSION['SR']['t0action'][$iA];
            unset($_SESSION['SR']);
        }
    }
    $Zfpf->clear_edit_lock_1c();
    if (isset($SessionSelectedTableRoot) and $SessionSelectedTableRoot == 'audit')
        $htmlFormArray = $arZfpf->ar_html_form_array('obsresult');
    elseif ($_SESSION['Selected']['k0user_of_ae_leader'] == -1) // See app schema. Most k0user_of_ae_leader == -2 cases are handled by t0action.php
        $htmlFormArray = $arZfpf->ar_html_form_array('Draft proposed action unassociated');
    elseif ($_SESSION['Selected']['k0user_of_ae_leader'] == 0)
        $htmlFormArray = $arZfpf->ar_html_form_array('Needs resolution');
    elseif ($_SESSION['Selected']['k0user_of_ae_leader'] == 1)
        $htmlFormArray = $arZfpf->ar_html_form_array('Approved by resolution assigned to person');
    elseif ($_SESSION['Selected']['k0user_of_ae_leader'] > 1)
        $htmlFormArray = $arZfpf->ar_html_form_array('Resolution approved by owner');
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Selected'], TRUE);
    // k0affected_entity is handled by c5affected_entity
    // Handle k0user field(s)
    if (!$_SESSION['Selected']['k0user_of_leader']) // 0 or the k0user. 0 here means a Draft proposed action unassociated.
        $Display['k0user_of_leader'] = $Nothing;
    else {
        $ResolutionAssignedTo = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
        $Display['k0user_of_leader'] = $ResolutionAssignedTo['NameTitleEmployerWorkEmail'];
    }
    if ($_SESSION['Selected']['k0user_of_ae_leader'] == 1)
        $Display['k0user_of_ae_leader'] = $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']); // Bootstrap this text here.
    elseif ($_SESSION['Selected']['k0user_of_ae_leader'] > 1) {
        $Display['k0user_of_ae_leader'] = $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']).' (who recommended resolution approval) and<br />
        '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_ae_leader']).' (the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader, who approved resolution on behalf of the owner)';
    }
    $Message = '<h1>
    Action Register</h1>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'ar_io03.php', $_SESSION['Selected'], $Display);
    if (!isset($SessionSelectedTableRoot)) { // Called from action register i1m case.
        $Message .= '
        <form action="ar_io03.php" method="post">';
        $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
        if ($who_is_editing != '[Nobody is editing.]')
            $Message .= '
            <p><b>'.$who_is_editing.' is editing the record you selected.</b><br />
            If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
        elseif ($EditAuth) {
            $MaxScope = $Zfpf->decrypt_1c($_SESSION['Selected']['c5affected_entity']);
            $UserIsAELeader = FALSE;
            if (
                ($MaxScope == 'Process-wide' and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0process']['k0user_of_leader']) or
                ($MaxScope == 'Facility-wide' and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0facility']['k0user_of_leader']) or
                ($MaxScope == 'Owner-wide' and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0owner']['k0user_of_leader']) or
                ($MaxScope == 'Contractor-wide' and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0contractor']['k0user_of_leader'])
               )
                $UserIsAELeader = TRUE;
            if ($_SESSION['Selected']['k0user_of_ae_leader'] == -1) {
                $Message .= '<p>
                    <input type="submit" name="ar_o1_from" value="Update this action" /></p>';
                // Open Action button
                if ($UserIsAELeader)
                    $Message .= '<p>
                    Actions cannot be "un-opened". They may be "closed" by approving their resolution. Resolution responsibility and timing are assigned when an action is opened.<br />
                    <input type="submit" name="open_unassociated_action_1" value="Open action" /></p>';
            }
            elseif ($_SESSION['Selected']['k0user_of_ae_leader'] == 0) {
                if ($_SESSION['Selected']['k0user_of_leader']) {
                    $AssignOrChange = 'Change responsibility or timing';
                    // Update resolution fields button
                    if ($_SESSION['Selected']['k0user_of_leader'] == $_SESSION['t0user']['k0user'])
                        $Message .= '<p>
                        <input type="submit" name="ar_o1_from" value="Update resolution fields" /></p>';
                }
                else {
                    $AssignOrChange = 'Assign responsibility and timing';
                    $Message .= '<p>
                    Before resolution fields can be edited, responsibility for resolution must be assigned by the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for this "'.$MaxScope.'" action.</p>';
                }
                // Resolution Responsibility and Timing button
                if ($UserIsAELeader) {
                    $Message .= '<p>
                    <input type="submit" name="assign_1_leader" value="'.$AssignOrChange.'" /></p>';
                    if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'])
                        $Message .= '<p>
                        Not marked resolved, so cannot close action. Only the "resolution assigned to" person (listed above) can mark the action as resolved.</p>';
                }
                // Resolution assigned to 'Recommend Resolution Approval' button
                if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader']) {
                    if ($Zfpf->decrypt_1c($_SESSION['Selected']['c6notes']) == $Nothing)
                        $Message .= '<p>
                        No resolution method, so cannot close action. Document the resolution method above.</p>';
                    else
                        $Message .= '<p>
                        <input type="submit" name="approve_1_leader" value="Mark resolved" /></p>';
                }
            }
            elseif ($_SESSION['Selected']['k0user_of_ae_leader'] == 1) {
                // Resolution assigned to 'Cancel recommendation to approve resolution' button
                if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
                    $Message .= '<p>
                    <input type="submit" name="approve_c1_leader" value="Mark unresolved" /></p>';
                elseif($UserIsAELeader)
                    $Message .= '<p>
                    Change resolution responsibility to yourself if you, the affected-entity leader, want this marked unresolved.<br />
                    <input type="submit" name="assign_1_leader" value="Change responsibility" /></p>';
                // Approve Resolution and Close Action button
                if ($UserIsAELeader) // User might be resolution leader and AE leader.
                    $Message .= '<p>
                    <input type="submit" name="approve_1_ae_leader" value="Approve resolution and close" /></p>';
            }
            // Cancel Approval of Resolution and Re-open Action button
            elseif ($_SESSION['Selected']['k0user_of_ae_leader'] > 1 and $UserIsAELeader)
                $Message .= '<p>
                    <input type="submit" name="approve_c1_ae_leader" value="Re-open action" /></p>';
        }
        else {
            if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
                $Message .= '<p><b>
                Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
            if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
                $Message .= '<p><b>
                Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
        }
        $Message .= '<p><p>
            <input type="submit" name="ar_history_o1" value="History of this record" /></p>
            <input type="submit" name="ar_i1m_age" value="Back to records list" /></p>
        </form>';
    }
    elseif (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']))  { // Adding action to the recommending report, like incident, scenario, obsresult.
        $Message .= '
        <form action="action_io03.php" method="post"><p>
            <input type="submit" name="action_o1" value="Associate with selected '.$RecordDescription.'" /></p>
        </form>';
        $_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action'] = $_SESSION['Selected']['k0action']; // Used in action_io03.php
        $_SESSION['Scratch']['t0action'] = $_SESSION['Selected']; // Used in ccsaZfpf::ccsa_io0_2, called by action_io03.php
    }
    if (isset($SessionSelectedTableRoot)) { // All cases besides action register i1m cases.
        if (!isset($GoBackName))
            $GoBackName = $GoBackTableRoot.'_o1';
        $Message .= '
        <form action="'.$GoBackTableRoot.'_io03.php" method="post"><p>
            <input type="submit" name="'.$GoBackName.'" value="Go back" /></p>
        </form>';
        // Restore calling file's $_SESSION['Selected'] and unset temporary $_SESSION['Scratch']
        $_SESSION['Selected'] = $_SESSION['Scratch']['t0'.$SessionSelectedTableRoot];
        unset($_SESSION['Scratch']['t0'.$SessionSelectedTableRoot]);
    }
    echo $Zfpf->xhtml_contents_header_1c('Action').$Message.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, i3, open_unassociated_action_1, approval, and assign code
if (isset($_SESSION['Selected']['k0action'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0action', '=', $_SESSION['Selected']['k0action']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    // Additional security check.
    if ($_SESSION['Scratch']['PlainText']['SecurityToken'] != 'ar_i1m.php' or $_SESSION['Selected']['k0user_of_ae_leader'] < -1 or ($who_is_editing != '[A new database row is being created.]' and !$EditAuth) or ($who_is_editing == '[A new database row is being created.]' and $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF))
        $Zfpf->send_to_contents_1c(); // Don't eject
    // Get useful information
    if ($_SESSION['Selected']['k0user_of_ae_leader'] == -1) // See app schema. Cannot be -2 in this context because that's handled by the recommending report (PHA, audit...)
        $htmlFormArray = $arZfpf->ar_html_form_array('Draft proposed action unassociated');
    if ($_SESSION['Selected']['k0user_of_ae_leader'] == 0 or isset($_SESSION['Scratch']['PlainText']['open_unassociated_action_k0user_of_ae_leader']))
        $htmlFormArray = $arZfpf->ar_html_form_array('Needs resolution');
    elseif ($_SESSION['Selected']['k0user_of_ae_leader'] == 1)
        $htmlFormArray = $arZfpf->ar_html_form_array('Approved by resolution assigned to person');
    elseif ($_SESSION['Selected']['k0user_of_ae_leader'] > 1)
        $htmlFormArray = $arZfpf->ar_html_form_array('Resolution approved by owner');
    $ActionName = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
    if ($_SESSION['Selected']['k0user_of_leader'])
        $ResolutionAssignedTo = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);    
    $MaxScope = $Zfpf->decrypt_1c($_SESSION['Selected']['c5affected_entity']);
    $UserIsAELeader = FALSE;
    if (
        ($MaxScope == 'Process-wide' and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0process']['k0user_of_leader']) or
        ($MaxScope == 'Facility-wide' and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0facility']['k0user_of_leader']) or
        ($MaxScope == 'Owner-wide' and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0owner']['k0user_of_leader']) or
        ($MaxScope == 'Contractor-wide' and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0contractor']['k0user_of_leader'])
       )
        $UserIsAELeader = TRUE;
    $CurrentDate = date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j', time());

    if (isset($_POST['open_unassociated_action_1'])) {
        $_SESSION['Scratch']['PlainText']['open_unassociated_action_k0user_of_ae_leader'] = 'open this action';
        echo $Zfpf->xhtml_contents_header_1c('Action').'<h2>
        Open unassociated action</h2><p>
        Click the "Assign responsibility and timing" button below to assign resolution responsibility to a PSM-CAP App user and to set a target date and time for resolving the action. Completing those steps will "open" the action, changing its status from "draft proposed action" to "needs resolution".</p><p>
        Once opened, actions cannot be "un-opened". They may be "closed" by approving their resolution.</p>
        <form action="ar_io03.php" method="post"><p>
            <input type="submit" name="assign_1_leader" value="Assign responsibility and timing" /></p><p>
            <input type="submit" name="ar_o1" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Approvals and task assignments
    foreach ($htmlFormArray as $KA => $VA)
        if (substr($KA, 0, 9) == 'k0user_of') {
            $TaskSubKeyA = substr($KA, 9);
            if (isset($_POST['approve_1'.$TaskSubKeyA]) or isset($_POST['approve_c1'.$TaskSubKeyA])) {
                if (!$_SESSION['Selected']['k0user_of_leader'] or ($TaskSubKeyA == '_leader' and ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] or (isset($_POST['approve_1_leader']) and $_SESSION['Selected']['k0user_of_ae_leader'] != 0) or (isset($_POST['approve_c1_leader']) and $_SESSION['Selected']['k0user_of_ae_leader'] != 1))) or ($TaskSubKeyA == '_ae_leader' and (!$UserIsAELeader or (isset($_POST['approve_1_ae_leader']) and $_SESSION['Selected']['k0user_of_ae_leader'] != 1) or (isset($_POST['approve_c1_ae_leader']) and $_SESSION['Selected']['k0user_of_ae_leader'] <= 1))) or $Zfpf->decrypt_1c($_SESSION['Selected']['c6notes']) == $Nothing)
                    $Zfpf->send_to_contents_1c(); // Don't eject
                $Zfpf->edit_lock_1c('action');
                $Display = $Zfpf->select_to_display_1e($htmlFormArray);
                // Handle k0user field(s)
                $ResolutionAssignedTo = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
                $Display['k0user_of_leader'] = $ResolutionAssignedTo['NameTitleEmployerWorkEmail'];
                if ($_SESSION['Selected']['k0user_of_ae_leader'] == 1)
                    $Display['k0user_of_ae_leader'] = $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']); // Bootstrap this text here.
                elseif ($_SESSION['Selected']['k0user_of_ae_leader'] > 1)
                    $Display['k0user_of_ae_leader'] = $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']).' (who recommended resolution approval) and<br />
                    '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_ae_leader']).' (the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader, who approved resolution on behalf of the owner)';
                if (isset($_POST['approve_1_leader']))
                    $NVT = array(
                        0 => 'approve_2'.$TaskSubKeyA,
                        1 => 'Recommend resolution approval',
                        2 => 'confirming that:<br />
                             - I completed everything needed to safely recommend this approval and<br />
                             - I am qualified to do this.</b></p><p>
                            <b>Approval Recommended By:</b><br />'
                    );
                elseif (isset($_POST['approve_1_ae_leader']))
                    $NVT = array(
                        0 => 'approve_2'.$TaskSubKeyA,
                        1 => 'Approve resolution and close action',
                        2 => 'confirming that:<br />
                             - I completed everything needed to safely make this approval and<br />
                             - I am qualified to do this.</b></p><p>
                            <b>Approved By:</b><br />'
                    ); 
                elseif (isset($_POST['approve_c1_leader']))
                    $NVT = array(
                        0 => 'approve_c2'.$TaskSubKeyA,
                        1 => 'Mark unresolved',
                        2 => 'canceling this recommendation.</b></p><p>
                             <b>Recommendation Canceled By:</b><br />'
                    );
                elseif (isset($_POST['approve_c1_ae_leader']))
                    $NVT = array(
                        0 => 'approve_c2'.$TaskSubKeyA,
                        1 => 'Re-open action',
                        2 => 're-opening this action and canceling approval of its resolution.</b></p><p>
                             <b>Approval Canceled By:</b><br />'
                    );
                if (isset($_POST['approve_1'.$TaskSubKeyA]))
                    $_SESSION['Scratch']['c6nymd'] = $Zfpf->encrypt_1c($User['NameTitleEmployerWorkEmail'].' on '.$CurrentDate);
                if (isset($_POST['approve_c1'.$TaskSubKeyA]))
                    $_SESSION['Scratch']['c6nymd'] = $EncryptedNothing;
                $ApprovalText = '<h1>
                '.$NVT[1].'</h1>
                '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'<p>
                <b>By clicking "'.$NVT[1].'" below, I am '.$NVT[2].'
                Name: <b>'.$User['Name'].'</b><br />
                Job Title: <b>'.$User['Title'].'</b><br />
                Employer<b>: '.$User['Employer'].'</b><br />
                Email Address<b>: '.$User['WorkEmail'].'</b><br />
                Date: <b>'.$CurrentDate.'</b></p>';
                echo $Zfpf->xhtml_contents_header_1c('Confirm').$ApprovalText.'
                <form action="ar_io03.php" method="post"><p>
                    <input type="submit" name="'.$NVT[0].'" value="'.$NVT[1].'" /></p><p>
                    <input type="submit" name="ar_o1" value="Take no action -- go back" /></p>
                </form>
                '.$Zfpf->xhtml_footer_1c();
                $_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
                $Zfpf->save_and_exit_1c();
            }
            if (isset($_POST['approve_2'.$TaskSubKeyA]) or isset($_POST['approve_c2'.$TaskSubKeyA])) {
                if (!$_SESSION['Selected']['k0user_of_leader'] or ($TaskSubKeyA == '_leader' and ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] or (isset($_POST['approve_1_leader']) and $_SESSION['Selected']['k0user_of_ae_leader'] != 0) or (isset($_POST['approve_c1_leader']) and $_SESSION['Selected']['k0user_of_ae_leader'] != 1))) or ($TaskSubKeyA == '_ae_leader' and (!$UserIsAELeader or (isset($_POST['approve_1_ae_leader']) and $_SESSION['Selected']['k0user_of_ae_leader'] != 1) or (isset($_POST['approve_c1_ae_leader']) and $_SESSION['Selected']['k0user_of_ae_leader'] <= 1))) or $Zfpf->decrypt_1c($_SESSION['Selected']['c6notes']) == $Nothing)
                    $Zfpf->send_to_contents_1c(); // Don't eject
                $Conditions[0] = array('k0action', '=', $_SESSION['Selected']['k0action']);
                if (isset($_POST['approve_2'.$TaskSubKeyA])) {
                    $Changes['c5ts'.$TaskSubKeyA] = $Zfpf->encrypt_1c(time());
                    if ($KA == 'k0user_of_leader') {
                        $Changes['c5status'] = $Zfpf->encrypt_1c('Marked resolved');
                        $Changes['k0user_of_ae_leader'] = 1;
                    }
                    elseif ($KA == 'k0user_of_ae_leader') {
                        $Changes['c5status'] = $Zfpf->encrypt_1c('Closed');
                        $Changes['k0user_of_ae_leader'] = $_SESSION['t0user']['k0user'];
                    }
                }
                elseif (isset($_POST['approve_c2'.$TaskSubKeyA])) {
                    $Changes['c5ts'.$TaskSubKeyA] = $EncryptedNothing;
                    if ($KA == 'k0user_of_leader') {
                        $Changes['c5status'] = $Zfpf->encrypt_1c('Needs resolution');
                        $Changes['k0user_of_ae_leader'] = 0;
                    }
                    elseif ($KA == 'k0user_of_ae_leader') {
                        $Changes['c5status'] = $Zfpf->encrypt_1c('Marked resolved');
                        $Changes['k0user_of_ae_leader'] = 1;
                    }
                }
                // $NVT1 should match $NVT[1] in above approve_1... and approve_c1 code
                if (isset($_POST['approve_2_leader']))
                    $NVT1 = 'Recommend resolution approval';
                elseif (isset($_POST['approve_2_ae_leader']))
                    $NVT1 = 'Approve resolution and close action';
                elseif (isset($_POST['approve_c2_leader']))
                    $NVT1 = 'Mark unresolved';
                elseif (isset($_POST['approve_c2_ae_leader']))
                    $NVT1 = 'Re-open action';
                $Changes['c6nymd'.$TaskSubKeyA] = $_SESSION['Scratch']['c6nymd'];
                $Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
                $_SESSION['Selected']['c5status'] = $Changes['c5status']; // Should always be set.
                $_SESSION['Selected']['k0user_of_ae_leader'] = $Changes['k0user_of_ae_leader']; // Should always be set.
                $_SESSION['Selected']['c5ts'.$TaskSubKeyA] = $Changes['c5ts'.$TaskSubKeyA]; // Should always be set.
                $_SESSION['Selected']['c6nymd'.$TaskSubKeyA] = $Changes['c6nymd'.$TaskSubKeyA]; // Should always be set.
                $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing']; // Should always be set.
                $DBMSresource = $Zfpf->credentials_connect_instance_1s();
                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0action', $Changes, $Conditions, TRUE, $htmlFormArray);
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                $Zfpf->close_connection_1s($DBMSresource);
                // Try to email the work email addresses associated with k0user_of_leader plus CoreZfpf::up_the_chain_1c()
                $UpTheChain = $Zfpf->up_the_chain_1c($MaxScope);
                if ($KA == 'k0user_of_leader' and !$UserIsAELeader) { // Resolution assigned to person is the current user and is not also the AEleader.
                    $UpTheChain['EmailAddresses'][] = $User['WorkEmail'];
                    $UpTheChain['DistributionList'] .= '<br />
                    Resolution assigned to person: '.$User['NameTitleEmployerWorkEmail'];
                }
                $UpTheChain['DistributionList'] .= '</p>'; // DistributionList doesn't include the final HTML </p> to facilitate adding additional recipients.
                $Subject = 'PSM-CAP: Action Register';
                $Body = '<p>
                Action Name: '.$ActionName.'<br />
                Step Taken: '.$NVT1.'<br />
                By: '.$User['NameTitleEmployerWorkEmail'].'</p>';
                $Body = $Zfpf->email_body_append_1c($Body, $UpTheChain['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $UpTheChain['DistributionList']);
                $EmailSent = $Zfpf->send_email_1c($UpTheChain['EmailAddresses'], $Subject, $Body);
                echo $Zfpf->xhtml_contents_header_1c('Action').'<h2>
                The app attempted to complete the following step: '.$NVT1.'</h2>';
                if ($EmailSent)
                    echo '<p>You and others involved should soon receive an email confirming this.</p>';
                else
                    echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
                echo '
                <form action="ar_io03.php" method="post"><p>
                    <input type="submit" name="ar_o1" value="Back to record" /></p>
                </form>
                '.$Zfpf->xhtml_footer_1c();
                $Zfpf->save_and_exit_1c();
            }
            // Task assignments
            // No edit_lock_1c() because only the AELeader can assign tasks.
            if (isset($_POST['assign_1'.$TaskSubKeyA])) {
                if (!$UserIsAELeader or $KA != 'k0user_of_leader')
                    $Zfpf->send_to_contents_1c(); // Don't eject
                echo $Zfpf->xhtml_contents_header_1c('Lookup User');
                $Zfpf->lookup_user_1c('ar_io03.php', 'ar_io03.php', 'assign_2'.$TaskSubKeyA, 'ar_o1');
                echo $Zfpf->xhtml_footer_1c();
                $Zfpf->save_and_exit_1c();
            }
            if (isset($_POST['assign_2'.$TaskSubKeyA])) {
                if (!$UserIsAELeader or $KA != 'k0user_of_leader')
                    $Zfpf->send_to_contents_1c(); // Don't eject
                // Create array $Conditions1[] and $TableNameUserEntity for selecting all users from the affected-entity/user junction table.
                if ($MaxScope == 'Contractor-wide') {
                    $TableNameUserEntity = 't0user_contractor';
                    $Conditions1[0] = array('k0contractor', '=', $_SESSION['Selected']['k0affected_entity']);
                }
                elseif ($MaxScope == 'Owner-wide') {
                    $TableNameUserEntity = 't0user_owner';
                    $Conditions1[0] = array('k0owner', '=', $_SESSION['Selected']['k0affected_entity']);
                }
                elseif ($MaxScope == 'Facility-wide') {
                    $TableNameUserEntity = 't0user_facility';
                    $Conditions1[0] = array('k0facility', '=', $_SESSION['Selected']['k0affected_entity']);
                }
                elseif ($MaxScope == 'Process-wide') {
                    $TableNameUserEntity = 't0user_process';
                    $Conditions1[0] = array('k0process', '=', $_SESSION['Selected']['k0affected_entity']);
                }
                $SpecialText = '<p>
                Target date and time to resolve:<br />
                <input type="text" name="c5ts_target" class="screenwidth" maxlength = "'.C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF.'" /></p>';
                $Zfpf->lookup_user_wrap_2c(
                    $TableNameUserEntity,
                    $Conditions1,
                    'ar_io03.php', // $SubmitFile
                    'ar_io03.php', // $TryAgainFile
                    array('k0user'), // $Columns1
                    'ar_io03.php', // $CancelFile
                    $SpecialText,
                    'Assign responsibility and/or timing', // $SpecialSubmitButton
                    'assign_3'.$TaskSubKeyA, // $SubmitButtonName
                    'assign_1'.$TaskSubKeyA, // $TryAgainButtonName
                    'ar_o1', // $CancelButtonName
                    'c5ts_logon_revoked', // $FilterColumnName
                    '[Nothing has been recorded in this field.]' // $Filter
                    ); // This function echos and exits.
            }
            if (isset($_POST['assign_3'.$TaskSubKeyA])) {
                if (!$UserIsAELeader or $KA != 'k0user_of_leader')
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                // Check user-input radio-button selection.
                // The user not selecting a radio button is OK in this case.
                if (isset($_POST['Selected'])) {
                    $Selected = $Zfpf->post_length_1c('Selected');
                    if (isset($_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]))
                        $k0user = $_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]; // This is the k0user of the newly selected user.
                    else
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                }
                else
                    $k0user = 0;
                unset($_SESSION['Scratch']['PlainText']['lookup_user']);
                // Check if user forgot to make an adequate selection.
                $OldTarget = $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_target']);
                $NewTarget = $Zfpf->text_to_timestamp_1c($Zfpf->post_length_blank_1c('c5ts_target'));
                if (
                    ((!$k0user or $k0user == $_SESSION['Selected'][$KA]) and ($NewTarget == $Nothing or $NewTarget == $OldTarget)) or 
                    (!$k0user and !$_SESSION['Selected'][$KA]) or 
                    ($NewTarget == $Nothing and $OldTarget == $Nothing)
                ) {
                    echo $Zfpf->xhtml_contents_header_1c('Nobody New').'<p>
                    Either:<br />
                    - you made no changes,<br />
                    - nobody has been assigned responsibility for resolution, or<br />
                    - no target date for resolution has been assigned.</p>
                    <form action="ar_io03.php" method="post"><p>
                        <input type="submit" name="assign_1'.$TaskSubKeyA.'" value="Try again" /></p><p>
                        <input type="submit" name="ar_o1" value="Cancel" /></p>
                    </form>
                    '.$Zfpf->xhtml_footer_1c();
                    $Zfpf->save_and_exit_1c();
                }
                // Update database with $k0user and any requested completion date.
                $Conditions[0] = array('k0action', '=', $_SESSION['Selected']['k0action']);
                $Body = '';
                $EchoText = '';
                if ($k0user and $k0user != $_SESSION['Selected'][$KA]) {
                    // Get any former leader info needed for notification emails, before updating $_SESSION['Selected'].
                    if ($_SESSION['Selected'][$KA])
                        $FormerLeader = $Zfpf->user_job_info_1c($_SESSION['Selected'][$KA]);
                    $Leader = $Zfpf->user_job_info_1c($k0user);
                    $Changes[$KA] = $k0user;
                    $_SESSION['Selected'][$KA] = $Changes[$KA];
                    if (isset($_SESSION['Scratch']['PlainText']['open_unassociated_action_k0user_of_ae_leader'])) {
                        $Changes['c5status'] = $Zfpf->encrypt_1c('Needs resolution');
                        $Changes['k0user_of_ae_leader'] = 0; // See app schema
                        $_SESSION['Selected']['c5status'] = $Changes['c5status'];
                        $_SESSION['Selected']['k0user_of_ae_leader'] = $Changes['k0user_of_ae_leader'];
                        $Body .= '<p>
                        This action was opened, changing its status from "draft proposed action" to "needs resolution".</p>';
                        $EchoText .= '<p>
                        The app attempted to open this action.</p>';
                    }
                    $Body .= '<p>
                    Resolution assigned to: '.$Leader['NameTitleEmployerWorkEmail'].'<br />
                    By: '.$User['NameTitleEmployerWorkEmail'].' (the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader)</p>';
                    $EchoText .= '<p>
                    The app attempted to assign resolution responsibility to: '.$Leader['NameTitleEmployerWorkEmail'].'</p>';
                }
                else
                    $Leader = $Zfpf->user_job_info_1c($_SESSION['Selected'][$KA]); // Leader same, but new target date.
                if ($NewTarget != $Nothing and $NewTarget != $OldTarget) {
                    $Changes['c5ts_target'] = $Zfpf->encrypt_1c($NewTarget);
                    $_SESSION['Selected']['c5ts_target'] = $Changes['c5ts_target'];
                    $Body .= '<p>';
                    if ($OldTarget != $Nothing)
                        $Body .= '<b>New </b>';
                    $Body .= 'Target Resolution Date and Time: '.$Zfpf->timestamp_to_display_1c($NewTarget).'</p>';
                    $EchoText .= '<p>
                    The app attempted to record a Target Resolution Date and Time of: '.$Zfpf->timestamp_to_display_1c($NewTarget).'</p>';
                }
                if (!isset($Changes))
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                $Body .= '<p>Action Name: '.$ActionName.'</p>';
                // $Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c(). Not needed because no edit_lock_1c() because only the AELeader can assign tasks.
                // $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing']; // Not needed because no edit_lock_1c() because only the AELeader can assign tasks.
                $DBMSresource = $Zfpf->credentials_connect_instance_1s();
                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0action', $Changes, $Conditions, TRUE, $htmlFormArray); 
                // $Affected should not be zero because we confirmed that a new user was selected above.
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                $Zfpf->close_connection_1s($DBMSresource);
                // Try to email any former leader, the newly-assigned leader, and the affected-entity leader (who must be the current user).
                // Don't email higher ranking leaders via up_the_chain_1c() for these assignments; less important than approvals.
                $EmailAddresses = array($User['WorkEmail'], $Leader['WorkEmail']);
                $Subject = 'PSM-CAP: Action Register for '.$ActionName;
                $DistributionList = '<p>
                <b>Distributed To (if an email address was found): </b><br />
                Resolution assigned to person: '.$Leader['NameTitleEmployerWorkEmail'];
                if (isset($FormerLeader)) {
                    $EmailAddresses[] = $FormerLeader['WorkEmail'];
                    $DistributionList .= '<br />
                    Former "resolution assigned to" person: '.$FormerLeader['NameTitleEmployerWorkEmail'];
                }
                $DistributionList .= '<br />
                Affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$User['NameTitleEmployerWorkEmail'];
                $UpTheChain = $Zfpf->up_the_chain_1c($MaxScope);
                $Body = $Zfpf->email_body_append_1c($Body, $UpTheChain['AEFullDescription'], '', $DistributionList);
                $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
                echo $Zfpf->xhtml_contents_header_1c('Action').$EchoText;
                if ($EmailSent)
                    echo '<p>You and others involved should soon receive an email confirming this.</p>';
                else
                    echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
                echo '
                <form action="ar_io03.php" method="post"><p>
                    <input type="submit" name="ar_o1" value="Back to record" /></p>
                </form>
                '.$Zfpf->xhtml_footer_1c();
                $Zfpf->save_and_exit_1c();
            }
        }

    // Additional security check for i1, i2, i3 code
    if ($_SESSION['Selected']['k0user_of_ae_leader'] != -1 and ($_SESSION['Selected']['k0user_of_ae_leader'] != 0 or $_SESSION['Selected']['k0user_of_leader'] != $_SESSION['t0user']['k0user'])) // See app schema; only allow editing here in these cases.
        $Zfpf->send_to_contents_1c(); // Don't eject
    // i1 code
    if (isset($_POST['ar_o1_from']))
        $Zfpf->edit_lock_1c('action', $ActionName); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error.
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['ar_i0n']) or isset($_POST['ar_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles & privileges.
        if ($_SESSION['Selected']['k0user_of_leader'])
            $Display['k0user_of_leader'] = $ResolutionAssignedTo['NameTitleEmployerWorkEmail'];
        else
            $Display['k0user_of_leader'] = $Nothing;
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
    elseif (isset($_SESSION['Post']) and !isset($_POST['ar_i2'])) { // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        // START upload_files special case.
        else {
            $htmlFormArray  = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            foreach ($htmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Selected'][$K]);
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, $K, 'ar_io03.php');
                    // FilesZfpf::6bfn_files_upload_1e updates $_SESSION['Selected'] and the database. 
                    // Or, it echos an error page an user has option to press $_POST['problem_c6bfn_files_upload_1e'] button.
                    $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
                    $SelectDisplay[$K] = $Zfpf->html_uploaded_files_1e($K); // Update the modified select display
                    $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($SelectDisplay);
                    $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($Zfpf->post_to_display_1e($htmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Post']), $UploadResults['count'], $K));
                    // $_SESSION['Post'] now holds $Display holding $_POST updated with $_SESSION['Selected']['c6bfn_...'] information.
                    header("Location: #$K");
                    $Zfpf->save_and_exit_1c();
                }
        }
    }
    if (!$_POST and isset($_SESSION['Post']))
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // This re-defines $Display after upload_files header() redirect above.
    // END uploads_files special case.
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        // Create HTML form
        echo $Zfpf->xhtml_contents_header_1c('Action');
        // To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        echo '<h1>
        Action Register</h1>
        <form action="ar_io03.php" method="post" enctype="multipart/form-data" >';
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
            <input type="submit" name="ar_i2" value="Review what you typed into form" /><br />
            If you only wanted to upload files, you are done. Click on "Go Back"</p>';
        if ($who_is_editing == '[A new database row is being created.]')
            echo '<p>
            <input type="submit" name="ar_i1m_age" value="Go back" /></p>';
        else
            echo '<p>
            <input type="submit" name="ar_o1" value="Go back" /></p>';
        echo '
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['ar_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('ar_io03.php', 'ar_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        // If the affected-entity changed, get the new affected-entity key into $ChangedRow
        if (isset($ChangedRow['c5affected_entity'])) {
            switch ($Zfpf->decrypt_1c($ChangedRow['c5affected_entity'])) {
                case 'Owner-wide':
                    if (!isset($_SESSION['StatePicked']['t0owner']['k0owner']))
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                    $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0owner']['k0owner'];
                    break;
                case 'Contractor-wide':
                    if (!isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                    $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0contractor']['k0contractor'];
                    break;
                case 'Facility-wide':
                    if (!isset($_SESSION['StatePicked']['t0facility']['k0facility']))
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                    $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0facility']['k0facility'];
                    break;
                case 'Process-wide':
                    if (!isset($_SESSION['StatePicked']['t0process']['k0process']))
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                    $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0process']['k0process'];
                    break;
                default:
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            }
        }
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]')
            $Zfpf->insert_sql_1s($DBMSresource, 't0action', $ChangedRow);
        else {
            $Conditions[0] = array('k0action', '=', $_SESSION['Selected']['k0action']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0action', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c('Action').'<h1>
        Action Register</h1><p>
        The record you just reviewed has been recorded.</p>
        <form action="ar_io03.php" method="post"><p>
            <input type="submit" name="ar_o1" value="Back to record" /></p><p>
            <input type="submit" name="ar_i1m_age" value="Back to records list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, i3, open_unassociated_action_1, approval, and assign code

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

