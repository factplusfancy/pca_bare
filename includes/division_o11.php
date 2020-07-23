<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
//
// This PHP file to allows viewing the practices and rule fragments associated with a rule division.

if (!isset($_SESSION['StatePicked']['t0rule']) or (!isset($_POST['selected_division']) and !isset($_SESSION['StatePicked']['t0division'])))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// For redundant security
if (isset($_SESSION['Scratch']))
    unset($_SESSION['Scratch']);
// Set in practice_o1.php. Unset here to facilitate new selection by user.
if (isset($_SESSION['t0user_practice']))
    unset($_SESSION['t0user_practice']);
$DBMSresource = $Zfpf->credentials_connect_instance_1s(LOW_PRIVILEGES_ZFPF);
// If needed, set $_SESSION['StatePicked']['t0division']
if (isset($_POST['selected_division'])) {
    $CheckedPost = $Zfpf->post_length_blank_1c('selected_division');
    if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0division'][$CheckedPost]))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['StatePicked']['t0division'] = $_SESSION['SelectResults']['t0division'][$CheckedPost];
    unset($_SESSION['SelectResults']);
}
$Conditions_k0division[0] = array('k0division', '=', $_SESSION['StatePicked']['t0division']['k0division']);
if (isset($_SESSION['StatePicked']['t0fragment'])) {
    $FragmentView = 1;
    unset($_SESSION['StatePicked']['t0fragment']);
}
elseif (isset($_POST['selected_view']) and $Zfpf->post_length_blank_1c('selected_view') == 'rule_fragments')
    $FragmentView = 1;
