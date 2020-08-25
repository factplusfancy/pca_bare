<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// TO DO FOR PRODUCTION VERSION  When updating the sample-observation methods below for new editions of IIAR 2, IIAR 6, IIAR 7, or IIAR 9 
// TO DO FOR PRODUCTION VERSION  also update the edition years in .../includes/templates/nh3r_psm-audit_etc.php including in the
// TO DO FOR PRODUCTION VERSION  t0audit:c5name, t0audit:c6howtoinstructions, and t0audit:c6background fields.

$NH3ROm = array( // Indent as done below for better display in HTML text areas and easier cut and paste to other documents. New lines help with tracking changes via git.
    1 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Management-system quality -- discussed with the people identified in the Owner/Operator\'s management-system description whether it accurately described their '.HAZSUB_NAME_ADJECTIVE_ZFPF.' duties and if they had the ability (training, experience...), time, and resources to complete these duties.')
    ),
    2 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Management-system content -- verified the management-system description designates a position with overall responsibility and lines of authority to positions for any delegated responsibilities and that names and titles were current.')
    ),
    3 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Employee-participation plan content -- checked if a written plan covered employee information access and consultation.')
    ),
    4 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Employee-participation plan quality --  with the responsible individual(s), per the Owner/Operator management system or the employee-participation plan, discussed if the employee-participation plan reflected current information access, consultation, and feedback opportunities for employees.')
    ),
    5 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Employee-participation plan documents -- checked a sample of employee-participation documents, from the prior three years or most recent if older, for, if called for by the plan: 
(1) PHA reports documented employees on team or other consultation, 
(2) training records documented requests for employee feedback on procedures and training, 
(3) notices posted covering information access and consultation, 
(4) meetings records documented attendance, topics covered, and decisions.')
    ),
    6 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('PHA information sources -- assessed if adequate information was available to complete the most recent process-hazard analysis (PHA), original or update, based on document-revision dates, possibly augmented by the PHA team\'s documented observations of the '.HAZSUB_PROCESS_NAME_ZFPF.', if built at the time.')
    ),
    7 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Facility\'s Safety Data Sheet (SDS) for anhydrous ammonia -- checked if the SDS was accessible to employees and contained the required information, namely: 
(1) toxicity, 
(2) permissible exposure limits, 
(3) physical properties, 
(4) reactivity, 
(5) corrosivity, 
(6) thermal and chemical stability, and 
(7) incompatible materials.')
    ),
    8 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Flow-diagram quality -- looked over flow diagram with a person experienced with the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.'. Asked if complete and correct. Compared its equipment packages to P&amp;ID and component list. Checked most flow directions.')
    ),
    9 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Flow diagram as tour aid -- during tour, used the flow diagram as a checklist to verify both that all equipment packages were viewed and that the diagram was complete and correct.')
    ),
    10 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('How it works -- checked if a written summary, the flow diagram, or other documents available to facility employees, taken together, described: 
(1) the anhydrous ammonia, lubricating oil, and typical significant contaminants (water, oxygen, and nitrogen) inside the '.HAZSUB_PROCESS_NAME_ZFPF.' and 
(2) the ammonia refrigerant\'s phase changes (gas condensing to liquid...) and so provided a summary "how it works" or "theory of operation".')
    ),
    11 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Inventory -- recorded the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' amounts: 
(1) posted near refrigerating-machinery room(s) and 
(2) written in the process-safety information as the maximum-intended inventory, based on 
(2.1) ammonia-charging records, including the levels, temperatures, and pressures after charging, 
(2.2) the sum of significant amounts in the '.HAZSUB_PROCESS_NAME_ZFPF.' components at maximum-intended-inventory levels, temperatures, and pressures, or 
(2.3) another method that produces results of similar quality. Checked a sample of the maximum-intended-inventory determination records and/or calculations for obvious errors. Checked if any component-based inventory included all equipment packages on the flow diagram.')
    ),
    12 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Operating limits, deviation consequences, controls, safety systems, and corrective actions -- obtained or made a list of documents describing these. Asked a person experienced with the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.' to provide a "tour" of the '.HAZSUB_PROCESS_NAME_ZFPF.' controls user interface(s), and together assessed consistency between: these documents, the controls, and what we considered to be good practices. Checked if these documents described: 
(1) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' compressors shutoff by
(1.1) high-discharge pressure (IIAR 9-2020 7.4.5),
(1.2) low-suction pressure,
(1.3) high-liquid level in vessels that fed them (IIAR 9-2020 7.4.4), 
(2) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' pumps shutoff by low-liquid level in vessels that fed them, 
(3) '.HAZSUB_PROCESS_NAME_ZFPF.' emergency-stop switch/button(s), 
(4) all ammonia sensors and the setpoints for actions that they trigger, such as 
(4.1) notifying Owner/Operator representatives qualified to muster assistance in emergencies and likely to be reachable on holidays and off hours (so alarms in an always-attended location or the alarm system can call out to multiple people\'s phones or a reliable answering service with a call list),
(4.2) audio/visual alarms outside all entrances into and inside the refrigerating-machinery room(s), 
(4.3) emergency ventilation,
(4.4) '.HAZSUB_PROCESS_NAME_ZFPF.' shutoff,
(5) adequate instrumentation and controls for the safe operation of the '.HAZSUB_PROCESS_NAME_ZFPF.', including shutdowns and restarting (IIAR 9-2020 7.4.7.1),
(6) controls physical and cyber security, including limiting
(6.1) who can change settings to trusted and authorized people, especially for safety settings, (IIAR 9-2020 7.4.7.3) and
(6.2) remote access, such as with "something you have and something you know" two-step authentication, in other words, a password plus a temporary, single use, security code sent to the authorized person\'s mobile phone, and
(7) design pressures and temperatures of instruments that were part of the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' pressure-containing envelope, including sight glasses, or references to manufacturer manuals with this information and a statement indicating they were suitable for service at their location in the  '.HAZSUB_PROCESS_NAME_ZFPF.' (IIAR 9-2020 7.4.7.4.')
    ),
    13 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Materials of construction -- checked a sample of documents about piping and equipment for completeness, including: 
(1) information-location summary, such as change-management number references on components lists, 
(2) vessel-manufacturer data reports (U forms), shop drawings, and any optional nameplate photographs, 
(3) equipment, valve, instrumentation, and controls manufacturer manuals, 
(4) piping material-test reports (linked to the '.HAZSUB_PROCESS_NAME_ZFPF.' by heat numbers on delivery documentation, post-installation inspection reports, or similar), 
(5) insulation and paint specifications, 
(6) supports specifications, and 
(7) any other written descriptions about materials of construction. 
For a pre-2010 '.HAZSUB_PROCESS_NAME_ZFPF.', this information may reasonably not be available -- particularly for piping, small pressure vessels that may have been field fabricated from piping, insulation, paint, and supports. Regardless, if not available, the mechanical-integrity program should address any resulting uncertainties.')
    ),
    14 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Piping and instrumentation diagram (P&amp;ID) -- checked a sample of P&amp;ID against flow diagram and, during tour, some equipment packages, including items not visible on the piping, such as check valves under insulation near refrigerant-pump discharges. Compared P&amp;ID revision dates to change-management documents.')
    ),
    15 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Electrical classification -- checked explanation of why no areas were classified as hazardous locations under applicable electrical code. Or, checked a sample of areas classified as hazardous locations for non-compliant electrical equipment, focusing on equipment added after original installation or temporary activities, and also checked a sample of plans/policies covering these areas and change management in them.')
    ),
    16 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Pressure-relief documents -- checked that as-built design documents showed: 
