<?php

use Go\Core\AspectKernel;
use Go\Core\AspectContainer;

use App\Aspects\RepositoryCacheAspect;

/**
 * Application Aspect Kernel
 */
class ApplicationAspectKernel extends AspectKernel
{
    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     *
     * @param AspectContainer $container
     */
    protected function configureAop(AspectContainer $container)
    {
        $cache = app('cache');
        $container->registerAspect(new RepositoryCacheAspect($cache));
    }
}