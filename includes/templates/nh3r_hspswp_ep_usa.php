<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file populates t0practices with the ammonia-refrigeration templates below for 
// hazardous-substance procedures and safe-work practices (nh3hspswp) and emergency planning (ep), tailored for rules in the USA.
// Inserted as: Process Standard Practice
// along with t0process_practice, t0practice_division, and t0fragment_practice rows.

// The following variables must be defined by the requiring file.
//    $DBMSresource
//    $Zfpf
//    $EncryptedNothing
//    $EncryptedNobody
//    $EncryptedLowPrivileges
//    $Encrypted_document_i1m_php

$practices = array( // Indent as done below for better display in HTML text areas and easier cut and paste to other documents. New lines help with tracking changes via git.
    0 => array(
        'c5name' => $Zfpf->encrypt_1c('Documenting Procedures People Follow'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEAAA'),
        'c6description' => $Zfpf->encrypt_1c(' (1) The written descriptions of hazardous-substance procedures and safe-work practices focus on tasks people do that promptly affect the '.HAZSUB_PROCESS_NAME_ZFPF.'. They are tasks where a mistake could promptly cause harm or unacceptable risks, including:
(1.1) opening or shutting a valve in '.HAZSUB_NAME_ADJECTIVE_ZFPF.' service by any method (touching a control panel, turning a valve stem by hand, and so forth), 
(1.2) monitoring (typically including inspections done weekly or more often), 
(1.3) corrective actions (fixes) or other goal-driven adjustments (optimizing), 
(1.4) adding/removing materials to/from the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(1.5) removing hazardous energy from some or all of it, piping opening, and returning piping to service, and 
(1.6) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' safety-system testing or disabling for maintenance. 
(2) The hazardous-substance procedures and safe-work practices called for by the PSM-CAP compliance practices below (in this PSM-CAP App division) are intended to meet the "operating" procedure requirements of the PSM and CAP rules, though most automated processes no longer require the minute-by-minute human operations that this term invokes and, in special cases when the intrinsic or automated operation of processes makes operating procedures unnecessary, neither rule requires them, such as at OSHA\'s normally unoccupied remote facilities or under EPA\'s Program 1 Prevention Program. 
(3) The hazardous-substance procedures and safe-work practices don\'t need to and may not cover: 
(3.1) the intrinsic or automated operation of systems, equipment, and controls -- including "how it works", "theory of operation", and "technical operating specifications" -- which are primarily covered in the process-safety information and which may be referenced by these procedures for people to follow, 
(3.2) tasks done to piping or equipment (the work object) after completing the hazardous-substance procedures and safe-work practices needed to 
(3.2.1) isolate the work object from the rest of the '.HAZSUB_PROCESS_NAME_ZFPF.' and 
(3.2.2) remove hazardous substances and energy from the work object, 
(3.3) inspection, testing, and maintenance (ITM) that can be done without opening the primary-containment envelop of the '.HAZSUB_PROCESS_NAME_ZFPF.' and without creating unacceptable risks. 
Items (3.1) and (3.2) above are covered under the "Inspection, testing, and maintenance (ITM) for safe operation and mechanical integrity" division of this PSM-CAP App. 
Option: provide more details in a practice document here.'),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('Employee and Contractor Access to Information They Need'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEBBB'),
        'c6description' => $Zfpf->encrypt_1c('This app gives access to documents associated with a practice when a user is given "view" privileges for the practice. Option: describe where any paper copies of documents are kept for employees or contractors, in a practice document here.'),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('Security and Access Control'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEECCC'),
        'c6description' => $Zfpf->encrypt_1c('These may range from "authorized personnel only" signs and locked doors to surveillance, fences, and so forth, as the Owner/Operator deems prudent for the circumstances or as required by other authorities or rules. See also the emergency planning sections of this app.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('Current and Accurate Certification'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEDDD'),
        'c6description' => $Zfpf->encrypt_1c('This practice provides a form you may use to meet the "certify annually that these operating procedures are current and accurate" requirement. The form has instructions for verifying this.'),
        'c5require_file' => $Zfpf->encrypt_1c('hspswp_cert_i1m.php'),
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    4 => array(
        'c5name' => $Zfpf->encrypt_1c('Daily Inspection'),
        'c2standardized' => '[Nothing has been recorded in this field.]', // c2 not encrypted, these are treated as custom process-wide practices, associated with the process selected when its leader installs these.
        'c5number' => $Zfpf->encrypt_1c('EEEEEE'), // See templates/divisions.php for practice numbering system.
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions (may be checklist with instructions) covering: 
(1) check 
    (1A) access to, 
    (1B) egress from, 
    (1C) lighting in, 
    (1D) housekeeping of, 
    (1E) heat or cold unsafe for people or equipment in, and 
    (1F) other hazards in 
all areas containing the piping and equipment to be inspected, such as rooms, roofs, and platforms -- 
(1.1) if an area appears unsafe, don\'t enter (or leave immediately), 
(1.2) immediately report any hazards, including access or egress problems, and 
(1.3) report or fix any lighting or housekeeping problems, 
(2) inspect/note/fix, as applicable, the '.HAZSUB_PROCESS_NAME_ZFPF.' 
    (2A) compressors, 
    (2B) pumps (including '.HAZSUB_NAME_ADJECTIVE_ZFPF.' pumps, secondary heat-transfer liquid pumps, and cooling-water pumps), 
    (2C) condensers, 
    (2D) automatic purgers, 
    (2E) transfer systems, and 
    (2F) their nearby piping and vessels 
for, if applicable -- 
(2.1) ammonia, oil, or secondary heat-transfer liquid leaks, 
(2.2) excessive oil-drip rate from shaft seals, 
(2.3) too hot or too cold, 
(2.4) excessive vibrations, 
(2.5) odd sounds, 
(2.6) rotating wrong way, 
(2.7) "not working right" (including alarms), 
(2.8) guard missing (for drives, fans...), 
(2.9) air-flow obstructions (near fans, air-cooled motors...), 
(2.10) excessive ice buildup 
(2.11) heaters (including heat tape...) not working when needed, such as when below-freezing conditions might occur, 
(2.12) foaming, cloudiness, or odd color in 
(2.12.1) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' sight glasses, 
(2.12.2) lubricating-oil sight glasses, 
(2.12.3) cooling water, or 
(2.12.4) secondary heat-transfer liquids, 
(2.13) inadequate flow of cooling water or secondary heat-transfer liquids, 
(2.14) oil-draining needed, based on frost line or poor cooling, 
(2.15) new exterior damage, such as cracks, dents, scratches, or impact marks, 
(2.16) pressure-relief devices 
(2.16.1) chattering, 
(2.16.2) frosted (possible leak-by), 
(2.16.3) visibly tampered with, broken ASME seal, or damaged, or 
(2.16.4) that have opened at some time in the past, if equipped with an indicator, such as the device\'s LED indicator, a flow flag, or a gauge between a rupture disk and a pressure-relief valve, or 
(2.17) maintenance needed (based on run-time hours or condition), 
(3) inspect/note/fix any automatic purger for -- 
(3.1) water level not at its normal fill mark in its water-bubble column or 
(3.2) ammonia odors coming from the drain where water flows, 
(4) inspect/note/fix and (if not logged by controls) record a quantitative value for, if applicable, the -- 
(4.1) compressor 
(4.1.1) suction pressure(s), 
(4.1.2) discharge pressure(s), 
(4.1.3) discharge temperature(s), 
(4.1.4) oil temperature(s), 
(4.1.5) oil level(s), typically in bullseye sight glasses,
(4.1.6) oil-filter differential pressure(s), 
(4.1.7) motor amps, 
(4.2) oil levels in any oil reservoirs for pumps, such as the indicator rod\'s position, 
(4.3) automatic purger counts, 
(4.4) transfer-system counts, 
(4.5) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' pump discharge pressure(s), 
(4.6) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' levels in major vessels, 
(4.7) temperatures of things that need to be cooled (or heated), as needed for production quality, such as 
(4.7.1) room air, 
(4.7.2) secondary heat-transfer liquids, 
(4.7.3) raw materials, or 
(4.7.4) products, and 
(5) acceptable ranges and shutoff methods, if not covered by the Operating Limits, Deviation Consequences, Controls, Safety Systems, and Corrective Actions document(s), in enough detail for a qualified daily inspector to decide when and how to -- 
(5.1) call in assistance, such as a supervisor or a qualified '.HAZSUB_PROCESS_NAME_ZFPF.' contractor, and 
(5.2) if safe, shut off equipment, for example by pressing the correct "off" button (but without having to hand-turn valve stems). 
* The above tasks may be automated or done less often based on operating experience and judgment, but document the justification for any frequency reductions. 
Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    5 => array(
        'c5name' => $Zfpf->encrypt_1c('Weekly Inspection'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEFFF'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions for inspecting all '.HAZSUB_PROCESS_NAME_ZFPF.' piping and equipment not covered by the Daily Inspection, for similar things and with similar short-term corrective actions -- call in assistance and simple shutoff, if needed. Of course, use judgment and operating experience to select inspection frequency, such as each shift, daily, weekly, or monthly, but document the basis for reductions -- such as better sensors, controls, and automation -- from the somewhat old-school suggested frequencies here. Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    6 => array(
        'c5name' => $Zfpf->encrypt_1c('Low Load Begin and End'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEGGG'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions covering any actions that employees or contractors routinely need to complete when the '.HAZSUB_PROCESS_NAME_ZFPF.' needs to run at reduced capacity, such as during weekends or off seasons. This may be fully automated. Or, it may include things like turning on/off/auto switches for compressors or pumps, including pumps for secondary coolants. It may include idling the entire '.HAZSUB_PROCESS_NAME_ZFPF.' or parts of it. Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    7 => array(
        'c5name' => $Zfpf->encrypt_1c('Leak Mitigation and Emergency Shutdown'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEHHH'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions for assessing small to catastrophic incidents, including leaks, fires, and explosions involving the '.HAZSUB_PROCESS_NAME_ZFPF.'. Has options for what, if anything, can be done to mitigate leaks in several broad types of situations. Distinguishes between leaks when some or all '.HAZSUB_NAME_ADJECTIVE_ZFPF.' compressors should be shut off (high-side leaks) and leaks when compressors should often be left running, while '.HAZSUB_NAME_ADJECTIVE_ZFPF.' flow to the leak is stopped, if safely possible (low-side leaks). Includes guidance on when to press the emergency-stop button for the entire '.HAZSUB_PROCESS_NAME_ZFPF.'. Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    8 => array(
        'c5name' => $Zfpf->encrypt_1c('Small-Leak Notifications, Investigation, and Actions'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEIII'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions for small leaks, also called "incidental releases", which don\'t require "emergency response" meaning "a response effort by employees from outside the immediate release area or by other designated responders (i.e., mutual aid groups, local fire departments, etc.) to an occurrence which results, or is likely to result, in an uncontrolled release of a hazardous substance. Responses to incidental releases of hazardous substances where the substance can be absorbed, neutralized, or otherwise controlled at the time of release by employees in the immediate release area, or by maintenance personnel are not considered to be emergency responses within the scope of this standard. Responses to releases of hazardous substances where there is no potential safety or health hazard (i.e., fire, explosion, or chemical exposure) are not considered to be emergency responses." 29 CFR 1910.120(a)(3). Procedure descriptions include: 
(1) notifying the facility\'s emergency coordinator (or similar), 
(2) having a buddy, who can contact the emergency coordinator, 
(3) pre-action inspection for damaged equipment that may cause the procedure to fail, 
(4) identifying and troubleshooting (or calling in help for) reasonably-foreseeable failure possibilities, such as leak-by a valve seat, threaded bonnet loosens, seal cap or stem stuck, and so forth. 
* Option: refer to sub-procedures for specific tasks, such as the Operating Refrigeration Valve Stems sub-procedure. 
Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    9 => array(
        'c5name' => $Zfpf->encrypt_1c('Startup After Power Failure, Leak, or Emergency Shutdown'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEJJJ'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions covering: 
(1) how the '.HAZSUB_PROCESS_NAME_ZFPF.', including its controls, responds to power blips, outages, and power restoration (including interrupted defrosting, if applicable), 
(2) any needed human actions during or after power failures, 
(3) handling shutdowns due to a high level in a vessel that feeds compressors (the first step may be to call in a qualified '.HAZSUB_PROCESS_NAME_ZFPF.' contractor); and 
(4) re-start after leaks and emergency shutdowns (okay to say that a unique re-start plan will be developed in consultation with a qualified '.HAZSUB_PROCESS_NAME_ZFPF.' contractor, after investigating conditions). 
Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    10 => array(
        'c5name' => $Zfpf->encrypt_1c('Ammonia Charging'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEKKK'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions covering '.HAZSUB_NAME_ADJECTIVE_ZFPF.' quality and inventory control, charging from cylinders, charging from tanker trucks, and so forth, as applicable. Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    11 => array(
        'c5name' => $Zfpf->encrypt_1c('Oil Charging and Draining'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEELLL'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions covering lubricating oil quality and inventory control, draining oil, and charging oil into '.HAZSUB_NAME_ADJECTIVE_ZFPF.' compressors and any '.HAZSUB_NAME_ADJECTIVE_ZFPF.' pumps with seals that use oil. All '.HAZSUB_PROCESS_NAME_ZFPF.' openings from which oil is drained are equipped with a spring-closing valve and, optionally, an oil pot (useful where oil is routinely drained), except for draining oil from equipment that has been isolated, pumped out, and vented to atmospheric pressure. If these procedures will be used without a piping-opening permit, the procedure descriptions shall include valve tag numbers and details needed to safely complete their tasks. Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    12 => array(
        'c5name' => $Zfpf->encrypt_1c('Hot Work Permit and Sub-Procedures'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEMMM'),
        'c6description' => $Zfpf->encrypt_1c('These include: 
(1) the hot-work permit policy and form applicable to the process and 
(2) the Evacuating Ammonia for Hot Work sub-procedure description, which applies to hot-work on the '.HAZSUB_PROCESS_NAME_ZFPF.'. 
Sub-procedure description also includes: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    13 => array(
        'c5name' => $Zfpf->encrypt_1c('Lockout-Tagout, Piping-Opening Permit, and Sub-Procedures'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEENNN'),
        'c6description' => $Zfpf->encrypt_1c('Valve stems on the '.HAZSUB_PROCESS_NAME_ZFPF.' shall not be turned by hand, unless following a written hazardous-substance procedure description or a piping-opening permit. See: 
(1) Lockout-Tagout (LOTO) policy or similar, 
(2) Piping-Opening Permit form (and any templates for routine activities, see below) designed to cover LOTO of all hazardous-energy sources affecting the piping being opened, and 
(3) the following sub-procedure descriptions: 
(3.1) Operating Refrigeration Valve Stems, 
(3.2) Closing Liquid-Ammonia Stop Valves Upstream of Solenoid and Motorized Valves, 
(3.3) Pump-down with Hose, 
(3.4) Venting Ammonia to Water, and 
(3.5) Ammonia Flange Opening. 
* Other procedure descriptions may reference these sub-procedures, for example, an oil-draining procedure description may reference and require training on the Operating Refrigeration Valve Stems sub-procedure. 
* Once a piping-opening permit has been completed for a routine task, it may be converted into a template, to more easily generate the permit next time. 
The permit form and sub-procedure descriptions also include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    14 => array(
        'c5name' => $Zfpf->encrypt_1c('Equipment-Package Procedures'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEOOO'),
        'c6description' => $Zfpf->encrypt_1c('These may be developed to simplify the system-wide procedure descriptions and documents, such as the Operating Limits, Deviation Consequences, Controls, Safety Systems, and Corrective Actions document(s). They may be worthwhile for equipment that routinely needs to be isolated, pumped out, and vented for service, such as compressors; however, this may also be handled by template piping-opening permits. Equipment-packages also provide an option for grouping procedures, such as for adding oil, draining oil, or temporary operation. If written, each procedure description also includes (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to. Optional.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    15 => array(
        'c5name' => $Zfpf->encrypt_1c('Temporary Operations'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEPPP'),
        'c6description' => $Zfpf->encrypt_1c('Any allowed temporary operations are covered in hazardous-substance procedure descriptions or, if related to a corrective action, in the Operating Limits, Deviation Consequences, Controls, Safety Systems, and Corrective Actions document(s). Otherwise, equipment is operated normally or shut down. If an unanticipated need for a temporary operation arises, complete the change-management applicability determination included in this app, and follow instructions there. Temporary operations may involve: 
(1) swing compressors, 
(2) switching a motorized valve to hand operation, 
(3) disabling a motorized valve and controlling liquid flow with an existing hand-expansion valve instead, and so forth. 
If written, each temporary-operation procedure description also includes (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to. Optional.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    16 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Operations'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEEQQQ'),
        'c6description' => $Zfpf->encrypt_1c('The Owner/Operator forbids '.HAZSUB_PROCESS_NAME_ZFPF.' emergency operations, unless they are part of a leak-mitigation or an emergency shutdown effort that is being completed per either 
(1) a written hazardous-substance procedure description or 
(2) the spoken instructions of an emergency-response incident commander, such as, often, the senior officer on a responding fire-department vehicle.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ),
    17 => array(
        'c5name' => $Zfpf->encrypt_1c('Initial Startup and Normal Shutdown'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEERRR'),
        'c6description' => $Zfpf->encrypt_1c('See this app\'s Change Management System, which includes tasks like special isolation, pump-down, venting, cleaning, tie-in, and startup procedures, and which covers initial system commissioning and startup, additions, and partial or complete shutdown and decommissioning. Idling all or part of the '.HAZSUB_PROCESS_NAME_ZFPF.' is covered by the Low Load Begin and End procedure, the Piping-Opening Permit safe-work practice, or any (optional) equipment-package procedures.'),
        'c5require_file' => $EncryptedNothing,
        'c5require_file_privileges' => $EncryptedNothing
    ),
    18 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety-Shutoffs Testing'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEESSS'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions covering: 
(1) adjusting controls and/or valves to verify that '.HAZSUB_NAME_ADJECTIVE_ZFPF.' compressors shut down within the desired range (near the setpoint) for the following safety shutoffs, as applicable: 
(1.1) high-discharge pressure, 
(1.2) high-discharge temperature, 
(1.3) low-oil pressure, 
(1.4) low-suction pressure, and 
(1.5) any other compressor-safety shutoffs manufacturer recommendations call for testing and 
(2) if turning a valve stem on the '.HAZSUB_PROCESS_NAME_ZFPF.' is needed to fully verify the function of a float switch that shuts off '.HAZSUB_NAME_ADJECTIVE_ZFPF.' compressors or pumps, such as by filling or emptying a level column independently from the level in its vessel, verifying that when the level in the vessel(s) that feed them is 
(2.1) too high -- compressors shutdown and 
(2.2) too low -- pumps shut down. 
* The frequencies for these tests is covered under inspection, testing, and maintenance (ITM) for safe operation and mechanical integrity, but hazardous-substance procedure descriptions are needed for them due to their hazards. 
* See IIAR 6, latest edition, for requirements and guidance on test methods.
Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    ),
    19 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety-System Testing or Disabling for Maintenance'),
        'c2standardized' => '[Nothing has been recorded in this field.]',
        'c5number' => $Zfpf->encrypt_1c('EEESSS'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions covering: 
(1) agreement on timing from managers and supervisors responsible anyone working in the areas protected by a safety system that will need to be disabled, 
(2) "testing alarms" start and end notifications to everyone who may see or hear any alarms that will be triggered, 
(3) immediately before disabling a safety system 
(3.1) clearing all non-essential personnel from areas protected by a safety system, 
(3.2) verifying essential personnel who will remain have adequate training and equipment, including personal-protective equipment, 
(3.3) to the extent practical, stopping operations and minimizing conditions that could cause a need for the safety system, 
(3.4) verifying that ongoing operations are running safely, and 
(3.5) if testing fans, ensuring that there are no loose, lightweight, things that could be sucked into the fans, 
(4) minimizing the simultaneous disabling of safety-system functions to the extent practical, and 
(5) notifications to managers and supervisors once the safety systems are fully operational again. 
Procedure descriptions include: (A) applicability, (B) roles, responsibilities, and required training, (C) special equipment required, including personal-protective equipment (PPE), (D) first aid and safety data sheet reference; and (E) safety warnings before the first step they apply to'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    )
);
// Populate t0practice and t0practice_division for HSPSWP division
$practice_division = array(); // In case used by previously required file.
$k0practice = get_highest_in_table($Zfpf, $DBMSresource, 'k0practice', 't0practice');
foreach ($practices as $K => $V) {
    $V['k0practice'] = ++$k0practice;
    $practices[$K]['k0practice'] = $k0practice; // needed later for practice_division and fragment_practice
    $V['c2standardized'] = 'Process Standard Practice'; // Not encrypted, c2 field.
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0practice', $V);
    $practice_division[] = array('k0practice' => $k0practice);
}
$practice_division[] = array('k0practice' => 46); // k0practice 46 is Operating Limits, Deviation Consequences, Controls, and Safety Systems from PSI, treat like a HSPSWP practice as well.
// Make array of divisions to be associated with these practices.
$Divisions = array(
    5,  // HSPSWP in Cheesehead division method. See templates/divisions.php
    19, // Operating Procedures in OSHA PSM
    34  // Operating Procedures in EPA CAP
);
$k0practice_division = get_highest_in_table($Zfpf, $DBMSresource, 'k0practice_division', 't0practice_division');
foreach ($Divisions as $VA) {
    foreach ($practice_division as $VB) {
        $VB['k0practice_division'] = ++$k0practice_division;
        $VB['k0division'] = $VA;
        $VB['c5who_is_editing'] = $EncryptedNobody;
        $Zfpf->insert_sql_1s($DBMSresource, 't0practice_division', $VB);
    }
}
// Populate t0practice_division for Hot Work division
$practice_division = array(); // In case used by previously required file.
$practice_division[] = array('k0practice' => $practices[12]['k0practice']); // Hot Work Permit and Sub-Procedures
// Make array of divisions to be associated with above practice.
$Divisions = array( // none in Cheesehead
    24, // hot work in OSHA PSM
    42  // hot work in EPA CAP
);
$k0practice_division = get_highest_in_table($Zfpf, $DBMSresource, 'k0practice_division', 't0practice_division');
foreach ($Divisions as $VA) {
    foreach ($practice_division as $VB) {
        $VB['k0practice_division'] = ++$k0practice_division;
        $VB['k0division'] = $VA;
        $VB['c5who_is_editing'] = $EncryptedNobody;
        $Zfpf->insert_sql_1s($DBMSresource, 't0practice_division', $VB);
    }
}

// Handle Emergency Action Plan here because it's the only emergency planning (EP) division practice for non-responding facilities, besides small leaks.
$EAPpractice = array(
    0 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Action Plan'),
        'c5number' => $Zfpf->encrypt_1c('LLLAAA'),
        'c6description' => $Zfpf->encrypt_1c('Plan covering the full range of potential incidents, including, for ammonia: 
(1) "what to do if I smell ammonia or notice other hazards" -- such as how to notify facility management and individual move-to-safety, 
(2) determining and communicating routes to safe locations inside (shelter-in-place) or outside (evacuation), 
(3) sweeps, if safe, while leaving and headcount, 
(4) any needed shutdown of critical plant equipment before evacuating (may reference leak mitigation and emergency shutdown procedures), 
(5) calls to needed emergency responders, such as the local fire department and a contractor, pre-qualified to provide emergency response, 
(6) any rescue or medical duties of employees, 
(7) in the USA, within 15 minutes of discovering that 100 pounds or more of anhydrous ammonia has leaked within a 24-hour period, calls to (7.1) local (typically 911), (7.2) state, and (7.3) federal (the National Response Center) emergency-response authorities, 
(8) greeting and briefing emergency responders (conditions, actions taken or underway, and needs), 
(9) advanced coordination with local emergency responders, including 
(9.1) at least yearly checking that the emergency plan of the community where the facility is located has up-to-date information on 
(9.1.1) facility contacts, 
(9.1.2) facility hazardous-substance quantities, 
(9.1.3) facility hazardous-substance risks and any other risks (high temperatures and pressures and so forth), and 
(9.1.4) any facility resources helpful during incidents, 
(9.2) when a change occurs at the facility that may affect offsite-response plans, such as contact information, building access, materials locations, and so forth, promptly notifying the local emergency responders, and 
(9.3) at least yearly exercising of emergency-response notification mechanisms by inviting the local emergency responders (and any contracted responders) to tour the facility or to complete any other reasonable preparedness efforts they request, including providing access to facility site maps and floor plans with details relevant to emergency response and, if requested, a copy of the facility Emergency Action Plan, 
(10) emergency-actions training, drills, and their documentation, at least 
(10.1) when an employee is first assigned to a job, 
(10.2) when an employee\'s responsibilities under the plan change, 
(10.3) when the plan changes, and typically 
(10.4) yearly drills or refreshers, and 
(11) all other applicable requirements in 29 CFR 1910.38 and other relevant rules, depending on any other hazardous substances and circumstances at the facility, covering, for example, medical emergencies, earthquakes, hurricanes, tornadoes, bomb threats, and so forth.'.DOC_WHERE_KEPT_ZFPF),
        'c5require_file' => $Encrypted_document_i1m_php,
        'c5require_file_privileges' => $EncryptedLowPrivileges
    )
);
// Populate t0practice and t0practice_division for EAP practice(s)
$practice_division = array(); // In case used by previously required file.
$k0practice = get_highest_in_table($Zfpf, $DBMSresource, 'k0practice', 't0practice');
foreach ($EAPpractice as $K => $V) {
    $V['k0practice'] = ++$k0practice;
    $EAPpractice[$K]['k0practice'] = $k0practice; // needed later for practice_division and fragment_practice
    $V['c2standardized'] = 'Process Standard Practice'; // Not encrypted, c2 field.
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0practice', $V);
    $practice_division[] = array('k0practice' => $k0practice);
}
$practice_division[] = array('k0practice' => $practices[7]['k0practice']); // Leak Mitigation and Emergency Shutdown (intentionally redundant)
$practice_division[] = array('k0practice' => $practices[8]['k0practice']); // Small-Leak Notifications, Investigation, and Actions
// Make array of divisions to be associated with these practices.
$Divisions = array(
    12,  // EP in Cheesehead division method. See templates/divisions.php
    27, // EP in OSHA PSM
    44  // EP in EPA CAP
);
$k0practice_division = get_highest_in_table($Zfpf, $DBMSresource, 'k0practice_division', 't0practice_division');
foreach ($Divisions as $VA) {
    foreach ($practice_division as $VB) {
        $VB['k0practice_division'] = ++$k0practice_division;
        $VB['k0division'] = $VA;
        $VB['c5who_is_editing'] = $EncryptedNobody;
        $Zfpf->insert_sql_1s($DBMSresource, 't0practice_division', $VB);
    }
}

// Populate t0fragment_practice
$fragment_practice = array(  // PSM fragments k0 start at 1, CAP fragments k0 start at 1000. See templates/psm_fragments.php and templates/cap_fragments.php.
    0 => array(
        'k0fragment' => 112, // Emergency Action Plan
        'k0practice' => $EAPpractice[0]['k0practice']
    ),
    1 => array(
        'k0fragment' => 1173, // 40 CFR 68.90 and 68.93 Applicability and Community Emergency-Response Option
        'k0practice' => $EAPpractice[0]['k0practice']
    ),
    2 => array(
        'k0fragment' => 112,
        'k0practice' => $practices[7]['k0practice'] // Leak Mitigation and Emergency Shutdown (intentionally redundant)
    ),
    3 => array(
        'k0fragment' => 1173,
        'k0practice' => $practices[7]['k0practice'] // Leak Mitigation and Emergency Shutdown (intentionally redundant)
    ),
    4 => array(
        'k0fragment' => 112,
        'k0practice' => $practices[8]['k0practice'] // Small-Leak Notifications, Investigation, and Actions (intentionally redundant)
    ),
    5 => array(
        'k0fragment' => 1173,
        'k0practice' => $practices[8]['k0practice'] // Small-Leak Notifications, Investigation, and Actions (intentionally redundant)
    ),
    6 => array(
        'k0fragment' => 44, // PSM -- The primary key, k0..., for the first PSM operating-procedure fragment, "Purpose..."
        'k0practice' => 46 // Operating Limits, Deviation Consequences, Controls, Safety Systems, and Corrective Actions
    ),
    7 => array(
        'k0fragment' => 1099, // CAP -- The primary key, k0..., for the first CAP operating-procedure fragment, "Purpose..."
        'k0practice' => 46
    ),
    8 => array(
        'k0fragment' => 53, // Operating Limits, Deviation Consequences, and Corrective Actions
        'k0practice' => 46
    ),
    9 => array(
        'k0fragment' => 1108, // Operating Limits, Deviation Consequences, and Corrective Actions
        'k0practice' => 46
    ),
    10 => array(
        'k0fragment' => 61, // Safety Systems
        'k0practice' => 46
    ),
    11 => array(
        'k0fragment' => 1116, // Safety Systems
        'k0practice' => 46
    ),
    12 => array(
        'k0fragment' => 44,
        'k0practice' => $practices[0]['k0practice']
    ),
    13 => array(
        'k0fragment' => 1099,
        'k0practice' => $practices[0]['k0practice']
    ),
    14 => array(
        'k0fragment' => 44, // redundantly link access here
        'k0practice' => $practices[1]['k0practice']
    ),
    15 => array(
        'k0fragment' => 1099, // redundantly link access here
        'k0practice' => $practices[1]['k0practice']
    ),
    16 => array(
        'k0fragment' => 62, // Access to Procedures
        'k0practice' => $practices[1]['k0practice']
    ),
    17 => array(
        'k0fragment' => 1117, // Access to Procedures
        'k0practice' => $practices[1]['k0practice']
    ),
    18 => array(
        'k0fragment' => 65, // Access Control
        'k0practice' => $practices[2]['k0practice']
    ),
    19 => array(
        'k0fragment' => 1120, // Access Control
        'k0practice' => $practices[2]['k0practice']
    ),
    20 => array(
        'k0fragment' => 63, // Always Up-to-date fragment
        'k0practice' => 14 // Change Management System practice
    ),
    21 => array(
        'k0fragment' => 1118, // Always Up-to-date fragment
        'k0practice' => 14 // Change Management System practice
    ),
    22 => array(
        'k0fragment' => 64, // Annual Certification
        'k0practice' => $practices[3]['k0practice']
    ),
    23 => array(
        'k0fragment' => 1119, // Annual Certification
        'k0practice' => $practices[3]['k0practice']
    ),
    24 => array(
        'k0fragment' => 47, // Normal Operations
        'k0practice' => $practices[4]['k0practice'] // Daily Inspection
    ),
    25 => array(
        'k0fragment' => 1102, // Normal Operations
        'k0practice' => $practices[4]['k0practice'] // Daily Inspection
    ),
    26 => array(
        'k0fragment' => 47, // Normal Operations
        'k0practice' => $practices[5]['k0practice'] // Weekly Inspection
    ),
    27 => array(
        'k0fragment' => 1102, // Normal Operations
        'k0practice' => $practices[5]['k0practice'] // Weekly Inspection
    ),
    28 => array(
        'k0fragment' => 47, // Normal Operations
        'k0practice' => $practices[6]['k0practice'] // Low Load Begin and End
    ),
    29 => array(
        'k0fragment' => 1102, // Normal Operations
        'k0practice' => $practices[6]['k0practice'] // Low Load Begin and End
    ),
    30 => array(
        'k0fragment' => 49,
        'k0practice' => $practices[7]['k0practice'] // Leak Mitigation and Emergency Shutdown
    ),
    31 => array(
        'k0fragment' => 1104,
        'k0practice' => $practices[7]['k0practice'] // Leak Mitigation and Emergency Shutdown
    ),
    32 => array(
        'k0fragment' => 113,
        'k0practice' => $practices[8]['k0practice'] // Small-Leak Notifications, Investigation, and Actions (no CAP fragment for this.)
    ),
    33 => array(
        'k0fragment' => 52,
        'k0practice' => $practices[9]['k0practice'] // Startup After Power Failure, Leak, or Emergency Shutdown
    ),
    34 => array(
        'k0fragment' => 1107,
        'k0practice' => $practices[9]['k0practice'] // Startup After Power Failure, Leak, or Emergency Shutdown
    ),
    35 => array(
        'k0fragment' => 58,
        'k0practice' => $practices[10]['k0practice'] // Ammonia Charging
    ),
    36 => array(
        'k0fragment' => 1113,
        'k0practice' => $practices[10]['k0practice'] // Ammonia Charging
    ),
    37 => array(
        'k0fragment' => 59,
        'k0practice' => $practices[10]['k0practice'] // Ammonia Charging
    ),
    38 => array(
        'k0fragment' => 1114,
        'k0practice' => $practices[10]['k0practice'] // Ammonia Charging
    ),
    39 => array(
        'k0fragment' => 58,
        'k0practice' => $practices[11]['k0practice'] // Oil Charging and Draining
    ),
    40 => array(
        'k0fragment' => 1113,
        'k0practice' => $practices[11]['k0practice'] // Oil Charging and Draining
    ),
    41 => array(
        'k0fragment' => 99, // Hot work PSM fragment
        'k0practice' => $practices[12]['k0practice'] // Hot Work Permit and Sub-Procedures
    ),
    42 => array(
        'k0fragment' => 1158, // Hot work CAP fragment
        'k0practice' => $practices[12]['k0practice'] // Hot Work Permit and Sub-Procedures
    ),
    43 => array(
        'k0fragment' => 65, // Safe-work practices PSM fragment
        'k0practice' => $practices[12]['k0practice'] // Hot Work Permit and Sub-Procedures
    ),
    44 => array(
        'k0fragment' => 1120, // Safe-work practices CAP fragment
        'k0practice' => $practices[12]['k0practice'] // Hot Work Permit and Sub-Procedures
    ),
    45 => array(
        'k0fragment' => 65, // Safe-work practices PSM fragment
        'k0practice' => $practices[13]['k0practice'] // Lockout-Tagout, Piping-Opening Permit, and Sub-Procedures
    ),
    46 => array(
        'k0fragment' => 1120, // Safe-work practices CAP fragment
        'k0practice' => $practices[13]['k0practice'] // Lockout-Tagout, Piping-Opening Permit, and Sub-Procedures
    ),
                     // $practices[14] skipped, optional, equipment-package procedures.
    47 => array(
        'k0fragment' => 48,
        'k0practice' => $practices[15]['k0practice'] // Temporary Operations
    ),
    48 => array(
        'k0fragment' => 1103,
        'k0practice' => $practices[15]['k0practice'] // Temporary Operations
    ),
    49 => array(
        'k0fragment' => 50,
        'k0practice' => $practices[16]['k0practice'] // Emergency Operations
    ),
    50 => array(
        'k0fragment' => 1105,
        'k0practice' => $practices[16]['k0practice'] // Emergency Operations
    ),
    51 => array(
        'k0fragment' => 46, // fragment 46 is initial startup (don't confuse with practice 46, above).
        'k0practice' => $practices[17]['k0practice'] // Initial Startup and Normal Shutdown
    ),
    52 => array(
        'k0fragment' => 1101,
        'k0practice' => $practices[17]['k0practice'] // Initial Startup and Normal Shutdown
    ),
    53 => array(
        'k0fragment' => 51,
        'k0practice' => $practices[17]['k0practice'] // Initial Startup and Normal Shutdown
    ),
    54 => array(
        'k0fragment' => 1106,
        'k0practice' => $practices[17]['k0practice'] // Initial Startup and Normal Shutdown
    )
);
// Handle the safety and health information included in all hazardous-substance procedures and in the piping-opening permit form.
$SHfragments = array(54, 55, 56, 57, 60, 1109, 1110, 1111, 1112, 1115); // database primary keys, see templates/psm_fragments.php and templates/cap_fragments.php. PSM fragments k0 start at 1, CAP fragments k0 start at 1000.
$ApplicablePractices = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15); // $practices array keys above
foreach ($SHfragments as $VA) {
    foreach ($ApplicablePractices as $VB)
        $fragment_practice[] = array(
            'k0fragment' => $VA,
            'k0practice' => $practices[$VB]['k0practice']
        );
}
$k0fragment_practice = get_highest_in_table($Zfpf, $DBMSresource, 'k0fragment_practice', 't0fragment_practice');
foreach ($fragment_practice as $V) {
    $V['k0fragment_practice'] = ++$k0fragment_practice;
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0fragment_practice', $V);
}

