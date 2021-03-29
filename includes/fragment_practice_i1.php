<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles mapping practices to rule fragments and divisions. 
// It echos a checkbox list of fragments, organized by division, with ones currently linked to the practice checked.
// It also echos a checkbox list of divisions, with a division checked when a practice is associated with it but not to any fragments within it. These are the divisions that may be dissociated from a practice (the ones whose fragments have already been dissociated from the practice.)
// Divisions are included here because they are groups of rule fragments. This app divides: a rule into divisions and divisions into fragments.

// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'fragment_practice_i1.php') {
    // $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] is set in practice_io03.php
    if (!isset($_SESSION['Scratch']['PlainText']['FragPracPrivileges']) or 
        ($_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'StandardPractices' and $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'CustomPractices') or 
        ($_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'StandardPractices' and !isset($_SESSION['Scratch']['PlainText']['SecurityToken'])) or 
        ($_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'StandardPractices' and $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'practice_io03.php?process' and $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'practice_io03.php?facility' and $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'practice_io03.php?owner' and $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'practice_io03.php?contractor'))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'fragment_practice_i1.php'; // Set new security token.
}

$_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
    'practice_fragment' => 'Fragments',
    'practice_division' => 'Divisions'
);

// Needs to be reset here
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes' and $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF) // Only app admins with full privieges can edit template practices.
    $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] = 'StandardPractices'; // Needed in includes/fragment_practice_i1.php

if (!isset($_SESSION['Selected']['k0practice'])) { // Get selected practice. practice_io03.php created $_SESSION['SelectResults']['t0practice']
    $CheckedPost = $Zfpf->post_length_blank_1c('selected');
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0practice'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Selected'] = $_SESSION['SelectResults']['t0practice'][$CheckedPost];
    unset($_SESSION['SelectResults']['t0practice']);
}
$HTML = '<h1>
Practice-to-Requirements Mapping</h1><p>
Currently selected practice:<br />';
if ($_SESSION['Selected']['c2standardized'] == '[Nothing has been recorded in this field.]')
    $HTML .= 'Custom practice -- ';
else {
    $HTML .= $_SESSION['Selected']['c2standardized'].' -- ';
    if ($_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'StandardPractices')
        $HTML .=  'You don\'t have updating privileges. Only app admins can update standard practices, like this one. Either go back and update this practice, to convert it into a custom practice, or ask an app admin to update the standard practice.<br />';
}
$HTML .= $Zfpf->entity_name_description_1c($_SESSION['Selected'], FALSE).'</p>';

