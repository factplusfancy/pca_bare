<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This class allows creating new users, etc.

class UserZfpf {

    public function htmlFormArray() {
        $htmlFormArray = array(
            'c5name_family' => array('Family name (or other similarly used name -- user will be listed under this name, if submitted)', ''),
            'c5name_given1' => array('Primary given name (user will be listed under this name, if nothing is submitted under family name)', REQUIRED_FIELD_ZFPF),
            'c5name_given2' => array('Additional name(s) or nicknames', ''),
            'k2username_hash' => array('Username, at least '.USERNAME_MIN_BYTES_ZFPF.' characters (bytes), the future user may change this later', REQUIRED_FIELD_ZFPF),
            's5password_hash' => array('Temporary password, at least '.PASSWORD_MIN_BYTES_ZFPF.' characters (bytes), with '.PASSWORD_MIN_SPECIAL_CHAR_ZFPF.' special characters, the future user must change this on first logon', REQUIRED_FIELD_ZFPF),
            'c5p_global_dbms' => array('Global database management system (DBMS) privileges', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF, 'dropdown', $_SESSION['PlainText']['GLOBAL_DBMS_OPTIONS_ZFPF']),
            'c5app_admin' => array('PSM-CAP application administrative privileges (app-admin privileges)', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF, 'dropdown', array('No', 'Yes')),
            'c5ts_created' => array('User created', '', C5_MAX_BYTES_ZFPF, 'app_assigned'),
            'c5ts_logon_revoked' => array('User logon credentials revoked time', '', C5_MAX_BYTES_ZFPF, 'app_assigned'),
            'c5ts_last_logon' => array('Last logon', '', C5_MAX_BYTES_ZFPF, 'app_assigned'),
            'c5personal_phone_mobile' => array('Personal mobile phone', ''),
            'c5personal_phone_home' => array('Home phone', ''),
            'c5e_contact_name' => array('Emergency-contact name', REQUIRED_FIELD_ZFPF),
            'c5e_contact_phone' => array('Emergency-contact phone', REQUIRED_FIELD_ZFPF),
            'c5challenge_question1' => array('Challenge question 1', REQUIRED_FIELD_ZFPF),
            's5cq_answer_hash1' => array('Challenge question 1 answer', REQUIRED_FIELD_ZFPF),
            'c5challenge_question2' => array('Challenge question 2', REQUIRED_FIELD_ZFPF),
            's5cq_answer_hash2' => array('Challenge question 2 answer', REQUIRED_FIELD_ZFPF),
            'c5challenge_question3' => array('Challenge question 3', REQUIRED_FIELD_ZFPF),
            's5cq_answer_hash3' => array('Challenge question 3 answer', REQUIRED_FIELD_ZFPF)
        );
        return $htmlFormArray;
    }

    public function user_i0n($Zfpf, $FormActionConfirm, $FormActionBack, $BackInputName = 'not_needed') {
        $EncryptedCurrentTime = $Zfpf->encrypt_1c(time());
        $EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
        $Encrypted0 = $Zfpf->encrypt_1c(0);
        $_SESSION['Selected'] = array(
            'k0user' => time().mt_rand(1000000, 9999999),
            'k2username_hash' => '[Nothing has been recorded in this field.]',
            's5password_hash' =>  $EncryptedNothing,
            'c5ts_password' => $Encrypted0,
            'c5p_global_dbms' => $Zfpf->encrypt_1c(LOW_PRIVILEGES_ZFPF), // Lowest allowed global DBMS privileges.
            'c5app_admin' => $Zfpf->encrypt_1c('No'), // Lowest privileges.
            'c5ts_created' => $EncryptedCurrentTime,
            'c5ts_logon_revoked' => $EncryptedNothing,
            'c5ts_last_logon' => $Encrypted0,
            'c5attempts' => $Encrypted0,
            'c6active_sessions' => $Zfpf->encode_encrypt_1c(array()),
            'c5name_family' => $EncryptedNothing,
            'c5name_given1' => $EncryptedNothing,
            'c5name_given2' => $EncryptedNothing,
            'c5personal_phone_mobile' => $EncryptedNothing,
            'c5personal_phone_home' => $EncryptedNothing,
            'c5e_contact_name' => $EncryptedNothing,
            'c5e_contact_phone' => $EncryptedNothing,
            'c5challenge_question1' => $EncryptedNothing,
            's5cq_answer_hash1' => $EncryptedNothing,
            'c5challenge_question2' => $EncryptedNothing,
            's5cq_answer_hash2' => $EncryptedNothing,
            'c5challenge_question3' => $EncryptedNothing,
            's5cq_answer_hash3' => $EncryptedNothing,
            'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
        );
        $this->user_i1($Zfpf, $FormActionConfirm, $FormActionBack, $BackInputName, FALSE); // This function echos and exits.
    }
    
