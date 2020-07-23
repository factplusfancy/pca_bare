<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check.  Redundant because this is a required file.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'contractor_qual_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

$DBMSresource = $Zfpf->credentials_connect_instance_1s();
if (isset($_SESSION['t0user_owner']))
    $Conditions[0] = array('k0owner', '=', $_SESSION['t0user_owner']['k0owner']); // User is employee of the owner.
elseif (isset($_SESSION['t0owner_contractor']))
    $Conditions[0] = array('k0owner', '=', $_SESSION['t0owner_contractor']['k0owner']); // User is employee of contractor associated with the owner (that user currently selected.)
if (isset($Conditions)) {
    list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions);
    unset($Conditions);
    if ($RROC) {
        foreach ($SROC as $V)
            $Conditions[] = array('k0contractor', '=', $V['k0contractor'], 'OR');
        unset($Conditions[--$RROC][3]); // remove the final, hanging, 'OR'.
    }
}
elseif (isset($_SESSION['t0user_contractor']))
    $Conditions[0] = array('k0contractor', '=', $_SESSION['t0user_contractor']['k0contractor']); // User is employee of contractor, and user cannot or has not selected an owner.
if (isset($Conditions)) {
    list($_SESSION['SelectResults']['t0contractor_qual'], $RowsReturned['t0contractor_qual']) = $Zfpf->select_sql_1s($DBMSresource, 't0contractor_qual', $Conditions);
    if ($RowsReturned['t0contractor_qual'] > 0) {
        // For efficiency, sort $_SESSION['SelectResults']... by k0...
        foreach ($_SESSION['SelectResults']['t0contractor_qual'] as $V)
            $k0contractors[] = $V['k0contractor'];
        array_multisort($k0contractors, $_SESSION['SelectResults']['t0contractor_qual']);
        // Sort $_SESSION['SelectResults']['t0contractor_qual'] ascending by the contractor name, the qualification focus, and also sort other data to echo.
        foreach ($_SESSION['SelectResults']['t0contractor_qual'] as $V) {
            if (!isset($SRC) or $SRC[0]['k0contractor'] != $V['k0contractor']) {
                // Don't rely on $_SESSION['t0user_contractor'] because one contractor might be looking at another contractors info.
                $Conditions2[0] = array('k0contractor', '=', $V['k0contractor']);
                list($SRC, $RRC) = $Zfpf->select_sql_1s($DBMSresource, 't0contractor', $Conditions2);
                if ($RRC != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                $Name = $Zfpf->decrypt_1c($SRC[0]['c5name']);
                $City = $Zfpf->decrypt_1c($SRC[0]['c5city']);
                $StateProvince = $Zfpf->decrypt_1c($SRC[0]['c5state_province']);
                $Country = $Zfpf->decrypt_1c($SRC[0]['c5country']);
            }
            $Focuses[] = $Zfpf->decrypt_1c($V['c5focus']);
            $Names[] = $Name; // TO DO test this file with multiple contractors and focuses
            $Cities[] = $City;
            $StateProvinces[] = $StateProvince;
            $Countries[] = $Country;
        }
        array_multisort($Names, $Focuses, $Cities, $StateProvinces, $Countries, $_SESSION['SelectResults']['t0contractor_qual']);
        // Make HTML from the sorted results.
        $Message = '<h2>
        Select a contractor-qualification record.</h2>
        <form action="contractor_qual_io03.php" method="post"><p>';
        foreach ($_SESSION['SelectResults']['t0contractor_qual'] as $K => $V) {
            $Message .= '
            <input type="radio" name="selected" value="'.$K.'" ';
            if ($K == 0) // Select the first incident by default to ensure something is posted (unless a hacker is tampering).
                $Message .= 'checked="checked" ';
            $Message .= '/><b>'.$Names[$K].',</b> '.$Cities[$K].', ';
            if ($StateProvinces[$K] != '[Nothing has been recorded in this field.]')
                $Message .= $StateProvinces[$K].', ';
            $Message .= $Countries[$K].' -- '.$Focuses[$K];
            $Message .= '<br />';
        }
        $Message .= '</p><p>
            <input type="submit" name="contractor_qual_o1" value="Select qualification" /></p>
        </form>';
    }
    else
        $Message = '<p><b> No contractor-qualification records were found</b> for the contractors whose records you have permission to view. Please contact your supervisor if this seems amiss.</p>';
}
else
    $Message = '<p>You do not have permission to view any contractor records. You or your employer need to be associated with a contractor to view their qualification records. Please contact your supervisor if this seems amiss.</p>';
$Zfpf->close_connection_1s($DBMSresource);
// Only a contractor can start a new qualification record about themselves.
// Need at least INSERT global privileges to start a new record.
if (isset($_SESSION['t0user_contractor']) and $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != LOW_PRIVILEGES_ZFPF)
    $Message .= '
    <form action="contractor_qual_io03.php" method="post"><p>
        <input type="submit" name="contractor_qual_i0n" value="Insert new qualification record" /></p>
    </form>';
elseif ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == LOW_PRIVILEGES_ZFPF)
    $Message .= '<p><b>
    Global Privileges Notice</b>: You have privileges to neither insert new nor update PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
echo $Zfpf->xhtml_contents_header_1c('Select Record').'<h1>
Contractor Qualification</h1>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

