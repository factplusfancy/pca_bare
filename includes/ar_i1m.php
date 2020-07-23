<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check.
// Cannot screen for process being set because Action Register can apply contractor-, owner-, facility-, or process-wide.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'ar_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

require INCLUDES_DIRECTORY_PATH_ZFPF.'/arZfpf.php';
$arZfpf = new arZfpf;
if (isset($_SESSION['Selected']['k0action']))
    unset($_SESSION['Selected']); // Clear in case arrived from ar_io03.php with this set.
$SpecialConditions = array('k0user_of_ae_leader', '=', 0); // Means c5status holds, encrypted, 'Needs resolution...' See app schema.
$Conditions = $arZfpf->conditions_state_picked($SpecialConditions);
list($_SESSION['SelectResults']['t0action'], $RowsReturned['t0action']) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
echo $arZfpf->actions_list($Zfpf, $RowsReturned['t0action'], 'Unresolved Actions', 'c5ts_target');
$Zfpf->save_and_exit_1c();

