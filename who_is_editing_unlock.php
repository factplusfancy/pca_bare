<?php
// *** LEGAL NOTICES ***  
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

// Security check
if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes')
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);

//Make Headers and Connect to DBMS
echo $Zfpf->xhtml_contents_header_1c('Who Is Editing Unlock').'
<h1>Who Is Editing Unlock</h1>';
$DBMSresource = $Zfpf->credentials_connect_instance_1s();

// Remove user-selected edit locks from table rows.
if (isset($_POST['unlock_edit']) and isset($_POST['locked'])) {
    echo '
    <h2>Records (table rows) Unlocked:</h2><p>';
	foreach($_SESSION['Scratch']['PlainText']['Locked'] as $K => $V)
		if (in_array($K, $_POST['locked']))
			$SelectedLocked[] = $V;
	foreach($SelectedLocked as $V) {
		$KeyName = substr_replace($V['TN'], 'k0', 0, 2);
		$Conditions[0] = array($KeyName, '=', $V['PK']);
		$Changes['c5who_is_editing'] = $Zfpf->encrypt_1c('[Nobody is editing.]');
        $Affected = $Zfpf->update_sql_1s($DBMSresource, $V['TN'], $Changes, $Conditions, FALSE);
        if ($Affected != 1)
            $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
	    echo '* <b>Table:</b> '.$V['TN'].'. <b>Record Name:</b> '.$V['c5name'].'. <b>Locked by:</b> '.$V['c5who_is_editing'].'</p>';
	}
	echo '</p>';
}
unset($_SESSION['Scratch']['PlainText']['Locked']); 

