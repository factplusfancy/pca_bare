<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// General security check. SPECIAL CASE set scurity tolken here, because called via administer1.php link
if (isset($_SESSION['StatePicked']['t0owner'])) // Same privileges needed to get link from administer1.php or practice_o1.php.
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'contractor_i1m.php';
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'contractor_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);
$OwnerContractorPrivilegesStrlen = 0; // Use zero to allow greater-than comparison later.
if (isset($_SESSION['t0user_owner'])) {
    $OwnerContractorPrivilegesStrlen = strlen($Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor']));
    $Conditions[0] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner']);
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions, array('k0contractor'));
    if ($RROC) foreach ($SROC as $K => $V) {
        $Conditions[0] = array('k0contractor', '=', $V['k0contractor']);
        list($SRC, $RRC) = $Zfpf->select_sql_1s($DBMSresource, 't0contractor', $Conditions);
        if ($RRC != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $DisplayInfo[$K] = $Zfpf->entity_name_description_1c($SRC[0]);
        $SortInfo[$K] = $Zfpf->to_lower_case_1c($DisplayInfo[$K]);
        $_SESSION['SelectResults']['t0contractor'][$K] = $SRC[0];
        $_SESSION['Scratch']['PlainText']['AssociatedContractorPrimaryKeys'][$K] = $V['k0contractor']; // Used in contractor_associate code.
    }
    $Zfpf->close_connection_1s($DBMSresource);
    if (isset($SortInfo))
        array_multisort($SortInfo, $DisplayInfo, $_SESSION['SelectResults']['t0contractor']);
}
elseif (isset($_SESSION['t0user_contractor'])) { // Setup to echo only one radio button, for the currently selected contractor.
        $DisplayInfo[0] = $Zfpf->entity_name_description_1c($_SESSION['StatePicked']['t0contractor']);
        $_SESSION['SelectResults']['t0contractor'][0] = $_SESSION['StatePicked']['t0contractor'];
}
else
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
$Message = '<h2>
Contractors associated with '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).', the Owner/Operator,<br />
whose information you have the privilege to view.</h2>
<form action="contractor_io03.php" method="post">';
if (isset($DisplayInfo)) {
    $Message .= '<p>';
    foreach ($_SESSION['SelectResults']['t0contractor'] as $K => $V) {
        if ($K)
            $Message .= '<br />';
        $Message .= '
        <input type="radio" name="selected" value="'.$K.'" ';
        if (!$K)
            $Message .= 'checked="checked" '; // Select the first radio button by default to ensure something is posted (unless a hacker is tampering).
        $Message .= '/>'.$DisplayInfo[$K];
    }
    $Message .= '</p><p>
    <input type="submit" name="contractor_o1" value="View contractor summary" /></p>';
}
else
    $Message .= '<p>
    <b>None found.</b> Please contact your supervisor if this seems amiss.</p>';
if ($OwnerContractorPrivilegesStrlen >= strlen(MID_PRIVILEGES_ZFPF) and $UserGlobalDBMSPrivileges != LOW_PRIVILEGES_ZFPF) // See 0read_me_psm_cap_app_standards.txt
    $Message .= '<p>
    <input type="submit" name="contractor_associate_1" value="Associate contractor with owner" /></p><p>
    Create records for a new contractor and its first admin. This involves creating new contractor, user, user-contractor, owner-contractor, and, if currently selected, user-facility, and user-process records.<br />
    <input type="submit" name="contractor_i0n" value="Create records for a new contractor" /></p>';
else {
    if ($OwnerContractorPrivilegesStrlen < strlen(MID_PRIVILEGES_ZFPF))
        $Message .= '<p><b>
        User-Owner Privileges Notice</b>: You lack privileges to create a new contractor and its first admin.</p>';
    if ($UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF)
        $Message .= '<p><b>
        Global Privileges Notice</b>: You have privileges to neither create nor edit PSM-CAP App records.</p>';
    $Message .= '<p>
    If you need additional privileges, please contact your supervisor or a PSM-CAP App administrator.</p>';
}
echo $Zfpf->xhtml_contents_header_1c().$Message.'
</form>
<form action="administer1.php" method="post"><p>
    <input type="submit" value="Back to administration" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

