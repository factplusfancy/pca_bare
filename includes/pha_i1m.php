<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// Check for user pointing their browser at this php file without permission or tampering.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'pha_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

$UserGlobalDBMSpriv = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);
$AppAdmin = $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']);
$ProcessPHAsCount = 0;

if (isset($_SESSION['StatePicked']['t0process']['k0process'])) {
    $Conditions[0] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process']);
    if ($AppAdmin == 'Yes')
        $Conditions[1] = array('k0pha', '<', 100000, '', 'OR '); // Gets templates
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    list($_SESSION['SelectResults']['t0pha'], $RowsReturned['t0pha']) = $Zfpf->select_sql_1s($DBMSresource, 't0pha', $Conditions);
    if ($RowsReturned['t0pha'] > 0) {
        $Message = '<p>
        <a class="toc" href="glossary.php#pha" target="_blank">PHA</a> for the currently selected process:</p>
        <form action="pha_io03.php" method="post"><p>';
        // Sort $_SESSION['SelectResults']['t0pha'] descending (SORT_DESC) by k0pha, which will sort newest to oldest due to time-stamp embedded in k0pha. 
        // Templates with k0pha below 100000 will be last.
        foreach ($_SESSION['SelectResults']['t0pha'] as $V)
            $k0pha[] = $V['k0pha'];
        array_multisort($k0pha, SORT_DESC, $_SESSION['SelectResults']['t0pha']);
        foreach ($_SESSION['SelectResults']['t0pha'] as $K => $V)
            if ($V['k0pha'] >= 100000) { // List templates that may be edited by Web App Admins separately, below.
                ++$ProcessPHAsCount;
                $Message .= '
                <input type="radio" name="selected" value="'.$K.'" ';
                if ($K == 0)
                    $Message .= 'checked="checked" '; // Select the first document by default to ensure something is posted (unless a hacker is tampering).
                $Message .= '/>';
                if ($Zfpf->decrypt_1c($V['c5ts_leader']) == '[Nothing has been recorded in this field.]')
                    $Message .= 'Current Working Draft';
                else
                    $Message .= 'Issued '.$Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($V['c5ts_leader'])); // PHAs don't have a name field. The issued date is used for a name.
                $Message .= '<br />';
            }
        $Message .= '</p>'; // $Message overwritten below if (!$ProcessPHAsCount)
    }
    if (!$ProcessPHAsCount) {
        // This overwrites the above $Message if only template PHAs were selected from the database.
        $Message = '<p><b>
        Nothing found</b> for the current process, neither draft nor issued. Please contact your supervisor if this seems amiss.</p>';
        if ($UserGlobalDBMSpriv == LOW_PRIVILEGES_ZFPF)
            $Message .= '<p><b>
            Global Privileges Notice</b>: You have privileges to neither create nor edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
        else { // Only allow creating the first PHA if none exists. Need at least INSERT global privileges to start a new record.
            unset ($Conditions); // Rare case, so reselect here to avoid selecting templates for users, who are not Web App Admins, each time.
            $Conditions[0] = array('k0pha', '<', 100000); // Gets templates
            list($_SESSION['SelectResults']['t0pha'], $RowsReturned['t0pha']) = $Zfpf->select_sql_1s($DBMSresource, 't0pha', $Conditions);
            $Message .= '<p>
            Create first PHA from:</p>
            <form action="pha_io03.php" method="post"><p>';
            if ($RowsReturned['t0pha'] > 0) {
                foreach ($_SESSION['SelectResults']['t0pha'] as $K => $V)
                    $Message .= '
                    <input type="radio" name="selected" value="'.$K.'" ';
                    if ($K == 0)
                        $Message .= 'checked="checked" '; // Select the first document by default to ensure something is posted (unless a hacker is tampering).
                    $Message .= '/>'.$Zfpf->decrypt_1c($V['c6nymd_leader']).'<br />'; // c6nymd_leader should hold the template name here.
                $Message .= '</p><p>
                    <input type="submit" name="pha_template" value="Create from selected template" /></p>';
            }
            $Message .= '<p>
                Creating a PHA from scratch is difficult, so try finding a template.<br />
                <input type="submit" name="pha_i0n" value="Create from scratch" /></p>';
            $TemplatesInMessage = TRUE;
        }
    }
}
else
    $Message = '<p>
    Looks like you have not selected a process, and a PHA must be associated with a process, except any template PHA listed below.</p>';
// Allow editing any template PHAs by Web App Admins.
if ($AppAdmin == 'Yes' and $UserGlobalDBMSpriv == MAX_PRIVILEGES_ZFPF) {
    if (!isset($_SESSION['StatePicked']['t0process']['k0process'])) {
        $Conditions[0] = array('k0pha', '<', 100000); // Gets templates
        list($_SESSION['SelectResults']['t0pha'], $RowsReturned['t0pha']) = $Zfpf->select_sql_1s($DBMSresource, 't0pha', $Conditions);
        if ($RowsReturned['t0pha'] > 0)
            $Message .= '
        <form action="pha_io03.php" method="post"><p>';
    }
    if ($RowsReturned['t0pha'] > $ProcessPHAsCount and !isset($TemplatesInMessage)) {
        $Message .= '<p>
        Template PHA you may view or edit:</p><p>';
        foreach ($_SESSION['SelectResults']['t0pha'] as $K => $V)
            if ($V['k0pha'] < 100000)
                $Message .= '
                <input type="radio" name="selected" value="'.$K.'" />'.$Zfpf->decrypt_1c($V['c6nymd_leader']).'<br />';
        $Message .= '</p>';
        $ProcessPHAsCount = 1; // Use this only to add button below.
    }
    if (isset($TemplatesInMessage))
        $Message .= '<p>Or you may...<br />
        <input type="submit" name="pha_o1" value="View or edit selected template" /></p>';
}
if ($ProcessPHAsCount)
    $Message .= '<p>
        <input type="submit" name="pha_o1" value="View selected" /></p>';
if ($ProcessPHAsCount or $UserGlobalDBMSpriv != LOW_PRIVILEGES_ZFPF) // Use one form so radio buttons mutually exclusive.
    $Message .= '
    </form>';
$Zfpf->close_connection_1s($DBMSresource);        
echo $Zfpf->xhtml_contents_header_1c('Pick Document').'<h1>
Process-hazard analysis (<a class="toc" href="glossary.php#pha" target="_blank">PHA</a>)</h1>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

