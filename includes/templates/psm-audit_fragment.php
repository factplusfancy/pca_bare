<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/psm_fragments.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/templates/cap_fragments.php';
$PSMAuditFragment = array( // This array links to all the fragments asociated with the Cheesehead Division (skips intro, definitions, etc.)
    1 => array(
        'k0fragment' => $psm_fragments[14]['k0fragment'] // Plan of Action for Employee Participation
    ),
    2 => array(
        'k0fragment' => $psm_fragments[15]['k0fragment'] // Consultation with Employees
    ),
    3 => array(
        'k0fragment' => $psm_fragments[16]['k0fragment'] // Information Access
    ),
    4 => array(
        'k0fragment' => $psm_fragments[17]['k0fragment'] // Timing (before PHA or HIRA) and Purpose
    ),
    5 => array(
        'k0fragment' => $psm_fragments[18]['k0fragment'] // Process-Materials Properties and Hazards
    ),
    6 => array(
        'k0fragment' => $psm_fragments[20]['k0fragment'] // Flow Diagram -- skipped 19 intro
    ),
    7 => array(
        'k0fragment' => $psm_fragments[21]['k0fragment'] // Process Chemistry
    ),
    8 => array(
        'k0fragment' => $psm_fragments[22]['k0fragment'] // Maximum Intended Inventory
    ),
    9 => array(
        'k0fragment' => $psm_fragments[23]['k0fragment'] // Operating Limits
    ),
    10 => array(
        'k0fragment' => $psm_fragments[24]['k0fragment'] // Consequences of Deviations
    ),
    11 => array(
        'k0fragment' => $psm_fragments[25]['k0fragment'] // Original Information Missing
    ),
    12 => array(
        'k0fragment' => $psm_fragments[27]['k0fragment'] // Materials of Construction -- skipped 26 intro
    ),
    13 => array(
        'k0fragment' => $psm_fragments[28]['k0fragment'] // P&ID
    ),
    14 => array(
        'k0fragment' => $psm_fragments[29]['k0fragment'] // Electrical Classifications
    ),
    15 => array(
        'k0fragment' => $psm_fragments[30]['k0fragment'] // Relief Systems
    ),
    16 => array(
        'k0fragment' => $psm_fragments[31]['k0fragment'] // Ventilation Systems
    ),
    17 => array(
        'k0fragment' => $psm_fragments[32]['k0fragment'] // Codes, Standards, etc.
    ),
    18 => array(
        'k0fragment' => $psm_fragments[33]['k0fragment'] // Material and Energy Balances
    ),
    19 => array(
        'k0fragment' => $psm_fragments[34]['k0fragment'] // Other Safety Systems
    ),
    20 => array(
        'k0fragment' => $psm_fragments[35]['k0fragment'] // Good Practices
    ),
    21 => array(
        'k0fragment' => $psm_fragments[36]['k0fragment'] // No Longer in General Use
    ),
    22 => array(
        'k0fragment' => $psm_fragments[37]['k0fragment'] // PHA Required
    ),
    23 => array(
        'k0fragment' => $psm_fragments[38]['k0fragment'] // Method
    ),
    24 => array(
        'k0fragment' => $psm_fragments[39]['k0fragment'] // Content
    ),
    25 => array(
        'k0fragment' => $psm_fragments[40]['k0fragment'] // Team Qualifications
    ),
    26 => array(
        'k0fragment' => $psm_fragments[41]['k0fragment'] // PHA Resolution
    ),
    27 => array(
        'k0fragment' => $psm_fragments[42]['k0fragment'] // Update and Revalidate
    ),
    28 => array(
        'k0fragment' => $psm_fragments[43]['k0fragment'] // Retention
    ),
    29 => array(
        'k0fragment' => $psm_fragments[44]['k0fragment'] // [Operating Procedures] Purpose
    ),
    30 => array(
        'k0fragment' => $psm_fragments[46]['k0fragment'] // Initial Startup [skipped 45, Operating phases intro]
    ),
    31 => array(
        'k0fragment' => $psm_fragments[47]['k0fragment'] // Normal Operations
    ),
    32 => array(
        'k0fragment' => $psm_fragments[48]['k0fragment'] // Temporary Operations
    ),
    33 => array(
        'k0fragment' => $psm_fragments[49]['k0fragment'] // Emergency Shutdown
    ),
    34 => array(
        'k0fragment' => $psm_fragments[50]['k0fragment'] // Emergency Operations
    ),
    35 => array(
        'k0fragment' => $psm_fragments[51]['k0fragment'] // Normal Shutdown
    ),
    36 => array(
        'k0fragment' => $psm_fragments[52]['k0fragment'] // Startup After Unusual Events
    ),
    37 => array(
        'k0fragment' => $psm_fragments[53]['k0fragment'] // Operating Limits, Deviation Consequences, and Corrective Actions
    ),
    38 => array(
        'k0fragment' => $psm_fragments[54]['k0fragment'] // Safety and Health
    ),
    39 => array(
        'k0fragment' => $psm_fragments[55]['k0fragment'] // Process-Materials Properties and Hazards
    ),
    40 => array(
        'k0fragment' => $psm_fragments[56]['k0fragment'] // Exposure Prevention
    ),
    41 => array(
        'k0fragment' => $psm_fragments[57]['k0fragment'] // First Aid
    ),
    42 => array(
        'k0fragment' => $psm_fragments[58]['k0fragment'] // Raw-Materials Quality Control
    ),
    43 => array(
        'k0fragment' => $psm_fragments[59]['k0fragment'] // Hazardous-Materials Inventory Control
    ),
    44 => array(
        'k0fragment' => $psm_fragments[60]['k0fragment'] // Special or Unique Hazards
    ),
    45 => array(
        'k0fragment' => $psm_fragments[61]['k0fragment'] // Safety Systems
    ),
    46 => array(
        'k0fragment' => $psm_fragments[62]['k0fragment'] // Access to Procedures
    ),
    47 => array(
        'k0fragment' => $psm_fragments[63]['k0fragment'] // Always Up-to-date
    ),
    48 => array(
        'k0fragment' => $psm_fragments[64]['k0fragment'] // Annual Certification
    ),
    49 => array(
        'k0fragment' => $psm_fragments[65]['k0fragment'] // Safe Work Practices and Access Control
    ),
    50 => array(
        'k0fragment' => $psm_fragments[66]['k0fragment'] // Initial Training
    ),
    51 => array(
        'k0fragment' => $psm_fragments[68]['k0fragment'] // Refresher Training [skipped 67, Started Before May 26, 1992 Exemption]
    ),
    52 => array(
        'k0fragment' => $psm_fragments[69]['k0fragment'] // Refresher Training Consultation
    ),
    53 => array(
        'k0fragment' => $psm_fragments[70]['k0fragment'] // Comprehension Verified and Documented
    ),
    54 => array(
        'k0fragment' => $psm_fragments[71]['k0fragment'] // Contractors Applicability
    ),
    55 => array(
        'k0fragment' => $psm_fragments[73]['k0fragment'] // Qualify [Contractors. Skipped 72, heading.]
    ),
    56 => array(
        'k0fragment' => $psm_fragments[74]['k0fragment'] // Hazards Notification
    ),
    57 => array(
        'k0fragment' => $psm_fragments[75]['k0fragment'] // Emergency-plans Briefing
    ),
    58 => array(
        'k0fragment' => $psm_fragments[76]['k0fragment'] // Safe Work Practices and Access Control
    ),
    59 => array(
        'k0fragment' => $psm_fragments[77]['k0fragment'] // Evaluate contractor performance
    ),
    60 => array(
        'k0fragment' => $psm_fragments[78]['k0fragment'] // Injury and Illness Log
    ),
    61 => array(
        'k0fragment' => $psm_fragments[80]['k0fragment'] // Contractor's Work-Practice Training [Skipped 79, heading.]
    ),
    62 => array(
        'k0fragment' => $psm_fragments[81]['k0fragment'] // Contractor's Hazards and Emergencies Training
    ),
    63 => array(
        'k0fragment' => $psm_fragments[82]['k0fragment'] // Contractor's Comprehension Verified and Documented
    ),
    64 => array(
        'k0fragment' => $psm_fragments[83]['k0fragment'] // Contractor's Safety Enforcement
    ),
    65 => array(
        'k0fragment' => $psm_fragments[84]['k0fragment'] // Contractor's Hazards Created or Discovered by the Work
    ),
    66 => array(
        'k0fragment' => $psm_fragments[85]['k0fragment'] // PSR Applicability
    ),
    67 => array(
        'k0fragment' => $psm_fragments[86]['k0fragment'] // PSR Requirements
    ),
    68 => array(
        'k0fragment' => $psm_fragments[87]['k0fragment'] // Applicability, Mechanical Integrity
    ),
    69 => array(
        'k0fragment' => $psm_fragments[88]['k0fragment'] // Maintenance Procedures
    ),
    70 => array(
        'k0fragment' => $psm_fragments[89]['k0fragment'] // Maintenance Training
    ),
    71 => array(
        'k0fragment' => $psm_fragments[90]['k0fragment'] // Inspection and Testing [91 to 94 combined with 90 in templates/psm_fragments.php].
    ),
    72 => array(
        'k0fragment' => $psm_fragments[95]['k0fragment'] // ITM Resolution
    ),
    73 => array(
        'k0fragment' => $psm_fragments[97]['k0fragment'] // Design and Installation Good Practices [skipped 96, heading "Quality Assurance"]
    ),
    74 => array(
        'k0fragment' => $psm_fragments[98]['k0fragment'] // Replacement-in-kind Quality Assurance
    ),
    75 => array(
        'k0fragment' => $psm_fragments[99]['k0fragment'] // Hot Work Permit
    ),
    76 => array(
        'k0fragment' => $psm_fragments[100]['k0fragment'] // MOC Applicability
    ),
    77 => array(
        'k0fragment' => $psm_fragments[101]['k0fragment'] // MOC Procedural Requirements
    ),
    78 => array(
        'k0fragment' => $psm_fragments[102]['k0fragment'] // Pre-Startup Training
    ),
    79 => array(
        'k0fragment' => $psm_fragments[103]['k0fragment'] // PSI Update
    ),
    80 => array(
        'k0fragment' => $psm_fragments[104]['k0fragment'] // Procedures Update
    ),
    81 => array(
        'k0fragment' => $psm_fragments[105]['k0fragment'] // Applicability, Incident Investigations
    ),
    82 => array(
        'k0fragment' => $psm_fragments[106]['k0fragment'] // 48-Hour Start Time
    ),
    83 => array(
        'k0fragment' => $psm_fragments[107]['k0fragment'] // Team Qualifications
    ),
    84 => array(
        'k0fragment' => $psm_fragments[108]['k0fragment'] // Report Content
    ),
    85 => array(
        'k0fragment' => $psm_fragments[109]['k0fragment'] // Resolution, Incident Investigations
    ),
    86 => array(
        'k0fragment' => $psm_fragments[110]['k0fragment'] // Employee and Contractor Briefing
    ),
    87 => array(
        'k0fragment' => $psm_fragments[111]['k0fragment'] // Retention
    ),
    88 => array(
        'k0fragment' => $psm_fragments[112]['k0fragment'] // Emergency Action Plan (Alert, Move-to-Safety, Headcount...)
    ),
    89 => array(
        'k0fragment' => $psm_fragments[113]['k0fragment'] // Small Leaks
    ),
    90 => array(
        'k0fragment' => $psm_fragments[114]['k0fragment'] // Emergency Response Option
    ),
    91 => array(
        'k0fragment' => $psm_fragments[115]['k0fragment'] // Certification of "have evaluated... to verify"
    ),
    92 => array(
        'k0fragment' => $psm_fragments[116]['k0fragment'] // Auditor Qualifications
    ),
    93 => array(
        'k0fragment' => $psm_fragments[117]['k0fragment'] // Report
    ),
    94 => array(
        'k0fragment' => $psm_fragments[118]['k0fragment'] // PSM Audit Resolution
    ),
    95 => array(
        'k0fragment' => $psm_fragments[119]['k0fragment'] // Retention
    ),
    96 => array(
        'k0fragment' => $cap_fragments[52]['k0fragment'] // Management System. Optionally included because relevant to PSM implementation.
    ),
    97 => array(
        'k0fragment' => $cap_fragments[190]['k0fragment'] // Recordkeeping. Optionally included because relevant to PSM implementation.
    ),
    98 => array(
        'k0fragment' => $cap_fragments[173]['k0fragment'] // Applicability and Community Emergency-Response Option. Optionally included because OSHA emergency planning included.
    ),
    99 => array(
        'k0fragment' => $cap_fragments[195]['k0fragment'] // Emergency coordination (Owner/Operator with community). Optionally included because OSHA emergency planning included.
    ),
    100 => array(
        'k0fragment' => $cap_fragments[174]['k0fragment'] // Owner/Operator Emergency-Response Option. Optionally included because OSHA emergency planning included.
    ),
    101 => array(
        'k0fragment' => $cap_fragments[196]['k0fragment'] // Emergency response exercises. Optionally included because OSHA emergency planning included.
    )
);
foreach ($PSMAuditFragment as $K => $V) {
    // PSM-audit and hazard-review report template
    // for anyhdrous-ammonia mechanical refrigeration
    $V['k0audit_fragment'] = $K;
    $PSMAuditFragment[$K]['k0audit_fragment'] = $V['k0audit_fragment']; // Used for subsequently required file: psm-audit_f_nh3r_om.php
    $V['k0audit'] = 1; // See includes/template/nh3r_psm-audit_etc.php
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0audit_fragment', $V);
    // Hazard-review and compliance-audit report template, for general duty only or EPA Program 2 Prevention Programs
    // for anyhdrous-ammonia mechanical refrigeration
    if ($K < 22 or $K > 28) { // Exclude the PHA fragments, checklist hazard review is covered with compliance audit. Semi-quantitative not required.
        $V['k0audit_fragment'] = $K + 1000; // Start primary keys for this template at 1001.
        $V['k0audit'] = 2; // See includes/template/nh3r_psm-audit_etc.php
        $Zfpf->insert_sql_1s($DBMSresource, 't0audit_fragment', $V);
    }
}

