<?php

// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// TO DO integrate with app
// TO DO add security check 

/* BACKGROUD
Scheduled tasks: For checking deadlines and sending reminders, this gives background on the cron utility and it's equivalent in Windows
https://www.drupal.org/docs/7/setting-up-cron/overview
  - script to send reminder email to Process PSM Leader if an employee’s refresher training is due within 6 months.  Remind monthly starting 6 months before until completed (so for any row in t0training_form where training completed more than 2.5 years ago.).
         Can use c5ts_employee in t0training_form to trigger reminder emails.
*/

/*
//This is the reminder script which will be run via cron utilities to check when employee training is required.
require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/FilesZfpf.php';
$Zfpf = new FilesZfpf;

$DBMSresource = $Zfpf->credentials_connect_instance_1s();
$Results = mysqli_query($DBMSresource, 'SELECT * FROM t0training_form;');  // TO DO best way???
$RowsReturned = 0;
if ($Results) {
    while ($Row = mysqli_fetch_assoc($Results))	{
        $SelectedResults[$RowsReturned] = $Row;
        ++$RowsReturned;
    }
    mysqli_free_result($Results);
}

// THIS IS JUST FOR DEBUGGING PURPOSES		
echo $RowsReturned.'<br/><br/>';

foreach ($SelectedResults as $K => $V) {
	//Get important variables
	$c5ts_employee = $Zfpf->decrypt_1c($V['c5ts_employee']);
	$c5ts_reminder_email = $Zfpf->decrypt_1c($V['c5ts_reminder_email']);
	$c5status = $Zfpf->decrypt_1c($V['c5status']);
	
	//Switch statement to see who needs to be reminded to finish training form
	switch($c5status)
	{
		case 'draft': break;				//send reminder to both employee and instructor
		case 'employee approved': break;	//send reminder to instructor
		case 'instructor approved': break;	//send no reminders for completion
	}
	//If 2 and a half years have passed since employee completed training
	if ($c5ts_employee != '[Nothing has been recorded in this field.]' and (time() >= strtotime("+30 months",$c5ts_employee))) and $c5ts_reminder_email == '[Nothing has been recorded in this field.]')
	{
		$Zfpf->send_reminder_1c($user,$subject); //function still needs to be written
		//set c5ts_reminder_email to time()
	}
	//Reminding monthly after the 2 and a half year point
	if (time() >= strtotime("+1 month",$c5ts_reminder_email)) 
	{
		$Zfpf->send_reminder_1c($user,$subject);
		//need to update c5ts_reminder_email with the new time()
	}
} 
//	TO DO once refresher training is provided the code that records this will need to return status to draft and the employee and instructor approvals to $Nothing
$Zfpf->close_connection_1s($DBMSresource);

$Zfpf->save_and_exit_1c();
*/

