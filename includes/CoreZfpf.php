<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

/*
 * The following are provided by the functions within the "Core" class (CoreZfpf) defined in this file:
 * removing the app's edit lock
 * log off
 * hashing
 * encrypt and decrypt
 * post isset, length, and blank field checking
 * getting user and user-context information
 * password check
 * idle-time checks requiring either a password check or automatic log off
 * standard HTML for contents, headers, and footers 
 *    Goal is for all HTML output by app to validate as XHTML 1.0 Strict, except for specific HTML5 specified in file
 *    0read_me_psm_cap_app_standards.txt
 * database interaction functions
 * miscellaneous other commonly used functions.
 */

require SETTINGS_DIRECTORY_PATH_ZFPF.'/CoreSettingsZfpf.php';

class CoreZfpf {

    ////////////////////////////////////////////////////////////////////////////
    // This function returns the table name and primary key name from a selected row,
    // such as t0user and k0user.
    public function table_and_key_names_1c($SelectedRow) {   
        $Keys = array_keys($SelectedRow);
        // In FACT+FANCY apps, the primary key is always the first column in a table and 
        // the first array key in $SelectedRow or $_SESSION['Selected'], except for $_SESSION['Selected']['lookup_user'].
        $PrimaryKeyName = $Keys[0];
        // The the table name and primary-key name, in FACT+FANCY apps, are always the same, with the first letter changed from k to t, for example, k0user and t0user.
        $TableName = substr_replace($PrimaryKeyName, 't', 0, 1);
        $TableRoot = substr($PrimaryKeyName, 2); // The is the primary key (or table) name without the first two characters, k0 and t0, respectively.
        return array($TableName, $PrimaryKeyName, $TableRoot);
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function checks if anyone else is editing a selected row.
    // Either $SelectedRow or $_SESSION['Selected'] must be passed in/set and contain one row queried from the $TableRoot table.
    // If $SelectedRow is passed in, FOLLOW THIS EXAMPLE:
    //          $_SESSION['Scratch']['t0subprocess'] = $____->edit_lock_1c('subprocess', 'subprocess', $_SESSION['Scratch']['t0subprocess']);
    // $TableRoot, optional, just speeds up the function, for t0subprocess, $TableRoot is subprocess (the schema teable name without fpf prefix.)
    // $RecordName, optional, may be c5name decrypted or some other method for naming a row (the record) in the $TableRoot table.
    //
    // If no edit lock is in place, one is put in place, $SelectResults[0] is returned, 
    // and if (!$PassedInRow) and $_SESSION['Selected'] is updated. Calling script continues executing.
    //
    // If there is an edit lock, any $_SESSION['Selected'] is unset, information about the edit lock is echoed to the user
    // and the function exits, so the calling script does not continue executing.
    public function edit_lock_1c($TableRoot = '', $RecordName = '', $PassedInRow = FALSE) {
        if ($PassedInRow)
            $SelectedRow = $PassedInRow;
        elseif (isset($_SESSION['Selected']))
            $SelectedRow = $_SESSION['Selected'];
        else
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' edit_lock_1c Eject Case 1');
        if ($TableRoot) {
            $PrimaryKeyName = 'k0'.$TableRoot;
            $TableName = 't0'.$TableRoot;
        }
        else
            list($TableName, $PrimaryKeyName) = $this->table_and_key_names_1c($SelectedRow);
        $Conditions[0] = array($PrimaryKeyName, '=', $SelectedRow[$PrimaryKeyName]);
        if (isset($_SESSION['Selected']['k2username_hash']) and $_SESSION['Selected']['k0user'] == $_SESSION['t0user']['k0user'] and $this->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
            $DBMSresource = $this->credentials_connect_instance_1s('logon maintenance'); // User self-editing their user record.
        else
            $DBMSresource = $this->credentials_connect_instance_1s();
        list($SelectResults, $RowsReturned) = $this->select_sql_1s($DBMSresource, $TableName, $Conditions);
        if ($RowsReturned != 1)
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned.' edit_lock_1c Eject Case 2');
        // Standard who_is_editing check.
        $who_is_editing = $this->decrypt_1c($SelectResults[0]['c5who_is_editing']);
        if ($who_is_editing != '[Nobody is editing.]' and $who_is_editing != substr($this->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF)) {
            echo $this->xhtml_contents_header_1c('Edit Lock').'<h1>
            Edit Lock</h1>';
            if (substr($who_is_editing, 0, 19) != 'PERMANENTLY LOCKED:')
                echo '<p><b>'.$who_is_editing.' is editing this '.$RecordName.' record, so the action you requested cannot be done.</b></p><p>
                If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
            else
                echo '<p>'.$who_is_editing.'</p>'; // This should echo the permanent-lock message.
            echo '
            <form action="contents0.php" method="post"><p><a id="bottom"></a>
                <input type="submit" value="Go to Contents" /></p>
            </form>
            '. $this->xhtml_footer_1c();
            $this->close_connection_1s($DBMSresource);
            $this->save_and_exit_1c();
        }
        if ($who_is_editing == '[Nobody is editing.]') { // UPDATE c5who_is_editing with the current user's contact information.
            $Changes['c5who_is_editing'] = $this->encrypt_1c(substr($this->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF));
            $Affected = $this->update_sql_1s($DBMSresource, $TableName, $Changes, $Conditions, FALSE); // Not recorded in t0history
            if ($Affected != 1)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected.' edit_lock_1c Eject Case 3');
            $SelectResults[0]['c5who_is_editing'] = $Changes['c5who_is_editing'];
        }
        $this->close_connection_1s($DBMSresource);
        if (!$PassedInRow)
            $_SESSION['Selected'] = $SelectResults[0]; // Only update $_SESSION['Selected'] if a special selected row wasn't passed in.
        return $SelectResults[0];
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function removes the app edit lock.
    // $_SESSION['Selected'] is only updated if nothing is passed in.
    //
    // By this app's standard, allowed values for c5who_is_editing (in the database) are:
    //   * '[Nobody is editing.]' or
    //   * substr($this->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF) for this editing users
    // When inserting a new row, $_SESSION['Selected']['c5who_is_editing'] == '[A new database row is being created.]'
    // but this changed to '[Nobody is editing.]' immediately before INSERTing the new row, so is never in the database.
    // Even if the database c5who_is_editing already contained '[Nobody is editing.]', encrypted, 
    // $Affected should never equal zero, because the newly encrypted [Nobody is editing.] will not match the former encryption.
    public function core_clear_edit_lock_1c($PassedInRow = FALSE) {
        if ($PassedInRow)
            $SelectedRow = $PassedInRow;
        elseif (isset($_SESSION['Selected']))
            $SelectedRow = $_SESSION['Selected'];
        if (isset($SelectedRow['c5who_is_editing'])) {
            list($TableName, $PrimaryKeyName) = $this->table_and_key_names_1c($SelectedRow);
            $Conditions[0] = array($PrimaryKeyName, '=', $SelectedRow[$PrimaryKeyName]);
            $DBMSresource = $this->credentials_connect_instance_1s(MAX_PRIVILEGES_ZFPF); // Provide maximum global DBMS privileges in case a user without update privileges was editing their personal information or user privileges were downgraded while editing.
            list($SR, $RR) = $this->select_sql_1s($DBMSresource, $TableName, $Conditions); // Refresh selected row from database in case stale.
            if ($RR == 1) {
                $SelectedRow = $SR[0];
                if (!$PassedInRow) // Means $SelectedRow came from un-refreshed $_SESSION['Selected']
                    $_SESSION['Selected'] = $SelectedRow; // Use as opportunity to refresh $_SESSION['Selected']
            }
            $who_is_editing = $this->decrypt_1c($SelectedRow['c5who_is_editing']);
            if (($who_is_editing != '[Nobody is editing.]' and isset($SelectedRow['k0user']) and isset($SelectedRow['k2username_hash']) and isset($_SESSION['t0user']['k0user']) and $SelectedRow['k0user'] == $_SESSION['t0user']['k0user']) or ($who_is_editing == substr($this->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF) and substr($who_is_editing, 0, 19) != 'PERMANENTLY LOCKED:')) { // Only reset c5who_is_editing if self-editing t0user or the current user is editing and not permanently locked.
                $Changes['c5who_is_editing'] = $this->encrypt_1c('[Nobody is editing.]');
                $Affected = $this->update_sql_1s($DBMSresource, $TableName, $Changes, $Conditions, FALSE); // Not recorded in t0history
                if ($Affected != 1)
                    error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::core_clear_edit_lock_1c() Error Log Case 1. Affected Rows: '.@$Affected); // Don't call eject_1c() in this function to avoid a loop. This function is called by log_off_1c()
                if (!$PassedInRow)
                    $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
                return $Changes['c5who_is_editing'];
            }
            else
                return $SelectedRow['c5who_is_editing']; // Return prior who_is_editing info if database was not updated.
            $this->close_connection_1s($DBMSresource);
        }
    }

