<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// General security check. SPECIAL CASE set scurity tolken here, because called via administer1.php link
if (isset($_SESSION['StatePicked']['t0facility']) and isset($_SESSION['t0user_facility']) and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_union']) == MAX_PRIVILEGES_ZFPF and $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == MAX_PRIVILEGES_ZFPF) // Same privileges needed to get link from administer1.php.
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'union_i1m.php';
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'union_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get all union records, then split into: associated with the facility and remaining.
$DBMSresource = $Zfpf->credentials_connect_instance_1s();
$Conditions = 'No Condition -- All Rows Included';
list($SRU, $RRU) = $Zfpf->select_sql_1s($DBMSresource, 't0union', $Conditions);
unset($Conditions);
$Conditions[0] = array('k0facility', '=', $_SESSION['t0user_facility']['k0facility']);
list($SRFU, $RRFU) = $Zfpf->select_sql_1s($DBMSresource, 't0facility_union', $Conditions, array('k0union'));
$Count = 0;
$OtherCount = 0;
if ($RRFU) foreach ($SRFU as $V)
    $FacilityAssociated[] = $V['k0union'];
if ($RRU) foreach ($SRU as $V) {
    $NameDescription = $Zfpf->entity_name_description_1c($V, FALSE); // Don't shorten description.
    if (isset($FacilityAssociated) and in_array($V['k0union'], $FacilityAssociated)) {
        $DisplayInfo[$Count] = $NameDescription;
        $SortInfo[$Count] = $Zfpf->to_lower_case_1c($NameDescription);
        $_SESSION['SelectResults']['t0union'][$Count] = $V;
        ++$Count;
    }
    else {
        $OtherDisplayInfo[$OtherCount] = $NameDescription;
        $OtherSortInfo[$OtherCount] = $Zfpf->to_lower_case_1c($NameDescription);
        $_SESSION['SelectResults']['Othert0union'][$OtherCount] = $V;
        ++$OtherCount;
    }
}
$Zfpf->close_connection_1s($DBMSresource);
if (isset($SortInfo))
    array_multisort($SortInfo, $DisplayInfo, $_SESSION['SelectResults']['t0union']);
if (isset($OtherSortInfo))
    array_multisort($OtherSortInfo, $OtherDisplayInfo, $_SESSION['SelectResults']['Othert0union']);

$Message = '<h2>
Unions associated, in database, with facility '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).'</h2>
<form action="union_io03.php" method="post">';
if (isset($DisplayInfo)) {
    $Message .= '<p>';
    foreach ($_SESSION['SelectResults']['t0union'] as $K => $V) {
        if ($K)
            $Message .= '<br />';
        $Message .= '
        <input type="radio" name="selected" value="'.$K.'" ';
        if (!$K)
            $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
        $Message .= '/>'.$DisplayInfo[$K];
    }
    $Message .= '</p><p>
    <input type="submit" name="union_o1" value="View union summary" /></p>';
}
else
    $Message .= '<p>
    <b>None found.</b> Please contact your supervisor if this seems amiss.</p>';
if (isset($OtherDisplayInfo)) {
    $Message .= '<h2>
Other unions in database (for this app implementation)</h2><p>';
    foreach ($_SESSION['SelectResults']['Othert0union'] as $K => $V) {
        if ($K)
            $Message .= '<br />';
        $Message .= '
        <input type="radio" name="other_selected" value="'.$K.'" ';
        if (!$K)
            $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
        $Message .= '/>'.$OtherDisplayInfo[$K];
    }
    $Message .= '</p><p>
    <input type="submit" name="union_associate_1" value="Associate union with facility" /></p>';
}
$Message .= '<p>
<input type="submit" name="union_i0n" value="Insert new union record" /></p>';

echo $Zfpf->xhtml_contents_header_1c().$Message.'
</form>
<form action="administer1.php" method="post"><p>
    <input type="submit" value="Back to administration" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

