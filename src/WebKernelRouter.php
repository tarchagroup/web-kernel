<?php

namespace Tarcha\WebKernel;

use Aura\Web_Kernel\WebKernelRouter as AuraWebKernelRouter;

class WebKernelRouter extends AuraWebKernelRouter
{
    /**
     *
     * Given a URL path, gets a matching route from the router.
     *
     * @param string $path The URL path.
     *
     * @return string
     *
     */
    protected function getRoute($path)
    {
        $verb = $this->request->method->get();
        $this->logger->info(__CLASS__ . " $verb $path");
        $route = $this->router->match($path, $this->request->server->get());
        //$this->logRoutesTried();
        return $route;
    }
}
