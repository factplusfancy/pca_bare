<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License. 

// This file handles all the input and output HTML forms
// SPECIAL CASE: including selecting observation topics (obstopic) and sample methods (obsmethod)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check.
// SPECIAL CASE the security token remains 'audit_i1m.php' for obsresult_io03.php. $_SESSION['Selected']['k0audit'] shall also be set.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'audit_i1m.php' or !isset($_SESSION['Selected']['k0audit']) or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Additional security check and get user and process information.
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

// Function used below and nowhere else.
function lm_obsresults_io03Zfpf($Zfpf, $EditLocked, $who_is_editing, $ReportType, $UserPracticePrivileges, $User) {
    $LimitsMessage = '';
    if ($EditLocked)
        $LimitsMessage .= '<p><b>'.$who_is_editing.' is editing this report.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF) // !$EditAuth case 1 of 2
        $LimitsMessage .= '<p><b>
        Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
    if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF) // !$EditAuth case 2 of 2
        $LimitsMessage .= '<p><b>
        Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    if ($_SESSION['Selected']['k0audit'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes')
        $LimitsMessage .= '<p>
        This is a template report, which only app admins can edit. You are not an app admin.</p>';
    if ($_SESSION['Selected']['k0user_of_certifier'])
        $LimitsMessage .= '<p>
        <b>Issued by: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']).'</b><br />
        This is not a draft report. Once a report has been issued by its report leader, the issued version cannot be changed. The report leader may retract the report and then it may be edited. Or, the action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p>';
    return $LimitsMessage;
}

// obsresult_i1m code
// SPECIAL CASE: this code is in obsresult_io03.php because user gets here by pressing HTML button output by audit_io03.php...
if (isset($_GET['obsresult_i1m']) or isset($_POST['obsresult_i1m'])) {
    // Additional security check: none possible here.
    if (isset($_SESSION['SR']))
        unset($_SESSION['SR']);
    if (isset($_SESSION['SelectResults'])) // May be set by ccsaZfpf::scenario_CCSA_Zfpf
        unset($_SESSION['SelectResults']);
    if (isset($_SESSION['Scratch']['t0obstopic']))
        unset($_SESSION['Scratch']['t0obstopic']);
    if (isset($_SESSION['Scratch']['t0obsmethod']))
        unset($_SESSION['Scratch']['t0obsmethod']);
    if (isset($_SESSION['Scratch']['t0obsresult']))
        unset($_SESSION['Scratch']['t0obsresult']);
    $Zfpf->clear_edit_lock_1c(); // Handles go back...
    $LimitsMessage = lm_obsresults_io03Zfpf($Zfpf, $EditLocked, $who_is_editing, $ReportType, $UserPracticePrivileges, $User);
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    Observations of<br />
    '.$Process['AEFullDescription'].'</h2><p>
    <a class="toc" href="glossary.php#obstopic" target="_blank">Observation topics</a> and sample observation methods in the scope are shown below. Select a topic or method to view any observation results.</p>';
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    // Get observation topics (Ot) associated with the selected report.
    $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
    list($SRAuOt, $RRAuOt) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_obstopic', $Conditions);
    if ($RRAuOt) {
        foreach ($SRAuOt as $VAuOt)
            $OtConditions[] = array('k0obstopic', '=', $VAuOt['k0obstopic'], 'OR');
        unset($OtConditions[--$RRAuOt][3]); // remove the final, hanging, 'OR'.
        list($SROt, $RROt) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic', $OtConditions);
        if ($RROt != ++$RRAuOt) // Pre-increment because decremented above.
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching the report to observation topics. Contact app admin.</p>');
        $i = 0;
        foreach ($SROt as $KOt => $VOt) {
            $OtName = $Zfpf->decrypt_1c($VOt['c5name']);
            $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'][$KOt] = substr($OtName, 0, 30).'...'; // Truncate for left-hand contents.
            $Message .= '<p><a id="'.$KOt.'"></a>';
            $Conditions[0] = array('k0obstopic', '=', $VOt['k0obstopic']);
            list($SROtOm, $RROtOm) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic_obsmethod', $Conditions);
            if ($RROtOm) {
                $Message .= '
                <b>'.$OtName.'</b>';
                if ($_SESSION['Selected']['k0audit'] >= 100000) { // Templates cannot have observation results.
                    if (!$LimitsMessage)
                        $Message .= '<a class="toc" href="obsresult_io03.php?Ot_all_Om_i0='.$i.'"> [Input all]</a>';
                    $Message .= '<a class="toc" href="obsresult_io03.php?Ot_all_Om_done_o1='.$i.'"> [Results]</a>';
                }
                // $i in both cases above will match the first $_SESSION['SR']['t0obstopic'][$i], so works.
                foreach ($SROtOm as $VOtOm) {
                    $Conditions[0] = array('k0obsmethod', '=', $VOtOm['k0obsmethod']);
                    list($SROm, $RROm) = $Zfpf->select_sql_1s($DBMSresource, 't0obsmethod', $Conditions);
                    if ($RROm != 1)
                        $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching observation topics to sample observation methods. Contact app admin.</p>');
                    // Need pairs of $_SESSION['SR']['t0obstopic'] and $_SESSION['SR']['t0obsmethod'] with matching numeric keys, for viewing and populating t0obsresult rows.
                    $_SESSION['SR']['t0obstopic'][$i] = $VOt; // Same stuff stored in session more than once, but not much data overall. Not too big.
                    $_SESSION['SR']['t0obsmethod'][$i] = $SROm[0];
                    $Message .= '<br />
                    <a class="toc" href="obsresult_io03.php?Om_all_Or_o1='.$i++.'">'.substr($Zfpf->decrypt_1c($SROm[0]['c6obsmethod']), 0, 60).'...</a>'; // Truncate c6obsmethod.
                }
            }
            else
                $Message .= '
                <b>'.$OtName.'</b><br />
                No sample observation methods were found for this topic.';
            $Message .= '</p>';
        }
        $Zfpf->close_connection_1s($DBMSresource);
    }
    else
        $Message .= '<p>
        No <a class="toc" href="glossary.php#obstopic" target="_blank">observation topics</a> have been included in the scope. Contact app admin.</p>';
    if (!$LimitsMessage)
        $Message .= '<p>
        <a class="toc" href="obsresult_io03.php?choose_obstopic_1">Choose observation topics to include in the scope.</a></p>';
    $Message .= $LimitsMessage.'<p>
    <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// choose_obstopic code
if (isset($_GET['choose_obstopic_1'])) {
    // Additional security check.
    if ($EditLocked or !$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or ($_SESSION['Selected']['k0audit'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes'))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__);
    $Zfpf->edit_lock_1c('audit', 'this report or one of its supporting records'); // Edit lock so someone else does try to add the same observation topic...
    $Message = '<h2>
    <a class="toc" href="glossary.php#obstopic" target="_blank">Observation topics</a> in the scope<br />
    '.$Process['AEFullDescription'].'</h2><p>
    If observations on a topic are already recorded in this report, that topic cannot be removed, so it is shown below without a checkbox.</p>
    <form action="obsresult_io03.php" method="post"><p>';
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    // Get observation topics (Ot) associated with the selected report.
    $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
    list($SRAuOt, $RRAuOt) = $Zfpf->select_sql_1s($DBMSresource, 't0audit_obstopic', $Conditions);
    $_SESSION['SR']['PlainText']['OtInReportKeys'] = array();
    $OtHasOrKeys = array();
    if ($RRAuOt) foreach ($SRAuOt as $VAuOt) {
        $_SESSION['SR']['PlainText']['OtInReportKeys'][] = $VAuOt['k0obstopic'];
        // Check if Ot already has Or (for this report), in which case this Ot cannot be removed from report.
        $Conditions[1] = array('k0obstopic', '=', $VAuOt['k0obstopic'], '', 'AND');
        list($SROr, $RROr) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
        if ($RROr) foreach ($SROr as $VOr)
            $OtHasOrKeys[] = $VAuOt['k0obstopic']; // Need the Ot key here, not the Or key.
    }
    // Get all possible Ot, then show either no checkbox, empty checkbox, or checked checkbox.
    list($SROt, $RROt) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic', 'No Condition -- All Rows Included');
    $i = 0;
    if ($RROt) {
        foreach ($SROt as $KOt => $VOt) {
            if ($KOt)
                $Message .= '<br />';
            $OtName = $Zfpf->decrypt_1c($VOt['c5name']);
            if (in_array($VOt['k0obstopic'], $OtHasOrKeys))
                $Message .= '
                - '.$OtName.' (observations already recorded)';
            else {
                $_SESSION['SR']['PlainText']['k0obstopic'][$i] = $VOt['k0obstopic']; // Only k0obstopic (the primary key) is needed.
                $Message .= '
                <input type="checkbox" name="obstopic['.$i++.']" value="1" ';
                if (in_array($VOt['k0obstopic'], $_SESSION['SR']['PlainText']['OtInReportKeys']))
                    $Message .= 'checked="checked" ';
                $Message .= '/>'.$OtName;
            }
        }
        $Message .= '</p><p>
        Uncheck <a class="toc" href="glossary.php#obstopic" target="_blank">observation topics</a> to remove them from the scope. Check to add them.<br />
        <input type="submit" name="choose_obstopic_2" value="Change to selected" /></p>';
    }
    else
        $Message .= '<p>
        No observation topics found. Contact app admin. These are typically included on installation.</p>';
    $Message .= '
    </form><p>
    <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
if (isset($_POST['choose_obstopic_2'])) {
    // Additional security check.
    if (!isset($_SESSION['SR']['PlainText']['k0obstopic']) or !isset($_SESSION['SR']['PlainText']['OtInReportKeys']) or $EditLocked or !$EditAuth or $_SESSION['Selected']['k0user_of_certifier'] or ($_SESSION['Selected']['k0audit'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes'))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__);
    $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit'], 'AND'); // Used for delete case below.
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Inserted = 0;
    $Deleted = 0;
    foreach ($_SESSION['SR']['PlainText']['k0obstopic'] as $K_k0obstopic => $V_k0obstopic) {
        // Case 1 where action required: obstopic selected but not associated.
        if (isset($_POST['obstopic'][$K_k0obstopic]) and !in_array($V_k0obstopic, $_SESSION['SR']['PlainText']['OtInReportKeys'])) {
            $NewRow = array(
                'k0audit_obstopic' => time().mt_rand(1000000, 9999999),
                'k0audit' => $_SESSION['Selected']['k0audit'],
                'k0obstopic' => $V_k0obstopic,
                'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0audit_obstopic', $NewRow);
            ++$Inserted;
        }
        // Case 2 where action required: obstopic not selected and is associated.
        if (!isset($_POST['obstopic'][$K_k0obstopic]) and in_array($V_k0obstopic, $_SESSION['SR']['PlainText']['OtInReportKeys'])) {
            $Conditions[1] = array('k0obstopic', '=', $V_k0obstopic);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0audit_obstopic', $Conditions, TRUE); // If 4th parameter true, delete_sql_1s will only delete one row.
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
            ++$Deleted;
        }
    }
    $Zfpf->close_connection_1s($DBMSresource);
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    Changes to<a class="toc" href="glossary.php#obstopic" target="_blank">observation topics</a> in the scope.</h2>';
    if ($Inserted)
        $Message .= '<p>
        <b>'.$Inserted.' added.</b></p>';
    if ($Deleted)
        $Message .= '<p>
        <b>'.$Deleted.' removed.</b></p>';
    elseif (!$Inserted)
        $Message .= '<p>
        <b>No changes made.</b> Observation topics were neither added nor removed from the scope.</p>';
    $Message .= '<p>
    <a class="toc" href="obsresult_io03.php?choose_obstopic_1">Back to choose observation topics</a></p><p>
    <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    unset($_SESSION['SR']);
    $Zfpf->clear_edit_lock_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_GET['Ot_all_Om_i0']) or isset($_POST['Ot_all_Om_i0'])) {
    // Additional security checks and handle $_GET
    if ($_SESSION['Selected']['k0audit'] < 100000) // Templates cannot have observation results.
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (!isset($_SESSION['Scratch']['t0obstopic'])) {
        if (!is_numeric($_GET['Ot_all_Om_i0']) or strlen($_GET['Ot_all_Om_i0']) > 9)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $i = $_GET['Ot_all_Om_i0'];
        if (!isset($_SESSION['SR']['t0obstopic'][$i]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0obstopic'] = $_SESSION['SR']['t0obstopic'][$i];
        unset($_SESSION['SR']);
    }
    $Zfpf->clear_edit_lock_1c(); // Handles go back...
    $OtName = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0obstopic']['c5name']);
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    Sample Observation Methods<br />
    '.$Process['AEFullDescription'].'</h2><p>
    Insert new observations via the links below, to sample observation methods.<br />
    <a class="toc" href="obsresult_io03.php?Ot_all_Om_done_o1">To view results on the observation topic below, click here.</a></p>';
    // Get sample observation methods (Om) associated with the selected observation topic (Ot).
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Conditions[0] = array('k0obstopic', '=', $_SESSION['Scratch']['t0obstopic']['k0obstopic']);
    list($SROtOm, $RROtOm) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic_obsmethod', $Conditions);
    if ($RROtOm) {
        $Message .= '<p><i>'.$OtName.':</i>';
        foreach ($SROtOm as $VOtOm)
            $OmConditions[] = array('k0obsmethod', '=', $VOtOm['k0obsmethod'], 'OR');
        unset($OmConditions[--$RROtOm][3]); // remove the final, hanging, 'OR'.
        list($SROm, $RROm) = $Zfpf->select_sql_1s($DBMSresource, 't0obsmethod', $OmConditions);
        if ($RROm != ++$RROtOm) // Pre-increment because decremented above.
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching observation topics to sample observation methods. Contact app admin.</p>');
        foreach ($SROm as $KOm => $VOm) {
            $_SESSION['SR']['t0obsmethod'][$KOm] = $VOm;
            $Message .= '<br />
            <a class="toc" href="obsresult_io03.php?obsresult_i0n_get_Om='.$KOm.'">'.substr($Zfpf->decrypt_1c($VOm['c6obsmethod']), 0, 60).'...</a>';
        }
        $Message .= '</p>';
    }
    else
        $Message .= '<p><b>None found.</b> No '.$OtName.' sample observation methods have been recorded in this deployment of the app. Contact app admin.</p>';
    $Message .= '<p>
    <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
    $Zfpf->close_connection_1s($DBMSresource);
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_GET['Ot_all_Om_done_o1']) or isset($_POST['Ot_all_Om_done_o1'])) { // isset($_POST['Ot_all_Om_done_o1']) needed for go back button below.
    if (isset($_SESSION['SelectResults'])) // May be set by ccsaZfpf::scenario_CCSA_Zfpf
        unset($_SESSION['SelectResults']);
    if (isset($_SESSION['Scratch']['t0obsresult']))
        unset($_SESSION['Scratch']['t0obsresult']);
    // Additional security checks and handle $_GET
    if ($_SESSION['Selected']['k0audit'] < 100000) // Templates cannot have observation results.
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (!isset($_SESSION['Scratch']['t0obstopic'])) {
        if (!is_numeric($_GET['Ot_all_Om_done_o1']) or strlen($_GET['Ot_all_Om_done_o1']) > 9)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $i = $_GET['Ot_all_Om_done_o1'];
        if (!isset($_SESSION['SR']['t0obstopic'][$i]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0obstopic'] = $_SESSION['SR']['t0obstopic'][$i];
        unset($_SESSION['SR']);
    }
    $Message = '<h2>
    Observation results at<br />
    '.$Process['AEFullDescription'].'</h2><p>
    <b><a class="toc" href="glossary.php#obstopic" target="_blank">Observation topic</a>:<br />
    '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0obstopic']['c5name']).'</b></p>';
    // Get observation results (Or) associated with the selected observation topic (Ot).
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit'], 'AND');
    $Conditions[1] = array('k0obstopic', '=', $_SESSION['Scratch']['t0obstopic']['k0obstopic']);
    list($SROr, $RROr) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
    $Zfpf->close_connection_1s($DBMSresource);
    $i = 0;
    if ($RROr) {
        $Message .= '<p>
        <a class="toc" href="glossary.php#obstopic" target="_blank">Specific-observation-topic unique identifiers (topic ID)</a> and <a class="toc" href="glossary.php#obstopic" target="_blank">as-done observation methods</a> are listed below, truncated.</p>';
        foreach ($SROr as $VOr)
            $OtId[] = $Zfpf->decrypt_1c($VOr['c5_obstopic_id']);
        array_multisort($OtId, $SROr); // Sort observation results (Or) by their specific observation topic (OtId aka c5_obstopic_id).
        // Group Or by OtId
        $PriorOtId = $OtId[0]; // array_multisort re-indexes numeric arrays, is this is the first OtId after sorting.
        foreach ($SROr as $KOr => $VOr) {
            if ($OtId[$KOr] == $PriorOtId)
                $OrArray[$PriorOtId][] = $VOr;
            else {
                $PriorOtId = $OtId[$KOr]; // Move to next OtId, they were sorted above.
                $OrArray[$PriorOtId][] = $VOr;
            }
        }
        foreach ($OrArray as $KOtId => $VOtId) { // $KOtId is the decrypted OtId
            $Omad = array();
            foreach ($VOtId as $VOr)
                $Omad[] = $Zfpf->decrypt_1c($VOr['c6obsmethod_as_done']);
            array_multisort($Omad, $VOtId); // Sort each Ot group of Or by Omad.
            $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'][$i] = substr($KOtId, 0, 30).'...'; // Truncate for left-hand contents.
            $Message .= '<p><a id="'.$i.'"></a>
            <b>'.$KOtId.'</b>';
            foreach ($VOtId as $KOr => $VOr) {
                $_SESSION['SR']['t0obsresult'][$i] = $VOr;
                $Message .= '<br />
                <a class="toc" href="obsresult_io03.php?obsresult_o1='.$i++.'">'.substr($Omad[$KOr], 0, 60).'...</a>';
            }
            $Message .= '</p>';
        }
    }
    else
        $Message .= '<p>
        No results found for this observation topic.</p>';
    $Message .= '<p>
    <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// history_o1 code
if (isset($_GET['obsresult_history_o1'])) {
    if ($_SESSION['Selected']['k0audit'] < 100000) // Templates cannot have observation results.
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (!isset($_SESSION['Scratch']['t0obsresult']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0obsresult', $_SESSION['Scratch']['t0obsresult']['k0obsresult']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one observation record', 'obsresult_io03.php', 'obsresult_o1'); // This echos and exits.
}

// Needed for many cases below
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
$ccsaZfpf = new ccsaZfpf;
$htmlFormArray = $ccsaZfpf->html_form_array('obsresult', $Zfpf);
$Types = array('action' => '<b>Actions, proposed or referenced.</b> If a deficiency, found by the observations, can be resolved by completing an open action, already in this app\'s action register, this open action should be referenced. Otherwise, a proposed action should be drafted, which this app logs in its action register once the report is issued by its report leader');

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Scratch']['t0obsresult']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'obsresult_io03.php', 'obsresult_o1', $_SESSION['Scratch']['t0obsresult']);
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'obsresult_io03.php', 'obsresult_o1', $_SESSION['Scratch']['t0obsresult']);
    $_POST['obsresult_o1'] = 1; // Only needed as catch, after minor stuff, like user forgot to select a file before downloading, or password check before download.
}

// o1 code
if (isset($_GET['obsresult_o1']) or isset($_POST['obsresult_o1'])) { // isset($_POST['obsresult_o1']) needed for go back from HistoryGetZfpf::selected_changes_html_h
    if ($_SESSION['Selected']['k0audit'] < 100000) // Templates cannot have observation results.
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // Handle $_GET
    if (!isset($_SESSION['Scratch']['t0obsresult'])) {
        if (!is_numeric($_GET['obsresult_o1']) or strlen($_GET['obsresult_o1']) > 9)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $i = $_GET['obsresult_o1'];
        if (!isset($_SESSION['SR']['t0obsresult'][$i]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0obsresult'] = $_SESSION['SR']['t0obsresult'][$i];
        unset($_SESSION['SR']);
    }
    if (isset($_SESSION['Scratch']['t0action'])) // May be set by ccsaZfpf::scenario_CCSA_Zfpf or downstream ("go back" from viewing an open action)
        unset($_SESSION['Scratch']['t0action']);
    if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar'])) // May be set by ccsaZfpf::scenario_CCSA_Zfpf or downstream
        unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']);
    if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action'])) // May be set by ccsaZfpf::scenario_CCSA_Zfpf or downstream
        unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action']);
    $Zfpf->clear_edit_lock_1c(); // Handles go back...
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Conditions[0] = array('k0obsmethod', '=', $_SESSION['Scratch']['t0obsresult']['k0obsmethod']);
    list($SROm, $RROm) = $Zfpf->select_sql_1s($DBMSresource, 't0obsmethod', $Conditions);
    if ($RROm != 1)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching an observation result to a sample observation method. Contact app admin.</p>');
    $_SESSION['Scratch']['t0obsmethod'] = $SROm[0]; // Potentially used by PHP files the user may call later.
    $LimitsMessage = lm_obsresults_io03Zfpf($Zfpf, $EditLocked, $who_is_editing, $ReportType, $UserPracticePrivileges, $User);
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    One observation of<br />
    '.$Process['AEFullDescription'].'</h2><p>
    <i>Sample observation method:</i><br />
    '.$Zfpf->decrypt_1c($SROm[0]['c6obsmethod']).'</p>';
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c5_obstopic_id' => 'Topic',
        'c6obsmethod_as_done' => 'Method',
        'c6obsresult' => 'Results',
        'c6bfn_supporting' => 'Documents'
    );
    list($SROtOm, $RROtOm) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic_obsmethod', $Conditions);
    if ($RROtOm) {
        $Message .= '<p>
        <i>Related observation topics:</i>';
        foreach ($SROtOm as $VOtOm) {
            $Conditions[0] = array('k0obstopic', '=', $VOtOm['k0obstopic']);
            list($SROt, $RROt) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic', $Conditions);
            if ($RROt != 1)
                $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching a sample observation method to observation topics. Contact app admin.</p>');
            $Message .= '<br />
            '.$Zfpf->decrypt_1c($SROt[0]['c5name']);
        }
        $Message .= '</p>';
    }
    $Zfpf->close_connection_1s($DBMSresource);
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0obsresult'], TRUE);
    $Message .= $Zfpf->select_to_o1_html_1e($htmlFormArray, 'obsresult_io03.php', $_SESSION['Scratch']['t0obsresult'], $Display, ' class="topborder"');
    if ($_SESSION['Selected']['k0audit'] >= 100000) // Template audits cannot have actions.
        $Message .= $ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Scratch']['t0obsresult'], $_SESSION['Selected']['k0user_of_certifier'], $User, $UserPracticePrivileges, $Zfpf, TRUE, 'obsresult', $Types);
        // ccsaZfpf::scenario_CCSA_Zfpf (with 6th parameter TRUE):
        // - returns HTML for list of actions to select from and
        // - sets $_SESSION['SelectResults'] and uses it if needed.
    $Message .= $LimitsMessage;
    if (!$LimitsMessage)
        $Message .= '<p>
        <a class="toc" href="obsresult_io03.php?obsresult_o1_from">Update this observation</a></p><p>
        <a class="toc" href="obsresult_io03.php?obsresult_delete_1">Remove this observation from the report</a></p>';
    $Message .= '<p>
        <a class="toc" href="obsresult_io03.php?obsresult_history_o1">History of this record</a></p>';
    if (isset($_SESSION['Scratch']['t0obstopic'])) {
        if (!$_SESSION['Selected']['k0user_of_certifier'])
            $Message .= '<p>
            <a class="toc" href="obsresult_io03.php?Ot_all_Om_i0">Back to input all</a></p>';
        $Message .= '<p>
        <a class="toc" href="obsresult_io03.php?Ot_all_Om_done_o1">Back to results</a></p>';
    }
    if (isset($_SESSION['Scratch']['t0obstopic']) and isset($_SESSION['Scratch']['t0obsmethod']))
        $Message .= '<p>
        <a class="toc" href="obsresult_io03.php?Om_all_Or_o1">Back to all observations for this method</a></p>';
    if (isset($_SESSION['Scratch']['t0fragment']) and isset($_SESSION['Scratch']['t0audit_fragment']))
        $Message .= '<p>
        <a class="toc" href="audit_fragment_io03.php?audit_fragment_o1">Back to compliance verification</a></p>';
    $Message .= '<p>
        <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();    
    $Zfpf->save_and_exit_1c();
}

// Om_all_Or_o1, a multi o1 code
// SPECIAL CASES, many, including handling junction tables: $_SESSION['Selected'] keeps holding a t0audit row;
// sets $_SESSION['Scratch']['t0obstopic'] and $_SESSION['Scratch']['t0obsmethod']
if (isset($_GET['Om_all_Or_o1']) or isset($_POST['Om_all_Or_o1'])) {
    // Additional security check and
    // if needed, get handle user selection from obsresult_i1m
    if (!isset($_SESSION['Scratch']['t0obstopic']) or !isset($_SESSION['Scratch']['t0obsmethod'])) {
        if (!isset($_GET['Om_all_Or_o1']) or !is_numeric($_GET['Om_all_Or_o1']) or strlen($_GET['Om_all_Or_o1']) > 9)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $i = $_GET['Om_all_Or_o1'];
        if (!isset($_SESSION['SR']['t0obstopic'][$i]) or !isset($_SESSION['SR']['t0obsmethod'][$i]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0obstopic'] = $_SESSION['SR']['t0obstopic'][$i];
        $_SESSION['Scratch']['t0obsmethod'] = $_SESSION['SR']['t0obsmethod'][$i];
        unset($_SESSION['SR']);
    }
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (isset($_SESSION['Scratch']['t0action'])) // May be set by ccsaZfpf::scenario_CCSA_Zfpf or downstream ("go back" from viewing an open action)
        unset($_SESSION['Scratch']['t0action']);
    if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar'])) // May be set by ccsaZfpf::scenario_CCSA_Zfpf or downstream
        unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']);
    if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action'])) // May be set by ccsaZfpf::scenario_CCSA_Zfpf or downstream
        unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action']);
    if (isset($_SESSION['Scratch']['t0obsresult']))
        unset($_SESSION['Scratch']['t0obsresult']);
    $Zfpf->clear_edit_lock_1c(); // Clears t0audit edit lock -- defaults to $_SESSION['Selected']; set in i1... code below.
    // Many to many relationships between t0obsresult, t0obsmethod, and t0obstopic
    // Here, show all obsresult for an obsmethod, regarless of obstopic. obsresult holds the specific "topic ID", in field c5_obstopic_id.
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Conditions[0] = array('k0obsmethod', '=', $_SESSION['Scratch']['t0obsmethod']['k0obsmethod']);
    $Conditions[1] = array('k0audit', '=', $_SESSION['Selected']['k0audit'], '', 'AND');
    list($SROr, $RROr) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
    $LimitsMessage = lm_obsresults_io03Zfpf($Zfpf, $EditLocked, $who_is_editing, $ReportType, $UserPracticePrivileges, $User);
    $Message = '<h2>';
    if ($ReportType)
        $Message .= $ReportType.'</h2><h2>';
    $Message .= '
    Observations of<br />
    '.$Process['AEFullDescription'].'</h2><p>
    <i>Sample observation method:</i><br />
    '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0obsmethod']['c6obsmethod']).'</p>';
    unset($Conditions[1]);
    list($SROtOm, $RROtOm) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic_obsmethod', $Conditions);
    if ($RROtOm) {
        $Message .= '<p>
        <i>Related observation topics:</i>';
        foreach ($SROtOm as $VOtOm) {
            $Conditions[0] = array('k0obstopic', '=', $VOtOm['k0obstopic']);
            list($SROt, $RROt) = $Zfpf->select_sql_1s($DBMSresource, 't0obstopic', $Conditions);
            if ($RROt != 1)
                $Zfpf->send_to_contents_1c(__FILE__, __LINE__, '<p>An error occurred matching a sample observation method to observation topics. Contact app admin.</p>');
            $Message .= '<br />
            '.$Zfpf->decrypt_1c($SROt[0]['c5name']);
        }
        $Message .= '</p>';
    }
    $Zfpf->close_connection_1s($DBMSresource);
    if ($RROr) {
        $i = 0;
        foreach ($SROr as $KOr => $VOr) {
            $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'][$KOr] = substr($Zfpf->decrypt_1c($VOr['c5_obstopic_id']), 0, 30).'...'; // Truncate for left-hand contents.
            $Message .= '<a id="'.$KOr.'"></a>';
            $Display = $Zfpf->select_to_display_1e($htmlFormArray, $VOr); // Cannot allow downloads form here (3rd parameter default of FALSE) because $_SESSION['Scratch']['t0obsresult'] is not set. 
            $Message .= $Zfpf->select_to_o1_html_1e($htmlFormArray, 'obsresult_io03.php', $VOr, $Display, ' class="topborder"');
            if ($_SESSION['Selected']['k0audit'] >= 100000) // Template audits cannot have actions.
                $Message .= $ccsaZfpf->scenario_CCSA_Zfpf($VOr, $_SESSION['Selected']['k0user_of_certifier'], $User, $UserPracticePrivileges, $Zfpf, FALSE, 'obsresult', $Types);
                // ccsaZfpf::scenario_CCSA_Zfpf (with 6th parameter FALSE): returns a list of action names in this observation. 
                // Cannot provide radio-button list of actions (6th parameter TRUE) because $_SESSION['SR']['t0obsresult'] is not set here.
            $_SESSION['SR']['t0obsresult'][$i] = $VOr;
            if (!$LimitsMessage and $_SESSION['Selected']['k0audit'] >= 100000) { // Templates cannot have observation results.
                $Message .= '<p>
                <a class="toc" href="obsresult_io03.php?obsresult_o1='.$i.'">[Download any supporting documents or propose an action]</a></p><p>
                <a class="toc" href="obsresult_io03.php?obsresult_o1_from='.$i.'">[Update this observation]</a></p>';
            }
            elseif ($_SESSION['Selected']['k0user_of_certifier'])
                $Message .= '<p>
                <a class="toc" href="obsresult_io03.php?obsresult_o1='.$i.'">[Download any supporting documents or view action details]</a></p>';
            $i++;
        }
        if (!$LimitsMessage and $_SESSION['Selected']['k0audit'] >= 100000)
            $Message .= '<p class="topborder">
            <a class="toc" href="obsresult_io03.php?obsresult_i0n">Insert a new observation for the selected sample observation method (shown above) and observation topic:<br />'.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0obstopic']['c5name']).'</a></p>';
        $Message .= $LimitsMessage.'<p>
        <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
    <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();    
        $Zfpf->save_and_exit_1c();
    }
    else { // !$RROr case
        if ($LimitsMessage or $_SESSION['Selected']['k0audit'] < 100000) { // Don't allow inserting a new t0obsresult row.
            $Message .= '<p class="topborder">
            No observations found for this sample observation method.</p>
            '.$LimitsMessage.'<p>
            <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
            <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
            echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();    
            $Zfpf->save_and_exit_1c();
        }
        else { // i0n code -- case 1 of 2. In this case, $Message gets overwritten by i1 code below.
            $_POST['obsresult_i0n'] = TRUE;
        } // don't exit, continue to i0n then i1 code.
    }
}

// i0n code -- case 2 of 2
if (isset($_POST['obsresult_i0n']) or isset($_GET['obsresult_i0n']) or isset($_GET['obsresult_i0n_get_Om'])) {
    // Additional security checks and handle $_GET
    if (isset($_GET['obsresult_i0n_get_Om'])) { // Get the user-selected observation method (Om).
        if (!is_numeric($_GET['obsresult_i0n_get_Om']) or strlen($_GET['obsresult_i0n_get_Om']) > 9)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $i = $_GET['obsresult_i0n_get_Om'];
        if (!isset($_SESSION['SR']['t0obsmethod'][$i]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0obsmethod'] = $_SESSION['SR']['t0obsmethod'][$i];
        unset($_SESSION['SR']);
        $_POST['obsresult_i0n'] = TRUE;
    }
    if (isset($_GET['obsresult_i0n']))
        $_POST['obsresult_i0n'] = TRUE; // Just allows using link instead of button.
    if (!isset($_SESSION['Scratch']['t0obstopic']) or !isset($_SESSION['Scratch']['t0obsmethod']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $_SESSION['Scratch']['t0obsresult'] = array(
        'k0obsresult' => time().mt_rand(1000000, 9999999),
        'k0audit' => $_SESSION['Selected']['k0audit'],
        'k0obstopic' => $_SESSION['Scratch']['t0obstopic']['k0obstopic'],
        'k0obsmethod' => $_SESSION['Scratch']['t0obsmethod']['k0obsmethod'],
        'c5_obstopic_id' => $_SESSION['Scratch']['t0obstopic']['c5name'], // Supply general observation topic (Ot) as sample for specific Otid.
        'c6obsmethod_as_done' => $_SESSION['Scratch']['t0obsmethod']['c6obsmethod'], // Supply general observation method (Om) as sample for Om as done.
        'c6obsresult' => $EncryptedNothing,
        'c6bfn_supporting' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
} // Don't exit, continue to i1 code.

// Coming from Om_all_Or_o1 case, need to set $_SESSION['Scratch']['t0obsresult'] before i1 code.
if (isset($_GET['obsresult_o1_from']) and !isset($_SESSION['Scratch']['t0obsresult'])) {
    if (!is_numeric($_GET['obsresult_o1_from']) or strlen($_GET['obsresult_o1_from']) > 9)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $i = $_GET['obsresult_o1_from'];
    if (!isset($_SESSION['SR']['t0obsresult'][$i]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['t0obsresult'] = $_SESSION['SR']['t0obsresult'][$i];
    unset($_SESSION['SR']);
} // Don't exit, continue to i1 code.

// i1, i2, i3 code
if (isset($_SESSION['Scratch']['t0obsresult'])) {
    // Additional security checks
    if ($_SESSION['Selected']['k0audit'] < 100000) // Templates cannot have observation results.
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if ($EditLocked or $_SESSION['Selected']['k0user_of_certifier'] or !$EditAuth)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__);
    if ($who_is_editing == '[Nobody is editing.]')
        $Zfpf->edit_lock_1c('audit', 'this report or one of its supporting records');
        // Edit lock report if user may change anything in it. 
        // CoreZfpf::edit_lock_1c defaults to locking row in table held in $_SESSION['Selected'], which here holds the t0audit row.// Left hand Table of contents 
    // Left-hand contents: Custom version in obsresult_i1m and o1 codes. Handle i1 code situations.
    if (isset($_POST['obsresult_i0n']) or isset($_GET['obsresult_o1_from']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e'])) 
        $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
            'c5_obstopic_id' => 'Topic',
            'c6obsmethod_as_done' => 'Method',
            'c6obsresult' => 'Results',
            'c6bfn_supporting' => 'Documents'
        );

// obsresult_delete code
    if (isset($_GET['obsresult_delete_1'])) {
        $Zfpf->edit_lock_1c('audit', 'this report or one of its supporting records');
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Remove observation from the report for<br />
        '.$Process['AEFullDescription'].'</h2><p>
        <b>Confirm that you want to remove the observation below from this report.</b></p><p>
        The information below and a record of this removal will remain in this app\'s history tables.</p>';
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0obsresult'], TRUE);
        $Message .= $Zfpf->select_to_o1_html_1e($htmlFormArray, 'obsresult_io03.php', $_SESSION['Scratch']['t0obsresult'], $Display);
        if ($_SESSION['Selected']['k0audit'] >= 100000) // Template audits cannot have actions.
            $Message .= $ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Scratch']['t0obsresult'], $_SESSION['Selected']['k0user_of_certifier'], $User, $UserPracticePrivileges, $Zfpf, FALSE, 'obsresult', $Types);
        $Message .= '
        <form action="obsresult_io03.php" method="post"><p>
            <input type="submit" name="obsresult_delete_2" value="Remove observation" /></p>
        </form><p>
        Take no action and go...</p><p>
        <a class="toc" href="obsresult_io03.php?obsresult_o1">Back to this observation</a></p><p>
        <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
        <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['obsresult_delete_2'])) {
        $Conditions[0] = array('k0obsresult', '=', $_SESSION['Scratch']['t0obsresult']['k0obsresult']);
        $Affected = $Zfpf->one_shot_delete_1s('t0obsresult', $Conditions, TRUE); // If 3rd parameter true, will only delete one row.
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        $Message = '<h2>';
        if ($ReportType)
            $Message .= $ReportType.'</h2><h2>';
        $Message .= '
        Removed observation from the report for<br />
        '.$Process['AEFullDescription'].'</h2><p>
        The app removed the observation.</p><p>
        <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
        <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
        $Zfpf->clear_edit_lock_1c();
        unset($_SESSION['Scratch']['t0obsresult']);
        unset($_SESSION['Scratch']['t0obsmethod']);
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Scratch']['t0obsresult'] is only source of $Display.
    if (isset($_POST['obsresult_i0n']) or isset($_GET['obsresult_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0obsresult'], FALSE, TRUE);
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
    elseif (isset($_SESSION['Post']) and !isset($_POST['obsresult_i2'])) { // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        else {
            $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            foreach ($htmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Scratch']['t0obsresult'][$K]); // SPECIAL CASE $_SESSION['Scratch']['t0obsresult'] is selected row.
                    // FilesZfpf::user_files_directory_1e has been checked, it supports t0obsresult
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e($_SESSION['Scratch']['t0obsresult']), $c6bfn_array, $K, 'obsresult_io03.php', $_SESSION['Scratch']['t0obsresult']);
                    $_SESSION['Scratch']['t0obsresult'][$K] = $UploadResults['new_c6bfn']; // SPECIAL CASE: FilesZfpf::6bfn_files_upload_1e updates the database but not $_SESSION['Scratch']['t0obsresult'][$K].
                    // Or, FilesZfpf::6bfn_files_upload_1e updates echos an error page an user has option to press $_POST['problem_c6bfn_files_upload_1e'] button.
                    $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
                    $SelectDisplay[$K] = $Zfpf->html_uploaded_files_1e($K, 0, $_SESSION['Scratch']['t0obsresult']); // Update the modified select display // SPECIAL CASE $_SESSION['Scratch']['t0obsresult'] is selected row. For 2nd field, default passed in.
                    $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($SelectDisplay);
                    $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($Zfpf->post_to_display_1e($htmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Post']), $UploadResults['count'], $K, $_SESSION['Scratch']['t0obsresult'])); // SPECIAL CASE: 5th field needed because the selected row isn't in $_SESSION['Selected'] here.
                    // $_SESSION['Post'] now holds $Display holding $_POST updated with $_SESSION['Scratch']['t0obsresult']['c6bfn_...'] information.
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
        Edit this observation of<br />
        '.$Process['AEFullDescription'].'</h2><p>
        Use exactly the same characters for each <a class="toc" href="glossary.php#obstopic" target="_blank">Specific-observation-topic unique identifiers (topic ID)</a>, whenever written. For example, an observation topic might be "compressors", with "Compressor RC1" as the topic ID. Be guided by the sample observation method but record the as-done observation method.</p>
        <form action="obsresult_io03.php" method="post" enctype="multipart/form-data" >'; // upload_files special case 2 of 3. To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        $Message .= $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display, $_SESSION['Scratch']['t0obsresult']); // SPECIAL CASE $_SESSION['Scratch']['t0obsresult'] is selected row. Only relevant when the selected row contains c6bfn fields.
        $Message .= $ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Scratch']['t0obsresult'], $_SESSION['Selected']['k0user_of_certifier'], $User, $UserPracticePrivileges, $Zfpf, FALSE, 'obsresult', $Types);
        $Message .= '<p>
            <input type="submit" name="obsresult_i2" value="Review what you typed into form" /><br />
            If you only wanted to upload files, you are done.</p>
        </form>'; // upload_files special case 3 of 3.
        if ($Zfpf->decrypt_1c($_SESSION['Scratch']['t0obsresult']['c5who_is_editing']) != '[A new database row is being created.]')
            $Message .= '<p>
            <a class="toc" href="obsresult_io03.php?obsresult_o1">Back to this observation</a></p>'; // Cannot go from i1 to o1 in i0n case.
        if (isset($_SESSION['Scratch']['t0obstopic']))
            $Message .= '<p>
            <a class="toc" href="obsresult_io03.php?Ot_all_Om_i0">Back to input all</a></p>';
        if (isset($_SESSION['Scratch']['t0obstopic']) and isset($_SESSION['Scratch']['t0obsmethod']))
            $Message .= '<p>
            <a class="toc" href="obsresult_io03.php?Om_all_Or_o1">Back to all observations for this method</a></p>';
        $Message .= '<p>
        <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
        <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['obsresult_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay, 0, FALSE, $_SESSION['Scratch']['t0obsresult']); // SPECIAL CASE: 3rd & 4th fields the defaults (no files just uploaded here), and 5th field needed because the selected row isn't in $_SESSION['Selected'] here.
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('obsresult_io03.php', 'obsresult_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c($_SESSION['Scratch']['t0obsresult']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Scratch']['t0obsresult']['c5who_is_editing']) == '[A new database row is being created.]') 
            $Zfpf->insert_sql_1s($DBMSresource, 't0obsresult', $ChangedRow);
        else {
            $Conditions[0] = array('k0obsresult', '=', $_SESSION['Scratch']['t0obsresult']['k0obsresult']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0obsresult', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Scratch']['t0obsresult'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        $Message = '<p>
        The draft report you were editing has been updated with your changes. It will remain a draft until it is issued by its report leader.</p>
        <p>
        <a class="toc" href="obsresult_io03.php?obsresult_o1">Back to this observation</a></p>';
        if (isset($_SESSION['Scratch']['t0obstopic']))
            $Message .= '<p>
            <a class="toc" href="obsresult_io03.php?Ot_all_Om_i0">Back to input all</a></p>';
        if (isset($_SESSION['Scratch']['t0obstopic']) and isset($_SESSION['Scratch']['t0obsmethod']))
            $Message .= '<p>
            <a class="toc" href="obsresult_io03.php?Om_all_Or_o1">Back to all observations for this method</a></p>';
        $Message .= '<p>
        <a class="toc" href="obsresult_io03.php?obsresult_i1m">Back to all topics</a></p><p>
        <a class="toc" href="audit_io03.php?audit_o1">Back to report intro</a></p>';
        echo $Zfpf->xhtml_contents_header_1c().$Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    
} // ends i1, i2, i3 code
$Zfpf->catch_all_1c('practice_o1.php');
$Zfpf->save_and_exit_1c();

