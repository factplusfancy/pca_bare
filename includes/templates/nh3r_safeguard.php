<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// TO DO finish populating this template.
$safeguard = array(
    0 => array(
        'c5name' => $Zfpf->encrypt_1c('Valves, Stop, none have threaded bonnets or similar (instead they have bolted bonnets)'), // required
        'c5hierarchy' => $Zfpf->encrypt_1c('Engineering: Improves Primary-Containment Envelope'), // required -- Hierarchy of Controls/Safeguards Type: 'Not Applicable or Multiple', 'Elimination', 'Substitution', 'Inventory Reduction', 'Engineering: Improves Primary-Containment Envelope', 'Engineering: Improves Instrumentation, Controls, or Machinery Reliability', 'Engineering: Greater Separation', 'Engineering: Secondary Containment or Release Treatment', 'Administrative', 'Personal-Protective Equipment (PPE)'
        'c6description' => $EncryptedNothing // optional
    ),
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('Valves, Control, none have threaded bonnets or similar (instead they have bolted bonnets).'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Engineering: Improves Primary-Containment Envelope'),
        'c6description' => $EncryptedNothing
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('Valves, all are located on roof (greater than 20-feet from air intakes) or in rooms with sensors, alarms, and emergency ventilation (such as the refrigerating-machinery room).'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Engineering: Greater Separation'),
        'c6description' => $EncryptedNothing
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('Valves, all have corrosion-resistant moving parts and seals (stainless steel stems, etc.)'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Engineering: Improves Primary-Containment Envelope'),
        'c6description' => $EncryptedNothing
    ),
    4 => array(
        'c5name' => $Zfpf->encrypt_1c('Maintenance: valves with stems (including control-valve manual-opening stems) are lubricated and exercised per manufacturers recommendations or industry practice.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $Zfpf->encrypt_1c('Valves, Stop, may be separated into groups, such as "critical for emergency isolation" (exercised every year) and "other" (exercised every two years).')
    ),
    5 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection, daily, includes leak check in refrigerating-machinery room.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $EncryptedNothing
    ),
    6 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection, weekly, includes leak check in all areas with ammonia piping or equipment.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $EncryptedNothing
    ),
    7 => array(
        'c5name' => $Zfpf->encrypt_1c('Procedures warn when turning a stop or control valve stem can separate a threaded bonnet from a valve body.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $EncryptedNothing
    ),
    8 => array(
        'c5name' => $Zfpf->encrypt_1c('Procedures warn against using excessive force on stuck valve stems.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $EncryptedNothing
    ),
    9 => array(
        'c5name' => $Zfpf->encrypt_1c('Procedures warn against using copper-based anti-seize (and call for lubricants compatible with all refrigeration system materials, including any galvanized coils).'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $EncryptedNothing
    ),
    10 => array(
        'c5name' => $Zfpf->encrypt_1c('Training: all facility employees and contractors are trained that only authorized employees or contractors can touch or maintain the refrigeration system (piping, valves, controls, etc.)'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $EncryptedNothing
    ),
    11 => array(
        'c5name' => $Zfpf->encrypt_1c('Training: all facility employees and contractors receive ammonia-awareness training that covers extra precautions when working near ammonia piping (accidental cut-ins, rigging, etc.)'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $EncryptedNothing
    ),
    12 => array(
        'c5name' => $Zfpf->encrypt_1c('Procedures call for monitoring pressure before opening the '.HAZSUB_PROCESS_NAME_ZFPF.'.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $Zfpf->encrypt_1c('Address via '.HAZSUB_PROCESS_NAME_ZFPF.' opening permits or in lockout-tagout policy. Typically: 
(1) any '.HAZSUB_NAME_ADJECTIVE_ZFPF.' liquid solenoid valve immediately downstream of a stop valve that needs to be shut is manually very-slightly opened, 
(2) all liquid, hot gas, and any other '.HAZSUB_NAME_ADJECTIVE_ZFPF.' supply stop valves are promptly closed, 
(3) without delay all liquid, hot gas, and any other '.HAZSUB_NAME_ADJECTIVE_ZFPF.' supply solenoid valves are manually fully opened, 
(4) any suction control valves (including regulators) that could retain pressure in the piping being isolated are manually fully opened, 
(5) allow time to for piping being isolated to pump down to suction pressure, 
(6) suction stop valves are closed as needed to complete the isolation, 
(7) THEN pressure in the isolated piping is monitored for fifteen minutes (or alternate time period depending on the volume isolated) to confirm that closed supply valves are holding (no leak-by), 
(8) if the remaining pressure, which should be the same as the compressor-suction pressure, in the isolated piping is above atmospheric, either 
(8.1) if available, the isolated piping is pumped down to a vacuum via an appropriate hose or pump-down system or 
(8.2) the isolated piping is vented to water, and 
(9) AGAIN, before opening the isolated piping to atmosphere, its pressure is monitored for fifteen minutes (or alternate time period depending on the volume isolated) to confirm that there is no leak-by any closed stop valves providing the isolation (these are valves that are also locked out.) 
* Venting-to-water procedures also need to address the possibility of isolation valve leak-by.')
    ),
    13 => array(
        'c5name' => $Zfpf->encrypt_1c('Pre-work notifications.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $Zfpf->encrypt_1c('Procedure descriptions or '.HAZSUB_PROCESS_NAME_ZFPF.' opening permits call for notifying supervisors or leads before opening the '.HAZSUB_PROCESS_NAME_ZFPF.' near where their employees are working.')
    ),
    14 => array(
        'c5name' => $Zfpf->encrypt_1c('Procedures call for wearing appropriate personal-protective equipment (PPE).'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Personal-Protective Equipment (PPE)'),
        'c6description' => $EncryptedNothing
    ),
    15 => array(
        'c5name' => $Zfpf->encrypt_1c('Pipe, valve, vessel, equipment, controls, and refrigeration-machinery room door labeling is adequate and per code.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $Zfpf->encrypt_1c('Special rules apply to emergency stops and emergency ventilation manual-start controls as well as the King valve. Valve Tags: install valve tags at locations where they will neither be covered with frost nor impede valve operation (so not around valve stems). Best location is on valve bodies that won\'t have frost, on rods strapped to valve bodies to hold the tags above the frost, or (least good option) on hand wheels (if the facility leaves hand wheels on stop valves). But, if on hand wheels, use plastic or similar tags and straps that won\'t cut someones hand when closing the valve, and strap all corners of the tag to the face of the hand wheel, not dangling from the edge, to avoid obstruction and to prevent the tag from getting knocked off.')
    ),
    16 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection, yearly, includes checking all labels against piping and instrumentation diagram.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $Zfpf->encrypt_1c('This includes checking all pipe, valve, vessel, equipment, controls, and refrigeration-machinery room door labels against the piping and instrumentation diagram to verify that labels are correctly installed, legible, in adequate condition, and consistent with the piping and instrumentation diagram.')
    ),
    17 => array(
        'c5name' => $Zfpf->encrypt_1c('Procedures refer to equipment and valves by BOTH a description and a number (or code), include a pre-work check for damage or labeling errors, and warn about damage or labeling errors.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $Zfpf->encrypt_1c('For example, "SAFETY REMINDER: damaged equipment -- do not work on damaged equipment without first isolating and removing anhydrous ammonia from the equipment. It may fail suddenly, resulting in a rapid leak. SAFETY REMINDER: labeling errors -- valve numbers in procedures, on the physical valve tags, or on the piping and instrumentation diagram may have errors. Pre-work Inspection: inspect air unit AU1 and its piping for leaks, damage, or valve-labeling errors or inconsistencies. Stop work and notify your supervisor if you notice anything amiss. [...] Close liquid-supply stop-valve AU-01 [...]"')
    ),
    18 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection, daily, includes check of alarms and trend charts on central-controls terminal.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $EncryptedNothing
    ),
    19 => array(
        'c5name' => $Zfpf->encrypt_1c('Alarms get sent to phones or pagers of key personnel as needed.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $EncryptedNothing
    ),
    20 => array(
        'c5name' => $Zfpf->encrypt_1c('Discharge locations of pressure-relief valves are adequately far from air intakes, windows, doors, ladders, walkways, or work areas -- except areas of infrequent work done with proper PPE.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Engineering: Greater Separation'),
        'c6description' => $EncryptedNothing
    ),
    21 => array(
        'c5name' => $Zfpf->encrypt_1c('Discharge locations for emergency ventilation are adequately far from air intakes, windows, doors, ladders, walkways, or work areas -- except areas of infrequent work done with proper PPE.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Engineering: Greater Separation'),
        'c6description' => $Zfpf->encrypt_1c('Discharge locations for refrigerating-machinery room normal ventilation should have similarly adequate separations.')
    ),
    22 => array(
        'c5name' => $Zfpf->encrypt_1c('Refrigerating-machinery room(s) have ammonia sensors, alarms, and emergency ventilation that are adequate and per code.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Engineering: Greater Separation'),
        'c6description' => $EncryptedNothing
    ),
    23 => array(
        'c5name' => $Zfpf->encrypt_1c('Ammonia has good warning properties, unlike, for example, propane or halocarbons.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Substitution'), // Ammonia was a substitute refrigerant for propane, which doesn't have good warning properties.
        'c6description' => $Zfpf->encrypt_1c('Most people can smell traces of ammonia in air at below harmful levels, do not have their sense of smell fatigued by continued exposure, will experience more discomfort as concentrations rise, and so will try to move to safety.')
    ),
    24 => array(
        'c5name' => $Zfpf->encrypt_1c('Procedures and training cover proper operation of all types of valve stems and seal caps in the process.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Administrative'),
        'c6description' => $Zfpf->encrypt_1c('Include warnings about trapping liquid ammonia, threaded bonnets, ammonia accumulation under seal caps, sudden ammonia leaks (around the packing) at stems when starting to close a fully open (and so back-seated) valve, excessive force (hands only on hand wheels, no tools), tag-and-seal open valves, and the need for lockout-tagout. For non-emergency situations, also include cleaning and lubricating stems, slightly loosening packing nuts (except with O-ring stem seals), and re-tightening packing nuts.')
    ),
    25 => array(
        'c5name' => $Zfpf->encrypt_1c('Adequate prevention against accidental or malicious '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases.'),
        'c5hierarchy' => $Zfpf->encrypt_1c('Engineering: Improves Primary-Containment Envelope'),
        'c6description' => $Zfpf->encrypt_1c('Effort includes: 
(1) training for all employees and contractors, 
(2) access controls, such as physical and cyber security, and 
(3) all piping openings to the atmosphere, for '.HAZSUB_NAME_ADJECTIVE_ZFPF.' charging, oil draining, purging, sampling, and so forth are 
(3.1) sealed with a threaded cap or plug when not attended by a qualified employee or contractor, who needs them to be open, 
(3.2) also sealed with a valve, in series, if they need to be opened more often than once every five years, and 
(3.3) locked when in an place that thieves or vandals could access without more trouble than cutting a lock.')
    )/*,
    99 => array(
        'c5name' => $Zfpf->encrypt_1c(''),
        'c5hierarchy' => $Zfpf->encrypt_1c(''),
        'c6description' => $Zfpf->encrypt_1c('')
    )*/
);
foreach ($safeguard as $K => $V) {
    $V['k0safeguard'] = $K + 1;
    $safeguard[$K]['k0safeguard'] = $V['k0safeguard'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0safeguard', $V);
}

