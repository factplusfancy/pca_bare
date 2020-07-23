<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file handles all the facility summary input and output HTML forms, except the:
//  - i0m & i1m files for listing existing records 

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;
$Zfpf->session_check_1c();

// SPECIAL CASE -- this CoreZfpf::clear_edit_lock_1c needed before CoreZfpf::session_cleanup_1c below.
if (isset($_SESSION['Scratch']['t0lepc'])) {
    $Zfpf->clear_edit_lock_1c($_SESSION['Scratch']['t0lepc']);
    unset($_SESSION['Scratch']['t0lepc']);
}

// Handle user clicked link to this file from left-hand contents or administer1.php
if (!$_POST and isset($_SESSION['StatePicked']['t0facility']) and isset($_SESSION['t0user_facility']) and !isset($_SESSION['Scratch']['PlainText']['UploadDone']) and !isset($_GET['change_psm_leader_1'])) { // !isset($_SESSION['Scratch']['PlainText']['UploadDone']) handles files just uploaded case.
    $Zfpf->session_cleanup_1c(); // This calls CoreZfpf::clear_edit_lock_1c and unsets $_SESSION['Scratch'] -- ok here.
    // Refresh $_SESSION['StatePicked']['t0facility'] in case it changed since the user logged in.
    $Conditions[0] = array('k0facility', '=', $_SESSION['StatePicked']['t0facility']['k0facility']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0facility', $Conditions);
    if ($RR != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'facility_i1m.php';
    $_SESSION['StatePicked']['t0facility'] = $SR[0]; // Refresh
    $_SESSION['Selected'] = $_SESSION['StatePicked']['t0facility'];
    $FromLinkWithoutPost = TRUE;
}

// General security check
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'facility_i1m.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

// Get useful variables.
$UserGlobalDBMSPrivileges = $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']);

// $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
$htmlFormArray = array(
    'c5name' => array('<a id="c5name"></a>Facility Name (what most people call it often works well)', REQUIRED_FIELD_ZFPF),
    'c6description' => array('Description', '', C6SHORT_MAX_BYTES_ZFPF),
    'k0user_of_leader' => array('PSM Leader', ''), // intentionally not in schema order.
    'k0lepc' => array('<a id="k0lepc"></a>Community or local emergency-planning committee (LEPC) or organization', ''),
    'c5phone' => array('Main Telephone Number', ''),
    'c5street1' => array('<a id="c5street1"></a>Address', REQUIRED_FIELD_ZFPF),
    'c5street2' => array('Address (extra line)', ''),
    'c5city' => array('City', REQUIRED_FIELD_ZFPF),
    'c5state_province' => array('State or Province', ''),
    'c5postal_code' => array('Postal Code', ''),
    'c5county' => array('County, parish, district, or similar', ''),
    'c5country' => array('Country', REQUIRED_FIELD_ZFPF),
    'c5website' => array('Website', ''),
    'c5latitude' => array('Latitude (decimal, 0 to 90, negative south of equator)', REQUIRED_FIELD_ZFPF),
    'c5longitude' => array('Longitude (decimal 0 to 180, negative west of prime meridian)', REQUIRED_FIELD_ZFPF),
    'c5lat_long_method' => array(
        'Method for determining above latitude and longitude', 
        REQUIRED_FIELD_ZFPF, 
        C5_MAX_BYTES_ZFPF, 
        'radio', 
        array(
            'Interpolation -- photo (satellite or aerial digital image of earth surface rectified to coordinate system of the reference datum selected below)',
            'Interpolation -- Digital Map Source (digital map rectified to coordinate system of the reference datum selected below)',
            'Interpolation -- Map (paper)',
            'Determined by registered land surveyor',
            'Other'
        )
    ),
    'c5lat_long_description' => array(
        'Pick best description of what is located at the above latitude and longitude', 
        REQUIRED_FIELD_ZFPF, 
        C5_MAX_BYTES_ZFPF, 
        'radio',
        array(
            'AB -- Administrative Building',
            'AE -- Atmospheric Emissions',
            'AM -- Air Monitoring Station Equipment',
            'AS -- Air Release Stack',
            'AV -- Air Release Vent',
            'CE -- Center of Facility',
            'FC -- Facility Centroid',
            'IP -- Intake Pipe',
            'LC -- Loading Area Centroid',
            'LF -- Loading Facility',
            'LW -- Liquid Waste Treatment',
            'NE -- Northeast Corner of Land Parcel',
            'NW -- Northwest Corner of Land Parcel',
            'OT -- Other',
            'PC -- Process Unit Area Centroid',
            'PF -- Plant Entrance (Freight)',
            'PG -- Plant Entrance (General)',
            'PP -- Plant Entrance (Personnel)',
            'PU -- Process Unit',
            'SD -- Solid Waste Treatment or Disposal Unit',
            'SE -- Southeast Corner of Land Parcel',
            'SP -- Lagoon or Settling Pond',
            'SS -- Solid Waste Storage Area',
            'ST -- Storage Tank',
            'SW -- Southwest Corner of Land Parcel',
            'UN -- Unknown',
            'WA -- Wellhead Protection Area',
            'WL -- Well',
            'WM -- Water Monitoring Station',
            'WR -- Pipe Release to Water'
        )
    ),
    'c5lat_long_accuracy' => array('Horizontal accuracy, in meters. You may give the radius of the circle around the above latitude and longitude in which you have 99% confidence the object described above is located', REQUIRED_FIELD_ZFPF),
    'c5lat_long_ref_datum' => array(
        'Reference datum of above latitude and longitude', 
        REQUIRED_FIELD_ZFPF,
        C5_MAX_BYTES_ZFPF,
        'radio', 
        array(
            'North American Datum of 1927',
            'North American Datum of 1983',
            'World Geodetic System of 1984',
            'Caribbean Terrestrial Reference Frame of 2022 (CATRF2022)',
            'Mariana Terrestrial Reference Frame of 2022 (MATRF2022)',
            'North American Terrestrial Reference Frame of 2022 (NATRF2022)',
            'Pacific Terrestrial Reference Frame of 2022 (PATRF2022)',
            'Other'
        )
    ),
    'c5phone_e_24hour' => array('<a id="c5phone_e_24hour"></a>24-hour emergency phone number. Often this is a phone number monitored by a security or answering service (or 24-hour facility office) with an emergency-contact roster, and if it goes to voicemail, the outgoing message lists mobile phone numbers on the emergency-contact roster. This roster may include contract or community responders.', REQUIRED_FIELD_ZFPF),
    'c5email_e' => array('Emergency Email Address. This may trigger texts and emails to people on the emergency-contract roster.', ''),
    'c5onsite_fte' => array('Full-time equivalent employees at the Facility. Do not include contractors.', REQUIRED_FIELD_ZFPF),
    'c6applicable_rules' => array(
        'Rules identified as applicable to the Facility', 
        '',
        array(
            array('Tort laws, under statutes or common law', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('General duty clause in Occupational Safety and Health Act of 1970, subparagraph 5(a)(1)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('General duty clause in Clear Air Act Clean Amendments of 1990, subparagraph 112(r)(1)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Reportable-quantity release, immediate notifications to federal, state, and local authorities upon discovery, per 40 CFR 302 and Emergency Planning and Community Right-to-Know Act (EPCRA) Section 304', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Onsite presence of Extremely Hazardous Substances notifications per EPCRA section 302', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Safety Data Sheet and inventories yearly reporting per EPCRA sections 311 and 312', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Toxics Release Inventory yearly reporting per EPCRA section 313', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Chemical Accident Prevention, Program 1 (for remote, low-risk... facilities)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Chemical Accident Prevention, Program 2 (for non-PSM... facilities)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Chemical Accident Prevention, Program 3 (for PSM... facilities)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Process Safety Management (PSM)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
            array('Clear Air Act, Title V, Air Operating Permit Program', '', C5_MAX_BYTES_ZFPF, 'checkbox')
        )
    ),
    'c6id_numbers' => array(
        'Some identification numbers, complete if applicable to the Facility', 
        '',
        array(
            array('U.S. EPA Facility Identifier for Risk Management Plan (RMP)', ''),
            array('Toxic Release Inventory identification number (TRI ID)', ''),
            array('Facility Index System (FINDS) or Unique Identification Number (UIN)', ''),
            array('Resource Conservation and Recovery Act (RCRA), Information System, Handler Identification (RCRIS Handler ID)', ''),
            array('Comprehensive Environmental Response, Compensation, and Liability Act (CERCLA), Information System, Site Identification (CERCLIS Site ID)', ''),
            array('Air Operating Permit identification', ''),
            array('Dun and Bradstreet, Data Universal Numbering System (DUNS) number', '')
        )
    ),
    'c6bfn_rmp_as_submitted' => array('<a id="c6bfn_rmp_as_submitted"></a>Printouts of Risk Management Plan (RMP) as submitted to the U.S. EPA', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
    'c5ts_rmp_last_submitted' => array('Date and time the last complete update of the RMP was submitted to the U.S. EPA', ''),
    'c6rmp_submission_history' => array('RMP submission history', ''),
    'c6bfn_public_meetings' => array('<a id="c6bfn_public_meetings"></a>Notices, minutes, or other records from any public meetings (or similar) on hazardous substances at the Facility', '', MAX_FILE_SIZE_ZFPF, 'upload_files')
);

// Left hand Table of contents
if (!$_POST or isset($_POST['facility_o1']) or isset($_POST['facility_i0n']) or isset($_POST['facility_o1_from']) or isset($_POST['undo_confirm_post_1e']) or isset($_POST['modify_confirm_post_1e']))
    $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
	    'c5name' => 'Facility name',
	    'k0lepc' => 'Local emergency organization',
	    'c5street1' => 'Facility location',
	    'c5phone_e_24hour' => 'Emergency phone number',
	    'c6bfn_rmp_as_submitted' => 'RMP as submitted',
	    'c6bfn_public_meetings' => 'Public meetings'
    );

// i0n code
if (isset($_POST['facility_i0n'])) {
    // Additional security check.
    if (!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_facility']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
        $Zfpf->send_to_contents_1c(); // Don't eject
    $Nothing = '[Nothing has been recorded in this field.]';
    $EncryptedNothing = $Zfpf->encrypt_1c($Nothing);
    $_SESSION['Selected'] = array (
        'k0facility' => time().mt_rand(1000000, 9999999),
        'k0lepc' => 0,
        'k0user_of_leader' => $_SESSION['t0user']['k0user'],
        'c5name' => $EncryptedNothing,
        'c6description' => $EncryptedNothing,
        'c5phone' => $EncryptedNothing,
        'c5street1' => $EncryptedNothing,
        'c5street2' => $EncryptedNothing,
        'c5city' => $EncryptedNothing,
        'c5state_province' => $EncryptedNothing,
        'c5postal_code' => $EncryptedNothing,
        'c5county' => $EncryptedNothing,
        'c5country' => $EncryptedNothing,
        'c5website' => $EncryptedNothing,
        'c5latitude' => $EncryptedNothing,
        'c5longitude' => $EncryptedNothing,
        'c5lat_long_method' => $EncryptedNothing,
        'c5lat_long_description' => $EncryptedNothing,
        'c5lat_long_accuracy' => $EncryptedNothing,
        'c5lat_long_ref_datum' => $EncryptedNothing,
        'c5phone_e_24hour' => $EncryptedNothing,
        'c5email_e' => $EncryptedNothing,
        'c5onsite_fte' => $EncryptedNothing,
        'c6applicable_rules' => $Zfpf->encode_encrypt_1c(array('Yes', 'Yes', 'Yes', $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing)),
        'c6id_numbers' => $Zfpf->encode_encrypt_1c(array($Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing, $Nothing)),
        'c6bfn_rmp_as_submitted' => $EncryptedNothing,
        'c5ts_rmp_last_submitted' => $EncryptedNothing,
        'c6rmp_submission_history' => $EncryptedNothing,
        'c6bfn_public_meetings' => $EncryptedNothing,
        'c5who_is_editing' => $Zfpf->encrypt_1c('[A new database row is being created.]')
    );
}

// history_o1 code
if (isset($_POST['facility_history_o1'])) {
    if (!isset($_SESSION['Selected']['k0facility']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/HistoryGetZfpf.php';
    $HistoryGetZfpf = new HistoryGetZfpf;
    list($SR, $RR) = $HistoryGetZfpf->one_row_h($Zfpf, 't0facility', $_SESSION['Selected']['k0facility']);
    $HistoryGetZfpf->selected_changes_html_h($Zfpf, $SR, $RR, 'History of one facility record', 'facility_io03.php', 'facility_o1'); // This echos and exits.
}

// Download files.
if (isset($_POST['download_selected']) or isset($_POST['download_all'])) {
    if (!isset($_SESSION['Selected']['k0facility']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['download_selected']))
        $Zfpf->download_selected_files_1e($htmlFormArray, 'facility_io03.php', 'facility_o1');
    if (isset($_POST['download_all']))
        $Zfpf->download_all_files_1e($htmlFormArray, 'facility_io03.php', 'facility_o1');
    $_POST['facility_o1'] = 1;
}

// o1 code
// SPECIAL CASE: isset($FromLinkWithoutPost) handles user clicked link to this file from left-hand contents
if (isset($_POST['facility_o1']) or isset($FromLinkWithoutPost)) {
    if (isset($_POST['facility_o1'])) {
        // Additional security check
        if (!isset($_SESSION['Selected']['k0facility']) and (!isset($_POST['selected']) or !isset($_SESSION['SelectResults']['t0facility'])))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        // $_SESSION cleanup
        if (isset($_SESSION['Scratch']['htmlFormArray']))
            unset($_SESSION['Scratch']['htmlFormArray']);
        if (isset($_SESSION['Post']))
            unset($_SESSION['Post']);
        if (!isset($_SESSION['Selected']['k0facility'])) {
            // Additional security check
            if (!isset($_SESSION['StatePicked']['t0owner']) or !isset($_SESSION['t0user_owner'])) // Same privileges needed to get link from administer1.php.
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $CheckedPost = $Zfpf->post_length_blank_1c('selected'); // Get user selection.
            if (!is_numeric($CheckedPost) or !isset($_SESSION['SelectResults']['t0facility'][$CheckedPost]))
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $_SESSION['Selected'] = $_SESSION['SelectResults']['t0facility'][$CheckedPost];
            unset($_SESSION['SelectResults']);
        }
        $Zfpf->clear_edit_lock_1c();
    }
    $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, TRUE);
	// Handle k0 field(s)
    $Display['k0user_of_leader'] = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader'])['NameTitleEmployerWorkEmail'];
    if (($_SESSION['t0user']['k0user'] == $_SESSION['Selected']['k0user_of_leader'] or $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) == 'Yes') and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF) // Current user is facility PSM leader or app admin.
        $Display['k0user_of_leader'] .= '<a class="toc" href="facility_io03.php?change_psm_leader_1"> [Change PSM leader]</a>';
    if ($_SESSION['Selected']['k0lepc']) {
        $Conditions[0] = array('k0lepc', '=', $_SESSION['Selected']['k0lepc']);
        list($SR, $RR) = $Zfpf->one_shot_select_1s('t0lepc', $Conditions);
        if ($RR != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $Display['k0lepc'] = '<a class="toc" href="lepc_io03.php">'.$Zfpf->decrypt_1c($SR[0]['c5name']).'</a>';
        $Description = $Zfpf->decrypt_1c($SR[0]['c6description']);
        if ($Description != '[Nothing has been recorded in this field.]')
            $Display['k0lepc'] .= '. '.substr($Description, 0, 100).' ...';
    }
    else
        $Display['k0lepc'] = 'None recorded.';
    if (isset($_SESSION['t0user_facility']) and $_SESSION['t0user_facility']['k0facility'] == $_SESSION['Selected']['k0facility'] and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF)
        $Display['k0lepc'] .= '<a class="toc" href="lepc_io03.php"> [Change or Update]</a>';
    echo $Zfpf->xhtml_contents_header_1c().'
    <h2>
    Facility Summary</h2>
    '.$Zfpf->select_to_o1_html_1e($htmlFormArray, 'facility_io03.php', $_SESSION['Selected'], $Display);
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);
    echo '
    <form action="facility_io03.php" method="post">';
    if ($who_is_editing != '[Nobody is editing.]')
        echo '<p><b>
        '.$who_is_editing.' is editing the record you selected.</b><br />
        If needed, contact them to coordinate editing this record. You will not be able to edit it until they are done.</p>';
    else {
        if (isset($_SESSION['t0user_facility']) and $_SESSION['t0user_facility']['k0facility'] == $_SESSION['Selected']['k0facility'] and $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) == MAX_PRIVILEGES_ZFPF and $UserGlobalDBMSPrivileges == MAX_PRIVILEGES_ZFPF)
            echo '<p>
            <input type="submit" name="facility_o1_from" value="Update facility summary" /></p>';
        else
            echo '<p><b>
            Update Privileges Notice</b>: You don\'t have update privileges on this record. Only a facility\'s employee with adequate privileges may update this record.</p>';
        if ($UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)
            echo '<p><b>
            Global Privileges Notice</b>: You don\'t have privileges to update or delete PSM-CAP App records. If you need this, please contact your supervisor or a PSM-CAP App administrator and ask them to upgrade your PSM-CAP App global privileges.</p>';
    }
    echo '<p>
        <input type="submit" name="facility_history_o1" value="History of this record" /></p>
    </form>';
	if (isset($_SESSION['t0user_owner']))
	    echo '
        <form action="facility_i0m.php" method="post"><p>
            <input type="submit" value="Back to facilities list" /></p>
        </form>';
	else
		echo '
        <form action="administer1.php" method="post"><p>
            <input type="submit" value="Back to administration" /></p>
        </form>';
    echo $Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}

// change_psm_leader, i1, i2, i3 code
if (isset($_SESSION['Selected']['k0facility'])) {
    // refresh $_SESSION['Selected'] for security check.
    $Conditions[0] = array('k0facility', '=', $_SESSION['Selected']['k0facility']);
    list($SR, $RR) = $Zfpf->one_shot_select_1s('t0facility', $Conditions);
    if ($RR == 1) // i0n case returns zero.
        $_SESSION['Selected'] = $SR[0];
    $who_is_editing = $Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']);

    // No edit lock because only PSM leader on an app admin can change the PSM leader.
    if (isset($_POST['change_psm_leader_1']) or isset($_GET['change_psm_leader_1'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        echo $Zfpf->xhtml_contents_header_1c('Lookup User');
        $Zfpf->lookup_user_1c('facility_io03.php', 'facility_io03.php', 'change_psm_leader_2', 'facility_o1');
        echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    if (isset($_POST['change_psm_leader_2'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        $TableNameUserEntity = 't0user_facility';
        $Conditions1[0] = array('k0facility', '=', $_SESSION['Selected']['k0facility']);
        $SpecialText = '<p><b>
        Pick PSM Leader</b></p><p>
        The current PSM leader will be replaced by the user you pick above.</p>';
        $Zfpf->lookup_user_wrap_2c(
            $TableNameUserEntity,
            $Conditions1,
            'facility_io03.php', // $SubmitFile
            'facility_io03.php', // $TryAgainFile
            array('k0user'), // $Columns1
            'facility_io03.php', // $CancelFile
            $SpecialText,
            'Assign PSM Leader', // $SpecialSubmitButton
            'change_psm_leader_3', // $SubmitButtonName
            'change_psm_leader_1', // $TryAgainButtonName
            'facility_o1', // $CancelButtonName
            'c5ts_logon_revoked', // $FilterColumnName
            '[Nothing has been recorded in this field.]' // $Filter
        ); // This function echos and exits.
    }
    if (isset($_POST['change_psm_leader_3'])) {
        if (($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader'] and $Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes') or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF or $who_is_editing == '[A new database row is being created.]')
            $Zfpf->send_to_contents_1c(); // Don't eject
        // Check user-input radio-button selection.
        // The user not selecting a radio button is OK in this case.
        if (isset($_POST['Selected'])) {
            $Selected = $Zfpf->post_length_1c('Selected');
            if (isset($_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]))
                $Changes['k0user_of_leader'] = $_SESSION['Scratch']['PlainText']['lookup_user'][$Selected]; // This is the k0user of the newly selected user.
            else
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        }
        unset($_SESSION['Scratch']['PlainText']['lookup_user']);
        if (!isset($Changes['k0user_of_leader']) or $Changes['k0user_of_leader'] == $_SESSION['Selected']['k0user_of_leader']) {
            echo $Zfpf->xhtml_contents_header_1c('Nobody Selected').'<h2>
            You did not select a someone different.</h2>
            <form action="facility_io03.php" method="post"><p>
                <input type="submit" name="change_psm_leader_1" value="Try again" /></p><p>
                <input type="submit" name="facility_o1" value="Back to facility summary" /></p>
            </form>
            '.$Zfpf->xhtml_footer_1c();
            $Zfpf->save_and_exit_1c();
        }
        $Conditions[0] = array('k0facility', '=', $_SESSION['Selected']['k0facility']);
        $ShtmlFormArray['k0user_of_leader'] = $htmlFormArray['k0user_of_leader'];
        $Affected = $Zfpf->one_shot_update_1s('t0facility', $Changes, $Conditions, TRUE, $ShtmlFormArray);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        // Email the current and former PSM leaders and, if applicable, current user, who must be an app admin.
        $AEFullDescription = 'facility '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5name']);
        $FormerLeader = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader']);
        $NewLeader = $Zfpf->user_job_info_1c($Changes['k0user_of_leader']);
        $EmailAddresses = array($FormerLeader['WorkEmail'], $NewLeader['WorkEmail']);
        $DistributionList = '<p>
        <b>Distributed To (if an email address was found):</b><br />
        Former PSM Leader: '.$FormerLeader['NameTitleEmployerWorkEmail'].'<br />
        New PSM Leader: '.$NewLeader['NameTitleEmployerWorkEmail'];
    	$Message = '<p>';
        if ($_SESSION['t0user']['k0user'] != $_SESSION['Selected']['k0user_of_leader']) {
            $CurrentUserAppAdmin = $Zfpf->current_user_info_1c();
            $EmailAddresses[] = $CurrentUserAppAdmin['WorkEmail'];
            $DistributionList .= '<br />
            App admin who changed the PSM leader: '.$CurrentUserAppAdmin['NameTitleEmployerWorkEmail'];
            $Message .= $CurrentUserAppAdmin['NameTitleEmployerWorkEmail'];
        }
        else
            $Message .= $FormerLeader['NameTitleEmployerWorkEmail'];
        $DistributionList .= '</p>';
        $Message .= ' changed the PSM leader for '.$AEFullDescription.'<br/><br/>
        to: '.$NewLeader['NameTitleEmployerWorkEmail'].'</p>';
        $_SESSION['Selected']['k0user_of_leader'] = $Changes['k0user_of_leader'];
        if (isset($_SESSION['StatePicked']['t0facility']))
            $_SESSION['StatePicked']['t0facility']['k0user_of_leader'] = $Changes['k0user_of_leader'];
    	$Subject = 'PSM-CAP: Changed PSM leader for '.$AEFullDescription;
        $Body = $Zfpf->email_body_append_1c($Message, $AEFullDescription, '', $DistributionList);
        $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Done</h2>
        '.$Message;
        if ($EmailSent)
            echo '<p>You and others involved should soon receive an email confirming this.</p>';
        else
            echo '<p>Nobody was notified of this by email perhaps because no relevant email addresses were found. Check that your work email address is recorded in this app.</p>';
        echo '
        <form action="facility_io03.php" method="post"><p>
            <input type="submit" name="facility_o1" value="Back to viewing record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }

    // i1, i2, i3 code
    if ($UserGlobalDBMSPrivileges == LOW_PRIVILEGES_ZFPF or ($who_is_editing != '[A new database row is being created.]' and (!isset($_SESSION['t0user_facility']) or $_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'] or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) != MAX_PRIVILEGES_ZFPF or $UserGlobalDBMSPrivileges != MAX_PRIVILEGES_ZFPF)))
        $Zfpf->send_to_contents_1c(); // Don't eject
    // i1 code
    // HTML input buttons named 'undo_confirm_post_1e' and 'modify_confirm_post_1e' are generated by a function in class ConfirmZfpf.
    // 1.1 $_SESSION['Selected'] is only source of $Display.
    if (isset($_POST['facility_i0n']) or isset($_POST['facility_o1_from'])) {
        $Display = $Zfpf->select_to_display_1e($htmlFormArray, FALSE, FALSE, TRUE);
        // Modify $Display for k0 or app_assigned fields or modify $htmlFormArray to express user roles and privileges.
        $Display['k0user_of_leader'] = $Zfpf->user_job_info_1c($_SESSION['Selected']['k0user_of_leader'])['NameTitleEmployerWorkEmail'];
        if ($_SESSION['Selected']['k0lepc']) {
            $Conditions[0] = array('k0lepc', '=', $_SESSION['Selected']['k0lepc']);
            list($SR, $RR) = $Zfpf->one_shot_select_1s('t0lepc', $Conditions);
            if ($RR != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
            $Display['k0lepc'] = $Zfpf->entity_name_description_1c($SR[0], 100, FALSE); // Don't bold name
        }
        else
            $Display['k0lepc'] = 'None recorded. Add later, when viewing record.';
        if (isset($_POST['facility_o1_from'])) {
            $Zfpf->edit_lock_1c('facility', $Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).' facility summary'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock. Not needed for i1n.
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
                    $UploadResults = $Zfpf->c6bfn_files_upload_1e($Zfpf->user_files_directory_1e(), $c6bfn_array, $K, 'facility_io03.php');
                    // FilesZfpf::6bfn_files_upload_1e updates $_SESSION['Selected'] and the database. 
                    // Or, it echos an error page an user has option to press $_POST['problem_c6bfn_files_upload_1e'] button.
                    $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
                    $SelectDisplay[$K] = $Zfpf->html_uploaded_files_1e($K); // Update the modified select display
                    $_SESSION['Scratch']['SelectDisplay'] = $Zfpf->encode_encrypt_1c($SelectDisplay);
                    $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($Zfpf->post_to_display_1e($htmlFormArray, $Zfpf->decrypt_decode_1c($_SESSION['Post']), $UploadResults['count'], $K));
                    // $_SESSION['Post'] now holds $Display holding $_POST updated with $_SESSION['Selected']['c6bfn_...'] information.
                    $_SESSION['Scratch']['PlainText']['UploadDone'] = TRUE; // Need for initial "user clicked link to this file" above.
                    header("Location: #$K"); // AN ANCHOR MUST BE SET FOR EMAIL upload_files FIELD
                    $Zfpf->save_and_exit_1c();
                }
        }
    }
    if (!$_POST and isset($_SESSION['Post']) and isset($_SESSION['Scratch']['PlainText']['UploadDone'])) {
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // This re-defines $Display after upload_files header() redirect above.
        unset($_SESSION['Scratch']['PlainText']['UploadDone']);
    }
    // END uploads_files special case 1 of 3.
    if (isset($Display) and isset($_SESSION['Scratch']['htmlFormArray'])) { // This is simplification instead of repeating above $_POST cases or nesting within them.
        echo $Zfpf->xhtml_contents_header_1c().'<h1>
		Facility Summary</h1>
        <form action="facility_io03.php" method="post" enctype="multipart/form-data" >'; // upload_files special case 2 of 3. To upload files via PHP, the following form attributes are required: method="post" enctype="multipart/form-data"
        echo $Zfpf->make_html_form_1e($Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']), $Display);
        echo '<p>
		    <input type="submit" name="facility_i2" value="Review what you typed into form" /><br />
		    If you only wanted to upload files, you are done. Click on "Go Back"</p>
		</form>'; // upload_files special case 3 of 3.
		if ($who_is_editing == '[A new database row is being created.]')
		    echo '
            <form action="facility_i0m.php" method="post"><p>
                <input type="submit" value="Back to facilities list" /></p>
            </form>';
		else
    		echo '
		    <form action="facility_io03.php" method="post"><p>
		        <input type="submit" name="facility_o1" value="Back to viewing record" /></p>
		    </form>';
		echo $Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
    // i2 code, implements the review and confirmation HTML page.
    elseif (isset($_POST['facility_i2']) and isset($_SESSION['Scratch']['htmlFormArray']) and isset($_SESSION['Post'])) {
        $htmlFormArray = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['htmlFormArray']);
        $SelectDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $LastDisplay = $Zfpf->decrypt_decode_1c($_SESSION['Post']); // Whitelist from $_SESSION['Post'] for "add fields" cases.
        $PostDisplay = $Zfpf->post_to_display_1e($htmlFormArray, $LastDisplay);
        $_SESSION['Post'] = $Zfpf->encode_encrypt_1c($PostDisplay); // Save user-posted info after checked username and password included.
	    echo $Zfpf->post_select_required_compare_confirm_1e('facility_io03.php', 'facility_io03.php', $htmlFormArray, $PostDisplay, $SelectDisplay);
        $Zfpf->save_and_exit_1c();
    }
    // i3 code
    elseif (isset($_POST['yes_confirm_post_1e'])) {
        if (!isset($_SESSION['Post']))
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
        $ChangedRow = $Zfpf->changes_from_post_1c();
        $DBMSresource = $Zfpf->credentials_connect_instance_1s();
        if ($Zfpf->decrypt_1c($_SESSION['Selected']['c5who_is_editing']) == '[A new database row is being created.]') {
            // Additional security check
            if (!isset($_SESSION['t0user_owner']) or $Zfpf->decrypt_1c($_SESSION['t0user_owner']['c5p_facility']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Zfpf->insert_sql_1s($DBMSresource, 't0facility', $ChangedRow);
            // Insert first facility admin
            $EncryptedMaxPriv = $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF);
            $EncryptedNobody = $Zfpf->encrypt_1c('[Nobody is editing.]');
            $Changes['t0user_facility'] = array(
                'k0user_facility' => time().mt_rand(1000000, 9999999),
                'k0user' => $_SESSION['Selected']['k0user_of_leader'],
                'k0facility' => $_SESSION['Selected']['k0facility'],
                'k0union' => 0,
                'c5p_facility' => $EncryptedMaxPriv,
                'c5p_union' => $EncryptedMaxPriv,
                'c5p_user' => $EncryptedMaxPriv,
                'c5p_process' => $EncryptedMaxPriv,
                'c5who_is_editing' => $EncryptedNobody
            );
            $Changes['t0owner_facility'] = array(
                'k0owner_facility' => time().mt_rand(1000000, 9999999),
                'k0owner' => $_SESSION['t0user_owner']['k0owner'],
                'k0facility' => $_SESSION['Selected']['k0facility'],
                'c5who_is_editing' => $EncryptedNobody
            );
            $Zfpf->insert_sql_1s($DBMSresource, 't0user_facility', $Changes['t0user_facility']);
            $Zfpf->insert_sql_1s($DBMSresource, 't0owner_facility', $Changes['t0owner_facility']);
            // Insert Facility Standard Practices into t0facility_practice and t0user_practice for first facility admin.
            $Conditions[0] = array('c2standardized', '=', 'Facility Standard Practice');
            list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0practice', $Conditions);
            if ($RR > 0) {
                foreach ($SR as $K => $V) {
                    $TemplateFacilityPractice[] = array(
                        'k0facility_practice' => time().$K.mt_rand(1000, 9999), // $K needed because time() may return the same value each pass.
                        'k0facility' => $_SESSION['Selected']['k0facility'],
                        'k0practice' => $V['k0practice'],
                        'c5who_is_editing' => $EncryptedNobody
                    );
                    $TemplateUserPractice[] = array(
                        'k0user_practice' => time().$K.mt_rand(1000, 9999),
                        'k0user' => $_SESSION['t0user']['k0user'],
                        'k0practice' => $V['k0practice'],
                        'k0owner' => 0,
                        'k0contractor' => 0,
                        'k0facility' => $_SESSION['Selected']['k0facility'],
                        'k0process' => 0,
                        'c5p_practice' => $Zfpf->encrypt_1c(MAX_PRIVILEGES_ZFPF),
                        'c5who_is_editing' => $EncryptedNobody
                    );
                }
                foreach ($TemplateFacilityPractice as $V)
                    $Zfpf->insert_sql_1s($DBMSresource, 't0facility_practice', $V);
                foreach ($TemplateUserPractice as $V)
                    $Zfpf->insert_sql_1s($DBMSresource, 't0user_practice', $V);
            }
        }
        else {
            // Additional security check
            if (!isset($_SESSION['t0user_facility']) or $_SESSION['t0user_facility']['k0facility'] != $_SESSION['Selected']['k0facility'] or $Zfpf->decrypt_1c($_SESSION['t0user_facility']['c5p_facility']) != MAX_PRIVILEGES_ZFPF)
                $Zfpf->send_to_contents_1c(); // Don't eject
            $Conditions[0] = array('k0facility', '=', $_SESSION['Selected']['k0facility']);
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0facility', $ChangedRow, $Conditions);
            if ($Affected != 1)
                $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
        }
        foreach ($ChangedRow as $K => $V)
            $_SESSION['Selected'][$K] = $V;
        $Zfpf->close_connection_1s($DBMSresource);
        unset($_SESSION['Post']);
        echo $Zfpf->xhtml_contents_header_1c().'<h2>
        Record(s) Updated</h2><p>
        The facility information you input and reviewed has been recorded.</p>
        <form action="facility_io03.php" method="post"><p>
            <input type="submit" name="facility_o1" value="Back to record" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        $Zfpf->save_and_exit_1c();
    }
} // Ends change_psm_leader, i1, i2, i3 code

$Zfpf->catch_all_1c('contents0.php');

$Zfpf->save_and_exit_1c();

