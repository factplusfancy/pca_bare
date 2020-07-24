For free initial assistance in completing these instructions, you may contact: 
James D. Hadley, P.E.
Fact Fancy, LLC
Newton, Massachusetts, USA
james.hadley@factplusfancy.com

(1) Clone the PSM-CAP App files from GitHub to the directory ("folder" on Microsoft Windows) where you want to host the app. 
(1.1) Install Git, if not already installed. See: 
    https://git-scm.com/downloads
    https://git-scm.com/download/linux
(1.1.1) On Ubuntu, for the latest stable upstream Git version... 
    $ sudo add-apt-repository ppa:git-core/ppa
    $ sudo apt-get update
    $ sudo apt-get install git
(1.2) For example, to clone from the command line, with a unix-like operating system:
    $ git clone https://github.com/factplusfancy/pca_bare /var/www/html/pca
(1.3) For background, see:
    https://git-scm.com/book/en/v2/Git-Basics-Getting-a-Git-Repository
    https://git-scm.com/docs/git-clone
    https://docs.github.com/en/github/creating-cloning-and-archiving-repositories/cloning-a-repository

(2) If not using MySQL, customize class CoreZfpf, in PSM-CAP App file includes/CoreZfpf.php, for your database management system (DBMS).
=> Getting help with this step: 
    * There is a high probability that, free of charge, Fact Fancy, LLC, can help you and code the "wrappers" for non-MySQL deployments within 30 days, but 
    * some coordination and testing would still be required on your part.

(2.1) Determine which DBMS you can and want to use, maybe by asking your organization's information-technology department. Or, for a stand-alone deployment on one computer, install LAMP, MAMP or WAMP on that computer. For example, see: 
    https://ubuntu.com/server/docs/lamp-applications
    https://www.mamp.info
    https://www.wampserver.com/en/
Notes: 
- PHP must be installed on the computer (the server) where this app will run. 
- As of 2020-07-24, this app runs on PHP 5 or higher, but safer to use a version supported by PHP, of course. 
  See https://www.php.net/supported-versions.php. 
- This app can fairly easily be modified to run on almost any DBMS. MySQL is its default DBMS. 

(2.2) If MySQL isn't the DBMS you will use, create wrappers for your DBMS in all functions that use "mysqli" in PSM-CAP App file includes/CoreZfpf.php -- namely functions:
    connect_instance_1s
    close_connection_1s
    query_1s
    dbms_string_escape_1s (or use named placeholders and prepared statements)
    create_table_sql_1s (if a wrapper is needed for your DBMS to handle database tables with a lot of columns, see includes/templates/schema.php)
    select_sql_1s
    update_sql_1s
    delete_sql_1s

(3) Customize your deployment, including its security.

(3.1) Read and address comments in PSM-CAP App file: directory_path_settings.php

(3.2) Read and address all comments labeled "// TO DO FOR PRODUCTION VERSION", for example, in PSM-CAP App files:
    logon.php
    setup.php
    includes/CoreZfpf.php
    includes/FilesZfpf.php

(3.3) In PSM-CAP App file /settings/CoreSettingsZfpf.php 

(3.3.1) change the definitions for the following PHP constants:
    USER_FILES_DIRECTORY_PATH_ZFPF      || for example, change pcaNAME
    HASH_SALT_ZFPF
    BINARY_KEY_ZFPF                     || KEEP COPIES OF THE PASSWORD YOU USE TO GENERATE THE ENCRYPTION KEY 
                                        || IN SAFE AND SECURE PLACES. 
                                        || YOUR DATA WILL BE LOST FOREVER IF YOU LOSE THIS.
    DATABASE_INSTANCE_ZFPF              || for example, change from psmcapmanual to define('DATABASE_INSTANCE_ZFPF', 'pcaNAME_db');
    USERNAME1_ZFPF to USERNAME4_ZFPF
    PASSWORD1_ZFPF to PASSWORD4_ZFPF

=> remember to:
|| KEEP COPIES OF THE PASSWORD YOU USE TO GENERATE THE ENCRYPTION KEY 
|| IN SAFE AND SECURE PLACES. 
|| YOUR DATA WILL BE LOST FOREVER IF YOU LOSE THIS.

(3.3.2) review from "options variables: uncomment one per variable -- START" to "options variables -- END". Defaults are: 
    $OptionFiles = 'local'; // for a local deployment, with user files saved at /var/www/html/user_files 
    $OptionZipDownloadsWorks = FALSE; // cannot download multiple, zipped, files at once
    $OptionHazSub = 'anhydrous_ammonia_refrigeration'; // tailors the app's wording for ammonia-refrigeration systems

(3.4) If using a front controller, read and address comments in file: front_controller.php

(3.5) Add and commit the above changes using Git. For example, with a unix-like operating system:
    $ cd /var/www/html/pca
    $ git add --all
    $ git commit -m 'deployment specific changes'

(4) Deploy and customize the PSM-CAP App (the app). 
Notes:
- For your DBMS deployment, you will need the password for a username with administrative privileges to do these steps.
- The default MySQL admin username is "root".

(4.1) In whatever directory you put the app's files, point your Web browser at PSM-CAP App file setup.php and follow the instructions there. 
For a local deployment on one computer, if you saved the app's files in a directory named "pca", in the "localhost" document root, 
for example /var/www/html/pca/ by default on some Linux operating systems, point your browser at: 
    http://localhost/pca/setup.php
    Note: If you are serving over the Internet, use SSL (https), of course.

(4.2) To install compliance practices tailored for ammonia-refrigeration systems, point your browser at PSM-CAP App file setup_nh3r_practices.php in the same directory as step 4.1 above, and follow the instructions there, for example: 
    http://localhost/pca/setup_nh3r_practices.php

(4.3) Use Git to remove setup.php, setup_nh3r_practices.php, and any other setup files from the document root, if needed for security, such as if the document root is accessible to the public for a production deployment. For example, with a unix-like operating system:
    $ cd /var/www/html/pca
    $ git rm setup.php
    $ git rm setup_nh3r_practices.php
    $ git commit -m 'removed setup files for security'

(4.4) Point your browser at logon.php, logon with the username and password you created in setup.php, and follow the app's instructions to setups owners, facilities, and processes. Using the example in Step 4.1, above, point your browser at:
    http://localhost/pca/logon.php
    Note: contact james.hadley@factplusfancy.com for free template files to automate setup of owners, facilities, processes, and some admin users.

