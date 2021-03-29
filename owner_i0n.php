<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This i0n file just provides some instructions and sends the user to the i0n code in owner_io03.php.
// Called via a link in administer1.php Administration -- App Admin Options

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;
$Zfpf->session_check_1c();

if ($Zfpf->decrypt_1c($_SESSION['t0user']['c5app_admin']) != 'Yes' or $Zfpf->decrypt_1c($_SESSION['t0user']['c5p_global_dbms']) != MAX_PRIVILEGES_ZFPF)
    $Zfpf->eject_1c(@$Zfpf->error_prefix_1c().__FILE__.':'.__LINE__);
else
    $_SESSION['Scratch']['PlainText']['SecurityToken'] = 'owner_i1m.php';

echo $Zfpf->xhtml_contents_header_1c().'<h2>
Add a new Owner/Operator (aka owner) record</h2><p>
To do this, you will also need to create the initial user and user-owner records for this owner\'s first administrator (admin).</p><p>
This user will be given the maximum owner privileges but not app admin privileges.</p><p>
You will be associated with this owner, via a user-owner record, but you will not be given any owner privileges.</p>
<form action="owner_io03.php" method="post"><p>
    <input type="submit" name="owner_i0n" value="Create records for a new owner" /></p>
</form>
<form action="administer1.php" method="post"><p>
    <input type="submit" value="Back to administration" /></p>
</form>
'.$Zfpf->xhtml_footer_1c();

$Zfpf->save_and_exit_1c();

