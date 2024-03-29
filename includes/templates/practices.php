<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// TO DO finish populating practices array. 
// DO NOT CHANGE array keys in or k0practice keys created by this file -- without updating the many references to them.
// Some practices are in other template files, specific to particular circumstances, such as nh3r_hspswp_ep_usa.php
// PROCEDURES AND MAINTENANCE PRACTICES ARE IN SEPARATE TEMPLATE FILES, to allow customization by process type.
// From schema, t0practice:c2standardized allowed values: Allowed values: Owner Standard Practice, Contractor Standard Practice, Facility Standard Practice, Process Standard Practice, or [Nothing has been recorded in this field.]
$EncryptedLowPrivileges = $Zfpf->encrypt_1c(LOW_PRIVILEGES_ZFPF);
$Encrypted_document_i1m_php = $Zfpf->encrypt_1c('document_i1m.php');
$practices = array(
    1 => array( // SPECIAL CASE The action register is almost always available from left-hand contents, so has known primary key hard-coded into ar_i0m.php. Its value is 1.
        'c5name' => $Zfpf->encrypt_1c('Action Register'),
        'c2standardized' => 'Owner Standard Practice', // So, only an owner representative can give a contractor contents-level Action Register access.
        'c5number' => $Zfpf->encrypt_1c('555LLL'), // This should sort before practice number AAAAAA.Otherwise, practices are numbered following the Cheesehead division method.
        'c6description' => $EncryptedNothing, // No need for description, handled by ar_... code.
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('Overall Responsibility and Assigning Responsibilities'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('AAAAAA'),
        'c6description' => $Zfpf->encrypt_1c('The Owner/Operator (aka owner) '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader has overall responsibility for process safety and chemical-accident prevention at all of the owner\'s facilities. The owner '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader may assign responsibilities to others, for example via the contractor, facility, or '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader roles of this PSM-CAP App. Of course, the owner '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader shall ensure that each subordinate leader has the competence to take on their assigned responsibilities. Before an admin ends a leader\'s access via this app to information about an entity they lead, the app provides a reminder to assign a new leader. Options: describe minimum competencies for '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leaders or document their current competencies (experience, training, and so forth) in a practice document here.'),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('Name Changes'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('AAABBB'),
        'c6description' => $Zfpf->encrypt_1c('If someone changes their name, have them update it in this app as follows: left-hand contents > [your name] > Update user record.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ),
    4 => array(
        'c5name' => $Zfpf->encrypt_1c('Job-Title Alterations'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('AAACCC'),
        'c6description' => $Zfpf->encrypt_1c('If a job title is altered, the '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader responsible for changing the title searches through all compliance-practice and procedure descriptions, forms, and so forth and then completes change management if these need revision to match the new job title.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ),
    5 => array(
        'c5name' => $Zfpf->encrypt_1c('Plan of Action'),
        'c2standardized' => 'Facility Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('BBBAAA'),
        'c6description' => $Zfpf->encrypt_1c('The compliance practices described under employee participation, in this PSM-CAP app, are the facility\'s written employee-participation plan. The facility '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader is responsible for employee participation.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ),
    6 => array(
        'c5name' => $Zfpf->encrypt_1c('New Hires'),
        'c2standardized' => 'Facility Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('BBBBBB'),
        'c6description' => $Zfpf->encrypt_1c('During their orientation, all new hires receive training on: (1) the '.HAZSUB_SAFETY_NOTICE_NAME_ZFPF.', (2) the facility Emergency Action Plan, including how to recognize '.HAZSUB_NAME_ADJECTIVE_ZFPF.' leaks and what to do if a leak occurs, and (3) where to find Safety Data Sheets (SDS).'.HAZSUB_DESCRIPTION_ZFPF.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    7 => array( // Has 'ammonia refrigeration' 
        'c5name' => $Zfpf->encrypt_1c('Information Access'),
        'c2standardized' => 'Facility Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('BBBCCC'),
        'c6description' => $Zfpf->encrypt_1c('The facility '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader continuously keeps posted the '.HAZSUB_SAFETY_NOTICE_NAME_ZFPF.', where all employees at the facility have the opportunity see it. View this practice for a sample notice. This notice informs employees about accessing information on process safety, and it provides background on some hazards at the facility and their management. The privileges system in the PSM-CAP App allows owner, contractor, facility, and '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leaders to control access to and editing of information within their responsibility. Computer logs and the app\'s history database table(s) provide records on how each app user may have used these privileges. Employees, neighbors, and others may request information that they don\'t have privileges to view or copy for many good, bad, or misguided reasons. An Owner/Operator representative, responsible for the requested information, handles each such request on a case by case basis, seeking human resources, legal, and security advice as needed, and documents all this via emails or other written records.'),
        'c5require_file' => $Zfpf->encrypt_1c('hs_safety_notice_o1.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    8 => array(
        'c5name' => $Zfpf->encrypt_1c('Consulting on PHA'),
        'c2standardized' => 'Facility Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('BBBDDD'),
        'c6description' => $Zfpf->encrypt_1c('If a process-hazard analysis (PHA) is required, all PHA teams (initial, update, revalidation, amendment, and so forth) include at least one employee (or contractor) who does hands-on operations or maintenance on the '.HAZSUB_PROCESS_NAME_ZFPF.' being evaluated. Or, other steps are/have been taken to consult with employees on "the conduct and development of process hazards analyses". This would not be relevant at facilities where only a checklist hazard review is needed to meet general-duty obligations.'),
        'c5require_file' => $Zfpf->encrypt_1c('pha_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    9 => array(
        'c5name' => $Zfpf->encrypt_1c('Consulting on Process Safety'),
        'c2standardized' => 'Facility Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('BBBEEE'),
        'c6description' => $Zfpf->encrypt_1c('An activity notice is posted for at least 30 days whenever one of the following gets underway: 
(1) process-hazard analyses (for PSM) or hazard reviews (for general-duty only or Program 2 Prevention Programs), including updates or amendments, 
(2) change management, 
(3) incident investigations, or 
(4) compliance audits (for PSM or Program 2 Prevention Programs). 
For these practices, the app can generate an activity notice, with instructions on posting and recording. These activity notices provide employees an opportunity to participate because future process-safety development at the facility almost always occurs via one of the above compliance practices.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ),
    10 => array(
        'c5name' => $Zfpf->encrypt_1c('Consulting on Hazardous-Substance Procedures and Safe-Work Practices, Controls, Equipment, and Unsafe Locations'),
        'c2standardized' => 'Facility Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('BBBFFF'),
        'c6description' => $Zfpf->encrypt_1c('The form that documents training on hazardous-substance procedures and safe-work practices also requests feedback on these procedures and safe-work practices, controls, equipment, and unsafe locations.'),
        'c5require_file' => $Zfpf->encrypt_1c('training_form_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    11 => array(
        'c5name' => $Zfpf->encrypt_1c('Consulting on Training'),
        'c2standardized' => 'Facility Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('BBBGGG'),
        'c6description' => $Zfpf->encrypt_1c('The form that documents training on hazardous-substance procedures and safe-work practices also requests feedback on the frequency of refresher training and suggestions on improving training.'),
        'c5require_file' => $Zfpf->encrypt_1c('training_form_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ), /* 2018-04-11 JDH combined with practice below.
    12 => array(
        'c5name' => $Zfpf->encrypt_1c('New Applicability Determination'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('IIIAAA'),
        'c6description' => $Zfpf->encrypt_1c('Assess if...'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ), */
    13 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability Determination: Start, View, or Edit'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('IIIBBB'),
        'c6description' => $Zfpf->encrypt_1c('Assess if a change requires change management. Start a new determination or view and edit an unapproved one.'),
        'c5require_file' => $Zfpf->encrypt_1c('cm_applies_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    14 => array(
        'c5name' => $Zfpf->encrypt_1c('Change Management System'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('IIICCC'),
        'c6description' => $Zfpf->encrypt_1c('When required, use this to meet all management of change and pre-startup safety review (MOC-PSR) requirements. This Change Management System covers, as needed, describing the change (markup of process-safety information, procedure descriptions, and so forth); design review; environmental, health, and safety review; human resources review; employee participation; PHA amendment; contractor qualification; updating process-safety information; updating hazardous-substance procedures and safe-work practices; updating the maintenance program; updating emergency plans; training on these updates; examination, inspection, and testing of new or altered fabrications (piping, structures, and so forth); special isolation, pump-down, venting, cleaning, tie-in, startup, and so forth procedures; and pre-startup review and approval. Change-management records need to be kept for the life of the '.HAZSUB_PROCESS_NAME_ZFPF.' if they contain documents needed as process-safety information, for example, detailing materials of construction. This app retains them indefinitely, as long as its database is maintained, unless purged per Owner/Operator policies.'),
        'c5require_file' => $Zfpf->encrypt_1c('cms_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ), /*  2018-04-13 JDH combined with practice below.
    15 => array(
        'c5name' => $Zfpf->encrypt_1c('New PHA or other HIRA'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('DDDAAA'),
        'c6description' => $Zfpf->encrypt_1c('Create the first...'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ), */
    16 => array(
        'c5name' => $Zfpf->encrypt_1c('PHA or other HIRA -- View or Create First, Update, and Issue'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('DDDBBB'),
        'c6description' => $Zfpf->encrypt_1c('Process-hazard analysis (PHA) or other hazard identification and risk analysis (HIRA): create the initial one; view, edit, and issue the current working-draft; view the latest issued; or view any previously issued versions. Use to update and revalidate, at least every five years. A PHA or HIRA, initial or amended, shall not be conducted until the design-review documents provide adequate information for the PHA or HIRA, at least for the subsystem being evaluated. See this app\'s Change Management System practice, which covers both initial installation and subsequent changes. Assemble a qualified team and use an allowed method for PHA or HIRA preparation or updates; describe both the team qualifications and methods in the PHA or HIRA report; see the rules associated with this practice.'),
        'c5require_file' => $Zfpf->encrypt_1c('pha_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ), /*  2018-04-13 JDH combined with practice above.
    17 => array(
        'c5name' => $Zfpf->encrypt_1c('Issue PHA or HIRA'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('DDDCCC'),
        'c6description' => $Zfpf->encrypt_1c('To issue the PHA or HIRA, its leader digitally approves a statement verifying that it has been completed per its method. Neither an initial, amended, nor updated and revalidated PHA or HIRA is complete until it has been issued by the its leader. Issuing the PHA or HIRA causes it to be archived, with no revisions allowed, but also creates a copy of the PHA or HIRA, to which future revisions may be made.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ),
    18 => array(
        'c5name' => $Zfpf->encrypt_1c('View An Issued PHA or HIRA'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('DDDDDD'),
        'c6description' => $Zfpf->encrypt_1c('View the "as issued by the PHA or HIRA leader" versions of prior PHA or HIRA reports.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ), */
    19 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution of PHA or HIRA Recommendations -- View Action Register'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('DDDEEE'),
        'c6description' => $Zfpf->encrypt_1c('The action register may be sorted by PHA or HIRA recommendations. It tracks their resolution.'),
        'c5require_file' => $Zfpf->encrypt_1c('ar_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ), /* 2018-10-03 JDH combined with practice below
    20 => array(
        'c5name' => $Zfpf->encrypt_1c('First PSM Compliance Audit'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('KKKAAA'),
        'c6description' => $Zfpf->encrypt_1c('Create the first...'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ), */
    21 => array(
        'c5name' => $Zfpf->encrypt_1c('PSM-Audit Reports -- View, Create, Edit, Issue, and Certify'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('KKKBBB'),
        'c6description' => $Zfpf->encrypt_1c('View draft or issued reports. Create, edit, or issue a report. To meet PSM requirements and good practices, the audit shall be conducted by at least one person knowledgeable in the '.HAZSUB_PROCESS_NAME_ZFPF.'. Describe auditor qualifications and methods in the report. Certify that the Owner/Operator has "evaluated compliance ... to verify that the [PSM] procedures and practices are adequate and are being followed". Once the report has been issued by the report leader, this certification is typically made by the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader, which is how the PSM-CAP App designates the responsible Owner/Operator representative, per the management system. Interpretations vary on what needs to be certified; the certification language in the PSM-CAP App follows the wording in the PSM and CAP rules. The PSM-CAP App retains PSM-audit reports indefinitely, as long as its database is maintained, unless purged per Owner/Operator policies.'),
        'c5require_file' => $Zfpf->encrypt_1c('audit_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ), /*  2018-10-03 JDH combined with practice above.
    22 => array(
        'c5name' => $Zfpf->encrypt_1c('Issue PSM Compliance Audit'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('KKKCCC'),
        'c6description' => $Zfpf->encrypt_1c('To issue a PSM compliance audit, the lead auditor digitally approves a statement verifying that it has been completed per its method. Issuing a PSM compliance audit causes it to be archived. Later, a copy may be created to use as a template for the next audit.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ),
    23 => array(
        'c5name' => $Zfpf->encrypt_1c('View An Issued PSM Compliance Audit'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('KKKDDD'),
        'c6description' => $Zfpf->encrypt_1c('View the "as issued by the lead auditor" version of prior PSM compliance audits.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ), */
    24 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution of Findings -- View Action Register'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('KKKEEE'),
        'c6description' => $Zfpf->encrypt_1c('The action register may be sorted by findings. It tracks resolution of deficiencies reported in findings.'),
        'c5require_file' => $Zfpf->encrypt_1c('ar_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ), /*  2018-10-03 JDH combined with practice 20 above.
    25 => array(
        'c5name' => $Zfpf->encrypt_1c('Certification of "have evaluated... to verify"'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('KKKFFF'),
        'c6description' => $Zfpf->encrypt_1c('This certification...'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ), */
    26 => array(
        'c5name' => $Zfpf->encrypt_1c('48-Hours To Start Investigation and Applicability'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('JJJAAA'),
        'c6description' => $Zfpf->encrypt_1c('The facility Emergency Action Plan describes how to communicate the discovery of a '.HAZSUB_NAME_ADJECTIVE_ZFPF.' leak or an incident (fires, forklift collisions, and so forth) potentially involving the '.HAZSUB_PROCESS_NAME_ZFPF.' -- such as employees notifying their supervisor or others as needed for the managers of the facility and any emergency teams to take needed actions. Once aware of the incident, the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader, or their backup, shall evaluate whether it resulted in, or could reasonably have resulted in, a catastrophic release, which is defined in the glossary. If so, as promptly as possible, but not later than 48 hours following the incident, assign an incident-investigation team leader, assemble a team, and start investigating the incident. The incident-investigation form may also be used to document less-than catastrophic incidents. Option: provide tools for evaluating the size and severity of an incident -- such as quantity-released calculation spreadsheets or simple dispersion modeling. This is optional because it is often hard or impossible to get good input information for these.'),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    27 => array(
        'c5name' => $Zfpf->encrypt_1c('Incident Investigation Form and Records'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('JJJBBB'),
        'c6description' => $Zfpf->encrypt_1c('The incident-investigation team leader documents the investigation using the form linked to this practice. This form provides instructions on the minimum investigation-team qualifications and prompts the team leader to meet the minimum information collection, evaluation, and follow-up requirements.'),
        'c5require_file' => $Zfpf->encrypt_1c('incident_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    28 => array(
        'c5name' => $Zfpf->encrypt_1c('Approval'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('JJJCCC'),
        'c6description' => $Zfpf->encrypt_1c('Once the incident-investigation team leader approves the investigation, the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader reviews the completed incident-investigation form and any documents referenced in it, which together make-up the incident-investigation report. Once any questions and comments are resolved, the '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader approves the incident-investigation report.'),
        'c5require_file' => $Zfpf->encrypt_1c('incident_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    29 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution of Incident-Investigations Recommendations -- View Action Register'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('JJJDDD'),
        'c6description' => $Zfpf->encrypt_1c('The action register may be sorted by incident-investigation recommendations. It tracks their resolution.'),
        'c5require_file' => $Zfpf->encrypt_1c('ar_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    30 => array(
        'c5name' => $Zfpf->encrypt_1c('Employee and Contractor Briefing'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('JJJEEE'),
        'c6description' => $Zfpf->encrypt_1c('The '.HAZSUB_PROCESS_NAME_ZFPF.' '.PROGRAM_LEADER_ADJECTIVE_ZFPF.' leader ensures that each incident-investigation report is reviewed with current employees or contractors whose job tasks are relevant to its conclusions and recommendations. The incident-investigation report form includes fields for describing these employees or contractors and also for uploading the agendas, attendance sheets, or other documentation of these reviews. On integrating recommendations into procedures and training, as needed, so they benefit future employees or contractors, see any Actions included in incident-investigation reports.'),
        'c5require_file' => $Zfpf->encrypt_1c('incident_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    31 => array(
        'c5name' => $Zfpf->encrypt_1c('Retention'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('JJJFFF'),
        'c6description' => $Zfpf->encrypt_1c('This app retains incident-investigation records indefinitely, as long as its database is maintained, unless purged per Owner/Operator policies. The PSM and CAP rules require their retention for at least five years.'),
        'c5require_file' => $Zfpf->encrypt_1c('incident_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    32 => array(
        'c5name' => $Zfpf->encrypt_1c('Initial Training'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('FFFAAA'),
        'c6description' => $Zfpf->encrypt_1c('Before an employee is assigned new job tasks involving a hazardous substance, train them on the hazardous-substance procedures and safe-work practices relevant to these job tasks, and document that they understood the training. Also, at least once, document that the employee understood a training on an overview of the '.HAZSUB_PROCESS_NAME_ZFPF.'.'),
        'c5require_file' => $Zfpf->encrypt_1c('training_form_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    33 => array(
        'c5name' => $Zfpf->encrypt_1c('Refresher Training'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('FFFBBB'),
        'c6description' => $Zfpf->encrypt_1c('Provide refresher training to all employees whose work involves completing hazardous-substance procedures and safe-work practices at least every three years or more frequently if requested by the employee. The training-documentation form asks the employee how often they want refresher training.'),
        'c5require_file' => $Zfpf->encrypt_1c('training_form_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ), /* JDH 2019-12-21 combined with practices 31 and 32 above.
    34 => array(
        'c5name' => $Zfpf->encrypt_1c('Training Documentation'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('FFFCCC'),
        'c6description' => $Zfpf->encrypt_1c('Complete training...'),
        'c5require_file' => $Zfpf->encrypt_1c('training_form_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ), */
    35 => array(
        'c5name' => $Zfpf->encrypt_1c('Identify Affected Contractors'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('GGGAAA'),
        'c6description' => $Zfpf->encrypt_1c('For work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.', prior to awarding work to a contractor for the first time, ensure the contractor completes a qualification record. This typically applies to contractors who: (1) work on the '.HAZSUB_PROCESS_NAME_ZFPF.', including its piping, equipment, supports, supporting structures (such as building structural systems), or foundations, (2) work on ventilation, pressure-relief, suppression, secondary containment, or similar safety systems of/for the '.HAZSUB_PROCESS_NAME_ZFPF.', (3) work on alarms, controls, instrumentation, motors, or other electrical components of/for the '.HAZSUB_PROCESS_NAME_ZFPF.', (4) paint, insulate, clean, or sanitize any of the above, (5) work adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.', particularly if they will be opening bolted or threaded piping joints, performing hot work, moving heavy objects, or doing electrical or controls work, and (6) transfer substances into/from the '.HAZSUB_PROCESS_NAME_ZFPF.'. If their work isn\'t adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.', this typically doesn\'t apply to contractors working on or transferring substances into/from piping systems separated from the '.HAZSUB_PROCESS_NAME_ZFPF.' by a heat exchanger, such as most cooling-water systems (and their water-treatment chemical suppliers) and also secondary heat-transfer systems (glycol, water...)'),
        'c5require_file' => $Zfpf->encrypt_1c('contractor_qual_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    36 => array(
        'c5name' => $Zfpf->encrypt_1c('Contractors with Qualification Records'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('GGGBBB'),
        'c6description' => $Zfpf->encrypt_1c('View, submit, update, or (for the Owner/Operator) document evaluation of contractor-qualification records. Each year, update and re-evaluate theses.'),
        'c5require_file' => $Zfpf->encrypt_1c('contractor_qual_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    37 => array(
        'c5name' => $Zfpf->encrypt_1c('Owner-to-contractor Notices'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('GGGCCC'),
        'c6description' => $Zfpf->encrypt_1c('The Owner/Operator gives notices before a contractor first starts work and about once per year for ongoing work, for contractors\' work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.' (their work), notifying the contractor organization about: (1) known fire, explosion, or toxic release hazards related to their work and the '.HAZSUB_PROCESS_NAME_ZFPF.', (2) applicable portions of the facility Emergency Action Plan, (3) the contractor organization\'s PSM responsibilities related to their work, and (4) any other relevant information.'),
        'c5require_file' => $Zfpf->encrypt_1c('contractor_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    38 => array(
        'c5name' => $Zfpf->encrypt_1c('Contractor-to-owner Notices'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('GGGDDD'),
        'c6description' => $Zfpf->encrypt_1c('Contractor organizations, who perform work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.' (their work), give notices as needed to inform the Owner/Operator about: (1) any unique hazards presented by their work, (2) hazards of any sort discovered at the facility, including by their work, (3) any injuries or illnesses of contractor individuals related to their work, and (4) any other relevant information, including providing Safety Data Sheets (SDS) for any materials the contractor will bring within the facility boundaries.'),
        'c5require_file' => $Zfpf->encrypt_1c('contractor_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    39 => array(
        'c5name' => $Zfpf->encrypt_1c('Entrance and Work Privileges and Records of each Contractor Individual'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('GGGEEE'),
        'c6description' => $Zfpf->encrypt_1c('Check if an individual human working as a contractor (a "contractor individual") has privileges to enter a facility, a particular area or room, or a job site and has privileges to complete specific types of work there. View the training or other records that may justify these entrance and work privileges. Upload training or other records and grant or revoke entrance or work privileges.'),
        'c5require_file' => $Zfpf->encrypt_1c('contractor_priv_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    40 => array(
        'c5name' => $Zfpf->encrypt_1c('Job-Site Audits'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('GGGFFF'),
        'c6description' => $Zfpf->encrypt_1c('Both Owner/Operator and contractor representatives periodically check on contractors while they are working. Document these job-site audits if disciplinary action is taken against a contractor (the organization or an individual); otherwise documenting them is optional.'),
        'c5require_file' => $Zfpf->encrypt_1c('contractor_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    41 => array(
        'c5name' => $Zfpf->encrypt_1c('Injury and Illness Records on Contractor Individuals'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('GGGGGG'),
        'c6description' => $Zfpf->encrypt_1c('For any injuries or illnesses related to a contractor individual\'s work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.' that required medical treatment beyond first aid, the Owner/Operator either makes a record or obtains and checks the quality of the record the contractor made. Often, both Owner/Operator and contractor policies describe their thresholds for injury or illness recordkeeping, which may include near misses, and which may be stored in separate medical-records systems. Unless prohibited by medical-records confidentiality rules, you may use this practice to upload a copy of injury or illness reports to the facility\'s PSM-CAP record for a contractor individual.'),
        'c5require_file' => $Zfpf->encrypt_1c('contractor_priv_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    42 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety Data Sheets'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCAAA'),
        'c6description' => $Zfpf->encrypt_1c('Safety Data Sheets (SDS) applicable to the '.HAZSUB_PROCESS_NAME_ZFPF.' are kept with the other SDS at the facility. 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    43 => array(
        'c5name' => $Zfpf->encrypt_1c('Flow Diagram'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCBBB'),
        'c6description' => $Zfpf->encrypt_1c('This "block flow" or "simplified process flow" diagram summarizes the '.HAZSUB_PROCESS_NAME_ZFPF.'\'s equipment packages and the flows between them. 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    44 => array(
        'c5name' => $Zfpf->encrypt_1c('Process Chemistry'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCCCC'),
        'c6description' => $Zfpf->encrypt_1c('A written summary, the flow diagram, or other documents available to facility employees, taken together, describe: (1) all major substances stored or flowing within the '.HAZSUB_PROCESS_NAME_ZFPF.' and (2) their significant chemical changes, including phase changes (gas condensing to liquid...) and so provide a summary "how it works" or "theory of operation". 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    45 => array(
        'c5name' => $Zfpf->encrypt_1c('Contained-Substances Inventories'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCDDD'),
        'c6description' => $Zfpf->encrypt_1c('These describe the maximum-intended '.HAZSUB_NAME_ADJECTIVE_ZFPF.' quantity inside the '.HAZSUB_PROCESS_NAME_ZFPF.' and the method used to determine this. These "maximum intended" inventories are typically less than the maximum amount that could fit in the '.HAZSUB_PROCESS_NAME_ZFPF.'\'s primary-containment envelope. Instead, they are based on the intended operation of the '.HAZSUB_PROCESS_NAME_ZFPF.' and any co-located '.HAZSUB_NAME_ADJECTIVE_ZFPF.' storage. They may be based on: (1) charging records, including the levels, temperatures, and pressures after charging (not applicable if the process makes hazardous substances), (2) the sum of significant amounts in the '.HAZSUB_PROCESS_NAME_ZFPF.' components at maximum-intended-inventory compositions, levels, temperatures, and pressures, or (3) another method that produces results of similar quality. Options: (A) provide inventory calculations showing the range of normal inventories and also the maximum intended and (B) inventory other, non-hazardous, substances in the '.HAZSUB_PROCESS_NAME_ZFPF.'. 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    46 => array(
        'c5name' => $Zfpf->encrypt_1c('Operating Limits, Deviation Consequences, Controls, Safety Systems, and Corrective Actions'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCEEE'),
        'c6description' => $Zfpf->encrypt_1c('The written descriptions of these cover acceptable ranges for applicable parameters, such as temperatures, pressures, levels, flows, or compositions. They also describe consequences of deviations outside acceptable ranges, in the context of the '.HAZSUB_PROCESS_NAME_ZFPF.' controls, safety systems, and the corrective actions that employees or contractors should take, if trained on procedures covering this. They can serve as a troubleshooting guide, and they may be called "technical operating specifications". They may be referenced by hazardous-substance procedures and safe-work practices or by inspection, testing, or maintenance procedures, which -- along with controls user-interfaces, manufacturers\' manuals, and similar documents -- may contain more details. For additional deviation consequences, see, as applicable, offsite-hazard assessments and also hazard reviews, process-hazard analyses, or other hazard identification and risk analyses. Options: explain the choice of operating limits with references to additional information and describe typical values for operating parameters. 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    47 => array(
        'c5name' => $Zfpf->encrypt_1c('Materials of Construction'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCFFF'),
        'c6description' => $Zfpf->encrypt_1c('The change-management documents, for the initial installation or subsequent changes, detail relevant materials of construction, for example, under design review, process-safety information as-built documents, or construction/fabrication inspection, examination, and testing. See this app\'s Change Management System practice.'),
        'c5require_file' => $Zfpf->encrypt_1c('cms_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    48 => array(
        'c5name' => $Zfpf->encrypt_1c('Piping and Instrument Diagram (P&amp;ID)'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCGGG'),
        'c6description' => $Zfpf->encrypt_1c('In addition to diagrams of piping systems and instrumentation, P&amp;ID often include component tables, with information on vessels, valves, and other equipment packages, such as the P&amp;ID tag, make, model, size, year built, and year installed. 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    49 => array(
        'c5name' => $Zfpf->encrypt_1c('Electrical Classification'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCHHH'),
        'c6description' => $Zfpf->encrypt_1c('Either (1) a practice document here summarizes why the '.HAZSUB_PROCESS_NAME_ZFPF.' causes no rooms or other areas to be classified as hazardous locations under the applicable electrical code or (2) the change-management documents, for the initial installation or subsequent changes, describe any needed hazardous-location electrical classifications, for example, under design review. For background, see the classes, divisions, and groups of NFPA 70, National Electrical Code, a National Fire Protection Association (NFPA) standard, as well as the applicable building and mechanical codes that may reference NFPA 70. 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    50 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety-Systems Details'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCIII'),
        'c6description' => $Zfpf->encrypt_1c('The change-management documents, for the initial installation or subsequent changes, detail relevant safety systems, for example, under design review and process-safety information as-built documents. Safety systems may include pressure-relief, ventilation, interlocks, detection, suppression, or other needed safety systems.'),
        'c5require_file' => $Zfpf->encrypt_1c('cms_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    51 => array(
        'c5name' => $Zfpf->encrypt_1c('Codes and Standards Details'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCJJJJ'),
        'c6description' => $Zfpf->encrypt_1c('The change-management documents, for the initial installation or subsequent changes, describe the applicable codes and standards editions, for example, under design review.'),
        'c5require_file' => $Zfpf->encrypt_1c('cms_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    52 => array(
        'c5name' => $Zfpf->encrypt_1c('Material and Energy Balances'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCKKK'),
        'c6description' => $Zfpf->encrypt_1c('These may not be applicable to storage-only systems. Material balances may not be applicable to closed-loop systems, such as some refrigeration systems. 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    53 => array(
        'c5name' => $Zfpf->encrypt_1c('Good Practices'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCLLL'),
        'c6description' => $Zfpf->encrypt_1c('The following document that equipment in the '.HAZSUB_PROCESS_NAME_ZFPF.' complies with reasonable and prudent good practices, including recognized and generally accepted good engineering practices: 
(1) change-management documents, for the initial installation or subsequent changes, 
(2) process-safety information, 
(3) process-hazard analyses (for PSM) or hazard reviews (for general-duty only or Program 2 Prevention Programs), and 
(4) inspection, testing, and maintenance practices. 
Option: provide additional summaries in a practice document here.'),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    54 => array(
        'c5name' => $Zfpf->encrypt_1c('No Longer in General Use or Original Information No Longer Exists'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('CCCMMM'),
        'c6description' => $Zfpf->encrypt_1c('If either of these apply, address via a practice document here.'),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    55 => array(
        'c5name' => $Zfpf->encrypt_1c('Release Scenarios'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('MMMAAA'),
        'c6description' => $Zfpf->encrypt_1c('Calculate worst-case and, if required, alternative release scenarios. See, as applicable, 40 CFR 68, Subpart B, and EPA guidance for the detailed requirements.
(1) A worst-case release scenario that the EPA may deem reasonable is the release over ten minutes of either the: 
(1.1) maximum-intended '.HAZSUB_NAME_ADJECTIVE_ZFPF.' inventory at the facility or 
(1.2) the capacity of the largest vessel in the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(1.3) whichever is smaller, and, optionally, adjusted for administrative controls, if 40 CFR 68, Subpart B, applies or otherwise 
(1.4) any quantity between (1.1) and, if smaller, (1.2) above, for general-duty only facilities. 
(2) To calculate the capacity of a cylindrical pressure vessel with elliptical heads: 
(2.1) determine its shell plus head-straight-flange length (L), diameter (D), and head elliptical ratio (E) by measuring it or from its shop drawing, 
(2.1.1) the head straight flange is the cylindrical portion of a vessel\'s head, between the shell-to-head weld and where the elliptical potion of the head starts (the head straight flange is also called a head skirt, but avoid confusing it with a support skirt), 
(2.2) sum the cylinder (shell plus head straight flange) and oblate spheroid (the elliptical portion of both heads) volumes, for example pi*((D/2)^2)*L + (4/3)*pi*((D/2)^2)*(D/(E*2)), and 
(2.3) multiply this volume by the density, at reasonable worst-case temperature and pressure, and then by, if applicable, the composition as a mass fraction. 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    56 => array(
        'c5name' => $Zfpf->encrypt_1c('Offsite-Consequences Evaluation'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('MMMBBB'),
        'c6description' => $Zfpf->encrypt_1c('See, as applicable, 40 CFR 68, Subpart B, and EPA guidance for the detailed requirements. 
An offsite-consequences evaluation that the EPA may deem reasonable is: 
(1) distance-to-endpoint(s) output by the EPA\'s RMP*Comp program, for the worst-case, and, if applicable, alternative release scenario(s), 
(2) circle(s) on a map, centered at a likely release point at the facility, showing with reasonable detail the area within the distance-to-endpoint(s), 
(3) residential human population estimate(s) within the distance-to-endpoint(s), 
(3.1) for anywhere in the USA, see the Circular Area Profiles app, posted by the University of Missouri, Missouri Census Data Center, at http://mcdc.missouri.edu/applications/caps.html and 
(4) lists of schools, hospitals, prisons, major commercial, office, or industrial areas, parks, wildlife preserves, wilderness areas, and similar within the distance-to-endpoint(s).
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    57 => array(
        'c5name' => $Zfpf->encrypt_1c('Planning, Reporting, and Updating'),
        'c2standardized' => 'Owner Standard Practice',
        'c5number' => $Zfpf->encrypt_1c('MMMCCC'),
        'c6description' => $Zfpf->encrypt_1c('(1) See the Emergency Action Plan compliance practice for details on coordinating with the community were the facility is located and with offsite emergency responders. 
(2) In the USA, see 40 CFR 68, Subparts B, E, and G, and also the EPA Central Data Exchange (CDX) website at https://cdx.epa.gov for details on offsite consequences, emergency response, Risk Management Plan (RMP), and other reporting and updating. 
(2.1) You may contact the CDX help desk for assistance at https://cdx.epa.gov/help -- they may turn out to be the most helpful help desk you have ever contacted! 
(2.2) Five-year accident history reporting information may be available from records associated with the Incident Investigation Form and Records compliance practice in this PSM-CAP App. 
(3) Retain copies of all submitted documents, including any RMPs, and related communications. 
'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    )
    // If additional practices are added, see TO DO FOR PRODUCTION VERSION comment in includes/templates/nh3r_general_duty.php
);
foreach ($practices as $K => $V) {
    $V['k0practice'] = $K; // Keys match array number above. Keys less than 100000 are reserved for templates (aka standard practices).
    $practices[$K]['k0practice'] = $V['k0practice'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0practice', $V);
}

