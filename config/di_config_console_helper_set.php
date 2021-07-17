<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

define('CONTAINER_CONSOLE_HELPER_SET', 'console_helper_set');

return [
    CONTAINER_CONSOLE_HELPER_SET => function () {
        return ConsoleRunner::createHelperSet(DI\get(CONTAINER_ENTITY_MANAGER));
    }
];
    