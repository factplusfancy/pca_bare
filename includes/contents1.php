<?php
// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file generates the initial page viewed after logon, starting with a password-expiration check.  
// Its output depends on whether the user is associated with one or more owners or contractors, facilities, processes, etc. as follows:
// 1. Check and display all owners and contractors user is associated with, if only one total, display that one, otherwise, allow to choose.
// 2. Same as 1st step with facilities and processes
// 3. Display PSM-CAP contents for that process, with user choice on how to order the contents.

if (
    (PASSWORD_EXPIRATION_ZFPF < time() - $Zfpf->decrypt_1c($_SESSION['t0user']['c5ts_password'])) and
    !isset($_SESSION['Scratch']['PasswordExpired'])
) { // User's password has expired and their not in the process of of changing it.
    if (!isset($UserZfpf)) {
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
        $UserZfpf = new UserZfpf;
    }
    $_SESSION['Scratch']['PasswordExpired'] = $Zfpf->encrypt_1c(TRUE);
    $_SESSION['Selected'] = $_SESSION['t0user'];
    $UserZfpf->username_password_i1(
        $Zfpf, 
        FALSE, // default $NameTitleEmployerWorkEmail 
        '<p><b>Change your password. It expired.</b></p>', // $Instructions
        'contents0.php', // $FormActionConfirm
        'username_password_i3', // $ConfirmInputName
        FALSE, // $GoBackForm
        FALSE, // $FormActionBack
        FALSE, // $BackInputName
        FALSE // $UsernamePostKey -- so won't get option to change username
        // use default $PasswordPostKey = 'new_password'
    );  // This function echos and exits. No back button because user is trying to log on.
}
if (isset($_SESSION['Scratch']['PasswordExpired'])) {
    unset($_SESSION['Scratch']['PasswordExpired']);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
    $UserZfpf = new UserZfpf;
    echo $UserZfpf->username_password_i3(
        $Zfpf,
        'logon maintenance', // $Privileges
        FALSE, // $RequirePasswordReset
        FALSE, // $BlankFieldOK
        'contents0.php', // $FormActionBack
        'not_needed', // $BackInputName
        'Next', // $BackInputValue
        FALSE // $UsernamePostKey
        // use default $PasswordPostKey = 'new_password'
    );
    unset($_SESSION['Selected']);
    $Zfpf->save_and_exit_1c();
}

// Cannot call CoreZfpf::session_cleanup_1c here, need $_SESSION['SelectResults']
// START modified session cleanup.
if (isset($_SESSION['Selected']['c5who_is_editing'])) // Attempt to remove the app's edit-locks
    $Zfpf->clear_edit_lock_1c();
if (isset($_SESSION['Selected']))
    unset($_SESSION['Selected']);
if (isset($_SESSION['Scratch']))
    unset($_SESSION['Scratch']);
if (isset($_SESSION['Post']))
    unset($_SESSION['Post']);
if (isset($_SESSION['SelectResults']['t0fragment']))
    unset($_SESSION['SelectResults']['t0fragment']); // May be set in division_o11.php
if (isset($_SESSION['SelectResults']['t0practice']))
    unset($_SESSION['SelectResults']['t0practice']); // Set in division_o11.php or fragment_o11.php
// The user selecting a contents page is equivalent to requesting to change the division, fragment, and practice.
if (isset($_SESSION['StatePicked']['t0division']))
    unset($_SESSION['StatePicked']['t0division']);
if (isset($_SESSION['StatePicked']['t0fragment']))
    unset($_SESSION['StatePicked']['t0fragment']);
if (isset($_SESSION['StatePicked']['t0practice']))
    unset($_SESSION['StatePicked']['t0practice']);
if (isset($_SESSION['t0user_practice']))
    unset($_SESSION['t0user_practice']);
// END modified session cleanup.