(1) the design standard and edition, 
(2) relief-device opening setpoint and capacity selection based on 
(2.1) the protected pressure vessel\'s maximum allowable working pressure (MAWP), 
(2.2) a reasonable design scenario(s), which, if a fire scenario, describes the assumed maximum amounts of flammable or combustible materials stored within or passing through (in piping, in barrels, and so forth) the same room as (or specified distances outdoors from) the protected equipment, 
(2.3) needed-flow calculations, and 
(2.4) inlet-piping losses, including from any 3-way valves, 
(3) relief-vent (RV) piping sizing based on installed 
(3.1) relief-device discharge capacities and 
(3.2) RV piping lengths, geometry, and fittings, including outlet moisture-entry prevention devices, 
(4) needed special measures for any stop valves on RV piping, 
(5) a safe discharge-to-atmosphere location(s), direction, and method, such as a tractor cap compatible with the RV piping material, 
(6) a reasonable pressure difference (allowance) between pressure-limiting and pressure-relief devices, such as between the setpoints for the compressor high-pressure shutoff and the oil-separator pressure-relief valve, and 
(7) that, if pressure-relief devices were protecting pressure vessels connected sufficiently to be at similar pressures during the design scenario, these pressure-relief devices had the same opening setpoint, at or below the lowest design pressure of all piping and equipment (including pressure vessels) that were so connected, for example, one setpoint for the low-side, at least 150 pounds per square inch (PSI), and one setpoint for the high-side, at least 250 PSI with evaporative condensers and 300 PSI with air-only condensers (IIAR 9-2020 7.2.2.1, 7.4.1, 7.4.2, and 7.4.3). 
Quality depends on detailed measurements and calculations beyond the scope, so noted: (1) any documentation on the competence of the individual and organization that provided the design, preferably in the as-built design documents, and (2) the design method used, such as the software or spreadsheet source. Checked a sample of as-built design documents for obvious errors.')
    ),
    17 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Pressure-relief system -- checked a sample of system for: 
(1) consistency with as-built design documents, including 
(1.1) if the pressure-vessel nameplate (or photograph of it) could be found and was legible and 
(1.2) if the pressure-vessel nameplate maximum allowable working pressure (MAWP) was equal to or greater than the opening setpoint of the pressure-relief device that protected the vessel, 
(2) needed overpressure protection missing, such as for 
(2.1) pressure vessels or 
(2.2) piping or equipment that could trap '.HAZSUB_NAME_ADJECTIVE_ZFPF.'liquid due to 
(2.2.1) normal or emergency automatic valves, such as some King solenoid valves, 
(2.2.2) leaks into periodically isolated equipment that isn\'t continuously monitored, vented, or pumped out, such as some condensers in winter if not continuously pumped out to an automatic purger, and 
(2.2.3) a reasonably likely failure or human error, such as, perhaps, closing a stop valve downstream of a check valve on a pump discharge, 
(3) pressure-relief devices 
(3.1) chattering, 
(3.2) frosted (possible leak-by),
(3.3) installed too long ago (over 5-years unless a facility-specific testing program was in place or discharge is back into the '.HAZSUB_PROCESS_NAME_ZFPF.'), or 
(3.4) visibly tampered with, broken ASME seal, or damaged, and 
(4) device outlet or relief-vent (RV) piping problems, including 
(4.1) located where water may condense in it, such as in cooled rooms, 
(4.2) had low spots likely to gather water or anything that may hinder relief flow or cause shocks, 
(4.3) had no provisions for safely draining water where it might accumulate, such as from a trap below each discharge-to-atmosphere outlet, 
(4.4) had been visibly blocked or tampered with,
(4.5) discharge-to-atmosphere outlet was unsafe, including
(4.5.1) less than 20 feet (6.1 m) in any direction from air intakes or building openings, such as windows or doors,
(4.5.2) both less than 20 feet (6.1 m) horizontally from and also less than 7.25 feet (2.2 m) above walking-working surfaces, such as catwalks and most roofs, 
(4.5.3) less than 15 feet (4.6 m) above "grade", such as surfaces where vehicles may drive, 
(4.5.4) directed towards where people may be walking or working (best if vertically upward, such as with a tractor cap), or 
(4.5.5) outlet was likely to allow water entry. (IIAR 9-2020 7.2.2.1, 7.4.1, 7.4.2, and 7.4.3)')
    ),
    18 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Ventilation for refrigerating-machinery room(s) documents -- checked that as-built design documents showed: 
(1) the design standard and edition, 
(2) all required normal, temperature control, and emergency exhaust and makeup air-flow calculations, 
(3) exhaust-fan(s) specifications, including 
(3.1) capacity, 
(3.2) non-sparking blades, and 
(3.3) totally-enclosed motor, unless the motor was very unlikely to get exposed to a flammable atmosphere, for example outdoors and not in the exhaust-air stream, 
(4) intakes(s) specifications, including 
(4.1) location likely to supply clean outdoor air, 
(4.2) inlet area, 
(4.3) inlet screen mesh size and materials, 
(4.4) any louvers, dampers, or similar fail open, 
(4.5) any fans, 
(4.6) any ducting, 
(4.7) any negative-pressure calculations, and 
(4.8) intake(s) only serve the refrigerating-machinery room (the room), 
(5) ventilation flow patterns, including sweeps the room with no excessive short-circuiting, 
(6) a safe discharge location(s), direction, method, and velocity, 
(7) ventilation control via manual on/auto switch, ammonia sensors, and possibly temperature sensors, 
(8) electrical-power supply for ventilation not shutoff by '.HAZSUB_PROCESS_NAME_ZFPF.' any emergency-stop switch/button, ammonia sensors, and similar, and 
(9) alarms on loss of power or known failure of ventilation systems, that notify Owner/Operator representatives qualified to muster assistance in emergencies and likely to be reachable on holidays and off hours (so alarms in an always-attended location or the alarm system can call out to multiple people\'s phones or a reliable answering service with a call list). (IIAR 9-2020 7.3.13)
Quality depends on detailed measurements and calculations beyond the scope, so noted: (1) any documentation on the competence of the individual and organization that provided the design, preferably in the as-built design documents, and (2) the design method used. Checked a sample of as-built design documents for obvious errors.')
    ),
    19 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Ventilation for refrigerating-machinery room(s) system -- with the responsible individual(s), per the Owner/Operator management system, cursorily looked over for and asked about:
(1) consistency with as-built design documents, 
(2) emergency ventilation would sweep each refrigerating-machinery room (the room) with no obvious dead zones, based on inlet and outlet locations, 
(3) ammonia sensor(s) not near fresh-air inlets, 
(4) exhaust discharge(s) not near air intakes, building openings, or walking-working surfaces, 
(5) on/auto ventilation manual-control switch/button(s) located near the room and where reasonably likely to remain safe and accessible during '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases, and 
(6) any equipment in the room likely to have power when the emergency ventilation does not (battery backed-up and so forth) that wasn\'t designed and installed to prevent becoming an ignition source, for explosions and fires.')
    ),
    20 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Design codes and standards editions -- checked if specified for all portions of the '.HAZSUB_PROCESS_NAME_ZFPF.', for example, in the change-management documents.')
    ),
    21 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Energy balance -- discussed hot-weather performance of the '.HAZSUB_PROCESS_NAME_ZFPF.' with a person experienced with the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.'.')
    ),
    22 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Energy-balance documents -- checked for completeness by comparing to flow diagram. Checked a sample of the calculations for obvious errors. Material balances are not relevant to closed-loop '.HAZSUB_PROCESS_NAME_ZFPF.'s. Quality depends on detailed measurements and calculations beyond the scope, so noted: (1) any documentation on competence of individual and organization that provided the energy-balance document, preferably as part of the document, and (2) the method used.')
    ),
    23 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Process-hazard analysis (PHA) -- checked a sample of scenarios, nodes, or other analysis building-blocks (and also the introductory text) in the latest PHA report for: 
(1) allowed method used correctly, 
(2) quality, completeness, and appropriate detail of how it covered the content requirements, namely, 
(2.1) process hazards, 
(2.2) previous incidents, 
(2.3) engineering and administrative controls, 
(2.4) controls-failure consequences, 
(2.5) facility siting, 
(2.6) human factors, and 
(2.7) qualitative evaluations of possible safety and health effects of failures (preferably semi-quantitative for ammonia-refrigeration systems with 10,000 pounds or more of anhydrous ammonia and optionally quantitative), 
(3) description of team members and their qualifications (and preferably attendance logs), to assess if these qualifications included, 
(3.1) expertise in '.HAZSUB_PROCESS_NAME_ZFPF.' engineering and operations, 
(3.2) at least one employee who had experience and knowledge specific to the '.HAZSUB_PROCESS_NAME_ZFPF.' being evaluated, and 
(3.3) at least one team member knowledgeable in the process-hazard analysis method used.')
    ),
    24 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('PHA recommendation resolution -- checked a sample of the resolution documents for: 
(1) planned or as-done resolution method, 
(2) target-resolution timing, 
(3) actual-resolution date, if resolved, 
(4) resolution completed "as soon as possible [... within] one to two [years or longer in documented] unusual circumstances" (57 Federal Register 6379, February 24, 1991), 
(5) referenced change-management documents as needed, such as when training needed on resolution-related changes.')
    ),
    25 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Resolution checks -- compared observed conditions to a sample of documented as-done resolution methods for findings or recommendations from: 
