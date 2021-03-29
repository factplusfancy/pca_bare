<?php
// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file allows user to change their own username and password (logon credentials)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

if (!$_POST or isset($_POST['username_password_i1'])) {
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_i1m.php';
    $_SESSION['Selected'] = $_SESSION['t0user'];
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
    $UserZfpf = new UserZfpf;
    $UserZfpf->username_password_i1(
        $Zfpf, 
        $Zfpf->current_user_info_1c()['NameTitleEmployerWorkEmail'], // $NameTitleEmployerWorkEmail
        '<p><b>Leave either blank if you don\'t want it changed.</b></p>', // $Instructions
        'username_password_i13bu.php', // $FormActionConfirm -- can use this default.
        $ConfirmInputName = 'step_i3', 
        $GoBackForm = TRUE, 
        'administer1.php', //$FormActionBack -- send user back where they came from.
        '' // $BackInputName
    ); // This function echos and exits.
}

// No step_i2, for reasons see username_password_i03.php

if (isset($_POST['step_i3'])) {
    // General security check.
    if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'user_i1m.php')
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
    $UserZfpf = new UserZfpf;
    echo $UserZfpf->username_password_i3($Zfpf, 'logon maintenance', FALSE);
    $Zfpf->save_and_exit_1c();
}

$Zfpf->catch_all_1c('administer1.php');

$Zfpf->save_and_exit_1c();

