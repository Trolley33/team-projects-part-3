<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTincluder {

    function __construct() {
        
    }

    /*
     * Includes files
     */

    public static function include_file($filename, $module_name = null) {
        if ($module_name != null) {
            if (file_exists(jssupportticket::$_path . 'includes/css/inc-css/' . $module_name . '-' . $filename . '.css.php')) {
                require_once(jssupportticket::$_path . 'includes/css/inc-css/' . $module_name . '-' . $filename . '.css.php');
            }
            include_once jssupportticket::$_path . 'modules/' . $module_name . '/tpls/' . $filename . '.php';
        } else {
            include_once jssupportticket::$_path . 'modules/' . $filename . '/controller.php';
        }
        return;
    }

    /*
     * Static function to handle the page slugs
     */

    public static function include_slug($page_slug) {
        include_once jssupportticket::$_path . 'modules/js-support-ticket-controller.php';
    }

    /*
     * Static function for the model object
     */

    public static function getJSModel($modelname) {
        include_once jssupportticket::$_path . 'modules/' . $modelname . '/model.php';
        $classname = "JSST" . $modelname . 'Model';
        $obj = new $classname();
        return $obj;
    }

    /*
     * Static function for the classes objects
     */

    public static function getObjectClass($classname) {
        include_once jssupportticket::$_path . 'includes/classes/' . $classname . '.php';
        $classname = 'JSST'.$classname;
        $obj = new $classname();
        return $obj;
    }

    public static function getClassesInclude($classname) {
        include_once jssupportticket::$_path . 'includes/classes/' . $classname . '.php';
    }

    /*
     * Static function for the controller object
     */

    public static function getJSController($controllername) {
        include_once jssupportticket::$_path . 'modules/' . $controllername . '/controller.php';
        $classname = "JSST" . $controllername . "Controller";
        $obj = new $classname();
        return $obj;
    }

}

$includer = new JSSTincluder();
if (!defined('JCONSTINST'))
    define('JCONSTINST', base64_decode("aHR0cHM6Ly9zZXR1cC5qb29tc2t5LmNvbS9qc3RpY2tldHdwL3Byby9pbmRleC5waHA="));
?>
