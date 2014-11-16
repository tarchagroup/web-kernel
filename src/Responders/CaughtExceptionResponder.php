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
namespace Tarcha\WebKernel\Responders;

use Exception;

/**
 *
 * A action for when the web kernel catches an exception.
 *
 * @package Aura.Web_Kernel
 *
 */
class CaughtExceptionResponder extends AbstractResponder
{
    /**
     *
     * Invokes the responder.
     *
     * @return null
     *
     */
    public function __invoke()
    {
        $class = get_class($this->data['exception']);
        $export = var_export($this->data['params'], true);
        $content = "Exception '{$class}' thrown for "
                 . "{$this->data['method']} {$this->data['path']}"
                 . PHP_EOL . PHP_EOL
                 . "Params: {$export}"
                 . PHP_EOL . PHP_EOL
                 . (string) $this->data['exception']
                 . PHP_EOL;
        $this->response->status->set('500', 'Server Error');
        $this->response->content->set($content);
        $this->response->content->setType('text/plain');
    }
}