$i = 0;
$_SESSION['Scratch']['PlainText']['OldAssociatedFrags'] = array(); // Start new and query database again each pass.
$_SESSION['Scratch']['PlainText']['CannotDissociateDivision'] = array();
$_SESSION['Scratch']['PlainText']['CanDissociateDivision'] = array();
$Conditions[0] = array('k0practice', '=', $_SESSION['Selected']['k0practice']);
$DBMSresource = $Zfpf->credentials_connect_instance_1s();
list($SR['t0fragment_practice'], $RR['t0fragment_practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment_practice', $Conditions);
if ($RR['t0fragment_practice']) foreach ($SR['t0fragment_practice'] as $V)
    $_SESSION['Scratch']['PlainText']['OldAssociatedFrags'][] = $V['k0fragment'];
else
    $HTML .= '<p>The selected practice is not associated with any requirements, aka <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a>.</p>';
if (isset($_POST['modify_confirm_post']) and isset($_SESSION['Scratch']['PlainText']['NewAssociatedFrags']))
    $KeysOfFragsToCheck = $_SESSION['Scratch']['PlainText']['NewAssociatedFrags'];
else
    $KeysOfFragsToCheck = $_SESSION['Scratch']['PlainText']['OldAssociatedFrags'];
// k0division 1 to 13 (first twelve) are the Cheesehead division method, which duplicates the PSM fragments but in a simpler division method.
list($SR['t0practice_division'], $RR['t0practice_division']) = $Zfpf->select_sql_1s($DBMSresource, 't0practice_division', $Conditions);
// Typically, no need to sort divisions because templates/practice_division.php and practice_io03.php embed good sorting in the primary key.
if ($RR['t0practice_division']) {
    $HTML .= '<h2><a id="practice_fragment"></a>
    Practice-to-Fragments Mapping</h2><p>
    The selected practice is associated with the divisions below and also any selected requirements, aka <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a>, below. <b>For similar rule fragments in different divisions (only difference may be the rule citation), if a practice is associated with one, it\'s best to associate it will all of these similar fragments.</b> The app automatically does this (<b>when disassociating and associating</b>) for fragments associated with a Cheesehead division, because the Cheesehead division method is only a simpler way of organizing the PSM and CAP fragments that typically need practices for compliance, so not including things like definitions.</p>
    <form action="fragment_practice_io03.php" method="post">';
     foreach ($SR['t0practice_division'] as $VA) {
        $FragPracInDivision = 0;
        $Conditions[0] = array('k0division', '=', $VA['k0division']);
        list($SR['t0division'], $RR['t0division']) = $Zfpf->select_sql_1s($DBMSresource, 't0division', $Conditions);
        if ($RR['t0division'] != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        list($SR['t0fragment_division'], $RR['t0fragment_division']) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment_division', $Conditions);
        $Conditions[0] = array('k0rule', '=', $SR['t0division'][0]['k0rule']);
        list($SR['t0rule'], $RR['t0rule']) = $Zfpf->select_sql_1s($DBMSresource, 't0rule', $Conditions);
        if ($RR['t0rule'] != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $HTML .= '<p>
        <b>'.$Zfpf->decrypt_1c($SR['t0division'][0]['c5name']).' -- '.$Zfpf->decrypt_1c($SR['t0rule'][0]['c5name']).'</b></p><p>';
        if ($RR['t0fragment_division']) foreach ($SR['t0fragment_division'] as $VB) {
            $Conditions[0] = array('k0fragment', '=', $VB['k0fragment']);
            list($SR['t0fragment'], $RR['t0fragment']) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment', $Conditions);
            if ($RR['t0fragment'] != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['SelectResults']['t0fragment']['frag_'.$i] = $SR['t0fragment'][0];
            $HTML .= '<input type="checkbox" value="Yes" name="frag_'.$i++.'" ';
            if (in_array($SR['t0fragment'][0]['k0fragment'], $KeysOfFragsToCheck))
                $HTML .= 'checked ';
            $HTML .= '/><b>'.$Zfpf->decrypt_1c($SR['t0fragment'][0]['c5name']).'</b>'.' ('.$Zfpf->decrypt_1c($SR['t0fragment'][0]['c5citation']).') '.$Zfpf->decrypt_1c($SR['t0fragment'][0]['c6quote']).'<br />';
            if (in_array($SR['t0fragment'][0]['k0fragment'], $_SESSION['Scratch']['PlainText']['OldAssociatedFrags']))
                $FragPracInDivision++; // Based on associations recorded in database, not unconfirmed user input.
        }
        else
            $HTML .= '<br />- No fragments are associated with this division. Typically, these associations are made by the template files when the app is initially setup. Contact an app admin for assistance.';
        $HTML .= '</p>';
        if ($FragPracInDivision)
            $_SESSION['Scratch']['PlainText']['CannotDissociateDivision'][] = $SR['t0division'][0]['k0division'];
        else
            $_SESSION['Scratch']['PlainText']['CanDissociateDivision'][] = $SR['t0division'][0]['k0division'];
    }
    if (isset($_SESSION['SelectResults']['t0fragment'])) {
        if ($_SESSION['Selected']['c2standardized'] == '[Nothing has been recorded in this field.]' or $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] == 'StandardPractices')
            $HTML .= '<p>
            <input type="submit" name="fragment_practice_i2" value="Review fragment selections" /></p>';
        else
            $HTML .= '<p>
            Only app admins can update standard practices, like the selected practice. Either go back and update this practice, to convert it into a custom practice, or ask an app admin to update the standard practice.</p>';
    }
    $HTML .= '<p>
    * * * *</p>
    </form>'; // Create some space on HTML before division form starts.
}
else
    $HTML .= '<p>The selected practice is not associated with any divisions of a <a class="toc" href="glossary.php#rule" target="_blank">rule</a> in the app.</p>';

// Practice-to-Divisions Mapping -- include here so all practice mapping is on one HTML form.
unset($SR);
unset($RR);
$i = 0;
$DivisionHTML = '';
list($SR['t0rule'], $RR['t0rule']) = $Zfpf->select_sql_1s($DBMSresource, 't0rule', 'No Condition -- All Rows Included');
if ($RR['t0rule']) foreach ($SR['t0rule'] as $VA) {
    $DivisionHTML .= '<p>
    <b>'.$Zfpf->decrypt_1c($VA['c5name']).'</b>';
    $Conditions[0] = array('k0rule', '=', $VA['k0rule']);
    list($SR['t0division'], $RR['t0division']) = $Zfpf->select_sql_1s($DBMSresource, 't0division', $Conditions);
    if ($RR['t0division']) foreach ($SR['t0division'] as $VB) {
        if (!in_array($VB['k0division'], $_SESSION['Scratch']['PlainText']['CannotDissociateDivision'])) {
            $_SESSION['SelectResults']['t0division']['div_'.$i] = $VB;
            $DivisionHTML .= '<br /><input type="checkbox" value="Yes" name="div_'.$i++.'" ';
            if ((in_array($VB['k0division'], $_SESSION['Scratch']['PlainText']['CanDissociateDivision']) and (!isset($_SESSION['Scratch']['PlainText']['NewDissociateDivision']) or !in_array($VB['k0division'], $_SESSION['Scratch']['PlainText']['NewDissociateDivision']))) or (isset($_SESSION['Scratch']['PlainText']['NewAssociateDivision']) and in_array($VB['k0division'], $_SESSION['Scratch']['PlainText']['NewAssociateDivision'])))
                $DivisionHTML .= 'checked ';
            $DivisionHTML .= '/>'.$Zfpf->decrypt_1c($VB['c5name']);
            $DivisionCitation = $Zfpf->decrypt_1c($VB['c5citation']);
            if ($DivisionCitation != '[Nothing has been recorded in this field.]')
                $DivisionHTML .= ' -- '.$DivisionCitation;
        }
    }
    $DivisionHTML .= '</p>';
}
$HTML .= '<h2><a id="practice_division"></a>
Practice-to-Divisions Mapping</h2>';
if ($DivisionHTML) {
    $HTML .= '<p>
    To associate a practice with fragments in other rules or divisions, first associate it with the division that contains this fragment.<br />
     - The unchecked rule divisions below are in the app but not associated with the selected practice.<br />
     - The checked rule division below may be dissociated from the selected practice because none of their fragments are associated with this practice. See above.</p>
    <form action="fragment_practice_io03.php" method="post">
    '.$DivisionHTML;
    if ($_SESSION['Selected']['c2standardized'] == '[Nothing has been recorded in this field.]' or $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] == 'StandardPractices')
        $HTML .= '<p>
        <input type="submit" name="practice_division_i2" value="Review division selections" /></p>';
    else
        $HTML .= '<p>
        Only app admins can update standard practices, like the selected practice. Either go back and update this practice, to convert it into a custom practice, or ask an app admin to update the standard practice.</p>';
    $HTML .= '
    </form>';
}
else
    $HTML .= '<p>
    There are no more rule divisions, in the app and not already associated with the selected practice.</p>';
$Zfpf->close_connection_1s($DBMSresource);
echo $Zfpf->xhtml_contents_header_1c().$HTML.'
<form action="administer1.php" method="post"><p>
    <input type="submit" value="Back to administration" /></p>
</form>
'.$Zfpf->xhtml_footer_1c(); // Hard to implement a "Go back" button to practice_io03.php
$Zfpf->save_and_exit_1c();

