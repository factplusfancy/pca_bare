<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License. 

// This file handles all the input and output HTML forms, except the:
//  - SPECIAL CASE: no i1m file.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
$ccsaZfpf = new ccsaZfpf;

// i3 code
if (isset($_POST['yes_confirm_post_1e'])) {
    // General security check
    // SPECIAL CASE: $_SESSION['Scratch']['t0cause'] serves like $_SESSION['Selected']. $_SESSION['Selected'] keeps holding a t0pha row.
    // SPECIAL CASE: $_SESSION['Scratch']['t0subprocess'] and $_SESSION['Scratch']['t0scenario'] keep holding their selected rows.
    if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'pha_i1m.php' or !isset($_SESSION['Selected']['k0pha']) or !isset($_SESSION['Scratch']['t0subprocess']['k0subprocess']) or !isset($_SESSION['Scratch']['t0scenario']['k0scenario']) or !isset($_SESSION['Scratch']['t0cause']['k0cause']) or !isset($_SESSION['Post']) or !isset($_SESSION['t0user_practice']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if ($_SESSION['Selected']['k0pha'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes')
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    echo $ccsaZfpf->ccsa_edit('cause', $Zfpf);
    $Zfpf->save_and_exit_1c();
}

// o1, history_o1, _i1aic, i1, and i2 code
// General security check.
// SPECIAL CASE the security token remains 'pha_i1m.php' for subprocess and scenario... PHP files. $_SESSION['Selected']... shall also be set.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'pha_i1m.php' or !isset($_SESSION['Selected']['k0pha']) or !isset($_SESSION['Scratch']['t0subprocess']) or !isset($_SESSION['Scratch']['t0scenario']) or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
if (!isset($_POST['cause_o1']) and !isset($_POST['cause_history_o1']) and $_SESSION['Selected']['k0pha'] < 100000 and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes')
    $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
$htmlFormArray = $ccsaZfpf->html_form_array('cause', $Zfpf);
$ccsaZfpf->ccsa_io0_2('cause', $Zfpf, $htmlFormArray);
$Zfpf->save_and_exit_1c();

