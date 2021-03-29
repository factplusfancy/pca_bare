<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
//
// This PHP file to allows viewing the rule fragments associated with a rule division. 

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF . '/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

$DBMSresource = $Zfpf->credentials_connect_instance_1s(LOW_PRIVILEGES_ZFPF);
require INCLUDES_DIRECTORY_PATH_ZFPF . '/fragment_o11.php';

$Zfpf->save_and_exit_1c();

