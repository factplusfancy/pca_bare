<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file sends the user to the fragment list (under the selected division).

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

$Zfpf->clear_edit_lock_1c();
if (isset($_SESSION['SelectResults']))
    unset($_SESSION['SelectResults']);
if (isset($_SESSION['StatePicked']['t0practice']))
    unset($_SESSION['StatePicked']['t0practice']);
if (isset($_SESSION['t0user_practice']))
    unset($_SESSION['t0user_practice']);
// This handles cases where user has made a selection but not started editing.
if (isset($_SESSION['Selected']))
    unset($_SESSION['Selected']);
if (isset($_SESSION['Scratch']))
    unset($_SESSION['Scratch']);
if (isset($_SESSION['Post']))
    unset($_SESSION['Post']);
    
require INCLUDES_DIRECTORY_PATH_ZFPF . '/division_o11.php';

$Zfpf->save_and_exit_1c();

