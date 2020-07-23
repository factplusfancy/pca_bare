<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
//
// This PHP file to allows viewing the practice details associated with a practice.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

// Security check.
if (!isset($_POST['selected_practice']) and !isset($_SESSION['StatePicked']['t0practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Cannot call CoreZfpf::session_cleanup_1c here, need $_SESSION['SelectResults']
// START modified session cleanup.
$Zfpf->clear_edit_lock_1c();
if (isset($_SESSION['Selected']))
    unset($_SESSION['Selected']);
if (isset($_SESSION['Scratch']))
    unset($_SESSION['Scratch']);
if (isset($_SESSION['Post']))
    unset($_SESSION['Post']);
// END modified session cleanup.

// Save any selected t0practice row in $_SESSION['StatePicked'] otherwise, the above security check ensures that $_SESSION['StatePicked']['t0practice'] is set.
if (isset($_POST['selected_practice'])) {
    $CheckedPost = $Zfpf->post_length_blank_1c('selected_practice');
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0practice'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['StatePicked']['t0practice'] = $_SESSION['SelectResults']['t0practice'][$CheckedPost];
}
if (isset($_SESSION['SelectResults']))
    unset($_SESSION['SelectResults']);
// Find the t0user_practice row, if any, and save or update in $_SESSION['t0user_practice']
$ConditionsUserPractice[] = array('k0user', '=', $_SESSION['t0user']['k0user'], 'AND');
$ConditionsUserPractice[] = array('k0practice', '=', $_SESSION['StatePicked']['t0practice']['k0practice'], 'AND');
if (isset($_SESSION['StatePicked']['t0owner']))
    $ConditionsUserPractice[] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner'], 'OR');
if (isset($_SESSION['StatePicked']['t0contractor']))
    $ConditionsUserPractice[] = array('k0contractor', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], 'OR');
if (isset($_SESSION['StatePicked']['t0facility']))
    $ConditionsUserPractice[] = array('k0facility', '=', $_SESSION['StatePicked']['t0facility']['k0facility'], 'OR');
if (isset($_SESSION['StatePicked']['t0process']))
    $ConditionsUserPractice[] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process'], 'OR');
$Count = count($ConditionsUserPractice);
if ($Count == 2)
    unset($ConditionsUserPractice[1][3]);
if ($Count == 3)
    unset($ConditionsUserPractice[2][3]);
if ($Count > 3) {
    $ConditionsUserPractice[2][4] = '(';
    $LastArrayKey = $Count - 1;
    $ConditionsUserPractice[$LastArrayKey][3] = ')';
}
$DBMSresource = $Zfpf->credentials_connect_instance_1s(LOW_PRIVILEGES_ZFPF);
list($SelectResults['t0user_practice'], $RowsReturned['t0user_practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0user_practice', $ConditionsUserPractice);
if ($RowsReturned['t0user_practice'] > 1)
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned);
// If a user has selected a practice that they are not associated with via t0user_practice, let them know, but don't show them the practice details.
if ($RowsReturned['t0user_practice'] < 1) {
    $Message = '<p>
    You are not authorized to complete the practice you selected (nor to view the details associated with it). If you need to be associated with this practice at your currently selected owner, contractor, facility, or process -- please contact your supervisor or a PSM-CAP App administrator for assistance.</p>
    <form action="contents0_s_fragment.php" method="post"><p>
        <input type="submit" value="Go Back" /></p>
    </form>';
    if (isset($_SESSION['t0user_practice']))
        unset($_SESSION['t0user_practice']); // $_SESSION['t0user_practice'] is also unset at the beginning of: fragment_o11.php, division_o11.php, and contents1.php
}
else {
    $_SESSION['t0user_practice'] = $SelectResults['t0user_practice'][0]; // Always update $_SESSION['t0user_practice'] here, in case privileges changed in meantime.
    $RequireFile = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0practice']['c5require_file']);
    $RequireFilePrivileges = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0practice']['c5require_file_privileges']);
    if ($RequireFile != '[Nothing has been recorded in this field.]') {
        if ($RequireFilePrivileges == '[Nothing has been recorded in this field.]')
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $UserDBMSPrivileges = strlen($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']));
        $RequiredDBMSPrivileges = strlen($RequireFilePrivileges);
        if ($UserDBMSPrivileges >= $RequiredDBMSPrivileges) {
            $Zfpf->close_connection_1s($DBMSresource);
            unset($DBMSresource); // Needed because the RequireFile may set a new variable named $DBMSresource
            $_SESSION['Scratch']['PlainText']['SecurityToken'] = $RequireFile;
            require INCLUDES_DIRECTORY_PATH_ZFPF.'/'.$RequireFile;
            $Zfpf->save_and_exit_1c(); // Most practices should have require files and so exit here (and execute the require file instead).
        }
        else
            $Message = '<p>
            You do not have the global PSM-CAP App privileges needed to complete the practice you selected. (This practice may require insert, update, or delete privileges that you don\'t have on the PSM-CAP App database.) Please contact your supervisor or a PSM-CAP App administrator for assistance. Or, try selecting a practice that only allows viewing the information you need.</p>
            <form action="contents0_s_fragment.php" method="post"><p>
                <input type="submit" value="Go Back" /></p>
            </form>';
    }
    else
        $Message = '<p>
        Nothing additional was found in the PSM-CAP App associated with the practice you selected. If the description above does not provide adequate information, please contact your supervisor or a PSM-CAP App administrator for assistance.</p>
            <form action="contents0_s_fragment.php" method="post"><p>
                <input type="submit" value="Go Back" /></p>
            </form>';
}
$Zfpf->close_connection_1s($DBMSresource);
$PracticeName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0practice']['c5name']);
$PracticeDescription = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0practice']['c6description']);
echo $Zfpf->xhtml_contents_header_1c($PracticeName).'<h2>
'.$PracticeName.'</h2>';
if ($PracticeDescription != '[Nothing has been recorded in this field.]')
    echo '<p>
    '.nl2br($PracticeDescription).'</p>';
echo '
'.$Message.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

