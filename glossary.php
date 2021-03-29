<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;

/*

SAMPLE $FixedLeftContents ENTRY:
|<a class="toc" href="#foo_bar">FOO_BAR</a><br />       // </p> instead of <br /> on last line.

SAMPLE GLOSSARY ENTRY:
<a id="foo_bar"></a><p>
<b>FOO_BAR</b> means [insert definition of FOO_BAR]</p>

SAMPLE LINKS TO A GLOSSARY ENTRY HTML FILE OUTPUT BY APP:
<a class="toc" href="glossary.php#foo_bar" target="_blank">FOO_BAR</a>  // Blue but no underline hyperlink format (or however class="toc" is defined in your cascading style sheets, css).

*/

$FixedLeftContents = '<p>
|<a class="toc" href="#glossary">Glossary</a><br /><br />
|<a class="toc" href="#actions">Actions</a><br /> 
|<a class="toc" href="#cap">CAP</a><br />
|<a class="toc" href="#catastrophic_release">Catastrophic release</a><br />
|<a class="toc" href="#practices">Compliance practices</a><br />
|<a class="toc" href="#epa">EPA</a><br />
|<a class="toc" href="#contractor">Contractor</a><br />
|<a class="toc" href="#facility">Facility</a><br />
|<a class="toc" href="#hira">HIRA</a><br />
|<a class="toc" href="#lepc">LEPC</a><br />
|<a class="toc" href="#note_fix">inspect/note/fix</a><br />
|<a class="toc" href="#obstopic">Observation...</a><br />
|<a class="toc" href="#owner_operator">Owner/Operator</a><br />
|<a class="toc" href="#osha">OSHA</a><br />
|<a class="toc" href="#pha">PHA</a><br />
|<a class="toc" href="#process">Process</a><br />
|<a class="toc" href="#p3pp">Program 3 Prevention Program</a><br />
|<a class="toc" href="#psm">PSM</a><br />
|<a class="toc" href="#rule">Rule</a><br />
|<a class="toc" href="#fragment">Rule fragment</a><br />
|<a class="toc" href="#union">Union</a></p>';

echo $Zfpf->xhtml_contents_header_1c('Glossary', FALSE, $FixedLeftContents).'
<a id="glossary"></a>
<h1>
Glossary</h1>

<p>
This glossary describes how words and acronyms are used in this <a class="toc" href="#psm">PSM</a>-<a class="toc" href="#cap">CAP</a> App, which is not necessarily how they are used by regulatory agencies or others.</p>

<a id="actions"></a><p>
<b>Actions</b>, in the context of this app\'s action register, are the tasks done to resolve findings and recommendations, including from audits, investigations, <a class="toc" href="#pha">PHA</a> or other <a class="toc" href="#hira">HIRA</a>, and so forth. This app allows tracking these through its action register. Any deficiency or recommendation that can be resolved by completing an open action, already in this app\'s action register, should reference that open action; otherwise, a proposed action should be drafted. See the <a class="toc" href="ar_i0m.php">action register</a> for details.</p>

<a id="cap"></a><p>
<b>CAP</b> means chemical-accident prevention in general, and for facilities located in the USA, the <a class="toc" href="#epa">EPA</a> regulations at 40 CFR 68, which may require a Risk Management Plan (RMP) and are sometimes referred to as the EPA\'s Risk Management regulations.</p>

<a id="catastrophic_release"></a><p>
<b>Catastrophic release</b> means a major uncontrolled emission, fire, or explosion, involving a hazardous substance that presents "serious danger" or "imminent and substantial endangerment" to employees, public health, or the environment. This combines, and includes all circumstances described in, the <a class="toc" href="#osha">OSHA</a> and <a class="toc" href="#epa">EPA</a> definitions at 29 CFR 1910.119(b) and 40 CFR 68.3.</p>

<a id="practices"></a><p>
<b>Compliance practices</b> are what owners, employees, or contractors do to follow applicable laws and standards. They include what\'s done to design, build, operate, and maintain <a class="toc" href="#process">processes</a> and <a class="toc" href="#facility">facilities</a> as well as to document that all this is done properly. They include hazard identification, risk analysis, operating procedures, training, inspection, testing, and maintenance as well as scheduling and documenting these, and so forth. They may be called practices, for short.</p>

<a id="contractor"></a><p>
<b>Contractor</b> means, depending on the context, either or both: the organization that entered into a contract with the <a class="toc" href="#owner_operator">Owner/Operator</a> to provide work or services as an independent contractor <b>and/or</b> an individual human whose work or services are compensated by this contracting organization, either directly or through subcontracting organizations (subcontractors). Sometimes the terms "<b>contractor organization</b>" and "<b>contractor individual</b>" may be used to distinguish between these two meanings.</p>

<a id="epa"></a><p>
<b>EPA</b> means the United States Environmental Protection Agency.</p>

