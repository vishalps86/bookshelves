<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

// viperks defined constant
defined('DOC_ROOT')             OR define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
defined('UPLOAD_DIR')           OR define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'].'/assets/upload/');
defined('UPLOAD_DIR_USER')      OR define('UPLOAD_DIR_USER', UPLOAD_DIR.'user/');
defined('UPLOAD_DIR_CAT')      OR define('UPLOAD_DIR_CAT', UPLOAD_DIR.'category/');
//defined('UPLOAD_DIR_CAT_URL')      OR define('UPLOAD_DIR_CAT_URL', 'assets/upload/category/');
defined('UPLOAD_DIR_CAT_URL')      OR define('UPLOAD_DIR_CAT_URL', 'upload/category/');
//defined('UPLOAD_DIR_USER_URL')  OR define('UPLOAD_DIR_USER_URL', 'assets/upload/user/');
defined('UPLOAD_DIR_USER_URL')  OR define('UPLOAD_DIR_USER_URL', 'upload/user/');
defined('UPLOAD_DIR_AFFILIATE') OR define('UPLOAD_DIR_AFFILIATE', UPLOAD_DIR.'affiliate/');


defined('UPLOAD_DIR_PRODUCT') OR define('UPLOAD_DIR_PRODUCT', UPLOAD_DIR.'product/');


defined('UPLOAD_DIR_ORGLOGO')      OR define('UPLOAD_DIR_ORGLOGO', UPLOAD_DIR.'organization/');
//defined('UPLOAD_DIR_ORGLOGO_URL')      OR define('UPLOAD_DIR_ORGLOGO_URL', 'assets/upload/organization/');
defined('UPLOAD_DIR_ORGLOGO_URL')      OR define('UPLOAD_DIR_ORGLOGO_URL', 'upload/organization/');

defined('UPLOAD_DIR_ORG')      OR define('UPLOAD_DIR_ORG', UPLOAD_DIR.'organization/');


defined('UPLOAD_DIR_VENDOR')      OR define('UPLOAD_DIR_VENDOR', UPLOAD_DIR.'vendor/');
//defined('UPLOAD_DIR_VENDOR_URL')      OR define('UPLOAD_DIR_VENDOR_URL', 'assets/upload/vendor/');
defined('UPLOAD_DIR_VENDOR_URL')      OR define('UPLOAD_DIR_VENDOR_URL', 'upload/vendor/');

defined('UPLOAD_DIR_BRAND')      OR define('UPLOAD_DIR_BRAND', UPLOAD_DIR.'brand/');
//defined('UPLOAD_DIR_BRAND_URL')      OR define('UPLOAD_DIR_BRAND_URL', 'assets/upload/brand/');
defined('UPLOAD_DIR_BRAND_URL')      OR define('UPLOAD_DIR_BRAND_URL', 'upload/brand/');

defined('FROM_EMAIL')           OR define('FROM_EMAIL', 'admin@viperks.net');
defined('FROME_NAME')           OR define('FROME_NAME', 'viperks');
defined('SITE_NAME')            OR define('SITE_NAME', 'Viperks');
defined('SITE_NAME_COM')        OR define('SITE_NAME_COM', 'viperks.net');
defined('EMAIL_TEMPLATE')       OR define('EMAIL_TEMPLATE',DOC_ROOT.'/application/views/email_template/template/email_template.php');


defined('SOURCE_DIR_FILES') OR define('SOURCE_DIR_FILES', $_SERVER['DOCUMENT_ROOT'].'/assets/files/');

defined('S3_ACCESS_KEY') OR define('S3_ACCESS_KEY', 'AKIAIU2F7BQXZFSXT6IA');
defined('S3_SECERET_KEY') OR define('S3_SECERET_KEY', '/f4bsz6COI0qcrsYOc2gR/E4JfnV9rG4YeFeylqb');
defined('S3_BUCKET') OR define('S3_BUCKET', 'viperks-images');
defined('S3_BUCKET_VIPERKS_PROD') OR define('S3_BUCKET_VIPERKS_PROD', 'viperks-prod');
defined('S3_BUCKET_VIPERKS_DEV') OR define('S3_BUCKET_VIPERKS_DEV', 'viperks-dev');


//defined('UPLOAD_DIR_PRODUCT_URL')      OR define('UPLOAD_DIR_PRODUCT_URL', 'assets/upload/product/');
defined('UPLOAD_DIR_PRODUCT_URL')      OR define('UPLOAD_DIR_PRODUCT_URL', 'upload/product/');


//defined('UPLOAD_DIR_AFFILIATE_URL')      OR define('UPLOAD_DIR_AFFILIATE_URL', 'assets/upload/affiliate/');
defined('UPLOAD_DIR_AFFILIATE_URL')      OR define('UPLOAD_DIR_AFFILIATE_URL', 'upload/affiliate/');

defined('S3_BASE_URL')      OR define('S3_BASE_URL', 'https://s3.amazonaws.com/viperks-images/');
defined('S3_BASE_URL_PROD')      OR define('S3_BASE_URL_PROD', 'https://s3.amazonaws.com/viperks-prod/');

defined('UPLOAD_DIR_BANNERS')  OR define('UPLOAD_DIR_BANNERS', UPLOAD_DIR.'banners/');


defined('UPLOAD_DIR_PO')      OR define('UPLOAD_DIR_PO', UPLOAD_DIR.'po/');


