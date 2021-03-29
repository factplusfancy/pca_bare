<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// General security check. SPECIAL CASE set scurity tolken here, because called via administer1.php link
if (isset($_SESSION['StatePicked']['t0contractor']) and isset($_SESSION['t0user_contractor'])) // Same privileges needed to get link from administer1.php.
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'owner_i1m.php';
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'owner_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

$Conditions[0] = array('k0contractor', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor']);
$DBMSresource = $Zfpf->credentials_connect_instance_1s();
list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions, array('k0owner'));
if ($RROC) foreach ($SROC as $K => $V) {
    $Conditions[0] = array('k0owner', '=', $V['k0owner']);
    list($SRO, $RRO) = $Zfpf->select_sql_1s($DBMSresource, 't0owner', $Conditions);
    if ($RRO != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $DisplayInfo[$K] = $Zfpf->entity_name_description_1c($SRO[0]);
    $SortInfo[$K] = $Zfpf->to_lower_case_1c($DisplayInfo[$K]);
    $_SESSION['SelectResults']['t0owner'][$K] = $SRO[0];
}
$Zfpf->close_connection_1s($DBMSresource);
if (isset($SortInfo))
    array_multisort($SortInfo, $DisplayInfo, $_SESSION['SelectResults']['t0owner']);

$Message = '<h2>
Owners associated with contractor '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']).'<br />
whose information you have the privilege to view.</h2>
<form action="owner_io03.php" method="post">';
if (isset($DisplayInfo)) {
    $Message .= '<p>';
    foreach ($_SESSION['SelectResults']['t0owner'] as $K => $V) {
        if ($K)
            $Message .= '<br />';
        $Message .= '
        <input type="radio" name="selected" value="'.$K.'" ';
        if (!$K)
            $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
        $Message .= '/>'.$DisplayInfo[$K];
    }
    $Message .= '</p><p>
    <input type="submit" name="owner_o1" value="View owner summary" /></p>';
}
else
    $Message .= '<p>
    <b>None found.</b> Please contact your supervisor if this seems amiss.</p>';

echo $Zfpf->xhtml_contents_header_1c().$Message.'
</form>
<form action="administer1.php" method="post"><p>
    <input type="submit" value="Back to administration" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

