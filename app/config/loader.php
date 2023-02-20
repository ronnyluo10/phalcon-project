<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->libraryDir,
        $config->application->enumsDir,
        $config->application->datatablesDir,
        $config->application->errorsDir,
    ]
);

$loader->registerFiles(
    [
        $config->application->helpersFile,
    ]
);

$loader->register();
