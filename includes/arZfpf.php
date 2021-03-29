<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This class provides the Action Register (ar) code 

class arZfpf {

    // $Context describes the status and source of the action.
    //   $Context allowed values                      k0user_of_ae_leader value      c5status value
    //   'obsresult'                                  -2                          'Draft proposed action'
    //   'incident'                                   -2                          'Draft proposed action'
    //   'scenario'                                   -2                          'Draft proposed action'
    //   'Draft proposed action unassociated'         -1                          'Draft proposed action'
    //   'Needs resolution'                            0                          'Needs resolution'
    //   'Approved by resolution assigned to person'   1                          'Needs resolution' (Avoids confusion; owner approval needed.)
    //   'Resolution approved by owner'               >1 (ae_leader's k0user)     'Resolution approved by owner'
    public function ar_html_form_array($Context = FALSE) {
        // $htmlFormArray is specified in the PSM-CAP App standards, in file 0read_me_psm_cap_app_standards.txt.
        // Defaults below are used for displaying output (o1 code)
        $Legend = array (
            'c5name' => '<a id="c5name"></a>Action name',
            'c5status' => '<a id="c5status"></a>Status',
            'c5priority' => '<a id="c5priority"></a>Priority (aka risk rank or relative risk from PHA or HIRA)',
            'c5affected_entity' => '<a id="c5affected_entity"></a>Maximum scope of the action (the affected entity)',
            'c6deficiency' => '<a id="c6deficiency"></a>Deficiency summary. Required for audit and hazard-review findings, otherwise optional but recommended -- if it\'s not broke, don\'t fix it. See also details in any recommending report.',
            'c6details' => '<a id="c6details"></a>Additional information, including any resolution options',
            'c5ts_target' => '<a id="c5ts_target"></a>Target resolution date and time',
            'k0user_of_leader' => '<a id="k0user_of_leader"></a>Resolution assigned to',
            'c6notes' => '<a id="c6notes"></a>Resolution method, modifications before resolution (contesting observations or deficiencies, etc.), or other notes',
            'c6bfn_supporting' => '<a id="c6bfn_supporting"></a>Supporting documents. These are optional and may include work orders, attendance records, photos, or a report appropriate for the situation\'s complexity'
        );
        foreach ($Legend as $K => $V)
            $Required[$K] = '';
        // Customize for context. First address priority
        if ($Context == 'Draft proposed action unassociated' or $Context == 'obsresult' or $Context == 'incident' or $Context == 'scenario') {
            $Required['c5name'] = REQUIRED_FIELD_ZFPF;
            $Required['c5affected_entity'] = REQUIRED_FIELD_ZFPF;
            $Legend['c6deficiency'] = '<a id="c6deficiency"></a>Deficiency summary. Optional but recommended -- if it\'s not broke, don\'t fix it';
            $Legend['c6details'] = '<a id="c6details"></a>Additional information, including any resolution options';
            if ($Context == 'Draft proposed action unassociated' or $Context == 'obsresult' or $Context == 'incident') {
                $Legend['c5priority'] = '<a id="c5priority"></a>Priority';
                $Required['c5priority'] = REQUIRED_FIELD_ZFPF;
                if ($Context == 'obsresult')
                    $PriorityRadioButtons = array(_10_PRIORITY_ZFPF, _07_PRIORITY_ZFPF, _06_PRIORITY_ZFPF, _05_PRIORITY_ZFPF, _04_PRIORITY_ZFPF);
                else
                    $PriorityRadioButtons = array(_10_PRIORITY_ZFPF, _07_PRIORITY_ZFPF, _06_PRIORITY_ZFPF, _05_PRIORITY_ZFPF, _04_PRIORITY_ZFPF, _01_PRIORITY_ZFPF);            
                if ($Context == 'Draft proposed action unassociated') {
                    $Legend['c5name'] = '<a id="c5name"></a>Proposed action name (the task, summarized uniquely; used for sorting, so pick first word well)';
                    // Display radio button only if entity is selected, so its identify (and primary key, k0) is known.
                    if (isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
                        $AERadioButtons[] = 'Contractor-wide';
                    if (isset($_SESSION['StatePicked']['t0owner']['k0owner']))
                        $AERadioButtons[] = 'Owner-wide';
                    if (isset($_SESSION['StatePicked']['t0facility']['k0facility']))
                        $AERadioButtons[] = 'Facility-wide';
                    if (isset($_SESSION['StatePicked']['t0process']['k0process']))
                        $AERadioButtons[] = 'Process-wide';
                }
                elseif ($Context == 'obsresult') {
                    $Legend['c5name'] = '<a id="c5name"></a>Action name (the finding, summarized uniquely; used for sorting, so pick first word well)';
                    $AERadioButtons = array('Process-wide', 'Facility-wide', 'Owner-wide'); // Findings may only relate to a contractor indirectly via another affected entity.
                    $Legend['c6deficiency'] = '<a id="c6deficiency"></a>Deficiency summary. Which <a class="toc" href="glossary.php#rule" target="_blank">rule</a> was violated and how. Details should be this report\'s observations and results';
                    $Required['c6deficiency'] = REQUIRED_FIELD_ZFPF;
                }
            }
            if ($Context == 'incident' or $Context == 'scenario') {
                $Legend['c5name'] = '<a id="c5name"></a>Action name (the recommendation, summarized uniquely; used for sorting, so pick first word well)';
                $AERadioButtons = array('Process-wide', 'Facility-wide', 'Owner-wide'); // An incident investigation, PHA or HIRA is always associated with the process, but their recommendations may relate to the facility or the owner, but not to a contractor (except indirectly via another affected entity).
            }
        }
        if ($Context =='Needs resolution')
            $Required['c6notes'] = REQUIRED_FIELD_ZFPF; // Force something to be put here.
        $htmlFormArray = array(
            'c5name' => array(
                $Legend['c5name'],
                $Required['c5name'],
                C5_MAX_BYTES_ZFPF
            ),
            'c5status' => array(
                $Legend['c5status'],
                $Required['c5status'],
                C5_MAX_BYTES_ZFPF,
                'app_assigned' // Always app-assigned
            ),
            'c5priority' => array(
                $Legend['c5priority'],
                $Required['c5priority'],
                C5_MAX_BYTES_ZFPF
            ),
            'c5affected_entity' => array(
                $Legend['c5affected_entity'],
                $Required['c5affected_entity'],
                C5_MAX_BYTES_ZFPF
            ),
            // k0affected_entity omitted because c5affected_entity already triggers this field.
            'c6deficiency' => array(
                $Legend['c6deficiency'],
                $Required['c6deficiency'],
                C6LONG_MAX_BYTES_ZFPF
            ),
            'c6details' => array(
                $Legend['c6details'],
                $Required['c6details'],
                C6LONG_MAX_BYTES_ZFPF
            )
        );
        // Handle special cases.
        if ($Context == 'scenario') // PHA or HIRA priority app_assigned base on severity and likelihood.
            $htmlFormArray['c5priority'] = array(
                $Legend['c5priority'],
                $Required['c5priority'],
                C5_MAX_BYTES_ZFPF,
                'app_assigned'
            );
        if (isset($PriorityRadioButtons))
            $htmlFormArray['c5priority'] = array(
                $Legend['c5priority'],
                $Required['c5priority'],
                C5_MAX_BYTES_ZFPF,
                'radio',
                $PriorityRadioButtons
            );
        if (isset($AERadioButtons))
            $htmlFormArray['c5affected_entity'] = array(
                $Legend['c5affected_entity'],
                $Required['c5affected_entity'],
                C5_MAX_BYTES_ZFPF,
                'radio',
                $AERadioButtons
            );
        if (!$Context or $Context == 'Needs resolution' or $Context == 'Approved by resolution assigned to person' or $Context == 'Resolution approved by owner') {
            if ($Context == 'Needs resolution') {
                $htmlFormArray['c5ts_target'] = array(
                    $Legend['c5ts_target'],
                    $Required['c5ts_target'],
                    C5_MAX_BYTES_ZFPF
                );
                foreach ($htmlFormArray as $K => $V)
                    $htmlFormArray[$K][3] = 'app_assigned'; // Only 'Needs resolution' used in $htmlFormArray
            }
            $ResolutionFields = array(
                'k0user_of_leader' => array(
                    $Legend['k0user_of_leader'],
                    '',
                    C5_MAX_BYTES_ZFPF
                ),
                'c6notes' => array(
                    $Legend['c6notes'],
                    $Required['c6notes'],
                    C6LONG_MAX_BYTES_ZFPF
                ),
                'c6bfn_supporting' => array(
                    $Legend['c6bfn_supporting'],
                    $Required['c6bfn_supporting'],
                    MAX_FILE_SIZE_ZFPF,
                    'upload_files'
                )
            );
            $htmlFormArray = array_merge($htmlFormArray, $ResolutionFields);
            // Two cases below occur only in o1 and approval code
            if ($Context == 'Approved by resolution assigned to person') {
                $htmlFormArray['k0user_of_ae_leader'] = array(
                    'Resolution recommended for approval by',
                    ''
                );
            }
            if ($Context == 'Resolution approved by owner')
                $htmlFormArray['k0user_of_ae_leader'] = array(
                    'Approvals',
                    ''
                );
        }
        return $htmlFormArray;
    }

    // Session variables below must be set.
    // $SpecialCondition is a three element numeric array, for example: $SpecialCondition = ('k0user_of_ae_leader', '=', 0);
    public function conditions_state_picked($SpecialCondition = FALSE) {
        // SQL conditions to query all applicable affected entities from a k0affected_entity database field, based on the user's state picked.
        if (isset($_SESSION['StatePicked']['t0process']['k0process']))
            $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0process']['k0process'], 'OR');
        if (isset($_SESSION['StatePicked']['t0facility']['k0facility']))
            $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0facility']['k0facility'], 'OR');
        if (isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
            if (!isset($_SESSION['StatePicked']['t0owner']['k0owner'])) // Contractor-wide case.
                $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor']);
            else
                $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], 'OR');
        if (isset($_SESSION['StatePicked']['t0owner']['k0owner']))
            $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0owner']['k0owner']);
        // Append special condition.
        if ($SpecialCondition) {
            $Conditions[0][4] = '(';
            $SpecialCondition[3] = '';
            $SpecialCondition[4] = ') AND';
            $Conditions[] = $SpecialCondition;
        }
        return $Conditions;
    }

