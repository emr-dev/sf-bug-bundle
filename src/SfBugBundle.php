<?php
namespace Emrdev\SfBugBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfBugBundle extends Bundle
{
    public function boot()
    {
        if ('prod' === $this->container->getParameter('kernel.environment')) {
            @trigger_error('Using SfBugBundle in production is not supported and puts your project at risk, disable it.', \E_USER_WARNING);
        }
    }
}
