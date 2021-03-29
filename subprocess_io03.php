<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License. 

// This file handles all the input and output HTML forms, except the:
//  - SPECIAL CASE: no i1m files  for subprocess and scenario PHP files. This file includes subprocess_i1m and io03 code
//  - i3 file for INSERTing into and UPDATEing the database.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

// General security check.
// SPECIAL CASE the security token remains 'pha_i1m.php' for subprocess and scenario PHP files. $_SESSION['Selected']... shall also be set.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'pha_i1m.php' or !isset($_SESSION['Selected']['k0pha']) or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// refresh $_SESSION['Selected'], which still holds the user-selected t0pha row.
$Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
list($SR, $RR) = $Zfpf->one_shot_select_1s('t0pha', $Conditions);
if ($RR == 1)
    $_SESSION['Selected'] = $SR[0];

// SPECIAL CASE -- cleanup if user hit "go back" from scenario viewing...
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
$Issued = $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_leader']);
if ($Issued == $Nothing)
    $Issued = FALSE;
if ($_SESSION['Selected']['k0pha'] < 100000)  // Editing template case.
    $TemplateSourceKey = $_SESSION['Selected']['k0pha'];
else {
    $TemplateSourceKey = $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']); // Before issuing, the source-template k0pha is kept in c6nymd_leader.
    if ($TemplateSourceKey == $Nothing) // PHA was created from scratch, rather than from a template, (the "i0n case")
        $TemplateSourceKey = FALSE;
}

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('Subsystem name', REQUIRED_FIELD_ZFPF)
);

// i1m code
if (isset($_POST['subprocess_i1m']) or isset($_GET['subprocess_i1m'])) {
    if (isset($_SESSION['Scratch']['t0subprocess']))
        unset($_SESSION['Scratch']['t0subprocess']);
    if (isset($_SESSION['Scratch']['PlainText']['SubprocessName']))
        unset($_SESSION['Scratch']['PlainText']['SubprocessName']);
    $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
    list($_SESSION['SelectResults']['t0subprocess'], $RowsReturned['t0subprocess']) = $Zfpf->one_shot_select_1s('t0subprocess', $Conditions);
    if ($RowsReturned['t0subprocess']) {
        $Message = '<p>
        Select subsystem to view.</p>
        <form action="subprocess_io03.php" method="post"><p>';
        // Sort $_SESSION['SelectResults']['t0subprocess'] alphabetically by name.
        foreach ($_SESSION['SelectResults']['t0subprocess'] as $V)
            $_SESSION['Scratch']['PlainText']['SubprocessName'][] = $Zfpf->decrypt_1c($V['c5name']); // SPECIAL CASE: used to avoid duplicates in template_1.
        array_multisort($_SESSION['Scratch']['PlainText']['SubprocessName'], SORT_ASC, $_SESSION['SelectResults']['t0subprocess']);
        foreach ($_SESSION['SelectResults']['t0subprocess'] as $K => $V) {
            $Message .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if ($K == 0)
                $Message .= 'checked="checked" '; // Select the first document by default to ensure something is posted (unless a hacker is tampering).
            $Message .= '/>'.$_SESSION['Scratch']['PlainText']['SubprocessName'][$K].'<br />';
        }
        $Message .= '</p><p>
            <input type="submit" name="subprocess_o1" value="View scenarios" /></p>
        </form>';
    }
    else
        $Message = '<p>
        No subsystems found for the current PHA.</p>';
    if (!$Issued and $EditAuth) {
        $Message .= '
        <form action="subprocess_io03.php" method="post">';
        // Check if t0pha has edit lock. $_SESSION['Selected'] holds t0pha row, see first security check above.
        $t0pha_who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
        if ($t0pha_who_is_editing != '[Nobody is editing.]') // Know PHA is not issued here.
            $Message .= '<p>
            Cannot insert a template subsystem nor insert a new subsystem because <b>'.$t0pha_who_is_editing.'</b> is editing the PHA record you selected. If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
        else {
            if ($TemplateSourceKey and $_SESSION['Selected']['k0pha'] >= 100000)
                $Message .= '<p>
                <input type="submit" name="template_1" value="Select template subsystem" /></p>';
            $Message .= '<p>
            <input type="submit" name="subprocess_i0n" value="Insert new subsystem" /></p>';
        }
        $Message .= '
        </form>';
    }
    elseif (!$EditAuth)
        $Message .= '<p>
        You don\'t have updating privileges on this record.</p>';
    elseif ($Issued)
        $Message .= '<p>This PHA has been issued, so no updating is allowed.</p>';
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    Subsystems for PHA</h1>
    '.$Message.'
    <form action="pha_io03.php" method="post"><p>
        <input type="submit" name="pha_o1" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}


