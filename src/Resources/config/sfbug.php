<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Emrdev\SfBugBundle\Controller\SfbugController;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(SfbugController::class)
        ->public()
        ->args([
            param('kernel.project_dir'),
            service('twig'),
            param('data_collector.templates'),
            service('profiler')->nullOnInvalid()
        ]);
};