(1) the latest PHA, 
(2) recent inspection, testing, and maintenance (ITM), 
(3) any incident investigations, and 
(4) the latest completed audit. 
Asked about this during tours and discussions.')
    ),
    26 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('PHA updates and retention -- with the responsible individual(s), per the Owner/Operator management system, looked for digital or paper copies of all past process-hazard-analysis and resolution documents and checked if they showed that: 
(1) the PHA was updated and revalidated at least every five years, 
(2) the PHA was amended as needed after changes that created process hazards not previously analyzed by the PHA, and 
(3) all PHA and resolution documents had been retained (for "the life of" the '.HAZSUB_PROCESS_NAME_ZFPF.').')
    ),
    27 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Procedure written descriptions -- with the responsible individual(s), per the Owner/Operator management system, discussed overall approach and checked a sample of the written descriptions of hazardous-substance procedures and safe-work practices and any separate inspection, testing, and maintenance (ITM) procedures to assess if -- in combination with each other, the facility change-management program, and the facility emergency plans -- they correctly described, as needed: 
(1) how to safely handle 
(1.1) normal operations, 
(1.2) non-automated tasks for unusual refrigeration loads, such as production shutdowns, 
(1.3) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' leak mitigation and emergency shutdown, 
(1.4) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' small leaks, 
(1.5) start-up after power failures or emergency shutdowns, 
(1.6) oil charging, draining, and sampling, including, if not permanently installed, installing a temporary spring-closing valve before draining or sampling, unless the procedure called for pump-down and isolation before oil draining or sampling, 
(1.7) ammonia charging, 
(1.8) removal and return to service for ITM, including shutoff-valve closing/re-opening and any control-valve manual opening/closing sequences for pump-down, isolation, leak-by checks, any venting to water, and return-to-service low-pressure then operating pressure and temperature leak checks, or equivalent, and 
(1.9) tie-in of additions, commissioning, and decommissioning, 
(2) hazards of a task and other needed information for completing the task before or in the description of the task\'s steps (references to information are okay to keep procedures concise), 
(3) how to recognize and address reasonably possible failures during the procedure, such as leak-by a closed valve, a control-valve malfunction, packing or bonnet leaks, and so forth, 
(4) required equipment, including personal-protective equipment (PPE), 
(5) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' first aid, and 
(6) roles, responsibilities, and required training, including training, fit testing, or medical testing/questionnaires on the required equipment.')
    ),
    28 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Raw-material quality and inventory control -- checked if these were covered by the written descriptions of the ammonia-charging and oil-changing procedures (or other administrative controls).')
    ),
    29 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Procedure and training discussions -- with a sample of employees or contractors who completed tasks covered by the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.' hazardous-substance procedures and safe-work practices -- such as daily inspections, oil draining, oil charging, ammonia charging, preparing piping and equipment for service, investigating or fixing '.HAZSUB_NAME_ADJECTIVE_ZFPF.' small leaks, and hot work -- preferably next to the relevant equipment or controls, asked about how to do one such task and 
(1) saw if they first got the latest applicable written procedure and/or practice descriptions (and not an old version they tucked away), 
(2) assessed if they understood 
(2.1) what they were able to do, 
(2.2) what they were allowed to do, and 
(2.3) how these limits were documented, 
(3) assessed if 
(3.1) their spoken description matched the latest applicable written procedure and/or practice descriptions and 
(3.2) both descriptions were safe, 
(4) if they didn\'t get the latest applicable written procedure and/or practice descriptions, later asked if they had access to these, and 
(5) also asked about: 
(5.1) operating limits, 
(5.2) what to do if a deviation occurs outside operating limits, 
(5.3) when to press the '.HAZSUB_PROCESS_NAME_ZFPF.' emergency-stop switch/button(s), 
(5.4) required equipment, including personal-protective equipment (PPE), 
(5.5) fit testing and medical testing/questionnaires for any respiratory protection they use, 
(5.6) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' first aid, 
(5.7) the nearest safety shower and eye/face wash, 
(5.8) quality and inventory control for charging ammonia and oil, 
(5.9) when they last drained oil from an opening in the '.HAZSUB_PROCESS_NAME_ZFPF.'  without both a stop valve and a spring-closing valve installed, in series, 
(5.10) any equipment that accumulated oil without a safe and reasonably easy way to drain it, 
(5.11) any hand-operated stop valves they needed to operate for maintenance or emergencies that were difficult to access -- even, if only for maintenance, from a ladder or lift (IIAR 9-2020 7.3.3.3), 
(5.12) how they were trained before first doing a task, 
(5.13) their satisfaction with the refresher-training frequency, 
(5.14) how they were informed of and trained on any changes affecting their work in the prior three years, and 
(5.15) whether any level sensors or float switches had shutoff '.HAZSUB_PROCESS_NAME_ZFPF.' compressors in the prior three years and, if so, how the restart from this was done.')
    ),
    30 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Procedure current and accurate certification -- checked if Owner/Operator had certified each year, for the prior three years, that the hazardous-substance procedures and safe-work practices (or equivilent administrative controls) were current and accurate.')
    ),
    31 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Access controls -- assessed and discussed these, such as: 
(1) sign-in/sign-out, 
(2) chaperoning, 
(3) doors to buildings locked, 
(4) fences with locked gates around outdoor piping and equipment, 
(5) roof-access controls, such as locked gates or doors for ladders and stairs, 
(6) facility attended 24-7, by employees of security guards, or 
(7) video, motion sensors, door or window contact sensors, or other security systems.')
    ),
    32 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Refresher training -- for a sample of initial and refresher training records, checked if they showed: 
(1) that employees were trained before doing tasks covered by hazardous-substance procedures and safe-work practices (the tasks) by comparing discussions with management, employees, and any relevant contractors about "who does what" with training records, 
(2) that employees who did the tasks completed refresher training at least every three years, 
(3) that employees who did the tasks were consulted on the frequency of refresher training, 
(4) the identity of the employee, 
(5) the date of training, and 
(6) the means used to verify that the employee understood the training.')
    ),
    33 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Contractor applicability and qualifications -- 
(1) reviewed the contractor-qualification documents with the responsible individual(s), per the Owner/Operator management system, to determine if they showed that the Owner/Operator evaluated the qualifications of contractors who, in the prior three years: 
(1.1) worked on the '.HAZSUB_PROCESS_NAME_ZFPF.', including its piping, equipment, supports, building structural systems, other support structures, or foundations, 
(1.2) worked on ventilation, pressure-relief, suppression, secondary containment, or similar safety systems of/for the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(1.3) worked on alarms, controls, instrumentation, motors, or other electrical components of/for the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(1.4) painted, insulated, cleaned, or sanitized any of the above, 
(1.5) worked adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.', particularly if they opened flanges, performed hot work, moved heavy objects, or did electrical or controls work, and 
(1.6) transferred substances into/from the '.HAZSUB_PROCESS_NAME_ZFPF.'. 
(2) Checked a sample of the contractor-qualification documents to assess if requested information was: 
(2.1) reasonably complete and 
(2.2) sufficient for evaluating contractor safety performance and programs.')
    ),
    34 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Owner-to-contractor notices -- with the responsible individual(s), per the Owner/Operator management system, checked a sample of any documents from the prior three years showing that, for contractors\' work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.' (their work), the Owner/Operator notified the contractor organizations about: 
(1) known fire, explosion, or toxic release hazards related to their work and the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(2) applicable portions of the facility Emergency Action Plan, and 
(3) the contractor organizations\' PSM and/or general duty responsibilities related to their work.')
    ),
    35 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Contractor performance in fulfilling their PSM and/or general duty obligations and injury or illness log -- with the responsible individual(s), per the Owner/Operator management system, discussed overall approach and checked a sample of any documents from the prior three years showing that, for contractors\' work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.' (their work), the Owner/Operator evaluated if the contractor organizations: 
