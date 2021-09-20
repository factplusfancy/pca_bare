<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This array contains the database schema for the PSM-CAP App
$Schema = array(
    't0session_ids' => array(
        'k0session_ids',
        'k0user',
        'c5session_id' // PHP session_id() output, encrypted.
    ),
    't0history' => array(
        'k0history',
        'k0user', // The primary key of the current user, who made the change. Includes users who made approvals, with c5ts_ or c6nymd_ fields.
        'k0_1st_in_row_affected', // The primary key of the database-table row that was inserted, updated, or deleted (the row affected).
        'k0_2nd_in_row_affected', // Any additional keys in the row affected, like in t0user_owner, a junction table, and so forth.
        'k0_3rd_in_row_affected',
        'k0_4th_in_row_affected',
        'k0_5th_in_row_affected',
        'k0_6th_in_row_affected',
        'k0_7th_in_row_affected',
        'k0_8th_in_row_affected',
        'k0_9th_in_row_affected',
        'k0_10th_in_row_affected',
        'k0_11th_in_row_affected',
        'k0_12th_in_row_affected',
        'k0_13th_in_row_affected',
        'k0_14th_in_row_affected',
        'k0_15th_in_row_affected',
        'k0_16th_in_row_affected',
        'k0_17th_in_row_affected',
        'k0_18th_in_row_affected',
        'k0_19th_in_row_affected',
        'k0_20th_in_row_affected',
        'k0user_of_leader', // Holds k0user_of_leader, or similar. See CoreZfpf::record_in_history_1c
        'c2table_name', // 0 and 2 data types, like k0, k2, and c2, are not encrypted. See CoreZfpf::data_type_1s
        'c2dbms_hostspec', // Useful for merging changes made to a local copy of database while offline (power outage, etc.)
        'c1ts_changed', // Redundant with time() in k0history, in case primary key method changes.
        'c5ntewe_at_time', // CoreZfpf::current_user_info_1c()['NameTitleEmployerWorkEmail'], in case name, title, etc. changes.
        'c6html_form_array', // CoreZfpf::encode_encrypt_1c($htmlFormArray) when user to input the change, see 0read_me_psm_cap_app_standards.txt
        'c6changed_row' // CoreZfpf::encode_encrypt_1c(CoreZfpf::changes_from_post_1c()) or whatever changed.
    ),
    't0user' => array(
        'k0user',
        'k2username_hash', // k2username_hash is not encrypted so SQL select can find it.
        's5password_hash',
        'c5ts_password',
        'c5p_global_dbms', // Allowed values: MAX_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF, and LOW_PRIVILEGES_ZFPF. See CoreZfpf.php function credentials_1s() and /settings/CoreSettingsZfpf.php
        'c5app_admin', // Allowed values: 'Yes' or 'No' (anything but 'Yes', treat as no), encrypted.
        'c5ts_created',
        'c5ts_logon_revoked', // Anything but '[Nothing has been recorded in this field.]' prevents logon.
        'c5ts_last_logon',
        'c5attempts',
        'c6active_sessions', // encoded-encrypted: empty array -- array() -- or an associative array with, keys: session_id(s) and values: session variable(s), for this user current logon(s).
        'c5name_family',
        'c5name_given1',
        'c5name_given2',
        'c5personal_phone_mobile', // Personal email not collected, use work email. Personal mobile phone typically changes less.
        'c5personal_phone_home',
        'c5e_contact_name',
        'c5e_contact_phone',
        'c5challenge_question1',
        's5cq_answer_hash1',
        'c5challenge_question2',
        's5cq_answer_hash2',
        'c5challenge_question3',
        's5cq_answer_hash3',
        'c5who_is_editing'
    ),
    't0user_owner' => array(
        'k0user_owner',
        'k0user',
        'k0owner',
        'c5job_title',
        'c5employee_number',
        'c5work_email',
        'c5work_phone_mobile',
        'c5work_phone_desk',
        'c5work_phone_pager', // TO DO Owner leaders (see t0contractor:k0user_of_leader) should get SIUD on all c5p fields in this table.
        'c5p_owner', // Minimum SIUD. U t0owner and IUD t0practice (owner-wide only, see t0user_practice.) Only app admins can ID t0owner.
        'c5p_user', // Minimum SI. Unlock an owner-associated user, I t0user, IUD t0user_owner, and IUD t0user_practice. This privilege cascades down to t0facility:c5p_user and t0process:c5p_user, via the user_owner, user_facility, and user_process code, so this admin can take owner-wide actions on users. Only users can modify their own t0user personal information, see UserZfpf:user_o1. Only the owner leader can D t0user, with exceptions, see t0owner:k0user_of_leader.
        'c5p_contractor', // Minimum SI. I t0contractor and IUD t0owner_contractor (including approving contractor_qual with practice-SIUD privileges). Only a contractor can UD its t0contractor row.
        'c5p_facility', // Minimum SIUD. IUD t0facility and IUD t0owner_facility.
        'c5who_is_editing'
    ),
    't0owner' => array(
        'k0owner',
        'k0user_of_leader', // Can separate owner-associated users and change their global DBMS privileges. See UserZfpf::user_o1. See CoreZfpf::record_in_history_1c
        'c5name',
        'c6description',
        'c5chief_officer_name', // Optional. The Chief Officer does not need to be a user. So, keeping this up-to-date is not prompted by this App.
        'c5phone',
        'c5street1',
        'c5street2',
        'c5city',
        'c5state_province',
        'c5postal_code',
        'c5country',
        'c5website',
        'c5who_is_editing'
    ),
    't0owner_facility' => array(
        'k0owner_facility',
        'k0owner',
        'k0facility',
        'c5who_is_editing'
    ),
    't0owner_contractor' => array( // Input and output (io) for this table is handled by contractor_io03.php and contractor_qual_io03.php
        'k0owner_contractor',
        'k0owner',
        'k0contractor',
        'c5ts_first_work_awarded',
        'c5ts_qual_evaluated',
        'c6bfn_owner_notices',
        'c6bfn_contractor_notices',
        'c6bfn_job_site_audits',
        'c5who_is_editing'
    ),
    't0user_contractor' => array(
        'k0user_contractor',
        'k0user',
        'k0contractor',
        'c5job_title',
        'c5employee_number',
        'c5work_email',
        'c5work_phone_mobile',
        'c5work_phone_desk',
        'c5work_phone_pager',
        'c5p_contractor', // Minimum SIUD. U t0contractor, U t0owner_contractor:c6bfn_contractor_notices, and IUD t0practice (contractor-wide only, see t0user_practice.) Only owners can ID t0contractor.
        'c5p_user', // Minimum SI. Unlock a contractor-associated user (contractor individual), I t0user, IUD t0user_contractor (if D a user losses access to app, unless user has a different user_contractor or user_owner privilege), and IUD t0user_practice (contractor-wide only). Only users can modify their own t0user personal information, see UserZfpf:user_o1. Only the contractor leader can D t0user, with exceptions, see t0contractor:k0user_of_leader.
        'c6bfn_general_training',
        'c5who_is_editing'
    ),
    't0contractor' => array(
        'k0contractor',
        'k0user_of_leader', // Can terminate contractor-associated users and change their global DBMS privileges, see UserZfpf::user_o1. See CoreZfpf::record_in_history_1c
        'c5name',
        'c6description',
        'c5chief_officer_name',
        'c5phone',
        'c5street1',
        'c5street2',
        'c5city',
        'c5state_province',
        'c5postal_code',
        'c5country',
        'c5website',
        'c5who_is_editing'
    ),
    // There's no t0contractor_qual::c5status below because owner evluation is recorded in t0owner_contractor::c5ts_qual_evaluated
    't0contractor_qual' => array(
		'k0contractor_qual',
		'k0contractor',
		'c5focus',
		'c6prior_work',
		'c6chemicals',
		'c6safety_programs',
		'c6training_method',
		'c6training_eval',
		'c6emr',
		'c6injury_illness',
		'c6bfn_fatalities',
		'c6bfn_law_violations',
		'c5who_is_editing'
	),
    't0union' => array(
        'k0union', // Union or other organization collective-bargaining on behalf of Owner/Operator employees. Consultation with unions representing contractor individuals is not required to be tracked for PSM (Employee Participation), unless the contractor is the operator of the facility.
        'c5name', // Template PSM-CAP App data includes "-None-"; see below.
        'c6description',
        'c5who_is_editing'
    ),
    't0facility_union' => array(
        'k0facility_union',
        'k0facility',
        'k0union',
        'c5who_is_editing'
    ),
    't0user_facility' => array(
        'k0user_facility',
        'k0user',
        'k0facility',
        'k0union', // Allowed values: 0 or the k0union. For each user-facility relationship, it's unlikely for more than one union to be involved.
        'c5p_facility', // Minimum SIUD. 
                        //          U t0facility 
                        //          IU t0lepc. When a t0lepc row is modified, all facilities in its territory (and associated with it via t0facility in an implementation of this app) will see the change, so this assumes a diligent and honest user will only update t0lepc accurately, thus serving others with the same LEPC.
                        //          IUD t0practice (facility-wide only, see t0user_practice.) Only owners can ID t0facility.
        'c5p_union', // Minimum SIUD. IU t0union, which holds just a name and description. When a t0union row is modified, all facilities associated with it via t0facility_union, in an implementation of this app, will see the change, so this assumes a diligent and honest user will only update it accurately, thus serving others dealing with the same union.
        'c5p_user', // Minimum SIUD. IUD t0user_facility and IUD t0user_practice (process-wide, the selected facility-wide, and the selected owner-wide), but only for users associated with the facility's owner (t0user_owner), possibly via a contractor (t0owner_contractor and t0user_contractor).  This privilege cascades down to t0process:c5p_user, via the user_facility and user_process code, so this admin can take facility-wide actions on users.
        'c5p_process',  // Minimum SIUD. IUD t0process and IUD t0facility_process
        'c5who_is_editing'
    ),
    't0contractor_priv' => array( // priv is short for privileges. Editing this controlled by t0user_practice:c5p_practice
        'k0contractor_priv',
        'k0user_facility', // If a user has an entry here, they have facility entrance privileges. These are part of the user-facility relationship. App is design to allow ONLY ONE k0contractor_priv for each k0user_facility.
        'c6bfn_facility_training', // See t0user_contractor::c6bfn_general_training for general contractor-individual training...
        'c6bfn_facility_agreements',
        'c6bfn_injury_records',
        'c5process_priv',
        'c5job_site_priv',
        'c6notes',
        'c5who_is_editing'
    ),
    't0facility' => array(
        'k0facility',
        'k0lepc',
        'k0user_of_leader', // See CoreZfpf::record_in_history_1c
        'c5name',
        'c6description',
        'c5phone',
        'c5street1',
        'c5street2',
        'c5city',
        'c5state_province',
        'c5postal_code',
        'c5county',
        'c5country',
        'c5website',
        'c5latitude',
        'c5longitude',
        'c5lat_long_method',
        'c5lat_long_description',
        'c5lat_long_accuracy',
        'c5lat_long_ref_datum',
        'c5phone_e_24hour',
        'c5email_e', // TO DO emergency-contact roster is tracked via the emergency planing facility practices.
        'c5onsite_fte',
        'c6applicable_rules',
        'c6id_numbers',
        'c6bfn_rmp_as_submitted',
        'c5ts_rmp_last_submitted',
        'c6rmp_submission_history',
        'c6bfn_public_meetings',
        'c5who_is_editing'
    ),
    't0lepc' => array( // Community or local emergency-planning committee (LEPC) or organization
        'k0lepc',
        'c5name',
        'c6description',
        'c5contact_name',
        'c5contact_email',
        'c5phone',
        'c5street1',
        'c5street2',
        'c5city',
        'c5state_province',
        'c5postal_code',
        'c5country',
        'c5website',
        'c5who_is_editing'
    ),
    't0facility_process' => array(
        'k0facility_process',
        'k0facility',
        'k0process',
        'c5who_is_editing'
    ),
    't0user_process' => array(
        'k0user_process',
        'k0user',
        'k0process',
        'c5name', // Optional, the user's PSM-CAP role (NOT the user's name)
        'c6description', // Details on the user's PSM-CAP role
        'c5p_process',  // Minimum SIUD. U t0process and IUD t0practice (process-wide only, see t0user_practice.) Only facility can ID t0process.
        'c5p_user',  // Minimum SIUD. IUD t0user_process, IUD t0user_practice (the selected process-wide, the selected facility-wide, and the selected owner-wide), but only for users associated with the facility.
        'c5who_is_editing'
    ),
    't0process' => array(
        'k0process',
        'k0user_of_leader', // See CoreZfpf::record_in_history_1c
        'c5name',
        'c6description',
        'c5industry_code',
        'c5who_is_editing'
    ),
    't0user_practice' => array(
        'k0user_practice',
        'k0user',
        'k0practice',
        'k0owner', // 0 or k0owner if an owner-wide practice (a t0owner_practice row exists).
        'k0contractor', // 0 or k0contractor if an contractor-wide practice (a t0contractor_practice row exists).
        'k0facility', // 0 or k0facility if an facility-wide practice (a t0facility_practice row exists).
        'k0process', // 0 or k0process if an process-wide practice (a t0process_practice row exists).
        'c5p_practice', // Either None or SIUD.
                        // None allows (1) S practice records (if associated with the practice via entry in this table) and (2) some practices allow a user with at least SI global DBMS privileges (if associated with the practice via entry in this table) to do inserts, such as starting a change-management applicability form but not updates. 
                        // SIUD allows IUD practice records (but not the practice itself). 
                        // STANDARD PRACTICES can only be changed by app admin (or via a new version of the PSM-CAP App) -- instead, add a new user-created practice, which can be edited if one has c5p_owner, c5p_contractor, c5p_facility, or c5p_process SIUD privileges with the entity associated with the practice.
        'c5who_is_editing'
    ),
    't0contractor_practice' => array(
        'k0contractor_practice',
        'k0contractor',
        'k0practice',
        'c5who_is_editing'
    ),
    't0owner_practice' => array(
        'k0owner_practice',
        'k0owner',
        'k0practice',
        'c5who_is_editing'
    ),
    't0facility_practice' => array(
        'k0facility_practice',
        'k0facility',
        'k0practice',
        'c5who_is_editing'
    ),
    't0process_practice' => array(
        'k0process_practice',
        'k0process',
        'k0practice',
        'c5who_is_editing'
    ),
    't0practice' => array(
        'k0practice',
        'c5name',
        'c2standardized', // Exception to encrypting everything to allow easier setup from templates. Allowed values: 'Owner Standard Practice', 'Contractor Standard Practice', 'Facility Standard Practice', 'Process Standard Practice', or '[Nothing has been recorded in this field.]'
        'c5number', // Required. App schema has two of these fields, in tables t0practice and t0fragment_division, so when viewing practices associated with a division or fragment, the practices will always be listed in practice order, but each division can order its fragments differently. These are not meant for user viewing, only sorting. Template PSM-CAP App practices use AAABBB format to allow inserting intermediate "numbers", such as AAABAA, etc.
        'c6description', // Optional, but typically needed.
        'c5require_file', // Optional. The full filename of a file that selecting the practice will execute when selected by a user.
        'c5require_file_privileges', // Required if c5require_file != '[Nothing has been recorded in this field.]' Holds minimum global DBMS privileges needed to complete a practice. Allowed values: MAX_PRIVILEGES_ZFPF, MID_PRIVILEGES_ZFPF, LOW_PRIVILEGES_ZFPF, or (only if no c5require_file) '[Nothing has been recorded in this field.]'
        'c5who_is_editing'
    ),
    't0practice_document' => array(
        'k0practice_document',
        'k0practice',
        'k0document',
        'k0process',
        'c5who_is_editing'
    ),
    't0document' => array(
        'k0document',
        'c5name',
        'c6description',
        'c6bfn_document', // Can hold array of base filenames for multiple documents.
        'c5who_is_editing'
    ),
    't0fragment_practice' => array(
        'k0fragment_practice',
        'k0fragment',
        'k0practice',
        'c5who_is_editing'
    ),
    't0fragment' => array(
        'k0fragment', // Attempt the smallest division of a rule's text that expresses a widely understood idea. Typically one to a few sentences (or a line in a list).
        'c5name', // Required. Informal name for the fragment to use in drop-down menus, etc.
        'c5superseded', // 'Superseded' means the fragment is no longer in effect, for whatever reason. Anything else means it is in effect.
        'c5citation', // Required. This is the legal citation for the fragment. Example: 29 CFR 1910.119(d)(3)(i)(B) or 29 CFR 1910.119(d)(2). You can slice the rule into as many fragments as you like (including an inconsistent mix of sub and sub-sub etc. paragraphs), as long as all the fragments, sorted by citation, produce the complete rule text.
        'c6quote', // Required. This is a fragment of the text of a rule, such as a subparagraph of a regulation. It may include editors' notes, such as differing text from otherwise identical rules (PSM and CAP for instance) or appending a link to a definition. Include end-bits of citations and sub... paragraph headings -- for example "(A) Diagrams..." -- as needed for the fragments to be assemble into the complete rule text.
        'c5source', // Optional. Full reference for quote (publisher, etc.), with notes on any editing.
        'c5who_is_editing'
    ),
    't0fragment_division' => array(
        'k0fragment_division',
        'k0fragment',
        'k0division',
        'c5number',  // Allowed values: 'AAA111', 'AAA222'... 'ZZZ888', 'ZZZ999' or '[Nothing has been recorded in this field.]' -- in which case division_o11.php sorts fragments by c5citation. This allows for custom divisions, like Cheesehead, to assemble fragments from various parts of a rule in a useful order. See comment above under t0practice:c5number.
        'c5who_is_editing'
    ),
    't0practice_division' => array(
        'k0practice_division',
        'k0practice',
        'k0division',
        'c5who_is_editing'
    ),
    't0division' => array(
        'k0division',
        'k0rule',
        'c5name', // Required. Example: Process Safety Information
        'c5citation', // Optional. Example: 29 CFR 1910.119(d)
        'c5who_is_editing'
    ),
    't0rule' => array(
        'k0rule',
        'c5name', // Required. Example: Process Safety Management (PSM) Standard
        'c5citation', // Example: 29 CFR 1910.119
        'c5who_is_editing'
    ),
    't0fragment_guidance' => array(
        'k0fragment_guidance',
        'k0fragment',
        'k0guidance',
        'c5who_is_editing'
    ),
    't0guidance' => array(
        'k0guidance',
        'c5name',
        'c5superseded', // 'Superseded' means no longer in effect, for whatever reason. Anything else means it is in effect.
        'c6quote',
        'c6source',
        'c5who_is_editing'
    ),
    't0change_management' => array(
        'k0change_management',
        'c5name',
        'c6description',
        'c5affected_entity', // required -- allowed values: '[Nothing has been recorded in this field.]', 'Contractor-wide', 'Owner-wide', 'Facility-wide', 'Process-wide'
        'k0affected_entity', // 0 or k0 of the affected entity (k0contractor, k0owner, k0facility, k0process)
        'k0user_of_initiator', // k0user of the user who completed the change-management applicability form (or a replacement initiator).
        'c6cm_applies_checks', // Encoded array for checkboxes.
        'k0user_of_applic_approver', // IMPORTANT APPROVAL by AELeader. 0 before approval. After approval, the k0user of the affected entity (process, facility, or owner) leader who approved this.
        'c5ts_applic_approver',
        'c6nymd_applic_approver',
        'k0user_of_project_manager', // 0 or k0user once assigned -- this and all but k0user_of_psr below are optional.
        'c5duration',
        'c5reason',
        'c6bfn_markup',
        'k0user_of_dr', // 0 or k0user of design reviewer once assigned
        'c5ts_dr_requested',
        'c5ts_dr',
        'c6nymd_dr',
        'c6notes_dr', // Text for notes, such as: Not Applicable, target date, completed date, or even the agreement text, etc.
        'c6bfn_dr', // Path to any supporting documents.
        'k0user_of_ehsr', // 0 or k0user of environmental, health, and safety (EHS) reviewer
        'c5ts_ehsr_requested',
        'c5ts_ehsr',
        'c6nymd_ehsr',
        'c6notes_ehsr',
        'c6bfn_ehsr',
        'k0user_of_hrr', // 0 or k0user of Human Resources (HR) reviewer
        'c5ts_hrr_requested',
        'c5ts_hrr',
        'c6nymd_hrr',
        'c6notes_hrr',
        'c6bfn_hrr',
        'k0user_of_cont_qual', // 0 or k0user of person responsible for assuring and documenting project contractor qualifications.
        'c5ts_cont_qual_requested',
        'c5ts_cont_qual',
        'c6nymd_cont_qual',
        'c6notes_cont_qual',
        'c6bfn_cont_qual',
        'k0user_of_act_notice', // 0 or k0user of person responsible for posting and filing Activity Notice.
        'c5ts_act_notice_requested',
        'c5ts_act_notice',
        'c6nymd_act_notice',
        'c6notes_act_notice',
        'c6bfn_act_notice',
        'k0user_of_psi', // 0 or k0user of person responsible for Process Safety Information (PSI) updates
        'c5ts_psi_requested',
        'c5ts_psi',
        'c6nymd_psi',
        'c6notes_psi',
        'c6bfn_psi',
        'k0user_of_pha_amend', // 0 or k0user of person responsible for Process Hazard Analysis (PHA) amendments, if needed.
        'c5ts_pha_amend_requested',
        'c5ts_pha_amend',
        'c6nymd_pha_amend',
        'c6notes_pha_amend',
        'c6bfn_pha_amend',
        'k0user_of_hs_omp_swp', // 0 or k0user of person responsible for hazardous substance (HS) operating and maintenance procedure (OMP) and Safe Work Practices (SWP) updates
        'c5ts_hs_omp_swp_requested',
        'c5ts_hs_omp_swp',
        'c6nymd_hs_omp_swp',
        'c6notes_hs_omp_swp',
        'c6bfn_hs_omp_swp',
        'k0user_of_training', // 0 or k0user of person responsible for training related to the change, on HS OMP and SWP updates, controls, etc.
        'c5ts_training_requested',
        'c5ts_training',
        'c6nymd_training',
        'c6notes_training',
        'c6bfn_training',
        'k0user_of_hs_pm', // 0 or k0user of person responsible for maintenance scheduling and record keeping updates.
        'c5ts_hs_pm_requested',
        'c5ts_hs_pm',
        'c6nymd_hs_pm',
        'c6notes_hs_pm',
        'c6bfn_hs_pm',
        'k0user_of_emergency', // 0 or k0user of person responsible for emergency planning, training, etc. updates
        'c5ts_emergency_requested',
        'c5ts_emergency',
        'c6nymd_emergency',
        'c6notes_emergency',
        'c6bfn_emergency',
        'k0user_of_iet', // 0 or k0user of person responsible for construction/fabrication inspection, examination, and testing (IET).
        'c5ts_iet_requested',
        'c5ts_iet',
        'c6nymd_iet',
        'c6notes_iet',
        'c6bfn_iet',
        'k0user_of_implement', // 0 or k0user of person responsible for special isolation, pump-down, venting, cleaning, tie-in, startup, etc. implementation.
        'c5ts_implement_requested',
        'c5ts_implement',
        'c6nymd_implement',
        'c6notes_implement',
        'c6bfn_implement',
        'k0user_of_psr', // IMPORTANT APPROVAL by AELeader. 0 before approval. After pre-startup approval, k0user of AELeader responsible for pre-startup safety review, which includes resolving recommendations from, and overall responsibility for, all of above optional reviews.
        'c5ts_psr_requested',
        'c5ts_psr',
        'c6nymd_psr',
        'c6notes_psr',
        'c6bfn_psr',
        'c5who_is_editing'
    ),
    't0pha' => array(
        'k0pha', // Less than 100000 for templates. See includes/pha_i1m.php
        'k0process', // 0 (for templates) or the k0process of the associated process.  Must associated with a process.
        'c6bfn_act_notice',
        'c6team_qualifications',
        'c6bfn_attendance',
        'c6background', // Can include revision and amendment history here.
        'c6method',
        'c6prior_incident_id',
        'c6bfn_pha_as_issued', // PHAs need to be kept for life of the process, so save an extra "as issued" copy here. In addition to copies email and in database.
        'k0user_of_leader', // 0 for templates. The PHA or HIRA team leader, which is initially set to the user who created the PHA or HIRA via pha_i1n.php. See CoreZfpf::record_in_history_1c
        'c5ts_leader', // '[Nothing has been recorded in this field.]', the time stamp when issued by team leader, or special cases, like superseded and archived drafts.
        'c6nymd_leader', // Allowed values: 
                         // - the name of a template PHA (templates are never issued),
                         // - before issuing: * the source-template k0pha or
                         //                   * if created from scratch, '[Nothing has been recorded in this field.]', or 
                         // - the digital-approval by team leader, indicating "completed per method".
        'c5who_is_editing'
    ),
    't0subprocess' => array( // These are called subsystems in the app text.
        'k0subprocess',
        'k0pha',
        'c5name', // Required.
        'c5who_is_editing'
    ),
    't0scenario' => array(
        'k0scenario',
        'k0subprocess',
        'c5name', // Required. This may be the short description of a "what if" scenario.
        'c5type', // Required. For allowed values, see /includes/ccsaZfpf.php.
        'c5severity', // Required.   // The PHA team shall assess the severity and likelihood, which are often site and process specific.
        'c5likelihood', // Required. // So, '[Nothing has been recorded in this field.]' indicates that the PHA team has not yet assessed these.
        'c5who_is_editing'
    ),
    't0scenario_cause' => array(
        'k0scenario_cause',
        'k0scenario',
        'k0cause',
        'c5who_is_editing'
    ),
    't0cause' => array(
        'k0cause',
        'c5name', // required
        'c6description', // optional
        'c5who_is_editing'
    ),
    't0scenario_consequence' => array(
        'k0scenario_consequence',
        'k0scenario',
        'k0consequence',
        'c5who_is_editing'
    ),
    't0consequence' => array(
        'k0consequence',
        'c5name', // required
        'c6description', // optional
        'c5who_is_editing'
    ),
    't0scenario_safeguard' => array(
        'k0scenario_safeguard',
        'k0scenario',
        'k0safeguard',
        'c5who_is_editing'
    ),
    't0safeguard' => array( // A safeguard, if independent from other safeguards, may be called an independent protection layer (IPL)
        'k0safeguard',
        'c5name', // required
        'c5hierarchy', // required -- Hierarchy of Controls/Safeguards Type: 'Not Applicable or Multiple', 'Elimination', 'Substitution', 'Inventory Reduction', 'Engineering: Improves Primary-Containment Envelope', 'Engineering: Improves Instrumentation, Controls, or Machinery Reliability', 'Engineering: Greater Separation', 'Engineering: Secondary Containment or Release Treatment', 'Administrative', 'Personal-Protective Equipment (PPE)'
        'c6description', // optional
        'c5who_is_editing'
    ),
    't0scenario_action' => array(
        'k0scenario_action',
        'k0scenario',
        'k0action',
        'c5who_is_editing'
    ),
    't0action' => array( // This is the action register (PHP files ar_).
        'k0action',
        'c5name', // required (a very short summary).
        'c5status', // required -- allowed values: 
                    //      'Draft proposed action'
                    //      'Needs resolution' // For example, when the document recommending this action received final approval.
                    //      'Marked resolved'
                    //      'Closed'
                    // When 'Draft proposed action', only display in draft recommending report, not in action register. 
                    // For all other cases, display in action register, so other documents can reference (but not edit) this action.
        'c5priority', // required -- allowed values: the ...PRIORITY_ZFPF constants in .../settings/CoreSettingsZfpf.php.
                    // See also CoreZfpf::risk_rank_1c in .../includes/CoreZfpf.php. 
                    // For PSM compliance audits, incident investigations, and unassociated actions, see $PriorityRadioButtons in arZfpf.php
        'c5affected_entity', // required -- allowed values: '[Nothing has been recorded in this field.]', 'Owner-wide', 'Facility-wide', 'Process-wide'
        'k0affected_entity', // 0 or k0 of the affected entity (k0owner, k0facility, k0process)
        'c6deficiency', // required only for compliance audits, optional for others.
        'c6details', // optional. May include resolution options.
        // Only allow editing above fields before action is opened, to preserve integrity of recommending report.
        'c5ts_target', // the target resolution time and date
        'k0user_of_leader', // 0 or a k0user once resolution assigned to someone. See CoreZfpf::record_in_history_1c
        'c5ts_leader',
        'c6nymd_leader',
        'c6notes', // Resolution method, modifications before resolution (contesting observations or deficiencies, etc.), or other notes.
        'c6bfn_supporting', // supporting documents.
        'k0user_of_ae_leader', // See c5status:
                                // associated means recommended or required by a document (such as a PHA, audit, or investigation)
                                // -2 while 'Draft proposed action' associated,
                                // -1 while 'Draft proposed action' unassociated,
                                //  0 while open ('Needs resolution')
                                //  1 when 'Approved by resolution assigned to person', and the
                                //  k0user of the AELeader once 'Resolution approved by owner' (by AELeader on behalf of owner.)
        'c5ts_ae_leader',
        'c6nymd_ae_leader',
        'c5who_is_editing'
    ),
    't0obsresult_action' => array( // Must be t0obsresult_action, cannot be reverse order, for ccsaZfpf::scenario_CCSA_Zfpf to work.
        'k0obsresult_action',
        'k0obsresult',
        'k0action',
        'c5who_is_editing'
    ),
    't0obstopic' => array( // obs stands for observation.
        'k0obstopic', // Less than 100000 for templates.
        'c5name', // The topic, place where, or thing about which observations will be made.
        'c5who_is_editing'
    ),
    't0obstopic_obsmethod' => array( // Check many things in each spot and some things get checked in many spots.
        'k0obstopic_obsmethod',
        'k0obstopic',
        'k0obsmethod',
        'c5who_is_editing'
    ),
    't0obsmethod' => array(
        'k0obsmethod', // Less than 100000 for templates.
        'c6obsmethod',
        'c5who_is_editing'
    ),
    't0audit_fragment_obsmethod' => array( // Mostly templates: link observation methods to rule fragments, for which they verify compliance.
        'k0audit_fragment_obsmethod',
        'k0audit_fragment',
        'k0obsmethod',
        'c5who_is_editing'
    ),
    't0obsresult' => array( // No "by who" and "when" fields below, they are covered by history table.
        'k0obsresult', // Less than 100000 for templates.
        'k0audit',
        'k0obsmethod',
        'c5_obstopic_id', // observation object unique identifier (object ID), see app file: glossary.php
        // In other contexts, such as a maintenance management system, there would typically be a many-to-many relationship between
        // an object ID and the observation results about it, such as to track the object, like a compressor, over time.
        // But, for an infrequently and often uniquely completed audit, having an object ID for each observation result works.
        'c6obsmethod_as_done', // t0obsmethod:c6obsmethod may change, so record here how actually done for the recorded c6obsresult.
        'c6obsresult', // Result of c6obsmethod_as_done, the observation method "as done".
        'c6bfn_supporting', // supporting documents, photos...
        'c5who_is_editing'
    ),
    't0audit_fragment' => array(
        'k0audit_fragment',
        'k0audit',
        'k0fragment',
        'c5who_is_editing'
    ),
    't0audit_obstopic' => array( // This links an audit to topics relevant to the audited process and facility.
        'k0audit_obstopic',
        'k0audit',
        'k0obstopic',
        'c5who_is_editing'
    ),
    't0audit' => array(
        'k0audit', // Less than 100000 for templates. See includes/audit_i1m.php
        'k0process', // Must be associated with a process, except templates, where k0process == 0.
        'c5name', // Best if Title Case
        'c5ts_as_of',
        'k0user_of_leader', // The lead auditor k0 user, set by i0n to current user. See CoreZfpf::record_in_history_1c
        'c6bfn_act_notice',
        'c5ts_leader',
        'c6nymd_leader', // This holds, encrypted, for non-template audits: the source-template k0audit until first issued.
        'k0user_of_certifier',  // 0 while draft, 1 when issued by lead auditor, or k0user of certifier. See includes/templates/practices.php $Practice[20] for certifier meaning.
                             // Only allow one draft audit per process. Once issued by lead auditor, audit cannot be edited, unless auditor-approval canceled.
                             // Certification here is an additional ower/operator step, but the auditor remains responsible for the audit and report quality.
        'c5ts_certifier',
        'c6nymd_certifier', // SPECIAL CASE: once approved this holds full certification "approval text", including nymd.
        'c6howtoinstructions', // Instructions to the facility on audit certification, resolution, retention, and next-audit deadline.
        'c6background',
        'c6audit_scope',
        'c6audit_method',
        'c6auditor_qualifications', // Name(s), title(s), organizations(s), and qualifications of auditor(s).
        'c6bfn_auditor_notes', // General notes, entrance and exit meeting attendance lists... Details in t0obsresult:c6obsmethod_as_done
        'c6suggestions', // Additional suggestions, which the auditor does not think are required by codes or regulations. For example, these may include suggestions to facilitate implementation or to consider exceeding specific requirements.
        'c5who_is_editing'
    ),
    't0incident' => array(
        'k0incident',
	    'k0process', // The pcm app requires all incident investigations to be associated with a process, so -- unlike t0change_management no need for k0affected_entity,
        'k0user_of_leader', // Always a k0user, set to current user via i0n code. AELeader may change later. See CoreZfpf::record_in_history_1c
        'c5name',
        'c5status', // Allowed values: "draft", "team-leader approved", "owner approved"
	    'c5ts_incident_start',
	    'c5ts_incident_end',
	    'c5ts_investigation_start',
	    'c5leader_qualifications',
	    'c6other_team_members',
        'c6bfn_act_notice',
	    'c6incident_location',
	    'c5severity_summary',
	    'c6summary_description',
	    'c5state_of_leaked_hs',
	    'c6mechanism_leaked',
	    'c6circumstances',
	    'c6events_actions',
	    'c6loss_damage',
	    'c6proximate_cause',
	    'c6mitigating',
	    'c6root_cause',
	    'c6bfn_supporting',
	    'c6affected_personel',
	    'c6bfn_review_attendance', // This is for documenting the review with affected personnel, which is a separate practice.
        'c5ts_leader',
        'c6nymd_leader',
        'k0user_of_ae_leader', // 0 while draft, 1 when approved by team leader, and k0user of process leader, the affected-entity (AE) leader, once approved.
        'c5ts_ae_leader',
        'c6nymd_ae_leader',
        'c5who_is_editing'
    ),
    't0incident_action' => array(
        'k0incident_action', // Allows many-to-many relationship between incidents and actions, modeled off scenario_action.
        'k0incident',
        'k0action',
        'c5who_is_editing'
    ),
    't0certify' => array( // General table for certifications liked to a process. Use for hazardous-substance procedures and safe-work practices "current and accurate" certification.
        'k0certify',
        'k0process',
        'k0user_of_ae_leader', // 0 while draft and k0user of process leader, the affected-entity (AE) leader, once approved.
        'c5ts_ae_leader', // TO DO Use to trigger the reminder emails
        'c6nymd_ae_leader',
        'c6bfn_as_certified',
        'c5who_is_editing'
    ),
    't0training_form' => array(
        'k0training_form',
        'k0process',
        'k0user_of_trainee',
        'k0user_of_instructor',
        'c5status', // 'draft', 'trainee approved', 'instructor approved'
        'c5ts_completed', // TO DO Use to trigger the reminder emails
        'c5hours',
        'c5hazards_overview', // yes no
        'c6procedures_swp',
        'c5injuries_illness',
        'c5suggestions',
        'c5difficulty',
        'c5unsafe_locations',
        'c6more_details',
        'c5refresher_training', // Drop down: no, every 2 years, every year, every 6 months
        'c5ts_trainee',
        'c6nymd_trainee',
	    'c6training_type',
	    'c6test_method',
    	'c6bfn_supporting',
        'c5ts_instructor',
        'c6nymd_instructor',
        'c5ts_reminder_email', // Time the reminder email was last sent. Send reminder emails monthly starting 6-months from deadline until resolved.
        'c5who_is_editing'
    )
   // TO DO update who_is_editing_unlock.php if adding tables to schema.
);

