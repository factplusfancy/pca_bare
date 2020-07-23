<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

// General security check
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'union_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get useful variables.
$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('<a id="c5name"></a>Union Name (the exact legal name typically works best)', REQUIRED_FIELD_ZFPF),
    'c6description' => array('Description', '', C6SHORT_MAX_BYTES_ZFPF)
);

// i0n code
if (isset($_POST['union_i0n'])) {
    // Additional security check. SPECIAL CASE none -- full security to get security token above.
    $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    $_SESSION['Selected'] = array (
        'k0union' => time().mt_rand(1000000, 9999999),
        'c5name' => $EncryptedNothing,
        'c6description' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['union_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0union']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0union', $_SESSION['Selected']['k0union']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one union record', 'union_io03.php', 'union_o1'); // This echos and exits.
}

if (isset($_POST['union_associate_1'])) {
    $CheckedPost = $Zfpf->post_length_blank_1c('other_selected'); // Get user selection.
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['Othert0union'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Selected'] = $_SESSION['SelectResults']['Othert0union'][$CheckedPost];
    unset($_SESSION['SelectResults']);
    // Insert facility-union record.
    $Changes['t0facility_union'] = array(
        'k0facility_union' => time().mt_rand(1000000, 9999999),
        'k0facility' => $_SESSION['t0user_facility']['k0facility'],
        'k0union' => $_SESSION['Selected']['k0union'],
        'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
    );
    $Zfpf->one_shot_insert_1s('t0facility_union', $Changes['t0facility_union']);
    $_POST['union_o1'] = 1;
}

// o1 code
if (isset($_POST['union_o1'])) {
    // Additional security check
    if (!isset($_SESSION['Selected']['k0union']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0union'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (!isset($_SESSION['Selected']['k0union'])) {
        // Additional security check. SPECIAL CASE none -- full security to get security token above.
        $CheckedPost = $Zfpf->post_length_blank_1c('selected'); // Get user selection.
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0union'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0union'][$CheckedPost];
        unset($_SESSION['SelectResults']);
    }
    $Zfpf->clear_edit_lock_1c();
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
    echo $Zfpf->xhtml_contents_header_1c().'
    <h2>
    Union Summary</h2>
    <form action="union_io03.php" method="post">
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'union_io03.php', $_SESSION['Selected'], $Display);
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    else
        echo '<p>
        <input type="submit" name="union_o1_from" value="Update union summary" /></p>';
    echo '<p>
        <input type="submit" name="union_history_o1" value="History of this record" /></p>
    </form>
    <form action="union_i0m.php" method="post"><p>
        <input type="submit" value="Back to unions list" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, i3 code
if (isset($_SESSION['Selected']['k0union'])) {
    // SPECIAL CASE: don't refresh $_SESSION['Selected'] -- no additional security check and $who_is_editing only used for i0n case.
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    // Additional security check. SPECIAL CASE none -- full security to get security token above.

    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['union_i0n']) or isset($_POST['union_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        if (isset($_POST['union_o1_from'])) {
            $Zfpf->edit_lock_1c('union', $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).' union summary'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. Not needed for i1n.
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
    // 1.3 $_SESSION['Post'] is source of $Display, the latest user-modified version.
    elseif (isset($_SESSION['Post']) and isset($_POST['modify_confirm_post_1e']))
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
		Union Summary</h1>
        <form action="union_io03.php" method="post">';
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
		    <input type="submit" name="union_i2" value="Review what you typed into form" /></p>
		</form>';
		if ($who_is_editing == '[A new database row is being created.]')
		    echo '
            <form action="union_i0m.php" method="post"><p>
                <input type="submit" value="Back to unions list" /></p>
            </form>';
		else
    		echo '
		    <form action="union_io03.php" method="post"><p>
		        <input type="submit" name="union_o1" value="Back to viewing record" /></p>
		    </form>';
		echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['union_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay); // Save user-posted info after checked username and password included.
	    echo $Zfpf->post_select_required_compare_confirm_1e('union_io03.php', 'union_io03.php', $htmlFormArray, $PostDisplay, $SelectDisplay);
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') {
            // Additional security check. SPECIAL CASE none -- full security to get security token above.
            $Zfpf->insert_sql_1s($DBMSresource, 't0union', $ChangedRow);
            // Insert facility-union record.
            $Changes['t0facility_union'] = array(
                'k0facility_union' => time().mt_rand(1000000, 9999999),
                'k0facility' => $_SESSION['t0user_facility']['k0facility'],
                'k0union' => $_SESSION['Selected']['k0union'],
                'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0facility_union', $Changes['t0facility_union']);
        }
        else {
            // Additional security check. SPECIAL CASE none -- full security to get security token above.
            $Conditions[0] = array('k0union', '=', $_SESSION['Selected']['k0union']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0union', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Record(s) Updated</h2><p>
        The union information you input and reviewed has been recorded.</p>
        <form action="union_io03.php" method="post"><p>
            <input type="submit" name="union_o1" value="Back to record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // Ends i1, i2, i3 code

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

