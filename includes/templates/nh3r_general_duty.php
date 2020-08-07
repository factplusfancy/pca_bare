<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// General duty for anhydrous-ammonia mechanical refrigeration (ammonia refrigeration).
// This division method matches the Cheesehead division method, except the following.
// (1) A five-year "hazard review" division replaces the PHA division, but includes compliance practices from the "PSM audit" division
// because a semi-quantitative PHA is not required, instead the PSM audit sample-observation methods, 
// intended to cover IIAR 9 and much more, provide a checklist for a general-duty hazard review.
// (2) It doesn't include the following Cheesehead divisions: 
// (2.1) PSM audit (required every 3-years by the PSM rules) and 
// (2.2) offsite hazard assessment and reporting. 
// Offsite hazard assessment, in other words "identify hazards which may result from [accidental] releases 
// using appropriate hazard assessment techniques" (Clean Air Act 112(r)(1)),
// is covered by checking, and attempting to ensure, that the emergency plan of the community
// where the facility is located has an adequate offsite hazard assessment.
// For example, see includes/templates/nh3r_hspswp_ep_usa.php > Emergency Action Plan > item 9.1.
//
// This file inserts a division method (into t0rule), divisions, rule fragments, and needed additional practices,
// maps divisions to fragments and practices, and
// maps fragments to practices.
//
// This file must be run AFTER running includes/templates/nh3r_hspswp_ep_usa.php and nh3r_itm.php for their
// compliance practices to be selected and mapped to the general duty for ammonia refrigeration divisions.
// See setup_nh3r_practices.php


