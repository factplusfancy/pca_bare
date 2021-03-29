<?php
// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file allows an app admin to lookup users and access the user_o1 code.
// See user_io03.php and includes/UserZfpf.php for other user-related code.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

// General security check
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__); // Eject here; extra security for editing user privileges

if (isset($_GET['app_admin']) or isset($_POST['step_i0a'])) {
    if (isset($_SESSION['Selected'])) // Unset $_SESSION['Selected'] in case user arrived from step_i1
        unset($_SESSION['Selected']);
    echo $Zfpf->xhtml_contents_header_1c('Lookup User');
    $Zfpf->lookup_user_1c('user_i0m.php', 'administer1.php', 'step_i0b');
    echo $Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

if (isset($_POST['step_i0b'])) {
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_i1m.php';
    if (isset($_SESSION['Selected']))
        unset($_SESSION['Selected']); // Redundant, be sure user cannot get to step_i1 from here with this set.
    $TableNameUserEntity = FALSE;
    $ConditionsUserEntity = FALSE;
    $Conditions = 'No Condition -- All Rows Included';
    $Zfpf->lookup_user_wrap_2c(
        $TableNameUserEntity,
        $ConditionsUserEntity,
        'user_i0m.php', // $SubmitFile
        'user_i0m.php', // $TryAgainFile
        array('k0user'), // $ColumnsUserEntity
        'administer1.php', // $CancelFile
        '<b>Select user record to view.</b></p>', // $SpecialText
        'Select User', // $SpecialSubmitButton
        'step_i1', //$SubmitButtonName
        'step_i0a', // $TryAgainButtonName
        FALSE, // $CancelButtonName
        FALSE, // $FilterColumnName // Allow app admin to see records of users whose logon credentials have been revoked.
        FALSE, // $Filter
        FALSE, // $FilterColumnName1
        FALSE, // $Filter1
        $Conditions
    ); // This function echos and exits.
}

if (isset($_POST['step_i1'])) {
    // General security check.
    if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_i1m.php')
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (!isset($_POST['Selected'])) { // Check if user forgot to make a selection.
        echo $Zfpf->xhtml_contents_header_1c('Nobody Selected').'<p>
        It appears you did not select a user.</p>
        <form action="user_i0m.php" method="post"><p>
            <input type="submit" name="step_i0a" value="Try Again" /></p>
        </form>
        <form action="administer1.php" method="post"><p>
            <input type="submit" value="Cancel" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    $CheckedPost = $Zfpf->post_length_blank_1c('Selected');
    if (!isset($_SESSION['Scratch']['PlainText']['lookup_user'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Conditions[0] = array('k0user', '=', $_SESSION['Scratch']['PlainText']['lookup_user'][$CheckedPost]);
    unset($_SESSION['Scratch']['PlainText']['lookup_user']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0user', $Conditions);
    if ($RR != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Selected'] = $SR[0];
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
    $UserZfpf = new UserZfpf;    
    $UserZfpf->user_o1($Zfpf, 'administer1.php'); // This function echos and exits.
}

$Zfpf->save_and_exit_1c();

