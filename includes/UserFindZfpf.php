<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// Class UserFindZfpf contains function(s) for looking up and selecting users
// in databases where all data elements are encrypted.

require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';

class UserFindZfpf extends CoreZfpf {

    /* 
     *  This function echos a user lookup html form.
     *  $SubmitFile is the html-form action by user clicking submit.
     *  $CancelFile, optional, is the html-form action by user clicking cancel.
     *  $SubmitButtonName, optional, is the HTML name for the button that calls the $SubmitFile. HTML button names become keys in the PHP $_POST array.
     *  $CancelButtonName, optional, is the HTML name for the button that calls the $CancelFile.
     */
    public function lookup_user_1c($SubmitFile, $CancelFile = 'contents0.php', $SubmitButtonName = FALSE, $CancelButtonName = FALSE) {
        echo '<h2>
        Lookup User.</h2><p>
        Only users meeting <b>all of the criteria you enter</b> will be found.<br />
        This search is case-insensitive, but otherwise search criteria must <b>exactly match</b> the information in the database. For example, Fred or Fred* will not match Frederick. But, frederick will match Frederick.<br />
        For phone numbers, only the numbers need to match; for example 614-555-1212 will match (614) 555-1212 and also 6145551212.</p>
        <form action="' . $SubmitFile . '" method="post"><p>
        Family Name:<br />
        <input type="text" name="FamilyName" class="screenwidth" maxlength="'.round(C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /></p><p>
        Primary Given Name:<br />
        <input type="text" name="PrimaryGivenName" class="screenwidth" maxlength="'.round(C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /></p><p>
        Personal Mobile Phone:<br />
        <input type="text" name="PersonalMobilePhone" class="screenwidth" maxlength="'.round(C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /></p>
        Home Phone:<br />
        <input type="text" name="HomePhone" class="screenwidth" maxlength="'.round(C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /></p><p>
        -- Or check below to --<br />
        <input type="checkbox" name="SelectAll" value="1" /> <b>Find all users</b> associated with your currently chosen entity, such as a process, facility, contractor, owner, etc.</p><p>
        <input type="submit"';
        if ($SubmitButtonName)
            echo ' name="' . $SubmitButtonName . '"';
        echo ' value="Search for User" /></p>
        </form>
        <form action="' . $CancelFile . '" method="post"><p>
        <input type="submit"';
        if ($CancelButtonName)
            echo ' name="' . $CancelButtonName . '"';
        echo '" value="Cancel" /></p>
        </form>';
    }

