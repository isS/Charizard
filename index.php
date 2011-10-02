<?php
define('BASEPATH', realpath(dirname(__FILE__)));
define('EXT', '.php');
define('PLUGINS', realpath(BASEPATH.'/plugins').'/');
define('TPL', realpath(BASEPATH.'/tpl').'/');
define('LOGFILE', realpath(BASEPATH.'log.txt'));

require_once('Charizard'.EXT);

$urls = array(
    '/' => 'index_handler',
    '/about' => 'about_handler',
);

class about_handler {
    function GET() {
        $render = Charizard::load('render');
        $template_values = array(
            'title' => 'Charizard',
            'u' => Charizard::load('url_helper')
        );
        $path = 'inner.html';
        echo $render->rende($path, $template_values);
    }
}

class index_handler {
    function GET() {
        $render = Charizard::load('render');
        $template_values = array(
            'title' => 'Charizard',
            'u' => Charizard::load('url_helper')
        );
        $path = 'index.html';
        echo $render->rende($path, $template_values);
    }
}

Charizard::run($urls, '', true);
?>
