<?php
namespace Tarcha\WebKernel\Responders;

use Aura\Web\Response;
use Aura\Web_Kernel\AbstractResponder as AuraAbstractResponder;
use Tarcha\WebKernel\Payloads\PayloadInterface;

abstract class AbstractResponder extends AuraAbstractResponder
{

    protected $available = [];

    protected $response;

    protected $payload;

    protected $payload_methods = [];

    private $kernel_payload_methods = [
        'Tarcha\WebKernel\Payloads\NoContent'     => 'noContent',
        'Tarcha\WebKernel\Payloads\Error'         => 'error',
        'Tarcha\WebKernel\Payloads\NotFound'      => 'notFound',
        'Tarcha\WebKernel\Payloads\NotRecognized' => 'notRecognized',
        'Tarcha\WebKernel\Payloads\Success'       => 'success',
        'Tarcha\WebKernel\Payloads\AlreadyExists' => 'alreadyExists',
        'Tarcha\WebKernel\Payloads\Invalid'       => 'noContent',
        'Tarcha\WebKernel\Payloads\Created'       => 'created'
    ];


    public function __construct(
        Response $response
    ) {
        $this->response = $response;
        $this->init();
    }

    protected function init()
    {
        // merge payload methods with builtin defaults
        $this->payload_methods
            = array_merge($this->kernel_payload_methods, $this->payload_methods);
    }

    public function __invoke()
    {
        $class = get_class($this->payload);
        $method = isset($this->payload_methods[$class])
                ? $this->payload_methods[$class]
                : 'notRecognized';
        $this->$method();
        return $this->response;
    }

    public function setPayload(PayloadInterface $payload)
    {
        $this->payload = $payload;
    }

    protected function noContent()
    {
        $this->response->status->set(204);
    }

    protected function notRecognized()
    {
        $domain_status = $this->payload->get('status');
        $this->response->status->set('500');
        $this->response->content
            ->set("Unknown domain payload status: '$domain_status'");
        return $this->response;
    }

    protected function notFound()
    {
        $this->response->status->set(404);
    }

    protected function error()
    {
        $e = $this->payload->get('exception');
        $this->response->status->set('500');
        $this->response->content->set($e->getMessage());
    }
    
    protected function invalid()
    {
        $e = $this->payload->get('message');
        $this->response->status->set('400');
        $this->response->content->set($e->getMessage());
    }

    // demograph
    protected function json()
    {
        $data = $this->payload->get();
        $this->response->status->set('200');
        $this->response->content->set(json_encode($data));
        $this->response->content->setType('application/json');
        return $this->response;
    }

    protected function success()
    {
        $this->response->status->set('200');
        $this->response->content->set('success');
        return $this->response;
    }

    protected function created()
    {
        $data = $this->payload->get();
        $this->response->status->set('201');
        $this->response->content->set($data);
        return $this->response;
    }

    protected function alreadyExists()
    {
        $data = $this->payload->get('exception');
        $this->response->status->set('422');
        $this->response->content->set($e->getMessage());
        return $this->response;
    }
}
