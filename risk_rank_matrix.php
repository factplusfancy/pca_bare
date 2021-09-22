<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License. 

// This files echos the risk-ranking matrix table only, on separate webpage, to avoid inserting the table in other webpages, which makes them hard to view on small screens, like mobile phones.

require 'directory_path_settings.php';
require INCLUDES_DIRECTORY_PATH_ZFPF.'/CoreZfpf.php';
$Zfpf = new CoreZfpf;

require INCLUDES_DIRECTORY_PATH_ZFPF.'/ccsaZfpf.php';
$ccsaZfpf = new ccsaZfpf; // Needed below

echo $Zfpf->xhtml_contents_header_1c('PSM-CAP', FALSE, FALSE); // FALSE, FALSE means no left-hand contents.
echo $ccsaZfpf->risk_rank_matrix($Zfpf);
echo $Zfpf->xhtml_footer_1c(FALSE); // FALSE means no left-hand contents.

