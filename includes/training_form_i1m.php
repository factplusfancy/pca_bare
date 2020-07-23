<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i1m file outputs an HTML form to select an existing record or create a new one.

// General security check.  Redundant because this is a required file.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'training_form_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

if (!isset($_SESSION['StatePicked']['t0process'])) {
    echo $Zfpf->xhtml_contents_header_1c().'<h1>
    No Process Selected</h1><p>
    Select a process to view employee-training records.</>
    <form action="contents0_s_practice.php" method="post"><p>
        <input type="submit" value="Go back" /></p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// Define SQL WHERE clause for all training_forms associated with the current process.
// If the user is associated with the training_forms practice for this process, they will be able to view these and download supporting files.
$Conditions[0] = array('k0process', '=', $_SESSION['StatePicked']['t0process']['k0process']);
$DBMSresource = $Zfpf->credentials_connect_instance_1s();
list($_SESSION['SelectResults']['t0training_form'], $RR['t0training_form']) = $Zfpf->select_sql_1s($DBMSresource, 't0training_form', $Conditions);
if ($RR['t0training_form'] > 0) {
    foreach ($_SESSION['SelectResults']['t0training_form'] as $K => $V) {
        if (!$V['k0user_of_trainee']) {
            $DisplayUserInfo[$K] = 'Unassigned drafts';
            $SortUserInfo[$K] = ''; // Collates first with PHP array_multisort
        }
        else {
            $TraineeJobInfo = $Zfpf->user_job_info_1c($V['k0user_of_trainee']);
            $DisplayUserInfo[$K] = $TraineeJobInfo['NameTitle'].' '.$TraineeJobInfo['WorkEmail'];
            $SortUserInfo[$K] = $Zfpf->to_lower_case_1c($DisplayUserInfo[$K]);
        }
        $TimestampCompleted[$K] = $Zfpf->decrypt_1c($V['c5ts_completed']);
    }
    array_multisort($SortUserInfo, $TimestampCompleted, SORT_DESC, $DisplayUserInfo, $_SESSION['SelectResults']['t0training_form']);
    $Message = '<h2>
    Select a training record.</h2>
    <form action="training_form_io03.php" method="post">';
    foreach ($_SESSION['SelectResults']['t0training_form'] as $K => $V) {
        if ($K)
            $Previous = $K-1;
        if (!$K or $DisplayUserInfo[$K] != $DisplayUserInfo[$Previous]) {
            if ($K)
                $Message .= '</p>'; // Don't need to close paragraph when !$K
            $Message .= '<p>
            <b>'.$DisplayUserInfo[$K].'</b>';
        }
        $Message .= '<br />
        <input type="radio" name="selected" value="'.$K.'" ';
        if (!$K)
            $Message .= 'checked="checked" '; // Select the first training_form by default to ensure something is posted (unless a hacker is tampering).
        $Message .= '/>'.$Zfpf->decrypt_1c($V['c6procedures_swp']).' -- '.$Zfpf->timestamp_to_display_1c($TimestampCompleted[$K]).' -- '.$Zfpf->decrypt_1c($V['c5status']);
    } // Close the last paragraph below.
    $Message .= '</p><p>
        <input type="submit" name="training_form_o1" value="Select training record" /></p>
    </form>';
}
else
    $Message = '<p><b>
    No training records were found</b> for the current process, neither draft nor approved. Please contact your supervisor if this seems amiss.</p>';
$Zfpf->close_connection_1s($DBMSresource);
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != LOW_PRIVILEGES_ZFPF) // Need at least INSERT global privileges to start a new record.
    $Message .= '
    <form action="training_form_io03.php" method="post"><p>
        <input type="submit" name="training_form_i0n" value="Start new training record" /></p>
    </form>';
else
    $Message .= '<p><b>
    Global Privileges Notice</b>: You have privileges to neither create nor edit PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
echo $Zfpf->xhtml_contents_header_1c('Training').'<h1>
Training on hazardous-substance procedures and safe-work practices</h1>
'.$Message.'
<form action="contents0_s_practice.php" method="post"><p>
    <input type="submit" value="Go back" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

