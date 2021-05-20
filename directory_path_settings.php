<?php
// *** LEGAL NOTICES *** 
// Copyright 2019-2021 Fact Fancy, LLC. All rights reserved. Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

// This PHP file defines the directories where the app will look for files to include.
// 
// PHP require is used rather than PHP include to halt PHP execution if the desired file is not found.
// 
// Settings are placed in a separate directory so they can be placed in a special secure location,
// since they contain keys and hash salts.
// This also helps assure only setting files will be edited on installation.
// 
// The default constant definitions below below assume the /settings/ and /includes/ 
// directories will be in the main app directory.
//
// Typically, a more secure option is to move them to a directory that isn't served up by a web server.
// To do this, replace the constant definitions below with the correct 
// directory path to the /settings/ and /includes/ directories, in single quotes. 
// For instance, if you are using Microsoft Windows, in the define() function below 
// you could replace:
// realpath(dirname(__FILE__)) . '/settings'
// with:
// 'C:/secure_directory_name/app_name/settings'
// 
// Note: forward slash (/) is used to separate directories here because in Microsoft Windows 
// either forward or back slash (/ or \) can be used to separate directories, 
// whereas UNIX and similar operating systems use only forward slash (/).
// PHP 5.3 and above can replace dirname(__FILE__) with __DIR__

define('APP_DIRECTORY_PATH_ZFPF', realpath(dirname(__FILE__)));
define('SETTINGS_DIRECTORY_PATH_ZFPF', realpath(dirname(__FILE__)) . '/settings');
define('INCLUDES_DIRECTORY_PATH_ZFPF', realpath(dirname(__FILE__)) . '/includes');

// Call below here because this file is called by all files in this app, which always need a session started.
// session_name('custom_session_name'); // Use this if two app deployments will be viewed on same browser.
                                        // PHP default is PHPSESSID
                                        // PSM-CAP App default is PHPSESSID_psmcapmanual
                                        // Must call before session start.
session_start();

