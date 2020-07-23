<?php
// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// PHA templates -- text for introductory sections of PHA reports.

$pha = array(
    0 => array(
        'k0pha' => 1, // Template PHA keys must be less than 100000, see .../includes/pha_i1m.php
        'c6bfn_act_notice' => $EncryptedNothing,
        'c6team_qualifications' => $Zfpf->encrypt_1c('[consulting firm name] assisted [client name] with completing this [year] process-hazard analysis (PHA). The PHA was led by [name, title, company] [describe leader\'s experience with PHA method and process type] [List qualifications of other PHA team members, such as design engineer, maintenance supervisor, mechanics, operators, health and safety manager, etc.]'),
        'c6bfn_attendance' => $EncryptedNothing, // Set as EncryptedNothing in setup.php
        'c6background' => $Zfpf->encrypt_1c('[type of process, quantity of chemical(s) it holds, rules that therefore require a PHA] [If applicable, provide history of past PHAs and PHA updates, revisions, or amendments.] [List dates of team meetings for latest PHA effort.]'),
        'c6method' => $Zfpf->encrypt_1c('The [year] PHA team used the [name and describe method, for example] what-if/checklist method. This is a hazard identification and risk analysis (HIRA) method intended to identify hazards and existing safeguards, provide a qualitative risk assessment for each hazard, and make recommendations, and so satisfy the PHA requirements of the PSM and CAP rules applicable in the United States of America.

Prior to staring the PHA, the team leader verified that the process-safety information, possibly augmented by the PHA team\'s ability to observe the as-built process, was adequate to complete the PHA. 
        
During the PHA meetings, common as well as site-specific what-if questions (scenarios) were asked. Then, for each scenario, the PHA team identified or reviewed potential causes, previous incidents, consequences, planned engineering or administrative safeguards, severity, and likelihood (of resulting in the agreed severity). The team also had the opportunity to identify recommendations for each scenario and to describe additional scenarios or prior incidents for the PHA team to consider.

Site and floor plans, the [process name] piping and instrumentation diagram, and other process-safety information were reviewed during the PHA meetings as needed to evaluate scenarios. The PHA was projected from a computer onto a screen during the PHA meetings, allowing the team members to review the PHA as the leader was typing the team\'s comments and recommendations into the PHA. A draft copy of the [year] PHA was also circulated to team members from [date] to [date]. 

Risks for each scenario were assigned using the Risk Ranking Matrix linked below.

[OPTIONAL FOR ADDITIONS OR NEW PLANTS] On [dates], prior to the preparation of a detailed design, pre-PHA meetings were held to assign responsibilities for completing the process safety information and to look for any needed engineering controls that could be implemented more easily if identified before the detailed design. These meetings included [names, titles, organizations]. A draft PHA based on the this effort was circulated to the meeting participants as well as [NAMES, TITLES, AND COMPANIES]'),
        'c6prior_incident_id' => $Zfpf->encrypt_1c('Many of the scenarios in the PHA are based on prior incidents or known failure mechanisms. In cases where a specific prior incident was discussed in detail by the PHA team, this may be summarized in the applicable scenario. Each scenario includes an optional scenario type field, which may be used to identify scenarios that were based on or inspired by previous incidents.'),
        'c6bfn_pha_as_issued' => $EncryptedNothing,
        'k0user_of_leader' => 0,
        'c5ts_leader' => $EncryptedNothing,
        'c6nymd_leader' => $Zfpf->encrypt_1c('Template ammonia-refrigeration what-if/checklist PHA 2019-06-05') // Put template name here. Never overwritten because templates are never issued. (They may be edited)
    )
    // Additional templates may be added here, and you would also need to make the template subsystems, scenarios, junction tables, causes, etc.
);
foreach ($pha as $K => $V) {
    $V['k0process'] = 0; // For all templates, this gets assigned when template is deployed.
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0pha', $V);
}

