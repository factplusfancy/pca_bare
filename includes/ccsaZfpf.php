<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This class serves the PHA code.

// CCSA means one of: cause, consequence, safeguard, or action

class ccsaZfpf {

    public function risk_rank_matrix($Zfpf) {
        return '
        <table border="1" cellspacing="" cellpadding="3">
            <caption><b>Risk-Ranking (and Priority) Matrix -- With Safeguards In Place</b></caption>
            <tr>
                <th>Severity =><br /><br />Likelihood</th>
                <th>'._1_SEVERITY_ZFPF.'</th>
                <th>'._2_SEVERITY_ZFPF.'</th>
                <th>'._3_SEVERITY_ZFPF.'</th>
                <th>'._4_SEVERITY_ZFPF.'</th>
                <th>'._5_SEVERITY_ZFPF.'</th>
            </tr>
            <tr>
                <th>'._1_LIKELIHOOD_ZFPF.'</th>
                <td>'.$Zfpf->risk_rank_1c(_1_SEVERITY_ZFPF, _1_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_2_SEVERITY_ZFPF, _1_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_3_SEVERITY_ZFPF, _1_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_4_SEVERITY_ZFPF, _1_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_5_SEVERITY_ZFPF, _1_LIKELIHOOD_ZFPF).'</td>
            </tr>
            <tr>
                <th>'._2_LIKELIHOOD_ZFPF.'</th>
                <td>'.$Zfpf->risk_rank_1c(_1_SEVERITY_ZFPF, _2_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_2_SEVERITY_ZFPF, _2_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_3_SEVERITY_ZFPF, _2_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_4_SEVERITY_ZFPF, _2_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_5_SEVERITY_ZFPF, _2_LIKELIHOOD_ZFPF).'</td>
            </tr>
            <tr>
                <th>'._3_LIKELIHOOD_ZFPF.'</th>
                <td>'.$Zfpf->risk_rank_1c(_1_SEVERITY_ZFPF, _3_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_2_SEVERITY_ZFPF, _3_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_3_SEVERITY_ZFPF, _3_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_4_SEVERITY_ZFPF, _3_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_5_SEVERITY_ZFPF, _3_LIKELIHOOD_ZFPF).'</td>
            </tr>
            <tr>
                <th>'._4_LIKELIHOOD_ZFPF.'</th>
                <td>'.$Zfpf->risk_rank_1c(_1_SEVERITY_ZFPF, _4_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_2_SEVERITY_ZFPF, _4_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_3_SEVERITY_ZFPF, _4_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_4_SEVERITY_ZFPF, _4_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_5_SEVERITY_ZFPF, _4_LIKELIHOOD_ZFPF).'</td>
            </tr>
            <tr>
                <th>'._5_LIKELIHOOD_ZFPF.'</th>
                <td>'.$Zfpf->risk_rank_1c(_1_SEVERITY_ZFPF, _5_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_2_SEVERITY_ZFPF, _5_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_3_SEVERITY_ZFPF, _5_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_4_SEVERITY_ZFPF, _5_LIKELIHOOD_ZFPF).'</td>
                <td>'.$Zfpf->risk_rank_1c(_5_SEVERITY_ZFPF, _5_LIKELIHOOD_ZFPF).'</td>
            </tr>
        </table><p></p>';
    }

