<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This o1 file outputs an HTML form to select a practice associated with an entity and then display it and, if applicable, to:
// - display the practice history,
// - create an entity-specific practice, or
// - to edit an entity-specific practice (if needed after creating an entity-specific practice to replace a standard practice 
// because standard practices cannot be edited.)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

if (isset( $_POST['fragment_practice_i1']))
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/fragment_practice_i1.php'; // Echos an exits
                
// SPECIAL CASE, put here to allow modifying in security check below. Otherwise complex $_POST if clauses would have to be repeated.
// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('<a id="c5name"></a>Practice name (what most people call it often works well)', REQUIRED_FIELD_ZFPF),
    'c6description' => array('Description', '', C6SHORT_MAX_BYTES_ZFPF), // intentionally not in schema order.
    'c2standardized' => array('Scope of app-standard practice. Not applicable to customized practices -- for them, set to [Nothing has been recorded in this field.] App admins may change app-standard practices', '', C5_MAX_BYTES_ZFPF, 'app_assigned'), // redefined for practice_templates code below.
    'c5number' => array('Practice number. Used for sorting practices. Defaults to ten increments above any selected practice.', REQUIRED_FIELD_ZFPF),
    'c5require_file' => array('Filename of implementing-computer code. Changes to practices beyond their name, number, or description require updating computer code and so creating a new version of the app. Often easier to keep the same filename and just change in code it stores', ''),
    'c5require_file_privileges' => array(
        'Minimum global-DBMS privileges needed to use any implementing-computer code. If there is no implementing-computer code, select [Nothing has been recorded in this field.]', 
        '', 
        C5_MAX_BYTES_ZFPF, 
        'dropdown',
        array(
            '[Nothing has been recorded in this field.]',
            LOW_PRIVILEGES_ZFPF,
            MID_PRIVILEGES_ZFPF,
            MAX_PRIVILEGES_ZFPF
        )
    )
);

