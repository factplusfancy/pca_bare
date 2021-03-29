<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i0m file requires an i1m file, after the session check and querying the action-register practice, to allow calling directly from left-hand contents.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

$Zfpf->session_cleanup_1c(); // Needed becaused called from left-hand contents, like administer1.php
// General security check.
// SPECIAL CASE: None
//      No security token because called from left-hand contents. 
//      Need to set now, along with 

// Select template action-register practice from t0practice
$Conditions_k0practice[0] = array('k0practice', '=', '1'); // SPECIAL CASE: see comment in ...includes/templates/practices.php
$DBMSresource = $Zfpf->credentials_connect_instance_1s();
list($SelectResults['t0practice'], $RowsReturned['t0practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions_k0practice);
if ($RowsReturned['t0practice'] != 1)
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
$_SESSION['StatePicked']['t0practice'] = $SelectResults['t0practice'][0];
// Select and check any associated t0user_practice row. Below copied from practice_o1.php
$Conditions_k0user_k0practice[] = array('k0user', '=', $_SESSION['t0user']['k0user'], 'AND');
$Conditions_k0user_k0practice[] = array('k0practice', '=', $_SESSION['StatePicked']['t0practice']['k0practice'], 'AND');
// The action register is typically an owner standard practice, but don't assume that here.
if (isset($_SESSION['StatePicked']['t0owner']))
    $Conditions_k0user_k0practice[] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner'], 'OR');
if (isset($_SESSION['StatePicked']['t0contractor']))
        $Conditions_k0user_k0practice[] = array('k0contractor', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], 'OR');
if (isset($_SESSION['StatePicked']['t0facility']))
    $Conditions_k0user_k0practice[] = array('k0facility', '=', $_SESSION['StatePicked']['t0facility']['k0facility'], 'OR');
if (isset($_SESSION['StatePicked']['t0process']))
    $Conditions_k0user_k0practice[] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process'], 'OR');
$Count = count($Conditions_k0user_k0practice);
if ($Count == 2)
    unset($Conditions_k0user_k0practice[1][3]);
if ($Count == 3)
    unset($Conditions_k0user_k0practice[2][3]);
if ($Count > 3) {
    $Conditions_k0user_k0practice[2][4] = '(';
    $LastArrayKey = $Count - 1;
    $Conditions_k0user_k0practice[$LastArrayKey][3] = ')';
}
list($SelectResults['t0user_practice'], $RowsReturned['t0user_practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $Conditions_k0user_k0practice);
$Zfpf->close_connection_1s($DBMSresource);
if ($RowsReturned['t0user_practice'] > 1)
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned);
// If a user has selected a practice that they are not associated with via t0user_practice, let them know, but don't show them the practice details.
if (!$RowsReturned['t0user_practice']) {
echo $Zfpf->xhtml_contents_header_1c('Action Register').'<h1>
    Action Register</h1><h2>
    Not authorized to view action register</h2><p>
    You are not associated with the action-register practice. Contact your supervisor or the app administrator to change this.</p>
    <form action="contents0.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
$_SESSION['t0user_practice'] = $SelectResults['t0user_practice'][0];                    
$_SESSION['Scratch']['PlainText']['SecurityToken'] = 'ar_i1m.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ar_i1m.php';

$Zfpf->save_and_exit_1c();