if (isset($FragmentView)) { // So, if !isset($_SESSION['StatePicked']['t0fragment']) and !isset($_POST['selected_view'] defaults to practice view.
    // Get fragments associated with this division.
    list($SR['t0fragment_division'], $RR['t0fragment_division']) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment_division', $Conditions_k0division);
    if (!$RR['t0fragment_division']) {
        unset ($_SESSION['StatePicked']['t0division']);
        echo $Zfpf->xhtml_contents_header_1c('No Fragments');
        echo '<p>
        No rule fragments were found in the database associated with the rule division you selected. Normally these are included in the database at installation. Contact your supervisor or a PSM-CAP App administrator for assistance with getting any needed content into the database.</p>
        <form action="contents0.php" method="post"><p>
            <input type="submit" value="Go back" /></p>
        </form>
        ';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->close_connection_1s($DBMSresource);
        $Zfpf->save_and_exit_1c();
    }
    elseif ($RR['t0fragment_division'] == 1) {
        $Conditions_k0fragment[0] = array('k0fragment', '=', $SR['t0fragment_division'][0]['k0fragment']);
        list($SR['t0fragment'], $RR['t0fragment']) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment', $Conditions_k0fragment);
        if ($RR['t0fragment'] != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RR);
        $_SESSION['StatePicked']['t0fragment'] = $SR['t0fragment'][0];
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/fragment_o11.php'; // Don't $Zfpf->close_connection_1s($DBMSresource); Need for required file.
        $Zfpf->save_and_exit_1c();
    }
    else { // This is typical case where a division has more than one fragments.
        foreach ($SR['t0fragment_division'] as $V) {
            $Conditions_k0fragment[0] = array('k0fragment', '=', $V['k0fragment']);
            list($SR['t0fragment'], $RR['t0fragment']) = $Zfpf->select_sql_1s($DBMSresource, 't0fragment', $Conditions_k0fragment);
            if ($RR['t0fragment'] != 1)
                $Zfpf->send_to_contents_1c('<p>An error occurred matching fragments to divisions. Contact app admin.</p>');
            $Number[] = $Zfpf->decrypt_1c($V['c5number']); // See schema t0fragment_division:c5number
            $Citation[] = $Zfpf->decrypt_1c($SR['t0fragment'][0]['c5citation']);
            $_SESSION['SelectResults']['t0fragment'][] = $SR['t0fragment'][0];
        }
        if (!in_array('[Nothing has been recorded in this field.]', $Number) and !in_array('', $Number)) // Sort by number.
            array_multisort($Number, $Citation, $_SESSION['SelectResults']['t0fragment']);
        else // Sort by citation.
            array_multisort($Citation, $_SESSION['SelectResults']['t0fragment']);
        $Message = '<p>
        Select the rule fragment whose compliance practices you want to view.</p>
        <form action="fragment_o1.php" method="post">';
        foreach ($_SESSION['SelectResults']['t0fragment'] as $K => $V) {
            if (isset($V['c5superseded']) and $Zfpf->decrypt_1c($V['c5superseded']) != 'Superseded') { // TO DO create fragment_superceded_o1.php (to allow viewing of superseded fragments).
                $Message .= '<p>
                <input type="radio" name="selected_fragment" value="'.$K.'" ';
                if ($K == 0) // Select the first fragment by default to ensure something is posted (unless a hacker is tampering).
                    $Message .= 'checked="checked" ';
                $Message .= '/><b>'.$Zfpf->decrypt_1c($V['c5name']).'</b> ('.$Citation[$K].')<br />'.nl2br($Zfpf->decrypt_1c($V['c6quote']));
                if (isset($V['c5source']) and $Zfpf->decrypt_1c($V['c5source']) != '[Nothing has been recorded in this field.]')
                    $Message .= '<br /><b>Source</b>: '.$Zfpf->decrypt_1c($V['c5source']);
                $Message .= '</p>';
            }
        }
        $Message .= '<p>
            <input type="submit" value="Select fragment" /></p>
        </form>
        <form action="contents0.php" method="post"><p>
            <input type="submit" value="Go back" /></p>
        </form>';
    }
}
else {
    // Send user straight to practices associated with a division.
    // Get practices associated with the selected division.
    list($SR['t0practice_division'], $RR['t0practice_division']) = $Zfpf->select_sql_1s($DBMSresource, 't0practice_division', $Conditions_k0division);
    if (!$RR['t0practice_division']) {
        unset ($_SESSION['StatePicked']['t0division']);
        echo $Zfpf->xhtml_contents_header_1c('No Practices').'<p>
        No practices were found in the database associated with the rule division you selected. These are normally included in the database at installation and gradually modified as needed. Contact your supervisor or a PSM-CAP App administrator for assistance with getting any needed content into the database.</p>
        <form action="contents0.php" method="post"><p>
            <input type="submit" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->close_connection_1s($DBMSresource);
        $Zfpf->save_and_exit_1c();
    }
    else { // Whether there is one or many practices associated with a division, what's displayed below is the same, and the user may select a practice to see any PSM-CAP App tools for that practice. Also, only show practices associated with the user-selected owner, contractor, facility, or process. This else clause was adopted from fragment_o11.php.
        if (isset($_SESSION['StatePicked']['t0owner'])) {
            $Conditions_k0practice[0] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner'], 'AND (');
            foreach ($SR['t0practice_division'] as $K => $V)
                $Conditions_k0practice[] = array ('k0practice', '=', $V['k0practice'], 'OR');
            // Replace the final, hanging, 'OR' with ')'.
            $LastArrayKey = $RR['t0practice_division'];
            $Conditions_k0practice[$LastArrayKey][3] = ')';
            list($SR['t0owner_practice'], $RR['t0owner_practice']) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_practice', $Conditions_k0practice);
            if ($RR['t0owner_practice'] > 0)
                foreach ($SR['t0owner_practice'] as $V)
                    $k0practice_array[] = $V['k0practice'];
        }
        if (isset($_SESSION['StatePicked']['t0contractor'])) {
            $Conditions_k0practice[0] = array('k0contractor', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], 'AND (');
            if (!isset($Conditions_k0practice[1])) { // This handles case where user selected a contractor but no owner.
                foreach ($SR['t0practice_division'] as $K => $V)
                    $Conditions_k0practice[] = array ('k0practice', '=', $V['k0practice'], 'OR');
                // Replace the final, hanging, 'OR' with ')'.
                $LastArrayKey = $RR['t0practice_division'];
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
            echo $Zfpf->xhtml_contents_header_1c('No Practices').'<p>
            No practices were found in the PSM-CAP App database associated with your process, facility, owner, or contractor and the rule division that you selected. Contact your supervisor or a PSM-CAP App administrator for assistance with getting any needed content into the database.</p>
            <form action="contents0.php" method="post"><p>
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
            <input type="radio" name="selected_practice" value="'.$K.'" ';
            if ($K == 0) // Select the first practice by default to ensure something is posted (unless a hacker is tampering).
                $Message .= 'checked="checked" ';
            $Message .= '/><b>'.$Zfpf->decrypt_1c($V['c5name']).'</b><br />'.nl2br($Zfpf->decrypt_1c($V['c6description'])).'</p>';
        }
        $Message .= '<p>
            <input type="submit" value="Select practice" /></p>
        </form>
        <form action="contents0.php" method="post"><p>
            <input type="submit" value="Go back" /></p>
        </form>';
    }
}
$Zfpf->close_connection_1s($DBMSresource);
$DivisionName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0division']['c5name']);
echo $Zfpf->xhtml_contents_header_1c($DivisionName).'<h1>
'.$DivisionName;
if (isset($_SESSION['StatePicked']['t0division']['c5citation']) and $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0division']['c5citation']) != '[Nothing has been recorded in this field.]')
    echo ' ('.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0division']['c5citation']).')';
echo '</h1>
'.$Message.$Zfpf->xhtml_footer_1c();
$Zfpf->save_and_exit_1c();

