<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file populates t0practices with the ammonia-refrigeration templates below for 
// inspection, testing, and maintenance (ITM) for safe operation and mechanical integrity.
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
    'c5name' => $Zfpf->encrypt_1c('Inspection, Testing, and Maintenance (ITM) Procedures Background'),
    'c5number' => $Zfpf->encrypt_1c('HHHAAA'),
    'c6description' => $Zfpf->encrypt_1c('(1) The PSM-CAP compliance practices for ITM below use industry standards, such as IIAR 6-2019, IIAR 9-2020 and ASME codes, manufacturer recommendations, and other currently-done good practices as the primary source of "recognized and generally accepted good engineering practices" (RAGAGEP or "good practices"). 
(2) The "hazardous-substance procedures and safe-work practices", in a separate section of this PSM-CAP App, cover a limited group of tasks, where a mistake could promptly cause harm or unacceptable risks, including:
(2.1) opening or shutting a valve in '.HAZSUB_NAME_ADJECTIVE_ZFPF.' service by any method (touching a control panel, turning a valve stem by hand, and so forth), 
(2.2) monitoring (typically including inspections done weekly or more often), 
(2.3) corrective actions (fixes) or other goal-driven adjustments (optimizing), 
(2.4) adding/removing materials to/from the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(2.5) '.HAZSUB_PROCESS_NAME_ZFPF.' opening and return-to-service, and 
(2.6) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' safety-system testing or disabling for maintenance.
(3) Outside the above group are a broad range of less-hazardous ITM tasks where mistakes can and should be caught before they cause harm. For these ITM tasks, a different approach to procedures and training is acceptable and also needed because they are often more complex and situation specific. So, these less-hazardous ITM tasks are described by a pyramid of documents, starting, at the pyramid\'s tip, with: 
(3.1) the "ITM for safe operation and mechanical integrity" PSM-CAP compliance practices below, which provide task schedules and summary descriptions, and then broadening out to documents that help implement or are referenced by these PSM-CAP compliance practices, such as 
(3.2) a work order or similar (see definition in the Scheduling, Method, Resolution, and Recordkeeping PSM-CAP compliance practice), 
(3.3) if needed, unique ITM procedure descriptions, 
(3.4) manufacturer recommendations (in manufacturer\'s manuals, bulletins, and so forth, which may give details on things like replacing a seal or operating testing equipment), and 
(3.5) industry standards (for non-destructive testing, fitness-for-service, and so forth). 
(4) Redundant ITM, automated controls, and the hazardous-substance procedures and safe-work practices should catch mistakes in these less-hazardous ITM tasks. 
(4.1) For example, '.HAZSUB_PROCESS_NAME_ZFPF.' opening permits should include leak checks before return-to-service. 
(4.2) Another example, automated or human checks of oil or crankcase temperature before re-starting a compressor after maintenance should catch a failure to reconnect an oil heater. 
(4.3) To meet good practices, inspection and testing must be done often enough, or with enough quality control, to assure that the likelihood and severity of one incorrect result does not create unacceptable risks. 
(5) The PSM-CAP compliance practices for ITM, like all PSM-CAP compliance practices, may be modified based on documented operating experience or manufacturer recommendations. 
(6) "inspect/note/fix" means 
(6.1) check for and resolve (safely and with any needed assistance) access or egress problems, hazards, or inadequate lighting in areas where the inspection will be done, 
(6.2) complete lockout-tagout and other safe-work practices, as needed for the inspection, 
(6.3) inspect using sight, hearing, touch, or smell and, if needed, common tools, such as belt-tension gauges, flashlights, magnifying glasses, or mirrors, 
(6.4) record the status, especially any problems and at least "ok" if you found no problems, but 
(6.5) only proceed with a fix if qualified and authorized by the responsible individual(s), per the Owner/Operator management system. 
(7) ITM frequencies may typically be modified within at least plus or minus one fourth (25%), but typically not more than six months, of the recommended frequencies, unless rules (laws, codes...), manufacturer recommendations, or operating experience indicate otherwise. See IIAR 6-2019, Table 5.2, for additional detail. 
Option: provide more details on the above topics in a practice document here.'),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
1 => array(
    'c5name' => $Zfpf->encrypt_1c('Inspection, Testing, and Maintenance Training Summary'),
    'c5number' => $Zfpf->encrypt_1c('HHHABB'),
    'c6description' => $Zfpf->encrypt_1c('This is done by on-the-job training and by sending employees to manufacturer or other training programs. Successfully completing inspection, testing, and maintenance (ITM) requires developing skill sets, such as replacing a seal or making accurate steel-thickness measurements by ultrasonic testing, so the training and its documentation is diverse and may include performance reviews needed to attain a job title, course certificates of completion, and so forth. Contractor qualification and training records address this training for contractors. Option: provide more details or include employee ITM training records in practice documents here.'),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
2 => array(
    'c5name' => $Zfpf->encrypt_1c('Overview of the '.HAZSUB_PROCESS_NAME_ZFPF.' and its hazards'),
    'c5number' => $Zfpf->encrypt_1c('HHHACC'),
    'c6description' => $Zfpf->encrypt_1c('Awareness training, often included in emergency-action plan training, is provided to all employees and contractors who complete inspection, testing, or maintenance on the '.HAZSUB_PROCESS_NAME_ZFPF.'. For contractors, this is documented in the Entrance Privileges and Records of a Contractor Individual PSM-CAP compliance practice. For employees who receive training beyond the awareness level, this may be documented with a record of training on hazardous-substance procedures and safe-work practices.'),
    'c5require_file' => $EncryptedNothing,
    'c5require_file_privileges' => $EncryptedNothing
),
3 => array(
    'c5name' => $Zfpf->encrypt_1c('Scheduling, Method, Resolution, and Recordkeeping'),
    'c5number' => $Zfpf->encrypt_1c('HHHADD'),
    'c6description' => $Zfpf->encrypt_1c('As used in PSM-CAP compliance practices, 
"work order or similar" means a computerized or paper system for 
(1) scheduling, 
(2) summarizing the tasks, needed qualifications or training, methods, parts, and materials, 
(3) recording any results, 
(4) tracking resolution of any deficiencies, and 
(5) recordkeeping. Each inspection, testing, and maintenance (ITM) record package includes at least: 
(5.1) when done, 
(5.2) who did it, 
(5.3) where and what the ITM was done to (description and a unique identifier), 
(5.4) how (method used), 
(5.5) results, including any deficiencies, and 
(5.6) resolution of all deficiencies. Once a deficiency is discovered, take actions needed to ensure safety, including, if needed, prompt removal from service. A wide range of resolution methods and timing are possible, depending on the risks posed by the deficiency.'),
    'c5require_file' => $EncryptedNothing,
    'c5require_file_privileges' => $EncryptedNothing
),
4 => array(
    'c5name' => $Zfpf->encrypt_1c('Replacement-in-kind Quality Assurance'),
    'c5number' => $Zfpf->encrypt_1c('HHHAEE'),
    'c6description' => $Zfpf->encrypt_1c('For replacement parts, use: 
(1) parts that the original equipment manufacturer (OEM) recommends as a replacement-in-kind for the old part,
(2) parts recommended by the OEM or another reputable manufacturer as a substitute that meets 
(2.1) the design specification of the old part and 
(2.2) the service requirements for this part, but
(2.3) only if this manufacturer recommendation is confirmed by the facility\'s qualified '.HAZSUB_PROCESS_NAME_ZFPF.' contractor or the responsible individual(s), per the Owner/Operator management system, or
(3) otherwise, change management is required to use a replacement part that does not meet the design specification for the old part.'),
    'c5require_file' => $EncryptedNothing,
    'c5require_file_privileges' => $EncryptedNothing
),
5 => array(
    'c5name' => $Zfpf->encrypt_1c('Insulation, Paint, and Underlying Steel Sub-practice'),
    'c5number' => $Zfpf->encrypt_1c('HHHAFF'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices. 
(1) Inspect insulated or painted steel '.HAZSUB_PROCESS_NAME_ZFPF.' piping, equipment, and supports by looking for damage at the exterior surfaces of their insulation or paint (the outermost surface exposed to the atmosphere), including between piping and supports and similar areas where rubbing may occur or moisture may accumulate. 
(2) For insulation systems -- with a protective jacket, vapor retarders, insulation, and sealants -- visible damage may include: dents, cracks, holes, frost buildups, any opening in the vapor retarder, and so forth. Thermal cameras (infrared imaging) can help discover insulation damage. The damaged insulation may include wet or frosted insulation remote from the visible damage, possibly in lower or colder areas where water may have migrated along piping and around vessels. 
(3) For paint systems, visible damage may include: blistering, pealing, rust stains, and so forth. 
(4) When an inspection discovers damage to insulation or paint (or other coatings), assessment and judgment are needed throughout the next steps to decide if the underlying steel is so damaged that it should be taken out of service immediately, pending replacement. 
(5) If and when the damaged insulation or paint can be removed, safely and without the steel immediately frosting up, complete the following. 
(5.1) If the underlying steel has moderately or severely corroded, test to measure the remaining steel thickness, for example, by ultrasonic testing. Replace or repair the steel as needed. 
(5.2) If only slight corrosion is visible on the steel\'s exterior surface, with no significant pitting, or otherwise if testing indicates adequate steel thickness remains, properly clean and prepare the steel surface, photograph it, and then apply a corrosion-inhibiting coating before re-painting or re-insulating any steel that has corroded. 
(5.3) When installing or replacing insulation, seal the vapor retarder to the steel periodically, such as near every piping weld, to create barriers to water migration away from a vapor-retarder breach. In other words, create vapor-retarder cells around vessels and within piping runs. This is better than a brushed-on vapor dam. 
(6) If removing damaged insulation is not feasible for a year or more, radiographic or pulsed eddy current non-destructive testing may be able to provide adequately-accurate measurement of remaining steel thickness under insulation, which may be wet or have ice-buildups in it. 
(7) See API 579-1/ASME FFS-1 Fitness-For-Service, latest edition, for assessing if adequate steel thickness remains. Do not use the simplified fitness-for-service information in IIAR 6-2019, Chapters 10 and 11 and Appendices A.10 and A.11, for example, when assessing corroded or damaged vessels or piping, without also consulting the latest edition of API 579-1/ASME FFS-1, Fitness-For-Service, because the simplified methods in IIAR 6-2019 do not reproduce the limitations of the API 579-1/ASME FFS-1 methods, for example, corrosion or damage near nozzles, supports, cracks, grooves, gouges, or other areas that may experience additional stress. 
(8) See ASME PCC-2, Repair of Pressure Equipment and Piping, latest edition, for temporary repair options, such as Article 3.6, Mechanical Clamp Repair, as well as long-term repair options, whose costs may be compared to replacement. 
(9) Keep steel-surface photographs and records of any steel-thickness measurements for as long as that steel remains in service.'),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
6 => array(
    'c5name' => $Zfpf->encrypt_1c('Sight Glass Sub-practice'),
    'c5number' => $Zfpf->encrypt_1c('HHHAGG'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices. 
(1) The glass and, if visible, any retaining ring or other fasteners, gaskets, and the housing -- inspect/note/fix for corrosion, misalignment, damage, or other problems, and 
(1.1) while shining a light at the glass from many different angles -- look for nicks, scratches, other imperfections, or an unclean internal surface. 
(2) Inspect/note/fix for other problems, including 
(2.1) hydraulic-shock risks too high where installed, such as in some piping for equipment with hot-gas defrost, 
(2.2) physical-damage risks too high, due to location or design, 
(2.3) linear (tubular or plate) sight glasses without 
(2.3.1) adequate protection against impacts from all directions over their entire length and 
(2.3.2) excess-flow valves, such as spring-check valves, at their inlets and vents, or 
(2.4) not suitable for service in the '.HAZSUB_PROCESS_NAME_ZFPF.'. (IIAR 9-2020 7.4.7 and industry practice).'),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
7 => array(
    'c5name' => $Zfpf->encrypt_1c('Ammonia Hose Sub-practice'),
    'c5number' => $Zfpf->encrypt_1c('HHHAGY'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices. Typically completed before each use or at least yearly if in continuous use. 
(1) For... 
    (1A) the hose, 
    (1B) its couplings, 
    (1C) the hose-to-coupling joints, 
    (1D) any valves or other fittings connected to the hose and their joints with the hose, and 
    (1E) the couplings the hose is to be attached to -- 
(1.1) inspect/note/fix for damage or other problems and 
(1.2) complete any other need ITM per manufacturer recommendations. 
(2) The manufacturer\'s markings on the hose -- verify that they show 
(2.1) the manufacturer\'s name, 
(2.2) that the manufacturer recommends the hose for 
(2.2.1) anhydrous ammonia at 
(2.2.2) a pressure equal to or greater than the design pressure of both the '.HAZSUB_PROCESS_NAME_ZFPF.' and any other vessels that the hose is to be connected to (transportation cylinders and so forth), 
(2.3) the year made, and 
(2.4) a hose expiration date that has not past. 
(3) Replace the hose if needed, based on its expiration date or the above inspection (IIAR 6-2019 11.1.4, 11.1.5, A11.1.4, and A11.1.4.1.1 and industry practice). 
See also the Association for Rubber Products Manufacturers (ARPM), IP-11-2: Hose Technical Bulletin -- Manual for Use, Maintenance, Testing, and Inspection of Anhydrous Ammonia Hose (Fifth Edition 2003 Reaffirmed 2015).'),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
8 => array(
    'c5name' => $Zfpf->encrypt_1c('Electric-Motor Bearings'),
    'c5number' => $Zfpf->encrypt_1c('HHHAHH'),
    'c6description' => $Zfpf->encrypt_1c('Following facility hazardous-substance procedures and safe-work practices: 
(1) lubricate per manufacturer recommendations or documented operating experience. 
(2) Work order or similar provides: 
(2.1) list of motors requiring lubrication and 
(2.2) for each motor the 
(2.2.1) lubricant (make and model or exact specification), 
(2.2.2) lubrication frequency, and 
(2.2.3) lubrication method (avoid overfilling, needed safe-work practices, and so forth). 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
9 => array(
    'c5name' => $Zfpf->encrypt_1c('Compressor Packages, Reciprocating, Monthly or Every 730 Run Hours'),
    'c5number' => $Zfpf->encrypt_1c('HHHAII'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices. 
(1) Shaft-seals -- measure oil-drip rate. Compare to acceptable rate based on manufacturer recommendations or documented operating experience. Replace if needed, per method in manufacturer recommendations. Monthly frequency acceptable if checks for excessive oil-drip rate from shaft seals are done in the Daily Inspection hazardous-substance procedure. Otherwise, use the frequency in the latest IIAR 6 edition. 
(2) If cylinder unloaders are installed and used -- verify functional. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
10 => array(
    'c5name' => $Zfpf->encrypt_1c('Compressor Packages, Reciprocating, Yearly or Every 10,000 Run Hours'),
    'c5number' => $Zfpf->encrypt_1c('HHHAJJ'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) compressor-safety shutoffs -- test following the Safety-Shutoffs Testing hazardous-substance procedure and calibrate, fix, or replace if needed, as applicable, the safety shutoffs for 
(1.1) high discharge pressure, 
(1.2) high discharge temperature (if any), 
(1.3) high level in vessel(s) that feed compressors, 
(1.4) low oil pressure (also verify operating oil pressure meets manufacturer recommendations), 
(1.5) low suction pressure, 
(1.6) high oil temperature, low cooling-water flow, or other loss-of-cooling shutoff (if any), and 
(1.7) any other safety shutoffs or sensors that the manufacturer recommends testing or calibrating, 
(2) electrical for/between motor controller, motor, oil heater, controls, and any local control panel -- 
(2.1) thermal scan as needed, 
(2.2) calibrate motor-current sensor, 
(2.3) inspect/note/fix other motor safeties, and 
(2.4) inspect/note/fix any connection, enclosures, timer, relay, or other problem, 
(3) foundation, mounting fasteners, and any needed vibration isolation -- inspect/note/fix if missing, loose, cracking, deteriorating, or other problem, 
(4) drives 
(4.1) if direct drive: motor-to-compressor alignment, coupling, any coupling fasteners, and shafts -- inspect/note/fix if loose, worn, excessive shaft float/end play, or other problem, measure hot alignment, and realign as needed, 
(4.2) if belt drive: belts, pulleys, fasteners, and shafts -- inspect/note/fix if missing, loose (measure belt tensions), worn, misaligned, excessive shaft float/end play, or other problem, 
(5) oil and filter maintenance -- either replace based on oil analysis and filter pressure drop or replace every 10,000 run hours (or yearly), 
(5.1) oil strainer -- inspect/note/fix and clean as needed, 
(6) oil heaters -- verify operation, 
(7) compressor internals, some or all may be inspected at reduced frequencies, per manufacturer recommendations or documented operating experience 
(7.1) gaskets, suction screens, safety heads, any unloaders, cylinder valves, valve seats, and liners, pistons, wrist pins and bushings, connecting rods, connecting-rod bearings and their crankshaft journals, and crankshaft main-bearings float -- inspect/note/fix as needed, 
(7.2) cylinder-head cooling system -- verify operation, clean strainer, and, if removing cylinder heads for other work, clean internal scale and inspect/note/fix any corrosion, 
(8) pressure gauges and thermometers verify reading correctly, and inspect/note/fix as needed, 
(9) exterior surface -- complete ITM per the Insulation, Paint, and Underlying Steel Sub-practice, 
(10) sight glasses -- complete ITM per the Sight Glass Sub-practice, 
(11) oil separator -- complete ITM per the Pressure Vessels, Yearly, PSM-CAP compliance practice,
(12) discharge check valve (or stop/check valve) -- verify holding when the compressor is off,
(13) any other tasks that the manufacturer recommends. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
11 => array(
    'c5name' => $Zfpf->encrypt_1c('Compressor Packages, Screw, Monthly or Every 730 Run Hours'),
    'c5number' => $Zfpf->encrypt_1c('HHHAKK'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices. 
(1) Shaft-seals -- measure oil-drip rate. Compare to acceptable rate based on manufacturer recommendations or documented operating experience. Replace if needed, per method in manufacturer recommendations. Monthly frequency acceptable if checks for excessive oil-drip rate from shaft seals are done in the Daily Inspection hazardous-substance procedure. Otherwise, use the frequency in the latest IIAR 6 edition. 
(2) Slide valves -- inspect/note/fix per manufacturer recommendations, and at least verify operation of capacity and volume-ratio slide valves through their full range and verify correct position at 0% and 100%. Calibrate, fix, or replace as needed. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
12 => array(
    'c5name' => $Zfpf->encrypt_1c('Compressor Packages, Screw, Yearly or Every 10,000 Run Hours'),
    'c5number' => $Zfpf->encrypt_1c('HHHALL'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) compressor-safety shutoffs -- test following the Safety-Shutoffs Testing hazardous-substance procedure and calibrate, fix, or replace if needed, as applicable, the safety shutoffs for 
(1.1) high discharge pressure, 
(1.2) high discharge temperature, 
(1.3) high level in vessel(s) that feed compressors, 
(1.4) high oil temperature, 
(1.5) low oil pressure, 
(1.6) low suction pressure, and 
(1.7) any other safety shutoffs or sensors that the manufacturer recommends testing or calibrating, 
(2) electrical for/between motor controller(s), motor(s), oil heater, controls, and local control panel -- 
(2.1) thermal scan as needed, 
(2.2) calibrate motor-current sensor, 
(2.3) inspect/note/fix other motor safeties, and 
(2.4) inspect/note/fix any connection, enclosures, timer, relay, or other problem, 
(3) foundation, mounting fasteners, and any needed vibration isolation -- inspect/note/fix if missing, loose, cracking, deteriorating, or other problem, 
(4) motor-to-compressor alignment, coupling, any coupling fasteners, and shafts -- inspect/note/fix if loose, worn, excessive shaft float/end play, or other problem, measure hot alignment, and realign as needed, 
(5) oil and filter maintenance -- either replace based on oil analysis and filter pressure drop or replace every 10,000 run hours (or yearly), 
(5.1) oil strainer -- inspect/note/fix and clean as needed, 
(6) oil heaters -- verify operation, 
(7) exterior surface -- complete ITM per the Insulation, Paint, and Underlying Steel Sub-practice, 
(8) sight glasses -- complete ITM per the Sight Glass Sub-practice, 
(9) oil separator -- complete ITM per the Pressure Vessels, Yearly, PSM-CAP compliance practice, 
(10) any other needed or manufacturer-recommended tasks, such as 
(10.1) proper oil flow in any oil return line from the coalescing side of oil separators -- inspect/note/fix,
(10.2) proper equalize-to-suction time after compressor shutoff, via a suction check-valve bypass or similar, so confirming the suction check valve is holding -- inspect/note/fix,
(10.3) discharge check valve (or stop/check valve) -- verify holding when the compressor is off,
(10.4) suction screen -- inspect/note/fix and clean as needed, 
(10.4.1) any economizer piping -- clean strainer and verify proper operation, including of any regulator, 
(10.5) oil-separator coalescing filter elements -- replace as needed or per manufacturer recommendations, 
(10.6) any liquid-injection cooling -- clean strainer as needed, inspect/note/fix any problems, verify operation, and for any pump, complete applicable ITM under the Pumps, Yearly or Every 10,000 Run Hours, PSM-CAP compliance practice, 
(10.7) any oil-cooling heat exchanger, such as plate or tube-in-shell -- complete applicable ITM per the Pressure Vessels, Yearly, PSM-CAP compliance practice. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
13 => array(
    'c5name' => $Zfpf->encrypt_1c('Compressor Packages, Screw, External Oil System, Every 5 Years or Every 45,000 Run Hours'),
    'c5number' => $Zfpf->encrypt_1c('HHHAMM'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices, if applicable: 
(1) oil pumps 
(1.1) inlet strainer -- clean, 
(1.2) motor-to-pump alignment, coupling, any coupling fasteners, and shafts -- as applicable, inspect/note/fix if loose, worn, excessive shaft float/end play, or other problem, measure alignment, and realign as needed, and 
(1.3) inspect/note/fix any problems, 
(2) any regulators, thermostatic-mixing valves, heat exchangers, and other components on the external oil circuit -- inspect/note/fix any problems; lubricate stems and exercise (close and re-open) stop valves. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
14 => array(
    'c5name' => $Zfpf->encrypt_1c('Condensers, Evaporative, Water Treatment'),
    'c5number' => $Zfpf->encrypt_1c('HHHANN'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices. 
(1) Perform and document all tests, chemical additions, water-strainer cleaning, and water sump clean-outs specified by the evaporative condenser water-treatment chemical supplier. 
(2) Verify the above water-treatment methods meet the condenser\'s manufacturer recommendations. 
(3) Note, improper water treatment can corrode tubes or decrease efficiency due to scaling or similar. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
15 => array(
    'c5name' => $Zfpf->encrypt_1c('Condensers, Evaporative, Major (each spring, typically)'),
    'c5number' => $Zfpf->encrypt_1c('HHHAOO'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) guards, shrouds, housing/enclosure exterior, and nearby piping -- inspect/note/fix if missing, loose, water leaks, air-flow obstructions, unclean, worn, cracks, dents, scratches, impact marks, or other damage, 
(2) fan drives, including any belts, pulleys, couplings, fasteners, and shafts -- 
(2.1) inspect/note/fix if missing, loose (measure belt tensions), worn, excessive shaft float/end play, or other problem, 
(2.2) shaft bearings -- lubricate as needed, 
(2.3) any gear boxes -- inspect/note/fix oil level, 
(3) fan blades, hubs, fasteners, and any balancing weights -- inspect/note/fix if cracks, loose or missing fasteners, or other problem, 
(4) water-spray pattern (nozzles...) -- inspect/note/fix as needed, 
(5) mist eliminator -- inspect/note/fix as needed, 
(6) visible-without-disassembly portions of the steel tubes -- inspect/note/fix if blemishes, corrosion, excessive scale, biofilms (slimy growths...), or, if galvanized, white powdery cells (white rust), 
(7) water sump and water-pump strainers -- inspect/note/fix and clean out as needed, 
(8) supports, from the condenser to the foundation (if independently supported), roof, or building-structural supports that carry its loads -- inspect/note/fix any 
(8.1) corrosion, wear, cracking, warping, stretching, bending, sagging, or deteriorating, 
(8.2) loose or missing fasteners, 
(8.3) loose or missing rods, hangers, or other support components, 
(8.4) roof damage, 
(8.5) damage to or problems with load-carrying building-structural supports near where any of the condenser supports are attached, 
(8.6) cracking, creep, efflorescence, sagging, or spalling of structural concrete, 
(8.7) shifting foundations, or 
(8.8) other indications of excessive loads, damage, or problems. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
16 => array(
    'c5name' => $Zfpf->encrypt_1c('Condensers, Evaporative, Minor (each fall, typically)'),
    'c5number' => $Zfpf->encrypt_1c('HHHAPP'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) guards, shrouds, housing/enclosure exterior, and nearby piping -- inspect/note/fix if missing, loose, water leaks, air-flow obstructions, unclean, worn, cracks, dents, scratches, impact marks, or other damage, 
(2) fan drives, including any belts, pulleys, couplings, fasteners, and shafts -- 
(2.1) inspect/note/fix if missing, loose (measure belt tensions), worn, excessive shaft float/end play, or other problem, 
(2.2) shaft bearings -- lubricate as needed, 
(2.3)  any gear boxes -- inspect/note/fix oil level, 
(3) fan blades, hubs, fasteners, and any balancing weights -- inspect/note/fix if cracks, loose or missing fasteners, or other problem. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
17 => array(
    'c5name' => $Zfpf->encrypt_1c('Pumps, Yearly or Every 10,000 Run Hours'),
    'c5number' => $Zfpf->encrypt_1c('HHHAQQ'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) pump-safety shutoffs -- test and calibrate or replace if needed, as applicable, the safety shutoffs for 
(1.1) low level in tank or vessel that feeds pump (for float switches on '.HAZSUB_PROCESS_NAME_ZFPF.' vessels, follow the Safety-Shutoffs Testing hazardous-substance procedure), 
(1.2) low seal oil, 
(1.3) vibration/cavitation, 
(2) electrical for/between any motor controller, motor, oil heater, controls, and local control panel -- 
(2.1) thermal scan as needed, 
(2.2) calibrate any motor-current sensor, 
(2.3) inspect/note/fix other motor safeties, and 
(2.4) inspect/note/fix any connection, enclosures, timer, relay, or other problem, 
(3) any foundation, mounting fasteners, and any needed vibration isolation -- inspect/note/fix if missing, loose, cracking, deteriorating, or other problem, 
(4) if belt driven or has a motor-to-pump shafts coupling: any belts and pulleys, couplings, fasteners, and shafts -- inspect/note/fix if loose (measure belt tensions), misaligned, worn, excessive shaft float/end play, or other problem, 
(5) any oil heaters -- verify operation, 
(6) exterior surface -- complete ITM per the Insulation, Paint, and Underlying Steel Sub-practice. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
18 => array(
    'c5name' => $Zfpf->encrypt_1c('Vibration Analysis'),
    'c5number' => $Zfpf->encrypt_1c('HHHARR'),
    'c6description' => $Zfpf->encrypt_1c('Complete if needed per manufacturer recommendations or documented operating experience. Common for twin-screw compressors. Rare for single-screw and reciprocating compressors; however, vibration analysis is often helpful for their motors, and so it is sometimes also done on single-screw compressors. Follow facility hazardous-substance procedures and safe-work practices. See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
19 => array(
    'c5name' => $Zfpf->encrypt_1c('Ammonia Purging and Purity, Yearly'),
    'c5number' => $Zfpf->encrypt_1c('HHHASS'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices. 
(1) Purity. 
(1.1) If any piping or equipment has been, over the last year, routinely below atmospheric pressure, assess the purity of the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' refrigerant, by its pressure-temperature relationship and if needed by water-content testing. 
(1.2) Purge out air, remove water, remove other contamination, or replace the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' refrigerant as needed to restore a safe and practical pressure-temperature relationship (IIAR 6-2019 14.1-Testing-b, 15.1-Testing-a, and Appendices A.14.1, A.15.1, and C). 
(1.3) See also the results of compressor oil analysis. 
(2) Automatic purging. If an automatic purger is installed -- inspect, test, and maintain it per manufacturer recommendations and industry standards, including: 
(2.1) the manufacturer-supplied enclosure or similar -- inspect/note/fix if loose, unclean, worn, cracks, dents, scratches, impact marks, or other damage, 
(2.2) mounting for manufacturer-supplied enclosure or similar -- inspect/note/fix any looseness or other problems, 
(2.3) any guarding or barricades for protection from impacts and traffic -- inspect/note/fix if missing, damaged, or inadequate, 
(2.4) visible-without-disassembly exterior surfaces of integral and nearby piping and small pressure vessels without ASME stamps -- complete ITM per the Insulation, Paint, and Underlying Steel Sub-practice, 
(2.5) solenoid valve(s) on foul-gas piping (purge points) -- verify function, for example, by using solenoid on/off/auto switches to open one purge point at a time and verify foul-gas flow to the automatic purger, 
(2.6) strainers, such as upstream of solenoid valves -- clean if needed, 
(2.7) controls and user interfaces, including indicator lights -- inspect/note/fix any problems, 
(2.8) water-bubble column -- clean if needed, 
(2.9) drain oil if needed, and 
(2.10) service or replace internal solenoid valves and other internal parts as needed and per manufacturer recommendations. 
(3) Manual purging. If an automatic purger isn\'t installed -- verify that the current purging method and schedule is maintaining the normal '.HAZSUB_NAME_ADJECTIVE_ZFPF.' pressure-temperature relationship, and so keeping condensing pressures from getting too high. Adjust as needed. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
20 => array(
    'c5name' => $Zfpf->encrypt_1c('Forced-Air Evaporators (for air cooling), Every 6 Months'),
    'c5number' => $Zfpf->encrypt_1c('HHHATT'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) guards, shrouds, housing/enclosure exterior, and nearby piping -- inspect/note/fix if missing, loose, air-flow obstructions, unclean, worn, cracks, dents, scratches, impact marks, or other damage, 
(2) fan drives, including any belts, pulleys, couplings, fasteners, and shafts -- 
(2.1) inspect/note/fix if missing, loose (measure belt tensions), worn, excessive shaft float/end play, or other problem, 
(2.2) shaft bearings -- lubricate as needed, 
(3) fan blades, hubs, fasteners, and any balancing weights -- inspect/note/fix if cracks, loose or missing fasteners, or other problem, 
(4) any drip pan -- inspect/note/fix if obstructed, such as water or ice accumulating in the pan, 
(5) any ammonia-in-air safety systems that sample or test the room being cooled by the evaporator -- 
(5.1) calibrate detectors, as needed, per manufacturer instructions, and 
(5.2) functionally test sensors, detectors, connected alarm systems, and their responses (shutoffs, audio/visual alarms, phone calls or electronic notifications...),
(6) cooling effectiveness -- drain oil, as needed (for example before the bottom of the coil has no frost because it accumulated oil), following the oil-draining hazardous-substance procedure, and inspect/note/fix any other source of reduced cooling. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
21 => array(
    'c5name' => $Zfpf->encrypt_1c('Forced-Air Evaporators (for air cooling), Yearly'),
    'c5number' => $Zfpf->encrypt_1c('HHHAUU'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) supports, from the evaporator package\'s housing to the foundation (if independently supported), floor, or building-structural supports that carry its loads, including any grating and flooring for mechanical penthouses -- inspect/note/fix any 
(1.1) corrosion, wear, cracking, warping, stretching, bending, sagging, or deteriorating, 
(1.2) loose or missing fasteners, 
(1.3) loose or missing rods, hangers, or other support components, 
(1.4) roof or floor damage, 
(1.5) damage to or problems with load-carrying building-structural supports near where any of the forced-air evaporator supports are attached, 
(1.6) cracking, creep, efflorescence, sagging, or spalling of structural concrete, 
(1.7) shifting foundations, or 
(1.8) other indications of excessive loads, damage, or problems, 
(2) any guarding or barricades for protection from impacts and traffic -- inspect/note/fix if missing, damaged, or inadequate, 
(3) any defrosting system, by observing one complete defrost from before the liquid-supply solenoid valve closes until after it reopens and the fans start -- inspect/note/fix 
(3.1) odd sounds, 
(3.2) excessive vibrations, 
(3.3) "not working right", 
(3.4) any drip-pan or condensate-drain piping heating problems, or 
(3.5) control-valve and fan operation sequence or timing are unsafe, not optimal, or inconsistent with the as-built design documents, 
(4) visible-without-disassembly portions of evaporator coils and their fins -- inspect/note/fix if unclean, corrosion, bent fins, or other problem, 
(5) any integral or nearby pressure vessel(s), such as surge drums or oil pots -- complete ITM per the Pressure Vessels, Yearly, PSM-CAP compliance practice. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
22 => array(
    'c5name' => $Zfpf->encrypt_1c('Make-up Air Units (that include evaporator coils), Weekly or As Needed'),
    'c5number' => $Zfpf->encrypt_1c('HHHAVV'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) air filters -- inspect/note/fix if unclean or other problem, replace if needed, 
(2) burner flame -- inspect/note/fix any problems, 
(3) damper -- inspect/note/fix any linkage or other problems, 
(4) volumetric air flow -- verify adequate. 
Modify frequency as needed based on documented operating experience. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
23 => array(
    'c5name' => $Zfpf->encrypt_1c('Make-up Air Units (that include evaporator coils), Every 6 Months'),
    'c5number' => $Zfpf->encrypt_1c('HHHAWW'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) guards, shrouds, housing/enclosure exterior, and nearby piping -- inspect/note/fix if missing, loose, air-flow obstructions, unclean, worn, cracks, dents, scratches, impact marks, or other damage, 
(2) fan drives, including any belts, pulleys, couplings, fasteners, and shafts -- 
(2.1) inspect/note/fix if missing, loose (measure belt tensions), worn, excessive shaft float/end play, or other problem, 
(2.2) shaft bearings -- lubricate as needed, 
(3) fan blades, hubs, fasteners, and any balancing weights -- inspect/note/fix if cracks, loose or missing fasteners, or other problem, 
(4) air filters -- inspect/note/fix if unclean or excessive pressure drop across the filter (differential pressure too high), replace if needed, 
(5) any ammonia-in-air, smoke, or burner safety systems that sample or test (5A) the unit\'s outlet air stream, (5B) the room(s) being supplied with air, (5C) the room where the unit is located, or (5D) any burner or flame -- 
(5.1) calibrate detectors, as needed, per manufacturer instructions, and 
(5.2) functionally test sensors, detectors, connected alarm systems, and their responses (shutoffs, audio/visual alarms, phone calls or electronic notifications...), 
(6) cooling effectiveness -- drain oil, as needed (for example before the bottom of the coil has no frost because it accumulated oil), following the oil-draining hazardous-substance procedure, and inspect/note/fix any other source of reduced cooling. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
24 => array(
    'c5name' => $Zfpf->encrypt_1c('Make-up Air Units (that include evaporator coils), Yearly'),
    'c5number' => $Zfpf->encrypt_1c('HHHAXX'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) supports, from the make-up air unit\'s enclosure to the foundation (if independently supported), roof, floor, or building-structural supports that carry its load -- inspect/note/fix any 
(1.1) corrosion, wear, cracking, warping, stretching, bending, sagging, or deteriorating, 
(1.2) loose or missing fasteners, 
(1.3) loose or missing rods, hangers, or other support components, 
(1.4) roof or floor damage, 
(1.5) damage to or problems with load-carrying building-structural supports near where any of the make-up air unit supports are attached, 
(1.6) cracking, creep, efflorescence, sagging, or spalling of structural concrete, 
(1.7) shifting foundations, or 
(1.8) other indications of excessive loads, damage, or problems, 
(2) any guarding or barricades for protection from impacts and traffic -- inspect/note/fix if missing, damaged, or inadequate, 
(3) any defrosting system, by observing one complete defrost from before the liquid-supply solenoid valve closes until after it reopens and the fans start -- inspect/note/fix 
(3.1) odd sounds, 
(3.2) excessive vibrations, 
(3.3) "not working right", 
(3.4) any drip-pan or condensate-drain piping heating problems, or 
(3.5) control-valve and fan operation sequence or timing are unsafe, not optimal, or inconsistent with the as-built design documents, 
(4) any drip pan -- inspect/note/fix if obstructed, such as water or ice accumulating in the pan, 
(5) visible-without-disassembly portions of evaporator coils and their fins -- inspect/note/fix if unclean, corrosion, bent fins, or other problem, 
(6) any integral or nearby pressure vessel(s), such as surge drums or oil pots -- complete ITM per the Pressure Vessels, Yearly, PSM-CAP compliance practice. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
25 => array(
    'c5name' => $Zfpf->encrypt_1c('Liquid Cooling or Heating Equipment, Every 6 Months'),
    'c5number' => $Zfpf->encrypt_1c('HHHAYY'),
    'c6description' => $Zfpf->encrypt_1c('These '.HAZSUB_NAME_ADJECTIVE_ZFPF.' evaporators, for cooling, or condensers, for heating, include chillers, ice builders, heaters for underfloor heat systems, and also tanks, silos, or other vessels cooled by jackets or internal plates or coils. Their heat exchangers include coils, plates, tubes, or pressure vessels, such as shell-and-tube, plate-and-frame, and also plates or tubes, in various shapes, either inside or wrapped around tanks, silos, or other liquid containers, some of which may have scraped or swept surfaces, build layers of ice, or have liquid films flowing over them (falling film). 
Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) exterior of the manufacturer-supplied package or similar, such as the enclosure or tank exterior, and nearby piping -- inspect/note/fix if loose, unclean, worn, cracks, dents, scratches, impact marks, or other damage, 
(2) cooling or heating effectiveness -- verify and drain oil, as needed (for example before the bottom of the heat-exchanger has no frost because it accumulated oil), following the oil-draining hazardous-substance procedure, and inspect/note/fix any other source of reduced cooling, 
(3) the liquid being cooled or heated -- 
(3.1) complete any needed testing and treatment based on operating experience, quality standards, chemical-supplier recommendations, or equipment-manufacturer recommendations (for example, for glycol, the freeze-point, via refractive index or specific gravity, pH, any corrosion inhibitors, and any biocides), 
(3.2) review results of any needed more-frequent testing from the prior six months, and 
(3.3) inspect/note/fix any problems, 
(4) any drip pan -- inspect/note/fix if obstructed, such as water or ice accumulating in the pan, 
(5) any ammonia-in-air safety systems that sample or test the room where the liquid cooling or heating equipment is located -- 
(5.1) calibrate detectors, as needed, per manufacturer instructions, and 
(5.2) functionally test sensors, detectors, connected alarm systems, and their responses (shutoffs, audio/visual alarms, phone calls or electronic notifications...), 
(6) complete any other needed ITM, based on operating experience or manufacturer recommendations. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
26 => array(
    'c5name' => $Zfpf->encrypt_1c('Liquid Cooling or Heating Equipment, Yearly'),
    'c5number' => $Zfpf->encrypt_1c('HHHAZZ'),
    'c6description' => $Zfpf->encrypt_1c('For a description these evaporators, see the Liquid Cooling or Heating Equipment, Every 6 Months, PSM-CAP compliance practice. Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) supports, from the manufacturer-supplied package or similar, such as the enclosure or tank, to the foundation (if independently supported), roof, floor, or building-structural supports that carry its load -- inspect/note/fix any 
(1.1) corrosion, wear, cracking, warping, stretching, bending, sagging, or deteriorating, 
(1.2) loose or missing fasteners, 
(1.3) loose or missing rods, hangers, or other support components, 
(1.4) roof or floor damage, 
(1.5) damage to or problems with load-carrying building-structural supports near where any of the liquid cooling or heating equipment supports are attached, 
(1.6) cracking, creep, efflorescence, sagging, or spalling of structural concrete, 
(1.7) shifting foundations, or 
(1.8) other indications of excessive loads, damage, or problems, 
(2) any guarding or barricades for protection from impacts and traffic -- inspect/note/fix if missing, damaged, or inadequate, 
(3) any defrosting or clean-in-place systems, by observing one complete defrost and/or cleaning from before the liquid-supply solenoid valve closes until after it reopens and the liquid-cooling equipment fully re-starts -- inspect/note/fix 
(3.1) odd sounds, 
(3.2) excessive vibrations, 
(3.3) "not working right", 
(3.4) any drip-pan or condensate-drain piping heating problems, or 
(3.5) controls or their results (stopping refrigeration, starting heating or cleaning, and so forth) sequence or timing are unsafe, not optimal, or inconsistent with the as-built design documents, 
(4) any sight glasses -- complete ITM per the Sight Glass Sub-practice, 
(5) any integral or nearby pressure vessel(s), such as surge drums, oil pots, the shells of shell-and-tube heat exchangers, and some large plate heat exchangers -- complete ITM per the Pressure Vessels, Yearly, PSM-CAP compliance practice. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
27 => array(
    'c5name' => $Zfpf->encrypt_1c('Pressure Vessels, Yearly'),
    'c5number' => $Zfpf->encrypt_1c('HHHBAA'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) supports, from the pressure vessel to the foundation (if independently supported), roof, floor, or building-structural supports that carry its load -- inspect/note/fix any 
(1.1) corrosion, wear, cracking, warping, stretching, bending, sagging, or deteriorating, 
(1.2) loose or missing fasteners, 
(1.3) loose or missing rods, hangers, or other support components, 
(1.4) roof or floor damage, 
(1.5) damage to or problems with load-carrying building-structural supports near where any of the pressure-vessel supports are attached, 
(1.6) cracking, creep, efflorescence, sagging, or spalling of structural concrete, 
(1.7) shifting foundations, or 
(1.8) other indications of excessive loads, damage, or problems, 
(2) any guarding or barricades for protection from impacts and traffic -- inspect/note/fix if missing, damaged, or inadequate, 
(3) exterior surface, including nozzles for piping connections -- complete ITM per the Insulation, Paint, and Underlying Steel Sub-practice, 
(4) any sight glasses -- complete ITM per the Sight Glass Sub-practice, 
(5) nameplate -- 
(5.1) if the nameplate cannot be found, consult with the facility\'s qualified '.HAZSUB_PROCESS_NAME_ZFPF.' contractor or the responsible individual(s), per the Owner/Operator management system, about where to look for it under insulation or otherwise how to proceed, 
(5.2) either find the oldest available photographs of the nameplate or photograph it now (including close-ups showing all text and a photo showing the entire side of the pressure vessel with the nameplate) and file the new photographs for future inspections, 
(5.3) compare the nameplate to the oldest-available photographs of it or otherwise to the vessel manufacturer\'s data report to The National Board of Boiler and Pressure Vessel Inspectors, or equivalent outside the USA, 
(5.4) note anything causing the nameplate to become illegible and consult with the facility\'s qualified '.HAZSUB_PROCESS_NAME_ZFPF.' contractor or the responsible individual(s), per the Owner/Operator management system, about how to protect the nameplate from further damage, 
(6) liquid level sensors, controls, and their responses (except for compressor and pump shutoff responses, which are checked by the compressor and pump ITM) -- test, observe responses, calibrate if needed, and inspect/note/fix any problems, 
(7) any liquid supply piping, by observing one complete filling from opening to closing of the liquid-supply solenoid valve -- inspect/note/fix any odd sounds or excessive vibrations, shaking, or movement, 
(8) any transfer system (including liquid piping, any hot-gas piping, any pumps, and any pressure vessels) by observing one complete transfer -- inspect/note/fix any 
(8.1) odd sounds, 
(8.2) excessive vibrations, shaking, or movement, 
(8.3) "not working right", 
(8.4) any transfer counter not working, or 
(8.5) maintenance needed, 
(9) bolted joints for access hatches, such as on oil separators -- 
(9.1) inspect/note/fix any missing, loose, bent, or stretched bolts, any misalignment, or other problem, 
(9.2) verify bolt tightness by attempting to slightly further tighten with an appropriately sized wrench or similar, 
(10) drain oil, if needed, following the oil-draining hazardous-substance procedure. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
28 => array(
    'c5name' => $Zfpf->encrypt_1c('Piping, All Labeling, and Exercising Emergency Valves, Yearly'),
    'c5number' => $Zfpf->encrypt_1c('HHHBBB'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) supports, from the piping\'s insulation protective jacket or its paint to the foundation (if independently supported), roof, floor, or building-structural supports that carry its load -- inspect/note/fix 
(1.1) any missing saddles (below insulation), 
(1.2) any piping that rests directly on fixed supports so that its paint is rubbing off, 
(1.3) any evidence of past movement (such as piping not on the supports, crooked or twisted piping or supports, and so forth), 
(1.4) corrosion, wear, cracking, warping, stretching, bending, sagging, or deteriorating, 
(1.5) loose or missing fasteners, 
(1.6) loose or missing rods, hangers, or other support components, 
(1.7) roof or floor damage, 
(1.8) damage to or problems with load-carrying building-structural supports near where any of the piping supports are attached, 
(1.9) cracking, creep, efflorescence, sagging, or spalling of structural concrete, 
(1.10) shifting foundations, or 
(1.11) other indications of excessive loads, damage, or problems,  
(2) any guarding or barricades for protection from impacts and traffic -- inspect/note/fix if missing, damaged, or inadequate, 
(3) exterior surface, including valve bodies -- complete ITM per the Insulation, Paint, and Underlying Steel Sub-practice, 
(4) any sight glasses -- complete ITM per the Sight Glass Sub-practice, 
(5) bolted joints, such as with flanges -- inspect/note/fix any missing, loose, bent, or stretched bolts, any misalignment, or other problem, 
(6) threaded joints -- inspect/note/fix if visibly loose or other problem, 
(7) seismic (7A) joints, (7B) restraints, or (7C) pipe penetrations through room or building envelopes, such as walls and floors, which need to be well sealed while allowing for differential movement during earthquakes -- 
(7.1) complete ITM per manufacturer recommendations or as-built design documents and 
(7.2) inspect/note/fix any problems, 
(8) piping openings to the atmosphere, for '.HAZSUB_NAME_ADJECTIVE_ZFPF.' charging, oil draining, purging, sampling, and so forth -- inspect/note/fix that accidental or malicious '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases are not too easy, such as a piping opening 
(8.1) sealed only with a valve and not also with a threaded cap or plug or 
(8.2) unlocked and in an place that thieves or vandals could access without more trouble than cutting a lock, 
(9) oil-draining locations -- inspect/note/fix that each one has a self-closing valve installed or otherwise that procedures are in place to properly install a temporary self-closing valve when oil is drained, 
(10) labeling of (10A) pipes, (10B) equipment, including pressure vessels, and (10C) valves (unless, for valves, the facility\'s lockout-tagout policy calls for pre-work valve tagging) -- inspect/note/fix for 
(10.1) labels, markings, or tags that are 
(10.1.1) missing (not present where they should be, including marking any check valves under insulation), 
(10.1.2) illegible (because faded, worn, unclean, and so forth), or 
(10.1.3) inconsistent with the '.HAZSUB_PROCESS_NAME_ZFPF.' piping and instrumentation diagram (P&amp;ID), and 
(10.2) compliance with either 
(10.2.1) the edition of IIAR Bulletin 114, Guidelines for Identification of Ammonia Refrigeration Piping and System Components, that the facility intends to follow (such as 1991, 2014, 2017, or 2019), or otherwise 
(10.2.2) the written description of the facility\'s unique labeling method, 
(11) the King valve and other hand-operated stop valves uniquely described in the facility\'s leak mitigation and emergency shutdown procedures -- 
(11.1) inspect/note/fix any labels, markings, or tags that are inconsistent with the leak mitigation and emergency shutdown procedures or P&amp;ID, 
(11.2) inspect/note/fix if any of these valves are not readily accessible to a person wearing a self-contained breathing apparatus (SCBA), such as operable by chain or hand wheel from a safe walking-working surface, unless remotely-controlled valves described in leak mitigation and emergency shutdown procedures give the same outcome as operating these valves (IIAR 9-2020 7.3.3), and 
(11.3) following the Operating Refrigeration Valve Stems sub-procedure, lubricate stems and exercise (close and re-open) these valves (IIAR 6-2019 11.1.6). 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
29 => array(
    'c5name' => $Zfpf->encrypt_1c('Pressure-relief systems, Yearly'),
    'c5number' => $Zfpf->encrypt_1c('HHHBCC'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) each pressure-relief device (PR device, typically a valve, sometimes in combination with an upstream rupture disk) -- inspect/note/fix for 
(1.1) chattering, 
(1.2) frosted (possible leak-by), 
(1.3) visibly tampered with, broken ASME seal, or damaged, or 
(1.4) it opened at some time in the past, if equipped with an indicator, such as the device\'s LED indicator, a flow flag, or a gauge between a rupture disk and a pressure-relief valve, 
(the fix for these is replacing the PR device, either after pumping out and isolating the pressure vessel or other equipment that it protects or, if the PR device is installed on a 3-way valve, after verifying that the 3-way valve is positioned so that the PR device is not protecting anything because the 3-way valve is blocking flow to the PR device\'s inlet, and, like any "fix", this fix can only be done if qualified and authorized by the responsible individual(s), per the Owner/Operator management system), 
(2) nameplate on each PR device -- 
(2.1) replace the PR device if no nameplate is found on it, 
(2.2) note anything causing the nameplate to become illegible and consult with the facility\'s qualified '.HAZSUB_PROCESS_NAME_ZFPF.' contractor or the responsible individual(s), per the Owner/Operator management system, about how to protect the nameplate from further damage, 
(3) time in service of each PR device -- 
(3.1) based on the facility\'s records or the "replace by" tag on the PR device, determine if the PR device needs to be replaced (every 5-years unless a facility-specific testing program is in place or its discharge is back into the '.HAZSUB_PROCESS_NAME_ZFPF.') and 
(3.2) confirm any "replace by" tag matches any facility records about the PR device\'s installation date (need at least one or the other), 
(4) if there are any stop valves between a PR device and the pressure vessel or other equipment that it protects, inspect/note/fix their labels, seals, and other controls for consistency with the facility\'s "tag-and-seal open" program, and check if this program was approved by the authority having jurisdiction (ASME Boiler and Pressure Vessel Code, Section VIII, Division 1, Appendix M, "Stop valves located in the pressure relief path" and IIAR 2-2019 1.3.2 and 15.4.1), 
(5) if there are any stop valves between a PR device outlet and the atmosphere or other intended discharge location, inspect/note/fix their labels, seals, and other controls for consistency with the facility\'s "tag-and-seal open" program (IIAR 2-2019 15.4.1), 
(6) relief-vent (RV) piping -- complete ITM consistent with applicable portions of the Piping, All Labeling, and Exercising Emergency Valves, Yearly, PSM-CAP Compliance Practice, 
(7) PR device outlet(s) or relief-vent (RV) piping -- inspect/note/fix any problems, including 
(7.1) located where water may condense in it, such as in cooled rooms, 
(7.2) has low spots likely to gather water or anything that may hinder relief flow or cause shocks, 
(7.3) has no provisions for safely draining water where it might accumulate, such as from a trap below each discharge-to-atmosphere outlet, 
(7.4) has been visibly blocked or tampered with, 
(7.5) discharge-to-atmosphere outlet is unsafe, including 
(7.5.1) less than 20 feet (6.1 m) in any direction from air intakes or building openings, such as windows or doors, 
(7.5.2) both less than 20 feet (6.1 m) horizontally from and also less than 7.25 feet (2.2 m) above walking-working surfaces, such as catwalks and most roofs, 
(7.5.3) less than 15 feet (4.6 m) above "grade", such as surfaces where vehicles may drive, 
(7.5.4) directed towards where people may be walking or working (best if vertically upward, such as with a tractor cap), or 
(7.5.5) outlet likely to allow water entry, including a pressure-relief valve without RV piping (outlet open to the atmosphere) and without a manufacturer-supplied blow-out plastic plug in its outlet, 
(8) unless documented operating experience has shown that water traps under RV-piping discharge-to-atmosphere outlets don\'t need to have water drained from them -- 
(8.1) inspect/note/fix that these traps have a safe and functional valve for draining water, 
(8.2) following the facility\'s safe-work practices for opening RV piping, drain any water, and 
(8.3) note if the frequency of this draining needs to be increased (or the discharge-to-atmosphere outlet protection from water entry needs to be improved), based on the amount of water drained. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
30 => array(
    'c5name' => $Zfpf->encrypt_1c('Pressure-relief devices, Every 5 Years'),
    'c5number' => $Zfpf->encrypt_1c('HHHBDD'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices. 
(1) Replace each pressure-relief device (PR device) after five years in service, unless a facility-specific testing program is in place or its discharge is back into the '.HAZSUB_PROCESS_NAME_ZFPF.'. 
(2) Before replacing, confirm that the year made shown on the nameplate of the replacement PR device isn\'t too long ago, per Owner/Operator policies, industry standards, or the applicable building and mechanical codes. 
(3) Once installed, punch the "replace by" tag and update the facility\'s records. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
31 => array(
    'c5name' => $Zfpf->encrypt_1c('Valve Exercising and Emergency-Valve Testing, Every 5 Years'),
    'c5number' => $Zfpf->encrypt_1c('HHHBEE'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices, including the Operating Refrigeration Valve Stems sub-procedure. 
(1) All hand-operated stop valves -- lubricate stems and exercise (close and re-open). 
(2) The King valve and other hand-operated stop valves uniquely described in the facility\'s leak mitigation and emergency shutdown procedures -- verify no leak-by (valve holding while closed) (IIAR 6-2019 11.1.6). 
(3) Any automatic King valve -- 
(3.1) verify no leak-by (valve holding while closed) (IIAR 6-2019 11.1.6) and 
(3.2) test its safeguards for avoiding shocks when re-opening (pressure equalization); a safe test method depends on the type of valve and safeguards. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
32 => array(
    'c5name' => $Zfpf->encrypt_1c('Ammonia Sensors and Responses, Ventilation, and E-stop, Every 6 Months'),
    'c5number' => $Zfpf->encrypt_1c('HHHBFF'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices, including the Safety-System Testing or Disabling for Maintenance safe-work practice, as applicable to the refrigerating-machinery room, pit, tunnel, or other area served by the ammonia sensors and responses, ventilation, or emergency-stops (the room): 
(1) all inlet and outlet fans, screens, louvers, dampers, or similar -- inspect/note/fix for 
(1.1) guard missing (for drives, fans...), 
(1.2) air-flow obstructions (near fans, air-cooled motors... and including clogged inlet screens), 
(1.3) loose, lightweight, things that could be sucked into the fans, 
(1.4) new exterior damage, such as cracks, dents, scratches, or impact marks, 
(1.5) anything but non-sparking blades on fans, 
(1.6) anything but totally-enclosed motors, unless the motor is very unlikely to get exposed to a flammable atmosphere, for example outdoors and not in the exhaust-air stream, or 
(1.7) any other visible problem, 
(2) on/auto switches for the room\'s emergency ventilation (the switch) -- 
(2.1) inspect/note/fix any damage or other problem with the switch and then 
(2.2) turn the switch to "on" and inspect/note/fix all emergency-ventilation fans, louvers, and dampers for, as applicable, 
(2.2.1) rotating wrong way, 
(2.2.2) excessive vibrations, 
(2.2.3) odd sounds, 
(2.2.4) louvers or dampers don\'t open properly, 
(2.2.5) air-flow-verification device(s), such as sail switch, vane switch, or anemometer, indicates inadequate air flow, 
(2.2.6) "not working right" (including alarms), or 
(2.2.7) maintenance needed, based on run hours or condition, and then 
(2.3) turn the switch back to "auto" (see lockout-tagout and Safety-System Testing or Disabling for Maintenance safe-work practices), 
(3) fan drives, including any belts, pulleys, couplings, fasteners, and shafts -- 
(3.1) inspect/note/fix if missing, loose (measure belt tensions), worn, excessive shaft float/end play, or other problem, 
(3.2) shaft bearings -- lubricate as needed, 
(4) fan blades, hubs, fasteners, and any balancing weights -- inspect/note/fix if cracks, loose or missing fasteners, or other problem, 
(5) ammonia sensors, detectors, and alarm systems, including any computer controls -- 
(5.1) inspect/note/fix their enclosures for damage, such as cracks, dents, scratches, or impact marks, 
(5.2) calibrate (in test mode so alarms don\'t sound, if practical), 
(5.3) inspect/note/fix any problems, such as replace ammonia sensors if needed, 
(6) E-stops (emergency-stops) for the '.HAZSUB_PROCESS_NAME_ZFPF.' -- inspect/note/fix for any tampering or damage, 
(7) signage -- inspect/note/fix for the following signs or equivalent, if applicable 
(7.1) outside every door 
(7.1.1) "REFRIGERATION MACHINERY ROOM, AUTHORIZED PERSONNEL ONLY" [yellow], 
(7.1.2) "CAUTION AMMONIA, R-717" [yellow], 
(7.1.3) "CAUTION EYE AND EAR PROTECTION REQUIRED IN THIS AREA" [yellow], and 
(7.1.4) the NFPA diamond, showing Health (blue) = 3, Fire (red) = 3, Reactivity (yellow) = 0, and Special (white) = [leave blank], except, for outdoor equipment, Fire (red) = 1, see National Fire Protection Association (NFPA) Standard 704, 
(7.2) "!WARNING, WHEN ALARMS ARE ACTIVATED, AMMONIA HAS BEEN DETECTED, 1. LEAVE ROOM IMMEDIATELY, 2. DO NOT ENTER EXCEPT BY TRAINED AND AUTHORIZED PERSONNEL, 3. DO NOT ENTER WITHOUT PERSONAL PROTECTIVE EQUIPMENT" [orange] next to all "ammonia concentration in air is unsafe" audible and visible alarms, which are required outside every door into the room and at least one inside the room, 
(7.3) "REFRIGERATION MACHINERY SHUTDOWN, EMERGENCY USE ONLY, BREAK GLASS TO ACTIVATE" [orange; substitute actual activation method for "break glass"], next to each E-stop, at least one E-stop is required, located near the room and where reasonably likely to remain safe and accessible during '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases, 
(7.4) "REFRIGERATING-MACHINERY ROOM VENTILATION, EMERGENCY USE ONLY" [orange], next to all the tamper resistant, on/auto switches for the room\'s emergency ventilation -- at least one is required, located near the room and where reasonably likely to remain safe and accessible during '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases, and 
(7.5) near the room and where reasonably likely to remain safe and accessible during '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases 
(7.5.1) a "who to call" sign with instructions for reaching 
(7.5.1.1) local emergency responders and 
(7.5.1.2) Owner/Operator representatives qualified to muster assistance in emergencies and likely to be reachable on holidays and off hours (so multiple names and phone numbers or a reliable answering service with a call list), 
(7.5.2) the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' quantity, 
(7.5.3) the type and quantity of lubricating oil in the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(7.5.4) the field test pressure(s) applied to the '.HAZSUB_PROCESS_NAME_ZFPF.', and also, sometimes optionally, 
(7.5.5) the name, address, and 24-hour service number for the facility\'s primary '.HAZSUB_PROCESS_NAME_ZFPF.' contractor, and 
(7.5.6) any required permits to operate or similar. 
(IIAR 2-2019 5.14, 5.15, 6.15, and Appendix J, IIAR 6-2019 12, IIAR 9-2020 7.2.9.1, 7.2.10, 7.3.11, and 7.3.12), 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
33 => array(
    'c5name' => $Zfpf->encrypt_1c('Ammonia Sensors and Responses, Ventilation, and E-stop, Yearly'),
    'c5number' => $Zfpf->encrypt_1c('HHHBGG'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices, including the Safety-System Testing or Disabling for Maintenance safe-work practice, as applicable to the refrigerating-machinery room, pit, tunnel, or other area served by the ammonia sensors and responses, ventilation, or emergency-stops (the room): 
(1) verify that the safety systems installed in the room are consistent with the as-built design documents (part of the PSM process-safety information), 
(2) belts for fan drives -- replace if needed or schedule replacement within 5-years of installation, 
(3) batteries for all safety systems in the room, including power-failure backup batteries -- 
(3.1) verify that the work order or similar has a complete list of the batteries that supply safety systems in the room, 
(3.2) replace any batteries for safety systems that do not have a low-battery alarm or monitored indicator, and 
(3.3) replace as needed batteries for safety systems with a low-battery alarm or monitored indicator, 
(4) enclosures, conduits, and wiring for all safety-system components -- inspect/note/fix for 
(4.1) securely mounted or located, 
(4.2) properly sealed and insulated, and 
(4.3) any damage or problems, 
(5) E-stops (emergency-stops) for the '.HAZSUB_PROCESS_NAME_ZFPF.' -- inspect/note/fix for 
(5.1) at least one E-stop is located near the room and where reasonably likely to remain safe and accessible during '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases, 
(5.2) each E-stop is a tamper-resistant, off-only, switch or button, 
(5.3) proper function by activating each E-stop, such as by unscrewing the holder for its breakable glass, and verifying that it shuts off all '.HAZSUB_NAME_ADJECTIVE_ZFPF.' 
(5.3.1) compressors, 
(5.3.2) pumps, and 
(5.3.3) normally-closed control valves that are not part of an emergency-control system (at least those located in or near the refrigerating-machinery room), such as solenoid valves for liquid and hot-gas supply and any automatic King valve, but 
(5.3.4) doesn\'t shut off safety systems, such as alarms and ventilation, 
(5.3.5) note, for compressors, E-stop function may be verified at their motor-control centers, instead of by suddenly stopping all compressors, 
(6) all ammonia sensor(s) in the room -- expose to a test gas to verify function and also inspect/note/fix for 
(6.1) sampling where ammonia concentrations could likely be highest (so not near fresh-air inlets), 
(6.2) in enough locations, considering the size of the room, 
(6.3) accessible for testing and maintenance, 
(6.4) connected to detectors and alarm systems adequate to, at or below the following parts per million by volume, ammonia concentrations in air (ppm) 
(6.4.1) no ppm signal, such as due to a malfunction or a power loss on the dedicated-branch electrical circuit for these systems -- notify Owner/Operator representatives qualified to muster assistance in emergencies and likely to be reachable on holidays and off hours (so alarms in an always-attended location or the alarm system can call out to multiple people\'s phones or a reliable answering service with a call list), 
(6.4.2) 25 ppm -- activate audible (15 decibels above average-ambient and 5 decibels above expected maximum-ambient sound pressure) and visible alarms in the room and outside its doors (automatic reset allowed once concentration falls below 25 ppm), 
(6.4.3) 50 ppm (preferably 25 ppm) -- notify Owner/Operator representatives, similarly to "no ppm signal" above, 
(6.4.4) 1000 ppm (preferably at or below 150 ppm, which is half the immediately dangerous to life or health, IDLH, concentration) -- activate the room\'s emergency ventilation (manual reset required), and 
(6.4.5) 40,000 ppm (preferably below 16,000 ppm, which is approximately one tenth, 10%, of the lower flammability limit, LFL, of ammonia in air, but mixtures of ammonia gas and lubricating-oil mists are more flammable than ammonia alone) -- trigger the same response as the above-described E-stop (manual reset required), 
(6.5) note, to verify that alarms are adequately audible, either 
(6.5.1) measure their sound pressure and compare to the measured average-ambient and expected maximum-ambient sound pressures or 
(6.5.2) verify that no changes in the room increased the average-ambient or expected maximum-ambient sound pressures since the last documented measurement, 
(6.6) note, for compressors, the shutoff function may be verified at their motor-control centers, instead of by suddenly stopping all compressors, 
(7) louvers and dampers -- shutoff power to them and verify that they fail open, 
(8) air-flow-verification device(s), such as sail switch, vane switch, or anemometer -- test function and inspect/note/fix any problems. 
(IIAR 2-2019 6.13, IIAR 6-2019 12, and IIAR 9-2020 7.3.12), 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
34 => array(
    'c5name' => $Zfpf->encrypt_1c('Safety Showers, Eye/Face Washes, Eyewashes, and Drench Hoses, Weekly'),
    'c5number' => $Zfpf->encrypt_1c('HHHBHH'),
    'c6description' => $Zfpf->encrypt_1c('Complete the following per facility hazardous-substance procedures and safe-work practices. 
(1) Each safety shower, eye/face wash, eyewash, or drench hose (the unit) -- inspect/note/fix for 
(1.1) access obstructions, within 55 feet of the unit, 
(1.2) inadequate lighting, within 55 feet of the unit, 
(1.3) not identified with a highly-visible sign, and 
(1.4) water leaks or other problems, while off. 
(2) Each safety shower, eye/face wash, eyewash, or drench hose -- activate and inspect/note/fix for 
(2.1) water flow starts within one second, lifting off nozzle covers for eye/face washes, eyewashes, and drench hoses, 
(2.2) water flow seems safe and adequate, required minimum flows are 
(2.2.1) 20 gallons (75.7 liters) of water per minute for showers, 
(2.2.2) 3 gallons (11.4 liters) of water per minute for eye/face washes (and drench hoses used as substitutes for them), 
(2.2.3) 0.4 gallons (1.5 liters) of water per minute for eyewashes, and 
(2.2.4) the required minimum duration of the above flows is 15 minutes, 
(2.3) water from eye/face washes, eyewashes, and drench hoses is not squirting out so fast that it could injure eyes, 
(2.4) water neither too hot nor too cold, it should feel tepid (lukewarm), required temperature range is 60-100 F (16-38 C), 
(2.5) sediment or color in the water, flush until water is clear, and 
(2.6) water leaks or other problems, while activated. 
(3) Return each unit to ready-to-use status (no water flowing), wipe off dust covers, and put them back over each eye/face wash, eyewash, and drench hose nozzle. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
35 => array(
    'c5name' => $Zfpf->encrypt_1c('Safety Showers, Eye/Face Washes, Eyewashes, and Drench Hoses, Yearly'),
    'c5number' => $Zfpf->encrypt_1c('HHHBII'),
    'c6description' => $Zfpf->encrypt_1c('Inspect, test, and maintain per manufacturer recommendations, industry standards, and facility hazardous-substance procedures and safe-work practices: 
(1) each safety shower, eye/face wash, eyewash, or drench hose (the unit) -- inspect/note/fix for 
(1.1) the measured water flows meet the following required minimums 
(1.1.1) showers: 20 gallons (75.7 liters) of water per minute, 
(1.1.2) eye/face washes: 3 gallons (11.4 liters) of water per minute,
(1.1.3) eyewashes: 0.4 gallons (1.5 liters) of water per minute, and
(1.1.4) drench hoses used as substitutes for eye/face washes: 3 gallons (11.4 liters) of water per minute, 
(1.2) the unit is activated by a mechanism that 
(1.2.1) is easily located, accessible, and within reach of the unit, 
(1.2.2) for showers, is no higher than 69 inches (173.3 cm) above the floor, 
(1.2.3) a person, who could be authorized to work within 55 feet of the unit, could easily use to cause the unit to deliver the minimum-required flow within one second of first starting to operate the mechanism, and 
(1.2.4) once activated, without any more effort (hands free) causes the unit to deliver the minimum-required flow for at least 15 minutes or until purposely shut off, 
(1.3) eye/face wash and eyewash nozzles spout water 
(1.3.1) between 33 inches (83.8 cm) and 53 inches (134.6 cm) above the floor 
(1.3.2) at least 6 inches (15.3 cm) from the wall or nearest obstruction, and 
(1.3.3) not more than 8-inches (20.3 cm) above the nozzle and adequately to wash both eyes simultaneously, 
(1.4) the water flow lifts off nozzle covers for eye/face washes, eyewashes, and drench hoses, 
(1.5) shower outlet is 
(1.5.1) between 82 inches (208.3 cm) and 96 inches (243.8 cm) above the floor and 
(1.5.2) centered at least 16 inches (40.6 cm) from the nearest obstruction, 
(1.6) at 60 inches (152.4 cm) above the floor, the shower\'s water-spray pattern is at least 20 inches (50.8 cm) in diameter, and 
(1.7) the water temperature is between 60 to 100 F (16 to 38 C) when it flows out of the unit, 
(2) combination stations -- inspect/note/fix for: both the shower and the eye/face wash (or eyewash) 
(2.1) meet all applicable requirements above when working alone or simultaneously and 
(2.2) are positioned so that they can be used simultaneously by the same person, 
(3) refrigerating-machinery room (the room) access to, preferably, combination safety shower and eye/face wash stations (or otherwise combination safety shower and eyewash stations or, if grandfathered, drench hose stations) -- inspect/note/fix for 
(3.1) one station inside the room, 
(3.2) one station outside the room and a maximum 10-second travel time (typically 55 feet) from its door, and 
(3.3) additional stations as needed for a maximum 10-second travel time (typically 55 feet), without doors or other obstructions in the travel path, from all walking-working surfaces in the room to a station (IIAR 9-2020 7.3.7), 
(4) notes on decontamination 
(4.1) eyewashes may work well for materials that can damage eyes without damaging skin much, such as some powders or saw dust, 
(4.2) eye/face washes may work well for materials that can damage both eyes and skin, such as acids, caustics, and anhydrous ammonia, 
(4.3) safety showers, due to the force of their flows, may damage eyes, if used to decontaminate eyes. 
See work order or similar.'.DOC_WHERE_KEPT_ZFPF),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
),
36 => array(
    'c5name' => $Zfpf->encrypt_1c('Other equipment'),
    'c5number' => $Zfpf->encrypt_1c('HHHBZZ'),
    'c6description' => $Zfpf->encrypt_1c('This list of PSM-CAP compliance practices for inspection, testing, and maintenance (ITM) does not include all types of '.HAZSUB_PROCESS_NAME_ZFPF.' equipment, controls, and safety systems. Create additional PSM-CAP compliance practices as needed.'),
    'c5require_file' => $Encrypted_document_i1m_php,
    'c5require_file_privileges' => $EncryptedLowPrivileges
)
);
// Populate t0practice and t0practice_division
$practice_division = array(); // In case used by previously required file.
$k0practice = $Zfpf->get_highest_in_table($DBMSresource, 'k0practice', 't0practice');
foreach ($practices as $K => $V) {
    $V['k0practice'] = ++$k0practice;
    $practices[$K]['k0practice'] = $k0practice; // needed later for practice_division and fragment_practice
    $V['c2standardized'] = 'Process Standard Practice'; // Not encrypted, c2 field.
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0practice', $V);
    $practice_division[] = array('k0practice' => $k0practice);
}
// Make array of divisions to be associated with these PSM-CAP compliance practices.
$Divisions = array(
    8,  // Inspection, testing, and maintenance (ITM) for safe operation and mechanical integrity in Cheesehead division method. See templates/divisions.php
    23, // Mechanical Integrity in OSHA PSM
    36  // Mechanical Integrity in EPA CAP
);
$k0practice_division = $Zfpf->get_highest_in_table($DBMSresource, 'k0practice_division', 't0practice_division');
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
    // See also $GeneralFragments below
    0 => array(
        'k0fragment' => 89, // PSM Maintenance Training
        'k0practice' => $practices[1]['k0practice'] // ITM training
    ),
    1 => array(
        'k0fragment' => 1128, // CAP Maintenance Training
        'k0practice' => $practices[1]['k0practice'] // ITM training
    ),
    2 => array(
        'k0fragment' => 89, // PSM Maintenance Training
        'k0practice' => $practices[2]['k0practice'] // Hazards awareness training
    ),
    3 => array(
        'k0fragment' => 1128, // CAP Maintenance Training
        'k0practice' => $practices[2]['k0practice'] // Hazards awareness training
    ),
    4 => array(
        'k0fragment' => 95, // PSM resolution
        'k0practice' => $practices[3]['k0practice']
    ),
    5 => array(
        'k0fragment' => 1133, // CAP resolution
        'k0practice' => $practices[3]['k0practice']
    ),
    6 => array(
        'k0fragment' => 96, // PSM Quality Assurance heading
        'k0practice' => 14 // Change Management System
    ),
    7 => array(
        'k0fragment' => 96, // PSM  Quality Assurance heading 
        'k0practice' => $practices[4]['k0practice']
    ),
    8 => array(
        'k0fragment' => 98, // PSM Replacement-in-kind Quality Assurance
        'k0practice' => $practices[4]['k0practice']
    ), // k0fragment 97 and 1134, PSM and CAP Design and Installation Good Practices, are handled by fragment_practice.php
    9 => array(
        'k0fragment' => 1135, // CAP Replacement-in-kind Quality Assurance
        'k0practice' => $practices[4]['k0practice']
    )/* ,
     => array(
        'k0fragment' => , // PSM 
        'k0practice' => $practices[]['k0practice']
    ),
     => array(
        'k0fragment' => , // CAP 
        'k0practice' => $practices[]['k0practice']
    ) */
);
// Handle fragments addressed by many PSM-CAP compliance practices for ITM.
$GeneralFragments = array(87, 88, 90, 1126, 1127, 1129); // database primary keys, see templates/psm_fragments.php and templates/cap_fragments.php. PSM fragments k0 start at 1, CAP fragments k0 start at 1000.
$ApplicablePractices = array(0, 1, 3, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36); // $practices array keys, above // TO DO add all equipment-specific PSM-CAP compliance practices for ITM.
foreach ($GeneralFragments as $VA) {
    foreach ($ApplicablePractices as $VB)
        $fragment_practice[] = array(
            'k0fragment' => $VA,
            'k0practice' => $practices[$VB]['k0practice']
        );
}
$k0fragment_practice = $Zfpf->get_highest_in_table($DBMSresource, 'k0fragment_practice', 't0fragment_practice');
foreach ($fragment_practice as $V) {
    $V['k0fragment_practice'] = ++$k0fragment_practice;
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0fragment_practice', $V);
}