// General security check.
// Same privileges needed to get link from administer1.php
// Also, discern between privileges of the current user and give them the choice with the maximum information.
if (isset($_GET['process']) or (isset($_SESSION['Scratch']['PlainText']['SecurityToken']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] == 'practice_io03.php?process')) {
    if (!isset($_SESSION['t0user_process']) or !isset($_SESSION['t0user_facility']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'practice_io03.php?process';
    $Scope = 'Process-wide';
    $EntityName = $Zfpf->affected_entity_info_1c($Scope, $_SESSION['t0user_process']['k0process'])['AEFullDescription'];
    $TableRoot = 'process';
}
elseif (isset($_GET['facility']) or (isset($_SESSION['Scratch']['PlainText']['SecurityToken']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] == 'practice_io03.php?facility')) {
    if (!isset($_SESSION['t0user_facility']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'practice_io03.php?facility';
    $Scope = 'Facility-wide';
    $EntityName = $Zfpf->affected_entity_info_1c($Scope, $_SESSION['t0user_facility']['k0facility'])['AEFullDescription'];
    $TableRoot = 'facility';
}
elseif (isset($_GET['owner']) or (isset($_SESSION['Scratch']['PlainText']['SecurityToken']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] == 'practice_io03.php?owner')) {
    if (!isset($_SESSION['t0user_owner']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'practice_io03.php?owner';
    $Scope = 'Owner-wide';
    $EntityName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
    $TableRoot = 'owner';
}
elseif (isset($_GET['contractor']) or (isset($_SESSION['Scratch']['PlainText']['SecurityToken']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] == 'practice_io03.php?contractor')) {
    if (!isset($_SESSION['t0user_contractor']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'practice_io03.php?contractor';
    $Scope = 'Contractor-wide';
    $EntityName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']);
    $TableRoot = 'contractor';
}
elseif (isset($_GET['practice_templates_i1m']) or isset($_POST['practice_templates_i1m']) or isset($_POST['practice_templates_i1']) or isset($_POST['practice_templates_modify_confirm_post_1e']) or isset($_POST['practice_templates_undo_confirm_post_1e']) or isset($_POST['practice_templates_i2']) or isset($_POST['practice_templates_yes_confirm_post_1e'])) {
    if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes' or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF) // Only app admins with full privieges can edit template practices.
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // SPECIAL CASE: modify $htmlFormArray to allow updating scope of template practice. Do here so available to all practice_templates code.
    $htmlFormArray['c2standardized'] = array('Scope of app-standard practice.', '', C5_MAX_BYTES_ZFPF, 'radio', array('Owner Standard Practice', 'Contractor Standard Practice', 'Facility Standard Practice', 'Process Standard Practice')); // Templates must have a scope.
    $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] = 'StandardPractices'; // Needed below
}
else
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);


// practice_templates_i1m code -- GET because coming from link in administer1.php
if (isset($_GET['practice_templates_i1m']) or isset($_POST['practice_templates_i1m'])) {
    if (!isset($_SESSION['Scratch']['PlainText']['FragPracPrivileges']) or $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'StandardPractices')
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Message = '<h2>
    Standard practices in this deployment of the app.</h2><p>
    These standard practices are stored in the particular database that serves a deployment of the app, so updating a standard practice is a choice that needs to be made for each deployment, which might affect multiple Owner/Operators. An app admin can implement this choice.</p>';
    $Conditions[0] = array('k0practice', '<', 100000);
    list($_SESSION['SelectResults']['t0practice'], $RR['t0practice']) = $Zfpf->one_shot_select_1s('t0practice', $Conditions);
    if ($RR['t0practice']) {
        $Message .= '
        <form action="practice_io03.php" method="post"><p>';
        foreach ($_SESSION['SelectResults']['t0practice'] as $K => $V)
            $PracticeNumber[$K] = $Zfpf->decrypt_1c($V['c5number']);
        array_multisort($PracticeNumber, $_SESSION['SelectResults']['t0practice']); // sort by t0practice:c5number
        foreach ($_SESSION['SelectResults']['t0practice'] as $K => $V) {
            $NameDescription = $Zfpf->entity_name_description_1c($V);
            if ($K)
                $Message .= '<br />';
            $Message .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if (!$K)
                $Message .= 'checked="checked" '; // Ensure something is posted.
            $Message .= '/>'.$NameDescription;
        }
        $Message .= '</p><p>
        <b>WARNING: updating a standard practice affects everyone using this deployment of the app.</b> First confirm, typically in writing, from an authorized representative of all Owner/Operators using this deployment of the app that they want to commit themselves to following the updated practice. If all don\'t agree, some Owner/Operators can update the practice for their entities only, under their administration page in the app.</p><p>
            <input type="submit" name="practice_templates_i1" value="Update selected practice" /></p><p>
            Update associations with the <a class="toc" href="glossary.php#psm" target="_blank">PSM</a> or <a class="toc" href="glossary.php#cap" target="_blank">CAP</a> requirements (aka <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a>) that the selected practice helps meet.<br />
            See WARNING above.<br />
            <input type="submit" name="fragment_practice_i1" value="Alter requirement mapping" /></p>
        </form>';
    }
    else
        $Message .= '<p>No standard practices were found. This is unusual because they are normally installed with the app and are not deleted when a practice is changed to a custom practice.</p>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.'
    <form action="administer1.php" method="post"><p>
        <input type="submit" value="Back to administration" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// practice_templates_i1 code (skip o1 code because practice_o1.php displays most of contents of a t0practice row.)
if (isset($_POST['practice_templates_i1'])) {
    // Get the user selection (typically hanled by o1 code)
    $CheckedPost = $Zfpf->post_length_blank_1c('selected');
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0practice'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Selected'] = $_SESSION['SelectResults']['t0practice'][$CheckedPost];
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
    // SPECIAL CASE: Not practical to edit_lock at app level, especially if require_file code is being updated.
    // SPECIAL CASE: no need for $_SESSION['Scratch']['htmlFormArray']
    $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($Display);
    $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
}
// SPECIAL CASE: HTML input buttons named 'practice_templates_undo_confirm_post_1e' and 'practice_templates_modify_confirm_post_1e' are generated by ConfirmZfpf::post_select_required_compare_confirm_1e
// 1.2 $_SESSION['Scratch']['SelectDisplay'] is source of $Display, the version generated from the database record.
elseif (isset($_POST['practice_templates_undo_confirm_post_1e']) and isset($_SESSION['Scratch']['SelectDisplay'])) {
    $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
    $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
}
// 1.3 $_SESSION['Post'] is source of $Display, the latest user-modified version.
elseif (isset($_SESSION['Post']) and isset($_POST['practice_templates_modify_confirm_post_1e']))
    $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
// finish practice_templates_i1 code
if (isset($Display) and (isset($_POST['practice_templates_i1']) or isset($_POST['practice_templates_undo_confirm_post_1e']) or isset($_POST['practice_templates_modify_confirm_post_1e']))) { // SPECIAL CASE -- repeat $_POST conditions to distiguish from non-template i1 code below.
    if (!isset($_SESSION['Scratch']['PlainText']['FragPracPrivileges']) or $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'StandardPractices')
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
	Practice Summary</h1>
    <form action="practice_io03.php" method="post">';
    echo $Zfpf->make_html_form_1e($htmlFormArray, $Display);
    echo '<p>
	    <input type="submit" name="practice_templates_i2" value="Review what you typed into form" /></p><p>
        <input type="submit" name="practice_templates_i1m" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
// practice_templates_i2 code, implements the review and confirmation HTML page.
if (isset($_POST['practice_templates_i2']) and isset($_SESSION['Post'])) {
    if (!isset($_SESSION['Scratch']['PlainText']['FragPracPrivileges']) or $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'StandardPractices')
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
    $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
    $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
    if ($PostDisplay['c5require_file'] == '[Nothing has been recorded in this field.]' and $PostDisplay['c5require_file_privileges'] != '[Nothing has been recorded in this field.]') {
        $RFMessage = '<p>
        Because the practice doesn\'t have implementing-computer code, the "global-DBMS privileges needed to use any implementing-computer code" must be set to: [Nothing has been recorded in this field.]</p>';
        $PostDisplay['c5require_file_privileges'] = '[Nothing has been recorded in this field.]';
    }
    elseif ($PostDisplay['c5require_file'] != '[Nothing has been recorded in this field.]' and $PostDisplay['c5require_file_privileges'] == '[Nothing has been recorded in this field.]')
        $RFMessage = '<p>
        Because the practice will include implementing-computer code, the "global-DBMS privileges needed to use any implementing-computer code" <b>cannot</b> be set to: [Nothing has been recorded in this field.]</p>';
    $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay); // Save user-posted info after checked username and password included.
    if (isset($RFMessage)) {
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
	    Practice Summary</h1>
        <form action="practice_io03.php" method="post">
        '.$RFMessage.'<p>
	        <input type="submit" name="practice_templates_modify_confirm_post_1e" value="Go back and fix" /></p><p>
	        <input type="submit" name="practice_templates_undo_confirm_post_1e" value="Discard what you just typed" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    echo $Zfpf->post_select_required_compare_confirm_1e('practice_io03.php', 'practice_io03.php', $htmlFormArray, $PostDisplay, $SelectDisplay, 'practice_templates_', 'practice_templates_');
    $Zfpf->save_and_exit_1c();
}
// practice_templates_i3 code
if (isset($_POST['practice_templates_yes_confirm_post_1e'])) {
    if (!isset($_SESSION['Post']) or !isset($_SESSION['Scratch']['PlainText']['FragPracPrivileges']) or $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] != 'StandardPractices')
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $ChangedRow = $Zfpf->changes_from_post_1c();
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    // SPECIAL CASE: no i0n code. Make it would requires inserting rows into t0fragment_practice and t0practice_division, per user selections.
    $Conditions[0] = array('k0practice', '=', $_SESSION['Selected']['k0practice']);
    $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0practice', $ChangedRow, $Conditions);
    if ($Affected != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
    $Zfpf->close_connection_1s($DBMSresource);
    unset($_SESSION['Selected']);
    unset($_SESSION['Post']);
    echo $Zfpf->xhtml_contents_header_1c('Training').'<p>
    The updates you just reviewed have been recorded. Please review the practice (including any required files) to verify.</p>
    <form action="practice_io03.php" method="post"><p>
        <input type="submit" name="practice_templates_i1m" value="Back to practices list" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// Below is code for everything but editing standard practices.
if (!isset($TableRoot)) // See general security check above.
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

$FullGlobalP = FALSE;
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF)
    $FullGlobalP = TRUE;
$IUPrivileges = FALSE;
if ($FullGlobalP and $Zfpf->decrypt_1c($_SESSION['t0user_'.$TableRoot]['c5p_user']) == MAX_PRIVILEGES_ZFPF) {
    $IUPrivileges = TRUE;
    $_SESSION['Scratch']['PlainText']['FragPracPrivileges'] = 'CustomPractices'; // Needed in includes/fragment_practice_i1.php
}

// i0m equivalent code
if (!$_POST or isset($_POST['practice_i0m'])) {
    if (isset($_SESSION['Selected']))
        unset($_SESSION['Selected']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    $Conditions[0] = array('k0'.$TableRoot, '=', $_SESSION['t0user_'.$TableRoot]['k0'.$TableRoot]);
    list($SREP, $RREP) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$TableRoot.'_practice', $Conditions);
    if ($RREP) {
        foreach ($SREP as $K => $V) {
            $Conditions[0] = array('k0practice', '=', $V['k0practice']);
            list($SRP, $RRP) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions);
            if ($RRP != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' t0'.$TableRoot.'_practice for '.$TableRoot.' primary key '.$k0TR.' holds k0practice: '.$V['k0practice'].', which is not in t0practice.');
            $PracticeNumber[$K] = $Zfpf->decrypt_1c($SRP[0]['c5number']);
            $_SESSION['SelectResults']['t0practice'][$K] = $SRP[0];
        }
        array_multisort($PracticeNumber, $_SESSION['SelectResults']['t0practice']); // sort by t0practice:c5number
    }
    $Zfpf->close_connection_1s($DBMSresource);
    $Message = '<h2>
    Practices directly associated with '.$EntityName.'</h2><p>
    The practices of any associated entities are <b>not</b> listed below. To view them, first select the entity you want to view (owner, contractor, facility, or process), then, on the administration page, click on the practices link for that entity.</p>
    <form action="practice_io03.php" method="post">';
    if (isset($_SESSION['SelectResults']['t0practice'])) {
        $Message .= '<p>';
        foreach ($_SESSION['SelectResults']['t0practice'] as $K => $V) {
            $NameDescription = $Zfpf->entity_name_description_1c($V, FALSE); // Don't shorten description here.
            if ($K)
                $Message .= '<br />';
            $Message .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if (!$K)
                $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
            $Message .= '/>'.$NameDescription;
        }
        $Message .= '</p>';
        if ($IUPrivileges) {
            $Message .= '<p>
            <input type="submit" name="practice_i1" value="Update selected practice" /></p><p>
            Updating converts a standard practice to a custom practice. For a custom practice, you may also alter associations with the <a class="toc" href="glossary.php#psm" target="_blank">PSM</a> or <a class="toc" href="glossary.php#cap" target="_blank">CAP</a> requirements (aka <a class="toc" href="glossary.php#fragment" target="_blank">rule fragments</a>) that the selected practice helps meet. Only app admins can update or alter associations for standard practices.<br />
            <input type="submit" name="fragment_practice_i1" value="Alter requirement mapping" /></p><p>
            Insert a new practice, below the selected practice, and <b>associated with same divisions and fragments as the selected practice.</b><br />
            <input type="submit" name="practice_i0n" value="Insert new practice" /></p>';
            if ($TableRoot == 'process')
                $Message .= '<p>
                Widen scope of selected practice to facility-wide.<br />
                <input type="submit" name="facility_wide_1" value="Widen scope" /></p>';
            elseif ($TableRoot == 'facility' and isset($_SESSION['t0user_owner']))
                $Message .= '<p>
                Widen scope of selected practice to owner-wide.<br />
                <input type="submit" name="owner_wide_1" value="Widen scope" /></p>';
            $Message .= '<p>
            <input type="submit" name="practice_separate_1" value="Separate practice from '.$TableRoot.'" /></p>';
        }
    }
    else {
        $Message .= '<p>
        <b>None found</b> for '.$EntityName.'. Please contact your supervisor if this seems amiss.</p>';
        if ($IUPrivileges)
            $Message .= '<p>
            <input type="submit" name="practice_i0n2" value="Insert a new practice" /></p>';
    }
    if (!$IUPrivileges)
        $Message .= '<p>
        <b>Practice-Privileges Notice</b>: You don\'t have privileges to update or create practices.</p>';
    $Message .= '
    </form>';
    echo $Zfpf->xhtml_contents_header_1c().$Message.'
    <form action="administer1.php" method="post"><p>
        <input type="submit" value="Back to administration" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_POST['owner_wide_1']) and !isset($_SESSION['t0user_owner']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

if (isset($_POST['practice_i1']) or isset($_POST['practice_i0n']) or isset($_POST['facility_wide_1']) or isset($_POST['owner_wide_1']) or isset($_POST['practice_separate_1'])) {
    $CheckedPost = $Zfpf->post_length_blank_1c('selected');
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0practice'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if ($_SESSION['SelectResults']['t0practice'][$CheckedPost]['c2standardized'] != '[Nothing has been recorded in this field.]' or isset($_POST['practice_i0n'])) { // Either (1) inserting a new practice or (2) updating/scope-widening/separating but the current practice is a standard one. If (2), may need to insert a new practice that will be an edited version of the standard practice because standard practices cannot be edited; they are referenced by many entities.
        $PracticeNumber = $Zfpf->decrypt_1c($_SESSION['SelectResults']['t0practice'][$CheckedPost]['c5number']); // Even new rows need this.
        if (!isset($_POST['practice_separate_1'])) {
            // Don't increment the practice number if separating the practice.
            ++$PracticeNumber;
            ++$PracticeNumber;
            ++$PracticeNumber;
            ++$PracticeNumber;
            ++$PracticeNumber;
            ++$PracticeNumber;
            ++$PracticeNumber;
            ++$PracticeNumber;
            ++$PracticeNumber;
            ++$PracticeNumber; // New number defaults to ten increments above the selected practice's number. There are about 17,500 increments (26^3) between each c5number for template practices, see templates/practices.php.
        }
        if (isset($_POST['practice_i0n'])) { // i0n code -- only creating a new practice
            $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
            $_SESSION['Selected'] = array(
                'k0practice' => time().mt_rand(1000000, 9999999),
                'c5name' => $EncryptedNothing,
                'c2standardized' => '[Nothing has been recorded in this field.]',
                'c5number' => $Zfpf->encrypt_1c($PracticeNumber),
                'c6description' => $EncryptedNothing,
                'c5require_file' => $EncryptedNothing,
                'c5require_file_privileges' => $EncryptedNothing,
                'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
            );
            // Below holds k0practice of the selected practice after which the new practice will be inserted. See o1 code.
            $_SESSION['Scratch']['PlainText']['InsertAfterPracticeKey'] = $_SESSION['SelectResults']['t0practice'][$CheckedPost]['k0practice'];
            if (isset($_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey']))
                unset($_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey']);
        }
        else { // Changing from standard to custom practice for updating or scope-widening. Or, separating form a standard practice.
            $_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey'] = $_SESSION['SelectResults']['t0practice'][$CheckedPost]['k0practice'];
            if (isset($_SESSION['Scratch']['PlainText']['InsertAfterPracticeKey']))
                unset($_SESSION['Scratch']['PlainText']['InsertAfterPracticeKey']);
            $_SESSION['Selected'] = array( 
                'k0practice' => time().mt_rand(1000000, 9999999),
                'c5name' => $_SESSION['SelectResults']['t0practice'][$CheckedPost]['c5name'],
                'c2standardized' => '[Nothing has been recorded in this field.]',
                'c5number' => $Zfpf->encrypt_1c($PracticeNumber),
                'c6description' => $_SESSION['SelectResults']['t0practice'][$CheckedPost]['c6description'],
                'c5require_file' => $_SESSION['SelectResults']['t0practice'][$CheckedPost]['c5require_file'],
                'c5require_file_privileges' => $_SESSION['SelectResults']['t0practice'][$CheckedPost]['c5require_file_privileges'],
                'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
            );
            if (isset($_POST['practice_separate_1']))
                $_SESSION['Selected']['c2standardized'] = $_SESSION['SelectResults']['t0practice'][$CheckedPost]['c2standardized'];
        }
    }
    else // updating/scope-widening/separating a custom practice
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0practice'][$CheckedPost]; // Current practice non-standard, so can just update it.
    unset($_SESSION['SelectResults']['t0practice']);
}

if (isset($_POST['practice_i0n2'])) { // i0n2 code -- only creating a new practice, for entity with no prior practices.
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $_SESSION['Selected'] = array(
        'k0practice' => time().mt_rand(1000000, 9999999),
        'c5name' => $EncryptedNothing,
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('TTTAAA'), // Arbitrary, towards end of alphabet, formatted as in templates/practices.php. See also templates/divisions.php, which describes how practices are numbered with prefixes AAA to MMM matching the 13 divisions of the Cheesehead division method.
        'c6description' => $EncryptedNothing,
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// facility_wide_1, owner_wide_1, practice_separate_1, i1 (remainder), i2, and i3 code
if (isset($_SESSION['Selected']['k0practice'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0practice', '=', $_SESSION['Selected']['k0practice']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0practice', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    // Additional security check
    if (!$IUPrivileges)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject

    // Get useful variables
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);

    if (isset($_POST['facility_wide_1']) or isset($_POST['owner_wide_1']) or isset($_POST['practice_separate_1'])) {
        // Use o1 code equivalent as confirmation page.
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        $Display['c2standardized'] = $_SESSION['Selected']['c2standardized']; // SPECIAL CASE: no encryption.
        if (isset($_POST['facility_wide_1'])) {
            $ActionText = 'scope increase to facility-wide';
            $InputName = 'facility_wide_2';
        }
        if (isset($_POST['owner_wide_1'])) {
            $ActionText = 'scope increase to owner-wide';
            $InputName = 'owner_wide_2';
        }
        if (isset($_POST['practice_separate_1'])) {
            $ActionText = 'separating practice from '.$TableRoot;
            $InputName = 'practice_separate_2';
        }
        $ConfirmationText = '<h2>
        Confirm '.$ActionText.' for the following practice.</h2>
        '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display);
        echo $Zfpf->xhtml_contents_header_1c().$ConfirmationText.'
        <form action="practice_io03.php" method="post"><p>
            Confirm '.$ActionText.'.<br />
		    <input type="submit" name="'.$InputName.'" value="Confirm" /></p><p>
            <input type="submit" name="practice_i0m" value="Take no action -- go back" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $_SESSION['Scratch']['ActionText'] = $Zfpf->encrypt_1c($ActionText);
        $_SESSION['Scratch']['ConfirmationText'] = $Zfpf->encrypt_1c($ConfirmationText);
        $Zfpf->save_and_exit_1c();
    }

    if (isset($_POST['facility_wide_2']) or isset($_POST['owner_wide_2']) or isset($_POST['practice_separate_2'])) {
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if (isset($_POST['facility_wide_2']) or isset($_POST['owner_wide_2'])) {
            // Insert new entity-practice junction-table row.
            if (isset($_POST['facility_wide_2']))
                $NewTableRoot = 'facility';
            if (isset($_POST['owner_wide_2']))
                $NewTableRoot = 'owner';
            $EntityPracticeRow = array(
                'k0'.$NewTableRoot.'_practice' => time().mt_rand(1000000, 9999999),
                'k0'.$NewTableRoot => $_SESSION['t0user_'.$NewTableRoot]['k0'.$NewTableRoot],
                'k0practice' => $_SESSION['Selected']['k0practice'],
                'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
            );
            $ShtmlFormArray = array(
                'k0'.$NewTableRoot.'_practice' => array('k0'.$NewTableRoot.'_practice (row inserted due to practice-scope widening)', ''),
                'k0'.$NewTableRoot => array('k0'.$NewTableRoot, ''),
                'k0practice' => array('k0practice', '')
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0'.$NewTableRoot.'_practice', $EntityPracticeRow, TRUE, $ShtmlFormArray);
            unset($ShtmlFormArray);
            // Delete former entity-practice junction-table row.
            if (isset($_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey'])) {
                $Conditions[0] = array('k0practice', '=', $_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey'], 'AND');
                // Also insert new practice for scope-widening away from a standard practice.
                $Nobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
                $_SESSION['Selected']['c5who_is_editing'] = $Nobody;
                $Zfpf->insert_sql_1s($DBMSresource, 't0practice', $_SESSION['Selected']);
                // Also insert t0practice_division and t0fragment_practice rows for new practice
                $ConditionsP[0] = array('k0practice', '=', $_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey']);
                list($SR_PD, $RR_PD) = $Zfpf->select_sql_1s($DBMSresource, 't0practice_division', $ConditionsP);
                if ($RR_PD) foreach ($SR_PD as $K => $V) { // Typically, a practice is only associated with one division but more would be allowed.
                    $NewRow = array(
                        'k0practice_division' => ++$K.time().mt_rand(10000, 99999),
                        'k0practice' => $_SESSION['Selected']['k0practice'],
                        'k0division' => $V['k0division'], // Associated with same division(s) as selected practice.
                        'c5who_is_editing' => $Nobody
                    );
                    $Zfpf->insert_sql_1s($DBMSresource, 't0practice_division', $NewRow);
                }
                list($SR_FP, $RR_FP) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment_practice', $ConditionsP);
                if ($RR_FP) foreach ($SR_FP as $K => $V) {
                    $NewRow = array(
                        'k0fragment_practice' => ++$K.time().mt_rand(10000, 99999),
                        'k0fragment' => $V['k0fragment'], // Associated with same fragments as selected practice.
                        'k0practice' => $_SESSION['Selected']['k0practice'],
                        'c5who_is_editing' => $Nobody
                    );
                    $Zfpf->insert_sql_1s($DBMSresource, 't0fragment_practice', $NewRow);
                }
                // Also, prepare for t0user_practice update.
                $Changes['k0practice'] = $_SESSION['Selected']['k0practice'];
                $ShtmlFormArray['k0practice'] = array('k0practice (scope-widening required practice change from standard to custom)', '');
            }
            else
                $Conditions[0] = array('k0practice', '=', $_SESSION['Selected']['k0practice'], 'AND');
            $Conditions[1] = array('k0'.$TableRoot, '=', $_SESSION['t0user_'.$TableRoot]['k0'.$TableRoot]);
            $Zfpf->delete_sql_1s($DBMSresource, 't0'.$TableRoot.'_practice', $Conditions); // ShtmlFormArray handled by CoreZfpf::delete_sql_1s
            // Update t0user_practice, same conditions for select.
            list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $Conditions);
            $Changes['k0'.$TableRoot] = 0;
            $Changes['k0'.$NewTableRoot] = $_SESSION['t0user_'.$NewTableRoot]['k0'.$NewTableRoot];
            $ShtmlFormArray['k0'.$TableRoot] = array('k0'.$TableRoot.' (set to 0 due to scope widening)', '');
            $ShtmlFormArray['k0'.$NewTableRoot] = array('k0'.$NewTableRoot.' (set to primary-key value due to scope widening)', '');
            if ($RR) foreach ($SR as $K => $V) {
                $Conditions[2] = array('k0user', '=', $V['k0user'], '', 'AND');
                $Zfpf->update_sql_1s($DBMSresource, 't0user_practice', $Changes, $Conditions, TRUE, $ShtmlFormArray); // Error handling via history recording.
            }
        }
        if (isset($_POST['practice_separate_2'])) {
            // Delete former entity-practice junction-table row.
            if (isset($_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey']))
                $Conditions[0] = array('k0practice', '=', $_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey'], 'AND');
            else {
                $Conditions[0] = array('k0practice', '=', $_SESSION['Selected']['k0practice']);
                $Zfpf->delete_sql_1s($DBMSresource, 't0practice', $Conditions); // Also delete the custom practice.
                $Conditions[0] = array('k0practice', '=', $_SESSION['Selected']['k0practice'], 'AND');
            }
            $Conditions[1] = array('k0'.$TableRoot, '=', $_SESSION['t0user_'.$TableRoot]['k0'.$TableRoot]);
            $Zfpf->delete_sql_1s($DBMSresource, 't0'.$TableRoot.'_practice', $Conditions); // ShtmlFormArray handled by CoreZfpf::delete_sql_1s
            // Delete t0user_practice rows, same conditions for select.
            list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $Conditions);
            if ($RR) foreach ($SR as $K => $V) {
                $Conditions[2] = array('k0user', '=', $V['k0user'], '', 'AND');
                $Zfpf->delete_sql_1s($DBMSresource, 't0user_practice', $Conditions); // Error handling via history recording.
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        // Email all involved 
        $Chain = $Zfpf->up_the_chain_1c($Scope);
        $CurrentUser = $Zfpf->current_user_info_1c();
        $Chain['EmailAddresses'][] = $CurrentUser['WorkEmail']; // Duplicate email addresses removed by CoreZfpf::send_email_1c
        $Chain['DistributionList'] .= '<br />
        User who did this: '.$CurrentUser['NameTitleEmployerWorkEmail'].'</p>'; // Duplicate listing on distribution list desired, to show if same person filled multiple roles.
        $ActionText = $Zfpf->decrypt_1c($_SESSION['Scratch']['ActionText']);
        $PracticeName = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
        $Subject = 'PSM-CAP: '.$ActionText.' for practice: '.$PracticeName;
        $Body = '<p>
        '.$CurrentUser['NameTitle'].', completed:<br />
        - '.$ActionText.'<br />
        for the practice shown below:</p><p>
        *** START COPY OF CONFIRMATION DOCUMENT ***</p>
        '.$Zfpf->decrypt_1c($_SESSION['Scratch']['ConfirmationText']).'
        <p>
        *** END COPY OF CONFIRMATION DOCUMENT ***</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $EntityName, FALSE, $Chain['DistributionList']);
        $EmailSent = $Zfpf->send_email_1c($Chain['EmailAddresses'], $Subject, $Body);
        unset($_SESSION['Selected']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Completed: '.$ActionText.'</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="practice_io03.php" method="post"><p>
            <input type="submit" name="practice_i0m" value="Back to practices list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['practice_i0n']) or isset($_POST['practice_i0n2']) or isset($_POST['practice_i1'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        $Display['c2standardized'] = '<br />'.$_SESSION['Selected']['c2standardized']; // SPECIAL CASE: no encryption.
        // SPECIAL CASE: Not practical to edit_lock at app level, especially if require_file code is being updated.
        // SPECIAL CASE: no need for $_SESSION['Scratch']['htmlFormArray']
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
    if (isset($Display)) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
		Practice Summary</h1>
        <form action="practice_io03.php" method="post">';
        echo $Zfpf->make_html_form_1e($htmlFormArray, $Display);
        echo '<p>
		    <input type="submit" name="practice_i2" value="Review what you typed into form" /></p><p>
            <input type="submit" name="practice_i0m" value="Back to practices list" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['practice_i2']) and isset($_SESSION['Post'])) {
        $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        if ($PostDisplay['c5require_file'] == '[Nothing has been recorded in this field.]' and $PostDisplay['c5require_file_privileges'] != '[Nothing has been recorded in this field.]') {
            $RFMessage = '<p>
            Because the practice doesn\'t have implementing-computer code, the "global-DBMS privileges needed to use any implementing-computer code" must be set to: [Nothing has been recorded in this field.]</p>';
            $PostDisplay['c5require_file_privileges'] = '[Nothing has been recorded in this field.]';
        }
        elseif ($PostDisplay['c5require_file'] != '[Nothing has been recorded in this field.]' and $PostDisplay['c5require_file_privileges'] == '[Nothing has been recorded in this field.]')
            $RFMessage = '<p>
            Because the practice will include implementing-computer code, the "global-DBMS privileges needed to use any implementing-computer code" <b>cannot</b> be set to: [Nothing has been recorded in this field.]</p>';
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay); // Save user-posted info after checked username and password included.
        if (isset($RFMessage)) {
            echo $Zfpf->xhtml_contents_header_1c().'<h1>
		    Practice Summary</h1>
            <form action="practice_io03.php" method="post">
            '.$RFMessage.'<p>
		        <input type="submit" name="modify_confirm_post_1e" value="Go back and fix" /></p><p>
		        <input type="submit" name="undo_confirm_post_1e" value="Discard what you just typed" /></p>
            </form>'.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
	    echo $Zfpf->post_select_required_compare_confirm_1e('practice_io03.php', 'practice_io03.php', $htmlFormArray, $PostDisplay, $SelectDisplay);
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($who_is_editing == '[A new database row is being created.]') {  // True for practice_i0n, practice_i0n2, or -- only when change from standard to custom practice -- practice_i1.
            $Zfpf->insert_sql_1s($DBMSresource, 't0practice', $ChangedRow);
            // Exclude i0n2 case, which is rare because app typically installed with standard practices. In i0n2 case, use will have to assign a practice to fragments and division(s) later.
            $ConditionsP = FALSE;
            if (isset($_SESSION['Scratch']['PlainText']['InsertAfterPracticeKey']))
                $ConditionsP[0] = array('k0practice', '=', $_SESSION['Scratch']['PlainText']['InsertAfterPracticeKey']);
            elseif (isset($_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey']))
                $ConditionsP[0] = array('k0practice', '=', $_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey']);
            if ($ConditionsP) {
                // Also insert t0practice_division and t0fragment_practice rows for new practice row
                $Nobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
                list($SR_PD, $RR_PD) = $Zfpf->select_sql_1s($DBMSresource, 't0practice_division', $ConditionsP);
                if ($RR_PD) foreach ($SR_PD as $K => $V) { // Typically, a practice is only associated with one division but more would be allowed.
                    $NewRow = array(
                        'k0practice_division' => ++$K.time().mt_rand(10000, 99999),
                        'k0practice' => $_SESSION['Selected']['k0practice'],
                        'k0division' => $V['k0division'], // Associated with same division(s) as selected practice.
                        'c5who_is_editing' => $Nobody
                    );
                    $Zfpf->insert_sql_1s($DBMSresource, 't0practice_division', $NewRow);
                }
                list($SR_FP, $RR_FP) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment_practice', $ConditionsP);
                if ($RR_FP) foreach ($SR_FP as $K => $V) {
                    $NewRow = array(
                        'k0fragment_practice' => ++$K.time().mt_rand(10000, 99999),
                        'k0fragment' => $V['k0fragment'], // Associated with same fragments as selected practice.
                        'k0practice' => $_SESSION['Selected']['k0practice'],
                        'c5who_is_editing' => $Nobody
                    );
                    $Zfpf->insert_sql_1s($DBMSresource, 't0fragment_practice', $NewRow);
                }
            }
            $EntityPracticeRow = array(
                'k0'.$TableRoot.'_practice' => time().mt_rand(1000000, 9999999),
                'k0'.$TableRoot => $_SESSION['t0user_'.$TableRoot]['k0'.$TableRoot],
                'k0practice' => $_SESSION['Selected']['k0practice'],
                'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
            );
            $ShtmlFormArray = array(
                'k0'.$TableRoot.'_practice' => array('k0'.$TableRoot.'_practice (row inserted due to practice change or insert)', ''),
                'k0'.$TableRoot => array('k0'.$TableRoot, ''),
                'k0practice' => array('k0practice', '')
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0'.$TableRoot.'_practice', $EntityPracticeRow, TRUE, $ShtmlFormArray);
            if (isset($_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey'])) { // Update all t0user_practice rows to point to the new custom practice.
                $Conditions[0] = array('k0practice', '=', $_SESSION['Scratch']['PlainText']['FormerStandardPracticeKey'], 'AND');
                $Conditions[1] = array('k0'.$TableRoot, '=', $_SESSION['t0user_'.$TableRoot]['k0'.$TableRoot]);
                $Zfpf->delete_sql_1s($DBMSresource, 't0'.$TableRoot.'_practice', $Conditions); // ShtmlFormArray handled by CoreZfpf::delete_sql_1s
                list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $Conditions);
                $Changes['k0practice'] = $_SESSION['Selected']['k0practice'];
                $ShtmlFormArray = array('k0practice' => array('k0practice (updated due to practice change from standard to custom)', ''));
                if ($RR) foreach ($SR as $K => $V) {
                    $Conditions[2] = array('k0user', '=', $V['k0user'], '', 'AND');
                    $Zfpf->update_sql_1s($DBMSresource, 't0user_practice', $Changes, $Conditions, TRUE, $ShtmlFormArray); // Error handling via history recording.
                }
            }
        }
        else { // updating a custom practice
            $Conditions[0] = array('k0practice', '=', $_SESSION['Selected']['k0practice']);
            $Zfpf->update_sql_1s($DBMSresource, 't0practice', $ChangedRow, $Conditions); // Error handling via history recording.
        }
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        unset($_SESSION['Selected']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Record(s) Updated</h2><p>
        The practice information you input and reviewed has been recorded.</p>
        <form action="practice_io03.php" method="post"></p>
            <input type="submit" name="practice_i0m" value="Back to practices list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // Ends facility_wide_1, owner_wide_1, practice_separate_1, i1 (remainder), i2, and i3 code

$Zfpf->catch_all_1c('administer1.php');

$Zfpf->save_and_exit_1c();