// Check for user input affecting the Contents display.
if (isset($_POST)) {
    if (isset($_POST['selected_owner-contractor']) and isset($_SESSION['SelectResults']['t0user_owner'])) {
        $CheckedPost['selected_owner-contractor'] = $Zfpf->post_length_blank_1c('selected_owner-contractor');
        foreach ($_SESSION['SelectResults']['t0user_owner'] as $K => $V) {
            if ($CheckedPost['selected_owner-contractor'] == $K) {
                $_SESSION['t0user_owner'] = $_SESSION['SelectResults']['t0user_owner'][$K];
                unset($_SESSION['SelectResults']);
            }
        }
    }
    if (isset($_POST['selected_owner-contractor']) and isset($_SESSION['SelectResults']['t0user_contractor'])) {
        $CheckedPost['selected_owner-contractor'] = $Zfpf->post_length_blank_1c('selected_owner-contractor');
        foreach ($_SESSION['SelectResults']['t0user_contractor'] as $K => $V) { // The $K here is the $i assigned below (when generating the radio button)
                                                                                // , so t0user_owner and t0user_contractor numeric arrays don't overlap.
            if ($CheckedPost['selected_owner-contractor'] == $K) {
                $_SESSION['t0user_contractor'] = $_SESSION['SelectResults']['t0user_contractor'][$K];
                unset($_SESSION['SelectResults']);
            }
        }
    }
    if (isset($_POST['selected_owner']) and isset($_SESSION['SelectResults']['t0owner_contractor'])) {
        $CheckedPost['selected_owner'] = $Zfpf->post_length_blank_1c('selected_owner');
        foreach ($_SESSION['SelectResults']['t0owner_contractor'] as $K => $V) {
            if ($CheckedPost['selected_owner'] == $K) {
                $_SESSION['t0owner_contractor'] = $_SESSION['SelectResults']['t0owner_contractor'][$K];
                unset($_SESSION['SelectResults']);
            }
        }
    }
    if (isset($_POST['selected_facility']) and isset($_SESSION['SelectResults']['t0user_facility'])) {
        $CheckedPost['selected_facility'] = $Zfpf->post_length_blank_1c('selected_facility');
        foreach ($_SESSION['SelectResults']['t0user_facility'] as $K => $V) {
            if ($CheckedPost['selected_facility'] == $K) {
                $_SESSION['t0user_facility'] = $_SESSION['SelectResults']['t0user_facility'][$K];
                unset($_SESSION['SelectResults']);
            }
        }
    }
    if (isset($_POST['selected_process']) and isset($_SESSION['SelectResults']['t0user_process'])) {
        $CheckedPost['selected_process'] = $Zfpf->post_length_blank_1c('selected_process');
        foreach ($_SESSION['SelectResults']['t0user_process'] as $K => $V) {
            if ($CheckedPost['selected_process'] == $K) {
                $_SESSION['t0user_process'] = $_SESSION['SelectResults']['t0user_process'][$K];
                unset($_SESSION['SelectResults']);
            }
        }
    }
}

