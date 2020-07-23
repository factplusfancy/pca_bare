<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// Class ConfirmZfpf contains function(s) for confirming input data

require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserFindZfpf.php';

class ConfirmZfpf extends UserFindZfpf {

    ////////////////////////////////////////////////////////////////////////////
    // This function attempts to decrypt an fpf standard c6bfn field from a database, 
    // check if anything is recorded there, and if so, unencode the c6bfn array.
    // $c6bfn is the contents of a c6bfn field from the database (what's under the c6bfn column in one row of a database).
    // See 0read_me_psm_cap_app_standards.txt for the c6bfn field specification.
    public function c6bfn_decrypt_decode_1e($c6bfn) {
        // Decrypt, check, and decode the base filename array.
        $c6bfn = $this->decrypt_1c($c6bfn);
        if ($c6bfn == '[Nothing has been recorded in this field.]')
            return FALSE;
        else {
            // In some cases PHP function unserialize may be exploitable via PHP object injection.
            // So use json_decode() and json_encode() instead of serialize() unserialize
            // See http://php.net/manual/en/function.unserialize.php
            // See https://security.stackexchange.com/questions/63179/php-object-injection-prevention-owasp
            $c6bfn = json_decode($c6bfn, TRUE); // json_decode returns NULL if argument cannot be decoded. The second parameter must be TRUE to return an array.
            if ($c6bfn and is_array($c6bfn))
                return $c6bfn;
            else // A user can only get here if an error has occurred.
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' c6bfn_decrypt_decode_1e() Eject Case 1 (did not have an allowed value for a c6bfn database field)');
        }
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function creates HTML for viewing any uploaded files, based on c6bfn_ fields,
    // including the $Value variable for ConfirmZfpf::html_form_field_1e in the upload_files special case.
    // $SelectedRow the result of a SQL select of one row in an fpf app database table, which may be stored in $_SESSION['Selected']
    public function html_uploaded_files_1e($c6bfn_column_name, $FilesUploaded = 0, $SelectedRow = FALSE) {
        if (!$SelectedRow and !$_SESSION['Selected'])
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' html_uploaded_files_1e() Eject Case 1');
        if (!$SelectedRow)
            $SelectedRow = $_SESSION['Selected'];
        if (!isset($SelectedRow[$c6bfn_column_name]))
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' html_uploaded_files_1e() Eject Case 2');
        $Value = '';
        if (isset($SelectedRow['c5who_is_editing']) and $this->decrypt_1c($SelectedRow['c5who_is_editing']) == '[A new database row is being created.]')
            $Value .= 'NEW-RECORD NOTE. You are creating a new record. Until you have saved (reviewed and confirmed) a draft of this record, you will <b>not</b> be able to upload files.';
        else {
            if ($FilesUploaded)
                $Value .= '
                <b>'.$FilesUploaded.' file(s) just uploaded.</b><br />';
            $c6bfn_array = $this->c6bfn_decrypt_decode_1e($SelectedRow[$c6bfn_column_name]); // Returns false if no files have been uploaded.
            if ($c6bfn_array) {
                $Value .= 'RECORDED FILES. The digital files described below, as named by whoever uploaded them, are currently recorded as part of this record.<br />';
                $C = 0;
                foreach ($c6bfn_array as $V) // $V is the unencrypted base filename, including any suffix, provided by the user who uploaded the file.
                    $Value .= '
                    ('.++$C.') '.$V[0].' -- uploaded by: '.$V[1].'<br />';
            }
            else
                $Value .='No uploaded files found for this field.<br />';
        }
        return $Value;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function sets $Display, an array of HTML values for HTML form, based on (and with keys matching)
    // $htmlFormArray, which is described in 0read_me_psm_cap_app_standards.txt, and 
    // $SelectedRow the result of a SQL select of one row in an fpf app database table, which may be stored in $_SESSION['Selected']
    // $DownloadsForm is passed in as TRUE (by ConfirmZfpf::select_to_o1_html_1e and o1 code) for tables with upload_files (aka c6bfn) fields, to allow downloading files. If false, filenames are displayed but cannot be downloaded.
    // $NoNL2BR is passed in as TRUE by ConfirmZfpf::post_select_required_compare_confirm_1e. Use to stop new line to <br /> conversion, when displaying user inputted text with new lines in HTML textarea fields.
    public function select_to_display_1e($htmlFormArray, $SelectedRow = FALSE, $DownloadsForm = FALSE, $NoNL2BR = FALSE) {
        if (!$SelectedRow and !$_SESSION['Selected'])
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' select_to_display_1e() Eject Case 1');
        if (!$SelectedRow)
            $SelectedRow = $_SESSION['Selected'];
        foreach ($htmlFormArray as $KA => $VA) {
            if (isset($VA[2]) and is_array($VA[2])) // This indicates that an encrypted and encoded array of HTML fields was put into a single database field.
                $Display[$KA] = $this->decrypt_decode_1c($SelectedRow[$KA]); // TO DO 2020-07-22 Note: this doesn't do nl2br, which looks pretty good with the PSM-CAP App.
            else {
                if (substr($KA, 0, 1) == 'k') { // This special case cannot be in a nested array. Database keys -- and k2username_hash -- are not encrypted.
                    if (isset($SelectedRow[$KA]))
                        $Display[$KA] = $SelectedRow[$KA];
                    else
                        $Display[$KA] = FALSE; // Set here, will need to overwrite in calling code, as a special case.
                }
                elseif (substr($KA, 0, 1) == 's') // This special case cannot be in a nested array.
                    $Display[$KA] = 'Secret information that was hashed before recording (hopefully impossible to unhash).';
                elseif (isset($VA[3]) and $VA[3] == 'upload_files') {
                    // ConfirmZfpf can use FilesZfpf functions here because it must be extended by FilesZfpf if the upload_files special case is used in $htmlFormArray.
                    if ($DownloadsForm) {
                        $Display[$KA] = '';
                        $c6bfn_array = $this->c6bfn_decrypt_decode_1e($SelectedRow[$KA]);
                        if ($c6bfn_array) {
                            $Display[$KA] .= 'RECORDED FILES. The digital files described below, as named by whoever uploaded them, are currently recorded as part of this record.<br />';
                            $Count = 0;
                            if (ZIP_DOWNLOAD_WORKS_ZFPF)
                                foreach ($c6bfn_array as $VB)
                                    $Display[$KA] .= '<input type="checkbox" name="'.$KA.$Count++.'" value="1" />'.$VB[0].' -- uploaded by: '.$VB[1].'<br />';
                            else {
                                foreach ($c6bfn_array as $VB)
                                    $Display[$KA] .= '<input type="radio" name="identifier_for_fileszfpf" value="'.$KA.$Count++.'" />'.$VB[0].' -- uploaded by: '.$VB[1].'<br />';
                                $Display[$KA] .= '
                                <input type="submit" name="download_selected" value="Download selected file" />';
                            }
                        }
                        else
                            $Display[$KA] .= 'No uploaded files found for this field.<br />';
                    }
                    else
                        $Display[$KA] = $this->html_uploaded_files_1e($KA, 0, $SelectedRow);
                }
                elseif (substr($KA, 1, 2) > 4) { // Indicates an encrypted fpf data type: c5 or c6. See 0read_me_psm_cap_app_standards.txt
                    $Display[$KA] = $this->timestamp_to_display_1c($this->decrypt_1c($SelectedRow[$KA]), $KA); // timestamp_to_display_1c handles all other field types.
                    if (!$NoNL2BR)
                        $Display[$KA] = nl2br($Display[$KA]);
                }
                else { // The un-encrypted fpf data types: c1, c2, c3, and c4. 0read_me_psm_cap_app_standards.txt
                    $Display[$KA] = $this->timestamp_to_display_1c($SelectedRow[$KA], $KA); // timestamp_to_display_1c handles all other field types.
                    if (!$NoNL2BR)
                        $Display[$KA] = nl2br($Display[$KA]);
                }
            }
        }
        return $Display;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function creates o1 (output 1) HTML, from 
    // $htmlFormArray, which is described in 0read_me_psm_cap_app_standards.txt.
    // $CallingFile -- if $htmlFormArray includes c6bfn fields (upload_files special case), set calling file to get download files form. If false, only a list of uploaded files is shown. $CallingFile is the HTML form action to return to the current o1 file after the download dialog box closes.
    // $SelectedRow and $Display are only passed in for special cases, such as k0 in $htmlFormArray.
    // $ParagraphClass allows setting the CSS class of an HTML paragraph, only outside fieldsets, for example:
    //     $ParagraphClass = ' class="topborder"'  // Note leading space; see use below.
    public function select_to_o1_html_1e($htmlFormArray, $CallingFile = FALSE, $SelectedRow = FALSE, $Display = FALSE, $ParagraphClass = '') {
        if (!$SelectedRow and !$_SESSION['Selected'])
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' select_to_o1_html_1e() Eject Case 1');
        if (!$SelectedRow)
            $SelectedRow = $_SESSION['Selected'];
        $HTML = '';
        $DownloadsForm = FALSE;
        if ($CallingFile)
            foreach ($htmlFormArray as $K => $V)
                if (isset($V[3]) and $V[3] == 'upload_files' and $this->c6bfn_decrypt_decode_1e($SelectedRow[$K])) {
                    $HTML .= '<form action="'.$CallingFile.'" method="post">';
                    $DownloadsForm = TRUE;
                    break;
                }
        if (!$Display)
            $Display = $this->select_to_display_1e($htmlFormArray, $SelectedRow, $DownloadsForm);
        foreach ($htmlFormArray as $KA => $VA) {
            if (isset($VA[2]) and is_array($VA[2])) {
                // Handle second (nested) layer of $htmlFormArray -- an encoded array of HTML fields from a single database field.
                $HTML .= '
                <fieldset><legend>'.$VA[0].'</legend>'; // HTML fieldset used for all of these cases.
                $SubsetCounter = 0;
                foreach ($Display[$KA] as $KB => $VB) {
                    $Label = $VA[2][$SubsetCounter][0]; // Required $htmlFormArray parameter
                    $SpecialCase = FALSE;
                    if (isset($VA[2][$SubsetCounter][2]) and isset($VA[2][$SubsetCounter][3]))
                        $SpecialCase = $VA[2][$SubsetCounter][3];
                    if ($SubsetCounter == 0)
                        $HTML .= '<p>';
                    if ($VB != '[Nothing has been recorded in this field.]' or ($SpecialCase != 'radio' and $SpecialCase != 'checkbox')) // In all cases, except unselected radio buttons or check boxes (so including drop-down menus), echo the label even if nothing is recorded.
                        $HTML .= '- <i>'.$Label.':</i> '.$VB.'<br />'; // "- " was added for better display on small screens, like mobile phones...
                    if (++$SubsetCounter == count($VA[2])) { // count($VA[2]) gives the fields in $htmlFormArray[$KA] -- the subset repeated within $Display[$KA]
                        $HTML .= '</p>';
                        $SubsetCounter = 0;
                    }
                }
                $HTML .= '
                </fieldset>';
            }
            else
                // Handle single-layer $htmlFormArray.
                $HTML .= '<p'.$ParagraphClass.'>
                <i>'.$VA[0].':</i><br />
                '.$Display[$KA].'</p>';
            $ParagraphClass = ''; // For example, only add a top border to the fist paragraph.
        }
        if ($DownloadsForm) {
            if (ZIP_DOWNLOAD_WORKS_ZFPF)
                $HTML .= '<p>
                <input type="submit" name="download_selected" value="Download all selected files" />
                <input type="submit" name="download_all" value="Download all files in this record" /></p>';
            $HTML .= '
            </form>';
        }
        return $HTML;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function checks $_POST against a key whitelist, runs post through CoreZfpf::xss_prevent_1c, and truncates post to a max length.
    // It returns a user's post as array $NewDisplay, checked adequately for echoing as the value in HTML form fields, and nothing else.
    // If $_POST is not set, or its keys and value sizes do not match $htmlFormArray and $OldDisplay whitelist, user is ejected.
    // It can check a $_POST with up to two layers, with the second (nested) layer holding a set of one or more grouped fields.
    // $FilesUploaded and $FilesUploadedField are passed in after uploading files, to display information on the uploaded files.
    // $SelectedRow the result of a SQL select of one row in an fpf app database table, which may be stored in $_SESSION['Selected']
    public function post_to_display_1e($htmlFormArray, $OldDisplay, $FilesUploaded = 0, $FilesUploadedField = FALSE, $SelectedRow = FALSE) {
        if (!$SelectedRow and !$_SESSION['Selected'])
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' post_to_display_1e() Eject Case 1');
        if (!$SelectedRow)
            $SelectedRow = $_SESSION['Selected'];
        foreach ($htmlFormArray as $KA => $VA) {
            if (isset($VA[2]) and is_array($VA[2])) {
                // Handle second (nested) layer of $htmlFormArray. An encoded array of HTML fields will go into a single database field.
                // Note: count($OldDisplay[$KA]) would give the total HTML fields in this set, including extra fields from user pressing an "add field" button.
                $SubsetCounter = 0;
                foreach ($OldDisplay[$KA] as $KB => $VB) {
                    // Get $htmlFormArray info. Initialize to default each pass, so no holdover from prior pass, and simpler logic.
                    $MaxLength = C5_MAX_BYTES_ZFPF;
                    $SpecialCase = FALSE;
                    if (isset($VA[2][$SubsetCounter][2])) {
                        $MaxLength = $VA[2][$SubsetCounter][2];
                        if (isset($VA[2][$SubsetCounter][3]))
                            $SpecialCase = $VA[2][$SubsetCounter][3];
                    }
                    if (++$SubsetCounter == count($VA[2])) // count($VA[2]) gives the fields in $htmlFormArray[$KA] -- the subset repeated within $OldDisplay[$KA]
                        $SubsetCounter = 0;
                    if ($SpecialCase == 'app_assigned') // Update $NewDisplay later for app_assigned cases.
                        $NewDisplay[$KA][$KB] = $OldDisplay[$KA][$KB];
                    // The upload_files special case (c6bfn_ fields) is not allowed here.
                    elseif (isset($_POST[$KA][$KB])) {
                        // Get user post, if in whitelist of $_POST keys. Then check max length.
                        $NewDisplay[$KA][$KB] = $this->max_length_1c($this->xss_prevent_1c($_POST[$KA][$KB]), $MaxLength);
                        if ($NewDisplay[$KA][$KB] === '') // Convert empty string (but not 0, FALSE..., thus ===) to fpf standard for blank fields.
                            $NewDisplay[$KA][$KB] = '[Nothing has been recorded in this field.]';
                        if ($SpecialCase == 'radio') {
                            // Handle any "Other" text field at the bottom of a radio-button set -- the "Other" radio button special-special case.
                            $Other = 'radio_button_other_1'.$KA;
                            $RadioValuesPopped = $VA[2][$SubsetCounter][4];
                            array_pop($RadioValuesPopped);
                            if (isset($_POST[$Other][$KB]) and $_POST[$Other][$KB] != '' and $_POST[$Other][$KB] != '[Nothing has been recorded in this field.]' and !in_array($_POST[$KA][$KB], $RadioValuesPopped))
                                $NewDisplay[$KA][$KB] = $this->max_length_1c($this->xss_prevent_1c($_POST[$Other]), $MaxLength);
                        }
                    }
                    elseif ($SpecialCase == 'radio' or $SpecialCase == 'checkbox') // Handle unselected checkbox radio-button set special cases. Must come here to avoid overwriting a selected radio button or checkbox.
                        $NewDisplay[$KA][$KB] = '[Nothing has been recorded in this field.]';
                    else // Eject user for tampering with HTML form.
                        $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' post_to_display_1e() Eject Case 2, KA was: '.@$KA.'KB was: '.@$KB);
                }
            }
            else {
                // Handle single-layer $htmlFormArray. Similar to above but easier.
                $MaxLength = C5_MAX_BYTES_ZFPF;
                $SpecialCase = FALSE;
                if (isset($VA[2])) {
                    $MaxLength = $VA[2];
                    if (isset($VA[3]))
                        $SpecialCase = $VA[3];
                }
                if ($SpecialCase == 'app_assigned' or substr($KA, 0, 2) == 'k0')
                    $NewDisplay[$KA] = $OldDisplay[$KA];
                    // The k0 special case here shall not be in a nested array in $htmlFormArray, per its spec. 
                    // In this case, $NewDisplay will hold a database key that needs to be replaced with a SQL select result later.
                elseif ($SpecialCase == 'upload_files') {
                    if ($KA == $FilesUploadedField)
                        $NewDisplay[$KA] = $this->html_uploaded_files_1e($KA, $FilesUploaded, $SelectedRow);
                    else
                        $NewDisplay[$KA] = $this->html_uploaded_files_1e($KA, 0, $SelectedRow);
                }
                elseif (isset($_POST[$KA])) {
                    $NewDisplay[$KA] = $this->max_length_1c($this->xss_prevent_1c($_POST[$KA]), $MaxLength);
                    if ($NewDisplay[$KA] === '')
                        $NewDisplay[$KA] = '[Nothing has been recorded in this field.]';
                    if ($SpecialCase == 'radio') {
                        $Other = 'radio_button_other_1'.$KA;
                        $RadioValuesPopped = $VA[4];
                        array_pop($RadioValuesPopped);
                        if (isset($_POST[$Other]) and $_POST[$Other] != '' and $_POST[$Other] != '[Nothing has been recorded in this field.]' and !in_array($_POST[$KA], $RadioValuesPopped))
                            $NewDisplay[$KA] = $this->max_length_1c($this->xss_prevent_1c($_POST[$Other]), $MaxLength);
                    }
                    if (substr($KA, 0, 5) == 'c5ts_')
                        $NewDisplay[$KA] = $this->timestamp_to_display_1c($this->text_to_timestamp_1c($NewDisplay[$KA])); // Time stamps not allowed in nested arrays.
                }
                elseif ($SpecialCase == 'radio' or $SpecialCase == 'checkbox') // Handle unselected checkboxes or radio-button sets.
                    $NewDisplay[$KA] = '[Nothing has been recorded in this field.]';
                else // Eject user for tampering with HTML form.
                    $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' post_to_display_1e() Eject Case 3, KA was: '.@$KA);
            }
        }
        return $NewDisplay;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function creates HTML ready to echo to user that must be echoed inside an HTML <form> and <p> or <fieldset> to validate as HTML5 or xhtml strict.
    // For radio buttons, $Label must be a numeric array of the options.
    // $Name is used to create the HTML input name, which becomes a key in the PHP $_POST array. By convention, it typically matches the $htmlFormArray keys.
    // $Value is the HTML input value. It should start with text and have neither '<br /> ' nor punctuation, to work with i1 forms and 12 confirmation pages.
    // $Label shall end with neither a space nor punctuation.
    // $Required, shall be the empty string '' (if not required) orstart with a space then whatever symbolizes a required field in the app.  See .../settings/CoreSettings/Zfpf.php
    // $MaxLength =< C5_MAX_BYTES_ZFPF outputs a HTML text input, >C5_MAX_BYTES_ZFPF outputs a textarea.  See $MaxLength in 0read_me_psm_cap_app_standards.txt
    // $SpecialCase allowed values are in the if clauses below.
    // $OptionValues is an array of radio button HTML-form values, except the last one can be an "Other" text field.
    // $CheckboxValue is the HTML-form value for a checkbox.
    // $NewDatabaseRow suppresses HTML file-upload buttons if true. Cannot upload files in i0n context, when creating a new database row.
    public function html_form_field_1e($Name, $Value, $Label, $Required, $MaxLength = C5_MAX_BYTES_ZFPF, $SpecialCase = FALSE, $OptionValues = FALSE, $CheckboxValue = 'Yes', $NewDatabaseRow = FALSE) {
        if ($SpecialCase == 'app_assigned')
            return $Label.$Required.':<br /><i>'.$Value.'</i><br />'; // Final page break handles multiple-field subsets; browsers ignore if followed by </p>.
        elseif (substr($Name, 0, 2) == 'k0')
            return $Label.$Required.':<br /><i>'.$Value.'</i>'; // k0 fields never occur in multiple-field subsets, so this special formats works and looks nicer.
        elseif ($SpecialCase == 'upload_files') {
            // Put input type="file" HTML here so it appears on i1 form, but not on i2 confirmation page.
            // MAX_FILE_SIZE must precede input type="file" fields. It's for convenience, not hacking prevention; server-side checks needed.
            if (!$NewDatabaseRow)
                $Value .= '
                <input type="hidden" name="MAX_FILE_SIZE" value="'.$MaxLength.'" />
                <input type="file" name="file_1_'.$Name.'" /><br />
                <input type="file" name="file_2_'.$Name.'" /><br />
                <input type="file" name="file_3_'.$Name.'" /><br />
                The maximum file size allowed is '.$MaxLength/1000000 .' MB (megabytes).<br />
                Clicking "Upload Now" attempts to upload <b>only the files in this field</b> and, if successful, returns you to this form, where you may upload more files.<br />
                <input type="submit" name="upload_'.$Name.'" value="Upload now" />';
            return $Label.$Required.':<br />'.$Value.'<br />';
        }
        elseif ($SpecialCase == 'checkbox') {
            $HTML = '<input type="checkbox" name="'.$Name.'" value="'.$CheckboxValue.'" ';
            if ($Value != '[Nothing has been recorded in this field.]')
                $HTML .= 'checked="checked" ';
            $HTML .= '/> '.$Label.$Required.'<br />';
            return $HTML;
        }
        elseif ($SpecialCase == 'radio') {
            $LastKey = count($OptionValues) - 1;
            $HTML = $Label.$Required.':<br />';
            foreach($OptionValues as $K => $V) {
                if ($K == $LastKey and substr($V, 0, 5) == 'Other' and $MaxLength <= C5_MAX_BYTES_ZFPF) {
                    // This function does not (yet) handle textarea other fields with radio buttons.
                    $RadioValuesPopped = $OptionValues;
                    array_pop($RadioValuesPopped);
                    $HTML .= '<input type="radio" name="'.$Name.'" value="'.$V.'" ';
                    if ($Value != '[Nothing has been recorded in this field.]' and !in_array($Value, $RadioValuesPopped))
                        $HTML .= 'checked="checked" />'.$V.'<input type="text" name="radio_button_other_1'.$Name.'" value="'.$Value.'" ';
                    else
                        $HTML .= '/>'.$V.'<input type="text" name="radio_button_other_1'.$Name.'" value="[Nothing has been recorded in this field.]" ';
                    $HTML .= 'class="screenwidth" maxlength="'.round($MaxLength/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /><br />';
                }
                else {
                    $HTML .= '<input type="radio" name="'.$Name.'" value="'.$V.'" ';
                    if ($Value == $V)
                        $HTML .= 'checked="checked" ';
                    $HTML .= '/>'.$V.'<br />';
                }
            }
            return $HTML;
        }
        elseif ($SpecialCase == 'dropdown') {
            $HTML = $Label.$Required.': 
            <select name="'.$Name.'">';
            foreach($OptionValues as $V) {
                if ($Value == $V)
                    $HTML .= '<option value="'.$V.'" selected="selected">'.$V.'</option>';
                else
                    $HTML .= '<option value="'.$V.'">'.$V.'</option>';
            }
            $HTML .= '</select><br />';
            return $HTML;
        }
        elseif ($MaxLength > C5_MAX_BYTES_ZFPF) {
            if ($Value == '[Nothing has been recorded in this field.]')
                $Value = ''; // Make textarea fields blank when '[Nothing has been recorded in this field.]'
            return $Label.$Required.':<br /><textarea rows="5" cols="140" class="screenwidth" name="'.$Name.'" maxlength="'.round($MaxLength/HTML_MAX_CHAR_DIVISOR_ZFPF).'">'.$Value.'</textarea><br />';
        }
        else {
            if ($Value == '[Nothing has been recorded in this field.]')
                $Value = ''; // Make text fields blank when '[Nothing has been recorded in this field.]'
            return $Label.$Required.':<br /><input type="text" name="'.$Name.'" value="'.$Value.'" class="screenwidth" maxlength="'.round($MaxLength/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /><br />';
        }
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function echos HTML to based on $htmlFormArray and
    // $Display, which may be modified by calling file after it is created by post_to_display_1e or another function above.
    // $SelectedRow must be passed in if $_SESSION['Selected'] doesn't hold the selected row and the table contains upload_files fields.
    public function make_html_form_1e($htmlFormArray, $Display, $SelectedRow = array()) {
        $Message = '';
        foreach ($htmlFormArray as $KA => $VA) {
            if (isset($VA[2]) and is_array($VA[2])) {
                // Handle second (nested) layer of $htmlFormArray. An encoded array of HTML fields will go into a single database field.
                $Message .= '
                <fieldset><legend>'.$VA[0].$VA[1].'</legend>'; // HTML fieldset used for all of these cases.
                $SubsetCounter = 0;
                foreach ($Display[$KA] as $KB => $VB) {
                    $Name = $KA.'['.$KB.']'; // the HTML input name. Other quoting methods may not work. The HTML input value is $VB.
                    $Label = $VA[2][$SubsetCounter][0]; // Required $htmlFormArray parameter
                    $Required = $VA[2][$SubsetCounter][1]; // Required $htmlFormArray parameter
                    // Get any optional $htmlFormArray parameters. Initialize them to default values in ConfirmZfpf::html_form_field_1e
                    $MaxLength = C5_MAX_BYTES_ZFPF;
                    $SpecialCase = FALSE;
                    if (isset($VA[2][$SubsetCounter][2])) {
                        $MaxLength = $VA[2][$SubsetCounter][2];
                        if (isset($VA[2][$SubsetCounter][3]))
                            $SpecialCase = $VA[2][$SubsetCounter][3];
                    }
                    if ($SubsetCounter == 0)
                        $Message .= '<p>';
                    if ($SpecialCase == 'radio' or $SpecialCase == 'dropdown')
                        $Message .= $this->html_form_field_1e($Name, $VB, $Label, $Required, $MaxLength, $SpecialCase, $VA[2][$SubsetCounter][4]);
                    else
                        $Message .= $this->html_form_field_1e($Name, $VB, $Label, $Required, $MaxLength, $SpecialCase);
                    if (++$SubsetCounter == count($VA[2])) { // count($VA[2]) gives the fields in $htmlFormArray[$KA] -- the subset repeated within $Display[$KA]
                        $Message .= '</p>';
                        $SubsetCounter = 0;
                    }
                }
                if (isset($VA[3]) and $VA[3] == 'add_field')
                    $Message .= '
                    <p><input type="submit" name="add_'.$KA.'" value="Add fields" /></p>';
                $Message .= '
                </fieldset>';
            }
            else {
                // Handle single-layer $htmlFormArray. Similar to above but easier.
                $Message .= '<p>';
                if (isset($VA[3])) {
                    if ($VA[3] == 'radio' or $VA[3] == 'dropdown')
                        $Message .= $this->html_form_field_1e($KA, $Display[$KA], $VA[0], $VA[1], $VA[2], $VA[3], $VA[4]);
                    elseif ($VA[3] == 'upload_files') {
                        $NewDatabaseRow = FALSE;
                        if (isset($SelectedRow['c5who_is_editing']) and $this->decrypt_1c($SelectedRow['c5who_is_editing']) == '[A new database row is being created.]')
                            $NewDatabaseRow = TRUE;
                        elseif (isset($_SESSION['Selected']['c5who_is_editing']) and $this->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]')
                            $NewDatabaseRow = TRUE;
                        $Message .= $this->html_form_field_1e($KA, $Display[$KA], $VA[0], $VA[1], $VA[2], $VA[3], FALSE, 'Yes', $NewDatabaseRow);
                    }
                    else
                        $Message .= $this->html_form_field_1e($KA, $Display[$KA], $VA[0], $VA[1], $VA[2], $VA[3]); // No 8th parameter, so only checkbox value possible is the default, "Yes".
                }
                elseif (isset($VA[2]))
                    $Message .= $this->html_form_field_1e($KA, $Display[$KA], $VA[0], $VA[1], $VA[2]);
                else
                    $Message .= $this->html_form_field_1e($KA, $Display[$KA], $VA[0], $VA[1]);
                $Message .= '</p>';
            }
        }
        return $Message;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function compares user post to database select and the $htmlFormArray.
    // If any required fields, per $htmlFormArray, are not complete, echo list of these to the user.
    // Otherwise, information in modified fields (post vs database) is echoed to user for confirmation.
    // $_SESSION['Selected'] must be set, to handled c6bfn_ fields, and it always would be in this context.
    // $PostDisplay = $this->post_to_display_1e -- with the appropriate $OldDisplay passed to it.
    // $SelectDisplay = $this->select_to_display_1e -- by default it is created from $_SESSION['Selected']
    // app_assigned (except k0 fields) shouldn't require special treatment here, if calling file is implemented properly.
    // $InputNameConfirmPrefix and $InputNameBackPrefix are optional, for allowing one file to distinguish the buttons of different i3 forms.
    // $who_is_editing -- decrypted -- only needs to be passed in if the table contains only not required fields. See contractor_priv_io03.php
    public function post_select_required_compare_confirm_1e($FormActionConfirm, $FormActionBack, $htmlFormArray, $PostDisplay, $SelectDisplay = FALSE, $InputNameConfirmPrefix = '', $InputNameBackPrefix = '', $who_is_editing = '') {
        if (!$SelectDisplay)
            $SelectDisplay = $this->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        foreach ($htmlFormArray as $KA => $VA) {
            $RequiredField = FALSE;
            if ($VA[1] == REQUIRED_FIELD_ZFPF)
                $RequiredField = TRUE;
            if (isset($VA[2]) and is_array($VA[2])) {
                // Handle second (nested) layer of $htmlFormArray. An encoded array of HTML fields will go into a single database field.
                $SubsetCounter = 0;
                foreach ($PostDisplay[$KA] as $KB => $VB) {
                    // c6bfn_ and c5ts_ fields are not allowed as nested $htmlFormArray fields. See 0read_me_psm_cap_app_standards.txt
                    if (($RequiredField or $VA[2][$SubsetCounter][1] == REQUIRED_FIELD_ZFPF) and $VB == '[Nothing has been recorded in this field.]')
                        $RequiredMissing[$KA][$KB] = $VA[2][$SubsetCounter][0];
                    if ($RequiredField and ((isset($VA[2][$SubsetCounter][3]) and $VA[2][$SubsetCounter][3] == 'checkbox') or substr($VA[2][$SubsetCounter][0], 0, 5) == 'Other') and $VB != '[Nothing has been recorded in this field.]') {
                        // If an entire legend set is a required field, completing it may be satisfied by:
                        // checking any check box in it or adding text to any "Other" text field in it. 
                        $RequiredField = FALSE;
                        if (isset($RequiredMissing[$KA]))
                            unset($RequiredMissing[$KA]);
                    }
                    if (!isset($SelectDisplay[$KA][$KB]) or $SelectDisplay[$KA][$KB] != $VB) {
                        // Due to "Add Fields" button, $SelectDisplay[$KA][$KB] may not be set.
                        $ModifiedValues[$KA][$KB] = $VB;
                        $ModifiedFieldNames[$KA][$KB] = $VA[2][$SubsetCounter][0];
                    }
                    if (++$SubsetCounter == count($VA[2])) // count($VA[2]) gives the fields in $htmlFormArray[$KA] -- the subset repeated within $PostDisplay[$KA]
                        $SubsetCounter = 0;
                }
                if (
                    isset($ModifiedValues[$KA]) or 
                    (is_array($PostDisplay[$KA]) and is_array($SelectDisplay[$KA]) and count($PostDisplay[$KA]) < count($SelectDisplay[$KA]))
                ) // The 1st condition indicates something in the array changed; the 2nd condition indicates a deletion with the array.
                    $NewStuff[$KA] = $PostDisplay[$KA]; // Either way, the whole encoded array going into one database field needs to be updated.
            }
            else {
                // Handle single-layer $htmlFormArray. Similar to above but easier.
                $SpecialCase = FALSE;
                if (isset($VA[3]))
                    $SpecialCase = $VA[3];
                if ($RequiredField and ($PostDisplay[$KA] == '[Nothing has been recorded in this field.]' or ($SpecialCase == 'upload_files' and $this->decrypt_1c($_SESSION['Selected'][$KA]) == '[Nothing has been recorded in this field.]')))
                    $RequiredMissing[$KA] = $VA[0];
                if ($SelectDisplay[$KA] != $PostDisplay[$KA] and $SpecialCase != 'upload_files' and substr($KA, 0, 2) != 'k0') {
                    // App insures all variables in above if clause are set. File uploads are confirmed independently, never via this i2 confirm function.
                    $ModifiedValues[$KA] = $PostDisplay[$KA];
                    $NewStuff[$KA] = $PostDisplay[$KA]; // Only the changed fields need to be updated.
                    $ModifiedFieldNames[$KA] = $VA[0];
                }
            }
        }
        if (isset($RequiredMissing) and !$RequiredMissing)
            unset($RequiredMissing);
        // Here to end is the html sent to browsers.
        $Message = '<a id="top"></a>';
        if (isset($RequiredMissing)) {
            // 1. Required information is missing.
            $Message .= '<h2>
            You did not provide the following required information.</h2>';
            foreach ($htmlFormArray as $KA => $VA) {
                if (isset($RequiredMissing[$KA])) {
                    if (isset($VA[2]) and is_array($VA[2])) {
                        $SubsetCounter = 0;
                        $SubsetPara = FALSE;
                        $ParaCounter = 1;
                        $Message .= '<fieldset><legend>'.$VA[0].'</legend>';
                        foreach ($PostDisplay[$KA] as $KB => $VB) {
                            if (!$SubsetPara and isset($RequiredMissing[$KA][$KB])) {
                                $Message .= '<p>';
                                $SubsetPara = TRUE;
                            }
                            if (isset($RequiredMissing[$KA][$KB])) {
                                $Message .= 'Field '.$ParaCounter.'.'.++$SubsetCounter.': '.$RequiredMissing[$KA][$KB].'.<br />'; // Echoing app-defined legends, so nl2br() not needed.
                                --$SubsetCounter; // Just get $SubsetCounter back to prior value for counting within subset.
                            }
                            if (++$SubsetCounter == count($VA[2])) { // count($VA[2]) gives the fields in $htmlFormArray[$KA] -- the subset repeated within $PostDisplay[$KA]
                                $SubsetCounter = 0;
                                ++$ParaCounter;
                                if ($SubsetPara) {
                                    $Message .= '</p>';
                                    $SubsetPara = FALSE;
                                }
                            }
                        }
                        $Message .= '</fieldset>';
                    }
                    else
                        $Message .= '<p>'.$VA[0].'.</p>';
                }
            }
            $Message .= '
            <form action="'.$FormActionBack.'" method="post"><p>
                <input type="submit" name="'.$InputNameBackPrefix.'modify_confirm_post_1e" value="Enter required information" /></p>
            </form>';
        }
        elseif (!isset($ModifiedValues) and $who_is_editing != '[A new database row is being created.]') {
            // 2. User made no changes to recorded information, so provide a notice.
            $Message .= '<p>
            <b>You did not change any information.</b></p>
            <form action="'.$FormActionBack.'" method="post"><p>
                <input type="submit" name="'.$InputNameBackPrefix.'modify_confirm_post_1e" value="Go back and make changes" /></p>
            </form>';
        }
        else {
            // 3. Allow the user to confirm the new or modified information, and save this information in the session variable.
            $Message .= '<p>
            <b>Please confirm that the information you modified, listed below, is complete and correct.</b></p>';
            // Handle a new database row without required fields and no changes.
            if (!isset($ModifiedValues) and $who_is_editing == '[A new database row is being created.]') {
                $NewStuff = $SelectDisplay;
                $Message .= '<p>
                You are creating a new record, and you did not change the default information.</p>';
            }
            else foreach ($htmlFormArray as $KA => $VA) {
                if (isset($ModifiedValues[$KA])) {
                    if (isset($VA[2]) and is_array($VA[2])) {
                        $SubsetCounter = 0;
                        $SubsetPara = FALSE;
                        $Message .= '<fieldset><legend>'.$VA[0].'</legend>';
                        foreach ($PostDisplay[$KA] as $KB => $VB) {
                            if (!$SubsetPara and isset($ModifiedValues[$KA][$KB])) {
                                $Message .= '<p>';
                                $SubsetPara = TRUE;
                            }
                            if (isset($ModifiedValues[$KA][$KB]))
                                $Message .= '<i>'.$ModifiedFieldNames[$KA][$KB].':</i><br />'.nl2br($ModifiedValues[$KA][$KB]).'<br /><br />'; // nl2br() only needed when not echoing within form field.
                            if (++$SubsetCounter == count($VA[2])) { // count($VA[2]) gives the fields in $htmlFormArray[$KA] -- the subset repeated within $PostDisplay[$KA]
                                $SubsetCounter = 0;
                                if ($SubsetPara) {
                                    $Message .= '</p>';
                                    $SubsetPara = FALSE;
                                }
                            }
                        }
                        $Message .= '</fieldset>';
                    }
                    else
                        $Message .= '<p><i>'.$ModifiedFieldNames[$KA].':</i><br />'.nl2br($ModifiedValues[$KA]).'</p>'; // nl2br() only needed when not echoing within form field.
                }
            }
            if ($FormActionConfirm)
                $Message .= '
                <form action="'.$FormActionConfirm.'" method="post"><p>
                <input type="submit" name="'.$InputNameConfirmPrefix.'yes_confirm_post_1e" value="Confirm" /></p>
                </form>';
            $Message .= '<form action="'.$FormActionBack.'" method="post"><p>
            <input type="submit" name="'.$InputNameBackPrefix.'modify_confirm_post_1e" value="Modify" /></p><p>
            <input type="submit" name="'.$InputNameBackPrefix.'undo_confirm_post_1e" value="Discard what you just typed" /></p>
            </form>';
            $_SESSION['Scratch']['ModifiedValues'] = $this->encode_encrypt_1c($NewStuff); // Holds only database fields that changed, but if the database field holds an array, and anything in the array changed, the whole array needs to be updated.
        }
        $Message .= '<a id="bottom"></a>';
        return $this->xhtml_contents_header_1c().$Message.$this->xhtml_footer_1c();
    }

}

