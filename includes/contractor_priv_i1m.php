<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check, in addition to security in file that required this file. 
// t0contractor_priv supplements information in t0user_facility, so editing user only needs to be associated with an owner and a facility. 
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'contractor_priv_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

if (!isset($_SESSION['StatePicked']['t0facility'])) {
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    No Facility Selected</h1><p>
    Select a facility to see contractor-individual entrance privileges and records.</>
    <form action="contents0_s_practice.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

function contractor_user_facility($Zfpf, $DBMSresource, $k0contractor, $ContractorUserRadios, $ContractorName = FALSE) {
    $Conditions[0] = array('k0contractor', '=', $k0contractor);
    if (!$ContractorName) {
        list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0contractor', $Conditions);
        if ($RR != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows returned: '.@$RR);
        $ContractorName = $Zfpf->decrypt_1c($SR[0]['c5name']);
    }
    $ConditionsUF[0] = array('k0facility', '=', $_SESSION['StatePicked']['t0facility']['k0facility'], 'AND (');
    list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0user_contractor', $Conditions);
    if ($RR) { // Every contractor should have at least one user, but don't shutdown to allow contractor list to continue.
        foreach ($SR as $V)
            $ConditionsUF[] = array('k0user', '=', $V['k0user'], 'OR');
        $ConditionsUF[$RR][3] = ')'; // Replace final 'OR' with ')'
        list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0user_facility', $ConditionsUF);
        if ($RR) {
            if (isset($_SESSION['SelectResults']['t0user_facility']))
                $i = count($_SESSION['SelectResults']['t0user_facility']);
            else
                $i = 0;
            foreach ($SR as $V) {
                $UserFacility[$i] = $V;
                $_SESSION['SelectResults']['Key'][$i]['k0contractor'] = $k0contractor; // Don't need to array_multisort because all the same for this contactor.
                $UserJobInfo = $Zfpf->user_job_info_1c($V['k0user']);
                $DisplayUserInfo[$i] = $UserJobInfo['NameTitle'].' '.$UserJobInfo['WorkEmail'];
                $SortUserInfo[$i] = $Zfpf->to_lower_case_1c($DisplayUserInfo[$i]);
                $i++;
            }
            array_multisort($SortUserInfo, $DisplayUserInfo, $UserFacility); // This reindexes the numeric arrays, fixed below.
            $i = $i - $RR; // Puts $i back to before above foreach.
            $ContractorUserRadios .= '<p><b>'.$ContractorName.'</b>';
            foreach ($DisplayUserInfo as $K => $V) {
                $ContractorUserRadios .= '<br />
                <input type="radio" name="selected" value="'.$i.'" ';
                if (!$i) // First user of first contractor.
                    $ContractorUserRadios .= 'checked="checked" '; // Select the very first radio button by default to ensure something is posted (unless a hacker is tampering).
                $ContractorUserRadios .= '/>'.$V;
                $_SESSION['SelectResults']['t0user_facility'][$i] = $UserFacility[$K];
                $i++;
            }
            $ContractorUserRadios .= '</p>';
        }
    }
    return $ContractorUserRadios;
}

if (isset($_SESSION['SelectResults']['t0user_facility']))
    unset($_SESSION['SelectResults']['t0user_facility']);
$DBMSresource = $Zfpf->credentials_connect_instance_1s();
if (isset($_SESSION['StatePicked']['t0contractor'])) // Get all contractor individuals with a user-facility record.
    $ContractorUserRadios = contractor_user_facility($Zfpf, $DBMSresource, $_SESSION['StatePicked']['t0contractor']['k0contractor'], '', $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']));
else { // user is an employee of the owner.
    $ContractorUserRadios = '';
    if (!isset($_SESSION['StatePicked']['t0owner']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Conditions[0] = array('k0owner', '=', $_SESSION['StatePicked']['t0owner']['k0owner']);
    list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions);
    if ($RR) foreach ($SR as $V)
        $ContractorUserRadios = contractor_user_facility($Zfpf, $DBMSresource, $V['k0contractor'], $ContractorUserRadios);
}
$Zfpf->close_connection_1s($DBMSresource);
if ($ContractorUserRadios)
    $Message = '<p>
    Select a contractor individual.</p>
    <form action="contractor_priv_io03.php" method="post">'
    .$ContractorUserRadios.'<p>
        <input type="submit" name="contractor_priv_o1" value="Select record" /></p>
    </form>';
else {
    $CurrentUserInfo = $Zfpf->current_user_info_1c();
    $Message = '<p><b>No contractor individuals found</b>, who are associated with both the facility and ';
    if (isset($_SESSION['StatePicked']['t0contractor']))
        $Message .= $CurrentUserInfo['Employer'];
    else
        $Message .= 'a contractor approved by '.$CurrentUserInfo['Employer'].', the Owner/Operator';
    $Message .= '. For assistance contact the facility '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader or the Owner/Operator\'s contractor contact.</p>';
}
echo $Zfpf->xhtml_contents_header_1c('PSM CAP').'<h2>
Entrance Privileges and Records of each Contractor Individual</h2>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

