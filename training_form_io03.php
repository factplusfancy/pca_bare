<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the training_form input and output HTML forms, except the:
//  - i1m file for listing existing records (and giving the option to start a new record) and

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'training_form_i1m.php' or !isset($_SESSION['StatePicked']['t0process']) or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get current-user information...
$User = $Zfpf->current_user_info_1c();
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$EditAuth = FALSE;
if ($User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
    $EditAuth = TRUE;
$Nothing = '[Nothing has been recorded in this field.]';
$EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
    
// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'k0user_of_trainee' => array(
        'Trainee, who received training and demonstrated understanding',
        ''
    ),
    'k0user_of_instructor' => array(
        'Instructor, responsible for verifying that Trainee understood this training',
        ''
    ),
    'c5status' => array(
        'Training-Form Status', 
        '', 
        C5_MAX_BYTES_ZFPF, 
        'app_assigned'
    ),
    'c5ts_completed' => array('
        <a id="c5ts_completed"></a><b>Instructor:</b><br />
        Date and time training completed',
        REQUIRED_FIELD_ZFPF
    ),
    'c5hours' => array(
        'Approximate hours of training. For on-the-job training, this may be difficult to estimate. Optional',
        ''
    ),
    'c5hazards_overview' => array(
        'Did this training cover an overview of the '.HAZSUB_PROCESS_NAME_ZFPF.' and its hazards', 
        REQUIRED_FIELD_ZFPF, 
        C5_MAX_BYTES_ZFPF, 
        'radio',
        array('Yes', 'No')
    ),
    'c6procedures_swp' => array(
        'List the hazardous-substance procedures and safe-work practices, or other topics, covered by this training record', 
        REQUIRED_FIELD_ZFPF, 
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c5injuries_illness' => array(
        '<a id="c5injuries_illness"></a><b>Trainee:</b><br />
        In the last three years, have you had any injuries or illnesses related to your work on or near the '.HAZSUB_PROCESS_NAME_ZFPF,
        REQUIRED_FIELD_ZFPF,
        C5_MAX_BYTES_ZFPF,
        'radio',
        array('Yes', 'No')
    ),
    'c5suggestions' => array(
        'Do you have suggestions to improve the hazardous-substance procedures and safe-work practices or the training method',
        REQUIRED_FIELD_ZFPF, 
        C5_MAX_BYTES_ZFPF, 
        'radio',
        array('Yes', 'No')
    ),
    'c5difficulty' => array(
        'Do you have to work with any difficult to operate controls or equipment',
        REQUIRED_FIELD_ZFPF, 
        C5_MAX_BYTES_ZFPF, 
        'radio',
        array('Yes', 'No')
    ),
    'c5unsafe_locations' => array(
        'Do you have to work in locations that you think may be unsafe, for example, where it is easy to fall or slip, where you cannot see well, or in spaces that are hard to get out of',
        REQUIRED_FIELD_ZFPF, 
        C5_MAX_BYTES_ZFPF, 
        'radio',
        array('Yes', 'No')
    ),
    'c6more_details' => array(
        '<b>If you answered "yes" to any question above</b>, you may give details below and, if you like, to the instructor or anyone you trust to get this information to responsible facility managers',
        '',
        C6SHORT_MAX_BYTES_ZFPF
    ),
    'c5refresher_training' => array(
        '<a id="c5refresher_training"></a>Do you want refresher training on hazardous-substance procedures and safe-work practices more often than every 3 years? (If yes, what frequency do you recommend)',
        REQUIRED_FIELD_ZFPF, 
        C5_MAX_BYTES_ZFPF, 
        'dropdown',
        array('No', 'Every 2 years', 'Every year', 'Every 6 months')
    ), 
    'c6training_type' => array(
        '<a id="c6training_type"></a><b>Instructor.</b> Indicate the training method', 
        REQUIRED_FIELD_ZFPF,
        array(
            array('Classroom', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('On-the-job', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Other', '', C5_MAX_BYTES_ZFPF)
        )
    ),
	'c6test_method' => array(
	    '<a id="c6test_method"></a><b>Instructor.</b> Indicate the means you used to verify that the Trainee understood the training', 
	    REQUIRED_FIELD_ZFPF,
        array(
            array('Observation', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Demonstration', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Spoken Test', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Written Test', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Other', '', C5_MAX_BYTES_ZFPF)
        )
    ),
	'c6bfn_supporting' => array(
	    '<a id="c6bfn_supporting"></a><b>Supporting Documents.</b> If written tests were given, upload the graded tests. Optional supporting documents include agendas (especially for longer training), copies of the procedures or practices trained on, other training materials, and so forth',
	    '', 
	    MAX_FILE_SIZE_ZFPF, 
	    'upload_files'
    )
);

// Left-hand contents has to be customized for context, so handled in o1 and i1 code below.

if (isset($_POST['training_form_i0n'])) {
	// Additional security check. Handle inadequate global privileges. Otherwise, any user associated with this practice can start a new record.
    if ($User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    // Initialize $_SESSION['Selected']
    $_SESSION['Selected'] = array(
        'k0training_form' => time().mt_rand(1000000, 9999999),
		'k0process' => $_SESSION['StatePicked']['t0process']['k0process'],
		'k0user_of_trainee' => 0,
		'k0user_of_instructor' => $_SESSION['t0user']['k0user'],
        'c5status' => $Zfpf->encrypt_1c('draft'),
		'c5ts_completed' => $EncryptedNothing,
		'c5hours' => $EncryptedNothing,
		'c5hazards_overview' => $EncryptedNothing,
		'c6procedures_swp' => $EncryptedNothing,
		'c5injuries_illness' => $EncryptedNothing,
		'c5suggestions' => $EncryptedNothing,
		'c5difficulty' => $EncryptedNothing,
		'c5unsafe_locations' => $EncryptedNothing,
		'c6more_details' => $EncryptedNothing,
		'c5refresher_training' => $EncryptedNothing,
		'c5ts_trainee' => $EncryptedNothing,
		'c6nymd_trainee' => $EncryptedNothing,
		'c6training_type' => $Zfpf->encode_encrypt_1c(array($Nothing, $Nothing, $Nothing)),
		'c6test_method' => $Zfpf->encode_encrypt_1c(array($Nothing, $Nothing, $Nothing, $Nothing, $Nothing)),
		'c6bfn_supporting' => $EncryptedNothing,
		'c5ts_instructor' => $EncryptedNothing,
		'c6nymd_instructor' => $EncryptedNothing,
        'c5ts_reminder_email' => $EncryptedNothing,
		'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['training_form_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0training_form']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0training_form', $_SESSION['Selected']['k0training_form']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one training record', 'training_form_io03.php', 'training_form_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0training_form']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['download_selected'])) 
        $Zfpf->download_selected_files_1e($htmlFormArray, 'training_form_io03.php', 'training_form_o1');
    if (isset($_POST['download_all'])) 
        $Zfpf->download_all_files_1e($htmlFormArray, 'training_form_io03.php', 'training_form_o1');
    $_POST['training_form_o1'] = 1;
}

// o1 code
if (isset($_POST['training_form_o1'])) {
    // Additional security check.
    if (!isset($_SESSION['Selected']['k0training_form']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0training_form'])))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (!isset($_SESSION['Selected']['k0training_form'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0training_form'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0training_form'][$CheckedPost];
        unset($_SESSION['SelectResults']);
    }
    $Zfpf->clear_edit_lock_1c();
    $Status = $Zfpf->decrypt_1c($_SESSION['Selected']['c5status']);
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Selected'], TRUE);
    // Handle k0 field(s)
    $InstructorInfo = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_instructor']);
    $Display['k0user_of_instructor'] = $InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'];
    if ($_SESSION['Selected']['k0user_of_trainee']) {
        $TraineeInfo = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_trainee']);
        $Display['k0user_of_trainee'] = $TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'];
        if ($Display['c5injuries_illness'] != $Nothing and $Display['c5suggestions'] != $Nothing and $Display['c5difficulty'] != $Nothing and $Display['c5unsafe_locations'] != $Nothing and $Display['c5refresher_training'] != $Nothing)
            $_SESSION['Scratch']['ReadyForTraineeApproval'] = $Zfpf->encrypt_1c(TRUE);
        elseif (isset($_SESSION['Scratch']['ReadyForTraineeApproval']))
            unset($_SESSION['Scratch']['ReadyForTraineeApproval']);
    }
    else { // Instructor's add trainee link -- cannot put form for button here, because interferes with downloads files.
        if ($who_is_editing == '[Nobody is editing.]' and $EditAuth and $Status == 'draft' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'])
	        $Display['k0user_of_trainee'] = '
	        <a href="training_form_io03.php?add_trainee_1">Add Trainee</a>'; 
	    else
            $Display['k0user_of_trainee'] = '[Nothing has been recorded in this field.]';
    }
    // Left-hand contents has to be customized for context, so handled in o1 and i1 code.
    if ($Status == 'draft' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'])
        $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
            'c5ts_completed' => 'Instructor questions', 
            'c6training_type' => 'Training method', 
            'c6test_method' => 'Test method', 
            'c6bfn_supporting' => 'Supporting documents'
        );
    elseif ($Status == 'draft' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_trainee'])
        $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
            'c5injuries_illness' => 'Trainee questions', 
            'c5refresher_training' => 'Refresher frequency'
        );
    else
        $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
            'c5ts_completed' => 'Instructor questions', 
            'c5injuries_illness' => 'Trainee questions', 
            'c5refresher_training' => 'Refresher frequency', 
            'c6training_type' => 'Training method', 
            'c6test_method' => 'Test method', 
            'c6bfn_supporting' => 'Supporting documents'
        );
    echo $Zfpf->xhtml_contents_header_1c('Training').'
    <h2>
    Record of training on hazardous-substance procedures and safe-work practices</h2>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'training_form_io03.php', $_SESSION['Selected'], $Display);
    // Check if anyone else is editing the selected row and check user privileges. See messages to the user below regarding privileges.
    if ($who_is_editing != '[Nobody is editing.]')
        echo '
        <p><b>'.$who_is_editing.' is editing the training record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    elseif ($Status == 'draft' and ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'] or $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_trainee']) and $EditAuth)
        echo '
        <form action="training_form_io03.php" method="post"><p>
            <input type="submit" name="training_form_o1_from" value="Update this record" /></p>
        </form>';
    else {
        if ($Status == 'trainee approved')
            echo '<p>
            <b>Training record approved by the trainee:</b> '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_trainee']).'</p>';
        elseif ($Status == 'instructor approved')
            echo '<p>
            <b>Training record approved by both:</b><br/>
            - the trainee: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_trainee']).' <b>and</b><br />
            - the instructor: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_instructor']).'</p>';
        else {
            echo '
            <p>You don\'t have editing privileges on this training record. The Instructor or the Trainee only can edit draft records, if they have adequate privileges.</p>';
            if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
                echo '<p><b>
                Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
            if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
                echo '<p><b>
                Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
        }
    }
    if ($who_is_editing == '[Nobody is editing.]' and $EditAuth) {
        // Instructor's add trainee button
	    if ($Status == 'draft' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'] and !$_SESSION['Selected']['k0user_of_trainee'])
		    echo '
	    	<form action="training_form_io03.php" method="post"><p>
	    	    <input type="submit" name="add_trainee_1" value="Add trainee"/></p>
		    </form>';
        elseif (!$_SESSION['Selected']['k0user_of_trainee'])
            echo '<p>
            The Trainee can only be added by the Instructor, one trainee per record, and only on draft training records.</p>';
        // Instructor's delete record button
	    if ($Status == 'draft' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'])
		    echo '
	    	<form action="training_form_io03.php" method="post"><p>
	    	    <input type="submit" name="delete_record" value="Delete draft record"/></p>
		    </form>';
        else
            echo '<p>
            Only draft training records can be deleted and only by the Instructor associated with the record.</p>';
        // Trainee approve button
        if ($Status == 'draft' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_trainee']) {
            if (isset($_SESSION['Scratch']['ReadyForTraineeApproval']))
		        echo '
	        	<form action="training_form_io03.php" method="post"><p>
	        	    <input type="submit" name="trainee_approval" value="Approve record"/></p>
		        </form>';
	        else
	            echo '<p>
                Click "Update this record" and answer the required questions, then you can approve this record.</p>';
	    }
        // Trainee cancel-approval button
        if ($Status == 'trainee approved' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_trainee'])
		    echo '
        	<form action="training_form_io03.php" method="post"><p>
        	    <input type="submit" name="trainee_approval_c" value="Cancel approval"/></p>
		    </form>';
        // Instructor approve button (on behalf of owner)
        if ($Status == 'trainee approved' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'])
		    echo '
	    	<form action="training_form_io03.php" method="post"><p>
	    	    <input type="submit" name="instructor_approval" value="Approve record"/></p>
		    </form>';
	    elseif ($Status == 'draft' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'])
	        echo '<p>
            The Trainee must approve this record before you (the Instructor) can approve it.</p>';
        // Instructor cancel-approval button (on behalf of owner)
        if ($Status == 'instructor approved' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'])
		    echo '
	    	<form action="training_form_io03.php" method="post"><p>
	    	    <input type="submit" name="instructor_approval_c" value="Cancel approval"/></p>
		    </form>';
    }
    echo '
    <form action="training_form_io03.php" method="post"><p>
        <input type="submit" name="training_form_history_o1" value="History of this record" /></p>
    </form>
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, i3, and approval code
if (isset($_SESSION['Selected']['k0training_form'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0training_form', '=', $_SESSION['Selected']['k0training_form']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0training_form', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    // Additional security check.
    if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'] and $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_trainee']) or ($who_is_editing != '[A new database row is being created.]' and !$EditAuth) or ($who_is_editing == '[A new database row is being created.]' and $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF))
        $Zfpf->send_to_contents_1c(); // Don't eject
    if (isset($_POST['training_form_o1_from']) or isset($_POST['delete_record']) or isset($_POST['trainee_approval']) or isset($_POST['trainee_approval_c']) or isset($_POST['instructor_approval']) or isset($_POST['instructor_approval_c']))
        $Zfpf->edit_lock_1c('training_form'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. In i0n case would trigger error. SPECIAL CASE: add_trainee edit_lock_1c below.
    $Status = $Zfpf->decrypt_1c($_SESSION['Selected']['c5status']);
    // Get useful information
    $Process = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['Selected']['k0process']);
    $InstructorInfo = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_instructor']);
    if ($_SESSION['Selected']['k0user_of_trainee'])
        $TraineeInfo = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_trainee']);
    $CurrentDate = date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j', time());

    // Add trainee code
    if (isset($_POST['add_trainee_1']) or isset($_GET['add_trainee_1'])) {
        if (!$EditAuth or $Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'] or $_SESSION['Selected']['k0user_of_trainee'])
            $Zfpf->send_to_contents_1c(); // Don't eject
        $Zfpf->clear_edit_lock_1c(); // In case arrived here by canceling from add_trainee_2
        echo $Zfpf->xhtml_contents_header_1c('Lookup User');
        $Zfpf->lookup_user_1c('training_form_io03.php', 'training_form_io03.php', 'add_trainee_2', 'training_form_o1');
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['add_trainee_2'])) {
        if (!$EditAuth or $Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'] or $_SESSION['Selected']['k0user_of_trainee'])
            $Zfpf->send_to_contents_1c(); // Don't eject
        $Zfpf->edit_lock_1c('training_form');
        $Conditions1[0] = array('k0process', '=', $_SESSION['Selected']['k0process']);
        $SpecialText = '<h2>
        Pick Trainee</h2><p>
        Careful -- you will not be able to change the trainee later, but you may delete the record before it is approved and create a new one.</p>';
        $Zfpf->lookup_user_wrap_2c(
            't0user_process', // $TableNameUserEntity
            $Conditions1,
            'training_form_io03.php', // $SubmitFile
            'training_form_io03.php', // $TryAgainFile
            array('k0user'), // $Columns1
            'training_form_io03.php', // $CancelFile
            $SpecialText,
            'Pick Trainee', // $SpecialSubmitButton
            'add_trainee_3', // $SubmitButtonName
            'add_trainee_1', // $TryAgainButtonName
            'training_form_o1', // $CancelButtonName
            'c5ts_logon_revoked', // $FilterColumnName
            '[Nothing has been recorded in this field.]' // $Filter
            ); // This function echos and exits.
    }
    if (isset($_POST['add_trainee_3'])) {
        if (!$EditAuth or $Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'] or $_SESSION['Selected']['k0user_of_trainee'])
            $Zfpf->send_to_contents_1c(); // Don't eject
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
            <form action="training_form_io03.php" method="post"><p>
                <input type="submit" name="add_trainee_1" value="Try again" /></p><p>
                <input type="submit" name="training_form_o1" value="Cancel" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            unset($_SESSION['Scratch']['PlainText']['lookup_user']);
            $Zfpf->save_and_exit_1c();
        }
        if ($k0user == $_SESSION['t0user']['k0user']) {
            echo $Zfpf->xhtml_contents_header_1c().'<p>
            It appears you selected yourself as the Trainee. The Instructor cannot also be the Trainee.</p>
            <form action="training_form_io03.php" method="post"><p>
                <input type="submit" name="add_trainee_1" value="Try again" /></p><p>
                <input type="submit" name="training_form_o1" value="Cancel" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            unset($_SESSION['Scratch']['PlainText']['lookup_user']);
            $Zfpf->save_and_exit_1c();
        }
        unset($_SESSION['Scratch']['PlainText']['lookup_user']);
        // Update database with $k0user
        $Conditions[0] = array('k0training_form', '=', $_SESSION['Selected']['k0training_form']);
        $Changes['k0user_of_trainee'] = $k0user;
        $Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
        // Update $_SESSION['Selected']
        $_SESSION['Selected']['k0user_of_trainee'] = $Changes['k0user_of_trainee'];
        $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0training_form', $Changes, $Conditions, TRUE, $htmlFormArray); 
        // $Affected should not be zero because we confirmed that a new user was selected above.
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        $Zfpf->close_connection_1s($DBMSresource);
        // Email the Trainee ($TraineeInfo) and the Instructor 
        $TraineeInfo = $Zfpf->user_job_info_1c($k0user); // Newly set, so get info here. Other info variables set above.
	    $EmailAddresses[] = $InstructorInfo['WorkEmail'];
        $EmailAddresses[] = $TraineeInfo['WorkEmail'];
	    $Subject = 'PSM-CAP: Training Record: Instructor Added Trainee: '.$TraineeInfo['NameTitle'];
	    $Body = '<p>
	    The Instructor added Trainee '.$TraineeInfo['NameTitle'].' to the draft training record for the training completed on '.$Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_completed'])).'</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        Instructor: '.$InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'].' '.$InstructorInfo['WorkEmail'].'<br />
        Trainee: '.$TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'].' '.$TraineeInfo['WorkEmail'].'</p>';
	    $Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], '', $DistributionList);
	    $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Done').'<h2>
        The user you selected is now the Trainee.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="training_form_io03.php" method="post"><p>
        <input type="submit" name="training_form_o1" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

	// Delete record 1
	if (isset($_POST['delete_record'])) {
        // Additional security check.
	    if (!$EditAuth or $Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'])
            $Zfpf->send_to_contents_1c(); // Don't eject
		$ApprovalText = '<h1>
        Delete Training Record</h1>';
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_instructor'] = $InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'];
        if ($_SESSION['Selected']['k0user_of_trainee'])
            $Display['k0user_of_trainee'] = $TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'];
        else
            $Display['k0user_of_trainee'] = '[Nothing has been recorded in this field.]';
        $ApprovalText .= $Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'<p>
		<b>By clicking "Delete record" below, I am confirming that:<br />
		 - I am authorized to delete this record,<br />
		 - I am doing so now for legitimate reasons, and<br />
		 - I understand the record may remain in history tables indefinitely.</b></p><p>
		<b>Approved By:</b><br />
		Name: <b>'.$User['Name'].'</b><br />
		Job Title: <b>'.$User['Title'].'</b><br />
		Employer<b>: '.$User['Employer'].'</b><br />
		Email Address<b>: '.$User['WorkEmail'].'</b><br />
		Date: <b>'.$CurrentDate.'</b></p>';
		echo $Zfpf->xhtml_contents_header_1c('Confirm').$ApprovalText.'
		<form action="training_form_io03.php" method="post"><p>
		    <input type="submit" name="delete_record_2" value="Delete record" /></p><p>
		    <input type="submit" name="training_form_o1" value="Take no action -- go back" /></p>
		</form>
		'.$Zfpf->xhtml_footer_1c();
		$_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
		$Zfpf->save_and_exit_1c();
	}
	
	// Delete record 2
	if (isset($_POST['delete_record_2'])) {
        // Additional security check.
	    if (!$EditAuth or $Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'])
            $Zfpf->send_to_contents_1c(); // Don't eject
		$Conditions[0] = array('k0training_form', '=', $_SESSION['Selected']['k0training_form']);
		$DBMSresource = $Zfpf->credentials_connect_instance_1s();
		$Affected = $Zfpf->delete_sql_1s($DBMSresource, 't0training_form', $Conditions, TRUE, $htmlFormArray); // Same effect as clear_edit_lock_1c()
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
		$Zfpf->close_connection_1s($DBMSresource);
		// Try to email the Instructor
		$EmailAddresses[] = $InstructorInfo['WorkEmail'];
		$Subject = 'PSM-CAP: Instructor Deleted a Draft Training Record';
		$Body = '<p>The Instructor deleted the draft training record shown below.</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        Instructor: '.$InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'].' '.$InstructorInfo['WorkEmail'].'<br />';
		if ($_SESSION['Selected']['k0user_of_trainee']) {
		    // Only email the AELeader if a Trainee had been associated with the draft record.
		    $EmailAddresses[] = $TraineeInfo['WorkEmail'];
		    $EmailAddresses[] = $Process['AELeaderWorkEmail'];
		    $Subject .= ' on '.$TraineeInfo['NameTitle'];
            $DistributionList .= '
            Trainee: '.$TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'].' '.$TraineeInfo['WorkEmail'].'<br />
            The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' '.$Process['AELeaderWorkEmail'].'</p>';
	    }
		$Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $DistributionList);
		$EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
		echo $Zfpf->xhtml_contents_header_1c('Deleted').'<h2>
		You just deleted the training record you had selected.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="practice_o1.php" method="post"><p>
            <input type="submit" value="Go back" /></p>
        </form>
		'.$Zfpf->xhtml_footer_1c();
		unset($_SESSION['Selected']);
		$Zfpf->save_and_exit_1c();
	}
    
	// Trainee approving record 1
	if (isset($_POST['trainee_approval'])) {
        // Additional security check.
	    if (!$EditAuth or $Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_trainee'] or !isset($_SESSION['Scratch']['ReadyForTraineeApproval']))
            $Zfpf->send_to_contents_1c(); // Don't eject
		$ApprovalText = '<h1>
        Training Record: Trainee Approval</h1>';
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_instructor'] = $InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'];
        $Display['k0user_of_trainee'] = $TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'];
        $ApprovalText .= $Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'<p>
		<b>By clicking "Approve training record" below, I am confirming that:<br />
		 - I completed the training described above</b></p><p>
		<b>Approved By:</b><br />
		Name: <b>'.$User['Name'].'</b><br />
		Job Title: <b>'.$User['Title'].'</b><br />
		Employer<b>: '.$User['Employer'].'</b><br />
		Email Address<b>: '.$User['WorkEmail'].'</b><br />
		Date: <b>'.$CurrentDate.'</b></p>';
		echo $Zfpf->xhtml_contents_header_1c('Confirm Approval').$ApprovalText.'
		<form action="training_form_io03.php" method="post"><p>
		    <input type="submit" name="trainee_approval_2" value="Approve training record" /></p><p>
		    <input type="submit" name="training_form_o1" value="Take no action -- go back" /></p>
		</form>
		'.$Zfpf->xhtml_footer_1c();
		$_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
		$_SESSION['Scratch']['c6nymd_trainee'] = $Zfpf->encrypt_1c($User['NameTitle'].', '. $User['Employer'].' '.$User['WorkEmail'].' on '.$CurrentDate);
		$Zfpf->save_and_exit_1c();
	}
	
	// Trainee approving record 2
	if (isset($_POST['trainee_approval_2'])) {
        // Additional security check.
	    if (!$EditAuth or $Status != 'draft' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_trainee'] or !isset($_SESSION['Scratch']['ReadyForTraineeApproval']))
            $Zfpf->send_to_contents_1c(); // Don't eject
		$Conditions[0] = array('k0training_form', '=', $_SESSION['Selected']['k0training_form']);
		$Changes['c5status'] = $Zfpf->encrypt_1c('trainee approved');
		$Changes['c5ts_trainee'] = $Zfpf->encrypt_1c(time());
		$Changes['c6nymd_trainee'] = $_SESSION['Scratch']['c6nymd_trainee'];
		$Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
		$DBMSresource = $Zfpf->credentials_connect_instance_1s();
		$Affected = $Zfpf->update_sql_1s($DBMSresource, 't0training_form', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
		// Update $_SESSION['Selected']
		$_SESSION['Selected']['c5status'] = $Changes['c5status'];
		$_SESSION['Selected']['c5ts_trainee'] = $Changes['c5ts_trainee'];
		$_SESSION['Selected']['c6nymd_trainee'] = $Changes['c6nymd_trainee'];
		$_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
		$Zfpf->close_connection_1s($DBMSresource);
		// Try to email the instructor, the trainee, and the process leader.
		$EmailAddresses = array($InstructorInfo['WorkEmail'], $TraineeInfo['WorkEmail'], $Process['AELeaderWorkEmail']);
		$Subject = 'PSM-CAP: Training Record Approved by Trainee '.$TraineeInfo['NameTitle'];
		$Body = '<p>'.$TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'].' (the Trainee) approved the following training record.</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        Trainee: '.$TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'].' '.$TraineeInfo['WorkEmail'].'<br />
        Instructor: '.$InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'].' '.$InstructorInfo['WorkEmail'].'<br />
        The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' '.$Process['AELeaderWorkEmail'].'</p>';
		$Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $DistributionList);
		$EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
		echo $Zfpf->xhtml_contents_header_1c('Approved').'<h2>
		You just approved your training record.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
		<form action="training_form_io03.php" method="post"><p>
		    <input type="submit" name="training_form_o1" value="Back to viewing record" /></p>
		</form>
		'.$Zfpf->xhtml_footer_1c();
		$Zfpf->save_and_exit_1c();
	}

	// Trainee canceling approval 1
	if (isset($_POST['trainee_approval_c'])) {
	    if (!$EditAuth or $Status != 'trainee approved' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_trainee'])
            $Zfpf->send_to_contents_1c(); // Don't eject
		$ApprovalText = '<h1>
Training Record: Canceling Trainee Approval</h1>';
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_instructor'] = $InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'];
        $Display['k0user_of_trainee'] = $TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'];
        $ApprovalText .= $Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'<p>
		<b>By clicking "Cancel training-record approval" below, as the Trainee, I cancel my approval of this record.</b></p><p>
		<b>Approval Canceled By:</b><br />
		Name: <b>'.$User['Name'].'</b><br />
		Job Title: <b>'.$User['Title'].'</b><br />
		Employer<b>: '.$User['Employer'].'</b><br />
		Email Address<b>: '.$User['WorkEmail'].'</b><br />
		Date: <b>'.$CurrentDate.'</b></p>';
		echo $Zfpf->xhtml_contents_header_1c('Cancel Approval');
		echo $ApprovalText.'
		<form action="training_form_io03.php" method="post"><p>
		    <input type="submit" name="trainee_approval_c2" value="Cancel training-record approval" /></p><p>
		    <input type="submit" name="training_form_o1" value="Take no action -- go back" /></p>
		</form>
		'.$Zfpf->xhtml_footer_1c();
		$_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
		$Zfpf->save_and_exit_1c();
	}

	// Trainee canceling approval 2
	if (isset($_POST['trainee_approval_c2'])) {
	    if (!$EditAuth or $Status != 'trainee approved' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_trainee'])
            $Zfpf->send_to_contents_1c(); // Don't eject
		$Conditions[0] = array('k0training_form', '=', $_SESSION['Selected']['k0training_form']);
		$Changes['c5status'] = $Zfpf->encrypt_1c('draft');
		$Changes['c5ts_trainee'] = $EncryptedNothing;
		$Changes['c6nymd_trainee'] = $EncryptedNothing;
		$Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
		$DBMSresource = $Zfpf->credentials_connect_instance_1s();
		$Affected = $Zfpf->update_sql_1s($DBMSresource, 't0training_form', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
		$_SESSION['Selected']['c5status'] = $Changes['c5status'];
		$_SESSION['Selected']['c5ts_trainee'] = $Changes['c5ts_trainee'];
		$_SESSION['Selected']['c6nymd_trainee'] = $Changes['c6nymd_trainee'];
		$_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
		$Zfpf->close_connection_1s($DBMSresource);
		// Try to email the instructor, the trainee, and the process leader.
		$EmailAddresses = array($InstructorInfo['WorkEmail'], $TraineeInfo['WorkEmail'], $Process['AELeaderWorkEmail']);
		$Subject = 'PSM-CAP: Trainee Canceled Approval of a Training Record';
		$Body = '<p>'.$TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'].' (the Trainee) canceled their approval of the following training record.</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        Trainee: '.$TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'].' '.$TraineeInfo['WorkEmail'].'<br />
        Instructor: '.$InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'].' '.$InstructorInfo['WorkEmail'].'<br />
        The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' '.$Process['AELeaderWorkEmail'].'</p>';
		$Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $DistributionList);
		$EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
		echo $Zfpf->xhtml_contents_header_1c('Canceled').'<h2>
		You just canceled your approval of a training record.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
		<form action="training_form_io03.php" method="post"><p>
		    <input type="submit" name="training_form_o1" value="Back to viewing record" /></p>
		</form>
		'.$Zfpf->xhtml_footer_1c();
		$Zfpf->save_and_exit_1c();
	}

	// Instructor approving record 1
	if (isset($_POST['instructor_approval'])) {
        // Additional security check.
	    if (!$EditAuth or $Status != 'trainee approved' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'])
            $Zfpf->send_to_contents_1c(); // Don't eject
		$ApprovalText = '<h1>
        Training Record: Instructor Approval</h1>';
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_instructor'] = $InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'];
        $Display['k0user_of_trainee'] = $TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'];
        $ApprovalText .= $Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'<p>
		<b>By clicking "Approve training record" below, I am confirming that:<br />
		 - this record accurately describes the training<br />
		 - I verified that the Trainee understood the training as described above.</b></p><p>
		<b>Approved By:</b><br />
		Name: <b>'.$User['Name'].'</b><br />
		Job Title: <b>'.$User['Title'].'</b><br />
		Employer<b>: '.$User['Employer'].'</b><br />
		Email Address<b>: '.$User['WorkEmail'].'</b><br />
		Date: <b>'.$CurrentDate.'</b></p>';
		echo $Zfpf->xhtml_contents_header_1c('Confirm Approval').$ApprovalText.'
		<form action="training_form_io03.php" method="post"><p>
		    <input type="submit" name="instructor_approval_2" value="Approve training record" /></p><p>
		    <input type="submit" name="training_form_o1" value="Take no action -- go back" /></p>
		</form>
		'.$Zfpf->xhtml_footer_1c();
		$_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
		$_SESSION['Scratch']['c6nymd_instructor'] = $Zfpf->encrypt_1c($User['NameTitle'].', '. $User['Employer'].' '.$User['WorkEmail'].' on '.$CurrentDate);
		$Zfpf->save_and_exit_1c();
	}
	
	// Instructor approving record 2
	if (isset($_POST['instructor_approval_2'])) {
        // Additional security check.
	    if (!$EditAuth or $Status != 'trainee approved' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'])
            $Zfpf->send_to_contents_1c(); // Don't eject
		$Conditions[0] = array('k0training_form', '=', $_SESSION['Selected']['k0training_form']);
		$Changes['c5status'] = $Zfpf->encrypt_1c('instructor approved');
		$Changes['c5ts_instructor'] = $Zfpf->encrypt_1c(time());
		$Changes['c6nymd_instructor'] = $_SESSION['Scratch']['c6nymd_instructor'];
		$Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
		$DBMSresource = $Zfpf->credentials_connect_instance_1s();
		$Affected = $Zfpf->update_sql_1s($DBMSresource, 't0training_form', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
		// Update $_SESSION['Selected']
		$_SESSION['Selected']['c5status'] = $Changes['c5status'];
		$_SESSION['Selected']['c5ts_instructor'] = $Changes['c5ts_instructor'];
		$_SESSION['Selected']['c6nymd_instructor'] = $Changes['c6nymd_instructor'];
		$_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
		$Zfpf->close_connection_1s($DBMSresource);
		// Try to email the instructor, the trainee, and the process leader.
		$EmailAddresses = array($InstructorInfo['WorkEmail'], $TraineeInfo['WorkEmail'], $Process['AELeaderWorkEmail']);
		$Subject = 'PSM-CAP: Training Record Approved by Instructor '.$InstructorInfo['NameTitle'];
		$Body = '<p>'.$InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'].' (the Instructor) approved the following training record.</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        Trainee: '.$TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'].' '.$TraineeInfo['WorkEmail'].'<br />
        Instructor: '.$InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'].' '.$InstructorInfo['WorkEmail'].'<br />
        The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' '.$Process['AELeaderWorkEmail'].'</p>';
		$Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $DistributionList);
		$EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
		echo $Zfpf->xhtml_contents_header_1c('Approved').'<h2>
		You just approved a training record.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
		<form action="training_form_io03.php" method="post"><p>
		    <input type="submit" name="training_form_o1" value="Back to viewing record" /></p>
		</form>
		'.$Zfpf->xhtml_footer_1c();
		$Zfpf->save_and_exit_1c();
	}

	// Instructor canceling approval 1
	if (isset($_POST['instructor_approval_c'])) {
	    if (!$EditAuth or $Status != 'instructor approved' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'])
            $Zfpf->send_to_contents_1c(); // Don't eject
		$ApprovalText = '<h1>
Training Record: Canceling Instructor Approval</h1>';
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_instructor'] = $InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'];
        $Display['k0user_of_trainee'] = $TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'];
        $ApprovalText .= $Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'<p>
		<b>By clicking "Cancel training-record approval" below, as the Instructor, I cancel my approval of this record.</b></p><p>
		<b>Approval Canceled By:</b><br />
		Name: <b>'.$User['Name'].'</b><br />
		Job Title: <b>'.$User['Title'].'</b><br />
		Employer<b>: '.$User['Employer'].'</b><br />
		Email Address<b>: '.$User['WorkEmail'].'</b><br />
		Date: <b>'.$CurrentDate.'</b></p>';
		echo $Zfpf->xhtml_contents_header_1c('Cancel Approval');
		echo $ApprovalText.'
		<form action="training_form_io03.php" method="post"><p>
	        <input type="submit" name="instructor_approval_c2" value="Cancel training-record approval" /></p><p>
		    <input type="submit" name="training_form_o1" value="Take no action -- go back" /></p>
		</form>
		'.$Zfpf->xhtml_footer_1c();
		$_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
		$Zfpf->save_and_exit_1c();
	}

	// Instructor canceling approval 2
	if (isset($_POST['instructor_approval_c2'])) {
	    if (!$EditAuth or $Status != 'instructor approved' or $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_instructor'])
            $Zfpf->send_to_contents_1c(); // Don't eject
		$Conditions[0] = array('k0training_form', '=', $_SESSION['Selected']['k0training_form']);
		$Changes['c5status'] = $Zfpf->encrypt_1c('trainee approved');
		$Changes['c5ts_instructor'] = $EncryptedNothing;
		$Changes['c6nymd_instructor'] = $EncryptedNothing;
		$Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c().
		$DBMSresource = $Zfpf->credentials_connect_instance_1s();
		$Affected = $Zfpf->update_sql_1s($DBMSresource, 't0training_form', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
		$_SESSION['Selected']['c5status'] = $Changes['c5status'];
		$_SESSION['Selected']['c5ts_instructor'] = $Changes['c5ts_instructor'];
		$_SESSION['Selected']['c6nymd_instructor'] = $Changes['c6nymd_instructor'];
		$_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
		$Zfpf->close_connection_1s($DBMSresource);
		// Try to email the instructor, the trainee, and the process leader.
		$EmailAddresses = array($InstructorInfo['WorkEmail'], $TraineeInfo['WorkEmail'], $Process['AELeaderWorkEmail']);
		$Subject = 'PSM-CAP: Instructor Canceled Approval of a Training Record';
		$Body = '<p>'.$InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'].' (the Instructor) canceled their approval of the following training record.</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        Trainee: '.$TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'].' '.$TraineeInfo['WorkEmail'].'<br />
        Instructor: '.$InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'].' '.$InstructorInfo['WorkEmail'].'<br />
        The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$Process['AELeaderNameTitle'].', '.$Process['AELeaderEmployer'].' '.$Process['AELeaderWorkEmail'].'</p>';
		$Body = $Zfpf->email_body_append_1c($Body, $Process['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $DistributionList);
		$EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
		echo $Zfpf->xhtml_contents_header_1c('Canceled').'<h2>
		You just canceled your approval of a training record.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
		<form action="training_form_io03.php" method="post"><p>
		    <input type="submit" name="training_form_o1" value="Back to viewing record" /></p>
		</form>
		'.$Zfpf->xhtml_footer_1c();
		$Zfpf->save_and_exit_1c();
	}

    // Additional security check for i1 and i2 code
    if ($Status != 'draft')
        $Zfpf->send_to_contents_1c(); // Don't eject
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
	if (isset($_POST['training_form_i0n']) or isset($_POST['training_form_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles & privileges.
        $Display['k0user_of_instructor'] = $InstructorInfo['NameTitle'].', '.$InstructorInfo['Employer'];
        if ($_SESSION['Selected']['k0user_of_trainee'])
            $Display['k0user_of_trainee'] = $TraineeInfo['NameTitle'].', '.$TraineeInfo['Employer'];
        elseif (isset($_POST['training_form_i0n']))
            $Display['k0user_of_trainee'] = 'Select the trainee later, once you input and confirm the details below.';
        else
            $Display['k0user_of_trainee'] = 'None selected. Click on the "Go back" button below to select a trainee.';
	    if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_trainee']) {
	        unset ($htmlFormArray['c5ts_completed']);
	        unset ($htmlFormArray['c5hours']);
	        unset ($htmlFormArray['c5hazards_overview']);
	        unset ($htmlFormArray['c6procedures_swp']);
	        unset ($htmlFormArray['c6training_type']);
	        unset ($htmlFormArray['c6test_method']);
	        unset ($htmlFormArray['c6bfn_supporting']);
	    }
	    if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor']) {
	        unset ($htmlFormArray['c5injuries_illness']);
	        unset ($htmlFormArray['c5suggestions']);
	        unset ($htmlFormArray['c5difficulty']);
	        unset ($htmlFormArray['c5unsafe_locations']);
	        unset ($htmlFormArray['c6more_details']);
	        unset ($htmlFormArray['c5refresher_training']);
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
    elseif (isset($_SESSION['Post']) and !isset($_POST['training_form_i2'])) { // !isset($_POST... only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        elseif (isset($_POST['upload_c6bfn_supporting'])) { // DOUBLE SPECIAL CASE BECAUSE ONLY ONE c6bfn FIELD.
            $htmlFormArray  = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Selected']['c6bfn_supporting']);
                    // FilesZfpf::user_files_directory_1e has been checked, it supports t0training_form
            $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, 'c6bfn_supporting', 'training_form_io03.php');
            // FilesZfpf::6bfn_files_upload_1e updates $_SESSION['Selected'] and the database. 
            // Or, it echos an error page an user has option to press $_POST['problem_c6bfn_files_upload_1e'] button.
            $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
            $SelectDisplay['c6bfn_supporting'] = $Zfpf->html_uploaded_files_1e('c6bfn_supporting'); // Update the modified select display
            $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($SelectDisplay);
            $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($Zfpf->post_to_display_1e($htmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Post']), $UploadResults['count'], 'c6bfn_supporting'));
            // $_SESSION['Post'] now holds $Display holding $_POST updated with $_SESSION['Selected']['c6bfn_...'] information.
            header("Location: #c6bfn_supporting"); // AN ANCHOR MUST BE SET FOR ALL upload_files FIELDS
            $Zfpf->save_and_exit_1c();
        }
    }
    if (!$_POST and isset($_SESSION['Post']))
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // This re-defines $Display after upload_files header() redirect above.
    // END upload_files special case 1 of 3.
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        // Create HTML form
        // Left-hand contents has to be customized for context, so handled in o1 and i1 code.
        if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'])
            $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
                'c5ts_completed' => 'Instructor questions', 
                'c6training_type' => 'Training method', 
                'c6test_method' => 'Test method', 
                'c6bfn_supporting' => 'Supporting documents'
            );
        elseif ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_trainee'])
            $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
                'c5injuries_illness' => 'Trainee questions', 
                'c5refresher_training' => 'Refresher frequency'
            );
        echo $Zfpf->xhtml_contents_header_1c('Training').'<h1>
		Record of training on hazardous-substance procedures and safe-work practices</h1>
        <form action="training_form_io03.php" method="post" enctype="multipart/form-data" >'; // upload_files special case 2 of 3. To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
	    echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
		    <input type="submit" name="training_form_i2" value="Review what you typed into form" />';
		if ($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_instructor'])
            echo '<br />
            If you only wanted to upload files, you are done. Click on "Go back"'; // upload_files special case 3 of 3.
		echo '</p>
		</form>';
		if ($who_is_editing == '[A new database row is being created.]')
		    echo '
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>';
		else
    		echo '
		    <form action="training_form_io03.php" method="post"><p>
		        <input type="submit" name="training_form_o1" value="Go back" /></p>
		    </form>';
		echo $Zfpf->xhtml_footer_1c();
		$Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['training_form_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        if (isset($_SESSION['Scratch']['ReadyForTraineeApproval']))
            unset($_SESSION['Scratch']['ReadyForTraineeApproval']); // SPECIAL CASE session cleanup.
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('training_form_io03.php', 'training_form_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') 
            $Zfpf->insert_sql_1s($DBMSresource, 't0training_form', $ChangedRow);
        else {
            $Conditions[0] = array('k0training_form', '=', $_SESSION['Selected']['k0training_form']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0training_form', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        $Zfpf->close_connection_1s($DBMSresource);
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c('Training').'
        <p>
        The training-documentation record you just reviewed has been recorded as a draft. It will remain a draft until both the Trainee and the Instructor have approved it.</p>
        <form action="training_form_io03.php" method="post"><p>
            <input type="submit" name="training_form_o1" value="Back to record" /></p>
        </form>
        <form action="practice_o1.php" method="post"><p>
            <input type="submit" value="Back to records list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, i3, and approval code

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