(1) assured that each contractor individual was trained 
(1.1) to safely do their work, 
(1.2) on known fire, explosion, or toxic-release hazards related to their work and the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(1.3) on applicable portions of the facility Emergency Action Plan, and 
(1.4) on changes relevant to their work, safety, or health, 
(2) had documents about this training showing 
(2.1) the identity of the contractor individual, 
(2.2) the date of training, and 
(2.3) the means used to verify that the contractor individual understood the training, 
(3) assured that each contractor individual followed the safety rules of the facility including applicable hazardous-substance procedures and safe-work practices, or equivalent contractor-organization procedures and practices, and 
(4) notified the Owner/Operator of 
(4.1) any unique hazards presented by their work, 
(4.2) hazards of any sort discovered at the facility, including by their work, and 
(4.3) any injuries or illnesses of contractor individuals related to their work.
(5) Also, checked if the Owner/Operator maintained a log or equivalent of any contractor injuries or illnesses.')
    ),
    36 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('If contractors were doing work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.' (their work) during the onsite audit effort and were available to talk, asked a sample of them if they had received adequate training or information on: 
(1) the hazards of their work and the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(2) applicable portions of the facility Emergency Action Plan, 
(3) facility-access rules, such as sign-in/sign-out, 
(4) hazardous-substance procedures and safe-work practices applicable to their work, 
(5) reporting hazards created by or discovered during their work to appropriate facility personnel.')
    ),
    37 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Inspection, testing, and maintenance (ITM) procedures, training, scheduling, and recordkeeping -- with the responsible individual(s), per the Owner/Operator management system, and a person experienced with the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.' ITM, discussed overall approach and checked a sample of completed work orders or equivalent documents, from the ITM program for the '.HAZSUB_PROCESS_NAME_ZFPF.', to assess if they: 
(1) covered 
(1.1) the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' pressure-containing envelope and everything within it (piping, pressure vessels, compressors, condensers, evaporators, pumps, and so forth), 
(1.2) its supports (including structures and foundations), 
(1.3) its pressure-relief system, and also 
(1.4) the '.HAZSUB_PROCESS_NAME_ZFPF.' controls and instrumentation (including for safe operation, alarms, any interlocks, emergency shutdown, and ventilation), 
(1.5) the refrigerating-machinery room(s) ventilation systems, 
(1.6) aspects of secondary heat-transfer liquids, piping, and equipment whose failure could increase risks to the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' pressure-containing envelope, such as 
(1.6.1) any needed corrosion prevention or monitoring for brine, glycol, water, and other liquids and for the heat exchangers between them and the ammonia refrigerant (including condenser-water treatment) and 
(1.6.2) fans for condensers, forced-air evaporators, and so forth, and 
(1.7) if any piping was routinely below atmospheric pressure, assessing the purity of the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' refrigerant, by its pressure-temperature relationship and if needed by water-content testing (IIAR 6-2019 14.1-Testing-b, 15.1-Testing-a, Appendix A.15.1, and Appendix C), 
(2) referenced any needed ITM procedures, for example in manufacturer manuals, 
(3) summarized training need to complete the ITM (including an overview of the '.HAZSUB_PROCESS_NAME_ZFPF.' and its hazards), 
(4) described ITM tasks consistently with good practices, such as manufacturer recommendations, industry standards, or documented operating experience, 
(5) showed that tasks had been completed per schedules, 
(6) documented that deficiencies discovered by the ITM were resolved "before further use or in a safe and timely manner when necessary means are taken to assure safe operation" 29 CFR 1910.119(j)(5), and 
(7) included the 
(7.1) date of the ITM, 
(7.2) name of the person who did the ITM, 
(7.3) unique identifier, such as a serial number or asset ID, for the piping or equipment on which the ITM was done, 
(7.4) as-done description of the ITM method, and 
(7.5) results of inspections or tests.')
    ),
    38 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Replacement-in-kind quality assurance -- with the responsible individual(s), per the Owner/Operator management system, and a person experienced with the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.' ITM: 
(1) discussed how replacement-in-kind materials (including lubricating oil), parts, and equipment were obtained for the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.' and 
(2) checked a sample of procurement documents from the prior three years for any cases where a "replacement" wasn\'t a "replacement in kind" but change management wasn\'t completed. "Replacement in kind" means a replacement which satisfies the design specification." 29 CFR 1910.119(b)')
    ),
    39 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('ITM discussions -- with a sample of employees or contractors who do ITM on the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.', asked questions like: 
(1) how have you typically learned to do an '.HAZSUB_PROCESS_NAME_ZFPF.' ITM task for the first time, 
(2) how is this training documented, 
(3) how useful are the facility\'s work orders for its '.HAZSUB_PROCESS_NAME_ZFPF.' ITM, 
(4) how do you typically obtain replacement parts for the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.', 
(5) are you aware of any 
(5.1) unresolved deficiencies or 
(5.2) other unmet safety or ITM needs at the facility, and 
(6) do you have suggestions about this or a way of making suggestions for facility management.')
    ),
    40 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('ITM observed conditions -- checked a sample of the '.HAZSUB_PROCESS_NAME_ZFPF.' piping and equipment to assess whether called for ITM was being completed. For example, 
(1) lots of damaged insulation or paint would suggest periodic insulation and paint inspection and repair was not done, 
(2) excessive noise, vibrations, or pipes shaking when valves operated would suggest inadequate maintenance or installation, 
(3) lots of staining from ongoing oil or water leaks or lots of temporary fixes would suggest slow correction of deficiencies.')
    ),
    41 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Hot-work policy and permits -- 
(1) checked, for completeness, a sample of any recent permits for hot work on the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.' and compared these to the change-management documents. 
(2) Checked if the facility\'s hot-work policy or equivalent described: 
(2.1) job titles of who can approve hot-work permits and their training, 
(2.2) fire-watcher training, typically at least fire extinguishers and how to sound the fire alarm, and 
(2.3) any areas where hot work is allowed without a permit. 
(3) Checked if the facility\'s hot-work permit form covered: 
(3.1) avoiding hot work or moving hot-work object; otherwise... 
(3.2) timing, location, inspection, authorization: 
(3.2.1) date(s) authorized for hot work, 
(3.2.2) identify hot-work object (location, description...), 
(3.2.3) name of person performing hot work, 
(3.2.4) continuous fire watch and fire monitor requirements, 
(3.2.5) name of fire watcher, 
(3.2.6) authorization preceded by site inspection, 
(3.3) fire protection and hot-work equipment: 
(3.3.1) in sprinklered buildings, sprinklers are not impaired, 
(3.3.2) fire extinguishers operable and on hand, 
(3.3.3) hot-work equipment in good working condition, 
(3.4) within 35 feet of hot work:  
(3.4.1) move out all readily movable combustibles, 
(3.4.2) shield all other combustibles, including floors, walls, partitions, ceilings, or roofs of combustible construction, 
(3.5) things that can carry heat, sparks... past floors, walls... or over 35 feet 
(and needed addition protection or fire watchers): 
(3.5.1) openings, cracks, holes, drains, elevated work... 
(3.5.2) ventilation or conveyors, 
(3.5.3) heat applied to thermally conductive material that passes through, such as steel pipes, beams... 
(3.5.4) combustible materials adjacent to the opposite side of conductive wall, 
(3.6) high fire-risk situations: 
(3.6.1) places where explosive atmospheres may develop, from flammable gases, vapors, liquids, lint, dust... 
(3.6.2) large quantities of readily combustible material, 
(3.6.3) hot-work on assemblies that include combustibles, such as in coatings, sandwiched inside, torch-applied roofing...')
    ),
    42 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Change and incident discussions -- asked the responsible individual(s), per the Owner/Operator management system, and also a sample of the people who operate, inspect, test, or maintain the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.' about, in the prior three years: 
(1) any changes to the '.HAZSUB_PROCESS_NAME_ZFPF.' and to chemicals (including lubricating oil in it and sanitation chemicals used near it), technology, equipment, procedures, buildings, structures, traffic patterns, or nearby activities that could affect the '.HAZSUB_PROCESS_NAME_ZFPF.' or that it could affect, such as during a fire or explosion, and also 
(2) any '.HAZSUB_NAME_ADJECTIVE_ZFPF.' leaks or other incidents involving the '.HAZSUB_PROCESS_NAME_ZFPF.', including any fires or explosions.')
    ),
    43 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Change and incident tour observations -- checked for, on or near the '.HAZSUB_PROCESS_NAME_ZFPF.': 
