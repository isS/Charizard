<?php
class parser {
    function __construct() {
        $this->path = $_SERVER['PATH_INFO'];
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
    }

    function get_path() {
        if (isset($this->path)) {
            return $this->path;
        }

        return false;
    }

    function get_method() {
        if (isset($this->method)) {
            return $this->method;
        }

        return false;
    }
}
?>
