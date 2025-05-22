<?php

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function () {
    return new \App\Kernel('prod', false);
};