(1) changes that appeared recent and 
(2) signs of past incidents or near misses, such as 
(2.1) scratched or dented piping, equipment, insulation, or drip pans and 
(2.2) staining or corrosion suggesting past fires, explosions, or '.HAZSUB_NAME_ADJECTIVE_ZFPF.' leaks. If any, 
(3) asked the employees or contractors, who were accompanying the auditor on the tour, about them.')
    ),
    44 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Change-management documents -- 
(1) reviewed forms and instructions to assess if properly completing them would meet pre-startup safety review, new-construction mechanical integrity, and management of change requirements at 29 CFR 1910.119(i), 1910.119(j)(6)(i) and (ii), and 1910.119(l), by checking if they include: 
(1.1) the change\'s 
(1.1.1) technical basis, such as a comprehensive design review, 
(1.1.2) affects on safety and health, 
(1.1.3) affects on operating procedures, and 
(1.1.4) timing and duration, 
(1.2) change authorization by the responsible individual(s), per the Owner/Operator management system, and 
(1.3) a pre-startup safety review -- done before putting any hazardous substance into new or altered piping or equipment and before implementing a change to the stage when training is needed on new or altered procedures, practices, or controls -- verifying that 
(1.3.1) the '.HAZSUB_PROCESS_NAME_ZFPF.' and everything it depends on or affects, including buildings, structures, supports, piping, vessels, and equipment, as built, 
(1.3.1.1) are suitable for their service and 
(1.3.1.2) are in accordance with their 
(1.3.1.2.1) design specifications, 
(1.3.1.2.2) all instructions from manufacturers of their component parts, and 
(1.3.1.2.3) applicable legal requirements, 
(1.3.2) procedures and practices are in place and are adequate, including 
(1.3.2.1) hazardous-substance procedures and safe-work practices, 
(1.3.2.2) inspection, testing, and maintenance (ITM) procedures, and 
(1.3.2.3) emergency plans, 
(1.3.3) the process-hazard analysis (PHA) has been updated if needed due to the change and applicable PHA recommendations have been resolved, and 
(1.3.4) each employee or contractor individual involved in operating, inspecting, testing, or maintaining the '.HAZSUB_PROCESS_NAME_ZFPF.' has been informed of and trained on the change as needed, 
(2) verified that all applicable changes discovered during discussions and tours had corresponding change-management documents, and 
(3) checked a sample of any changes from the prior three years to assess if: 
(3.1) their change-management documents were complete and consistent with conditions reported in discussions or observed on tours, 
(3.2) current written versions of the following had been updated as needed 
(3.2.1) process-safety information, 
(3.2.2) hazardous-substance procedures and safe-work practices, 
(3.2.3) ITM procedures and program, and 
(3.2.4) emergency plans, 
(3.3) training records showed the means used to verify that employees understood the training needed as a result of the change, and 
(3.4) owner-to-contractor notices or equivalent showed that contractor organizations had been informed of the change if additional training of contractor individuals was needed as a result of the change. Owner/Operator evaluation of contractor performance in fulling their training, and other PSM and/or general duty, requirements is covered by the separate "contractor performance..." sample observation method.')
    ),
    45 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Incident discussions -- asked almost everyone audit-discussions were held with (so a sample of employees and possibly contractors) if they recalled, in the prior three years: 
(1) any explosions, fires, or '.HAZSUB_NAME_ADJECTIVE_ZFPF.' leaks that caused anyone at the facility to leave the area where they were working or 
(2) vehicle collisions with '.HAZSUB_NAME_ADJECTIVE_ZFPF.' piping or equipment. If they recalled any of these, asked, as applicable: 
(3) what happened, 
(4) how were communications during the incident, 
(5) how did you move to a safe spot, 
(6) how was the incident brought under control, 
(7) how were any incident-investigation findings communicated to you, 
(8) for any suggestions on avoiding a repeat incident, and 
(9) whether steps needed to avoid a repeat incident had been taken.')
    ),
    46 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Incident-investigation documents -- 
(1) reviewed forms and instructions to assess if properly completing them would meet the PSM and general duty incident-investigation requirements by calling for: 
(1.1) a system to initiate an investigation within 48 hours after any 
(1.1.1) "catastrophic release" or 
(1.1.2) near miss to a "catastrophic release" (see glossary for definition), for example, by calling for voluntary logging and assessment of '.HAZSUB_NAME_ADJECTIVE_ZFPF.' small leaks and near misses to less-than-catastrophic releases, or similar, 
(1.2) an incident-investigation team that includes at least one person knowledgeable about the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.', a contractor individual from any contractor organization whose work was involved in the incident, and anyone else needed to thoroughly investigate and analyze the incident, 
(1.3) an incident-investigation report that includes, at a minimum 
(1.3.1) incident-start date and time, 
(1.3.2) investigation-start date and time, 
(1.3.3) incident description, 
(1.3.4) contributing factors, and 
(1.3.5) any recommendations, 
(1.4) a system to 
(1.4.1) promptly address, resolve, and document resolution of the incident-investigation findings and recommendations and 
(1.4.2) review the incident-investigation report with all affected personnel whose job tasks are relevant to the incident findings, including contractor individuals where applicable, 
(2) checked a sample of any incident-investigation documents from the prior three years to assess if they showed that the above requirements were met, 
(3) compared any incident-investigation documents to incidents reported in discussions, discovered during tours, or mentioned in change-management documents to assess if 
(3.1) incident investigations had been initiated when required in the prior three years, 
(3.2) incident-investigation reports had been retained for at least five years, and 
(3.3) change management had been completed if required due to the resolution of incident-investigation recommendations.')
    ),
    47 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Emergency Action Plan basic discussions -- asked a sample of employees and any contractors present: 
(1) "How would you know if the air we are breathing is unsafe due to a leak from the refrigeration system?" [don\'t say the word "ammonia" at first], 
(2) "What would you do if you discover a leak?", 
(3) "What is the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' evacuation procedure?" 
(4) "If your normal exit route isn\'t safe, how would you be told about a better exit route?" 
(5) "How often does this facility do drills or give you training on these topics?", 
(6) "Can you hear loudspeaker announcements everywhere you work? How about fire, leak, or tornado alarms?", 
(7) [if they have two-way radios] "Is the radio reception good everywhere at this facility?", and 
(8) [if they operate forklifts] "Are there any pipes or equipment that are hard to avoid with forklift trucks or jacks? Does anything need additional guarding at this facility?"')
    ),
    48 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Emergency Action Plan advanced discussions -- asked a sample of employees with extra responsibilities during emergencies, such as supervisors, emergency coordinators, or any employees who investigate small leaks, as applicable: 
(1) "What are your thresholds for calling 911? What\'s an example when you would and wouldn\'t?", 
(2) [for off shifts] "What are your thresholds for calling facility managers on their mobile phones or at home?", 
(3) "Where are these phone numbers kept?", 
(4) "What are your thresholds for evacuating all or part of this facility?" 
(5) "If an often-used exit route becomes unsafe due to a leak or fire, how would you determine safe routes to safe gathering places and communicate these to all employees and contractors onsite?" 
(6) "What types of '.HAZSUB_NAME_ADJECTIVE_ZFPF.' leaks can facility employees investigate and fix?" 
(7) "Under what conditions do they have to stop and move to safety?", 
(8) "Does this facility have a written procedure for '.HAZSUB_NAME_ADJECTIVE_ZFPF.' small leaks?", 
(9) "What maintenance and calibration is done on this facility\'s special equipment for '.HAZSUB_NAME_ADJECTIVE_ZFPF.' small leaks, such as hand-held detectors and any respirators (escape-only...)? Please show these to me so we can look over their cleanliness and exterior condition?", and 
(10) "Does this facility have a means to monitor for ammonia in air during a power failure, such as a hand-held detector?" (IIAR 9-2020 7.4.7.2')
    ),
    49 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Emergency Action Plan tour -- checked a sample of locations near the '.HAZSUB_PROCESS_NAME_ZFPF.' for: 
