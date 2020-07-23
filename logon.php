<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file checks: for proper logon (credentials...), if minimum user information is missing, or if the password has expired.
// Otherwise, it includes contents1.php

require 'directory_path_settings.php';
// Since this is the logon PHP file, don't always call $Zfpf->session_check_1c()

if (!$_POST) { // Echo form for entering credentials
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
    $Zfpf = new CoreZfpf;
    if (isset($_SESSION['t0user'])) { // The last user on the current browser forgot to logoff. Destroy every active log on with the prior username that wasn't logged off. Reason: if someone forgot to log off here, maybe they forgot to log off elsewhere.
        $Conditions[0] = array('k0user', '=', $_SESSION['t0user']['k0user']); // Set this before unsetting $_SESSION via log_off_1c below.
        $ActiveSessions = $Zfpf->check_array_1c($Zfpf->decrypt_decode_1c($_SESSION['t0user']['c6active_sessions']));
        $SessionID = session_id();
        if (isset($ActiveSessions[$SessionID]))
            unset($ActiveSessions[$SessionID]); // Allows calling log_off_all_1c after log_off_1c without repeating logoff code
        $DBMSresource = $Zfpf->credentials_connect_instance_1s('logon maintenance');
        $Zfpf->log_off_1c($DBMSresource); // Log off current session first because that's top priority, in case error later.
        if ($ActiveSessions) {
            $Changes['c6active_sessions'] = $Zfpf->encode_encrypt_1c($Zfpf->log_off_all_1c($ActiveSessions, $DBMSresource));
            $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, FALSE); // Not recorded in t0history
            if ($Affected != 1)
                error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        }
        $Zfpf->close_connection_1s($DBMSresource);
    }
    $FixedLeftContents = '<p>
    |PSM-CAP App<br />
    |Log On</p>'; // Don't give any option here that might indicate a user is already logged on
    echo $Zfpf->xhtml_contents_header_1c('PSM-CAP Log On', FALSE, $FixedLeftContents).'<h1>
    Log On</h1><p>
    <b>You may only logon with '.MAX_SESSIONS_ZFPF.' device(s) at a time.</b></p>';
    if ((@parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) == '/psm')
        echo '
        <form action="https://factplusfancy.com/pcademo/logon.php" method="post">';  // SPECIAL CASE: for factplusfancy.com/psm app.yaml redirect
    else
        echo '
        <form action="logon.php" method="post">'; // APP_DIRECTORY_PATH_ZFPF doesn't point to same spot as unix socket localhost
    echo '<p>
        Username: <input type="text" name="username" class="screenwidth" maxlength="'.round(C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /></p><p>
        Password: <input type="password" name="password" class="screenwidth" maxlength="'.round(C5_MAX_BYTES_ZFPF/HTML_MAX_CHAR_DIVISOR_ZFPF).'" /></p><p>
        <input type="submit" name="logon" value="Log on -- authorized-users only" /></p><p>
        The PSM-CAP App causes a cookie to be downloaded to your browser, mainly to verify that you have logged in.
        </p>
    </form>
    '.$Zfpf->xhtml_footer_1c();
    exit; // Don't save and exit.
}

