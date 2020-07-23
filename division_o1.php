<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
//
// This PHP file to allows viewing the rule fragments associated with a rule division.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

// Cannot call CoreZfpf::session_cleanup_1c here, need $_SESSION['SelectResults']
// START modified session cleanup.
if (isset($_SESSION['Selected']))
    unset($_SESSION['Selected']);
if (isset($_SESSION['Scratch']))
    unset($_SESSION['Scratch']);
if (isset($_SESSION['Post']))
    unset($_SESSION['Post']);
if (isset($_SESSION['SelectResults']['t0fragment']))
    unset($_SESSION['SelectResults']['t0fragment']); // May be set in division_o11.php
if (isset($_SESSION['SelectResults']['t0practice']))
    unset($_SESSION['SelectResults']['t0practice']); // Set in division_o11.php or fragment_o11.php
// The user selecting a contents page is equivalent to requesting to change the division, fragment, and practice.
if (isset($_SESSION['StatePicked']['t0division']))
    unset($_SESSION['StatePicked']['t0division']);
if (isset($_SESSION['StatePicked']['t0fragment']))
    unset($_SESSION['StatePicked']['t0fragment']);
if (isset($_SESSION['StatePicked']['t0practice']))
    unset($_SESSION['StatePicked']['t0practice']);
if (isset($_SESSION['t0user_practice']))
    unset($_SESSION['t0user_practice']);
// END modified session cleanup.

// Security check.
if (!isset($_SESSION['StatePicked']['t0rule']) or (!isset($_POST['selected_division']) and !isset($_SESSION['StatePicked']['t0division'])))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

require INCLUDES_DIRECTORY_PATH_ZFPF . '/division_o11.php';

$Zfpf->save_and_exit_1c();

