<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// Check for user pointing their browser at this php file without permission or tampering.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'pha_i1m.php' or !isset($_SESSION['t0user_practice']) or !isset($_SESSION['StatePicked']['t0process']['k0process']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

$AppAdmin = $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']);
$PracticePriv = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$ProcessPHAsCount = 0;
$Conditions[0] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process']);
if ($PracticePriv == MAX_PRIVILEGES_ZFPF)
    $Conditions[1] = array('k0pha', '<', 100000, '', 'OR '); // Gets templates
$DBMSresource = $Zfpf->credentials_connect_instance_1s();
list($_SESSION['SelectResults']['t0pha'], $RowsReturned['t0pha']) = $Zfpf->select_sql_1s($DBMSresource, 't0pha', $Conditions);
$Message = '<form action="pha_io03.php" method="post" enctype="multipart/form-data">';
if ($RowsReturned['t0pha']) {
    // Sort $_SESSION['SelectResults']['t0pha'] descending (SORT_DESC) by k0pha, which will sort newest to oldest due to time-stamp embedded in k0pha. 
    // Templates with k0pha below 100000 will be last.
    foreach ($_SESSION['SelectResults']['t0pha'] as $V) {
        $k0pha[] = $V['k0pha'];
        if ($V['k0pha'] >= 100000)
            ++$ProcessPHAsCount;
    }
    array_multisort($k0pha, SORT_DESC, $_SESSION['SelectResults']['t0pha']);
    if ($ProcessPHAsCount) {
        $Message .= '<p>
        PHA for the currently selected process:</p><p>';
        foreach ($_SESSION['SelectResults']['t0pha'] as $K => $V)
            if ($V['k0pha'] >= 100000) { // List templates that may be edited by Web App Admins separately, below.
                $Message .= '
                <input type="radio" name="selected" value="'.$K.'" ';
                if ($K == 0)
                    $Message .= 'checked="checked" '; // Select the first document by default to ensure something is posted (unless a hacker is tampering).
                $Message .= '/>';
                $LeaderApprovedTime = $Zfpf->decrypt_1c($V['c5ts_leader']);
                if ($LeaderApprovedTime == '[Nothing has been recorded in this field.]')
                    $Message .= 'Current working draft (CWD)';
                elseif (is_numeric($LeaderApprovedTime))
                    $Message .= 'PHA issued '.$Zfpf->timestamp_to_display_1c($LeaderApprovedTime); // PHAs don't have a name field. The issued date is used for a name.
                else
                    $Message .= $LeaderApprovedTime; // Special cases, like superseded and archived drafts.
                $Message .= '<br />';
            }
        $Message .= '</p>';
        // Allow editing template PHAs by app admins, even if a process PHA has been created.
        if ($AppAdmin == 'Yes') {
            if ($RowsReturned['t0pha'] > $ProcessPHAsCount) { // This means one or more template PHAs need to be displayed to the admin.
                $Message .= '<p>
                Template PHA you may view or edit because you are an app admin:</p><p>';
                foreach ($_SESSION['SelectResults']['t0pha'] as $K => $V)
                    if ($V['k0pha'] < 100000)
                        $Message .= '
                        <input type="radio" name="selected" value="'.$K.'" />'.$Zfpf->decrypt_1c($V['c6nymd_leader']).'<br />';
                $Message .= '</p>';
            }
        }
        $Message .= '<p>
        <input type="submit" name="pha_o1" value="View selected" /></p>';
    }
    else {  // Only template PHAs found, which means $Conditions[1] above was set, so $PracticePriv == MAX_PRIVILEGES_ZFPF.
            // Only allow creating the first PHA if none exists. When a PHA is issued, a new current working draft is created, identical to the issued PHA, for future modification. If a user wants to start over, from a newer template, code changes would be needed to this file and pha_io03.php?team_leader_approval_2.
        $Message .= '<p>
        <b>Nothing found</b> for the current process, neither draft nor issued. Please contact your supervisor if this seems amiss.</p><p>
        Create first PHA from:</p><p>';
        foreach ($_SESSION['SelectResults']['t0pha'] as $K => $V) {
            $Message .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if ($K == 0)
                $Message .= 'checked="checked" '; // Select the first document by default to ensure something is posted (unless a hacker is tampering).
            $Message .= '/>'.$Zfpf->decrypt_1c($V['c6nymd_leader']).'<br />'; // c6nymd_leader should hold the template name here.
        }
        $Message .= '</p>';
        if ($AppAdmin == 'Yes')
            $Message .= '<p>
            <input type="submit" name="pha_o1" value="View or edit selected template" /></p>';
        else
            $Message .= '<p>
            <input type="submit" name="pha_o1" value="View selected template" /></p>';
        $Message .= '</p><p>
            <input type="submit" name="pha_template" value="Create from selected template" /></p>';
    }
}
// Below relevent both if and if !$RowsReturned['t0pha']
if (!$ProcessPHAsCount and $PracticePriv != MAX_PRIVILEGES_ZFPF)
    $Message .= '<p>
    <b>Nothing found</b> for the current process, neither draft nor issued.</p><p>
    <b>Practice Privileges Notice</b>: You have privileges to neither import PHAs from JSON files nor create a PHA from a template. If you need these, please contact your supervisor or an app admin.</p>';
