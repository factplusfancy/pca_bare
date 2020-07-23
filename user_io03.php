<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the user input and output HTML forms, except the:
//  - i0m and i1m files for listing existing records (and giving the option to start a new record)
//  - SPECIAL CASE: this file calls the i3 code in the UserZfpf.php 

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

if (isset($_SESSION['Scratch']['PlainText']['SecurityToken']) and isset($_POST['user_o1']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_i1m.php') {
    // SPECIAL CASES, user may arrive by clicking button named user_o1 output by o1 code of :
    //  contractor_priv_io03.php, 
    //  user_contractor_io03.php (having come from either contractor_priv_io03.php or user_contractor_i0m.php), or
    //  user_owner_io03.php
    if ('contractor_priv_i1m.php' == $_SESSION['Scratch']['PlainText']['SecurityToken'] and isset($_SESSION['StatePicked']['t0facility']) and isset($_SESSION['Scratch']['t0user_facility']) and isset($_SESSION['Scratch']['k0contractor'])) {
        if (!isset($_SESSION['Scratch']['GoBackFormAction']))
            $_SESSION['Scratch']['GoBackFormAction'] = $Zfpf->encrypt_1c('practice_o1.php');
        $Conditions[0] = array('k0user', '=', $_SESSION['Scratch']['t0user_facility']['k0user']);
        unset($_SESSION['Scratch']['t0user_facility']);
    }
    elseif ('user_contractor_i1m.php' == $_SESSION['Scratch']['PlainText']['SecurityToken'] and isset($_SESSION['Selected']['k0user_contractor'])) {
        if (!isset($_SESSION['Scratch']['GoBackFormAction'])) // If set in contractor_priv to user_contractor to here case, don't want to update GoBack here.
            $_SESSION['Scratch']['GoBackFormAction'] = $Zfpf->encrypt_1c('user_contractor_i0m.php');
        $_SESSION['Scratch']['k0contractor'] = $_SESSION['Selected']['k0contractor'];
        $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
    }
    elseif ('user_owner_i1m.php' == $_SESSION['Scratch']['PlainText']['SecurityToken'] and isset($_SESSION['Selected']['k0user_owner'])) {
        if (!isset($_SESSION['Scratch']['GoBackFormAction']))
            $_SESSION['Scratch']['GoBackFormAction'] = $Zfpf->encrypt_1c('user_owner_i0m.php');
        $_SESSION['Scratch']['k0owner'] = $_SESSION['Selected']['k0owner'];
        $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
    }
    else
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0user', $Conditions);
    if ($RR != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RR);
    $_SESSION['Selected'] = $SR[0];
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_i1m.php';
}

// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

if (isset($_SESSION['Scratch']['GoBackFormAction'])) // Handle users who got here from contractor_priv_io03.php or user_contractor_io03.php
    $FormActionBack = $Zfpf->decrypt_1c($_SESSION['Scratch']['GoBackFormAction']);
else
    $FormActionBack = 'administer1.php'; // Otherwise assume user got here from administer1.php

if (isset($_POST['user_history_o1'])) {
    $HistoryCredentials = FALSE;
    if (isset($_SESSION['Scratch']['k0owner']) and isset($_SESSION['t0user_owner']) and $_SESSION['Scratch']['k0owner'] == $_SESSION['t0user_owner']['k0owner'] and strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_user'])) >= strlen(MID_PRIVILEGES_ZFPF))
        $HistoryCredentials = TRUE;
    elseif (isset($_SESSION['Scratch']['k0contractor']) and isset($_SESSION['t0user_contractor']) and $_SESSION['Scratch']['k0contractor'] == $_SESSION['t0user_contractor']['k0contractor'] and strlen($Zfpf->decrypt_1c($_SESSION['t0user_contractor']['c5p_user'])) >= strlen(MID_PRIVILEGES_ZFPF))
        $HistoryCredentials = TRUE;
    elseif ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes')
        $HistoryCredentials = TRUE;
    if (!isset($_SESSION['Selected']['k0user']) or (!$HistoryCredentials and $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user']))
        $Zfpf->send_to_contents_1c(); // Don't eject
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0user', $_SESSION['Selected']['k0user']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'User-History Records', 'user_io03.php', 'user_o1'); // This echos and exits.
}
// Cases below need UserZfpf
require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
$UserZfpf = new UserZfpf;
if (isset($_POST['user_o1']))
    $UserZfpf->user_o1($Zfpf, $FormActionBack); // This echos and exits.
if (isset($_POST['change_global_dbms_priv_1']))
    $UserZfpf->change_global_dbms_priv_1($Zfpf); // This echos and exits.
if (isset($_POST['change_global_dbms_priv_2']))
    $UserZfpf->change_global_dbms_priv_2($Zfpf); // This echos and exits.
if (isset($_POST['revoke_logon_1']))
    $UserZfpf->revoke_logon_1($Zfpf); // This echos and exits.
if (isset($_POST['revoke_logon_2']))
    $UserZfpf->revoke_logon_2($Zfpf); // This echos and exits.
if (isset($_POST['restore_logon_1']))
    $UserZfpf->restore_logon_1($Zfpf); // This echos and exits.
if (isset($_POST['restore_logon_2']))
    $UserZfpf->restore_logon_2($Zfpf); // This echos and exits.
if (isset($_POST['user_i0n']))
    $UserZfpf->user_i0n($Zfpf, 'user_io03.php', $FormActionBack); // This echos and exits.
if (isset($_POST['user_o1_from'])) {
    $Zfpf->edit_lock_1c('user'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error.
    $UserZfpf->user_i1($Zfpf, 'user_io03.php', 'user_io03.php', 'user_o1'); // This echos and exits.
}
if (isset($_POST['user_undo_confirm_post_1e']) or isset($_POST['user_modify_confirm_post_1e'])) {
    if (!isset($_SESSION['Selected']['k2username_hash']) or !isset($_SESSION['Scratch']['SelectDisplay']) or !isset($_SESSION['Post']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['user_undo_confirm_post_1e'])) {
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
    }
    else
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
    $UserZfpf->user_i1($Zfpf, 'user_io03.php', 'user_io03.php', 'user_o1', $Display); // This echos and exits.
}
if (isset($_POST['user_i2']))
    $UserZfpf->user_i2($Zfpf, 'user_io03.php', 'user_io03.php'); // This echos and exits.
if (isset($_POST['user_yes_confirm_post_1e'])) { // set by user_i2. Indicates run the user_i3 code
    if (!isset($_SESSION['Selected']['k2username_hash']) or !isset($_SESSION['Post']) or !isset($_SESSION['Scratch']['ModifiedValues']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Privileges = FALSE;
    if ($_SESSION['Selected']['k0user'] == $_SESSION['t0user']['k0user'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF) //  User self-editing their user record.
        $Privileges = 'logon maintenance';
    $Finished = $UserZfpf->user_i3($Zfpf, FALSE, FALSE, $Privileges);
    $_SESSION['Selected'] = $Finished['Row'];
    if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user'])
        $_SESSION['t0user'] = $_SESSION['Selected'];
    unset($_SESSION['Post']);
    echo $Zfpf->xhtml_contents_header_1c('User Updated').'<h2>
    User Record Updated</h2>
    '.$Finished['Message'].'
    <form action="user_io03.php" method="post"><p>
        <input type="submit" name="user_o1" value="Back to Record" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}


// grant_app_admin and revoke_app_admin. Put here because no other files need to call nor reach here.
// app_admin security check
$AppAdmin = FALSE;
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes')
    $AppAdmin = TRUE;
if (!$AppAdmin or !isset($_SESSION['Selected']['c5app_admin']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);  // Eject here & below; higher security for app admin changes.
$SelectedUserIsAppAdmin = FALSE;
if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5app_admin']) == 'Yes')
    $SelectedUserIsAppAdmin = TRUE;
if (isset($_POST['grant_app_admin_1'])) {
    // Additional security check.
    if ($SelectedUserIsAppAdmin or $Zfpf->decrypt_1c($_SESSION['Selected']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Grant app-admin privileges</h2><p>
    Grant app-admin privileges on the PSM-CAP app for:<br />
    <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p>
    <form action="user_io03.php" method="post"><p>
        <input type="submit" name="grant_app_admin_2" value="Grant app-admin privileges" /></p><p>
        <input type="submit" name="user_o1" value="Take no action -- go back" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
if (isset($_POST['grant_app_admin_2'])) {
    // Additional security check.
    if ($SelectedUserIsAppAdmin or $Zfpf->decrypt_1c($_SESSION['Selected']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Changes['c5app_admin'] = $Zfpf->encrypt_1c('Yes');
    $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
    $htmlFormArrayShort = array(
        'c5app_admin' => array('PSM-CAP application administrative privileges (app-admin privileges)', '')
    );
    $Affected = $Zfpf->one_shot_update_1s('t0user', $Changes, $Conditions, TRUE, $htmlFormArrayShort);
    if ($Affected != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
    $_SESSION['Selected']['c5app_admin'] = $Changes['c5app_admin'];
    $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Confirmation</h2><p>
    App-admin privileges granted to:<br />
    <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p>
    <form action="user_io03.php" method="post"><p>
        <input type="submit" name="user_o1" value="Back to User Record" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
if (isset($_POST['revoke_app_admin_1'])) {
    // Additional security check.
    if (!$SelectedUserIsAppAdmin)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Revoke app-admin privileges</h2><p>
    Revoke these privileges on the PSM-CAP app for:<br />
    <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p>
    <form action="user_io03.php" method="post"><p>
        <input type="submit" name="revoke_app_admin_2" value="Revoke app-admin privileges" /></p><p>
        <input type="submit" name="user_o1" value="Take no action -- go back" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
if (isset($_POST['revoke_app_admin_2'])) {
    // Additional security check.
    if (!$SelectedUserIsAppAdmin)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Changes['c5app_admin'] = $Zfpf->encrypt_1c('No');
    $Conditions[0] = array('k0user', '=', $_SESSION['Selected']['k0user']);
    $htmlFormArrayShort = array(
        'c5app_admin' => array('PSM-CAP application administrative privileges (app-admin privileges)', '')
    );
    $Affected = $Zfpf->one_shot_update_1s('t0user', $Changes, $Conditions, TRUE, $htmlFormArrayShort);
    if ($Affected != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
    $_SESSION['Selected']['c5app_admin'] = $Changes['c5app_admin'];
    $SelectedUser = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user']);
    echo $Zfpf->xhtml_contents_header_1c().'<h2>
    Confirmation</h2><p>
    App-admin privileges revoked from:<br />
    <b>'.$SelectedUser['NameTitleEmployerWorkEmail'].'</b></p>
    <form action="user_io03.php" method="post"><p>
        <input type="submit" name="user_o1" value="Back to User Record" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

$Zfpf->catch_all_1c($FormActionBack);

$Zfpf->save_and_exit_1c();

