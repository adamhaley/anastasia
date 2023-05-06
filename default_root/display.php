<?php

require "control/require.php";

if(!$_GET[\P]) {
    $_GET[\P] = 'home';
}
$f = new frontend($PHP_SELF, $REQUEST_URI, $_GET, $_POST, $_SESSION);

echo $f->action();
