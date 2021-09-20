<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the change-management system (cm_applies and cms) input and output HTML forms, except:
//  - some i1m files for listing existing records (and giving the option to start a new record)

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// General security check. 
// Cannot screen for process being set because CM can apply contractor-, owner-, facility-, or process-wide.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or ($_SESSION['Scratch']['PlainText']['SecurityToken'] != 'cms_i1m.php' and $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'cm_applies_i1m.php') or !isset($_SESSION['t0user_practice']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get needed variables.
$User = $Zfpf->current_user_info_1c();
$UserPracticePrivileges = $Zfpf->decrypt_1c($_SESSION['t0user_practice']['c5p_practice']);
$Nothing = '[Nothing has been recorded in this field.]';
$EncryptedNothing = $Zfpf->encrypt_1c('[Nothing has been recorded in this field.]');
$EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
$EditAuth = FALSE;
if ($User['GlobalDBMSPrivileges'] == MAX_PRIVILEGES_ZFPF and $UserPracticePrivileges == MAX_PRIVILEGES_ZFPF)
    $EditAuth = TRUE;
if ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'cms_i1m.php')
    $GoBackSubmitName = 'cms_o1';
else
    $GoBackSubmitName = 'cm_applies_o1';

// Create cmsZfpf object, to get correct htmlFormArray, left-hand contents, initialize i0n session selected, etc.
require INCLUDES_DIRECTORY_PATH_ZFPF.'/cmsZfpf.php';
$cmsZfpf = new cmsZfpf;

// i0mall code -- to give user the option to view all change-management records associated with their current state picked.
if (isset($_POST['cms_i0mall'])) {
    // Define SQL WHERE clause for all change-management records that the user is authorized to view.
    // Removing this condition changes below from open to call: $Conditions[] = array('k0user_of_psr', '=', 0, 'AND ('); // plus closing ')' on last owner condition below.
    if (isset($_SESSION['StatePicked']['t0process']['k0process']))
        $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0process']['k0process'], 'OR');
    if (isset($_SESSION['StatePicked']['t0facility']['k0facility']))
        $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0facility']['k0facility'], 'OR');
    if (isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
        if (!isset($_SESSION['StatePicked']['t0owner']['k0owner'])) // Contractor-wide change case.
            $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], ')');
        else
            $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0contractor']['k0contractor'], 'OR');
    if (isset($_SESSION['StatePicked']['t0owner']['k0owner']))
        $Conditions[] = array('k0affected_entity', '=', $_SESSION['StatePicked']['t0owner']['k0owner']);
    // Connect to DBMS
    $DBMSresource = $Zfpf->credentials_connect_instance_1s();
    list($_SESSION['SelectResults']['t0change_management'], $RowsReturned['t0change_management']) = $Zfpf->select_sql_1s($DBMSresource, 't0change_management', $Conditions);
    if ($RowsReturned['t0change_management'] > 0) {
        $Message = '<form action="cms_io03.php" method="post"><h2>Change Name</h2><p>
        <b>All records for your currently selected process, facility, owner and/or contractor</b><br />';
        // Sort $_SESSION['SelectResults']['t0change_management'] descending (SORT_DESC) by k0change_management, which will sort newest to oldest due to time-stamp embedded in k0change_management.
        foreach ($_SESSION['SelectResults']['t0change_management'] as $V) {
            $k0change_management[] = $V['k0change_management'];
        }
        array_multisort($k0change_management, SORT_DESC, $_SESSION['SelectResults']['t0change_management']);
        foreach ($_SESSION['SelectResults']['t0change_management'] as $K => $V) {
            $Message .= '<input type="radio" name="selected" value="' . $K . '" ';
            if ($K == 0) // Select the first change_management by default to ensure something is posted (unless a hacker is tampering).
                $Message .= 'checked="checked" ';
            $Message .= '/><b>' . $Zfpf->decrypt_1c($V['c5name']) . '</b>';
            if (isset($V['c6description']) and $Zfpf->decrypt_1c($V['c6description']) != $Nothing)
                $Message .= ' -- ' . substr($Zfpf->decrypt_1c($V['c6description']), 0, 160); // Truncate this to about 2 lines (160 characters).
            $Message .= '<br />';
        }
        $Message .= '</p><p><input type="submit" name="cms_o1" value="View change-management record" /></p>
        </form>';
    }
    else
        $Message = '<p><b>
        Nothing Found.</b> No change-management records were found for -- as applicable -- the process, facility, owner, or contractor that you are currently associated with. Please contact your supervisor if this seems amiss.</p>';
    // Close DBMS connection.
    $Zfpf->close_connection_1s($DBMSresource);
    //Here to end is the html form sent to browsers
    echo $Zfpf->xhtml_contents_header_1c('Select Change');
    echo '<h1>
    Change-Management System</h1>
    '.$Message.'
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>';
    echo $Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// generate activity notice code, put before i0n initialization of $_SESSION['Selected'], for security check below.
