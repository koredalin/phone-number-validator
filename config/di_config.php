<?php

use App\Common\Interfaces\DateTimeManagerInterface;
use App\Common\DateTimeManager;
use App\Common\Interfaces\PasswordGeneratorInterface;
use App\Common\PasswordGenerator;
use App\Common\Interfaces\ConfirmationCodeGeneratorInterface;
use App\Common\ConfirmationCodeGenerator;

$containerDeclarations = [
    
    DateTimeManagerInterface::class => DI\create(DateTimeManager::class),
    
    PasswordGeneratorInterface::class => DI\create(PasswordGenerator::class),
    
    ConfirmationCodeGeneratorInterface::class => DI\create(ConfirmationCodeGenerator::class),
];
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_router.php');
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_query_builder.php');
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_entity_manager.php');
//$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_console_helper_set.php');
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_entities.php');
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_repositories.php');
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_repository_services.php');
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_queries.php');
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_twig.php');
$containerDeclarations = array_merge($containerDeclarations, require_once __DIR__.DIRECTORY_SEPARATOR.'di_config_controllers.php');

return $containerDeclarations;