    // $Context is always 'action' if called in obsresult_io03.php or action_io03.php where:
    //    $_SESSION['Scratch']['PlainText']['SecurityToken'] == 'incident_i1m.php' or
    //    $_SESSION['Scratch']['PlainText']['SecurityToken'] == 'audit_i1m.php' (from obsmethod_io03.php)
    // In PHA context, handles CCSA and other cases below.
    // $TableRoot allowed values: 'scenario' [of a PHA or HIRA], 'obsresult', or 'incident'
    public function html_form_array($Context, $Zfpf, $TableRoot = 'scenario', $arZfpf = FALSE) {
        if (substr($Context, 0, 9) == 'scenario_') {
            if ($Context == 'scenario_io0_2') {
                $NameAnchor = '<a id="c5name"></a>';
                $SeverityAnchor = '<a id="c5severity"></a>';
            }
            else {
                $NameAnchor = '<a id="'.$Context.'"></a>';
                $SeverityAnchor = '';
            }
            // See file 0read_me_psm_cap_app_standards.txt for $htmlFormArray specs.
            $htmlFormArray = array(
                // k0subprocess omitted
                'c5name' => array($NameAnchor.'Scenario Name', REQUIRED_FIELD_ZFPF),
                'c5type' => array('Scenario Type', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF, 'dropdown', array('Prior Incident', 'Human Factors', 'Facility Siting', 'Human Factors and Prior Incident', 'Facility Siting and Prior Incident', 'Human Factors and Facility Siting', 'All of Above', 'None')),
                'c5severity' => array($SeverityAnchor.'Severity with safeguards in place', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF, 'radio', array(_1_SEVERITY_ZFPF, _2_SEVERITY_ZFPF, _3_SEVERITY_ZFPF, _4_SEVERITY_ZFPF, _5_SEVERITY_ZFPF)),
                'c5likelihood' => array('Likelihood with safeguards in place', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF, 'radio', array(_1_LIKELIHOOD_ZFPF, _2_LIKELIHOOD_ZFPF, _3_LIKELIHOOD_ZFPF, _4_LIKELIHOOD_ZFPF, _5_LIKELIHOOD_ZFPF))
            );
        }
        elseif ($Context == 'cause')
            $htmlFormArray = array(
                'c5name' => array('Cause Name', REQUIRED_FIELD_ZFPF),
                'c6description' => array('Cause Description', '', C6SHORT_MAX_BYTES_ZFPF)
            );
        elseif ($Context == 'consequence')
            $htmlFormArray = array(
                'c5name' => array('Consequence Name', REQUIRED_FIELD_ZFPF),
                'c6description' => array('Consequence Description', '', C6SHORT_MAX_BYTES_ZFPF)
            );
        elseif ($Context == 'safeguard')
            $htmlFormArray = array(
                'c5name' => array('Safeguard Name', REQUIRED_FIELD_ZFPF),
                'c5hierarchy' => array('Hierarchy of Controls/Safeguards Type', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF, 'radio', array('Not Applicable or Multiple', 'Elimination', 'Substitution', 'Inventory Reduction', 'Engineering: Improves Primary-Containment Envelope', 'Engineering: Improves Instrumentation, Controls, or Machinery Reliability', 'Engineering: Greater Separation', 'Engineering: Secondary Containment or Release Treatment', 'Administrative', 'Personal-Protective Equipment (PPE)')),
                'c6description' => array('Safeguard Description', '', C6SHORT_MAX_BYTES_ZFPF)
            );
        elseif ($Context == 'action' and $arZfpf)
            $htmlFormArray = $arZfpf->ar_html_form_array($TableRoot);
        elseif ($Context == 'obsresult') {
            // $TableRoot is not used below, so it can be left in its default value.
            $htmlFormArray = array(
                'c5_obstopic_id' => array('<a id="c5_obstopic_id"></a>Specific-observation-topic unique identifier (topic ID). Use exactly the same characters for each topic ID, whenever written', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF),
                'c6obsmethod_as_done' => array('<a id="c6obsmethod_as_done"></a>Observation method, as done', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF),
                'c6obsresult' => array('<a id="c6obsresult"></a>Results', REQUIRED_FIELD_ZFPF, C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_supporting' => array('<a id="c6bfn_supporting"></a>Supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files')
            );
        }
        else
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. html_form_array() Eject Case 1.');
        return $htmlFormArray;
    }

    // Function for junction tables in scenarios; includes cause, consequence, safeguard, action (CCSA) i1m code
    public function scenario_CCSA_Zfpf($SelectedRow, $Issued, $User, $UserPracticePrivileges, $Zfpf, $Form = TRUE, $TableRoot = 'scenario', $Types = FALSE) {
        if (isset($_SESSION['SelectResults']))
            unset($_SESSION['SelectResults']);
        $EditLocked = TRUE; // This always sets this variable, for using on issuing PHA or elsewhere.
        if ($TableRoot == 'scenario' and isset($_SESSION['Scratch']['t0subprocess'])) // A subprocess may not be selected, such as for issuing the PHA in pha_io03.php.
            $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0subprocess']['c5who_is_editing']);  // For subprocess-wide edit_lock.
        else
            $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']); // For report-wide edit_lock for obsresult, incident, and PHA.
        if ($who_is_editing == '[Nobody is editing.]' or $who_is_editing == substr($Zfpf->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF))
            $EditLocked = FALSE;
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $ConditionsA[0] = array('k0'.$TableRoot, '=', $SelectedRow['k0'.$TableRoot]);
        $Message = '';
        $Name = FALSE;
        if (!$Types)
            $Types = array('cause' => 'Causes', 'consequence' => 'Consequences', 'safeguard' => 'Safeguards', 'action' => '<b>Actions, proposed or referenced.</b> If a recommendation can be resolved by completing an open action, already in this app\'s action register, this open action should be referenced. Otherwise, a proposed action should be drafted, which this app logs in its action register once the report is issued by the PHA or HIRA leader');
        foreach ($Types as $KA => $VA) {
        if (isset($_SESSION['Scratch']['t0'.$KA]))
            unset($_SESSION['Scratch']['t0'.$KA]);
            if ($Form)
                $Message .= '
                <form action="'.$KA.'_io03.php" method="post">';
            $Message .= '<p>
                <a id="'.$KA.'"></a><i>'.$VA.':</i><br />'; // Using this anchor is optional.
            list($_SESSION['SelectResults']['t0'.$TableRoot.'_'.$KA], $RRCount) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$TableRoot.'_'.$KA, $ConditionsA); // RR here means rows returned.
            if ($RRCount) {
                foreach ($_SESSION['SelectResults']['t0'.$TableRoot.'_'.$KA] as $KB => $VB) {
                    $ConditionsB[0] = array('k0'.$KA, '=', $VB['k0'.$KA]);
                    list($SelectResults, $RRCheck) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$KA, $ConditionsB);
                    if ($RRCheck != 1) // Each junction table row should be associated with only one row in each table joined.
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRCheck);
                    else
                        $_SESSION['SelectResults']['t0'.$KA][] = $SelectResults[0];
                }
                // Sort alphabetically by name.
                unset($Name);
                foreach ($_SESSION['SelectResults']['t0'.$KA] as $VB)
                    $Name[] = $Zfpf->decrypt_1c($VB['c5name']);
                array_multisort($Name, $_SESSION['SelectResults']['t0'.$KA]);
                foreach ($_SESSION['SelectResults']['t0'.$KA] as $KB => $VB) {
                    if ($Form) {
                        $Message .= '
                        <input type="radio" name="selected" value="'.$KB.'" ';
                        if ($KB == 0)
                            $Message .= 'checked="checked" '; // Select the first document by default to ensure something is posted (unless a hacker is tampering).
                        $Message .= '/>';
                    }
                    $Message .= $Name[$KB].'<br />';
                }
                if ($Form) {
                    $Message .= '
                        <input type="submit" name="'.$KA.'_o1" value="View details';
                    if (!$EditLocked and !$Issued and $User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
                        $Message .= ', edit, or remove" />';
                    else
                        $Message .= '" />';
                }
            }
            else
                $Message .= 'None found.';
            if (!$EditLocked and $Form and !$Issued and $User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF) {
                $Nothing = '[Nothing has been recorded in this field.]';
                if ($TableRoot == 'scenario' and $KA == 'action' and $Zfpf->decrypt_1c($SelectedRow['c5severity']) == $Nothing and $Zfpf->decrypt_1c($SelectedRow['c5likelihood']) == $Nothing)
                    $Message .= '<br />To add an '.$KA.', first assign severity and likelihood.';
                else {
                    $NameSuffix = '_i1aic';
                    $ValueDetail = 'Add ';
                    $ReportType = 'scenario';
                    $ReportPackage = ' PHA report'; // The PHA "package" holds many scenarios
                    if ($KA == 'action') { // obsresult and investigation only have $KA == 'action'. Scenarios may also have CCS.
                        if ($TableRoot == 'obsresult') {
                            if (isset($_SESSION['Selected']['k0audit'])) {
                                $ObsReportType = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
                                if ($ObsReportType != '[Nothing has been recorded in this field.]')
                                    $ObsReportType = $ObsReportType.' '; // Trailing space so no-effect if blank.
                                else
                                    $ObsReportType = '';
                            }
                            // Use above general default $NameSuffix
                            $ReportType = 'observation record';
                            $ReportPackage = ' '.$ObsReportType.'report'; // Report "packages" with observation results (obsresult) can have many observation results.
                        }
                        if ($TableRoot == 'incident') {
                            $NameSuffix = '_i0n';
                            $ReportType = 'incident-investigation report';
                            // $ReportPackage is not applicable to incident investigations.
                        }
                        $Message .= '<br />
                        Reference an open action, from the Action Register, in this '.$ReportType.'.<br />
                        <input type="submit" name="action_ifrom_ar" value="Reference an open action" /><br />'; // $_POST['action_ifrom_ar'] handled by ccsaZfpf::ccsa_io0_2 below, called by action_io03.php
                        if ($TableRoot == 'incident') // Investigation actions may only be new drafts (i0n) or references to Action Register.
                            $Message .= '
                            Add a proposed '.$KA.' to this '.$ReportType.'.<br />';
                        else { // PHA-scenario and obsresult actions may be newly proposed, references to drafts in report package, or references to Action Register.
                            $Message .= '
                            Add an action already in this draft'.$ReportPackage.' (proposed or referenced), or draft a new action.<br />';                        
                            $ValueDetail = 'New or in-report ';
                        }
                    }
                    else
                        $Message .= '<br />
                        Add an existing or new '.$KA.' to this '.$ReportType.'.<br />';
                    $Message .= '
                    <input type="submit" name="'.$KA.$NameSuffix.'" value="'.$ValueDetail.$KA.'" />'; // $_POST[$KA.$NameSuffix] handled by ccsaZfpf::ccsa_io0_2 below
                }
            }
            $Message .= '</p>';
            if ($Form)
                $Message .= '</form>';
            // No need for edit locked message here because it appears at bottom of $TableRoot.'_o1 code -- where, for example, "edit scenario" button would be.
        }
        $Zfpf->close_connection_1s($DBMSresource);
        return $Message;
    }

    // The $_SESSION variables used below must be set.
    public function other_ccsa_in_pha($ccsa, $Zfpf, $k0pha) {
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $KeysArray = array();
        if (isset($_SESSION['Scratch']['t0scenario'])) {
            // Get CCSA row keys of all CCSA already in the scenario, if user selected a scenario. Otherwise, all CCSA in PHA will be returned.
            $Conditions[0] = array('k0scenario', '=', $_SESSION['Scratch']['t0scenario']['k0scenario']);
            list($SR['t0scenario_'.$ccsa], $RR['t0scenario_'.$ccsa]) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $Conditions);
            if ($RR['t0scenario_'.$ccsa])
                foreach ($SR['t0scenario_'.$ccsa] as $V) {
                    $Conditions[0] = array('k0'.$ccsa, '=', $V['k0'.$ccsa]);
                    list($SR['t0'.$ccsa], $RR['t0'.$ccsa]) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$ccsa, $Conditions);
                    if ($RR['t0'.$ccsa] != 1)
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RR['t0'.$ccsa]);
                    if (!in_array($SR['t0'.$ccsa][0]['k0'.$ccsa], $KeysArray))
                        $KeysArray[] = $SR['t0'.$ccsa][0]['k0'.$ccsa]; // Collects keys of CCSA in the scenario.
                }
        }
        // Get all CCSA rows in the PHA, then exclude ones in the scenario.
        $Conditions[0] = array('k0pha', '=', $k0pha);
        list($SR['t0subprocess'], $RR['t0subprocess']) = $Zfpf->select_sql_1s($DBMSresource, 't0subprocess', $Conditions);
        if ($RR['t0subprocess'])
            foreach ($SR['t0subprocess'] as $VA) {
                $Conditions[0] = array('k0subprocess', '=', $VA['k0subprocess']);
                list($SR['t0scenario'], $RR['t0scenario']) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $Conditions);
                if ($RR['t0scenario'])
                    foreach ($SR['t0scenario'] as $VB) {
                        $Conditions[0] = array('k0scenario', '=', $VB['k0scenario']);
                        list($SR['t0scenario_'.$ccsa], $RR['t0scenario_'.$ccsa]) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_'.$ccsa, $Conditions);
                        if ($RR['t0scenario_'.$ccsa])
                            foreach ($SR['t0scenario_'.$ccsa] as $VC) {
                                $Conditions[0] = array('k0'.$ccsa, '=', $VC['k0'.$ccsa]);
                                list($SR['t0'.$ccsa], $RR['t0'.$ccsa]) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$ccsa, $Conditions);
                                if ($RR['t0'.$ccsa] != 1)
                                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RR['t0'.$ccsa]);
                                if (!in_array($SR['t0'.$ccsa][0]['k0'.$ccsa], $KeysArray)) { // Collects set of CCSA in PHA not already in the scenario.
                                    $KeysArray[] = $SR['t0'.$ccsa][0]['k0'.$ccsa];
                                    $OtherCCSA[] = $SR['t0'.$ccsa][0];
                                }
                            }
                    }
            }
        $Zfpf->close_connection_1s($DBMSresource);
        $Message = '
        <form action="'.$ccsa.'_io03.php" method="post">';
        if (isset($OtherCCSA)) {
            // Sort $OtherCCSA by name.
            foreach ($OtherCCSA as $V)
                $Name[] = $Zfpf->decrypt_1c($V['c5name']);
            array_multisort($Name, $OtherCCSA);
            $Message .= '<p>
            Select '.$ccsa.'s to add to the scenario.</p><p>';
            foreach ($OtherCCSA as $K => $V) {
                $Message .= '
                <input type="checkbox" name="'.$ccsa.$K.'" value="1" />'.$Name[$K].'<br />';
            }
            $Message .= '</p><p>
            <input type="submit" name="checkbox_wrangler" value="Add '.$ccsa.'(s) to scenario" /></p>';
        }
        else {
            $OtherCCSA = FALSE;
            $Message .= '<p>
            No additional '.$ccsa.'s were found in any scenarios in the PHA or HIRA. This can happen if your scenario used all the '.$ccsa.'s in the PHA or HIRA. This is common for proposed actions and also when a PHA or HIRA was created from scratch.</p>';
        }
        return array($OtherCCSA, $Message);
    }

    // This function returns an array with any t0action rows associated with the report (aka audit) -- FALSE otherwise --
    // excluding ones already associated with the currently selected obsresult, if $_SESSION['Scratch']['t0obsresult'] is set,
    // and a message with an HTML form listing these actions.
    // $_SESSION['Selected']['k0audit'] must be set. 
    // $_SESSION['Scratch']['t0obsresult'] is optional. If not set, all actions in the report will be returned.
    public function other_actions_in_audit($Zfpf) {
        if (!isset($_SESSION['Selected']['k0audit']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $KeysArray = array();
        if (isset($_SESSION['Scratch']['t0obsresult'])) { // Get action row keys of all actions already in any currently selected obsresult.
            $Conditions[0] = array('k0obsresult', '=', $_SESSION['Scratch']['t0obsresult']['k0obsresult']);
            list($SROrA, $RROrA) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult_action', $Conditions);
            if ($RROrA) foreach ($SROrA as $VOrA) {
                $Conditions[0] = array('k0action', '=', $VOrA['k0action']);
                list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $Conditions);
                if ($RRA != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRA);
                if (!in_array($SRA[0]['k0action'], $KeysArray))
                    $KeysArray[] = $SRA[0]['k0action']; // Collects keys of actions in the obsresult.
            }
        }
        // Get all action rows in the report, then exclude ones in the current obsresult.
        $Conditions[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
        list($SROr, $RROr) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $Conditions);
        if ($RROr) foreach ($SROr as $VOr) {
            $Conditions[0] = array('k0obsresult', '=', $VOr['k0obsresult']);
            list($SROrA, $RROrA) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult_action', $Conditions);
            if ($RROrA) foreach ($SROrA as $VOrA) {
                $Conditions[0] = array('k0action', '=', $VOrA['k0action']);
                list($SRA, $RRA) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $Conditions);
                if ($RRA != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RRA);
                if (!in_array($SRA[0]['k0action'], $KeysArray)) {
                    $KeysArray[] = $SRA[0]['k0action'];
                    $OtherActions[] = $SRA[0];  // Collects unique set of actions in report, not already in any currently selected obsresult.
                }
            }
        }
        $Zfpf->close_connection_1s($DBMSresource);
        $Message = '
        <form action="action_io03.php" method="post">';
        if (isset($OtherActions)) {
            // Sort $OtherActions by name.
            foreach ($OtherActions as $V)
                $Name[] = $Zfpf->decrypt_1c($V['c5name']);
            array_multisort($Name, $OtherActions);
            $Message .= '<p>
            Select actions to add to the observation record.</p><p>';
            foreach ($OtherActions as $K => $V)
                $Message .= '<input type="checkbox" name="action'.$K.'" value="1" />'.$Name[$K].'<br />';
            $Message .= '</p><p>
            Add the selected action(s) to the observation record.<br />
            <input type="submit" name="checkbox_wrangler" value="Add action(s)" /></p>';
        }
        else {
            $OtherActions = FALSE;
            $ObsReportType = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
            if ($ObsReportType != '[Nothing has been recorded in this field.]')
                $ObsReportType = $ObsReportType.' '; // Trailing space so no-effect if blank.
            else
                $ObsReportType = '';
            $Message .= '<p>
            No additional actions were found in the '.$ObsReportType.'report. This can happen if the current observation record already references all the actions currently in the '.$ObsReportType.'report.</p>';
        }
        // Don't close the HTML form, so calling file can add additional items.
        return array($OtherActions, $Message);
    }

    // The $_SESSION variables used below must be set.
    // Called by $this->checkbox_wrangler, $this->ccsa_remove, scenario i3 code (for new or edited scenarios), and subprocess_io03.php (for removing subprocesses).
    // Not called when adding template subprocess/subsystem, because S and L are not defined in templates, to encourage customization.
    // Not called when creating a new action because it will be associated only with the scenario it was first added to.
    // $AffectedActions is associative array. Its keys are one or more t0action row primary keys (k0action). Its values are the priority of the scenario being edited, removed, or having actions associated or removed.
    // For removed scenarios, all values in $AffectedActions should be set to _00_PRIORITY_ZFPF
    // $k0scenario is the primary key of the selected t0scenario row (being being edited, removed, or having actions associated or removed).
    // $OldSPriority is the former priority of the edited or removed scenario. Use default value for scenarios added via checkbox wrangler.
    // S means scenario. 
    // QS means queried scenario.
    public function risk_rank_priority_update($Zfpf, $DBMSresource, $AffectedActions, $k0scenario, $OldSPriority = _00_PRIORITY_ZFPF) {
        $Conditions[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
        list($SelectResults['t0subprocess'], $RowsReturned['t0subprocess']) = $Zfpf->select_sql_1s($DBMSresource, 't0subprocess', $Conditions);
        if ($RowsReturned['t0subprocess']) foreach ($SelectResults['t0subprocess'] as $VA) {
            $Conditions[0] = array('k0subprocess', '=', $VA['k0subprocess']);
            list($SelectResults['t0scenario'], $RowsReturned['t0scenario']) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $Conditions);
            if ($RowsReturned['t0scenario'])
                foreach ($SelectResults['t0scenario'] as $VB) {
                    if ($VB['k0scenario'] != $k0scenario) {
                        $Conditions[0] = array('k0scenario', '=', $VB['k0scenario']);
                        list($SelectResults['t0scenario_action'], $RowsReturned['t0scenario_action']) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario_action', $Conditions);
                        if ($RowsReturned['t0scenario_action']) {
                            $QSPriority = $Zfpf->risk_rank_1c($Zfpf->decrypt_1c($VB['c5severity']), $Zfpf->decrypt_1c($VB['c5likelihood']));
                            foreach ($SelectResults['t0scenario_action'] as $VC) {
                                $k0action = $VC['k0action'];
                                if (isset($AffectedActions[$k0action]) and $AffectedActions[$k0action] < $QSPriority)
                                    $AffectedActions[$k0action] = $QSPriority;
                            }
                        }
                    }
                }
        }
        $TotalAffected = 0;
        foreach ($AffectedActions as $K => $V) {
            if ($V != $OldSPriority) { // False only if NewSPriority < OldSPriority but a QSPriority == OldSPriority.
                $NewActionPriority['c5priority'] = $Zfpf->encrypt_1c($V);
                $Conditions[0] = array('k0action', '=', $K);
                list($SelectResults['t0action'], $RowsReturned['t0action']) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $Conditions);
                if ($RowsReturned['t0action'] != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                if ($SelectResults['t0action'][0]['k0user_of_ae_leader'] == -2) { // See app schema. If -1 should not be associated here. If 0 or greater, don't want to change.
                    $ShtmlFormArray = array(
                        'c5priority' => array('Priority (aka risk rank or relative risk from PHA or HIRA)', '')
                    );
                    $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0action', $NewActionPriority, $Conditions, TRUE, $ShtmlFormArray);
                    if ($Affected != 1)
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
                }
                ++$TotalAffected;
            }
        }
        return $TotalAffected;
    }

    // The $_SESSION variables used below must be set.
    public function checkbox_wrangler($ccsa, $Zfpf, $TableRoot = 'scenario') {
        if (!isset($_SESSION['SelectResults']['t0'.$ccsa]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. checkbox_wrangler() Eject Case 1.');
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Inserted = 0;
        foreach ($_SESSION['SelectResults']['t0'.$ccsa] as $K => $V)
            if (isset($_POST[$ccsa.$K])) {
                $NewRow = array(
                    'k0'.$TableRoot.'_'.$ccsa => time().$K.mt_rand(10000, 99999),
                    'k0'.$TableRoot => $_SESSION['Scratch']['t0'.$TableRoot]['k0'.$TableRoot],
                    'k0'.$ccsa => $V['k0'.$ccsa],
                    'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
                );
                $Zfpf->insert_sql_1s($DBMSresource, 't0'.$TableRoot.'_'.$ccsa, $NewRow);
                ++$Inserted;
                if ($TableRoot == 'scenario' and $ccsa == 'action') {
                    $k0action = $V['k0action'];
                    $AffectedActions[$k0action] = $Zfpf->risk_rank_1c($Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5severity']), $Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5likelihood']));
                }
            }
        if (isset($AffectedActions)) // Only set when $TableRoot == 'scenario'
            $this->risk_rank_priority_update($Zfpf, $DBMSresource, $AffectedActions, $_SESSION['Scratch']['t0scenario']['k0scenario']);
        $Zfpf->close_connection_1s($DBMSresource);
        if ($TableRoot == 'obsresult') {
            $Zfpf->clear_edit_lock_1c(); // Report-wide edit_lock -- updates $_SESSION['Selected'] and database-table row that $_SESSION['Selected'] was queried from.
            $RecordType = 'observation record';
        }
        elseif ($TableRoot == 'scenario') { // The default value
            $_SESSION['Scratch']['t0subprocess']['c5who_is_editing'] = $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['t0subprocess']); // Subprocess-wide edit_lock
            $RecordType = $TableRoot;
        }
        $Message = $Zfpf->xhtml_contents_header_1c('Add '.$ccsa).'<h2>
        Add '.$ccsa.'</h2>';
        if ($Inserted)
            $Message .= '<p>
            The app recorded the '.$Inserted.' '.$ccsa.'(s) you selected as part of the '.$RecordType.'.</p>';
        else
            $Message .= '<p>
            You didn\'t select any checkboxes, so no changes were made to the '.$RecordType.'.</p>';
        $Message .=  '<form action="'.$TableRoot.'_io03.php" method="post"><p>
            <input type="submit" name="'.$TableRoot.'_o1" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        return $Message;
    }

    // called in ccsaZfpf.php o1 code, scenario_io03.php, and subprocess_io03.php
    // The $_SESSION variables used below must be set, if not passed in.
    // Removes a CCSA from a obsresult, incident, or scenario by deleting one junction table row and, 
    // if CCSA is a "Draft proposed action" and nothing else points to the CCSA via junction table, deleting the CCSA.
    public function ccsa_remove($ccsa, $Zfpf, $TableRoot = 'scenario', $TableRootRow = NULL, $CCSA_Row = NULL) {
        if (!$CCSA_Row)
            $CCSA_Row = $_SESSION['Scratch']['t0'.$ccsa];
        if ($TableRoot == 'obsresult') {
            if (!$TableRootRow)
                $TableRootRow = $_SESSION['Scratch']['t0obsresult'];
            if (!isset($_SESSION['Selected']['k0audit']) or !isset($TableRootRow) or !isset($CCSA_Row))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. ccsa_remove()');
        }
        elseif ($TableRoot == 'incident') {
            if (!$TableRootRow)
                $TableRootRow = $_SESSION['Selected'];
            if (!isset($TableRootRow) or !isset($CCSA_Row))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. ccsa_remove()');
        }
        elseif ($TableRoot == 'scenario') { // The default value.
            if (!$TableRootRow)
                $TableRootRow = $_SESSION['Scratch']['t0scenario'];
            if (!isset($_SESSION['Selected']['k0pha']) or !isset($_SESSION['Scratch']['t0subprocess']) or !isset($TableRootRow) or !isset($CCSA_Row))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. ccsa_remove()');
        }
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0'.$ccsa, '=', $CCSA_Row['k0'.$ccsa]);
        // SR mean select results
        // JT mean junction table
        // CCSA means one of: cause, consequence, safeguard, or action
        list($SR_JT_CCSA, $RR_JT_CCSA) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$TableRoot.'_'.$ccsa, $Conditions);
        if (!$RR_JT_CCSA)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. ccsa_remove(). Rows Returned: '.@$RR_JT_CCSA);
        $Conditions[1] = array('k0'.$TableRoot, '=', $TableRootRow['k0'.$TableRoot], '', ' AND '); // See CoreZfpf::where_qsl_1s
        // S below stands for obsresult, incident, or scenario
        list($SR_JT_S_CCSA, $RR_JT_S_CCSA) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$TableRoot.'_'.$ccsa, $Conditions);
        if ($RR_JT_S_CCSA != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. ccsa_remove(). Rows Returned: '.@$RR_JT_S_CCSA);
        $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0'.$TableRoot.'_'.$ccsa, $Conditions);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. ccsa_remove(). Affected: '.@$Affected);
        // Only delete actions whose status is "Draft proposed action" and so t0action::k0user_of_ae_leader == -2 because here only dealing with actions associated with a report.
        if ($RR_JT_CCSA == 1 and ($ccsa != 'action' or $CCSA_Row['k0user_of_ae_leader'] == -2)) {
            unset ($Conditions[1]);
            $Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0'.$ccsa, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. ccsa_remove(). Affected: '.@$Affected);
        }
        elseif ($TableRoot == 'scenario' and $ccsa == 'action' and $CCSA_Row['k0user_of_ae_leader'] == -2) { // Other scenarios may reference the action, so its priority needs to be updated to the highest-priority of those scenarios.
            $k0action = $CCSA_Row['k0action'];
            $AffectedActions[$k0action] = _00_PRIORITY_ZFPF;
            $OldSPriority = $Zfpf->risk_rank_1c($Zfpf->decrypt_1c($TableRootRow['c5severity']), $Zfpf->decrypt_1c($TableRootRow['c5likelihood']));
            $this->risk_rank_priority_update($Zfpf, $DBMSresource, $AffectedActions, $TableRootRow['k0scenario'], $OldSPriority);
        }
        $Zfpf->close_connection_1s($DBMSresource);
        if (isset($_SESSION['Scratch']['t0'.$ccsa]))
            unset($_SESSION['Scratch']['t0'.$ccsa]);
        $Message = '<h1>
        Removed '.$ccsa.'</h1><p>
        The app tried to remove the association between the '.$ccsa.' and your current report. If nothing else relied on this '.$ccsa.', the app attempted to delete it.</p>
        <form action="'.$TableRoot.'_io03.php" method="post"><p>
            <input type="submit" name="'.$TableRoot.'_o1" value="Go back" /></p>
        </form>';
        return $Zfpf->xhtml_contents_header_1c('Removed '.$ccsa).$Message.$Zfpf->xhtml_footer_1c();
    }

    // The $_SESSION variables used below must be set.
    public function ccsa_io0_2($ccsa, $Zfpf, $htmlFormArray, $TableRoot = 'scenario', $arZfpf = FALSE) {
        // Additional security check
        $User = $Zfpf->current_user_info_1c();
        if ($TableRoot == 'scenario' and $_SESSION['Selected']['k0pha'] < 100000) { // Template PHA case
            if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes' or $User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF) // Only Web App Admins got the option to edit template PHAs in pha_i1m.php
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Process['AEFullDescription'] = 'Not associated with a process because this is a template.';
        }
        else { 
            // Except templates, this app requires PHAs, incident investigations, and reports with obsresults, like PSM audits, to be associated with a process.
            // The template PHA case is handled in if clause above. Other templates (in t0audit) are not allowed to have actions, 
            // and the check below would eject them because $_SESSION['Selected']['k0process'] == 0.
            if (!isset($_SESSION['StatePicked']['t0process']['k0process']) or $_SESSION['Selected']['k0process'] != $_SESSION['StatePicked']['t0process']['k0process'])
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $Process = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['StatePicked']['t0process']['k0process']);
        }
        // Get useful information.
        $UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
        $Nothing = '[Nothing has been recorded in this field.]';
        $EncryptedNothing = $Zfpf->encrypt_1c($Nothing);
        if ($TableRoot == 'obsresult') {
            $k0TableRoot = $_SESSION['Scratch']['t0obsresult']['k0obsresult'];
            $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']); // For report-wide edit_lock.
            if (isset($_SESSION['Selected']['k0audit'])) {
                $ObsReportType = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
                if ($ObsReportType != '[Nothing has been recorded in this field.]')
                    $ObsReportType = $ObsReportType.' '; // Trailing space so no-effect if blank.
                else
                    $ObsReportType = '';
            }
            $Heading = '<h2>
            '.$ObsReportType.'Report for<br />
            '.$Process['AEFullDescription'].'</h2>';
            $InitialPriority = $EncryptedNothing;
            $ReportType = 'observation record';
            $EditLockMessage = 'the '.$ObsReportType.'report you are working on';
        }
        elseif ($TableRoot == 'incident') {
            $k0TableRoot = $_SESSION['Selected']['k0incident'];
            $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']); // For report-wide edit_lock.
            $Heading = '<h2>
            Incident-Investigation Report for<br />
            Incident Name: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).' at <br />
            '.$Process['AEFullDescription'].'</h2>';
            $InitialPriority = $EncryptedNothing;
            $ReportType = 'incident-investigation report';
            $EditLockMessage = 'this incident-investigation report';
        }
        elseif ($TableRoot == 'scenario') { // The default value.
            $k0TableRoot = $_SESSION['Scratch']['t0scenario']['k0scenario'];
            $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Scratch']['t0subprocess']['c5who_is_editing']); // For subprocess-wide edit_lock.
            $Heading = '<h2>
            Process-hazard analysis (PHA) for<br />
            '.$Process['AEFullDescription'].'<br />
            Subsystem: '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0subprocess']['c5name']).'<br />
            Scenario: '.$Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5name']).'</h2>';
            $InitialPriority = $Zfpf->encrypt_1c($Zfpf->risk_rank_1c($Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5severity']), $Zfpf->decrypt_1c($_SESSION['Scratch']['t0scenario']['c5likelihood']))); // Initial priority is the risk rank of the associated PHA scenario.
            $ReportType = 'scenario';
            $EditLockMessage = 'a record for the subsystem you selected or its scenarios';
        }
        $Issued = $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_leader']); // For pha, $_SESSION['Selected'] still holds the user-selected t0pha row.
        if ($Issued == $Nothing)
            $Issued = FALSE;
        $EditLocked = TRUE;
        if ($who_is_editing == '[Nobody is editing.]' or $who_is_editing == substr($Zfpf->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF))
            $EditLocked = FALSE;
        // i1aic code -- modified i1m code for selecting a CCSA already in current (i1aic) obsresult or PHA 
        // plus buttons for selecting a CCSA from templates (i1t) or creating a new CCSA (i0n).
        if (isset($_POST[$ccsa.'_i1aic'])) {
            if ($EditLocked or $Issued or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or ($TableRoot != 'scenario' and $TableRoot != 'obsresult'))
                $Zfpf->send_to_contents_1c(); // Don't eject
            if (isset($_SESSION['SelectResults']['t0'.$ccsa]))
                unset($_SESSION['SelectResults']['t0'.$ccsa]);
            if ($TableRoot == 'scenario') { // edit_lock subprocess since a scenario in it is changing.
                if ($who_is_editing == '[Nobody is editing.]') // Still need to edit lock for current user.
                    $_SESSION['Scratch']['t0subprocess'] = $Zfpf->edit_lock_1c('subprocess', 'subsystem', $_SESSION['Scratch']['t0subprocess']);
                list($_SESSION['SelectResults']['t0'.$ccsa], $Message) = $this->other_ccsa_in_pha($ccsa, $Zfpf, $_SESSION['Selected']['k0pha']);
                // Cases below are: "editing template" or "editing a PHA created from a template". A template source key exists.
                if ($ccsa != 'action' and ($_SESSION['Selected']['k0pha'] < 100000 or $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']) != $Nothing))
                    $Message .= '<p>
                    <input type="submit" name="'.$ccsa.'_i1t" value="View template '.$ccsa.'s" /></p>';
                $GoBackActionFile = 'scenario_io03.php';
                $GoBackInputName = 'scenario_o1';
            }
            elseif ($TableRoot == 'obsresult') {
                if ($ccsa != 'action')
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                if ($who_is_editing == '[Nobody is editing.]')
                    $Zfpf->edit_lock_1c('audit', 'this report or one of its supporting records');
                // 
                list($_SESSION['SelectResults']['t0action'], $Message) = $this->other_actions_in_audit($Zfpf);
                $GoBackActionFile = 'obsresult_io03.php';
                $GoBackInputName = 'obsresult_o1';
            }
            $Message .= '<p>
                <input type="submit" name="'.$ccsa.'_i0n" value="Draft a new '.$ccsa.'" /></p>
            </form>'; // HTML form opened in ccsaZfpf::other_ccsa_in_pha must be closed here.
            echo $Zfpf->xhtml_contents_header_1c('Add '.$ccsa).'<h1>
            Add '.$ccsa.'</h1>
            '.$Message.'
            <form action="'.$GoBackActionFile.'" method="post"><p>
                <input type="submit" name="'.$GoBackInputName.'" value="Go back" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        // i1t code -- modified i1m code for selecting a CCSA from template (i1t) PHA or creating a new CCSA (i0n).
        if (isset($_POST[$ccsa.'_i1t'])) {
            if ($EditLocked or $Issued or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or $TableRoot != 'scenario')
                $Zfpf->send_to_contents_1c(); // Don't eject
            if ($_SESSION['Selected']['k0pha'] < 100000)  // Editing template case.
                $TemplateSourceKey = $_SESSION['Selected']['k0pha'];
            else {
                $TemplateSourceKey = $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']); // Before issuing, the source-template k0pha is kept in c6nymd_leader, encrypted.
                if ($TemplateSourceKey == $Nothing) // PHA was created from scratch, rather than from a template, (the PHA "i0n case")
                    $TemplateSourceKey = FALSE;
            }
            if (!$TemplateSourceKey)
                $Zfpf->send_to_contents_1c(); // Don't eject
            if (isset($_SESSION['SelectResults']['t0'.$ccsa]))
                unset($_SESSION['SelectResults']['t0'.$ccsa]);
            list($_SESSION['SelectResults']['t0'.$ccsa], $Message) = $this->other_ccsa_in_pha($ccsa, $Zfpf, $TemplateSourceKey);
            $Message .= '<p>
                <input type="submit" name="'.$ccsa.'_i0n" value="Draft a new '.$ccsa.'" /></p>';
            echo $Zfpf->xhtml_contents_header_1c('Add '.$ccsa).'<h1>
            Add template '.$ccsa.'</h1>
            '.$Message.'
                <input type="submit" name="'.$ccsa.'_i1aic" value="Go back" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        if (isset($_POST['action_ifrom_ar'])) {
            if ($EditLocked or $Issued or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF or !$arZfpf)
                $Zfpf->send_to_contents_1c(); // Don't eject
            if (isset($_SESSION['SelectResults']['t0action']))
                unset($_SESSION['SelectResults']['t0action']);
            $SpecialConditions = array('k0user_of_ae_leader', '=', 0); // Means c5status holds, encrypted, 'Needs resolution...' See app schema. Same as .../includes/ar_i1m.php
            $Conditions = $arZfpf->conditions_state_picked($SpecialConditions);
            list($_SESSION['SelectResults']['t0action'], $RowsReturned) = $Zfpf->one_shot_select_1s('t0action', $Conditions);
            $_SESSION['Scratch']['PlainText']['action_ifrom_ar'] = $TableRoot;
            echo $arZfpf->actions_list($Zfpf, $RowsReturned, 'Unresolved actions');
            $Zfpf->save_and_exit_1c();
        }
        // checkbox_wrangler code -- inserts junction-table row(s) for selected CCSA
        if (isset($_POST['checkbox_wrangler'])) {
            if ($EditLocked or $Issued or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            echo $this->checkbox_wrangler($ccsa, $Zfpf, $TableRoot);
            $Zfpf->save_and_exit_1c();
        }
        // i0n code
        if (isset($_POST[$ccsa.'_i0n'])) {
            // Additional security check.
            if ($EditLocked or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            // SPECIAL CASE: $_SESSION['Scratch']['t0'.$ccsa], this serves like $_SESSION['Selected']. $_SESSION['Selected'] keeps holding a t0pha, t0incident, or t0audit row.
            if ($ccsa == 'cause' or $ccsa == 'consequence')
                $_SESSION['Scratch']['t0'.$ccsa] = array(
                    'k0'.$ccsa => time().mt_rand(1000000, 9999999),
                    'c5name' => $EncryptedNothing,
                    'c6description' => $EncryptedNothing,
                    'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
                );
            if ($ccsa == 'safeguard')
                $_SESSION['Scratch']['t0'.$ccsa] = array(
                    'k0'.$ccsa => time().mt_rand(1000000, 9999999),
                    'c5name' => $EncryptedNothing,
                    'c5hierarchy' => $Zfpf->encrypt_1c('Not Applicable or Multiple'),
                    'c6description' => $EncryptedNothing,
                    'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
                );
            if ($ccsa == 'action')
                $_SESSION['Scratch']['t0'.$ccsa] = array(
                    'k0'.$ccsa => time().mt_rand(1000000, 9999999),
                    'c5name' => $EncryptedNothing,
                    'c5status' => $Zfpf->encrypt_1c('Draft proposed action'),
                    'c5priority' => $InitialPriority,
                    'c5affected_entity' => $EncryptedNothing,
                    'k0affected_entity' => 0,
                    'c6deficiency' => $EncryptedNothing,
                    'c6details' => $EncryptedNothing,
                    'c5ts_target' => $EncryptedNothing,
                    'k0user_of_leader' => 0,
                    'c5ts_leader' => $EncryptedNothing,
                    'c6nymd_leader' => $EncryptedNothing,
                    'c6notes' => $EncryptedNothing,
                    'c6bfn_supporting' => $EncryptedNothing,
                    'k0user_of_ae_leader' => -2,
                    'c5ts_ae_leader' => $EncryptedNothing,
                    'c6nymd_ae_leader' => $EncryptedNothing,
                    'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
                );
        }
        // history_o1 code
        if (isset($_POST[$ccsa.'_history_o1'])) {
            if (!isset($_SESSION['Scratch']['t0'.$ccsa]['k0'.$ccsa]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
            $HistoryGetZfpf = new HistoryGetZfpf;
            list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0'.$ccsa, $_SESSION['Scratch']['t0'.$ccsa]['k0'.$ccsa]);
            $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one '.$ccsa.' record', $ccsa.'_io03.php', $ccsa.'_o1'); // This echos and exits.
        }
        // o1 code
        // SPECIAL CASES: $_SESSION['Scratch']['t0'.$ccsa] serves like $_SESSION['Selected'].  $_SESSION['Selected'] keeps holding a t0pha, t0incident, or t0audit row.
        if (isset($_POST[$ccsa.'_o1'])) {
            // Additional security check.
            if (!isset($_SESSION['Scratch']['t0'.$ccsa]) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0'.$ccsa])))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            // $_SESSION cleanup
            if (isset($_SESSION['Scratch']['htmlFormArray']))
                unset($_SESSION['Scratch']['htmlFormArray']);
            if (isset($_SESSION['Post']))
                unset($_SESSION['Post']);
            if ($TableRoot == 'obsresult')
                $Zfpf->clear_edit_lock_1c(); // Report-wide edit_lock
            elseif ($TableRoot == 'incident')
                $Zfpf->clear_edit_lock_1c(); // Report-wide edit_lock
            elseif ($TableRoot == 'scenario')
                $_SESSION['Scratch']['t0subprocess']['c5who_is_editing'] = $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['t0subprocess']); // Subprocess-wide edit_lock
            if (!isset($_SESSION['Scratch']['t0'.$ccsa])) {
                $CheckedPost = $Zfpf->post_length_blank_1c('selected');
                if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0'.$ccsa][$CheckedPost]))
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                $_SESSION['Scratch']['t0'.$ccsa] = $_SESSION['SelectResults']['t0'.$ccsa][$CheckedPost];
                unset($_SESSION['SelectResults']);
            }
            $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0'.$ccsa]);
            echo $Zfpf->xhtml_contents_header_1c($ccsa).$Heading.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Scratch']['t0'.$ccsa], $Display);
            if ($EditLocked) {
                if (substr($who_is_editing, 0, 19) != 'PERMANENTLY LOCKED:')
                    echo '<p><b>'.$who_is_editing.' is editing '.$EditLockMessage.'.</b><br />
                    If needed, contact them to coordinate. You will not be able to edit these, nor add new '.$ccsa.'s, until they are done.</p>';
                else
                    echo '<p>'.$who_is_editing.'</p>'; // This should echo the permanent-lock message.
            }
            elseif (!$Issued and $User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF) {
                echo '
                <form action="'.$ccsa.'_io03.php" method="post"><p>';
                if ($ccsa != 'action' or $Zfpf->decrypt_1c($_SESSION['Scratch']['t0action']['c5status']) == 'Draft proposed action')
                    echo '
                    <input type="submit" name="'.$ccsa.'_o1_from" value="Update this '.$ccsa.'" /><br />';
                else
                    echo '<b>Not draft, cannot edit.</b> This action is associated with an issued document. Comments and resolution documentation may be added to it via this app\'s action register.<br /><br />';
                echo '
                    Remove this '.$ccsa.' from the current '.$ReportType.'<br />
                    <input type="submit" name="'.$ccsa.'_remove" value="Remove '.$ccsa.'" /></p>
                </form>';
            }
            else {
                echo '
                <p>You don\'t have editing privileges on this record.</p>';
                if ($Issued)
                    echo '<p>
                    Once a document has been issued, like this one, it cannot be edited.</p>';
                if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
                    echo '<p><b>
                    Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
                if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
                    echo '<p><b>
                    Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
            }
            echo '
            <form action="'.$ccsa.'_io03.php" method="post"><p>
                <input type="submit" name="'.$ccsa.'_history_o1" value="History of this record" /></p>
            </form>
            <form action="'.$TableRoot.'_io03.php" method="post"><p>
                <input type="submit" name="'.$TableRoot.'_o1" value="Go back" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        // i1 & i2 and $ccsa.'_remove code
        if (isset($_SESSION['Scratch']['t0'.$ccsa]['k0'.$ccsa])) {
            // Additional security check.
            if ($EditLocked or $Issued or (!isset($_POST[$ccsa.'_i0n']) and ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF or $UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)))
                $Zfpf->send_to_contents_1c(); // Don't eject
            // SPECIAL CASE -- edit_lock_1c under i1 code, below, because not needed for one step ccsa_remove()
            // '.$ccsa.'_remove code.
            if (isset($_POST[$ccsa.'_remove'])) {
                if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF or $UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
                    $Zfpf->send_to_contents_1c(); // Don't eject
                echo $this->ccsa_remove($ccsa, $Zfpf, $TableRoot);
                $Zfpf->save_and_exit_1c();
            }
            // Additional security check for i1 and i2 code (editing)
            if ($ccsa == 'action' and $Zfpf->decrypt_1c($_SESSION['Scratch']['t0action']['c5status']) != 'Draft proposed action')
                $Zfpf->send_to_contents_1c(); // Don't eject
            // i1 code
            // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
            // 1.1 $_SESSION['Scratch']['t0'.$ccsa] is only source of $Display.
            if (isset($_POST[$ccsa.'_i0n']) or isset($_POST[$ccsa.'_o1_from'])) {
                if (isset($_POST[$ccsa.'_o1_from']) and $who_is_editing == '[Nobody is editing.]') { // edit_lock at higher level to prevent report changes while modifying CCSA.
                    if ($TableRoot == 'obsresult')
                        $Zfpf->edit_lock_1c('audit', 'this report or one of its supporting records');
                    elseif ($TableRoot == 'incident')
                        $Zfpf->edit_lock_1c('incident', 'incident-investigation');
                    elseif ($TableRoot == 'scenario')
                        $_SESSION['Scratch']['t0subprocess'] = $Zfpf->edit_lock_1c('subprocess', 'subsystem', $_SESSION['Scratch']['t0subprocess']);
                }
                $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Scratch']['t0'.$ccsa], FALSE, TRUE);
                // To standardize, always save possibly modified htmlFormArray, SelectDisplay, and (to simplify code below) placeholder for user's post.
                $_SESSION['Scratch']['htmlFormArray'] = $Zfpf->encode_encrypt_1c($htmlFormArray);
                $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($Display);
                $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
            }
            // 1.2 $_SESSION['Scratch']['SelectDisplay'] is source of $Display, the version generated from the database record.
            elseif (isset($_POST['undo_confirm_post_1e']) and isset($_SESSION['Scratch']['SelectDisplay'])) {
                $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
                $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
            }
            // 1.3 $_SESSION['Post'] is source of $Display, the latest user-modified version.  Also use for upload_files.
            elseif (isset($_SESSION['Post']) and isset($_POST['modify_confirm_post_1e']))
                $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
            if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
                // Create HTML form
                echo $Zfpf->xhtml_contents_header_1c($ccsa);
                // To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
                echo '<h1>
                Edit '.$ccsa.'</h1>'.$Heading.'
                <form action="'.$ccsa.'_io03.php" method="post" enctype="multipart/form-data" >';
                echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
                echo '<p>
                    <input type="submit" name="'.$ccsa.'_i2" value="Review changes to above fields" /></p>
                </form>';
                if ($Zfpf->decrypt_1c($_SESSION['Scratch']['t0'.$ccsa]['c5who_is_editing']) == '[A new database row is being created.]') {
                    if ($TableRoot == 'obsresult')
                        echo '
                        <form action="obsresult_io03.php" method="post"><p>
                            <input type="submit" name="obsresult_o1" value="Go back" /></p>
                        </form>';
                    elseif ($TableRoot == 'incident')
                        echo '
                        <form action="incident_io03.php" method="post"><p>
                            <input type="submit" name="incident_o1" value="Go back" /></p>
                        </form>';
                    elseif ($TableRoot == 'scenario')
                        echo '
                        <form action="'.$ccsa.'_io03.php" method="post"><p>
                            <input type="submit" name="'.$ccsa.'_i1aic" value="Go back" /></p>
                        </form>';
                }
                else
                    echo '
                    <form action="'.$ccsa.'_io03.php" method="post"><p>
                        <input type="submit" name="'.$ccsa.'_o1" value="Go back" /></p>
                    </form>';
                echo $Zfpf->xhtml_footer_1c();
                $Zfpf->save_and_exit_1c();
            }
            // i2 code, implements the review and confirmation HTML page.
            elseif (isset($_POST[$ccsa.'_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
                $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
                $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
                $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
                $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
                echo $Zfpf->post_select_required_compare_confirm_1e($ccsa.'_io03.php', $ccsa.'_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
                $Zfpf->save_and_exit_1c();
            }
        } // ends i1, i2, and approval code
        $Zfpf->catch_all_1c('practice_o1.php');
    }

    // The $_SESSION variables used below must be set.
    public function ccsa_edit($ccsa, $Zfpf, $TableRoot = 'scenario') {
        // Additional security check.
        if (isset($_SESSION['Selected']['c5ts_leader']) and $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_leader']) != '[Nothing has been recorded in this field.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        $User = $Zfpf->current_user_info_1c();
        if ($User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
            $Zfpf->send_to_contents_1c(); // Don't eject
        if ($Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']) != MAX_PRIVILEGES_ZFPF)
            $Zfpf->send_to_contents_1c(); // Don't eject
        if (!isset($_SESSION['Scratch']['t0'.$ccsa]) or !isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 1.');
        if ($TableRoot == 'obsresult') {
            $k0TableRoot = $_SESSION['Scratch']['t0obsresult']['k0obsresult'];
            $ReportType = 'observation record';
            $Zfpf->clear_edit_lock_1c(); // Report-wide edit_lock
        }
        elseif ($TableRoot == 'incident') {
            $k0TableRoot = $_SESSION['Selected']['k0incident'];
            $ReportType = 'incident-investigation report';
            $Zfpf->clear_edit_lock_1c(); // Report-wide edit_lock
        }
        elseif ($TableRoot == 'scenario') { // The default value
            $k0TableRoot = $_SESSION['Scratch']['t0scenario']['k0scenario'];
            $ReportType = 'scenario';
            $_SESSION['Scratch']['t0subprocess']['c5who_is_editing'] = $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['t0subprocess']); // Subprocess-wide edit_lock
        }
        $ChangedRow = $Zfpf->changes_from_post_1c($_SESSION['Scratch']['t0'.$ccsa]);
        if ($ccsa == 'action') {
            // If the affected-entity changed, get the new affected-entity key into $ChangedRow
            if (isset($ChangedRow['c5affected_entity'])) {
                switch ($Zfpf->decrypt_1c($ChangedRow['c5affected_entity'])) {
                    case 'Owner-wide':
                        if (!isset($_SESSION['StatePicked']['t0owner']['k0owner']))
                            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 2.');
                        $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0owner']['k0owner'];
                        break;
                    case 'Contractor-wide':
                        if (!isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
                            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 3.');
                        $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0contractor']['k0contractor'];
                        break;
                    case 'Facility-wide':
                        if (!isset($_SESSION['StatePicked']['t0facility']['k0facility']))
                            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 4.');
                        $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0facility']['k0facility'];
                        break;
                    case 'Process-wide':
                        if (!isset($_SESSION['StatePicked']['t0process']['k0process']))
                            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 5.');
                        $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0process']['k0process'];
                        break;
                    default:
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 6.');
                }
            }
        }
        $EncryptedUserInfo = $Zfpf->encrypt_1c(substr($Zfpf->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF));
        $EncryptedTime = $Zfpf->encrypt_1c(time());
        $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Scratch']['t0'.$ccsa]['c5who_is_editing']) == '[A new database row is being created.]') {
            $Zfpf->insert_sql_1s($DBMSresource, 't0'.$ccsa, $ChangedRow); // Here $ChangedRow is the new CCSA row.
            $_SESSION['Scratch']['t0'.$ccsa] = $ChangedRow;
            // Insert JT row that points to new CCSA.
            $NewRow = array(
                'k0'.$TableRoot.'_'.$ccsa => time().mt_rand(1000000, 9999999),
                'k0'.$TableRoot.'' => $k0TableRoot,
                'k0'.$ccsa => $ChangedRow['k0'.$ccsa],
                'c5who_is_editing' => $EncryptedNobody
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0'.$TableRoot.'_'.$ccsa, $NewRow);
            $Message = '<h1>
            New '.$ccsa.' recorded</h1><p>
            The app tried to record the '.$ccsa.' you drafted and to associate it with the '.$ReportType.'.</p>';
        }
        else { // The CCSA is being edited.
            $Conditions[0] = array('k0'.$ccsa, '=', $_SESSION['Scratch']['t0'.$ccsa]['k0'.$ccsa]);
            list($SR_JT_CCSA, $RR_JT_CCSA) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$TableRoot.'_'.$ccsa, $Conditions);
            if (!$RR_JT_CCSA) // Shall be at least one junction table (JT) row for every CCSA row.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 7.');
            if ($RR_JT_CCSA > 1) {
                $k0TableRootWithCCSA = array_column($SR_JT_CCSA, 'k0'.$TableRoot); // array_unique not needed because a CCSA can only be in a obsresult, incident, or scenario once.
                // Get all k0TableRoot in the obsresult report (t0audit), incident, or PHA
                if ($TableRoot == 'obsresult') { // Two layers: t0audit > t0obsresult
                    $ConditionsA[0] = array('k0audit', '=', $_SESSION['Selected']['k0audit']);
                    list($SROr, $RROr) = $Zfpf->select_sql_1s($DBMSresource, 't0obsresult', $ConditionsA);
                    if ($RROr)
                        foreach ($SROr as $VOr)
                            $k0TableRootInReport[] = $VOr['k0obsresult']; // array_unique not needed because each t0obsresult row linked to one t0audit row is unique (many-to-one).
                }
                if ($TableRoot == 'incident')
                    $k0TableRootInReport = $_SESSION['Selected']['k0incident']; // One layer: only one k0incident per incident-investigation report.
                if ($TableRoot == 'scenario') { // Three layers: pha > subprocess > scenario
                    $ConditionsA[0] = array('k0pha', '=', $_SESSION['Selected']['k0pha']);
                    list($SRSp, $RRSp) = $Zfpf->select_sql_1s($DBMSresource, 't0subprocess', $ConditionsA);
                    if ($RRSp)
                        foreach ($SRSp as $VSp) {
                            $ConditionsA[0] = array('k0subprocess', '=', $VSp['k0subprocess']);
                            list($SRSc, $RRSc) = $Zfpf->select_sql_1s($DBMSresource, 't0scenario', $ConditionsA);
                            if ($RRSc)
                                foreach ($SRSc as $VSc)
                                    $k0TableRootInReport[] = $VSc['k0scenario']; // array_unique not needed because each scenario in PHA is unique (cannot be in two subprocesses.)
                        }
                }
                if (!isset($k0TableRootInReport))
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 8.');
            }
            if ($RR_JT_CCSA == 1 or count(array_diff($k0TableRootWithCCSA, $k0TableRootInReport)) == 0) {
                // First case above indicates the CCSA is only associated with one TableRoot (obsresult, incident, or scenario) in the entire database.
                // Second case means the CCSA is only referenced by the current obsresult report (t0audit), incident, or PHA
                // Either way, can just update the 't0'.$ccsa row
                // If only one JT row associated with CCSA, it shall be associated with the TableRoot.
                if ($RR_JT_CCSA == 1 and $SR_JT_CCSA[0]['k0'.$ccsa] != $_SESSION['Scratch']['t0'.$ccsa]['k0'.$ccsa])
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 9.');
                if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
                    $Zfpf->send_to_contents_1c(); // Don't eject
                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0'.$ccsa, $ChangedRow, $Conditions);
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 10. Affected Rows: '.@$Affected);
                foreach ($ChangedRow as $K => $V)
                    $_SESSION['Scratch']['t0'.$ccsa][$K] = $V;
                // No need to update the JT ('t0'.$TableRoot.'_'.$ccsa) because it already points to the above updated 't0'.$ccsa
            }
            // TO DO 2019-05-29 Note: currently, the case below can never happen because only draft proposed actions may be edited but only open (non-draft) actions can be referenced by another report. However, if later someone wants to allow draft proposed actions to be referenced by multiple reports (obsresult reports, investigations, PHA) this function would support it.
            else { // CCSA used in more than one PHAs, report, etc., do, for example:
                        // only revise CCSA for current PHA (but for all its scenarios associated with the CCSA)
                        // Insert a new t0'.$ccsa row and point all uses of the CCSA in the PHA to it.
                foreach ($_SESSION['Scratch']['t0'.$ccsa] as $K => $V)
                    if (!isset($ChangedRow[$K]))
                        $ChangedRow[$K] = $V; // Complete $ChangedRow for insert below, as if a '[A new database row is being created.]'
                $ChangedRow['k0'.$ccsa] = time().mt_rand(1000000, 9999999); // Needed to insert a new t0'.$ccsa row; the new k0 hasn't been initialized in i0n code.
                $Zfpf->insert_sql_1s($DBMSresource, 't0'.$ccsa, $ChangedRow); // Here $ChangedRow is the edited CCSA row, with a new key (k0...).
                $_SESSION['Scratch']['t0'.$ccsa] = $ChangedRow;
                foreach ($k0TableRootInReport as $V) {
                    // Update JT row that points to new CCSA.
                    $Conditions[1] = array('k0'.$TableRoot, '=', $V, '', ' AND '); // See CoreZfpf::where_qsl_1s. Adds to $Conditions[0] above to find JT row pointing to former CCSA.
                    // S below means scenario, it stands in for obsresult and incident as well.
                    list($SR_JT_S_CCSA, $RR_JT_S_CCSA) = $Zfpf->select_sql_1s($DBMSresource, 't0'.$TableRoot.'_'.$ccsa, $Conditions);
                    if ($RR_JT_S_CCSA > 1) // Only one JT row per S-CCSA pair. Could be 0 if CCSA not in S.
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' ccsa_edit() Eject Case 11. Rows Returned: '.@$RR_JT_S_CCSA);
                    if ($RR_JT_S_CCSA == 1) { // CCSA is in S.
                        $UpdateOnlyKey['k0'.$ccsa] = $ChangedRow['k0'.$ccsa];
                        if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
                            $Zfpf->send_to_contents_1c(); // Don't eject
                        $Zfpf->update_sql_1s($DBMSresource, 't0'.$TableRoot.'_'.$ccsa, $UpdateOnlyKey, $Conditions);
                    }
                }
            }
            $Message = '<h1>
            Edited '.$ccsa.'</h1><p>
            The app tried to record the revisions you made to the '.$ccsa.' and to associate the revised '.$ccsa.' with the current report, wherever used.</p>';
        }
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        $Message .= '<p>
        The draft document you were editing has been updated with your changes. This document will remain a draft until it is issued by the team leader.</p>
        <form action="'.$ccsa.'_io03.php" method="post"><p>
            <input type="submit" name="'.$ccsa.'_o1" value="Back to '.$ccsa.'" /></p>
        </form>
        <form action="'.$TableRoot.'_io03.php" method="post"><p>
            <input type="submit" name="'.$TableRoot.'_o1" value="Go back" /></p>
        </form>';
        return $Zfpf->xhtml_contents_header_1c('Done '.$ccsa).$Message.$Zfpf->xhtml_footer_1c();
    }

}