if (isset($_GET['act_notice_1'])) {
    if (!isset($_SESSION['Selected']['k0change_management']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/ActNoticeZfpf.php';
    $ActNoticeZfpf = new ActNoticeZfpf;
    echo $ActNoticeZfpf->act_notice_generate($Zfpf, 'cms');
    $Zfpf->save_and_exit_1c();
}

// i0n code
if (isset($_POST['cms_i0n'])) {
    // Additional security check. Handle inadequate global privileges. Otherwise, any user associated with this practice can start a new record.
    if ($User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // Initialize $_SESSION['Selected']
    $_SESSION['Selected'] = array(
        'k0change_management' => time().mt_rand(1000000, 9999999),
        'c5name' => $EncryptedNothing,
        'c6description' => $EncryptedNothing,
        'c5affected_entity' => $EncryptedNothing,
        'k0affected_entity' => 0,
        'k0user_of_initiator' => $_SESSION['t0user']['k0user'],
        'c6cm_applies_checks' => $Zfpf->encode_encrypt_1c(array($Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing)),
        'k0user_of_applic_approver' => 0,
        'c5ts_applic_approver' => $EncryptedNothing,
        'c6nymd_applic_approver' => $EncryptedNothing,
        'k0user_of_project_manager' => 0,
        'c5duration' => $EncryptedNothing,
        'c5reason' => $EncryptedNothing,
        'c6bfn_markup' => $EncryptedNothing,
        'k0user_of_dr' => 0,
        'c5ts_dr_requested' => $EncryptedNothing,
        'c5ts_dr' => $EncryptedNothing,
        'c6nymd_dr' => $EncryptedNothing,
        'c6notes_dr' => $EncryptedNothing,
        'c6bfn_dr' => $EncryptedNothing,
        'k0user_of_ehsr' => 0,
        'c5ts_ehsr_requested' => $EncryptedNothing,
        'c5ts_ehsr' => $EncryptedNothing,
        'c6nymd_ehsr' => $EncryptedNothing,
        'c6notes_ehsr' => $EncryptedNothing,
        'c6bfn_ehsr' => $EncryptedNothing,
        'k0user_of_hrr' => 0,
        'c5ts_hrr_requested' => $EncryptedNothing,
        'c5ts_hrr' => $EncryptedNothing,
        'c6nymd_hrr' => $EncryptedNothing,
        'c6notes_hrr' => $EncryptedNothing,
        'c6bfn_hrr' => $EncryptedNothing,
        'k0user_of_cont_qual' => 0,
        'c5ts_cont_qual_requested' => $EncryptedNothing,
        'c5ts_cont_qual' => $EncryptedNothing,
        'c6nymd_cont_qual' => $EncryptedNothing,
        'c6notes_cont_qual' => $EncryptedNothing,
        'c6bfn_cont_qual' => $EncryptedNothing,
        'k0user_of_act_notice' => 0,
        'c5ts_act_notice_requested' => $EncryptedNothing,
        'c5ts_act_notice' => $EncryptedNothing,
        'c6nymd_act_notice' => $EncryptedNothing,
        'c6notes_act_notice' => $EncryptedNothing,
        'c6bfn_act_notice' => $EncryptedNothing,
        'k0user_of_psi' => 0,
        'c5ts_psi_requested' => $EncryptedNothing,
        'c5ts_psi' => $EncryptedNothing,
        'c6nymd_psi' => $EncryptedNothing,
        'c6notes_psi' => $EncryptedNothing,
        'c6bfn_psi' => $EncryptedNothing,
        'k0user_of_pha_amend' => 0,
        'c5ts_pha_amend_requested' => $EncryptedNothing,
        'c5ts_pha_amend' => $EncryptedNothing,
        'c6nymd_pha_amend' => $EncryptedNothing,
        'c6notes_pha_amend' => $EncryptedNothing,
        'c6bfn_pha_amend' => $EncryptedNothing,
        'k0user_of_hs_omp_swp' => 0,
        'c5ts_hs_omp_swp_requested' => $EncryptedNothing,
        'c5ts_hs_omp_swp' => $EncryptedNothing,
        'c6nymd_hs_omp_swp' => $EncryptedNothing,
        'c6notes_hs_omp_swp' => $EncryptedNothing,
        'c6bfn_hs_omp_swp' => $EncryptedNothing,
        'k0user_of_training' => 0,
        'c5ts_training_requested' => $EncryptedNothing,
        'c5ts_training' => $EncryptedNothing,
        'c6nymd_training' => $EncryptedNothing,
        'c6notes_training' => $EncryptedNothing,
        'c6bfn_training' => $EncryptedNothing,
        'k0user_of_hs_pm' => 0,
        'c5ts_hs_pm_requested' => $EncryptedNothing,
        'c5ts_hs_pm' => $EncryptedNothing,
        'c6nymd_hs_pm' => $EncryptedNothing,
        'c6notes_hs_pm' => $EncryptedNothing,
        'c6bfn_hs_pm' => $EncryptedNothing,
        'k0user_of_emergency' => 0,
        'c5ts_emergency_requested' => $EncryptedNothing,
        'c5ts_emergency' => $EncryptedNothing,
        'c6nymd_emergency' => $EncryptedNothing,
        'c6notes_emergency' => $EncryptedNothing,
        'c6bfn_emergency' => $EncryptedNothing,
        'k0user_of_iet' => 0,
        'c5ts_iet_requested' => $EncryptedNothing,
        'c5ts_iet' => $EncryptedNothing,
        'c6nymd_iet' => $EncryptedNothing,
        'c6notes_iet' => $EncryptedNothing,
        'c6bfn_iet' => $EncryptedNothing,
        'k0user_of_implement' => 0,
        'c5ts_implement_requested' => $EncryptedNothing,
        'c5ts_implement' => $EncryptedNothing,
        'c6nymd_implement' => $EncryptedNothing,
        'c6notes_implement' => $EncryptedNothing,
        'c6bfn_implement' => $EncryptedNothing,
        'k0user_of_psr' => 0,
        'c5ts_psr_requested' => $EncryptedNothing,
        'c5ts_psr' => $EncryptedNothing,
        'c6nymd_psr' => $EncryptedNothing,
        'c6notes_psr' => $EncryptedNothing,
        'c6bfn_psr' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['cms_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0change_management']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0change_management', $_SESSION['Selected']['k0change_management']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one change-management record', 'cms_io03.php', 'cms_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0change_management']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $htmlFormArray = $cmsZfpf->htmlFormArray(TRUE); // cms special case. Cannot get here from cm_applies, and only if $CMRequired, so force TRUE here.
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'cms_io03.php', 'cms_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'cms_io03.php', 'cms_o1');
    $_POST['cms_o1'] = 1;
}

// o1 code
if (isset($_POST['cms_o1']) or isset($_POST['cm_applies_o1'])) {
    // Additional security check.
    if ((isset($_POST['cms_o1']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'cms_i1m.php') or (isset($_POST['cm_applies_o1']) and $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'cm_applies_i1m.php') or (!isset($_SESSION['Selected']['k0change_management']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0change_management']))))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // $_SESSION cleanup
    if (isset($_SESSION['Scratch']['htmlFormArray']))
        unset($_SESSION['Scratch']['htmlFormArray']);
    if (isset($_SESSION['Post']))
        unset($_SESSION['Post']);
    if (!isset($_SESSION['Selected']['k0change_management'])) {
        $CheckedPost = $Zfpf->post_length_blank_1c('selected');
        if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0change_management'][$CheckedPost]))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $_SESSION['Selected'] = $_SESSION['SelectResults']['t0change_management'][$CheckedPost];
        unset($_SESSION['SelectResults']);
    }
    $Zfpf->clear_edit_lock_1c();
    $AE = $Zfpf->affected_entity_info_1c(); // $_SESSION['Selected']['c5affected_entity'] and $_SESSION['Selected']['k0affected_entity'] are always properly defined here, because required fields when i1n goes to i1 form.
    // Determine if change management is required. If any checkbox is checked, CM is required. The default checkbox value is "Yes" in ConfirmZfpf::html_form_field_1e
    $CMRequired = in_array('Yes', $Zfpf->decrypt_decode_1c($_SESSION['Selected']['c6cm_applies_checks']));
    if ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'cm_applies_i1m.php') {
        $htmlFormArray = $cmsZfpf->htmlFormArray(FALSE); // FALSE here returns short-version of htmlFormArray.
        $HeaderAndIntro = '<h1>Change-Management Applicability Determination</h1>';
    }
    else {
        $htmlFormArray = $cmsZfpf->htmlFormArray($CMRequired);
        if (!$CMRequired)
            $HeaderAndIntro = '<h1>Change-Management Applicability Determination</h1>';
        else
            $HeaderAndIntro = '<h1>Change-Management System</h1><p>
            Before authorizing startup, '.$AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'].' (the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader) may assign the tasks below to qualified individuals and, in any case, shall verify and document completion of any needed tasks under Startup Authorization, the final task below. Many tasks below may not be needed for a particular change.</p>';
    }
    // Cannot add "Generate an activity notice" option, in o1 display, because these go back to i1, ejecting user if not draft...
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, $_SESSION['Selected'], TRUE);
    list($htmlFormArray, $Display) = $cmsZfpf->html_form_modify($htmlFormArray, $Display, $CMRequired);
    // Handle k0 field(s) -- below works for all cases because $htmlFormArray is modified if $CMRequired, above.
    foreach ($htmlFormArray as $K => $V)
        if (substr($K, 0, 9) == 'k0user_of') {
            $TaskSubKey = substr($K, 9);
            if (!$_SESSION['Selected'][$K]) { // Role or task has NOT been assigned.
                if ($K == 'k0user_of_applic_approver') {
                    $Display[$K] = 'Not yet approved.';
                    // Allow AE leader, change project manager, and change initiator to edit applicability checklist.
                    if (($_SESSION['t0user']['k0user'] == $AE['AELeader_k0user'] or $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_project_manager'] or $_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_initiator']) and $EditAuth)
                        $Display[$K] .= '<br /><input type="submit" name="cm_applies_o1_from" value="Update '.$V[3][1].'" />';
                    // Allow AE leader to approve applicability determination.
                    if ($_SESSION['t0user']['k0user'] == $AE['AELeader_k0user'] and $EditAuth)
                        $Display[$K] .= '<br /><input type="submit" name="approve_1'.$TaskSubKey.'" value="Approve '.$V[3][1].'" />'; // same as name="approve_1_applic_approver"
                }
                elseif ($K == 'k0user_of_psr') {
                    $Display[$K] = $AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'];
                    // Allow startup approval (and PSR editing) only after applicability-determination approval.
                    if ($_SESSION['t0user']['k0user'] == $AE['AELeader_k0user'] and $EditAuth) {
                        if ($_SESSION['Selected']['k0user_of_applic_approver'])
                            $Display[$K] .= '<br /><input type="submit" name="cms_o1_from" value="Update your sections" />
                                             <br /><input type="submit" name="approve_1'.$TaskSubKey.'" value="Approve Startup" />'; // Capital S to match confirmation page.
                        else {
                            $Display[$K] .= '<br /><br />Startup authorization and notes editing cannot be done before approving the change-management <a href="#k0user_of_applic_approver">applicability determination</a>.';
                            unset($htmlFormArray['c6notes'.$TaskSubKey]);
                            unset($htmlFormArray['c6bfn'.$TaskSubKey]);
                        }
                    }
                }
                else { // These cases cannot be above two plus k0user_of_initiator, which is always assigned by i0n code.
                    $Display[$K] = 'Not assigned. '.$V[3][0];
                    // Allow AE Leader to assign tasks before startup authorization.
                    if ($_SESSION['t0user']['k0user'] == $AE['AELeader_k0user'] and !$_SESSION['Selected']['k0user_of_psr'] and $EditAuth)
                        $Display[$K] .= '<br /><input type="submit" name="assign_1'.$TaskSubKey.'" value="Assign '.$V[3][1].'" />';
                    // unset notes and base file name (BFM) htmlFormArray fields for unassigned tasks
                    unset($htmlFormArray['c6notes'.$TaskSubKey]);
                    unset($htmlFormArray['c6bfn'.$TaskSubKey]);
                }
            }
            else { // Role or task has been assigned.
                $UJI = $Zfpf->user_job_info_1c($_SESSION['Selected'][$K]); // Use this even for k0user_of_psr in case a former AE Leader authorized startup.
                $Display[$K] = $UJI['NameTitle'].', '.$UJI['Employer'];
                if (isset($_SESSION['Selected']['c5ts'.$TaskSubKey.'_requested'])) {
                    $Requested = $Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($_SESSION['Selected']['c5ts'.$TaskSubKey.'_requested']));
                    if ($Requested != $Nothing)
                        $Display[$K] .= ' -- task-completion target: '.$Requested;
                }
                // Allow a task leader to approve the task as complete before startup authorization.
                if ($K != 'k0user_of_initiator' and $K != 'k0user_of_applic_approver' and $K != 'k0user_of_project_manager' and $_SESSION['t0user']['k0user'] == $_SESSION['Selected'][$K] and $EditAuth) {
                    if (!$_SESSION['Selected']['k0user_of_psr']) {
                        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5ts'.$TaskSubKey]) == $Nothing)
                            $Display[$K] .= '<br /><input type="submit" name="cms_o1_from" value="Update your sections" />
                                             <br /><input type="submit" name="approve_1'.$TaskSubKey.'" value="Approve '.$V[3][1].'" />';
                        else
                            $Display[$K] .= '<br />
                            Task approved as complete by '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd'.$TaskSubKey]).'<br />
                            <input type="submit" name="approve_c1'.$TaskSubKey.'" value="Cancel approval" />';
                    }
                    elseif ($Zfpf->decrypt_1c($_SESSION['Selected']['c5ts'.$TaskSubKey]) != $Nothing)
                        $Display[$K] .= '<br />Task approved as complete by '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd'.$TaskSubKey]);
                }
                // Allow AE Leader to reassign tasks before startup authorization, except initiator and applic_approver, even if task approved (current leader can cancel approval).
                // Former task leader notes and supporting documents remain recorded. 
                // The AE Leader, the current leader, and any former leader are all emailed about a reassignment below, so they may coordinate transfer.
                if ($K != 'k0user_of_initiator' and $K != 'k0user_of_applic_approver' and $_SESSION['t0user']['k0user'] == $AE['AELeader_k0user'] and !$_SESSION['Selected']['k0user_of_psr'] and $EditAuth)
                    $Display[$K] .= '<br /><input type="submit" name="assign_1'.$TaskSubKey.'" value="Reassign '.$V[3][1].'" />';
                // Allow AE Leader to cancel the change-management applicability determination before startup authorization.
                if ($K == 'k0user_of_applic_approver' and $_SESSION['t0user']['k0user'] == $AE['AELeader_k0user'] and !$_SESSION['Selected']['k0user_of_psr'] and $EditAuth)
                    $Display[$K] .= '<br /><input type="submit" name="cm_applies_approval_c1" value="Cancel '.$V[3][1].'" />';
            }
        }
    echo $Zfpf->xhtml_contents_header_1c('Change').$HeaderAndIntro.'
    <form action="cms_io03.php" method="post">
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'cms_io03.php', $_SESSION['Selected'], $Display).'
    </form>';
    // Editing is done by task, via $Display, above. Only one leader per task can edit, so no concern about edit locking via app (aka who_is_editing).
    if ($_SESSION['Selected']['k0user_of_psr'])
        echo '<p>
        This record cannot be altered because <b>startup was authorized by:</b><br />
        '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6nymd_psr']).'<br />
        A new change management is required to reverse or alter a permanent change (or to alter the authorized methods for reversing a temporary change).</p>';
    if (!$EditAuth) {
        echo '
        <p>You don\'t have editing privileges on this record.</p>';
        if ($UserPracticePrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Practice Privileges Notice</b>: You don\'t have update privileges for this practice, if you need to edit any records for this practice, please contact your supervisor or a PSM-CAP App administrator.</p>';
        if ($User['GlobalDBMSPrivileges'] != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    if ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'cms_i1m.php')
        echo '
        <form action="cms_io03.php" method="post"><p>
            <input type="submit" name="cms_history_o1" value="History of this record" /></p>
        </form>';
    echo '
    <form action="practice_o1.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// i1, i2, i3, approval, and task assignment code
if (isset($_SESSION['Selected']['k0change_management'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0change_management', '=', $_SESSION['Selected']['k0change_management']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0change_management', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    // Additional security check
    if ((isset($_POST['cms_i0n']) and $who_is_editing != '[A new database row is being created.]') or ($who_is_editing != '[A new database row is being created.]' and !$EditAuth) or ($who_is_editing == '[A new database row is being created.]' and $User['GlobalDBMSPrivileges'] == LOW_PRIVILEGES_ZFPF))
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    if ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'cm_applies_i1m.php')
        $Heading = '<h1>
        Change-Management Applicability Determination</h1>';
    else
        $Heading = '<h1>
        Change-Management System</h1>';
    if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5affected_entity']) != $Nothing) // Only true for i0n case.
        $AE = $Zfpf->affected_entity_info_1c(); // Ensures $_SESSION['Selected']['c5affected_entity'] and $_SESSION['Selected']['k0affected_entity'] are defined.
    if (isset($_POST['cm_applies_o1_from']) or (isset($_POST['cms_o1_from']) and $_SESSION['t0user']['k0user'] == $AE['AELeader_k0user']) or isset($_POST['cm_applies_approval_c1'])) // SPECIAL CASES, edit lock approvals and task assignments, below.
        $Zfpf->edit_lock_1c('change_management'); // This re-does SELECT query...
    elseif ($who_is_editing != '[Nobody is editing.]' and $who_is_editing != '[A new database row is being created.]' and $who_is_editing != substr($Zfpf->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF)) {
        echo $Zfpf->xhtml_contents_header_1c().$Heading.'
        <p><b>'.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>
        <form action="cms_io03.php" method="post"><p>
            <input type="submit" name="'.$GoBackSubmitName.'" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // Get needed variables
    $CurrentDate = date('\Y\e\a\r Y, \M\o\n\t\h n, \D\a\y j', time());
    $CMRequired = in_array('Yes', $Zfpf->decrypt_decode_1c($_SESSION['Selected']['c6cm_applies_checks']));
    $CI = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_initiator']); // k0user_of_initiator is assign in i0n code and can only be changed to another valid k0user.
    // Get correct htmlFormArray. Force to short-version of htmlFormArray for several cases.
    if (isset($_POST['cm_applies_approval_c1']) or isset($_POST['approve_1_applic_approver']) or isset($_POST['cms_i0n']) or isset($_POST['cm_applies_o1_from']) or !$CMRequired)
        $htmlFormArray = $cmsZfpf->htmlFormArray(FALSE); // FALSE here returns short-version of htmlFormArray.
    else
        $htmlFormArray = $cmsZfpf->htmlFormArray(TRUE); // $CMRequired must be TRUE here.

    // Affected-entity leader canceling approval of change-management applicability determination.
    if (isset($_POST['cm_applies_approval_c1'])) {
        if (!$EditAuth or $_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'])
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $htmlFormArray = $cmsZfpf->htmlFormArray(FALSE); // Even in full CMS, only needed the CMApplies htmlFormArray for cancelling CM applies determination.
        $ApprovalText = '<h1>
        Canceling Approval of Change-Management Applicability Determination</h1>';
        $Display = $Zfpf->select_to_display_1e($htmlFormArray);
        $Display['k0user_of_initiator'] = $CI['NameTitle'].', '.$CI['Employer'];
        $Display['k0user_of_applic_approver'] = $AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'];
        list($htmlFormArray, $Display) = $cmsZfpf->html_form_modify($htmlFormArray, $Display, $CMRequired);
        $ApprovalText .= $Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'<p>
        <b>By clicking "Cancel applicability approval" below, as the current '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader for '.$AE['AEFullDescription'].', I cancel approval of this record.</b></p><p>
        <b>Approval Canceled By:</b><br />
        Name: <b>'.$User['Name'].'</b><br />
        Job Title: <b>'.$User['Title'].'</b><br />
        Employer<b>: '.$User['Employer'].'</b><br />
        Email Address<b>: '.$User['WorkEmail'].'</b><br />
        Date: <b>'.$CurrentDate.'</b></p>';
        echo $Zfpf->xhtml_contents_header_1c('Cancel Approval').$ApprovalText.'
        <form action="cms_io03.php" method="post"><p>
            <input type="submit" name="cm_applies_approval_c2" value="Cancel applicability approval" /></p><p>
            <input type="submit" name="'.$GoBackSubmitName.'" value="Take no action -- go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['cm_applies_approval_c2'])) {
        if (!$EditAuth or $_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'])
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        $Conditions[0] = array('k0change_management', '=', $_SESSION['Selected']['k0change_management']);
        $Changes['k0user_of_applic_approver'] = 0;
        $Changes['c5ts_applic_approver'] = $EncryptedNothing;
        $Changes['c6nymd_applic_approver'] = $EncryptedNothing;
        $Changes['c5who_is_editing'] = $EncryptedNobody; // Same effect as clear_edit_lock_1c().
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0change_management', $Changes, $Conditions, TRUE, $htmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.$Affected);
        $_SESSION['Selected']['k0user_of_applic_approver'] = $Changes['k0user_of_applic_approver'];
        $_SESSION['Selected']['c5ts_applic_approver'] = $Changes['c5ts_applic_approver'];
        $_SESSION['Selected']['c6nymd_applic_approver'] = $Changes['c6nymd_applic_approver'];
        $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
        $Zfpf->close_connection_1s($DBMSresource);
        // Try to email the change initiator, the affected-entity leader (here same as the current user), and (if assigned) the change project manager.
        $EmailAddresses = array($CI['WorkEmail'], $AE['AELeaderWorkEmail']);
        $Subject = 'PSM-CAP: Change-Management Applicability Determination Canceled by '.$AE['AELeaderNameTitle'];
        $Body = '<p>'.$AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'].' (the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader) canceled approval of the following change-management applicability-determination record.</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        Change initiator: '.$CI['NameTitleEmployerWorkEmail'].'<br />
        Affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$AE['AELeaderNameTitleEmployerWorkEmail'];
        if ($_SESSION['Selected']['k0user_of_project_manager']) {
            $ChangePMInfo = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_project_manager']);
            $EmailAddresses[] = $ChangePMInfo['WorkEmail'];
            $DistributionList .= '<br />
            Change project manager: '.$ChangePMInfo['NameTitleEmployerWorkEmail'];
        }
        $DistributionList .= '</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $AE['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $DistributionList);
        $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Canceled').'<h2>
        The app attempted to cancel approval of the change-management applicability determination.</h2>';
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="cms_io03.php" method="post"><p>
            <input type="submit" name="'.$GoBackSubmitName.'" value="Back to viewing record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // Approvals and task assignments
    foreach ($htmlFormArray as $KA => $VA)
        if (substr($KA, 0, 9) == 'k0user_of') {
            $TaskSubKeyA = substr($KA, 9);
            if (isset($_POST['approve_1'.$TaskSubKeyA]) or isset($_POST['approve_c1'.$TaskSubKeyA])) { // Excludes k0user_of_initiator and k0user_of_project_manager cases.
                if (!$EditAuth or ($_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'] and $_SESSION['t0user']['k0user'] != $_SESSION['Selected'][$KA]))
                    $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
                if ($KA == 'k0user_of_applic_approver' or $KA == 'k0user_of_psr')
                    $Zfpf->edit_lock_1c('change_management');
                $Display = $Zfpf->select_to_display_1e($htmlFormArray);
                if ($KA != 'k0user_of_applic_approver' and $KA != 'k0user_of_psr') { // Trim htmlFormArray for all but these to include only the task at hand.
                    $TaskhtmlFormArray[$KA] = $htmlFormArray[$KA];
                    $TaskhtmlFormArray['c6notes'.$TaskSubKeyA] = $htmlFormArray['c6notes'.$TaskSubKeyA];
                    $TaskhtmlFormArray['c6bfn'.$TaskSubKeyA] = $htmlFormArray['c6bfn'.$TaskSubKeyA];
                    $htmlFormArray = $cmsZfpf->htmlFormArray(FALSE);
                    $htmlFormArray = array_merge($htmlFormArray, $TaskhtmlFormArray);
                }
                else {
                    if ($_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'])
                        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
                    if ($KA == 'k0user_of_applic_approver' and !$_SESSION['Selected']['k0user_of_applic_approver'])
                        unset($htmlFormArray['k0user_of_applic_approver']); // don't show "Not yet approved." on approval form.
                }
                if (isset($_POST['approve_1_psr']) and $Zfpf->decrypt_1c($_SESSION['Selected']['c5duration']) == $Nothing) {
                    echo $Zfpf->xhtml_contents_header_1c().'<h2>
                    Change duration not recorded.</h2>
                    <p>Go back an input the change duration. This is required.</p>
                    <form action="cms_io03.php" method="post"><p>
                        <input type="submit" name="'.$GoBackSubmitName.'" value="Go back" /></p>
                    </form>
                    '.$Zfpf->xhtml_footer_1c();
                    $Zfpf->save_and_exit_1c();
                }
                list($htmlFormArray, $Display) = $cmsZfpf->html_form_modify($htmlFormArray, $Display, $CMRequired); // Must come after above htmlFormArray possible reset.
                // Handle k0 field(s). Need to loop through htmlFormArray again.
                foreach ($htmlFormArray as $KB => $VB)
                    if (substr($KB, 0, 9) == 'k0user_of') {
                        $TaskSubKeyB = substr($KB, 9);
                        if (!$_SESSION['Selected'][$KB]) {
                            if ($KB == 'k0user_of_applic_approver')
                                $Display[$KB] = 'Not yet approved.';
                            elseif ($KB == 'k0user_of_psr')
                                $Display[$KB] = $AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'];
                            else { // Unset notes and bfn for unassigned tasks, only applies to k0user_of_psr case.
                                $Display[$KB] = 'Not assigned. '.$VB[3][1];
                                unset($htmlFormArray['c6notes'.$TaskSubKeyB]);
                                unset($htmlFormArray['c6bfn'.$TaskSubKeyB]);
                            }
                        }
                        else { // Role or task has been assigned.
                            $UJI = $Zfpf->user_job_info_1c($_SESSION['Selected'][$KB]); // Use this even for k0user_of_psr in case a former AE Leader authorized startup.
                            $Display[$KB] = $UJI['NameTitle'].', '.$UJI['Employer'];
                            if ($TaskSubKeyB != $TaskSubKeyA and $TaskSubKeyB != '_initiator' and $TaskSubKeyB != '_project_manager') { // Verify a c5ts field exists.
                                $WhenApproved = $Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($_SESSION['Selected']['c5ts'.$TaskSubKeyB]));
                                if ($WhenApproved == $Nothing)
                                    $Display[$KB] .= ' -- <b>HAS NOT APPROVED</b>';
                                else
                                    $Display[$KB] .= ' -- approved: '.$WhenApproved;
                            }
                        }
                    }
                if (isset($_POST['approve_1'.$TaskSubKeyA])) { // I verified, myself or through others, that everything needed to safely startup has been completed and
                    $NVT = array(
                        'approve_2'.$TaskSubKeyA,
                        'Approve '.$VA[3][1],
                        'confirming that:<br />
                         - I verified, myself or through others whose qualifications I determined are adequate, that everything needed to safely make this approval has been completed and<br />
                         - I am qualified to do this.</b></p><p>
                        <b>Approved By:</b><br />',
                        
                    );
                    $_SESSION['Scratch']['c6nymd'] = $Zfpf->encrypt_1c($User['NameTitleEmployerWorkEmail'].' on '.$CurrentDate);
                }
                elseif (isset($_POST['approve_c1'.$TaskSubKeyA])) {
                    $NVT = array(
                        'approve_c2'.$TaskSubKeyA,
                        'Cancel approval',
                        'canceling this approval.</b></p><p>
                        <b>Approval Canceled By:</b><br />'
                    );
                    $_SESSION['Scratch']['c6nymd'] = $EncryptedNothing;
                }
                $ApprovalText = '<h1>
                '.$NVT[1].'</h1>
                '.$Zfpf->select_to_o1_html_1e($htmlFormArray, FALSE, $_SESSION['Selected'], $Display).'<p>
                <b>By clicking "'.$NVT[1].'" below, I am '.$NVT[2].'
                Name: <b>'.$User['Name'].'</b><br />
                Job Title: <b>'.$User['Title'].'</b><br />
                Employer<b>: '.$User['Employer'].'</b><br />
                Email Address<b>: '.$User['WorkEmail'].'</b><br />
                Date: <b>'.$CurrentDate.'</b></p>';
                echo $Zfpf->xhtml_contents_header_1c('Confirm').$ApprovalText.'
                <form action="cms_io03.php" method="post"><p>
                    <input type="submit" name="'.$NVT[0].'" value="'.$NVT[1].'" /></p><p>
                    <input type="submit" name="'.$GoBackSubmitName.'" value="Take no action -- go back" /></p>
                </form>
                '.$Zfpf->xhtml_footer_1c();
                $_SESSION['Scratch']['ApprovalText'] = $Zfpf->encrypt_1c($ApprovalText);
                $Zfpf->save_and_exit_1c();
            }
            if (isset($_POST['approve_2'.$TaskSubKeyA]) or isset($_POST['approve_c2'.$TaskSubKeyA])) {
                if (!$EditAuth or ($_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'] and $_SESSION['t0user']['k0user'] != $_SESSION['Selected'][$KA]))
                    $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
                $Conditions[0] = array('k0change_management', '=', $_SESSION['Selected']['k0change_management']);
                if (isset($_POST['approve_2'.$TaskSubKeyA])) {
                    $Changes['c5ts'.$TaskSubKeyA] = $Zfpf->encrypt_1c(time());
                    if ($KA == 'k0user_of_applic_approver' or $KA == 'k0user_of_psr') {
                        $Changes[$KA] = $_SESSION['t0user']['k0user'];
                        $_SESSION['Selected'][$KA] = $Changes[$KA];
                    }
                }
                elseif (isset($_POST['approve_c2'.$TaskSubKeyA])) {
                    $Changes['c5ts'.$TaskSubKeyA] = $EncryptedNothing;
                    if ($KA == 'k0user_of_applic_approver') {
                        $Changes[$KA] = 0;
                        $_SESSION['Selected'][$KA] = $Changes[$KA];
                    }
                    elseif ($KA == 'k0user_of_psr') // Startup authorization cannot be canceled. A new change-management record is needed to reverse this.
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                }
                $Changes['c6nymd'.$TaskSubKeyA] = $_SESSION['Scratch']['c6nymd'];
                $Changes['c5who_is_editing'] = $EncryptedNobody; // Same effect as clear_edit_lock_1c().
                // Finish updating $_SESSION['Selected']
                $_SESSION['Selected']['c5ts'.$TaskSubKeyA] = $Changes['c5ts'.$TaskSubKeyA];
                $_SESSION['Selected']['c6nymd'.$TaskSubKeyA] = $Changes['c6nymd'.$TaskSubKeyA];
                $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing'];
                $DBMSresource = $Zfpf->credentials_connect_instance_1s();
                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0change_management', $Changes, $Conditions, TRUE, $htmlFormArray);
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                if (isset($_POST['approve_2'.$TaskSubKeyA])) {
                    $SubjectText = ' Approved by ';
                    $BodyText = ' made';
                    $EchoText = 'approve the';
                }
                elseif (isset($_POST['approve_c2'.$TaskSubKeyA])) {
                    $SubjectText = ' Approval Canceled by ';
                    $BodyText = ' canceled';
                    $EchoText = 'cancel approval of the';
                }
                $Zfpf->close_connection_1s($DBMSresource);
                // Try to email the current user (either a task leader or the AE leader), the change project manager (if assigned), the change initiator, and the AE leader.
                $EmailAddresses = array($User['WorkEmail'], $CI['WorkEmail'], $AE['AELeaderWorkEmail']);
                $Subject = 'PSM-CAP: '.$VA[3][1].$SubjectText.$User['NameTitle'];
                $Body = '<p>'.$User['NameTitle'].', '.$User['Employer'].$BodyText.' the following approval.</p>';
                $DistributionList = '<p>
                <b>Distributed To (if an email address was found): </b><br />';
                if ($_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'])
                    $DistributionList .= $VA[3][1].' Leader: '.$User['NameTitleEmployerWorkEmail'].'<br />';
                if ($_SESSION['Selected']['k0user_of_project_manager']) {
                    $ChangePM = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_project_manager']);
                    $EmailAddresses[] = $ChangePM['WorkEmail'];
                    $DistributionList .= 'Change project manager: '.$ChangePM['NameTitleEmployerWorkEmail'].'<br />';
                }
                $DistributionList .= '
                Change initiator: '.$CI['NameTitleEmployerWorkEmail'].'<br />
                Affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$AE['AELeaderNameTitleEmployerWorkEmail'].'</p>';
                $Body = $Zfpf->email_body_append_1c($Body, $AE['AEFullDescription'], $Zfpf->decrypt_1c($_SESSION['Scratch']['ApprovalText']), $DistributionList);
                $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
                echo $Zfpf->xhtml_contents_header_1c('Approved').'<h2>
                The app attempted to '.$EchoText.' '.$VA[3][1].', a change-management task.</h2>';
                if ($EmailSent)
                    echo '<p>You and others involved should soon receive an email confirming this.</p>';
                else
                    echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
                echo '
                <form action="cms_io03.php" method="post"><p>
                    <input type="submit" name="'.$GoBackSubmitName.'" value="Back to viewing record" /></p>
                </form>
                '.$Zfpf->xhtml_footer_1c();
                $Zfpf->save_and_exit_1c();
            }
            // Task assignments
            // No edit_lock_1c() because only the AELeader can assign tasks.
            if (isset($_POST['assign_1'.$TaskSubKeyA])) {
                if ($_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'])
                    $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
                echo $Zfpf->xhtml_contents_header_1c('Lookup User');
                $Zfpf->lookup_user_1c('cms_io03.php', 'cms_io03.php', 'assign_2'.$TaskSubKeyA, 'cms_o1');
                echo $Zfpf->xhtml_footer_1c();
                $Zfpf->save_and_exit_1c();
            }
            if (isset($_POST['assign_2'.$TaskSubKeyA])) {
                if ($_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'])
                    $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
                // Create array $Conditions1[] and $TableNameUserEntity for selecting all users from the affected-entity/user junction table.
                $AffectedEntity = $Zfpf->decrypt_1c($_SESSION['Selected']['c5affected_entity']);
                if ($AffectedEntity == 'Contractor-wide') {
                    $TableNameUserEntity = 't0user_contractor';
                    $Conditions1[0] = array('k0contractor', '=', $_SESSION['Selected']['k0affected_entity']);
                }
                elseif ($AffectedEntity == 'Owner-wide') {
                    $TableNameUserEntity = 't0user_owner';
                    $Conditions1[0] = array('k0owner', '=', $_SESSION['Selected']['k0affected_entity']);
                }
                elseif ($AffectedEntity == 'Facility-wide') {
                    $TableNameUserEntity = 't0user_facility';
                    $Conditions1[0] = array('k0facility', '=', $_SESSION['Selected']['k0affected_entity']);
                }
                elseif ($AffectedEntity == 'Process-wide') {
                    $TableNameUserEntity = 't0user_process';
                    $Conditions1[0] = array('k0process', '=', $_SESSION['Selected']['k0affected_entity']);
                }
                $SpecialText = '';
                if ($KA != 'k0user_of_project_manager')
                    $SpecialText .= '<p>
                    Requested Completion Date and Time (optional):<br />
                    <input type="text" name="c5ts'.$TaskSubKeyA.'_requested" class="screenwidth" maxlength = "'.C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF.'" /></p>';
                $SpecialText .= '<p><b>
                Pick Task Leader</b></p><p>
                The current task leader, if any, will be replaced by the user you pick above.</p>';
                $Zfpf->lookup_user_wrap_2c(
                    $TableNameUserEntity,
                    $Conditions1,
                    'cms_io03.php', // $SubmitFile
                    'cms_io03.php', // $TryAgainFile
                    array('k0user'), // $Columns1
                    'cms_io03.php', // $CancelFile
                    $SpecialText,
                    'Assign Task Leader', // $SpecialSubmitButton
                    'assign_3'.$TaskSubKeyA, // $SubmitButtonName
                    'assign_1'.$TaskSubKeyA, // $TryAgainButtonName
                    'cms_o1', // $CancelButtonName
                    'c5ts_logon_revoked', // $FilterColumnName
                    $Nothing // $Filter
                ); // This function echos and exits.
            }
            if (isset($_POST['assign_3'.$TaskSubKeyA])) {
                if ($_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'])
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
                else
                    $k0user = 0;
                unset($_SESSION['Scratch']['PlainText']['lookup_user']);
                // Check if user forgot to make an adequate selection.
                $OldTarget = $Nothing;
                $NewTarget = $Nothing;
                if ($KA != 'k0user_of_project_manager') {
                    $OldTarget = $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts'.$TaskSubKeyA.'_requested']);
                    $NewTarget = $Zfpf->text_to_timestamp_1c($Zfpf->post_length_blank_1c('c5ts'.$TaskSubKeyA.'_requested'));
                }
                if ((($NewTarget == $Nothing or $NewTarget == $OldTarget) and (!$k0user or $_SESSION['Selected'][$KA] == $k0user)) or (!$_SESSION['Selected'][$KA] and !$k0user)) {
                    echo $Zfpf->xhtml_contents_header_1c('Nobody New').'<p>
                    Either:<br />
                    - you forgot to make a selection or input anything,<br />
                    - you made no changes, or<br />
                    - there was no former leader assigned and you selected nobody for this.</p>
                    <form action="cms_io03.php" method="post"><p>
                        <input type="submit" name="assign_1'.$TaskSubKeyA.'" value="Try again" /><br />
                        <input type="submit" name="cms_o1" value="Cancel" /></p>
                    </form>
                    '.$Zfpf->xhtml_footer_1c();
                    $Zfpf->save_and_exit_1c();
                }
                // Update database and $_SESSION['Selected'] with $k0user (if changed) and any requested completion date.
                $Conditions[0] = array('k0change_management', '=', $_SESSION['Selected']['k0change_management']);
                $Body = '';
                $EchoText = '';
                if ($k0user) {
                    // Get any former leader info needed for notification emails, before updating $_SESSION['Selected'].
                    if ($_SESSION['Selected'][$KA])
                        $FormerLeader = $Zfpf->user_job_info_1c($_SESSION['Selected'][$KA]);
                    $Leader = $Zfpf->user_job_info_1c($k0user);
                    $Changes[$KA] = $k0user;
                    $_SESSION['Selected'][$KA] = $Changes[$KA];
                    $Body .= '<p>'.$VA[3][1].' assigned to '.$Leader['NameTitle'].', '.$Leader['Employer'].'<br />
                    by '.$AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'].' (the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader).</p>';
                    $EchoText .= '<p>
                    The app attempted to assign '.$VA[3][1].', a change-management task.</p>';
                }
                else
                    $Leader = $Zfpf->user_job_info_1c($_SESSION['Selected'][$KA]); // Leader same, but new target date.
                if ($NewTarget != $OldTarget and $NewTarget != $Nothing) {
                    $Changes['c5ts'.$TaskSubKeyA.'_requested'] = $Zfpf->encrypt_1c($NewTarget);
                    $_SESSION['Selected']['c5ts'.$TaskSubKeyA.'_requested'] = $Changes['c5ts'.$TaskSubKeyA.'_requested'];
                    $Body .= '<p>';
                    if ($OldTarget != $Nothing)
                        $Body .= '<b>New </b>';
                    $Body .= 'Requested Completion Date and Time for '.$VA[3][1].': '.$Zfpf->timestamp_to_display_1c($NewTarget).'</p>';
                    $EchoText .= '<p>
                    The app attempted to record the requested completion date and time for '.$VA[3][1].', a change-management task.</p>';
                }
                $ChangeName = $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
                $Body .= '<p>Change Name: '.$ChangeName.'</p>';
                // $Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]'); // Same effect as clear_edit_lock_1c(). Not needed because no edit_lock_1c() because only the AELeader can assign tasks.
                // $_SESSION['Selected']['c5who_is_editing'] = $Changes['c5who_is_editing']; // Not needed because no edit_lock_1c() because only the AELeader can assign tasks.
                $DBMSresource = $Zfpf->credentials_connect_instance_1s();
                $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0change_management', $Changes, $Conditions); // TO DO option: get htmlFormArray and pass in , TRUE, $htmlFormArray for t0history.
                // $Affected should not be zero because we confirmed that a new user was selected above.
                if ($Affected != 1)
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
                $Zfpf->close_connection_1s($DBMSresource);
                // Email the any former task leader, the newly-assigned task leader, the affected-entity leader (who should be the current user), and any change project manager.
                $EmailAddresses = array($AE['AELeaderWorkEmail'], $Leader['WorkEmail']);
            	$Subject = 'PSM-CAP: Change Management for '.$ChangeName;
                $DistributionList = '<p>
                <b>Distributed To (if an email address was found): </b>';
                if ($_SESSION['Selected']['k0user_of_project_manager']) {
                    $ChangePM = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_project_manager']);
                    $EmailAddresses[] = $ChangePM['WorkEmail'];
                    $DistributionList .= '<br />
                    Change project manager: '.$ChangePM['NameTitleEmployerWorkEmail'];
                }
                if ($KA != 'k0user_of_project_manager')
                    $DistributionList .= '<br />
                    Leader for '. $VA[3][1].': '.$Leader['NameTitleEmployerWorkEmail'];
                if (isset($FormerLeader)) {
                    $EmailAddresses[] = $FormerLeader['WorkEmail'];
                    $DistributionList .= '<br />Former ';
                    if ($KA != 'k0user_of_project_manager')
                        $DistributionList .= 'Leader for ';
                    $DistributionList .= $VA[3][1].': '.$FormerLeader['NameTitleEmployerWorkEmail'];
                }
                $DistributionList .= '<br />
                Affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$AE['AELeaderNameTitleEmployerWorkEmail'];
                $DistributionList .= '</p>';
                $Body = $Zfpf->email_body_append_1c($Body, $AE['AEFullDescription'], '', $DistributionList);
                $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
                echo $Zfpf->xhtml_contents_header_1c('Assigned').$EchoText;
                if ($EmailSent)
                    echo '<p>You and others involved should soon receive an email confirming this.</p>';
                else
                    echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
                echo '
                <form action="cms_io03.php" method="post"><p>
                    <input type="submit" name="cms_o1" value="Back to viewing record" /></p>
                </form>
                '.$Zfpf->xhtml_footer_1c();
                $Zfpf->save_and_exit_1c();
            }
        }

    // Additional security check for i1 and i2 code
    if ($_SESSION['Selected']['k0user_of_psr'])
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['cms_i0n']) or isset($_POST['cm_applies_o1_from']) or isset($_POST['cms_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE); // cms note: $htmlFormArray is modified above.
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        if (!isset($AE))
            $AE = 0; // Allows i0n code to run without triggering variable not set error.
        foreach ($htmlFormArray as $KA => $VA) {
            if (substr($KA, 0, 9) == 'k0user_of') {
                $TaskSubKey = substr($KA, 9);
                if (!$_SESSION['Selected'][$KA]) { // Here, $_SESSION['Selected']['k0user_of_psr'] always 0, so statement true. See send_to_contents_1c above.
                    if ($KA == 'k0user_of_applic_approver')
                        $Display[$KA] = 'Not yet approved.';
                    elseif ($KA == 'k0user_of_project_manager')
                        $Display[$KA] = 'None assigned. Optional.';
                    elseif ($KA == 'k0user_of_psr' and $AE and $_SESSION['t0user']['k0user'] == $AE['AELeader_k0user']) // In this case $AE will be properly set. Only AELeader can edit startup authorization (PSR) fields.
                        $Display[$KA] = $AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'];
                    else {
                        // These cases cannot be above three plus k0user_of_initiator, which is always assigned here.
                        // Task not assigned at all, so cannot be assigned to current user, and so
                        // unset all htmlFormArray fields for unassigned tasks.
                        unset($htmlFormArray[$KA]);
                        unset($htmlFormArray['c6notes'.$TaskSubKey]);
                        unset($htmlFormArray['c6bfn'.$TaskSubKey]);
                    }
                }
                else { // Task has been assigned...
                    if ($KA == 'k0user_of_initiator')
                        $Display[$KA] = $CI['NameTitle'].', '.$CI['Employer']; // Set above and cannot be changed in meantime.
                    elseif ($KA == 'k0user_of_applic_approver') {
                        $AplicApprover = $Zfpf->user_job_info_1c($_SESSION['Selected'][$KA]);
                        $Display[$KA] = $AplicApprover['NameTitle'].', '.$AplicApprover['Employer'];
                    }
                    elseif ($KA == 'k0user_of_project_manager') {
                        $ChangePM = $Zfpf->user_job_info_1c($_SESSION['Selected'][$KA]);
                        $Display[$KA] = $ChangePM['NameTitle'].', '.$ChangePM['Employer'];
                    }
                    elseif ($_SESSION['t0user']['k0user'] == $_SESSION['Selected'][$KA]) { // ...and assigned to the current user.
                        $UJI = $Zfpf->user_job_info_1c($_SESSION['Selected'][$KA]);
                        $Display[$KA] = $UJI['NameTitle'].', '.$UJI['Employer'];
                        if (isset($_SESSION['Selected']['c5ts'.$TaskSubKey.'_requested'])) {
                            $Requested = $Zfpf->timestamp_to_display_1c($Zfpf->decrypt_1c($_SESSION['Selected']['c5ts'.$TaskSubKey.'_requested']));
                            if ($Requested != $Nothing)
                                $Display[$KA] .= ' -- task-completion target: '.$Requested;
                        }
                    }
                    else {  // ... but not assigned to the current user.
                        unset($htmlFormArray[$KA]);
                        unset($htmlFormArray['c6notes'.$TaskSubKey]);
                        unset($htmlFormArray['c6bfn'.$TaskSubKey]);
                    }
                }
            }
            elseif ($KA == 'c6cm_applies_checks')
                // Don't allow editing of c5name, c6description, c5affected_entity, or cm_applies_checks if applicability determination is approved
                // And, if not approved, only allow editing by the affected-entity leader, change project manager, or change initiator.
                if ($_SESSION['Selected']['k0user_of_applic_approver'] or ($AE and $_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'] and $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_project_manager'] and $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_initiator'])) {
                    $htmlFormArray['c5name'][1] = '';
                    $htmlFormArray['c6description'][1] = '';
                    $htmlFormArray['c5affected_entity'][1] = '';
                    $htmlFormArray['c5name'][3] = 'app_assigned';
                    $htmlFormArray['c6description'][3] = 'app_assigned';
                    $htmlFormArray['c5affected_entity'][3] = 'app_assigned';
                    foreach ($VA[2] as $KB => $VB) {
                        if (isset($Display[$KA][$KB]) and $Display[$KA][$KB] == $Nothing) {
                            unset($htmlFormArray[$KA][2][$KB]);
                            unset($Display[$KA][$KB]);
                        }
                        else
                            $htmlFormArray[$KA][2][$KB][3] = 'app_assigned';
                    }
                    $htmlFormArray[$KA][2] = array_values($htmlFormArray[$KA][2]); // Re-index array, remove missing numeric keys.
                    $Display[$KA] = array_values($Display[$KA]);
                    $ExtraMessage = ' The change-management applicability checklist may only be edited by the affected-entity leader, the change project manager, or the change initiator.';
                    if ($_SESSION['Selected']['k0user_of_applic_approver']) // In this case $AE will be properly set.
                        $ExtraMessage = ' This cannot be edited because the applicability determination has been approved. Before startup authorization, '.$AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'].', the affected-entity leader, may cancel this applicability-determination approval.';
                    if ($CMRequired)
                        $htmlFormArray[$KA][0] = 'Change management <b>is required</b> because the proposed change will reportedly alter the things marked "Yes" below.'.$ExtraMessage;
                    else
                        $htmlFormArray[$KA][0] = 'Change Management <b>is not required</b> because the proposed change will reportedly alter nothing listed below.'.$ExtraMessage;
                }
            elseif ($KA == 'c6bfn_markup')
                // Allow any task leader or the AE Leader to upload c6bfn_markup
                if (!in_array($_SESSION['t0user']['k0user'], $_SESSION['Selected']) and $_SESSION['t0user']['k0user'] != $AE['AELeader_k0user']) {
                    $htmlFormArray['c6bfn_markup'] = array('<a id="c6bfn_markup"></a><b>Full description of change</b>, such as a project manual or any needed markups of the process-safety information, hazardous-substance procedures and safe-work practices, or inspection, testing, and maintenance (ITM) procedures', '', MAX_FILE_SIZE_ZFPF, 'app_assigned');
                    $Display['c6bfn_markup'] = 'You do not have permission to upload files. You may download files from the View Change-Management Record page. Files may be uploaded only by change task leaders, the affected-entity leader, the change project manager, or the change initiator.'; 
                    // A former AE leader who approved the applicability determination (k0user_of_applic_approver) could also upload, based on above in_array condition.
                }
        }
        // Limit who can edit c5duration, c5ts_psr_requested, c5reason
        if (isset($htmlFormArray['c5duration']) and $_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'] and $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_project_manager']) {
            $htmlFormArray['c5duration'][1] = '';
            $htmlFormArray['c5ts_psr_requested'][1] = '';
            $htmlFormArray['c5duration'][3] = 'app_assigned';
            $htmlFormArray['c5ts_psr_requested'][3] = 'app_assigned';
            $htmlFormArray['c5reason'][3] = 'app_assigned';
            unset($htmlFormArray['c5duration'][4]);
        }
        // To standardize, always save possibly modified htmlFormArray, SelectDisplay, and (to simplify code below) placeholder for user's post.
        $_SESSION['Scratch']['htmlFormArray'] = $Zfpf->encode_encrypt_1c($htmlFormArray);
        $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($Display);
        $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
    }
    // 1.2 $_SESSION['Scratch']['SelectDisplay'] is source of $Display, the version generated from the database record.
    elseif (isset($_POST['undo_confirm_post_1e'])) {
        if (!isset($_SESSION['Scratch']['SelectDisplay']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
    }
    // 1.3 $_SESSION['Post'] is source of $Display, the latest user-modified version.  Also use for upload_files.
    elseif (isset($_SESSION['Post']) and !isset($_POST['cms_i2'])) { // !isset($_POST['cms_i2']) only needed for uploads_files special case, just lets i2 skip foreach below.
        if (isset($_POST['modify_confirm_post_1e']) or isset($_POST['problem_c6bfn_files_upload_1e']))
            $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
        // START upload_files special case.
        else {
            $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
            foreach ($htmlFormArray as $K => $V)
                if (substr($K, 0, 5) == 'c6bfn' and isset($_POST['upload_'.$K])) {
                    $c6bfn_array = $Zfpf->c6bfn_decrypt_decode_1e($_SESSION['Selected'][$K]);
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, $K, 'cms_io03.php');
                    // FilesZfpf::6bfn_files_upload_1e updates $_SESSION['Selected'] and the database. 
                    // Or, it echos an error page an user has option to press $_POST['problem_c6bfn_files_upload_1e'] button.
                    $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
                    $SelectDisplay[$K] = $Zfpf->html_uploaded_files_1e($K); // Update the modified select display
                    $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($SelectDisplay);
                    $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($Zfpf->post_to_display_1e($htmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Post']), $UploadResults['count'], $K));
                    // $_SESSION['Post'] now holds $Display holding $_POST updated with $_SESSION['Selected']['c6bfn_...'] information.
                    header("Location: #$K");
                    $Zfpf->save_and_exit_1c();
                }
        }
    }
    if (!$_POST and isset($_SESSION['Post']))
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // This re-defines $Display after header() redirect above.
    // END uploads_files special case
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        // Create HTML form
        // To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        echo $Zfpf->xhtml_contents_header_1c('Change').$Heading.'
        <form action="cms_io03.php" method="post" enctype="multipart/form-data" >';
        // Add "Generate an activity notice" option, for i1 display, if not a new record. Do here to keep out of history table.
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        if ($who_is_editing != '[A new database row is being created.]' and isset($htmlFormArray['c6bfn_act_notice'])) // Exclude cms_i0n and cm_applies_o1_from. cm_applies_o1_from has a shorter htmlFormArray that doesn't include c6bfn_act_notice. See /includes/cmsZfpf.php
            $htmlFormArray['c6bfn_act_notice'][0] .= '<br />
            <a class="toc" href="cms_io03.php?act_notice_1">[Generate an activity notice]</a>';
        echo $Zfpf->make_html_form_1e($htmlFormArray, $Display);
        echo '<p>
            <input type="submit" name="cms_i2" value="Review what you typed into form" /><br />
            If you only wanted to upload files, you are done. Click on "Go Back"</p>
        </form>';
        if ($who_is_editing == '[A new database row is being created.]')
            echo '
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>';
        else
            echo '
            <form action="cms_io03.php" method="post"><p>
                <input type="submit" name="'.$GoBackSubmitName.'" value="Go back" /></p>
            </form>';
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['cms_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay);
        echo $Zfpf->post_select_required_compare_confirm_1e('cms_io03.php', 'cms_io03.php', $htmlFormArray, $PostDisplay, $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']));
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        // If the affected-entity changed, get the new affected-entity key into $ChangedRow
        if (isset($ChangedRow['c5affected_entity'])) {
            switch ($Zfpf->decrypt_1c($ChangedRow['c5affected_entity'])) {
                case 'Owner-wide':
                    if (!isset($_SESSION['StatePicked']['t0owner']['k0owner']))
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                    $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0owner']['k0owner'];
                    break;
                case 'Contractor-wide':
                    if (!isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                    $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0contractor']['k0contractor'];
                    break;
                case 'Facility-wide':
                    if (!isset($_SESSION['StatePicked']['t0facility']['k0facility']))
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                    $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0facility']['k0facility'];
                    break;
                case 'Process-wide':
                    if (!isset($_SESSION['StatePicked']['t0process']['k0process']))
                        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
                    $ChangedRow['k0affected_entity'] = $_SESSION['StatePicked']['t0process']['k0process'];
                    break;
                default:
                    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            }
        }
        $OldWhoIsEditing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']); // SPECIAL CASE
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V; // SPECIAL CASE: update $_SESSION['Selected'] here for use in CoreZfpf::affected_entity_info_1c below.
        // Get current-user, affected-entity (AE), and change-initiator (CI) information.
        $User = $Zfpf->current_user_info_1c();
        $AE = $Zfpf->affected_entity_info_1c(); // This function defaults to $_SESSION['Selected']['c5affected_entity'] and $_SESSION['Selected']['k0affected_entity']
        $CI = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_initiator']);
        // Additional security check
        if ($_SESSION['Selected']['k0user_of_psr'] 
            or (
            $_SESSION['t0user']['k0user'] != $AE['AELeader_k0user'] and 
            $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_project_manager'] and 
            $_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_initiator']
            )
            and (
            isset($ChangedRow['c5name']) or 
            isset($ChangedRow['c6description']) or 
            isset($ChangedRow['c5affected_entity']) or 
            isset($ChangedRow['c6cm_applies_checks'])
            )) {
            $Zfpf->close_connection_1s($DBMSresource);
            $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
        }
        // SPECIAL CASE: who_is_editing check
        if ($OldWhoIsEditing != '[Nobody is editing.]' and $OldWhoIsEditing != '[A new database row is being created.]' and $OldWhoIsEditing != substr($Zfpf->user_identification_1c(), 0, C5_MAX_BYTES_ZFPF)) {
            echo $Zfpf->xhtml_contents_header_1c().$Heading.'
            <p><b>'.$OldWhoIsEditing.' is editing the record you selected.</b><br />
            If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>
            <form action="practice_o1.php" method="post"><p>
                <input type="submit" value="Go back" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        if ($OldWhoIsEditing == '[A new database row is being created.]') {
            $Zfpf->insert_sql_1s($DBMSresource, 't0change_management', $ChangedRow);
            $Subject = 'PSM-CAP: Change-Management Applicability Determination Started';
            $Body = '<p>'.$User['NameTitle'].', '.$User['Employer'].' started the following change-management applicability determination.</p>';  // i0n not only cm_applies.
        }
        else {
            $Conditions[0] = array('k0change_management', '=', $_SESSION['Selected']['k0change_management']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0change_management', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
            $Subject = 'PSM-CAP: Change-Management Record Updated';
            $Body = '<p>'.$User['NameTitle'].', '.$User['Employer'].' revised the following change-management record.</p>';
        }
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        // Try to email the current user, the change initiator, the affected-entity leader, and (if assigned) the change project manager.
        // CoreZfpf::send_email_1c removes duplicate email address
        $EmailAddresses = array($User['WorkEmail'], $CI['WorkEmail'], $AE['AELeaderWorkEmail']);
        $Body .= '<p>
        Change Name: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).'<br />
        Change Description: '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6description']).'<br />
        At '.$AE['AEFullDescription'].' (the affected entity)</p>';
        // Editing a change-management record is not allowed after startup authorization, so k0user_of_psr = 0 here, always.
        if (in_array('Yes', $Zfpf->decrypt_decode_1c($_SESSION['Selected']['c6cm_applies_checks']))) // CM is required.
            $Body .= '<p>Change management is required. Unless you are acting under the authority of an emergency plan, <b>do not make this change before startup is authorized by the affected-entity leader</b>. Some preparations for the change may be authorized by the affected-entity leader before startup authorization.</p>';
        else // Approval that CM isn't required has NOT yet occurred. (If CM not required has been approved, should be here.)
            $Body .= '<p>Awaiting approval that change management is not required. Unless you are acting under the authority of an emergency plan, <b>do not make this change until the PSM-CAP App shows that the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader has approved this determination that change-management does not apply</b>.</p><p>
        '.$AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'].', the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader, would need to log onto the PSM-CAP app to approve this applicability determination.</p>';
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found): </b><br />
        User who did this: '.$User['NameTitle'].', '.$User['Employer'].' '.$User['WorkEmail'].'<br />
        Change initiator: '.$CI['NameTitle'].', '.$CI['Employer'].' '.$CI['WorkEmail'].'<br />
        Affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader: '.$AE['AELeaderNameTitle'].', '.$AE['AELeaderEmployer'].' '.$AE['AELeaderWorkEmail'];
        if ($_SESSION['Selected']['k0user_of_project_manager']) {
            $ChangePMInfo = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_project_manager']);
            $EmailAddresses[] = $ChangePMInfo['WorkEmail'];
            $DistributionList .= '<br />
            Change project manager: '.$ChangePMInfo['NameTitle'].', '.$ChangePMInfo['Employer'].' '.$ChangePMInfo['WorkEmail'];
        }
        $DistributionList .= '</p>';
        $Body = $Zfpf->email_body_append_1c($Body, $AE['AEFullDescription'], FALSE, $DistributionList);
        $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c('Change').$Heading.'<p>
        The updates you just reviewed have been recorded. Do not "startup" the change until the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader has authorized startup.</p>';
        if ($EmailSent)
            echo '<p>The PSM-CAP App just tried to email you and the affected-entity '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader, check with them to expedite.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="cms_io03.php" method="post"><p>
            <input type="submit" ';
            if ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'cms_i1m.php') // See the security check for the o1 code
                echo 'name="cms_o1"';
            if ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'cm_applies_i1m.php')
                echo 'name="cm_applies_o1"';
            echo ' value="Back to record" /></p>
        </form>
        <form action="practice_o1.php" method="post"><p>
            <input type="submit" value="Back to records list" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // ends i1, i2, i3, approval, and task assignment code

$Zfpf->catch_all_1c('practice_o1.php');

$Zfpf->save_and_exit_1c();