    // If $RowsReturned == 0, $_SESSION['SelectResults']['t0action'] doesn't have to be set, otherwise it must be set.
    // $_SESSION['Scratch']['PlainText']['action_ifrom_ar'] must be set if called from obsresult, incident, or scenario.
    // $RowsReturned is the number of values in $_SESSION['SelectResults']['t0action'], such as the rows returned by CoreZfpf::select_sql_1s().
    // $Description customizes $Message, below
    // $SortBy is the column name of the t0action table used for sorting
    // $SortOrder is either SORT_ASC to sort ascending or SORT_DESC to sort descending.
    public function actions_list($Zfpf, $RowsReturned, $Description = '', $SortBy = 'c5ts_target', $SortOrder = SORT_ASC) {
        if ($RowsReturned and isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar'])) { // Exclude from $_SESSION['SelectResults']['t0action'] actions already in $TableRoot
            $TableRoot = $_SESSION['Scratch']['PlainText']['action_ifrom_ar'];
            if ($TableRoot == 'obsresult')
                $k0TableRoot = $_SESSION['Scratch']['t0obsresult']['k0obsresult'];
            elseif ($TableRoot == 'incident')
                $k0TableRoot = $_SESSION['Selected']['k0incident'];
            elseif ($TableRoot == 'scenario')
                $k0TableRoot = $_SESSION['Scratch']['t0scenario']['k0scenario'];
            $Conditions[0] = array('k0'.$TableRoot, '=', $k0TableRoot);
            list($SR, $RR) = $Zfpf->one_shot_select_1s('t0'.$TableRoot.'_action', $Conditions);
            if ($RR) {
                foreach ($SR as $V)
                    $ActionsInTableRoot[] = $V['k0action'];
                foreach ($_SESSION['SelectResults']['t0action'] as $K => $V)
                    if (in_array($V['k0action'], $ActionsInTableRoot)) {
                        unset($_SESSION['SelectResults']['t0action'][$K]);
                        --$RowsReturned;
                    }
            }
        }
        $Message = '<h1>
        <a class="toc" href="glossary.php#actions" target="_blank">Action</a> Register</h1>
        <form action="ar_io03.php" method="post"><p>';
        if ($Description)
            $Message .= '<b>'.$Description.'</b>:<br />';
        if ($RowsReturned) {
            // 1st display actions with no resolution-target timing, 2nd display remaining actions, sorted ascending by $SortBy.
            foreach ($_SESSION['SelectResults']['t0action'] as $K => $V) {
                $SortByValue = $Zfpf->decrypt_1c($V[$SortBy]);
                if ($SortByValue == '[Nothing has been recorded in this field.]')
                    $SortArray[$K] = $V['k0action']/10000000000; // Display these first. k0 fields are the timestamp created concatenated with a 7-digit random number.
                else
                    $SortArray[$K] = $SortByValue;
            }
            array_multisort($SortArray, $SortOrder, $_SESSION['SelectResults']['t0action']); // PHP's array_multisort() default is sort ascending.
            $_SESSION['Scratch']['ActionRows'] = $_SESSION['SelectResults']['t0action']; // Used this to generate csv file
            foreach ($_SESSION['SelectResults']['t0action'] as $K => $V) {
                if ($K)
                    $Message .= '<br />';
                $Message .= '
                <input type="radio" name="selected" value="'.$K.'" ';
                if (!$K) // Select the first action by default to ensure something is posted (unless a hacker is tampering).
                    $Message .= 'checked="checked" ';
                $Message .= '/><b>'.$Zfpf->decrypt_1c($V['c5name']).'</b>';
                $SortByValue = $Zfpf->decrypt_1c($V[$SortBy]);
                if ($SortByValue != '[Nothing has been recorded in this field.]') {
                    $Message .= ' -- ';
                    if ($SortBy == 'c5ts_target')
                        $Message .= 'Resolution Target:  ';
                    if ($SortBy == 'c5priority')
                        $Message .= 'Priority:  ';
                    $Message .= $Zfpf->timestamp_to_display_1c($SortByValue);
                }
                elseif ($SortBy != 'c5priority') {
                    $Priority = $Zfpf->decrypt_1c($V['c5priority']);
                    if ($Priority != '[Nothing has been recorded in this field.]')
                        $Message .= ' -- Priority: '.$Priority; // To avoid crowded display, only show priority if no resolution target.
                }
            }
            $Message .= '</p><p>
                <input type="submit" name="ar_o1" value="View action" /></p><p>
                <a class="toc" href="ar_io03.php?ar_download_csv">Download above actions as csv file, delineated by: |</a>'; // paragraph closed outside if-else statement.
        }
        else
            $Message .= '- None Found (for the current process, facility, owner, or contractor).';
        $Message .= '</p><p>
        <b>View:</b><br />';
        if (!isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']))
            $Message .= '
            <a class="toc" href="ar_io03.php?ar_i1m_all">All open and closed actions</a><br />';
        $Message .= '
        <a class="toc" href="ar_io03.php?ar_i1m_rfa">Marked resolved actions</a><br />
        <a class="toc" href="ar_io03.php?ar_i1m_age">Unresolved actions</a><br />';
        if (!isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']))
            $Message .= '
            <a class="toc" href="ar_io03.php?ar_i1m_draft_unassociated">Proposed unassociated actions</a>. These are unassociated with a recommending report, like an audit report. They remain proposed actions (suggestions) until they are opened, as unresolved actions, by the affected-entity leader, representing the Owner/Operator.<br />';
        $Message .= '<br />
        <b>Sorting options for unresolved actions:</b><br />
        <a class="toc" href="ar_io03.php?ar_i1m_age">By resolution target (default)</a><br />
        <a class="toc" href="ar_io03.php?ar_i1m_priority">By priority</a><br />';
        if (isset($_SESSION['StatePicked']['t0process']['k0process'])) {
            $Message .= '
            <a class="toc" href="ar_io03.php?ar_i1m_audit">Findings from audits, hazard reviews...</a><br />
            <a class="toc" href="ar_io03.php?ar_i1m_incident">Incident investigation</a><br />
            <a class="toc" href="ar_io03.php?ar_i1m_pha">PHA or HIRA</a><br />
            <a class="toc" href="ar_io03.php?ar_i1m_process">Process</a><br />';
        }
        if (isset($_SESSION['StatePicked']['t0facility']['k0facility']))
            $Message .= '
            <a class="toc" href="ar_io03.php?ar_i1m_facility">Facility</a><br />';
        if (isset($_SESSION['StatePicked']['t0owner']['k0owner']))
            $Message .= '
            <a class="toc" href="ar_io03.php?ar_i1m_owner">Owner</a><br />';
        if (isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
            $Message .= '
            <a class="toc" href="ar_io03.php?ar_i1m_contractor">Contractor</a><br />';
        $Message .= '</p><p>';
        // Need at least INSERT global privileges to start a new record and suppress if user is selecting an open action to associate with an audit, PHA, etc.
        $GlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);
        if ($GlobalDBMSPrivileges != LOW_PRIVILEGES_ZFPF and !isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']))
            $Message .= '
            Propose a new action unassociated with a recommending report.<br />
            <input type="submit" name="ar_i0n" value="Propose unassociated action" />';
        elseif ($GlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF)
            $Message .= '
            <b>Global Privileges Notice</b>: You have privileges to neither create nor edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.';
        $Message .= '</p><p>
        An action is:<br />
        - opened, and unresolved, once the report recommending it (for audits, incident investigations, PHA or HIRA, etc.) is issued, per the approval process for the type of report (or once a proposed "unassociated action" is accepted by the '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader),<br />
        - marked resolved once the person assigned responsibility documents its resolution, and<br />
        - closed once its resolution is approved by the affected-entity\'s '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader, representing the <a class="toc" href="glossary.php#owner_operator" target="_blank">Owner/Operator</a>.</p>
        </form>';
        if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']))
            $Message .= '
            <form action="'.$_SESSION['Scratch']['PlainText']['action_ifrom_ar'].'_io03.php" method="post"><p>
                <input type="submit" name="'.$_SESSION['Scratch']['PlainText']['action_ifrom_ar'].'_o1" value="Go back" /></p>
            </form>';
        else
            $Message .= '
            <form action="contents0_s_practice.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>';
        return $Zfpf->xhtml_contents_header_1c('Select Action').$Message.$Zfpf->xhtml_footer_1c();
    }

}