if ($PracticePriv == MAX_PRIVILEGES_ZFPF) {
    $Message .= '<p>';
    if ($ProcessPHAsCount)
        $Message .= '
        <b>Archive and replace the current working draft (CWD)</b> with a PHA imported from a JSON file that is compatible with the PSM-CAP App PHA schema.<br />
        Maximum file size '.MAX_FILE_SIZE_ZFPF/1000000 .' MB (megabytes).<br />
            <input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_FILE_SIZE_ZFPF.'" />
            <input type="file" name="file_1_replace_cwd_pha_json" /><br />
            <input type="submit" name="pha_import_replace_cwd" value="Replace CWD with JSON file" /></p>';
    else {
        if (!$RowsReturned['t0pha'])
            $Message .= '
            <b>Nothing found</b> for the current process, neither draft nor issued, and<br />
            no templates were found in this PSM-CAP App deployment. Either:<br />
            * ask an app admin to import a template PHA, or<br />';
        $Message .= '
        * Import a PHA from a JSON file that is compatible with the PSM-CAP App PHA schema.<br />
        Maximum file size '.MAX_FILE_SIZE_ZFPF/1000000 .' MB (megabytes).<br />
            <input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_FILE_SIZE_ZFPF.'" />
            <input type="file" name="file_1_first_pha_json" /><br />
            <input type="submit" name="pha_import_first" value="Create from JSON file" /></p><p>
        Otherwise, creating a PHA from scratch is difficult, so try finding a template.<br />
            <input type="submit" name="pha_i0n" value="Create from scratch" /></p>';
    }
}
if ($AppAdmin == 'Yes')
    $Message .= '<p>
    Import a template PHA, accessible to all users in this PSM-CAP App deployment with PHA-edit privileges, from a JSON file that is compatible with the PSM-CAP App PHA schema.<br />
    Maximum file size '.MAX_FILE_SIZE_ZFPF/1000000 .' MB (megabytes).<br />
        <input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_FILE_SIZE_ZFPF.'" />
        <input type="file" name="file_1_template_pha_json" /><br />
        <input type="submit" name="pha_import_template" value="Import template" /></p>';
$Message .= '
</form>';
$Zfpf->close_connection_1s($DBMSresource);        
echo $Zfpf->xhtml_contents_header_1c().'<h1>
Process-hazard analysis (<a class="toc" href="glossary.php#pha" target="_blank">PHA</a>)</h1>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();
$Zfpf->save_and_exit_1c();

