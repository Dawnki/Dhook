<?php

require __DIR__ . '/Config.php';

require __DIR__ . '/Dhook.php';

try {
    $hook = new Dhook();
    $hook->run();
} catch (Exception $exception) {
    header('HTTP/1.1 500 Fail');
    echo $exception->getMessage();
    return;
}

header('HTTP/1.1 200 OK');
echo 'success';
return;