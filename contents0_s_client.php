<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file sends the user to the web-app table of contents 

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

unset($_SESSION['PlainText']['ChangeSelection']['owners_only-from-t0owner_contractor']);
if (isset($_SESSION['t0owner_contractor']))
    unset($_SESSION['t0owner_contractor']);
if (isset($_SESSION['StatePicked']['t0owner']))
    unset($_SESSION['StatePicked']['t0owner']);
if (isset($_SESSION['PlainText']['ChangeSelection']['facilities']))
    unset($_SESSION['PlainText']['ChangeSelection']['facilities']);
if (isset($_SESSION['t0user_facility']))
    unset($_SESSION['t0user_facility']);
if (isset($_SESSION['StatePicked']['t0facility']))
    unset($_SESSION['StatePicked']['t0facility']);
if (isset($_SESSION['PlainText']['ChangeSelection']['processes']))
    unset($_SESSION['PlainText']['ChangeSelection']['processes']);
if (isset($_SESSION['t0user_process']))
    unset($_SESSION['t0user_process']);
if (isset($_SESSION['StatePicked']['t0process']))
    unset($_SESSION['StatePicked']['t0process']);
if (isset($_SESSION['StatePicked']['t0rule']))
    unset($_SESSION['StatePicked']['t0rule']);
if (isset($_SESSION['StatePicked']['t0division']))
    unset($_SESSION['StatePicked']['t0division']);
if (isset($_SESSION['StatePicked']['t0fragment']))
    unset($_SESSION['StatePicked']['t0fragment']);
if (isset($_SESSION['StatePicked']['t0practice']))
    unset($_SESSION['StatePicked']['t0practice']);
if (isset($_SESSION['SelectResults']))
    unset($_SESSION['SelectResults']);
require INCLUDES_DIRECTORY_PATH_ZFPF . '/contents1.php'; // This unsets $_SESSION['Scratch']

$Zfpf->save_and_exit_1c();

