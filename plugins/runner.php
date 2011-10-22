<?php if (!defined('BASEPATH')) exit('No direct access allowed!');

class runner {
    function __construct($param = false) {
        if ($param) {
            foreach($param as $arg => $value) {
                $this->$arg = $value;
            }
        }
    }

    function run() {
        krsort($this->urls);
        $urls = $this->urls;

        // Load the kernel stuff.
        $requester = Charizard::load('requester');
        $status = Charizard::load('status_coder');
        // logger
    
        $base_url = Charizard::$base_url = $requester->base_url;
        $path = Charizard::$path = $requester->path;
        $current = Charizard::$current = $requester->current;
        if (!$path) {
            $status->_404();
        }
        $method = Charizard::$method = $requester->method;
        if (!$method) {
            $status->_405();
        }

        foreach($urls as $regex => $class) {
            $regex = str_replace('/', '\/', $regex);
            $regex = '^' . $regex . '\/?$';

            $found = false;
            
            if (preg_match("/$regex/i", $path, $matches)) {
                $found = true;
                
                if (class_exists($class)) {
                    $obj = new $class;
                    if (method_exists($obj, $method)) {
                        $obj->$method($matches[1]);
                    } else {
                        $status->_405();
                    }
                } else {
                    $status->_404("$class not found!! =@", 'error');
                }

                break;
            }
        }

        if (!$found) {
            $status->_404("$path not found!! =@", 'error');
        }
        return;
    }
}

?>
