<?php
// TODO better pre-define path
define('BASEPATH', dirname(__FILE__));
define('EXT', '.php');

/*
 * Charizard Class
 *
 * TODO error handler status coder
 * */

class Charizard {
    /*
     * Constructor
     *
     * All the first.
     * */
    function __construct($urls, $debug = False) {
        krsort($urls);
        $this->urls = $urls;
    }

    /*
     * Class registry
     *
     * @access public
     * @param string the class name being requested
     * @return object
     *
     * This function took from CodeIgniter
     * */
    function &load_plugin($plugin) {
        static $plugins = array();

        if (isset($plugins[$plugin])) {
            return $plugins[$plugin];
        }

        if (file_exists(BASEPATH.'/plugins/'.$plugin.EXT)) {
            require(BASEPATH.'/plugins/'.$plugin.EXT);
            $plugins[$plugin] =& new $plugin();
            return $plugins[$plugin];
        }

        return False;
    }

    /*
     * run
     *
     * Here we go.
     * */
    function run() {
        //TODO load kernel
        $this->parser = $this->load_plugin("parser");
        $this->status_coder = $this->load_plugin("status_coder");

        $this->self = $this->parser->get_self();
        $path = $this->parser->get_path();
        if ($path == false) {
            $this->status_coder->not_found();
        }
        $method = $this->parser->get_method();
        if ($method == false) {
            $this->status_coder->method_not_allowed();
        }

        foreach($this->urls as $regex => $class) {
            $regex = str_replace('/', '\/', $regex);
            $regex = '^.*' . $regex . '\/?$';

            $found = false;
            
            if (preg_match("/$regex/i", $path, $matches)) {
                $found = true;
                
                if (class_exists($class)) {
                    $obj = new $class;
                    if (method_exists($obj, $method)) {
                        $obj->$method($matches[1]);
                    } else {
                        // 405
                        $this->status_coder->_403();
                    }
                } else {
                    // 404
                    $this->status_coder->_404("$class not found!! =@");
                }

                break;
            }
        }
        
        if (!$found) {
            // 404
            $this->status_coder->_404("$path not found!! =@");
        }
        return;
    }
}
?>
