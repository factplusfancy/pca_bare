<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file sends the user to the web-app table of contents 

// It is identical to contents0_s_rule.php

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

// This cleans up the left hand table of contents on the main menu. See CoreZfpf.php.
$_SESSION['PlainText']['suppress_rule_o1_contents1_temp'] = 1;

if (isset($_SESSION['StatePicked']['t0division']))
    unset($_SESSION['StatePicked']['t0division']);
if (isset($_SESSION['StatePicked']['t0fragment']))
    unset($_SESSION['StatePicked']['t0fragment']);
if (isset($_SESSION['StatePicked']['t0practice']))
    unset($_SESSION['StatePicked']['t0practice']);

require INCLUDES_DIRECTORY_PATH_ZFPF . '/contents1.php';

$Zfpf->save_and_exit_1c();