// TO DO Update below when new tables are added to the schema, in includes/template/schema.php.
// TO DO Optional improvement: make a foreach loop through the $Schema array, from includes/templates/schema.php, to generate the SQL below.
$RN = "'No name field'"; // Must be double quoted. Serves as the default record name, for database tables that don't have a name field.
// SQL command which returns array with keys TN, PK, c5name, and c5who_is_editing
$SQL = "SELECT 't0action' 		    as TN, k0action as PK, c5name, c5who_is_editing FROM t0action UNION
SELECT 't0audit' 			        as TN, k0audit as PK, c5name, c5who_is_editing FROM t0audit UNION
SELECT 't0audit_fragment'           as TN, k0audit_fragment as PK, $RN as c5name, c5who_is_editing FROM t0audit UNION
SELECT 't0audit_fragment_obsmethod' as TN, k0audit_fragment_obsmethod as PK, $RN as c5name, c5who_is_editing FROM t0audit UNION
SELECT 't0audit_obstopic'           as TN, k0audit_obstopic as PK, $RN as c5name, c5who_is_editing FROM t0audit UNION
SELECT 't0cause' 				    as TN, k0cause as PK, c5name, c5who_is_editing FROM t0cause UNION
SELECT 't0certify' 				    as TN, k0certify as PK, $RN as c5name, c5who_is_editing FROM t0certify UNION
SELECT 't0change_management' 	    as TN, k0change_management as PK, c5name, c5who_is_editing FROM t0change_management UNION
SELECT 't0consequence ' 		    as TN, k0consequence as PK, c5name, c5who_is_editing FROM t0consequence UNION
SELECT 't0contractor' 			    as TN, k0contractor as PK, c5name, c5who_is_editing FROM t0contractor UNION
SELECT 't0contractor_practice' 	    as TN, k0contractor_practice as PK, $RN as c5name, c5who_is_editing FROM t0contractor_practice UNION
SELECT 't0contractor_priv' 			as TN, k0contractor_priv as PK, $RN as c5name, c5who_is_editing FROM t0contractor_priv UNION
SELECT 't0contractor_qual' 			as TN, k0contractor_qual as PK, c5focus as c5name, c5who_is_editing FROM t0contractor_qual UNION
SELECT 't0division' 				as TN, k0division as PK, c5name, c5who_is_editing FROM t0division UNION
SELECT 't0document' 				as TN, k0document as PK, c5name, c5who_is_editing FROM t0document UNION
SELECT 't0facility' 				as TN, k0facility as PK, c5name, c5who_is_editing FROM t0facility UNION
SELECT 't0facility_practice' 		as TN, k0facility_practice as PK, $RN as c5name, c5who_is_editing FROM t0facility_practice UNION
SELECT 't0facility_process' 		as TN, k0facility_process as PK, $RN as c5name, c5who_is_editing FROM t0facility_process UNION
SELECT 't0facility_union' 			as TN, k0facility_union as PK, $RN as c5name, c5who_is_editing FROM t0facility_union UNION
SELECT 't0fragment' 				as TN, k0fragment as PK, c5name, c5who_is_editing FROM t0fragment UNION
SELECT 't0fragment_division' 		as TN, k0fragment_division as PK, $RN as c5name, c5who_is_editing FROM t0fragment_division UNION
SELECT 't0fragment_guidance' 		as TN, k0fragment_guidance as PK, $RN as c5name, c5who_is_editing FROM t0fragment_guidance UNION
SELECT 't0fragment_practice' 		as TN, k0fragment_practice as PK, $RN as c5name, c5who_is_editing FROM t0fragment_practice UNION
SELECT 't0guidance' 				as TN, k0guidance as PK, c5name, c5who_is_editing FROM t0guidance UNION
SELECT 't0incident' 				as TN, k0incident as PK, c5name, c5who_is_editing FROM t0incident UNION
SELECT 't0incident_action' 			as TN, k0incident_action as PK, $RN as c5name, c5who_is_editing FROM t0incident_action UNION
SELECT 't0lepc' 					as TN, k0lepc as PK, c5name, c5who_is_editing FROM t0lepc UNION
SELECT 't0obsmethod'                as TN, k0obsmethod as PK, $RN as c5name, c5who_is_editing FROM t0owner_contractor UNION
SELECT 't0obsresult'                as TN, k0obsresult as PK, $RN as c5name, c5who_is_editing FROM t0owner_contractor UNION
SELECT 't0obsresult_action' 		as TN, k0obsresult_action as PK, $RN as c5name, c5who_is_editing FROM t0owner_contractor UNION
SELECT 't0obstopic' 				as TN, k0obstopic as PK, c5name, c5who_is_editing FROM t0owner UNION
SELECT 't0obstopic_obsmethod' 		as TN, k0obstopic_obsmethod as PK, $RN as c5name, c5who_is_editing FROM t0owner_contractor UNION
SELECT 't0owner' 					as TN, k0owner as PK, c5name, c5who_is_editing FROM t0owner UNION
SELECT 't0owner_contractor' 		as TN, k0owner_contractor as PK, $RN as c5name, c5who_is_editing FROM t0owner_contractor UNION
SELECT 't0owner_facility' 			as TN, k0owner_facility as PK, $RN as c5name, c5who_is_editing FROM t0owner_facility UNION
SELECT 't0owner_practice' 			as TN, k0owner_practice as PK, $RN as c5name, c5who_is_editing FROM t0owner_practice UNION
SELECT 't0pha' 						as TN, k0pha as PK, $RN as c5name, c5who_is_editing FROM t0pha UNION
SELECT 't0practice' 				as TN, k0practice as PK, c5name, c5who_is_editing FROM t0practice UNION
SELECT 't0practice_division' 		as TN, k0practice_division as PK, $RN as c5name, c5who_is_editing FROM t0practice_division UNION
SELECT 't0practice_document' 		as TN, k0practice_document as PK, $RN as c5name, c5who_is_editing FROM t0practice_document UNION
SELECT 't0process'                  as TN, k0process as PK, c5name, c5who_is_editing FROM t0process UNION
SELECT 't0process_practice' 		as TN, k0process_practice as PK, $RN as c5name, c5who_is_editing FROM t0process_practice UNION
SELECT 't0rule' 					as TN, k0rule as PK, c5name, c5who_is_editing FROM t0rule UNION
SELECT 't0safeguard' 				as TN, k0safeguard as PK, $RN as c5name, c5who_is_editing FROM t0safeguard UNION
SELECT 't0scenario' 				as TN, k0scenario as PK, c5name, c5who_is_editing FROM t0scenario UNION
SELECT 't0scenario_action' 			as TN, k0scenario_action as PK, $RN as c5name, c5who_is_editing FROM t0scenario_action UNION
SELECT 't0scenario_cause' 			as TN, k0scenario_cause as PK, $RN as c5name, c5who_is_editing FROM t0scenario_cause UNION
SELECT 't0scenario_consequence' 	as TN, k0scenario_consequence as PK, $RN as c5name, c5who_is_editing FROM t0scenario_consequence UNION
SELECT 't0scenario_safeguard' 		as TN, k0scenario_safeguard as PK, $RN as c5name, c5who_is_editing FROM t0scenario_safeguard UNION
SELECT 't0subprocess' 		        as TN, k0subprocess as PK, c5name, c5who_is_editing FROM t0subprocess UNION
SELECT 't0training_form'			as TN, k0training_form as PK, $RN as c5name, c5who_is_editing FROM t0training_form UNION
SELECT 't0union' 					as TN, k0union as PK, c5name, c5who_is_editing FROM t0union UNION
SELECT 't0user' 					as TN, k0user as PK, c5name_family as c5name, c5who_is_editing FROM t0user UNION
SELECT 't0user_contractor' 			as TN, k0user_contractor as PK, $RN as c5name, c5who_is_editing FROM t0user_contractor UNION
SELECT 't0user_facility' 			as TN, k0user_facility as PK, $RN as c5name, c5who_is_editing FROM t0user_facility UNION
SELECT 't0user_owner' 				as TN, k0user_owner as PK, $RN as c5name, c5who_is_editing FROM t0user_owner UNION
SELECT 't0user_practice' 			as TN, k0user_practice as PK, $RN as c5name, c5who_is_editing FROM t0user_practice UNION
SELECT 't0user_process' 			as TN, k0user_process as PK, $RN as c5name, c5who_is_editing FROM t0user_process;";
//Return results of query and designate how many rows were returned
$RowsReturned = 0;
$Results = $Zfpf->query_1s($DBMSresource, $SQL); // No user supplied text in SQL query, so no risk of SQL injection.
if ($Results === FALSE)
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
else {
    while ($Row = mysqli_fetch_assoc($Results)) {
        $SelectResults[$RowsReturned] = $Row;
        ++$RowsReturned;
    }
    mysqli_free_result($Results);
}
echo '
<form  action="who_is_editing_unlock.php" method="post">
<p>Returned Rows: '.$RowsReturned.'</p>';
//Loops through returned rows and put locked rows in a new variable ($LockedResults)
if (isset($SelectResults)) foreach ($SelectResults as $K => $V) {
    $WhoIsEditing = $Zfpf->decrypt_1c($V['c5who_is_editing']);
	if ($WhoIsEditing != '[Nobody is editing.]' and substr($WhoIsEditing, 0, 19) != 'PERMANENTLY LOCKED:') {
		$_SESSION['Scratch']['PlainText']['Locked'][$K] = $V; // Limited information is stored in $_SESSION, unencrypted, for this special case.
		$_SESSION['Scratch']['PlainText']['Locked'][$K]['c5who_is_editing'] = $WhoIsEditing; // Decrypt everything for CoreZfpf::save_and_exit_1c
	}
}
//Checks to see if there are any Locked Rows
if (!isset($_SESSION['Scratch']['PlainText']['Locked']))
	echo '<p>
    No Locked Rows, not counting "permanently locked" rows, such as for an issued PHA or HIRA.</p>';
