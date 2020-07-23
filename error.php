<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2020 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;

echo $Zfpf->xhtml_contents_header_1c('PSM-CAP Error', FALSE, '').'<h2>
Oops... an error occurred.</h2><p>

Please do not hesitate to contact <a href="mailto:info@factplusfancy.com">info@factplusfancy.com</a> for assistance.</p><p>

<b>Legal Notice:</b> Attempting to access or accessing portions of this app or website that you are not authorized to access is strictly prohibited and against the law. Any disclosure, printing, copying, distribution, transmission, reliance on for either actions or inactions, or other use of any information obtained (either directly or indirectly) from portions of this app or website that you are not authorized to access is also strictly prohibited and against the law.</p>
'.$Zfpf->xhtml_footer_1c();

// Don't save and exit.

