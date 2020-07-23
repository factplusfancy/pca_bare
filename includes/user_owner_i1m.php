<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check. SPECIAL CASE set security token here, because called via administer1.php link to user_owner_i0m.php.
if (isset($_SESSION['StatePicked']['t0owner']) and isset($_SESSION['t0user_owner'])) // Same privileges needed to get user_owner_i0m.php link from administer1.php.
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_owner_i1m.php';
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_owner_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// In case called from o1_code "Go Back" button:
if (isset($_SESSION['Selected']))
    unset($_SESSION['Selected']);

// Get useful variables
$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);
$UserOwnerPrivilegesStrlen = strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user']));

$Conditions[0] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner']);
list($_SESSION['SelectResults']['t0user_owner'], $RR['t0user_owner']) = $Zfpf->one_shot_select_1s('t0user_owner', $Conditions);
if ($RR['t0user_owner'] > 0) foreach ($_SESSION['SelectResults']['t0user_owner'] as $K => $V) {
    $UserJobInfo = $Zfpf->user_job_info_1c($V['k0user']);
    if (isset($_SESSION['Scratch']['PlainText']['LogonRevokedUsersOnly'])) {
        if ($UserJobInfo['TimeLogonRevoked'] == '[Nothing has been recorded in this field.]')
            unset($_SESSION['SelectResults']['t0user_owner'][$K]);
        else {
            $DisplayUserInfo[$K] = $UserJobInfo['NameTitle'].', '.$UserJobInfo['WorkEmail'];
            $SortUserInfo[$K] = $Zfpf->to_lower_case_1c($DisplayUserInfo[$K]);
        }
    }
    else {
        if ($UserJobInfo['TimeLogonRevoked'] != '[Nothing has been recorded in this field.]')
            unset($_SESSION['SelectResults']['t0user_owner'][$K]);
        else {
            $DisplayUserInfo[$K] = $UserJobInfo['NameTitle'].', '.$UserJobInfo['WorkEmail'];
            $SortUserInfo[$K] = $Zfpf->to_lower_case_1c($DisplayUserInfo[$K]);
        }
    }
}
if (isset($_SESSION['Scratch']['PlainText']['LogonRevokedUsersOnly']))
    $Message = '<h2>
    User-owner records with revoked logon credentials</h2>';
else
    $Message = '<h2>
    User-owner records with active logon credentials</h2>';
if (isset($SortUserInfo)) {
    array_multisort($SortUserInfo, $DisplayUserInfo, $_SESSION['SelectResults']['t0user_owner']);
    $Message .= '
    <form action="user_owner_io03.php" method="post"><p>';
    foreach ($_SESSION['SelectResults']['t0user_owner'] as $K => $V) {
        if ($K)
            $Message .= '<br />';
        $Message .= '
        <input type="radio" name="selected" value="'.$K.'" ';
        if (!$K)
            $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
        $Message .= '/>'.$DisplayUserInfo[$K];
    }
    $Message .= '</p><p>
        <input type="submit" name="user_owner_o1" value="View user-owner record" /></p>
    </form>';
}
else
    $Message .= '<p>
    <b>None found</b> for the current owner. Please contact your supervisor if this seems amiss.</p>';
if (!isset($_SESSION['Scratch']['PlainText']['LogonRevokedUsersOnly'])) {
    if ($UserOwnerPrivilegesStrlen >= strlen(MID_PRIVILEGES_ZFPF) and $UserGlobalDBMSPrivileges != LOW_PRIVILEGES_ZFPF) // See 0read_me_psm_cap_app_standards.txt
        $Message .= '
        <form action="user_owner_io03.php" method="post"><p>
            New user and user-owner records.<br />
            <input type="submit" name="user_i0n" value="Insert new-user records" /></p>
        </form>';
    else {
        if ($UserOwnerPrivilegesStrlen < strlen(MID_PRIVILEGES_ZFPF))
            $Message .= '<p><b>
            User-Owner Privileges Notice</b>: You lack privileges to insert a new user record associated with the currently selected owner.</p>';
        if ($UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF)
            $Message .= '<p><b>
            Global Privileges Notice</b>: You have privileges to neither insert new nor update PSM-CAP App records.</p>';
        $Message .= '<p>
        If you need additional privileges, please contact your supervisor or a PSM-CAP App administrator.</p>';
    }
    $Message .= '
    <form action="user_owner_i0m.php" method="post"><p>
        <input type="submit" name="LogonRevokedUsersOnly" value="Revoked logon-credentials users" /></p>
    </form>';
}
else {
    unset($_SESSION['Scratch']['PlainText']['LogonRevokedUsersOnly']);
    $Message .= '
    <form action="user_owner_i0m.php" method="post"><p>
        <input type="submit" value="Active logon-credentials users" /></p>
    </form>';
}
echo $Zfpf->xhtml_contents_header_1c().'<h1>
'.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).'</h1>
'.$Message.'
<form action="administer1.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

