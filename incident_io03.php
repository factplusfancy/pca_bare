<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the incident input and output HTML forms, except the:
//  - i1m file for listing existing records (and giving the option to start a new record)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'incident_i1m.php' or !isset($_SESSION['StatePicked']['t0owner']) or !isset($_SESSION['StatePicked']['t0facility']) or !isset($_SESSION['StatePicked']['t0process']) or !isset($_SESSION['t0user_practice'])) // Process must be selected, so facility and owner must too, all three needed below.
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get current-user information...
$User = $Zfpf->current_user_info_1c();
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$EditAuth = FALSE;
if ($User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
    $EditAuth = TRUE;
$UserIsProcessPSMLeader = FALSE; // No incident templates
$Process = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['StatePicked']['t0process']['k0process']); // The PSM-CAP App requires incident investigations to be associated with a process. See above general security check.
if ($_SESSION['t0user']['k0user'] == $Process['AELeader_k0user'])
    $UserIsProcessPSMLeader = TRUE;
$EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
$EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array(
        'Incident standard name', 
        '', 
        C5_MAX_BYTES_ZFPF, 
        'app_assigned'
    ),
    'c5status' => array(
        'Investigation status', 
        '', 
        C5_MAX_BYTES_ZFPF, 
        'app_assigned'
    ),
    'c5ts_incident_start' => array(
        '<a id="c5ts_incident_start"></a><b>Timing.</b><br />Incident-start date and time (app will update incident name with this)', 
        REQUIRED_FIELD_ZFPF
    ),
    'c5ts_incident_end' => array(
        'Incident-end date and time', 
        REQUIRED_FIELD_ZFPF
    ),
    'c5ts_investigation_start' => array(
        'Investigation-start date and time', 
        REQUIRED_FIELD_ZFPF
    ),
    'k0user_of_leader' => array(
        '<a id="k0user_of_leader"></a><b>Investigation team.</b> Include on the team:<br />
        (A) one person knowledgeable about the '.HAZSUB_PROCESS_NAME_ZFPF.' involved in the incident,<br />
        (B) an employee of any contractor whose work was involved in the incident, and<br />
        (C) anyone else needed to thoroughly understand the incident, including its causes and preventing a recurrence.<br /><br />
        Team-leader name, job title, and organization',
        ''
    ), // This field put here, rather than in order of schema, to display above to the fields immediately below.
    'c5leader_qualifications' => array(
        'Team-leader qualifications', 
        REQUIRED_FIELD_ZFPF
    ),
    'c6other_team_members' => array(
        'Other team-member names, titles, organizations, and any relevant qualifications', 
        REQUIRED_FIELD_ZFPF, 
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6bfn_act_notice' => array(
        '<a id="c6bfn_act_notice"></a>Activity notice(s) posted', 
        '', 
        MAX_FILE_SIZE_ZFPF, 
        'upload_files'
    ),
    'c6incident_location' => array(
        '<a id="c6incident_location"></a><b>Location<br />
        '.$Process['AEFullDescription'].'</b><br />
        Describe location of the incident, areas affected, and source of any leak',
        REQUIRED_FIELD_ZFPF,
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c5severity_summary' => array(
        '<a id="c5severity_summary"></a><b>Severity summary</b>', 
        REQUIRED_FIELD_ZFPF, 
        C5_MAX_BYTES_ZFPF, 
        'radio', 
        array('Catastrophic release or leak', 'Near miss to catastrophic release (possibly combined with a smaller leak)', 'Smaller (not catastrophic) leak (investigation optional, some investigation and tracking suggested)', 'Near miss to smaller leak (investigation optional)')
    ),
    'c6summary_description' => array(
        '<a id="c6summary_description"></a><b>Short description of the incident for employees and the public</b><br />
        Include neither confidential nor security-sensitive information here because this summary is often posted on employee-notice boards... Typically, describe what, if anything, leaked, including the hazardous substances and other materials or energy sources, such as lubricating oil, steam... Indicate if a fire or explosion occurred',
        REQUIRED_FIELD_ZFPF,
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c5state_of_leaked_hs' => array(
        '<a id="c5state_of_leaked_hs"></a><b>State of leaked hazardous substance (if applicable)</b>',
        '',
        C5_MAX_BYTES_ZFPF, 
        'radio', 
        array('Gas', 'Liquid', 'Liquid and gas', 'Other (powders, slurries...)')
    ),
    'c6mechanism_leaked' => array(
        '<a id="c6mechanism_leaked"></a><b>Mechanism that leaked</b> or almost leaked for near misses. Describe:<br />
        (A) the equipment package this mechanism was part of, such as compressor, pump, vessel, valve, piping...,<br />
        (B) the mechanism itself, such as valve discharging to the atmosphere, joint (bolted, brazed, threaded, welded...), shaft seal, wall of pipe, vessel, valve body, hose...),<br />
        (C) relevant materials and methods of the mechanism, including any gaskets, springs, fabrication methods...,<br />
        (D) other significant details, such as was the leak from a connection for adding or removing material, draining oil, pressure relief...',
        REQUIRED_FIELD_ZFPF,
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6circumstances' => array(
        '<a id="c6circumstances"></a><b>Weather conditions (as much detail as available) and circumstances leading up to the incident</b> (include only job titles of any people involved, number to distinguish them if needed)',
        REQUIRED_FIELD_ZFPF, 
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6proximate_cause' => array(
        '<a id="c6proximate_cause"></a><b>Proximate cause of the incident</b>. Describe the initiating event(s) that directly led to the incident',
        REQUIRED_FIELD_ZFPF, 
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6events_actions' => array(
        '<a id="c6events_actions"></a><b>Events and activities as the incident unfolded</b>, including the timing and details of any notifications to offsite responders and governmental authorities',
        REQUIRED_FIELD_ZFPF,
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6mitigating' => array(
        '<a id="c6mitigating"></a><b>Mitigating acts or circumstances</b>. Describe safeguards, decisions, responses, or other circumstances (automated or human) that helped control the incident',
        REQUIRED_FIELD_ZFPF,
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6loss_damage' => array(
        '<a id="c6loss_damage"></a><b>Losses and damage from the incident.</b> Describe as applicable:<br />
        (A) chemical name (or equivalent) and estimated quantity of each hazardous substance released (and weight percent for mixtures),<br />
        (B) fatalities (number onsite or offsite),<br />
        (C) injuries requiring medical treatment or hospitalization (number onsite or offsite),<br />
        (D) property damage (cost estimate for onsite or offsite),<br />
        (E) environmental damage (other than above property-damage estimates),<br />
        (F) people evacuated (approximate number onsite and offsite),<br />
        (G) people sheltered-in-place (approximate number onsite and offsite),<br />
        (H) lost production or product losses,<br />
        (I) news reports or other effects on reputation,<br />
        (J) other significant negative consequences, or<br />
        none -- for near misses',
        REQUIRED_FIELD_ZFPF,
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6root_cause' => array(
        '<a id="c6root_cause"></a><b>Root cause and additional contributing causes of the incident, its likelihood, or its severity.</b><br /> Describe any particular problem or contributing shortcomings that led to the incident. Consider the following rough types of causes.<br />
        (A) People: administrative controls, unauthorized changes, contractor qualifications, human factors, procedures, training, and so forth.<br />
        (B) Things in/of/for the '.HAZSUB_PROCESS_NAME_ZFPF.': design, fabrication, construction, maintenance, aging, corrosion, expansion or contraction (of materials in the '.HAZSUB_PROCESS_NAME_ZFPF.' or its containment envelope), shocks, vibrations, and so forth of equipment, piping, vessels, supports (including structures and buildings), controls, safety systems, labeling, lighting, and so forth.<br />
        (C) External events: earthquake, lightning strike, electrical power surge or outage, external explosion or fire, flooding, high winds (hurricane, sand storm, tornado), train derailment, airplane crash, failure of cooling, compressed air, or other utilities, and so forth.',
        REQUIRED_FIELD_ZFPF,
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6bfn_supporting' => array(
        '<a id="c6bfn_supporting"></a><b>Supporting documents.</b> These are optional and may include incident-investigation team attendance records, photos, or a report appropriate for the complexity of the incident', 
        '', 
        MAX_FILE_SIZE_ZFPF, 
        'upload_files'
    ),
    'c6affected_personel' => array(
        '<a id="c6affected_personel"></a><b>Communicating with stakeholders</b>, such as employees, contractors, governmental authorities, or the public. Describe (such as by names, job titles, departments, or employers) the employees or the contractors whose job tasks are relevant to the incident-investigation conclusions and recommendations. The incident-investigation report shall be reviewed with these employees or contractors. Describing any needed or required reporting to governmental authorities or public meetings, for example, under 40 CFR 68.168, 68.195, or 68.210(b) in the USA',
        REQUIRED_FIELD_ZFPF, 
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6bfn_review_attendance' => array(
        '<a id="c6bfn_review_attendance"></a><b>Stakeholder-communications documents</b> Attendance records or other documentation that needed or required communications, described above, were made', 
        '',
        MAX_FILE_SIZE_ZFPF, 
        'upload_files'
    )
);
$Types = array('action' => '<b>Actions, proposed or referenced</b>, including any recommendations to prevent future incidents. If a recommendation can be resolved by completing an open action, already in this app\'s action register, this open action should be referenced. Otherwise, a proposed action should be drafted, which this app logs in its action register once the report is approved by the '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for the '.HAZSUB_PROCESS_NAME_ZFPF.' where the incident occurred'); // See ccsaZfpf::scenario_CCSA_Zfpf

//Left hand Table of contents
if (!isset($_POST['incident_i2']) and !isset($_POST['incident_history_o1']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
        'c5ts_incident_start' => 'Incident timing',
        'k0user_of_leader' => 'Team',
        'c6bfn_act_notice' => 'Activity notice',
        'c6incident_location' => 'Location',
        'c5severity_summary' => 'Severity',
        'c6summary_description' => 'Description',
        'c5state_of_leaked_hs' => 'Physical state',
        'c6mechanism_leaked' => 'Mechanism',
        'c6circumstances' => 'Circumstances',
        'c6events_actions' => 'Events and activities',
        'c6loss_damage' => 'Losses and damage',
        'c6proximate_cause' => 'Proximate cause',
        'c6mitigating' => 'Mitigating acts',
        'c6root_cause' => 'Root cause',
        'c6bfn_supporting' => 'Supporting documents',
        'c6affected_personel' => 'Communicating with stakeholders',
        'c6bfn_review_attendance' => 'Stakeholder-communications documents',
        'actions' => 'Actions' // SPECIAL CASE, id="actions" used below
    );

// The if clauses below determine which HTML button the user pressed.

// generate activity notice code, put before i0n initialization of $_SESSION['Selected'], for security check below.
if (isset($_GET['act_notice_1'])) {
    if (!isset($_SESSION['Selected']['k0incident']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/ActNoticeZfpf.php';
    $ActNoticeZfpf = new ActNoticeZfpf;
    echo $ActNoticeZfpf->act_notice_generate($Zfpf, 'incident');
    $Zfpf->save_and_exit_1c();
}

// i0n code
if (isset($_POST['incident_i0n'])) {
    // Additional security check.
    if ($User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // Initial draft of investigation name.
    $DraftName = '[Incident-start time will be added by app.] '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']); // These must be set to start an investigation, see security check.
    // Initialize $_SESSION['Selected']
    $_SESSION['Selected'] = array(
        'k0incident' => time().mt_rand(1000000, 9999999),
        'k0process' => $_SESSION['StatePicked']['t0process']['k0process'],
        'k0user_of_leader' => $_SESSION['t0user']['k0user'],
        'c5name' => $Zfpf->encrypt_1c(substr($DraftName, 0, C5_MAX_BYTES_ZFPF)),
        'c5status' => $Zfpf->encrypt_1c('draft'),
        'c5ts_incident_start' => $EncryptedNothing,
        'c5ts_incident_end' => $EncryptedNothing,
        'c5ts_investigation_start' => $EncryptedNothing,
        'c5leader_qualifications' => $EncryptedNothing,
        'c6other_team_members' => $EncryptedNothing,
        'c6bfn_act_notice' => $EncryptedNothing,
        'c6incident_location' => $EncryptedNothing,
        'c5severity_summary' => $EncryptedNothing,
        'c6summary_description' => $EncryptedNothing,
        'c5state_of_leaked_hs' => $EncryptedNothing,
        'c6mechanism_leaked' => $EncryptedNothing,
        'c6circumstances' => $EncryptedNothing,
        'c6events_actions' => $EncryptedNothing,
        'c6loss_damage' => $EncryptedNothing,
        'c6proximate_cause' => $EncryptedNothing,
        'c6mitigating' => $EncryptedNothing,
        'c6root_cause' => $EncryptedNothing,
        'c6bfn_supporting' => $EncryptedNothing,
        'c6affected_personel' => $EncryptedNothing,
        'c6bfn_review_attendance' => $EncryptedNothing,
        'c5ts_leader' => $EncryptedNothing,
        'c6nymd_leader' => $EncryptedNothing,
        'k0user_of_ae_leader' => 0,
        'c5ts_ae_leader' => $EncryptedNothing,
        'c6nymd_ae_leader' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['incident_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0incident']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0incident', $_SESSION['Selected']['k0incident']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one incident record', 'incident_io03.php', 'incident_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0incident']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'incident_io03.php', 'incident_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'incident_io03.php', 'incident_o1');
    $_POST['incident_o1'] = 1; // Only needed as catch, after minor stuff, like user forgot to select a file before downloading, or password check before download.
}

// o1 code
if (isset($_POST['incident_o1'])) {
    // Additional security check.
    if (!isset($_SESSION['Selected']['k0incident']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0incident'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']))
        unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']);
    if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action']))
        unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action']);
    if (!isset($_SESSION['Selected']['k0incident'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0incident'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0incident'][$CheckedPost];
        unset($_SESSION['SelectResults']);
    }
    $Zfpf->clear_edit_lock_1c();
    // Cannot add "Generate an activity notice" option, in o1 display, because these go back to i1, ejecting user if not draft...
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Selected'], TRUE);
    // Handle k0 field(s)
    $TeamLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
    $Display['k0user_of_leader'] = $TeamLeader['NameTitle'].', '.$TeamLeader['Employer'];
    if ($_SESSION['Selected']['k0user_of_ae_leader'] == 1) { // See app schema.
        $htmlFormArray['k0user_of_ae_leader'] = array('<b>Incident-investigation report approved by the investigation-team leader</b>', '');
        $Display['k0user_of_ae_leader'] = $Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']); // Bootstrap this into k0user_of_ae_leader field.
    }
    if ($_SESSION['Selected']['k0user_of_ae_leader'] > 1) {
        $htmlFormArray['k0user_of_ae_leader'] = array('<b>Incident-investigation report approved by both</b>', '');
        $Display['k0user_of_ae_leader'] = '
        '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']).' (the investigation-team leader) <b>and</b><br />
        '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_ae_leader']).' (the affected-entity leader for PSM on behalf of the owner)';
    }
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
    $ccsaZfpf = new ccsaZfpf;
    $NoAddButton = TRUE;
    if (!$_SESSION['Selected']['k0user_of_ae_leader'] and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
        $NoAddButton = FALSE; // Bootstrap on $Issued variable in function below
    echo $Zfpf->xhtml_contents_header_1c('Investigation').'<h2>
    Incident-Investigation Report</h2>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'incident_io03.php', $_SESSION['Selected'], $Display).'
    <a id="actions"></a>'.$ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Selected'], $NoAddButton, $User, $UserPracticePrivileges, $Zfpf, TRUE, 'incident', $Types);
    // Check if anyone else is editing the selected row and check user privileges. See messages to the user below regarding privileges.
    $Status = $Zfpf->decrypt_1c($_SESSION['Selected']['c5status']);
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '
        <p><b>'.$who_is_editing.' is editing the incident-investigation record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    elseif ($Status == 'draft' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'] and $EditAuth)
        echo '
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="incident_o1_from" value="Update this record" /></p>
        </form>';
    else {
        echo '
        <p>You don\'t have editing privileges on this incident-investigation record. Only draft incident-investigation records can be edited, and only the investigation-team leader can edit these.</p>';
        if ($Status != 'draft')
            echo '<p>
            This is not a draft investigation record. The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader may cancel owner approval of an investigation and, optionally, change the investigation-team leader. The team leader may cancel the team-leader approval, making the investigation record a draft that the team leader may edit.</p>';
        if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
        if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    if ($who_is_editing == '[Nobody is editing.]' and $EditAuth) {
        // Change team-leader button
        if ($Status != 'owner approved' and $UserIsProcessPSMLeader)
            echo '
            <form action="incident_io03.php" method="post"><p>
                <input type="submit" name="change_leader_1" value="Change team leader" /></p>
            </form>';
        if ($Status == 'owner approved' and $UserIsProcessPSMLeader)
            echo '<p>
            To change the investigation-team leader, you (the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader) need to first cancel owner approval of this incident-investigation record.</p>';
        // Team-leader approve button
        if ($Status == 'draft' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
            echo '
            <form action="incident_io03.php" method="post"><p>
                <input type="submit" name="team_leader_approval" value="Approve draft report" /></p>
            </form>';
        // Team-leader cancel-approval button
        if ($Status == 'team-leader approved' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
            echo '
            <form action="incident_io03.php" method="post"><p>
                <input type="submit" name="team_leader_approval_c" value="Cancel team-leader approval" /></p>
            </form>';
        // Process leader cannot approve yet notice.
        if ($Status == 'draft' and $UserIsProcessPSMLeader)
            echo '<p>
            The incident-investigation team leader must approve this record before you (the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader) can approve it.</p>';
        // Process leader approve button (on behalf of owner)
        if ($Status == 'team-leader approved' and $UserIsProcessPSMLeader)
            echo '
            <form action="incident_io03.php" method="post"><p>
                <input type="submit" name="psm_leader_approval" value="Approve final report" /></p>
            </form>';
        // Process leader cancel-approval button (on behalf of owner)
        if ($Status == 'owner approved' and $UserIsProcessPSMLeader)
            echo '
            <form action="incident_io03.php" method="post"><p>
                <input type="submit" name="psm_leader_approval_c" value="Cancel owner approval" /></p>
            </form>';
    }
    echo '
    <form action="incident_io03.php" method="post"><p>
        <input type="submit" name="incident_history_o1" value="History of this record" /></p>
    </form>
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, i3, change team leader, and approval code
if (isset($_SESSION['Selected']['k0incident'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0incident', '=', $_SESSION['Selected']['k0incident']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0incident', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $Status = $Zfpf->decrypt_1c($_SESSION['Selected']['c5status']);
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    // Additional security check.
    if (($who_is_editing != '[A new database row is being created.]' and !$EditAuth) or $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // Get useful information
    $IncidentName = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
    $TeamLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
    $CurrentDate = date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j', time());
    if (isset($_POST['incident_o1_from']) or isset($_POST['team_leader_approval']) or isset($_POST['team_leader_approval_c']) or isset($_POST['psm_leader_approval']) or isset($_POST['psm_leader_approval_c']))
        $Zfpf->edit_lock_1c('incident', $IncidentName); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error.

    // Change team leader code
    if (isset($_POST['change_leader_1'])) {
        if (!$EditAuth or $Status == 'owner approved' or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Zfpf->clear_edit_lock_1c(); // In case arrived here by canceling from change_leader_2
        echo $Zfpf->xhtml_contents_header_1c('Lookup User');
        $Zfpf->lookup_user_1c('incident_io03.php', 'incident_io03.php', 'change_leader_2', 'incident_o1');
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['change_leader_2'])) {
        if (!$EditAuth or $Status == 'owner approved' or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Zfpf->edit_lock_1c('incident');
        $Conditions1[0] = array('k0process', '=', $_SESSION['Selected']['k0process']);
        $SpecialText = '<h2>
        Pick New Team Leader</h2>';
        $Zfpf->lookup_user_wrap_2c(
            't0user_process', // $TableNameUserEntity
            $Conditions1,
            'incident_io03.php', // $SubmitFile
            'incident_io03.php', // $TryAgainFile
            array('k0user'), // $Columns1
            'incident_io03.php', // $CancelFile
            $SpecialText,
            'Pick Team Leader', // $SpecialSubmitButton
            'change_leader_3', // $SubmitButtonName
            'change_leader_1', // $TryAgainButtonName
            'incident_o1', // $CancelButtonName
            'c5ts_logon_revoked', // $FilterColumnName
            '[Nothing has been recorded in this field.]' // $Filter
            ); // This function echos and exits.
    }
    if (isset($_POST['change_leader_3'])) {
        if (!$EditAuth or $Status == 'owner approved' or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        // Check user-input radio-button selection.
        // The user not selecting a radio button is OK in this case.
        if (isset($_POST['Selected'])) {
            $Selected = $Zfpf->post_length_1c('Selected');
            if (isset($_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]))
                $k0user = $_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]; // This is the k0user of the newly selected user.
            else
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        }
        else {
            echo $Zfpf->xhtml_contents_header_1c('Nobody New').'<p>
            It appears you did not select anyone.</p>
            <form action="incident_io03.php" method="post"><p>
                <input type="submit" name="change_leader_1" value="Try again" /><br />
                <input type="submit" name="incident_o1" value="Cancel" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            unset($_SESSION['Scratch']['PlainText']['lookup_user']);
            $Zfpf->save_and_exit_1c();
        }
        unset($_SESSION['Scratch']['PlainText']['lookup_user']);
        // Update database with $k0user
        $Conditions[0] = array('k0incident', '=', $_SESSION['Selected']['k0incident']);
        $Changes['k0user_of_leader'] = $k0user;
        $Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
        // SPECIAL CASE: Get former user info needed for notification email, before updating $_SESSION['Selected'].
        $OldTeamLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
        // Update $_SESSION['Selected']
        $_SESSION['Selected']['k0user_of_leader'] = $Changes['k0user_of_leader'];
        $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0incident', $Changes, $Conditions, TRUE, $htmlFormArray);
        // $Affected should not be zero because we confirmed that a new user was selected above.
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the former and newly-assigned Team Leaders and the Affected-Entity Leader.
        $NewTeamLeader = $Zfpf->user_job_info_1c($k0user); // Newly set, so get info here. Other info variables set above.
	    $EmailAddresses[] = $Process['AELeaderWorkEmail'];
        $EmailAddresses[] = $NewTeamLeader['WorkEmail'];
        $EmailAddresses[] = $OldTeamLeader['WorkEmail'];
	    $Subject = 'PSM-CAP: New Incident-Investigation Team Leader Assigned: '.$NewTeamLeader['NameTitle'];
	    $Body = '<p>
	    The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader changed the incident-investigation team leader, as described on the distribution list below, for:<br />
        * Incident Name: '.$IncidentName.'<br />
        * '.$Process['AEFullDescription'].'</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' '.$Process['AELeaderWorkEmail'].'<br />
        New team leader: '.$NewTeamLeader['NameTitle'].', '.$NewTeamLeader['Employer'].' '.$NewTeamLeader['WorkEmail'].'<br />
        Former team leader: '.$OldTeamLeader['NameTitle'].', '.$OldTeamLeader['Employer'].' '.$OldTeamLeader['WorkEmail'].'</p>';
	    $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], '', $DistributionList);
	    $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Done').'<h2>
        The user you selected is now the incident-investigation team leader.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="incident_o1" value="Back to investigation record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Team leader approving report 1
    if (isset($_POST['team_leader_approval'])) {
        // Additional security check.
        if (!$EditAuth or $Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'])
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_leader'] = $TeamLeader['NameTitle'].', '.$TeamLeader['Employer'];
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
        $ccsaZfpf = new ccsaZfpf;
        $NoAddButton = TRUE;
        if (!$_SESSION['Selected']['k0user_of_ae_leader'] and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
            $NoAddButton = FALSE;
        $ApprovalText = '<h1>
        Incident Investigation: Team Leader Approval of Report</h1>
        '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'
        <a id="actions"></a>'.$ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Selected'], $NoAddButton, $User, $UserPracticePrivileges, $Zfpf, FALSE, 'incident', $Types).'<p>
        <b>By clicking "Approve investigation report" below, as the leader of the team that prepared the above-described incident-investigation report for '.$Process['AEFullDescription'].', I am confirming that:<br />
         - I have been, since I started leading the incident investigation that this report documents, and I am now qualified to lead this incident investigation,<br />
         - I am qualified to approve this report, and<br />
         - I approve the incident-investigation report.</b></p><p>
        <b>Approved By:</b><br />
        Name: <b>'.$User['Name'].'</b><br />
        Job Title: <b>'.$User['Title'].'</b><br />
        Employer<b>: '.$User['Employer'].'</b><br />
        Email Address<b>: '.$User['WorkEmail'].'</b><br />
        Date: <b>'.$CurrentDate.'</b></p>';
        echo $Zfpf->xhtml_contents_header_1c('Confirm Approval').$ApprovalText.'
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="team_leader_approval_2" value="Approve investigation report" /></p><p>
            <input type="submit" name="incident_o1" value="Take no action -- go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
        $_SESSION['Scratch']['c6nymd_leader'] = $Zfpf->encrypt_1c($User['NameTitleEmployerWorkEmail'].' on '.$CurrentDate);
        $Zfpf->save_and_exit_1c();
    }
    
    // Team leader approving report 2
    if (isset($_POST['team_leader_approval_2'])) {
        // Additional security check.
        if (!$EditAuth or $Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'])
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Conditions[0] = array('k0incident', '=', $_SESSION['Selected']['k0incident']);
        $Changes['c5status'] = $Zfpf->encrypt_1c('team-leader approved');
        $Changes['c5ts_leader'] = $Zfpf->encrypt_1c(time());
        $Changes['c6nymd_leader'] = $_SESSION['Scratch']['c6nymd_leader'];
        $Changes['k0user_of_ae_leader'] = 1;
        $Changes['c5who_is_editing'] = $EncryptedNobody;
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0incident', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        // Update $_SESSION['Selected']
        $_SESSION['Selected']['c5status'] = $Changes['c5status'];
        $_SESSION['Selected']['c5ts_leader'] = $Changes['c5ts_leader'];
        $_SESSION['Selected']['c6nymd_leader'] = $Changes['c6nymd_leader'];
        $_SESSION['Selected']['k0user_of_ae_leader'] = $Changes['k0user_of_ae_leader'];
        $_SESSION['Selected']['c5who_is_editing'] = $EncryptedNobody; // Same effect as clear_edit_lock_1c().
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the incident-investigation team leader and the process, facility, and owner leaders.
        $Chain = $Zfpf->up_the_chain_1c();
        $Chain['EmailAddresses'][] = $TeamLeader['WorkEmail']; // Duplicate email addresses removed by CoreZfpf::send_email_1c
        $Chain['DistributionList'] .= '<br />
        Incident-Investigation Team Leader: '.$TeamLeader['NameTitle'].', '.$TeamLeader['Employer'].' '.$TeamLeader['WorkEmail'].'</p>'; // Duplicate listing on distribution list desired, to show if same person filled multiple roles.
        $Subject = 'PSM-CAP: Report Approved by Team Leader. Investigation of Incident: '.$IncidentName;
        $Body = '<p>
        The incident-investigation team leader approved the investigation report for:<br />
        * Incident Name: '.$IncidentName.'<br />
        * '.$Process['AEFullDescription'].'</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $Chain['DistributionList']);
        $EmailSent = $Zfpf->send_email_1c($Chain['EmailAddresses'], $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Investigation').'<h2>
        You just approved the incident-investigation report. The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader should have been emailed, so they may review and approve it.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="incident_io03.php" method="post"><p>
           <input type="submit" name="incident_o1" value="Back to investigation record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Team leader canceling approval 1
    if (isset($_POST['team_leader_approval_c'])) {
        // Additional security check.
        if (!$EditAuth or $Status != 'team-leader approved' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'])
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_leader'] = $TeamLeader['NameTitle'].', '.$TeamLeader['Employer'];
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
        $ccsaZfpf = new ccsaZfpf;
        $NoAddButton = TRUE;
        if (!$_SESSION['Selected']['k0user_of_ae_leader'] and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
            $NoAddButton = FALSE;
        $ApprovalText = '<h1>
        Incident Investigation: Canceling Team Leader Approval of Report</h1>
        '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'
        <a id="actions"></a>'.$ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Selected'], $NoAddButton, $User, $UserPracticePrivileges, $Zfpf, FALSE, 'incident', $Types).'<p>
        <b>By clicking "Cancel team-leader approval" below, as the team leader for the above-described incident-investigation report for '.$Process['AEFullDescription'].', I cancel the team-leader approval.</b></p><p>
        This report had been approved by: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_leader']).'<br />
        with approval Timestamp: '.date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j, \T\i\m\e H:i:s', $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_leader'])).'</p><p>
        <b>Approval Canceled By:</b><br />
        Name: <b>'.$User['Name'].'</b><br />
        Job Title: <b>'.$User['Title'].'</b><br />
        Employer<b>: '.$User['Employer'].'</b><br />
        Email Address<b>: '.$User['WorkEmail'].'</b><br />
        Date: <b>'.$CurrentDate.'</b></p>';
        echo $Zfpf->xhtml_contents_header_1c('Cancel Approval').$ApprovalText.'
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="team_leader_approval_c2" value="Cancel team-leader approval" /></p><p>
            <input type="submit" name="incident_o1" value="Take no action -- go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $_SESSION['Scratch']['c6nymd_leader'] = $Zfpf->encrypt_1c($User['NameTitleEmployerWorkEmail'].' on '.$CurrentDate);
        $Zfpf->save_and_exit_1c();
    }
    
    // Team leader canceling approval 2
    if (isset($_POST['team_leader_approval_c2'])) {
        // Additional security check.
        if (!$EditAuth or $Status != 'team-leader approved' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'])
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Conditions[0] = array('k0incident', '=', $_SESSION['Selected']['k0incident']);
        $Changes['c5status'] = $Zfpf->encrypt_1c('draft');
        $Changes['c5ts_leader'] = $EncryptedNothing;    
        $Changes['c6nymd_leader'] = $EncryptedNothing;
        $Changes['k0user_of_ae_leader'] = 0;
        $Changes['c5who_is_editing'] = $EncryptedNobody; // Same effect as clear_edit_lock_1c().
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0incident', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        $_SESSION['Selected']['c5status'] = $Changes['c5status'];
        $_SESSION['Selected']['c5ts_leader'] = $Changes['c5ts_leader'];
        $_SESSION['Selected']['c6nymd_leader'] = $Changes['c6nymd_leader'];
        $_SESSION['Selected']['k0user_of_ae_leader'] = $Changes['k0user_of_ae_leader'];
        $_SESSION['Selected']['c5who_is_editing'] = $EncryptedNobody;
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the incident-investigation team leader and the process, facility, and owner leaders.
        $Chain = $Zfpf->up_the_chain_1c();
        $Chain['EmailAddresses'][] = $TeamLeader['WorkEmail'];
        $Chain['DistributionList'] .= 'Incident-Investigation Team Leader: '.$TeamLeader['NameTitle'].', '.$TeamLeader['Employer'].' '.$TeamLeader['WorkEmail'].'</p>';
        $Subject = 'PSM-CAP: Report Approval Canceled by Team Leader. Investigation of Incident: '.$IncidentName;
        $Body = '<p>
        The incident-investigation team leader canceled approval of the investigation report for:<br />
        * Incident Name: '.$IncidentName.'<br />
        * '.$Process['AEFullDescription'].'</p><p>
        This did <b>not</b> remove proposed actions in the previously issued report from the action register. The action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], '', $Chain['DistributionList']);
        $EmailSent = $Zfpf->send_email_1c($Chain['EmailAddresses'], $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Investigation').'<h2>
        You just canceled approval of the incident-investigation report.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '<p>
        This did <b>not</b> remove proposed actions in the previously issued report from the action register. The action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p>
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="incident_o1" value="Back to investigation record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Process leader approving report 1
    if (isset($_POST['psm_leader_approval'])) {
        // Additional security check.
        if (!$EditAuth or $Status != 'team-leader approved' or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_leader'] = $TeamLeader['NameTitle'].', '.$TeamLeader['Employer'];
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
        $ccsaZfpf = new ccsaZfpf;
        $NoAddButton = TRUE;
        if (!$_SESSION['Selected']['k0user_of_ae_leader'] and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
            $NoAddButton = FALSE;
        $ApprovalText = '<h1>
        Incident investigation: '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader approval of report</h1>
        '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'
        <a id="actions"></a>'.$ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Selected'], $NoAddButton, $User, $UserPracticePrivileges, $Zfpf, FALSE, 'incident', $Types).'<p>
        <b>BE CAREFUL:</b> Approving this report permanently logs its proposed actions in this app\'s action register, allowing other reports to reference them. You may cancel your approval of this report later, but doing this will <b>not</b> remove its proposed actions from the action register. The action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p><p>
        <b>By clicking "Approve report" below, as the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for '.$Process['AEFullDescription'].', I am confirming that:<br />
         - I carefully reviewed this incident-investigation report,<br />
         - I agree with its conclusions and recommendations, including its actions, proposed or referenced, and<br />
         - I approve this incident-investigation report.</b></p><p>
        <b>Approved By:</b><br />
        Name: <b>'.$User['Name'].'</b><br />
        Job Title: <b>'.$User['Title'].'</b><br />
        Employer<b>: '.$User['Employer'].'</b><br />
        Email Address<b>: '.$User['WorkEmail'].'</b><br />
        Date: <b>'.$CurrentDate.'</b></p>';
        echo $Zfpf->xhtml_contents_header_1c('Confirm Approval').$ApprovalText.'
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="psm_leader_approval_2" value="Approve report" /></p><p>
            <input type="submit" name="incident_o1" value="Take no action -- go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
        $_SESSION['Scratch']['c6nymd_ae_leader'] = $Zfpf->encrypt_1c($User['NameTitleEmployerWorkEmail'].' on '.$CurrentDate);
        $Zfpf->save_and_exit_1c();
    }
    
    // Process leader approving report 2
    if (isset($_POST['psm_leader_approval_2'])) {
        // Additional security check.
        if (!$EditAuth or $Status != 'team-leader approved' or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Conditions[0] = array('k0incident', '=', $_SESSION['Selected']['k0incident']);
        $Changes['c5status'] = $Zfpf->encrypt_1c('owner approved');
        $Changes['k0user_of_ae_leader'] = $_SESSION['t0user']['k0user'];
        $Changes['c5ts_ae_leader'] = $Zfpf->encrypt_1c(time());    
        $Changes['c6nymd_ae_leader'] = $_SESSION['Scratch']['c6nymd_ae_leader'];
        $Changes['c5who_is_editing'] = $EncryptedNobody;
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0incident', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        $_SESSION['Selected']['c5status'] = $Changes['c5status'];
        $_SESSION['Selected']['k0user_of_ae_leader'] = $Changes['k0user_of_ae_leader'];
        $_SESSION['Selected']['c5ts_ae_leader'] = $Changes['c5ts_ae_leader'];
        $_SESSION['Selected']['c6nymd_ae_leader'] = $Changes['c6nymd_ae_leader'];
        $_SESSION['Selected']['c5who_is_editing'] = $EncryptedNobody; // Same effect as clear_edit_lock_1c().
        // Update status of actions in incident-investigation report to 'Needs resolution'
        list($SelectResults['t0incident_action'], $RowsReturned['t0incident_action']) = $Zfpf->select_sql_1s($DBMSresource, 't0incident_action', $Conditions);
        if ($RowsReturned['t0incident_action'] > 0)
            foreach ($SelectResults['t0incident_action'] as $V) {
                $ConditionsAction[0] = array('k0action', '=', $V['k0action']);
                list($SelectResults['t0action'], $RowsReturned['t0action']) = $Zfpf->select_sql_1s($DBMSresource, 't0action', $ConditionsAction);
                if ($RowsReturned['t0action'] != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Rows Returned: '.@$RowsReturned['t0action']);
                if ($SelectResults['t0action'][0]['k0user_of_ae_leader'] == -2) { // See the app schema. Only update proposed actions created by this report, not already opened actions (in ar) that it references.
                    $NewStatus['c5status'] = $Zfpf->encrypt_1c('Needs resolution');
                    $NewStatus['k0user_of_ae_leader'] = 0; // See the app schema.
                    $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0action', $NewStatus, $ConditionsAction);
                    if ($Affected != 1)
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                    // SPECIAL CASE: Don't record the above UPDATE in the history table because well known that all actions updated as above when incident-investigation report is issued.
                }
            }
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the incident-investigation team leader and the process, facility, and owner leaders.
        $Chain = $Zfpf->up_the_chain_1c();
        $Chain['EmailAddresses'][] = $TeamLeader['WorkEmail'];
        $Chain['DistributionList'] .= 'Incident-Investigation Team Leader: '.$TeamLeader['NameTitle'].', '.$TeamLeader['Employer'].' '.$TeamLeader['WorkEmail'].'</p>';
        $Subject = 'PSM-CAP: Report Approved by the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader. Investigation of Incident: '.$IncidentName;
        $Body = '<p>
        The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader approved the investigation report for:<br />
        * Incident Name: '.$IncidentName.'<br />
        * '.$Process['AEFullDescription'].'</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $Chain['DistributionList']);
        $EmailSent = $Zfpf->send_email_1c($Chain['EmailAddresses'], $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Investigation').'<h2>
        You just approved this incident-investigation report, so it is now fully approved.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="incident_o1" value="Back to investigation record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Process Leader canceling approval 1
    if (isset($_POST['psm_leader_approval_c'])) {
        // Additional security check.
        if (!$EditAuth or $Status != 'owner approved' or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_leader'] = $TeamLeader['NameTitle'].', '.$TeamLeader['Employer'];
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
        $ccsaZfpf = new ccsaZfpf;
        $NoAddButton = TRUE;
        if (!$_SESSION['Selected']['k0user_of_ae_leader'] and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'])
            $NoAddButton = FALSE;
        $ApprovalText = '<h1>
        Incident investigation: canceling '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader approval of report</h1>
        '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'
        <a id="actions"></a>'.$ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Selected'], $NoAddButton, $User, $UserPracticePrivileges, $Zfpf, FALSE, 'incident', $Types).'<p>
        <b>By clicking "Cancel owner-approval" below, as the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for '.$Process['AEFullDescription'].', I cancel the owner approval of the above incident-investigation report.</b></p><p>
        Canceling owner approval will <b>not</b> remove the previously-approved report\'s proposed actions from the action register. The action register allows resolving actions without completing them, via its "resolution method" field, if a proposed action turns out to be unneeded or counterproductive.</p><p>
        This report had been approved by: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_ae_leader']).'<br />
        with approval Timestamp: '.date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j, \T\i\m\e H:i:s', $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_ae_leader'])).'</p>';
        echo $Zfpf->xhtml_contents_header_1c('Cancel Approval').$ApprovalText.'
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="psm_leader_approval_c2" value="Cancel owner-approval" /></p><p>
            <input type="submit" name="incident_o1" value="Take no action -- go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $_SESSION['Scratch']['c6nymd_ae_leader'] = $Zfpf->encrypt_1c($User['NameTitleEmployerWorkEmail'].' on '.$CurrentDate);
        $Zfpf->save_and_exit_1c();
    }
    
    // Process Leader canceling approval 2
    if (isset($_POST['psm_leader_approval_c2'])) {
        // Additional security check.
        if (!$EditAuth or $Status != 'owner approved' or !$UserIsProcessPSMLeader)
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Conditions[0] = array('k0incident', '=', $_SESSION['Selected']['k0incident']);
        $Changes['c5status'] = $Zfpf->encrypt_1c('team-leader approved');
        $Changes['k0user_of_ae_leader'] = 1;
        $Changes['c5ts_ae_leader'] = $EncryptedNothing;
        $Changes['c6nymd_ae_leader'] = $EncryptedNothing;
        $Changes['c5who_is_editing'] = $EncryptedNobody; // Same effect as clear_edit_lock_1c().
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0incident', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        $_SESSION['Selected']['c5status'] = $Changes['c5status'];
        $_SESSION['Selected']['k0user_of_ae_leader'] = $Changes['k0user_of_ae_leader'];
        $_SESSION['Selected']['c5ts_ae_leader'] = $Changes['c5ts_ae_leader'];
        $_SESSION['Selected']['c6nymd_ae_leader'] = $Changes['c6nymd_ae_leader'];
        $_SESSION['Selected']['c5who_is_editing'] = $EncryptedNobody;
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the incident-investigation team leader and the process, facility, and owner leaders.
        $Chain = $Zfpf->up_the_chain_1c();
        $Chain['EmailAddresses'][] = $TeamLeader['WorkEmail'];
        $Chain['DistributionList'] .= 'Incident-Investigation Team Leader: '.$TeamLeader['NameTitle'].', '.$TeamLeader['Employer'].' '.$TeamLeader['WorkEmail'].'</p>';
        $Subject = 'PSM-CAP: incident-investigation report approval canceled';
        $Body = '<p>
        The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader canceled approval the investigation report for:<br />
        * Incident Name: '.$IncidentName.'<br />
        * '.$Process['AEFullDescription'].'</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], '', $Chain['DistributionList']);
        $EmailSent = $Zfpf->send_email_1c($Chain['EmailAddresses'], $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Investigation').'<h2>
        You just canceled approval of this incident-investigation report.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="incident_o1" value="Back to investigation record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Additional security check for i1, i2, i3 code
    if ($Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'])
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['incident_i0n']) or isset($_POST['incident_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles & privileges.
        $Display['k0user_of_leader'] = $TeamLeader['NameTitle'].', '.$TeamLeader['Employer'];
        if ($Display['c5ts_incident_start'] != '[Nothing has been recorded in this field.]') {
            $DraftName = $Display['c5ts_incident_start'].', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']);
            $Display['c5name'] = substr($DraftName, 0, C5_MAX_BYTES_ZFPF);
        }
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
    // START upload_files special case 1 of 3.
    elseif (isset($_SESSION['Post']) and !isset($_POST['incident_i2'])) { // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        else {
            $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            foreach ($htmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Selected'][$K]);
                    // FilesZfpf::user_files_directory_1e has been checked, it supports t0incident
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, $K, 'incident_io03.php');
                    // FilesZfpf::6bfn_files_upload_1e updates $_SESSION['Selected'] and the database. 
                    // Or, it echos an error page an user has option to press $_POST['problem_c6bfn_files_upload_1e'] button.
                    $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
                    $SelectDisplay[$K] = $Zfpf->html_uploaded_files_1e($K); // Update the modified select display
                    $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($SelectDisplay);
                    $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($Zfpf->post_to_display_1e($htmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Post']), $UploadResults['count'], $K));
                    // $_SESSION['Post'] now holds $Display holding $_POST updated with $_SESSION['Selected']['c6bfn_...'] information.
                    header("Location: #$K"); // AN ANCHOR MUST BE SET FOR ALL upload_files FIELDS
                    $Zfpf->save_and_exit_1c();
                }
        }
    }
    if (!$_POST and isset($_SESSION['Post']))
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // This re-defines $Display after upload_files header() redirect above.
    // END upload_files special case 1 of 3.
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        // Create HTML form
        echo $Zfpf->xhtml_contents_header_1c('Investigation');
        echo '<h1>
        Incident-Investigation Report</h1>
        <form action="incident_io03.php" method="post" enctype="multipart/form-data" >'; // upload_files special case 2 of 3. To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        // Add "Generate an activity notice" option, for i1 display, if not a new record. Do here to keep out of history table.
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        if ($who_is_editing != '[A new database row is being created.]') 
            $htmlFormArray['c6bfn_act_notice'][0] .= '<br />
            <a class="toc" href="incident_io03.php?act_notice_1">[Generate an activity notice]</a>';
        echo $Zfpf->make_html_form_1e($htmlFormArray, $Display);
        require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
        $ccsaZfpf = new ccsaZfpf;
        echo '<a id="actions"></a>
        '.$ccsaZfpf->scenario_CCSA_Zfpf($_SESSION['Selected'], FALSE, $User, $UserPracticePrivileges, $Zfpf, FALSE, 'incident', $Types).'<p>
            <input type="submit" name="incident_i2" value="Review what you typed into form" /><br />
            If you only wanted to upload files, you are done.</p>
        </form>'; // upload_files special case 3 of 3.
        if ($who_is_editing == '[A new database row is being created.]')
            echo '
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>';
        else
            echo '
            <form action="incident_io03.php" method="post"><p>
                <input type="submit" name="incident_o1" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['incident_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('incident_io03.php', 'incident_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        // SPECIAL CASE: Insert or update the incident state time at begining of the incident name. These app-assigned names sort by date.
        if (isset($ChangedRow['c5ts_incident_start'])) {
            $DraftName = $Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($ChangedRow['c5ts_incident_start'])).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']); // These must be set to edit an investigation, see security check.
            $ChangedRow['c5name'] = $Zfpf->encrypt_1c(substr($DraftName, 0, C5_MAX_BYTES_ZFPF));
        }
        // END SPECIAL CASE
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') {
            $Zfpf->insert_sql_1s($DBMSresource, 't0incident', $ChangedRow);
        }
        else {
            $Conditions[0] = array('k0incident', '=', $_SESSION['Selected']['k0incident']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0incident', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c('Incident Investigation').'<p>
        The incident-investigation information you input and reviewed has been recorded as a draft. It will remain a draft until both the incident-investigation team leader and the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader approve the investigation.</p>
        <form action="incident_io03.php" method="post"><p>
            <input type="submit" name="incident_o1" value="Back to record" /></p>
        </form>
        <form action="practice_o1.php" method="post"><p>
            <input type="submit" value="Back to records list" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, i3, change team leader, and approval code

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

