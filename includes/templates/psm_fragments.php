<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// Populate t0fragment with OSHA's PSM rule
// $psm_fragments contains the entire OSHA PSM rule (without appendices).
$psm_fragments = array(
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('Rule Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119'),
        'c6quote' => $Zfpf->encrypt_1c('Process safety management of highly hazardous chemicals.')
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('Rule Purpose'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119'),
        'c6quote' => $Zfpf->encrypt_1c('Purpose. This section contains requirements for preventing or minimizing the consequences of catastrophic releases of toxic, reactive, flammable, or explosive chemicals. These releases may result in toxic, fire or explosion hazards.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(a)'),
        'c6quote' => $Zfpf->encrypt_1c('(a) Application. (1) This section applies to the following: (i) A process which involves a chemical at or above the specified threshold quantities listed in appendix A to this section; (ii) A process which involves a flammable liquid or gas (as defined in 1910.1200(c) of this part) on site in one location, in a quantity of 10,000 pounds (4535.9 kg) or more except for: (A) Hydrocarbon fuels used solely for workplace consumption as a fuel (e.g., propane used for comfort heating, gasoline for vehicle refueling), if such fuels are not a part of a process containing another highly hazardous chemical covered by this standard; (B) Flammable liquids stored in atmospheric tanks or transferred which are kept below their normal boiling point without benefit of chilling or refrigeration. (2) This section does not apply to: (i) Retail facilities; (ii) Oil or gas well drilling or servicing operations; or, (iii) Normally unoccupied remote facilities.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    4 => array(
        'c5name' => $Zfpf->encrypt_1c('Atmospheric Tank'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Atmospheric tank means a storage tank which has been designed to operate at pressures from atmospheric through 0.5 p.s.i.g. (pounds per square inch gauge, 3.45 Kpa).'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    5 => array(
        'c5name' => $Zfpf->encrypt_1c('Boiling Point'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Boiling point means the boiling point of a liquid at a pressure of 14.7 pounds per square inch absolute (p.s.i.a.) (760 mm.). For the purposes of this section, where an accurate boiling point is unavailable for the material in question, or for mixtures which do not have a constant boiling point, the 10 percent point of a distillation performed in accordance with the Standard Method of Test for Distillation of Petroleum Products, ASTM D–86–62, which is incorporated by reference as specified in [29 CFR] 1910.6, may be used as the boiling point of the liquid.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    6 => array(
        'c5name' => $Zfpf->encrypt_1c('Catastrophic Release'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Catastrophic release means a major uncontrolled emission, fire, or explosion, involving one or more highly hazardous chemicals, that presents serious danger to employees in the workplace.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    7 => array(
        'c5name' => $Zfpf->encrypt_1c('Facility'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Facility means the buildings, containers or equipment which contain a process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    8 => array(
        'c5name' => $Zfpf->encrypt_1c('Highly Hazardous Chemical'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Highly hazardous chemical means a substance possessing toxic, reactive, flammable, or explosive properties and specified by paragraph (a)(1) of this section.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    9 => array(
        'c5name' => $Zfpf->encrypt_1c('Hot Work'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Hot work means work involving electric or gas welding, cutting, brazing, or similar flame or spark-producing operations.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    10 => array(
        'c5name' => $Zfpf->encrypt_1c('Normally Unoccupied Remote Facility'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Normally unoccupied remote facility means a facility which is operated, maintained or serviced by employees who visit the facility only periodically to check its operation and to perform necessary operating or maintenance tasks. No employees are permanently stationed at the facility. Facilities meeting this definition are not contiguous with, and must be geographically remote from all other buildings, processes or persons.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    11 => array(
        'c5name' => $Zfpf->encrypt_1c('Process'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Process means any activity involving a highly hazardous chemical including any use, storage, manufacturing, handling, or the on-site movement of such chemicals, or combination of these activities. For purposes of this definition, any group of vessels which are interconnected and separate vessels which are located such that a highly hazardous chemical could be involved in a potential release shall be considered a single process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    12 => array(
        'c5name' => $Zfpf->encrypt_1c('Replacement in Kind'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Replacement in kind means a replacement which satisfies the design specification.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    13 => array(
        'c5name' => $Zfpf->encrypt_1c('Trade Secret'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Trade secret means any confidential formula, pattern, process, device, information or compilation of information that is used in an employer\'s business, and that gives the employer an opportunity to obtain an advantage over competitors who do not know or use it. Appendix D contained in [29 CFR] 1910.1200 sets out the criteria to be used in evaluating trade secrets.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    14 => array(
        'c5name' => $Zfpf->encrypt_1c('Employee Participation Plan'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(c)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Employee participation. (1) Employers shall develop a written plan of action regarding the implementation of the employee participation required by this paragraph.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    15 => array(
        'c5name' => $Zfpf->encrypt_1c('Consultation with Employees'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(c)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('Employers shall consult with employees and their representatives on the conduct and development of process hazards analyses and on the development of the other elements of process safety management in this standard.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    16 => array(
        'c5name' => $Zfpf->encrypt_1c('Information Access'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(c)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('Employers shall provide to employees and their representatives access to process hazard analyses and to all other information required to be developed under this standard.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    17 => array(
        'c5name' => $Zfpf->encrypt_1c('Timing (before PHA or HIRA) and Purpose'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)'),
        'c6quote' => $Zfpf->encrypt_1c('Process safety information. In accordance with the schedule set forth in paragraph (e)(1) of this section, the employer shall complete a compilation of written process safety information before conducting any process hazard analysis required by the standard. The compilation of written process safety information is to enable the employer and the employees involved in operating the process to identify and understand the hazards posed by those processes involving highly hazardous chemicals. This process safety information shall include information pertaining to the hazards of the highly hazardous chemicals used or produced by the process, information pertaining to the technology of the process, and information pertaining to the equipment in the process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    18 => array(
        'c5name' => $Zfpf->encrypt_1c('Process-Materials Properties and Hazards'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Information pertaining to the hazards of the highly hazardous chemicals in the process. This information shall consist of at least the following: (i) Toxicity information; (ii) Permissible exposure limits; (iii) Physical data; (iv) Reactivity data; (v) Corrosivity data; (vi) Thermal and chemical stability data; and
(vii) Hazardous effects of inadvertent mixing of different materials that could foreseeably occur. NOTE: Safety Data Sheets meeting the requirements of 29 CFR 1910.1200(g) may be used to comply with this requirement to the extent they contain the information required by this subparagraph.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    19 => array(
        'c5name' => $Zfpf->encrypt_1c('Process Technology Introduction'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('Information pertaining to the technology of the process. (i) Information concerning the technology of the process shall include at least the following:'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    20 => array(
        'c5name' => $Zfpf->encrypt_1c('Flow Diagram'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(2)(i)(A)'),
        'c6quote' => $Zfpf->encrypt_1c('A block flow diagram or simplified process flow diagram (see appendix B to this section);'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    21 => array(
        'c5name' => $Zfpf->encrypt_1c('Process Chemistry'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(2)(i)(B)'),
        'c6quote' => $Zfpf->encrypt_1c('Process chemistry;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    22 => array(
        'c5name' => $Zfpf->encrypt_1c('Maximum Intended Inventory'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(2)(i)(C)'),
        'c6quote' => $Zfpf->encrypt_1c('Maximum intended inventory;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    23 => array(
        'c5name' => $Zfpf->encrypt_1c('Operating Limits'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(2)(i)(D)'),
        'c6quote' => $Zfpf->encrypt_1c('Safe upper and lower limits for such items as temperatures, pressures, flows or compositions; and,'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    24 => array(
        'c5name' => $Zfpf->encrypt_1c('Consequences of Deviations'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(2)(i)(E)'),
        'c6quote' => $Zfpf->encrypt_1c('An evaluation of the consequences of deviations, including those affecting the safety and health of employees.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    25 => array(
        'c5name' => $Zfpf->encrypt_1c('Original Information No Longer Exists'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(2)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('Where the original technical information no longer exists, such information may be developed in conjunction with the process hazard analysis in sufficient detail to support the analysis.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    26 => array(
        'c5name' => $Zfpf->encrypt_1c('Process Equipment Introduction'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('Information pertaining to the equipment in the process. (i) Information pertaining to the equipment in the process shall include:'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    27 => array(
        'c5name' => $Zfpf->encrypt_1c('Materials of Construction'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(i)(A)'),
        'c6quote' => $Zfpf->encrypt_1c('Materials of construction;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    28 => array(
        'c5name' => $Zfpf->encrypt_1c('P&amp;ID'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(i)(B)'),
        'c6quote' => $Zfpf->encrypt_1c('Piping and instrument diagrams [P&amp;ID];'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    29 => array(
        'c5name' => $Zfpf->encrypt_1c('Electrical Classifications'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(i)(C)'),
        'c6quote' => $Zfpf->encrypt_1c('Electrical classification;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    30 => array(
        'c5name' => $Zfpf->encrypt_1c('Relief Systems'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(i)(D)'),
        'c6quote' => $Zfpf->encrypt_1c('Relief system design and design basis;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    31 => array(
        'c5name' => $Zfpf->encrypt_1c('Ventilation Systems'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(i)(E)'),
        'c6quote' => $Zfpf->encrypt_1c('Ventilation system design;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    32 => array(
        'c5name' => $Zfpf->encrypt_1c('Codes, Standards, etc.'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(i)(F)'),
        'c6quote' => $Zfpf->encrypt_1c('Design codes and standards employed;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    33 => array(
        'c5name' => $Zfpf->encrypt_1c('Material and Energy Balances'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(i)(G)'),
        'c6quote' => $Zfpf->encrypt_1c('Material and energy balances for processes built after May 26, 1992; and,'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    34 => array(
        'c5name' => $Zfpf->encrypt_1c('Other Safety Systems'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(i)(H)'),
        'c6quote' => $Zfpf->encrypt_1c('Safety systems (e.g. interlocks, detection or suppression systems).'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    35 => array(
        'c5name' => $Zfpf->encrypt_1c('Good Practices'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall document that equipment complies with recognized and generally accepted good engineering practices.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    36 => array(
        'c5name' => $Zfpf->encrypt_1c('No Longer in General Use'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(d)(3)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('For existing equipment designed and constructed in accordance with codes, standards, or practices that are no longer in general use, the employer shall determine and document that the equipment is designed, maintained, inspected, tested, and operating in a safe manner.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    37 => array(
        'c5name' => $Zfpf->encrypt_1c('PHA Required'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(e)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Process hazard analysis. (1) The employer shall perform an initial process hazard analysis (hazard evaluation) on processes covered by this standard. [Next sentance in Content fragment below, with (e)(1) and (3) citation. Omitted no longer relevant 1990s phase-in rules.]'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    38 => array(
        'c5name' => $Zfpf->encrypt_1c('Method'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(e)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall use one or more of the following methodologies that are appropriate to determine and evaluate the hazards of the process being analyzed. (i) What-If; (ii) Checklist; (iii) What-If/Checklist; (iv) Hazard and Operability Study (HAZOP); (v) Failure Mode and Effects Analysis (FMEA); (vi) Fault Tree Analysis; or (vii) An appropriate equivalent methodology.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    39 => array(
        'c5name' => $Zfpf->encrypt_1c('Content'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(e)(1) and (3)'),
        'c6quote' => $Zfpf->encrypt_1c('(e)(1) [...] The process hazard analysis shall be appropriate to the complexity of the process and shall identify, evaluate, and control the hazards involved in the process. [...] (3) The process hazard analysis shall address: (i) The hazards of the process; (ii) The identification of any previous incident which had a likely potential for catastrophic consequences in the workplace; (iii) Engineering and administrative controls applicable to the hazards and their interrelationships such as appropriate application of detection methodologies to provide early warning of releases. (Acceptable detection methods might include process monitoring and control instrumentation with alarms, and detection hardware such as hydrocarbon sensors.); (iv) Consequences of failure of engineering and administrative controls; (v) Facility siting; (vi) Human factors; and (vii) A qualitative evaluation of a range of the possible safety and health effects of failure of controls on employees in the workplace.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    40 => array(
        'c5name' => $Zfpf->encrypt_1c('Team Qualifications'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(e)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('The process hazard analysis shall be performed by a team with expertise in engineering and process operations, and the team shall include at least one employee who has experience and knowledge specific to the process being evaluated. Also, one member of the team must be knowledgeable in the specific process hazard analysis methodology being used.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    41 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(e)(5)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall establish a system to promptly address the team\'s findings and recommendations; assure that the recommendations are resolved in a timely manner and that the resolution is documented; document what actions are to be taken; complete actions as soon as possible; develop a written schedule of when these actions are to be completed; communicate the actions to operating, maintenance and other employees whose work assignments are in the process and who may be affected by the recommendations or actions.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    42 => array(
        'c5name' => $Zfpf->encrypt_1c('Update and Revalidate'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(e)(6)'),
        'c6quote' => $Zfpf->encrypt_1c('At least every five (5) years after the completion of the initial process hazard analysis, the process hazard analysis shall be updated and revalidated by a team meeting the requirements in paragraph (e)(4) of this section, to assure that the process hazard analysis is consistent with the current process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    43 => array(
        'c5name' => $Zfpf->encrypt_1c('Retention'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(e)(7)'),
        'c6quote' => $Zfpf->encrypt_1c('Employers shall retain process hazards analyses and updates or revalidations for each process covered by this section, as well as the documented resolution of recommendations described in paragraph (e)(5) of this section for the life of the process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    44 => array(
        'c5name' => $Zfpf->encrypt_1c('Purpose'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Operating procedures (1) The employer shall develop and implement written operating procedures that provide clear instructions for safely conducting activities involved in each covered process consistent with the process safety information and shall address at least the following elements.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    45 => array(
        'c5name' => $Zfpf->encrypt_1c('Operating Phases'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(i)'),
        'c6quote' => $Zfpf->encrypt_1c('Steps for each operating phase:'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    46 => array(
        'c5name' => $Zfpf->encrypt_1c('Initial Startup'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(i)(A)'),
        'c6quote' => $Zfpf->encrypt_1c('Initial startup;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    47 => array(
        'c5name' => $Zfpf->encrypt_1c('Normal Operations'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(i)(B)'),
        'c6quote' => $Zfpf->encrypt_1c('Normal operations;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    48 => array(
        'c5name' => $Zfpf->encrypt_1c('Temporary Operations'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(i)(C)'),
        'c6quote' => $Zfpf->encrypt_1c('Temporary operations;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    49 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Shutdown'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(i)(D)'),
        'c6quote' => $Zfpf->encrypt_1c('Emergency shutdown including the conditions under which emergency shutdown is required, and the assignment of shutdown responsibility to qualified operators to ensure that emergency shutdown is executed in a safe and timely manner;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    50 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Operations'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(i)(E)'),
        'c6quote' => $Zfpf->encrypt_1c('Emergency Operations;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    51 => array(
        'c5name' => $Zfpf->encrypt_1c('Normal Shutdown'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(i)(F)'),
        'c6quote' => $Zfpf->encrypt_1c('Normal shutdown; and,'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    52 => array(
        'c5name' => $Zfpf->encrypt_1c('Startup After Unusual Events'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(i)(G)'),
        'c6quote' => $Zfpf->encrypt_1c('Startup following a turnaround, or after an emergency shutdown.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    53 => array(
        'c5name' => $Zfpf->encrypt_1c('Operating Limits, Deviation Consequences, and Corrective Actions'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('Operating limits: (A) Consequences of deviation; and (B) Steps required to correct or avoid deviation.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    54 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety and Health'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('Safety and health considerations:'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    55 => array(
        'c5name' => $Zfpf->encrypt_1c('Process-Materials Properties and Hazards'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(iii)(A)'),
        'c6quote' => $Zfpf->encrypt_1c('Properties of, and hazards presented by, the chemicals used in the process;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    56 => array(
        'c5name' => $Zfpf->encrypt_1c('Exposure Prevention'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(iii)(B)'),
        'c6quote' => $Zfpf->encrypt_1c('Precautions necessary to prevent exposure, including engineering controls, administrative controls, and personal protective equipment;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    57 => array(
        'c5name' => $Zfpf->encrypt_1c('First Aid'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(iii)(C)'),
        'c6quote' => $Zfpf->encrypt_1c('Control measures to be taken if physical contact or airborne exposure occurs;'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    58 => array(
        'c5name' => $Zfpf->encrypt_1c('Raw-Materials Quality Control'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(iii)(D)[01]'),
        'c6quote' => $Zfpf->encrypt_1c('Quality control for raw materials and'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    59 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazardous-Materials Inventory Control'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(iii)(D)[02]'),
        'c6quote' => $Zfpf->encrypt_1c('control of hazardous chemical inventory levels; and,'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    60 => array(
        'c5name' => $Zfpf->encrypt_1c('Special or Unique Hazards'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(iii)(E)'),
        'c6quote' => $Zfpf->encrypt_1c('Any special or unique hazards.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    61 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety Systems'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(1)(iv)'),
        'c6quote' => $Zfpf->encrypt_1c('Safety systems and their functions.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    62 => array(
        'c5name' => $Zfpf->encrypt_1c('Access to Procedures'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('Operating procedures shall be readily accessible to employees who work in or maintain a process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    63 => array(
        'c5name' => $Zfpf->encrypt_1c('Always Up-to-date'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(3)[01]'),
        'c6quote' => $Zfpf->encrypt_1c('The operating procedures shall be reviewed as often as necessary to assure that they reflect current operating practice, including changes that result from changes in process chemicals, technology, and equipment, and changes to facilities.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    64 => array(
        'c5name' => $Zfpf->encrypt_1c('Annual Certification'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(3)[02]'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall certify annually that these operating procedures are current and accurate.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    65 => array(
        'c5name' => $Zfpf->encrypt_1c('Safe Work Practices and Access Control'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(f)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall develop and implement safe work practices to provide for the control of hazards during operations such as lockout/tagout; confined space entry; opening process equipment or piping; and control over entrance into a facility by maintenance, contractor, laboratory, or other support personnel. These safe work practices shall apply to employees and contractor employees.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    66 => array(
        'c5name' => $Zfpf->encrypt_1c('Initial Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(g)(1)(i)'),
        'c6quote' => $Zfpf->encrypt_1c('Training -- (1) Initial training. (i) Each employee presently involved in operating a process, and each employee before being involved in operating a newly assigned process, shall be trained in an overview of the process and in the operating procedures as specified in paragraph (f) of this section. The training shall include emphasis on the specific safety and health hazards, emergency operations including shutdown, and safe work practices applicable to the employee\'s job tasks.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    67 => array(
        'c5name' => $Zfpf->encrypt_1c('Started Before May 26, 1992 Exemption'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(g)(1)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('In lieu of initial training for those employees already involved in operating a process on May 26, 1992, an employer may certify in writing that the employee has the required knowledge, skills, and abilities to safely carry out the duties and responsibilities as specified in the operating procedures.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    68 => array(
        'c5name' => $Zfpf->encrypt_1c('Refresher Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(g)(2)[01]'),
        'c6quote' => $Zfpf->encrypt_1c('Refresher training shall be provided at least every three years, and more often if necessary, to each employee involved in operating a process to assure that the employee understands and adheres to the current operating procedures of the process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    69 => array(
        'c5name' => $Zfpf->encrypt_1c('Refresher Training Consultation'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(g)(2)[02]'),
        'c6quote' => $Zfpf->encrypt_1c('The employer, in consultation with the employees involved in operating the process, shall determine the appropriate frequency of refresher training.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    70 => array(
        'c5name' => $Zfpf->encrypt_1c('Comprehension Verified and Documented'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(g)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('Training documentation. The employer shall ascertain that each employee involved in operating a process has received and understood the training required by this paragraph. The employer shall prepare a record which contains the identity of the employee, the date of training, and the means used to verify that the employee understood the training.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    71 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability, Contractors'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Contractors -- (1) Application. This paragraph applies to contractors performing maintenance or repair, turnaround, major renovation, or specialty work on or adjacent to a covered process. It does not apply to contractors providing incidental services which do not influence process safety, such as janitorial work, food and drink services, laundry, delivery or other supply services.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    72 => array(
        'c5name' => $Zfpf->encrypt_1c('Owner/Operator Responsibilities'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('Employer responsibilities.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    73 => array(
        'c5name' => $Zfpf->encrypt_1c('Qualify'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(2)(i)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer, when selecting a contractor, shall obtain and evaluate information regarding the contract employer\'s safety performance and programs.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    74 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazards Notification'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(2)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall inform contract employers of the known potential fire, explosion, or toxic release hazards related to the contractor\'s work and the process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    75 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency-plans Briefing'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(2)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall explain to contract employers the applicable provisions of the emergency action plan required by paragraph (n) of this section.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    76 => array(
        'c5name' => $Zfpf->encrypt_1c('Safe Work Practices and Access Control'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(2)(iv)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall develop and implement safe work practices consistent with paragraph (f)(4) of this section, to control the entrance, presence and exit of contract employers and contract employees in covered process areas.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    77 => array(
        'c5name' => $Zfpf->encrypt_1c('Evaluate contractor performance'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(2)(v)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall periodically evaluate the performance of contract employers in fulfilling their obligations as specified in paragraph (h)(3) of this section.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    78 => array(
        'c5name' => $Zfpf->encrypt_1c('Injury and Illness Log'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(2)(vi)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall maintain a contract employee injury and illness log related to the contractor\'s work in process areas.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    79 => array(
        'c5name' => $Zfpf->encrypt_1c('Contractor Responsibilities'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('Contract employer responsibilities.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    80 => array(
        'c5name' => $Zfpf->encrypt_1c('Work-Practice Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(3)(i)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract employer shall assure that each contract employee is trained in the work practices necessary to safely perform his/her job.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    81 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazards and Emergencies Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(3)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract employer shall assure that each contract employee is instructed in the known potential fire, explosion, or toxic release hazards related to his/her job and the process, and the applicable provisions of the emergency action plan.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    82 => array(
        'c5name' => $Zfpf->encrypt_1c('Comprehension Verified and Documented'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(3)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract employer shall document that each contract employee has received and understood the training required by this paragraph. The contract employer shall prepare a record which contains the identity of the contract employee, the date of training, and the means used to verify that the employee understood the training.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    83 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety Enforcement'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(3)(iv)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract employer shall assure that each contract employee follows the safety rules of the facility including the safe work practices required by paragraph (f)(4) of this section.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    84 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazards Created or Discovered by the Work'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(h)(3)(v)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract employer shall advise the employer of any unique hazards presented by the contract employer\'s work, or of any hazards found by the contract employer\'s work.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    85 => array(
        'c5name' => $Zfpf->encrypt_1c('PSR Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(i)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Pre-startup safety review [PSR]. (1) The employer shall perform a pre-startup safety review for new facilities and for modified facilities when the modification is significant enough to require a change in the process safety information.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    86 => array(
        'c5name' => $Zfpf->encrypt_1c('PSR Requirements'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(i)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('The pre-startup safety review shall confirm that prior to the introduction of highly hazardous chemicals to a process: (i) Construction and equipment is in accordance with design specifications; (ii) Safety, operating, maintenance, and emergency procedures are in place and are adequate; (iii) For new facilities ["Facility" means the buildings, containers or equipment which contain a process. 29 CFR 1910.119(b)], a process hazard analysis has been performed and recommendations have been resolved or implemented before startup; and modified facilities meet the requirements contained in management of change, paragraph (l)[; and] (iv) Training of each employee involved in operating a process has been completed.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    87 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability, Mechanical Integrity'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Mechanical integrity -- (1) Application. Paragraphs (j)(2) through (j)(6) of this section apply to the following process equipment: (i) Pressure vessels and storage tanks; (ii) Piping systems (including piping components such as valves); (iii) Relief and vent systems and devices; (iv) Emergency shutdown systems; (v) Controls (including monitoring devices and sensors, alarms, and interlocks) and, (vi) Pumps.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    88 => array(
        'c5name' => $Zfpf->encrypt_1c('Maintenance Procedures'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('Written procedures. The employer shall establish and implement written procedures to maintain the on-going integrity of process equipment.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    89 => array(
        'c5name' => $Zfpf->encrypt_1c('Maintenance Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('Training for process maintenance activities. The employer shall train each employee involved in maintaining the on-going integrity of process equipment in an overview of that process and its hazards and in the procedures applicable to the employee\'s job tasks to assure that the employee can perform the job tasks in a safe manner.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    90 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection and Testing.'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('Inspection and testing. (i) Inspections and tests shall be performed on process equipment. (ii) Inspection and testing procedures shall follow recognized and generally accepted good engineering practices. (iii) The frequency of inspections and tests of process equipment shall be consistent with applicable manufacturers\' recommendations and good engineering practices, and more frequently if determined to be necessary by prior operating experience. (iv) The employer shall document each inspection and test that has been performed on process equipment. The documentation shall identify the date of the inspection or test, the name of the person who performed the inspection or test, the serial number or other identifier of the equipment on which the inspection or test was performed, a description of the inspection or test performed, and the results of the inspection or test.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ), /* Combined into above fragment.
    90 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection and Testing'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('Inspection and testing.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    91 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection and Testing Required'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(4)(i)'),
        'c6quote' => $Zfpf->encrypt_1c('Inspections and tests shall be performed on process equipment.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    92 => array(
        'c5name' => $Zfpf->encrypt_1c('Good Practices'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(4)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('Inspection and testing procedures shall follow recognized and generally accepted good engineering practices.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    93 => array(
        'c5name' => $Zfpf->encrypt_1c('Frequency'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(4)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('The frequency of inspections and tests of process equipment shall be consistent with applicable manufacturers\' recommendations and good engineering practices, and more frequently if determined to be necessary by prior operating experience.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    94 => array(
        'c5name' => $Zfpf->encrypt_1c('Records'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(4)(iv)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall document each inspection and test that has been performed on process equipment. The documentation shall identify the date of the inspection or test, the name of the person who performed the inspection or test, the serial number or other identifier of the equipment on which the inspection or test was performed, a description of the inspection or test performed, and the results of the inspection or test.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),*/
    95 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(5)'),
        'c6quote' => $Zfpf->encrypt_1c('Equipment deficiencies. The employer shall correct deficiencies in equipment that are outside acceptable limits (defined by the process safety information in paragraph (d) of this section) before further use or in a safe and timely manner when necessary means are taken to assure safe operation.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    96 => array(
        'c5name' => $Zfpf->encrypt_1c('Quality Assurance'), // This was split off from next two practices to make the Cheesehead Division fragments look cleaner.
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(6)'),
        'c6quote' => $Zfpf->encrypt_1c('Quality assurance.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    97 => array(
        'c5name' => $Zfpf->encrypt_1c('Design and Installation Good Practices'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(6)(i) and (ii) [Mechanical Integrity]'),
        'c6quote' => $Zfpf->encrypt_1c('(i) In the construction of new plants and equipment, the employer shall assure that equipment as it is fabricated is suitable for the process application for which they will be used. (ii) Appropriate checks and inspections shall be performed to assure that equipment is installed properly and consistent with design specifications and the manufacturer\'s instructions.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    98 => array(
        'c5name' => $Zfpf->encrypt_1c('Replacement-in-kind Quality Assurance'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(j)(6)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall assure that maintenance materials, spare parts and equipment are suitable for the process application for which they will be used.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    99 => array(
        'c5name' => $Zfpf->encrypt_1c('Hot Work Permit'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(k)'),
        'c6quote' => $Zfpf->encrypt_1c('Hot work permit. (1) The employer shall issue a hot work permit for hot work operations conducted on or near a covered process. (2) The permit shall document that the fire prevention and protection requirements in 29 CFR 1910.252(a) have been implemented prior to beginning the hot work operations; it shall indicate the date(s) authorized for hot work; and identify the object on which hot work is to be performed. The permit shall be kept on file until completion of the hot work operations.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    100 => array(
        'c5name' => $Zfpf->encrypt_1c('MOC Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(l)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Management of change [MOC]. (1) The employer shall establish and implement written procedures to manage changes (except for "replacements in kind") to process chemicals, technology, equipment, and procedures; and, changes to facilities that affect a covered process. [Relevant definitions from 29 CFR 1910.119(b): "Replacement in kind" means a replacement which satisfies the design specification. "Facility" means the buildings, containers or equipment which contain a process. "Process" means any activity involving a highly hazardous chemical including any use, storage, manufacturing, handling, or the on-site movement of such chemicals, or combination of these activities. For purposes of this definition, any group of vessels which are interconnected and separate vessels which are located such that a highly hazardous chemical could be involved in a potential release shall be considered a single process.]'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    101 => array(
        'c5name' => $Zfpf->encrypt_1c('MOC Procedural Requirements'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(l)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('The procedures shall assure that the following considerations are addressed prior to any change: (i) The technical basis for the proposed change; (ii) Impact of change on safety and health; (iii) Modifications to operating procedures; (iv) Necessary time period for the change; and, (v) Authorization requirements for the proposed change.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    102 => array(
        'c5name' => $Zfpf->encrypt_1c('Pre-Startup Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(l)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('Employees involved in operating a process and maintenance and contract employees whose job tasks will be affected by a change in the process shall be informed of, and trained in, the change prior to start-up of the process or affected part of the process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    103 => array(
        'c5name' => $Zfpf->encrypt_1c('PSI Update'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(l)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('If a change covered by this paragraph results in a change in the process safety information required by paragraph (d) of this section, such information shall be updated accordingly.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    104 => array(
        'c5name' => $Zfpf->encrypt_1c('Procedures Update'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(l)(5)'),
        'c6quote' => $Zfpf->encrypt_1c('If a change covered by this paragraph results in a change in the operating procedures or practices required by paragraph (f) of this section, such procedures or practices shall be updated accordingly.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    105 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability, Incident Investigations'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(m)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Incident investigation. (1) The employer shall investigate each incident which resulted in, or could reasonably have resulted in a catastrophic release of highly hazardous chemical in the workplace.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    106 => array(
        'c5name' => $Zfpf->encrypt_1c('48-Hour Start Time'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(m)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('An incident investigation shall be initiated as promptly as possible, but not later than 48 hours following the incident.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    107 => array(
        'c5name' => $Zfpf->encrypt_1c('Team Qualifications'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(m)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('An incident investigation team shall be established and consist of at least one person knowledgeable in the process involved, including a contract employee if the incident involved work of the contractor, and other persons with appropriate knowledge and experience to thoroughly investigate and analyze the incident.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    108 => array(
        'c5name' => $Zfpf->encrypt_1c('Report Content'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(m)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('A report shall be prepared at the conclusion of the investigation which includes at a minimum: (i) Date of incident; (ii) Date investigation began; (iii) A description of the incident; (iv) The factors that contributed to the incident; and, (v) Any recommendations resulting from the investigation.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    109 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(m)(5)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall establish a system to promptly address and resolve the incident report findings and recommendations. Resolutions and corrective actions shall be documented.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    110 => array(
        'c5name' => $Zfpf->encrypt_1c('Employee and Contractor Briefing'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(m)(6)'),
        'c6quote' => $Zfpf->encrypt_1c('The report shall be reviewed with all affected personnel whose job tasks are relevant to the incident findings including contract employees where applicable.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    111 => array(
        'c5name' => $Zfpf->encrypt_1c('Retention'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(m)(7)'),
        'c6quote' => $Zfpf->encrypt_1c('Incident investigation reports shall be retained for five years.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    112 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Action Plan (Alert, Move-to-Safety, Headcount...)'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(n)[01]'),
        'c6quote' => $Zfpf->encrypt_1c('Emergency planning and response. The employer shall establish and implement an emergency action plan for the entire plant in accordance with the provisions of 29 CFR 1910.38.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    113 => array(
        'c5name' => $Zfpf->encrypt_1c('Small Leaks'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(n)[02]'),
        'c6quote' => $Zfpf->encrypt_1c('In addition, the emergency action plan shall include procedures for handling small releases.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    114 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Response Option'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(n)[03]'),
        'c6quote' => $Zfpf->encrypt_1c('Employers covered under this standard may also be subject to the hazardous waste and emergency response provisions contained in 29 CFR 1910.120 (a), (p) and (q). [Relevant definition from 29 CFR 1910.120(a)(3): Emergency response or responding to emergencies means a response effort by employees from outside the immediate release area or by other designated responders (i.e., mutual aid groups, local fire departments, etc.) to an occurrence which results, or is likely to result, in an uncontrolled release of a hazardous substance. Responses to incidental releases of hazardous substances where the substance can be absorbed, neutralized, or otherwise controlled at the time of release by employees in the immediate release area, or by maintenance personnel are not considered to be emergency responses within the scope of this standard. Responses to releases of hazardous substances where there is no potential safety or health hazard (i.e., fire, explosion, or chemical exposure) are not considered to be emergency responses.]'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    115 => array(
        'c5name' => $Zfpf->encrypt_1c('Certification of "have evaluated... to verify"'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(o)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Compliance Audits. (1) Employers shall certify that they have evaluated compliance with the provisions of this section at least every three years to verify that the procedures and practices developed under the standard are adequate and are being followed.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    116 => array(
        'c5name' => $Zfpf->encrypt_1c('Auditor Qualifications'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(o)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('The compliance audit shall be conducted by at least one person knowledgeable in the process.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    117 => array(
        'c5name' => $Zfpf->encrypt_1c('Report'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(o)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('A report of the findings of the audit shall be developed.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    118 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(o)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('The employer shall promptly determine and document an appropriate response to each of the findings of the
compliance audit, and document that deficiencies have been corrected.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    119 => array(
        'c5name' => $Zfpf->encrypt_1c('Retention'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(o)(5)'),
        'c6quote' => $Zfpf->encrypt_1c('Employers shall retain the two (2) most recent compliance audit reports.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    120 => array(
        'c5name' => $Zfpf->encrypt_1c('Required Disclosures'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(p)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Trade secrets. (1) Employers shall make all information necessary to comply with the section available to those persons responsible for compiling the process safety information (required by paragraph (d) of this section), those assisting in the development of the process hazard analysis (required by paragraph (e) of this section), those responsible for developing the operating procedures (required by paragraph (f) of this section), and those involved in incident investigations (required by paragraph (m) of this section), emergency planning and response (paragraph (n) of this section) and compliance audits (paragraph (o) of this section) without regard to possible trade secret status of such information.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    121 => array(
        'c5name' => $Zfpf->encrypt_1c('Confidentiality Agreements'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(p)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('Nothing in this paragraph shall preclude the employer from requiring the persons to whom the information is made available under paragraph (p)(1) of this section to enter into confidentiality agreements not to disclose the information as set forth in 29 CFR 1910.1200.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    122 => array(
        'c5name' => $Zfpf->encrypt_1c('Employee Access'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119(p)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('Subject to the rules and procedures set forth in 29 CFR 1910.1200(i)(1) through 1910.1200(i)(12), employees and their designated representatives shall have access to trade secret information contained within the process hazard analysis and other documents required to be developed by this standard.'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    123 => array(
        'c5name' => $Zfpf->encrypt_1c('List of Hazardous Highly Chemicals, Toxics and Reactives (Mandatory)'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119, Appendix A'),
        'c6quote' => $Zfpf->encrypt_1c('[29 CFR 1910.119, Appendix A, lists the toxic and reactive (but not flammable) regulated materials and their threshold quantities. This list is not reproduced here. The latest version may be downloaded from www.gpo.gov -- the U.S. Government Printing Office.]'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    ),
    124 => array(
        'c5name' => $Zfpf->encrypt_1c('Appendices B to D: non-mandatory guidance'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('29 CFR 1910.119, Appendix B to Appendix D'),
        'c6quote' => $Zfpf->encrypt_1c('[29 CFR 1910.119, Appendices B to D, provide non-mandatory guidance, some of which has become dated. They are not reproduced here. They may be downloaded from www.gpo.gov -- the U.S. Government Printing Office.]'),
        'c5source' => $Zfpf->encrypt_1c('29 CFR Chapter XVII (2015-07-01)')
    )
);
foreach ($psm_fragments as $K => $V) {
    $psm_fragments[$K]['k0fragment'] = $K; // SPECIAL CASE. PSM fragments may use 1 to 999 here. Keys less than 100000 are reserved for templates.
    $psm_fragments[$K]['c5who_is_editing'] = $EncryptedNobody;
}

