<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License. 

// This file handles all the input and output HTML forms, SPECIAL CASE: including audit_fragment_i1m

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf; // ConfirmZfpf needed for for HistoryGetZfpf::selected_changes_html_h
$Zfpf->session_check_1c();

// General security check.
// SPECIAL CASE the security token remains 'audit_i1m.php' for audit_fragment_io03.php. $_SESSION['Selected']['k0audit'] shall also be set.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'audit_i1m.php' or !isset($_SESSION['Selected']['k0audit']) or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Additional security check
$User = $Zfpf->current_user_info_1c();
if ($_SESSION['Selected']['k0audit'] < 100000) // Template case
    $Process['AEFullDescription'] = 'Not associated with a process because this is a template.';
else { // This app requires these reports, except templates, to be associated with a process.
    if (!isset($_SESSION['StatePicked']['t0process']['k0process']) or $_SESSION['Selected']['k0process'] != $_SESSION['StatePicked']['t0process']['k0process'])
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Process = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['StatePicked']['t0process']['k0process']);
}

// Get useful information...
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$EditAuth = FALSE;
if ($User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
    $EditAuth = TRUE;
// Check if anyone else is editing this report, if so, treat all parts of it as edit_locked.
$who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
$EditLocked = TRUE;
if ($who_is_editing == '[Nobody is editing.]' or $who_is_editing == substr($Zfpf->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF))
    $EditLocked = FALSE; // Will be false if edit_locked by current user.
$ReportType = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
if ($ReportType != '[Nothing has been recorded in this field.]')
    $ReportType = $ReportType.' '; // Trailing space so no-effect if blank.
else
    $ReportType = '';

// audit_fragment_i1m code
// SPECIAL CASE: this code is in audit_fragment_io03.php because user can get here by pressing HTML button output by audit_io03.php...
if (isset($_GET['audit_fragment_i1m'])) {
    // Additional security check: none possible here.
    if (isset($_SESSION['SR']))
        unset($_SESSION['SR']);
    if (isset($_SESSION['Scratch']['t0fragment']))
        unset($_SESSION['Scratch']['t0fragment']);
    if (isset($_SESSION['Scratch']['t0audit_fragment']))
        unset($_SESSION['Scratch']['t0audit_fragment']);
    // Give options to view fragments by the division methods recorded in t0rule.
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    list($SRR, $RRR) = $Zfpf->select_sql_1s($DBMSresource, 't0rule', 'No Condition -- All Rows Included');
    if (!$RRR)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>No rules found. Contact app admin. Typically setup on installation.</p>');
    $OtherRules = '';
    foreach ($SRR as $VR) {
        if ($_GET['audit_fragment_i1m'] == $VR['k0rule']) { // This plus "if (!isset($Conditions))" below should handle user tampering with $_GET.
            $RuleName = $Zfpf->decrypt_1c($VR['c5name']);
            $Conditions[0] = array('k0rule', '=', $VR['k0rule']);
        }
        else
            $OtherRules .= '<br /><a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m='.$VR['k0rule'].'">'.$Zfpf->decrypt_1c($VR['c5name']).'</a>';
    }
    if (!isset($Conditions))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>Rule or division method not found. Contact app admin. Typically setup on installation.</p>');
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    <a class="toc" href="glossary.php#fragment" target="_blank">Rule-fragment</a> compliance verifications for<br />
    '.$Process['AEFullDescription'].'</h2><p>
    Rule fragments sorted by:<br />
    <b>'.$RuleName.'</b></p>';
    if ($OtherRules)
        $Message .= '<p>
        Instead sort by:'.$OtherRules.'</p>';
    $Message .= '<p>
    Rule fragments in <a class="toc" href="audit_io03.php?audit_o1#c6audit_scope">the scope</a> are shown below. Also, compliance verification never applies to some parts of rules, like definitions.</a></p>';
    list($SRD, $RRD) = $Zfpf->select_sql_1s($DBMSresource, 't0division', $Conditions);
    if (!$RRD)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>No rule divisions found. Contact app admin. Typically setup on installation.</p>');
    $i = 0;
    foreach ($SRD as $KD => $VD) {
        $Conditions = array();
        $Number = array();
        $Citation = array();
        $FragmentsArray = array();
        $AuditFragmentArray = array();
        $Conditions[0] = array('k0division', '=', $VD['k0division']);
        list($SRFD, $RRFD) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment_division', $Conditions);
        if ($RRFD) {
            $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit'], 'AND');
            foreach ($SRFD as $VFD) {
                // Exclude fragments outside the scope.
                $Conditions[1] = array('k0fragment', '=', $VFD['k0fragment']);
                list($SRAuF, $RRAuF) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment', $Conditions);
                if ($RRAuF) {
                    $FConditions[0] = array('k0fragment', '=', $VFD['k0fragment']);
                    list($SRF, $RRF) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment', $FConditions);
                    if ($RRF != 1)
                        $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching fragments to divisions. Contact app admin.</p>');
                    $Number[] = $Zfpf->decrypt_1c($VFD['c5number']); // See schema t0fragment_division:c5number
                    $Citation[] = $Zfpf->decrypt_1c($SRF[0]['c5citation']);
                    $FragmentsArray[] = $SRF[0];
                    $AuditFragmentArray[] = $SRAuF[0];
                }
            }
            if ($Number) {
                if (!in_array('[Nothing has been recorded in this field.]', $Number) and !in_array('', $Number)) // Sort by number.
                    array_multisort($Number, $Citation, $FragmentsArray, $AuditFragmentArray);
                else // Sort by citation.
                    array_multisort($Citation, $FragmentsArray, $AuditFragmentArray);
                $DivisionName = $Zfpf->decrypt_1c($VD['c5name']);
                $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'][$KD] = $DivisionName;
                $Message .= '<p><a id="'.$KD.'"></a>
                <b>'.$DivisionName.'</b>';
                foreach ($FragmentsArray as $KF => $VF) {
                    $_SESSION['SR']['t0fragment'][$i] = $VF;
                    $_SESSION['SR']['t0audit_fragment'][$i] = $AuditFragmentArray[$KF];
                    $Message .= '<br />
                    <a class="toc" href="audit_fragment_io03.php?audit_fragment_o1='.$i++.'">'.$Zfpf->decrypt_1c($VF['c5name']).' -- '.$Citation[$KF].'</a>';
                }
                $Message .= '</p>';
            }
        }
    }
    $Zfpf->close_connection_1s($DBMSresource);
    if (!$EditLocked and $EditAuth and !$_SESSION['Selected']['k0user_of_certifier'] and ($_SESSION['Selected']['k0audit'] >= 100000 or $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes')) // Only app admins can edit templates.
        $Message .= '<p>
        <a class="toc" href="audit_fragment_io03.php?choose_fragment_1">Change rule fragments in the scope.</a></p>';
    else {
        $Message .= '<p>
        You cannot change the <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a> in the scope because:';
        if ($EditLocked)
            $Message .= '<br />
            '.$who_is_editing.' is editing this report.</p>';
        if (!$EditAuth)
            $Message .= '<br />
            You don\'t have editing privileges on this report. Contact an app admin to increases your privileges.';
        if ($_SESSION['Selected']['k0user_of_certifier'])
            $Message .= '<br />
            This report has been issued.';
        if ($_SESSION['Selected']['k0audit'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes')
            $Message .= '
            <br />This is a template report, which only app admins can edit. You are not an app admin.';
        $Message .= '</p>';
    }
    $Message .= '<p>
    <a class="toc" href="audit_io03.php?audit_o1#bottom">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// choose_fragment code
if (isset($_GET['choose_fragment_1'])) {
    // Additional security check.
    if ($EditLocked or !$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or ($_SESSION['Selected']['k0audit'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes'))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__);
    $Zfpf->clear_edit_lock_1c(); // Set in choose_fragment_2
    if (isset($_SESSION['SR']))
        unset($_SESSION['SR']);
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    Change <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a> in the scope for<br />
    '.$Process['AEFullDescription'].'</h2><p>
    <b>First, select a rule division</b></p>
    <form action="audit_fragment_io03.php" method="post">';
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    list($SRR, $RRR) = $Zfpf->select_sql_1s($DBMSresource, 't0rule', 'No Condition -- All Rows Included');
    if (!$RRR)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>No rules found. Contact app admin. Typically setup on installation.</p>');
    $DivCount = 0;
    foreach ($SRR as $VR) {
        $Message .= '<p><b>'.$Zfpf->decrypt_1c($VR['c5name']).'</b></p><p>';
        $Conditions[0] = array('k0rule', '=', $VR['k0rule']);
        list($SRD, $RRD) = $Zfpf->select_sql_1s($DBMSresource, 't0division', $Conditions);
        if (!$RRD)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>No rule divisions found. Contact app admin. Typically setup on installation.</p>');
        foreach ($SRD as $VD) {
            $_SESSION['SR']['t0division'][$DivCount] = $VD;
            $Message .= '
            <input type="radio" name="selected" value="'.$DivCount++.'" ';
            if ($DivCount == 1)
                $Message .= 'checked="checked" '; // Select the first document by default to ensure something is posted (unless a hacker is tampering).
            $Message .= '/>'.$Zfpf->decrypt_1c($VD['c5name']).'<br />';
        }
        $Message .= '</p>';
    }
    $Zfpf->close_connection_1s($DBMSresource);
    $Message .= '<p>
        Select a division to browse its <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a>.<br />
        <input type="submit" name="choose_fragment_2" value="Select division" /></p>
    </form><p>
    <a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m=1">Back to all compliance verifications</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1#bottom">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
if (isset($_POST['choose_fragment_2'])) {
    // Additional security check.
    if (!isset($_SESSION['SR']['t0division']) or $EditLocked or !$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or ($_SESSION['Selected']['k0audit'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes'))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__);
    $Zfpf->edit_lock_1c('audit', 'this report or one of its supporting records'); // Edit lock now, step 1 above not effected by other editors.
    $CheckedPost = $Zfpf->post_length_blank_1c('selected');
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SR']['t0division'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    Change <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a> in the scope for<br />
    '.$Process['AEFullDescription'].'</h2>
    <form action="audit_fragment_io03.php" method="post"><p>
    Uncheck <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a> to remove them from the scope. Check to add them.</p><p>
    <b>'.$Zfpf->decrypt_1c($_SESSION['SR']['t0division'][$CheckedPost]['c5name']).'</b><br />';
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
    list($SRAuF, $RRAuF) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment', $Conditions);
    $_SESSION['SR']['PlainText']['FragmentsInReportKeys'] = array();
    if ($RRAuF) foreach ($SRAuF as $VAuF)
        $_SESSION['SR']['PlainText']['FragmentsInReportKeys'][] = $VAuF['k0fragment'];
    $Conditions[0] = array('k0division', '=', $_SESSION['SR']['t0division'][$CheckedPost]['k0division']);
    list($SRFD, $RRFD) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment_division', $Conditions);
    if ($RRFD) {
        foreach ($SRFD as $KFD => $VFD) {
            if ($KFD)
                $Message .= '<br />';
            $_SESSION['SR']['PlainText']['k0fragment'][$KFD] = $VFD['k0fragment'];
            $Message .= '
            <input type="checkbox" name="fragment['.$KFD.']" value="1" ';
            if (in_array($VFD['k0fragment'], $_SESSION['SR']['PlainText']['FragmentsInReportKeys']))
                $Message .= 'checked="checked" ';
            $Conditions[0] = array('k0fragment', '=', $VFD['k0fragment']);
            list($SRF, $RRF) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment', $Conditions);
            if ($RRF != 1)
                $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching fragments to divisions. Contact app admin.</p>');
            $Message .= '/>'.$Zfpf->decrypt_1c($SRF[0]['c5name']).' -- '.$Zfpf->decrypt_1c($SRF[0]['c5citation']);
        }
        $Message .= '<br />
        <input type="submit" name="choose_fragment_3" value="Change to selected" />';
    }
    else
        $Message .= '<br />
        No <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a> found in this division. Typically included with app setup. Contact app admin.';
    $Zfpf->close_connection_1s($DBMSresource);
    unset($_SESSION['SR']['t0division']);
    $Message .= '</p>
    </form><p>
    <a class="toc" href="audit_fragment_io03.php?choose_fragment_1">Back to select division.</a></p><p>
    <a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m=1">Back to all compliance verifications</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1#bottom">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
if (isset($_POST['choose_fragment_3'])) {
    // Additional security check.
    if (!isset($_SESSION['SR']['PlainText']['k0fragment']) or !isset($_SESSION['SR']['PlainText']['FragmentsInReportKeys']) or $EditLocked or !$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or ($_SESSION['Selected']['k0audit'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes'))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__);
    $Inserted = 0;
    $Deleted = 0;
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit'], 'AND'); // Used for delete case below.
    foreach ($_SESSION['SR']['PlainText']['k0fragment'] as $KF => $VF) { // $VF is the k0fragment key
        // Case 1 where action required: fragment selected but not associated.
        if (isset($_POST['fragment'][$KF]) and !in_array($VF, $_SESSION['SR']['PlainText']['FragmentsInReportKeys'])) {
            $NewRow = array(
                'k0audit_fragment' => time().mt_rand(1000000, 9999999),
                'k0audit' => $_SESSION['Selected']['k0audit'],
                'k0fragment' => $VF,
                'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0audit_fragment', $NewRow);
            ++$Inserted;
        }
        // Case 2 where action required: fragment not selected and is associated.
        if (!isset($_POST['fragment'][$KF]) and in_array($VF, $_SESSION['SR']['PlainText']['FragmentsInReportKeys'])) {
            $Conditions[1] = array('k0fragment', '=', $VF);
            list($SRAuF, $RRAuF) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment', $Conditions, array('k0audit_fragment')); // Needed to delete t0audit_fragment_obsmethod rows.
            if ($RRAuF != 1)
                $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching this audit to fragments. Contact app admin.</p>');
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit_fragment', $Conditions, TRUE); // If 4th parameter true, delete_sql_1s will only delete one row.
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
            // Also delete t0audit_fragment_obsmethod rows associated with the deleted t0audit_fragment row
            $AuFConditions[0] = array('k0audit_fragment', '=', $SRAuF[0]['k0audit_fragment']);
            list($SRAuFOm, $RRAuFOm) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $AuFConditions, array('k0audit_fragment_obsmethod'));
            if ($RRAuFOm) foreach ($SRAuFOm as $VAuFOm) {
                $AuFOmConditions[0] = array('k0audit_fragment_obsmethod', '=', $VAuFOm['k0audit_fragment_obsmethod']);
                $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $AuFOmConditions, TRUE);
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
            }
            ++$Deleted;
        }
    }
    $Zfpf->close_connection_1s($DBMSresource);
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    Changes to <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a> in the scope for<br />
    '.$Process['AEFullDescription'].'</h2>';
    if ($Inserted)
        $Message .= '<p>
        <b>'.$Inserted.' added</b> Next step, associate <a class="toc" href="glossary.php#obstopic" target="_blank">sample observation methods</a> with the rule fragment(s) you added to the scope.</p>';
    if ($Deleted)
        $Message .= '<p>
        <b>'.$Deleted.' removed.</b></p>';
    elseif (!$Inserted)
        $Message .= '<p>
        <b>No changes made.</b> Rule fragments were neither added nor removed from the scope.</p>';
    $Message .= '<p>
    <a class="toc" href="audit_fragment_io03.php?choose_fragment_1">Back to select division.</a></p><p>
    <a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m=1">Back to all compliance verifications</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1#bottom">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    unset($_SESSION['SR']);
    $Zfpf->clear_edit_lock_1c();
    $Zfpf->save_and_exit_1c();
}

// history_o1 code
if (isset($_GET['audit_fragment_history_o1'])) {
    if (!isset($_SESSION['Scratch']['t0audit_fragment']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0audit_fragment', $_SESSION['Scratch']['t0audit_fragment']['k0audit_fragment']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one report-fragment record', 'audit_fragment_io03.php', 'audit_fragment_o1'); // This echos and exits.
}

// o1 code
// SPECIAL CASES, many, including: handling junction tables, $_SESSION['Scratch']['t0fragment'] serves like $_SESSION['Selected'], $_SESSION['Selected'] keeps holding a t0audit row.
if (isset($_POST['audit_fragment_o1']) or isset($_GET['audit_fragment_o1'])) { // isset($_POST['audit_fragment_o1']) needed for go back from HistoryGetZfpf::selected_changes_html_h
    // Additional security check.
    if (!isset($_SESSION['Scratch']['t0fragment']) and (!isset($_GET['audit_fragment_o1']) or !isset($_SESSION['SR']['t0fragment']) or !isset($_SESSION['SR']['t0audit_fragment'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // If needed, get t0fragment row user selected in audit_fragment_i1m
    if (!isset($_SESSION['Scratch']['t0fragment']) or !isset($_SESSION['Scratch']['t0audit_fragment'])) {
        if (!isset($_GET['audit_fragment_o1']) or !is_numeric($_GET['audit_fragment_o1']) or strlen($_GET['audit_fragment_o1']) > 9)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $i = $_GET['audit_fragment_o1'];
        if (!isset($_SESSION['SR']['t0fragment'][$i]) or !isset($_SESSION['SR']['t0audit_fragment'][$i]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0fragment'] = $_SESSION['SR']['t0fragment'][$i];
        $_SESSION['Scratch']['t0audit_fragment'] = $_SESSION['SR']['t0audit_fragment'][$i];
        unset($_SESSION['SR']);
    }
    // Session cleanup
    if (isset($_SESSION['Scratch']['t0obsresult'])) // Set in obsresult_io03.php:obsresult_o1
        unset($_SESSION['Scratch']['t0obsresult']);
    $Zfpf->clear_edit_lock_1c(); // Clears t0audit edit lock -- defaults to $_SESSION['Selected']; set in code below.
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    <a class="toc" href="glossary.php#fragment" target="_blank">Rule-fragment</a> compliance verifications for<br />
    '.$Process['AEFullDescription'].'</h2><p>
    <a class="toc" href="glossary.php#fragment" target="_blank"><b>Rule fragment:</b></a><br />
    '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5name']).' -- '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5citation']).'<br />'.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c6quote']).'</p>';
    // Get sample observation methods (Om) and any results (Or) associated with the selected fragment, in current report.
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Conditions[0] = array('k0audit_fragment', '=', $_SESSION['Scratch']['t0audit_fragment']['k0audit_fragment']);
    list($SRAuFOm, $RRAuFOm) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $Conditions);
    $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit'], 'AND');
    if ($RRAuFOm) {
        $iA = 0;
        $iOr = 0;
        $AKeys = array();
        foreach ($SRAuFOm as $KAuFOm => $VAuFOm) {
            $Otid = array();
            $Conditions[1] = array('k0obsmethod', '=', $VAuFOm['k0obsmethod']);
            list($SROr, $RROr) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
            if ($RROr) {
                foreach ($SROr as $VOr)
                    $Otid[] = $Zfpf->decrypt_1c($VOr['c5_obstopic_id']);
                array_multisort($Otid, $SROr); // Sort observation results (Or) by specific-observation-topic unique identifier (Otid).
            }
            $Or[$KAuFOm] = array();
            if ($RROr) foreach ($SROr as $KOr => $VOr) { // Each sample observation method (Om) could have Or for many Otid.
                $_SESSION['SR']['t0obsresult'][$iOr] = $VOr;
                $Or[$KAuFOm][$KOr] = '<p>
                <b><i>Object ID</i></b>: <br />'.$Otid[$KOr].'<br />
                <b><i>As-done method</i></b>: <br />'.nl2br($Zfpf->decrypt_1c($VOr['c6obsmethod_as_done'])).'<br />
                <b><i>Result</i></b>: <br />'.nl2br($Zfpf->decrypt_1c($VOr['c6obsresult']));
                if ($Zfpf->decrypt_1c($VOr['c6bfn_supporting']) != '[Nothing has been recorded in this field.]') // Provide link to Or record, showing supporting documents.
                    $Or[$KAuFOm][$KOr] .= '<br /><a class="toc" href="obsresult_io03.php?obsresult_o1='.$iOr++.'">[View supporting documents in record.]</a>';
                // Get any actions (A) associated with each observation result (Or).
                $OrConditions[0] = array('k0obsresult', '=', $VOr['k0obsresult']);
                list($SROrA, $RROrA) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult_action', $OrConditions);
                if ($RROrA) {
                    $Or[$KAuFOm][$KOr] .= '<br />
                    <i>Actions, proposed or referenced:</i>';
                    foreach ($SROrA as $VOrA) {
                        $k0action = $VOrA['k0action'];
                        if (!in_array($k0action, $AKeys)) {
                            $AConditions[0] = array('k0action', '=', $k0action);
                            list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $AConditions);
                            if ($RRA == 1) {
                                $_SESSION['SR']['t0action'][$iA] = $SRA[0]; // Used in ar_io03.php.
                                $ANameLink[$k0action] = '<br /><a class="toc" href="ar_io03.php?ar_o1='.$iA++.'">'.$Zfpf->decrypt_1c($SRA[0]['c5name']).'</a>';
                                $AKeys[] = $k0action;
                            }
                            else { // Log error and disregard action, so script continues.
                                error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' t0action rows returned: '.@$RRA);
                                $ANameLink[$k0action] = '<br />[Action not found. Error. Contact App Admin.]';
                            }
                        }
                        $Or[$KAuFOm][$KOr] .= $ANameLink[$k0action];
                    }
                }
                $Or[$KAuFOm][$KOr] .= '</p>';
            }
            // Get the sample observation method from t0obsmethod. See "observation topic" in app glossary for background.
            $OmConditions[0] = array('k0obsmethod', '=', $VAuFOm['k0obsmethod']);
            list($SROm, $RROm) = $Zfpf->select_sql_1s($DBMSresource, 't0obsmethod', $OmConditions);
            if ($RROm == 1) // Should be exactly 1
                $Om[$KAuFOm] = nl2br($Zfpf->decrypt_1c($SROm[0]['c6obsmethod']));  // $Om[$KAuFOm] should always be populated here, but
            else {                                                         // if not log error and 
                $Om[$KAuFOm] = '[Nothing has been recorded in this field.]'; // set $Om[$KAuFOm] so keys match in array_multisort below.
                error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' t0obsmethod rows returned: '.@$RROm);
            }
        }
        if ($iOr) // At least one obsresult found, so display message below.
            $Message .= '<p>
                Each observation record below includes its:<br />
                - <a class="toc" href="glossary.php#obstopic" target="_blank">observation object unique identifier (object ID)</a>,<br />
                - as-done method,<br />
                - result,<br />
                - supporting-documents link, if any, and <br />
                - <a class="toc" href="glossary.php#actions" target="_blank">actions</a>, proposed or referenced, if any.</p>';
        // Sort each group of Or by Om, then list under Om headings.
        array_multisort($Om, $Or);
        foreach ($Om as $KOm => $VOm) { // $KOm here is like $KAuFOm above, just integers starting at 0.
            $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'][$KOm] = substr($VOm, 0, 30).'...'; // $VOm is the decrypted sample observation method.
            $Message .= '<p class="topborder"><a id="'.$KOm.'"></a><b><a class="toc" href="glossary.php#obstopic" target="_blank">Sample observation method</a> '.++$KOm.':</b><br />'.$VOm.'</p>';
            if ($Or[--$KOm]) foreach ($Or[$KOm] as $VOr) // Pre-decrement $KOm to undo the incrementing above for user output.
                $Message .= $VOr; // $VOr has one result per observation topic, each enclosed in a paragraph.
            else
                $Message .= '<p>
                No results found for this observation method.</p>';
        }
    }
    else
        $Message .= '<p>
        <b>No <a class="toc" href="glossary.php#obstopic" target="_blank">sample observation methods</a> were found for this <a class="toc" href="glossary.php#fragment" target="_blank">rule fragment</a>.</b> If you have privileges to "choose sample observation methods for this rule-fragment compliance verification," a link for this should be below.</p>';
    $Zfpf->close_connection_1s($DBMSresource);
    $TopBorder = ' class="topborder"';
    if ($EditLocked) {
        $Message .= '<p'.$TopBorder.'><b>'.$who_is_editing.' is editing this report.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
        $TopBorder = '';
    }
    elseif (!$_SESSION['Selected']['k0user_of_certifier']) {
        if ($EditAuth and ($_SESSION['Selected']['k0audit'] >= 100000 or $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes')) { // Only app admins can edit templates.
            $Message .= '<p'.$TopBorder.'>
            <a class="toc" href="audit_fragment_io03.php?choose_obsmethod_1">Choose sample observation methods for this rule-fragment compliance verification.</a></p><p>
            <a class="toc" href="audit_fragment_io03.php?audit_fragment_remove_1">Remove this rule fragment from the scope.</a></p>';
            $TopBorder = '';
        }
        else {
            if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF) {
                $Message .= '<p'.$TopBorder.'><b>
                Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
                $TopBorder = '';
            }
            if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF) {
                $Message .= '<p'.$TopBorder.'><b>
                Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
                $TopBorder = '';
            }
        }
    }
    if ($_SESSION['Selected']['k0user_of_certifier']) {
        $Message .= '<p'.$TopBorder.'>
        <b>Issued by: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']).'</b><br />
        This is not a draft report. Once a report has been issued by its report leader, the issued version cannot be changed. The report leader may retract the report and then it may be edited. Or, the action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p>';
        $TopBorder = '';
    }
    $Message .= '<p'.$TopBorder.'>
    <a class="toc" href="audit_fragment_io03.php?audit_fragment_history_o1">History of this record</a></p><p>
    <a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m=1">Back to all compliance verifications</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1#bottom">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();    
    $Zfpf->save_and_exit_1c();
}
    
// choose_obsmethod and audit_fragment_remove code
if (isset($_SESSION['Scratch']['t0fragment']) and isset($_SESSION['Scratch']['t0audit_fragment'])) {

    // Additional security check
    if ($EditLocked or $_SESSION['Selected']['k0user_of_certifier'] or !$EditAuth or ($_SESSION['Selected']['k0audit'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes'))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__);

    // choose_obsmethod code
    if (isset($_GET['choose_obsmethod_1'])) {
        if (isset($_SESSION['SR']))
            unset($_SESSION['SR']);
        $Zfpf->edit_lock_1c('audit', 'this report or one of its supporting records');
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Choose sample observation methods for<br />
        '.$Process['AEFullDescription'].'</h2><p>
        <b>Choose <a class="toc" href="glossary.php#obstopic" target="_blank">sample observation methods</a> for compliance verification of <a class="toc" href="glossary.php#fragment" target="_blank">rule fragment</a></b>:<br />
        '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5name']).' -- '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5citation']).'<br />'.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c6quote']).'</p><p>
        Sample observation methods are sorted by <a class="toc" href="glossary.php#obstopic" target="_blank">observation topic</a>. Some are associated with more than one topic.<br />
        <b>If a sample observation method is selected for any topic, it will be and remain associated with the above rule fragment.</b></p><p>
        Only observation topics in the scope are listed below.<br />
        <a class="toc" href="obsresult_io03.php?choose_obstopic_1">Choose observation topics to include in the scope.</a></p>';
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        // Make one-level numeric array of sample observation methods associated with the selected fragment, for this report.
        $Conditions[0] = array('k0audit_fragment', '=', $_SESSION['Scratch']['t0audit_fragment']['k0audit_fragment']);
        list($SRAuFOm, $RRAuFOm) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $Conditions, array('k0obsmethod'));
        $_SESSION['SR']['PlainText']['OmKeys'] = array();
        if ($RRAuFOm) foreach ($SRAuFOm as $VAuFOm)
            $_SESSION['SR']['PlainText']['OmKeys'][] = $VAuFOm['k0obsmethod'];
        // Get observation topics associated with this report.
        $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        list($SRAuOt, $RRAuOt) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_obstopic', $Conditions);
        if (!$RRAuOt) {
            $Zfpf->close_connection_1s($DBMSresource);
            $Message .= '<p>
            No observation topics have been included in the scope.<br />
            <a class="toc" href="obsresult_io03.php?choose_obstopic_1">Choose observation topics to include in the scope.</a></p><p>
            <a class="toc" href="audit_fragment_io03.php?audit_fragment_o1">Back to compliance verification</a></p>';
            echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();  
            $Zfpf->save_and_exit_1c();
        }
        foreach ($SRAuOt as $VAuOt)
            $OtConditions[] = array('k0obstopic', '=', $VAuOt['k0obstopic'], 'OR');
        unset($OtConditions[--$RRAuOt][3]); // remove the final, hanging, 'OR'.
        list($SROt, $RROt) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic', $OtConditions);
        if ($RROt != ++$RRAuOt) // Pre-increment because decremented above.
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching the report to observation topics. Contact app admin.</p>');
        $Message .= '
        <form action="audit_fragment_io03.php" method="post">';
        $_SESSION['SR']['PlainText']['k0obsmethod'] = array();
        $i = 0;
        foreach ($SROt as $KOt => $VOt) {
            $OtOmNames = array();
            $OtOmKeys = array();
            $Conditions[0] = array('k0obstopic', '=', $VOt['k0obstopic']);
            list($SROtOm, $RROtOm) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic_obsmethod', $Conditions);
            if ($RROtOm) { // Disregard observation topics (Ot) that have no sample observation methods (Om)
                foreach ($SROtOm as $VOtOm) {
                    $k0obsmethod = $VOtOm['k0obsmethod'];
                    // Get t0obsmethod row if needed.
                    if (!in_array($k0obsmethod, $_SESSION['SR']['PlainText']['k0obsmethod'])) {
                        $Conditions[0] = array('k0obsmethod', '=', $k0obsmethod);
                        list($SROm, $RROm) = $Zfpf->select_sql_1s($DBMSresource, 't0obsmethod', $Conditions);
                        if ($RROm != 1)
                            $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching observation topics to sample observation methods. Contact app admin.</p>');
                        $AllOmNames[$k0obsmethod] = nl2br($Zfpf->decrypt_1c($SROm[0]['c6obsmethod']));
                        $AllOmKeys[$k0obsmethod] = $SROm[0]['k0obsmethod'];
                        $_SESSION['SR']['PlainText']['k0obsmethod'][$i] = $SROm[0]['k0obsmethod'];
                        $OmCheckbox[$k0obsmethod] = '<br />
                        <input type="checkbox" name="Om['.$i++.']" value="1" ';
                        // See if this observation method is already associated with this fragment, for this report.
                        if (in_array($k0obsmethod, $_SESSION['SR']['PlainText']['OmKeys']))
                            $OmCheckbox[$k0obsmethod] .= 'checked="checked" ';
                        $OmCheckbox[$k0obsmethod] .= '/>'.$AllOmNames[$k0obsmethod];
                    }
                    $OtOmNames[] = $AllOmNames[$k0obsmethod];
                    $OtOmKeys[] = $AllOmKeys[$k0obsmethod];
                }
                array_multisort($OtOmNames, $OtOmKeys);
                $OtName = $Zfpf->decrypt_1c($VOt['c5name']);
                $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'][$KOt] = substr($OtName, 0, 30).'...'; // Truncate for left-hand contents.           
                $Message .= '<p><a id="'.$KOt.'"></a>
                <b>'.$OtName.'</b>';
                foreach ($OtOmKeys as $VOmk0)
                    $Message .= $OmCheckbox[$VOmk0];
                $Message .= '</p>';
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        $Message .= '<p>
            <input type="submit" name="choose_obsmethod_2" value="Change to selected" /></p>
        </form><p>
        <a class="toc" href="audit_fragment_io03.php?audit_fragment_o1">Back to compliance verification</a></p><p>
        <a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m=1">Back to all compliance verifications</a></p><p>
        <a class="toc" href="audit_io03.php?audit_o1#bottom">Back to report intro</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();  
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['choose_obsmethod_2'])) {
        if (!isset($_SESSION['SR']['PlainText']['k0obsmethod']) or !isset($_SESSION['SR']['PlainText']['OmKeys']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Inserted = 0;
        $Deleted = 0;
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0audit_fragment', '=', $_SESSION['Scratch']['t0audit_fragment']['k0audit_fragment'], 'AND'); // Needed for delete case below.
        foreach ($_SESSION['SR']['PlainText']['k0obsmethod'] as $i => $VOmk0) {
            // Case 1 where action required: obsmethod selected but not associated with fragment, for this report.
            if (isset($_POST['Om'][$i]) and !in_array($VOmk0, $_SESSION['SR']['PlainText']['OmKeys'])) {
                $NewRow = array(
                    'k0audit_fragment_obsmethod' => time().mt_rand(1000000, 9999999),
                    'k0audit_fragment' => $_SESSION['Scratch']['t0audit_fragment']['k0audit_fragment'],
                    'k0obsmethod' => $VOmk0,
                    'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
                );
                $Zfpf->insert_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $NewRow);
                ++$Inserted;
            }
            // Case 2 where action required: obsmethod not selected and is associated with fragment, for this report.
            if (!isset($_POST['Om'][$i]) and in_array($VOmk0, $_SESSION['SR']['PlainText']['OmKeys'])) {
                $Conditions[1] = array('k0obsmethod', '=', $VOmk0);
                $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $Conditions, TRUE); // If 4th parameter true, delete_sql_1s will only delete one row.
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                ++$Deleted;
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['SR']);
        $Zfpf->clear_edit_lock_1c();
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Choose sample observation methods for<br />
        '.$Process['AEFullDescription'].'</h2><p>
        <b>Changes to <a class="toc" href="glossary.php#obstopic" target="_blank">sample observation methods</a> for compliance verification of <a class="toc" href="glossary.php#fragment" target="_blank">rule fragment</a></b>:<br />
        '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5name']).' -- '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5citation']).'<br />'.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c6quote']).'</p>';
        if ($Inserted)
            $Message .= '<p>
            <b>'.$Inserted.' added</b>, possibly associated with several observation topics.</p>';
        if ($Deleted)
            $Message .= '<p>
            <b>'.$Deleted.' removed.</b><br />
            To remove a <a class="toc" href="glossary.php#obstopic" target="_blank">sample observation method</a>, uncheck it under <b>all</b> observation topics it is associated with.</p>';
        elseif (!$Inserted)
            $Message .= '<p>
            <b>No changes made.</b><br/>
            To remove a <a class="toc" href="glossary.php#obstopic" target="_blank">sample observation method</a>, uncheck it under <b>all</b> observation topics it is associated with.</p>';
        $Message .= '<p>
        <a class="toc" href="audit_fragment_io03.php?choose_obsmethod_1">Back to choose sample observation methods.</a></p><p>
        <a class="toc" href="audit_fragment_io03.php?audit_fragment_o1">Back to compliance verification</a></p><p>
        <a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m=1">Back to all compliance verifications</a></p><p>
        <a class="toc" href="audit_io03.php?audit_o1#bottom">Back to report intro</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();  
        $Zfpf->save_and_exit_1c();
    }

    // audit_fragment_remove code
    if (isset($_GET['audit_fragment_remove_1'])) {
        $Zfpf->edit_lock_1c('audit', 'this report or one of its supporting records');
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Remove <a class="toc" href="glossary.php#fragment" target="_blank">rule-fragment</a> compliance verification from the scope for<br />
        '.$Process['AEFullDescription'].'</h2><p>
        <b>Confirm that you want to remove the compliance verification for:</b><br />
        '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5name']).' -- '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5citation']).'<br />'.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c6quote']).'</p>
        <form action="audit_fragment_io03.php" method="post"><p>
            <input type="submit" name="audit_fragment_remove_2" value="Remove verification" /></p>
        </form><p>
        Take no action and go...</p><p>
        <a class="toc" href="audit_fragment_io03.php?audit_fragment_o1">Back to compliance verification</a></p><p>
        <a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m=1">Back to all compliance verifications</a></p><p>
        <a class="toc" href="audit_io03.php?audit_o1#bottom">Back to report intro</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // audit_fragment_remove_2 code
    if (isset($_POST['audit_fragment_remove_2'])) {
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0audit_fragment', '=', $_SESSION['Scratch']['t0audit_fragment']['k0audit_fragment']);
        $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit_fragment', $Conditions, TRUE); // If 4th parameter true, delete_sql_1s will only delete one row.
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        // Also delete t0audit_fragment_obsmethod rows associated with the deleted t0audit_fragment row
        list($SRAuFOm, $RRAuFOm) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $Conditions, array('k0audit_fragment_obsmethod'));
        if ($RRAuFOm) foreach ($SRAuFOm as $VAuFOm) {
            $AuFOmConditions[0] = array('k0audit_fragment_obsmethod', '=', $VAuFOm['k0audit_fragment_obsmethod']);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit_fragment_obsmethod', $AuFOmConditions, TRUE);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        $Zfpf->close_connection_1s($DBMSresource);
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Removed rule-fragment compliance verification from the scope for<br />
        '.$Process['AEFullDescription'].'</h2><p>
        The app removed the compliance verification for:</p><p>
        '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5name']).' -- '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c5citation']).'<br />'.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0fragment']['c6quote']).'</p><p>
        <a class="toc" href="audit_fragment_io03.php?audit_fragment_i1m=1">Back to all compliance verifications</a></p><p>
        <a class="toc" href="audit_io03.php?audit_o1#bottom">Back to report intro</a></p>';
        $Zfpf->clear_edit_lock_1c();
        unset($_SESSION['Scratch']['t0fragment']);
        unset($_SESSION['Scratch']['t0audit_fragment']);
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

} // End choose_obsmethod and audit_fragment_remove code

$Zfpf->catch_all_1c('practice_o1.php');
$Zfpf->save_and_exit_1c();

