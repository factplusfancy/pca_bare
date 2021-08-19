<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// Populate t0fragment with EPA's CAP rule
// $psm_fragments contains the entire EPA CAP rule (without appendices).
$cap_fragments = array(
    0 => array(
        'c5name' => $Zfpf->encrypt_1c('Rule Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68'),
        'c6quote' => $Zfpf->encrypt_1c('Chemical Accident Prevention Provisions'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('Subpart Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Subpart A'),
        'c6quote' => $Zfpf->encrypt_1c('Subpart A -- General'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('Scope'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.1'),
        'c6quote' => $Zfpf->encrypt_1c('This part sets forth the list of regulated substances and thresholds, the petition process for adding or deleting substances to the list of regulated substances, the requirements for owners or operators of stationary sources concerning the prevention of accidental releases, and the State accidental release prevention programs approved under section 112(r). The list of substances, threshold quantities, and accident prevention regulations promulgated under this part do not limit in any way the general duty provisions under [Clean Air Act Amendments of 1990] section 112(r)(1).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('Definitions'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Definitions. For the purposes of this part:'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    4 => array(
        'c5name' => $Zfpf->encrypt_1c('Accidental release'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Accidental release means an unanticipated emission of a regulated substance or other extremely hazardous substance into the ambient air from a stationary source.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    5 => array(
        'c5name' => $Zfpf->encrypt_1c('Act'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Act means the Clean Air Act as amended (42 U.S.C. 7401 et seq.)'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    6 => array(
        'c5name' => $Zfpf->encrypt_1c('Administrative controls'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Administrative controls mean written procedural mechanisms used for hazard control.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    7 => array(
        'c5name' => $Zfpf->encrypt_1c('Administrator'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Administrator means the administrator of the U.S. Environmental Protection Agency.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    8 => array(
        'c5name' => $Zfpf->encrypt_1c('AIChE/CCPS'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('AIChE/CCPS means the American Institute of Chemical Engineers/Center for Chemical Process Safety.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    9 => array(
        'c5name' => $Zfpf->encrypt_1c('API'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('API means the American Petroleum Institute.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    10 => array(
        'c5name' => $Zfpf->encrypt_1c('Article'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Article means a manufactured item, as defined under 29 CFR 1910.1200(b), that is formed to a specific shape or design during manufacture, that has end use functions dependent in whole or in part upon the shape or design during end use, and that does not release or otherwise result in exposure to a regulated substance under normal conditions of processing and use.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    11 => array(
        'c5name' => $Zfpf->encrypt_1c('ASME'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('ASME means the American Society of Mechanical Engineers.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    12 => array(
        'c5name' => $Zfpf->encrypt_1c('CAS'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('CAS means the Chemical Abstracts Service.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    13 => array(
        'c5name' => $Zfpf->encrypt_1c('Catastrophic release'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Catastrophic release means a major uncontrolled emission, fire, or explosion, involving one or more regulated substances that presents imminent and substantial endangerment to public health and the environment.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    197 => array(
        'c5name' => $Zfpf->encrypt_1c('CBI'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('CBI means confidential business information.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    14 => array(
        'c5name' => $Zfpf->encrypt_1c('Classified information'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Classified information means "classified information" as defined in the Classified Information Procedures Act, 18 U.S.C. App. 3, section 1(a) as "any information or material that has been determined by the United States Government pursuant to an executive order, statute, or regulation, to require protection against unauthorized disclosure for reasons of national security."'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    15 => array(
        'c5name' => $Zfpf->encrypt_1c('Condensate'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Condensate means hydrocarbon liquid separated from natural gas that condenses due to changes in temperature, pressure, or both, and remains liquid at standard conditions.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    16 => array(
        'c5name' => $Zfpf->encrypt_1c('Covered process'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Covered process means a process that has a regulated substance present in more than a threshold quantity as determined under [40 CFR] 68.115.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    17 => array(
        'c5name' => $Zfpf->encrypt_1c('Crude oil'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Crude oil means any naturally occurring, unrefined petroleum liquid.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    18 => array(
        'c5name' => $Zfpf->encrypt_1c('Designated agency'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Designated agency means the state, local, or Federal agency designated by the state under the provisions of [40 CFR] 68.215(d).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    19 => array(
        'c5name' => $Zfpf->encrypt_1c('DOT'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('DOT means the United States Department of Transportation.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    20 => array(
        'c5name' => $Zfpf->encrypt_1c('Environmental receptor'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Environmental receptor means natural areas such as national or state parks, forests, or monuments; officially designated wildlife sanctuaries, preserves, refuges, or areas; and Federal wilderness areas, that could be exposed at any time to toxic concentrations, radiant heat, or overpressure greater than or equal to the endpoints provided in [40 CFR] 68.22(a) , as a result of an accidental release and that can be identified on local U. S. Geological Survey maps.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    21 => array(
        'c5name' => $Zfpf->encrypt_1c('Field gas'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Field gas means gas extracted from a production well before the gas enters a natural gas processing plant.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    22 => array(
        'c5name' => $Zfpf->encrypt_1c('Hot work'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Hot work means work involving electric or gas welding, cutting, brazing, or similar flame or spark-producing operations.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    23 => array(
        'c5name' => $Zfpf->encrypt_1c('Implementing agency'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Implementing agency means the state or local agency that obtains delegation for an accidental release prevention program under subpart E, 40 CFR part 63. The implementing agency may, but is not required to, be the state or local air permitting agency. If no state or local agency is granted delegation, EPA will be the implementing agency for that state.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    24 => array(
        'c5name' => $Zfpf->encrypt_1c('Injury'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Injury means any effect on a human that results either from direct exposure to toxic concentrations; radiant heat; or overpressures from accidental releases or from the direct consequences of a vapor cloud explosion (such as flying glass, debris, and other projectiles) from an accidental release and that requires medical treatment or hospitalization.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    198 => array(
        'c5name' => $Zfpf->encrypt_1c('LEPC'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('LEPC means local emergency planning committee as established under 42 U.S.C. 11001(c).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    25 => array(
        'c5name' => $Zfpf->encrypt_1c('Major change'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Major change means introduction of a new process, process equipment, or regulated substance, an alteration of process chemistry that results in any change to safe operating limits, or other alteration that introduces a new hazard.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    26 => array(
        'c5name' => $Zfpf->encrypt_1c('Mechanical integrity'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Mechanical integrity means the process of ensuring that process equipment is fabricated from the proper materials of construction and is properly installed, maintained, and replaced to prevent failures and accidental releases.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    27 => array(
        'c5name' => $Zfpf->encrypt_1c('Medical treatment'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Medical treatment means treatment, other than first aid, administered by a physician or registered professional personnel under standing orders from a physician.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    28 => array(
        'c5name' => $Zfpf->encrypt_1c('Mitigation or mitigation system'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Mitigation or mitigation system means specific activities, technologies, or equipment designed or deployed to capture or control substances upon loss of containment to minimize exposure of the public or the environment. Passive mitigation means equipment, devices, or technologies that function without human, mechanical, or other energy input. Active mitigation means equipment, devices, or technologies that need human, mechanical, or other energy input to function.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    29 => array(
        'c5name' => $Zfpf->encrypt_1c('NAICS'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('NAICS means North American Industry Classification System.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    30 => array(
        'c5name' => $Zfpf->encrypt_1c('NFPA'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('NFPA means the National Fire Protection Association.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    31 => array(
        'c5name' => $Zfpf->encrypt_1c('Natural gas processing plant (gas plant)'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Natural gas processing plant (gas plant) means any processing site engaged in the extraction of natural gas liquids from field gas, fractionation of mixed natural gas liquids to natural gas products, or both, classified as North American Industrial Classification System (NAICS) code 211112 (previously Standard Industrial Classification (SIC) code 1321).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    32 => array(
        'c5name' => $Zfpf->encrypt_1c('Offsite'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Offsite means areas beyond the property boundary of the stationary source, and areas within the property boundary to which the public has routine and unrestricted access during or outside business hours'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    33 => array(
        'c5name' => $Zfpf->encrypt_1c('OSHA'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('OSHA means the U.S. Occupational Safety and Health Administration.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    34 => array(
        'c5name' => $Zfpf->encrypt_1c('Owner or operator'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Owner or operator means any person who owns, leases, operates, controls, or supervises a stationary source.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    35 => array(
        'c5name' => $Zfpf->encrypt_1c('Petroleum refining process unit'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Petroleum refining process unit means a process unit used in an establishment primarily engaged in petroleum refining as defined in NAICS code 32411 for petroleum refining (formerly SIC code 2911) and used for the following: Producing transportation fuels (such as gasoline, diesel fuels, and jet fuels), heating fuels (such as kerosene, fuel gas distillate, and fuel oils), or lubricants; Separating petroleum; or Separating, cracking, reacting, or reforming intermediate petroleum streams. Examples of such units include, but are not limited to, petroleum based solvent units, alkylation units, catalytic hydrotreating, catalytic hydrorefining, catalytic hydrocracking, catalytic reforming, catalytic cracking, crude distillation, lube oil processing, hydrogen production, isomerization, polymerization, thermal processes, and blending, sweetening, and treating processes. Petroleum refining process units include sulfur plants.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    36 => array(
        'c5name' => $Zfpf->encrypt_1c('Population'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Population means the public.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    37 => array(
        'c5name' => $Zfpf->encrypt_1c('Process'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Process means any activity involving a regulated substance including any use, storage, manufacturing, handling, or on-site movement of such substances, or combination of these activities. For the purposes of this definition, any group of vessels that are interconnected, or separate vessels that are located such that a regulated substance could be involved in a potential release, shall be considered a single process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    38 => array(
        'c5name' => $Zfpf->encrypt_1c('Produced water'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Produced water means water extracted from the earth from an oil or natural gas production well, or that is separated from oil or natural gas after extraction.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    39 => array(
        'c5name' => $Zfpf->encrypt_1c('Public'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Public means any person except employees or contractors at the stationary source.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    40 => array(
        'c5name' => $Zfpf->encrypt_1c('Public receptor'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Public receptor means offsite residences, institutions (e.g., schools, hospitals), industrial, commercial, and office buildings, parks, or recreational areas inhabited or occupied by the public at any time without restriction by the stationary source where members of the public could be exposed to toxic concentrations, radiant heat, or overpressure, as a result of an accidental release.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    41 => array(
        'c5name' => $Zfpf->encrypt_1c('Regulated substance'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Regulated substance is any substance listed pursuant to section 112(r)(3) of the Clean Air Act as amended, in [40 CFR] 68.130.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    42 => array(
        'c5name' => $Zfpf->encrypt_1c('Replacement in kind'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Replacement in kind means a replacement that satisfies the design specifications.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    43 => array(
        'c5name' => $Zfpf->encrypt_1c('Retail facility'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Retail facility means a stationary source at which more than one-half of the income is obtained from direct sales to end users or at which more than one-half of the fuel sold, by volume, is sold through a cylinder exchange program.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    44 => array(
        'c5name' => $Zfpf->encrypt_1c('RMP'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('RMP means the risk management plan required under subpart G of this part [40 CFR 68].'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    45 => array(
        'c5name' => $Zfpf->encrypt_1c('Stationary source'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Stationary source means any buildings, structures, equipment, installations, or substance emitting stationary activities which belong to the same industrial group, which are located on one or more contiguous properties, which are under the control of the same person (or persons under common control), and from which an accidental release may occur. The term stationary source does not apply to transportation, including storage incident to transportation, of any regulated substance or any other extremely hazardous substance under the provisions of this part. A stationary source includes transportation containers used for storage not incident to transportation and transportation containers connected to equipment at a stationary source for loading or unloading. Transportation includes, but is not limited to, transportation subject to oversight or regulation under 49 CFR parts 192, 193, or 195, or a state natural gas or hazardous liquid program for which the state has in effect a certification to DOT under 49 U.S.C. section 60105. A stationary source does not include naturally occurring hydrocarbon reservoirs. Properties shall not be considered contiguous solely because of a railroad or pipeline right-of-way.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    46 => array(
        'c5name' => $Zfpf->encrypt_1c('Threshold quantity'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Threshold quantity means the quantity specified for regulated substances pursuant to section 112(r)(5) of the Clean Air Act as amended, listed in [40 CFR] 68.130 and determined to be present at a stationary source as specified in [40 CFR] 68.115 of this part.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    47 => array(
        'c5name' => $Zfpf->encrypt_1c('Typical meteorological conditions'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Typical meteorological conditions means the temperature, wind speed, cloud cover, and atmospheric stability class, prevailing at the site based on data gathered at or near the site or from a local meteorological station.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    48 => array(
        'c5name' => $Zfpf->encrypt_1c('Vessel'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Vessel means any reactor, tank, drum, barrel, cylinder, vat, kettle, boiler, pipe, hose, or other container.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    49 => array(
        'c5name' => $Zfpf->encrypt_1c('Worst-case release'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.3'),
        'c6quote' => $Zfpf->encrypt_1c('Worst-case release means the release of the largest quantity of a regulated substance from a vessel or process line failure that results in the greatest distance to an endpoint defined in [40 CFR] 68.22(a).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    50 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.10'),
        'c6quote' => $Zfpf->encrypt_1c('Applicability. (a) Except as provided in paragraphs (b) through (f) of this section, an owner or operator of a stationary source that has more than a threshold quantity of a regulated substance in a process, as determined under [40 CFR] 68.115, shall comply with the requirements of this part no later than the latest of the following dates: (1) June 21, 1999; (2) Three years after the date on which a regulated substance is first listed under [40 CFR] 68.130; (3) The date on which a regulated substance is first present above a threshold quantity in a process; or (4) For any revisions to this part, the effective date of the final rule that revises this part. (b) By March 14, 2018, the owner or operator of a stationary source shall comply with the emergency response coordination activities in [40 CFR] 68.93, as applicable. (c) Within three years of when the owner or operator determines that the stationary source is subject to the emergency response program requirements of [40 CFR] 68.95, pursuant to [40 CFR] 68.90(a), the owner or operator must develop and implement an emergency response program in accordance with [40 CFR] 68.95. (d) By December 19, 2023, the owner or operator shall have developed plans for conducting emergency response exercises in accordance with provisions of [40 CFR] 68.96, as applicable. (e) The owner or operator of a stationary source shall comply with the public meeting requirement in [40 CFR] 68.210(b) within 90 days of any RMP reportable accident at the stationary source with known offsite impacts specified in [40 CFR] 68.42(a), that occurs after March 15, 2021. (f) After December 19, 2024, for any risk management plan initially submitted as required by [40 CFR] 68.150(b)(2) or (3) or submitted as an update required by [40 CFR] 68.190, the owner or operator shall comply with the following risk management plan provisions of subpart G of this part: (1) Reporting a public meeting after an RMP reportable accident under [40 CFR] 68.160(b)(21) as promulgated on December 19, 2019; (2) Reporting emergency response program information under [40 CFR] 68.180(a)(1) as promulgated on December 19, 2019; (3) Reporting emergency response program information under [40 CFR] 68.180(a)(2) and (3) as promulgated on January 13, 2017, as applicable; and, (4) Reporting emergency response program and exercises information under [40 CFR] 68.180(b) as promulgated on January 13, 2017, as applicable. The owner or operator shall submit dates of the most recent notification, field and tabletop exercises in the risk management plan, for exercises completed as required under [40 CFR] 68.96 at the time the risk management plan is either submitted under [40 CFR] 68.150(b)(2) or (3), or is updated under [40 CFR] 68.190. (g) Program 1 eligibility requirements. A covered process is eligible for Program 1 requirements as provided in [40 CFR] 68.12(b) if it meets all of the following requirements: (1) For the five years prior to the submission of an RMP, the process has not had an accidental release of a regulated substance where exposure to the substance, its reaction products, overpressure generated by an explosion involving the substance, or radiant heat generated by a fire involving the substance led to any of the following offsite: (i) Death; (ii) Injury; or (iii) Response or restoration activities for an exposure of an environmental receptor; (2) The distance to a toxic or flammable endpoint for a worst-case release assessment conducted under subpart B and [40 CFR] 68.25 is less than the distance to any public receptor, as defined in [40 CFR] 68.30; and (3) Emergency response procedures have been coordinated between the stationary source and local emergency planning and response organizations. (h) Program 2 eligibility requirements. A covered process is subject to Program 2 requirements if it does not meet the eligibility requirements of either paragraph (g) or paragraph (i) of this section. (i) Program 3 eligibility requirements. A covered process is subject to Program 3 if the process does not meet the requirements of paragraph (g) of this section, and if either of the following conditions is met: (1) The process is in NAICS code 32211, 32411, 32511, 325181, 325188, 325192, 325199, 325211, 325311, or 32532; or (2) The process is subject to the OSHA process safety management standard, 29 CFR 1910.119. (j) If at any time a covered process no longer meets the eligibility criteria of its Program level, the owner or operator shall comply with the requirements of the new Program level that applies to the process and update the RMP as provided in [40 CFR] 68.190. (k) The provisions of this part shall not apply to an Outer Continental Shelf ("OCS") source, as defined in 40 CFR 55.2.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    51 => array(
        'c5name' => $Zfpf->encrypt_1c('General requirements'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.12'),
        'c6quote' => $Zfpf->encrypt_1c('(a) General requirements. The owner or operator of a stationary source subject to this part shall submit a single RMP, as provided in [40 CFR] 68.150 to 68.185. The RMP shall include a registration that reflects all covered processes. (b) Program 1 requirements. In addition to meeting the requirements of paragraph (a) of this section, the owner or operator of a stationary source with a process eligible for Program 1, as provided in [40 CFR] 68.10(g), shall: (1) Analyze the worst-case release scenario for the process(es), as provided in [40 CFR] 68.25; document that the nearest public receptor is beyond the distance to a toxic or flammable endpoint defined in [40 CFR] 68.22(a); and submit in the RMP the worst-case release scenario as provided in [40 CFR] 68.165; (2) Complete the five-year accident history for the process as provided in [40 CFR] 68.42 of this part and submit it in the RMP as provided in [40 CFR] 68.168; (3) Ensure that response actions have been coordinated with local emergency planning and response agencies; and (4) Certify in the RMP the following: "Based on the criteria in 40 CFR 68.10, the distance to the specified endpoint for the worst-case accidental release scenario for the following process(es) is less than the distance to the nearest public receptor: [list process(es)]. Within the past five years, the process(es) has (have) had no accidental release that caused offsite impacts provided in the risk management program rule (40 CFR 68.10(g)(1)). No additional measures are necessary to prevent offsite impacts from accidental releases. In the event of fire, explosion, or a release of a regulated substance from the process(es), entry within the distance to the specified endpoints may pose a danger to public emergency responders. Therefore, public emergency responders should not enter this area except as arranged with the emergency contact indicated in the RMP. The undersigned certifies that, to the best of my knowledge, information, and belief, formed after reasonable inquiry, the information submitted is true, accurate, and complete. [Signature, title, date signed]." (c) Program 2 requirements. In addition to meeting the requirements of paragraph (a) of this section, the owner or operator of a stationary source with a process subject to Program 2, as provided in [40 CFR] 68.10(h), shall: (1) Develop and implement a management system as provided in [40 CFR] 68.15; (2) Conduct a hazard assessment as provided in [40 CFR] 68.20 through 68.42; (3) Implement the Program 2 prevention steps provided in [40 CFR] 68.48 through 68.60 or implement the Program 3 prevention steps provided in [40 CFR] 68.65 through 68.87; 
(4) Coordinate response actions with local emergency planning and response agencies as provided in [40 CFR] 68.93; (5) Develop and implement an emergency response program, and conduct exercises, as provided in [40 CFR] 68.90 to 68.96; and (6) Submit as part of the RMP the data on prevention program elements for Program 2 processes as provided in [40 CFR] 68.170. (d) Program 3 requirements. In addition to meeting the requirements of paragraph (a) of this section, the owner or operator of a stationary source with a process subject to Program 3, as provided in [40 CFR] 68.10(i) shall: (1) Develop and implement a management system as provided in [40 CFR] 68.15; (2) Conduct a hazard assessment as provided in [40 CFR] 68.20 through 68.42; (3) Implement the prevention requirements of [40 CFR] 68.65 through 68.87; (4) Coordinate response actions with local emergency planning and response agencies as provided in [40 CFR] 68.93; (5) Develop and implement an emergency response program, and conduct exercises, as provided in [40 CFR] 68.90 to 68.96; and (6) Submit as part of the RMP the data on prevention program elements for Program 3 processes as provided in [40 CFR] 68.175.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    52 => array(
        'c5name' => $Zfpf->encrypt_1c('Management System'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.15'),
        'c6quote' => $Zfpf->encrypt_1c('Management. (a) The owner or operator of a stationary source with processes subject to Program 2 or Program 3 shall develop a management system to oversee the implementation of the risk management program elements. (b) The owner or operator shall assign a qualified person or position that has the overall responsibility for the development, implementation, and integration of the risk management program elements. (c) When responsibility for implementing individual requirements of this part is assigned to persons other than the person identified under paragraph (b) of this section, the names or positions of these people shall be documented and the lines of authority defined through an organization chart or similar document. [61 FR 31718, June 20, 1996]'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    53 => array(
        'c5name' => $Zfpf->encrypt_1c('Subpart Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Subpart B'),
        'c6quote' => $Zfpf->encrypt_1c('Subpart B -- Hazard Assessment'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    54 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazard-Assessment Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.20'),
        'c6quote' => $Zfpf->encrypt_1c('Applicability. The owner or operator of a stationary source subject to this part [40 CFR 68] shall prepare a worst-case release scenario analysis as provided in [40 CFR] 68.25 of this part and complete the five-year accident history as provided in [40 CFR] 68.42. The owner or operator of a Program 2 and 3 process must comply with all sections in this subpart [Subpart B -- Hazard Assessment, 40 CFR 68.20 to 68.42] for these processes.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    55 => array(
        'c5name' => $Zfpf->encrypt_1c('Offsite consequence analysis parameters'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.22'),
        'c6quote' => $Zfpf->encrypt_1c('Offsite consequence analysis parameters. (a) Endpoints. For analyses of offsite consequences, the following endpoints shall be used: (1) Toxics. The toxic endpoints provided in appendix A of this part. (2) Flammables. The endpoints for flammables vary according to the scenarios studied: (i) Explosion. An overpressure of 1 psi. (ii) Radiant heat/exposure time. A radiant heat of 5 [kilowatts per square meter] for 40 seconds. (iii) Lower flammability limit. A lower flammability limit as provided in NFPA documents or other generally recognized sources. (b) Wind speed/atmospheric stability class. For the worst-case release analysis, the owner or operator shall use a wind speed of 1.5 meters per second and F atmospheric stability class. If the owner or operator can demonstrate that local meteorological data applicable to the stationary source show a higher minimum wind speed or less stable atmosphere at all times during the previous three years, these minimums may be used. For analysis of alternative scenarios, the owner or operator may use the typical meteorological conditions for the stationary source. (c) Ambient temperature/humidity. For worst-case release analysis of a regulated toxic substance, the owner or operator shall use the highest daily maximum temperature in the previous three years and average humidity for the site, based on temperature/humidity data gathered at the stationary source or at a local meteorological station; an owner or operator using the RMP Offsite Consequence Analysis Guidance may use 25 [degrees Celsius] and 50 percent humidity as values for these variables. For analysis of alternative scenarios, the owner or operator may use typical temperature/humidity data gathered at the stationary source or at a local meteorological station. (d) Height of release. The worst-case release of a regulated toxic substance shall be analyzed assuming a ground level (0 feet) release. For an alternative scenario analysis of a regulated toxic substance, release height may be determined by the release scenario. (e) Surface roughness. The owner or operator shall use either urban or rural topography, as appropriate. Urban means that there are many obstacles in the immediate area; obstacles include buildings or trees. Rural means there are no buildings in the immediate area and the terrain is generally flat and unobstructed. (f) Dense or neutrally buoyant gases. The owner or operator shall ensure that tables or models used for dispersion analysis of regulated toxic substances appropriately account for gas density. (g) Temperature of released substance. For worst case, liquids other than gases liquified by refrigeration only shall be considered to be released at the highest daily maximum temperature, based on data for the previous three years appropriate for the stationary source, or at process temperature, whichever is higher. For alternative scenarios, substances may be considered to be released at a process or ambient temperature that is appropriate for the scenario.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    56 => array(
        'c5name' => $Zfpf->encrypt_1c('Worst-case release scenario analysis'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.25'),
        'c6quote' => $Zfpf->encrypt_1c('Worst-case release scenario analysis. (a) The owner or operator shall analyze and report in the RMP: (1) For Program 1 processes, one worst-case release scenario for each Program 1 process; (2) For Program 2 and 3 processes: (i) One worst-case release scenario that is estimated to create the greatest distance in any direction to an endpoint provided in appendix A of this part resulting from an accidental release of regulated toxic substances from covered processes under worst-case conditions defined in [40 CFR] 68.22; (ii) One worst-case release scenario that is estimated to create the greatest distance in any direction to an endpoint defined in [40 CFR] 68.22(a) resulting from an accidental release of regulated flammable substances from covered processes under worst-case conditions defined in [40 CFR] 68.22; and (iii) Additional worst-case release scenarios for a hazard class if a worst-case release from another covered process at the stationary source potentially affects public receptors different from those potentially affected by the worst-case release scenario developed under paragraphs (a)(2)(i) or (a)(2)(ii) of this section. (b) Determination of worst-case release quantity. The worst-case release quantity shall be the greater of the following: (1) For substances in a vessel, the greatest amount held in a single vessel, taking into account administrative controls that limit the maximum quantity; or (2) For substances in pipes, the greatest amount in a pipe, taking into account administrative controls that limit the maximum quantity. (c) Worst-case release scenario -- toxic gases. (1) For regulated toxic substances that are normally gases at ambient temperature and handled as a gas or as a liquid under pressure, the owner or operator shall assume that the quantity in the vessel or pipe, as determined under paragraph (b) of this section, is released as a gas over 10 minutes. The release rate shall be assumed to be the total quantity divided by 10 unless passive mitigation systems are in place. (2) For gases handled as refrigerated liquids at ambient pressure: (i) If the released substance is not contained by passive mitigation systems or if the contained pool would have a depth of 1 cm or less, the owner or operator shall assume that the substance is released as a gas in 10 minutes; (ii) If the released substance is contained by passive mitigation systems in a pool with a depth greater than 1 cm, the owner or operator may assume that the quantity in the vessel or pipe, as determined under paragraph (b) of this section, is spilled instantaneously to form a liquid pool. The volatilization rate (release rate) shall be calculated at the boiling point of the substance and at the conditions specified in paragraph (d) of this section. (d) Worst-case release scenario -- toxic liquids. (1) For regulated toxic substances that are normally liquids at ambient temperature, the owner or operator shall assume that the quantity in the vessel or pipe, as determined under paragraph (b) of this section, is spilled instantaneously to form a liquid pool. (i) The surface area of the pool shall be determined by assuming that the liquid spreads to 1 centimeter deep unless passive mitigation systems are in place that serve to contain the spill and limit the surface area. Where passive mitigation is in place, the surface area of the contained liquid shall be used to calculate the volatilization rate. (ii) If the release would occur onto a surface that is not paved or smooth, the owner or operator may take into account the actual surface characteristics. (2) The volatilization rate shall account for the highest daily maximum temperature occurring in the past three years, the temperature of the substance in the vessel, and the concentration of the substance if the liquid spilled is a mixture or solution. (3) The rate of release to air shall be determined from the volatilization rate of the liquid pool. The owner or operator may use the methodology in the RMP Offsite Consequence Analysis Guidance or any other publicly available techniques that account for the modeling conditions and are recognized by industry as applicable as part of current practices. Proprietary models that account for the modeling conditions may be used provided the owner or operator allows the implementing agency access to the model and describes model features and differences from publicly available models to local emergency planners upon request. (e) Worst-case release scenario -- flammable gases. The owner or operator shall assume that the quantity of the substance, as determined under paragraph (b) of this section and the provisions below, vaporizes resulting in a vapor cloud explosion. A yield factor of 10 percent of the available energy released in the explosion shall be used to determine the distance to the explosion endpoint if the model used is based on TNT equivalent methods. (1) For regulated flammable substances that are normally gases at ambient temperature and handled as a gas or as a liquid under pressure, the owner or operator shall assume that the quantity in the vessel or pipe, as determined under paragraph (b) of this section, is released as a gas over 10 minutes. The total quantity shall be assumed to be involved in the vapor cloud explosion. (2) For flammable gases handled as refrigerated liquids at ambient pressure: (i) If the released substance is not contained by passive mitigation systems or if the contained pool would have a depth of one centimeter or less, the owner or operator shall assume that the total quantity of the substance is released as a gas in 10 minutes, and the total quantity will be involved in the vapor cloud explosion. (ii) If the released substance is contained by passive mitigation systems in a pool with a depth greater than 1 centimeter, the owner or operator may assume that the quantity in the vessel or pipe, as determined under paragraph (b) of this section, is spilled instantaneously to form a liquid pool. The volatilization rate (release rate) shall be calculated at the boiling point of the substance and at the conditions specified in paragraph (d) of this section. The owner or operator shall assume that the quantity which becomes vapor in the first 10 minutes is involved in the vapor cloud explosion. (f) Worst-case release scenario -- flammable liquids. The owner or operator shall assume that the quantity of the substance, as determined under paragraph (b) of this section and the provisions below, vaporizes resulting in a vapor cloud explosion. A yield factor of 10 percent of the available energy released in the explosion shall be used to determine the distance to the explosion endpoint if the model used is based on TNT equivalent methods. (1) For regulated flammable substances that are normally liquids at ambient temperature, the owner or operator shall assume that the entire quantity in the vessel or pipe, as determined under paragraph (b) of this section, is spilled instantaneously to form a liquid pool. For liquids at temperatures below their atmospheric boiling point, the volatilization rate shall be calculated at the conditions specified in paragraph (d) of this section. (2) The owner or operator shall assume that the quantity which becomes vapor in the first 10 minutes is involved in the vapor cloud explosion. (g) Parameters to be applied. The owner or operator shall use the parameters defined in [40 CFR] 68.22 to determine distance to the endpoints. The owner or operator may use the methodology provided in the RMP Offsite Consequence Analysis Guidance or any commercially or publicly available air dispersion modeling techniques, provided the techniques account for the modeling conditions and are recognized by industry as applicable as part of current practices. Proprietary models that account for the modeling conditions may be used provided the owner or operator allows the implementing agency access to the model and describes model features and differences from publicly available models to local emergency planners upon request. (h) Consideration of passive mitigation. Passive mitigation systems may be considered for the analysis of worst case provided that the mitigation system is capable of withstanding the release event triggering the scenario and would still function as intended. (i) Factors in selecting a worst-case scenario. Notwithstanding the provisions of paragraph (b) of this section, the owner or operator shall select as the worst case for flammable regulated substances or the worst case for regulated toxic substances, a scenario based on the following factors if such a scenario would result in a greater distance to an endpoint defined in [40 CFR] 68.22(a) beyond the stationary source boundary than the scenario provided under paragraph (b) of this section: (1) Smaller quantities handled at higher process temperature or pressure; and (2) Proximity to the boundary of the stationary source.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    57 => array(
        'c5name' => $Zfpf->encrypt_1c('Alternative release scenario analysis'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.28'),
        'c6quote' => $Zfpf->encrypt_1c('Alternative release scenario analysis. (a) The number of scenarios. The owner or operator shall identify and analyze at least one alternative release scenario for each regulated toxic substance held in a covered process(es) and at least one alternative release scenario to represent all flammable substances held in covered processes. (b) Scenarios to consider. (1) For each scenario required under paragraph (a) of this section, the owner or operator shall select a scenario: (i) That is more likely to occur than the worst-case release scenario under [40 CFR] 68.25; and (ii) That will reach an endpoint offsite, unless no such scenario exists. (2) Release scenarios considered should include, but are not limited to, the following, where applicable: (i) Transfer hose releases due to splits or sudden hose uncoupling; (ii) Process piping releases from failures at flanges, joints, welds, valves and valve seals, and drains or bleeds; (iii) Process vessel or pump releases due to cracks, seal failure, or drain, bleed, or plug failure; (iv) Vessel overfilling and spill, or overpressurization and venting through relief valves or rupture disks; and (v) Shipping container mishandling and breakage or puncturing leading to a spill. (c) Parameters to be applied. The owner or operator shall use the appropriate parameters defined in [40 CFR] 68.22 to determine distance to the endpoints. The owner or operator may use either the methodology provided in the RMP Offsite Consequence Analysis Guidance or any commercially or publicly available air dispersion modeling techniques, provided the techniques account for the specified modeling conditions and are recognized by industry as applicable as part of current practices. Proprietary models that account for the modeling conditions may be used provided the owner or operator allows the implementing agency access to the model and describes model features and differences from publicly available models to local emergency planners upon request. (d) Consideration of mitigation. Active and passive mitigation systems may be considered provided they are capable of withstanding the event that triggered the release and would still be functional. (e) Factors in selecting scenarios. The owner or operator shall consider the following in selecting alternative release scenarios: (1) The five-year accident history provided in [40 CFR] 68.42; and (2) Failure scenarios identified under [40 CFR] 68.50 or 68.67.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    58 => array(
        'c5name' => $Zfpf->encrypt_1c('Defining offsite impacts -- population'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.30'),
        'c6quote' => $Zfpf->encrypt_1c('Defining offsite impacts -- population (a) The owner or operator shall estimate in the RMP the population within a circle with its center at the point of the release and a radius determined by the distance to the endpoint defined in [40 CFR] 68.22(a). (b) Population to be defined. Population shall include residential population. The presence of institutions (schools, hospitals, prisons), parks and recreational areas, and major commercial, office, and industrial buildings shall be noted in the RMP. (c) Data sources acceptable. The owner or operator may use the most recent Census data, or other updated information, to estimate the population potentially affected. (d) Level of accuracy. Population shall be estimated to two significant digits.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    59 => array(
        'c5name' => $Zfpf->encrypt_1c('Defining offsite impacts -- environment'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 60.33'),
        'c6quote' => $Zfpf->encrypt_1c('Defining offsite impacts -- environment. (a) The owner or operator shall list in the RMP environmental receptors within a circle with its center at the point of the release and a radius determined by the distance to the endpoint defined in [40 CFR] 68.22(a) of this part. (b) Data sources acceptable. The owner or operator may rely on information provided on local U.S. Geological Survey maps or on any data source containing U.S.G.S. data to identify environmental receptors.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    60 => array(
        'c5name' => $Zfpf->encrypt_1c('Review and update'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.36'),
        'c6quote' => $Zfpf->encrypt_1c('(a) The owner or operator shall review and update the offsite consequence analyses at least once every five years. (b) If changes in processes, quantities stored or handled, or any other aspect of the stationary source might reasonably be expected to increase or decrease the distance to the endpoint by a factor of two or more, the owner or operator shall complete a revised analysis within six months of the change and submit a revised risk management plan as provided in [40 CFR] 68.190.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    61 => array(
        'c5name' => $Zfpf->encrypt_1c('Documentation'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.39'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall maintain the following records on the offsite consequence analyses: (a) For worst-case scenarios, a description of the vessel or pipeline and substance selected as worst case, assumptions and parameters used, and the rationale for selection; assumptions shall include use of any administrative controls and any passive mitigation that were assumed to limit the quantity that could be released. Documentation shall include the anticipated effect of the controls and mitigation on the release quantity and rate. (b) For alternative release scenarios, a description of the scenarios identified, assumptions and parameters used, and the rationale for the selection of specific scenarios; assumptions shall include use of any administrative controls and any mitigation that were assumed to limit the quantity that could be released. Documentation shall include the effect of the controls and mitigation on the release quantity and rate. (c) Documentation of estimated quantity released, release rate, and duration of release. (d) Methodology used to determine distance to endpoints. (e) Data used to estimate population and environmental receptors potentially affected.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    62 => array(
        'c5name' => $Zfpf->encrypt_1c('Five-year accident history'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.42'),
        'c6quote' => $Zfpf->encrypt_1c('Five-year accident history. (a) The owner or operator shall include in the five-year accident history all accidental releases from covered processes that resulted in deaths, injuries, or significant property damage on site, or known offsite deaths, injuries, evacuations, sheltering in place, property damage, or environmental damage. (b) Data required. For each accidental release included, the owner or operator shall report the following information: (1) Date, time, and approximate duration of the release; (2) Chemical(s) released; (3) Estimated quantity released in pounds and, for mixtures containing regulated toxic substances, percentage concentration by weight of the released regulated toxic substance in the liquid mixture; (4) Five- or six-digit NAICS code that most closely corresponds to the process; (5) The type of release event and its source; (6) Weather conditions, if known; (7) On-site impacts; (8) Known offsite impacts; (9) Initiating event and contributing factors if known; (10) Whether offsite responders were notified if known; and (11) Operational or process changes that resulted from investigation of the release and that have been made by the time this information is submitted in accordance with [40 CFR] 68.168. (c) Level of accuracy. Numerical estimates may be provided to two significant digits.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    63 => array(
        'c5name' => $Zfpf->encrypt_1c('Subpart Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Subpart C'),
        'c6quote' => $Zfpf->encrypt_1c('Subpart C -- Program 2 Prevention Program'), // Included here for possible future use, even though not included in the EPA CAP (Program 3 only) division method.
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    64 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety information'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.48'),
        'c6quote' => $Zfpf->encrypt_1c('Safety information. (a) The owner or operator shall compile and maintain the following up-to-date safety information related to the regulated substances, processes, and equipment: (1) Safety Data Sheets (SDS) that meet the requirements of 29 CFR 1910.1200(g); (2) Maximum intended inventory of equipment in which the regulated substances are stored or processed; (3) Safe upper and lower temperatures, pressures, flows, and compositions; (4) Equipment specifications; and (5) Codes and standards used to design, build, and operate the process. (b) The owner or operator shall ensure that the process is designed in compliance with recognized and generally accepted good engineering practices. Compliance with Federal or state regulations that address industry-specific safe design or with industry-specific design codes and standards may be used to demonstrate compliance with this paragraph. (c) The owner or operator shall update the safety information if a major change occurs that makes the information inaccurate.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13)')
    ),
    65 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazard review'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.50'),
        'c6quote' => $Zfpf->encrypt_1c('Hazard review. (a) The owner or operator shall conduct a review of the hazards associated with the regulated substances, process, and procedures. The review shall identify the following: (1) The hazards associated with the process and regulated substances; (2) Opportunities for equipment malfunctions or human errors that could cause an accidental release; (3) The safeguards used or needed to control the hazards or prevent equipment malfunction or human error; and (4) Any steps used or needed to detect or monitor releases. (b) The owner or operator may use checklists developed by persons or organizations knowledgeable about the process and equipment as a guide to conducting the review. For processes designed to meet industry standards or Federal or state design rules, the hazard review shall, by inspecting all equipment, determine whether the process is designed, fabricated, and operated in accordance with the applicable standards or rules. (c) The owner or operator shall document the results of the review and ensure that problems identified are resolved in a timely manner. (d) The review shall be updated at least once every five years. The owner or operator shall also conduct reviews whenever a major change in the process occurs; all issues identified in the review shall be resolved before startup of the changed process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    66 => array(
        'c5name' => $Zfpf->encrypt_1c('Operating procedures'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.52'),
        'c6quote' => $Zfpf->encrypt_1c('Operating procedures. (a) The owner or operator shall prepare written operating procedures that provide clear instructions or steps for safely conducting activities associated with each covered process consistent with the safety information for that process. Operating procedures or instructions provided by equipment manufacturers or developed by persons or organizations knowledgeable about the process and equipment may be used as a basis for a stationary source\'s operating procedures. (b) The procedures shall address the following: (1) Initial startup; (2) Normal operations; (3) Temporary operations; (4) Emergency shutdown and operations; (5) Normal shutdown; (6) Startup following a normal or emergency shutdown or a major change that requires a hazard review; (7) Consequences of deviations and steps required to correct or avoid deviations; and (8) Equipment inspections. (c) The owner or operator shall ensure that the operating procedures are updated, if necessary, whenever a major change occurs and prior to startup of the changed process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    67 => array(
        'c5name' => $Zfpf->encrypt_1c('Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.54'),
        'c6quote' => $Zfpf->encrypt_1c('Training. (a) The owner or operator shall ensure that each employee presently operating a process, and each employee newly assigned to a covered process have been trained or tested competent in the operating procedures provided in [40 CFR] 68.52 that pertain to their duties. For those employees already operating a process on June 21, 1999, the owner or operator may certify in writing that the employee has the required knowledge, skills, and abilities to safely carry out the duties and responsibilities as provided in the operating procedures. (b) Refresher training. Refresher training shall be provided at least every three years, and more often if necessary, to each employee operating a process to ensure that the employee understands and adheres to the current operating procedures of the process. The owner or operator, in consultation with the employees operating the process, shall determine the appropriate frequency of refresher training. (c) The owner or operator may use training conducted under Federal or state regulations or under industry-specific standards or codes or training conducted by covered process equipment vendors to demonstrate compliance with this section to the extent that the training meets the requirements of this section. (d) The owner or operator shall ensure that operators are trained in any updated or new procedures prior to startup of a process after a major change.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    68 => array(
        'c5name' => $Zfpf->encrypt_1c('Maintenance'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.56'),
        'c6quote' => $Zfpf->encrypt_1c('Maintenance. (a) The owner or operator shall prepare and implement procedures to maintain the on-going mechanical integrity of the process equipment. The owner or operator may use procedures or instructions provided by covered process equipment vendors or procedures in Federal or state regulations or industry codes as the basis for stationary source maintenance procedures. (b) The owner or operator shall train or cause to be trained each employee involved in maintaining the on-going mechanical integrity of the process. To ensure that the employee can perform the job tasks in a safe manner, each such employee shall be trained in the hazards of the process, in how to avoid or correct unsafe conditions, and in the procedures applicable to the employee\'s job tasks. (c) Any maintenance contractor shall ensure that each contract maintenance employee is trained to perform the maintenance procedures developed under paragraph (a) of this section. (d) The owner or operator shall perform or cause to be performed inspections and tests on process equipment. Inspection and testing procedures shall follow recognized and generally accepted good engineering practices. The frequency of inspections and tests of process equipment shall be consistent with applicable manufacturers\' recommendations, industry standards or codes, good engineering practices, and prior operating experience.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    69 => array(
        'c5name' => $Zfpf->encrypt_1c('Compliance audits'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.58'),
        'c6quote' => $Zfpf->encrypt_1c('Compliance audits. (a) The owner or operator shall certify that they have evaluated compliance with the provisions of this subpart [40 CFR 68.48 to 68.60] at least every three years to verify that the procedures and practices developed under the rule are adequate and are being followed. (b) The compliance audit shall be conducted by at least one person knowledgeable in the process. (c) The owner or operator shall develop a report of the audit findings. (d) The owner or operator shall promptly determine and document an appropriate response to each of the findings of the compliance audit and document that deficiencies have been corrected. (e) The owner or operator shall retain the two (2) most recent compliance audit reports. This requirement does not apply to any compliance audit report that is more than five years old.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    70 => array(
        'c5name' => $Zfpf->encrypt_1c('Incident investigation.'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.60'),
        'c6quote' => $Zfpf->encrypt_1c('Incident investigation. (a) The owner or operator shall investigate each incident which resulted in, or could reasonably have resulted in a catastrophic release. (b) An incident investigation shall be initiated as promptly as possible, but not later than 48 hours following the incident. (c) A summary shall be prepared at the conclusion of the investigation which includes at a minimum: (1) Date of incident; (2) Date investigation began; (3) A description of the incident; (4) The factors that contributed to the incident; and, (5) Any recommendations resulting from the investigation. (d) The owner or operator shall promptly address and resolve the investigation findings and recommendations. Resolutions and corrective actions shall be documented. (e) The findings shall be reviewed with all affected personnel whose job tasks are affected by the findings. (f) Investigation summaries shall be retained for five years.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    71 => array(
        'c5name' => $Zfpf->encrypt_1c('Subpart Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Subpart D'),
        'c6quote' => $Zfpf->encrypt_1c('Subpart D -- Program 3 Prevention Program'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    72 => array(
        'c5name' => $Zfpf->encrypt_1c('Timing (before PHA or HIRA) and Purpose'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Process safety information. (a) The owner or operator shall complete a compilation of written process safety information before conducting any process hazard analysis required by the rule. The compilation of written process safety information is to enable the owner or operator and the employees involved in operating the process to identify and understand the hazards posed by those processes involving regulated substances. This process safety information shall include information pertaining to the hazards of the regulated substances used or produced by the process, information pertaining to the technology of the process, and information pertaining to the equipment in the process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    73 => array(
        'c5name' => $Zfpf->encrypt_1c('Process-Materials Properties and Hazards'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Information pertaining to the hazards of the regulated substances in the process. This information shall consist of at least the following: (1) Toxicity information (2) Permissible exposure limits; (3) Physical data; (4) Reactivity data: (5) Corrosivity data; (6) Thermal and chemical stability data; and (7) Hazardous effects of inadvertent mixing of different materials that could foreseeably occur. Note to paragraph (b): Safety Data Sheets (SDS) meeting the requirements of 29 CFR 1910.1200(g) may be used to comply with this requirement to the extent they contain the information required by paragraph (b) of this section [40 CFR 68.65(b)].'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    74 => array(
        'c5name' => $Zfpf->encrypt_1c('Process Technology Introduction'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(c)'),
        'c6quote' => $Zfpf->encrypt_1c('Information pertaining to the technology of the process. (1) Information concerning the technology of the process shall include at least the following:'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    75 => array(
        'c5name' => $Zfpf->encrypt_1c('Flow Diagram'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(c)(1)(i)'),
        'c6quote' => $Zfpf->encrypt_1c('A block flow diagram or simplified process flow diagram;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    76 => array(
        'c5name' => $Zfpf->encrypt_1c('Process Chemistry'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(c)(1)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('Process chemistry;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    77 => array(
        'c5name' => $Zfpf->encrypt_1c('Maximum Intended Inventory'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(c)(1)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('Maximum intended inventory;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    78 => array(
        'c5name' => $Zfpf->encrypt_1c('Operating Limits'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(c)(1)(iv)'),
        'c6quote' => $Zfpf->encrypt_1c('Safe upper and lower limits for such items as temperatures, pressures, flows or compositions; and,'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    79 => array(
        'c5name' => $Zfpf->encrypt_1c('Consequences of Deviations'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(c)(1)(v)'),
        'c6quote' => $Zfpf->encrypt_1c('An evaluation of the consequences of deviations.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    80 => array(
        'c5name' => $Zfpf->encrypt_1c('Original Information No Longer Exists'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(c)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('(2) Where the original technical information no longer exists, such information may be developed in conjunction with the process hazard analysis in sufficient detail to support the analysis.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    81 => array(
        'c5name' => $Zfpf->encrypt_1c('Process Equipment Introduction'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)'),
        'c6quote' => $Zfpf->encrypt_1c('Information pertaining to the equipment in the process. (1) Information pertaining to the equipment in the process shall include:'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    82 => array(
        'c5name' => $Zfpf->encrypt_1c('Materials of Construction'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(1)(i)'),
        'c6quote' => $Zfpf->encrypt_1c('Materials of construction;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    83 => array(
        'c5name' => $Zfpf->encrypt_1c('P&amp;ID'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(1)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('Piping and instrument diagrams (P&amp;IDs);'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    84 => array(
        'c5name' => $Zfpf->encrypt_1c('Electrical Classifications'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(1)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('Electrical classification;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    85 => array(
        'c5name' => $Zfpf->encrypt_1c('Relief Systems'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(1)(iv)'),
        'c6quote' => $Zfpf->encrypt_1c('Relief system design and design basis;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    86 => array(
        'c5name' => $Zfpf->encrypt_1c('Ventilation Systems'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(1)(v)'),
        'c6quote' => $Zfpf->encrypt_1c('Ventilation system design;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    87 => array(
        'c5name' => $Zfpf->encrypt_1c('Codes, Standards, etc.'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(1)(vi)'),
        'c6quote' => $Zfpf->encrypt_1c('Design codes and standards employed;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    88 => array(
        'c5name' => $Zfpf->encrypt_1c('Material and Energy Balances'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(1)(vii)'),
        'c6quote' => $Zfpf->encrypt_1c('Material and energy balances for processes built after June 21, 1999; and'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    89 => array(
        'c5name' => $Zfpf->encrypt_1c('Other Safety Systems'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(1)(viii)'),
        'c6quote' => $Zfpf->encrypt_1c('Safety systems (e.g. interlocks, detection or suppression systems).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    90 => array(
        'c5name' => $Zfpf->encrypt_1c('Good Practices'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall document that equipment complies with recognized and generally accepted good engineering practices.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    91 => array(
        'c5name' => $Zfpf->encrypt_1c('No Longer in General Use'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.65(d)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('For existing equipment designed and constructed in accordance with codes, standards, or practices that are no longer in general use, the owner or operator shall determine and document that the equipment is designed, maintained, inspected, tested, and operating in a safe manner.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    92 => array(
        'c5name' => $Zfpf->encrypt_1c('PHA Required'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.67(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Process hazard analysis. (a) The owner or operator shall perform an initial process hazard analysis (hazard evaluation) on processes covered by this part. The process hazard analysis shall be appropriate to the complexity of the process and shall identify, evaluate, and control the hazards involved in the process. The owner or operator shall determine and document the priority order for conducting process hazard analyses based on a rationale which includes such considerations as extent of the process hazards, number of potentially affected employees, age of the process, and operating history of the process. The process hazard analysis shall be conducted as soon as possible, but not later than June 21, 1999. Process hazards analyses completed to comply with 29 CFR 1910.119(e) are acceptable as initial process hazards analyses. These process hazard analyses shall be updated and revalidated, based on their completion date.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    93 => array(
        'c5name' => $Zfpf->encrypt_1c('Method'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.67(b)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall use one or more of the following methodologies that are appropriate to determine and evaluate the hazards of the process being analyzed. (1) What-If; (2) Checklist; (3) What-If/Checklist; (4) Hazard and Operability Study (HAZOP); (5) Failure Mode and Effects Analysis (FMEA); (6) Fault Tree Analysis; or (7) An appropriate equivalent methodology.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    94 => array(
        'c5name' => $Zfpf->encrypt_1c('Content'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.67(a) and (c)'),
        'c6quote' => $Zfpf->encrypt_1c('(a) [...] The process hazard analysis shall be appropriate to the complexity of the process and shall identify, evaluate, and control the hazards involved in the process. [...] (c) The process hazard analysis shall address: (1) The hazards of the process; (2) The identification of any previous incident which had a likely potential for catastrophic consequences; (3) Engineering and administrative controls applicable to the hazards and their interrelationships such as appropriate application of detection methodologies to provide early warning of releases. (Acceptable detection methods might include process monitoring and control instrumentation with alarms, and detection hardware such as hydrocarbon sensors.); (4) Consequences of failure of engineering and administrative controls; (5) Stationary source siting; (6) Human factors; and (7) A qualitative evaluation of a range of the possible safety and health effects of failure of controls.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    95 => array(
        'c5name' => $Zfpf->encrypt_1c('Team Qualifications'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.67(d)'),
        'c6quote' => $Zfpf->encrypt_1c('The process hazard analysis shall be performed by a team with expertise in engineering and process operations, and the team shall include at least one employee who has experience and knowledge specific to the process being evaluated. Also, one member of the team must be knowledgeable in the specific process hazard analysis methodology being used.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    96 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.67(e)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall establish a system to promptly address the team\'s findings and recommendations; assure that the recommendations are resolved in a timely manner and that the resolution is documented; document what actions are to be taken; complete actions as soon as possible; develop a written schedule of when these actions are to be completed; communicate the actions to operating, maintenance and other employees whose work assignments are in the process and who may be affected by the recommendations or actions.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    97 => array(
        'c5name' => $Zfpf->encrypt_1c('Update and Revalidate'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.67(f)'),
        'c6quote' => $Zfpf->encrypt_1c('At least every five (5) years after the completion of the initial process hazard analysis, the process hazard analysis shall be updated and revalidated by a team meeting the requirements in paragraph (d) of this section [40 CFR 68.67(d)], to assure that the process hazard analysis is consistent with the current process. Updated and revalidated process hazard analyses completed to comply with 29 CFR 1910.119(e) are acceptable to meet the requirements of this paragraph.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    98 => array(
        'c5name' => $Zfpf->encrypt_1c('Retention'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.67(g)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall retain process hazards analyses and updates or revalidations for each process covered by this section, as well as the documented resolution of recommendations described in paragraph (e) of this section [40 CFR 68.67(e)] for the life of the process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    99 => array(
        'c5name' => $Zfpf->encrypt_1c('Purpose'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Operating procedures. (a) The owner or operator shall develop and implement written operating procedures that provide clear instructions for safely conducting activities involved in each covered process consistent with the process safety information and shall address at least the following elements.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    100 => array(
        'c5name' => $Zfpf->encrypt_1c('Operating Phases'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Steps for each operating phase:'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    101 => array(
        'c5name' => $Zfpf->encrypt_1c('Initial Startup'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(1)(i)'),
        'c6quote' => $Zfpf->encrypt_1c('Initial startup;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    102 => array(
        'c5name' => $Zfpf->encrypt_1c('Normal Operations'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(1)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('Normal operations;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    103 => array(
        'c5name' => $Zfpf->encrypt_1c('Temporary Operations'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(1)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('Temporary operations;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    104 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Shutdown'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(1)(iv)'),
        'c6quote' => $Zfpf->encrypt_1c('Emergency shutdown including the conditions under which emergency shutdown is required, and the assignment of shutdown responsibility to qualified operators to ensure that emergency shutdown is executed in a safe and timely manner.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    105 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Operations'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(1)(v)'),
        'c6quote' => $Zfpf->encrypt_1c('Emergency operations;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    106 => array(
        'c5name' => $Zfpf->encrypt_1c('Normal Shutdown'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(1)(vi)'),
        'c6quote' => $Zfpf->encrypt_1c('Normal shutdown; and,'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    107 => array(
        'c5name' => $Zfpf->encrypt_1c('Startup After Unusual Events'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(1)(vii)'),
        'c6quote' => $Zfpf->encrypt_1c('Startup following a turnaround, or after an emergency shutdown.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    108 => array(
        'c5name' => $Zfpf->encrypt_1c('Operating Limits, Deviation Consequences, and Corrective Actions'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('Operating limits: (i) Consequences of deviation; and (ii) Steps required to correct or avoid deviation.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    109 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety and Health'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('Safety and health considerations:'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    110 => array(
        'c5name' => $Zfpf->encrypt_1c('Process-Materials Properties and Hazards'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(3)(i)'),
        'c6quote' => $Zfpf->encrypt_1c('Properties of, and hazards presented by, the chemicals used in the process;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    111 => array(
        'c5name' => $Zfpf->encrypt_1c('Exposure Prevention'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(3)(ii)'),
        'c6quote' => $Zfpf->encrypt_1c('Precautions necessary to prevent exposure, including engineering controls, administrative controls, and personal protective equipment;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    112 => array(
        'c5name' => $Zfpf->encrypt_1c('First Aid'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(3)(iii)'),
        'c6quote' => $Zfpf->encrypt_1c('Control measures to be taken if physical contact or airborne exposure occurs;'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    113 => array(
        'c5name' => $Zfpf->encrypt_1c('Raw-Materials Quality Control'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(3)(iv)[01]'),
        'c6quote' => $Zfpf->encrypt_1c('Quality control for raw materials and'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    114 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazardous-Materials Inventory Control'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(3)(iv)[02]'),
        'c6quote' => $Zfpf->encrypt_1c('control of hazardous chemical inventory levels; and,'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    115 => array(
        'c5name' => $Zfpf->encrypt_1c('Special or Unique Hazards'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(3)(v)'),
        'c6quote' => $Zfpf->encrypt_1c('Any special or unique hazards.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    116 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety Systems'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(a)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('Safety systems and their functions.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    117 => array(
        'c5name' => $Zfpf->encrypt_1c('Access to Procedures'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Operating procedures shall be readily accessible to employees who work in or maintain a process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    118 => array(
        'c5name' => $Zfpf->encrypt_1c('Always Up-to-date'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(c)[01]'),
        'c6quote' => $Zfpf->encrypt_1c('The operating procedures shall be reviewed as often as necessary to assure that they reflect current operating practice, including changes that result from changes in process chemicals, technology, and equipment, and changes to stationary sources.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    119 => array(
        'c5name' => $Zfpf->encrypt_1c('Annual Certification'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(c)[02]'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall certify annually that these operating procedures are current and accurate.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    120 => array(
        'c5name' => $Zfpf->encrypt_1c('Safe Work Practices and Access Control'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.69(d)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall develop and implement safe work practices to provide for the control of hazards during operations such as lockout/tagout; confined space entry; opening process equipment or piping; and control over entrance into a stationary source by maintenance, contractor, laboratory, or other support personnel. These safe work practices shall apply to employees and contractor employees.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    121 => array(
        'c5name' => $Zfpf->encrypt_1c('Initial Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.71(a)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Training. (a) Initial training. (1) Each employee presently involved in operating a process, and each employee before being involved in operating a newly assigned process, shall be trained in an overview of the process and in the operating procedures as specified in [40 CFR] 68.69. The training shall include emphasis on the specific safety and health hazards, emergency operations including shutdown, and safe work practices applicable to the employee\'s job tasks.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    122 => array(
        'c5name' => $Zfpf->encrypt_1c('Started Before June 21, 1999 Exemption'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.71(a)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('In lieu of initial training for those employees already involved in operating a process on June 21, 1999 an owner or operator may certify in writing that the employee has the required knowledge, skills, and abilities to safely carry out the duties and responsibilities as specified in the operating procedures.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    123 => array(
        'c5name' => $Zfpf->encrypt_1c('Refresher Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.71(b)[01]'),
        'c6quote' => $Zfpf->encrypt_1c('Refresher training shall be provided at least every three years, and more often if necessary, to each employee involved in operating a process to assure that the employee understands and adheres to the current operating procedures of the process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    124 => array(
        'c5name' => $Zfpf->encrypt_1c('Refresher Training Consultation'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.71(b)[02]'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator, in consultation with the employees involved in operating the process, shall determine the appropriate frequency of refresher training'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    125 => array(
        'c5name' => $Zfpf->encrypt_1c('Comprehension Verified and Documented'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.71(c)'),
        'c6quote' => $Zfpf->encrypt_1c('Training documentation. The owner or operator shall ascertain that each employee involved in operating a process has received and understood the training required by this paragraph [section 40 CFR 68.71]. The owner or operator shall prepare a record which contains the identity of the employee, the date of training, and the means used to verify that the employee understood the training.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    126 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Mechanical integrity. (a) Application. Paragraphs (b) through (f) of this section apply to the following process equipment: (1) Pressure vessels and storage tanks; (2) Piping systems (including piping components such as valves); (3) Relief and vent systems and devices; (4) Emergency shutdown systems; (5) Controls (including monitoring devices and sensors, alarms, and interlocks) and, (6) Pumps.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    127 => array(
        'c5name' => $Zfpf->encrypt_1c('Maintenance Procedures'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Written procedures. The owner or operator shall establish and implement written procedures to maintain the on-going integrity of process equipment.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    128 => array(
        'c5name' => $Zfpf->encrypt_1c('Maintenance Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(c)'),
        'c6quote' => $Zfpf->encrypt_1c('Training for process maintenance activities. The owner or operator shall train each employee involved in maintaining the on-going integrity of process equipment in an overview of that process and its hazards and in the procedures applicable to the employee\'s job tasks to assure that the employee can perform the job tasks in a safe manner.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    129 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection and Testing'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(d)'),
        'c6quote' => $Zfpf->encrypt_1c('Inspection and testing. (1) Inspections and tests shall be performed on process equipment. (2) Inspection and testing procedures shall follow recognized and generally accepted good engineering practices. (3) The frequency of inspections and tests of process equipment shall be consistent with applicable manufacturers\' recommendations and good engineering practices, and more frequently if determined to be necessary by prior operating experience. (4) The owner or operator shall document each inspection and test that has been performed on process equipment. The documentation shall identify the date of the inspection or test, the name of the person who performed the inspection or test, the serial number or other identifier of the equipment on which the inspection or test was performed, a description of the inspection or test performed, and the results of the inspection or test.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ), /*  Combined into above fragment.
    129 => array(
        'c5name' => $Zfpf->encrypt_1c('Inspection and Testing Required'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(d)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('Inspection and testing. (1) Inspections and tests shall be performed on process equipment.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    130 => array(
        'c5name' => $Zfpf->encrypt_1c('Good Practices'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(d)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('Inspection and testing procedures shall follow recognized and generally accepted good engineering practices.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    131 => array(
        'c5name' => $Zfpf->encrypt_1c('Frequency'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(d)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('The frequency of inspections and tests of process equipment shall be consistent with applicable manufacturers\' recommendations and good engineering practices, and more frequently if determined to be necessary by prior operating experience.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    132 => array(
        'c5name' => $Zfpf->encrypt_1c('Records'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(d)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall document each inspection and test that has been performed on process equipment. The documentation shall identify the date of the inspection or test, the name of the person who performed the inspection or test, the serial number or other identifier of the equipment on which the inspection or test was performed, a description of the inspection or test performed, and the results of the inspection or test.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),*/
    133 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(e)'),
        'c6quote' => $Zfpf->encrypt_1c('Equipment deficiencies. The owner or operator shall correct deficiencies in equipment that are outside acceptable limits (defined by the process safety information in [40 CFR] 68.65) before further use or in a safe and timely manner when necessary means are taken to assure safe operation.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    134 => array(
        'c5name' => $Zfpf->encrypt_1c('Design and Installation Good Practices'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(f)(1) and (2)'),
        'c6quote' => $Zfpf->encrypt_1c('Quality assurance. (1) In the construction of new plants and equipment, the owner or operator shall assure that equipment as it is fabricated is suitable for the process application for which they will be used. (2) Appropriate checks and inspections shall be performed to assure that equipment is installed properly and consistent with design specifications and the manufacturer\'s instructions.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    135 => array(
        'c5name' => $Zfpf->encrypt_1c('Replacement-in-kind Quality Assurance'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.73(f)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall assure that maintenance materials, spare parts and equipment are suitable for the process application for which they will be used.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    136 => array(
        'c5name' => $Zfpf->encrypt_1c('MOC Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.75(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Management of change. (a) The owner or operator shall establish and implement written procedures to manage changes (except for "replacements in kind") to process chemicals, technology, equipment, and procedures; and, changes to stationary sources that affect a covered process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    137 => array(
        'c5name' => $Zfpf->encrypt_1c('MOC Procedural Requirements'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.75(b)'),
        'c6quote' => $Zfpf->encrypt_1c('The procedures shall assure that the following considerations are addressed prior to any change: (1) The technical basis for the proposed change; (2) Impact of change on safety and health; (3) Modifications to operating procedures; (4) Necessary time period for the change; and, (5) Authorization requirements for the proposed change.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    138 => array(
        'c5name' => $Zfpf->encrypt_1c('Pre-Startup Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.75(c)'),
        'c6quote' => $Zfpf->encrypt_1c('Employees involved in operating a process and maintenance and contract employees whose job tasks will be affected by a change in the process shall be informed of, and trained in, the change prior to start-up of the process or affected part of the process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    139 => array(
        'c5name' => $Zfpf->encrypt_1c('PSI Update'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.75(d)'),
        'c6quote' => $Zfpf->encrypt_1c('If a change covered by this paragraph [section 40 CFR 68.75] results in a change in the process safety information required by [40 CFR] 68.65 of this part, such information shall be updated accordingly.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    140 => array(
        'c5name' => $Zfpf->encrypt_1c('Procedures Update'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.75(e)'),
        'c6quote' => $Zfpf->encrypt_1c('If a change covered by this paragraph [section 40 CFR 68.75] results in a change in the operating procedures or practices required by [40 CFR] 68.69, such procedures or practices shall be updated accordingly.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    141 => array(
        'c5name' => $Zfpf->encrypt_1c('PSR Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.77(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Pre-startup review. (a) The owner or operator shall perform a pre-startup safety review for new stationary sources and for modified stationary sources when the modification is significant enough to require a change in the process safety information.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    142 => array(
        'c5name' => $Zfpf->encrypt_1c('PSR Requirements'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.77(b)'),
        'c6quote' => $Zfpf->encrypt_1c('The pre-startup safety review shall confirm that prior to the introduction of regulated substances to a process: (1) Construction and equipment is in accordance with design specifications; (2) Safety, operating, maintenance, and emergency procedures are in place and are adequate; (3) For new stationary sources, a process hazard analysis has been performed and recommendations have been resolved or implemented before startup; and modified stationary sources meet the requirements contained in management of change, [40 CFR] 68.75[; and] (4) Training of each employee involved in operating a process has been completed.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    143 => array(
        'c5name' => $Zfpf->encrypt_1c('Certification of "have evaluated... to verify"'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.79(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Compliance audits. (a) The owner or operator shall certify that they have evaluated compliance with the provisions of this subpart [40 CFR 68.65 to 68.87] at least every three years to verify that procedures and practices developed under this subpart are adequate and are being followed.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    144 => array(
        'c5name' => $Zfpf->encrypt_1c('Auditor Qualifications'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.79(b)'),
        'c6quote' => $Zfpf->encrypt_1c('The compliance audit shall be conducted by at least one person knowledgeable in the process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    145 => array(
        'c5name' => $Zfpf->encrypt_1c('Report'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.79(c)'),
        'c6quote' => $Zfpf->encrypt_1c('A report of the findings of the audit shall be developed.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    146 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.79(d)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall promptly determine and document an appropriate response to each of the findings of the compliance audit, and document that deficiencies have been corrected.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    147 => array(
        'c5name' => $Zfpf->encrypt_1c('Retention'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.79(e)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall retain the two (2) most recent compliance audit reports.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    148 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.81(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Incident investigation. (a) The owner or operator shall investigate each incident which resulted in, or could reasonably have resulted in a catastrophic release of a regulated substance.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    149 => array(
        'c5name' => $Zfpf->encrypt_1c('48-Hour Start Time'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.81(b)'),
        'c6quote' => $Zfpf->encrypt_1c('An incident investigation shall be initiated as promptly as possible, but not later than 48 hours following the incident.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    150 => array(
        'c5name' => $Zfpf->encrypt_1c('Team Qualifications'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.81(c)'),
        'c6quote' => $Zfpf->encrypt_1c('An incident investigation team shall be established and consist of at least one person knowledgeable in the process involved, including a contract employee if the incident involved work of the contractor, and other persons with appropriate knowledge and experience to thoroughly investigate and analyze the incident.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    151 => array(
        'c5name' => $Zfpf->encrypt_1c('Report Content'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.81(d)'),
        'c6quote' => $Zfpf->encrypt_1c('A report shall be prepared at the conclusion of the investigation which includes at a minimum: (1) Date of incident; (2) Date investigation began; (3) A description of the incident; (4) The factors that contributed to the incident; and, (5) Any recommendations resulting from the investigation.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    152 => array(
        'c5name' => $Zfpf->encrypt_1c('Resolution'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.81(e)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall establish a system to promptly address and resolve the incident report findings and recommendations. Resolutions and corrective actions shall be documented.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    153 => array(
        'c5name' => $Zfpf->encrypt_1c('Employee and Contractor Briefing'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.81(f)'),
        'c6quote' => $Zfpf->encrypt_1c('The report shall be reviewed with all affected personnel whose job tasks are relevant to the incident findings including contract employees where applicable.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    154 => array(
        'c5name' => $Zfpf->encrypt_1c('Retention'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.81(g)'),
        'c6quote' => $Zfpf->encrypt_1c('Incident investigation reports shall be retained for five years.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    155 => array(
        'c5name' => $Zfpf->encrypt_1c('Plan of Action for Employee Participation'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.83(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Employee participation. (a) The owner or operator shall develop a written plan of action regarding the implementation of the employee participation required by this section.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    156 => array(
        'c5name' => $Zfpf->encrypt_1c('Consultation with Employees'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.83(b)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall consult with employees and their representatives on the conduct and development of process hazards analyses and on the development of the other elements of process safety management in this rule.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    157 => array(
        'c5name' => $Zfpf->encrypt_1c('Information Access'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.83(c)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall provide to employees and their representatives access to process hazard analyses and to all other information required to be developed under this rule.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    158 => array(
        'c5name' => $Zfpf->encrypt_1c('Hot Work Permit'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.85'),
        'c6quote' => $Zfpf->encrypt_1c('Hot work permit. (a) The owner or operator shall issue a hot work permit for hot work operations conducted on or near a covered process. (b) The permit shall document that the fire prevention and protection requirements in 29 CFR 1910.252(a) have been implemented prior to beginning the hot work operations; it shall indicate the date(s) authorized for hot work; and identify the object on which hot work is to be performed. The permit shall be kept on file until completion of the hot work operations.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    159 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(a)'),
        'c6quote' => $Zfpf->encrypt_1c('Contractors. (a) Application. This section applies to contractors performing maintenance or repair, turnaround, major renovation, or specialty work on or adjacent to a covered process. It does not apply to contractors providing incidental services which do not influence process safety, such as janitorial work, food and drink services, laundry, delivery or other supply services.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    160 => array(
        'c5name' => $Zfpf->encrypt_1c('Owner/Operator Responsibilities'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(b)'),
        'c6quote' => $Zfpf->encrypt_1c('Owner or operator responsibilities.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    161 => array(
        'c5name' => $Zfpf->encrypt_1c('Qualify'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(b)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator, when selecting a contractor, shall obtain and evaluate information regarding the contract owner or operator\'s [aka the contractor\'s] safety performance and programs.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    162 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazards Notification'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(b)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall inform contract owner or operator of the known potential fire, explosion, or toxic release hazards related to the contractor\'s work and the process.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    163 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency-plans Briefing'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(b)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall explain to the contract owner or operator the applicable provisions of subpart E of this part [40 CFR 68.90 and 68.95].'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    164 => array(
        'c5name' => $Zfpf->encrypt_1c('Safe Work Practices and Access Control'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(b)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall develop and implement safe work practices consistent with [40 CFR] 68.69(d), to control the entrance, presence, and exit of the contract owner or operator and contract employees in covered process areas. [See also 40 CFR 68.69(d), which applies to contractors]'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    165 => array(
        'c5name' => $Zfpf->encrypt_1c('Evaluate'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(b)(5)'),
        'c6quote' => $Zfpf->encrypt_1c('The owner or operator shall periodically evaluate the performance of the contract owner or operator in fulfilling their obligations as specified in paragraph (c) of this section [40 CFR 68.87(c)].'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    166 => array(
        'c5name' => $Zfpf->encrypt_1c('Contractor Responsibilities'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(c)'),
        'c6quote' => $Zfpf->encrypt_1c('Contract owner or operator [aka contractor] responsibilities.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    167 => array(
        'c5name' => $Zfpf->encrypt_1c('Work-Practice Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(c)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract owner or operator shall assure that each contract employee is trained in the work practices necessary to safely perform his/her job.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    168 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazards and Emergencies Training'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(c)(2)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract owner or operator shall assure that each contract employee is instructed in the known potential fire, explosion, or toxic release hazards related to his/her job and the process, and the applicable provisions of the emergency action plan.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    169 => array(
        'c5name' => $Zfpf->encrypt_1c('Comprehension Verified and Documented'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(c)(3)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract owner or operator shall document that each contract employee has received and understood the training required by this section. The contract owner or operator shall prepare a record which contains the identity of the contract employee, the date of training, and the means used to verify that the employee understood the training.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    170 => array(
        'c5name' => $Zfpf->encrypt_1c('Safety Enforcement'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(c)(4)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract owner or operator shall assure that each contract employee follows the safety rules of the stationary source including the safe work practices required by [40 CFR] 68.69(d).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    171 => array(
        'c5name' => $Zfpf->encrypt_1c('Hazards Created or Discovered by the Work'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.87(c)(5)'),
        'c6quote' => $Zfpf->encrypt_1c('The contract owner or operator shall advise the owner or operator of any unique hazards presented by the contract owner or operator\'s work, or of any hazards found by the contract owner or operator\'s work.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    172 => array(
        'c5name' => $Zfpf->encrypt_1c('Subpart Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Subpart E'),
        'c6quote' => $Zfpf->encrypt_1c('Subpart E -- Emergency Response'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    173 => array(
        'c5name' => $Zfpf->encrypt_1c('Applicability and Community Emergency-Response Option'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.90'),
        'c6quote' => $Zfpf->encrypt_1c('Applicability. (a) Responding stationary source. Except as provided in paragraph (b) of this section, the owner or operator of a stationary source with Program 2 and Program 3 processes shall comply with the requirements of [40 CFR] 68.93, 68.95, and 68.96. (b) Non-responding stationary source. The owner or operator of a stationary source whose employees will not respond to accidental releases of regulated substances need not comply with [40 CFR] 68.95 of this part provided that: (1) For stationary sources with any regulated toxic substance held in a process above the threshold quantity, the stationary source is included in the community emergency response plan developed under 42 U.S.C. 11003; (2) For stationary sources with only regulated flammable substances held in a process above the threshold quantity, the owner or operator has coordinated response actions with the local fire department; (3) Appropriate mechanisms are in place to notify emergency responders when there is a need for a response; (4) The owner or operator performs the annual emergency response coordination activities required under [40 CFR] 68.93; and (5) The owner or operator performs the annual notification exercises required under [40 CFR] 68.96(a).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    195 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency coordination (Owner/Operator with community)'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.93'),
        'c6quote' => $Zfpf->encrypt_1c('Emergency response coordination activities. The owner or operator of a stationary source shall coordinate response needs with local emergency planning and response organizations to determine how the stationary source is addressed in the community emergency response plan and to ensure that local response organizations are aware of the regulated substances at the stationary source, their quantities, the risks presented by covered processes, and the resources and capabilities at the stationary source to respond to an accidental release of a regulated substance. (a) Coordination shall occur at least annually, and more frequently if necessary, to address changes: At the stationary source; in the stationary source\'s emergency response and/or emergency action plan; and/or in the community emergency response plan. (b) Coordination shall include providing to the local emergency planning and response organizations: The stationary source\'s emergency response plan if one exists; emergency action plan; updated emergency contact information; and other information necessary for developing and implementing the local emergency response plan. For responding stationary sources, coordination shall also include consulting with local emergency response officials to establish appropriate schedules and plans for field and tabletop exercises required under [section] 68.96(b). The owner or operator shall request an opportunity to meet with the local emergency planning committee (or equivalent) and/or local fire department as appropriate to review and discuss those materials. (c) The owner or operator shall document coordination with local authorities, including: The names of individuals involved and their contact information (phone number, email address, and organizational affiliations); dates of coordination activities; and nature of coordination activities. (d) Classified and restricted information. The disclosure of information classified or restricted by the Department of Defense or other Federal agencies or contractors of such agencies shall be controlled by applicable laws, regulations, or executive orders concerning the release of that classified or restricted information.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    174 => array(
        'c5name' => $Zfpf->encrypt_1c('Owner/Operator Emergency-Response Option'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.95'),
        'c6quote' => $Zfpf->encrypt_1c('Emergency response program. (a) The owner or operator shall develop and implement an emergency response program for the purpose of protecting public health and the environment. Such program shall include the following elements: (1) An emergency response plan, which shall be maintained at the stationary source and contain at least the following elements: (i) Procedures for informing the public and the appropriate Federal, state, and local emergency response agencies about accidental releases; (ii) Documentation of proper first-aid and emergency medical treatment necessary to treat accidental human exposures; and (iii) Procedures and measures for emergency response after an accidental release of a regulated substance; (2) Procedures for the use of emergency response equipment and for its inspection, testing, and maintenance; (3) Training for all employees in relevant procedures; and (4) Procedures to review and update, as appropriate, the emergency response plan to reflect changes at the stationary source and ensure that employees are informed of changes. The owner or operator shall review and update the plan as appropriate based on changes at the stationary source or new information obtained from coordination activities, emergency response exercises, incident investigations, or other available information, and ensure that employees are informed of the changes. (b) A written plan that complies with other Federal contingency plan regulations or is consistent with the approach in the National Response Team\'s Integrated Contingency Plan Guidance ("One Plan") and that, among other matters, includes the elements provided in paragraph (a) of this section, shall satisfy the requirements of this section if the owner or operator also complies with paragraph (c) of this section. (c) The emergency response plan developed under paragraph (a)(1) of this section shall be coordinated with the community emergency response plan developed under 42 U.S.C. 11003. Upon request of the LEPC or emergency response officials, the owner or operator shall promptly provide to the local emergency response officials information necessary for developing and implementing the community emergency response plan.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    196 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency response exercises'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.96'),
        'c6quote' => $Zfpf->encrypt_1c('Emergency response exercises. (a) Notification exercises. At least once each calendar year, the owner or operator of a stationary source with any Program 2 or Program 3 process shall conduct an exercise of the stationary source\'s emergency response notification mechanisms required under [40 CFR] 68.90(b)(3) or [40 CFR] 68.95(a)(1)(i), as appropriate, before December 19, 2024, and annually thereafter. Owners or operators of responding stationary sources may perform the notification exercise as part of the tabletop and field exercises required in paragraph (b) of this section. The owner/operator shall maintain a written record of each notification exercise conducted over the last five years. (b) Emergency response exercise program. The owner or operator of a stationary source subject to the requirements of [40 CFR] 68.95 shall develop and implement an exercise program for its emergency response program, including the plan required under [40 CFR] 68.95(a)(1). Exercises shall involve facility emergency response personnel and, as appropriate, emergency response contractors. When planning emergency response field and tabletop exercises, the owner or operator shall coordinate with local public emergency response officials and invite them to participate in the exercise. The emergency response exercise program shall include: (1) Emergency response field exercises. The owner or operator shall conduct field exercises involving the simulated accidental release of a regulated substance (i.e., toxic substance release or release of a regulated flammable substance involving a fire and/or explosion). (i) Frequency. As part of coordination with local emergency response officials required by [40 CFR] 68.93, the owner or operator shall consult with these officials to establish an appropriate frequency for field exercises. (ii) Scope. Field exercises shall involve tests of the source’s emergency response plan, including deployment of emergency response personnel and equipment. Field exercises should include: Tests of procedures to notify the public and the appropriate Federal, state, and local emergency response agencies about an accidental release; tests of procedures and measures for emergency response actions including evacuations and medical treatment; tests of communications systems; mobilization of facility emergency response personnel, including contractors, as appropriate; coordination with local emergency responders; emergency response equipment deployment; and any other action identified in the emergency response program, as appropriate. (2) Tabletop exercises. The owner or operator shall conduct a tabletop exercise involving the simulated accidental release of a regulated substance. (i) Frequency. As part of coordination with local emergency response officials required by [40 CFR] 68.93, the owner or operator shall consult with these officials to establish an appropriate frequency for tabletop exercises, and shall conduct a tabletop exercise before December 21, 2026, and at a minimum of at least once every three years thereafter. (ii) Scope. Tabletop exercises shall involve discussions of the source’s emergency response plan. The exercise should include discussions of: Procedures to notify the public and the appropriate Federal, state, and local emergency response agencies; procedures and measures for emergency response including evacuations and medical treatment; identification of facility emergency response personnel and/or contractors and their responsibilities; coordination with local emergency responders; procedures for emergency response equipment deployment; and any other action identified in the emergency response plan, as appropriate. (3) Documentation. The owner or operator shall prepare an evaluation report within 90 days of each field and tabletop exercise. The report should include: A description of the exercise scenario; names and organizations of each participant; an evaluation of the exercise results including lessons learned; recommendations for improvement or revisions to the emergency response exercise program and emergency response program, and a schedule to promptly address and resolve recommendations. (c) Alternative means of meeting exercise requirements. The owner or operator may satisfy the requirement to conduct notification, field and/or tabletop exercises through: (1) Exercises conducted to meet other Federal, state or local exercise requirements, provided the exercise meets the requirements of paragraphs (a) and/or (b) of this section, as appropriate. (2) Response to an accidental release, provided the response includes the actions indicated in paragraphs (a) and/or (b) of this section, as appropriate. When used to meet field and/or tabletop exercise requirements, the owner or operator shall prepare an after-action report comparable to the exercise evaluation report required in paragraph (b)(3) of this section, within 90 days of the incident.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    175 => array(
        'c5name' => $Zfpf->encrypt_1c('Subpart Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Subpart F'),
        'c6quote' => $Zfpf->encrypt_1c('Subpart F -- Regulated Substances for Accidental Release Prevention'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    176 => array(
        'c5name' => $Zfpf->encrypt_1c('Regulated Substances for Accidental Release Prevention'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.100 to 68.130'),
        'c6quote' => $Zfpf->encrypt_1c('[40 CFR 68.100 to 68.130 list the regulated substances and the method for listing. These sections are not reproduced here. The latest version may be downloaded from www.gpo.gov -- the U.S. Government Printing Office.]'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    177 => array(
        'c5name' => $Zfpf->encrypt_1c('Subpart Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Subpart G'),
        'c6quote' => $Zfpf->encrypt_1c('Subpart G -- Risk Management Plan'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    178 => array(
        'c5name' => $Zfpf->encrypt_1c('Submission'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.150'),
        'c6quote' => $Zfpf->encrypt_1c('Submission. (a) The owner or operator shall submit a single RMP that includes the information required by [40 CFR] 68.155 through 68.185 for all covered processes. The RMP shall be submitted in the method and format to the central point specified by EPA as of the date of submission. (b) The owner or operator shall submit the first RMP no later than the latest of the following dates: (1) June 21, 1999; (2) Three years after the date on which a regulated substance is first listed under [40 CFR] 68.130; or (3) The date on which a regulated substance is first present above a threshold quantity in a process. (c) The owner or operator of any stationary source for which an RMP was submitted before June 21, 2004, shall revise the RMP to include the information required by [40 CFR] 68.160(b)(6) and (14) by June 21, 2004 in the manner specified by EPA prior to that date. Any such submission shall also include the information required by [40 CFR] 68.160(b)(20) (indicating that the submission is a correction to include the information required by [40 CFR] 68.160(b)(6) and (14) or an update under [40 CFR] 68.190). (d) RMPs submitted under this section shall be updated and corrected in accordance with [40 CFR] 68.190 and 68.195. (e) Notwithstanding the provisions of [40 CFR] 68.155 to 68.190, the RMP shall exclude classified information. Subject to appropriate procedures to protect such information from public disclosure, classified data or information excluded from the RMP may be made available in a classified annex to the RMP for review by Federal and state representatives who have received the appropriate security clearances. (f) Procedures for asserting that information submitted in the RMP is entitled to protection as confidential business information are set forth in [40 CFR] 68.151 and 68.152.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    179 => array(
        'c5name' => $Zfpf->encrypt_1c('Confidential Business Information'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.151'),
        'c6quote' => $Zfpf->encrypt_1c('Assertion of claims of confidential business information. (a) Except as provided in paragraph (b) of this section, an owner or operator of a stationary source required to report or otherwise provide information under this part may make a claim of confidential business information for any such information that meets the criteria set forth in 40 CFR 2.301. (b) Notwithstanding the provisions of 40 CFR part 2, an owner or operator of a stationary source subject to this part may not claim as confidential business information the following information: (1) Registration data required by [40 CFR] 68.160(b)(1) through (6), (8), (10) through (13), and (21), and NAICS code and Program level of the process set forth in [40 CFR] 68.160(b)(7); (2) Offsite consequence analysis data required by [40 CFR] 68.165(b)(4), (b)(9), (b)(10), (b)(11), and (b)(12). (3) Accident history data required by [40 CFR] 68.168; (4) Prevention program data required by [40 CFR] 68.170(b), (d), (e)(1), (f) through (k); (5) Prevention program data required by [40 CFR] 68.175(b), (d), (e)(1), (f) through (p); and (6) Emergency response program data required by [40 CFR] 68.180. (c) Notwithstanding the procedures specified in 40 CFR part 2, an owner or operator asserting a claim of CBI with respect to information contained in its RMP, shall submit to EPA at the time it submits the RMP the following: (1) The information claimed confidential, provided in a format to be specified by EPA; (2) A sanitized (redacted) copy of the RMP, with the notation "CBI" substituted for the information claimed confidential, except that a generic category or class name shall be substituted for any chemical name or identity claimed confidential; and (3) The document or documents substantiating each claim of confidential business information, as described in [40 CFR] 68.152.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 84 FR 69843-69916 (2019-12-19)')
    ),
    180 => array(
        'c5name' => $Zfpf->encrypt_1c('Registration'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.160'),
        'c6quote' => $Zfpf->encrypt_1c('Registration. (a) The owner or operator shall complete a single registration form and include it in the RMP. The form shall cover all regulated substances handled in covered processes. (b) The registration shall include the following data: (1) Stationary source name, street, city, county, state, zip code, latitude and longitude, method for obtaining latitude and longitude, and description of location that latitude and longitude represent; (2) The stationary source Dun and Bradstreet number; (3) Name and Dun and Bradstreet number of the corporate parent company; (4) The name, telephone number, and mailing address of the owner or operator; (5) The name and title of the person or position with overall responsibility for RMP elements and implementation, and (optional) the e-mail address for that person or position; (6) The name, title, telephone number, 24-hour telephone number, and, as of June 21, 2004, the e-mail address (if an e-mail address exists) of the emergency contact; (7) For each covered process, the name and CAS number of each regulated substance held above the threshold quantity in the process, the maximum quantity of each regulated substance or mixture in the process (in pounds) to two significant digits, the five- or six-digit NAICS code that most closely corresponds to the process, and the Program level of the process; (8) The stationary source EPA identifier; (9) The number of full-time employees at the stationary source; (10) Whether the stationary source is subject to 29 CFR 1910.119; (11) Whether the stationary source is subject to 40 CFR part 355; (12) If the stationary source has a CAA Title V operating permit, the permit number; and (13) The date of the last safety inspection of the stationary source by a Federal, state, or local government agency and the identity of the inspecting entity. (14) As of June 21, 2004, the name, the mailing address, and the telephone number of the contractor who prepared the RMP (if any); (15) Source or Parent Company E-Mail Address (Optional); (16) Source Homepage address (Optional) (17) Phone number at the source for public inquiries (Optional); (18) Local Emergency Planning Committee (Optional); (19) OSHA Voluntary Protection Program status (Optional); (20) As of June 21, 2004, the type of and reason for any changes being made to a previously submitted RMP; the types of changes to RMP are categorized as follows: (i) Updates and re-submissions required under [40 CFR] 68.190(b); (ii) Corrections under [40 CFR] 68.195 or for purposes of correcting minor clerical errors, updating administrative information, providing missing data elements or reflecting facility ownership changes, and which do not require an update and re-submission as specified in [40 CFR] 68.190(b); (iii) De-registrations required under [40 CFR] 68.190(c); and (iv) Withdrawals of an RMP for any facility that was erroneously considered subject to this part 68. (21) Whether a public meeting has been held following an RMP reportable accident, pursuant to [40 CFR] 68.210(b).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    181 => array(
        'c5name' => $Zfpf->encrypt_1c('Offsite Consequence Analysis'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.165'),
        'c6quote' => $Zfpf->encrypt_1c('Offsite consequence analysis. (a) The owner or operator shall submit in the RMP information: (1) One worst-case release scenario for each Program 1 process; and (2) For Program 2 and 3 processes, one worst-case release scenario to represent all regulated toxic substances held above the threshold quantity and one worst-case release scenario to represent all regulated flammable substances held above the threshold quantity. If additional worst-case scenarios for toxics or flammables are required by [40 CFR] 68.25(a)(2)(iii), the owner or operator shall submit the same information on the additional scenario(s). The owner or operator of Program 2 and 3 processes shall also submit information on one alternative release scenario for each regulated toxic substance held above the threshold quantity and one alternative release scenario to represent all regulated flammable substances held above the threshold quantity. (b) The owner or operator shall submit the following data: (1) Chemical name; (2) Percentage weight of the chemical in a liquid mixture (toxics only); (3) Physical state (toxics only); (4) Basis of results (give model name if used); (5) Scenario (explosion, fire, toxic gas release, or liquid spill and evaporation); (6) Quantity released in pounds; (7) Release rate; (8) Release duration; (9) Wind speed and atmospheric stability class (toxics only); (10) Topography (toxics only); (11) Distance to endpoint; (12) Public and environmental receptors within the distance; (13) Passive mitigation considered; and (14) Active mitigation considered (alternative releases only);'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    182 => array(
        'c5name' => $Zfpf->encrypt_1c('Five-year Accident History'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.168'),
        'c6quote' => $Zfpf->encrypt_1c('Five-year accident history. The owner or operator shall submit in the RMP the information provided in [40 CFR] 68.42(b) on each accident covered by [40 CFR] 68.42(a).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    183 => array(
        'c5name' => $Zfpf->encrypt_1c('Prevention Program 2'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.170'),
        'c6quote' => $Zfpf->encrypt_1c('Prevention program/Program 2. (a) For each Program 2 process, the owner or operator shall provide in the RMP the information indicated in paragraphs (b) through (k) of this section. If the same information applies to more than one covered process, the owner or operator may provide the information only once, but shall indicate to which processes the information applies. (b) The five- or six-digit NAICS code that most closely corresponds to the process. (c) The name(s) of the chemical(s) covered. (d) The date of the most recent review or revision of the safety information and a list of Federal or state regulations or industry-specific design codes and standards used to demonstrate compliance with the safety information requirement. (e) The date of completion of the most recent hazard review or update. (1) The expected date of completion of any changes resulting from the hazard review; (2) Major hazards identified; (3) Process controls in use; (4) Mitigation systems in use; (5) Monitoring and detection systems in use; and (6) Changes since the last hazard review. (f) The date of the most recent review or revision of operating procedures. (g) The date of the most recent review or revision of training programs; (1) The type of training provided -- classroom, classroom plus on the job, on the job; and (2) The type of competency testing used. (h) The date of the most recent review or revision of maintenance procedures and the date of the most recent equipment inspection or test and the equipment inspected or tested. (i) The date of the most recent compliance audit and the expected date of completion of any changes resulting from the compliance audit. (j) The date of the most recent incident investigation and the expected date of completion of any changes resulting from the investigation. (k) The date of the most recent change that triggered a review or revision of safety information, the hazard review, operating or maintenance procedures, or training.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    184 => array(
        'c5name' => $Zfpf->encrypt_1c('Prevention Program 3'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.175'),
        'c6quote' => $Zfpf->encrypt_1c('Prevention program/Program 3. (a) For each Program 3 process, the owner or operator shall provide the information indicated in paragraphs (b) through (p) of this section. If the same information applies to more than one covered process, the owner or operator may provide the information only once, but shall indicate to which processes the information applies. (b) The five- or six-digit NAICS code that most closely corresponds to the process. (c) The name(s) of the substance(s) covered. (d) The date on which the safety information was last reviewed or revised. (e) The date of completion of the most recent PHA or update and the technique used. (1) The expected date of completion of any changes resulting from the PHA; (2) Major hazards identified; (3) Process controls in use; (4) Mitigation systems in use; (5) Monitoring and detection systems in use; and (6) Changes since the last PHA. (f) The date of the most recent review or revision of operating procedures. (g) The date of the most recent review or revision of training programs; (1) The type of training provided -- classroom, classroom plus on the job, on the job; and (2) The type of competency testing used. (h) The date of the most recent review or revision of maintenance procedures and the date of the most recent equipment inspection or test and the equipment inspected or tested. (i) The date of the most recent change that triggered management of change procedures and the date of the most recent review or revision of management of change procedures. (j) The date of the most recent pre-startup review. (k) The date of the most recent compliance audit and the expected date of completion of any changes resulting from the compliance audit; (l) The date of the most recent incident investigation and the expected date of completion of any changes resulting from the investigation; (m) The date of the most recent review or revision of employee participation plans; (n) The date of the most recent review or revision of hot work permit procedures; (o) The date of the most recent review or revision of contractor safety procedures; and (p) The date of the most recent evaluation of contractor safety performance.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    185 => array(
        'c5name' => $Zfpf->encrypt_1c('Emergency Response Program'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.180'),
        'c6quote' => $Zfpf->encrypt_1c('Emergency response program and exercises. (a) The owner or operator shall provide in the RMP: (1) Name, phone number and email address of local emergency planning and response organizations with which the stationary source last coordinated emergency response efforts, pursuant to [40 CFR] 68.10(g)(3) or [40 CFR] 68.93. (2) The date of the most recent coordination with the local emergency response organizations, pursuant to [40 CFR] 68.93 and (3) A list of Federal or state emergency plan requirements to which the stationary source is subject. (b) The owner or operator shall identify in the RMP whether the facility is a responding stationary source or a non-responding stationary source, pursuant to [40 CFR] 68.90. (1) For non-responding stationary sources, the owner or operator shall identify: (i) For stationary sources with any regulated toxic substance held in a process above the threshold quantity, whether the stationary source is included in the community emergency response plan developed under 42 U.S.C. 11003, pursuant to [40 CFR] 68.90(b)(1); (ii) For stationary sources with only regulated flammable substances held in a process above the threshold quantity, the date of the most recent coordination with the local fire department, pursuant to [40 CFR] 68.90(b)(2); (iii) What mechanisms are in place to notify the public and emergency responders when there is a need for emergency response; and (iv) The date of the most recent notification exercise, as required in [40 CFR] 68.96(a). (2) For responding stationary sources, the owner or operator shall identify: (i) The date of the most recent review and update of the emergency response plan, pursuant to [40 CFR] 68.95(a)(4); (ii) The date of the most recent notification exercise, as required in [40 CFR] 68.96(a); (iii) The date of the most recent field exercise, as required in [40 CFR] 68.96(b)(1); and (iv) The date of the most recent tabletop exercise, as required in [40 CFR] 68.96(b)(2).'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    186 => array(
        'c5name' => $Zfpf->encrypt_1c('RMP Certification'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.185'),
        'c6quote' => $Zfpf->encrypt_1c('Certification. (a) For Program 1 processes, the owner or operator shall submit in the RMP the certification statement provided in [40 CFR] 68.12(b)(4). (b) For all other covered processes, the owner or operator shall submit in the RMP a single certification that, to the best of the signer\'s knowledge, information, and belief formed after reasonable inquiry, the information submitted is true, accurate, and complete.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    187 => array(
        'c5name' => $Zfpf->encrypt_1c('RMP Updates'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.190'),
        'c6quote' => $Zfpf->encrypt_1c('Updates. (a) The owner or operator shall review and update the RMP as specified in paragraph (b) of this section and submit it in the method and format to the central point specified by EPA as of the date of submission. (b) The owner or operator of a stationary source shall revise and update the RMP submitted under [40 CFR] 68.150 as follows: (1) At least once every five years from the date of its initial submission or most recent update required by paragraphs (b)(2) through (b)(7) of this section, whichever is later. For purposes of determining the date of initial submissions, RMPs submitted before June 21, 1999 are considered to have been submitted on that date. (2) No later than three years after a newly regulated substance is first listed by EPA; (3) No later than the date on which a new regulated substance is first present in an already covered process above a threshold quantity; (4) No later than the date on which a regulated substance is first present above a threshold quantity in a new process; (5) Within six months of a change that requires a revised PHA or hazard review; (6) Within six months of a change that requires a revised offsite consequence analysis as provided in [40 CFR] 68.36; and (7) Within six months of a change that alters the Program level that applied to any covered process. (c) If a stationary source is no longer subject to this part, the owner or operator shall submit a de-registration to EPA within six months indicating that the stationary source is no longer covered.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    188 => array(
        'c5name' => $Zfpf->encrypt_1c('Required Corrections to RMP'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.195'),
        'c6quote' => $Zfpf->encrypt_1c('Required corrections. The owner or operator of a stationary source for which a RMP was submitted shall correct the RMP as follows: (a) New accident history information -- For any accidental release meeting the five-year accident history reporting criteria of [40 CFR] 68.42 and occurring after April 9, 2004, the owner or operator shall submit the data required under [40 CFR] 68.168, 68.170(j), and 68.175(l) with respect to that accident within six months of the release or by the time the RMP is updated under [40 CFR] 68.190, whichever is earlier. (b) Emergency contact information -- Beginning June 21, 2004, within one month of any change in the emergency contact information required under [40 CFR] 68.160(b)(6), the owner or operator shall submit a correction of that information.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    189 => array(
        'c5name' => $Zfpf->encrypt_1c('Subpart Name'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Subpart H'),
        'c6quote' => $Zfpf->encrypt_1c('Subpart H -- Other Requirements'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    190 => array(
        'c5name' => $Zfpf->encrypt_1c('Recordkeeping'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.200'),
        'c6quote' => $Zfpf->encrypt_1c('Recordkeeping. The owner or operator shall maintain records supporting the implementation of this part at the stationary source for five years, unless otherwise provided in subpart D of this part [the Program 3 Prevention Program].'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13) added: "at the stationary source"')
    ),
    191 => array(
        'c5name' => $Zfpf->encrypt_1c('Public Access to RMP Information'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.210'),
        'c6quote' => $Zfpf->encrypt_1c('Availability of information to the public. (a) RMP availability. The RMP required under subpart G of this part shall be available to the public under 42 U.S.C. 7414(c) and 40 CFR part 1400. (b) Public meetings. The owner or operator of a stationary source shall hold a public meeting to provide information required under [40 CFR] 68.42(b), no later than 90 days after any RMP reportable accident at the stationary source with any known offsite impact specified in [40 CFR] 68.42(a). (c) Classified and restricted information. The disclosure of information classified or restricted by the Department of Defense or other Federal agencies or contractors of such agencies shall be controlled by applicable laws, regulations, or executive orders concerning the release of that classified or restricted information.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01), 82 FR 4594-4705 (2017-01-13), 84 FR 69843-69916 (2019-12-19)')
    ),
    192 => array(
        'c5name' => $Zfpf->encrypt_1c('Affect on Air Operating Permits'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.215'),
        'c6quote' => $Zfpf->encrypt_1c('Permit content and air permitting authority or designated agency requirements. (a) These requirements apply to any stationary source subject to this part 68 and parts 70 or 71 of this chapter [40 CFR 70 or 71, the Clean Air Act, Title V, Air Operating Permits]. The 40 CFR part 70 or part 71 permit for the stationary source shall contain: (1) A statement listing this part as an applicable requirement; (2) Conditions that require the source owner or operator to submit: (i) A compliance schedule for meeting the requirements of this part by the dates provided in [40 CFR] 68.10(a) through (f) and 68.96(a) and (b)(2)(i), or; (ii) As part of the compliance certification submitted under 40 CFR 70.6(c)(5), a certification statement that the source is in compliance with all requirements of this part, including the registration and submission of the RMP. (b) The owner or operator shall submit any additional relevant information requested by the air permitting authority or designated agency. (c) For 40 CFR part 70 or part 71 permits issued prior to the deadline for registering and submitting the RMP and which do not contain permit conditions described in paragraph (a) of this section, the owner or operator or air permitting authority shall initiate permit revision or reopening according to the procedures of 40 CFR 70.7 or 71.7 to incorporate the terms and conditions consistent with paragraph (a) of this section. (d) The state may delegate the authority to implement and enforce the requirements of paragraph (e) of this section to a state or local agency or agencies other than the air permitting authority. An up-to-date copy of any delegation instrument shall be maintained by the air permitting authority. The state may enter a written agreement with the Administrator under which EPA will implement and enforce the requirements of paragraph (e) of this section. (e) The air permitting authority or the agency designated by delegation or agreement under paragraph (d) of this section shall, at a minimum: (1) Verify that the source owner or operator has registered and submitted an RMP or a revised plan when required by this part; (2) Verify that the source owner or operator has submitted a source certification or in its absence has submitted a compliance schedule consistent with paragraph (a)(2) of this section; (3) For some or all of the sources subject to this section, use one or more mechanisms such as, but not limited to, a completeness check, source audits, record reviews, or facility inspections to ensure that permitted sources are in compliance with the requirements of this part; and (4) Initiate enforcement action based on paragraphs (e)(1) and (e)(2) of this section as appropriate.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    193 => array(
        'c5name' => $Zfpf->encrypt_1c('Audits by EPA'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68.220'),
        'c6quote' => $Zfpf->encrypt_1c('Audits. (a) In addition to inspections for the purpose of regulatory development and enforcement of the Act, the implementing agency shall periodically audit RMPs submitted under subpart G of this part to review the adequacy of such RMPs and require revisions of RMPs when necessary to ensure compliance with subpart G of this part. (b) The implementing agency shall select stationary sources for audits based on any of the following criteria: (1) Accident history of the stationary source; (2) Accident history of other stationary sources in the same industry; (3) Quantity of regulated substances present at the stationary source; (4) Location of the stationary source and its proximity to the public and environmental receptors; (5) The presence of specific regulated substances; (6) The hazards identified in the RMP; and (7) A plan providing for neutral, random oversight. (c) Exemption from audits. A stationary source with a Star or Merit ranking under OSHA\'s voluntary protection program shall be exempt from audits under paragraph (b)(2) and (b)(7) of this section. (d) The implementing agency shall have access to the stationary source, supporting documentation, and any area where an accidental release could occur. (e) Based on the audit, the implementing agency may issue the owner or operator of a stationary source a written preliminary determination of necessary revisions to the stationary source\'s RMP to ensure that the RMP meets the criteria of subpart G of this part. The preliminary determination shall include an explanation for the basis for the revisions, reflecting industry standards and guidelines (such as AIChE/CCPS guidelines and ASME and API standards) to the extent that such standards and guidelines are applicable, and shall include a timetable for their implementation. (f) Written response to a preliminary determination. (1) The owner or operator shall respond in writing to a preliminary determination made in accordance with paragraph (e) of this section. The response shall state the owner or operator will implement the revisions contained in the preliminary determination in accordance with the timetable included in the preliminary determination or shall state that the owner or operator rejects the revisions in whole or in part. For each rejected revision, the owner or operator shall explain the basis for rejecting such revision. Such explanation may include substitute revisions. (2) The written response under paragraph (f)(1) of this section shall be received by the implementing agency within 90 days of the issue of the preliminary determination or a shorter period of time as the implementing agency specifies in the preliminary determination as necessary to protect public health and the environment. Prior to the written response being due and upon written request from the owner or operator, the implementing agency may provide in writing additional time for the response to be received. (g) After providing the owner or operator an opportunity to respond under paragraph (f) of this section, the implementing agency may issue the owner or operator a written final determination of necessary revisions to the stationary source\'s RMP. The final determination may adopt or modify the revisions contained in the preliminary determination under paragraph (e) of this section or may adopt or modify the substitute revisions provided in the response under paragraph (f) of this section. A final determination that adopts a revision rejected by the owner or operator shall include an explanation of the basis for the revision. A final determination that fails to adopt a substitute revision provided under paragraph (f) of this section shall include an explanation of the basis for finding such substitute revision unreasonable. (h) Thirty days after completion of the actions detailed in the implementation schedule set in the final determination under paragraph (g) of this section, the owner or operator shall be in violation of subpart G of this part and this section unless the owner or operator revises the RMP prepared under subpart G of this part as required by the final determination, and submits the revised RMP as required under [40 CFR] 68.150. (i) The public shall have access to the preliminary determinations, responses, and final determinations under this section in a manner consistent with [40 CFR] 68.210. (j) Nothing in this section shall preclude, limit, or interfere in any way with the authority of EPA or the state to exercise its enforcement, investigatory, and information gathering authorities concerning this part under the Act.'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    ),
    194 => array(
        'c5name' => $Zfpf->encrypt_1c('Toxic Endpoints Table'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('40 CFR 68, Appendix A'),
        'c6quote' => $Zfpf->encrypt_1c('Appendix A to Part 68 -- Table of Toxic Endpoints. [This table lists the endpoints for the toxic regulated substances. These are estimates of a chemical\'s concentration in air from which a member of the general public could escape without harm if exposed. Due to children, elderly, etc., a member of the general public may not be able to escape as easily as a worker, so these concentrations are lower than the thresholds used for occupational safety or emergency response. Toxic-endpoint concentrations are used to determine the distance, from the source of a release, that would be affected by the release, which is called the distance to toxic endpoint. This Table of Toxic Endpoints is not reproduced here. The latest version may be downloaded from www.gpo.gov -- the U.S. Government Printing Office.]'),
        'c5source' => $Zfpf->encrypt_1c('40 CFR Chapter I (2014-07-01)')
    )
    // 199 is next array number. 195 to 198 interspersed above.
);
foreach ($cap_fragments as $K => $V) {
    $cap_fragments[$K]['k0fragment'] = $K + 1000; // SPECIAL CASE. CAP fragments may use 1000 to 1999 here. Keys less than 100000 are reserved for templates.
    $cap_fragments[$K]['c5who_is_editing'] = $EncryptedNobody;
}