$DBMSresource = $Zfpf->credentials_connect_instance_1s(LOW_PRIVILEGES_ZFPF);
// t0user put in $_SESSION at log-in, so insert user conditions array for subsequent SQL queries here.
$Conditions_k0user[0] = array('k0user', '=', $_SESSION['t0user']['k0user']);
// Get user-owner or user-contractor relationship information.
if (!isset($_SESSION['t0user_owner']) and !isset($_SESSION['t0user_contractor'])) {
    list($SelectResults['t0user_owner'], $RowsReturned['t0user_owner']) = $Zfpf->select_sql_1s($DBMSresource, 't0user_owner', $Conditions_k0user);
    list($SelectResults['t0user_contractor'], $RowsReturned['t0user_contractor']) = $Zfpf->select_sql_1s($DBMSresource, 't0user_contractor', $Conditions_k0user);
    $RowsReturned['Owner+Contractor'] = $RowsReturned['t0user_owner'] + $RowsReturned['t0user_contractor'];
    // Interpret rows returned, drill down, and generate Contents. 
    if ($RowsReturned['Owner+Contractor'] == 1) {
        if ($RowsReturned['t0user_owner'] == 1)
            $_SESSION['t0user_owner'] = $SelectResults['t0user_owner'][0];
        else // This is the case where $RowsReturned['t0user_contractor']  == 1.
            $_SESSION['t0user_contractor'] = $SelectResults['t0user_contractor'][0];
    }
    elseif ($RowsReturned['Owner+Contractor'] > 1) {
        $_SESSION['PlainText']['ChangeSelection']['owners-contractors'] = $RowsReturned['Owner+Contractor'];
        $Message = '<p>Select the entity (owner or contactor) whose records you want to view.</p>
<form action="contents0.php" method="post">
<p>';
        if ($RowsReturned['t0user_owner'] > 0) {
            $_SESSION['SelectResults']['t0user_owner'] = $SelectResults['t0user_owner'];
            foreach ($SelectResults['t0user_owner'] as $K => $V) {
                $Conditions_k0owner[0] = array('k0owner', '=', $SelectResults['t0user_owner'][$K]['k0owner']);
                list($SelectResults['t0owner'], $RowsReturned['t0owner']) = $Zfpf->select_sql_1s($DBMSresource, 't0owner', $Conditions_k0owner);
                if ($RowsReturned['t0owner'] != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned['t0owner']);
                $Message .= '<input type="radio" name="selected_owner-contractor" value="' . $K . '" />' . $Zfpf->decrypt_1c($SelectResults['t0owner'][0]['c5name']) . '<br />';
            }
        }
        if ($RowsReturned['t0user_contractor'] > 0) {
            $i = $RowsReturned['t0user_owner'];
            foreach ($SelectResults['t0user_contractor'] as  $K => $V) {
                $_SESSION['SelectResults']['t0user_contractor'][$i] = $SelectResults['t0user_contractor'][$K];
                $Conditions_k0contractor[0] = array('k0contractor', '=', $SelectResults['t0user_contractor'][$K]['k0contractor']);
                list($SelectResults['t0contractor'], $RowsReturned['t0contractor']) = $Zfpf->select_sql_1s($DBMSresource, 't0contractor', $Conditions_k0contractor);
                if ($RowsReturned['t0contractor'] != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned['t0contractor']);
                $Message .= '<input type="radio" name="selected_owner-contractor" value="' . $i . '" />' . $Zfpf->decrypt_1c($SelectResults['t0contractor'][0]['c5name']) . '<br />';
                ++$i;
            }
            unset($i);
        }
        $Message .= '</p>
<p>
<input type="submit" value="Select" /></p>
</form>';
    }
    // In this and the following case, $RowsReturned['Owner+Contractor'] == 0 so the user is associated with neither an owner nor a contractor.
    // First, this is the "Admin Only" case where the PSM-CAP App administrator is not associated with any owners or contractors.
    elseif ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes')
        $Message = '<p>Records indicate that you are a PSM-CAP App administrator that is not associated with any owners or contractors.  Go to the <a href="administer1.php">Administration</a> page if you want to change this.</p>';
    // Second, this case indicates an error. This PSM-CAP App attempts to avoid it: any user that is not a PSM-CAP App administrator 
    // should be associated with an owner or contractor when the user's record is inserted.
    else
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
}

// Get contractor information, if the user is a contractor's employee.
if (isset($_SESSION['t0user_contractor']) and !isset($_SESSION['StatePicked']['t0contractor'])) {
    $Conditions_k0contractor[0] = array('k0contractor', '=', $_SESSION['t0user_contractor']['k0contractor']);
    list($SelectResults['t0contractor'], $RowsReturned['t0contractor']) = $Zfpf->select_sql_1s($DBMSresource, 't0contractor', $Conditions_k0contractor);
    if ($RowsReturned['t0contractor'] != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned['t0contractor']);
    $_SESSION['StatePicked']['t0contractor'] = $SelectResults['t0contractor'][0];
}