    // This function is a wrapper for lookup_user_2c
    // Input variables are the same, but in a different order, expect:
    // $TableNameUserEntity is the name of the table *from* which potential users will be selected: t0user_process, t0user_factility, t0user_owner, t0user_contractor...
    // $ConditionsUserEntity the standard fpf conditions array for the above select query (may use if limiting search to users associated with an entity), and
    // $ColumnsUserEntity the columns to be returned by the above select query, typically the default value below.
    // The value assigned to $FilterColumnName must be included in the $Columns array, below.
    // $Conditions may be set to 'No Condition -- All Rows Included' to search all users in database instance (for matches with user-supplied keywords)
    public function lookup_user_wrap_2c(
            $TableNameUserEntity,
            $ConditionsUserEntity,
            $SubmitFile,
            $TryAgainFile,
            $ColumnsUserEntity = array('k0user'),
            $CancelFile = 'contents0.php',
            $SpecialText = '',
            $SpecialSubmitButton = 'Submit',
            $SubmitButtonName = FALSE,
            $TryAgainButtonName = FALSE,
            $CancelButtonName = FALSE,
            $FilterColumnName = FALSE,
            $Filter = FALSE,
            $FilterColumnName1 = FALSE,
            $Filter1 = FALSE,
            $Conditions = FALSE
        ) {
        // For display, select only information needed for an admin to confirm a user's identity.
        // To use UserFindZfpf::lookup_user_2c() below, ONLY INCLUDE ONE KEY COLUMN, namely the primary key column for the table you are selecting from.
        // Key columns start with "k" in FACT+FANCY web-apps.
        // 'c5p_global_dbms' is included here to allow verifying that the selected user has adequate global DBMS privileges to be assigned to a task, if applicable.
        $Columns = array(
            'k0user',
            'c5p_global_dbms',
            'c5ts_logon_revoked',
            'c5name_family',
            'c5name_given1',
            'c5name_given2',
            'c5personal_phone_mobile',
            'c5personal_phone_home',
            'c5e_contact_name',
            'c5e_contact_phone'
        );
        $DBMSresource = $this->credentials_connect_instance_1s();
        if (!$Conditions) {
            list($SelectResults1, $RowsReturned1) = $this->select_sql_1s($DBMSresource, $TableNameUserEntity, $ConditionsUserEntity, $ColumnsUserEntity);
            if (!$RowsReturned1)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned1);
            else {
                foreach ($SelectResults1 as $V)
                    $Conditions[] = array ('k0user', '=', $V['k0user'], 'OR');
                unset($Conditions[--$RowsReturned1][3]); // remove the final, hanging, 'OR'.
            }
        }
        // Create parameters for UserFindZfpf::lookup_user_2c()
        // Perform the t0user select with the $Conditions and $Columns defined above.
        list($SelectResults, $RowsReturned) = $this->select_sql_1s($DBMSresource, 't0user', $Conditions, $Columns);
        if ($RowsReturned < 1) {
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' No rows returned from the t0user table. Investigate.');
            $this->close_connection_1s($DBMSresource);
            $this->normal_log_off_1c('An error occurred. Please contact the webmaster for assistance before you try the last operation that you performed again.');
        }
        $this->close_connection_1s($DBMSresource);
        $this->lookup_user_2c($SubmitFile, $TryAgainFile, $SelectResults, $CancelFile, $SpecialText, $SpecialSubmitButton, $FilterColumnName, $Filter, $FilterColumnName1, $Filter1, $SubmitButtonName, $TryAgainButtonName, $CancelButtonName);
    }

    /* 
     *  This function processes the user post from lookup_user_1c() and the SELECT from the calling file.
     *  It assumes ONLY ONE KEY COLUMN, the primary key, has been selected, via the $Columns variable in the calling PHP file.
     *  $SubmitFile, $SubmitButtonName, $CancelFile, and $CancelButtonName are same as in lookup_user_1c().
     *  $TryAgainFile and $TryAgainButtonName allow sends the user back to inputing intomation for looking up a user.
     *  $SelectResults is the result of a SELECT query on the t0user table in the calling PHP file.
     *  The user's privileges or StatePicked determine the conditions of this SELECT query 
     *  $SpecialText allows echoing unique text just above the submit button.
     *  $SpecialSubmitButton allows unique text to replace "submit" in this button.
     *  $FilterColumnName and $Filter allow limit returned users to when the data under $FilterColumnName === $Filter.
     */
    // TO DO 2019-05-29 Note: reminders if rewriting this function:
    //    - Because the database entries are encrypted, array_keys() is used to match search criteria 
    //    - and array_multisort() is used to sort the matches, rather than using SQL -- WHERE and ORDER BY.
    public function lookup_user_2c($SubmitFile, $TryAgainFile, $SelectResults, $CancelFile = 'contents0.php', $SpecialText = '', $SpecialSubmitButton = 'Submit', $FilterColumnName = FALSE, $Filter = FALSE, $FilterColumnName1 = FALSE, $Filter1 = FALSE, $SubmitButtonName = FALSE, $TryAgainButtonName = FALSE, $CancelButtonName = FALSE) {
        if ($SubmitFile) {
            // Decrypt, and escape html special characters from, $SelectResults -- excluding key columns, which start with "k".
            // Create 1-layer arrays to compare against user posts.
            foreach ($SelectResults as $K1 => $V1)
                foreach ($V1 as $K2 => $V2) {
                    if (substr($K2, 0,1) != 'k')
                        $SelectResults[$K1][$K2] = $this->decrypt_1c($V2);
                    else // This assumes ONLY ONE KEY COLUMN, the primary key, has been selected, via the $Columns variable in the calling PHP file.
                        $SelectedPrimaryKey[$K1] = $V2;
                    if (isset($SelectResultsString[$K1]))
                        $SelectResultsString[$K1] .= $SelectResults[$K1][$K2];
                    else
                        $SelectResultsString[$K1] = $SelectResults[$K1][$K2];
                }
            // Eliminate duplicated values from $SelectResultsString, this is needed for history tables
            // for example, where there may be multiple identical arrays in $SelectResults (queried from a history table).
            // preg_replace('~[^0-9]~', '', $String) removes from $String all characters except 0 to 9.
            $SelectResultsString = array_unique($SelectResultsString);
            foreach ($SelectResultsString as $K1 => $V1) {
                if($Filter and $FilterColumnName) {
                    if ($Filter1 and $FilterColumnName1) {
                        if ($SelectResults[$K1][$FilterColumnName] === $Filter and $SelectResults[$K1][$FilterColumnName1] === $Filter1) {
                            $FamilyArray[$K1]  = $this->to_lower_case_1c($SelectResults[$K1]['c5name_family']);
                            $Given1Array[$K1]  = $this->to_lower_case_1c($SelectResults[$K1]['c5name_given1']);
                            $MobileArray[$K1]  = preg_replace('~[^0-9]~', '', $SelectResults[$K1]['c5personal_phone_mobile']);
                            $HomeArray[$K1]  = preg_replace('~[^0-9]~', '', $SelectResults[$K1]['c5personal_phone_home']);
                        }
                    }
                    elseif ($SelectResults[$K1][$FilterColumnName] === $Filter) {
                        $FamilyArray[$K1]  = $this->to_lower_case_1c($SelectResults[$K1]['c5name_family']);
                        $Given1Array[$K1]  = $this->to_lower_case_1c($SelectResults[$K1]['c5name_given1']);
                        $MobileArray[$K1]  = preg_replace('~[^0-9]~', '', $SelectResults[$K1]['c5personal_phone_mobile']);
                        $HomeArray[$K1]  = preg_replace('~[^0-9]~', '', $SelectResults[$K1]['c5personal_phone_home']);
                    }
                }
                else {
                    // The following 1-layer numeric arrays are need to use array_keys() and arraymultisort() below.
                    $FamilyArray[$K1]  = $this->to_lower_case_1c($SelectResults[$K1]['c5name_family']);
                    $Given1Array[$K1]  = $this->to_lower_case_1c($SelectResults[$K1]['c5name_given1']);
                    $MobileArray[$K1]  = preg_replace('~[^0-9]~', '', $SelectResults[$K1]['c5personal_phone_mobile']);
                    $HomeArray[$K1]  = preg_replace('~[^0-9]~', '', $SelectResults[$K1]['c5personal_phone_home']);
                }
            }
            // Check user post.
            // Get $SelectResults keys that match user post.
            // Case-insensitive comparison for names only, not phone numbers.
            // array_keys() used instead of array_search() to return all matches, not just the first one.
            if (isset($_POST['SelectAll']) and $_POST['SelectAll'] == 1)
                $Match = array_keys($FamilyArray);
            else {
                $Match = 0;
                $NoMatch = 0;
                $FamilyName = $this->xss_prevent_1c($this->post_length_blank_1c('FamilyName'));
                if ($FamilyName !== '[Nothing has been recorded in this field.]') {
                    $FamilyName = $this->to_lower_case_1c($FamilyName);
                    $Match = array_keys($FamilyArray, $FamilyName);
                    if (!$Match)
                        $NoMatch = 1;
                }
                $PrimaryGivenName = $this->xss_prevent_1c($this->post_length_blank_1c('PrimaryGivenName'));
                if ($PrimaryGivenName !== '[Nothing has been recorded in this field.]' and !$NoMatch) {
                    $PrimaryGivenName = $this->to_lower_case_1c($PrimaryGivenName);
                    $Given1Match = array_keys($Given1Array, $PrimaryGivenName);
                    if (!$Given1Match) {
                        $NoMatch = 1;
                        $Match = 0;
                    }
                    else {
                        if ($Match)
                            $Match = array_intersect($Match, $Given1Match);
                        else
                            $Match = $Given1Match;
                    }
                }
                // $this->xss_prevent_1c() is not needed for comparison with SELECTed info because preg_replace('~[^0-9]~', '', $String) gets rid of them.
                $PersonalMobilePhone = $this->post_length_blank_1c('PersonalMobilePhone');
                if ($PersonalMobilePhone !== '[Nothing has been recorded in this field.]' and !$NoMatch) {
                    $PersonalMobilePhone = preg_replace('~[^0-9]~', '', $PersonalMobilePhone);
                    $MobileMatch = array_keys($MobileArray, $PersonalMobilePhone);
                    if (!$MobileMatch) {
                        $NoMatch = 1;
                        $Match = 0;
                    }
                    else {
                        if ($Match)
                            $Match = array_intersect($Match, $MobileMatch);
                        else
                            $Match = $MobileMatch;
                    }
                }
                $HomePhone = $this->post_length_blank_1c('HomePhone');
                if ($HomePhone !== '[Nothing has been recorded in this field.]' and !$NoMatch) {
                    $HomePhone = preg_replace('~[^0-9]~', '', $HomePhone);
                    $HomeMatch = array_keys($HomeArray, $HomePhone);
                    if (!$HomeMatch) {
                        $NoMatch = 1;
                        $Match = 0;
                    }
                    else {
                        if ($Match)
                            $Match = array_intersect($Match, $HomeMatch);
                        else
                            $Match = $HomeMatch;
                    }
                }
            }
            echo $this->xhtml_contents_header_1c('Select User');
            if ($Match) {
                // Eliminate duplicated values from $Match.
                // Not be needed for above array_intersect() (AND) searching because the master array, indeed non of the "match" arrays,
                // will not have duplicate values.
                // This would be needed for array_merge() (OR) searching.
                // $Match = array_unique($Match);
                // Sort $SelectResults that match user post ascending by names,
                // which requires creating 1-layer arrays of names with keys matching $SelectResults.
                // Address case of no family name (parts of Eastern Europe, Malasia, Iceland, etc.)
                // Store primary key valves in $_SESSION['Scratch']['PlainText']['lookup_user'] so the user cannot see them because 
                // the $_SESSION['Scratch']['PlainText']['lookup_user'] keys are put in the output html form and passed to next PHP file via a post, 
                // rather than the primary key valves.
                if (isset($_SESSION['Scratch']['PlainText']['lookup_user']))
                    unset($_SESSION['Scratch']['PlainText']['lookup_user']);
                foreach ($Match as $K => $V) {
                    $MatchSelectResults[$K] = $SelectResults[$V];
                    $_SESSION['Scratch']['PlainText']['lookup_user'][$K] = $SelectedPrimaryKey[$V];
                    if ($FamilyArray[$V] === '[Nothing has been recorded in this field.]') {
                        $Sort1[$K] = $Given1Array[$V];
                        $Sort2[$K] = $this->to_lower_case_1c($SelectResults[$V]['c5name_given2']);
                        $Sort3[$K] = ' '; // A space is used because it typically collates first.
                        $MatchSelectResults[$K]['Punctuation'] = '';
                    }
                    else {
                        $Sort1[$K] = $FamilyArray[$V];
                        $Sort2[$K] = $Given1Array[$V];
                        $Sort3[$K] = $this->to_lower_case_1c($SelectResults[$V]['c5name_given2']);
                        $MatchSelectResults[$K]['Punctuation'] = ', ';
                    }
                }
                // Use array_multisort to renumber and sort the numeric key of $MatchSelectResults based on the above names arrays.
                array_multisort($Sort1, $Sort2, $Sort3, $MatchSelectResults, $_SESSION['Scratch']['PlainText']['lookup_user']);
                // Update the form action below when reusing this file.
                echo '<h2>
                Pick one user below.</h2>
                <form action="' . $SubmitFile . '" method="post"><p><b>
                Names starting with the 26 standard English letters are listed first, sorted case insensitive.</b> Names starting with other characters are listed after these.</p>';
                foreach ($MatchSelectResults as $K => $V) {
                    // Address missing information for some fields. 
                    // For other fields, [Nothing has been recorded in this field.] will be displayed if this is the case.
                    if ($V['c5name_given2'] === '[Nothing has been recorded in this field.]')
                        $V['c5name_given2'] = '';
                    if ($V['c5name_family'] === '[Nothing has been recorded in this field.]') {
                        $V['c5name_family'] = '';
                        $V['Punctuation'] = '';
                    }
                    echo '<p>
                    <input type="radio" name="Selected" value="' . $K . '" />  ' . $V['c5name_family'] . $V['Punctuation'] . $V['c5name_given1'] . ' ' . $V['c5name_given2'] . ' <i>(any family or similar name, primary given name then any other names)</i><br />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Personal Mobile Phone Number:</i> ' . $V['c5personal_phone_mobile'] . '<br />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Personal Home Phone Number:</i> ' . $V['c5personal_phone_home'] . '<br />
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Emergency Contact Name and Phone:</i> ' . $V['c5e_contact_name'] . ', ' . $V['c5e_contact_phone'];
                    if (isset($V['c5p_global_dbms'])) {
                        echo '<br />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Global DBMS Privileges:</i> ' . $V['c5p_global_dbms'] . '</p>';
                        $_SESSION['Scratch'][$K]['c5p_global_dbms'] = $this->encrypt_1c($V['c5p_global_dbms']);
                    }
                    else
                        echo '</p>';
                }
                echo $SpecialText . '<p>
                <input type="submit"';
                if ($SubmitButtonName)
                    echo ' name="' . $SubmitButtonName . '"';
                echo ' value="' . $SpecialSubmitButton . '" /></p>
                </form>';
                if ($TryAgainFile) {
                    echo '
                    <form action="' . $TryAgainFile . '" method="post"><p>
                    <input type="submit"';
                    if ($TryAgainButtonName)
                        echo ' name="' . $TryAgainButtonName . '"';
                    echo ' value="Look for a Different User" /></p>
                    </form>';
                }
            }
            else {
                echo '<p>
                No users matched the conditions you input.</p><p>
                Use the Employee (User) History look-up to find a user whose name changed.</p><p>
                Make certain the user exists and is associated with the owner, contractor, facility etc. that you are currently associated with.</p><p>
                Or, use more general conditions.</p>';
                if ($TryAgainFile) {
                    echo '
                    <form action="' . $TryAgainFile . '" method="post"><p>
                    <input type="submit"';
                    if ($TryAgainButtonName)
                        echo ' name="' . $TryAgainButtonName . '"';
                    echo ' value="Try Again" /></p>
                    </form>';
                }
            }
        }
        else
            echo $SpecialText;
        echo '
        <form action="' . $CancelFile . '" method="post"><p>
        <input type="submit"';
        if ($CancelButtonName)
            echo ' name="' . $CancelButtonName . '"';
        echo ' value="Cancel" /></p>
        </form>'.$this->xhtml_footer_1c();
        $this->save_and_exit_1c();
    }

}

