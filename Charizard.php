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
        self::$urls = $urls;

        $runner = Charizard::load('runner', array('urls' => $urls));
        $runner->run();
    }
}
?>
