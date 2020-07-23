<?php
// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file allows an admin to change the username and password (logon credentials) of another user.
// See user_io03.php and includes/UserZfpf.php for other user-related code.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/UserFindZfpf.php';
$Zfpf = new UserFindZfpf;
$Zfpf->session_check_1c();

// General security check
$Authorized = FALSE;
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF) {
    if (isset($_SESSION['StatePicked']['t0owner']) and isset($_SESSION['t0user_owner']) and strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user'])) >= strlen(MID_PRIVILEGES_ZFPF))
        $Authorized = 'Owner';
    elseif (isset($_SESSION['StatePicked']['t0contractor']) and isset($_SESSION['t0user_contractor']) and strlen($Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_user'])) >= strlen(MID_PRIVILEGES_ZFPF))
        $Authorized = 'Contractor';
}
if (!$Authorized and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') // App admins skip use only Steps 1 and 2 here.
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__); // eject here & below; extra security for password changes

if (!$_POST or isset($_POST['step_i0a'])) { // !$_POST means arriving here directly from HTML link in administer1.php
    if (isset($_SESSION['Selected'])) // Unset $_SESSION['Selected'] in case user arrived from step_i1
        unset($_SESSION['Selected']);
    echo $Zfpf->xhtml_contents_header_1c('Lookup User');
    $Zfpf->lookup_user_1c('username_password_i03.php', 'administer1.php', 'step_i0b');
    echo $Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_POST['step_i0b'])) {
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_i1m.php';
    if (isset($_SESSION['Selected']))
        unset($_SESSION['Selected']); // Redundant, be sure user cannot get to step_i1 from here with this set.
    // Limit the t0user SELECT done by UserFind::lookup_user_2c() as possible.
    $Conditions = FALSE;
    if ($Authorized == 'Owner') {
        $_SESSION['Scratch']['k0owner'] = $_SESSION['t0user_owner']['k0owner']; // Determines privileges in UserZfpf::user_o1()
        $TableNameUserEntity = 't0user_owner';
        $ConditionsUserEntity[0] = array('k0owner', '=', $_SESSION['t0user_owner']['k0owner']);
    }
    elseif ($Authorized == 'Contractor') {
        $_SESSION['Scratch']['k0contractor'] = $_SESSION['t0user_contractor']['k0contractor']; // Determines privileges in UserZfpf::user_o1()
        $TableNameUserEntity = 't0user_contractor';
        $ConditionsUserEntity[0] = array('k0contractor', '=', $_SESSION['t0user_contractor']['k0contractor']);
    }
    $Zfpf->lookup_user_wrap_2c(
        $TableNameUserEntity,
        $ConditionsUserEntity,
        'username_password_i03.php', // $SubmitFile
        'username_password_i03.php', // $TryAgainFile
        array('k0user'), // $ColumnsUserEntity
        'administer1.php', // $CancelFile
        '<b>Select user whose username and/or password (logon credentials) you want to change.</b></p>', // $SpecialText
        'Select User', // $SpecialSubmitButton
        'step_i1', //$SubmitButtonName
        'step_i0a', // $TryAgainButtonName
        FALSE, // $CancelButtonName
        'c5ts_logon_revoked', // $FilterColumnName
        '[Nothing has been recorded in this field.]', // $Filter
        FALSE, // $FilterColumnName1
        FALSE, // $Filter1
        $Conditions
    ); // This function echos and exits.
}

if (isset($_POST['step_i1'])) {
    // General security check.
    if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_i1m.php')
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (!isset($_SESSION['Selected']['k2username_hash'])) { // Arriving, via i0a & i0b, from administer1.php; k2username_hash is unique to t0user.
        if (!isset($_POST['Selected'])) { // Check if user forgot to make a selection.
            echo $Zfpf->xhtml_contents_header_1c('Nobody Selected').'<p>
            It appears you did not select a user.</p>
            <form action="username_password_i03.php" method="post"><p>
                <input type="submit" name="step_i0a" value="Try Again" /></p>
            </form>
            <form action="administer1.php" method="post"><p>
                <input type="submit" value="Cancel" /></p>
            </form>'.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        $CheckedPost = $Zfpf->post_length_blank_1c('Selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['Scratch']['PlainText']['lookup_user'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Conditions[0] = array('k0user', '=', $_SESSION['Scratch']['PlainText']['lookup_user'][$CheckedPost]);
        unset($_SESSION['Scratch']['PlainText']['lookup_user']);
        list($SR, $RR) = $Zfpf->one_shot_select_1s('t0user', $Conditions);
        if ($RR != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $SR[0];
    }
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
    $UserZfpf = new UserZfpf;    
    $UserZfpf->username_password_i1($Zfpf); // This function echos and exits. Use defaults for its remaining arguments.
}

// No step_i2, the typical confirmation page, for lots of reasons:
// - cannot safely display the password, so its confirmed by typing it twice on the i1 page,
// - would be hard to safely go back from all the cases arriving from i1, such as
//   administer1.php, logon.php, user_io03.php and the ways of getting to those files.

if (isset($_POST['step_i3'])) {
    // General security check.
    if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_i1m.php')
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
    $UserZfpf = new UserZfpf;
    echo $UserZfpf->username_password_i3($Zfpf);
    $Zfpf->save_and_exit_1c();
}

$Zfpf->catch_all_1c('administer1.php');

$Zfpf->save_and_exit_1c();

