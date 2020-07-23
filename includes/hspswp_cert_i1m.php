<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check.  Redundant because this is a required file.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'hspswp_cert_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

if (!isset($_SESSION['StatePicked']['t0process'])) {
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    No Process Selected</h1><p>
    Select a process to view certification records for hazardous-substance procedures and safe-work practices.</>
    <form action="contents0_s_practice.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// Define SQL WHERE clause for all hspswp_cert associated with the current process.
// If the user is associated with the hspswp_cert practice for this process, they will be able to view these and download supporting files.
$Conditions[0] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process']);

$DBMSresource = $Zfpf->credentials_connect_instance_1s();
list($_SESSION['SelectResults']['t0certify'], $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0certify', $Conditions);
$Zfpf->close_connection_1s($DBMSresource);
if ($RR) {
    $Message = '
    <form action="hspswp_cert_io03.php" method="post"><p>
    <b>Date Certified:</b><br />';
    // Sort $_SESSION['SelectResults']['t0certify'] descending (SORT_DESC) by k0certify, which will sort newest to oldest due to time-stamp embedded in k0certify.
    foreach ($_SESSION['SelectResults']['t0certify'] as $V)
        $k0certify[] = $V['k0certify'];
    array_multisort($k0certify, SORT_DESC, $_SESSION['SelectResults']['t0certify']);
    foreach ($_SESSION['SelectResults']['t0certify'] as $K => $V) {
        $Message .= '
        <input type="radio" name="selected" value="'.$K.'" ';
        if ($K == 0)
            $Message .= 'checked="checked" '; // Select the first hspswp_cert by default to ensure something is posted (unless a hacker is tampering).
        $Message .= '/>'.$Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($V['c5ts_ae_leader'])).'<br />';
    }
    $Message .= '</p><p>
        <input type="submit" name="hspswp_cert_o1" value="Select certification" /></p>
    </form>';
}
else
    $Message = '<p><b>
    No certification records were found</b> for the current process\'s hazardous-substance procedures and safe-work practices. Please contact your supervisor if this seems amiss.</p>';
$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);
if ($_SESSION['t0user']['k0user'] == $_SESSION['StatePicked']['t0process']['k0user_of_leader'] and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF) // Means user is process PSM lead and, as expected, has SIUD global DBMS privileges.
    $Message .= '
    <form action="hspswp_cert_io03.php" method="post"><p>
        <input type="submit" name="hspswp_cert_i0n" value="Make new certification" /></p>
    </form>';
else {
    if ($_SESSION['t0user']['k0user'] != $_SESSION['StatePicked']['t0process']['k0user_of_leader'])
        $Message .= '<p><b>
        Certify Privileges Notice</b>: Only the process PSM leader may certify that the hazardous-substance procedures and safe-work practices applicable to the process are current and accurate.</p>';
    if ($UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
        $Message .= '<p><b>
        Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
}
echo $Zfpf->xhtml_contents_header_1c().'<h2>
Certifications for hazardous-substance procedures and safe-work practices</h2>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