require INCLUDES_DIRECTORY_PATH_ZFPF.'/ConfirmZfpf.php';
$Zfpf = new ConfirmZfpf; // Need ConfirmZfpf and CoreZfpf for UserZfpf::user_i1 and user_i2 function calls below.
require INCLUDES_DIRECTORY_PATH_ZFPF.'/UserZfpf.php';
$UserZfpf = new UserZfpf;
if (isset($_POST['logon'])) { // Do logon checks
    $PresentedCredentials = $UserZfpf->username_password_check($Zfpf, FALSE, 'username', 'password', FALSE, TRUE);
    if ($PresentedCredentials['Message']) {
        echo $Zfpf->xhtml_contents_header_1c('Logon Error', FALSE, FALSE).'
        '.$PresentedCredentials['Message'].'<p><b>
        You may have mistyped something.</b></p>
        <form action="logon.php" method="post"><p>
            <input type="submit" value="Try Again" /></p>
        </form>'.$Zfpf->xhtml_footer_1c();
        exit; // Don't save and exit.
    }
    // Get user information from database
    $Conditions[0] = array('k2username_hash', '=', $PresentedCredentials['UsernameHash']);
    $DBMSresource = $Zfpf->credentials_connect_instance_1s('logon maintenance');
    list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresource, 't0user', $Conditions);
    // Check if multiple or no usernames match hash of $_POST['username'].
    if ($RR != 1) {
        if ($RR < 1)
            $Message = '<h2>
            Username not found</h2><p>
            Make sure you typed it correctly, or contact an app admin for assistance.</p>
            <form action="logon.php" method="post"><p>
                <input type="submit" value="Try Again" /></p>
            </form>';
        elseif ($RR > 1) {
            error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Could not log on because '.@$RR.' users had this username. This is highly unusual. INVESTIGATE PROMPTLY.');
            $Message = '<h2>
            Username error</h2><p>
            Please contact an app admin for assistance.</p>';
        }
        $Zfpf->log_off_1c($DBMSresource); // This destroys... the session that was started above.
        $Zfpf->close_connection_1s($DBMSresource);
        echo $Zfpf->xhtml_contents_header_1c('Logon Error', FALSE, FALSE).$Message.$Zfpf->xhtml_footer_1c();
        exit; // Don't save and exit.
    }
    // Put encrypted t0user database row information into $_SESSION
    $_SESSION['t0user'] = $SR[0];
    $Conditions[0] = array('k0user', '=', $_SESSION['t0user']['k0user']); // Set this before unsetting $_SESSION via log_off_1c() calls below.
    $ActiveSessions = $Zfpf->check_array_1c($Zfpf->decrypt_decode_1c($_SESSION['t0user']['c6active_sessions']));
    // Check user supplied password against password stored in database.
    if (!password_verify($PresentedCredentials['Password'], $Zfpf->decrypt_1c($_SESSION['t0user']['s5password_hash']))) {
        $Attempts = 1 + $Zfpf->decrypt_1c($_SESSION['t0user']['c5attempts']);
        error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Incorrect password presented at logon '.@$Attempts.' times in a row since the user\'s last successful logon');
        $Changes['c5attempts'] = $Zfpf->encrypt_1c($Attempts);
        $Message = '<p>
        After your third failed attempt, you won\'t be able to logon until an app admin resets your password.</p>
        <form action="logon.php" method="post"><p>
            <input type="submit" value="Try Again" /></p>
        </form>';
        $Zfpf->log_off_1c($DBMSresource); // Destroy the current session.
        if ($Attempts > 2) {
            $Changes['s5password_hash'] = $Zfpf->encrypt_1c($Zfpf->hash_1c(openssl_random_pseudo_bytes(16))); // Overwrite password with pretty-reliably random stuff, then hash. hash_1c() okay here because this is not a user-supplied password.
            $Changes['c6active_sessions'] = $Zfpf->encode_encrypt_1c($Zfpf->log_off_all_1c($ActiveSessions)); // As a precaution, log off all...
            $Message = '<p>
            That\'s over the limit. You won\'t be able to logon until an app admin resets your password.</p>';
        }
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, FALSE);
        if ($Affected != 1)
            error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected.'. If 0, the app may not limit logon attempts. User gave the incorrect password '.$Attempts.' times in a row.');
        $Zfpf->close_connection_1s($DBMSresource);
        echo $Zfpf->xhtml_contents_header_1c('Logon Error', FALSE, FALSE).'<h2>
        Incorrect password</h2><p>
        You have supplied an incorrect password <b>'.$Attempts.' times in a row</b>.</p>
        '.$Message.$Zfpf->xhtml_footer_1c();
        exit; // Don't save and exit.
    }
    // Check if user has been terminated.
    if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5ts_logon_revoked']) != '[Nothing has been recorded in this field.]') {
        error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' User has an active password, but this user\'s use of the DBMS has been terminated. This is highly unusual. INVESTIGATE PROMPTLY -- who terminated the user\'s privileges and how did they do it? The user will not be able to logon again until their account is unlocked and they are no longer terminated.');
        $Zfpf->log_off_1c($DBMSresource);
        $Changes['s5password_hash'] = $Zfpf->encrypt_1c($Zfpf->hash_1c(openssl_random_pseudo_bytes(16))); // Overwrite password...
        $Changes['c6active_sessions'] = $Zfpf->encode_encrypt_1c($Zfpf->log_off_all_1c($ActiveSessions)); // As a precaution, log off all...
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, FALSE);
        if ($Affected != 1)
            error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        $Zfpf->close_connection_1s($DBMSresource);
        echo $Zfpf->xhtml_contents_header_1c('Logon Error', FALSE, FALSE).'<h2>
        Logon credentials terminated</h2><p>
        Please contact your supervisor or an app admin for assistance.</p>'.$Zfpf->xhtml_footer_1c();
        exit; // Don't save and exit.
    }
    // Start updating active sessions for this username. See also the final database update below.
    $ActiveSessions[session_id()] = $Zfpf->decrypt_array_1c($_SESSION);
    if (count($ActiveSessions) > MAX_SESSIONS_ZFPF) {
        error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.'. User tried to logon with '.MAX_SESSIONS_ZFPF.' session(s), the maximum allowed, already active.');
        $Zfpf->log_off_1c($DBMSresource); // Destroy the current session.
        $Changes['c6active_sessions'] = $Zfpf->encode_encrypt_1c($Zfpf->log_off_all_1c($ActiveSessions)); // As a precaution, log off all...
        $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, FALSE);
        $Zfpf->close_connection_1s($DBMSresource);
        $Message = $Zfpf->xhtml_contents_header_1c('Logon Error', FALSE, FALSE).'<h2>
        Your username was already logged on with '.MAX_SESSIONS_ZFPF.' session(s), the maximum allowed.</h2>';
        if ($Affected == 1)
            $Message .= '<p>
            As  precaution, the app attempted to log off all devices that had been logged on with your username.</p><p>
            You can try to logon again.</p><p>
            <b>If you suspect hacking or that someone else used your username and password, contact your supervisor or an app admin immediately.</b></p>';
        else {
            $Message .= '<p>
            <b>An error occurred, contact your supervisor or an app admin.</b></p>';
            error_log(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected Rows: '.@$Affected);
        }
        echo $Message.'<p>
        Background:<br />
        You may only logon with '.MAX_SESSIONS_ZFPF.' device(s) at a time. If you didn\'t try to logon with more than '.MAX_SESSIONS_ZFPF.' devices (like both a smartphone and a laptop or two browsers on one laptop), then either:<br />
        - hacking -- someone else used your username and password to logon or<br />
        - cookie lost -- the session cookie was deleted from the device you are trying to logon on with, without your having logged off properly.</p>'.$Zfpf->xhtml_footer_1c();
        exit; // Don't save and exit.
    }
    // 2-Step Authentication
    // Use work email in first returned (should be oldest) t0user_owner record, if none, then first t0user_contractor record, if none, skip 2-step.
    // Could send email to all addresses on file, but then if any one email account is compromised, hacker gets in.
    $EmailAddresses = array(); // Will hold only one email.
    unset($SR);
    $DBMSresourceLow = $Zfpf->credentials_connect_instance_1s(LOW_PRIVILEGES_ZFPF); // Needed for CoreZfpf::select_sql_1s on t0user_owner...
    list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresourceLow, 't0user_owner', $Conditions); // $Conditions set properly above.
    if ($RR) foreach ($SR as $V) {
        $WorkEmail = $Zfpf->decrypt_1c($V['c5work_email']);
        if ($WorkEmail != '[Nothing has been recorded in this field.]' and $WorkEmail != 'info@factplusfancy.com') { // Possible default values.
            $EmailAddresses[0] = $WorkEmail;
            break;
        }
    }
    if (!$EmailAddresses) { // Look for user_contractor record.
        unset($SR);
        list($SR, $RR) = $Zfpf->select_sql_1s($DBMSresourceLow, 't0user_contractor', $Conditions);
        if ($RR) foreach ($SR as $V) {
            $WorkEmail = $Zfpf->decrypt_1c($V['c5work_email']);
            if ($WorkEmail != '[Nothing has been recorded in this field.]' and $WorkEmail != 'info@factplusfancy.com') {
                $EmailAddresses[0] = $WorkEmail;
                break;
            }
        }
    }
    $Zfpf->close_connection_1s($DBMSresourceLow);
    if ($EmailAddresses) {
        $TwoStepCode = mt_rand(10000000, 99999999);
        $_SESSION['TwoStepCode'] = $Zfpf->encrypt_1c($TwoStepCode);
        $_SESSION['TwoStepCodeTime'] = $Zfpf->encrypt_1c(time());
	    $Subject = 'PSM-CAP: Security Code for Two-Step Authentication';
	    $Body = '<p>
	    ******************<br />
	    ******************<br />
	    ******************<br />
	    Security Code for Two-Step Authentication<br />
	    ******************<br />
	    ******************<br />
	    ******************<br />
	    <br />
	    You\'ll get a new code each time you log on.<br /><br />
	    Your temporary security code is:<br />
	    '.$TwoStepCode.'</p>';
	    $Body = $Zfpf->email_body_append_1c($Body, '', '', '');
	    $EmailSent = $Zfpf->send_email_1c($EmailAddresses, $Subject, $Body);
        if ($EmailSent) {
            $TwoStepMessage = '<p>
            The PSM-CAP App attempted to email you a temporary security code, which will work for about <b>'.round(ALLOWED_TWO_STEP_SECONDS/60).' minutes</b>.</p>
            <form action="logon.php" method="post"><p>
                Copy and paste (or type) security code from email: <input type="text" name="security_code" maxlength="8" /></p><p>
                <input type="submit" name="two_step" value="Submit" /></p>
            </form>';
            $_SESSION['SaveActiveSessions'] = $Zfpf->encode_encrypt_1c($ActiveSessions);
            $_SESSION['Savet0user'] = $_SESSION['t0user'];
            unset($_SESSION['t0user']); // This prevents hacking the two-step by pointing browser at, for example, contents0.php
        }
        else {
            $TwoStepMessage = '<p>
            Please try to <a href="logon.php">log on</a> again because the app wasn\'t able to email you a security code for two-step authentication. If this problem persists, ask your supervisor or an app admin for assistance.</p>';
            $Zfpf->log_off_1c($DBMSresource); // t0user:c6active_sessions hasn't been updated, so can call this.
        }
        $FixedLeftContents = '<p>
        |PSM-CAP App<br />
        |Two-Step Log on</p>';
        echo $Zfpf->xhtml_contents_header_1c('PSM-CAP 2-Step', FALSE, $FixedLeftContents).'<h2>
        Two-Step Authentication</h2>
        '.$TwoStepMessage.$Zfpf->xhtml_footer_1c();
        $Zfpf->close_connection_1s($DBMSresource);
        exit; // Don't save and exit.
    }
    else {
        $OkayToLogOn = TRUE; 
        // TO DO FOR PRODUCTION VERSION Consider preventing logon without 2-step, then every user needs a recorded email address to log on.
        // TO DO FOR PRODUCTION VERSION Consider adding a log of logons, their IP addresses...
        // TO DO FOR PRODUCTION VERSION Also: consider checking IP address for all logons, send email (to admin?) if not in typical location.
        // TO DO FOR PRODUCTION VERSION Option: error_log(@$Zfpf->error_prefix_1c().' successful logon'); // Prefer avoid adding to error log.
    }
}
elseif (isset($_POST['two_step'])) {
    $DBMSresource = $Zfpf->credentials_connect_instance_1s('logon maintenance'); // Needed below.
    if (isset($_SESSION['TwoStepCodeTime']))
        $ElapsedTime = time() - $Zfpf->decrypt_1c($_SESSION['TwoStepCodeTime']);
    // $_POST['security_code'] not displayed to user or saved in database, so xxs and sql injection not a concern.
    if (!isset($_SESSION) or 
        !isset($_SESSION['Savet0user']) or
        !isset($_SESSION['SaveActiveSessions']) or
        !isset($_SESSION['TwoStepCode']) or
        !isset($_SESSION['TwoStepCodeTime']) or
        $ElapsedTime > ALLOWED_TWO_STEP_SECONDS or
        !isset($_POST['security_code']) or
        $Zfpf->decrypt_1c($_SESSION['TwoStepCode']) != substr($_POST['security_code'], 0, 8)
       ) {
        $Zfpf->log_off_1c($DBMSresource); // Clean up session. Do before CoreZfpf::xhtml_contents_header_1c so setcookie() can send HTML header.
        $Zfpf->close_connection_1s($DBMSresource);
        echo $Zfpf->xhtml_contents_header_1c('Bad 2-Step', FALSE, FALSE).'<h2>
        Two-step authentication was not successful.</h2><p>
        Please try to <a href="logon.php">log on</a> again because either:<br />
        - your temporary security code expired (after about '.round(ALLOWED_TWO_STEP_SECONDS/60).' minutes),<br />
        - you mistyped this security code,<br />
        - or some other error happened.</p><p>
        If this problem persists, ask your supervisor or an app admin for assistance.</p>
        '.$Zfpf->xhtml_footer_1c();
        exit; // Don't save and exit.
    }
    else {
        $_SESSION['t0user'] = $_SESSION['Savet0user'];
        unset($_SESSION['Savet0user']);
        $ActiveSessions = $Zfpf->check_array_1c($Zfpf->decrypt_decode_1c($_SESSION['SaveActiveSessions']));
        unset($_SESSION['SaveActiveSessions']);
        unset($_SESSION['TwoStepCode']);
        unset($_SESSION['TwoStepCodeTime']);
        $OkayToLogOn = TRUE;
    }
}
else
    $Zfpf->session_check_1c(); // This verifies successful logon, so far.