    // This function allows recursive calls to core_clear_edit_lock_1c
    // WARNING, calling this without a passed-in row will only clear edit locks in $_SESSION['Selected']
    // So, if calling edit_lock_1c with a passed-in row, need to add special case below, so, for example, administer1.php clears the edit lock.
    // TO DO look for better solution to special cases below.
    public function clear_edit_lock_1c($PassedInRow = FALSE) {
        // Handle special cases
        if (isset($_SESSION['Selected']['k0pha']) and !$PassedInRow) { // Check for edit_lock on subprocesses. See pha_io03.php, team_leader_approval_1 and scenario_io03.php
            $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
            list($SR_SP, $RR_SP) = $this->one_shot_select_1s('t0subprocess', $Conditions); // Use user global privileges.
            if ($RR_SP) foreach ($SR_SP as $V_SP)
                $this->core_clear_edit_lock_1c($V_SP);
        }
        if (isset($_SESSION['Scratch']['t0lepc']) and !$PassedInRow)
            $this->core_clear_edit_lock_1c($_SESSION['Scratch']['t0lepc']);
        if (isset($_SESSION['Scratch']['OC']['Selected']) and !$PassedInRow)
            $this->core_clear_edit_lock_1c($_SESSION['Scratch']['OC']['Selected']);
        // TEMPLATE if (isset() and !$PassedInRow)
        // Handle general case.
        return $this->core_clear_edit_lock_1c($PassedInRow);
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function unsets $_SESSION variables not needed for 
    // the main and left-hand contents pages and similar.
    public function session_cleanup_1c() {
        $this->clear_edit_lock_1c(); // In case user landed here from editing a record.
        if (isset($_SESSION['SelectResults']))
            unset($_SESSION['SelectResults']);
        if (isset($_SESSION['Selected']))
            unset($_SESSION['Selected']);
        if (isset($_SESSION['Scratch']))
            unset($_SESSION['Scratch']);
        if (isset($_SESSION['Post']))
            unset($_SESSION['Post']);
        // Call only when user's division, fragment, and practice selections can be reset, like in administer1.php.
        if (isset($_SESSION['StatePicked']['t0division']))
            unset($_SESSION['StatePicked']['t0division']);
        if (isset($_SESSION['StatePicked']['t0fragment']))
            unset($_SESSION['StatePicked']['t0fragment']);
        if (isset($_SESSION['StatePicked']['t0practice']))
            unset($_SESSION['StatePicked']['t0practice']);
        if (isset($_SESSION['t0user_practice']))
            unset($_SESSION['t0user_practice']);
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function decrypts a six-level array.
    // For example, decrypting $_SESSION so it can be encoded.
    // $Array must have every value encrypted, except keys whose values may hold an
    // unencrypted database-field data (second character is 0, 1, 2, 3, or 4)
    // $Array['PlainText'], at any level of $Array, is a sub-array reserved for plain text (not encrypted).
    // If something besides an array is passed in, it is returned, unchanged.
    public function decrypt_array_1c($Array) {
        if (is_array($Array)) foreach ($Array as $K1 => $V1) {
            if (is_array($V1) and $K1 !== 'PlainText') foreach ($V1 as $K2 => $V2) { // Must use !== because 0 != 'PlainText' evaluates FALSE
                if (is_array($V2) and $K2 !== 'PlainText') foreach ($V2 as $K3 => $V3) {
                    if (is_array($V3) and $K3 !== 'PlainText') foreach ($V3 as $K4 => $V4) {
                        if (is_array($V4) and $K4 !== 'PlainText') foreach ($V4 as $K5 => $V5) {
                            if (is_array($V5) and $K5 !== 'PlainText') foreach ($V5 as $K6 => $V6) {
                                if (is_array($V6))
                                    $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' decrypt_array_1c() Eject Case 1. Input array had more than six levels.');
                                if ($K6 !== 'PlainText') {
                                    $Scnd = substr($K6, 1, 1);
                                    if (is_numeric($K6) or !is_numeric($Scnd) or $Scnd > 4)
                                        $Array[$K1][$K2][$K3][$K4][$K5][$K6] = $this->decrypt_1c($V6);
                                }
                            }
                            elseif ($K5 !== 'PlainText') {
                                $Scnd = substr($K5, 1, 1);
                                if (is_numeric($K5) or !is_numeric($Scnd) or $Scnd > 4)
                                    $Array[$K1][$K2][$K3][$K4][$K5] = $this->decrypt_1c($V5);
                            }
                        }
                        elseif ($K4 !== 'PlainText') {
                            $Scnd = substr($K4, 1, 1);
                            if (is_numeric($K4) or !is_numeric($Scnd) or $Scnd > 4)
                                $Array[$K1][$K2][$K3][$K4] = $this->decrypt_1c($V4);
                        }
                    }
                    elseif ($K3 !== 'PlainText') {
                        $Scnd = substr($K3, 1, 1);
                        if (is_numeric($K3) or !is_numeric($Scnd) or $Scnd > 4)
                            $Array[$K1][$K2][$K3] = $this->decrypt_1c($V3);
                    }
                }
                elseif ($K2 !== 'PlainText') {
                    $Scnd = substr($K2, 1, 1);
                    if (is_numeric($K2) or !is_numeric($Scnd) or $Scnd > 4)
                        $Array[$K1][$K2] = $this->decrypt_1c($V2);
                }
            }
            elseif ($K1 !== 'PlainText') {
                $Scnd = substr($K1, 1, 1);
                if (is_numeric($K1) or !is_numeric($Scnd) or $Scnd > 4)
                    $Array[$K1] = $this->decrypt_1c($V1);
            }
        }
        return $Array;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function encrypts whatever above CoreZfpf::decrypt_array_1c decrypts.
    public function encrypt_array_1c($Array) {
        if (is_array($Array)) foreach ($Array as $K1 => $V1) {
            if (is_array($V1) and $K1 !== 'PlainText') foreach ($V1 as $K2 => $V2) { // Must use !== because 0 != 'PlainText' evaluates FALSE
                if (is_array($V2) and $K2 !== 'PlainText') foreach ($V2 as $K3 => $V3) {
                    if (is_array($V3) and $K3 !== 'PlainText') foreach ($V3 as $K4 => $V4) {
                        if (is_array($V4) and $K4 !== 'PlainText') foreach ($V4 as $K5 => $V5) {
                            if (is_array($V5) and $K5 !== 'PlainText') foreach ($V5 as $K6 => $V6) {
                                if (is_array($V6))
                                    $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' encrypt_array_1c() Eject Case 1. Input array had more than six levels.');
                                if ($K6 !== 'PlainText') {
                                    $Scnd = substr($K6, 1, 1);
                                    if (is_numeric($K6) or !is_numeric($Scnd) or $Scnd > 4)
                                        $Array[$K1][$K2][$K3][$K4][$K5][$K6] = $this->encrypt_1c($V6);
                                }
                            }
                            elseif ($K5 !== 'PlainText') {
                                $Scnd = substr($K5, 1, 1);
                                if (is_numeric($K5) or !is_numeric($Scnd) or $Scnd > 4)
                                    $Array[$K1][$K2][$K3][$K4][$K5] = $this->encrypt_1c($V5);
                            }
                        }
                        elseif ($K4 !== 'PlainText') {
                            $Scnd = substr($K4, 1, 1);
                            if (is_numeric($K4) or !is_numeric($Scnd) or $Scnd > 4)
                                $Array[$K1][$K2][$K3][$K4] = $this->encrypt_1c($V4);
                        }
                    }
                    elseif ($K3 !== 'PlainText') {
                        $Scnd = substr($K3, 1, 1);
                        if (is_numeric($K3) or !is_numeric($Scnd) or $Scnd > 4)
                            $Array[$K1][$K2][$K3] = $this->encrypt_1c($V3);
                    }
                }
                elseif ($K2 !== 'PlainText') {
                    $Scnd = substr($K2, 1, 1);
                    if (is_numeric($K2) or !is_numeric($Scnd) or $Scnd > 4)
                        $Array[$K1][$K2] = $this->encrypt_1c($V2);
                }
            }
            elseif ($K1 !== 'PlainText') {
                $Scnd = substr($K1, 1, 1);
                if (is_numeric($K1) or !is_numeric($Scnd) or $Scnd > 4)
                    $Array[$K1] = $this->encrypt_1c($V1);
            }
        }
        return $Array;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function logs off the user
    // It should only be used in the logon.php script, before the database has been updated that the user is logged on;
    // for other cases, use either normal_log_off_1c or eject_1c.
    // normal_log_off_1c allows user to on again. 
    // eject_1c means an administrator must take an action before a user can log on again.
    public function log_off_1c($DBMSresource = FALSE) {
        if (!$DBMSresource) {
            $DBMSresource = $this->credentials_connect_instance_1s('logon maintenance');
            $CloseConnection = TRUE;
        }
        // Attempt to remove the app's edit-locks, regardless of whether a normal_log_off_1c or eject_1c.
        $this->clear_edit_lock_1c();
        // Delete row in t0session_ids holding the session_id. Cannot use SQL select because session_ids are encrypted. And, each use may have more than one of them, so cannot use k0user to lookup the session_id.
        $SessionID = session_id();
        if ($SessionID) {
            list($SR, $RR) = $this->select_sql_1s($DBMSresource, 't0session_ids', 'No Condition -- All Rows Included');
            if ($RR) foreach ($SR as $V)
                if ($SessionID == $this->decrypt_1c($V['c5session_id'])) {
                    $Conditions[0] = array('k0session_ids', '=', $V['k0session_ids']);
                    $Affected = $this->delete_sql_1s($DBMSresource, 't0session_ids', $Conditions, FALSE);
                    if ($Affected != 1)
                        error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::log_off_1c Error Log Case 1. Affected Rows: '.@$Affected);
                    break;
                }
        }
        if (isset($CloseConnection))
            $this->close_connection_1s($DBMSresource);
        if (isset($_SESSION))
            $_SESSION = array();
        // Below is standard log off code per the PHP Manual example.
        $CookieInfo = session_get_cookie_params();
        setcookie(session_name(), '', time()-42000, $CookieInfo['path'], $CookieInfo['domain'], $CookieInfo['secure'], $CookieInfo['httponly']);
        session_destroy();
    }

    ////////////////////////////////////////////////////////////////////////////
   // This function logs off every session whose session_id is a key of the array
   // $ActiveSessions, which holds t0user:c6active_sessions info after decrypting and decoding.
    public function log_off_all_1c($ActiveSessions, $DBMSresource = FALSE) {
        if ($ActiveSessions) {
            if (!$DBMSresource) {
                $DBMSresource = $this->credentials_connect_instance_1s('logon maintenance');
                $CloseConnection = TRUE;
            }
            foreach ($ActiveSessions as $K => $V) {
                session_id($K); // Set the session_id to the stored session.
                session_start(); // Start a session with the prior session_id.
                $this->log_off_1c($DBMSresource); // Log off that session.
            }
            if (isset($CloseConnection))
                $this->close_connection_1s($DBMSresource);
        }
        return array(); // Helps with normal usage of this function, see eject_1c below.
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function logs off the user and exits the current file without updating the database that the user is logged off,
    // so an administrator has to reset the user's account before the user can log on.
    // Use when hacking may be occuring, a serious error occurs, or a database interaction error occurs.
    public function eject_1c($ErrorLog = '') {
        if ($ErrorLog)
            error_log($ErrorLog);
        if (isset($DBMSresource))
            $this->close_connection_1s($DBMSresource); // DBMS connection with adequate credentials created below.
        $DBMSresource = $this->credentials_connect_instance_1s('logon maintenance');
        $SessionID = session_id();
        if (isset($_SESSION['t0user'])) {
            $Conditions[0] = array('k0user', '=', $_SESSION['t0user']['k0user']);
            list($SR, $RR) = $this->select_sql_1s($DBMSresource, 't0user', $Conditions, array('c6active_sessions')); // Get latest from database
            if ($RR == 1) { // Don't eject otherwise, to avoid loop.
                $ActiveSessions = $this->check_array_1c($this->decrypt_decode_1c($SR[0]['c6active_sessions']));
                $ServerActiveSessions = $this->check_array_1c($this->decrypt_decode_1c($_SESSION['t0user']['c6active_sessions']));
                foreach ($ServerActiveSessions as $K => $V)
                    if (!isset($ActiveSessions[$K]))
                        $ActiveSessions[$K] = $V; // Get all possible session_ids to destroy later.
            }
            else // Use c6active_sessions from session variable if new one not retrieved from database.
                $ActiveSessions = $this->check_array_1c($this->decrypt_decode_1c($_SESSION['t0user']['c6active_sessions']));
            // Log off current session first because that's top priority, in case error later.
            if (isset($ActiveSessions[$SessionID]))
                unset($ActiveSessions[$SessionID]); // Allows calling log_off_all_1c after log_off_1c without repeating logoff
            else // !$ActiveSessions or !isset($ActiveSessions[$SessionID])
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::eject_1c Error Log Case 1. Session ID from cookie not found in database-stored session IDs when ejecting user');
            $this->log_off_1c($DBMSresource); // Destroy current session even if no stored session ID was found. $_SESSION is now an empty array.
            $LogOffCalledOnCurrentForSure = TRUE;
            if ($ActiveSessions)
                $this->log_off_all_1c($ActiveSessions, $DBMSresource); // Log off all regardless.
            $Changes['c6active_sessions'] = $this->encode_encrypt_1c(array()); // Needed to handle single logon, log_off_1c, above.
            // TO DO FOR PRODUCTION VERSION - verify line below is not commented out.
            $Changes['s5password_hash'] = $this->encrypt_1c($this->hash_1c(openssl_random_pseudo_bytes(16))); // Will overwrite password...
            $Affected = $this->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, FALSE); // Not recorded in t0history
            if (!$Affected)
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected.'. CoreZfpf::eject_1c Error Log Case 2. URGENT: A POTENTIAL HACKER MAY STILL HAVE ACCESS');
            elseif ($Affected > 1)
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected.'. CoreZfpf::eject_1c Error Log Case 3');
        }
        elseif ($SessionID) {
            list($SRA, $RRA) = $this->select_sql_1s($DBMSresource, 't0session_ids', 'No Condition -- All Rows Included');
            if ($RRA) foreach ($SRA as $VA) { // Cannot use SQL select because session_ids are encrypted.
                if ($SessionID == $this->decrypt_1c($VA['c5session_id'])) {
                    $Conditions[0] = array('k0user', '=', $VA['k0user']);
                    list($SRB, $RRB) = $this->select_sql_1s($DBMSresource, 't0user', $Conditions, array('c6active_sessions'));
                    if ($RRB == 1) { // Don't eject if $RRB != 1 to avoid loop.
                        $ActiveSessions = $this->check_array_1c($this->decrypt_decode_1c($SRB[0]['c6active_sessions']));
                        if (!isset($ActiveSessions[$SessionID])) // Low chance, but might happen.
                            $this->log_off_1c($DBMSresource); // Destroy current session
                        if ($ActiveSessions) {
                            $Changes['c6active_sessions'] = $this->encode_encrypt_1c($this->log_off_all_1c($ActiveSessions, $DBMSresource));
                            $Affected = $this->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, FALSE); // Not recorded in t0history
                            if (!$Affected)
                                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected.'. CoreZfpf::eject_1c Error Log Case 4. URGENT: A POTENTIAL HACKER MAY STILL HAVE ACCESS');
                            elseif ($Affected > 1)
                                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected.'. CoreZfpf::eject_1c Error Log Case 5');
                        }
                        $LogOffCalledOnCurrentForSure = TRUE;
                    }
                    break;
                }
            }
        }
        else
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::eject_1c Error Log Case 6. URGENT: A POTENTIAL HACKER MAY STILL HAVE ACCESS');
        if (!isset($LogOffCalledOnCurrentForSure))
            $this->log_off_1c($DBMSresource); // Destroy current session.
        $this->close_connection_1s($DBMSresource);
        echo '<h1>
        Error!</h1><h2>
        If you were logged on, the app tried to log you out as a precaution.</h2><p>
        An app admin needs to unlock your account before you can logon again, sorry -- PSM-CAP App</p>'; // Don't echo $this->xhtml_contents_header_1c('Logged Off', FALSE, FALSE) to avoid "Cannot modify header information" warning if headers already sent.
        exit; // Don't save and exit.
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function sends user to contents, instead of ejecting, for reason in $SpecialMessage
    public function send_to_contents_1c($File = __FILE__, $Line = __LINE__, $SpecialMessage = '') {
        $this->clear_edit_lock_1c();
        error_log(@$this->error_prefix_1c().$File.':'.$Line.' CoreZfpf::send_to_contents_1c '.$SpecialMessage);
        if (!$SpecialMessage)
            $SpecialMessage = '<p>Cannot complete, probably due to a user-privilege change or a document-status change, such as no longer a draft document. Navigate back to the document to check its status. Coordinate with whoever changed its status.</p>';
        echo $this->xhtml_contents_header_1c().'<h2>
        Special Message</h2>
        '.$SpecialMessage.'
        <form action="contents0.php" method="post"><p><a id="bottom"></a>
            <input type="submit" value="Go to Contents" /></p>
        </form>
        '.$this->xhtml_footer_1c();
        $this->save_and_exit_1c();
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function logs off the user and updates the database that the user is logged off.
    public function normal_log_off_1c($SpecialMessage = '', $FailureMessage = '', $LogOffAll = FALSE) {
        if (isset($DBMSresource)) // Just in case, though this should never be set when called.
            $this->close_connection_1s($DBMSresource); // DBMS connection with adequate credentials created below.
        $DBMSresource = $this->credentials_connect_instance_1s('logon maintenance');
        if (isset($_SESSION['t0user'])) {
            $Conditions[0] = array('k0user', '=', $_SESSION['t0user']['k0user']);
            list($SR, $RR) = $this->select_sql_1s($DBMSresource, 't0user', $Conditions, array('c6active_sessions')); // Get latest from database
            if ($RR != 1)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' normal_log_off_1c() Eject Case 1. Rows Returned: '.@$RR);
            $ActiveSessions = $this->check_array_1c($this->decrypt_decode_1c($SR[0]['c6active_sessions']));
            // Log off current session first because that's top priority, in case error later.
            $SessionID = session_id();
            if (isset($ActiveSessions[$SessionID]))
                unset($ActiveSessions[$SessionID]); // Allows calling log_off_all_1c after log_off_1c without repeating logoff
            else { // !$ActiveSessions or !isset($ActiveSessions[$SessionID])
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::normal_log_off_1c Error Log Case 1. Session ID from cookie not found in database-stored session IDs when logging off user');
                $ActiveSessions = $this->log_off_all_1c($ActiveSessions, $DBMSresource); // Do this as a precaution.
                $SpecialMessage .= '<p>
                <b>The app attempted to log-off all devices</b> that had been logged on with your username, as a precaution, due to a minor error that occurred.</p>';
            }
            $this->log_off_1c($DBMSresource); // Destroy current session even if no stored session ID was found. $_SESSION is now an empty array.
            if ($LogOffAll and $ActiveSessions) {
                $ActiveSessions = $this->log_off_all_1c($ActiveSessions, $DBMSresource);
                $SpecialMessage .= '<p>
                The app attempted to log off all devices that had been logged on with your username.</p>';
            }
            $Changes['c6active_sessions'] = $this->encode_encrypt_1c($ActiveSessions);
            $Affected = $this->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, FALSE); // Not recorded in t0history
            if ($Affected != 1) {
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::normal_log_off_1c Error Log Case 2. Affected Rows: '.@$Affected);
                $SpecialMessage .= '<p>
                <b>An error occurred during log off. Promptly ask an app admin to check the error log.</b></p>';
            }
            echo $this->xhtml_contents_header_1c('Logged Off', FALSE, FALSE).'<h2>
            You have logged off. Please close your Web browser.</h2><p>
            Or, you may <a href="logon.php">log on</a> again.</p>';
            if ($SpecialMessage)
                echo '<p><b>Special Log-Off Message: </b></p>'.$SpecialMessage;
        }
        else {
            // Don't try to recover session from database, to avoid a loop. CoreZfpf::session_check_1c does this.
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::normal_log_off_1c Error Log Case 3. Log off attempted without proper session. Causes: (1) someone pointed browser an app file when not logged on; (2) user hit back button after log off; or (3) session hijacked by hacker, legitimate user logged off, and then hacker tried to continue.');
            $this->log_off_1c($DBMSresource); // Call this anyway to destroy the session on the server side and maybe the client side too.
            echo $this->xhtml_contents_header_1c('Log-Off Error', FALSE, FALSE);
            if ($FailureMessage)
                echo '<p><b>Special Log-Off Failure Message: </b></p>'.$FailureMessage;
            echo '<h2>
            Standard Log-Off Failure Message: Sorry, you were <u>not</u> properly logged off.</h2><p>
            You may have tried to log off from a device (or Web browser) where you were not logged on.</p><p>
            <b>Otherwise, contact an administrator or your supervisor. Ask them to review the web-server error log.</b></p>';
        }
        echo $this->xhtml_footer_1c(FALSE); // FALSE needed here because no fixed left contents created by xhtml_contents_header_1c()
        $this->close_connection_1s($DBMSresource);
        exit; // Don't save and exit.
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function converts letters from uppercase to lowercase.
    // It assumes UTF-8 encoding is being used.
    // Use this for case-insensitive comparison and sorting, etc.
    public function to_lower_case_1c($String) {
        return mb_strtolower($String, 'UTF-8');
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function salts, hashes, etc. a string. 
    // Used mainly for hashing passwords in logon.php, setup.php, CoreZfpf.php, UserZfpf.php, and possibly elsewhere.
    // NOT suitable for hashing passwords, for that see 0read_me_psm_cap_app_standards.txt
    public function hash_1c($String) {
        return hash_pbkdf2('sha512', $String, HASH_SALT_ZFPF, HASH_ITERATIONS_ZFPF, 32);
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function encrypts $text and returns the encryption concatenated with the initialization vector.
    // TO DO 2020-07-22 verify openssl_encrypt is still secure and a best symmetric-key encryption as implemented below.
    // TO DO 2019-07-22 determine if a longer initialization vector, $iv, would be more secure.
    public function encrypt_1c($text) {
        // Create iv
        $secure_iv = FALSE;
        $iv = openssl_random_pseudo_bytes(16, $secure_iv);  // Use openssl_random_pseudo_bytes function to make sure iv is secure.
        if ($secure_iv) {
            // Check if string ends in null characters -- which would prevent proper decryption.
            $rtrim_text = rtrim($text, "\0");
            if ($rtrim_text != $text)
                $this->send_to_contents_1c(__FILE__, __LINE__, 'You attempted to input a string that ended with a null character, which is not allowed.'); // This would mess up decryption, but might occur by mistake when uploading files.
            // Encrypt text and concatenate with IV
            // openssl_encrypt uses 16-byte blocks in CBC the last block is padded with null characters, to make it 16 bytes.
            $crypttext = openssl_encrypt($text, 'aes-256-cbc', BINARY_KEY_ZFPF, OPENSSL_RAW_DATA, $iv);
            $CTA = substr($crypttext, 0, 7); // TO DO FOR PRODUCTION VERSION -- complicate IV concatenation
            $CTB = substr($crypttext, 7); // TO DO FOR PRODUCTION VERSION -- complicate IV concatenation
            return $CTA.$iv.$CTB;
        }
        else // $iv is not secure so function fails
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' encrypt_1c() Eject Case 2. Failed to create a secure initialization vector');
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function decrypts a string.
    public function decrypt_1c($crypttext) {
        if (!$crypttext)       
            return $crypttext; // This avoids error if empty or null field in database, allows app to work on subset of schema.
        if (strlen($crypttext) < 32) // $crypttext doesn't meet minimum length based on CBC block size
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::decrypt_1c() Eject Case 1');
        // separate IV from crypttext
        $iv = substr($crypttext, 7, 16); // TO DO FOR PRODUCTION VERSION -- complicate IV concatenation
        $CTA = substr($crypttext, 0, 7); // TO DO FOR PRODUCTION VERSION -- complicate IV concatenation
        $CTB = substr($crypttext, 23); // TO DO FOR PRODUCTION VERSION -- complicate IV concatenation
        $crypttext = $CTA.$CTB;
        $text = openssl_decrypt($crypttext, 'aes-256-cbc', BINARY_KEY_ZFPF, OPENSSL_RAW_DATA, $iv);
        // Remove the null characters from end of decrypted string, which may have been added during CBC encryption.
        return rtrim($text, "\0");
    }

    ////////////////////////////////////////////////////////////////////////////
    // This wrapper function may be modified as needed to prevent cross-site scripting.
    // All user-input text is run through this function.
    // If changed, check csv_safe_xss_prevent_decode_1c()
    public function xss_prevent_1c($text) {
        return htmlspecialchars($text, ENT_COMPAT | ENT_HTML5, 'UTF-8'); //  &   <   >  "    are coded to   &amp;   &lt;   &gt;  &quot;
    }
    ////////////////////////////////////////////////////////////////////////////
    // This function decodes xss_prevent_1c, except per comments below.
    public function csv_safe_xss_prevent_decode_1c($text) {
        $text = htmlspecialchars_decode($text, ENT_NOQUOTES | ENT_HTML5); // &amp;   &lt;   &gt;   are decoded to   &   <   >
        $text = str_replace('&quot;', "'",  $text); // &quot; converted to ' to avoid interfering with CSV double quotes (allows new lines in CSV fields).
        return $text; 
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function first encodes a plain-text array or string, then encrypts it.
    // DOES NOT WORK ON ENCRYPTED STUFF, such as the array $ChangedRow output by CoreZfpf::changes_from_post_1c
    // Works on strings that are themselves json encoded.
    public function encode_encrypt_1c($ArrayStringEtc) {
        $EncodedEncryptedArray = $this->encrypt_1c(json_encode($ArrayStringEtc));
        if (!isset($EncodedEncryptedArray) or !$EncodedEncryptedArray) // Tripped by 0 or failure. Not tripped by array(), FALSE, NULL, or ''
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' encode_encrypt_1c() Eject Case 1');
        return $EncodedEncryptedArray;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function first decrypts a string, then decodes it to an array.
    // In some cases the PHP function unserialize may be exploitable via PHP object injection.
    // So use json_encode() and json_decode() instead of serialize() and unserialize().
    // See http://php.net/manual/en/function.unserialize.php
    // See https://security.stackexchange.com/questions/63179/php-object-injection-prevention-owasp
    public function decrypt_decode_1c($ArrayStringEtc) {
        return json_decode($this->decrypt_1c($ArrayStringEtc), TRUE); // The second parameter must be TRUE to return an array.
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns known information useful for identifying the current user.
    // Use it for who_is_editing in edit locks, for error logs, and for special cases.
    // current_user_info_1c is better for echoed HTML and emails.
    // If what this function returns is changed between calling edit_lock_1c and clear_edit_lock_1c, then
    // clear_edit_lock_1c won't work, except for changes a user makes to their own t0user record.
    public function user_identification_1c() {
        $Info = '';
        if (isset($_SESSION['t0user'])) {
            $PersonalPhoneMobile = $this->decrypt_1c($_SESSION['t0user']['c5personal_phone_mobile']);
            $PersonalPhoneHome = $this->decrypt_1c($_SESSION['t0user']['c5personal_phone_home']);
            $EContactName = $this->decrypt_1c($_SESSION['t0user']['c5e_contact_name']);
            $EContactPhone = $this->decrypt_1c($_SESSION['t0user']['c5e_contact_phone']);
            if ($this->decrypt_1c($_SESSION['t0user']['c5name_given1']) != '[Nothing has been recorded in this field.]') // Not recorded for first admin after setup.
                $Info .= $this->full_name_1c($_SESSION['t0user']);
            if ($PersonalPhoneMobile != '[Nothing has been recorded in this field.]')
                $Info .= ', mobile: '.$PersonalPhoneMobile;
            if ($PersonalPhoneHome != '[Nothing has been recorded in this field.]')
                $Info .= ', home: '.$PersonalPhoneHome;
            if ($EContactName != '[Nothing has been recorded in this field.]') // Not recorded for first admin after setup.
                $Info .= ', E-contact: '.$EContactName.', ' .$EContactPhone;
        }
        if (!$Info) {
            if (isset($_POST['username']))
                $Info = 'User-supplied username: '.substr($_POST['username'], 0, C5_MAX_BYTES_ZFPF);
            else
                $Info = 'Name and username unknown';
        }
        return $Info;
    }
    
    public function error_prefix_1c() {
        $Prefix = 'PSM-CAP User ';
        if (isset($_SESSION['t0user']['k0user']))
            $Prefix .= $_SESSION['t0user']['k0user'].' ';
        $Prefix .= @$this->user_identification_1c().' File:Line ';
        return $Prefix;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function truncates input to the allowed maximum length.
    // $MaxLength -- see description in 0read_me_psm_cap_app_standards.txt
    // and C5_MAX_BYTES_ZFPF definition in CoreSettingsZfpf.php
    public function max_length_1c($String, $MaxLength = C5_MAX_BYTES_ZFPF) {
        // PHP function strlen() counts bytes not characters (Chinese, Korean, Japanese etc. utf8 characters are 3 bytes each)
        if (strlen($String) > $MaxLength) {
            $String = substr($String, 0, $MaxLength - 3).'...';
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' User input truncated to '.$MaxLength.' bytes. Please notify this user. Asian and some other non-roman characters require 2 or 3 bytes each, and text written in these characters may be truncated even if it fit in an HTML form with maxlength set. Using HTML special characters & < > " etc. may also cause user input to be truncated because these get converted to the 4- or 5-byte HTML entities &amp; &lt; &gt; &quot; etc. respectively');
        }
        return $String;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Use ConfirmZfpf::post_to_display_1e() for whitelisting $_POST keys for most HTML forms.
    // This function:
    // - is only used for special situations.
    // - performs the same function as if (!array_key_exists($ArrayKey, $_POST)) but uses isset(), which is faster.
    // - also calls the cross-site scripting prevention function.
    // $ArrayKey is the HTML field name, which is also a key of the PHP superglobal array $_POST.
    // RADIO BUTTONS and CHECK BOXES required special handling before calling this function -- otherwise if none selected, user will be ejected.
    public function post_1c($ArrayKey) {
        if (isset($_POST[$ArrayKey]))
            return $this->xss_prevent_1c($_POST[$ArrayKey]);
        // Eject user if function has not been ended by return above because $_POST[$ArrayKey] is not set.
        $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' post_1c() Eject Case 1');
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function calls $this->post_1c() and then truncates input to the allowed maximum length.
    // $MaxLength -- see above max_length_1c.
    public function post_length_1c($ArrayKey, $MaxLength = C5_MAX_BYTES_ZFPF) {
        return $this->max_length_1c($this->post_1c($ArrayKey), $MaxLength);
    }

    //////////////////////////////////////////////////////////////////////////// 
    // This function calls $this->post_length_1c() and replaces blank user input with '[Nothing has been recorded in this field.]'.
    // $MaxLength -- see above max_length_1c.
    // RADIO BUTTONS and CHECK BOXES -- see note under $this->post_ic above
    public function post_length_blank_1c($ArrayKey, $MaxLength = C5_MAX_BYTES_ZFPF) {
        $Post = $this->post_length_1c($ArrayKey, $MaxLength);
        if ($Post === '')
            $Post = '[Nothing has been recorded in this field.]';
        return $Post;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns the value ($Value) of a decrypted timestamp field in the app's chosen time format. It leaves other types of fields unchanged
    // $Value is decrypted timestamp field.
    // $ColumnName is an fpf standard database column name, which are also the first-layer keys of $htmlFormArray and $_SESSION['Selected']
    public function timestamp_to_display_1c($Value, $ColumnName = 'c5ts_') {
        // Check if a time-stamp, in seconds since start of 1970, is recorded. Works until the year 318,857.
        if (substr($ColumnName, 0, 5) == 'c5ts_' and is_numeric($Value) and $Value < 9999999999999)
            return date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j, \T\i\m\e H:i:s', $Value);
        else
            return $Value;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // This function returns a timestamp from a range of common date and time formats and otherwise returns its input unchanged
    // Timestamps will only be recognized by app if NOT in encoded array.
    public function text_to_timestamp_1c($Text) {
        // Convert Year _, Month _, Day _ dates into a format that strtotime() recognizes.
        if (substr($Text, 0, 5) == 'Year ') {
            $YMD_Date = explode(', ', $Text);
            if (count($YMD_Date) == 3 and substr($YMD_Date[1], 0, 6) == 'Month ' and substr($YMD_Date[2], 0, 4) == 'Day ') {
                $Year = substr($YMD_Date[0], 5);
                $Month = substr($YMD_Date[1], 6);
                $Day = substr($YMD_Date[2], 4);
                $Text = $Year.'-'.$Month.'-'.$Day;
            }
            if (count($YMD_Date) == 4 and substr($YMD_Date[1], 0, 6) == 'Month ' and substr($YMD_Date[2], 0, 4) == 'Day ' and substr($YMD_Date[3], 0, 5) == 'Time ') {
                $Year = substr($YMD_Date[0], 5);
                $Month = substr($YMD_Date[1], 6);
                $Day = substr($YMD_Date[2], 4);
                $Time = substr($YMD_Date[3], 5);
                $Text = $Year.'-'.$Month.'-'.$Day.' '.$Time;
            }
        }
        $DateTimestamp = strtotime($Text); // Returns FALSE if $Text does not match a wide range of common date formats.
        if ($DateTimestamp)
            $Text = $DateTimestamp; // If a date was entered, record as a time stamp, otherwise record whatever other deadline description the user entered as a string.
        return $Text;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function requires the user to supply his/her password.
    // If supplied password is correct -- it sends the user back to last requested PHP file with the same $_POST information.
    // If supplied password is wrong -- it ejects the user.
    public function password_check_1c() {
        if (isset($_POST)) {
            if (count($_POST) > 2000) // Eject any hacker that modified the HTML form...
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' password_check_1c() Eject Case 1');
            // Save information in $_SESSION while password is checked.
            $_SESSION['SavePost'] = $this->encode_encrypt_1c($_POST); // Safe from SQL-, HTML-, and PHP-injection because sent to neither database nor user and decoded with json_decode.
        }
        if (isset($_GET)) {
            if (count($_GET) > 2000) // Eject any hacker that modified the HTML form...
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' password_check_1c() Eject Case 2');
            // Save information in $_SESSION while password is checked.
            $_SESSION['SaveGet'] = $this->encode_encrypt_1c($_GET); // Safe from SQL-, HTML-, and PHP-injection because sent to neither database nor user and decoded with json_decode.
        }
        // $_SERVER['PHP_SELF'] does not work below if using pcm/front_controller.php
        // so instead use @parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        echo $this->xhtml_contents_header_1c('Password Check').'<p>
        <b>Please confirm your password.</b> This is a security check to confirm your identity.</p>
        <form action="'.$this->xss_prevent_1c(@parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)).'" method="post"><p>
        Password:<br />
            <input type="password" name="PasswordCheck" /></p><p>
            <input type="submit" value="Continue" /></p>
        </form>'.$this->xhtml_footer_1c();
        $this->save_and_exit_1c();
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function should be called at the beginning of each served PHP file in the web-app, except setup.php, logon.php, and logoff.php.
    // This function starts the session and then, if $this->password_check_1c() had already been called, 
    // it checks if the correct password was supplied; otherwise, it ejects the user
    // or calls $this->password_check_1c() under certain conditions.
    // The goal is to prevent an impostor from jumping on a terminal while a user takes a coffee break,
    // and -- even if the impostor successfully does this -- to kick the impostor off after a specified period if time.
    // As well as to serve as a backstop against session hijacking.
    public function session_check_1c() {
        // TO DO FOR PRODUCTION VERSION -- comment out - START.
        // unset($_SESSION['t0user']); // Calling unset($_SESSION) here wipes out $_GET
        // TO DO FOR PRODUCTION VERSION -- comment out - END.
        $DBMSresource = $this->credentials_connect_instance_1s(LOW_PRIVILEGES_ZFPF); // Only used for SQL selects below.
        if (!isset($_SESSION['t0user'])) {
            $SessionID = session_id();
            if ($SessionID) {
                list($SR, $RR) = $this->select_sql_1s($DBMSresource, 't0session_ids', 'No Condition -- All Rows Included');
                if ($RR) foreach ($SR as $VA) { // Cannot use SQL select because session_ids are encrypted.
                    if ($SessionID == $this->decrypt_1c($VA['c5session_id'])) {
                        $Conditions[0] = array('k0user', '=', $VA['k0user']);
                        list($SRB, $RRB) = $this->select_sql_1s($DBMSresource, 't0user', $Conditions);
                        if ($RRB == 1) {
                            $ActiveSessions = $this->decrypt_decode_1c($SRB[0]['c6active_sessions']);  // CoreZfpf::check_array_1c not needed here because of the if (isset($ActiveSessions)) below.
                            if (isset($ActiveSessions[$SessionID]))
                                $_SESSION = $this->encrypt_array_1c($ActiveSessions[$SessionID]);
                        } // Don't eject if $RRB != 1 -- that's excessive. Instead, CoreZfpf::normal_log_off_1c is called below.
                        break;
                    }
                }
            }
        }
        if (!isset($_SESSION['t0user'])) { // Plan B, if it's still not set.
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::session_check_1c Error Log Case 1 -- session not found or lost, from app-server memory and database backup. Or, user not found in database sometime after login.');
            $this->normal_log_off_1c('<p>The server lost your session, sorry. This happens more on a cheap Web demo. <b>Log on again and carry on!</b></p>', '<p>The server lost your session, sorry. This happens more on a cheap Web demo. <b>Try to log on again and click "Log Off All" in the upper-left corner.</b> Then log on again. Ignore the "Standard Log-Off Failure Message" below.</p>'); // Two messages passed in here, if CoreZfpf::normal_log_off_1c works or doesn't work, respectively.
        }
        // Refresh $_SESSION['t0user'] and $_SESSION['t0user_practice'] in case privileges changed, etc.
        $Conditions[0] = array('k0user', '=', $_SESSION['t0user']['k0user']);
        list($SR, $RR) = $this->select_sql_1s($DBMSresource, 't0user', $Conditions);
        if ($RR == 1) {
            $_SESSION['t0user']['c5p_global_dbms'] = $SR[0]['c5p_global_dbms']; // Don't refresh c6active_sessions, leads to loop in memory.
            if (isset($_SESSION['t0user_practice'])) {
                $Conditions[0] = array('k0user_practice', '=', $_SESSION['t0user_practice']['k0user_practice']);
                list($SR, $RR) = $this->select_sql_1s($DBMSresource, 't0user_practice', $Conditions);
                if ($RR != 1) {
                    unset($_SESSION['t0user_practice']);
                    $this->send_to_contents_1c(__FILE__, __LINE__); // !$RR means user lost all privileges to this practice; $RR > 1 some other error.
                }
                $_SESSION['t0user_practice'] = $SR[0];
            }
        }
        $this->close_connection_1s($DBMSresource);
        // Password and timeout checks.
        $EncryptedTime = $this->encrypt_1c(time());
        if (isset($_POST['PasswordCheck'])) {
            if (!password_verify($this->max_length_1c($_POST['PasswordCheck']), $this->decrypt_1c($_SESSION['t0user']['s5password_hash']))) {
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' User supplied an incorrect password during a check so was logged off');
                $this->normal_log_off_1c('<p>You supplied an incorrect password. You have been logged off as a precaution.<br /><br />If you need assistance, please contact your supervisor or an app admin.</p>');
            }
            $_SESSION['CheckPasswordSupplied'] = $EncryptedTime;
            $_SESSION['CheckLastAction'] = $EncryptedTime;
            if (isset($_SESSION['SavePost'])) {
                $_POST = $this->decrypt_decode_1c($_SESSION['SavePost']); // Decrypt saved user-post and put back in $_POST for calling PHP file.
                unset($_SESSION['SavePost']);
            }
            if (isset($_SESSION['SaveGet'])) {
                $_GET = $this->decrypt_decode_1c($_SESSION['SaveGet']); // Decrypt saved "get" and put back in $_GET for calling PHP file.
                unset($_SESSION['SaveGet']);
            }
        }
        if (TIMEOUT_INACTIVE_LOG_OFF_ZFPF < time() - $this->decrypt_1c($_SESSION['CheckLastAction']))
            $this->normal_log_off_1c('<p><b>You were logged off because your session has been inactive (session timeout).</b><br /> If you need assistance, please contact an administrator or your supervisor.</p>');
        elseif (TIMEOUT_INACTIVE_PASSWORD_CHECK_ZFPF < time() - $this->decrypt_1c($_SESSION['CheckLastAction']))
            $this->password_check_1c();
        if (TIMEOUT_ACTIVE_PASSWORD_CHECK_ZFPF < time() - $this->decrypt_1c($_SESSION['CheckPasswordSupplied']))
            $this->password_check_1c();
        $_SESSION['CheckLastAction'] = $EncryptedTime;
        // TO DO note: The above timeouts protect some against session hijacking, so...
        // decide if benefit to also: Each script execution, regenerating session ID and deleting the old session 
        // as a precaution against session hijacking, using
        // session_regenerate_id(TRUE)
        // if this is called, need to update t0user:active_sessions AND follow guidance in PHP Manual
        // to avoid creating security and function problems during poor connectivity edge of wifi range, mobile tower switch, power blip...
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns the standard contents and header, with top anchor.
    // All arguments are optional, the default $Title is PSM-CAP
    // To suppress the logoff button or fixed-left contents, pass FALSE as their argument.
    public function xhtml_contents_header_1c($Title = 'PSM CAP', $LogOffButton = TRUE, $FixedLeftContents = 'DefaultFixedLeftContents', $DBMSresource = FALSE, $OnPageAnchorLinks = TRUE, $SendHeader = TRUE) {
        if ($SendHeader)
            header('Content-Type: text/html; charset=utf-8'); // This is intended to help prevent cross-site scripting (XSS)
        // TO DO FOR PRODUCTION VERSION comment out line below. Use in development to validate all but allowed HTML5 as XHTML 1.0 Strict.  See Design Decisions document for allowed HTML5 (file: 0read_me_PSM-CAP_Design_Decisions...)
        // $Return = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> <html xmlns="http://www.w3.org/1999/xhtml">';
        $Return = '<!DOCTYPE html> <html lang="en"> '; // This is the DOCTYPE for HTML5, second markup declares the language as English.
        $Return .= ' 
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <meta content="text/html; charset=utf-8" http-equiv="content-type" />
            <title>'.$Title.'</title>
            <meta name="description" content="PSM-CAP App" />
            <meta name="keywords" content="PSM-CAP App" />
            <link rel="stylesheet" href="fpf.css" type="text/css" />
            <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
            <!--[if lte IE 6]>
                <style type="text/css">
                body {
                height:100%;
                overflow-y:auto;
                overflow-x:hidden;
                }
                * html div.fixedleft {position: absolute;}
                /*<![CDATA[*/ 
                html {height:100%; overflow:hidden;}
                /*]]>*/
                </style>
            <![endif]-->
        </head>
        <body>
        <div class="box">';
        if ($LogOffButton or $FixedLeftContents)
            $Return .= '
            <div class="fixedleft">';
        if ($LogOffButton) {
            $Return .= '<p>
            |<a class="toc" href="logoff.php">Log Off</a>';
            $ActiveSessions = $this->check_array_1c($this->decrypt_decode_1c($_SESSION['t0user']['c6active_sessions']));
            if (!$ActiveSessions)
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::decrypt_decode_1c on database field t0user:c6active_sessions returned false, when it should hold at least the session for the current logon');
            else {
                $SessionsCount = count($ActiveSessions);
                if($SessionsCount > 1)
                    $Return .= '<br /><br />
                    |<a class="toc" href="logoff.php?log_off_all">Log Off All</a>';
                $Return .= '<br /><br />
                |<i>Devices logged on:</i> <b>'.$SessionsCount.'</b>';
            }
            $Return .= '<br />
            |<i>Last log on:</i><br />'.date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j, \T\i\m\e H:i', $this->decrypt_1c($_SESSION['t0user']['c5ts_last_logon'])).'</p>';
        }
        // The default fixed-left left-hand contents.
        if ($FixedLeftContents == 'DefaultFixedLeftContents') {
            if (isset($_SESSION['Selected']))
                $Return .= '<p>
                |'.$this->full_name_1c($_SESSION['t0user']); // Return names without link to user info.
            else
                $Return .= '<p>
                |<a class="toc" href="user_o0.php">'.$this->full_name_1c($_SESSION['t0user']).'</a>';
            if (isset($_SESSION['t0user_owner']['c5job_title']))
                $Return .= '<br />
                |'.$this->decrypt_1c($_SESSION['t0user_owner']['c5job_title']);
            if (isset($_SESSION['t0user_contractor']['c5job_title']))
                $Return .= '<br />
                |'.$this->decrypt_1c($_SESSION['t0user_contractor']['c5job_title']);
            $Return .= '</p><p>';
            // Return current location in manual
            // See Design Decisions document (file: 0read_me_PSM-CAP_Design_Decisions...) for a description of $_SESSION['StatePicked']
            // This generates the typical fixed left contents list for user navigation.
            if (isset($_SESSION['StatePicked']['t0contractor']['k0contractor']) or isset($_SESSION['StatePicked']['t0owner']['k0owner']))
                $Return .= '
                |<a class="toc" href="ar_i0m.php">Action Register</a><br />';
            $Return .= '
            |<a class="toc" href="administer1.php">Admin.</a><br />
            |<a class="toc" href="contents0.php">Contents</a><br />
            |<a class="toc" href="glossary.php" target="_blank">Glossary</a><br />'; // Two line breaks after this.
            // List current contractor (if applicable), owner, facility, and process in left-hand contents.
            if (isset($_SESSION['StatePicked'])) foreach ($_SESSION['StatePicked'] as $Ksp => $Vsp) {
                if ($Ksp == 't0contractor' or $Ksp == 't0owner' or $Ksp == 't0facility' or $Ksp == 't0process')
                    $Return .= '<br />
                    |<a class="toc" href="'.substr($Ksp, 2).'_io03.php">'.substr($this->decrypt_1c($Vsp['c5name']), 0, 33).'</a>'; // 33 characters is an arbitrary length to fit into a line or two on the left-hand contents for a typical screen.
                if ($Ksp == 't0process')
                    break;
            }
            $Return .= '</p>';
            // These if statements allow the user to switch between owners, contractors, facilities, rules, fragments, etc.
            // $_SESSION['PlainText']['ChangeSelection'] is to only give the "change your selection" options if a user has more than one option,
            // for instance, if they are associated with two processes. This is not done for rule, division, fragment, and practice 
            // because there are almost always more than one of these.
            if (isset($_SESSION['StatePicked'])) {
                $MessageChangeSelection = '';
                // No additional condition needed here because above if (isset($_SESSION['StatePicked'])) 
                // will suppress this button if an owner or contractor has not been picked.
                if (isset($_SESSION['PlainText']['ChangeSelection']['owners-contractors']))
                    $MessageChangeSelection .= '<br />
                    |<a class="toc" href="contents0_s_employer.php">Owners or Contractors</a>';
                if (isset($_SESSION['PlainText']['ChangeSelection']['owners_only-from-t0owner_contractor']) and isset($_SESSION['t0owner_contractor']))
                    $MessageChangeSelection .= '<br />
                    |<a class="toc" href="contents0_s_client.php">Owners (Contractor\'s clients)</a>';
                if (isset($_SESSION['PlainText']['ChangeSelection']['facilities']) and isset($_SESSION['t0user_facility']))
                    $MessageChangeSelection .= '<br />
                    |<a class="toc" href="contents0_s_facility.php">Facilities</a>';
                if (isset($_SESSION['PlainText']['ChangeSelection']['processes']) and isset($_SESSION['t0user_process']))
                    $MessageChangeSelection .= '<br />
                    |<a class="toc" href="contents0_s_process.php">Processes</a>';
                if (isset($_SESSION['StatePicked']['t0rule']) and !isset($_POST['rule']) and !isset($_SESSION['PlainText']['suppress_rule_o1_contents1_temp']))
                    $MessageChangeSelection .= '<br />
                    |<a class="toc" href="rule_o1.php">Rules (division methods)</a>';
                if (isset($_SESSION['PlainText']['suppress_rule_o1_contents1_temp']))
                    unset($_SESSION['PlainText']['suppress_rule_o1_contents1_temp']);
                if (isset($_SESSION['StatePicked']['t0division']))
                    $MessageChangeSelection .= '<br />
                    |<a class="toc" href="contents0_s_division.php">Divisions</a>';
                if (isset($_SESSION['StatePicked']['t0fragment']))
                    $MessageChangeSelection .= '<br />
                    |<a class="toc" href="contents0_s_fragment.php">Fragments</a>';
                if (isset($_SESSION['StatePicked']['t0practice']))
                    $MessageChangeSelection .= '<br />
                    |<a class="toc" href="contents0_s_practice.php">Practices</a>';
                if ($MessageChangeSelection)
                    $Return .= '<p>
                    |<b>List:</b>'.$MessageChangeSelection.'</p>';
            }
            // This displays rule radio buttons in the left-hand contents.
            // Only display these radio buttons if $DBMSresource was passed in, which is typically only done by .../includes/contents1.php.
            if ($DBMSresource and isset($_SESSION['StatePicked']['t0process'])) {
                list($_SESSION['SelectResults_t0rule_contents1_temp'], $RowsReturned['t0rule']) = $this->select_sql_1s($DBMSresource, 't0rule', 'No Condition -- All Rows Included'); // $_SESSION['SelectResults_t0rule_contents1_temp'] is a backdoor way to return data; it allows radio button in the left-hand contents.
                if ($RowsReturned['t0rule'] > 0) {
                    // Sort $_SESSION['SelectResults_t0rule_contents1_temp'] by k0rule.
                    foreach ($_SESSION['SelectResults_t0rule_contents1_temp'] as $V)
                        $k0rule[] = $V['k0rule'];
                    array_multisort($k0rule, $_SESSION['SelectResults_t0rule_contents1_temp']);
                    $Return .= '
                    <form action="contents0.php" method="post"><p>
                    <a class="toc" href="glossary.php#rule" target="_blank">Rule</a> or division method:<br />';
                    foreach ($_SESSION['SelectResults_t0rule_contents1_temp'] as $K => $V) {
                        $Return .= '
                        <input type="radio" name="rule" value="'.$K.'" ';
                        if (isset($_POST['rule']) and $_POST['rule'] == $K)
                                $Return .= 'checked="checked" ';
                        elseif ($K == 0)
                            $Return .= 'checked="checked" '; // $K == 0 is the default Cheesehead Division Order
                        $Return .= '/>'.$this->decrypt_1c($V['c5name']).'<br />';
                    }
                    $Return .= '
                    <input type="submit" value="Switch" /></p>
                    </form>';
                }
                else { // Cannot call CoreZfpf::normal_log_off_1c here because HTML headers already sent.
                    echo $Return.'</div>
                    <div class="text"><a id="top"></a>
                    <div class="ff">PSM-CAP App</div>
                    <p class="spacer">&nbsp;</p><p>
                    No rules have been recorded in the database, and this PSM-CAP App will not work until this needed content is added.</p><p>
                    Normally these are included in the database at installation.</p><p>
                    <a href="logoff.php">Log off</a> and contact your supervisor or a PSM-CAP App administrator for assistance.</p>
                    '.$this->xhtml_footer_1c();
                }
            }
            // Below adds on-page anchor hyperlinks to the left-hand contents.
            if ($OnPageAnchorLinks) {
                $Return .= '<p>
                |<a class="toc" href="#top">Top of page</a><br /> 
                |<a class="toc" href="#bottom">Bottom of page</a><br />';  // Two line breaks after this.
                if (isset($_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'])) {
                    foreach ($_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] as $K => $V)
                        $Return .= '<br />
                        |<a class="toc" href="#'.$K.'">'.$V.'</a>'; // $K is ID and $V is description, of anchors.
                    unset($_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors']);
                }
                $Return .= '</p>';
            }
        }
        elseif ($FixedLeftContents)
            $Return .= $FixedLeftContents;
         // This generates the "letter head" at the top of the web page.
        if ($LogOffButton or $FixedLeftContents)
            $Return .= '
            </div>
            <div class="text">'; // closes <div class="fixedleft"> and opens <div class="text"> when fixed-left contents is used.
        $Return .= '
        <a id="top"></a>
        <div class="ff">PSM-CAP App</div>
        <p class="spacer">&nbsp;</p>';
        return $Return;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns the standard footer, with bottom anchor.
    public function xhtml_footer_1c($FixedLeft = TRUE) {
        $Return = '
        <div class="footer">
        process safety management | chemical accident prevention</div>
        <p class="alignright"><a id="bottom"></a>
        Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0.<br />
        <b>Provided for free, as is. Use at your own risk. Absolutely no warranty.</b></p>';
        // TO DO FOR PRODUCTION VERSION -- comment out - START.
        /*// For troubleshooting test emails
            if (isset($_SESSION['EmailTest'])) {
                $Return .= '<p>$EmailAddresses: '.$_SESSION['EmailTest']['EmailAddresses'].'<br /><br />
                '.'$Headers: '.$_SESSION['EmailTest']['Headers'].'<br /><br />
                '.'$Subject: '.$_SESSION['EmailTest']['Subject'].'<br /><br />
                '.'$Message:<br />'.$_SESSION['EmailTest']['Message'].'</p>';
                unset($_SESSION['EmailTest']);
            }
        //*/
        /*// For troubleshooting session variable, keys only 
        // such as CoreZfpf::decrypt_1c() Eject Case 1 triggered by CoreZfpf::save_and_exit_1c
        $Return .= '$_SESSION first level:<br />';
        $Return .= print_r(array_keys($_SESSION), TRUE);
        foreach ($_SESSION as $VA) if (is_array($VA)) {
            $Return .= '<br /><br />$_SESSION second level:<br />';
            $Return .= print_r(array_keys($VA), TRUE);
            foreach ($VA as $VB) if (is_array($VB)) {
                $Return .= '<br /><br />$_SESSION third level:<br />';
                $Return .= print_r(array_keys($VB), TRUE);
                foreach ($VB as $VC) if (is_array($VC)) {
                    $Return .= '<br /><br />$_SESSION fourth level:<br />';
                    $Return .= print_r(array_keys($VC));
                }
            }
        }
        //*/
        /*// For troubleshooting session variable, keys and values.
            if (isset($_SESSION))
                $Return .= '<p>'.print_r($_SESSION, TRUE).'</p>';
        //*/
        // TO DO FOR PRODUCTION VERSION -- comment out - END.
        if ($FixedLeft)
            $Return .= '
            </div>'; // Close <div class="text">  if it was opened by CoreZfpf:xhtml_contents_header_1c
        $Return .= '
        </div>
        </body>
        </html>'; // Close <div class="box">
        return $Return;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function generates a standardized distribution list.
    // $AEscope allowed values Process-wide (default), Facility-wide, or Owner-wide.
    public function up_the_chain_1c($AEscope = 'Process-wide') {
        if (!isset($_SESSION['StatePicked']['t0owner']['k0owner']))
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' up_the_chain_1c() Eject Case 1');
        $AffectedEntityInfo = $this->affected_entity_info_1c('Owner-wide', $_SESSION['StatePicked']['t0owner']['k0owner']);
        $EmailAddresses = array();
        if ($AffectedEntityInfo['AELeaderWorkEmail'])
            $EmailAddresses[] = $AffectedEntityInfo['AELeaderWorkEmail'];
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        Owner '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$AffectedEntityInfo['AELeaderNameTitle'].', '.$AffectedEntityInfo['AELeaderEmployer'].' '.$AffectedEntityInfo['AELeaderWorkEmail'];
        $AEFullDescription = $AffectedEntityInfo['AEFullDescription'];
        if ($AEscope == 'Process-wide' or $AEscope == 'Facility-wide') {
            if (!isset($_SESSION['StatePicked']['t0facility']['k0facility']))
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' up_the_chain_1c() Eject Case 2');
            $AffectedEntityInfo = $this->affected_entity_info_1c('Facility-wide', $_SESSION['StatePicked']['t0facility']['k0facility']);
            if ($AffectedEntityInfo['AELeaderWorkEmail'])
                $EmailAddresses[] = $AffectedEntityInfo['AELeaderWorkEmail'];
            $DistributionList .= '<br />
            Facility '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$AffectedEntityInfo['AELeaderNameTitle'].', '.$AffectedEntityInfo['AELeaderEmployer'].' '.$AffectedEntityInfo['AELeaderWorkEmail'];
            $AEFullDescription = $AffectedEntityInfo['AEFullDescription'];
        }
        if ($AEscope == 'Process-wide') {
            if (!isset($_SESSION['StatePicked']['t0process']['k0process']))
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' up_the_chain_1c() Eject Case 3');
            $AffectedEntityInfo = $this->affected_entity_info_1c('Process-wide', $_SESSION['StatePicked']['t0process']['k0process']);
            if ($AffectedEntityInfo['AELeaderWorkEmail'])
                $EmailAddresses[] = $AffectedEntityInfo['AELeaderWorkEmail'];
            $DistributionList .= '<br />
            Process '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$AffectedEntityInfo['AELeaderNameTitle'].', '.$AffectedEntityInfo['AELeaderEmployer'].' '.$AffectedEntityInfo['AELeaderWorkEmail'];
            $AEFullDescription = $AffectedEntityInfo['AEFullDescription'];
        }
        // DistributionList doesn't include the final HTML </p> to facilitate adding additional recipients.
        return array(
            'EmailAddresses'    => $EmailAddresses,
            'DistributionList'  => $DistributionList,
            'AEFullDescription' => $AEFullDescription
        );
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function appends standard text to the body of an email.
    // $Body and $DistributionList shall be contained in paragraph tags <p></p> for HTML emails.
    // $AEFullDescription may created by CoreZfpf::affected_entity_info_1c -- may be any text that makes sense in context below.
    // $ApprovedDocument is the text of a document a user approved.
    public function email_body_append_1c($Body = '', $AEFullDescription = '', $ApprovedDocument = '', $DistributionList = '') {
        if ($ApprovedDocument)
            $Body .= "<p>
            *** START COPY OF APPROVED DOCUMENT ***</p>
            $ApprovedDocument
            <p>
            *** END COPY OF APPROVED DOCUMENT ***</p>";
        if ($DistributionList)
            $Body .= $DistributionList;
        $Body .= '<p>
        -- <br />
        This email was sent by the PSM-CAP App';
        if ($AEFullDescription)
            $Body .= ' for '.$AEFullDescription;
        return $Body.'</p><p>
        The information transmitted via this email is private and confidential. Notify us, by replying to this email, if you received it by mistake, without proper authorization, or to unsubscribe.</p>';
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function is a wrapper for PHP function mail() to allow modification for different operating systems and other contexts.
    // $EmailAddresses may be an empty array, which evaluates as false, or a numeric array of email addresses and/or empty strings.
    // OPTION...  X-Mailer: PHP/'.@phpversion()  ...but not required and Google App Engine (GAE) requires manually enabling phpversion()
    // OPTION... .'X-Mailer: PHP '.$NewLine.'MIME-Version: 1.0 '.$NewLine. -- these were taken out because removed by GAE
    public function send_email_1c($EmailAddresses, $Subject, $Body, $CustomHeaders = FALSE, $From = EMAIL_FROM_ZFPF, $ReplyTo = EMAIL_REPLY_TO_ZFPF, $NewLine = PHP_EOL) {
        if ($EmailAddresses) {
            if (isset($Subject) and $Subject) // Convert &amp; HTML entity made by CoreZfpf::xss_prevent back to text.
                $Subject = str_replace('&amp;', '&', $Subject); // Don't restore < and > because rarely used in subject and might help hacker.
            $Headers = 'From: '.$From.' '.$NewLine.'Reply-To: '.$ReplyTo.' '.$NewLine.'Content-type: text/html; charset=utf-8 '.$NewLine;
            if ($CustomHeaders)
                $Headers = $CustomHeaders;
            $EmailAddresses = array_unique($EmailAddresses);
            $EmailAddressString = '';
            foreach ($EmailAddresses as $V)
                if ($V)
                    $EmailAddressString .= $V.', ';
            if ($EmailAddressString) {
                $EmailAddressString = substr($EmailAddressString, 0, -2); // remove the final, hanging, comma space.
                // TO DO Email server setup posts:
                // TO DO Worked on WAMP, http://stackoverflow.com/questions/7820225/how-to-send-email-from-local-wamp-server-using-php
                // TO DO Worked on Ubuntu, https://www.linux.com/learn/install-and-configure-postfix-mail-server
                // TO DO uncomment line below once an email server is setup
                return mail($EmailAddressString, $Subject, $Body, $Headers);
                // return TRUE; // Uncomment this if line above in commented out.
                // For development: variables below are used to echo email parameters to browser in xhtml_footer_1c
                // $_SESSION['EmailTest']['EmailAddresses'] = $EmailAddressString;
                // $_SESSION['EmailTest']['Headers'] = $Headers;
                // $_SESSION['EmailTest']['Subject'] = $Subject;
                // $_SESSION['EmailTest']['Message'] = $Body;
            }
        }
        return FALSE;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns as HTML (safe for echo) the name and, if any, description of an entity
    // $TableRow an associative array with one row from a database table, whose keys are the database-table column names
    // c5name (and optionally c6descrption) fields, in $TableRow database table, are needed to return useful info.
    // $ShortenDescription does nothing if false, otherwise truncates contents of c6description to the passed-in number of bytes.
    // $BoldName defaults to bold type for the entity name.
    // $NameDescription is HTML with a standardized format for describing entities.
    // returns false if $TableRow database table doesn't have a c5name field.
    public function entity_name_description_1c($TableRow, $ShortenDescription = 100, $BoldName = TRUE) {    
        $NameDescription = '';
        if (isset($TableRow['c5name'])) {
            $NameDescription = $this->decrypt_1c($TableRow['c5name']);
            if ($BoldName)
                $NameDescription = '<b>'.$NameDescription.'</b>';
            if (isset($TableRow['c6description'])) {
                $Description = $this->decrypt_1c($TableRow['c6description']);
                if ($Description != '[Nothing has been recorded in this field.]') {
                    if ($ShortenDescription)
                        $Description = substr($Description, 0, $ShortenDescription).' ...';
                    $NameDescription .= ' -- '.$Description;
                }
            }
        }
        return $NameDescription;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns as HTML (safe for echo) all recorded name information SELECTed from t0user
    // $t0user is an array holding one row from the t0user table -- with array keys matching t0user columns.
    public function full_name_1c($t0user) {
        $Name = '';
        $c5name_family = $this->decrypt_1c($t0user['c5name_family']);
        $c5name_given1 = $this->decrypt_1c($t0user['c5name_given1']);
        $c5name_given2 = $this->decrypt_1c($t0user['c5name_given2']);
        $c5ts_logon_revoked = $this->decrypt_1c($t0user['c5ts_logon_revoked']);
        if ($c5name_family != '[Nothing has been recorded in this field.]')
            $Name .= $c5name_family.', ';
        if ($c5name_given1 != '[Nothing has been recorded in this field.]')
            $Name .= $c5name_given1;
        if ($c5name_given2 != '[Nothing has been recorded in this field.]')
            $Name .= ' '.$c5name_given2;
        if (!$Name)
            $Name = 'Name not found';
        if ($c5ts_logon_revoked != '[Nothing has been recorded in this field.]')
            $Name .= ', logon credentials revoked '.$this->timestamp_to_display_1c($c5ts_logon_revoked); // This just gives notice of this whenever the full name is displayed.
        return $Name;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function requires no input and
    // returns the array of information on the current user.
    public function current_user_info_1c() {
        // Handle app admin not associated with an owner or contractor, etc.
        $GlobalDBMSPrivileges = $this->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);
        $Name = $this->full_name_1c($_SESSION['t0user']);
        $Title = '[Nothing has been recorded in this field.]';
        $Employer = '[Nothing has been recorded in this field.]';
        $WorkEmail = '[Nothing has been recorded in this field.]';
        $TimeLogonRevoked = $this->timestamp_to_display_1c($this->decrypt_1c($_SESSION['t0user']['c5ts_logon_revoked']));
        if (isset($_SESSION['StatePicked']['t0contractor']) and isset($_SESSION['t0user_contractor'])) {
            $Title = $this->decrypt_1c($_SESSION['t0user_contractor']['c5job_title']);
            $Employer = $this->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']);
            $WorkEmail = $this->decrypt_1c($_SESSION['t0user_contractor']['c5work_email']);
        }
        elseif (isset($_SESSION['StatePicked']['t0owner']) and isset($_SESSION['t0user_owner'])) {
            $Title = $this->decrypt_1c($_SESSION['t0user_owner']['c5job_title']);
            $Employer = $this->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
            $WorkEmail = $this->decrypt_1c($_SESSION['t0user_owner']['c5work_email']);
        }
        if ($Title == '[Nothing has been recorded in this field.]')
            $Title = 'title not found';
        if ($Employer == '[Nothing has been recorded in this field.]')
            $Employer = 'employer not found';
        if ($WorkEmail == '[Nothing has been recorded in this field.]')
            $WorkEmail = 'work email address not found';
        $NameTitle = $Name.', '.$Title;
        $NameTitleEmployerWorkEmail = $Name.', '.$Title.', '.$Employer.', '.$WorkEmail;
        return array(
            'GlobalDBMSPrivileges'  => $GlobalDBMSPrivileges,
            'Name'                  => $Name,
            'Title'                 => $Title,
            'NameTitle'             => $NameTitle,
            'Employer'              => $Employer,
            'WorkEmail'             => $WorkEmail,
            'NameTitleEmployerWorkEmail' => $NameTitleEmployerWorkEmail,
            'TimeLogonRevoked' => $TimeLogonRevoked
        );
    }

    ////////////////////////////////////////////////////////////////////////////
    // When looking for information about a user, ask yourself, "Do I want...
    //      (1) the latest information about the user (their latest-recorded name, job, contact information...) or
    //      (2) the information that described the user at a particular time in the past.
    // For approvals, historic (option 2) user information is stored in the c6nymd... database columns at the time of digital approval.
    // Otherwise, historic user information may be found in the history records.
    // 
    // This function addresses option 1 above, by attempting to retrieve the latest, most relevant user information.
    // A user might be employed by the owner (that the function-calling user currently selected), by one or more owner-associated contractors, or by none of these.
    //
    // This function returns the latest user information (from t0user) and
    // any applicable user employment information from t0user_owner or t0user_contractor.
    //
    // In the rare cases when a user works for more than one owner-associated employer, the following hierarchy is used:
    //      - if the user is an employee of the owner (that function-calling user currently selected), that user_owner employment information is used, and any user_contractor employment information is ignored.
    //      - else if the user is an employee of one owner-associated contractor, that employment information is used.
    //      - else if the user is an employee of more than one owner-associated contractor, then
    //          - if $_SESSION['StatePicked']['t0contractor']['k0contractor'] isset (by the function-calling user) and is associated with the owner and the user, that employment information is used.
    //          - else the user_contractor employment information for the contractor with the oldest relationship with the owner is used.
    // If the function-calling user has selected a contractor but not an owner, and the user whose information is sought is an employee of this contractor, then that employment information is used.
    // Lastly, if the user is currently associated with neither the owner nor an associated contractor, the function-calling user is notified and referred to the history records, via $Message.
    //
    // The user's k0user is the only required input, other information is gathered from the $_SESSION['StatePicked'] context.
    // Returns an array with keys:
    //    't0user'              FALSE or an associative array with one row from table t0user, whose keys are the database-table column names.
    //    't0user_employer'     FALSE or an associative array with one row from t0user_owner or t0user_contractor.
    //    't0employer'          FALSE or an associative array with one row from t0owner or t0contractor.
    //    'Message'             FALSE or a message about the status or lack of job information.
    //    'Name'                'Name not found' or full_name_1c output, which is decrypted.
    //    'Title'               'title not found' or decrypted c5job_title field from t0owner or t0contractor.
    //    'NameTitle'           Name, Title
    //    'Employer'            'employer not found' or decrypted employer name
    //    'WorkEmail'           'work email address not found' or decrypted work email
    //    'NameTitleEmployerWorkEmail' Name, Title, Employer, WorkEmail
    public function user_job_info_1c($k0user) {
        $t0user = FALSE;
        $t0user_employer = FALSE;
        $t0employer = FALSE;
        $Message = '';
        $Name = 'Name not found';
        $Title = '[Nothing has been recorded in this field.]';
        $Employer = '[Nothing has been recorded in this field.]';
        $WorkEmail = '[Nothing has been recorded in this field.]';
        $TimeLogonRevoked = '[Nothing has been recorded in this field.]';
        $ConditionsUser[0] = array('k0user', '=', $k0user);
        $DBMSresource = $this->credentials_connect_instance_1s();
        list($SRUser, $RRUser) = $this->select_sql_1s($DBMSresource, 't0user', $ConditionsUser);
        if ($RRUser > 1)
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' user_job_info_1c() Eject Case 1. Rows Returned: '.@$RRUser);
        elseif ($RRUser == 1) {
            $t0user = $SRUser[0];
            $Name = $this->full_name_1c($t0user);
            $TimeLogonRevoked = $this->timestamp_to_display_1c($this->decrypt_1c($t0user['c5ts_logon_revoked']));
        }
        if (isset($_SESSION['StatePicked']['t0owner']['k0owner'])) { // The function-calling user has selected an owner.
            $ConditionsUO[0] = array('k0user', '=', $k0user, 'AND');
            $ConditionsUO[1] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner']);
            list($SRUO, $RRUO) = $this->select_sql_1s($DBMSresource, 't0user_owner', $ConditionsUO);
            if ($RRUO > 1)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' user_job_info_1c() Eject Case 2. Rows Returned: '.@$RRUO);
            elseif ($RRUO == 1) { // The user is an employee of the owner that is currently selected by the function-calling user.
                $t0user_employer = $SRUO[0];
                $t0employer = $_SESSION['StatePicked']['t0owner'];
            }
            else { // The user may be an employee of an owner-associated contractor.
                $Message .= 'Not employee of the Owner. Check the history records.';
                list($SRUC, $RRUC) = $this->select_sql_1s($DBMSresource, 't0user_contractor', $ConditionsUser);
                if (!$RRUC)
                    $Message .= ' Also, not employee of any contractor in PSM-CAP App.';
                else {
                    $ConditionsOC[0] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner']);
                    list($SROC, $RROC) = $this->select_sql_1s($DBMSresource, 't0owner_contractor', $ConditionsOC);
                    if ($RROC) foreach ($SROC as $OC) {
                        foreach ($SRUC as $UC) {
                            if (isset($_SESSION['StatePicked']['t0contractor']['k0contractor'])) {
                                if ($OC['k0contractor'] == $_SESSION['StatePicked']['t0contractor']['k0contractor'] and $UC['k0contractor'] == $_SESSION['StatePicked']['t0contractor']['k0contractor']) { // The user, the function-calling user, and the owner are all associated with this contractor, so use this job information.
                                    $t0user_employer = $UC;
                                    $t0employer = $_SESSION['StatePicked']['t0contractor'];
                                    break 2;
                                }
                            }
                            elseif ($UC['k0contractor'] == $OC['k0contractor']) { // Use the user-associated contractor with the oldest association with the owner, if any, which will be $SROC[0], the first one reach in the outer foreach loop. Really just a guess, but most contractor individuals are only associated with one contractor organization, so only one choice here.
                                $t0user_employer = $UC;
                                $ConditionsContractor[0] = array('k0contractor', '=', $OC['k0contractor']);
                                list($SRContractor, $RRContractor) = $this->select_sql_1s($DBMSresource, 't0contractor', $ConditionsContractor);
                                if ($RRContractor > 1)
                                    $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' user_job_info_1c() Eject Case 3. Rows Returned: '.@$RRContractor);
                                elseif ($RRContractor == 1) {
                                    $t0employer = $SRContractor[0];
                                    break 2;
                                }
                            }
                        }
                    }
                    if (!$t0user_employer)
                        $Message .= ' Also, not employee of an owner-associated contractor.';
                }
            }
        }
        elseif (isset($_SESSION['StatePicked']['t0contractor']['k0contractor'])) { // The function-calling user has selected a contractor but not an owner.
            list($SRUC, $RRUC) = $this->select_sql_1s($DBMSresource, 't0user_contractor', $ConditionsUser);
            if (!$RRUC)
                $Message .= 'No owner was selected and not employee of any contractor in PSM-CAP App. Check the history records.';
            else {
                foreach ($SRUC as $UC) {
                    if ($UC['k0contractor'] == $_SESSION['StatePicked']['t0contractor']['k0contractor']) { // The user is an employee of this contractor.
                        $t0user_employer = $UC;
                        $t0employer = $_SESSION['StatePicked']['t0contractor'];
                        break;
                    }
                }
                if (!$t0user_employer)
                    $Message .= 'No owner was selected and not employee of selected contractor. Check the history records.';
            }
        }
        $this->close_connection_1s($DBMSresource);
        if ($t0user_employer) {
            $Message = FALSE;
            $Title = $this->decrypt_1c($t0user_employer['c5job_title']);
            $Employer = $this->decrypt_1c($t0employer['c5name']);
            $WorkEmail = $this->decrypt_1c($t0user_employer['c5work_email']);
        }
        if ($Title == '[Nothing has been recorded in this field.]')
            $Title = 'title not found';
        if ($Employer == '[Nothing has been recorded in this field.]')
            $Employer = 'employer not found';
        if ($WorkEmail == '[Nothing has been recorded in this field.]')
            $WorkEmail = 'work email address not found';
        $NameTitle = $Name.', '.$Title;
        $NameTitleEmployerWorkEmail = $Name.', '.$Title.', '.$Employer.', '.$WorkEmail;
        return array(
            't0user'            => $t0user,
            't0user_employer'   => $t0user_employer,
            't0employer'        => $t0employer,
            'Message'           => $Message,
            'Name'              => $Name,
            'Title'             => $Title,
            'NameTitle'         => $NameTitle,
            'Employer'          => $Employer,
            'WorkEmail'         => $WorkEmail,
            'NameTitleEmployerWorkEmail' => $NameTitleEmployerWorkEmail,
            'TimeLogonRevoked' => $TimeLogonRevoked
        );
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns HTML for a standard organization description based on a
    // $SelectedRow from a database table with standard fields for describing an organization
    // such as t0owner or t0contractor
    // <a id="organization"></a> -- an HTML anchor -- is part of the returned HTML.
    public function organziation_info_html_1c($SelectedRow) {
        $Street2 = $this->decrypt_1c($SelectedRow['c5street2']);
        $Description = $this->decrypt_1c($SelectedRow['c6description']);
        $HTML = '<p><a id="organization"></a>
        <b>Name:</b> '.$this->decrypt_1c($SelectedRow['c5name']).'<br />
        <b>Address:</b> '.$this->decrypt_1c($SelectedRow['c5street1']);
        if ($Street2 != '[Nothing has been recorded in this field.]')
            $HTML .= ', '.$Street2;
        $HTML .= ', '.$this->decrypt_1c($SelectedRow['c5city']).', '.$this->decrypt_1c($SelectedRow['c5state_province']).', '.$this->decrypt_1c($SelectedRow['c5country']).'<br />
        <b>Lead Officer or Responsible Individual:</b> '.$this->decrypt_1c($SelectedRow['c5chief_officer_name']);
        if($Description != '[Nothing has been recorded in this field.]')
            $HTML .= '<br />
            <b>Description:</b> '.$Description;
        $HTML .= '</p>';
        return $HTML;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns as HTML (safe for echo) information about an affected entity and its leader.
    // $AEscope (Allowed values: 'Owner-wide', 'Contractor-wide', 'Facility-wide', 'Process-wide', or lower-case versions of these) defaults to $_SESSION['Selected']['c5affected_entity']
    // $AEk0 is the primary key for the affected owner, contractor, facility, or process. It defaults to $_SESSION['Selected']['k0affected_entity']
    // Remaining information comes from the $_SESSION['StatePicked'] context.
    // Returns, decrypted, an associative array with keys:
    //      AEscope           -- Allowed values: 'Owner-wide', 'Contractor-wide', 'Facility-wide', or 'Process-wide'
    //      AETypeAndName     -- example: 'Owner/Operator: My Company, Inc.' or 'Process: Stuff in Tanks and Piping'
    //      AEFullDescription -- example: 'Process: X in Facility Y owned by Z' or 'Contractor: A'
    //      AELeader_k0user   -- the k0user key for the affected-entity (AE) leader.
    //      AELeaderName      -- the user_job_info_1c() output
    //      AELeaderTitle     -- the user_job_info_1c() output
    //      AELeaderNameTitle -- the user_job_info_1c() output
    //      AELeaderEmployer  -- the user_job_info_1c() output
    //      AELeaderWorkEmail -- the user_job_info_1c() output
    public function affected_entity_info_1c($AEscope = FALSE, $AEk0 = FALSE) {
        if ((!isset($_SESSION['Selected']['c5affected_entity']) and !$AEscope) or (!isset($_SESSION['Selected']['k0affected_entity']) and !$AEk0))
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' affected_entity_info_1c() Eject Case 1');
        if (!$AEscope)
            $AEscope = $this->decrypt_1c($_SESSION['Selected']['c5affected_entity']);
        if (!$AEk0)
            $AEk0 = $_SESSION['Selected']['k0affected_entity'];
        if ($AEscope == 'Owner-wide' or $AEscope == 'owner-wide') {
            if (!isset($_SESSION['StatePicked']['t0owner']) or $_SESSION['StatePicked']['t0owner']['k0owner'] != $AEk0)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' affected_entity_info_1c() Eject Case 2');
            $AETypeAndName = 'Owner/Operator '.$this->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
            $AEFullDescription = $AETypeAndName; // No difference between these variables at this top level.
            $AELeader_k0user = $_SESSION['StatePicked']['t0owner']['k0user_of_leader'];
        }
        elseif ($AEscope == 'Contractor-wide' or $AEscope == 'contractor-wide') {
            if (!isset($_SESSION['StatePicked']['t0contractor']) or $_SESSION['StatePicked']['t0contractor']['k0contractor'] != $AEk0)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' affected_entity_info_1c() Eject Case 3');
            $AETypeAndName = 'contractor '.$this->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']);
            $AEFullDescription = $AETypeAndName; // No difference between these variables at this top level.
            $AELeader_k0user = $_SESSION['StatePicked']['t0contractor']['k0user_of_leader'];
        }
        elseif ($AEscope == 'Facility-wide' or $AEscope == 'facility-wide') {
            if (!isset($_SESSION['StatePicked']['t0facility']) or $_SESSION['StatePicked']['t0facility']['k0facility'] != $AEk0)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' affected_entity_info_1c() Eject Case 4');
            $AETypeAndName = 'facility '.$this->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']);
            $AEFullDescription = $AETypeAndName.' owned by '.$this->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']); // If $_SESSION['StatePicked'] has process or facility information, it must also have owner information.
            $AELeader_k0user = $_SESSION['StatePicked']['t0facility']['k0user_of_leader'];
        }
        elseif ($AEscope == 'Process-wide' or $AEscope == 'process-wide') {
            if (!isset($_SESSION['StatePicked']['t0process']) or $_SESSION['StatePicked']['t0process']['k0process'] != $AEk0)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' affected_entity_info_1c() Eject Case 5');
            $AETypeAndName = 'process '.$this->decrypt_1c($_SESSION['StatePicked']['t0process']['c5name']);
            $AEFullDescription = $AETypeAndName.' in facility '.$this->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).' owned by '.$this->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
            $AELeader_k0user = $_SESSION['StatePicked']['t0process']['k0user_of_leader'];
        }
        else
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' affected_entity_info_1c() Eject Case 6');
        if (!isset($AELeader_k0user)) {
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::affected_entity_info_1c() Error Log Case 1 (no AE leader)');
            $this->send_to_contents_1c(__FILE__, __LINE__, '<p>The PSM-CAP App <b>could not find a leader</b> associated with the affected entity that you selected, so the task that you had started was canceled. Each entity (owner, contractor, facility, or process) should have exactly one PSM-CAP leader. Please contact your supervisor or a PSM-CAP App administrator for assistance.</p>');
        }
        $AELeaderInfo = $this->user_job_info_1c($AELeader_k0user);
        return array(
            'AEscope'           => $AEscope,
            'AETypeAndName'     => $AETypeAndName,
            'AEFullDescription' => $AEFullDescription,
            'AELeader_k0user'   => $AELeader_k0user,
            'AELeaderName'      => $AELeaderInfo['Name'],
            'AELeaderTitle'     => $AELeaderInfo['Title'],
            'AELeaderNameTitle' => $AELeaderInfo['NameTitle'],
            'AELeaderEmployer'  => $AELeaderInfo['Employer'],
            'AELeaderWorkEmail' => $AELeaderInfo['WorkEmail'],
            'AELeaderNameTitleEmployerWorkEmail' => $AELeaderInfo['NameTitleEmployerWorkEmail'],
            'AELeaderInfo'      => $AELeaderInfo
            
        );
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function creates a $ChangedRow for i3 files, an array with encrypted values (except k0 fields)
    // and with keys matching $htmlFormAray and $_SESSION['Scratch']['ModifiedValues'],
    // $EncryptedOldStuff, typically from $_SESSION['Selected'], is replaced with any
    // $NewStuff, typically decrypted/decoded $_SESSION['Scratch']['ModifiedValues'],
    // special cases, such a k, s, c5ts, c6bfn, and also handled, see below.
    public function changes_from_post_1c($EncryptedOldStuff = FALSE, $NewStuff = FALSE) {
        if (!$EncryptedOldStuff) {
            if (!isset($_SESSION['Selected']))
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__);
            $EncryptedOldStuff = $_SESSION['Selected'];
        }
        if (!$NewStuff) {
            if (!isset($_SESSION['Scratch']['ModifiedValues']))
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__);
            $NewStuff = $this->decrypt_decode_1c($_SESSION['Scratch']['ModifiedValues']);
        }
        foreach ($EncryptedOldStuff as $KA => $VA) {
            if (isset($NewStuff[$KA])) {
                if (is_array($NewStuff[$KA]))
                    $ChangedRow[$KA] = $this->encode_encrypt_1c($NewStuff[$KA]); // See $NewStuff in ConfirmZfpf::post_select_required_compare_confirm_1e
                elseif (
                    substr($KA, 0, 6) == 'c6bfn_' or
                    substr($KA, 0, 2) == 'k0'
                )
                    $ChangedRow[$KA] = $VA; // Fields for files uploads (c6bfn) and for keys (k0) are recorded separately from i3 code.
                elseif (substr($KA, 0, 5) == 'c5ts_')
                    $ChangedRow[$KA] = $this->encrypt_1c($this->text_to_timestamp_1c($NewStuff[$KA]));
                elseif (
                    substr($KA, 0, 2) == 'c5' or 
                    substr($KA, 0, 2) == 'c6'
                ) // This case must come after all c5 and c6 special cases above.
                    $ChangedRow[$KA] = $this->encrypt_1c($NewStuff[$KA]);
                elseif (substr($KA, 0, 2) == 'k2') // such as k2username_hash
                    $ChangedRow[$KA] = $this->hash_1c($NewStuff[$KA]);
                elseif (substr($KA, 0, 2) == 's2')
                    $ChangedRow[$KA] = password_hash($NewStuff[$KA], PASSWORD_DEFAULT);
                elseif (substr($KA, 0, 2) == 's5')
                    $ChangedRow[$KA] = $this->encrypt_1c(password_hash($NewStuff[$KA], PASSWORD_DEFAULT));
                else
                    $ChangedRow[$KA] = $NewStuff[$KA]; // fpf 0, 1, 2, 3, and 4 datatypes are not encrypted.
            }
            elseif (isset($EncryptedOldStuff['c5who_is_editing']) and $this->decrypt_1c($EncryptedOldStuff['c5who_is_editing']) == '[A new database row is being created.]')
                $ChangedRow[$KA] = $VA; // So, $ChangedRow holds everything in $EncryptedOldStuff for inserts.
        }
        if (isset($EncryptedOldStuff['c5who_is_editing']))
            $ChangedRow['c5who_is_editing'] = $this->encrypt_1c('[Nobody is editing.]'); // Restore c5who_is_editing if present in OldStuff
        return $ChangedRow;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // This function outputs a risk ranking, based on severity and likelihood inputs.
    // NULL is returned if the input does not match the cases below.
    // The constants below are, like all constants in this web-app, defined in /settings/CoreSettingsZfpf.php
    public function risk_rank_1c($Severity, $Likelihood) {
        switch ($Likelihood) {
            case _1_LIKELIHOOD_ZFPF:
                switch ($Severity) {
                    case _1_SEVERITY_ZFPF:
                        $RiskRank = _00_PRIORITY_ZFPF;
                    break;
                    case _2_SEVERITY_ZFPF:
                        $RiskRank = _01_PRIORITY_ZFPF;
                    break;
                    case _3_SEVERITY_ZFPF:
                        $RiskRank = _03_PRIORITY_ZFPF;
                    break;
                    case _4_SEVERITY_ZFPF:
                        $RiskRank = _05_PRIORITY_ZFPF;
                    break;
                    case _5_SEVERITY_ZFPF:
                        $RiskRank = _07_PRIORITY_ZFPF;
                    break;
                }
            break;
            case _2_LIKELIHOOD_ZFPF:
                switch ($Severity) {
                    case _1_SEVERITY_ZFPF:
                        $RiskRank = _00_PRIORITY_ZFPF;
                    break;
                    case _2_SEVERITY_ZFPF:
                        $RiskRank = _02_PRIORITY_ZFPF;
                    break;
                    case _3_SEVERITY_ZFPF:
                        $RiskRank = _04_PRIORITY_ZFPF;
                    break;
                    case _4_SEVERITY_ZFPF:
                        $RiskRank = _06_PRIORITY_ZFPF;
                    break;
                    case _5_SEVERITY_ZFPF:
                        $RiskRank = _08_PRIORITY_ZFPF;
                    break;
                }
            break;
            case _3_LIKELIHOOD_ZFPF:
                switch ($Severity) {
                    case _1_SEVERITY_ZFPF:
                        $RiskRank = _00_PRIORITY_ZFPF;
                    break;
                    case _2_SEVERITY_ZFPF:
                        $RiskRank = _03_PRIORITY_ZFPF;
                    break;
                    case _3_SEVERITY_ZFPF:
                        $RiskRank = _05_PRIORITY_ZFPF;
                    break;
                    case _4_SEVERITY_ZFPF:
                        $RiskRank = _07_PRIORITY_ZFPF;
                    break;
                    case _5_SEVERITY_ZFPF:
                        $RiskRank = _09_PRIORITY_ZFPF;
                    break;
                }
            break;
            case _4_LIKELIHOOD_ZFPF:
                switch ($Severity) {
                    case _1_SEVERITY_ZFPF:
                        $RiskRank = _00_PRIORITY_ZFPF;
                    break;
                    case _2_SEVERITY_ZFPF:
                        $RiskRank = _04_PRIORITY_ZFPF;
                    break;
                    case _3_SEVERITY_ZFPF:
                        $RiskRank = _06_PRIORITY_ZFPF;
                    break;
                    case _4_SEVERITY_ZFPF:
                        $RiskRank = _08_PRIORITY_ZFPF;
                    break;
                    case _5_SEVERITY_ZFPF:
                        $RiskRank = _10_PRIORITY_ZFPF;
                    break;
                }
            break;
            case _5_LIKELIHOOD_ZFPF:
                switch ($Severity) {
                    case _1_SEVERITY_ZFPF:
                        $RiskRank = _00_PRIORITY_ZFPF;
                    break;
                    case _2_SEVERITY_ZFPF:
                        $RiskRank = _05_PRIORITY_ZFPF;
                    break;
                    case _3_SEVERITY_ZFPF:
                        $RiskRank = _08_PRIORITY_ZFPF;
                    break;
                    case _4_SEVERITY_ZFPF:
                        $RiskRank = _09_PRIORITY_ZFPF;
                    break;
                    case _5_SEVERITY_ZFPF:
                        $RiskRank = _10_PRIORITY_ZFPF;
                    break;
                }
            break;
        }
        if (isset($RiskRank))
            return $RiskRank;
        else
            return 'Not yet determined. Either severity, likelihood, or both have not been recorded.';
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns actions, either draft actions from a recommending report ($SourceDocDraftActions)
    // or open actions associated with the current owner, contractor, facility, or process ($AssociatedActions).
    // $k0source -- see setup.php for a description of t0action:k0source.
    // $AlreadySelectedKeys is an optional array holding keys for actions to be excluded from both returned arrays.
    public function actions_select_1c($k0source, $AlreadySelectedKeys = NULL) {
        $SourceDocDraftActions = NULL;
        $AssociatedActions = NULL;
        $DBMSresource = $this->credentials_connect_instance_1s();
        // SELECT draft actions associated with the recommending report (the source), excluding $AlreadySelectedKeys.  See setup.php for a description of t0action:k0source.
        $Conditions[0] = array('k0source', '=', $k0source);
        list($SelectResults, $RowsReturned) = $this->select_sql_1s($DBMSresource, 't0action', $Conditions);
        if ($RowsReturned > 0) {
            foreach ($SelectResults as $V) {
                if (empty($AlreadySelectedKeys) or !in_array($V['k0action'], $AlreadySelectedKeys)) {
                    $SourceDocDraftActions[] = $V;
                    $c5name[] = $this->decrypt_1c($V['c5name']);
                }
            }
            if (isset($c5name)) {
                array_multisort($c5name, $SourceDocDraftActions);
                unset($c5name);
            }
        }
        // SELECT all open actions associated with the process, it's facility, or it's owner, to avoid the user creating duplicates.
        // Actions that have been approved as resolved are not planned/ongoing actions; if they didn't fix the problem, a new action is needed.
        $ConditionsAction[] = array('k0source', '=', 0, 'AND '); // This selects only NOT "draft" actions, so no intersection with draft actions selected from recommending report above.
        $ConditionsAction[] = array('k0user_of_ae_leader', '=', 0, 'AND ('); // This selects only NOT "owner approved" actions -- ones that the affected-entity leader has NOT approved as complete. In other words, this code is selecting open actions, that the recommending report is providing another reason to complete. When k0user_of_ae_leader == 0, database column t0action:c5status is not equal to "owner approved". Once an action has been owner approved, it can be listed as an existing safeguard.
        $ConditionsAction[] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process'], 'OR');
        $ConditionsAction[] = array('k0facility', '=', $_SESSION['StatePicked']['t0facility']['k0facility'], 'OR');
        if (isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
            $ConditionsAction[] = array('k0contractor', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], 'OR');
        $ConditionsAction[] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner'], ')');
        list($SelectResults, $RowsReturned) = $this->select_sql_1s($DBMSresource, 't0action', $ConditionsAction);
        if ($RowsReturned > 0) {
            foreach ($SelectResults as $V) {
                if (empty($AlreadySelectedKeys) or !in_array($V['k0action'], $AlreadySelectedKeys)) {
                    $AssociatedActions[] = $V;
                    $c5name[] = $this->decrypt_1c($V['c5name']);
                }
            }
            if (isset($c5name))
                array_multisort($c5name, $AssociatedActions);
        }
        $this->close_connection_1s($DBMSresource);
        return array($SourceDocDraftActions, $AssociatedActions);
    }

    ////////////////////////////////////////////////////////////////////////////
    // Use this function at the end of io03 files, 
    // where a user may arrive here by pressing the back or forward button on their browser.
    public function catch_all_1c($Action) {
        echo $this->xhtml_contents_header_1c('Oops');
                // TO DO FOR PRODUCTION VERSION -- comment out - START.
                // print_r($_POST);
                // echo '<p>The above print_r($_POST) will be removed form the production version.</p>';
                // TO DO FOR PRODUCTION VERSION -- comment out - END.
        echo '<h1>
        Oops...</h1><p><b>
        The app could not figure out what you wanted to do.</b></p><p>
        You may have pressed the back button or the forward button on your browser, which are not supported by this app.</p>
        <form action="'.$Action.'" method="post"><p>
        <input type="submit" value="Go Back" /></p>
        </form>
        '.$this->xhtml_footer_1c();
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function checks a couple errors with the c6active_sessions array, after decrypting and decoding, and returns empty array if found.
    public function check_array_1c($ActiveSessions) {
        if (is_null($ActiveSessions)) {
            $ActiveSessions = array();
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::check_array_1c Error Log Case 1 -- decrypt_decode_1c returned NULL, perhaps because app attempted to encode bit data, like an encrypted string in database field t0user:c6active_sessions returned');
        }
        elseif (!is_array($ActiveSessions)) {
            $ActiveSessions = array();
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::check_array_1c Error Log Case 2 -- decrypt_decode_1c did not return an array, maybe when database field t0user:c6active_sessions was passed in');
        }
        if (!$ActiveSessions and isset($_SESSION['t0user']))
            $_SESSION['t0user']['c6active_sessions'] = $this->encode_encrypt_1c(array()); // Cleanup here too.
        return $ActiveSessions;
    }

    ////////////////////////////////////////////////////////////////////////////
    // Use this function saves $_SESSION in the database
    // and serves as wrapper for exit, so anything else needed before exiting can be done here.
    // Called at the bottom of every .php file in /pcm/ except
    //      directory_path_settings.php
    //      front_controller.php
    //      glossary.php
    //      logon.php
    //      logoff.php
    //      setup.php
    // The following call exit() without calling this file
    //      CoreZfpf::eject_1c
    //      CoreZfpf::normal_log_off_1c
    //      _1s store functions in this file and stuff in setup.php
    // $DBMSresource, if passed in, must have at least 'logon maintenance' privileges.
    public function save_and_exit_1c($DBMSresource = FALSE) {
        $SessionID = session_id();
        if (isset($_SESSION['t0user']) and $SessionID) {
            $DBMSresource = $this->credentials_connect_instance_1s(MAX_PRIVILEGES_ZFPF); // MAX_PRIVILEGES_ZFPF needed to select from t0user_practice and update t0user, so overwrite any $DBMSResource passed in.
            $Conditions[0] = array('k0user', '=', $_SESSION['t0user']['k0user']);
            list($SR, $RR) = $this->select_sql_1s($DBMSresource, 't0user', $Conditions); // Get latest from database
            if ($RR != 1)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' save_and_exit_1c() Eject Case 1. Rows Returned: '.@$RR);
            $ActiveSessions = $this->check_array_1c($this->decrypt_decode_1c($SR[0]['c6active_sessions']));
            // TO DO FOR PRODUCTION VERSION -- comment out - START.
            // For troubleshooting CoreZfpf::check_array_1c Error Log -- triggered by line above.
            // var_dump($ActiveSessions);
            // TO DO FOR PRODUCTION VERSION -- comment out - END.
            if (!isset($ActiveSessions[$SessionID]))
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::save_and_exit_1c Error Log Case 1 -- decrypt_decode_1c on database field t0user:c6active_sessions did not hold the session for the current logon, when it should'); // Don't eject, just rebuild active sessions array.
            // TO DO FOR PRODUCTION VERSION -- comment out - START.
            // var_dump($_SESSION);
            // TO DO FOR PRODUCTION VERSION -- comment out - END.
            $ActiveSessions[$SessionID] = $this->decrypt_array_1c($_SESSION); // Overwrite entry for current session.
            $Changes['c6active_sessions'] = $this->encode_encrypt_1c($ActiveSessions); // Update database...
            $Affected = $this->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, FALSE); // ...not recorded in history.
            if ($Affected != 1)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' save_and_exit_1c() Eject Case 2. Affected: '.@$Affected);
        }
        if (isset($DBMSresource) and $DBMSresource)
            $this->close_connection_1s($DBMSresource); // Always try to close connection here, in case calling script didn't.
        exit; // Don't save and exit.
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function returns the highest value in column $ColumnName of table $TableName, less than $MaxValue
    // For what is meant by "highest", see https://www.php.net/manual/en/function.max.php 
    // It may be used to get the highest primary key in a database table, including the keys below a $MaxValue indicating a template or sample entry.
    function get_highest_in_table($DBMSresource, $ColumnName, $TableName, $MaxValue = FALSE) {
        list($SR, $RR) = $this->select_sql_1s($DBMSresource, $TableName, 'No Condition -- All Rows Included', array($ColumnName));
        if ($RR) {
            foreach ($SR as $V) {
                if (!$MaxValue or $V[$ColumnName] <= $MaxValue)
                    $ColumnValues[] = $V[$ColumnName];
            }
            return max($ColumnValues);
        }
        else
            return 0; // Case when table has no rows.
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////// Database Interaction Functions - here to end of this PHP file. ////////////////////////////
    // The goal of wrapping all database functions is to allow changing DBMS by only 
    // changing functions in this file.
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////
    // This function supplies credentials based on the constants defined in StoreSettingsZfpf.php
    public function credentials_1s($Privileges = FALSE) {
        if (!$Privileges and isset($_SESSION['t0user']['c5p_global_dbms'])) {
            $Privileges = $this->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);
        }
        switch ($Privileges) {
            case MAX_PRIVILEGES_ZFPF:
                return array(USERNAME1_ZFPF, $this->hash_1c(PASSWORD1_ZFPF));
            case MID_PRIVILEGES_ZFPF:
                return array(USERNAME2_ZFPF, $this->hash_1c(PASSWORD2_ZFPF));
            case 'logon maintenance': // This is only used for logging on and off. It's not an allowed value for a user's global DBMS privileges.
                return array(USERNAME3_ZFPF, $this->hash_1c(PASSWORD3_ZFPF));
            case LOW_PRIVILEGES_ZFPF:
                return array(USERNAME4_ZFPF, $this->hash_1c(PASSWORD4_ZFPF));
        }
        error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::credentials_1s');
        exit('<br />An error occurred. Contact your supervisor or an app admin for assistance.<br />');
    }

    ////////////////////////////////////////////////////////////////////////////
    // See 0read_me_psm_cap_app_standards.txt
    // This function returns a DBMS data type name based on the FACT+FANCY (fpf) 
    // data-type name that is always the first 2 characters of a column name in fpf databases.
    // Mapping to additional DBMS data types may be added via additional case statements below.
    // If you're not using MySQL 4.1 (production release October 2004) or above, you need to use PHP mysql functions, 
    // so add a case below for mysql with same datatypes as mysqli and create the mysql wrappers with mysql_real_escape_string()
    // A useful (but arbitrary) data length is pre-selected below for c4.
    // The assumption is that c4 (CHAR(n)) will only be used for 1 character storage (true/false, etc.) 
    // and c2 (VARCHAR(255)) will be used for all other character storage.  
    // Conceptual note: the very simplified data-type design-approach below is done for similar 
    // reasons to making bedroom doors in a house a standard safe size, which is much wiser than making bedroom doors
    // the minimum size that each person needs to fit through their door.
    public function data_type_1s($ColumnName, $DBMStype) {
        $fpfDataType = substr($ColumnName, 1, 1);
        switch ($fpfDataType) {
            case '0':
                switch ($DBMStype) {
                    case 'MDB2':
                        return 'float';  // If integer is used here, it maps to signed integer in MySQL with upper limit of 2147483647
                    case 'mssql':
                        return 'bigint not null';
                    case 'mysqli':
                        return 'BIGINT NOT NULL';
                    case 'oci8':
                        return 'NUMBER NOT NULL'; // TO DO 2019-05-29 Need to verify if this is a proper Oracle datatype.
                }
            case '1':
                switch ($DBMStype) {
                    case 'MDB2':
                        return 'float';
                    case 'mssql':
                        return 'float';
                    case 'mysqli':
                        return 'DOUBLE';
                    case 'oci8':
                        return 'NUMBER'; // TO DO 2019-05-29 Need to verify if this is a proper Oracle datatype.
                }
            case '2':
                switch ($DBMStype) {
                    case 'MDB2':
                        return 'text';
                    case 'mssql':
                        return 'varchar(255)';
                    case 'mysqli':
                        return 'VARCHAR(255)';
                    case 'oci8':
                        return 'VARCHAR(255)'; // TO DO 2019-05-29 Need to verify if this is a proper Oracle datatype.
                }
            case '3':
                switch ($DBMStype) {
                    case 'MDB2':
                        return 'clob';
                    case 'mssql':
                        return 'text';
                    case 'mysqli':
                        return 'LONGTEXT';
                    case 'oci8':
                        return 'CLOB';
                }
            // CHAR(1), a subset of c4, is mapped below; see comment above.
            case '4':
                switch ($DBMStype) {
                    case 'MDB2':
                        return 'text';
                    case 'mssql':
                        return 'char(1)';
                    case 'mysqli':
                        return 'CHAR(1)';
                    case 'oci8':
                        return 'CHAR(1)';
                }
            case '5':
                switch ($DBMStype) {
                    case 'MDB2':
                        return 'text'; // VARBINARY treated like text. Potentially DBMS SPECIFIC INSTRUCTION
                    case 'mssql':
                        return 'varbinary(255)';
                    case 'mysqli':
                        return 'VARBINARY(255)';
                    case 'oci8':
                        return 'RAW(255)';
                }
            case '6':
                switch ($DBMStype) {
                    case 'MDB2':
                        return 'blob';
                    case 'mssql':
                        return 'image';
                    case 'mysqli':
                        return 'LONGBLOB';
                    case 'oci8':
                        return 'BLOB';
                }
        }
        error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::data_type_1s');
        exit('<br />An error occurred. Contact your supervisor or an app admin for assistance.<br />');
    }


    ////////////////////////////////////////////////////////////////////////////
    // This function connects to DBMS and selects the database instance (the named collection of tables, typically implemented by the storage engine as a directory (folder) on a computer file system.)
    // $username, $password are for database instance.
    // $instance is the database-instance name recognized by the DBMS.
    // Returns the identifier for a DBMS resource.
    // $HostPath is the path to the location on the computer that hosts the database, such as "localhost" for development machines, an Internet Protocol (IP) address, etc.
    // $port is the port number to attempt to connect to the MySQL server.
    // $sock is the socket or named pipe to connect to the MySQL server, such as a Unix domain socket.
    public function connect_instance_1s($username, $password, $instance = DATABASE_INSTANCE_ZFPF, $HostPath = DBMS_HOSTPATH_ZFPF, $port = DBMS_PORT_ZFPF, $socket = DBMS_SOCKET_ZFPF) {
        ///////////////////////////////// mysqli wrapper - start ///////////////////////////////
        $DBMSresource = mysqli_connect($HostPath, $username, $password, $instance, $port, $socket);
        if (!$DBMSresource) {
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::connect_instance_1s. DBMS Error: '.@mysqli_connect_error().' (Blank means no DBMS Error.)');
            exit('<br />An error occurred. Contact your supervisor or an app admin for assistance.<br />');
            // TO DO 2019-05-29 for a high-volume app: add better error handling, such reties with exponential backoff, and connection pooling or persistent connections, see http://php.net/manual/en/mysqli.persistconns.php
        }
        return $DBMSresource;
        ///////////////////////////////// mysqli wrapper - end ///////////////////////////////
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function combines credentials() and connect_instance()
    public function credentials_connect_instance_1s($Priviledges = FALSE) {
        list($username, $password) = $this->credentials_1s($Priviledges);
        $DBMSresource = $this->connect_instance_1s($username, $password);
        return $DBMSresource;
    }


    ////////////////////////////////////////////////////////////////////////////
    // This function disconnects from the DBMS
    public function close_connection_1s($DBMSresource) {
        ////////////////////////////////// mysqli wrapper - start ///////////////////////////////
        mysqli_close($DBMSresource);
        ////////////////////////////////// mysqli wrapper - end ///////////////////////////////
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function wraps DBMS-specific escaping functions, to allow use of the escaped characters,
    // prevent SQL injection or, if applicable, random error from data encryption.
    public function dbms_string_escape_1s($DBMSresource = FALSE, $String = NULL) {
        if (!$DBMSresource or is_array($String) or is_object($String)) { // Check input for mysqli_real_escape_string()
            if (!$DBMSresource)
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::dbms_string_escape_1s !$DBMSresource');
            if (is_array($String))
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::dbms_string_escape_1s is_array($String)');
            if (is_object($String))
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::dbms_string_escape_1s is_object($String)');
            if ($DBMSresource)
                $this->close_connection_1s($DBMSresource);
            exit('<br />An error occurred. Contact your supervisor or an app admin for assistance.<br />');
        }
        // mysqli is only DBMS currently handled.
        return mysqli_real_escape_string($DBMSresource, $String);
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function is an abstraction of a DBMS query, both to manipulate and retrieve data, but (SECURITY WARNING) 
    // to prevent SQL injection hacking it should be used only to create database instances and tables during setup.
    // Prepared statements or dbms_string_escape_1s() are used for other queries, which involve user input.
    // $Query is typically a SQL script
    // $DBMSresource is the DBMS resource identifier created by connect_instance_1s()
// SECURITY WARNING: $QuerySQL has not been escaped to avoid SQL injection!!! Use the SELECT, INSERT, UPDATE, OR DELETE functions below for handling user input. This function is only for setup with script-provided input or for being called by the SELECT, INSET, UPDATE, OR DELETE functions.
    public function query_1s($DBMSresource, $QuerySQL) {
// SECURITY WARNING - see above.
        //////////////////////////////////  mysqli wrapper - start ///////////////////////////////
        $Outcome = mysqli_query($DBMSresource, $QuerySQL);
        if (!$Outcome) {
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::query_1s. Error Log Case 1. DBMS Error: '.@mysqli_error($DBMSresource).' (Blank means no DBMS Error.) The query SQL was: '.@$QuerySQL.'  ');
            $this->close_connection_1s($DBMSresource);
            // Do NOT try to call eject_1c() here or in most _1s (SQL) functions because this would create an error loop.
            // Ejecting etc. shall be done by error handling when _1s functions are called, except for insert, see below.
            // Don't exit here to allow subsequent error logs.
        }
        return $Outcome;
        // for SELECT returns a mysqli_result object, 
        // for successful INSERT or DELETE returns TRUE, 
        // for UPDATE returns the number of rows affected.
        //////////////////////////////////  mysqli wrapper - end ///////////////////////////////
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function can be used to create a database schema or add tables to a schema.
    // $Tables is an associative array, with table names as keys, where each array element is a 
    // numeric array of column names. The first two characters of each column name must be the 
    // FACT+FANCY data type; see DataTypes() above.  The first column must be the primary key 
    // (FACT+FANCY data type k0).  Other columns that start with the letter k will be indexed.  So, 
    // all columns used as foregin keys, with FACT+FANCY data type k0, will be indexed, though the 
    // DBMS is not is "told" that these will serve as foreign keys. Rule: put all foreign keys 
    // immediately after the first column.  Typically, the username column in the user table is also 
    // indexed, by naming the column something like k2_username or k2_username_hash.
    // Example:
    // $Tables = array(
    //  t0user => array(k0user, k2_username_hash, c2name, c1age),
    //  t0employer => array(k0employer, c2name, c2street)
    // );
// SECURITY WARNING: $CreateTableSQL has not been escaped to avoid SQL injection!!! Use the Select, Inset, Update, or Delete functions below for handling user input. This function is only for setup with script-provided input.
    public function create_table_sql_1s($DBMSresource, $Tables, $DBMStype) {
// SECURITY WARNING - see above.
        foreach ($Tables as $K1 => $V1) {
            $CreateTableSQL = 'CREATE TABLE '.$K1.' (';
            foreach ($V1 as $K2 => $V2) {
                $CreateTableSQL .= $V2.' '.$this->data_type_1s($V2, $DBMStype);
                if ($K2 == 0)
                    $CreateTableSQL .= ' PRIMARY KEY '; //DBMS SPECIFIC -- works for mssql, mysqli, oci8, mysql 5.1
                $CreateTableSQL .= ', ';
            }
            $CreateTableSQL = substr_replace($CreateTableSQL, ')', -2);
            if ($DBMStype == 'mysqli')
                $CreateTableSQL .= ' ROW_FORMAT=DYNAMIC';
                // TO DO 2014-10-01 This is MySQL specific to get around 8,000 byte row limit via off-page storage.  SQL Server (mssql) appears to handle this by default for variable length and image (fpf datatype c6) datatypes. Other DBMS may require similar effort to MySQL.
            if (!$this->query_1s($DBMSresource, $CreateTableSQL)) exit; // Don't save and exit.
        }
        // Table columns are indexed with a separate CREATE INDEX command because the syntax for 
        // indexing within the create table command varies widely between DBMS.  This is slower, but 
        // simpler to code, and only done once on setup anyhow.
        foreach ($Tables as $K1 => $V1) {
            foreach ($V1 as $K2 => $V2) {
                if ((substr($V2, 0, 1) == 'k') and ($K2 != 0)) {
                    $CreateIndexSQL = 'CREATE INDEX i'.substr($V2, 1).' ON '.$K1.' ('.$V2.')';
                    if (!$this->query_1s($DBMSresource, $CreateIndexSQL)) exit; // Don't save and exit.
                }
            }
        }
    }


   /*
    ****************** INSERT, SELECT, UPDATE, & DELETE FUNCTIONS - mysqli WRAPPER - START ******************
    * 
    * myqsli works with:
    * MySQL 4.1 (production release October 2004) or above.
    * PHP 5.0 (July 13, 2004) or above
    * This wrapper does NOT use PHP's MySQL Native Driver. 
    * MySQL Native Driver is part of the official PHP sources as of PHP 5.3.0 (June 30, 2009).
    * 
    * SQL syntax requires: single quote values that are strings, no quotes for numbers.
    * 
    * Named Placeholders and prepared statements
    * To convert to named placeholders... see PHP Data Objects (PDO)
    * $DataTypes and $Values are present in this wrapper only as a guide if converting this to a named placeholders wrapper.
    * $DataTypes is a string for mysqli prepared statements (or array in some cases) containing the data types of the values.
    * $Values is an array of the values that will replace the placeholders.
    */

    // This function records a one-row SQL insert, update, or delete in the t0history table
    // $ChangedRow is typically the output of CoreZfpf::changes_from_post_1c. It may not have all fields in the database table.
    // For use with updates, $ChangedRow may not contain the primary key, so it must be passed in.
    // $htmlFormArray needs to be passed in for changes outside i1, i2, i3 train, such as task assignments, approvals, and SQL deletes.
    // See 0read_me_psm_cap_app_standards.txt for specification of $htmlFormArray
    // Other parameters are as defined in the SQL functions in CoreZfpf.
    public function record_in_history_1c($DBMSresource, $TableName, $ChangedRow, $PrimaryKey = FALSE, $htmlFormArray = FALSE) {
        if (!$PrimaryKey) {
            $PrimaryKeyName = substr_replace($TableName, 'k', 0, 1);
            if (!isset($ChangedRow[$PrimaryKeyName])) { // True be design for insert_sql_1s, update_sql_1s, and delete_sql_1s below.
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::record_in_history_1c Error Log Case 1');
                exit('<br />An error occurred. Contact your supervisor or an app admin for assistance.<br />');
            }
            $PrimaryKey = $ChangedRow[$PrimaryKeyName];
        }
        // Get junction-table keys and $TopUserInChangedRow
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/schema.php';
        foreach ($Schema[$TableName] as $K => $V)
            if ($K > 0 and substr($V, 0, 2) == 'k0') {
                if (!isset($TopUserFieldNeeded) and ($V == 'k0user_of_leader' or $V == 'k0user_of_psr' or $V == 'k0user_of_instructor'))
                    $TopUserFieldNeeded = $V;
                else
                    $OtherKeyFieldsNeeded[] = $V;
            }
        $TopUserInChangedRow = 0; // Some database tables don't have k0user fields.
        if (isset($TopUserFieldNeeded)) {
            if (isset($ChangedRow[$TopUserFieldNeeded]))
                $TopUserInChangedRow = $ChangedRow[$TopUserFieldNeeded];
            else
                $SelectNeededFields[] = $TopUserFieldNeeded;
        }
        if (isset($OtherKeyFieldsNeeded)) foreach ($OtherKeyFieldsNeeded as $K => $V) {
            if (isset($ChangedRow[$V]))
                $OtherKeys[$K] = $ChangedRow[$V];
            else
                $SelectNeededFields[] = $V;
        }
        if (isset($SelectNeededFields)) {
            $PrimaryKeyName = substr_replace($TableName, 'k', 0, 1);
            $Conditions[0] = array($PrimaryKeyName, '=', $PrimaryKey);
            list($SR, $RR) = $this->select_sql_1s($DBMSresource, $TableName, $Conditions, $SelectNeededFields);
            if ($RR != 1) {
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::record_in_history_1c Error Log Case 2');
                exit('<br />An error occurred. Contact your supervisor or an app admin for assistance.<br />');
            }
            if (isset($TopUserFieldNeeded) and !$TopUserInChangedRow)
                $TopUserInChangedRow = $SR[0][$TopUserFieldNeeded];
            if (isset($OtherKeyFieldsNeeded)) foreach ($OtherKeyFieldsNeeded as $K => $V) {
                if (!isset($OtherKeys[$K]))
                    $OtherKeys[$K] = $SR[0][$V];
            }
        }
        $CountOtherKeys = 0;
        while ($CountOtherKeys < 19) { // Allows 19 other keys (21 keys total, including primary key and top-user key)
            if (!isset($OtherKeys[$CountOtherKeys]))
                $OtherKeys[$CountOtherKeys] = 0; // Fills in unneeded other key fields with 0. FALSE gives SQL syntax error. Do last in case $ChangedRow value is 0 and prior value is not 0.
            $CountOtherKeys++;
        }
        // Get $CRhtmlFormArray
        if (!$htmlFormArray and isset($_SESSION['Scratch']['htmlFormArray']))
                $htmlFormArray = $this->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']); // $_SESSION['Scratch']['htmlFormArray'] typically set in i1 code and exists in i3 code that calls SQL inserts & updates.
        foreach ($ChangedRow as $K => $V) {
            if ($K == 'c5who_is_editing' or $K == 'c6active_sessions') // Stuff that's not put in history. c5who_is_editing == '[Nobody is editing.]' here, always. Needed information on who made the change is stored in t0history:k0user.
                unset($ChangedRow[$K]);
            elseif ($htmlFormArray and isset($htmlFormArray[$K]))
                $CRhtmlFormArray[$K] = $htmlFormArray[$K]; // $CRhtmlFormArray is the htmlFormArray with only the $ChangedRow fields.
            elseif (substr($K, 0, 5) == 'c5ts_')
                $CRhtmlFormArray[$K] = array('Timestamp field '.$K, '');
            elseif (substr($K, 0, 7) == 'c6nymd_')
                $CRhtmlFormArray[$K] = array('Approved by name and date field '.$K, '');
            else
                $CRhtmlFormArray[$K] = array($K, ''); // This will just label the field by the database table column name, which should the key of $ChangedRow, to ensure that each needed $CRhtmlFormArray field exits for interpreting the t0history information later. Such as, perhaps, for username and password updates.
        }
        // Fixup $ChangedRow 
        foreach ($ChangedRow as $K => $V)
            if (substr($K, 1, 2) >= '5') // c5 and c6 fpf data types are encrypted and must be decrypted for CoreZfpf::encode_encrypt_1c to work.
                $ChangedRow[$K] = $this->decrypt_1c($V);
        // Make k0 fields human readable, cannot rely on $_SESSION['Post'] or similar for this.
        // k0 fields are not decrypted by ConfirmZfpf::select_to_display_1e because they are not encrypted.
        // Except, in t0history, the entire $ChangedRow is encoded and encrypted at rest (when saved on drive), so
        // so unencrypted plain text can be put in the k0 fields, along with the key, so they are human and machine readable.
        foreach ($ChangedRow as $K => $V) {
            if ($V and $V != $PrimaryKey) { // If a key field, means it holds a key that references a row in another database table.
                if ($K == 'k2username_hash')
                    $ChangedRow[$K] = 'Usernames are hashed before recording.';
                // s datatype, passwords..., handled by ConfirmZfpf::select_to_display_1e
                if ($K == 'k0user' or substr($K, 0, 10) == 'k0user_of_') {
                    if ($V > 10) // Low numbers in some key fields have special meanings, see schema for audit, incident, etc.
                        $ChangedRow[$K] = $this->user_job_info_1c($V)['NameTitleEmployerWorkEmail'].' '.$V; // Append the key (the k0 number).
                    else
                        $ChangedRow[$K] = $V;
                }
                if ($K == 'k0user_facility' and $TableName == 't0contractor_priv') {
                    if (isset($_SESSION['Scratch']['t0user_facility']))
                        $Contractor_k0user = $_SESSION['Scratch']['t0user_facility']['k0user'];
                    else {
                        $Conditions[0] = array('k0user_facility', '=', $V);
                        unset($SR);
                        unset($RR);
                        list($SR, $RR) = $this->select_sql_1s($DBMSresource, 't0user_facility', $Conditions);
                        if ($RR == 1)
                            $Contractor_k0user = $SR[0]['k0user'];
                    }
                    $ChangedRow[$K] = $this->user_job_info_1c($Contractor_k0user)['NameTitleEmployerWorkEmail'].' '.$Contractor_k0user.' with k0user_facility '.$V;
                }
                if ($K == 'k0owner' or $K == 'k0contractor' or $K == 'k0union' or $K == 'k0facility' or $K == 'k0lepc' or $K == 'k0process' or $K == 'k0practice' or $K == 'k0document' or $K == 'k0fragment' or $K == 'k0division' or $K == 'k0rule' or $K == 'k0guidance' or $K == 'k0subprocess' or $K == 'k0scenario' or $K == 'k0cause' or $K == 'k0consequence' or $K == 'k0safeguard' or $K == 'k0action' or $K == 'k0obstopic' or $K == 'k0audit') {
                    // TO DO If new tables added to schema, and they have key fields (besides their primary key) that are not captured by above conditions, add additional conditions above if you want the history tables to show the name, description, and key recorded in history, in these k0 fields, rather than just the key.
                    // t0obsmethod and t0obsresult don't have c5name or c6desrciption fields, so just record their numeric key.
                    $ReferencedTableName = substr_replace($K, 't', 0, 1);
                    $Conditions[0] = array($K, '=', $V);
                    unset($SR);
                    unset($RR);
                    list($SR, $RR) = $this->select_sql_1s($DBMSresource, $ReferencedTableName, $Conditions);
                    if ($RR == 1) { // Don't eject here if $RR != 1 to avoid nuisance errors.
                        $NameDescription = $this->entity_name_description_1c($SR[0], 100, FALSE); // Don't bold name in history tables.
                        if ($NameDescription)
                            $ChangedRow[$K] = $NameDescription.' '.$V; // Append the key (the k0 number).
                    }
                }
            }
        }
        $HostSpec = '';
        if (DBMS_HOSTPATH_ZFPF)
            $HostSpec .= DBMS_HOSTPATH_ZFPF;
        if (DBMS_PORT_ZFPF) {
            if ($HostSpec)
                $HostSpec .= '|'; // '|' is used as delimiter, since not typically in path, port, or socket strings.
            $HostSpec .= DBMS_PORT_ZFPF;
        }
        if (DBMS_SOCKET_ZFPF) {
            if ($HostSpec)
                $HostSpec .= '|';
            $HostSpec .= DBMS_SOCKET_ZFPF;
        }
        if (!$HostSpec)
            $HostSpec = '[Nothing has been recorded in this field.]';
        $HostSpec = $this->max_length_1c($HostSpec);
        $History = array(
            'k0history' => time().mt_rand(1000000, 9999999),
            'k0user' => $_SESSION['t0user']['k0user'], // The user who made the change.
            'k0_1st_in_row_affected' => $PrimaryKey,
            'k0_2nd_in_row_affected' => $OtherKeys[0],
            'k0_3rd_in_row_affected' => $OtherKeys[1],
            'k0_4th_in_row_affected' => $OtherKeys[2],
            'k0_5th_in_row_affected' => $OtherKeys[3],
            'k0_6th_in_row_affected' => $OtherKeys[4],
            'k0_7th_in_row_affected' => $OtherKeys[5],
            'k0_8th_in_row_affected' => $OtherKeys[6],
            'k0_9th_in_row_affected' => $OtherKeys[7],
            'k0_10th_in_row_affected' => $OtherKeys[8],
            'k0_11th_in_row_affected' => $OtherKeys[9],
            'k0_12th_in_row_affected' => $OtherKeys[10],
            'k0_13th_in_row_affected' => $OtherKeys[11],
            'k0_14th_in_row_affected' => $OtherKeys[12],
            'k0_15th_in_row_affected' => $OtherKeys[13],
            'k0_16th_in_row_affected' => $OtherKeys[14],
            'k0_17th_in_row_affected' => $OtherKeys[15],
            'k0_18th_in_row_affected' => $OtherKeys[16],
            'k0_19th_in_row_affected' => $OtherKeys[17],
            'k0_20th_in_row_affected' => $OtherKeys[18],
            'k0user_of_leader' => $TopUserInChangedRow,
            'c2table_name' => $TableName,
            'c2dbms_hostspec' => $HostSpec,
            'c1ts_changed' => time(),
            'c5ntewe_at_time' => $this->encrypt_1c($this->current_user_info_1c()['NameTitleEmployerWorkEmail']),
            'c6html_form_array' => $this->encode_encrypt_1c($CRhtmlFormArray),
            'c6changed_row' => $this->encode_encrypt_1c($ChangedRow)
        );
        $this->no_history_insert_sql_1s($DBMSresource, 't0history', $History); // Error handling done by no_history_insert_sql_1s.
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function prepares and executes a one-row SQL INSERT statement.
    // $TableName is the table name in the selected database instance
    // $NewRow is an associative array with the column name as the key(s) and the data as the value(s).
    // $RecordInHistory == TRUE will record the insert in t0history
    // $Results == 1 if the one row was successfully inserted, the user will be ejected otherwise.
    // NOTE: This function always includes the column names in the SQL script, even when, 
    // because all columns are being inserted, this is not necessary.
    // $ColumnsString is a comma delineated list of column names, in parenthesis, unquoted.
    // $ValuesString is a comma delineated list of values, in parenthesis, with strings single quoted and numbers unquoted.
    public function insert_sql_1s($DBMSresource, $TableName, $NewRow, $RecordInHistory = TRUE, $htmlFormArray = FALSE) { // This wraps no_history_insert_sql_1s to allow calling it for history inserts
        $Results = $this->no_history_insert_sql_1s($DBMSresource, $TableName, $NewRow);
        if ($RecordInHistory)
            $this->record_in_history_1c($DBMSresource, $TableName, $NewRow, FALSE, $htmlFormArray);
        return $Results;
    }
    public function no_history_insert_sql_1s($DBMSresource, $TableName, $NewRow) {
        $ValuesString = '(';
        $ColumnsString = '(';
        foreach ($NewRow as $K => $V) {
            $ColumnsString .= "$K, ";
            $SafeValue = $this->dbms_string_escape_1s($DBMSresource, $V);
            $fpfDataType = substr($K, 1, 1);
            if ($fpfDataType < 2)
                $ValuesString .= "$SafeValue, ";
            else
                $ValuesString .= '\''.$SafeValue.'\', ' ;
        }
        $ValuesString = substr_replace($ValuesString, ')', -2);
        $ColumnsString = substr_replace($ColumnsString, ')', -2);
        // Finish SQL script.
        $SQL = "INSERT INTO $TableName $ColumnsString VALUES $ValuesString";
        $Results = $this->query_1s($DBMSresource, $SQL);
        if (!$Results)
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' no_history_insert_sql_1s() Eject Case 1');
            // The short error log above is OK because query_1s() provides the DBMS error, if any.
            // Can eject user here because eject_1c() calls UPDATE (but not INSERT).
        return $Results;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function creates the WHERE string to use with SELECT, UPDATE, and DELETE SQL statements.
    // 
    // $Conditions is a 2-layer numeric array with the first layer containing 1 or more conditions, 
    // where each condition (the second layer) is a five-element numeric array containing:
    // [0] the column name, such as k0user
    // [1] a comparison operator, such as =, >, <
    // [2] the condition value
    // [3] (optional) SQL for after the condition value, such as AND, OR, ) [close parenthesis]. Set to '' to have [4] without [3]
    // [4] (optional) SQL for before the column name, such as OR ( 
    // Example 1:
    // $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['lookup_user'][$Selected]);
    // Example 2:
    // $Conditions = array(
    //      array('c2username', '=', 'fredsmith1', 'OR'),
    //      array('c2name', '=', 'Fred', 'AND', '('),
    //      array('c1age', '<', 30, ')')
    // );
    // To select, update, or delete all rows, set $Condition = 'No Condition -- All Rows Included'; if 
    // $Condition is not suppied, the user is logged off with error logging, to prevent accidental 
    // deletion or updating of all rows.
    // DIRECT QUERY WRAPPERS: 
    // $ConditionsAndPlaceholders has values, not placeholders -- the variable's name is a leftover!!!
    // $ConditionsAndPlaceholders is a comma-delineated string containing column names, 
    // comparison operators, any logical operators, and values; for Example 2 above it is: 
    //     WHERE c2username = 'fredsmith1' OR (c2name = 'Fred' AND c2age < 30) 
    //
        ////////////////////////////// prepared statement (unnamed placeholders) DRAFT wrapper ////////////////
        // $ConditionsAndPlaceholders is a comma-delineated string containing column names, 
        // comparison operators, any logical operators, and placeholders; for Example 2 above it is: 
        //     WHERE c2username = ? OR (c2name = ? AND c1age < ?)
        //
        //  ////////////////////////// Named placeholders (Oracle-style) DRAFT wrapper ///////////////////////// 
        // not written -- change " ?" to " :{$V[0]}" and $Values[] = $V[2]; to $Values[{$V[0]}] = $V[2];.
        //
        //
    // The $Datatypes and $Values arguments are only used in the prepared statements wrapper, for UPDATES.
    public function where_sql_1s($DBMSresource, $Conditions = FALSE, $DataTypes = NULL, $Values = NULL) {
        if (!$Conditions) {
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::where_sql_1s no conditions given');
            exit('<br />An error occurred. Contact your supervisor or an app admin for assistance.<br />');
        }
        if ($Conditions == 'No Condition -- All Rows Included')
            $ConditionsAndPlaceholders = '';
        else {
            $ConditionsAndPlaceholders = 'WHERE ';
            foreach ($Conditions as $V) {
                if (substr($ConditionsAndPlaceholders, -1) != ' ' and substr($ConditionsAndPlaceholders, -1) != '(')
                    $ConditionsAndPlaceholders .= ' ';
                if (isset($V[4]))
                    $ConditionsAndPlaceholders .= $V[4];
                if (substr($ConditionsAndPlaceholders, -1) != ' ' and substr($ConditionsAndPlaceholders, -1) != '(')
                    $ConditionsAndPlaceholders .= ' ';
                $SafeValue = $this->dbms_string_escape_1s($DBMSresource, $V[2]);
                $fpfDataType = substr($V[0], 1, 1);
                if ($fpfDataType < 2)
                    $ConditionsAndPlaceholders .= $V[0].' '.$V[1].' '.$SafeValue;
                else
                    $ConditionsAndPlaceholders .= $V[0].' '.$V[1].' \''.$SafeValue.'\'';
                if (isset($V[3])) {
                    if (substr($V[3], 0, 1) == ')')
                        $ConditionsAndPlaceholders .= $V[3];
                    elseif ($V[3]) // If FALSE, no space is inserted. Allows having a $V[4] without a $V[3]
                        $ConditionsAndPlaceholders .= ' '.$V[3];
                }
            }
        }
        return array($ConditionsAndPlaceholders, $DataTypes, $Values);
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function preforms a SQL SELECT statement.
    // $TableName is the table name in the selected database instance.
    // To select all rows from a table, set $Conditions = 'No Condition -- All Rows Included'
    // $Conditions and $ConditionsAndPlaceholders is same as in $this->where_sql_1s()
    // $Columns (optional, default = '*') is a numeric array of the columns to select from. 
    // The default is to select all columns in the rows meeting the $Conditions. 
    // SQL syntax: $Columns is used to create $ColumnsString, a comma delineated list, no parenthesis.
    // The assumption in this function design is that, most of the time, all columns of rows meeting 
    // $Conditions will be selected, so the column specification is put last. To select all rows of 
    // certain columns, the $Conditions parameter must be the string 'No Condition -- All Rows Included', for example: 
    // $this->select_sql_1s($DBMSresource, $TableName, 'No Condition -- All Rows Included', $Columns)
    public function select_sql_1s($DBMSresource, $TableName, $Conditions = FALSE, $Columns = '*') {
        // $ConditionsAndPlaceholders has values, not placeholders -- the variable's name is a leftover!!!
        list($ConditionsAndPlaceholders, $DataTypes, $Values) = $this->where_sql_1s($DBMSresource, $Conditions);
        // Create columns string
        if ($Columns == '*')
            $ColumnsString = $Columns;
        else {
            $ColumnsString = '';
            foreach ($Columns as $V)
                $ColumnsString .= "$V, ";
            $ColumnsString = substr_replace($ColumnsString, '', -2);
        }
        // Create SQL prepared statement script.
        // $ConditionsAndPlaceholders has values, not placeholders -- the variable's name is a leftover!!!
        $SQL = "SELECT $ColumnsString FROM $TableName $ConditionsAndPlaceholders";
        $Results = $this->query_1s($DBMSresource, $SQL);
        $RowsReturned = 0;
        if ($Results) {
            while ($Row = mysqli_fetch_assoc($Results)) {
                $SelectResults[$RowsReturned] = $Row;
                ++$RowsReturned;
            }
            mysqli_free_result($Results);
        }
        // This if statement avoids an PHP undefined variable notice for $SelectResults when no are rows returned.
        if (!$RowsReturned)
            $SelectResults = 0;
        // $SelectResults is a numeric array containing one associative array for each row returned by the SELECT query.
        // $RowsReturned is the number of rows returned by the SELECT query.
        return array($SelectResults, $RowsReturned);
        // ERROR HANDLING on $RowsReturned shall be done by the calling file.
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function prepares and executes an SQL UPDATE statement.
    // $TableName is the table name in the selected database instance.
    // $Conditions and $ConditionsAndPlaceholders is same as in CoreZfpf::where_sql_1s
    // $Changes is similar to $NewRow in CoreZfpf::insert_sql_1s, but $Changes may include only the columns to be updated or all columns.
    // $ColumnsAndValues is a comma-delineated string with syntax: 
    //     ColumnName1 = value[, ColumnName2 = value]...
    //     where valves are single quoted for strings and not quoted for numbers.
    // $Affected: An integer greater than zero indicates the number of rows affected. 
    //     Zero indicates that no records where updated for an UPDATE statement, in other words, no rows matched 
    //     the WHERE clause in the query or that no query has yet been executed.
    // If not recorded in history, ERROR HANDLING on $Affected shall be done by the calling file 
    // because may not know here how many rows are supposed to be affected.
    public function update_sql_1s($DBMSresource, $TableName, $Changes, $Conditions = FALSE, $RecordInHistory = TRUE, $htmlFormArray = FALSE) {
        if ($RecordInHistory) {
            list($SR, $RR) = $this->select_sql_1s($DBMSresource, $TableName, $Conditions);
            if ($RR != 1) {
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::update_sql_1s SQL-update conditions returned '.@$RR.' rows in a SQL-select test before a SQL update only allowed to affect one database-table row');
                exit('<br />An error occurred. Contact your supervisor or an app admin for assistance.<br />');
            }
            else {
                $PrimaryKeyName = substr_replace($TableName, 'k', 0, 1);
                $PrimaryKey = $SR[0][$PrimaryKeyName];
                $this->record_in_history_1c($DBMSresource, $TableName, $Changes, $PrimaryKey, $htmlFormArray);
            }
        }
        // Create $ColumnsAndValues string
        $ColumnsAndValues = '';
        foreach ($Changes as $K => $V) {
            $ColumnsAndValues .= "$K = ";
            $SafeValue = $this->dbms_string_escape_1s($DBMSresource, $V);
            $fpfDataType = substr($K, 1, 1);
            if ($fpfDataType < 2)
                $ColumnsAndValues .= "$SafeValue, ";
            else
                $ColumnsAndValues .= '\''.$SafeValue.'\', ' ;
        }
        // Get rid of final ', ' on $ColumnsAndValues string
        $ColumnsAndValues = substr_replace($ColumnsAndValues, '', -2);
        // Create $ConditionsAndPlaceholders string
        list($ConditionsAndPlaceholders, $DataTypes, $Values) = $this->where_sql_1s($DBMSresource, $Conditions);
        // Create SQL script.
        // $ConditionsAndPlaceholders has values, not placeholders -- the variable's name is a leftover!!!
        $SQL = "UPDATE $TableName SET $ColumnsAndValues $ConditionsAndPlaceholders";
        if ($this->query_1s($DBMSresource, $SQL))
            $Affected = mysqli_affected_rows($DBMSresource);
        else
            $Affected = 0;
        return $Affected;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function performs an SQL DELETE statement.
    // Variables have same meaning as in CoreZfpf::update_sql_1s and CoreZfpf::insert_sql_1s
    // If not recorded in history, ERROR HANDLING on $Affected shall be done by the calling file 
    // because may not know here how many rows are supposed to be affected.
    public function delete_sql_1s($DBMSresource, $TableName, $Conditions, $RecordInHistory = TRUE) {
        if ($RecordInHistory) {
            list($SR, $RR) = $this->select_sql_1s($DBMSresource, $TableName, $Conditions);
            if ($RR != 1) {
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' CoreZfpf::delete_sql_1s SQL-delete conditions returned '.@$RR.' rows in a SQL-select test before a SQL delete only allowed to affect one database-table row');
                exit('<br />An error occurred. Contact your supervisor or an app admin for assistance.<br />');
            }
            $PrimaryKeyName = substr_replace($TableName, 'k', 0, 1);
            $PrimaryKey = $SR[0][$PrimaryKeyName];
            foreach ($SR[0] as $K => $V)
                $htmlFormArray[$K] = array($K.' [Entire row was deleted]', '');
            $this->record_in_history_1c($DBMSresource, $TableName, $SR[0], $PrimaryKey, $htmlFormArray);
        }
        list($ConditionsAndPlaceholders, $DataTypes, $Values) = $this->where_sql_1s($DBMSresource, $Conditions);
        $SQL = "DELETE FROM $TableName $ConditionsAndPlaceholders";
        if ($this->query_1s($DBMSresource, $SQL))
            $Affected = mysqli_affected_rows($DBMSresource);
        else
            $Affected = 0;
        return $Affected;
    }


   /*
    ********************* INSERT, SELECT, UPDATE, & DELETE FUNCTIONS - mysqli WRAPPER - END *********************
    */


   /*
    ************************* ONE SHOT FUNCTIONS ************************* 
    * The next four "one shot" functions combine connecting to the DBMS, selecting an instance, 
    * performing a query or data manipulation, and -- if applicable -- getting SELECT results into an 
    * array. As input they take the $NewRow, $Changes and $Conditions variables, described in comments 
    * above for the INSERT_SQL() and WHERE_SQL() functions.
    */ 
    public function one_shot_insert_1s($TableName, $NewRow, $RecordInHistory = TRUE, $htmlFormArray = FALSE, $Privileges = FALSE) {
        $DBMSresource = $this->credentials_connect_instance_1s($Privileges);
        $Done = $this->insert_sql_1s($DBMSresource, $TableName, $NewRow, $RecordInHistory, $htmlFormArray);
        $this->close_connection_1s($DBMSresource);
        return $Done;
    }
    public function one_shot_select_1s($TableName, $Conditions, $Columns = '*', $Privileges = FALSE) {
        $DBMSresource = $this->credentials_connect_instance_1s($Privileges);
        list($SelectResults, $RowsReturned) = $this->select_sql_1s($DBMSresource, $TableName, $Conditions, $Columns);
        $this->close_connection_1s($DBMSresource);
        return array($SelectResults, $RowsReturned);
    }
    public function one_shot_update_1s($TableName, $Changes, $Conditions, $RecordInHistory = TRUE, $htmlFormArray = FALSE, $Privileges = FALSE) {
        $DBMSresource = $this->credentials_connect_instance_1s($Privileges);
        $Affected = $this->update_sql_1s($DBMSresource, $TableName, $Changes, $Conditions, $RecordInHistory, $htmlFormArray);
        $this->close_connection_1s($DBMSresource);
        return $Affected;
    }
    public function one_shot_delete_1s($TableName, $Conditions, $RecordInHistory = TRUE, $Privileges = FALSE) {
        $DBMSresource = $this->credentials_connect_instance_1s($Privileges);
        $Affected = $this->delete_sql_1s($DBMSresource, $TableName, $Conditions, $RecordInHistory);
        $this->close_connection_1s($DBMSresource);
        return $Affected;
    }

}

