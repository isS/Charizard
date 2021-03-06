<?php if (!defined('BASEPATH')) exit('No direct access allowed!');

class Charizard {
    static $plugins = array();
    static $urls;
    static $path;
    static $base_url;
    static $current;
    static $method;
    static $log = false;

    static function &load($plugin, $param = false) {
        if (isset(self::$plugins[$plugin])) {
            return self::$plugins[$plugin];
        }

        if (file_exists(PLUGINS.$plugin.EXT)) {
            require(PLUGINS.$plugin.EXT);
            if ($param) {
                self::$plugins[$plugin] = &new $plugin($param);
            } else {
                self::$plugins[$plugin] = &new $plugin();
            }
            return self::$plugins[$plugin];
        }
    }

    static function run($urls, $log = false) {
        if (self::$log != $log) {
            self::$log = $log;
        }
        krsort($urls);
        self::$urls = $urls;

        // Load the kernel stuff.
        $requester = self::load('requester');
        $status = self::load('status_coder');
        $logger = self::load('logger', array('file' => LOGFILE, 'enable' => $log));

        // Get the request.
        $base_url = self::$base_url = $requester->base_url;
        $path = self::$path = $requester->path;
        $current = self::$current = $requester->current;
        if (!$path) {
            $logger->log("$path didn't found.", 'error');
            $status->_404();
        }
        $method = self::$method = $requester->method;
        if (!$method) {
            $logger->log("$method didn't found.", 'error');
            $status->_405();
        }

        // Find the controller.
        foreach($urls as $regex => $class) {
            $regex = str_replace('/', '\/', $regex);
            $regex = '^' . $regex . '\/?$';

            $found = false;
            
            if (preg_match("/$regex/i", $path, $matches)) {
                $found = true;
                
                if (class_exists($class)) {
                    $obj = new $class;
                    if (method_exists($obj, $method)) {
                        $logger->log("$path found.", 'success');
                        $obj->$method($matches[1]);
                    } else {
                        $logger->log("$method doesn't exists.", 'error');
                        $status->_405();
                    }
                } else {
                    $logger->log("$class doesn't exists.");
                    $status->_404("$class not found!! =@", 'error');
                }

                break;
            }
        }

        if (!$found) {
            $logger->log("$path not found.");
            $status->_404("$path not found!! =@", 'error');
        }
        return;
    }
}
?>
