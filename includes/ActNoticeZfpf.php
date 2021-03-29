<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This class is to generate or handle activity notices.

class ActNoticeZfpf {

    // Returns HTML for activity notices
    // $Zfpf must have CoreZfpf (so it is an object that is an instance of at least the CoreZfpf class).
    // $FileRoot allowed values: 'audit', 'cms', 'incident', 'pha'. 
    // $GoBackFile and $GoBackName defaults should work for the above $FileRoot values.
    public function act_notice_generate($Zfpf, $FileRoot, $GoBackFile = FALSE, $GoBackName = FALSE) {
        $Zfpf->clear_edit_lock_1c(); // In case user came here from i1 form, this allows "Go back" button to send user to i1 form.
        if (!$GoBackFile)
            $GoBackFile = $FileRoot.'_io03.php';
        if (!$GoBackName)
            $GoBackName = $FileRoot.'_o1_from';
        if ($FileRoot == 'cms')
            $AEInfo = $Zfpf->affected_entity_info_1c(); // Default parameters should work, from $_SESSION['Selected']
        else
            $AEInfo = $Zfpf->affected_entity_info_1c('Process-wide', $_SESSION['Selected']['k0process']);
        $HTML = $Zfpf->xhtml_contents_header_1c('Activity Notice', FALSE, FALSE).'<h1>
        Process Safety Activity Notice</h1><p><i>
        Affected-Entity Full Description:</i><br /><b>
        '.$AEInfo['AEFullDescription'].'</b></p>';
        if (($AEInfo['AEscope'] == 'Facility-wide' or $AEInfo['AEscope'] == 'Process-wide') and isset($_SESSION['StatePicked']['t0facility'])) {
            // $_SESSION['StatePicked']['t0facility'] should be set, see eject_1c calls in CoreZfpf::affected_entity_info_1c
            $Street2 = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5street2']);
            $Description = $Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c6description']);
            $HTML .= '<p><i>
            Facility Name:</i><br />
            '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5name']).'</p>';
            if ($Description != '[Nothing has been recorded in this field.]')
                $HTML .= '<p><i>
                Facility Description:</i><br />
                '.$Description.'</p>';
            $HTML .= '<p><i>
            Facility Location:</i><br />
            '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5street1']);
            if ($Street2 != '[Nothing has been recorded in this field.]')
                $HTML .= ', '.$Street2;
            $HTML .= ', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5city']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5state_province']).', '.$Zfpf->decrypt_1c($_SESSION['StatePicked']['t0facility']['c5country']).'</p>';
        }
        if ($FileRoot == 'audit') {
            $HTML .= '<p><b>
            A compliance audit or hazard review of the above process\'s is planned soon or ongoing.</b></p>';
            $AsOfTimestamp = $Zfpf->decrypt_1c($_SESSION['Selected']['c5ts_as_of']);
            if ($AsOfTimestamp != '[Nothing has been recorded in this field.]')
                $HTML .= '<p><i>
                Date planned or completed:</i><br />
                '.$Zfpf->timestamp_to_display_1c($AsOfTimestamp).'</p>';
        }
        elseif ($FileRoot == 'cms') {
            $HTML .= '<p><b>
            Change management has started for the following planned (or possible) change.</b></p><p><i>
            Change name:</i><br />
            '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).'</p><p><i>
            Change description:</i><br />
            '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6description']).'</p>';
        }
        elseif ($FileRoot == 'incident') {
            $HTML .= '<p><b>
            The following incident investigation has started.</b></p><p><i>
            Incident standard name:</i><br />
            '.$Zfpf->decrypt_1c($_SESSION['Selected']['c5name']).'</p><p><i>
            Short description of the incident for employees and the public:</i><br />
            '.$Zfpf->decrypt_1c($_SESSION['Selected']['c6summary_description']).'</p>';
        }
        elseif ($FileRoot == 'pha') {
            $HTML .= '<p><b>
            A process-hazard analysis (PHA) or hazard identification and risk analysis (HIRA) on the above process is planned soon or ongoing.</b></p>';
        }
        $HTML .= '<p>
        * All employees are welcome to review information developed to improve process safety and, if applicable, to comply with the Process Safety Management (PSM) and Chemical Accident Prevention (CAP) regulations.</p><p>
        * Your input is welcome. Contact your supervisor, or your organization\'s safety personnel, for more information or to provide feedback.</p><p><i>
        Date this notice was intended to be posted, on or about:</i><br />
        '.$Zfpf->timestamp_to_display_1c(time()).'</p><p>
        Instructions:<br />
        - Print or download (typically as a pdf) this notice, click "Go back" below, and upload the notice (that you downloaded) into the record under the "activity notice" field.<br />
        - Post this notice where all employees, who work at or near the above affected entity, can see it.<br />
        - Post it today or as soon as possible after today.<br />
        - You may email this notice to some or all affected employees, instead of posting it for them.</p>
        <form action="'.$GoBackFile.'" method="post"><p>
            <input type="submit" name="'.$GoBackName.'" value="Go back" /></p>
        </form>
        '.$Zfpf->xhtml_footer_1c();
        return $HTML;
    }

}

