<?php
require_once("Charizard.php");

$urls = array(
    '/' => 'index',
    '/welcome/([0-9a-zA-Z.]+)' => 'welcome',
    '/redirect' => 'redirect',
    '/cookiejar' => 'cookiejar',
    '/cookiestore' => 'cookiestore',
);

class index {
    function GET() {
        echo 'Hello dude =)';

        return;
    }
}

class welcome {
    function GET($name) {
        echo "Hello $name.";
        return;
    }
}

class cookiejar {
    function GET() {
        $sess = Charizard::load('sessioner');
        $key = 74107410;
        $sess->create($key, 600);
        echo "cookie set <br />";
    }
}

class cookiestore {
    function GET() {
        $sess = Charizard::load('sessioner');
        $key = 74107410;
        $cookie = $sess->get($key);
        var_dump($cookie);
        $sess->destory();
        echo "cookie destory<br />";
    }
}

class redirect {
    function GET() {
        $status = Charizard::load('status_coder');
        $status->_301('http://www.google.com', Charizard::$base_url);
    }
}

Charizard::$log = false;
Charizard::run($urls);
?>