<a id="facility"></a><p>
<b>Facility</b> means a place -- such as a manufacturing plant, warehouse, tank and transfer site, and so forth -- with one or more processes that use highly-hazardous chemical(s). Its meaning here includes both the <a class="toc" href="#osha">OSHA</a> definition of "facility" and the <a class="toc" href="#epa">EPA</a> definition of "stationary source", which are copied below.<br />
- OSHA: Facility means the buildings, containers or equipment which contain a process. 29 CFR 1910.119(b)<br />
- EPA: Stationary source means any buildings, structures, equipment, installations, or substance emitting stationary activities which belong to the same industrial group, which are located on one or more contiguous properties, which are under the control of the same person (or persons under common control), and from which an accidental release may occur. The term stationary source does not apply to transportation, including storage incident to transportation, of any regulated substance or any other extremely hazardous substance under the provisions of this part. A stationary source includes transportation containers used for storage not incident to transportation and transportation containers connected to equipment at a stationary source for loading or unloading. Transportation includes, but is not limited to, transportation subject to oversight or regulation under 49 CFR parts 192, 193, or 195, or a state natural gas or hazardous liquid program for which the state has in effect a certification to DOT under 49 U.S.C. section 60105. A stationary source does not include naturally occurring hydrocarbon reservoirs. Properties shall not be considered contiguous solely because of a railroad or pipeline right-of-way. 40 CFR 68.3</p>

<a id="hira"></a><p>
<b>HIRA</b> means hazard identification and risk analysis. Example HIRA methods include checklists, what-if analysis, what-if/checklist analysis, hazard and operability analysis (HazOp), failure modes and effects analysis (FMEA), failure modes, effects, and criticality analysis (FMECA), layer of protection analysis (LOPA), event trees, and fault trees.</p>

<a id="lepc"></a><p>
<b>LEPC</b> means the Local Emergency Planning Committee or another community emergency-planning organization. The LEPC information should describe the most local emergency-planning organization whose authority is respected by most members of the community where the facility is located.</p>

<a id="note_fix"></a><p>
<b>inspect/note/fix</b> means 
(1) check for and resolve access or egress problems, hazards, or inadequate lighting in areas where the inspection will be done, 
(2) complete lockout-tagout and other safe-work practices, as needed for the inspection, 
(3) inspect using sight, hearing, touch, or smell and, if needed, common tools, such as belt-tension gauges, flashlights, magnifying glasses, or mirrors, 
(4) record the status, especially any problems and at least "Ok" if no problems found, but 
(5) only proceed with a fix if qualified and authorized by the responsible individual(s), per the Owner/Operator management system.</p>

<a id="obstopic"></a><p>
<b>Observation topics</b> are sets of <b>sample observation methods</b>, grouped by convenience for making the observations. The PSM-CAP App records each <b>observation result</b> paired with its (1) observation topic, (2) <b>specific-observation-topic unique identifier (topic ID)</b>, (3) sample observation method, and (4) <b>as-done observation method</b>. For example, an observation topic might be "compressors", with "Compressor RC1" as the topic ID. When making an observation, be guided by the sample observation method but record the as-done observation method.</p>

<a id="osha"></a><p>
<b>OSHA</b> means the Occupational Safety and Health Administration of the United States Department of Labor.</p>

<a id="owner_operator"></a><p>
<b>Owner/Operator</b> means the owner or the operator of a process that contains one or more highly-hazardous chemical(s), whichever has responsibility, or both if they share responsibility. And, it means both the <b>"employer"</b> as used in the <a class="toc" href="#osha">OSHA</a> <a class="toc" href="#psm">PSM</a> standard and the <b>"owner or operator"</b> as used in the Clean Air Act, the legislative authority of the <a class="toc" href="#cap">CAP</a> regulations administered by the <a class="toc" href="#epa">EPA</a>. The PSM-CAP App uses "owner" as shorthand for Owner/Operator.</p>

<a id="pha"></a><p>
<b>PHA</b> means process-hazard analysis, a type of <a class="toc" href="#hira">HIRA</a>, which in the USA has a specific meaning under the <a class="toc" href="#psm">PSM</a> and <a class="toc" href="#cap">CAP</a> rules.</p>

<a id="process"></a><p>
<b>Process</b> means any activity involving a highly hazardous chemical (or regulated substance) including any use, storage, manufacturing, handling, or the on-site movement of such chemicals, or combination of these activities. For purposes of this definition, any group of vessels which are interconnected and separate vessels which are located such that a highly hazardous chemical could be involved in a potential release shall be considered a single process. See guidance on co-location for determining if the substances in two nearby processes should be counted together for comparison to threshold quantities.</p>