(1) access difficult or unsafe to places employees or contractors routinely worked, 
(2) lighting poor, 
(3) exits obstructed or inadequately marked, 
(4) slippery walking surfaces, such as ice, oil, water, and so forth on exit routes, which include the entire path of travel from work areas to safe locations, 
(5) evacuation and shelter-in-place floor plans not posted, or 
(6) not enough wind indicators, such as wind socks or pennants, for everyone exiting buildings or work areas to see the wind direction.')
    ),
    50 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Emergency Action Plan documents -- checked if the plan(s) addressed 
(A) the full range of potential incidents and 
(B) in the USA, employee safety per 29 CFR 1910.38 (Items 1 to 6 and 10), release reporting per CERCLA and EPCRA (Item 7), good practices (Item 8), community-responder coordination per 40 CFR 68.90, 68.93, and 68.96(a) (Item 9), and identifying offsite "hazards which may result from ... releases" per the Clean Air Act 112(r)(1) General Duty Clause (Item 9.1.5), including, 
for anhydrous ammonia: 
(1) "what to do if I smell ammonia or notice other hazards" -- such as how to notify facility management and individual move-to-safety, 
(2) determining and communicating routes to safe locations inside (shelter-in-place) or outside (evacuation), 
(3) sweeps, if safe, while leaving and headcount, 
(4) any needed shutdown of critical plant equipment before evacuating (may reference leak mitigation and emergency shutdown procedures), 
(5) calls to needed emergency responders, such as the local fire department and a contractor, pre-qualified to provide emergency response, 
(6) any rescue or medical duties done by employees, 
(7) in the USA, within 15 minutes of discovering that 100 pounds or more of anhydrous ammonia has leaked within a 24-hour period, calls to 
(7.1) local (typically 911), 
(7.2) state, and 
(7.3) federal (the National Response Center) emergency-response authorities, 
(8) greeting and briefing emergency responders (conditions, actions taken or underway, and needs), 
(9) advanced coordination with local emergency responders, including 
(9.1) at least yearly checking, and attempting to ensure, that the emergency plan of the community where the facility is located has up-to-date information on 
(9.1.1) facility contacts, 
(9.1.2) facility hazardous-substance quantities and typical approximate locations, 
(9.1.3) facility hazardous-substance risks and any other risks (high temperatures and pressures and so forth), 
(9.1.4) any facility resources helpful during incidents, and
(9.1.5) a reasonably accurate worst-case release scenario and offsite-consequences evaluation for the facility, such as 
(9.1.5.1) a distance-to-endpoint output by the U.S. EPA\'s RMP*Comp program based on either the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' maximum-intended inventory or the volume of the largest pressure vessel in the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(9.1.5.2) a circle on a map, centered at a potential release point at the facility, such as the refrigerating-machinery room, showing with reasonable detail the area within the distance-to-endpoint, 
(9.1.5.3) a residential human population estimate within the distance-to-endpoint, and 
(9.1.5.4) a list of schools, hospitals, prisons, major commercial, office, or industrial areas, parks, wildlife preserves, wilderness areas, and similar within the distance-to-endpoint, 
(9.2) promptly notifying the local emergency responders when a change occurs at the facility that may affect offsite-response plans, such as 
(9.2.1) changes to the information listed under Item 9.1 above or 
(9.2.2) changes to the Emergency Action Plan, 
(9.3) at least yearly exercising of emergency-response notification mechanisms by inviting the local emergency responders (and any contracted responders) to tour the facility or to complete any other reasonable preparedness efforts they request, including providing access to facility site maps and floor plans with details relevant to emergency response and, if requested, a copy of the facility Emergency Action Plan, and 
(9.4) documenting this advanced coordination with local emergency responders (Items 9.1 to 9.3), such as with copies of email exchanges describing 
(9.4.1) who the Owner/Operator coordinated with, including names, phone numbers, email addresses, and organizational affiliations, 
(9.4.2) the dates of coordination efforts, and 
(9.4.3) the nature of coordination efforts, 
(10) emergency-actions training, drills, and their documentation, at least 
(10.1) when an employee is first assigned to a job, 
(10.2) when an employee\'s responsibilities under the Emergency Action Plan change, 
(10.3) when the Emergency Action Plan changes, and typically 
(10.4) yearly drills or refreshers, and 
(11) all other applicable requirements in 29 CFR 1910.38 and other relevant rules, depending on any other hazardous substances and circumstances at the facility, covering, for example, medical emergencies, earthquakes, hurricanes, tornadoes, bomb threats, and so forth.')
    ),
    51 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Emergency-response discussions, equipment, and documents -- if facility employees plan to do emergency response, 
(1) with a sample of emergency-response team employees, discussed 
(1.1) training, 
(1.2) medical evaluation, 
(1.3) their assessment of the team\'s capabilities, 
(1.4) incident command, 
(1.5) other special roles and responsibilities, 
(2) with at least one emergency-response team member, looked over the emergency-response equipment and assessed 
(2.1) the likelihood that enough of it would be accessible during a catastrophic release and 
(2.2) its cleanliness and exterior condition, 
(3) checked, for quality and completeness, a sample of 
(3.1) emergency-response equipment inspection, testing, and maintenance records, 
(3.2) emergency-response training and medical evaluation records, and 
(3.3) community-responder coordination and exercise records required by 40 CFR 68, Subpart E, 
(4) checked if the facility\'s emergency-response plan included the "elements" required by 29 CFR 1910.120(q)(2), namely, 
(4.1) pre-emergency planning and coordination with outside parties, 
(4.2) personnel roles, lines of authority, training, and communication, 
(4.3) emergency recognition and prevention, 
(4.4) safe distances and places of refuge, 
(4.5) site security and control, 
(4.6) evacuation routes and procedures, 
(4.7) decontamination, 
(4.8) emergency medical treatment and first aid, 
(4.9) emergency alerting and response procedures, 
(4.10) critique of response and follow-up, and 
(4.11) personal-protective equipment (PPE) and other emergency equipment, and 
(5) checked if the facility\'s emergency-response plan (or Emergency Action Plan) covered the requirements in 40 CFR 68, Subpart E.')
    ),
    52 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Audit and hazard review documents for the facility\'s '.HAZSUB_PROCESS_NAME_ZFPF.' -- checked if the prior two audit and hazard review reports (the reports), their resolution records, and any required Owner/Operator certifications that relied on them: 
(1) had been retained, 
(2) included in their reported scopes an audit and hazard review of compliance with at least 
(2.1) general-duty law, and, if applicable, 
(2.2) 40 CFR 68, Subpart D (EPA\'s Program 3 Prevention Program) and 
(2.3) 29 CFR 1910.119 (OSHA\'s PSM), 
(3) documented completion of these scopes following good practices, such as including a tour, discussions or interviews, and document reviews, 
(4) described the qualifications of the individual(s) who completed each audit and hazard review and these qualifications included knowledge of the '.HAZSUB_PROCESS_NAME_ZFPF.', possibly gained while at the facility for the audit and hazard review if the individual(s) had experience with similar '.HAZSUB_PROCESS_NAME_ZFPF.'s elsewhere, and 
(5) showed that the Owner/Operator had 
(5.1) promptly determined and documented an appropriate response to each finding in the reports, 
(5.2) documented that deficiencies, discovered due to the reported findings, had been corrected, and 
(5.3) adequately certified that they had evaluated compliance, if applicable (cases 2.2, 2.3, and 2.4 above).')
    ),
    53 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Maintenance employees or contractors not authorized to work on the '.HAZSUB_PROCESS_NAME_ZFPF.' -- if any at the facility, asked a sample of these individuals, 
(1) "For your work, do you ever have to turn '.HAZSUB_NAME_ADJECTIVE_ZFPF.' valve stems?", 
(2) "Have you been asked to look into the situation when someone reported '.HAZSUB_NAME_ADJECTIVE_ZFPF.' leaks at this facility? What did you do?", and 
(3) "Have you been asked to inspect or maintain '.HAZSUB_NAME_ADJECTIVE_ZFPF.' equipment? What did you do?"')
    ),
    54 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Piping, equipment, and supports tours -- checked a sample of these, in or for the '.HAZSUB_PROCESS_NAME_ZFPF.', and the areas around them for: 
