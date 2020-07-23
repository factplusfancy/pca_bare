<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// Class FilesZfpf contains function(s) for:
//  - uploading and downloading files to the server's file system, including echoing HTML for this.

require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';

class FilesZfpf extends ConfirmZfpf {

    ////////////////////////////////////////////////////////////////////////////
    // This function is a wrapper.
    public function write_file_1e($DataToWrite, $BaseFileName, $Directory = FALSE, $SelectedRow = FALSE) {
        if (!$Directory)
            $Directory = $this->user_files_directory_1e($SelectedRow);
        $FileFullPath = $Directory.$BaseFileName;
        $BytesWritten = file_put_contents($FileFullPath, $DataToWrite); // TO DO FOR PRODUCTION VERSION -- review this line for security and speed. file_put_contents may be used elsewhere in app files.
        return $BytesWritten;
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function is a wrapper.
    public function read_file_1e($BaseFileName, $Directory = FALSE, $SelectedRow = FALSE) {
        if (!$Directory)
            $Directory = $this->user_files_directory_1e($SelectedRow);
        $FileFullPath = $Directory.$BaseFileName;
        $String = file_get_contents($FileFullPath); // TO DO FOR PRODUCTION VERSION -- review this line for security and speed.
        return $String;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // Download Selected button pressed -- typically called in o1 code.
    // $SelectedRow must be passed in or $_SESSION['Selected'] must be set for this function to work.
    // See contractor_qual_io03.php for a typical implementation.
    // If no file is selected, no "save as" diaglog box appears, and (in typical implementation) user sent to o1 code without any message. Not easy implementin a message.
    public function download_selected_files_1e($htmlFormArray, $GoBackFileName = 'contents0.php', $GoBackInputName = '', $SelectedRow = FALSE) {
        if (!$SelectedRow) {
            if (!isset($_SESSION['Selected']))
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' download_selected_files_1e() Eject Case 1.');
            $SelectedRow = $_SESSION['Selected'];
        }
        $Directory = $this->user_files_directory_1e($SelectedRow);
        foreach ($htmlFormArray as $KA => $VA)
            if (isset($VA[3]) and $VA[3] == 'upload_files') {
                $c6bfn_array = $this->c6bfn_decrypt_decode_1e($SelectedRow[$KA]);
                if ($c6bfn_array) {
                    $Count = 0;
                    foreach ($c6bfn_array as $KB => $VB) {
                        if (ZIP_DOWNLOAD_WORKS_ZFPF) {
                            $PossibleKey = $KA.$Count++;
                            if (isset($_POST[$PossibleKey]))
                                $c6bfn_selected[$KB] = $VB;
                        }
                        elseif (isset($_POST['identifier_for_fileszfpf']) and $_POST['identifier_for_fileszfpf'] == $KA.$Count++)
                            $c6bfn_selected[$KB] = $VB;
                    }
                }
            }
        if (isset($c6bfn_selected)) {
            if (!ZIP_DOWNLOAD_WORKS_ZFPF and count($c6bfn_selected) > 1) // Implies hacker modified HTML form to try to download more than one file at once, when not allowed.
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' download_selected_files_1e() Eject Case 2. User tried to download multiple files when not allowed.');
            $this->download_c6bfn_files_1e($Directory, $c6bfn_selected, $GoBackFileName, $GoBackInputName);
        }
    }

