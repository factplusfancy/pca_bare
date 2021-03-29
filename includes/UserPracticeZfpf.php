<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This class allows populating groups of t0user_practice rows

class UserPracticeZfpf {

    ////////////////////////////////////////////////////////////////////////////
    // This function populates arrays of t0practice and t0user_practice rows for one user and an entity.
    // $Zfpf a CoreZfpf object
    // $k0user the t0user primary key for the user.
    // $TableRoot allowed values: owner, contractor, facility, process (the entity)
    // $k0TR primary key for the entity's row in 't0'.$TableRoot
    // returns $EUP, matching numeric arrays of: existing t0practice rows and
    //                                           existing or draft t0user_practice rows,
    //                                           both sorted by by t0practice:c5number.
    public function eup_arrays($Zfpf, $k0user, $TableRoot, $k0TR) {
        $EUP = array('P' => array(), 'UP' => array());
        $k0owner = 0;
        if ($TableRoot == 'owner')
            $k0owner = $k0TR;
        $k0contractor = 0;
        if ($TableRoot == 'contractor')
            $k0contractor = $k0TR;
        $k0facility = 0;
        if ($TableRoot == 'facility')
            $k0facility = $k0TR;
        $k0process = 0;
        if ($TableRoot == 'process')
            $k0process = $k0TR;
        $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
        $EncryptedNone = $Zfpf->encrypt_1c(NO_PRIVILEGES_ZFPF);
        $EncryptedNewRowBeingCreated = $Zfpf->encrypt_1c('[A new database row is being created.]');
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0'.$TableRoot, '=', $k0TR);
        list($SREP, $RREP) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$TableRoot.'_practice', $Conditions); // Must start with select from entity-practice table because may be trying to populate t0user_practice (might not be a row for a practice in t0user_practice yet).
        if ($RREP) {
            foreach ($SREP as $K => $V) {
                $Conditions[0] = array('k0practice', '=', $V['k0practice']);
                list($SRP, $RRP) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions);
                if ($RRP != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' t0'.$TableRoot.'_practice for '.$TableRoot.' primary key '.$k0TR.' holds k0practice: '.$V['k0practice'].', which is not in t0practice.');
                $EUP['P'][$K] = $SRP[0];
                $PracticeNumber[$K] = $Zfpf->decrypt_1c($SRP[0]['c5number']);
                $Conditions[1] = array('k0'.$TableRoot, '=', $k0TR, 'AND', 'AND'); // Puts AND before and after the middle condition.
                $Conditions[2] = array('k0user', '=', $k0user);
                list($SRUP, $RRUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $Conditions);
                unset($Conditions);
                if ($RRUP > 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' More than one t0user_practice row for k0practice: '.$V['k0practice'].', k0'.$TableRoot.': '.$k0TR.', and k0user: '.$k0user);
                elseif ($RRUP == 1)
                    $EUP['UP'][$K] = $SRUP[0];
                else // initialize a t0user_practice row, like i0n case.
                    $EUP['UP'][$K] = array (
                        'k0user_practice' => time().mt_rand(1000000, 9999999),
                        'k0user' => $k0user,
                        'k0practice' => $V['k0practice'],
                        'k0owner' => $k0owner,
                        'k0contractor' => $k0contractor,
                        'k0facility' => $k0facility,
                        'k0process' => $k0process,
                        'c5p_practice' => $EncryptedNone,
                        'c5who_is_editing' => $EncryptedNewRowBeingCreated
                    );
            }
            array_multisort($PracticeNumber, $EUP['P'], $EUP['UP']); // sort by t0practice:c5number
        }
        $Zfpf->close_connection_1s($DBMSresource);
        return $EUP;
    }

    ////////////////////////////////////////////////////////////////////////////
    // i1 code
    // $Zfpf a CoreZfpf object
    // $SelectedUser is the array output by CoreZfpf::user_job_info_1c for the user whose privileges are being changed.
    // $TableRoot allowed values: owner, contractor, facility, process (the entity)
    // $EUP is the output of above UserPractice::eup_arrays
    // returns HTML for input 1 (i1) form.
    public function u_p_i1($Zfpf, $SelectedUser, $TableRoot, $Scope, $EUP) {
        if (isset($_SESSION['Post'])) {
            if (!isset($_POST['u_p_undo_confirm_post_1e']))
                $_POST = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
            unset($_SESSION['Post']);
        }
        if (isset($_SESSION['Scratch']['NewUPRow']));
            unset($_SESSION['Scratch']['NewUPRow']);
        $SelectedUserGlobalDBMSPriv = $Zfpf->decrypt_1c($SelectedUser['t0user']['c5p_global_dbms']);
        $HTML = '<h2>
        User-Practice Privileges</h2><p>
        Privileges of user:<br />
        * '.$SelectedUser['NameTitleEmployerWorkEmail'].'<br />
        '.$Scope.'</p><p>
        This user\'s global database management system (DBMS) privileges are:<br />
        * '.$SelectedUserGlobalDBMSPriv.'<br />
        Only practice-privilege options equal to or lower than these are shown below.</p>
        <form action="user_'.$TableRoot.'_io03.php" method="post"><p>
            <input type="submit" name="u_p_history" value="History of user-practice privileges" /></p>';
        if ($EUP['P']) {
            $HTML .= '<p> 
            <input type="submit" name="u_p_none_view" value="All: no privileges" /> 
            <input type="submit" name="u_p_all_view" value="All: View..." /> ';
            if ($SelectedUserGlobalDBMSPriv == MAX_PRIVILEGES_ZFPF)
                $HTML .= '
                <input type="submit" name="u_p_all_edit" value="All: Edit..." /></p>';
            $HTML .= '</p>';
            foreach ($EUP['P'] as $K => $V) {
                $Key = $EUP['UP'][$K]['k0user_practice']; // Use this instead of $K to avoid need for edit locks, see function u_p_i2 below.
                $CreatingUPRow = FALSE;
                if ($Zfpf->decrypt_1c($EUP['UP'][$K]['c5who_is_editing']) == '[A new database row is being created.]')
                    $CreatingUPRow = TRUE;
                $EditPrivileges = $Zfpf->decrypt_1c($EUP['UP'][$K]['c5p_practice']);
                $PracticeName = $Zfpf->decrypt_1c($V['c5name']);
                $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'][$K] = $PracticeName;
                $HTML .= '<p><a id="'.$K.'"></a>
                <b>'.$PracticeName.'</b>. ';
                $Description = $Zfpf->decrypt_1c($V['c6description']);
                if ($Description != '[Nothing has been recorded in this field.]')
                    $HTML .= $Description;
                $HTML .='<br />
                <input type="radio" name="u_p_r_'.$Key.'" '; 
                if (isset($_POST['u_p_none_view']) or (!isset($_POST['u_p_all_view']) and !isset($_POST['u_p_all_edit']) and ((!isset($_POST['u_p_r_'.$Key]) and $CreatingUPRow) or (isset($_POST['u_p_r_'.$Key]) and substr($_POST['u_p_r_'.$Key], 0, 5) == 'none'))))
                    $HTML .= ' checked="checked"';
                $HTML .= ' value="none"/>No privileges. 
                <input type="radio" name="u_p_r_'.$Key.'" ';
                if (isset($_POST['u_p_all_view']) or (!isset($_POST['u_p_none_view']) and !isset($_POST['u_p_all_edit']) and ((!isset($_POST['u_p_r_'.$Key]) and !$CreatingUPRow and $EditPrivileges != MAX_PRIVILEGES_ZFPF) or (isset($_POST['u_p_r_'.$Key]) and substr($_POST['u_p_r_'.$Key], 0, 5) == 'view'))))
                    $HTML .= ' checked="checked"';
                $HTML .= ' value="view"/>View';
                if ($SelectedUserGlobalDBMSPriv != LOW_PRIVILEGES_ZFPF)
                    $HTML .= ' and start some';
                $HTML .= ' records. ';
                if ($SelectedUserGlobalDBMSPriv == MAX_PRIVILEGES_ZFPF) {
                    $HTML .= '
                    <input type="radio" name="u_p_r_'.$Key.'" ';
                    if (isset($_POST['u_p_all_edit']) or (!isset($_POST['u_p_none_view']) and !isset($_POST['u_p_all_view']) and ((!isset($_POST['u_p_r_'.$Key]) and !$CreatingUPRow and $EditPrivileges == MAX_PRIVILEGES_ZFPF) or (isset($_POST['u_p_r_'.$Key]) and substr($_POST['u_p_r_'.$Key], 0, 5) == 'edit'))))
                        $HTML .= ' checked="checked"';
                    $HTML .= ' value="edit"/>Edit and view records.';
                }
                $HTML .= '</p>';
            }
            $HTML .= '</p><p>
            <input type="submit" name="u_p_i2" value="Review user-practice privileges you selected" /></p>';
        }
        else 
            $HTML .= '<p><b>No <a class="toc" href="glossary.php#practices" target="_blank">compliance practices</a> found for the selected entity.</b> It may not have any entity-wide practices.</p>';
        $HTML .= '<p>
            <input type="submit" name="user_'.$TableRoot.'_o1" value="Take no action -- go back" /></p>
        </form>';
        return $HTML;
    }

    // i2 code -- same parameters as u_p_i1 function above.
    public function u_p_i2($Zfpf, $SelectedUser, $TableRoot, $Scope, $EUP) {
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($_POST);
        $SelectedUserGlobalDBMSPriv = $Zfpf->decrypt_1c($SelectedUser['t0user']['c5p_global_dbms']);
        $HTML = '<h2>
        User-Practice Privileges</h2><p>
        Privileges of user:<br />
        * '.$SelectedUser['NameTitleEmployerWorkEmail'].'<br />
        '.$Scope.'</p><p>
        This user\'s global database management system (DBMS) privileges are:<br />
        * '.$SelectedUserGlobalDBMSPriv.'<br />
        Only practice privileges equal to or lower than these are permitted.</p>
        <form action="user_'.$TableRoot.'_io03.php" method="post">';
        if ($EUP['P']) foreach ($EUP['P'] as $K => $V) {
            $Key = $EUP['UP'][$K]['k0user_practice'];
            $Count[$Key] = $K;
            $CreatingUPRow = FALSE;
            if ($Zfpf->decrypt_1c($EUP['UP'][$K]['c5who_is_editing']) == '[A new database row is being created.]')
                $CreatingUPRow[$Key] = TRUE;
            $EditPrivileges = $Zfpf->decrypt_1c($EUP['UP'][$K]['c5p_practice']);
            if (isset($_POST['u_p_r_'.$Key])) { // No edit_lock, so may not be set if sources in database of $EUP arrays changed.
                if (substr($_POST['u_p_r_'.$Key], 0, 5) == 'none') {
                    if (!$CreatingUPRow)
                        $Task[$Key] = 'DeleteUPRow';
                }
                elseif (substr($_POST['u_p_r_'.$Key], 0, 5) == 'view') {
                    if ($CreatingUPRow) {
                        $Task[$Key] = 'NoneToView';
                        $_SESSION['Scratch']['NewUPRow'][$Key] = $EUP['UP'][$K];
                    }
                    elseif ($EditPrivileges == MAX_PRIVILEGES_ZFPF)
                        $Task[$Key] = 'EditToView';
                }
                elseif (substr($_POST['u_p_r_'.$Key], 0, 5) == 'edit') {
                    if ($SelectedUserGlobalDBMSPriv != MAX_PRIVILEGES_ZFPF)
                        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
                    if ($CreatingUPRow) {
                        $Task[$Key] = 'NoneToEdit';
                        $_SESSION['Scratch']['NewUPRow'][$Key] = $EUP['UP'][$K];
                    }
                    elseif ($EditPrivileges != MAX_PRIVILEGES_ZFPF)
                        $Task[$Key] = 'ViewToEdit';
                }
                else
                    $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
            }
        }
        if (isset($Task)) {
            $AndSomeInserts = '';
            if ($SelectedUserGlobalDBMSPriv != LOW_PRIVILEGES_ZFPF)
                $AndSomeInserts = ' and some inserts';
            $HTML .= '<p>
            <b>Please confirm that the privileges you modified, listed below, are complete and correct.</b></p>';
            foreach ($Task as $Key => $V) {
                $K = $Count[$Key];
                $HTML .= '<p>
                <b>'.$Zfpf->decrypt_1c($EUP['P'][$K]['c5name']).'</b>. ';
                $Description = $Zfpf->decrypt_1c($EUP['P'][$K]['c6description']);
                if ($Description != '[Nothing has been recorded in this field.]')
                    $HTML .= $Description;
                $HTML .= '<br />';
                if ($V == 'DeleteUPRow')
                    $HTML .= '* No privileges (downgrade)</p>';
                if ($V == 'NoneToView')
                    $HTML .= '* View'.$AndSomeInserts.' privileges (upgrade)</p>';
                if ($V == 'EditToView')
                    $HTML .= '* View'.$AndSomeInserts.' privileges (downgrade)</p>';
                if ($V == 'NoneToEdit')
                    $HTML .= '* Edit privileges (upgrade from none)</p>';
                if ($V == 'ViewToEdit')
                    $HTML .= '* Edit privileges (upgrade from view)</p>';
            }
            $HTML .= '<p>
            <input type="submit" name="u_p_i3" value="Confirm your changes" /></p>
            <input type="submit" name="u_p_modify_confirm_post_1e" value="Modify your changes" /></p><p>
            <input type="submit" name="u_p_undo_confirm_post_1e" value="Undo all your changes and input new information" /></p>';
            $_SESSION['Scratch']['Task'] = $Zfpf->encode_encrypt_1c($Task);
        }
        else
            $HTML .= '<p>
            <b>You did not change anything.</b></p><p>
            <input type="submit" name="u_p_modify_confirm_post_1e" value="Go back and make changes" /></p>';
        $HTML .= '
        </form>';
        return $HTML;
    }

    // i3 code
    public function u_p_i3($Zfpf) {
        if (!isset($_SESSION['Scratch']['EUP']) or !isset($_SESSION['Post']) or !isset($_SESSION['Scratch']['Task']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Task = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['Task']);
        $ShtmlFormArray['c5p_practice'] = array('Practice Edit Privileges', ''); // A shortened htmlFormArray with only the field being changed, for t0history.
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        foreach ($Task as $Key => $V) {
            $OneRowFound = FALSE;
            if (!isset($_SESSION['Scratch']['NewUPRow'][$Key])) {
                $Conditions[0] = array('k0user_practice', '=', $Key);
                list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $Conditions);
                if ($RR > 1 )
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                if ($RR == 1)
                    $OneRowFound = TRUE;  // $RR == 0 okay, means UP row was deleted since this edit started, by another.
            }
            if (isset($_SESSION['Scratch']['NewUPRow'][$Key]) or $OneRowFound) {
                if ($V == 'DeleteUPRow' and $OneRowFound) {
                    $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0user_practice', $Conditions);
                    if ($Affected != 1)
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                }
                elseif ($V == 'NoneToView' and isset($_SESSION['Scratch']['NewUPRow'][$Key])) {
                    $_SESSION['Scratch']['NewUPRow'][$Key]['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]');
                    $Zfpf->insert_sql_1s($DBMSresource, 't0user_practice', $_SESSION['Scratch']['NewUPRow'][$Key]); // Initialized with view privileges in function u_p_i1 above.
                }
                elseif ($V == 'EditToView' and $OneRowFound) {
                    $Changes['c5p_practice'] = $Zfpf->encrypt_1c(NO_PRIVILEGES_ZFPF);
                    $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_practice', $Changes, $Conditions, TRUE, $ShtmlFormArray);
                    if ($Affected != 1)
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                }
                elseif ($V == 'NoneToEdit' and isset($_SESSION['Scratch']['NewUPRow'][$Key])) {
                    $_SESSION['Scratch']['NewUPRow'][$Key]['c5p_practice'] = $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF);
                    $_SESSION['Scratch']['NewUPRow'][$Key]['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]');
                    $Zfpf->insert_sql_1s($DBMSresource, 't0user_practice', $_SESSION['Scratch']['NewUPRow'][$Key]);
                }
                elseif ($V == 'ViewToEdit' and $OneRowFound) {
                    $Changes['c5p_practice'] = $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF);
                    $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_practice', $Changes, $Conditions, TRUE, $ShtmlFormArray);
                    if ($Affected != 1)
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                }
                else
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Scratch']['EUP']);
        unset($_SESSION['Post']);
        unset($_SESSION['Scratch']['Task']);
        if (isset($_SESSION['Scratch']['NewUPRow']));
            unset($_SESSION['Scratch']['NewUPRow']);
    }

    ////////////////////////////////////////////////////////////////////////////
    // user-practice history arrays
    // $Zfpf a CoreZfpf object
    // $k0user is the t0user primary key for the user whose user-practice records history is being searched.
    // $EntityArray is an associative array with these allowed keys: owner, contractor, facility, process (the entity)
    // and values equal to the primary key for the entity (in the table for that entity), for example: array('facility' => k0facility)
    public function eup_history_array($Zfpf, $k0user, $EntityArray) {
        $EUPH = array();
        $Conditions[0] = array('k0_2nd_in_row_affected', '=', $k0user, 'AND');
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        foreach ($EntityArray as $K => $V) {
            if ($K == 'owner')
                $Conditions[1] = array('k0_4th_in_row_affected', '=', $V);
            elseif ($K == 'contractor')
                $Conditions[1] = array('k0_5th_in_row_affected', '=', $V);
            elseif ($K == 'facility')
                $Conditions[1] = array('k0_6th_in_row_affected', '=', $V);
            elseif ($K == 'process')
                $Conditions[1] = array('k0_7th_in_row_affected', '=', $V);
            else
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0history', $Conditions);
            if ($RR)
                $EUPH = array_merge($EUPH, $SR);
        }
        $Zfpf->close_connection_1s($DBMSresource);
        return $EUPH;
    }

}

