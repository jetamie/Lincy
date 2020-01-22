<?php
namespace library\Middleware;

class Handle extends Interfacer
{
    /**
     * @var Interfacer[]
     */
    protected $_decorate = [];

    public function addMiddleware(Interfacer $middleware)
    {
        $this->_decorate[] = $middleware;
    }

    public function handle()
    {
        // TODO: Implement handle() method.
        if (!empty($this->_decorate)) {
            foreach ($this->_decorate as $middleware) {
                $middleware->before();
                $middleware->handle();
                $middleware->after();
            }
        }
    }
}