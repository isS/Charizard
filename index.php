<?php
require_once("Charizard.php");

$urls = array(
    '/' => 'index',
    '/welcome/([0-9a-zA-Z.]+)' => 'welcome',
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

$app = new Charizard($urls);
$app->run();
?>
