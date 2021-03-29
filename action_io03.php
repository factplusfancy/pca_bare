<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License. 

// This file handles all the input and output HTML forms, except the:
//  - SPECIAL CASE: no i1m file.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf;
$Zfpf->session_check_1c();
require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
$ccsaZfpf = new ccsaZfpf;

// i3 code
if (isset($_POST['yes_confirm_post_1e'])) {
    // SPECIAL CASE security check.
    if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or !isset($_SESSION['Post']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'incident_i1m.php') {
        if (!isset($_SESSION['Selected']['k0incident']) or !isset($_SESSION['Scratch']['t0action'])) // There are no template incidents.
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $TableRoot = 'incident';
    }
    elseif ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'audit_i1m.php') {
        if (!isset($_SESSION['Selected']['k0audit']) or !isset($_SESSION['Scratch']['t0action']) or $_SESSION['Selected']['k0audit'] < 100000) // Template audits cannot have actions
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $TableRoot = 'obsresult';
    }
    elseif ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'pha_i1m.php') {
        if (!isset($_SESSION['Selected']['k0pha']) or !isset($_SESSION['Scratch']['t0subprocess']) or !isset($_SESSION['Scratch']['t0scenario']) or !isset($_SESSION['Scratch']['t0action']) or $_SESSION['Selected']['k0pha'] < 100000) // Template PHAs cannot have actions
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $TableRoot = 'scenario';
    }
    else
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    echo $ccsaZfpf->ccsa_edit('action', $Zfpf, $TableRoot);
    $Zfpf->save_and_exit_1c();
}

// o1, history_o1, _i1aic, i1, i2 code, and action register i1m code, all via ccsaZfpf::ccsa_io0_2
require INCLUDES_DIRECTORY_PATH_ZFPF.'/arZfpf.php';
$arZfpf = new arZfpf;
// SPECIAL CASE security check.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']))
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
if ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'audit_i1m.php') {
    if (!isset($_SESSION['Selected']['k0audit']) or !isset($_SESSION['Scratch']['t0obsresult']) or $_SESSION['Selected']['k0audit'] < 100000) // Template audits cannot have actions
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $TableRoot = 'obsresult';
    $k0TableRoot = $_SESSION['Scratch']['t0obsresult']['k0obsresult'];
    $htmlFormArray = $ccsaZfpf->html_form_array('action', $Zfpf, $TableRoot, $arZfpf);
}
elseif ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'incident_i1m.php') {
    if (!isset($_SESSION['Selected']['k0incident'])) // There are no template incidents.
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $TableRoot = 'incident';
    $k0TableRoot = $_SESSION['Selected']['k0incident'];
    $htmlFormArray = $ccsaZfpf->html_form_array('action', $Zfpf, $TableRoot, $arZfpf);
}
elseif ($_SESSION['Scratch']['PlainText']['SecurityToken'] == 'pha_i1m.php') {
    if (!isset($_SESSION['Selected']['k0pha']) or !isset($_SESSION['Scratch']['t0subprocess']) or !isset($_SESSION['Scratch']['t0scenario']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if ($_SESSION['Selected']['k0pha'] < 100000) // Templates don't have actions.
        $Zfpf->send_to_contents_1c(__FILE__, __LINE__); // Don't eject
    $TableRoot = 'scenario';
    $k0TableRoot = $_SESSION['Scratch']['t0scenario']['k0scenario'];
    $htmlFormArray = $ccsaZfpf->html_form_array('action', $Zfpf, $TableRoot, $arZfpf); // Default $TableRoot is 'scenario'.
}
else
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
if (isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']) and isset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action'])) {
    if ($_SESSION['Scratch']['PlainText']['action_ifrom_ar'] != $TableRoot)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    // Insert JT row that points to action.
    $NewRow = array(
        'k0'.$TableRoot.'_action' => time().mt_rand(1000000, 9999999),
        'k0'.$TableRoot => $k0TableRoot,
        'k0action' => $_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action'],
        'c5who_is_editing' => $Zfpf->encrypt_1c('[Nobody is editing.]')
    );
    $Zfpf->one_shot_insert_1s('t0'.$TableRoot.'_action', $NewRow);
    unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar']);
    unset($_SESSION['Scratch']['PlainText']['action_ifrom_ar_k0action']);
}
$ccsaZfpf->ccsa_io0_2('action', $Zfpf, $htmlFormArray, $TableRoot, $arZfpf);
$Zfpf->save_and_exit_1c();

