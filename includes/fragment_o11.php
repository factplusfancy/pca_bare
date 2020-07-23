<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file to allows viewing the practices associated with a rule fragment. IMPORTANT...
// REGARDLESS OF WHETHER THE USER IS ASSOCIATED WITH THE PRACTICE
// No t0user_practice entry is needed for any user associated with a process (or facility, owner, or contractor) to view the name & description of the practices associated with that process.

// $DBMSresource has been created by all files requiring this PHP file.
if (!isset($_SESSION['StatePicked']['t0division']) or !isset($DBMSresource) or (!isset($_POST['selected_fragment']) and !isset($_SESSION['StatePicked']['t0fragment'])))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// redundant security
if (isset($_SESSION['Scratch']))
    unset($_SESSION['Scratch']);
// Set in practice_o1.php. Unset here to facilitate new selection by user.
if (isset($_SESSION['t0user_practice']))
    unset($_SESSION['t0user_practice']);
// Get practices associated with this fragment.
if (isset($_POST['selected_fragment'])) {
    $CheckedPostFragment = $Zfpf->post_length_blank_1c('selected_fragment');
    $_SESSION['StatePicked']['t0fragment'] = $_SESSION['SelectResults']['t0fragment'][$CheckedPostFragment];
    unset($_SESSION['SelectResults']);
}
$Conditions_k0fragment[0] = array('k0fragment', '=', $_SESSION['StatePicked']['t0fragment']['k0fragment']);
list($SR['t0fragment_practice'], $RR['t0fragment_practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment_practice', $Conditions_k0fragment);
if (isset($RR['t0fragment_division']) and $RR['t0fragment_division'] == 1) // Only one fragment in division, division_o11.php includes the current file.
    $GoBackAction = 'contents0.php';
else
    $GoBackAction = 'contents0_s_fragment.php';
if ($RR['t0fragment_practice'] < 1) {
    $Message = '<p>
    No practices were found in the PSM-CAP App database associated with the rule fragment you selected. These are normally included in the database at installation and gradually modified as needed. Contact your supervisor or a PSM-CAP App administrator for assistance with getting any needed content into the database.</p>
    <form action="'.$GoBackAction.'" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>';
}
else { // Whether there is one or many practices associated with a fragment, what's displayed below is the same, and the user must click on a practice to see any PSM-CAP App tools, in t0details (associated via t0practice_details) for that practice. Also, only show practices associated with the user-selected owner, contractor, facility, or process.
    if (isset($_SESSION['StatePicked']['t0owner'])) {
        $Conditions_k0practice[0] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner'], 'AND (');
        foreach ($SR['t0fragment_practice'] as $K => $V)
            $Conditions_k0practice[] = array ('k0practice', '=', $V['k0practice'], 'OR');
        // Replace the final, hanging, 'OR' with ')'.
        $LastArrayKey = $RR['t0fragment_practice'];
        $Conditions_k0practice[$LastArrayKey][3] = ')';
        list($SR['t0owner_practice'], $RR['t0owner_practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_practice', $Conditions_k0practice);
        if ($RR['t0owner_practice'] > 0)
            foreach ($SR['t0owner_practice'] as $V)
                $k0practice_array[] = $V['k0practice'];
    }
    if (isset($_SESSION['StatePicked']['t0contractor'])) {
        $Conditions_k0practice[0] = array('k0contractor', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], 'AND (');
        if (!isset($Conditions_k0practice[1])) { // This handles case where user selected a contractor but no owner.
            foreach ($SR['t0fragment_practice'] as $K => $V)
                $Conditions_k0practice[] = array ('k0practice', '=', $V['k0practice'], 'OR');
            // Replace the final, hanging, 'OR' with ')'.
            $LastArrayKey = $RR['t0fragment_practice'];
            $Conditions_k0practice[$LastArrayKey][3] = ')';
        }
        list($SR['t0contractor_practice'], $RR['t0contractor_practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0contractor_practice', $Conditions_k0practice);
        if ($RR['t0contractor_practice'] > 0)
            foreach ($SR['t0contractor_practice'] as $V)
                $k0practice_array[] = $V['k0practice'];
    }
    if (isset($_SESSION['StatePicked']['t0facility'])) {
        $Conditions_k0practice[0] = array('k0facility', '=', $_SESSION['StatePicked']['t0facility']['k0facility'], 'AND (');
        list($SR['t0facility_practice'], $RR['t0facility_practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_practice', $Conditions_k0practice);
        if ($RR['t0facility_practice'] > 0)
            foreach ($SR['t0facility_practice'] as $V)
                $k0practice_array[] = $V['k0practice'];
    }
    if (isset($_SESSION['StatePicked']['t0process'])) {
        $Conditions_k0practice[0] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process'], 'AND (');
        list($SR['t0process_practice'], $RR['t0process_practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0process_practice', $Conditions_k0practice);
        if ($RR['t0process_practice'] > 0)
            foreach ($SR['t0process_practice'] as $V)
                $k0practice_array[] = $V['k0practice'];
    }
    if (!isset($k0practice_array)) {
        unset ($_SESSION['StatePicked']['t0fragment']);
        echo $Zfpf->xhtml_contents_header_1c('No Practices').'<p>
        No practices were found in the PSM-CAP App database associated with your process, facility, owner, or contractor and the rule fragment that you selected. Contact your supervisor or a PSM-CAP App administrator for assistance with getting any needed content into the database.</p>
        <form action="'.$GoBackAction.'" method="post"><p>
            <input type="submit" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->close_connection_1s($DBMSresource);
        $Zfpf->save_and_exit_1c();
    }
    $k0practice_array = array_unique($k0practice_array);
    unset($Conditions_k0practice);
    foreach ($k0practice_array as $V)
        $Conditions_k0practice[] = array('k0practice', '=', $V, 'OR');
    // remove the final, hanging, 'OR'.
    $LastArrayKey = count($k0practice_array) - 1;
    unset($Conditions_k0practice[$LastArrayKey][3]);
    list($SR['t0practice'], $RR['t0practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions_k0practice);
    if ($RR['t0practice'] != count($k0practice_array)) // There should have been no duplicate k0practice.
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // Sort $SR['t0practice'] by c5number.
    foreach ($SR['t0practice'] as $V)
        $c5number[] = $Zfpf->decrypt_1c($V['c5number']);
    array_multisort($c5number, $SR['t0practice']);
    $_SESSION['SelectResults']['t0practice'] = $SR['t0practice'];
    $Message = '<p>
    Select the compliance practice you want to view.</p>
    <form action="practice_o1.php" method="post">';
    foreach ($_SESSION['SelectResults']['t0practice'] as $K => $V) {
        $Message .= '<p>
        <input type="radio" name="selected_practice" value="' . $K . '" ';
        if ($K == 0) // Select the first practice by default to ensure something is posted (unless a hacker is tampering).
            $Message .= 'checked="checked" ';
        $Message .= '/><b>' . $Zfpf->decrypt_1c($V['c5name']) . '</b><br />' . nl2br($Zfpf->decrypt_1c($V['c6description'])).'</p>';
    }
    $Message .= '<p>
        <input type="submit" value="Select practice" /></p>
    </form>
    <form action="'.$GoBackAction.'" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>';
}
$Zfpf->close_connection_1s($DBMSresource);

$FragmentName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0fragment']['c5name']);
echo $Zfpf->xhtml_contents_header_1c($FragmentName).'<h1>
' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0division']['c5name']);
if (isset($_SESSION['StatePicked']['t0division']['c5citation']) and $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0division']['c5citation']) != '[Nothing has been recorded in this field.]')
    echo ' (' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0division']['c5citation']) . ')';
echo '</h1><h2>
' . $FragmentName . ' (' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0fragment']['c5citation']) . ')</h2><p>
' . nl2br($Zfpf->decrypt_1c($_SESSION['StatePicked']['t0fragment']['c6quote']));
if (isset($_SESSION['StatePicked']['t0fragment']['c5source']) and $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0fragment']['c5source']) != '[Nothing has been recorded in this field.]')
    echo '<br /><b>Source</b>: ' . $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0fragment']['c5source']);
echo '</p>'.$Message.$Zfpf->xhtml_footer_1c();

