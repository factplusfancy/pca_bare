<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License. 

// This file handles all the input and output HTML forms, except the:
//  - SPECIAL CASE: no i1m files for scenario PHP files.
//  - i3 file for INSERTing into and UPDATEing the database.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
$ccsaZfpf = new ccsaZfpf; // Needed below

// General security check.
// SPECIAL CASE the security token remains 'pha_i1m.php' for subprocess and scenario PHP files. $_SESSION['Selected']... shall also be set.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'pha_i1m.php' or !isset($_SESSION['Selected']['k0pha']) or !isset($_SESSION['Scratch']['t0subprocess']) or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// refresh $_SESSION['Selected'], which still holds the user-selected t0pha row.
$Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
list($SR, $RR) = $Zfpf->one_shot_select_1s('t0pha', $Conditions);
if ($RR == 1)
    $_SESSION['Selected'] = $SR[0];
// refresh $_SESSION['Scratch']['t0subprocess'] for security check.
$Conditions[0] = array('k0subprocess', '=', $_SESSION['Scratch']['t0subprocess']['k0subprocess']);
list($SR, $RR) = $Zfpf->one_shot_select_1s('t0subprocess', $Conditions);
if ($RR == 1)
    $_SESSION['Scratch']['t0subprocess'] = $SR[0];

// SPECIAL CASE -- cleanup if user hit "go back" from viewing...
if (isset($_SESSION['Scratch']['t0cause']))
    unset($_SESSION['Scratch']['t0cause']);
if (isset($_SESSION['Scratch']['t0consequence']))
    unset($_SESSION['Scratch']['t0consequence']);
if (isset($_SESSION['Scratch']['t0safeguard']))
    unset($_SESSION['Scratch']['t0safeguard']);
if (isset($_SESSION['Scratch']['t0action']))
    unset($_SESSION['Scratch']['t0action']);

// Additional security check
$User = $Zfpf->current_user_info_1c();
if ($_SESSION['Selected']['k0pha'] < 100000) // Template PHA case
    $Process['AEFullDescription'] = 'Not associated with a process because this is a template.';
else { // This app requires PHAs, except templates, to be associated with a process.
    if (!isset($_SESSION['StatePicked']['t0process']['k0process']) or $_SESSION['Selected']['k0process'] != $_SESSION['StatePicked']['t0process']['k0process'])
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Process = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['StatePicked']['t0process']['k0process']);
}

