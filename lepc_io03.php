<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the facility summary input and output HTML forms, except the:
//  - i0m & i1m files for listing existing records.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

// General security check. SPECIAL CASE can only arrive here from facility_io03.php
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'facility_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get useful variables.
$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);

// Handle user clicked link to this file from left-hand contents or administer1.php
if (!$_POST or isset($_POST['lepc_i1m'])) {
    if (!isset($_SESSION['Selected']['k0facility']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if ($_SESSION['Selected']['k0lepc'] and !isset($_POST['lepc_i1m'])) {
        $Conditions[0] = array('k0lepc', '=', $_SESSION['Selected']['k0lepc']);
        list($SR, $RR) = $Zfpf->one_shot_select_1s('t0lepc', $Conditions);
        if ($RR != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Scratch']['t0lepc'] = $SR[0];
        $FromLinkWithoutPost = TRUE;
    }
    else {
        if (!isset($_SESSION['t0user_facility']) or $_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'] or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
            $Zfpf->send_to_contents_1c(); // Don't eject
        if (isset($_SESSION['Scratch']['t0lepc'])) {
            if ($Zfpf->decrypt_1c($_SESSION['Scratch']['t0lepc']['c5who_is_editing']) != '[A new database row is being created.]')
                $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['t0lepc']);
            unset($_SESSION['Scratch']['t0lepc']);
        }
        $Conditions = 'No Condition -- All Rows Included';
        list($SR, $RR) = $Zfpf->one_shot_select_1s('t0lepc', $Conditions);
        if ($RR) foreach ($SR as $K => $V) {
            $DisplayInfo[$K] = $Zfpf->entity_name_description_1c($V);
            $SortInfo[$K] = $Zfpf->to_lower_case_1c($DisplayInfo[$K]);
            $_SESSION['SelectResults']['t0lepc'][$K] = $V;
        }
        if (isset($SortInfo))
            array_multisort($SortInfo, $DisplayInfo, $_SESSION['SelectResults']['t0lepc']);
            $Message = '<h2>
           Community or local emergency-planning committees (LEPC) or organizations<br />
           with records in this app implementation</h2>
            <form action="lepc_io03.php" method="post">';
            if (isset($DisplayInfo)) {
                $Message .= '<p>';
                foreach ($_SESSION['SelectResults']['t0lepc'] as $K => $V) {
                    if ($K)
                        $Message .= '<br />';
                    $Message .= '
                    <input type="radio" name="selected" value="'.$K.'" ';
                    if (!$K)
                        $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
                    $Message .= '/>'.$DisplayInfo[$K];
                }
                $Message .= '</p><p>
                <input type="submit" name="lepc_o1" value="View or update" /></p><p>
                <input type="submit" name="lepc_change_1" value="Change facility LEPC to above" /></p>';
            }
            else
                $Message .= '<p>
                <b>None found.</b> Please contact your supervisor if this seems amiss.</p>';
            $Message .= '<p>
                <input type="submit" name="lepc_i0n" value="Insert new record" /></p>';
            echo $Zfpf->xhtml_contents_header_1c().$Message.'
            </form>
		    <form action="facility_io03.php" method="post"><p>
		        <input type="submit" name="facility_o1" value="Back to facility record" /></p>
		    </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
    }
}

if (isset($_POST['lepc_change_1'])) {    
    if (!isset($_SESSION['t0user_facility']) or $_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'] or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or !isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0lepc']))
        $Zfpf->send_to_contents_1c(); // Don't eject
    $CheckedPost = $Zfpf->post_length_blank_1c('selected'); // Get user selection.
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0lepc'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if ($_SESSION['Selected']['k0lepc'] != $_SESSION['SelectResults']['t0lepc'][$CheckedPost]['k0lepc']) { // $_SESSION['Selected'] still holds t0facility
        $Changes['k0lepc'] = $_SESSION['SelectResults']['t0lepc'][$CheckedPost]['k0lepc'];
        $Conditions[0] = array('k0facility', '=', $_SESSION['Selected']['k0facility']);
        $Affected = $Zfpf->one_shot_update_1s('t0facility', $Changes, $Conditions);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        $_SESSION['Selected']['k0lepc'] = $Changes['k0lepc'];
    } 
    // If same LEPC selected as before, such send user to o1 code (without notice)
    $_SESSION['Scratch']['t0lepc'] = $_SESSION['SelectResults']['t0lepc'][$CheckedPost];
    unset($_SESSION['SelectResults']);
    $_POST['lepc_o1'] = 1;
}

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('<a id="c5name"></a>Name of community or local emergency-planning committee (LEPC) or organization', REQUIRED_FIELD_ZFPF),
    'c6description' => array('Description', '', C6SHORT_MAX_BYTES_ZFPF),
    'c5contact_name' => array('<a id="c5contact_name"></a>Contact Name', REQUIRED_FIELD_ZFPF),
    'c5contact_email' => array('Contact Email', REQUIRED_FIELD_ZFPF),
    'c5phone' => array('<a id="c5phone"></a>Main Telephone Number', REQUIRED_FIELD_ZFPF),
    'c5street1' => array('<a id="c5street1"></a>Address', REQUIRED_FIELD_ZFPF),
    'c5street2' => array('Address (extra line)', ''),
    'c5city' => array('City', REQUIRED_FIELD_ZFPF),
    'c5state_province' => array('State or Province', ''),
    'c5postal_code' => array('Postal Code', ''),
    'c5country' => array('Country', REQUIRED_FIELD_ZFPF),
    'c5website' => array('<a id="c5website"></a>Website', '')
);

