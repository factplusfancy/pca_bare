<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This special-case io03 file allows updating associations between rule divisions, fragments, and practices.
// See background in includes/fragment_practice_i1.php

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

// General security check
// Security token equal to "fragment_practice_i1.php" indicates user has Full Global and entity c5p_user privileges.
if (!isset($_SESSION['Selected']['k0practice']) or !isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'fragment_practice_i1.php' or ($_SESSION['Selected']['c2standardized'] != '[Nothing has been recorded in this field.]' and $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'StandardPractices'))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

if (isset($_POST['modify_confirm_post']) or isset($_POST['undo_confirm_post'])) {
    if (isset($_POST['undo_confirm_post'])) { // Stuff only needed for modify_confirm_post
        if (isset($_SESSION['Scratch']['PlainText']['NewAssociatedFrags']))
            unset($_SESSION['Scratch']['PlainText']['NewAssociatedFrags']);
        if (isset($_SESSION['Scratch']['PlainText']['NewAssociateDivision']))
            unset($_SESSION['Scratch']['PlainText']['NewAssociateDivision']);
        if (isset($_SESSION['Scratch']['PlainText']['NewDissociateDivision']))
            unset($_SESSION['Scratch']['PlainText']['NewDissociateDivision']);
    }
    if (isset($_SESSION['Scratch']['FragsToAssociate']))
        unset($_SESSION['Scratch']['FragsToAssociate']);
    if (isset($_SESSION['Scratch']['FragsToDissociate']))
        unset($_SESSION['Scratch']['FragsToDissociate']);
    if (isset($_SESSION['Scratch']['DivsToAssociate']))
        unset($_SESSION['Scratch']['DivsToAssociate']);
    if (isset($_SESSION['Scratch']['DivsToDissociate']))
        unset($_SESSION['Scratch']['DivsToDissociate']);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/fragment_practice_i1.php'; // Echos an exits
}

