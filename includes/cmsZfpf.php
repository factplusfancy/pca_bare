<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This class serves the change-management system (cms) code. 

class cmsZfpf {

    // $htmlFormArray is specified in the PSM-CAP App Standards, in file 0read_me_psm_cap_app_standards.txt.
    public function htmlFormArray($CMRequired) {
        // For $htmlFormArray['c5affected_entity'] determine which radio buttons to display.
        if (isset($_SESSION['StatePicked']['t0contractor']['k0contractor']))
            $AERadioButtons[] = 'Contractor-wide';
        if (isset($_SESSION['StatePicked']['t0owner']['k0owner']))
            $AERadioButtons[] = 'Owner-wide';
        if (isset($_SESSION['StatePicked']['t0facility']['k0facility']))
            $AERadioButtons[] = 'Facility-wide';
        if (isset($_SESSION['StatePicked']['t0process']['k0process']))
            $AERadioButtons[] = 'Process-wide';
        $htmlFormArray = array(
            'c5name' => array('<a id="c5name"></a>Change name', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF),
            'c6description' => array('Change description', REQUIRED_FIELD_ZFPF, C6SHORT_MAX_BYTES_ZFPF),
            'c5affected_entity' => array(
                'Maximum scope of the proposed change (the affected entity)',
                REQUIRED_FIELD_ZFPF,
                C5_MAX_BYTES_ZFPF,
                'radio',
                $AERadioButtons
            ),
            // k0affected_entity omitted because c5affected_entity already triggers this field.
            'k0user_of_initiator' => array('Change initiator', ''),
            'c6cm_applies_checks' => array(
                'Will the proposed change alter any of the following?',
                '',
                array(
                    array('(Test 1) the piping and instrumentation diagram for the '.HAZSUB_PROCESS_NAME_ZFPF.'', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 2) any procedure for the '.HAZSUB_PROCESS_NAME_ZFPF.', including hazardous-substance procedures and safe-work practices or inspection, testing, and maintenance (ITM) procedures', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 3) any component of the '.HAZSUB_PROCESS_NAME_ZFPF.' or its safety systems  -- including pressure-relief, emergency ventilation, instrumentation, and controls -- such that it no longer "satisfies the design specification"', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 4) the loads on buildings or structures that support the '.HAZSUB_PROCESS_NAME_ZFPF.', such as the addition of significant new loads to roofs carrying its piping or equipment', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 5) the potential for anything to fall on, catch fire or explode near, corrode, or damage the '.HAZSUB_PROCESS_NAME_ZFPF.', including materials storage or use and cleaning or sanitation', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 6) traffic patterns near the '.HAZSUB_PROCESS_NAME_ZFPF.', such as rearranging storage racks', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 7) the distance or protective structures between the '.HAZSUB_PROCESS_NAME_ZFPF.' and commonly occupied areas, such as temporary construction offices, break rooms, loading docks, some production areas, and so forth', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 8) the distance between '.HAZSUB_NAME_ADJECTIVE_ZFPF.' pressure-relief or ventilation discharge zones (considering fluid and air flows after discharge) and doors, windows, air intakes, ladders, or walking-working surfaces (including catwalks)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 9) ITM or emergency-response access to the '.HAZSUB_PROCESS_NAME_ZFPF.'', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 10) ignition sources, especially open flames or over 750 F (400 C) surfaces, or electrical classifications near the '.HAZSUB_PROCESS_NAME_ZFPF.'', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 11) the controls for the '.HAZSUB_PROCESS_NAME_ZFPF.' or its safety systems, including ventilation, sensors and detectors, alarms, automatic shutoffs for compressors, pumps, and fans, interlocks, suppression systems, and so forth', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 12) the set-points or actions triggered by sensors, pressure-relief valves, or shutoffs devices for the '.HAZSUB_PROCESS_NAME_ZFPF.'', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 13) the safe-operating ranges for temperatures, pressures, flows, compositions, and so forth in the '.HAZSUB_PROCESS_NAME_ZFPF.'', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 14) the specifications of materials put into the '.HAZSUB_PROCESS_NAME_ZFPF.' (including any lubricating oil)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 15) the safe-operating ranges and specifications of liquids (including the composition, pressure, and temperature of cooling water, glycol, brine, and including during sanitation, startup, shutdown, and so forth) that contact heat-exchanger components (including jackets, plates, and tubes), which are part of the '.HAZSUB_NAME_ADJECTIVE_ZFPF.' containment envelope', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 16) those portions of utilities, such as electrical supply, that support or interface with the '.HAZSUB_PROCESS_NAME_ZFPF.' such that their failure might cause '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases, explosions, emergency ventilation failure, or impede emergency response', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 17) the number, qualifications, or authority of personnel with process safety, operations, or ITM responsibilities for the '.HAZSUB_PROCESS_NAME_ZFPF.' -- such that changes to process chemicals, technology, equipment, procedures, or facilities are needed to accommodate these organizational changes (March 31, 2009, OSHA interpretation, memo to Regional Administrators)', '', C5_MAX_BYTES_ZFPF, 'checkbox'),
                    array('(Test 18) anything else that might increase the likelihood of '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases, impede mitigation of '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases, or hinder emergency response to '.HAZSUB_NAME_ADJECTIVE_ZFPF.' releases', '', C5_MAX_BYTES_ZFPF, 'checkbox')
                )
            ),
            'k0user_of_applic_approver' => array('<a id="k0user_of_applic_approver"></a>Change-management applicability determination approved by', '', C5_MAX_BYTES_ZFPF, array('', 'Applicability Determination'))
        );
        if ($CMRequired) {
            // Additional fields if change management is required.
            $CMRhtmlFormArray = array(
                'k0user_of_project_manager' => array('<a id="k0user_of_project_manager"></a>Change Project Manager', '', C5_MAX_BYTES_ZFPF, array('', 'Change Project Manager')),
                'c5duration' => array(
                    'Change duration', 
                    REQUIRED_FIELD_ZFPF, 
                    C5_MAX_BYTES_ZFPF, 
                    'radio',
                    array('Permanent', 'Other/Temporary. Indicate time or conditions for change to end (and return to current situation)')
                ),
                'c5ts_psr_requested' => array('Target date and time for startup authorization, which shall be before putting any hazardous substance into new or altered piping or equipment, if applicable', REQUIRED_FIELD_ZFPF, C5_MAX_BYTES_ZFPF), // Note, this is not in schema order, to display at desired location, which works fine for this app.
                'c5reason' => array('Reason for change', '', C5_MAX_BYTES_ZFPF),
                'c6bfn_markup' => array('<a id="c6bfn_markup"></a><b>Full description of change</b>, such as: 
(1) a project manual or 
(2) markups showing planned changes to the 
(2.1) process-safety information, 
(2.2) hazardous-substance procedures and safe-work practices, 
(2.3) inspection, testing, and maintenance (ITM) program, 
(2.4) emergency plans, and so forth', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_dr' => array('<a id="c6bfn_dr"></a><b>Design review</b>, leader. Effort may include uploading copies of, as needed:<br />
(1) permit applications and permits, including building, electrical, environmental, hazardous substance, mechanical, and so forth,<br />
(2) plans, specifications, and calculations, showing the edition of applicable codes and standards, and including any needed <b>evaluations of performance</b> -- such as contraction and expansion, corrosion, erosion, reactions (chemical, physical...), shocks, stresses and strains, vibrations, weakening, and so forth -- <b>over the code-required design or other reasonably possible ranges for</b><br />
(2.1) '.HAZSUB_PROCESS_NAME_ZFPF.' internal conditions, such as<br />
(2.1.1) temperature,<br />
(2.1.2) pressure,<br />
(2.1.3) flow,<br />
(2.1.4) composition,<br />
(2.1.5) any other relevant process parameters,<br />
(2.2) failures of any needed utilities, for example compressed air, electrical (outages, blips, restoration...), or water,<br />
(2.3) relevant external circumstances, such as<br />
(2.3.1) earthquakes/seismic,<br />
(2.3.2) substances stored or handled nearby and the corrosion, ignition sources, fires, or explosions they may cause or promote (cleaning, corrosive, combustible, oxidizers, and electrical equipment, transformers, and wires...),<br />
(2.3.3) traffic (impacts from airplanes, carts, forklifts, trains, trucks...),<br />
(2.3.4) vandalism, terrorism, or theft,<br />
(2.3.5) weather (ice, humidity, hurricanes, rain, salinity/sea spray, sand or dust storms, snow, tornadoes, wind...), and so forth,<br />
(3) protecting people (life safety), onsite or offsite, documentation, such as any needed<br />
(3.1) locating and siting studies,<br />
(3.1) assessments of explosions, fires, or leaks,<br />
(3.2) code-required separations, and/or<br />
(3.3) separation of '.HAZSUB_NAME_ADJECTIVE_ZFPF.' equipment and piping, installed as part of the change, from people to the extent practical (for example by locating them in machinery rooms or penthouses, above roofs, or outdoors),<br />
(4) fastening and joining procedures, including welding-procedure specifications and qualification records,<br />
(5) insulation and paint specifications, covering any needed corrosion-under-insulation prevention,<br />
(6) documentation of post-design changes (change orders), and so forth,<br />
<b> after reviewing them as needed to verify that they describe how to safely and legally complete changes to, as applicable: </b><br />
(A) the '.HAZSUB_PROCESS_NAME_ZFPF.', including<br />
(A.1) its instrumentation and controls and<br />
(A.2) its primary-containment envelope, such as<br />
(A.2.1) piping and<br />
(A.2.2) equipment, including compressors, pumps, and vessels,<br />
(B) safety systems for the '.HAZSUB_PROCESS_NAME_ZFPF.', such as<br />
(B.1) alarms, including audible and visible alarms and notification systems that call, text, or email Owner/Operator representatives,<br />
(B.2) automatic shutoffs, including for compressors, pumps, valves, and so forth,<br />
(B.3) interlocks,<br />
(B.4) instrumentation and controls for safety systems, including sensors, detectors, microprocessors and so forth,<br />
(B.5) pressure-relief systems,<br />
(B.6) suppression systems, and<br /> 
(B.7) ventilation systems, and so forth, and<br />
(C) things the '.HAZSUB_PROCESS_NAME_ZFPF.' relies on, such as<br />
(C.1) supports, including<br />
(C.1.1) hangers, rods, saddles, and so forth,<br />
(C.1.2) building structures,<br />
(C.1.3) other structures,<br />
(C.1.4) foundations and geotechnical/subsurface conditions, and<br />
(C.1.5) all support-related fasteners,<br />
(C.2) room and building envelopes or other protection from the weather,<br />
(C.3) electrical systems, including<br />
(C.3.1) any hazardous-location classifications and related engineering or administrative controls,<br />
(C.4) other utilities, and so forth', '', C5_MAX_BYTES_ZFPF, array('Typically needed if mechanical, structural, or other engineered/designed systems will be changed.', 'Design Review')),
                'c6notes_dr' => array('Design review notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_dr' => array('Design review supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_ehsr' => array('<a id="c6bfn_ehsr"></a><b>Environmental, health, and safety (EHS) Review</b>, leader', '', C5_MAX_BYTES_ZFPF, array('Typically needed if the change may affect environmental, health, or safety programs, equipment, or infrastructure.', 'EHS Review')),
                'c6notes_ehsr' => array('EHS review notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_ehsr' => array('EHS review supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_hrr' => array('<a id="c6bfn_hrr"></a><b>Human-resources (HR) review</b>, leader', '', C5_MAX_BYTES_ZFPF, array('Typically needed if the change may increase workload, needed skills, or responsibilities.', 'HR Review')),
                'c6notes_hrr' => array('HR review notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_hrr' => array('HR review supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_cont_qual' => array('<a id="c6bfn_cont_qual"></a><b>Contractor Qualification</b> for this change, leader. Effort may include uploading welder performance qualification and continuity records as well as completing applicable requirements in the "contractor" division of this PSM-CAP App', '', C5_MAX_BYTES_ZFPF, array('', 'Contractor Qualification')),
                'c6notes_cont_qual' => array('Contractor qualification notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_cont_qual' => array('Contractor qualification supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_act_notice' => array('<a id="c6bfn_act_notice"></a><b>Activity Notice</b> -- posting and filing, leader', '', C5_MAX_BYTES_ZFPF, array('', 'Activity Notice')),
                'c6notes_act_notice' => array('Activity Notice notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_act_notice' => array('Activity Notice supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_psi' => array('<a id="c6bfn_psi"></a><b>Process-safety information (PSI) as-built documents</b>, leader for updating. Effort may include uploading, as applicable: 
(1) as-built versions of all documents needed for the above design review, 
(2) delivery documents plus manufacturers\' manuals, reports, or equivalent for everything installed, such as 
(2.1) insulation and paint manufacturers\' specifications or product descriptions, 
(2.2) manufacturers\' manuals or other documents covering materials of construction, installation, startup, operation, and maintenance, including for controls, compressors, instrumentation, pumps, and valves, 
(2.3) piping material test reports, 
(2.4) vessel data reports and shop drawings, and 
<b>for the entire '.HAZSUB_PROCESS_NAME_ZFPF.', the updated </b>
(3) flow diagram, 
(4) '.HAZSUB_NAME_ADJECTIVE_ZFPF.' maximum-intended inventory and 
(4.1) any optional inventories of materials contained in the '.HAZSUB_PROCESS_NAME_ZFPF.', 
(5) operating limits, deviation consequences, controls, and safety systems document(s), 
(6) piping and instrumentation diagram and component list, 
(7) material and energy balances, and 
(8) any other needed PSI', '', C5_MAX_BYTES_ZFPF, array('', 'PSI')),
                'c6notes_psi' => array('Process-safety information as-built documents notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_psi' => array('Process-safety information as-built documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_pha_amend' => array('<a id="c6bfn_pha"></a><b>Process-hazard analysis (PHA) amendment</b>, leader. Background: 
"New facilities" require a PHA amendment, whereas "modified facilities" do not per 29 CFR 1910.119(i)(2)(iii). 
"Facility means the buildings, containers or equipment which contain a process," but neither "new" nor "modified" are defined by 29 CFR 1910.119(b). 
Opinions differ, but to be safe, amend the PHA if the change will add buildings, piping, vessels, or other equipment that are different enough from what the current PHA evaluated that additional scenarios are needed to evaluate their hazards. 
Option: amend the PHA as needed to keep it continuously up to date. 
Use the PHA division of this PSM-CAP App to add any needed subsystems, scenarios, or analysis to the PHA or other hazard identification and risk analysis', '', C5_MAX_BYTES_ZFPF, array('', 'PHA Amendment')),
                'c6notes_pha_amend' => array('PHA amendment notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_pha_amend' => array('PHA amendment supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_hs_omp_swp' => array('<a id="c6bfn_hs_omp_swp"></a><b>Hazardous-substance procedures and safe-work practices updates</b>, leader', '', C5_MAX_BYTES_ZFPF, array('', 'Procedures')),
                'c6notes_hs_omp_swp' => array('Hazardous-substance procedures and safe-work practices updates notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_hs_omp_swp' => array('Hazardous-substance procedures and safe-work practices updates supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_training' => array('<a id="c6bfn_training"></a><b>Training on hazardous-substance procedures and safe-work practices updates</b>, leader. Effort may include: 
(1) identifying employees or contractors who need to be trained on these updates, 
(2) scheduling the training, and 
(3) documenting the means used to verify that each of these employees or contractors understood their training on these updates. 
Training of contractor individuals may be done and documented by their contractor organization', '', C5_MAX_BYTES_ZFPF, array('', 'Training')),
                'c6notes_training' => array('Training on hazardous-substance procedures and safe-work practices updates notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_training' => array('Training on hazardous-substance procedures and safe-work practices updates supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_hs_pm' => array('<a id="c6bfn_hs_pm"></a><b>Inspection, testing, and maintenance (ITM) program updates</b>, leader. Effort may include updates to, as needed: 
(1) the work order or similar system, covering 
(1.1) scheduling, 
(1.2) summarizing the tasks, needed qualifications or training, methods, parts, and materials, 
(1.3) recording any results, 
(1.4) tracking resolution of any deficiencies, and 
(1.5) recordkeeping and 
(2) training affected employees and contractors on these updates, 
as needed for the safe operation and mechanical integrity of the '.HAZSUB_PROCESS_NAME_ZFPF, '', C5_MAX_BYTES_ZFPF, array('', 'ITM')),
                'c6notes_hs_pm' => array('ITM-program updates and training notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_hs_pm' => array('ITM-program updates and training documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_emergency' => array('<a id="c6bfn_emergency"></a><b>Emergency-plans updates and training</b>, leader. Effort may include updates to, as needed:
(1) facility plans, such as the
(1.1) Emergency Action Plan, 
(1.2) any employee emergency-response plans, and so forth, 
(2) training employees and contractors on these updates, and 
(3) notifying offsite (community or contracted) emergency responders about potentially needed changes to their plans', '', C5_MAX_BYTES_ZFPF, array('', 'Emergency Planning')),
                'c6notes_emergency' => array('Emergency-plans updates and training notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_emergency' => array('Emergency-plans updates and training supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_iet' => array('<a id="c6bfn_iet"></a><b>Construction/fabrication inspection, examination, and testing (IET)</b>, leader. Effort may include, as needed, requesting and uploading documentation that, for everything the change affected: 
(1) "as-built satisfies design", such as 
(1.1) inspection or observation of construction reports, 
(1.2) material or other test reports, 
(1.3) written representations or warranties from the contractor(s) who did the work, and 
(2) the following were satisfactorily completed:
(2.1) manufacturers\' installation and startup instructions, such as for compressors, pumps, and so forth, 
(2.2) leak tests, 
(2.3) pressure tests, 
(2.4) shaft alignment and other needed alignment, 
(2.5) vibration analysis, 
(2.6) welding inspections or tests, and so forth', '', C5_MAX_BYTES_ZFPF, array('', 'IET')),
                'c6notes_iet' => array('Construction/fabrication inspection, examination, and testing notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_iet' => array('Construction/fabrication inspection, examination, and testing supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_implement' => array('<a id="c6bfn_implement"></a><b>Change final implementation and startup</b>, leader. Effort may include procedures for and supervision of special isolation, pump-down, venting, cleaning, tie-in, startup, and other tasks affecting piping or equipment that already contain hazardous substances', '', C5_MAX_BYTES_ZFPF, array('', 'Implementation')),
                'c6notes_implement' => array('Change final implementation and startup notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_implement' => array('Change final implementation and startup supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files'),
                'k0user_of_psr' => array('<a id="c6bfn_psr"></a><b>Startup Authorization</b>. This task includes: 
(1) overall responsibility for, and resolving recommendations from, all change-management tasks, including any that were not assigned above, and 
(2) any other pre-startup safety review (PSR) tasks needed to verify that 
(2.1) the entire '.HAZSUB_PROCESS_NAME_ZFPF.' and everything it depends on or affects, including buildings, structures, supports, piping, vessels, and equipment, as built, are 
(2.1.1) fit for service (in other words, suitable for the process application for which they will be used) and 
(2.1.2) reasonably and prudently safe, and 
(2.2) all things changed, for the current project, are in accordance with their 
(2.2.1) design specifications, 
(2.2.2) instructions from manufacturers of their component parts, if any, and 
(2.2.3) applicable legal requirements. 
<b>Before startup authorization, neither </b>
(A) put any hazardous substance into new or altered piping or equipment <b>nor </b>
(B) implement a change to the stage when training is needed on new or altered procedures, practices, or controls. 
Affected-entity leader', '', C5_MAX_BYTES_ZFPF, array('', 'Startup')),
                'c6notes_psr' => array('Startup Authorization and PSR notes', '', C6LONG_MAX_BYTES_ZFPF),
                'c6bfn_psr' => array('Startup Authorization and PSR supporting documents', '', MAX_FILE_SIZE_ZFPF, 'upload_files')
            );
            $htmlFormArray = array_merge($htmlFormArray, $CMRhtmlFormArray);
            // Additional left-hand contents if change management is required and if not in i2 confirmation form, where anchors referenced below may not be set.
            if (isset($_POST['cms_o1']))
                $_SESSION['Scratch']['PlainText']['left_hand_contents_on_page_anchors'] = array(
                    'c5name' => 'Change name',
                    'k0user_of_applic_approver' => 'Applicability determination',
                    'k0user_of_project_manager' => 'Project manager',
                    'c6bfn_markup' => 'Description',
                    'c6bfn_dr' => 'Design review',
                    'c6bfn_ehsr' => 'EHS review',
                    'c6bfn_hrr' => 'HR review',
                    'c6bfn_cont_qual' => 'Contractor qualification',
                    'c6bfn_act_notice' => 'Activity notice',
                    'c6bfn_psi' => 'PSI update',
                    'c6bfn_pha' => 'PHA amendment',
                    'c6bfn_hs_omp_swp' => 'Procedures',
                    'c6bfn_training' => 'Training',
                    'c6bfn_hs_pm' => 'ITM',
                    'c6bfn_emergency' => 'Emergency planning',
                    'c6bfn_iet' => 'IET',
                    'c6bfn_implement' => 'Implementation',
                    'c6bfn_psr' => 'Startup'
                );
        }
        return $htmlFormArray;
    }
    
    public function html_form_modify($htmlFormArray, $Display, $CMRequired) {
        if ($CMRequired)
            $htmlFormArray['c6cm_applies_checks'][0] = 'Change management is required because reportedly the proposed change will alter the following.';
        else {
            $htmlFormArray['c6cm_applies_checks'] = array('Change-Management Applicability Determination', '');
            $Display['c6cm_applies_checks'] = 'Change Management is not required. Reportedly nothing on the change-management applicability checklist will be altered by the proposed change.';
        }
        return array($htmlFormArray, $Display);
    }

}