// Get owner information.
if ((isset($_SESSION['t0user_owner']) or isset($_SESSION['t0user_contractor'])) and !isset($_SESSION['StatePicked']['t0owner'])) {
    if (isset($_SESSION['t0user_owner']))
        $Conditions_k0owner[0] = array('k0owner', '=', $_SESSION['t0user_owner']['k0owner']);
    else {
        list($SelectResults['t0owner_contractor'], $RowsReturned['t0owner_contractor']) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions_k0contractor);
        if ($RowsReturned['t0owner_contractor'] == 1) {
            $_SESSION['t0owner_contractor'] = $SelectResults['t0owner_contractor'][0];
            $Conditions_k0owner[0] = array('k0owner', '=', $_SESSION['t0owner_contractor']['k0owner']);        
        }
    }
    if (isset($Conditions_k0owner[0])) {
        list($SelectResults['t0owner'], $RowsReturned['t0owner']) = $Zfpf->select_sql_1s($DBMSresource, 't0owner', $Conditions_k0owner);
        if ($RowsReturned['t0owner'] != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned['t0owner']);
        $_SESSION['StatePicked']['t0owner'] = $SelectResults['t0owner'][0];
    }
    elseif ($RowsReturned['t0owner_contractor'] > 1) {
        // This is common case where a user is associated with a single contractor, but this contractor is associated with more than one owners.
        $_SESSION['PlainText']['ChangeSelection']['owners_only-from-t0owner_contractor'] = $RowsReturned['t0owner_contractor'];
        $Message = '<p>Select the owner whose records you want to view.</p>
<form action="contents0.php" method="post">
<p>';
        $_SESSION['SelectResults']['t0owner_contractor'] = $SelectResults['t0owner_contractor'];
            foreach ($SelectResults['t0owner_contractor'] as $K => $V) {
                $Conditions_k0owner[0] = array('k0owner', '=', $SelectResults['t0owner_contractor'][$K]['k0owner']);
                list($SelectResults['t0owner'], $RowsReturned['t0owner']) = $Zfpf->select_sql_1s($DBMSresource, 't0owner', $Conditions_k0owner);
                if ($RowsReturned['t0owner'] != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned['t0owner']);
                $Message .= '<input type="radio" name="selected_owner" value="' . $K . '" />' . $Zfpf->decrypt_1c($SelectResults['t0owner'][0]['c5name']) . '<br />';
            }
        $Message .= '</p>
<p>
<input type="submit" value="Select" /></p>
</form>';
    }
    elseif ($RowsReturned['t0owner_contractor'] < 1) {
        $ContractorName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']);
        $Message =  '<p>Records indicate that you are a PSM-CAP App user employed by contractor <b>' . $ContractorName . '</b>, but this contractor is not associated with any PSM-facility owners. If you plan to do business with a PSM-facility owner, ask them to associate  ' . $ContractorName . ' with the facilities where you will be working.</p><p>You may go to the <a href="administer1.php">Administration</a> page to update user or contractor information.</p>';
    }
}