if (isset($_POST['fragment_practice_i2'])) {
    if (!isset($_SESSION['SelectResults']['t0fragment']) or !isset($_SESSION['Scratch']['PlainText']['OldAssociatedFrags']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
    $_SESSION['Scratch']['PlainText']['NewAssociatedFrags'] = $_SESSION['Scratch']['PlainText']['OldAssociatedFrags']; // Whatever is posted will overwrite NewAssociatedFrags.
    foreach ($_SESSION['SelectResults']['t0fragment'] as $K => $V) {
        if (isset($_POST[$K]) and !in_array($V['k0fragment'], $_SESSION['Scratch']['PlainText']['OldAssociatedFrags']) and !in_array($V['k0fragment'], $_SESSION['Scratch']['PlainText']['NewAssociatedFrags'])) {
            $_SESSION['Scratch']['PlainText']['NewAssociatedFrags'][] = $V['k0fragment']; // Avoid double insert, with Cheesehead division...
            // Assemble t0fragment_practice rows to insert, !in_array... verifies there's not one already.
            $_SESSION['Scratch']['FragsToAssociate'][] = array(
                'k0fragment_practice' => time().mt_rand(1000000, 9999999),
                'k0fragment' => $V['k0fragment'],
                'k0practice' => $_SESSION['Selected']['k0practice'],
                'c5who_is_editing' => $EncryptedNobody
            );
            $AssocHTML[] = '<br />- <b>'.$Zfpf->decrypt_1c($V['c5name']).'</b>'.' ('.$Zfpf->decrypt_1c($V['c5citation']).') '.$Zfpf->decrypt_1c($V['c6quote']);
        }
        if (!isset($_POST[$K]) and in_array($V['k0fragment'], $_SESSION['Scratch']['PlainText']['OldAssociatedFrags']) and in_array($V['k0fragment'], $_SESSION['Scratch']['PlainText']['NewAssociatedFrags'])) {
            // To avoid double delete, remove this fragment from $_SESSION['Scratch']['PlainText']['NewAssociatedFrags']
            $ArrayKey = array_search($V['k0fragment'], $_SESSION['Scratch']['PlainText']['NewAssociatedFrags']); // Already know it's in_array
            unset($_SESSION['Scratch']['PlainText']['NewAssociatedFrags'][$ArrayKey]);
            // Assemble only keys of t0fragment_practice rows to delete
            $_SESSION['Scratch']['FragsToDissociate'][] = array(
                'k0fragment' => $V['k0fragment']
            );
            $DissociateHTML[] = '<br /><b>- '.$Zfpf->decrypt_1c($V['c5name']).'</b>'.' ('.$Zfpf->decrypt_1c($V['c5citation']).') '.$Zfpf->decrypt_1c($V['c6quote']);
        }
    }
    $HTML = '<h1>
    Practice-to-Fragments Mapping</h1>
    <form action="fragment_practice_io03.php" method="post">';
    if (!isset($_SESSION['Scratch']['FragsToAssociate']) and !isset($_SESSION['Scratch']['FragsToDissociate']))
        $HTML .= '<p>
        <b>You did not change any information.</b></p><p>
        <input type="submit" name="undo_confirm_post" value="Go back and make changes" /></p>';
    else {
        $HTML .= '<p>
        <b>Confirm that the information you modified, listed below, is complete and correct.</b></p><p>
        Currently selected practice:<br />
        '.$Zfpf->entity_name_description_1c($_SESSION['Selected'], FALSE).'</p>';
        if (isset($_SESSION['Scratch']['FragsToAssociate'])) {
            $HTML .= '<p>
            <b>Associate the following rule fragments with the currently selected practice.</b>';
            foreach ($_SESSION['Scratch']['FragsToAssociate'] as $K => $V)
                $HTML .= $AssocHTML[$K];
            $HTML .= '</p>';
        }
        if (isset($_SESSION['Scratch']['FragsToDissociate'])) {
            $HTML .= '<p>
            <b>Dissociate the following rule fragments from the currently selected practice.</b>';
            foreach ($_SESSION['Scratch']['FragsToDissociate'] as $K => $V)
                $HTML .= $DissociateHTML[$K];
            $HTML .= '</p>';
        }
        $HTML .= '<p>
        <input type="submit" name="fragment_practice_i3" value="Confirm" /></p><p>
        <input type="submit" name="modify_confirm_post" value="Modify" /></p><p>
        <input type="submit" name="undo_confirm_post" value="Discard" /></p>'; // Match as possible format in ConfirmZfpf
    }
    $HTML .= '</form>';
    unset($_SESSION['SelectResults']);
    echo $Zfpf->xhtml_contents_header_1c().$HTML.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_POST['fragment_practice_i3'])) {
    if (!isset($_SESSION['Scratch']['FragsToAssociate']) and !isset($_SESSION['Scratch']['FragsToDissociate']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    if (isset($_SESSION['Scratch']['FragsToAssociate'])) {
        foreach ($_SESSION['Scratch']['FragsToAssociate'] as $V)
            $Zfpf->insert_sql_1s($DBMSresource, 't0fragment_practice', $V);
        unset($_SESSION['Scratch']['FragsToAssociate']);
        // fragment_practice_i1.php only gives option to associate fragments with a division with a t0practice_division row.
    }
    if (isset($_SESSION['Scratch']['FragsToDissociate'])) {
        $Conditions[0] = array('k0practice', '=', $_SESSION['Selected']['k0practice'], 'AND');
        foreach ($_SESSION['Scratch']['FragsToDissociate'] as $V) {
            $Conditions[1] = array('k0fragment', '=', $V['k0fragment']);
            list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment_practice', $Conditions);
            if ($RR != 1) // Check that conditions only return one row with a select, before deleting.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0fragment_practice', $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.$Affected);
        }
        unset($_SESSION['Scratch']['FragsToDissociate']);
    }
    $Zfpf->close_connection_1s($DBMSresource);
    unset($_SESSION['Selected']); // Redundant due to session_cleanup_1c() in administer1.php
    unset($_SESSION['Scratch']); // Redundant due to session_cleanup_1c() in administer1.php
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Record(s) Updated</h2><p>
    The practice-to-fragment associations you input and reviewed have been recorded.</p>
    <form action="administer1.php" method="post"><p>
        <input type="submit" value="Back to administration" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c(); // Hard to implement a "Go back" button to practice_io03.php
    $Zfpf->save_and_exit_1c();
}

if (isset($_POST['practice_division_i2'])) {
    if (!isset($_SESSION['SelectResults']['t0division']) or !isset($_SESSION['Scratch']['PlainText']['CannotDissociateDivision']) or !isset($_SESSION['Scratch']['PlainText']['CanDissociateDivision']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
    $_SESSION['Scratch']['PlainText']['NewAssociateDivision'] = array(); // Whatever is posted will overwrite.
    $_SESSION['Scratch']['PlainText']['NewDissociateDivision'] = array(); // Whatever is posted will overwrite.
    foreach ($_SESSION['SelectResults']['t0division'] as $K => $V) {
        if (in_array($V['k0division'], $_SESSION['Scratch']['PlainText']['CannotDissociateDivision']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__); // User didn't get option to alter these.
        $DivisionCitation = $Zfpf->decrypt_1c($V['c5citation']);
        if ($DivisionCitation == '[Nothing has been recorded in this field.]')
            $DivisionCitation = '';
        else
            $DivisionCitation = ' -- '.$DivisionCitation;
        if (isset($_POST[$K]) and !in_array($V['k0division'], $_SESSION['Scratch']['PlainText']['CanDissociateDivision'])) {
            $_SESSION['Scratch']['PlainText']['NewAssociateDivision'][] = $V['k0division']; // Use for modify
            // Assemble rows to insert, !in_array... verifies there's not one already.
            $_SESSION['Scratch']['DivsToAssociate'][] = array(
                'k0practice_division' => time().mt_rand(1000000, 9999999),
                'k0practice' => $_SESSION['Selected']['k0practice'],
                'k0division' => $V['k0division'],
                'c5who_is_editing' => $EncryptedNobody
            );
            $AssocHTML[] = '<br />- '.$Zfpf->decrypt_1c($V['c5name']).$DivisionCitation;
        }
        if (!isset($_POST[$K]) and in_array($V['k0division'], $_SESSION['Scratch']['PlainText']['CanDissociateDivision'])) {
            $_SESSION['Scratch']['PlainText']['NewDissociateDivision'][] = $V['k0division']; // Use for modify
            // Assemble only keys of rows to delete
            $_SESSION['Scratch']['DivsToDissociate'][] = array(
                'k0division' => $V['k0division']
            );
            $DissociateHTML[] = '<br />- '.$Zfpf->decrypt_1c($V['c5name']).$DivisionCitation;
        }
    }
    $HTML = '<h1>
    Practice-to-Divisions Mapping</h1>
    <form action="fragment_practice_io03.php" method="post">';
    if (!isset($_SESSION['Scratch']['DivsToAssociate']) and !isset($_SESSION['Scratch']['DivsToDissociate']))
        $HTML .= '<p>
        <b>You did not change any information.</b></p><p>
        <input type="submit" name="undo_confirm_post" value="Go back and make changes" /></p>';
    else {
        $HTML .= '<p>
        <b>Confirm that the information you modified, listed below, is complete and correct.</b></p><p>
        Currently selected practice:<br />
        '.$Zfpf->entity_name_description_1c($_SESSION['Selected'], FALSE).'</p>';
        if (isset($_SESSION['Scratch']['DivsToAssociate'])) {
            $HTML .= '<p>
            <b>Associate the following rule divisions with the currently selected practice.</b>';
            foreach ($_SESSION['Scratch']['DivsToAssociate'] as $K => $V)
                $HTML .= $AssocHTML[$K];
            $HTML .= '</p>';
        }
        if (isset($_SESSION['Scratch']['DivsToDissociate'])) {
            $HTML .= '<p>
            <b>Dissociate the following rule divisions from the currently selected practice.</b>';
            foreach ($_SESSION['Scratch']['DivsToDissociate'] as $K => $V)
                $HTML .= $DissociateHTML[$K];
            $HTML .= '</p>';
        }
        $HTML .= '<p>
        <input type="submit" name="practice_division_i3" value="Confirm" /></p><p>
        <input type="submit" name="modify_confirm_post" value="Modify" /></p><p>
        <input type="submit" name="undo_confirm_post" value="Discard" /></p>'; // Match as possible format in ConfirmZfpf
    }
    $HTML .= '</form>';
    unset($_SESSION['SelectResults']);
    echo $Zfpf->xhtml_contents_header_1c().$HTML.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_POST['practice_division_i3'])) {
    if (!isset($_SESSION['Scratch']['DivsToAssociate']) and !isset($_SESSION['Scratch']['DivsToDissociate']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    if (isset($_SESSION['Scratch']['DivsToAssociate'])) {
        foreach ($_SESSION['Scratch']['DivsToAssociate'] as $V)
            $Zfpf->insert_sql_1s($DBMSresource, 't0practice_division', $V);
        unset($_SESSION['Scratch']['DivsToAssociate']);
    }
    if (isset($_SESSION['Scratch']['DivsToDissociate'])) {
        $Conditions[0] = array('k0practice', '=', $_SESSION['Selected']['k0practice'], 'AND');
        foreach ($_SESSION['Scratch']['DivsToDissociate'] as $V) {
            $Conditions[1] = array('k0division', '=', $V['k0division']);
            list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0practice_division', $Conditions);
            if ($RR != 1) // Check that conditions only return one row with a select, before deleting.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0practice_division', $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.$Affected);
        }
        unset($_SESSION['Scratch']['DivsToDissociate']);
    }
    $Zfpf->close_connection_1s($DBMSresource);
    unset($_SESSION['Selected']); // Redundant due to session_cleanup_1c() in administer1.php
    unset($_SESSION['Scratch']); // Redundant due to session_cleanup_1c() in administer1.php
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Record(s) Updated</h2><p>
    The practice-to-division associations you input and reviewed have been recorded.</p>
    <form action="administer1.php" method="post"><p>
        <input type="submit" value="Back to administration" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c(); // Hard to implement a "Go back" button to practice_io03.php
    $Zfpf->save_and_exit_1c();
}

$Zfpf->catch_all_1c('administer1.php');
$Zfpf->save_and_exit_1c();

