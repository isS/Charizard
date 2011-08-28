<?php
class Charizard {
    var $urls;

    function __construct($urls) {
        krsort($urls);
        $this->urls = $urls;
    }

    // Here we go.
    function run($urls) {
        // Now we take $path from PATH_INFO
        $path = $_SERVER['PATH_INFO'];
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        $this->_parse_path($path, $method);
    }

    function _parse_path($path, $method) {
        // I just take the code from [gluephp](gluephp.net),
        // may be someday I'll rewrite it. =)
        
        foreach($this->urls as $regex => $class) {
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
                        // We didn't find the method. =(
                        echo "We didn't find the Method $class->$method. =(";
                    }
                } else {
                    // We didn't find the class. =(
                    echo "We didn't find the Class $class. =(";
                }

                break;
            }
        }

        if (!$found) {
            // We didn't find it!! =@
            echo "We didn't find your request. =@";
        }

        return;
    }
}
?>