// Get useful information...
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$EditAuth = FALSE;
if ($User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and 
        (
            ($_SESSION['Selected']['k0pha'] >= 100000 and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF) or 
            ($_SESSION['Selected']['k0pha'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes')
        )
   )
    $EditAuth = TRUE;
$Nothing = '[Nothing has been recorded in this field.]';
$EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
$Issued = $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_leader']); // $_SESSION['Selected'] still holds the user-selected t0pha row.
if ($Issued == $Nothing)
    $Issued = FALSE;
$SubprocessName = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0subprocess']['c5name']);
// Check if anyone else is editing the subprocess, if so, treat all scenarios in the subprocess as edit_locked.
$who_is_editing = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0subprocess']['c5who_is_editing']);
$EditLocked = TRUE;
if ($who_is_editing == '[Nobody is editing.]' or $who_is_editing == substr($Zfpf->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF))
    $EditLocked = FALSE;

// Get appropriate $htmlFormArray
$htmlFormArray = $ccsaZfpf->html_form_array('scenario_io0_2', $Zfpf);

// Left-hand contents
if (!isset($_POST['scenario_i2']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c5name' => 'Scenario name',
        'c5severity' => 'Severity',
        'cause' => 'Causes',
        'consequence' => 'Consequences',
        'safeguard' => 'Safeguard',
        'action' => 'Actions'
    );

// i0n code
if (isset($_POST['scenario_i0n'])) {
    // Additional security check.
    if ($EditLocked or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // Initialize $_SESSION['Scratch']['t0scenario'] SPECIAL CASE, this serves like $_SESSION['Selected']. $_SESSION['Selected'] keeps holding a t0pha row.
    $_SESSION['Scratch']['t0scenario'] = array(
        'k0scenario' => time().mt_rand(1000000, 9999999),
        'k0subprocess' => $_SESSION['Scratch']['t0subprocess']['k0subprocess'],
        'c5name' => $EncryptedNothing,
        'c5type' => $EncryptedNothing,
        'c5severity' => $EncryptedNothing,
        'c5likelihood' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['scenario_history_o1'])) {
    if (!isset($_SESSION['Scratch']['t0scenario']['k0scenario']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0scenario', $_SESSION['Scratch']['t0scenario']['k0scenario']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one scenario record', 'scenario_io03.php', 'scenario_o1'); // This echos and exits.
}

// o1 code
// SPECIAL CASES, many, including: handling junction tables, $_SESSION['Scratch']['t0scenario'] serves like $_SESSION['Selected']. $_SESSION['Selected'] keeps holding a t0pha row.
if (isset($_POST['scenario_o1'])) {
    // Additional security check.
    if (!isset($_SESSION['Scratch']['t0scenario']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0scenario'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']))
        unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']);
    if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action']))
        unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action']);
    $_SESSION['Scratch']['t0subprocess']['c5who_is_editing'] = $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['t0subprocess']); // Needed for subprocess-wide lock.
    if (!isset($_SESSION['Scratch']['t0scenario'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0scenario'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0scenario'] = $_SESSION['SelectResults']['t0scenario'][$CheckedPost];
        // SPECIAL CASE unset($_SESSION['SelectResults']) is in $ccsaZfpf->scenario_CCSA_Zfpf(), called below.
    }
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0scenario']);
    $Message = $Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Scratch']['t0scenario'], $Display);
    $Message .= '<p>
    <i>Risk ranking with existing safeguards in place:</i><br />
    '.$Zfpf->risk_rank_1c($Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5severity']), $Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5likelihood'])).'</p>
    '.$ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Scratch']['t0scenario'], $Issued, $User, $UserPracticePrivileges, $Zfpf); // SPECIAL CASE
    echo $Zfpf->xhtml_contents_header_1c('Scenario').'<h2>
    Process-hazard analysis (PHA) for<br />
    '.$Process['AEFullDescription'].'<br />
    Subsystem: '.$SubprocessName.'</h2>
    '.$Message;
    if ($EditLocked) {
        if (substr($who_is_editing, 0, 19) != 'PERMANENTLY LOCKED:')
            echo '<p><b>'.$who_is_editing.' is editing a record for the subsystem you selected or its scenarios.</b><br />
            If needed, contact them to coordinate. You will not be able to edit these, nor add new scenarios, until they are done.</p>';
        else
            echo '<p>'.$who_is_editing.'</p>'; // This should echo the permanent-lock message.
    }
    elseif (!$Issued and $EditAuth)
        echo '
        <form action="scenario_io03.php" method="post"><p>
            <input type="submit" name="scenario_o1_from" value="Update scenario" /></p><p>
            <input type="submit" name="scenario_remove_1" value="Remove scenario" /></p>
        </form>';
    else {
        echo '
        <p>You don\'t have editing privileges on this record.</p>';
        if ($Issued)
            echo '<p>
            Once a PHA has been issued, like this one, it cannot be edited.</p>';
        if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
        if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '
    <form action="scenario_io03.php" method="post"><p>
        <input type="submit" name="scenario_history_o1" value="History of this record" /></p>
    </form>
    <form action="subprocess_io03.php" method="post"><p>
        <input type="submit" name="subprocess_o1" value="Back to scenario list" /></p><p>
        <input type="submit" name="subprocess_i1m" value="Back to subsystem List" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, and scenario_remove code
if (isset($_SESSION['Scratch']['t0scenario']['k0scenario'])) {
// No need to refresh session for scenario due to subprocess-wide edit lock.

    // scenario_remove_1 code
    if (isset($_POST['scenario_remove_1'])) {
        // Additional security check.
        if ($EditLocked or $Issued or !$EditAuth)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $ScenarioName = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5name']);
        echo $Zfpf->xhtml_contents_header_1c('Remove Scenario').'<h2>
        Remove scenario?</h2><p>
        Confirm you want to remove scenario:<br />
        <b>'.$ScenarioName.'</b><br />
        from subsystem:<br />
        '.$SubprocessName.' in the<br />
        Process-hazard analysis (PHA) for<br />
        '.$Process['AEFullDescription'].'</p>
        <form action="scenario_io03.php" method="post"><p>
            <input type="submit" name="scenario_remove_2" value="Remove scenario" /></p><p>
            <input type="submit" name="scenario_o1" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // scenario_remove_2 code
    if (isset($_POST['scenario_remove_2'])) {
        // Additional security check.
        if ($EditLocked or $Issued or !$EditAuth)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $ScenarioName = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5name']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $ConditionsScenario[0] = array('k0scenario', '=', $_SESSION['Scratch']['t0scenario']['k0scenario']);
        $Types = array('cause', 'consequence', 'safeguard', 'action');
        foreach ($Types as $ccsa) {
            list($SelectResults['t0scenario_'.$ccsa], $RowsReturned['t0scenario_'.$ccsa]) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $ConditionsScenario);
            if ($RowsReturned['t0scenario_'.$ccsa] > 0)
                foreach ($SelectResults['t0scenario_'.$ccsa] as $VB) {
                    $ConditionsCCSA[0] = array('k0'.$ccsa, '=', $VB['k0'.$ccsa]);
                    list($SelectResults['t0'.$ccsa], $RowsReturned['t0'.$ccsa]) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$ccsa, $ConditionsCCSA);
                    if ($RowsReturned['t0'.$ccsa] != 1) // Each junction table row should only point to one CCSA row.
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                    $ccsaZfpf->ccsa_remove($ccsa, $Zfpf, 'scenario', $_SESSION['Scratch']['t0scenario'], $SelectResults['t0'.$ccsa][0]);
                }
        }
        $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0scenario', $ConditionsScenario, TRUE, $htmlFormArray);
        if ($Affected != 1) {
            error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Scratch']['t0scenario']);
        echo $Zfpf->xhtml_contents_header_1c('Scenario Removed').'<h2>
        Scenario removed.</h2><p>
        The app attempted to remove scenario:<br />
        <b>'.$ScenarioName.'</b><br />
        from subsystem:<br />
        '.$SubprocessName.' in the<br />
        Process-hazard analysis (PHA) for<br />
        '.$Process['AEFullDescription'].'</p>
        <form action="subprocess_io03.php" method="post"><p>
            <input type="submit" name="subprocess_o1" value="Back to scenario list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Additional security check for i1 and i2 code.
    $NewScenarioRow = FALSE;
    if ($Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5who_is_editing']) == '[A new database row is being created.]')
        $NewScenarioRow = TRUE;
    if ($EditLocked or $Issued or (!$NewScenarioRow and !$EditAuth) or ($NewScenarioRow and $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    if ($who_is_editing == '[Nobody is editing.]') // edit_lock subprocess if user may change anything in it.
        $_SESSION['Scratch']['t0subprocess'] = $Zfpf->edit_lock_1c('subprocess', 'subsystem', $_SESSION['Scratch']['t0subprocess']);
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Scratch']['t0scenario'] is only source of $Display.
    if (isset($_POST['scenario_i0n']) or isset($_POST['scenario_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0scenario'], FALSE, TRUE);
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
        // Create HTML form
        echo $Zfpf->xhtml_contents_header_1c('Scenario');
        echo '<h1>
        Edit scenario</h1><h2>
        Process-hazard analysis (PHA) for<br />
        '.$Process['AEFullDescription'].'<br />
        Subsystem: '.$SubprocessName.'</h2>
        <form action="scenario_io03.php" method="post" enctype="multipart/form-data" >';
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
            <input type="submit" name="scenario_i2" value="Review changes to above fields" /></p>
        </form><p>
        <a href="risk_rank_matrix.php" target="_blank">Risk-Ranking Matrix</a></p>
        '.$ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Scratch']['t0scenario'], $Issued, $User, $UserPracticePrivileges, $Zfpf, FALSE); // SPECIAL CASE
        if ($NewScenarioRow)
            echo '
            <form action="subprocess_io03.php" method="post"><p>
                <input type="submit" name="subprocess_o1" value="Go back" /></p>
            </form>';
        else
            echo '
            <form action="scenario_io03.php" method="post"><p>
                <input type="submit" name="scenario_o1" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['scenario_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('scenario_io03.php', 'scenario_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c($_SESSION['Scratch']['t0scenario']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5who_is_editing']) == '[A new database row is being created.]') 
            $Zfpf->insert_sql_1s($DBMSresource, 't0scenario', $ChangedRow);
        else {
            $Conditions[0] = array('k0scenario', '=', $_SESSION['Scratch']['t0scenario']['k0scenario']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0scenario', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        // SPECIAL CASE: Determine if Risk Rank (Priority) changed. S means scenario.
        $OldSPriority = $Zfpf->risk_rank_1c($Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5severity']), $Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5likelihood']));
        if (isset($ChangedRow['c5severity']))
            $PostChangeSeverity = $Zfpf->decrypt_1c($ChangedRow['c5severity']);
        else
            $PostChangeSeverity = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5severity']);
        if (isset($ChangedRow['c5likelihood']))
            $PostChangeLikelihood = $Zfpf->decrypt_1c($ChangedRow['c5likelihood']);
        else
            $PostChangeLikelihood = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5likelihood']);
        $NewSPriority = $Zfpf->risk_rank_1c($PostChangeSeverity, $PostChangeLikelihood);
        if ($OldSPriority != $NewSPriority) {
            // Find any actions in current scenario.
            $Conditions[0] = array('k0scenario', '=', $_SESSION['Scratch']['t0scenario']['k0scenario']);
            list($SelectResults['t0scenario_action'], $RowsReturned['t0scenario_action']) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_action', $Conditions);
            if ($RowsReturned['t0scenario_action'] > 0) {
                // Update risk rank for all actions to the highest of any associated scenario.
                foreach ($SelectResults['t0scenario_action'] as $V) {
                    $k0action = $V['k0action'];
                    $AffectedActions[$k0action] = $NewSPriority;
                }
                $ccsaZfpf->risk_rank_priority_update($Zfpf, $DBMSresource, $AffectedActions, $_SESSION['Scratch']['t0scenario']['k0scenario'], $OldSPriority);
            }
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Scratch']['t0scenario'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c('Scenario').'
        <p>
        The draft document you were editing has been updated with your changes. This document will remain a draft until it is issued by the team leader.</p>
        <form action="scenario_io03.php" method="post"><p>
            <input type="submit" name="scenario_o1" value="Back to scenario" /></p>
        </form>
        <form action="subprocess_io03.php" method="post"><p>
            <input type="submit" name="subprocess_o1" value="Back to scenario list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, and approval code

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

