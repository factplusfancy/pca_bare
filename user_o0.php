<?php
// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file allows user to view their t0user record.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();

if (!$_POST) {
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'user_i1m.php';
    $_SESSION['Selected'] = $_SESSION['t0user'];
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
    $UserZfpf = new UserZfpf;
    $UserZfpf->user_o1($Zfpf, 'contents0.php'); // This function echos and exits.
}

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

