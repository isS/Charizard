<?php
define('BASEPATH', realpath(dirname(__FILE__)));
define('EXT', '.php');
define('PLUGINS', realpath(BASEPATH.'/plugins').'/');
define('TPL', realpath(BASEPATH.'/tpl').'/');
define('LOGFILE', realpath(BASEPATH.'log.txt'));

require_once('Charizard'.EXT);

$urls = array(
    '/name/dude' => 'dude',
    '/words' => 'hello'
);

class dude {
    function GET() {
        $s = Charizard::load('status_coder');
        $s->_301('lol');
    }
}

Charizard::run($urls, '', true);
?>
