<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check.  Redundant because this is a required file.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'incident_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

if (!isset($_SESSION['StatePicked']['t0process'])) {
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    No Process Selected</h1><p>
    Select a process to view incident-investigation records.</p>
    <form action="contents0_s_practice.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// Define SQL WHERE clause for all incident investigations associated with the current process.
// If the user is associated with the incident-investigation practice for this process, they will be able to view these and download supporting files.
$Conditions[0] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process']);

$DBMSresource = $Zfpf->credentials_connect_instance_1s();
list($_SESSION['SelectResults']['t0incident'], $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0incident', $Conditions);
$Zfpf->close_connection_1s($DBMSresource);
if ($RR) {
    $Message = '
    <form action="incident_io03.php" method="post"><p>
    <b>Incident Name:</b><br />';
    // Sort $_SESSION['SelectResults']['t0incident'] descending (SORT_DESC) by k0incident, which will sort newest to oldest due to time-stamp embedded in k0incident.
    foreach ($_SESSION['SelectResults']['t0incident'] as $V)
        $k0incident[] = $V['k0incident'];
    array_multisort($k0incident, SORT_DESC, $_SESSION['SelectResults']['t0incident']);
    foreach ($_SESSION['SelectResults']['t0incident'] as $K => $V) {
        $Message .= '
        <input type="radio" name="selected" value="'.$K.'" ';
        if ($K == 0)
            $Message .= 'checked="checked" '; // Select the first incident by default to ensure something is posted (unless a hacker is tampering).
        $Message .= '/><b>'.$Zfpf->decrypt_1c($V['c5name']).'</b>'.' ('.$Zfpf->decrypt_1c($V['c5status']).')';
        if ($Zfpf->decrypt_1c($V['c6summary_description']) != '[Nothing has been recorded in this field.]')
            $Message .= ' -- '.substr($Zfpf->decrypt_1c($V['c6summary_description']), 0, 160); // Truncate the description at about 2 lines (160 characters).
        $Message .= '<br />';
    }
    $Message .= '</p><p>
        <input type="submit" name="incident_o1" value="Select investigation" /></p>
    </form>';
}
else
    $Message = '<p><b>
    No incident investiagtons were found</b> for the current process, neither draft nor approved. Please contact your supervisor if this seems amiss.</p>';
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != LOW_PRIVILEGES_ZFPF) // Need at least INSERT global privileges to start a new record.
    $Message .= '
    <form action="incident_io03.php" method="post"><p>
        <input type="submit" name="incident_i0n" value="Start new investigation" /></p>
    </form>';
else
    $Message .= '<p><b>
    Global Privileges Notice</b>: You have privileges to neither create nor edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
echo $Zfpf->xhtml_contents_header_1c('Select Incident').'<h1>
Incident Investigation</h1>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