// Left hand Table of contents
if (isset($_POST['lepc_o1']) or isset($_POST['lepc_i0n']) or isset($_POST['lepc_o1_from']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
	    'c5name' => 'Organization name',
	    'c5contact_name' => 'Contact', 
	    'c5phone' => 'Telephone number',  
	    'c5street1' => 'Address',
	    'c5website' => 'Website'
    );

// i0n code
if (isset($_POST['lepc_i0n'])) {
    // Additional security check.
    if (!isset($_SESSION['t0user_facility']) or $_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'] or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $_SESSION['Scratch']['t0lepc'] = array (
        'k0lepc' => time().mt_rand(1000000, 9999999),
        'c5name' => $EncryptedNothing,
        'c6description' => $EncryptedNothing,
        'c5contact_name' => $EncryptedNothing,
        'c5contact_email' => $EncryptedNothing,
        'c5phone' => $EncryptedNothing,
        'c5street1' => $EncryptedNothing,
        'c5street2' => $EncryptedNothing,
        'c5city' => $EncryptedNothing,
        'c5state_province' => $EncryptedNothing,
        'c5postal_code' => $EncryptedNothing,
        'c5country' => $EncryptedNothing,
        'c5website' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['lepc_history_o1'])) {
    if (!isset($_SESSION['Scratch']['t0lepc']['k0lepc']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0lepc', $_SESSION['Scratch']['t0lepc']['k0lepc']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one community or local emergency-planning committee (LEPC) or organization record', 'lepc_io03.php', 'lepc_o1'); // This echos and exits.
}

// o1 code
// SPECIAL CASE: isset($FromLinkWithoutPost) handles user clicked link to this file from left-hand contents
if (isset($_POST['lepc_o1']) or isset($FromLinkWithoutPost)) {
    if (isset($_POST['lepc_o1'])) {
        // Additional security check
        if (!isset($_SESSION['Scratch']['t0lepc']['k0lepc']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0lepc'])))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // $_SESSION cleanup
        if (isset($_SESSION['Scratch']['htmlFormArray']))
            unset($_SESSION['Scratch']['htmlFormArray']);
        if (isset($_SESSION['Post']))
            unset($_SESSION['Post']);
        if (isset($_SESSION['Scratch']['t0lepc']))
            $_SESSION['Scratch']['t0lepc']['c5who_is_editing'] = $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['t0lepc']); // SPECIAL CASE
        if (!isset($_SESSION['Scratch']['t0lepc']['k0lepc'])) {
            // Additional security check
            if (!isset($_SESSION['StatePicked']['t0owner']) or !isset($_SESSION['t0user_owner'])) // Same privileges needed to get link from administer1.php.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $CheckedPost = $Zfpf->post_length_blank_1c('selected'); // Get user selection.
            if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0lepc'][$CheckedPost]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['Scratch']['t0lepc'] = $_SESSION['SelectResults']['t0lepc'][$CheckedPost];
            unset($_SESSION['SelectResults']);
        }
    }
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0lepc']);
    echo $Zfpf->xhtml_contents_header_1c().'
    <h2>
    Community or local emergency-planning committee (LEPC) or organization summary</h2>
    <form action="lepc_io03.php" method="post">
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'lepc_io03.php', $_SESSION['Scratch']['t0lepc'], $Display); // SPECIAL CASE: start form before to include button in $Display
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0lepc']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    else {
        if (isset($_SESSION['t0user_facility']) and $_SESSION['t0user_facility']['k0facility'] == $_SESSION['Selected']['k0facility'] and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF) 
            echo '<p>
            <input type="submit" name="lepc_o1_from" value="Update this summary" /></p>
            <input type="submit" name="lepc_i1m" value="Change facility LEPC" /></p>';
        else
            echo '<p><b>
            Update Privileges Notice</b>: You don\'t have update privileges on this record. Only an facility\'s employee with adequate privileges may update this record.</p>';
        if ($UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to update or delete PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '<p>
        <input type="submit" name="lepc_history_o1" value="History of this record" /></p>
    </form>
    <form action="facility_io03.php" method="post"><p>
        <input type="submit" name="facility_o1" value="Back to facility record" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}


// SPECIAL CASE: legacy i3, then i1, i2, no needed to refresh $_SESSION['Selected']
if (isset($_POST['yes_confirm_post_1e'])) {
    // Additional security check
    if (!isset($_SESSION['t0user_facility']) or $_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'] or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or !isset($_SESSION['Post']))
        $Zfpf->send_to_contents_1c(); // Don't eject
    $Changes = $Zfpf->changes_from_post_1c($_SESSION['Scratch']['t0lepc']);
    if ($Zfpf->decrypt_1c($_SESSION['Scratch']['t0lepc']['c5who_is_editing']) == '[A new database row is being created.]')
        $Zfpf->one_shot_insert_1s('t0lepc', $Changes);
    else {
        $Conditions[0] = array('k0lepc', '=', $_SESSION['Scratch']['t0lepc']['k0lepc']);
        $Affected = $Zfpf->one_shot_update_1s('t0lepc', $Changes, $Conditions);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
    }
    foreach ($Changes as $K => $V)
        $_SESSION['Scratch']['t0lepc'][$K] = $V;
    unset($_SESSION['Post']);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Record(s) Updated</h2><p>
    The community or local emergency-planning committee (LEPC) or organization information you input and reviewed has been recorded.</p>
    <form action="lepc_io03.php" method="post"><p>
        <input type="submit" name="lepc_o1" value="Back to record" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1 and i2 code
if (isset($_SESSION['Scratch']['t0lepc']['k0lepc'])) {
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0lepc']['c5who_is_editing']);
    if (!isset($_SESSION['t0user_facility']) or $_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'] or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Scratch']['t0lepc'] is only source of $Display.
    if (isset($_POST['lepc_i0n']) or isset($_POST['lepc_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0lepc'], FALSE, TRUE);
        if (isset($_POST['lepc_o1_from'])) {
            $_SESSION['Scratch']['t0lepc'] = $Zfpf->edit_lock_1c('lepc', $Zfpf->decrypt_1c($_SESSION['Scratch']['t0lepc']['c5name']).' summary', $_SESSION['Scratch']['t0lepc']); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. Not needed for i1n. SPECIAL CASE
        }
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
    if ($who_is_editing == '[A new database row is being created.]') {
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
    }
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
		Community or local emergency-planning committee (LEPC) or organization summary</h1>
        <form action="lepc_io03.php" method="post">';
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
		    <input type="submit" name="lepc_i2" value="Review what you typed into form" /></p>
		</form>';
		if ($who_is_editing == '[A new database row is being created.]')
		    echo '
            <form action="lepc_io03.php" method="post"><p>
                <input type="submit" name="lepc_i1m" value="Go back" /></p>
            </form>';
		else
    		echo '
		    <form action="lepc_io03.php" method="post"><p>
		        <input type="submit" name="lepc_o1" value="Go back" /></p>
		    </form>';
		echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['lepc_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay, 0, FALSE, $_SESSION['Scratch']['t0lepc']);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay); // Save user-posted info after checked username and password included.
	    echo $Zfpf->post_select_required_compare_confirm_1e('lepc_io03.php', 'lepc_io03.php', $htmlFormArray, $PostDisplay, $SelectDisplay);
        $Zfpf->save_and_exit_1c();
    }
} // Ends i1 and i2 code.

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

