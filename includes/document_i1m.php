<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check.  Redundant because this a "require" file, for example, by practice_o1.php
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'document_i1m.php' or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

if (!isset($_SESSION['t0user_process'])) {
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    Compliance-Practice Documents</h1>
    <p>You currently have not selected a process, and compliance-practice documents must be associated with a process.</p>
    <p>Contact your supervisor or an app admin for assistance.</p>
    <form action="contents0_s_practice.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}
// Define SQL WHERE clause for all documents associated with the current practice.
// If the user is associated with the a practice, they will be able to view these.
$Conditions[0] = array('k0practice', '=', $_SESSION['t0user_practice']['k0practice'], 'AND');
$Conditions[1] = array('k0process', '=', $_SESSION['t0user_process']['k0process']);
$DBMSresource = $Zfpf->credentials_connect_instance_1s();
list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0practice_document', $Conditions);
$Message = '<p>
Currently selected practice:<br />
'.$Zfpf->entity_name_description_1c($_SESSION['StatePicked']['t0practice'], FALSE).'</p>';
if (!$RR)
    $Message .= '<p>
    No document records were found, associated with the selected practice.</p>';
else {
    unset($Conditions);
    foreach ($SR as $V)
        $Conditions[] = array('k0document', '=', $V['k0document'], 'OR');
    unset($Conditions[--$RR][3]); // remove the final, hanging, 'OR'.
    list($_SESSION['SelectResults']['t0document'], $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0document', $Conditions);
    if (!$RR) // There must be at least one t0document row here (one for each t0practice_document row.)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Message .= '
    <form action="document_io03.php" method="post">
    <p><b>Documents associated with this practice:</b><br />';
    // Sort by lowercase c5name and c6description
    foreach ($_SESSION['SelectResults']['t0document'] as $K => $V) {
        $DisplayInfo[$K] = $Zfpf->entity_name_description_1c($V);
        $SortInfo[$K] = $Zfpf->to_lower_case_1c($DisplayInfo[$K]);
    }
    array_multisort($SortInfo, $DisplayInfo, $_SESSION['SelectResults']['t0document']);
    foreach ($_SESSION['SelectResults']['t0document'] as $K => $V) {
        $Message .= '
        <input type="radio" name="selected" value="'.$K.'" ';
        if ($K == 0)
            $Message .= 'checked="checked" '; // Select the first document by default to ensure something is posted (unless a hacker is tampering).
        $Message .= '/>'.$DisplayInfo[$K].'<br />';
    }
    $Message .= '</p><p>
        <input type="submit" name="document_o1" value="Select document" /></p>
        </form>';
}
$Zfpf->close_connection_1s($DBMSresource);

if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF and $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']) == MAX_PRIVILEGES_ZFPF) // Require update... privileges to start a new record -- pretty big deal to create a new document.
    $Message .= '
    <form action="document_io03.php" method="post"><p>
        <input type="submit" name="document_i0n" value="New document" /></p>
    </form>';
else
    $Message .= '<p><b>
    Privileges Notice</b>: You don\'t have privileges to create a new document. This requires maximum global-DBMS and practice privileges.</p>';
echo $Zfpf->xhtml_contents_header_1c().'<h1>
Compliance-Practice Documents</h1>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