<a id="p3pp"></a><p>
<b>Program 3 Prevention Program</b> means the rules, similar to <a class="toc" href="#psm">PSM</a>, at 40 CFR 68, Subpart D, which is a portion of the <a class="toc" href="#cap">CAP</a> rule administered by the <a class="toc" href="#epa">EPA</a>. The elements of the <a class="toc" href="#osha">OSHA</a> PSM standard and the EPA Program 3 Prevention Program are very similar, except for emergency planning and trade secrets. The main differences are that the Program 3 Prevention Program substitutes:<br />
- "owners and operators" for "employers",<br />
- "regulated substance" for "highly hazardous chemical",<br />
- "stationary source" for "facility", and<br />
- in the definition of "catastrophic release", "presents imminent and substantial endangerment to public health and the environment" for "presents serious danger to employees in the workplace";<br />
Also, compared to the OSHA PSM standard, the Program 3 Prevention Program doesn\'t have:<br />
- the phrase "in the workplace" or "on employees in the workplace" in the PHA content and incident investigation applicability requirements and<br />
- any injury and illness log requirement for contractors resembling 29 CFR 1910.119(h)(2)(vi).</p><p>

A cross reference is provided in the table below.</p>

<table border="1" cellspacing="" cellpadding="3">
    <tr>
        <th>PSM Element</th>
        <th>OSHA Citation</th>
        <th>EPA Citation</th>
    </tr>
    <tr>
        <td>Employee Participation</td>
        <td>29 CFR 1910.119(c)</td>
        <td>40 CFR 68.83</td>
    </tr>
    <tr>
        <td>Process Safety Information (PSI)</td>
        <td>29 CFR 1910.119(d)</td>
        <td>40 CFR 68.65</td>
    </tr>
    <tr>
        <td>Process Hazard Analysis (PHA)</td>
        <td>29 CFR 1910.119(e)</td>
        <td>40 CFR 68.67</td>
    </tr>
    <tr>
        <td>Operating Procedures</td>
        <td>29 CFR 1910.119(f)</td>
        <td>40 CFR 68.69</td>
    </tr>
    <tr>
        <td>Operator Training</td>
        <td>29 CFR 1910.119(g)</td>
        <td>40 CFR 68.71</td>
    </tr>
    <tr>
        <td>Contractors</td>
        <td>29 CFR 1910.119(h)</td>
        <td>40 CFR 68.87</td>
    </tr>
    <tr>
        <td>Pre-Startup Safety Review (PSR)</td>
        <td>29 CFR 1910.119(i)</td>
        <td>40 CFR 68.77</td>
    </tr>
    <tr>
        <td>Mechanical Integrity</td>
        <td>29 CFR 1910.119(j)</td>
        <td>40 CFR 68.73</td>
    </tr>
    <tr>
        <td>Hot Work Permit</td>
        <td>29 CFR 1910.119(k)</td>
        <td>40 CFR 68.85</td>
    </tr>
    <tr>
        <td>Management of Change (MOC)</td>
        <td>29 CFR 1910.119(l)</td>
        <td>40 CFR 68.75</td>
    </tr>
    <tr>
        <td>Incident Investigation</td>
        <td>29 CFR 1910.119(m)</td>
        <td>40 CFR 68.81</td>
    </tr>
    <tr>
        <td>Emergency Planning and Response</td>
        <td>29 CFR 1910.119(n)</td>
        <td>40 CFR 68, Subpart E</td>
    </tr>
    <tr>
        <td>Compliance Audit</td>
        <td>29 CFR 1910.119(o)</td>
        <td>40 CFR 68.79</td>
    </tr>
    <tr>
        <td>Trade Secrets</td>
        <td>29 CFR 1910.119(p)</td>
        <td>40 CFR 68.151 and 68.152</td>
    </tr>
</table> 

<p></p>

<a id="psm"></a><p>
<b>PSM</b> means process-safety management in general, and for facilities located in the USA, the <a class="toc" href="#osha">OSHA</a> regulations at 29 CFR 1910.119.</p>

<a id="rule"></a><p>
<b>Rule</b> means broadly any public collection of requirements. Laws, regulations, industry standards, and standard methods are all rules. Rules are typically organized using a division method, such as the dozen or so elements of PSM.</p>

<a id="fragment"></a><p>
<b>Rule fragment</b> means a small part of a rule. The PSM-CAP App attempts to split rules into fragments such that each fragment of text describes one requirement. This is difficult and sometimes a bit arbitrary. A fragment might correspond to a paragraph, a sub-paragraph, or less. Fragments are typically much smaller than the primary divisions of a rule, such as its chapters or sections. The PSM-CAP App maps rule fragments to compliance practices.</p>

<a id="union"></a><p>
<b>Union</b> means an organization that represents employees in a way that requires consultation between the union and the Owner/Operator under the PSM-CAP Employee Participation rules. The Employee Participation rules do not require consultation with unions that represent contractor individuals, unless they are employees of a contractor organization that is the operator of the facility.</p>

'.$Zfpf->xhtml_footer_1c();

