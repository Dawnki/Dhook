<?php

require_once __DIR__ . '/Config.php';

require_once __DIR__ . '/ErrorRecord.php';

require_once __DIR__ . '/Dhook.php';

try {
    $dhook = new Dhook();
    $dhook->run();
} catch (Exception $e) {
    $code = $e->getCode();
    switch ($code) {
        case 1100:
            header('HTTP/1.1 450 EVENT');
            break;
        case 1101:
            header('HTTP/1.1 451 POST');
            break;
        case 1102:
            header('HTTP/1.1 452 REMOTE');
            break;
        case 1103:
            header('HTTP/1.1 453 TOKEN');
            break;
        default:
            header('HTTP/1.1 454 OTHER');
            break;
    }
    echo $e->getMessage();
    return;
}

header('HTTP/1.1 200 OK');
echo '更新成功';
return;
