<?php

namespace Tarcha\WebKernel\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->types['Aura\Web\Request'] = $di->lazyGet('aura/web-kernel:request');
        $di->types['Aura\Web\Response'] = $di->lazyGet('aura/web-kernel:response');
        $di->types['Aura\Router\Router'] = $di->lazyGet('aura/web-kernel:router');
        $di->types['Aura\Dispatcher\Dispatcher'] = $di->lazyGet('aura/web-kernel:dispatcher');

        // Tarcha\WebKernel\WebKernelRouter
        $di->params['Tarcha\WebKernel\WebKernelRouter'] = array(
            'request' => $di->lazyGet('aura/web-kernel:request'),
            'router' => $di->lazyGet('aura/web-kernel:router'),
            'logger' => $di->lazyGet('tarcha/project-kernel:logger'),
        );

        // Tarcha\WebKernel\WebKernelDispatcher
        $di->params['Tarcha\WebKernel\WebKernelDispatcher'] = array(
            'request' => $di->lazyGet('aura/web-kernel:request'),
            'dispatcher' => $di->lazyGet('aura/web-kernel:dispatcher'),
            'logger' => $di->lazyGet('tarcha/project-kernel:logger'),
        );

    }

    public function modify(Container $di)
    {

    }
}