    ////////////////////////////////////////////////////////////////////////////
    // Download All button pressed -- typically called in o1 code.
    // $SelectedRow must be passed in or $_SESSION['Selected'] must be set for this function to work.
    // See contractor_qual_io03.php for a typical implementation.
    public function download_all_files_1e($htmlFormArray, $GoBackFileName = 'contents0.php', $GoBackInputName = '', $SelectedRow = FALSE) {
        if (!ZIP_DOWNLOAD_WORKS_ZFPF) // Implies hacker modified HTML form to try to download more than one file at once, when not allowed.
            $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' download_all_files_1e() Eject Case 1. User tried to download all files when not allowed.');
        if (!$SelectedRow) {
            if (!isset($_SESSION['Selected']))
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' download_all_files_1e() Eject Case 2.');
            $SelectedRow = $_SESSION['Selected'];
        }
        $Directory = $this->user_files_directory_1e($SelectedRow);
        $c6bfn_array = FALSE;
        foreach ($htmlFormArray as $K => $V)
            if (isset($V[3]) and $V[3] == 'upload_files') {
                if (!$c6bfn_array)
                    $c6bfn_array = $this->c6bfn_decrypt_decode_1e($SelectedRow[$K]);
                else {
                    $Temp = $this->c6bfn_decrypt_decode_1e($SelectedRow[$K]);
                    if ($Temp)
                        $c6bfn_array = array_merge($c6bfn_array, $Temp);
                }
            }
        if ($c6bfn_array) {
            $this->download_c6bfn_files_1e($Directory, $c6bfn_array, $GoBackFileName, $GoBackInputName);
        }
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function is a wrapper for the file encryption function 
    // Return encrypted text on success with iv embedded inside
    public function encrypt_file_1e($FileFullPath) {
        $plaintext = file_get_contents($FileFullPath);
        return $this->encrypt_1c($plaintext);
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function is a wrapper for the file decryption function 
    // Return decrypted text on success, FALSE on failure.
    public function decrypt_file_1e($FileFullPath) {
        $ciphertext = file_get_contents($FileFullPath);
        return $this->decrypt_1c($ciphertext);
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function is a wrapper for the malware checking software 
    // Return 'Safe' if no malware found, and 
    // whatever message the malware software provides otherwise. 
    /*    // TO DO FOR PRODUCTION VERSION -- options START
        
        May only work on Linux.
        http://php-clamav.sourceforge.net/
        
        Good Possible Solutions:
        https://support.tilaa.com/entries/96709807-How-can-I-scan-my-server-for-malware-infections-

        How it Happens:
        https://www.webroot.com/blog/2011/02/22/malicious-php-scripts-on-the-rise/

        Simple Easy Solution:
        https://github.com/nbs-system/php-malware-finder

        Scanner:
        https://sourceforge.net/projects/smscanner/
        Those are just some of the pages I found during research
        oops, forgot this one as well:
        Linux Malware Detect
        https://www.rfxn.com/projects/linux-malware-detect/
        // TO DO FOR PRODUCTION VERSION -- options END
    */
    public function malware_control_1e($FileFullPath) {
        // TO DO FOR PRODUCTION VERSION call whatever malware software an particular implementation is using. 
        return 'Safe';
    }

    ////////////////////////////////////////////////////////////////////////////
    // This function downloads files in a zip archive.
    // $PathsAndLocalNames is a two-dimensional numeric array described in comment below, in the foreach loop.
    // $ZipFileName is the name that will be given to the zip archive.
    // TO DO 2019-06-06 Consider as upgrade: if this fails, unlink and rmdir (see below) and loop through PathsAndLocalNames and download each file one at a time.
    public function zip_download_files_1e($PathsAndLocalNames, $ZipFileName = '') {
        // This implements an arbitrary standard naming method for zip archives, if the calling file did not provide a name.
        if (!$ZipFileName)
            $ZipFileName = 'pcm_app'.time().'.zip';
        // Initiate a PHP ZipArchieve object.
        $ZipArchive = new ZipArchive();
        // Create a directory that almost certainly has a unique name. This function later removes this directory.
        if (!file_exists(ZIP_TEMP_DIRECTORY_ZFPF))
            if (!mkdir(ZIP_TEMP_DIRECTORY_ZFPF, CHMOD_DIRECTORY_PERMISSIONS_ZFPF, TRUE))
                return ' zip_download_files_1e() Error Log Case 1. mkdir failed';
        $ZipFullPath = ZIP_TEMP_DIRECTORY_ZFPF.$ZipFileName;
        $ZipStatus = $ZipArchive->open($ZipFullPath, ZipArchive::CREATE); // 2018-05-08 ZipArchive::OVERWRITE didn't work after PHP7 upgrade, switched to ZipArchive::CREATE
        if ($ZipStatus !== TRUE) // Must use !== to distinguish TRUE from error code.
            return ' zip_download_files_1e() Error Log Case 2. A PHP ZipArchive object could not open a zip file named '.$ZipFullPath.'. PHP error code: '.$ZipStatus;
        $Junk = '';
        foreach ($PathsAndLocalNames as $V) {
        // $V[0] is the full path and filename on the server. 
        // $V[1] is the local name to be assigned inside the zip archive, which is the same as the user-assigned name on upload
            if (is_readable($V[0])) {
                if (strlen($Junk) < 5000000) // To avoid memory problems, limit size of $Junk used for overwriting temp file to 5 MB.
                    $Junk .= $V[0];
                $FileAsString = $this->decrypt_file_1e($V[0]); // FilesZfpf::decrypt_file_1e returns plain text or FALSE on failure.
                if ($FileAsString) {
                    $ZipStatus = $ZipArchive->addFromString($V[1], $FileAsString);
                    if (!$ZipStatus)
                        return ' zip_download_files_1e() Error Log Case 3. A PHP ZipArchive object could not add file '.$V[0];
                }
                else
                    return ' zip_download_files_1e() Error Log Case 4. When creating a Zip archive file, could not decrypt file '.$V[0];
            }
            else
                return ' zip_download_files_1e() Error Log Case 5. When creating a Zip archive file, could not read file '.$V[0];
        }
        if (!$ZipArchive->close()); // TO DO as of 2019-06-06 a Google App Engine (GAE) PHP 5 standard environment bug causes this to fail. May work with GAE PHP 7 standard environment.
            return ' zip_download_files_1e() Error Log Case 6. A PHP ZipArchive object could not close, which typically means writing the zip file to the temporary directory defined in /settings/CoreSettingsZfpf.php failed';
        // HTML and other headers to try to get all types of browsers to open a download dialog box.
        header('Content-Description: File Transfer'); // This is a MIME header, but may help with some browsers.
        header('Content-Type: application/zip'); // This is a MIME type, but may help with some browsers.
        header('Content-Disposition: attachment; filename="'.$ZipFileName.'"'); // The main ingredient that alone should cause a "perfect" browser to open a "save file" dialog box.
        header('Expires: 0'); // May only be needed for legacy browsers.
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); // May only be needed for legacy browsers.
        header('Pragma: public'); // May only be needed for legacy browsers.
        header('Content-Length: '.filesize($ZipFullPath)); // May help with some browsers.
        $Success = @readfile($ZipFullPath); // FALSE on failure, @ suppresses error message. A custom message is created below.
        file_put_contents($ZipFullPath, $Junk); // Overwrite temporary zip file
        unlink($ZipFullPath); // "Delete" temporary zip file
        rmdir(ZIP_TEMP_DIRECTORY_ZFPF); // "Delete" directory, it must be empty for this PHP function to work.
        if (!$Success)
            return ' zip_download_files_1e() Error Log Case 7. readfile() returned false';
        $this->save_and_exit_1c(); // Once the file downloads, the "save file" dialog box should disappear and the user should be at the unchanged HTML page from which they triggered the download. Exit here and the user can do whatever they want on that page.
    }

    //////////////////////////////////////////////////////////////////////////////
    // This function has HTML headers and PHP code for downloading a single file
    // held as a string in a PHP variable
    // $FileAsString is a string that will be the contents of the downloaded file
    // $LocalName is the default name for the file to be saved locally, when downloaded.
    // $Application is an optional application type, like "pdf". "octet-stream" is default; means user or their equipment has to figure out the application. $Application = pdf works often, but not with Firefox mobile browser as of 2019-06-04.
    public function download_one_file($FileAsString, $LocalName, $Application = 'octet-stream') {
        header('Content-Description: File Transfer'); // This is a MIME header, but may help with some browsers.
        header('Content-Type: application/'.$Application); // This is a MIME header, but may help with some browsers.
        header('Content-Disposition: attachment; filename="'.$LocalName.'"'); // The main ingredient that alone should cause a "perfect" browser to open a "save file" dialog box.
        header('Expires: 0'); // May only be needed for legacy browsers.
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); // May only be needed for legacy browsers.
        header('Pragma: public'); // May only be needed for legacy browsers.
        header('Content-Length: '.strlen($FileAsString)); // strlen() returns size, in bytes, of a string. Cannot use filesize() here.
        echo $FileAsString;
        $this->save_and_exit_1c(); // Once the file downloads, the "save file" dialog box should disappear and the user should be at the unchanged HTML page from which they triggered the download. Exit here and the user can do whatever they want on that page.
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // This function has code for downloading a single file, or
    // It creates the $PathsAndLocalNames array for FilesZfpf::zip_download_files_1e and sends users there.
    // $Directory is the directory where files to be downloaded are stored. It must include a trailing directory delimiter, for example, /foo/bar/
    // $c6bfn_array is the decrypted, decoded array from c6bfn database fields. See 0read_me_psm_cap_app_standards.txt
    // Only storing base filenames in the database facilitates changing the practice-directory path, for example, if a facility is sold from one owner to another.
    // $GoBackFileName and $GoBackInputName allow customizing where user is sent on failure.
    // A custom $ZipFileName will be passed along if provided.
    public function download_c6bfn_files_1e($Directory, $c6bfn_array, $GoBackFileName = 'contents0.php', $GoBackInputName = '', $ZipFileName = '') {
        if (ZIP_DOWNLOAD_WORKS_ZFPF) {
            foreach ($c6bfn_array as $K => $V) {
                $PathsAndLocalNames[] = array($Directory.$K, $V[0]);
                $LocalNames[] = $V[0];
            }
            foreach ($PathsAndLocalNames as $K => $V) {
                $FilesNameUsed = array_keys($LocalNames, $V[1]);
                if (count($FilesNameUsed) > 1)
                    $PathsAndLocalNames[$K][1] = $V[1].'_'.$K;
            }
            $ErrorMessage = $this->zip_download_files_1e($PathsAndLocalNames, $ZipFileName); // exits on success, download dialog box appears...
            error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.$ErrorMessage);
            $ProblemMessage = '<p>Compressing files for download (zip) failed.</p>';
        }
        else { // Without zip, user can only download one file at a time.
            if (count($c6bfn_array) > 1) // Double check here.
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' download_c6bfn_files_1e() Eject Case 1. User tried to download multiple files when not allowed.');
            foreach ($c6bfn_array as $K => $V) {
                $FileFullPath = $Directory.$K;
                $LocalName = $V[0];
            }
            $Application = 'octet-stream'; // Default
            $FileExtension = substr($LocalName, -3);
            if ($FileExtension == 'pdf')   // Only pdf helps make download work, if different from default, as of 2019-06-04
                $Application = $FileExtension;
            $FileAsString = FALSE;
            if (is_readable($FileFullPath)) {
                $FileAsString = $this->decrypt_file_1e($FileFullPath); // FilesZfpf::decrypt_file_1e returns plain text or FALSE on failure.
                if (!$FileAsString) {
                    error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' download_c6bfn_files_1e() Error Log Case 2. Could not decrypt file '.$FileFullPath);
                    $ProblemMessage = '<p>The file could not be decrypted.</p>';
                }
            }
            else {
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' download_c6bfn_files_1e() Error Log Case 3. PHP could not read file '.$FileFullPath);
                $ProblemMessage = '<p>The file could not be found or it could not be read from the server\'s storage disk.</p>';
            }
            if ($FileAsString)
                $this->download_one_file($FileAsString, $LocalName, $Application);
        }
        // Below echos if script does not exit after a download via one of the methods above.
        if (!$GoBackInputName)
            $GoBackInputName = substr($GoBackFileName, 0, -8).'o1';
        echo $this->xhtml_contents_header_1c().'<h2>
        File download problem</h2><p>
        Nothing was downloaded because...</p>
        '.$ProblemMessage.'
        <p>
        Contact your supervisor or a PSM-CAP app admin for assistance.</p>
        <form action="'.$GoBackFileName.'" method="post"><p>
            <input type="submit" name="'.$GoBackInputName.'"  value="Go back" /></p>
        </form>'.$this->xhtml_footer_1c();
        $this->save_and_exit_1c();
    }

    /////////////////////////////////////////////////////////////////////////////////
    // This function defines a standard $Directory for user files based on context
    // It checks if this directory already exists, and makes it if it doesn't.
    // $SelectedRow must be passed in or $_SESSION['Selected'] must be set.
    //
    /******* SUPPORTED DATABASE TABLE LIST -- ADD NEW AS NEEDED *******
     * (1) Practices whose records are not easily associated with an entity.
     - action               // Association might be switch between contractor, owner, facility, or process.
     - contractor_qual      // Must be available to owners who haven't selected a contractor.
     *
     * (2) Records associated with owners
     - change_management    // Association might be switch between owner, facility, or process -- so file under owner.
     - facility
     - owner_contractor     // File contractor_io03.php handles t0owner_contractor uploads and downloads.
     *
     * (3) Records associated with facilities (and so also owners)
     - contractor_priv
     *
     * (4) Records associated with contractors
     - user_contractor
     * 
     * (5) Records associated with a practice and always associated with a process.
     *     No customization needed, handled by final else clause below.
     - audit
     - incident
     - pha
     - training_form
     *
     */
    public function user_files_directory_1e($SelectedRow = FALSE) {
        if (!$SelectedRow) {
            if (!isset($_SESSION['Selected']))
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' user_files_directory_1e() Eject Case 1');
            $SelectedRow = $_SESSION['Selected'];
        }
        list($TableName, $PrimaryKeyName, $TableRoot) = $this->table_and_key_names_1c($SelectedRow);
        // Variables below are used to set $Directory based on keys in $_SESSION['StatePicked'] and $SelectedRow.
        $k0owner = '';
        $k0contractor = '';
        $k0facility = '';
        $k0process = '';
        $k0practice = '';
        $k0selected = current($SelectedRow); //current() returns the primary key here, which by fpf standard is the first field in the database row held by $SelectedRow.
        if (isset($_SESSION['StatePicked']['t0owner']))
            $k0owner = $_SESSION['StatePicked']['t0owner']['k0owner'];
        if (isset($_SESSION['StatePicked']['t0contractor']))
            $k0contractor = $_SESSION['StatePicked']['t0contractor']['k0contractor']; // Set for contractors once an owner is selected.
        if (isset($_SESSION['StatePicked']['t0facility']))
            $k0facility = $_SESSION['StatePicked']['t0facility']['k0facility'];
        if (isset($_SESSION['StatePicked']['t0process']))
            $k0process = $_SESSION['StatePicked']['t0process']['k0process'];
        // Use $TableName instead of k0practice in path because practices can get switched between entities, changing their k0practice.
        // $TableName.'/'.$k0selected.'/' in a path ensures a record has its own directory, any preceding path only organizes between entities.
        if ($TableRoot == 'action' or $TableRoot == 'contractor_qual')
            $Directory = USER_FILES_DIRECTORY_PATH_ZFPF.'/'.$TableName.'/'.$k0selected.'/';
        elseif ($TableRoot == 'change_management' or $TableRoot == 'facility' or $TableRoot == 'owner_contractor') {
            if (!$k0owner)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' user_files_directory_1e() Eject Case 2');
            $Directory = USER_FILES_DIRECTORY_PATH_ZFPF.'/'.$k0owner.'/'.$TableName.'/'.$k0selected.'/';
        }
        elseif ($TableRoot == 'contractor_priv') {
            if (!$k0owner or !$k0facility)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' user_files_directory_1e() Eject Case 3');
            $Directory = USER_FILES_DIRECTORY_PATH_ZFPF.'/'.$k0owner.'/'.$k0facility.'/'.$TableName.'/'.$k0selected.'/';
        }
        elseif ($TableRoot == 'user_contractor') {
            if (!$k0contractor)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' user_files_directory_1e() Eject Case 4');
            $Directory = USER_FILES_DIRECTORY_PATH_ZFPF.'/'.$k0contractor.'/'.$TableName.'/'.$k0selected.'/';
        }
        else {
            if (!$k0owner or !$k0facility or !$k0process)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' user_files_directory_1e() Eject Case 5');
            $Directory = USER_FILES_DIRECTORY_PATH_ZFPF.'/'.$k0owner.'/'.$k0facility.'/'.$k0process.'/'.$TableName.'/'.$k0selected.'/';
        }
        // Check is this directory exists and if not create it.
        if (!file_exists($Directory))
            if (!mkdir($Directory, CHMOD_DIRECTORY_PERMISSIONS_ZFPF, TRUE)) {
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' user_files_directory_1e() Error Log Case 1. mkdir failed');
                echo $this->xhtml_contents_header_1c().'<h2>
                File upload problem</h2><p>
                No files were uploaded because...</p><p>
                The needed directory (aka folder) could not be created.</p><p>
                Try again or contact your supervisor or a PSM-CAP app admin for assistance.</p>
                <form action="contents0.php" method="post"><p>
                    <input type="submit" value="Go back" /></p>
                </form>
                '.$this->xhtml_footer_1c(); // This case is rare, so don't bother with $GoBackFileName... just send to contents.
                $this->save_and_exit_1c();
            }
        return $Directory;
    }

    ////////////////////////////////////////////////////////////////////////////
    // $SelectedRow must be passed in or $_SESSION['Selected'] must be set for this function to work.
    // This function checks the $_FILES array for
    //   - files exceeding the max size
    //   - malware
    //   - base filename conflicts.
    // It checks if $Directory exists and if not creates it.
    // If OK, it encrypts and moves files from the $_FILES temporary directory, defined in php.ini, to $Directory with an app-assigned base filename.
    // Lastly it updates the base filename array, $c6bfn_array (or creates it if $c6bfn_array == FALSE), encodes it, encrypts it, 
    // and UPDATES the appropriate database field, determined by $c6bfn_column_name and $SelectedRow.
    // $GoBackFilename is the PHP filename that the "Go back" button will point to if the files cannot be uploaded for a cause that rarely implies hacking.
    // $_POST['problem_c6bfn_files_upload_1e'] will be set if a user clicks on this Go back button. This is the standard name for this button below.
    public function c6bfn_files_upload_1e($Directory, $c6bfn_array, $c6bfn_column_name, $GoBackFilename, $SelectedRow = FALSE) {
        if (!$SelectedRow) {
            if (!isset($_SESSION['Selected']))
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' c6bfn_files_upload_1e() Eject Case 1');
            $SelectedRow = $_SESSION['Selected'];
            $UpdateSessionSelected = TRUE;
        }
        foreach ($_FILES as $K => $V) 
            if (substr($K, 7) != $c6bfn_column_name)
                unset ($_FILES[$K]); // Remove from $_FILES array any files not selected in the same HTML field as the "upload now" button that the user clicked.
        $ProblemMessage = ''; // PHP evaluates the empty string as false.
        $NoFileSelected = 0;
        foreach ($_FILES as $V) {
            $GoodForUpload = 1;
            if ($V['error'] == 1) {
                 // The uploaded file exceeds the upload_max_filesize directive in php.ini.
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' c6bfn_files_upload_1e() Eject Case 2');
            }
            elseif ($V['error'] == 2) {
                // The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
                $GoodForUpload = 0;
                $ProblemMessage .= '<p>
                <b>Too large</b>.'.$this->xss_prevent_1c($V['name']).' is larger than the maximum file size allowed. You may divide it as needed to stay below the maximum file size, which is shown on the form you used to upload the file.</p>';
            }
            elseif ($V['error'] == 3)  {
                // The uploaded file was only partially uploaded.
                $GoodForUpload = 0;
                $ProblemMessage .= '<p>
                <b>Incomplete Upload</b>.'.$this->xss_prevent_1c($V['name']).' was only partially uploaded. Please try again. Or, contact your supervisor or a PSM-CAP App administrator for assistance.</p>';
            }
            elseif ($V['error'] == 4) {
                // User did not select a file. Or "no file was uploaded" for some other reason.
                $GoodForUpload = 0;
                $NoFileSelected++;
            }
            elseif ($V['error'] == 5 or $V['error'] == 6) {
                // Missing a temporary folder. Introduced in PHP 5.0.3. Or, Failed to write file to disk. Introduced in PHP 5.1.0.
                $GoodForUpload = 0;
                $ProblemMessage .= '<p>
                <b>Miscellaneous Problem</b>. A problem occurred. Please contact your supervisor or a PSM-CAP App administrator for assistance. Ask them to check the error log.</p>';
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' c6bfn_files_upload_1e() Error Log Case 1. $_FILES error '.$V['error'].'. 5 means missing a temporary folder. 6 means failed to write file to disk');
            }
            $MalwareCheck = $this->malware_control_1e($V['tmp_name']);
            if ($MalwareCheck != 'Safe') {
                $GoodForUpload = 0;
                $ProblemMessage .= '<p>
                <b>Malware</b>. Malware-control software detected a problem with '.$this->xss_prevent_1c($V['name']).' Please scan this file for malware before tying to upload again. You may contact your supervisor or a PSM-CAP App administrator for assistance.</p>';
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' c6bfn_files_upload_1e() Error Log Case 2. Files::malware_control_1e on an uploaded file returned: '.@$MalwareCheck);
            }
            if ($GoodForUpload)
                $UserFilenames[] = $V['name']; // No need for FilesZfpf::xss_prevent_1c() because never displayed to browser.
        }
        if (isset($UserFilenames))
            $UniqueUserFilenames = array_unique($UserFilenames);
        if (count($_FILES) == $NoFileSelected)
            $ProblemMessage .= '<p>
            <b>No Files</b>. The app did not detect that you selected any files for uploading. If you did, please contact your supervisor or a PSM-CAP App administrator for assistance.</p>';
        elseif (isset($UserFilenames) and $UniqueUserFilenames != $UserFilenames)
            $ProblemMessage .= '<p>
            <b>Same Name</b>. The PSM-CAP App detected that you selected two or more files with the same name. Files must have different names to be uploaded at the same time because files for each implementation of a practice, such as an incident investigation, are stored in one directory (aka folder). If you upload a file with the same name later, the App will append _overwrite_scheduled_[timestamp] to the name of the first file you uploaded. Months may pass before the actual overwriting; this timing depends on choices by the App administrators.</p>';
        if ($ProblemMessage) {
            echo $this->xhtml_contents_header_1c().'<h2>
            File upload problem</h2><p>
            No files were uploaded because...</p>
            '.$ProblemMessage.'
            <p>
            Contact your supervisor or a PSM-CAP app admin for assistance.</p>
            <form action="'.$GoBackFilename.'" method="post"><p>
                <input type="submit" name="problem_c6bfn_files_upload_1e" value="Go back" /></p>
            </form>
            '.$this->xhtml_footer_1c();
            $this->save_and_exit_1c();
        }
        // Check is the target directory exists and if not create it.
        if (!file_exists($Directory))
            if (!mkdir($Directory, CHMOD_DIRECTORY_PERMISSIONS_ZFPF, TRUE)) {
                error_log(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' c6bfn_files_upload_1e() Error Log Case 3');
                $this->normal_log_off_1c($SuccessMessage = 'You were logged off because of a problem with uploading files. Please contact your supervisor or a PSM-CAP App administrator for assistance before doing this again. Ask them to check the error log. You may log on again and use other aspects of the PSM-CAP App.');
            }
        $Count = 0;
        $Alpa = 'A';
        $Uploader = $this->current_user_info_1c();
        foreach ($_FILES as $VA) {
            if ($VA['error'] == 0) {
                // $VA['error'] could also equal 4 here, because the user uploading less than max possible does not stop script.
                $ServerBFN = time().$Alpa++.mt_rand(100000, 999999);
                $FileFullPath = $Directory.$ServerBFN;
                if (file_put_contents($FileFullPath, $this->encrypt_file_1e($VA["tmp_name"]))) {
                    // If this is the first file uploaded, calling script should pass in FALSE for $c6bfn_array
                    if ($c6bfn_array)
                        foreach ($c6bfn_array as $KB => $VB) {
                            // $VA['name'] is the current-upload user-provided filename. $VB[0] is a recorded user-provided filename.
                            if ($this->xss_prevent_1c($VA['name']) == $VB[0]) {
                                $c6bfn_array[$KB][0] .= '_overwrite_scheduled_'.time();
                                break;
                            }
                        }
                    else
                        unset($c6bfn_array); // Clear the boolean FALSE, so that the array creation method below works.
                    $c6bfn_array[$ServerBFN][0] = $this->xss_prevent_1c($VA['name']);
                    $c6bfn_array[$ServerBFN][1] = $Uploader['NameTitle'].', '.$Uploader['Employer'].' '.$Uploader['WorkEmail'];
                    $c6bfn_arrayChanged = TRUE;
                    $Count++;
                }
            }
        }
        if (isset($c6bfn_arrayChanged)) {
            // UPDATE the database with the new base filename information.
            $Changes[$c6bfn_column_name] = $this->encode_encrypt_1c($c6bfn_array);
            list($TableName, $PrimaryKeyName, $TableRoot) = $this->table_and_key_names_1c($SelectedRow);
            $Conditions[0] = array($PrimaryKeyName, '=', $SelectedRow[$PrimaryKeyName]);
            $Affected = $this->one_shot_update_1s($TableName, $Changes, $Conditions);
            if ($Affected != 1)
                $this->eject_1c(@$this->error_prefix_1c().__FILE__.':'.__LINE__.' c6bfn_files_upload_1e() Eject Case 3. Affected: '.@$Affected);
            if(isset($UpdateSessionSelected))
                $_SESSION['Selected'][$c6bfn_column_name] = $Changes[$c6bfn_column_name]; // update $_SESSION['Selected'] to match the database.
        }
        return array('count' => $Count, 'new_c6bfn' => $Changes[$c6bfn_column_name]);
    }

}

