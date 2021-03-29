<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check.  Redundant because this is a required file.
// Cannot screen for process being set because CM can apply contractor-, owner-, facility-, or process-wide.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'cms_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Define SQL WHERE clause for all startup-not-yet-authorized change-management records that the user is authorized to view.
$Conditions[] = array('k0user_of_psr', '=', 0, 'AND (');
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
if ($RowsReturned['t0change_management']) {
    // Sort $_SESSION['SelectResults']['t0change_management'] descending (SORT_DESC) by k0change_management, which will sort newest to oldest due to time-stamp embedded in k0change_management.
    foreach ($_SESSION['SelectResults']['t0change_management'] as $V)
        $k0change_management[] = $V['k0change_management'];
    array_multisort($k0change_management, SORT_DESC, $_SESSION['SelectResults']['t0change_management']);
    $UnauthMessage = '';
    $NoNeedMessage = '';
    $SomethingSelected = 0;
    foreach ($_SESSION['SelectResults']['t0change_management'] as $K => $V) {
        if (in_array('Yes', $Zfpf->decrypt_decode_1c($V['c6cm_applies_checks']))) {
            $UnauthMessage .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if (!$SomethingSelected++) // Ensure one radio is selected.
                $UnauthMessage .= 'checked="checked" ';
            $UnauthMessage .= '/><b>'.$Zfpf->decrypt_1c($V['c5name']).'</b>';
            if ($Zfpf->decrypt_1c($V['c6description']) != '[Nothing has been recorded in this field.]')
                $UnauthMessage .= ' -- '.substr($Zfpf->decrypt_1c($V['c6description']), 0, 160); // Truncate this to about 2 lines (160 characters).
            $UnauthMessage .= '<br />';
        }
        elseif (!$V['k0user_of_applic_approver']) {
            $NoNeedMessage .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if (!$SomethingSelected++) // Ensure one radio is selected.
                $NoNeedMessage .= 'checked="checked" ';
            $NoNeedMessage .= '/><b>'.$Zfpf->decrypt_1c($V['c5name']).'</b>';
            if ($Zfpf->decrypt_1c($V['c6description']) != '[Nothing has been recorded in this field.]')
                $NoNeedMessage .= ' -- '.substr($Zfpf->decrypt_1c($V['c6description']), 0, 160); // Truncate this to about 2 lines (160 characters).
            $NoNeedMessage .= '<br />';
        }
    }
    if ($UnauthMessage)
        $UnauthMessage = '<p><b>Unauthorized for startup</b><br/>'.$UnauthMessage.'</p>';
    if ($NoNeedMessage)
        $NoNeedMessage = '<p><b>Awaiting approval that change management is not required</b><br/>'.$NoNeedMessage.'</p>';
    if ($UnauthMessage or $NoNeedMessage)
        $Message = '
        <form action="cms_io03.php" method="post"><h2>Change Name</h2>'.$UnauthMessage.$NoNeedMessage.'<p>
            <input type="submit" name="cms_o1" value="View record" /></p>';
}
if (!isset($Message))
    $Message = '<p><b>
    Nothing open found.</b> Neither change-management records unauthorized for startup nor unapproved applicability determinations were found for -- as applicable -- the process, facility, owner, or contractor that you are currently associated with.</p>
    <form action="cms_io03.php" method="post">';
$Message .= '<p>
    View all records, including startup-authorized records and approved applicability determinations.<br />
    <input type="submit" name="cms_i0mall" value="View all records" /></p><p>';
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != LOW_PRIVILEGES_ZFPF) // Need at least INSERT global privileges to start a new record.
    $Message .= '
    <input type="submit" name="cms_i0n" value="Start new determination" /></p>';
else
    $Message .= '<p><b>
    Global Privileges Notice</b>: You have privileges to neither create nor edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
$Message .= '
</form>';
echo $Zfpf->xhtml_contents_header_1c('Select Change').'<h1>
Change-Management System</h1>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

