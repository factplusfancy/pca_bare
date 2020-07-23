For free initial assistance in completing these instructions, please contact: 
james.hadley@factplusfancy.com

(1) Clone app from Github to the directory (called folder on Microsoft Windows) where you want the app. 
(1.1) If needed, install git. See: 
    https://git-scm.com/downloads
    https://git-scm.com/download/linux    || For very latest on Ubuntu... $sudo add-apt-repository ppa:git-core/ppa   $sudo apt-get update    $sudo apt-get install git
(1.2) For example, from the command line, in unix-like operating system:
    $ git clone https://github.com/factplusfancy/pca_bare /var/www/html/pca
(1.3) for background, see:
        https://git-scm.com/book/en/v2/Git-Basics-Getting-a-Git-Repository
        https://git-scm.com/docs/git-clone
        https://docs.github.com/en/github/creating-cloning-and-archiving-repositories/cloning-a-repository

(2) If not using MySQL, customize for your database management system (DBMS).

(2.1) Determine which DBMS you can and want to use, maybe by asking your organization's information-technology department. Or, for a stand-alone deployment on one computer, install LAMP, MAMP or WAMP on that computer. For example, see: 
    https://ubuntu.com/server/docs/lamp-applications
    https://www.mamp.info
    https://www.wampserver.com/en/
Notes: 
- As of 2020-07-21, this app runs on PHP 5 or higher, but safer to use a version supported by PHP, of course; see https://www.php.net/supported-versions.php. PHP must be installed on the server where this app will run. 
- This app can fairly easily be modified to run on almost any DBMS. MySQL is its default DBMS. 

(2.2) If MySQL isn't the DBMS you will use, create wrappers for your DBMS in all functions that use "mysqli" in file includes/CoreZfpf.php -- namely functions:
    connect_instance_1s
    close_connection_1s
    query_1s
    dbms_string_escape_1s (or use named placeholders and prepared statements)
    create_table_sql_1s (if need to handle tables with many columns, see includes/templates/schema.php)
    select_sql_1s
    update_sql_1s
    delete_sql_1s

(3) Customizing a deployment, including its security.

(3.1) Read and address comments in file: directory_path_settings.php

(3.2) Read and address all comments labeled "// TO DO FOR PRODUCTION VERSION", for example, in files:
    logon.php
    setup.php
    includes/CoreZfpf.php
    includes/FilesZfpf.php

(3.3) in /settings/CoreSettingsZfpf.php 

(3.3.1) change:
    USER_FILES_DIRECTORY_PATH_ZFPF      || for example, change pcaNAME
    HASH_SALT_ZFPF
    BINARY_KEY_ZFPF                     || KEEP COPIES OF THIS ENCRYPTION KEY IN SAFE AND SECURE PLACES. YOUR DATA WILL BE LOST IF YOU LOSE THIS.
    DATABASE_INSTANCE_ZFPF              || for example, change from psmcapmanual to define('DATABASE_INSTANCE_ZFPF', 'pcaNAME_db');
    USERNAME1_ZFPF to USERNAME4_ZFPF
    PASSWORD1_ZFPF to PASSWORD4_ZFPF

(3.3.2) review from "options variables: uncomment one per variable -- START" to "options variables -- END". Defaults are: 
    $OptionFiles = 'local'; // for a local deployment, with user files saved at /var/www/html/user_files 
    $OptionZipDownloadsWorks = FALSE; // cannot download multiple, zipped, files at once
    $OptionHazSub = 'anhydrous_ammonia_refrigeration'; // tailors app's wording for ammonia-refrigeration systems

(3.4) If using a front controller, read and address comments in file: front_controler.php

(4) Deploy the app
(4.1) In whatever directory you put the app's files and sub-directories, point your browser at file setup.php and follow instructions there. For example, for a local deployment on one computer, if you saved the app's files in a directory named "pca", in the "localhost" document root (which is often by default /var/www/html/ on Linux operating systems), point your browser at:
      http://localhost/pca/setup.php
(4.2) To install compliance practices tailored for ammonia-refrigeration systems, point your browser at setup_nh3r_practices.php in the same directory as step 4.1 above, for example: 
      http://localhost/pca/setup_nh3r_practices.php
(4.3) delete setup.php, setup_nh3r_practices.php, and any other setup files from the document root, if needed for security, such as the document root accessible to the public for a production deployment. If you are serving over the Internet, use SSL (https), of course.

(5) Point your browser at logon.php, logon with the username and password you created in setup.php, and follow the app's instructions to setups owners, facilities, and processes. Using the example in 4.1 above, point your browser at:
      http://localhost/pca/logon.php

