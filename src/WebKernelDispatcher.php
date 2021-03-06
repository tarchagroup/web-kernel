<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Web_Kernel
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Tarcha\WebKernel;

use Aura\Web\Request;
use Aura\Dispatcher\Dispatcher;
use Exception;
use Psr\Log\LoggerInterface;

/**
 *
 * Web kernel dispatcher logic.
 *
 * @package Aura.Web_Kernel
 *
 */
class WebKernelDispatcher
{
    /**
     *
     * A web (not HTTP!) request object.
     *
     * @var Request
     *
     */
    protected $request;

    /**
     *
     * A web dispatcher.
     *
     * @var Dispatcher
     *
     */
    protected $dispatcher;

    /**
     *
     * A PSR-3 logger.
     *
     * @var LoggerInterface
     *
     */
    protected $logger;

    /**
     *
     * Constructor.
     *
     * @param Request $request A web request object.
     *
     * @param Dispatcher $dispatcher A web dispatcher.
     *
     * @param LoggerInterface $logger A PSR-3 logger.
     *
     */
    public function __construct(
        Request $request,
        Dispatcher $dispatcher,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    /**
     *
     * Dispatches the request.
     *
     * @return null
     *
     */
    public function __invoke()
    {
        $action = $this->request->params->get('action');
        $this->logControllerValue($action);
        $this->checkForMissingController($action);
        try {
            $this->dispatcher->__invoke($this->request->params->get());
        } catch (Exception $e) {
            $this->caughtException($e);
        }
    }

    /**
     *
     * Logs the action to be dispatched to.
     *
     * @param mixed $action The action to be dispatched to.
     *
     * @return null
     *
     */
    protected function logControllerValue($action)
    {
        if (is_object($action)) {
            $what = 'object';
        } else {
            $what = $action;
        }
        $this->logger->info(__CLASS__ . ' dispatching '  . $what);
        
        // https://docs.newrelic.com/docs/agents/php-agent/features/php-frameworks-integrating-support-new-relic#dev
        if (extension_loaded('newrelic')) {
            newrelic_name_transaction($what);
        }
    }

    /**
     *
     * Check for a missing action.
     *
     * @param mixed $action The action to be dispatched to.
     *
     * @return null
     *
     */
    protected function checkForMissingController($action)
    {
        $exists = is_object($action)
               || $this->dispatcher->hasObject($action);
        if ($exists) {
            return;
        }

        $this->logger->warning(__METHOD__ . " missing action '$action'");
        $this->request->params['action']  = 'aura.web_kernel.missing_action';
        $this->request->params['missing_action'] = $action;
    }

    /**
     *
     * Caught an exception while dispatching.
     *
     * @param Exception $exception The caught exception.
     *
     * @return null
     *
     */
    protected function caughtException(Exception $e)
    {
        $this->logger->critical($e);
        $this->dispatcher->__invoke([
            'action' => 'aura.web_kernel.caught_exception',
            'exception' => $e,
        ]);
    }
}
