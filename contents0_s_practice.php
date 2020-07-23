<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file sends the user to the practice list (under the selected fragment)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

$Zfpf->clear_edit_lock_1c();
if (isset($_SESSION['SelectResults']))
    unset($_SESSION['SelectResults']);
if (isset($_SESSION['Selected'])) // This handles cases where user has made a selection but not started editing.
    unset($_SESSION['Selected']);
if (isset($_SESSION['Scratch']))
    unset($_SESSION['Scratch']);
if (isset($_SESSION['Post']))
    unset($_SESSION['Post']);
if (isset($_SESSION['t0user_practice']))
    unset($_SESSION['t0user_practice']);
if (isset($_SESSION['StatePicked']['t0practice']) and isset($_SESSION['StatePicked']['t0division'])) {
    unset($_SESSION['StatePicked']['t0practice']);
    if (isset($_SESSION['StatePicked']['t0fragment'])) { // This is case where user selected a rule fragment.
        $DBMSresource = $Zfpf->credentials_connect_instance_1s(LOW_PRIVILEGES_ZFPF); // Connect to DBMS -- needed for require file.
        require INCLUDES_DIRECTORY_PATH_ZFPF . '/fragment_o11.php';
    }
    else // This is case where user went straight from contents to the practices associated with a division.
        require INCLUDES_DIRECTORY_PATH_ZFPF . '/division_o11.php';
}
else
    require INCLUDES_DIRECTORY_PATH_ZFPF . '/contents1.php';

$Zfpf->save_and_exit_1c();