    // o1 code
    public function user_o1($Zfpf, $FormActionBack, $BackInputName = 'not_needed') {
	    // Additional security check.
        if (!isset($_SESSION['Selected']['k0user']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0user'])))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // $_SESSION cleanup
        if (isset($_SESSION['Scratch']['SelectDisplay']))
            unset($_SESSION['Scratch']['SelectDisplay']);
        if (isset($_SESSION['Scratch']['htmlFormArray']))
            unset($_SESSION['Scratch']['htmlFormArray']);
        if (isset($_SESSION['Post']))
            unset($_SESSION['Post']);
        if (isset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']))
            unset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']);
        $Zfpf->clear_edit_lock_1c();
        if (!isset($_SESSION['Selected']['k0user'])) {
            $CheckedPost = $Zfpf->post_length_blank_1c('selected');
            if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0user'][$CheckedPost]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['Selected'] = $_SESSION['SelectResults']['t0user'][$CheckedPost];
            unset($_SESSION['SelectResults']);
        }
        $htmlFormArray = $this->htmlFormArray();
	    // Limit $htmlFormArray appropriately: 
        // Cannot show anything hashed, such as username, password, or challenge question answers.
        unset($htmlFormArray['k2username_hash']);
        unset($htmlFormArray['s5password_hash']);
        unset($htmlFormArray['s5cq_answer_hash1']);
        unset($htmlFormArray['s5cq_answer_hash2']);
        unset($htmlFormArray['s5cq_answer_hash3']);
        if ($_SESSION['Selected']['k0user'] != $_SESSION['t0user']['k0user']) { // Only show one's own challenge questions (but not answers).
            unset($htmlFormArray['c5challenge_question1']);
            unset($htmlFormArray['c5challenge_question2']);
            unset($htmlFormArray['c5challenge_question3']);
        }
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
	    echo $Zfpf->xhtml_contents_header_1c('User Record').'<h1>
        User Record</h1><h2>
        For personal and emergency contacts and other information of the person (so not about their relationship with an employer)</h2>
        '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'user_io03.php', $_SESSION['Selected'], $Display);
        $IsLeader = FALSE;
        $HistoryCredentials = FALSE;
        $LogonChangeCredentials = FALSE;
        $FullGlobalP = FALSE;
        if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF)
            $FullGlobalP = TRUE;
        // Check if current user's privileges with the organization that employs the selected user.
        if (isset($_SESSION['Scratch']['k0owner']) and isset($_SESSION['t0user_owner']) and $_SESSION['Scratch']['k0owner'] == $_SESSION['t0user_owner']['k0owner']) { // user_io03.php sets $_SESSION['Scratch']['k0owner']
            if (strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user'])) >= strlen(MID_PRIVILEGES_ZFPF)) {
                $HistoryCredentials = TRUE;
                if ($FullGlobalP and $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user'])
                    $LogonChangeCredentials = TRUE; // If self-editing, user may edit logon credentials without this, below.
            }
            if (isset($_SESSION['StatePicked']['t0owner']) and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0owner']['k0user_of_leader'])
                $IsLeader = TRUE;
        }
        elseif (isset($_SESSION['Scratch']['k0contractor']) and isset($_SESSION['t0user_contractor']) and $_SESSION['Scratch']['k0contractor'] == $_SESSION['t0user_contractor']['k0contractor']) { // user_io03.php sets $_SESSION['Scratch']['k0contractor']
            if (strlen($Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_user'])) >= strlen(MID_PRIVILEGES_ZFPF)) {
                $HistoryCredentials = TRUE;
                if ($FullGlobalP and $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user'])
                    $LogonChangeCredentials = TRUE;
            }
            if (isset($_SESSION['StatePicked']['t0contractor']) and $_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0contractor']['k0user_of_leader'])
                $IsLeader = TRUE;
        }
        $AppAdmin = FALSE;
        if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes') {
            $AppAdmin = TRUE;
            $HistoryCredentials = TRUE;
            if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user'])
                $LogonChangeCredentials = TRUE;
        }
        $SelectedUserIsAppAdmin = FALSE;
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5app_admin']) == 'Yes')
            $SelectedUserIsAppAdmin = TRUE;
        $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
        $ExtraNotice = '';
        if (!isset($_SESSION['Scratch']['k0owner']) and !isset($_SESSION['Scratch']['k0contractor']))
            $ExtraNotice = ' <b>Try accessing this user\'s record from the administration menu ("user-owner..." or "user-contractor..." records) if this seems amiss.</b>';
        if ($HistoryCredentials or $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user'])
            echo '
            <form action="user_io03.php" method="post"><p>
                <input type="submit" name="user_history_o1" value="History of user record" /></p>
            </form>';
        else
            echo '<p><b>
            User-History Notice</b>: You don\'t have privileges to view the user-history records.</p>';
        if ($LogonChangeCredentials)
            echo '
            <form action="username_password_i03.php" method="post"><p>
                <input type="submit" name="step_i1" value="Change password or username" /></p>
            </form>';
        elseif ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user']) // If self-editing, user may edit logon credentials below.
            echo '<p><b>
            Logon Credentials Notice</b>: You don\'t have privileges to unlock this user or change their username or password.'.$ExtraNotice.'</p>';
        if ($IsLeader and ($AppAdmin or !$SelectedUserIsAppAdmin) and $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user'] and $FullGlobalP) { // Allow below even if record is being edited.
            $_SESSION['Scratch']['GlobalAndLogonPrivAuth'] = $Zfpf->encrypt_1c(TRUE);
            echo '
            <form action="user_io03.php" method="post"><p>
                Change user\'s PSM-CAP app global privileges.<br />
                <input type="submit" name="change_global_dbms_priv_1" value="Change global privileges" /></p>';
            if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_logon_revoked']) == '[Nothing has been recorded in this field.]')
                echo '<p>
                Revoke user\'s logon credentials to this PSM-CAP app.<br />
                <input type="submit" name="revoke_logon_1" value="Revoke logon credentials" /></p>';
            else
                echo '<p>
                Restore user\'s logon credentials to this PSM-CAP app.<br />
                <input type="submit" name="restore_logon_1" value="Restore logon credentials" /></p>';
            echo '
            </form>';
        }
        elseif ($who_is_editing != '[Nobody is editing.]')
            echo '<p><b>
            '.$who_is_editing.' is editing the record you selected.</b><br />
            If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
        elseif ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user']) { // Allow user to edit their own recorded personal, emergency, and logon information (but not privileges), regardless of global DBMS privileges.
            echo '
            <form action="user_io03.php" method="post"><p>
                Update your user record, including challenge questions.<br />
                <input type="submit" name="user_o1_from" value="Update user record" /></p>
            </form>
            <form action="username_password_i13bu.php" method="post"><p>
                <input type="submit" name="username_password_i1" value="Change username or password" /></p>
            </form>';
            if ($IsLeader)
                echo '<p><b>
                No Self Privilege-Change Notice</b>: Users cannot revoke their own logon credentials to this PSM-CAP app or modify their own PSM-CAP app global privileges.</p>';
        }
        else {
            if (!$IsLeader)
                echo '<p><b>
                Modify Global User Privileges Notice</b>: Only the '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader of the owner (or contractor) that employs the selected user may change their PSM-CAP app global privileges, remove access to information, or revoke their logon credentials to this app. The app could not verify that you meet these criteria.'.$ExtraNotice.'</p>';
            if (!$AppAdmin and $SelectedUserIsAppAdmin)
                echo '<p><b>
                App Admin Notice</b>: The selected user is a PSM-CAP app administrator (app admin) so only another app admin can change their PSM-CAP app global privileges or revoke their logon credentials to this app. You are not an app admin.</p>';
            if (!$FullGlobalP)
                echo '<p><b>
                Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP app records. If you need this, please contact your supervisor or a PSM-CAP app administrator and ask them to upgrade your PSM-CAP app global privileges.</p>';
            if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user'])
                echo '<p><b>
                User-Only Notice</b>: Only users can modify their own recorded personal, emergency, and logon information.</p>';
        }
        if ($AppAdmin) {
            echo '
            <form action="user_io03.php" method="post">';
            if ($SelectedUserIsAppAdmin)
                echo '<p>
                <input type="submit" name="revoke_app_admin_1" value="Revoke app-admin privileges" /></p>';
            else {
                if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF)
                    echo '<p>
                    <input type="submit" name="grant_app_admin_1" value="Grant app-admin privileges" /></p>';
                else
                    echo '<p><b>
                    Global Privileges Inadequate for App Admin</b>: Users must have full global DBMS privileges to be granted app-admin privileges.</p>';
            }
            echo '
            </form>';
        }
        echo '
        <form action="'.$FormActionBack.'" method="post"><p>
            <input type="submit" name="'.$BackInputName.'" value="Go back" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    public function display_privileges_fp($Zfpf, $k0owner, $DBMSresource = FALSE, $AllUserPractices = FALSE) {
        if (!$DBMSresource) {
            $DBMSresource = $Zfpf->credentials_connect_instance_1s();
            $NeedToCloseConnection = TRUE;
        }
        $PrivilegesText = '';
        $ConditionsUP = array(); // Initialize as an array so calling function can do an array merge, even if unchanged in this function.
        $Conditions[0] = array('k0owner', '=', $k0owner);
        list($SROF, $RROF) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_facility', $Conditions, array('k0facility'));
        if ($RROF) foreach ($SROF as $VOF) {
            if (!$AllUserPractices)
                $ConditionsUP[] = array('k0facility', '=', $VOF['k0facility'], 'OR');
            unset($Conditions);
            $Conditions[0] = array('k0facility', '=', $VOF['k0facility']);
            list($SRF, $RRF) = $Zfpf->select_sql_1s($DBMSresource, 't0facility', $Conditions, array('k0user_of_leader', 'c5name', 'c5city', 'c5state_province', 'c5country'));
            if ($RRF != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRF);
            list($SRFP, $RRFP) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_process', $Conditions);
            $Conditions[1] = array('k0user', '=', $_SESSION['Selected']['k0user'], '', 'AND');
            list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions, array('c5p_facility', 'c5p_union', 'c5p_user', 'c5p_process'));
            if ($RRUF > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUF);
            if ($RRUF == 1) {
                if ($_SESSION['Selected']['k0user'] == $SRF[0]['k0user_of_leader'])
                    $IsLeader = 'Yes';
                else
                    $IsLeader = 'No';
                $PrivilegesText .= '<p>
                '.$Zfpf->decrypt_1c($SRF[0]['c5name']).', '.$Zfpf->decrypt_1c($SRF[0]['c5city']).', '.$Zfpf->decrypt_1c($SRF[0]['c5state_province']).', '.$Zfpf->decrypt_1c($SRF[0]['c5country']).':<br />
                - The '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$IsLeader.'<br />
                - Facility: '.$Zfpf->decrypt_1c($SRUF[0]['c5p_facility']).'<br />
                - Union: '.$Zfpf->decrypt_1c($SRUF[0]['c5p_union']).'<br />
                - User-facility (and subordinate practices): '.$Zfpf->decrypt_1c($SRUF[0]['c5p_user']).'<br />
                - Process: '.$Zfpf->decrypt_1c($SRUF[0]['c5p_process']).'</p>';
            }
            if ($RRFP) foreach ($SRFP as $VFP) {
                if (!$AllUserPractices)
                    $ConditionsUP[] = array('k0process', '=', $VFP['k0process'], 'OR');
                unset($Conditions);
                $Conditions[0] = array('k0process', '=', $VFP['k0process']);
                list($SRP, $RRP) = $Zfpf->select_sql_1s($DBMSresource, 't0process', $Conditions, array('k0user_of_leader', 'c5name', 'c6description'));
                if ($RRP != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRP);
                $Conditions[1] = array('k0user', '=', $_SESSION['Selected']['k0user'], '', 'AND');
                list($SRUP, $RRUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions, array('c5p_process', 'c5p_user'));
                if ($RRUP > 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUP);
                if ($RRUP == 1) {
                    if ($_SESSION['Selected']['k0user'] == $SRP[0]['k0user_of_leader'])
                        $IsLeader = 'Yes';
                    else
                        $IsLeader = 'No';
                    $PrivilegesText .= '<p>
                    '.$Zfpf->entity_name_description_1c($SRP[0], 100, FALSE).':<br />
                    - The '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$IsLeader.'<br />
                    - Process: '.$Zfpf->decrypt_1c($SRUP[0]['c5p_process']).'<br />
                    - User-process (and applicable facility and owner practices): '.$Zfpf->decrypt_1c($SRUP[0]['c5p_user']).'</p>';
                }
            }
        }
        if (isset($NeedToCloseConnection))
            $Zfpf->close_connection_1s($DBMSresource);
        return array($PrivilegesText, $ConditionsUP);
    }

    public function raw_practice_priv_means($Zfpf, $RawPracticePriv, $SelectedUser) {
        $AndSomeInserts = ''; // See t0user_practice notes in schema -- view privileges allow starting some records.
        if ($Zfpf->decrypt_1c($SelectedUser['t0user']['c5p_global_dbms']) != LOW_PRIVILEGES_ZFPF)
            $AndSomeInserts = ' and some inserts';
        if ($RawPracticePriv == NO_PRIVILEGES_ZFPF)
            return 'View'.$AndSomeInserts;
        if ($RawPracticePriv == MAX_PRIVILEGES_ZFPF)
            return 'Edit and view';
        return $RawPracticePriv;
    }
    
    public function display_privileges($Zfpf, $SelectedUser, $AllUserPractices = FALSE) {
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $ConditionsUP[0] = array('k0user', '=', $_SESSION['Selected']['k0user']); // Alone this catches all user-practice (UP) relationships.
        // Display owner or contractor privileges, based on priority in CoreZfpf::user_job_info_1c
        if ($_SESSION['Selected']['k0user'] == $SelectedUser['t0employer']['k0user_of_leader'])
            $IsLeader = 'Yes';
        else
            $IsLeader = 'No';
        $PrivilegesText = '<p>
        '.$SelectedUser['Employer'].':<br />';
        if (isset($SelectedUser['t0user_employer']['k0user_owner'])) {
            $PrivilegesText .= '
            - The '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$IsLeader.'<br />
            - Owner: '.$Zfpf->decrypt_1c($SelectedUser['t0user_employer']['c5p_owner']).'<br />
            - User: '.$Zfpf->decrypt_1c($SelectedUser['t0user_employer']['c5p_user']).'<br />
            - Contractor: '.$Zfpf->decrypt_1c($SelectedUser['t0user_employer']['c5p_contractor']).'<br />
            - Facility: '.$Zfpf->decrypt_1c($SelectedUser['t0user_employer']['c5p_facility']).'</p>';
            if (!$AllUserPractices)
                $ConditionsUP[1] = array('k0owner', '=', $SelectedUser['t0user_employer']['k0owner'], 'OR', 'AND (');
            list($PrivTextFP, $ConditionsUPFromFP) = $this->display_privileges_fp($Zfpf, $SelectedUser['t0user_employer']['k0owner'], $DBMSresource, $AllUserPractices); // FP stands for facilities and processes.
            $PrivilegesText .= $PrivTextFP;
            $ConditionsUP = array_merge($ConditionsUP, $ConditionsUPFromFP);
        }
        elseif (isset($SelectedUser['t0user_employer']['k0user_contractor'])) {
            $PrivilegesText .= '
            - The '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$IsLeader.'<br />
            - Contractor organization: '.$Zfpf->decrypt_1c($SelectedUser['t0user_employer']['c5p_contractor']).'<br />
            - Contractor individual (user): '.$Zfpf->decrypt_1c($SelectedUser['t0user_employer']['c5p_user']).'</p>';
            if (!$AllUserPractices)
                $ConditionsUP[1] = array('k0contractor', '=', $SelectedUser['t0user_employer']['k0contractor'], 'OR', 'AND (');
            $Conditions[0] = array('k0contractor', '=', $SelectedUser['t0user_employer']['k0contractor']);
            list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions, array('k0owner'));
            if ($RROC) foreach ($SROC as $VOC) {
                list($PrivTextFP, $ConditionsUPFromFP) = $this->display_privileges_fp($Zfpf, $VOC['k0owner'], $DBMSresource, $AllUserPractices);
                $PrivilegesText .= $PrivTextFP;
                $ConditionsUP[] = array('k0owner', '=', $VOC['k0owner'], 'OR'); // Contractors may do Owner Standard Practices.
                $ConditionsUP = array_merge($ConditionsUP, $ConditionsUPFromFP);
            }
        } // No "else eject" because current user may be an admin associated with neither an owner nor a contractor in the app.
        $CountCUP = count($ConditionsUP);
        if ($CountCUP == 2) {
            $ConditionsUP[1][3] = '';
            $ConditionsUP[1][4] = 'AND';
        }
        if ($CountCUP > 2) {
            $LastArrayKey = --$CountCUP;
            $ConditionsUP[$LastArrayKey][3] = ')';
        }
        list($SRUPractice, $RRUPractice) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $ConditionsUP, array('k0practice', 'c5p_practice'));
        if ($RRUPractice) {
            foreach ($SRUPractice as $KUPractice => $VUPractice) {
                $Conditions[0] = array('k0practice', '=', $VUPractice['k0practice']);
                list($SRPractice, $RRPractice) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions, array('c5name', 'c5number'));
                if ($RRPractice != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRPractice);
                $PracticeNumber[$KUPractice] = $Zfpf->decrypt_1c($SRPractice[0]['c5number']);
                $PracticeName[$KUPractice] = $Zfpf->decrypt_1c($SRPractice[0]['c5name']);
            }
            array_multisort($PracticeNumber, $PracticeName, $SRUPractice); // sort by t0practice:c5number
            $PrivilegesText .= '<p>
            Practice privileges ';
            if ($AllUserPractices)
                $PrivilegesText .= '<b>with all entities in this app deployment</b>:';
            else
                $PrivilegesText .= '<b>with all the above entities:</b>';
            foreach ($SRUPractice as $KUPractice => $VUPractice)
                $PrivilegesText .= '<br />
                - '.$PracticeName[$KUPractice].': '.$this->raw_practice_priv_means($Zfpf, $Zfpf->decrypt_1c($VUPractice['c5p_practice']), $SelectedUser);
            $PrivilegesText .= '</p>';
        }
        $Zfpf->close_connection_1s($DBMSresource);
        return $PrivilegesText;
    }

    public function change_global_dbms_priv_1($Zfpf) {
	    // Additional security check.
        if (!isset($_SESSION['Selected']['k0user']) or !isset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']))
            $Zfpf->send_to_contents_1c(); // Don't eject
        $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Change Global DBMS Privileges</h2>
        <form action="user_io03.php" method="post"><p>
        <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b><br />
        Select new global database-management system (DBMS) privileges (defaults to current privileges)<br />
        <select name="new_global_dbms_priv">';
        foreach($_SESSION['PlainText']['GLOBAL_DBMS_OPTIONS_ZFPF'] as $V) {
            if ($V == $Zfpf->decrypt_1c($_SESSION['Selected']['c5p_global_dbms'])) // Default is current privileges. 
                echo '<option value="'.$V.'" selected="selected">'.$V.'</option>';
            else
                echo '<option value="'.$V.'">'.$V.'</option>';
        }
        echo '</select></p>
        <p>If downgraded, the app will also try to downgrade, to the maximum allowed with the new global DBMS privileges, the user\'s privileges listed below.</p>
        <p>The user may have privileges with owners or contractors other than the currently selected one and with their facilities, processes, or practices, which the app will <b>not</b> automatically downgrade.</p>
        <p>The app cannot assign a new '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader. You need to do this, if you plan to downgrade a '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader\'s privileges. All '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leaders need full global DBMS privileges, including the '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leaders for owners, contractors, facilities, and processes and the leaders for any ongoing PHA or HIRA, incident investigation, or PSM audit.</p>
        '.$this->display_privileges($Zfpf, $SelectedUser, TRUE).'<p>
            Change user\'s PSM-CAP app global privileges.<br />
            <input type="submit" name="change_global_dbms_priv_2" value="Change global privileges" /></p><p>
            <input type="submit" name="user_o1" value="Take no action -- go back" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

     public function downgrade_privileges_fp($Zfpf, $DBMSresource, $k0owner) {
        $EncryptedNoPriv = $Zfpf->encrypt_1c(NO_PRIVILEGES_ZFPF);
        $Conditions[0] = array('k0owner', '=', $k0owner);
        list($SROF, $RROF) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_facility', $Conditions, array('k0facility'));
        if ($RROF) foreach ($SROF as $VOF) {
            unset($Conditions);
            $Conditions[0] = array('k0facility', '=', $VOF['k0facility']);
            list($SRFP, $RRFP) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_process', $Conditions);
            $Conditions[1] = array('k0user', '=', $_SESSION['Selected']['k0user'], '', 'AND');
            list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions, array('k0user_facility'));
            if ($RRUF > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUF);
            if ($RRUF == 1) {
                $ChangesUF['c5p_facility'] = $EncryptedNoPriv;
                $ChangesUF['c5p_union'] = $EncryptedNoPriv;
                $ChangesUF['c5p_user'] = $EncryptedNoPriv;
                $ChangesUF['c5p_process'] = $EncryptedNoPriv;
                $htmlFormArrayShort = array(
                    'c5p_facility' => array('c5p_facility privileges (downgraded along with c5p_global_dbms)', ''),
                    'c5p_union' => array('c5p_union privileges (downgraded along with c5p_global_dbms)', ''),
                    'c5p_user' => array('c5p_user privileges (downgraded along with c5p_global_dbms)', ''),
                    'c5p_process' => array('c5p_process privileges (downgraded along with c5p_global_dbms)', '')
                );
                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_facility', $ChangesUF, $Conditions, TRUE, $htmlFormArrayShort);
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
            }
            if ($RRFP) foreach ($SRFP as $VFP) {
                $Conditions[0] = array('k0process', '=', $VFP['k0process']);
                list($SRUP, $RRUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions, array('c5p_process', 'c5p_user'));
                if ($RRUP > 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUP);
                if ($RRUP == 1) {
                    $ChangesUP['c5p_process'] = $EncryptedNoPriv;
                    $ChangesUP['c5p_user'] = $EncryptedNoPriv;
                    $htmlFormArrayShort = array(
                        'c5p_process' => array('c5p_process privileges (downgraded along with c5p_global_dbms)', ''),
                        'c5p_user' => array('c5p_user privileges (downgraded along with c5p_global_dbms)', '')
                    );
                    $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_process', $ChangesUP, $Conditions, TRUE, $htmlFormArrayShort);
                    if ($Affected != 1)
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
                }
            }
        }
     }
      
     public function change_global_dbms_priv_2($Zfpf) {
	    // Additional security check.
        if (!isset($_SESSION['Selected']['k0user']) or !isset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']) or !isset($_POST['new_global_dbms_priv']) or !in_array($_POST['new_global_dbms_priv'], $_SESSION['PlainText']['GLOBAL_DBMS_OPTIONS_ZFPF'])) // !in_array here handles tasks of CoreZfpf:xss_prevent_1c and CoreZfpf:max_length_1c
            $Zfpf->send_to_contents_1c(); // Don't eject
        $OldPriv = $Zfpf->decrypt_1c($_SESSION['Selected']['c5p_global_dbms']);
        if ($_POST['new_global_dbms_priv'] == $OldPriv) {
            echo $Zfpf->xhtml_contents_header_1c().'<h2>
            Current Privileges Submitted</h2><p>
            The global DBMS privileges you submitted are the same as the current ones for the selected user. Try again.</p>
            <form action="user_io03.php" method="post"><p>
                <input type="submit" name="user_o1" value="Back to user record" /></p>
            </form>'.$Zfpf->xhtml_footer_1c();
            unset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']);
            $Zfpf->save_and_exit_1c();
        }
        $htmlFormArrayShort = array(
            'c5p_global_dbms' => array('Global Database Management System (DBMS) Privileges', '')
        );
        $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
        $ChangesU['c5p_global_dbms'] = $Zfpf->encrypt_1c($_POST['new_global_dbms_priv']); // User input checked by above security check.
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $ChangesU, $Conditions, TRUE, $htmlFormArrayShort);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
        $EncryptedNoPriv = $Zfpf->encrypt_1c(NO_PRIVILEGES_ZFPF);
        $EncryptedMidPriv =$Zfpf->encrypt_1c(MID_PRIVILEGES_ZFPF);
        $NewPrivStrlen = strlen($_POST['new_global_dbms_priv']);
        if ($NewPrivStrlen < strlen($OldPriv)) { // Downgrade dependent privileges.
            // if ($NewPrivStrlen < strlen(MAX_PRIVILEGES_ZFPF)) must be true here
            list($SRUPractice, $RRUPractice) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $Conditions, array('k0user_practice', 'c5p_practice'));
            if ($RRUPractice) foreach ($SRUPractice as $VUPratice) {
                $ChangesUPractice['c5p_practice'] = $EncryptedNoPriv; // See setup.php for allowed values.
                $htmlFormArrayShort = array(
                    'c5p_practice' => array('c5p_practice privileges (downgraded along with c5p_global_dbms)', '')
                );
                $Conditions[0] = array('k0user_practice', '=', $VUPratice['k0user_practice']);
                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_practice', $ChangesUPractice, $Conditions, TRUE, $htmlFormArrayShort);
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
            }
            if (isset($SelectedUser['t0user_employer']['k0user_owner'])) {
                $ChangesUO['c5p_owner'] = $EncryptedNoPriv;
                $ChangesUO['c5p_user'] = $EncryptedMidPriv;
                $ChangesUO['c5p_contractor'] = $EncryptedMidPriv;
                $ChangesUO['c5p_facility'] = $EncryptedNoPriv;
                if ($NewPrivStrlen < strlen(MID_PRIVILEGES_ZFPF)) {
                    $ChangesUO['c5p_user'] = $EncryptedNoPriv;
                    $ChangesUO['c5p_contractor'] = $EncryptedNoPriv;
                }
                $htmlFormArrayShort = array(
                    'c5p_owner' => array('c5p_owner privileges (downgraded along with c5p_global_dbms)', ''),
                    'c5p_user' => array('c5p_user privileges (downgraded along with c5p_global_dbms)', ''),
                    'c5p_contractor' => array('c5p_contractor privileges (downgraded along with c5p_global_dbms)', ''),
                    'c5p_facility' => array('c5p_facility privileges (downgraded along with c5p_global_dbms)', '')
                );
                $Conditions[0] = array('k0user_owner', '=', $SelectedUser['t0user_employer']['k0user_owner']);
                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_owner', $ChangesUO, $Conditions, TRUE, $htmlFormArrayShort);
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
                $this->downgrade_privileges_fp($Zfpf, $DBMSresource, $SelectedUser['t0user_employer']['k0owner']);
            }
            elseif (isset($SelectedUser['t0user_employer']['k0user_contractor'])) {
                $ChangesUC['c5p_contractor'] = $EncryptedNoPriv;
                $ChangesUC['c5p_user'] = $EncryptedMidPriv;
                if ($NewPrivStrlen < strlen(MID_PRIVILEGES_ZFPF))
                    $ChangesUC['c5p_user'] = $EncryptedNoPriv;
                $htmlFormArrayShort = array(
                    'c5p_contractor' => array('c5p_contractor privileges (downgraded along with c5p_global_dbms)', ''),
                    'c5p_user' => array('c5p_user privileges (downgraded along with c5p_global_dbms)', '')
                );
                $Conditions[0] = array('k0user_contractor', '=', $SelectedUser['t0user_employer']['k0user_contractor']);
                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user_contractor', $ChangesUC, $Conditions, TRUE, $htmlFormArrayShort);
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
                $Conditions[0] = array('k0contractor', '=', $SelectedUser['t0user_employer']['k0contractor']);
                list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions, array('k0owner'));
                if ($RROC) foreach ($SROC as $VOC)
                    $this->downgrade_privileges_fp($Zfpf, $DBMSresource, $VOC['k0owner']);
            } // No "else eject" because current user may be an admin associated with neither an owner nor a contractor in the app.
        }
        $_SESSION['Selected']['c5p_global_dbms'] = $ChangesU['c5p_global_dbms'];
        $Zfpf->close_connection_1s($DBMSresource);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Confirmation</h2><p>
        The app attempted to change the global DBMS privileges for:<br />
        <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p>
        <form action="user_io03.php" method="post"><p>
            <input type="submit" name="user_o1" value="Back to user record" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        unset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']);
        $Zfpf->save_and_exit_1c();
    }
 
    // Note: CoreZfpf::full_name_1c appends a note to the returned name if a user's logon credentials have been revoked.
    public function revoke_logon_1($Zfpf) {
	    // Additional security check.
        if (!isset($_SESSION['Selected']['k0user']) or !isset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']))
            $Zfpf->send_to_contents_1c(); // Don't eject
        $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
        echo $Zfpf->xhtml_contents_header_1c('Revoke Logon').'<h2>
        Revoke Logon Credentials</h2><p>
        Revoke the logon credentials to the PSM-CAP app for:<br />
        <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p>
        <p>This only prevents them from logging on and gives a notice of this wherever the app displays this user\'s name. You will need to assign a new leader if the above user is a '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for an owner, contractor, facility, or process, or for an ongoing PHA or HIRA, PSM audit, or incident investigation.</p>
        <form action="user_io03.php" method="post"><p>
            <input type="submit" name="revoke_logon_2" value="Revoke logon credentials" /></p><p>
            <input type="submit" name="user_o1" value="Take no action -- go back" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    public function revoke_logon_2($Zfpf) {
	    // Additional security check.
        if (!isset($_SESSION['Selected']['k0user']) or !isset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']))
            $Zfpf->send_to_contents_1c(); // Don't eject
        $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
        $htmlFormArrayShort = array(
            'c5ts_logon_revoked' => array('Logon Credentials Revoked', ''),
            's5password_hash' => array('Password, which was overwritten with random stuff', '')
        );
        $Changes['c5ts_logon_revoked'] = $Zfpf->encrypt_1c(time());
        $Changes['s5password_hash'] = $Zfpf->encrypt_1c($Zfpf->hash_1c(openssl_random_pseudo_bytes(16))); // Overwrite password...
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, TRUE, $htmlFormArrayShort);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        foreach ($Changes as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $ActiveSessions = $Zfpf->decrypt_decode_1c($_SESSION['Selected']['c6active_sessions']);
        if ($ActiveSessions) {
            // Logoff the selected user from all sessions where they were logged in, on the current server.
            // Do this after above update in case error with CoreZfpf::log_off_all_1c or session juggling, then do another update.
            $CurrentSessionID = session_id(); // Save this before calling CoreZfpf::log_off_all_1c
            unset($Changes);
            $Changes['c6active_sessions'] = $Zfpf->encode_encrypt_1c($Zfpf->log_off_all_1c($ActiveSessions));
            session_id($CurrentSessionID); // Restore the session_id after calling CoreZfpf::log_off_all_1c
            session_start(); // Restart the session for the current user.
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
            $_SESSION['Selected']['c6active_sessions'] = $Changes['c6active_sessions'];
        }
        $Zfpf->close_connection_1s($DBMSresource);
        $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
        echo $Zfpf->xhtml_contents_header_1c('Logon Revoked').'<h2>
        Confirmation</h2><p>
        The app attempted to revoke the logon credentials to the PSM-CAP app for:<br />
        <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p>
        <form action="user_io03.php" method="post"><p>
            <input type="submit" name="user_o1" value="Back to user record" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        unset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']);
        $Zfpf->save_and_exit_1c();
    }

    public function restore_logon_1($Zfpf) {
	    // Additional security check.
        if (!isset($_SESSION['Selected']['k0user']) or !isset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']))
            $Zfpf->send_to_contents_1c(); // Don't eject
        $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
        echo $Zfpf->xhtml_contents_header_1c('Restore Logon').'<h2>
        Restore Logon Credentials</h2><p>
        Restore the logon credentials to the PSM-CAP app for:<br />
        <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p>
        <p>Once restored, this user will have the following leadership and other privileges with the entities below <b>and may have privileges with other entities using this deployment of the app</b>:</p>
        '.$this->display_privileges($Zfpf, $SelectedUser, TRUE).'
        <form action="user_io03.php" method="post"><p>
            <input type="submit" name="restore_logon_2" value="Restore logon credentials" /></p><p>
            <input type="submit" name="user_o1" value="Take no action -- go back" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    public function restore_logon_2($Zfpf) {
	    // Additional security check.
        if (!isset($_SESSION['Selected']['k0user']) or !isset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']))
            $Zfpf->send_to_contents_1c(); // Don't eject
        $Changes['c5ts_logon_revoked'] = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
        $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
        $htmlFormArrayShort = array(
            'c5ts_logon_revoked' => array('Logon Credentials Revoked Timestamp, "[Nothing has been recorded in this field.]" below indicates that they were restored', '')
        );
        $Affected = $Zfpf->one_shot_update_1s('t0user', $Changes, $Conditions, TRUE, $htmlFormArrayShort);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        $_SESSION['Selected']['c5ts_logon_revoked'] = $Changes['c5ts_logon_revoked'];
        $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
        echo $Zfpf->xhtml_contents_header_1c('Logon Restored').'<h2>
        Confirmation</h2><p>
        The app attempted to restore the logon credentials to the PSM-CAP app for:<br />
        <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p><p>
        They will need a temporary password to logon.</p>
        <form action="username_password_i03.php" method="post"><p>
            <input type="submit" name="step_i1" value="Provide temporary password" /></p>
        </form>
        <form action="user_io03.php" method="post"><p>
            <input type="submit" name="user_o1" value="Back to user record" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        unset($_SESSION['Scratch']['GlobalAndLogonPrivAuth']);
        $Zfpf->save_and_exit_1c();
    }

    public function separate_user_p($Zfpf, $DBMSresource, $k0facility) {
        $LeadersNeeded = '';
        $ConditionsUP = array();
        $Conditions[0] = array('k0facility', '=', $k0facility);
        list($SRFP, $RRFP) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_process', $Conditions);
        if ($RRFP) foreach ($SRFP as $VFP) {
            $ConditionsUP[] = array('k0process', '=', $VFP['k0process'], 'OR');
            unset($Conditions);
            $Conditions[0] = array('k0process', '=', $VFP['k0process']);
            list($SRP, $RRP) = $Zfpf->select_sql_1s($DBMSresource, 't0process', $Conditions, array('k0user_of_leader', 'c5name', 'c6description'));
            if ($RRP != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRP);
            $Conditions[1] = array('k0user', '=', $_SESSION['Selected']['k0user'], '', 'AND');
            list($SRUP, $RRUP) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions, array('k0user_process'));
            if ($RRUP > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUP);
            if ($RRUP == 1) {
                $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0user_process', $Conditions);
                if ($Affected > 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
                if ($_SESSION['Selected']['k0user'] == $SRP[0]['k0user_of_leader']) {
                    $LeadersNeeded .= '- Need a new '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for process: '.$Zfpf->entity_name_description_1c($SRP[0], 100, FALSE).'<br />';
                }
            }
        }
        return array($LeadersNeeded, $ConditionsUP);
    }

    public function separate_user_fp($Zfpf, $DBMSresource, $k0owner) {
        $LeadersNeeded = '';
        $ConditionsUP = array();
        $Conditions[0] = array('k0owner', '=', $k0owner);
        list($SROF, $RROF) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_facility', $Conditions, array('k0facility'));
        if ($RROF) foreach ($SROF as $VOF) {
            $ConditionsUP[] = array('k0facility', '=', $VOF['k0facility'], 'OR');
            unset($Conditions);
            $Conditions[0] = array('k0facility', '=', $VOF['k0facility']);
            list($SRF, $RRF) = $Zfpf->select_sql_1s($DBMSresource, 't0facility', $Conditions, array('k0user_of_leader', 'c5name', 'c5city', 'c5state_province', 'c5country'));
            if ($RRF != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRF);
            $Conditions[1] = array('k0user', '=', $_SESSION['Selected']['k0user'], '', 'AND');
            list($SRUF, $RRUF) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions, array('k0user_facility'));
            if ($RRUF > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRUF);
            if ($RRUF == 1) {
                $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0user_facility', $Conditions);
                if ($Affected > 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
                if ($_SESSION['Selected']['k0user'] == $SRF[0]['k0user_of_leader'])
                    $LeadersNeeded .= '- Need a new '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for facility: '.$Zfpf->decrypt_1c($SRF[0]['c5name']).', '.$Zfpf->decrypt_1c($SRF[0]['c5city']).', '.$Zfpf->decrypt_1c($SRF[0]['c5state_province']).', '.$Zfpf->decrypt_1c($SRF[0]['c5country']).'<br />';
            }
            list($LeadersNeededP, $ConditionsUPFromP) = $this->separate_user_p($Zfpf, $DBMSresource, $VOF['k0facility']);
            $LeadersNeeded .= $LeadersNeededP;
            $ConditionsUP = array_merge($ConditionsUP, $ConditionsUPFromP);
        }
        return array($LeadersNeeded, $ConditionsUP);
    }

    public function separate_user($Zfpf, $From) {
        $LeadersNeeded = '';
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($From == 'owner') {
            if (!isset($_SESSION['Selected']['k0user_owner']) or !isset($_SESSION['t0user_owner']) or $_SESSION['t0user_owner']['k0owner'] != $_SESSION['Selected']['k0owner'] or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Conditions[0] = array('k0user_owner', '=', $_SESSION['Selected']['k0user_owner']);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0user_owner', $Conditions);
            if ($Affected > 1) // Allow separation process to continue if for some reason no rows affected.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
            $ConditionsUP[0] = array('k0user', '=', $_SESSION['Selected']['k0user']); // Start building the user-practice conditions.
            $ConditionsUP[1] = array('k0owner', '=', $_SESSION['Selected']['k0owner'], 'OR', 'AND (');
            list($LeadersNeededFP, $ConditionsUPFromFP) = $this->separate_user_fp($Zfpf, $DBMSresource, $_SESSION['Selected']['k0owner']);
            $LeadersNeeded .= $LeadersNeededFP;
            $ConditionsUP = array_merge($ConditionsUP, $ConditionsUPFromFP);
            $Conditions[0] = array('k0owner', '=', $_SESSION['Selected']['k0owner']);
            list($SRO, $RRO) = $Zfpf->select_sql_1s($DBMSresource, 't0owner', $Conditions, array('k0user_of_leader', 'c5name', 'c5city', 'c5state_province', 'c5country'));
            if ($RRO != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRF);
            if ($_SESSION['Selected']['k0user'] == $SRO[0]['k0user_of_leader'])
                $LeadersNeeded .= '- Need a new '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for owner: '.$Zfpf->decrypt_1c($SRO[0]['c5name']).', '.$Zfpf->decrypt_1c($SRO[0]['c5city']).', '.$Zfpf->decrypt_1c($SRO[0]['c5state_province']).', '.$Zfpf->decrypt_1c($SRO[0]['c5country']).'<br />';
        }
        elseif ($From == 'contractor') {
            if (!isset($_SESSION['Selected']['k0user_contractor']) or !isset($_SESSION['t0user_contractor']) or $_SESSION['t0user_contractor']['k0contractor'] != $_SESSION['Selected']['k0contractor'] or $Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Conditions[0] = array('k0user_contractor', '=', $_SESSION['Selected']['k0user_contractor']);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0user_contractor', $Conditions);
            if ($Affected > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
            $ConditionsUP[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
            $ConditionsUP[1] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor'], 'OR', 'AND (');
            $Conditions[0] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor']);
            list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions, array('k0owner'));
            if ($RROC) foreach ($SROC as $VOC) {
                list($LeadersNeededFP, $ConditionsUPFromFP) = $this->separate_user_fp($Zfpf, $DBMSresource, $VOC['k0owner']);
                $LeadersNeeded .= $LeadersNeededFP;
                $ConditionsUP[] = array('k0owner', '=', $VOC['k0owner'], 'OR'); // Contractors may do Owner Standard Practices.
                $ConditionsUP = array_merge($ConditionsUP, $ConditionsUPFromFP);
            }
            list($SRC, $RRC) = $Zfpf->select_sql_1s($DBMSresource, 't0contractor', $Conditions, array('k0user_of_leader', 'c5name', 'c5city', 'c5state_province', 'c5country'));
            if ($RRC != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRF);
            if ($_SESSION['Selected']['k0user'] == $SRC[0]['k0user_of_leader'])
                $LeadersNeeded .= '- Need a new '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for contractor: '.$Zfpf->decrypt_1c($SRC[0]['c5name']).', '.$Zfpf->decrypt_1c($SRC[0]['c5city']).', '.$Zfpf->decrypt_1c($SRC[0]['c5state_province']).', '.$Zfpf->decrypt_1c($SRC[0]['c5country']).'<br />';
        }
        elseif ($From == 'facility') {
            if (!isset($_SESSION['Selected']['k0user_facility']) or !isset($_SESSION['t0user_facility']) or $_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'] or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Conditions[0] = array('k0user_facility', '=', $_SESSION['Selected']['k0user_facility']);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0user_facility', $Conditions);
            if ($Affected > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
            $ConditionsUP[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
            $ConditionsUP[1] = array('k0facility', '=', $_SESSION['Selected']['k0facility'], 'OR', 'AND (');
            list($LeadersNeededP, $ConditionsUPFromP) = $this->separate_user_p($Zfpf, $DBMSresource, $_SESSION['Selected']['k0facility']);
            $LeadersNeeded .= $LeadersNeededP;
            $ConditionsUP = array_merge($ConditionsUP, $ConditionsUPFromP);
        }
        elseif ($From == 'process') {
            if (!isset($_SESSION['Selected']['k0user_process']) or !isset($_SESSION['t0user_process']) or $_SESSION['t0user_process']['k0process'] != $_SESSION['Selected']['k0process'] or $Zfpf->decrypt_1c($_SESSION['t0user_process']['c5p_user']) != MAX_PRIVILEGES_ZFPF or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Conditions[0] = array('k0user_process', '=', $_SESSION['Selected']['k0user_process']);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0user_process', $Conditions);
            if ($Affected > 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
            $ConditionsUP[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
            $ConditionsUP[1] = array('k0process', '=', $_SESSION['Selected']['k0process'], '', 'AND'); // Use this format for handling below.
        }
        else
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // Separate users from practices.
        $CountCUP = count($ConditionsUP);
        if ($CountCUP == 2) {
            $ConditionsUP[1][3] = '';
            $ConditionsUP[1][4] = 'AND';
        }
        if ($CountCUP > 2) {
            $LastArrayKey = --$CountCUP;
            $ConditionsUP[$LastArrayKey][3] = ')';
        }
        list($SRUPractice, $RRUPractice) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $ConditionsUP, array('k0user_practice'));
        if ($RRUPractice) foreach ($SRUPractice as $VUPractice) {
            $Conditions[0] = array('k0user_practice', '=', $VUPractice['k0user_practice']);
            $Zfpf->delete_sql_1s($DBMSresource, 't0user_practice', $Conditions); // Error checking done by function.
        }
        $Zfpf->close_connection_1s($DBMSresource);
        return $LeadersNeeded; // Errors should lead to user ejection above.
    }

    public function user_i1($Zfpf, $FormActionConfirm, $FormActionBack, $BackInputName = 'not_needed', $Display = FALSE) {
        if (!isset($_SESSION['Selected']['k2username_hash'])) // Field 'k2username_hash' is unique to the t0user table.
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $htmlFormArray = $this->htmlFormArray();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) != '[A new database row is being created.]') { // Temp passwords only for new users.
            unset($htmlFormArray['k2username_hash']);
            unset($htmlFormArray['s5password_hash']);
            unset($htmlFormArray['c5p_global_dbms']); // Except for new users, can only change this via userio03.php > change_global_DBMS_privileges
            unset($htmlFormArray['c5app_admin']); // Except for new users, can only give/remove this privilege via file in App Admin menu of administer1.php
        }
        elseif ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes')
            unset($htmlFormArray['c5app_admin']); // Even for new users, only an app admin can create another app admin.
        if ($_SESSION['Selected']['k0user'] != $_SESSION['t0user']['k0user']) { // Only allow editing one's own challenge questions.
            unset($htmlFormArray['c5challenge_question1']);
            unset($htmlFormArray['s5cq_answer_hash1']);
            unset($htmlFormArray['c5challenge_question2']);
            unset($htmlFormArray['s5cq_answer_hash2']);
            unset($htmlFormArray['c5challenge_question3']);
            unset($htmlFormArray['s5cq_answer_hash3']);
        }
        $_SESSION['Scratch']['htmlFormArray'] = $Zfpf->encode_encrypt_1c($htmlFormArray);
        if (!$Display) { // 1.1 $_SESSION['Selected'] is only source of $Display.
            $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
            $Nothing = '[Nothing has been recorded in this field.]';
            if (isset($Display['s5cq_answer_hash1']) and $Display['s5cq_answer_hash1'] != $Nothing) {
                $Display['s5cq_answer_hash1'] = '[Secret answer recorded, delete this text to change it.]';
            }
            if (isset($Display['s5cq_answer_hash2']) and $Display['s5cq_answer_hash2'] != $Nothing) {
                $Display['s5cq_answer_hash2'] = '[Secret answer recorded, delete this text to change it.]';
            }
            if (isset($Display['s5cq_answer_hash3']) and $Display['s5cq_answer_hash3'] != $Nothing) {
                $Display['s5cq_answer_hash3'] = '[Secret answer recorded, delete this text to change it.]';
            }
            $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($Display);
            $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
        }
        echo $Zfpf->xhtml_contents_header_1c('User Edit').'<h2>
        User Personal Information</h2>
        <form action="'.$FormActionConfirm.'" method="post" >';
        echo $Zfpf->make_html_form_1e($htmlFormArray, $Display);
        echo '<p>
            <input type="submit" name="user_i2" value="Review what you typed into form" /></p>
        </form>
        <form action="'.$FormActionBack.'" method="post"><p>
            <input type="submit" name="'.$BackInputName.'" value="Go back" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    public function user_i2($Zfpf, $FormActionConfirm, $FormActionBack) {
        if (!isset($_SESSION['Selected']['k2username_hash']) or !isset($_SESSION['Scratch']['SelectDisplay']) or !isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        foreach ($htmlFormArray as $K => $V)
            if ($PostDisplay[$K] == '[Secret answer recorded, delete this text to change it.]')
                $PostDisplay[$K] = $LastDisplay[$K];
        if (isset($htmlFormArray['k2username_hash'])) { // This is only set for new users, see above. Means s5password_hash also needed.
            $NewCredentials = $this->username_password_check($Zfpf, 'FALSE', 'k2username_hash', 's5password_hash', FALSE);
            // Not using ConfirmZfpf::post_to_display_1e output because it sent user posts through CoreZfpf::xss_prevent_1c
            $PostDisplay['k2username_hash'] = $NewCredentials['Username'];
            $PostDisplay['s5password_hash'] = $NewCredentials['Password']; // Display the new temporary password to the admin creating it.
        }
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        if (isset($NewCredentials) and $NewCredentials['Message']) {
            echo $Zfpf->xhtml_contents_header_1c('Error').$NewCredentials['Message'].'
            <form action="'.$FormActionBack.'" method="post"><p>
                <input type="submit" name="user_modify_confirm_post_1e" value="Go back" /></p>
            </form>'.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        echo $Zfpf->post_select_required_compare_confirm_1e($FormActionConfirm, $FormActionBack, $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']), 'user_', 'user_');
        $Zfpf->save_and_exit_1c();
    }

    public function user_i3($Zfpf, $Selected = FALSE, $ModifiedValues = FALSE, $Privileges = FALSE) {
        if (
            (!$Selected and !isset($_SESSION['Selected']['k2username_hash'])) or 
            (!$ModifiedValues and !isset($_SESSION['Scratch']['ModifiedValues']))
        )
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        if (!$Selected)
            $Selected = $_SESSION['Selected'];
        if (!$ModifiedValues)
            $ModifiedValues = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['ModifiedValues']);
        $ChangedRow = $Zfpf->changes_from_post_1c($Selected, $ModifiedValues);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s($Privileges);
        if ($Zfpf->decrypt_1c($Selected['c5who_is_editing']) == '[A new database row is being created.]')
            $Zfpf->insert_sql_1s($DBMSresource, 't0user', $ChangedRow);
        else {
            $Conditions[0] = array('k0user', '=', $Selected['k0user']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $Selected[$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        return array('Row' => $Selected, 'Message' => '<p>The user information you input and reviewed has been recorded.</p>');
    }
    
    // This function is called below and directly by setup.php
    public function username_password_html_form($UsernamePostKey = 'new_username', $PasswordPostKey = 'new_password') {
        if (!$UsernamePostKey and !$PasswordPostKey) // Indicates coding error or hacking attempt.
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Message = ''; 
        if ($UsernamePostKey)
            $Message .= '<p>
            <b>PSM-CAP App usernames shall:</b><br />
            - be at least '.USERNAME_MIN_BYTES_ZFPF.' characters long (or bytes long if using multi-byte Asian, Arabic, Russian... characters; they\'ll work but be truncated if greater than 255 bytes)<br />
            New Username: <input type="text" name="'.$UsernamePostKey.'" class="screenwidth" maxlength="'.round(C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /></p>'; 
        if ($PasswordPostKey)
            $Message .= '<p>
            <b>PSM-CAP App passwords shall:</b><br />
            - be at least '.PASSWORD_MIN_BYTES_ZFPF.' characters long (or bytes long if using multi-byte Asian, Arabic, Russian... characters; they\'ll  work but be truncated if greater than 255 bytes) and <br />
            - contain at least '.PASSWORD_MIN_SPECIAL_CHAR_ZFPF.' special character(s), something besides a to Z or 1 to 9, for instance @ or #.<br />
            New Password: <input type="password" name="'.$PasswordPostKey.'" class="screenwidth" maxlength="'.round(C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /><br />
            Confirm New Password: <input type="password" name="confirm_'.$PasswordPostKey.'" class="screenwidth" maxlength="'.round(C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /></p>';
        return $Message;
    }

    public function username_password_check($Zfpf, $BlankFieldOK = TRUE, $UsernamePostKey = 'new_username', $PasswordPostKey = 'new_password', $ConfirmPassword = TRUE, $LoggingOn = FALSE) {
        $Message = '';
        $NewUsername = FALSE;
        $NewPassword = FALSE;
        $NewUsernameHash = FALSE;
        $NewPasswordHashEncrypted = FALSE;
        if ($UsernamePostKey) {
            if (!isset($_POST[$UsernamePostKey]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $NewUsername = $Zfpf->max_length_1c($_POST[$UsernamePostKey]);
            $NewUsernameHash = $Zfpf->hash_1c($NewUsername);
            if ($BlankFieldOK and ($NewUsername === '' or $NewUsername == '[Nothing has been recorded in this field.]'))
                $NewUsernameHash = FALSE;
            elseif (strlen($NewUsername) < USERNAME_MIN_BYTES_ZFPF)
                $Message .= '<p>
                Usernames shall be at least '.USERNAME_MIN_BYTES_ZFPF.' characters long. Your last entry did not follow this rule.</p>';
            elseif ($PasswordPostKey and isset($_POST[$PasswordPostKey]) and $Zfpf->max_length_1c($_POST[$PasswordPostKey]) == $NewUsername)
                $Message .= '<p>
                Usernames and passwords shall be different. Your last entry did not follow this rule.</p>';
            elseif (!$LoggingOn) { // Handles logon.php and setup.php, where no database yet.
                $DBMSresource = $Zfpf->credentials_connect_instance_1s(LOW_PRIVILEGES_ZFPF); // Pass in SELECT for self admin.
                if (isset($_SESSION['Selected']['k0user']) and $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) != '[A new database row is being created.]') {
                    $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
                    list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0user', $Conditions, array('s5password_hash'));
                    if ($RR != 1)
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RR);
                    if (password_verify($NewUsername, $Zfpf->decrypt_1c($SR[0]['s5password_hash']))) // Means the submitted new username is the user's current password.
                        $Message .= '<p>
                        Usernames shall be different current and new passwords. Your last entry did not follow this rule.</p>';
                }
                $Conditions[0] = array('k2username_hash', '=', $NewUsernameHash);
                list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0user', $Conditions);
                if ($RR)
                    $Message .= '<p>
                    Username not available. Please choose a different username.</p>';
                $Zfpf->close_connection_1s($DBMSresource);
            }
        }
        if ($PasswordPostKey) {
            if (!isset($_POST[$PasswordPostKey]) or ($ConfirmPassword and !isset($_POST['confirm_'.$PasswordPostKey])))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $NewPassword = $Zfpf->max_length_1c($_POST[$PasswordPostKey]);
            $NewPasswordHashEncrypted = $Zfpf->encrypt_1c(password_hash($NewPassword, PASSWORD_DEFAULT));
            if ($BlankFieldOK and ($NewPassword === '' or $NewPassword == '[Nothing has been recorded in this field.]'))
                $NewPasswordHashEncrypted = FALSE;
            elseif (strlen($NewPassword) < PASSWORD_MIN_BYTES_ZFPF)
                $Message .= '<p>
                Passwords shall be at least '.PASSWORD_MIN_BYTES_ZFPF.' characters long. Your last entry did not follow this rule.</p>';
            elseif (strlen($NewPassword) - strlen(preg_replace('~[^a-zA-Z0-9]~', '', $NewPassword)) < PASSWORD_MIN_SPECIAL_CHAR_ZFPF)
                $Message .= '<p>
                Passwords shall contain at least '.PASSWORD_MIN_SPECIAL_CHAR_ZFPF.' special character(s), something besides a to Z or 1 to 9, for instance @ or #. Your last entry did not follow this rule.</p>';
            elseif ($ConfirmPassword and $_POST['confirm_'.$PasswordPostKey] != $_POST[$PasswordPostKey])
                $Message .= '<p>
                The password and confirm password you submitted did not match. Please try again.</p>';
            elseif (isset($_SESSION['Selected']['k0user']) and !$LoggingOn and password_verify($NewPassword, $Zfpf->decrypt_1c($_SESSION['Selected']['s5password_hash'])))
                $Message .= '<p>
                New password shall be different from old one. Please try again.</p>';
        }
        if (!$NewUsernameHash and !$NewPasswordHashEncrypted)
            $Message .= '<p>
            You submitted neither a username nor a password. Please try again.</p>';
        if ($Message) {
            $Message = '<h2>Username or password error.</h2>'.$Message;
            $NewUsername = FALSE;
            $NewPassword = FALSE;
            $NewUsernameHash = FALSE;
            $NewPasswordHashEncrypted = FALSE;
        }
        return array(
            'Message' => $Message,
            'Username' => $NewUsername,
            'Password' => $NewPassword,
            'UsernameHash' => $NewUsernameHash,
            'PasswordHashEncrypted' => $NewPasswordHashEncrypted
        );
    }

    public function username_password_i1(
        $Zfpf, 
        $NameTitleEmployerWorkEmail = FALSE,
        $Instructions = '<p><b>To unlock a user\'s account, you must change the password. Leave either blank if you don\'t want it changed.</b></p>',
        $FormActionConfirm = 'username_password_i03.php',
        $ConfirmInputName = 'step_i3',
        $GoBackForm = TRUE,
        $FormActionBack = 'user_io03.php',
        $BackInputName = 'user_o1',
        $UsernamePostKey = 'new_username', // Set to FALSE if to only echo password field
        $PasswordPostKey = 'new_password'  // Set to FALSE if to only echo username field
    ) {
        if (!isset($_SESSION['Selected']['k2username_hash']) or (!$UsernamePostKey and !$PasswordPostKey))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        if (!$NameTitleEmployerWorkEmail)
            $NameTitleEmployerWorkEmail = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user'])['NameTitleEmployerWorkEmail'];
        if (!$UsernamePostKey)
            $Mission = 'Change password'; 
        elseif (!$PasswordPostKey)
            $Mission = 'Change username';
        else // ejected above if (!$UsernamePostKey and !$PasswordPostKey)
            $Mission = 'Change username and/or password';
        $Message = $Zfpf->xhtml_contents_header_1c('Change Logon').'<h2>
        '.$Mission.' for:<br />
        '.$NameTitleEmployerWorkEmail.'</h2>
        '.$Instructions.'
        <form action="'.$FormActionConfirm.'" method="post">
        '.$this->username_password_html_form($UsernamePostKey, $PasswordPostKey).'<p>
            <input type="submit" name="'.$ConfirmInputName.'" value="'.$Mission.'" /></p>
        </form>';
        if ($GoBackForm)
            $Message .= '
            <form action="'.$FormActionBack.'" method="post"><p>
                <input type="submit" name="'.$BackInputName.'" value="Go back" /></p>
            </form>';
        echo $Message.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

// No ...i2, the typical confirmation page, for lots of reasons:
// - cannot safely display the password, so its confirmed by typing it twice on the i1 page,
// - would be hard to safely go back from all the cases arriving from i1, such as
//   administer1.php, logon.php, user_io03.php and the ways of getting to those files.

    public function username_password_i3(
        $Zfpf,
        $Privileges = FALSE,
        $RequirePasswordReset = TRUE,
        $BlankFieldOK = TRUE,
        $FormActionBack = 'user_io03.php',
        $BackInputName = 'user_o1',
        $BackInputValue = 'Go back',
        $UsernamePostKey = 'new_username',
        $PasswordPostKey = 'new_password'
    ) {
        if (!isset($_SESSION['Selected']['k2username_hash']) or (!$UsernamePostKey and !$PasswordPostKey))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $NewCredentials = $this->username_password_check($Zfpf, $BlankFieldOK, $UsernamePostKey, $PasswordPostKey);
        if ($NewCredentials['Message']) {
            echo $Zfpf->xhtml_contents_header_1c('Error').$NewCredentials['Message'].'
            <form action="'.$FormActionBack.'" method="post"><p>
                <input type="submit" name="'.$BackInputName.'" value="'.$BackInputValue.'" /></p>
            </form>'.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        if ($NewCredentials['UsernameHash']) {
            $Changes['k2username_hash'] = $NewCredentials['UsernameHash'];
            if ($_SESSION['Selected']['k0user'] == $_SESSION['t0user']['k0user']) // Updates if user changed their own username.
                $_SESSION['t0user']['k2username_hash'] = $Changes['k2username_hash'];
        }
        $UserUnlockMessage = '';
        if ($NewCredentials['PasswordHashEncrypted']) {
            $Changes['s5password_hash'] = $NewCredentials['PasswordHashEncrypted'];
            if ($RequirePasswordReset) { // See above: "To unlock a user\'s account, you must change the password."
                $UserUnlockMessage = '<p>
                And the selected user\'s account has been unlocked. This user will have to change their password when they next logon.</p>';
                $Changes['c5ts_password'] = $Zfpf->encrypt_1c(0); // This triggers making the user reset their password on the next logon.
            }
            else
                $Changes['c5ts_password'] = $Zfpf->encrypt_1c(time());
            if ($_SESSION['Selected']['k0user'] == $_SESSION['t0user']['k0user']) {  // Updates if user changed their own password.
                $_SESSION['t0user']['s5password_hash'] = $Changes['s5password_hash'];
                $_SESSION['t0user']['c5ts_password'] = $Changes['c5ts_password'];
            }
        }
        $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s($Privileges);
        $ShtmlFormArray = array(
            'k2username_hash' => array('Username', ''),
            's5password_hash' => array('Password', ''),
            'c5ts_password' => array('Time Password Changed', '')
        );
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, TRUE, $ShtmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        $Zfpf->close_connection_1s($DBMSresource);
        return $Zfpf->xhtml_contents_header_1c('Done').'<h2>
        User Logon Credentials Updated</h2>
        '.$UserUnlockMessage.'
        <form action="'.$FormActionBack.'" method="post"><p>
            <input type="submit" name="'.$BackInputName.'" value="'.$BackInputValue.'" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
    }

}

