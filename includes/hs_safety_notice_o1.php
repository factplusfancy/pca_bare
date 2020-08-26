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
        (1) General duty under the U.S. Clean Air Act, the U.S. Occupational Safety and Health Act, and often state law.';
    if ($_SESSION['StatePicked']['t0rule']['k0rule'] != 4)
        $HTML .= '<br />
        (2) Chemical Accident Prevention/Risk Management Plan. Administered by the U.S. Environmental Protection Agency (EPA) and designed to reduce risks to the community near this facility.<br />
        (3) Process Safety Management. Administered by the U.S. Department of Labor, Occupational Safety and Health Administration (OSHA), and designed to reduce risks to employees at this facility.';
    $HTML .= '</p><p>

    We implement a process-safety program to comply with the above rules. You are entitled to review most information about process safety where you work. Contact your supervisor, or your organization\'s safety personnel, for more information or to provide feedback. The '.HAZSUB_NAME_ADJECTIVE_ZFPF.' safety data sheet (SDS) and this facility\'s emergency plans also have more information.</p><p>

    Our process-safety program includes the following:</p><ul class="indent"><li>

    <b>Management system</b> -- assigning overall responsibility and lines of authority to others with specific responsibilities.</li><li>

    <b>Employee participation</b> -- consulting with employees on '.HAZSUB_NAME_ADJECTIVE_ZFPF.' safety.</li><li>

    <b>Process-safety information</b> -- drawings, manuals, and other documents covering design, materials, fabrication, construction, installation, and commissioning of the '.HAZSUB_PROCESS_NAME_ZFPF.'.</li><li>
    
    <b>';
    if ($_SESSION['StatePicked']['t0rule']['k0rule'] == 4)
        $HTML .= 'Hazard review';
    else
        $HTML .= 'Process-hazard analysis';
    $HTML .= '</b> -- a detailed review of potential failures, existing safeguards, and recommendations, updated every five years.</li><li>

    <b>Hazardous-substance procedures and safe-work practices</b> -- these cover '.HAZSUB_NAME_ADJECTIVE_ZFPF.' tasks where a mistake could promptly cause harm or unacceptable risks, such as monitoring, corrective actions, adding/removing materials to/from the '.HAZSUB_PROCESS_NAME_ZFPF.', and also '.HAZSUB_PROCESS_NAME_ZFPF.' opening, after removing hazardous energy, and return-to-service. As a rule of thumb, these more-hazardous tasks include operating a valve in '.HAZSUB_NAME_ADJECTIVE_ZFPF.' service by any method, such as turning a stem, pushing a button, or flipping a switch.</li><li>

    <b>Hot-work permit</b> -- required before doing any brazing, burning, cutting, drilling, grinding, soldering, welding, or other activities that produces sparks or ignition sources.</li><li>

    <b>Training</b> for employees who operate or maintain the '.HAZSUB_PROCESS_NAME_ZFPF.'.</li><li>

    <b>Contractors</b> -- qualifying and monitoring of contractors who work on or adjacent to the '.HAZSUB_PROCESS_NAME_ZFPF.'.</li><li>

    <b>Inspection, testing, and maintenance (ITM)</b> for safe operation and mechanical integrity.</li><li>

    <b>Change management</b> -- careful planning, review, and approval of '.HAZSUB_PROCESS_NAME_ZFPF.' changes.</li><li>

    <b>Incident investigation</b> of near misses and incidents, such as leaks, fires, or explosions.</li><li>

    <b>Audits</b> of our process-safety program every ';
    if ($_SESSION['StatePicked']['t0rule']['k0rule'] == 4)
        $HTML .= 'five years, combined with an update of the hazard review';
    else
        $HTML .= 'three years';
    $HTML .= '.</li><li>

    <b>Emergency planning</b> -- procedures and training on '.HAZSUB_NAME_ADJECTIVE_ZFPF.' leak recognition, notification, move-to-safety, and evacuation or shelter-in-place and also coordination with outside responders, leak mitigation and emergency shutdown, and other actions by qualified personnel.</li><li>

    <b>Offsite-hazard assessment</b> -- assessing risks to the community and environment around this facility and coordinating with offsite responders and governmental authorities.</li></ul><p>

    Please let us know if you have any questions or comments on '.HAZSUB_NAME_ADJECTIVE_ZFPF.' safety.</p>';
}
echo $HTML.'<p>
    <a class="toc" href="contents0_s_practice.php">Go back</a></p>
    '.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

