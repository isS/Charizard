<?php
class Charizard {
    var $urls;

    // TODO debug mode
    function __construct($urls) {
        krsort($urls);
        $this->urls = $urls;
    }

    // Here we go.
    function run() {
        // Now we take $path from PATH_INFO
        $path = $_SERVER['PATH_INFO'];
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        $this->_parse_path($path, $method);
    }

    function _parse_path($path, $method) {
        // I just take these code from [gluephp](gluephp.com),
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
                        $this->_show_error(405);
                    }
                } else {
                    // 404 We didn't find the class. =(
                    $this->_show_404();
                }

                break;
            }
        }

        if (!$found) {
            // 404 We didn't find it!! =@
            $this->_show_404();
        }

        return;
    }

    function _show_error($code = 500, $text = '') {
        // TODO: Generate styled error page
        $this->_set_status_code($code, $text);

        return;
    }

    function _show_404($text = '', $heading = '404') {
        // show 404 page
        // TODO: Fallback to a user-defined page.
        $this->_set_status_code(404);

        if ($text == '') {
            $text = "We didn't find it!! =@";
        }

        echo "<h1>$heading</h2>";
        echo "<p>$text</p>";

        exit;
    }

    function _set_status_code($code = 200, $text = '') {
        $error_text = array(
            200	=> 'OK',
            201	=> 'Created',
            202	=> 'Accepted',
            203	=> 'Non-Authoritative Information',
            204	=> 'No Content',
            205	=> 'Reset Content',
            206	=> 'Partial Content',

            // TODO seeother 303 and redirect 301
            300	=> 'Multiple Choices',
            301	=> 'Moved Permanently',
            302	=> 'Found',
            304	=> 'Not Modified',
            305	=> 'Use Proxy',
            307	=> 'Temporary Redirect',

            400	=> 'Bad Request',
            401	=> 'Unauthorized',
            403	=> 'Forbidden',
            404	=> 'Not Found',
            405	=> 'Method Not Allowed',
            406	=> 'Not Acceptable',
            407	=> 'Proxy Authentication Required',
            408	=> 'Request Timeout',
            409	=> 'Conflict',
            410	=> 'Gone',
            411	=> 'Length Required',
            412	=> 'Precondition Failed',
            413	=> 'Request Entity Too Large',
            414	=> 'Request-URI Too Long',
            415	=> 'Unsupported Media Type',
            416	=> 'Requested Range Not Satisfiable',
            417	=> 'Expectation Failed',

            500	=> 'Internal Server Error',
            501	=> 'Not Implemented',
            502	=> 'Bad Gateway',
            503	=> 'Service Unavailable',
            504	=> 'Gateway Timeout',
            505	=> 'HTTP Version Not Supported'
        );

        if ($code == '' || !is_numeric($code)) {
            $this->_show_error(500, "Status Code must be a numberic");
        }

        if (isset($error_code[$code]) && $text == '') {
            $text = $error_code[$code];
        }

        if ($text == '') {
            $this->_show_error(500, "Status Code is WRONG! or check your code text");
        }
	    
        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

		if (substr(php_sapi_name(), 0, 3) == 'cgi')
		{
			header("Status: {$code} {$text}", TRUE);
		}
		elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
		{
			header($server_protocol." {$code} {$text}", TRUE, $code);
		}
		else
		{
			header("HTTP/1.1 {$code} {$text}", TRUE, $code);
        }

        return;
    }
}
?>