// i0n code
if (isset($_POST['subprocess_i0n'])) {
    // Additional security check.
    // Check if t0pha has edit lock. $_SESSION['Selected'] holds t0pha row, see first security check above.
    $t0pha_who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or $t0pha_who_is_editing != '[Nobody is editing.]')
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // Initialize $_SESSION['Scratch']['t0subprocess'] SPECIAL CASE, this serves like $_SESSION['Selected']. $_SESSION['Selected'] keeps holding a t0pha row.
    $_SESSION['Scratch']['t0subprocess'] = array(
        'k0subprocess' => time().mt_rand(1000000, 9999999),
        'k0pha' => $_SESSION['Selected']['k0pha'],
        'c5name' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['subprocess_history_o1'])) {
    if (!isset($_SESSION['Scratch']['t0subprocess']['k0subprocess']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0subprocess', $_SESSION['Scratch']['t0subprocess']['k0subprocess']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one subsystem record', 'subprocess_io03.php', 'subprocess_o1'); // This echos and exits.
}

// o1 code & scenario_i1m code 
// lots of SPECIAL CASES, including: $_SESSION['Scratch']['t0subprocess'] serves like $_SESSION['Selected']. $_SESSION['Selected'] keeps holding a t0pha row.
if (isset($_POST['subprocess_o1'])) {
    // Additional security check.
    if (!isset($_SESSION['Scratch']['t0subprocess']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0subprocess'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (!isset($_SESSION['Scratch']['t0subprocess'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0subprocess'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0subprocess'] = $_SESSION['SelectResults']['t0subprocess'][$CheckedPost];
        // SPECIAL CASE unset($_SESSION['SelectResults']); in if clause below to clean up after "Go Back" from scenario
    }
    $_SESSION['Scratch']['t0subprocess']['c5who_is_editing'] = $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['t0subprocess']); // Needed for subprocess-wide lock.
    if (isset($_SESSION['SelectResults']))
        unset($_SESSION['SelectResults']);
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0subprocess']);
    // Handle k0 field(s) SPECIAL CASE: None in htmlFormArray
    echo $Zfpf->xhtml_contents_header_1c().'
    <h2>
    Process-hazard analysis (PHA) for<br />
    '.$Process['AEFullDescription'].'</h2>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Scratch']['t0subprocess'], $Display); // Here this only displays the subprocess name.
    // scenario_i1m code START
    $Conditions[0] = array('k0subprocess', '=', $_SESSION['Scratch']['t0subprocess']['k0subprocess']);
    list($_SESSION['SelectResults']['t0scenario'], $RowsReturned['t0scenario']) = $Zfpf->one_shot_select_1s('t0scenario', $Conditions);
    if ($RowsReturned['t0scenario']) {
        echo '<p>
        Select scenario to view.</p>
        <form action="scenario_io03.php" method="post"><p>';
        // Sort $_SESSION['SelectResults']['t0scenario'] alphabetically by name.
        foreach ($_SESSION['SelectResults']['t0scenario'] as $V)
            $ScenarioName[] = $Zfpf->decrypt_1c($V['c5name']);
        array_multisort($ScenarioName, SORT_ASC, $_SESSION['SelectResults']['t0scenario']);
        foreach ($_SESSION['SelectResults']['t0scenario'] as $K => $V) {
            echo '
            <input type="radio" name="selected" value="'.$K.'" ';
            if ($K == 0)
                echo 'checked="checked" '; // Select the first document by default to ensure something is posted (unless a hacker is tampering).
            echo '/>'.$ScenarioName[$K].'<br />';
        }
        echo '</p><p>
            <input type="submit" name="scenario_o1" value="View Scenario" /></p>
        </form>';
    }
    else
        echo '<p>
        No scenario records were found for the current subsystem. Typically, this happens if you inserted a new subsystem, rather than using a template. With proper privileges, you can insert new scenarios. See below.</p>';
    // scenario_i1m code END
    // Check if anyone else is editing the selected row and check user privileges. See messages to the user below regarding privileges.
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0subprocess']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]') {
        if (substr($who_is_editing, 0, 19) != 'PERMANENTLY LOCKED:')
            echo '<p><b>'.$who_is_editing.' is editing a record for the subsystem you selected or its scenarios.</b><br />
            If needed, contact them to coordinate. You will not be able to edit these, nor add new scenarios, until they are done.</p>';
        else
            echo '<p>'.$who_is_editing.'</p>'; // This should echo the permanent-lock message.
    }
    elseif (!$Issued and $EditAuth)
        echo '
        <form action="scenario_io03.php" method="post"><p>
            <input type="submit" name="scenario_i0n" value="Insert new scenario" /></p>
        </form>
        <form action="subprocess_io03.php" method="post"><p>
            <input type="submit" name="subprocess_o1_from" value="Update subsystem name" /></p>
            <input type="submit" name="subprocess_remove_1" value="Remove subsystem" /></p>
        </form>';
    else {
        echo '
        <p>You don\'t have updating privileges on this record.</p>';
        if ($Issued)
            echo '<p>
            Once a PHA has been issued, like this one, it cannot be updated.</p>';
        if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to update any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
        if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to update PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '
    <form action="subprocess_io03.php" method="post"><p>
        <input type="submit" name="subprocess_history_o1" value="History of this record" /></p><p>
        <input type="submit" name="subprocess_i1m" value="Back to subsystem list" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// template_1 code. Called directly by i1m code, so $_SESSION['Scratch']['t0subprocess'] is not set.
if (isset($_POST['template_1'])) {
    // Additional security check.
    $t0pha_who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($Issued or $t0pha_who_is_editing != '[Nobody is editing.]' or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or !$TemplateSourceKey)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    $Conditions[0] = array('k0pha', '=', $TemplateSourceKey);
    list($_SESSION['SelectResults']['TemplateSubs'], $RowsReturned['t0subprocess']) = $Zfpf->one_shot_select_1s('t0subprocess', $Conditions);
    if ($RowsReturned['t0subprocess']) {
        $Message = '<p>
        Select template subsystems to insert. Later, you will be able to update these, including their template scenarios.</p>
        <form action="subprocess_io03.php" method="post"><p>';
        // Eliminate template subprocesses with same name as subprocesses already in PHA.
        // Sort $_SESSION['SelectResults']['TemplateSubs'] alphabetically by name.
        foreach ($_SESSION['SelectResults']['TemplateSubs'] as $K => $V) {
            $TemplateName = $Zfpf->decrypt_1c($V['c5name']);
            if (isset($_SESSION['Scratch']['PlainText']['SubprocessName']) and in_array($TemplateName, $_SESSION['Scratch']['PlainText']['SubprocessName']))
                unset($_SESSION['SelectResults']['TemplateSubs'][$K]);
            else
                $SubprocessName[] = $TemplateName;
        }
    }
    if(isset($SubprocessName)) {
        array_multisort($SubprocessName, SORT_ASC, $_SESSION['SelectResults']['TemplateSubs']);
        foreach ($_SESSION['SelectResults']['TemplateSubs'] as $K => $V) {
            $Message .= '
            <input type="checkbox" name="Selected['.$K.']" value="Yes" />'.$SubprocessName[$K].'<br />';
        }
        $Message .= '</p><p>
            <input type="submit" name="template_2" value="Insert subsystems and scenarios" /></p>
        </form>';
    }
    else
        $Message = '<p>
        No template subsystems were found, perhaps because you inserted all template subsystems in this template PHA. You may insert a new subsystem. Or, look for another template PHA with the subsystems you need to analyze.</p>';
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    Template subsystems</h1><h2>
    Process-hazard analysis (PHA) for<br />
    '.$Process['AEFullDescription'].'</h2>
    '.$Message.'
    <form action="subprocess_io03.php" method="post"><p>
        <input type="submit" name="subprocess_i1m" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// template_2 code.
if (isset($_POST['template_2'])) {
    // Additional security check.
        $t0pha_who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($Issued or $t0pha_who_is_editing != '[Nobody is editing.]' or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or !$TemplateSourceKey)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Ai = 0;
    $Bi = 0;
    foreach ($_SESSION['SelectResults']['TemplateSubs'] as $KA => $VA) {
        if (isset($_POST['Selected'][$KA]) and $_POST['Selected'][$KA] == 'Yes') {
            $Conditions[0] = array('k0subprocess', '=', $VA['k0subprocess']); // Define $Conditions before redefining k0... below.
            $VA['k0subprocess'] = time().$KA.mt_rand(100, 999);
            $VA['k0pha'] = $_SESSION['Selected']['k0pha'];
            $Zfpf->insert_sql_1s($DBMSresource, 't0subprocess', $VA, TRUE, $htmlFormArray);
            list($SelectResults['t0scenario'], $RowsReturned['t0scenario']) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $Conditions);
            if ($RowsReturned['t0scenario'])
                foreach ($SelectResults['t0scenario'] as $KB => $VB) {
                    $Conditions[0] = array('k0scenario', '=', $VB['k0scenario']); // Define $Conditions before redefining k0... below.
                    $VB['k0scenario'] = time().$Ai++.mt_rand(100, 999);
                    $VB['k0subprocess'] = $VA['k0subprocess'];
                    $Zfpf->insert_sql_1s($DBMSresource, 't0scenario', $VB);
                    $Types = array('cause', 'consequence', 'safeguard', 'action');
                    foreach ($Types as $ccsa) {
                        list($SelectResults['t0scenario_'.$ccsa], $RowsReturned['t0scenario_'.$ccsa]) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $Conditions);
                        if ($RowsReturned['t0scenario_'.$ccsa])
                            foreach ($SelectResults['t0scenario_'.$ccsa] as $KC => $VC) {
                                $VC['k0scenario_'.$ccsa] = time().$Bi++.mt_rand(100, 999);
                                $VC['k0scenario'] = $VB['k0scenario'];
                                $Zfpf->insert_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $VC);
                            }
                    }
                }
            $Message = 'The app attempted to insert any template subsystems you just selected, and their template scenarios.';
        }
        else
            if (!isset($Message))
                $Message = 'Looks like you did not select any template subsystems to insert. None were inserted.';
    }
    $Zfpf->close_connection_1s($DBMSresource);
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    Template subsystems</h1><h2>
    Process-hazard analysis (PHA) for<br />
    '.$Process['AEFullDescription'].'</h2><p>
    '.$Message.'</p>
    <form action="subprocess_io03.php" method="post"><p>
        <input type="submit" name="subprocess_i1m" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, and subprocess_remove code
if (isset($_SESSION['Scratch']['t0subprocess']['k0subprocess'])) {
    // refresh $_SESSION['Scratch']['t0subprocess'] for security check.
    $Conditions[0] = array('k0subprocess', '=', $_SESSION['Scratch']['t0subprocess']['k0subprocess']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0subprocess', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Scratch']['t0subprocess'] = $SR[0];
   $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0subprocess']['c5who_is_editing']);

    // subprocess_remove_1 code
    if (isset($_POST['subprocess_remove_1'])) {
        // Additional security check.
        if ($who_is_editing != '[Nobody is editing.]' or $Issued or !$EditAuth)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $_SESSION['Scratch']['t0subprocess'] = $Zfpf->edit_lock_1c('subprocess', 'subsystem', $_SESSION['Scratch']['t0subprocess']); // This re-does SELECT query, checks edit lock, and if none, starts edit lock, which will be cleared by deleting the record or returning to the o1 code.
        $SubprocessName = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0subprocess']['c5name']);
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
        Remove Subsystem '.$SubprocessName.'?</h1><h2>
        Confirm you want to remove subsystem:<br />
        '.$SubprocessName.' from the selected<br />
        Process-hazard analysis (PHA) for<br />
        '.$Process['AEFullDescription'].'</h2>
        <form action="subprocess_io03.php" method="post"><p>
            <input type="submit" name="subprocess_remove_2" value="Remove subsystem" /></p><p>
            <input type="submit" name="subprocess_o1" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // subprocess_remove_2 code
    if (isset($_POST['subprocess_remove_2'])) {
        // Additional security check.
        if ($who_is_editing != substr($Zfpf->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF) or $Issued or !$EditAuth)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $SubprocessName = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0subprocess']['c5name']);
        $Conditions[0] = array('k0subprocess', '=', $_SESSION['Scratch']['t0subprocess']['k0subprocess']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0subprocess', $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1) {
            error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        list($SelectResults['t0scenario'], $RowsReturned['t0scenario']) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $Conditions);
        if ($RowsReturned['t0scenario']) {
            require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
            $ccsaZfpf = new ccsaZfpf;
            foreach ($SelectResults['t0scenario'] as $VA) {
                $ConditionsScenario[0] = array('k0scenario', '=', $VA['k0scenario']);
                $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0scenario', $ConditionsScenario);
                if ($Affected != 1) {
                    error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                }
                $Types = array('cause', 'consequence', 'safeguard', 'action');
                foreach ($Types as $ccsa) {
                    list($SelectResults['t0scenario_'.$ccsa], $RowsReturned['t0scenario_'.$ccsa]) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $ConditionsScenario);
                    if ($RowsReturned['t0scenario_'.$ccsa])
                        foreach ($SelectResults['t0scenario_'.$ccsa] as $VC) {
                            $ConditionsCCSA[0] = array('k0'.$ccsa, '=', $VC['k0'.$ccsa]);
                            list($SelectResults['t0'.$ccsa], $RowsReturned['t0'.$ccsa]) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$ccsa, $ConditionsCCSA);
                            if ($RowsReturned['t0'.$ccsa] != 1) // Each junction table row should only point to one CCSA row.
                                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                            $ccsaZfpf->ccsa_remove($ccsa, $Zfpf, 'scenario', $VA, $SelectResults['t0'.$ccsa][0]);
                        }
                }
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Scratch']['t0subprocess']);
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
        Subsystem Removed.</h1><h2>
        Subsystem '.$SubprocessName.' was removed from the selected<br />
        Process-hazard analysis (PHA) for<br />
        '.$Process['AEFullDescription'].'</h2>
        <form action="subprocess_io03.php" method="post"><p>
            <input type="submit" name="subprocess_i1m" value="Back to subsystem list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Additional security check for i1 and i2 code
    $t0pha_who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($Issued or ($who_is_editing != '[A new database row is being created.]' and !$EditAuth) or ($who_is_editing == '[A new database row is being created.]' and ($User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or $t0pha_who_is_editing != '[Nobody is editing.]')))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    if (isset($_POST['subprocess_o1_from']))
        $_SESSION['Scratch']['t0subprocess'] = $Zfpf->edit_lock_1c('subprocess', 'subsystem', $_SESSION['Scratch']['t0subprocess']); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error.
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Scratch']['t0subprocess'] is only source of $Display.
    if (isset($_POST['subprocess_i0n']) or isset($_POST['subprocess_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0subprocess'], FALSE, TRUE);
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
    elseif (isset($_SESSION['Post']) and !isset($_POST['subprocess_i2'])) // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        // Create HTML form
        echo $Zfpf->xhtml_contents_header_1c();
        // To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        echo '<h1>
        Update subsystem name</h1>
        <form action="subprocess_io03.php" method="post" enctype="multipart/form-data" >';
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
            <input type="submit" name="subprocess_i2" value="Review what you typed into form" /></p>
        </form>';
        if ($who_is_editing == '[A new database row is being created.]')
            echo '
            <form action="subprocess_io03.php" method="post"><p>
                <input type="submit" name="subprocess_i1m" value="Go back" /></p>
            </form>';
        else
            echo '
            <form action="subprocess_io03.php" method="post"><p>
                <input type="submit" name="subprocess_o1" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['subprocess_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('subprocess_io03.php', 'subprocess_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c($_SESSION['Scratch']['t0subprocess']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($who_is_editing == '[A new database row is being created.]') 
            $Zfpf->insert_sql_1s($DBMSresource, 't0subprocess', $ChangedRow);
        else {
            $Conditions[0] = array('k0subprocess', '=', $_SESSION['Scratch']['t0subprocess']['k0subprocess']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0subprocess', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Scratch']['t0subprocess'][$K] = $V;
        $History = $_SESSION['Scratch']['t0subprocess'];
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'
        <p>
        The draft document you were editing has been updated with your changes. This document will remain a draft until it is issued by the team leader.</p>
        <form action="subprocess_io03.php" method="post"><p>
            <input type="submit" name="subprocess_o1" value="Back to subsystem" /></p><p>
            <input type="submit" name="subprocess_i1m" value="Back to subsystems list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, and approval code

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