if (isset($OkayToLogOn)) {
    // Update database with current log-on information and reset attempts to zero.
    $EncryptedTime = $Zfpf->encrypt_1c(time());
    $Changes['c5ts_last_logon'] = $EncryptedTime;
    $Changes['c5attempts'] = $Zfpf->encrypt_1c(0);
    $Changes['c6active_sessions'] = $Zfpf->encode_encrypt_1c($ActiveSessions);
    if (!isset($Conditions)) // Two-step logon case.
        $Conditions[0] = array('k0user', '=', $_SESSION['t0user']['k0user']);
    $Affected = $Zfpf->update_sql_1s($DBMSresource, 't0user', $Changes, $Conditions, FALSE); // Successful log on update.
    if ($Affected != 1)
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__.' Affected: '.@$Affected);
    // Update $_SESSION, including session timeout parameters.
    unset($Changes['c5ts_last_logon']); // except $_SESSION['t0user']['c5ts_last_logon'] stays at the "last log on" time.
    foreach ($Changes as $K => $V)
        $_SESSION['t0user'][$K] = $V;
    $_SESSION['CheckPasswordSupplied'] = $EncryptedTime;
    $_SESSION['CheckLastAction'] = $EncryptedTime;
    // Add entry to k0session_ids, to lookup session if lost from server memory, via garbage collection...
    $NewRow = array(
        'k0session_ids' => time().mt_rand(1000000, 9999999),
        'k0user' => $_SESSION['t0user']['k0user'],
        'c5session_id' => $Zfpf->encrypt_1c(session_id())
    );
    $Zfpf->no_history_insert_sql_1s($DBMSresource, 't0session_ids', $NewRow); // Errors handled by function.
    $Zfpf->close_connection_1s($DBMSresource);
    // Check if required user information is stored in database
    // Typically this would be input when a user is created, but not for some initial users within organizations.
    if (
        $Zfpf->decrypt_1c($_SESSION['t0user']['c5name_given1']) == '[Nothing has been recorded in this field.]' or 
        $Zfpf->decrypt_1c($_SESSION['t0user']['c5e_contact_name']) == '[Nothing has been recorded in this field.]' or 
        $Zfpf->decrypt_1c($_SESSION['t0user']['c5e_contact_phone']) == '[Nothing has been recorded in this field.]' or 
        $Zfpf->decrypt_1c($_SESSION['t0user']['c5challenge_question1']) == '[Nothing has been recorded in this field.]' or 
        $Zfpf->decrypt_1c($_SESSION['t0user']['s5cq_answer_hash1']) == '[Nothing has been recorded in this field.]' or 
        $Zfpf->decrypt_1c($_SESSION['t0user']['c5challenge_question2']) == '[Nothing has been recorded in this field.]' or 
        $Zfpf->decrypt_1c($_SESSION['t0user']['s5cq_answer_hash2']) == '[Nothing has been recorded in this field.]' or 
        $Zfpf->decrypt_1c($_SESSION['t0user']['c5challenge_question3']) == '[Nothing has been recorded in this field.]' or 
        $Zfpf->decrypt_1c($_SESSION['t0user']['s5cq_answer_hash3']) == '[Nothing has been recorded in this field.]'
       ) {
        $_SESSION['Selected'] = $_SESSION['t0user']; // User will be editing their own personal information.
        $Zfpf->edit_lock_1c('user'); // This re-does SELECT query, checks edit lock, and if none, starts edit lock.
        $UserZfpf->user_i1($Zfpf, 'logon.php', 'logoff.php');
    }
    require INCLUDES_DIRECTORY_PATH_ZFPF.'/contents1.php'; // contents1.php echos and exits
}
// User-information missing next steps if triggered above.
if (isset($_POST['user_undo_confirm_post_1e']) or isset($_POST['user_modify_confirm_post_1e'])) {
    if (!isset($_SESSION['Selected']['k2username_hash']) or !isset($_SESSION['Scratch']['SelectDisplay']) or !isset($_SESSION['Post']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    if (isset($_POST['user_undo_confirm_post_1e'])) {
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Scratch']['SelectDisplay']);
        $_SESSION['Post'] = $_SESSION['Scratch']['SelectDisplay'];
    }
    else
        $Display = $Zfpf->decrypt_decode_1c($_SESSION['Post']);
    $UserZfpf->user_i1($Zfpf, 'logon.php', 'logoff.php', 'not_needed', $Display); // This function echos then exits.
}
elseif (isset($_POST['user_i2']))
    $UserZfpf->user_i2($Zfpf, 'logon.php', 'logon.php'); // Both confirm and go back actions from i2 code go to logon.php. Echos and exits.
elseif (isset($_POST['user_yes_confirm_post_1e'])) { // set by user_i2. Indicates run the user_i3 code
    if (!isset($_SESSION['Selected']['k2username_hash']) or !isset($_SESSION['Post']) or !isset($_SESSION['Scratch']['ModifiedValues']))
        $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
    $Finished = $UserZfpf->user_i3($Zfpf, FALSE, FALSE, 'logon maintenance');
    $_SESSION['t0user'] = $Finished['Row'];
    unset($_SESSION['Post']);
    unset($_SESSION['Selected']);
    echo $Zfpf->xhtml_contents_header_1c('Updated').'<h2>
    User Record Updated</h2>
    '.$Finished['Message'].'
    <form action="contents0.php" method="post"><p>
        <input type="submit" value="Continue" /></p>
    </form>'.$Zfpf->xhtml_footer_1c();
    $Zfpf->save_and_exit_1c();
}


// CoreZfpf::catch_all_1c won't work because it assumes user is logged in. Instead...
echo $Zfpf->xhtml_contents_header_1c('Logon Error', FALSE, FALSE).'<h2>
An error occurred.</h2><p>
Please contact your supervisor or an app admin for assistance.</p>
'.$Zfpf->xhtml_footer_1c();