// t0rule insert
$rules = array(
    4 => array( // As a convention, this is the 4th rule in PSM-CAP App. See /includes/templates/rules.php
        'c5name' => $Zfpf->encrypt_1c('General Duty for anhydrous-ammonia mechanical refrigeration'),
        'c5citation' => $Zfpf->encrypt_1c('Tort law, negligence, and duty of care. In the USA, the Occupational Safety and Health Act of 1970, Section 5, 29 U.S. Code 654, and the Clean Air Act Amendments of 1990, which promulgated paragraph 112(r)(1) of the Clean Air Act, 42 U.S. Code 7412(r)(1)')
    )
);
foreach ($rules as $K => $V) {
    $V['k0rule'] = $K;
    $rules[$K]['k0rule'] = $V['k0rule'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0rule', $V);
}

// t0division inserts
// As a convention, the General Duty division method starts with the 51st division in the PSM-CAP App, see /includes/templates/divisions.php 
$divisions = array(
    51 => array(
        'k0rule' => $rules[4]['k0rule'], // $rules[4] is the General Duty division method.
        'c5name' => $Zfpf->encrypt_1c('Management system'),
        'c5citation' => $EncryptedNothing
    ),
    52 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Employee participation'),
        'c5citation' => $EncryptedNothing
    ),
    53 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Process-safety information covering design, materials, fabrication, construction, and installation'),
        'c5citation' => $EncryptedNothing
    ),
    54 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Hazard review'),
        'c5citation' => $EncryptedNothing
    ),
    55 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Hazardous-substance procedures and safe-work practices'),
        'c5citation' => $EncryptedNothing
    ),
    56 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Training on hazardous-substance procedures and safe-work practices'),
        'c5citation' => $EncryptedNothing
    ),
    57 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Contractors (on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.')'),
        'c5citation' => $EncryptedNothing
    ),
    58 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Inspection, testing, and maintenance (ITM) for safe operation and mechanical integrity'),
        'c5citation' => $EncryptedNothing
    ),
    59 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Change management (MOC-PSR)'),
        'c5citation' => $EncryptedNothing
    ),
    60 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Incident investigation'),
        'c5citation' => $EncryptedNothing
    ),
    61 => array(
        'k0rule' => $rules[4]['k0rule'],
        'c5name' => $Zfpf->encrypt_1c('Emergency planning'),
        'c5citation' => $EncryptedNothing
    )
);
// Keys less than 100000 are reserved for templates.
foreach ($divisions as $K => $V) {
    $V['k0division'] = $K;
    $divisions[$K]['k0division'] = $V['k0division'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0division', $V);
}

// t0fragment inserts
$general_duty_fragments = array(
    0 => array(
        'c5name' => $Zfpf->encrypt_1c('Tort law, negligence, and duty of care.'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('Statutes, case law, and so forth in the applicable jurisdiction.'),
        'c6quote' => $Zfpf->encrypt_1c('See definitions of "negligence" (and "reasonable care") in applicable legal dictionaries, for example, "A failure to behave with the level of care that someone of ordinary prudence would have exercised under the same circumstances." (Cornell University, Legal Information Institute, Wex Legal Dictionary, Negligence, https://www.law.cornell.edu/wex/negligence -- accessed August 6, 2020). See also definitions of "strict liability" for "abnormally dangerous activity" or "ultrahazardous activity", for example, "An activity or process that presents an unavoidable risk of serious harm to the other people or [their] property, for which the actor may be held strictly liable for the harm, even if the actor has exercised reasonable care to prevent that harm. Also termed abnormally dangerous activity; extrahazardous activity. According to the Restatement (Second) of Torts, Section 520: \'In determining whether an activity is abnormally dangerous, the following factors are to be considered: (a) existence of a high degree of risk of some harm to the person, land, or chattels of others; (b) likelihood that the harm that results from it will be great; (c) inability to eliminate the risk by the exercise of reasonable care; (d) extent to which the activity is not a matter of common usage; (e) inappropriateness of the activity to the place where it is carried on; and (f) extent to which its value to the community is outweighed by its dangerous attributes.\' (Cornell University, Legal Information Institute, Wex Legal Dictionary, Ultrahazardous Activity, https://www.law.cornell.edu/wex/ultrahazardous_activity -- accessed August 6, 2020)".'),
        'c5source' => $EncryptedNothing
    ),
    1 => array(
        'c5name' => $Zfpf->encrypt_1c('OSHA general duty'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('Occupational Safety and Health Act of 1970, Section 5, Public Law 91–596, December 29, 1970, 84 Statutes 1593, 29 U.S. Code 654'),
        'c6quote' => $Zfpf->encrypt_1c('Duties. (a) Each employer -- (1) shall furnish to each of his employees employment and a place of employment which are free from recognized hazards that are causing or are likely to cause death or serious physical harm to his employees; (2) shall comply with occupational safety and health standards promulgated under this chapter. (b) Each employee shall comply with occupational safety and health standards and all rules, regulations, and orders issued pursuant to this chapter which are applicable to his own actions and conduct.'),
        'c5source' => $Zfpf->encrypt_1c('United States Code, 2011 Edition, https://www.govinfo.gov/content/pkg/USCODE-2011-title29/html/USCODE-2011-title29-chap15-sec654.htm and https://www.govinfo.gov/content/pkg/STATUTE-84/pdf/STATUTE-84-Pg1590.pdf and also https://www.osha.gov/laws-regs/oshact/completeoshact -- all three accessed August 6, 2020.')
    ),
    2 => array(
        'c5name' => $Zfpf->encrypt_1c('EPA general duty'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('Clean Air Act Amendments of 1990, which promulgated paragraph 112(r)(1) of the Clean Air Act, 42 U.S. Code 7412(r)(1)'),
        'c6quote' => $Zfpf->encrypt_1c('(r) Prevention of accidental releases. (1) Purpose and general duty. It shall be the objective of the regulations and programs authorized under this subsection to prevent the accidental release and to minimize the consequences of any such release of any substance listed pursuant to paragraph (3) or any other extremely hazardous substance. The owners and operators of stationary sources producing, processing, handling or storing such substances have a general duty in the same manner and to the same extent as section 654 of title 29 [OSHA general duty] to identify hazards which may result from such releases using appropriate hazard assessment techniques, to design and maintain a safe facility taking such steps as are necessary to prevent releases, and to minimize the consequences of accidental releases which do occur. For purposes of this paragraph, the provisions of section 7604 of this title [citizen suits] shall not be available to any person or otherwise be construed to be applicable to this paragraph. Nothing in this section shall be interpreted, construed, implied or applied to create any liability or basis for suit for compensation for bodily injury or any other injury or property damages to any person which may result from accidental releases of such substances.'),
        'c5source' => $Zfpf->encrypt_1c('United States Code, 2011 Edition, https://www.govinfo.gov/content/pkg/USCODE-2011-title42/html/USCODE-2011-title42-chap85-subchapI-partA-sec7412.htm -- accessed August 6, 2020')
    ),
    3 => array(
        'c5name' => $Zfpf->encrypt_1c('Building codes and industry standards'),
        'c5superseded' => $EncryptedNothing,
        'c5citation' => $Zfpf->encrypt_1c('State and local laws and regulations in the applicable jurisdiction.'),
        'c6quote' => $Zfpf->encrypt_1c('See the lists of codes and standards in the template hazard review(s) that may be provided with this PSM-CAP App. Select "General Duty..." then "Hazard review"'),
        'c5source' => $EncryptedNothing
    )
);
foreach ($general_duty_fragments as $K => $V) {
    $V['k0fragment'] = $K + 2000; // SPECIAL CASE. General duty fragments may use 2000 to 2999 here. Keys less than 100000 are reserved for templates.
    $general_duty_fragments[$K]['k0fragment'] = $V['k0fragment'];
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0fragment', $V);
}

// t0fragment_division inserts.
// Each general-duty division maps to all general-duty fragments.
$Number = 100000; // t0fragment_division:c5number only needs to be unique with a division method (and not between them).
foreach ($divisions as $VD)
    foreach ($general_duty_fragments as $VF) {
        $fragment_division[] = array(
                'k0fragment' => $VF['k0fragment'],
                'k0division' => $VD['k0division'],
                'c5number' => $Zfpf->encrypt_1c($Number)
        );
        $Number = $Number + 100; // Leave room for inserting new entries between existing ones.
    }
// Keys less than 100000 are reserved for templates.
$PrimaryKey = get_highest_in_table($Zfpf, $DBMSresource, 'k0fragment_division', 't0fragment_division'); // Defined in setup_nh3r_practices.php
foreach ($fragment_division as $V) {
    $V['k0fragment_division'] = $PrimaryKey;
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0fragment_division', $V);
}

// TO DO 
// TO DO practice_division > must include the nh3r_hspswp_ep_usa practices
// TO DO populate $practices -- use method from includes/division011.php, Line ~109 to 170.

// t0fragment_practice inserts.
// Each general-duty fragment maps to all general-duty practices
foreach ($general_duty_fragments as $VF) 
    foreach ($practices as $VP)
        $fragment_practice[] = array(
            'k0fragment' => $VF['k0fragment'],
            'k0practice' => $VP['k0practice']
        );
// Keys less than 100000 are reserved for templates.
$PrimaryKey = get_highest_in_table($Zfpf, $DBMSresource, 'k0fragment_practice', 't0fragment_practice'); // Defined in setup_nh3r_practices.php
foreach ($fragment_practice as $V) {
    $V['k0fragment_practice'] = ++$PrimaryKey;
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0fragment_practice', $V);
}