//If there are echo the number of rows that are locked and print them out in a list of checkboxes followed by a button
else {
    $RecordName = 'No name field'; // Same as $RN, but not double quoted.
	echo '<p>
	Locked Rows: '.count($_SESSION['Scratch']['PlainText']['Locked']).', not counting "permanently locked" rows, such as for an issued PHA or HIRA.</p>';
	foreach($_SESSION['Scratch']['PlainText']['Locked'] as $K => $V) {
		if ($V['c5name'] != $RecordName) {
		    $RecordName = $Zfpf->decrypt_1c($V['c5name']);
		    $_SESSION['Scratch']['PlainText']['Locked'][$K]['c5name'] = $RecordName; // Decrypt everything for CoreZfpf::save_and_exit_1c
	    }
		echo '<p>
		<input type="checkbox" name="locked[]" value="'.$K.'"/> <b>Table:</b> '.$V['TN'].'. <b>Record Name:</b> '.$RecordName.'. <b>Locked by:</b> '.$V['c5who_is_editing'].'</p>';
	}
	echo '<p>
	    <input type="submit" name="unlock_edit" value="Unlock Selected Rows" /></p>';
}
echo '
</form>
<form action="administer1.php" method="post"><p>
    <input type="submit" value="Back to administration"/></p>
</form>';

$Zfpf->close_connection_1s($DBMSresource);

$Zfpf->save_and_exit_1c();

