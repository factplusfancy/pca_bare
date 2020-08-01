<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the contractor-qualification input and output HTML forms, except the:
//  - i1m file for listing existing records (and giving the option to start a new record) 

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'contractor_qual_i1m.php' or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);
$Nothing = '[Nothing has been recorded in this field.]';
$EncryptedNothing = $Zfpf->encrypt_1c($Nothing);

$htmlFormArray = array(
	'c5focus' => array(
        'Work focus of this qualification record (contractors may have several of these)', // TO DO consider allowing: You may upload your policies.
        REQUIRED_FIELD_ZFPF
    ),
    'c6prior_work' => array(
        '<a id="c6prior_work"></a>References for work involving flammable, toxic, or other hazardous substances. Provide client contact information and work location, description, and value.',
        REQUIRED_FIELD_ZFPF,
        array (
            array('Reference', '', C6SHORT_MAX_BYTES_ZFPF)
        ),
        'add_field'
    ),
    'c6chemicals' => array(
        '<a id="c6chemicals"></a>Hazardous-substance experience. Indicate below the materials that your current employees have experience with on projects similar to work planned at the facility.',
        '',
        array(
            array('Ammonia, Anhydrous', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Ammonia, Aqua, 20% or greater', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Chlorine Gas', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Flammable Gas', '', C5_MAX_BYTES_ZFPF, 'checkbox'), 
            array('Flammable Liquid', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Sulfur Dioxide', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Another Process Safety Management chemical (listed in 29 CFR 1910.119, Appendix A) or other relevant substances', '', C6SHORT_MAX_BYTES_ZFPF)
        )
	),
    'c6safety_programs' => array(
        '<a id="c6safety_programs"></a>Safety programs. Check below if you have a written:',
        '',
        array(
            array('Safety training program', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Hazard communication program (per 29 CFR 1910.1200 or better)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Emergency action plan covering notifications, move-to-safety, headcount, and communications (per 29 CFR 1910.38 or better)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Emergency response plan and team (per 29 CFR 1910.120 or better)', '', C5_MAX_BYTES_ZFPF, 'checkbox')
        )
	),
    'c6training_method' => array(
        '<a id="c6training_method"></a>Training. Summarize your employee training, such as frequency, subjects covered, and method', // TO DO consider allowing: You may upload your policies.
        REQUIRED_FIELD_ZFPF,
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6training_eval' => array(
        'Testing. Summarize how you document that employees understood their training, for example, written tests or documented oral tests, observation, or demonstration',
        REQUIRED_FIELD_ZFPF,
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c6emr' => array(
        '<a id="c6emr"></a>Experience Modification Rating (EMR). This is typically calculated by your Workers Compensation insurer each year, by comparing your losses and payroll to similar employers.',
        REQUIRED_FIELD_ZFPF,
        array(
            array('Year', '', C5_MAX_BYTES_ZFPF, 'app_assigned'),
            array('Jurisdiction', '', C5_MAX_BYTES_ZFPF), // TO DO  Could use dropdown to ensure consistent jurisdiction names.
            array('EMR', '', C5_MAX_BYTES_ZFPF)
        ),
        'add_field'
	),
    'c6injury_illness' => array(
        '<a id="c6injury_illness"></a>Work-related injuries and illnesses. In the USA, the OSHA Log method gives rates per 200,000 hours worked per year (rate per 100 full-time employees)',
        REQUIRED_FIELD_ZFPF,
        array(
            array('Year', '', C5_MAX_BYTES_ZFPF, 'app_assigned'),
            array('Recordable-cases rate', '', C5_MAX_BYTES_ZFPF),
            array('Lost-time rate', '', C5_MAX_BYTES_ZFPF),
            array('Rate-calculation method', '', C5_MAX_BYTES_ZFPF)
	    ),
	    'add_field'
	),
    'c6bfn_fatalities' => array(
        '<a id="c6bfn_fatalities"></a><b>Fatalities</b>. For any work-related deaths of your organization\'s employees in the past three calendar years, upload reports', 
        '',
        MAX_FILE_SIZE_ZFPF,
        'upload_files'
    ),
    'c6bfn_law_violations' => array(
        '<a id="c6bfn_law_violations"><b>Law violations</b>. For any safety, heath, or environmental law violations for which your organization was penalized (fines, employee convictions, restitution, etc.) in the past three calendar years, upload the notice of violation, your reply, the final settlement, or similar documentation', 
        '',
        MAX_FILE_SIZE_ZFPF, 
        'upload_files'
    )
);

// Left-hand contents
if (!isset($_POST['contractor_qual_i2']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
	    'organization' => 'Contractor Info',
	    'c6prior_work' => 'References',
	    'c6chemicals' => 'Experience', 
	    'c6safety_programs' => 'Safety programs',  
	    'c6training_method' => 'Training and testing', 
	    'c6emr' => 'EMR',
	    'c6injury_illness' => 'Injuries',
	    'c6bfn_fatalities' => 'Fatalities',
	    'c6bfn_law_violations' => 'Law violations'
    );

// The if clauses below determine which HTML button the user pressed.

// i0n code
if (isset($_POST['contractor_qual_i0n'])) {
	// Additional security check. Only the contractor can create a qualification record about themselves.
	if (!isset($_SESSION['t0user_contractor']) or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
	// Initialize $_SESSION['Selected']
	$_SESSION['Selected'] = array(
        'k0contractor_qual' => time().mt_rand(1000000, 9999999),
		'k0contractor'      => $_SESSION['t0user_contractor']['k0contractor'],
		'c5focus'           => $EncryptedNothing,
		'c6prior_work'      => $Zfpf->encode_encrypt_1c(array($Nothing, $Nothing, $Nothing)),
		'c6chemicals'       => $Zfpf->encode_encrypt_1c(array($Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing)),
		'c6safety_programs' => $Zfpf->encode_encrypt_1c(array($Nothing, $Nothing, $Nothing, $Nothing)),
		'c6training_method' => $EncryptedNothing,
		'c6training_eval'   => $EncryptedNothing,
		'c6emr'             => $Zfpf->encode_encrypt_1c(array($Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing)), // require 3 years, 3 fields in each year subset
		'c6injury_illness'	=> $Zfpf->encode_encrypt_1c(array($Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing)), // require 3 years, 4 fields in each year subset
		'c6bfn_fatalities'  => $EncryptedNothing,
		'c6bfn_law_violations'  => $EncryptedNothing,
        'c5who_is_editing' 	=> $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
    // Do not exit, so i1 code below can run.
}

// history_o1 code
if (isset($_POST['contractor_qual_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0contractor_qual']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0contractor_qual', $_SESSION['Selected']['k0contractor_qual']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one contractor-qualifcation record', 'contractor_qual_io03.php', 'contractor_qual_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0contractor_qual']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'contractor_qual_io03.php', 'contractor_qual_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'contractor_qual_io03.php', 'contractor_qual_o1');
    $_POST['contractor_qual_o1'] = 1;
}

// o1 code
// $_SESSION['Selected'] is set in this if clause.
if (isset($_POST['contractor_qual_o1'])) {
    // Additional security check
    if (!isset($_SESSION['Selected']['k0contractor_qual']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0contractor_qual'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (!isset($_SESSION['Selected']['k0contractor_qual'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected'); // Get user selection.
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0contractor_qual'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0contractor_qual'][$CheckedPost];
        unset($_SESSION['SelectResults']);
    }
    $Zfpf->clear_edit_lock_1c();
    $Conditions[0] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor']);
    list($SRC, $RRC) = $Zfpf->one_shot_select_1s('t0contractor', $Conditions);
    if ($RRC != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    echo $Zfpf->xhtml_contents_header_1c('Contractor Qual').'
    <h2>
    Contractor Qualifications</h2>
    '.$Zfpf->organziation_info_html_1c($SRC[0]).'
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'contractor_qual_io03.php').'
    <form action="contractor_qual_io03.php" method="post">';
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    // Only the contractor can edit their qualification record.
    elseif (isset($_SESSION['t0user_contractor']) and $_SESSION['t0user_contractor']['k0contractor'] == $_SESSION['Selected']['k0contractor'] and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
        echo '<p>
        <input type="submit" name="contractor_qual_o1_from" value="Update this record" /></p>';
    else {
        echo '<p>You don\'t have editing privileges on this record. Only a contractor\'s employee with adequate privileges may edit its contractor-qualification record.</p>';
        if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
        if ($UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    if (isset($_SESSION['t0user_owner']) and isset($_SESSION['StatePicked']['t0owner']) and $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
        echo '<p>
        Document evaluation of this contractor qualification record.<br />
        <input type="submit" name="qual_evaluate_1" value="Document evaluation" /></p>';
    echo '<p>
        <input type="submit" name="contractor_qual_history_o1" value="History of this record" /></p>
    </form>
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, i3 code
if (isset($_SESSION['Selected']['k0contractor_qual'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0contractor_qual', '=', $_SESSION['Selected']['k0contractor_qual']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0contractor_qual', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);

    if (isset($_POST['qual_evaluate_1'])) {
        if (!isset($_SESSION['t0user_owner']) or !isset($_SESSION['StatePicked']['t0owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_contractor']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $UserPracticePrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Conditions[0] = array('k0contractor', '=', $_SESSION['Selected']['k0contractor']);
        list($SRC, $RRC) = $Zfpf->select_sql_1s($DBMSresource, 't0contractor', $Conditions);
        if ($RRC != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Conditions[1] = array('k0owner', '=', $_SESSION['t0user_owner']['k0owner'], '', 'AND');
        list($SROC, $RROC) = $Zfpf->select_sql_1s($DBMSresource, 't0owner_contractor', $Conditions);
        if ($RROC != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Changes['c5ts_qual_evaluated'] = $Zfpf->encrypt_1c(time());
        $ShtmlFormArray = array('c5ts_qual_evaluated' => array('Contractor qualifications last evaluated by owner representative and recorded in app', ''));
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0owner_contractor', $Changes, $Conditions, TRUE, $ShtmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        // Email the contractor PSM leader, the owner PSM leader, and, if different, the current user.
        $Zfpf->close_connection_1s($DBMSresource);
        $ContractorLeader = $Zfpf->user_job_info_1c($SRC[0]['k0user_of_leader']);
        $ContractorName = $Zfpf->decrypt_1c($SRC[0]['c5name']);
        $OwnerLeader = $Zfpf->user_job_info_1c($_SESSION['StatePicked']['t0owner']['k0user_of_leader']);
        $OwnerName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
        $EmailAddresses = array($ContractorLeader['WorkEmail'], $OwnerLeader['WorkEmail']);
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found):</b><br />
        Contractor PSM Leader: '.$ContractorLeader['NameTitleEmployerWorkEmail'].'<br />
        Owner PSM Leader: '.$OwnerLeader['NameTitleEmployerWorkEmail'];
    	$Message = '<p>';
        if ($_SESSION['t0user']['k0user'] != $_SESSION['StatePicked']['t0owner']['k0user_of_leader']) {
            $CurrentUser = $Zfpf->current_user_info_1c();
            $EmailAddresses[] = $CurrentUser['WorkEmail'];
            $DistributionList .= '<br />
            User who evaluated contractor qualifications: '.$CurrentUser['NameTitleEmployerWorkEmail'];
            $Message .= $CurrentUser['NameTitleEmployerWorkEmail'];
        }
        else
            $Message .= $OwnerLeader['NameTitleEmployerWorkEmail'];
        $DistributionList .= '</p>';
        $Message .= ' evaluated the qualifications of:<br />
        - '.$ContractorName.', the contractor,<br />
        on behalf of:<br/>
        - '.$OwnerName.', the Owner/Operator (aka the owner).</p>
        <p>
        Next, complete the following PSM-CAP contractor practices:<br />
        - Entrance Privileges and Records of each Contractor Individual,<br />
        - Job-Site Audits,<br />
        - Injury and Illness Records on Contractor Individuals (if needed), and<br />
        - next year have the contractor update, then re-evaluate, this qualification record.</p>';
    	$Subject = 'PSM-CAP: qualifications of '.$ContractorName.' evaluated';
        $Body = $Zfpf->email_body_append_1c($Message, 'Owner/Operator '.$OwnerName, '', $DistributionList);
        $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Done.</h2>'.$Message;
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
	    <form action="contractor_qual_io03.php" method="post"><p>
	        <input type="submit" name="contractor_qual_o1" value="Go back" /></p>
	    </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Additional security check for i1, i2, i3 code
    if (!isset($_SESSION['t0user_contractor']) or $UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF or ($who_is_editing != '[A new database row is being created.]' and ($_SESSION['t0user_contractor']['k0contractor'] != $_SESSION['Selected']['k0contractor'] or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)))
        $Zfpf->send_to_contents_1c(); // Don't eject
    
    // funciton for: Modify $Display for app_assigned fields.
    function modify_display_contractor_qual($htmlFormArray, $Display, $Nothing) {
        $GetDate = getdate();
        foreach ($htmlFormArray as $KA => $VA)
            if ($KA == 'c6emr' or $KA == 'c6injury_illness') {
                if (isset($Display[$KA][0]) and $Display[$KA][0] != $Nothing) {
                // Check for and fill any gap between most recent data and last full year.
                // $Display[$KA][0] should always be set, the isset($Display[$KA][0]) is to protect against error loops.
                // Above should mean the most-recent year, with recorded data, is in $Display[$KA][0] 
                // Add all years between most-recent recorded year and the current year-1 (the last complete calendar year) if after February 1st.
                // In January, use current year-2.
                    $YearGap = $GetDate['year'] - $Display[$KA][0]; // The current year minus the most-recent recorded year.
                    if ($GetDate['mon'] < 2)
                        --$YearGap ; // Don't require information from the prior calendar year until February 1st or later.
                    while ($YearGap > 1 and $Display[$KA][0] > $YearGap) { // $Display[$KA][0] > $YearGap is only to protect against error loops.
                        $ReportingYear = $Display[$KA][0] + --$YearGap;
                        // Add fields to fill YearGap. Similar method to Add fields button, but also populate the app assigned year field.
                        if ($KA == 'c6emr') {
                            $Temp[$KA][] = $ReportingYear; // 3 fields in each year subset.
                            $Temp[$KA][] = $Nothing;
                            $Temp[$KA][] = $Nothing;
                        }
                        if ($KA == 'c6injury_illness') {
                            $Temp[$KA][] = $ReportingYear; // 4 fields in each year subset.
                            $Temp[$KA][] = $Nothing;
                            $Temp[$KA][] = $Nothing;
                            $Temp[$KA][] = $Nothing;
                        }
                    }
                    if (isset($Temp[$KA]))
                        $Display[$KA] = array_merge ($Temp[$KA], $Display[$KA]);
                }
                $SubsetCounter = 0;
                $YearsBack = 1;
                $ReportingYear = FALSE;
                foreach ($Display[$KA] as $KB => $VB) { // Populate the appassigned year fields
                    $Label = $VA[2][$SubsetCounter][0]; // The field legend within a field set, a required $htmlFormArray parameter
                    if ($Label == 'Year') {
                        if ($VB == $Nothing) {
                            if (!$ReportingYear) { // i0n case, populates years from most recent to oldest.
                                if ($GetDate['mon'] < 2)
                                    $ReportingYear = $GetDate['year'] - $YearsBack - 1;
                                else
                                    $ReportingYear = $GetDate['year'] - $YearsBack;
                                $Display[$KA][$KB] = $ReportingYear;
                                ++$YearsBack;
                            }
                            else
                                $Display[$KA][$KB] = --$ReportingYear; // This populates any additional year subsets, after the first three, created by an "Add Fields" button.
                        }
                        else
                            $ReportingYear = $VB; // $ReportingYear defined here is used in the else statement immediately above, when user pressed an "Add Fields" button.
                    }
                    if (++$SubsetCounter == count($VA[2])) // count($VA[2]) gives the HTML fields in the subset within a fieldset.
                        $SubsetCounter = 0;
                }
            }
        return $Display;
    }
        
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
	if (isset($_POST['contractor_qual_i0n']) or isset($_POST['contractor_qual_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        if (isset($_POST['contractor_qual_o1_from'])) {
            $RecordName = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0contractor']['c5name']).' '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5focus']).' contractor-qualification';
            $Zfpf->edit_lock_1c('contractor_qual', $RecordName); // This re-does SELECT query, checks edit lock, and if none, starts edit lock.
        }
        // ADD FIELDS SPECIAL CASE: $_SESSION['Scratch']['SelectDisplay'] doesn't have app_assigned values.
        $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($Display);
        // Modify $Display for app_assigned fields
        $Display = modify_display_contractor_qual($htmlFormArray, $Display, $Nothing);
        // To standardize, always save possibly modified htmlFormArray, SelectDisplay, and (to simplify code below) placeholder for user's post.
        $_SESSION['Scratch']['htmlFormArray'] = $Zfpf->encode_encrypt_1c($htmlFormArray);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($Display);
    }
    // 1.2 $_SESSION['Scratch']['SelectDisplay'] is source of $Display, the version generated from the database record.
    elseif (isset($_POST['undo_confirm_post_1e']) and isset($_SESSION['Scratch']['SelectDisplay'])) {
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
    }
    // 1.3 $_SESSION['Post'] is source of $Display, the latest user-modified version.  Also use for upload_files.
    // START Special Case for "Add Fields" (enclosing upload_files special case)
    elseif (isset($_POST['add_c6prior_work']) or isset($_POST['add_c6emr']) or isset($_POST['add_c6injury_illness'])) {
        $Display = $Zfpf->post_to_display_1e($htmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Post']));
        if (isset($_POST['add_c6prior_work'])) {
            $Display['c6prior_work'][] = $Nothing; // 1 field in each subset.
            $Location = '#c6prior_work';
        }
        elseif (isset($_POST['add_c6emr'])) {
            $Display['c6emr'][] = $Nothing; // 3 fields in each year subset.
            $Display['c6emr'][] = $Nothing;
            $Display['c6emr'][] = $Nothing;
            $Location = '#c6emr';
        }
        else {
            $Display['c6injury_illness'][] = $Nothing; // 4 fields in each year subset.
            $Display['c6injury_illness'][] = $Nothing;
            $Display['c6injury_illness'][] = $Nothing;
            $Display['c6injury_illness'][] = $Nothing;
            $Location = '#c6injury_illness';
        }
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c(modify_display_contractor_qual($htmlFormArray, $Display, $Nothing)); // Saves $Display.
        header('Location: '.$Location);
        $Zfpf->save_and_exit_1c();
    }
    // START upload_files special case 1 of 3.
    elseif (isset($_SESSION['Post']) and !isset($_POST['contractor_qual_i2'])) { // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        else {
            $htmlFormArray  = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            foreach ($htmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Selected'][$K]);
                    // FilesZfpf::user_files_directory_1e has been checked, it supports t0contractor_qual
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, $K, 'contractor_qual_io03.php');
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
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // This re-defines $Display after header() redirect above.
    // END upload_files special case 1 of 3.
    // END Special Case for "Add Fields"
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        // Create HTML form
        echo $Zfpf->xhtml_contents_header_1c('Contractor Qual');
		echo '<h1>
		Contractor Qualification</h1>
        <form action="contractor_qual_io03.php" method="post" enctype="multipart/form-data" >'; // upload_files special case 2 of 3. To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
		echo $Zfpf->organziation_info_html_1c($_SESSION['StatePicked']['t0contractor']);
	    echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
		    <input type="submit" name="contractor_qual_i2" value="Review what you typed into form" /><br />
            If you only wanted to upload files, you are done. Click on "Go Back"</p>
        </form>'; // upload_files special case 3 of 3.
		if ($who_is_editing == '[A new database row is being created.]')
		    echo '
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>';
		else
    		echo '
		    <form action="contractor_qual_io03.php" method="post"><p>
		        <input type="submit" name="contractor_qual_o1" value="Go back" /></p>
		    </form>';
		echo $Zfpf->xhtml_footer_1c();
		$Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['contractor_qual_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('contractor_qual_io03.php', 'contractor_qual_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') 
            $Zfpf->insert_sql_1s($DBMSresource, 't0contractor_qual', $ChangedRow);
        else {
            $Conditions[0] = array('k0contractor_qual', '=', $_SESSION['Selected']['k0contractor_qual']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0contractor_qual', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c('Contractor Qualification').'
        <p>
        The contractor-qualifiaction information you input and reviewed has been recorded.</p>
        <form action="contractor_qual_io03.php" method="post"><p>
            <input type="submit" name="contractor_qual_o1" value="Back to record" /></p>
        </form>
        <form action="practice_o1.php" method="post"><p>
            <input type="submit" value="Back to records list" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // Ends i1, i2, i3 code...

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

