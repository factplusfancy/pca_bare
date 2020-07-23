<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i0m file requires an i1m file, after the session check,
// to allow calling via a link in administer1.php. Also called by user_contractor_io03.php

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();
if (isset($_SESSION['Scratch']['NewUserNameEmployer'])) // Set in user_contractor_io03.php for creating new users.
    $Zfpf->session_cleanup_1c();

if (isset($_POST['LogonRevokedUsersOnly']))
    $_SESSION['Scratch']['PlainText']['LogonRevokedUsersOnly'] = TRUE;

require INCLUDES_DIRECTORY_PATH_ZFPF . '/user_contractor_i1m.php';

$Zfpf->save_and_exit_1c();

