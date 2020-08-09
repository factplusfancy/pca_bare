<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check, in addition to security in file that required this file.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'audit_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

$UserGlobalDBMSpriv = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$ProcessAudits = '';
$TemplateAudits = '';
$Conditions[0] = array('k0audit', '<', 100000); // Gets templates
if (isset($_SESSION['StatePicked']['t0process']['k0process']))
    $Conditions[1] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process'], '', 'OR '); // Gets process reports.
list($_SESSION['SelectResults']['t0audit'], $RowsReturned['t0audit']) = $Zfpf->one_shot_select_1s('t0audit', $Conditions);
if ($RowsReturned['t0audit'] > 0) {
    // Sort $_SESSION['SelectResults']['t0audit'] descending (SORT_DESC) by k0audit, which will sort newest to oldest due to time-stamp embedded in k0audit. Templates with k0audit below 100000 will be last.
    foreach ($_SESSION['SelectResults']['t0audit'] as $V)
        $k0audit[] = $V['k0audit'];
    array_multisort($k0audit, SORT_DESC, $_SESSION['SelectResults']['t0audit']);
    foreach ($_SESSION['SelectResults']['t0audit'] as $K => $V) {
        if ($V['k0audit'] < 100000) {
            $TemplateAudits .= '<br />
            <input type="radio" name="selected" value="'.$K.'" ';
            if ($K == 0)
                $TemplateAudits .= 'checked="checked" '; // Ensure something is posted.
            $TemplateAudits .= '/>'.$Zfpf->decrypt_1c($V['c5name']);
        }
        else {
            $ProcessAudits .= '<br />
            <input type="radio" name="selected" value="'.$K.'" ';
            if ($K == 0)
                $ProcessAudits .= 'checked="checked" '; // Ensure something is posted.
            $ProcessAudits .= '/>';
            if ($V['k0user_of_certifier'] == 0)
                $ProcessAudits .= $Zfpf->decrypt_1c($V['c5name']).' [working draft]';
            else
                $ProcessAudits .= $Zfpf->decrypt_1c($V['c5name']).' as of '.$Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($V['c5ts_as_of'])); // The "as of" date serves like a name.
        }
    }
}
$Message = '
<form action="audit_io03.php" method="post">';
if ($ProcessAudits)
    $Message .= '<p><b>
    Reports for the currently selected process:</b>'.$ProcessAudits.'</p>';
elseif (!isset($_SESSION['StatePicked']['t0process']))
    $Message .= '<p><b>
    No process selected.</b> These documents (except templates) must be associated with a process.</p>';
else
    $Message .= '<p><b>
    No reports found</b> for the currently selected process, neither draft nor issued. Please contact your supervisor if this seems amiss.</p>';
if ($TemplateAudits)
    $Message .= '<p><b>
    Template reports:</b>'.$TemplateAudits.'</p>';
else
    $Message .= '<p><b>
    No template reports found</b>. Typically at least one is included with this app on installation. Please contact an app admin for assistance.</p>';
if ($ProcessAudits or $TemplateAudits) {
    $Message .= '<p>
    <input type="submit" name="audit_o1" value="View selected" /></p>';
    if (isset($_SESSION['StatePicked']['t0process']) and $UserGlobalDBMSpriv != LOW_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
        $Message .= '<p>
        Create a draft report from the above-selected template or issued report.<br />
        <input type="submit" name="audit_template" value="Create draft from selected" /></p>';
}
if (isset($_SESSION['StatePicked']['t0process']) and $UserGlobalDBMSpriv != LOW_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
    $Message .= '<p>
    Creating a report from scratch is difficult, so try finding a template.<br />
    <input type="submit" name="audit_i0n" value="Create draft from scratch" /></p>';
if ($UserGlobalDBMSpriv == LOW_PRIVILEGES_ZFPF and $UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
    $Message .= '<p>
    You don\'t have privileges to create a new report. Contact app admin.</p>';
$Message .= '
</form>';
echo $Zfpf->xhtml_contents_header_1c().'<h2>
Audit, hazard review, inspection, and similar observation-based reports.</h2>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

