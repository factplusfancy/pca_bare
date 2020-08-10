<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This file echos the hazardous substance (hs) safety notice

// Check for user pointing their browser at this php file without permission or tampering.
if (!isset($_SESSION['Scratch']['PlainText']['SecurityToken']) or $_SESSION['Scratch']['PlainText']['SecurityToken'] != 'hs_safety_notice_o1.php')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

if (!isset($_SESSION['StatePicked']['t0facility']))
    $HTML = $Zfpf->xhtml_contents_header_1c().'<h2>
    No facility selected</h2><p>
    To generate the '.HAZSUB_SAFETY_NOTICE_NAME_ZFPF.', a facility needs to be selected. If you are associated with one or more facilities, you may do this from the left-hand contents, on this page.</p>';
else {
    $HTML = $Zfpf->xhtml_contents_header_1c('Safety Notice', FALSE, FALSE).'<h1>
    '.HAZSUB_SAFETY_NOTICE_NAME_ZFPF.'</h1>';
    $Street2 = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5street2']);
    $Description = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c6description']);
    $HTML .= '<p>
    <i>Facility and owner:</i><br/>
    '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0owner']['c5name']);
    if ($Description != '[Nothing has been recorded in this field.]')
        $HTML .= ', '.$Description;
    $HTML .= ', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5street1']);
    if ($Street2 != '[Nothing has been recorded in this field.]')
        $HTML .= ', '.$Street2;
    $HTML .= ', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5country']).'</p>';
    $HTML .= '<p>

    The '.HAZSUB_PROCESS_NAME_ZFPF.' at this facility contains '.HAZSUB_NAME_ADJECTIVE_ZFPF.' and other materials, often at high pressures and temperatures.'.HAZSUB_DESCRIPTION_ZFPF.'</p><p>

    <b>'.HAZSUB_LEAK_FIRST_STEPS_ZFPF.'</b></p><p>
    
    The following requirements apply to the '.HAZSUB_PROCESS_NAME_ZFPF.' at this facility:<br />
        1. General duty reqiurements under the U.S. Clean Air Act, Occupational Safety and Health Act, and, typically, state law.<br />
        2. Chemical Accident Prevention/Risk Management Plan. Administered by the U.S. Environmental Protection Agency (EPA) and designed to reduce risks to the community near this facility.<br />
        3. Process Safety Management (PSM). Administered by the Occupational Safety and Health Administration (OSHA) and designed to reduce risks to employees at this facility.</p><p>

    We implement a process safety program to comply with the above rules. You are entitled to review most information about process safety where you work. Contact your supervisor, or your organization\'s safety personnel, for more information or to provide feedback. The '.HAZSUB_NAME_ADJECTIVE_ZFPF.' safety data sheet (SDS) and this facility\'s emergency plans also have more information.</p><p>

    Our process safety program includes the following elements:</p><ul class="indent"><li>

    <b>Management system</b> -- assigning overall responsibility and lines of authority to others with specific responsibilities.</li><li>

    <b>Employee participation</b> -- consulting with employees on '.HAZSUB_NAME_ADJECTIVE_ZFPF.' safety.</li><li>

    <b>Process-safety information</b> -- drawings, manuals, and other documents covering design, materials, fabrication, construction, and installation of the '.HAZSUB_PROCESS_NAME_ZFPF.'.</li><li>

    <b>Process-hazard analysis or Hazard Review</b> -- a detailed review of potential failures, existing safeguards, and recommendations.</li><li>

    <b>Hazardous-substance procedures and safe-work practices</b> -- these cover '.HAZSUB_NAME_ADJECTIVE_ZFPF.' tasks where a mistake could promptly cause harm or unacceptable risks, such as monitoring, corrective actions, adding/removing materials to/from the '.HAZSUB_PROCESS_NAME_ZFPF.', and also removing hazardous energy from some or all of it, piping opening, and returning piping to service. As a rule of thumb, these more-hazardous tasks include operating a valve in '.HAZSUB_NAME_ADJECTIVE_ZFPF.' service, by any method.</li><li>

    <b>Hot-work permit</b> -- a permit required prior to any welding, cutting, drilling, grinding, and so forth.</li><li>

    <b>Training</b> -- training for employees who operate and maintain the '.HAZSUB_PROCESS_NAME_ZFPF.'.</li><li>

    <b>Contractors</b> -- pre-screening and monitoring of contractors who work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.'.</li><li>

    <b>Inspection, testing, and maintenance (ITM) for safe operation and mechanical integrity</b>.</li><li>

    <b>Change management</b> -- careful planning, review, and approval of '.HAZSUB_PROCESS_NAME_ZFPF.' changes by management.</li><li>

    <b>Incident investigation</b> -- procedures for investigating both near misses and incidents (leaks and so forth.)</li><li>

    <b>Audits</b> -- audits of our process-safety program every three years (five years for general-duty only facilities).</li><li>

    <b>Emergency planning</b> -- procedures and training on '.HAZSUB_NAME_ADJECTIVE_ZFPF.' recognition, notification, move-to-safety, and evacuation or shelter-in-place and also coordination with outside responders, leak mitigation and emergency shutdown, and other actions by qualified personnel.</li><li>

    <b>Offsite hazard assessment</b> -- assessing risks to the community and environment around this facility and coordinating with offsite responders and governmental authorities.</li></ul><p>

    Please let us know if you have any questions or comments on '.HAZSUB_NAME_ADJECTIVE_ZFPF.' safety.</p>';
}
echo $HTML.'<p>
    <a class="toc" href="contents0_s_practice.php">Go back</a></p>
    '.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