(1) access to them difficult or unsafe, 
(2) lighting or visibility of them inadequate, including
(2.1) in refrigerating-machinery rooms, less than 30 foot-candles (320 lumens per square meter) at 3 feet (0.9 meters) above working surfaces,
(3) insulation problems, such as 
(3.1) none where needed, 
(3.2) frost-covered areas, 
(3.3) cracks or openings in the protective jacket or vapor retarder, or 
(3.4) dents from people stepping on or something hitting the insulation, 
(4) paint problems, such as 
(4.1) none where needed, 
(4.2) pealing, 
(4.3) blistering, or 
(4.4) rust stains, 
(5) excessive ice or frost build-up, 
(6) excessive movement, shaking, vibrating, or noise, 
(7) loose or missing pipe hangers or their saddles, 
(8) piping rests directly on fixed supports so that paint is rubbing off, 
(9) pipe supports too far apart, 
(10) stairs, ladders, catwalks, or their supports corroding or unsafe, especially near evaporative condensers, 
(11) roof sagging under supports, 
(12) foundations for supports cracking or shifting, 
(13) missing, illegible, or inadequate labels on 
(13.1) pipes, 
(13.2) equipment (including pressure vessels), or 
(13.3) valves (unless, for valves, pre-work valve tagging was done for each piping-opening permit and per the facility\'s lockout-tagout policy), 
(14) inadequate guarding or barricades for '.HAZSUB_NAME_ADJECTIVE_ZFPF.'
(14.1) piping, 
(14.2) hanging forced-air evaporators, 
(14.3) other equipment (including pressure vessels, level columns, and sight glasses) exposed to vehicle traffic, or 
(14.4) hot piping, located outside refrigerating-machinery rooms at a height of less than 7.25 feet, with an exposed outside surface temperature of 140 F (60 C) or higher (these hot pipes may have caution signs instead of guarding, IIAR 9-2020 7.2.12.4), 
(15) large quantities of '.HAZSUB_NAME_ADJECTIVE_ZFPF.' storage outside of refrigerating-machinery rooms and where people may be exposed, such as large pressure vessels in industrial occupancies or outside near things like picnic tables, 
(16) piping or equipment unsuited for the full operating or ambient temperature ranges, for example, vessels operating below their minimum design metal temperature (MDMT) (IIAR 9-2020 7.2.3), 
(17) accidental or malicious '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases too easy, such as a piping opening to the atmosphere, for charging, oil draining, purging, sampling, and so forth, that was 
(17.1) sealed only with a valve and not also with a threaded cap or plug or 
(17.2) unlocked and in an place that thieves or vandals could access without more trouble than cutting a lock, 
(18) equipment-enclosure or guarding problems, including 
(18.1) not suitable for location and its physical, chemical, biological, or other environmental challenges (IIAR 2-2019 7.2.6, IIAR 9-2020 7.2.11.1), 
(18.2) inadequate protection of people from moving parts, electricity, dangerous heat, and so forth, and 
(18.3) inadequate access (or other provisions) for inspection, testing, and maintenance, 
(19) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' piping or equipment
(19.1) inside shafts with moving objects, such as elevators or conveyors (IIAR 9-2020 7.4.6.2),
(19.2) in any enclosed spaces accessible to the public, such as lobbies, (IIAR 9-2020 7.4.6.3),
(19.3) touching soil or other materials that may corrode it, without adequate corrosion protection (IIAR 9-2020 7.4.6.4), or 
(19.4) encased in concrete or similar, unless installed in a pipe duct or conduit (IIAR 9-2020 7.4.6.5), or
(20) sight-glass problems, including
(20.1) hydraulic-shock risks too high where installed, such as in some piping for equipment with hot-gas defrost,
(20.2) physical-damage risks too high, due to location or design,
(20.3) linear, tubular or plate, sight glasses without 
(20.3.1) adequate protection against impacts from all directions over their entire length and 
(20.3.2) excess-flow valves, such as spring-check valves, at their inlets and vents, or
(20.4) not suitable for service in the '.HAZSUB_PROCESS_NAME_ZFPF.' (IIAR 9-2020 7.4.7),
(21) ammonia sensors were not installed, in rooms or areas where they were required by the applicable mechanical code or by the facility\'s insurance carrier, or the ammonia sensors didn\'t trigger the following, typically at 25 parts per million by volume (ppm), if required,
(21.1) audible and visible alarms in the room(s) where the sensors detected ammonia,
(21.2) notification of Owner/Operator representatives qualified to muster assistance in emergencies and likely to be reachable on holidays and off hours (so alarms in an always-attended location or the alarm system can call out to multiple people\'s phones or a reliable answering service with a call list), and 
(21.3) shutoff of any liquid and hot gas ammonia supplies to equipment in the rooms (IIAR 2-2019 7.2.3 or insurance carrier rules),
(22) industrial occupancies that were not separated from other occupancy classifications by a well-sealed envelope (walls, floors, ceilings, and so forth), and that contained, or that were open to mechanical penthouses which contained, '.HAZSUB_NAME_ADJECTIVE_ZFPF.' piping or equipment (IIAR 2-2019 7.2.1), or 
(23) ammonia odors.')
    ),
    55 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Refrigerating-machinery room(s) and any nearby large outdoor pressure vessels, such as receivers, tours -- checked for: 
(1) an automatic purger or other safe purging means was installed (IIAR 9-2020 7.2.4), 
(2) each refrigerating-machinery room (the room) was well sealed, so that 
(2.1) the fire ratings of the room\'s envelop were maintained, 
(2.2) air could not flow between the room and other interior spaces, including around or inside things that penetrated the room\'s envelop, such as 
(2.2.1) piping, 
(2.2.2 ) conduits, or 
(2.2.3) ducts, and access panels for ducts were gasketed and tight fitting (IIAR 9-2020 7.3.2.1, 7.3.2.5, and 7.3.6.2), 
(3) the King valve and other hand-operated stop valves uniquely described in leak mitigation and emergency shutdown procedures were 
(3.1) well labeled and 
(3.2) readily accessible to a person wearing a self-contained breathing apparatus (SCBA), such as operable by chain or hand wheel from a safe walking-working surface, unless remotely-controlled valves described in leak mitigation and emergency shutdown procedures gave the same outcome (IIAR 9-2020 7.3.3), 
(4) adequate signage, for example, 
(4.1) outside every door 
(4.1.1) "REFRIGERATION MACHINERY ROOM, AUTHORIZED PERSONNEL ONLY" [yellow], 
(4.1.2) "CAUTION AMMONIA, R-717" [yellow], 
(4.1.3) "CAUTION EYE AND EAR PROTECTION REQUIRED IN THIS AREA" [yellow], and 
(4.1.4) the NFPA diamond, showing Health (blue) = 3, Fire (red) = 3, Reactivity (yellow) = 0, and Special (white) = [leave blank], except, for outdoor equipment, Fire (red) = 1, see National Fire Protection Association (NFPA) Standard 704, 
(4.2) "!WARNING, WHEN ALARMS ARE ACTIVATED, AMMONIA HAS BEEN DETECTED, 1. LEAVE ROOM IMMEDIATELY, 2. DO NOT ENTER EXCEPT BY TRAINED AND AUTHORIZED PERSONNEL, 3. DO NOT ENTER WITHOUT PERSONAL PROTECTIVE EQUIPMENT" [orange] next to all "ammonia concentration in air is unsafe" audible and visible alarms, which are required outside every door into the room and at least one inside the room, 
(4.3) "REFRIGERATION MACHINERY SHUTDOWN, EMERGENCY USE ONLY, BREAK GLASS TO ACTIVATE" [orange; substitute actual activation method for "break glass"], next to each tamper-resistant, off-only, '.HAZSUB_PROCESS_NAME_ZFPF.' emergency-stop switch/button (E-stop) -- at least one E-stop is required, located near the room and where reasonably likely to remain safe and accessible during '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases, and which shuts off all '.HAZSUB_NAME_ADJECTIVE_ZFPF.' 
(4.3.1) compressors, 
(4.3.2) pumps, and 
(4.3.3) normally-closed control valves that are not part of an emergency-control system, such as solenoid valves for liquid and hot-gas supply, but 
(4.3.4) doesn\'t shut off safety systems, such as alarms and ventilation, 
(4.4) "REFRIGERATING-MACHINERY ROOM VENTILATION, EMERGENCY USE ONLY" [orange], next to all the tamper resistant, on/auto switches for the room\'s emergency ventilation -- at least one is required, located near the room and where reasonably likely to remain safe and accessible during '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases, and 
(4.5) near the room and where reasonably likely to remain safe and accessible during '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases 
(4.5.1) a "who to call" sign with instructions for reaching 
(4.5.1.1) local emergency responders and 
(4.5.1.2) Owner/Operator representatives qualified to muster assistance in emergencies and likely to be reachable on holidays and off hours (so multiple names and phone numbers or a reliable answering service with a call list), 
(4.5.2) the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' quantity, 
(4.5.3) the type and quantity of lubricating oil in the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(4.5.4) the field test pressure(s) applied, and also, sometimes optionally, 
(4.5.5) the name, address, and 24-hour service number for the facility\'s primary '.HAZSUB_PROCESS_NAME_ZFPF.' contractor, and 
(4.5.6) any required permits to operate or similar (IIAR 2-2019 5.14, 5.15, 6.15, and Appendix J; IIAR 9-2020 7.2.9.1, 7.2.10, 7.3.11, and 7.3.12), 
(5) safe '.HAZSUB_NAME_ADJECTIVE_ZFPF.' storage, including 
(5.1) only stored in cylinders or pressure vessels designed and labeled by their manufacturer for this service and 
(5.2) no transportation cylinders, vessels, or similar routinely connected to the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(6) safe flammable and combustible materials storage, including 
(6.1) all of these stored in approved fire-rated storage containers, 
(6.2) their quantities kept to the minimum needed for operations and maintenance, and 
(6.3) their quantities and proximity equal to or less that what was assumed in design scenarios for all pressure-relief systems in the room or nearby, 
(7) access and egress was adequate, including 
(7.1) complied with any applicable building code (checked if this was documented or otherwise, in unsprinklered machinery rooms in the USA, this is often a maximum 75-foot "common path of egress travel", which is the portion of the exit access that the occupants are required to traverse before two separate and distinct paths of egress travel to two exits are available, so for example, if an an unsprinklered machinery room had only one exit, no part of the room should be further than 75-feet from that exit, and if such a room had two or more exits, there should be neither blind aisle-ways nor alcoves without exits greater than 75 feet deep), 
(7.2) no part of the room was further than 150-feet from an exit door or exit-access door, 
(7.3) if the room\'s floor area is greater than 1,000 square feet, had two exits separated by more than half of the room\'s maximum horizontal dimension, 
(7.4) exit doors had panic hardware and swung open in the direction or egress, 
(7.5) fire rating of doors was 
(7.5.1) same as exterior wall, if to outside, or 
(7.5.2) 1-hour, if to inside and the room was unsprinklered (IIAR 2-2019, 5.17.8 and 6.10; IIAR 9-2020 7.3.3 and 7.3.9), and 
(7.6) the room was tidy and its floors were not slippery, such as due to oil spills or similar, 
(8) neither open flames nor surfaces greater than 800 F (427 C) were in the room, except: 
(8.1) fuel-burning appliances that 
(8.1.1) were shutoff by a sensor upon detection of ammonia at the concentration that starts the emergency ventilation or 
(8.1.2) had a sealed combustion chamber reliably fed by air from outside the room that was unlikely to contain ammonia during a leak and 
(8.2) matches and sulfur sticks used to detect leaks if any ongoing oil or ammonia draining or charging had been stopped once the leak started (IIAR 9-2020 7.3.5), 
(9) safety showers and eye/face washes --
(9.1) access 
(9.1.1) one inside the room, 
(9.1.2) one outside the room and a maximum 10-second travel time (typically 55 feet) from its door, and 
(9.1.3) additional as needed for a maximum 10-second travel time (typically 55 feet), without doors or other obstructions in the travel path, from all walking-working surfaces in the room to the safety shower and eye/face wash (IIAR 9-2020 7.3.7), 
(9.2) compliance with ANSI/ISEA Z358.1, see the Safety Showers and Eye/Face Washes, Yearly, PSM-CAP compliance practice for ITM, 
(10) floor drains or equivalent as needed to keep floor reasonably free of water, with engineered or administrative means for handling oil or other liquid spills (IIAR 9-2020 7.3.8), 
(11) ammonia sensor(s) in the room, 
(11.1) sampling where ammonia concentrations could likely be highest (so not near fresh-air inlets), 
(11.2) in enough locations, considering the size of the room, 
(11.3) accessible for testing and maintenance, 
(11.4) connected to detectors and alarm systems adequate to, at or below the following parts per million by volume, ammonia concentrations in air (ppm) 
(11.4.1) no ppm signal, such as due to a malfunction or a power loss on the dedicated-branch electrical circuit for these systems -- notify Owner/Operator representatives qualified to muster assistance in emergencies and likely to be reachable on holidays and off hours (so alarms in an always-attended location or the alarm system can call out to multiple people\'s phones or a reliable answering service with a call list), 
(11.4.2) 25 ppm -- activate audible (15 decibels above average-ambient and 5 decibels above expected maximum-ambient sound pressure) and visible alarms in the room and outside its doors (automatic reset allowed once concentration falls below 25 ppm), 
(11.4.3) 50 ppm (preferably 25 ppm) -- notify Owner/Operator representatives, similarly to "no ppm signal" above, 
(11.4.4) 1000 ppm (preferably 150 ppm, which is half the immediately dangerous to life or health, IDLH, concentration) -- activate the room\'s emergency ventilation (manual reset required), and 
(11.4.5) 40,000 ppm (preferably 16,000 ppm, which is approximately one tenth, 10%, of the lower flammability limit, LFL, of ammonia in air, but mixtures of ammonia gas and lubricating-oil mists are more flammable than ammonia alone) -- trigger the same response as the above-described E-stop (manual reset required) (IIAR 2-2019 6.13 and IIAR 9-2020 7.3.12), and 
(12) covers installed on a sample of electrical panels or junction boxes.')
    ),
    56 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Compressors, pumps, and pressure vessels located in miscellaneous indoor areas, such as pits, tunnels, attics, on top of freezer tunnels, between false ceilings the the floor or roof above, or located in outside areas, tours -- checked any of these, in the '.HAZSUB_PROCESS_NAME_ZFPF.', and the areas around them against applicable refrigerating-machinery room requirements, except:
(1) outdoor areas may have had suitable "natural" ventilation from their free opening areas, did\'t need to be well sealed, and so forth,
(2) fresh intake air for pits and tunnels should have been supplied near their lowest areas, such as their floors, and
(3) ventilation flow for pits and tunnels should have accounted for the volume of any rooms they exhaust through (IIAR 2-2019 7.3).')
    ),
    57 => array(
        'c6obsmethod' => $Zfpf->encrypt_1c('Seismic discussions, documents, and tours -- with the responsible individual(s), per the Owner/Operator management system, 
(1) reviewed published seismic maps, such as, in the USA, USGS Seismic Hazard Maps, and discussed seismic risks where the facility was located, and, if applicable:
(2) reviewed the conclusions and recommendations of any reports specifically assessing the as-built seismic safety of the '.HAZSUB_PROCESS_NAME_ZFPF.', including any damage assessments from prior earthquakes,
(3) reviewed a sample of as-built design documents for statements by structural engineers indicating coverage of seismic risks, per indicated design codes and editions, focusing on larger and heavier things, especially when such things were on long supports, creating additional risks from oscillations at their resonance frequency, and also focusing on flexible things near rigid things, such as pipe penetrations through concrete walls, for example,
(3.1) buildings,
(3.2) condenser support structures,
(3.3) large pressure vessels that contained liquid, such as receivers and recirculators, and their supports,
(3.4) anything heavier than piping, such as liquid-containing pressure vessels, that was hung from supports above it,
(3.5) piping supports,
(3.6) pipe penetrations through room or building envelopes, such as walls and floors, which need to be well sealed while allowing for differential movement during earthquakes, and
(3.7) the foundations of all of the above, where applicable, and
(4) during tours, checked a sample of the '.HAZSUB_PROCESS_NAME_ZFPF.' and its supports for consistency with seismic aspects of as-built design documents.')
    )
);
foreach ($NH3ROm as $K => $V) {
    $V['k0obsmethod'] = $K;
    $NH3ROm[$K]['k0obsmethod'] = $V['k0obsmethod']; // Used for subsequently required files
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0obsmethod', $V);
}

