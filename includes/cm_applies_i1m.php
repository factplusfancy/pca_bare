<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check.  Redundant because this is a required file.
// Cannot screen for process being set because CM can apply contractor-, owner-, facility-, or process-wide.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'cm_applies_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Define SQL WHERE clause for all unapproved change-management applicability determinations that the user is authorized to view.
$Conditions[] = array('k0user_of_applic_approver', '=', 0, 'AND (');
if (isset($_SESSION['StatePicked']['t0process']['k0process']))
    $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0process']['k0process'], 'OR');
if (isset($_SESSION['StatePicked']['t0facility']['k0facility']))
    $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0facility']['k0facility'], 'OR');
if (isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
    if (!isset($_SESSION['StatePicked']['t0owner']['k0owner'])) // Contractor-wide change case.
        $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], ')');
    else
        $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], 'OR');
if (isset($_SESSION['StatePicked']['t0owner']['k0owner']))
    $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0owner']['k0owner'], ')');

$DBMSresource = $Zfpf->credentials_connect_instance_1s();
list($_SESSION['SelectResults']['t0change_management'], $RowsReturned['t0change_management']) = $Zfpf->select_sql_1s($DBMSresource, 't0change_management', $Conditions);
$Zfpf->close_connection_1s($DBMSresource);
if ($RowsReturned['t0change_management'] > 0) {
    $Message = '<form action="cms_io03.php" method="post"><h2> Change Name</h2><p>
    <b>Unapproved applicability determinations</b><br />';
    // Sort $_SESSION['SelectResults']['t0change_management'] descending (SORT_DESC) by k0change_management, which will sort newest to oldest due to time-stamp embedded in k0change_management.
    foreach ($_SESSION['SelectResults']['t0change_management'] as $V) {
        $k0change_management[] = $V['k0change_management'];
    }
    array_multisort($k0change_management, SORT_DESC, $_SESSION['SelectResults']['t0change_management']);
    foreach ($_SESSION['SelectResults']['t0change_management'] as $K => $V) {
        $Message .= '
        <input type="radio" name="selected" value="'.$K.'" ';
        if ($K == 0) // Select the first change_management by default to ensure something is posted (unless a hacker is tampering).
            $Message .= 'checked="checked" ';
        $Message .= '/><b>'.$Zfpf->decrypt_1c($V['c5name']).'</b>';
        if ($Zfpf->decrypt_1c($V['c6description']) != '[Nothing has been recorded in this field.]')
            $Message .= ' -- '.substr($Zfpf->decrypt_1c($V['c6description']), 0, 160); // Truncate this to about 2 lines (160 characters).
        $Message .= '<br />';
    }
    $Message .= '</p><p>
        <input type="submit" name="cm_applies_o1" value="View record" /></p>
    </form>';
}
else
    $Message = '<p><b>
    Nothing Open Found.</b> No unapproved change-management applicability determinations were found for -- as applicable -- the process, facility, owner, or contractor that you are currently associated with. Please contact your supervisor if this seems amiss.</p>';
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != LOW_PRIVILEGES_ZFPF) // Need at least INSERT global privileges to start a new record.
    $Message .= '
    <form action="cms_io03.php" method="post"><p>
    <input type="submit" name="cms_i0n" value="Start new determination" /></p>
    </form>';
else
    $Message .= '<p><b>
    Global Privileges Notice</b>: You have privileges to neither create nor edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
echo $Zfpf->xhtml_contents_header_1c('Select Change').'<h1>
Change-Management Applicability Determination</h1><p>
With proper privileges, you may start a new applicability determination or edit one. Only unapproved determinations may be edited. To edit an approved applicability determination, first ask the appropriate PSM leader -- of a process, facility, owner, or contractor -- to cancel their approval using this app\'s Change Management System practice.</p>
'.$Message.'<p>
Approved applicability determinations may be viewed using this app\'s Change Management System practice.</p>
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

