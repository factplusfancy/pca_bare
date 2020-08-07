<?php

// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// PSM-audit templates -- sample text for introductory sections of PSM-audit reports.

$AmmoniaRefrigerationAudit = array(
    1 => array(
        'k0audit' => 1, // The ammonia-refrigeration (nh3r) audit and hazard review template // Template keys must be less than 100000, see .../includes/audit_i1m.php
        'c5name' => $Zfpf->encrypt_1c('PSM audit and hazard review for anhydrous-ammonia mechanical refrigeration (ammonia refrigeration). 2020-08-07 template.'), // k0process is handled before SQL insert below.
        'c5ts_as_of' => $EncryptedNothing,
        'k0user_of_leader' => 0,
        'c6bfn_act_notice' => $EncryptedNothing,
        'c5ts_leader' => $EncryptedNothing,
        'c6nymd_leader' => $EncryptedNothing,
        'k0user_of_certifier' => 0,
        'c5ts_certifier' => $EncryptedNothing,
        'c6nymd_certifier' => $EncryptedNothing,
        'c6howtoinstructions' => $Zfpf->encrypt_1c('

NOTE. This 2020-08-07 template,
if properly interpreted and applied by the auditor(s), using the terminology of this PSM-CAP App,
provides sample-observation methods that may be used to check an ammonia-refrigeration system for compliance with: 
(1) typical tort law "duty of care", 
(2) the U.S. federal "general duty" laws for hazardous substances, namely Clean Air Act paragraph 112(r)(1) and Occupational Safety and Health Act of 1970, Section 5, Duties, 
(3) the "minimum system safety requirements" described in International Institute of Ammonia Refrigeration (IIAR) standard IIAR 9-2020, which references IIAR 6-2019 and IIAR 7-2019, 
(4) the applicable editions of IIAR 2 and building codes, and 
(5) the U.S. federal PSM compliance audit regulations at 40 CFR 68.79 and 29 CFR 1910.119(o), administered by the EPA and OSHA, respectively. 

RESOLUTION OF AUDIT FINDINGS. Respond to any imminently dangerous situations immediately upon discovery. Otherwise, resolve findings as promptly as practical. The PSM-CAP app records audit findings in its Action Register, so initiate resolution planning by assigning a responsible individual and target-resolution date for each finding, via the Action Register. The PSM final rule preamble indicates, "It is OSHA\'s intention that the actions to be taken as a result of the process hazard analysis recommendations be completed as soon as possible. In most cases, OSHA believes that employers will be able to complete these actions within a one to two year time frame, but notes that in unusual circumstances longer completion periods may be necessary." (57 Federal Register 6379, February 24, 1991) This guidance provides a reasonable time frame for resolving compliance audit findings as well.

AUDIT REPORT RETENTION. The Owner/Operator is required to "retain the two most recent compliance audits."

CERTIFICATION AND NEXT AUDIT. A common interpretation of the PSM rule is that the Owner/Operator is required to certify that it has evaluated compliance at least every three years. If the Owner/Operator does this "as of" a date approximately marking the compliance-evaluation period -- such as when the lead auditor delivered a preliminary spoken or written report to the process PSM leader at the exit meeting, after the onsite audit services -- then complete the next audit onsite services in time to mark a similar compliance-evaluation period within three years. In addition, within a reasonable time, the lead auditor should deliver a final written PSM-audit report to the Owner/Operator, but the final written report issue date does not typically determine the setting or meeting of audit deadlines.'),
        'c6background' => $Zfpf->encrypt_1c('This section provides background on the process and the regulations, codes, and standards (rules) applicable to this audit. 
        
The PSM rules are designed to protect employees by safely managing processes that use highly hazardous chemicals, such as anhydrous ammonia. The EPA\'s Chemical Accident Prevention/Risk Management Plan rules (40 CFR 68) are designed to protect the surrounding community from similar hazards. Refrigeration systems that contain greater than 10,000 pounds of anhydrous ammonia are subject to both OSHA\'s PSM rule and the Program 3 Prevention Program requirements of the EPA\'s Chemical Accident Prevention rule.

Facility records indicated that its mechanical refrigeration system contained approximately [INSERT AMMONIA CHARGE] pounds of anhydrous ammonia.

The elements of OSHA\'s PSM and EPA\'s Program 3 Prevention Program are very similar, except for emergency planning and trade secrets. A cross reference table is provided in the glossary.

Acronyms and abbreviations used in this report are listed below.

CFR - Code of Federal Regulations
CMMS - Computerized Maintenance Management System
EPA - U.S. Environmental Protection Agency
ITM - Inspection, Testing, and Maintenance, which includes "mechanical integrity"
MAWP - Maximum Allowable Working Pressure
MDMT - Minimum Design Metal Temperature
MOC - Management of Change
OSHA - U.S. Department of Labor, Occupational Safety and Health Administration
P&ID - Piping and Instrumentation Diagram and Associated Component Lists
PHA - Process Hazard Analysis
PSI - Process Safety Information
PSIG - Pounds Per Square Inch Gauge -- the amount the measured pressure exceeds the atmospheric pressure.
PSM - Process Safety Management
PSR - Pre-startup Safety Review
RMP - Risk Management Plan
SOP - Standard Operating Procedure

Rules and guidance that may be cited in this report are listed below.

29 CFR 1910.119 -- Title 29, Code of Federal Regulations (CFR), Section 1910.119. This is the Process Safety Management (PSM) regulation administered by the U.S. Department of Labor, Occupational Safety and Health Administration (OSHA). Federal Register (FR) publication and amendments: 57 FR 6403, Feb. 24, 1992; 57 FR 7847, Mar. 4, 1992, as amended at 61 FR 9238, Mar. 7, 1996; 67 FR 67964, Nov. 7, 2002; 76 FR 80738, Dec. 27, 2011; 77 FR 17776, Mar. 26, 2012; 78 FR 9313, Feb. 8, 2013. Available from http://gpo.gov or http://osha.gov

40 CFR 68 -- Title 40, Code of Federal Regulations, Part 68. This is the Chemical Accident Prevention/Risk Management Plan (RMP) regulation administered by the U.S. Environmental Protection Agency (EPA). Subpart D is the Program 3 Prevention Program. Federal Register (FR) publication and amendments: 59 FR 4493, Jan. 31, 1994. Redesignated at 61 FR 31717, June 20, 1996; 61 FR 31729, June 20, 1996; as amended at 62 FR 45132, Aug. 25, 1997; 63 FR 645, Jan. 6, 1998; 64 FR 980, Jan. 6, 1999; 64 FR 28700, May 26, 1999; 65 FR 13250, Mar. 13, 2000; 69 FR 18832, Apr. 9, 2004. Available from http://gpo.gov or http://epa.gov

State and local codes -- Check these for applicability, especially building codes. 

__ Examples for Wisconsin are below START __

Wisconsin Commercial Building Code -- Chapter SPS 361 to 365, Wisconsin Administrative Code. This adopts the International Mechanical Code and other International Code Council codes, with some exceptions for ammonia refrigeration in industrial occupancies. Available from the Legislative Reference Bureau http://legis.wisconsin.gov/lrb/

SPS 345 (formerly Comm 45, ILHR 45, and Ind 45) -- Chapter SPS 345, Wisconsin Administrative Code. This is the state of Wisconsin\'s mechanical-refrigeration code, administered by the Division of Industry Services (formerly Safety & Buildings), Department of Safety and Professional Services (formerly Commerce). This code has a long history, for example, a revised edition of Ind 45 was published in March 1963. In September 1983 it was revised and renamed ILHR 45, and subsequently revised effective: September 1, 1994; November 1, 1996 (and renamed Comm 45); July 1, 2002; November 1, 2003 (which adopted the increased relief-vent (RV) pipes sizes in ASHRAE Addendum 15c-2000); September 1, 2010; January 1, 2012 (only renamed SPS 345); June 1, 2012; and October 1, 2013. Since September 1, 2010, this code has referenced ASHRAE 15-2007 (including addenda a to i) and IIAR 2-2008, which in turn reference the ASME codes described below. Guidance and information on any updates are available at https://dsps.wi.gov. The current and older editions of SPS 345, Comm 45, ILHR 45, and Ind 45 may be obtained by contacting the Legislative Reference Bureau http://legis.wisconsin.gov/lrb/

IMC -- International Mechanical Code (IMC). The IMC has been published every three years since 2000. IMC 2000 was adopted, in part, by Comm 45, Wisconsin Administrative Code, from November 1, 2003, to August 31, 2010. Regarding ammonia refrigeration, it has typically referenced and been mostly consistent with ASHRAE 15 and IIAR 2, with additional requirements. The IMC may be purchased from the International Code Council at http://www.iccsafe.org

__ Examples for Wisconsin are below END __

IFC -- International Fire Code (IFC). IFC references IIAR standards and includes additional requirements that may apply to ammonia-refrigeration systems. The IFC may be purchased from the International Code Council at http://www.iccsafe.org

NFPA 1 -- National Fire Protection Agency (NFPA) 1. NFPA 1 references IIAR standards and includes additional requirements that may apply to ammonia-refrigeration systems. NFPA 1 may be purchased from the at https://nfpa.org

ASHRAE 15 -- American National Standards Institute (ANSI)/American Society of Heating, Refrigeration, and Air Conditioning Engineers (ASHRAE), Standard 15, "Safety Standard for Mechanical Refrigeration." Since publication of the 2019 Addendum A to IIAR 2, building codes have started allowing ammonia-refrigeration systems to only follow IIAR 2, for design, without needing to also follow ASHRAE 15. ASHRAE 15 has been published every 3 years since 2001, with more frequent addenda (https://ashrae.org/standards-research--technology/standards-addenda) and interpretations (https://ashrae.org/standards-research--technology/standards-interpretations). The 2013 edition\'s forward indicates, "The first Safety Code for Mechanical Refrigeration, recognized as American Standard B9 in October 1930, appeared in the first edition, 1932-1933, of the ASRE Refrigerating Handbook and Catalog. ASRE revisions designated ASA B9 appeared in 1933 and 1939. ASRE revisions designated ASA B9.1 appeared in 1950, 1953, and 1958. After the formation of ASHRAE, editions appeared as ASA B9.1-1964, ANSI B9.1-1971, ANSI/ASHRAE 15-1978, ANSI/ASHRAE 15-1989, ANSI/ASHRAE 15-1992, ANSI/ASHRAE 15-1994, ANSI/ASHRAE 15-2001, ANSI/ASHRAE 15-2004, ANSI/ASHRAE 15-2007, and ANSI/ASHRAE 15-2010." ASHRAE publications may be purchased from http://ashrae.org

ASME Boiler and Pressure Vessel Code -- ASME (founded as the American Society of Mechanical Engineers) has published this code since 1914. Recent editions include 2010, 2013, 2015, 2017, and scheduled for every two years going forward -- possibly with more recent interpretations or code cases. ASME publications may be purchased from https://www.asme.org

ASME B31.5 -- "Refrigeration Piping and Heat Transfer Components." This code was separated from the power-piping code (B31.1) in 1962, and revised in 1966, 1974, 1978, 1983, 1987, 1989, 1992, 2001, 2006, and every three years since 2010, possibly with more frequent addenda and interpretations. ASME B31.5 covers materials, design, fabrication, assembly, erection, testing, and inspection. ASME publications may be purchased from https://www.asme.org

IIAR 1 -- ANSI/International Institute of Ammonia Refrigeration (IIAR), Standard 1, "Definitions and Terminology Used in IIAR Standards." The first edition was published in 2012. The next and latest edition was published in 2017. IIAR publications may be purchased from http://iiar.org

IIAR 2 -- ANSI/IIAR, Standard 2, "Safe Design of Closed-Circuit Ammonia Refrigeration Systems". The first edition was published in March 1974 as IIAR 74-2. Revised editions (or addenda) were published in 1984, December 1992, August 1999, October 2005, June 2008, August 2010, December 2012, November 2015 (named the 2014 edition), and July 2019 (named 2014 Addendum A). This standard was named "Equipment, Design, and Installation of Closed Circuit Ammonia Mechanical Refrigerating Systems" prior to the 2014 edition.

IIAR 3 -- ANSI/IIAR, Standard 3, "Ammonia Refrigeration Valves". IIAR 3-2005 was an "Informative Reference" of IIAR 2-2008. The first edition was published in 2005. Revised editions were published in 2012 and 2017.

IIAR 4 -- ANSI/IIAR, Standard 4, "Installation of Closed-Circuit Ammonia Refrigeration Systems". The first edition was published in 2015. This is the latest edition.

IIAR 5 -- ANSI/IIAR, Standard 5, "Start-up and Commissioning of Closed-Circuit Ammonia Refrigeration Systems". The first edition was published in 2013. A revised edition was published in 2019.

IIAR 6 -- ANSI/IIAR, Standard 6, "Inspection, Testing, and Maintenance of Closed-Circuit Ammonia Refrigeration Systems". The first edition was published in May 2019.

IIAR 7 -- ANSI/IIAR, Standard 7, "Developing Operating Procedures for Closed-Circuit Ammonia Mechanical Refrigerating Systems". The first edition was published in 2013. The next and latest edition was published in 2019.

IIAR 8 -- ANSI/IIAR, Standard 8, "Decommissioning of Closed-Circuit Ammonia Refrigeration Systems". The first edition was published in 2015. This is the latest edition.

IIAR 9 -- ANSI/IIAR, Standard 9, "Minimum System Safety Requirements for Existing Closed-Circuit Ammonia Refrigeration Systems". The first edition was published in March 2020. This is the latest edition.

IIAR Bulletins -- These provide recommendations that are meant to be voluntary and non-binding, but which may become "good engineering practice" and therefore required under PSM and RMP regulations if they are widely followed. A partial list is below.
IIAR Bulletin 107 (1997, superseded by IIAR 5-2013, IIAR 4-2015, and subsequent editions), "Guidelines for: Suggested Safety and Operating Procedures When Making Ammonia Refrigeration Plant Tie-ins."
IIAR Bulletin 108 (1986, superseded by IIAR 6-2019), "Guidelines for: Water Contamination in Ammonia Refrigeration Systems." Includes water-content test method.
IIAR Bulletin 109 (1997, superseded by IIAR 6-2019 and IIAR 9-2020), "Guidelines for: IIAR Minimum Safety Criteria for a Safe Ammonia Refrigeration System.".
IIAR Bulletin 110 (2002, revised in 2004 and 2007, superseded by IIAR 5-2013, IIAR 4-2015, IIAR 6-2019, and subsequent editions), "Guidelines for: Start-up, Inspection and Maintenance of Ammonia Mechanical Refrigerating Systems." Section 6.0 - Inspection and Maintenance contains information on mechanical integrity. Section 6.4 was revised in February 4, 2004. Section 6.6.3 was revised in May 24, 2007.
IIAR Bulletin 111 (2002, superseded by IIAR 2), "Guidelines for: Ammonia Machinery Room Ventilation." Superseded by the ventilation requirements in the 2010 Addendum A of IIAR 2-2008 and subsequent editions. But, provides diagrams of suggested air flow that are not in these standards.
IIAR Bulletin 112 (1998, superseded by IIAR 2), "Guidelines for: Ammonia Machinery Room Design." Superseded by the 2010 Addendum A of IIAR 2-2008 and subsequent editions.
IIAR Bulletin 114 (1991, revised in 2014, 2017, and 2019), "Guidelines for: Identification of Ammonia Refrigeration Piping and System Components."
IIAR Bulletin 116 (1992, superseded by IIAR 6-2019), "Guidelines for: Avoiding Component Failure in Industrial Refrigeration Systems Caused by Abnormal Pressure or Shock."

IIAR updates and interpretations are available at the IIAR website http://iiar.org, under the "Technology and Standards" menu select "Standards Review" and then select "ANSI/IIAR Standards Interpretations". For the latest editions, under the "Store" menu select "Bulletins" or "Standards".'),
        'c6audit_scope' => $Zfpf->encrypt_1c('This report documents the services that [AUDITOR COMPANY NAME] performed to assist the Owner/Operator with meeting both OSHA\'s PSM and the EPA\'s Program 3 Prevention Program compliance-audit requirements, at 29 CFR 1910.119(o) and 40 CFR 68.79, respectively. To get background for making the audit more effective and to attempt to ensure that the audit covered everything that may later be viewed as needing to be audited, the scope of this audit included:
* 29 CFR 1910.119 (the OSHA PSM standard) 
as well as the following portions of the EPA\'s Chemical Accident Prevention rule,
* 40 CFR 68.15 and 68.200 (Management System and Recordkeeping),
* 40 CFR 68, Subpart D (the Program 3 Prevention Program -- by auditing compliance with the nearly identical OSHA PSM requirements), and
* 40 CFR 68.90 to 68.96 (emergency preparedness).

The compliance verifications included with this report cite only the OSHA PSM standard. The EPA Program 3 Prevention Program requirements are nearly identical; the Program 3 Prevention Program substitutes:
- "owners and operators" for "employers",
- "regulated substance" for "highly hazardous chemical",
- "stationary source" for "facility", and
- in the definition of "catastrophic release", "presents imminent and substantial endangerment to public health and the environment" for "presents serious danger to employees in the workplace";
Also, compared to the OSHA PSM standard, the Program 3 Prevention Program doesn\'t have:
- the phrase "in the workplace" or "on employees in the workplace" in the PHA content and incident investigation applicability requirements and
- any injury and illness log requirement for contractors resembling 29 CFR 1910.119(h)(2)(vi).

The EPA\'s Hazard Assessment requirements at 40 CFR 68, Subpart B -- including the release scenarios, offsite impacts, and five-year accident history -- as well as its Risk Management Plan (RMP) reporting requirements at 40 CFR 68, Subpart G, do not need to be included in the 3-year PSM-audit because they are not aspects of 40 CFR 68 applicable to compliance with the Program 3 Prevention Program at 40 CFR 68, Subpart D (nor the OSHA PSM standard). The EPA requires that the Owner/Operator shall review, update, and resubmit the Hazard Assessment and the RMP once every 5 years under 40 CFR 68.36 and 68.190. The Process Hazard Analysis (or Hazard Identification and Risk Assessment) shall also be updated every 5 years under 29 CFR 1910.119(e)(6) and 40 CFR 68.67(f). These 5-year update tasks were not within the scope of this 3-year PSM audit.

This audit\'s scope did not include a mechanical-integrity inspection. The audit focused instead on whether an adequate mechanical-integrity program was in place and followed for the process. However, mechanical-integrity shortcomings incidentally observed during the tour are included in the audit findings.

Except where directly applicable to PSM compliance, this audit\'s scope did not include evaluating compliance with safety, environmental, building, mechanical, and related standards, codes, and regulations. The audit focused on whether the Owner/Operator had identified and documented compliance with codes directly applicable to the refrigeration system. For example, the audit did not evaluate refrigeration-equipment supports, earthquake or flood hazards, building structures, or means of egress -- even though findings about these (or to investigate these) may be included in the audit report if discovered incidentally during the audit.

The observations, findings, and resolution options in this report are focused on regulatory compliance. This audit did not evaluate health, safety, environmental, engineering, construction, financial, security or other considerations required for implementing any resolution options or other suggestions made in this report. Safe and effective resolution of audit findings -- including careful evaluation of resolution options or other suggestions made in this report and also including means, methods, and sequences -- is the responsibility of the Owner/Operator.

If you have any questions or concerns about the scope of this audit, please contact the auditor.'),
        'c6audit_method' => $Zfpf->encrypt_1c('On [DATES AUDIT ON-SITE SERVICES DONE] the auditor(s) listed above completed the on-site services for the audit described in this report. Additional document review was done off-site. [List managers who participated in the entrance and exit meetings. Briefly summarize people talked with, equipment viewed, and documents reviewed  -- details on these go in the as-done observation methods, in each observation record.]'),
        'c6auditor_qualifications' => $EncryptedNothing,
        'c6bfn_auditor_notes' => $EncryptedNothing,
        'c6suggestions' => $EncryptedNothing
    )
    // Additional templates may be added here, and you would also need to make the template subprocesses, scenarios, junction tables, causes, etc.
);
foreach ($AmmoniaRefrigerationAudit as $V) {
    $V['k0process'] = 0; // For all audit templates, this gets assigned when template is deployed. See ccsaZfpf::ccsa_io0_2 additional security check.
    $V['c5who_is_editing'] = $EncryptedNobody;
    $Zfpf->insert_sql_1s($DBMSresource, 't0audit', $V);
}

