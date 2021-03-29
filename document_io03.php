<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the t0document and t0practice-document input and output HTML forms, except the:
//  - i1m file for listing existing records (and giving the option to start a new record).

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'document_i1m.php' or !isset($_SESSION['t0user_practice']) or !isset($_SESSION['t0user_process']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get current-user information...
$EditAuth = FALSE;
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF and $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']) == MAX_PRIVILEGES_ZFPF)
    $EditAuth = TRUE;
$EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('Name', REQUIRED_FIELD_ZFPF),
    'c6description' => array('Document text or description of uploaded files', '', C6SHORT_MAX_BYTES_ZFPF),
    'c6bfn_document' => array('Uploaded files', '', MAX_FILE_SIZE_ZFPF, 'upload_files')
);

// i0n code
if (isset($_POST['document_i0n'])) {
    // Additional security check.
    if (!$EditAuth)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // Initialize $_SESSION['Selected']
    $_SESSION['Selected'] = array(
        'k0document' => time().mt_rand(1000000, 9999999),
        'c5name' => $EncryptedNothing,
        'c6description' => $EncryptedNothing,
        'c6bfn_document' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['document_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0document']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0document', $_SESSION['Selected']['k0document']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one document record', 'document_io03.php', 'document_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0document']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'document_io03.php', 'document_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'document_io03.php', 'document_o1');
    $_POST['document_o1'] = 1; // Only needed as catch, after minor stuff, like user forgot to select a file before downloading, or password check before download.
}

// o1 code
if (isset($_POST['document_o1'])) {
    // Additional security check.
    if (!isset($_SESSION['Selected']['k0document']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0document'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    $Zfpf->clear_edit_lock_1c();
    if (!isset($_SESSION['Selected']['k0document'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0document'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0document'][$CheckedPost];
        unset($_SESSION['SelectResults']);
    }
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Selected'], TRUE);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Document</h2>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'document_io03.php', $_SESSION['Selected'], $Display);
    // Check if anyone else is editing the selected row and check user privileges.
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '
        <p><b>'.$who_is_editing.' is editing the document record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    elseif ($EditAuth)
        echo '
        <form action="document_io03.php" method="post"><p>
            <input type="submit" name="document_o1_from" value="Update this record" /></p>
        </form>';
    else
        echo '<p><b>
        Privileges Notice</b>: You don\'t have privileges to update this document record. This requires maximum global-DBMS and practice privileges.</p>';
    echo '
    <form action="document_io03.php" method="post"><p>
        <input type="submit" name="document_history_o1" value="History of this record" /></p>
    </form>
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, and i3 code
if (isset($_SESSION['Selected']['k0document'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0document', '=', $_SESSION['Selected']['k0document']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0document', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    // Additional security check.
    if (!$EditAuth)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    if (isset($_POST['document_o1_from']))
        $Zfpf->edit_lock_1c('document'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error.

    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['document_i0n']) or isset($_POST['document_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
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
    elseif (isset($_SESSION['Post']) and !isset($_POST['document_i2']) and !isset($_POST['yes_confirm_post_1e'])) { // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        else {
            $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            foreach ($htmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Selected'][$K]);
                    // FilesZfpf::user_files_directory_1e has been checked, it supports t0document
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, $K, 'document_io03.php');
                    // FilesZfpf::6bfn_files_upload_1e updates $_SESSION['Selected'] and the database. 
                    // Or, it echos an error page an user has option to press $_POST['problem_c6bfn_files_upload_1e'] button.
                    $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
                    $SelectDisplay[$K] = $Zfpf->html_uploaded_files_1e($K); // Update the modified select display
                    $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($SelectDisplay);
                    $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($Zfpf->post_to_display_1e($htmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Post']), $UploadResults['count'], $K));
                    // $_SESSION['Post'] now holds $Display holding $_POST updated with $_SESSION['Selected']['c6bfn_...'] information.
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
        echo $Zfpf->xhtml_contents_header_1c();
        echo '<h1>
        Document</h1>
        <form action="document_io03.php" method="post" enctype="multipart/form-data" >'; // upload_files special case 2 of 3. To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        echo $Zfpf->make_html_form_1e($htmlFormArray, $Display);
        echo '<p>
            <input type="submit" name="document_i2" value="Review what you typed into form" /><br />
            If you only wanted to upload files, you are done. Click on "Go back"</p>
        </form>'; // upload_files special case 3 of 3.
        if ($who_is_editing == '[A new database row is being created.]')
            echo '
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>';
        else
            echo '
            <form action="document_io03.php" method="post"><p>
                <input type="submit" name="document_o1" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['document_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('document_io03.php', 'document_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') {
            $Zfpf->insert_sql_1s($DBMSresource, 't0document', $ChangedRow);
            $PracticeDocumentRow = array(
                'k0practice_document' => time().mt_rand(1000000, 9999999),
                'k0practice' => $_SESSION['t0user_practice']['k0practice'],
                'k0document' => $_SESSION['Selected']['k0document'],
                'k0process'=> $_SESSION['t0user_process']['k0process'],
                'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0practice_document', $PracticeDocumentRow);
        }
        else {
            $Conditions[0] = array('k0document', '=', $_SESSION['Selected']['k0document']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0document', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'<p>
        The information you input and reviewed has been recorded.</p>
        <form action="document_io03.php" method="post"><p>
            <input type="submit" name="document_o1" value="Back to record" /></p>
        </form>
        <form action="practice_o1.php" method="post"><p>
            <input type="submit" value="Back to records list" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, and i3 code

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

