<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file has a case for each PHP file in app directory, where front_controller.php is located, retrieved by __DIR__ below
// but not its sub-directories and also not:
//    directory_path_settings.php

// Uncomment the code below if using a front controller architecture.

/*
switch (@parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    case '__DIR__/action_io03.php':
        require 'action_io03.php';
    case '__DIR__/administer1.php':
        require 'administer1.php';
    case '__DIR__/ar_i0m.php':
        require 'ar_i0m.php';
    case '__DIR__/ar_io03.php':
        require 'ar_io03.php';
    case '__DIR__/audit_io03.php':
        require 'audit_io03.php';
    case '__DIR__/cause_io03.php':
        require 'cause_io03.php';
    case '__DIR__/cms_io03.php':
        require 'cms_io03.php';
    case '__DIR__/consequence_io03.php':
        require 'consequence_io03.php';
    case '__DIR__/contents0.php':
        require 'contents0.php';
    case '__DIR__/contents0_s_client.php':
        require 'contents0_s_client.php';
    case '__DIR__/contents0_s_division.php':
        require 'contents0_s_division.php';
    case '__DIR__/contents0_s_employer.php':
        require 'contents0_s_employer.php';
    case '__DIR__/contents0_s_facility.php':
        require 'contents0_s_facility.php';
    case '__DIR__/contents0_s_fragment.php':
        require 'contents0_s_fragment.php';
    case '__DIR__/contents0_s_practice.php':
        require 'contents0_s_practice.php';
    case '__DIR__/contents0_s_process.php':
        require 'contents0_s_process.php';
    case '__DIR__/contractor_i0m.php':
        require 'contractor_i0m.php';
    case '__DIR__/contractor_io03.php':
        require 'contractor_io03.php';
    case '__DIR__/contractor_priv_io03.php':
        require 'contractor_priv_io03.php';
    case '__DIR__/contractor_qual_io03.php':
        require 'contractor_qual_io03.php';
    case '__DIR__/division_o1.php':
        require 'division_o1.php';
    case '__DIR__/document_io03.php':
        require 'document_io03.php';
    case '__DIR__/facility_i0m.php':
        require 'facility_i0m.php';
    case '__DIR__/facility_io03.php':
        require 'facility_io03.php';
    case '__DIR__/audit_fragment_io03.php':
        require 'audit_fragment_io03.php';
    case '__DIR__/fragment_o1.php':
        require 'fragment_o1.php';
    case '__DIR__/glossary.php':
        require 'glossary.php';
    case '__DIR__/hspswp_cert_io03.php':
        require 'hspswp_cert_io03.php';
    case '__DIR__/incident_io03.php':
        require 'incident_io03.php';
    case '__DIR__/lepc_io03.php':
        require 'lepc_io03.php';
    case '__DIR__/logoff.php':
        require 'logoff.php';
    case '__DIR__/logon.php':
        require 'logon.php';
    case '/psm':
        require 'logon.php'; // route here, for marketing ease
    case '__DIR__/obsresult_io03.php':
        require 'obsresult_io03.php';
    case '__DIR__/owner_i0m.php':
        require 'owner_i0m.php';
    case '__DIR__/owner_i0n.php':
        require 'owner_i0n.php';
    case '__DIR__/owner_io03.php':
        require 'owner_io03.php';
    case '__DIR__/pha_io03.php':
        require 'pha_io03.php';
    case '__DIR__/practice_io03.php':
        require 'practice_io03.php';
    case '__DIR__/practice_o1.php':
        require 'practice_o1.php';
    case '__DIR__/process_i0m.php':
        require 'process_i0m.php';
    case '__DIR__/process_io03.php':
        require 'process_io03.php';
    case '__DIR__/reminder.php':
        require 'reminder.php';
    case '__DIR__/rule_o1.php':
        require 'rule_o1.php';
    case '__DIR__/safeguard_io03.php':
        require 'safeguard_io03.php';
    case '__DIR__/scenario_io03.php':
        require 'scenario_io03.php';
    case '__DIR__/subprocess_io03.php':
        require 'subprocess_io03.php';
    case '__DIR__/setup.php':
        include 'setup.php';
    case '__DIR__/setup_demos_1-3.php':
        include 'setup_demos_1-3.php';
    case '__DIR__/setup_demos_4-6.php':
        include 'setup_demos_4-6.php';
    case '__DIR__/setup_demos_7-9.php':
        include 'setup_demos_7-9.php';
    case '__DIR__/setup_demos_10-12.php':
        include 'setup_demos_10-12.php';
    case '__DIR__/setup_nh3r_1.php':
        include 'setup_nh3r_1.php';
    case '__DIR__/setup_nh3r_2.php':
        include 'setup_nh3r_2.php';
    case '__DIR__/training_form_io03.php':
        require 'training_form_io03.php';
    case '__DIR__/union_i0m.php':
        require 'union_i0m.php';
    case '__DIR__/union_io03.php':
        require 'union_io03.php';
    case '__DIR__/user_contractor_i0m.php':
        require 'user_contractor_i0m.php';
    case '__DIR__/user_contractor_io03.php':
        require 'user_contractor_io03.php';
    case '__DIR__/user_facility_i0m.php':
        require 'user_facility_i0m.php';
    case '__DIR__/user_facility_io03.php':
        require 'user_facility_io03.php';
    case '__DIR__/user_h_o1.php':
        require 'user_h_o1.php';
    case '__DIR__/user_i0m.php':
        require 'user_i0m.php';
    case '__DIR__/user_io03.php':
        require 'user_io03.php';
    case '__DIR__/username_password_i03.php':
        require 'username_password_i03.php';
    case '__DIR__/username_password_i13bu.php':
        require 'username_password_i13bu.php';
    case '__DIR__/user_o0.php':
        require 'user_o0.php';
    case '__DIR__/user_owner_i0m.php':
        require 'user_owner_i0m.php';
    case '__DIR__/user_owner_io03.php':
        require 'user_owner_io03.php';
    case '__DIR__/user_process_i0m.php':
        require 'user_process_i0m.php';
    case '__DIR__/user_process_io03.php':
        require 'user_process_io03.php';
    case '__DIR__/who_is_editing_unlock.php':
        require 'who_is_editing_unlock.php';
// Sample entry
//    case '__DIR__/___.php':
//        require '___.php';
    default:
        require 'error.php';
}
*/

