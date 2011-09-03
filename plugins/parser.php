<?php
class parser {
    function __construct() {
        # NEED more testing...
        $this->path = preg_replace('/\\?.*$/', '', $_SERVER['REQUEST_URI']);
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->self = preg_replace('/'.SCRIPT_NAME.'/', '', $_SERVER['SCRIPT_NAME']);
    }

    function get_self() {
        if (isset($this->self)) {
            return $this->self;
        }

        return false;
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