// Get user-facility relationship information.
if (isset($_SESSION['StatePicked']['t0owner']) and !isset($_SESSION['t0user_facility'])) {
    // Find facilities both the user and the owner are associated with.
    $Conditions_k0owner[0] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner']); // Need to redefine here due to above if() clauses.  Need to use $_SESSION['StatePicked']['t0owner'] since the use could be an owner or an contractor employee -- either $_SESSION['t0user_owner'] or $_SESSION['t0user_contractor' may be set.
    list($SelectResults['t0owner_facility'], $RowsReturned['t0owner_facility']) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_facility', $Conditions_k0owner);
    if ($RowsReturned['t0owner_facility'] > 0) {
        $Conditions_F[0] = $Conditions_k0user[0];
        $Conditions_F[0][3] = 'AND (';
        foreach ($SelectResults['t0owner_facility'] as $K_F => $V_F) {
            $i = $K_F + 1;
            if ($i == $RowsReturned['t0owner_facility'])
                $Conditions_F[$i] = array('k0facility', '=', $V_F['k0facility'], ')');
            else
                $Conditions_F[$i] = array('k0facility', '=', $V_F['k0facility'], 'OR');
        }
        list($SelectResults['t0user_facility'], $RowsReturned['t0user_facility']) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $Conditions_F);
        if ($RowsReturned['t0user_facility'] < 1) {
            // Echo message about associating users with facilities, if privileges allow.
            $OwnerName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
            $Message = '<p>Records indicate that you are a PSM-CAP App user employed by ';
            if (isset($_SESSION['StatePicked']['t0contractor']))
                $Message .= '<b>' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']) . '</b>, a contractor that may do business with ';
            $Message .= 'owner/operator <b>' . $OwnerName . '</b> but you are not yet associated with any facilities.</p>';
            $Message .= '<p>You may go to <a href="administer1.php">Administration</a> to update your information, or you may contact a ' . $OwnerName . ' PSM-CAP App administrator to have them associate you with the facilities where you are authorized to do work related to PSM processes.</p>';
            if (isset($_SESSION['t0user_owner']) and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_facility']) == MAX_PRIVILEGES_ZFPF)
                $Message .= '<p>You may also insert new facilities or associate facilities with ' . $OwnerName . ' on the <a href="administer1.php">Administration</a> page.</p>';
        }
        elseif ($RowsReturned['t0user_facility'] == 1)
            $_SESSION['t0user_facility'] = $SelectResults['t0user_facility'][0];
        else { // This is case where the user is associated with more than one of an owner's facilities ($RowsReturned['t0user_facility'] > 1, etc.)
            $_SESSION['PlainText']['ChangeSelection']['facilities'] = $RowsReturned['t0user_facility'];
            foreach ($SelectResults['t0user_facility'] as $V)
                $Conditions_k0facility[] = array('k0facility', '=', $V['k0facility'], 'OR');
            // remove the final, hanging, 'OR'.
            $LastArrayKey = $RowsReturned['t0user_facility'] - 1;
            unset($Conditions_k0facility[$LastArrayKey][3]);
            list($SelectResults['t0facility'], $RowsReturned['t0facility']) = $Zfpf->select_sql_1s($DBMSresource, 't0facility', $Conditions_k0facility);
            unset ($Conditions_k0facility); // This variable gets used again later.
            if ($RowsReturned['t0facility'] != $RowsReturned['t0user_facility']) // There should have been no duplicate k0facility SELECTed from the t0owner_facility nor t0user_facility.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            // Sort $SelectResults['t0facility'] by c5name.
            foreach ($SelectResults['t0facility'] as $V)
                $c5name[] = $Zfpf->decrypt_1c($V['c5name']);
            $_SESSION['SelectResults']['t0user_facility'] = $SelectResults['t0user_facility'];
            array_multisort($c5name, $SelectResults['t0facility'], $_SESSION['SelectResults']['t0user_facility']);
            $Message = '<p>
            Select the facility whose records you want to view.</p>
            <form action="contents0.php" method="post"><p>';
            foreach ($SelectResults['t0facility'] as $K => $V)
                $Message .= '
                <input type="radio" name="selected_facility" value="' . $K . '" />' . $Zfpf->decrypt_1c($V['c5name']) . '<br />';
            $Message .= '</p><p>
                <input type="submit" value="Select facility" /></p>
            </form>';
        }
    }
    else {
        // This is the case where $RowsReturned['t0owner_facility'] == 0.
        $OwnerName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        $Message = '<p>Records indicate that you are a PSM-CAP App user employed by ';
        if (isset($_SESSION['StatePicked']['t0contractor']))
            $Message .= '<b>' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']) . '</b>, a contractor that may do business with ';
        $Message .= 'owner/operator <b>' . $OwnerName . '</b></p><p>But, this owner/operator is not yet associated with any facilities.</p>';
        if (isset($_SESSION['t0user_owner'])) {
            if ($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_facility']) == MAX_PRIVILEGES_ZFPF)
                $Message .= '<p>
                Go to the <a href="administer1.php">Administration</a> page to add a new facility to the database and associate it with owner/operator ' . $OwnerName . '</p><p>You will also be able to associate ' . $OwnerName . ' with any existing facilities whose owners have given you the privileges to do so.</p>';
            else
                $Message .= '<p>
                Please contact your supervisor or a ' . $OwnerName . ' PSM-CAP App administrator to resolve this.</p> <p>You may go to <a href="administer1.php">Administration</a> to update your information</p>';
        }
        else
            $Message .= '<p>
            Please contact your supervisor or a ' . $OwnerName . ' PSM-CAP App administrator to resolve this.</p> <p>You may go to <a href="administer1.php">Administration</a> to update your information</p>';
    }
}

// Get facility information.
if (isset($_SESSION['t0user_facility']) and !isset($_SESSION['StatePicked']['t0facility'])) {
    $Conditions_k0facility[0] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility']);
    list($SelectResults['t0facility'], $RowsReturned['t0facility']) = $Zfpf->select_sql_1s($DBMSresource, 't0facility', $Conditions_k0facility);
    if ($RowsReturned['t0facility'] != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned['t0facility']);
    $_SESSION['StatePicked']['t0facility'] = $SelectResults['t0facility'][0];
}

// Get user-process relationship information.
if (isset($_SESSION['StatePicked']['t0facility']) and !isset($_SESSION['t0user_process'])) {
    // Find processes both the user and the facility are associated with.
    $Conditions_k0facility[0] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility']);
    list($SelectResults['t0facility_process'], $RowsReturned['t0facility_process']) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_process', $Conditions_k0facility);
    if ($RowsReturned['t0facility_process'] > 0) {
        $Conditions_P[0] = $Conditions_k0user[0];
        $Conditions_P[0][3] = 'AND (';
        foreach ($SelectResults['t0facility_process'] as $K_P => $V_P) {
            $i = $K_P + 1;
            if ($i == $RowsReturned['t0facility_process'])
                $Conditions_P[$i] = array('k0process', '=', $V_P['k0process'], ')');
            else
                $Conditions_P[$i] = array('k0process', '=', $V_P['k0process'], 'OR');
        }
        list($SelectResults['t0user_process'], $RowsReturned['t0user_process']) = $Zfpf->select_sql_1s($DBMSresource, 't0user_process', $Conditions_P);
        if ($RowsReturned['t0user_process'] < 1) {
            // Echo message about associating users with processes, if privileges allow.
            $Message = '<p>Records indicate that you are a PSM-CAP App user employed by ';
            if (isset($_SESSION['StatePicked']['t0contractor']))
                $Message .= '<b>' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']) . '</b>, a contractor that may do business with ';
            $OwnerName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
            $Message .= 'owner/operator <b>' . $OwnerName . '</b> and associated with the <b>' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']) . '</b> facility.</p><p>But, you are not associated with any PSM processes at this facility.</p>';
            if ($Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_process']) == MAX_PRIVILEGES_ZFPF)
                $Message .= '<p>Go to the <a href="administer1.php">Administration</a> page to insert a new process or to associate yourself with a process, or contact your supervisor for assistance.</p>';
            else
                $Message .= '<p>Please contact your supervisor or a ' . $OwnerName . ' PSM-CAP App administrator to resolve this.</p> <p>You may go to <a href="administer1.php">Administration</a> to update your information</p>';
        }
        elseif ($RowsReturned['t0user_process'] == 1)
            $_SESSION['t0user_process'] = $SelectResults['t0user_process'][0];
        else { // This is case where the user is associated with more than one of an facility's processes ($RowsReturned['t0user_process'] > 1, etc.)
            $_SESSION['PlainText']['ChangeSelection']['processes'] = $RowsReturned['t0user_process'];
            foreach ($SelectResults['t0user_process'] as $V)
                $Conditions_k0process[] = array('k0process', '=', $V['k0process'], 'OR');
            // remove the final, hanging, 'OR'.
            $LastArrayKey = $RowsReturned['t0user_process'] - 1;
            unset($Conditions_k0process[$LastArrayKey][3]);
            list($SelectResults['t0process'], $RowsReturned['t0process']) = $Zfpf->select_sql_1s($DBMSresource, 't0process', $Conditions_k0process);
            unset ($Conditions_k0process); // This variable gets used again later.
            if ($RowsReturned['t0process'] != $RowsReturned['t0user_process']) // There should have been no duplicate k0process SELECTed from the t0facility_process nor t0user_process.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            // Sort $SelectResults['t0process'] by c5name.
            foreach ($SelectResults['t0process'] as $V)
                $c5name[] = $Zfpf->decrypt_1c($V['c5name']); 
            $_SESSION['SelectResults']['t0user_process'] = $SelectResults['t0user_process'];
            array_multisort($c5name, $SelectResults['t0process'], $_SESSION['SelectResults']['t0user_process']);
            $Message = '<p>
            Select the process whose records you want to view.</p>
            <form action="contents0.php" method="post"><p>';
            foreach ($SelectResults['t0process'] as $K => $V)
                $Message .= '
                <input type="radio" name="selected_process" value="' . $K . '" />' . $Zfpf->decrypt_1c($V['c5name']) . '<br />';
            $Message .= '</p><p>
                <input type="submit" value="Select process" /></p>
            </form>';
        }
    }
    else {
        // This is the case where $RowsReturned['t0facility_process'] == 0.
        $Message = '<p>Records indicate that you are a PSM-CAP App user employed by ';
        if (isset($_SESSION['StatePicked']['t0contractor']))
            $Message .= '<b>' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']) . '</b>, a contractor that may do business with ';
        $OwnerName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        $Message .= 'owner/operator <b>' . $OwnerName . '</b> and associated with the <b>' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']) . '</b> facility.</p><p>But, no processes are associated with this facility.</p>';
        if ($Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_process']) == MAX_PRIVILEGES_ZFPF)
            $Message .= '<p>Go to the <a href="administer1.php">Administration</a> page to add a new process to the database and associate it with this facility.</p>';
        else
            $Message .= '<p>Please contact your supervisor or a ' . $OwnerName . ' PSM-CAP App administrator to resolve this.</p> <p>You may go to <a href="administer1.php">Administration</a> to update your information</p>';
    }
}

// Get process info.
if (isset($_SESSION['t0user_process']) and !isset($_SESSION['StatePicked']['t0process'])) {
    $Conditions_k0process[0] = array('k0process', '=', $_SESSION['t0user_process']['k0process']);
    list($SelectResults['t0process'], $RowsReturned['t0process']) = $Zfpf->select_sql_1s($DBMSresource, 't0process', $Conditions_k0process);
    if ($RowsReturned['t0process'] != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned['t0process']);
    $_SESSION['StatePicked']['t0process'] = $SelectResults['t0process'][0];
}

// call $Zfpf->xhtml_contents_header_1c() here to display rule-divsion options in the left-hand contents
// before running if (isset($_SESSION['StatePicked']['t0process'])) below.
// $_SESSION['SelectResults_t0rule_contents1_temp'] is a backdoor way to return data from function xhtml_contents_header_1c(), which allows radio button in the left-hand contents.
echo $Zfpf->xhtml_contents_header_1c('Contents', TRUE, 'DefaultFixedLeftContents', $DBMSresource, FALSE);
// Display rule divisions, such as PSM Elements.
if (isset($_SESSION['StatePicked']['t0process'])) {
    if (!isset($_SESSION['StatePicked']['t0rule']) or isset($_POST['rule'])) {
        if (isset($_POST['rule']))
            $Order = $Zfpf->post_length_blank_1c('rule');
        else // Default to Cheesehead order (a blend of the OSHA PSM and EPA CAP rule orders based on Wisconsin refrigeration experiences.)
            $Order = 0; // 0 is the Cheesehead order.
        $_SESSION['StatePicked']['t0rule'] = $_SESSION['SelectResults_t0rule_contents1_temp'][$Order];
        unset ($_SESSION['SelectResults_t0rule_contents1_temp']);
    }
    $Conditions_k0rule[0] = array('k0rule', '=', $_SESSION['StatePicked']['t0rule']['k0rule']);
    list($_SESSION['SelectResults']['t0division'], $RowsReturned['t0division']) = $Zfpf->select_sql_1s($DBMSresource, 't0division', $Conditions_k0rule);
    if ($RowsReturned['t0division'] > 0) {
        $Message = '
        <form action="division_o1.php" method="post"><p><b>' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0rule']['c5name']) . ':</b><br />';
        // Sort $_SESSION['SelectResults']['t0division'] by k0division. MySQL sorts by primary key automatically, but other database-management systems (DBMS) may not. This assumes t0division rows are ordered corectly by k0division, which, for the template rule divisions, is accomplished via setup.php. 
        foreach ($_SESSION['SelectResults']['t0division'] as $V)
            $k0division[] = $V['k0division'];
        array_multisort($k0division, $_SESSION['SelectResults']['t0division']);
        foreach ($_SESSION['SelectResults']['t0division'] as $K => $V) {
            $Message .= '
            <input type="radio" name="selected_division" value="' . $K . '" ';
            if ($K == 0) // Select the first division by default to ensure something is posted (unless a hacker is tampering).
                $Message .= 'checked="checked" ';
            $Message .= '/>' . $Zfpf->decrypt_1c($V['c5name']);
            if (isset($V['c5citation']) and $Zfpf->decrypt_1c($V['c5citation']) != '[Nothing has been recorded in this field.]')
                $Message .= ' (' . $Zfpf->decrypt_1c($V['c5citation']) . ')';
            $Message .= '<br />';
        }
        $Message .= '<b>
        View:</b><br />
            <input type="radio" name="selected_view" value="not_used" checked="checked" />Compliance practices<br />
            <input type="radio" name="selected_view" value="rule_fragments" />Rule fragments<br />
            <input type="submit" value="Select division and view" /></p>
        </form>';
    }
    else
        $Message = '<p>Divisions for the rule (or other classification) you selected have not been entered into the database. Normally these are included in the database at installation. Contact your supervisor or a PSM-CAP App administrator for assistance with getting any needed content into the database.</p>';
}
$Zfpf->close_connection_1s($DBMSresource);

// TO DO Consider displaying any open action register entries for which the user is responsible.

// echo '<h1>
// PSM-CAP App</h1>'; // Commented out when general header changed to PSM-CAP App
echo '<h2>
Contents</h2>';
if (isset($Message))
    echo $Message;
echo $Zfpf->xhtml_footer_1c();
$Zfpf->save_and_exit_1c();

