<?php

/**
 * Class TransactionalApplication
 */
class TransactionalApplication extends \Laravel\Lumen\Application
{
    /**
     * Override callControllerCallable method - wraps every request in a db transaction
     *
     * @param  callable  $callable
     * @param  array  $parameters
     * @return \Illuminate\Http\Response
     */
    protected function callControllerCallable(callable $callable, array $parameters = [])
    {
        try {
            return $this->prepareResponse(
                app('db')->transaction(function () use ($callable, $parameters) {
                    return $this->call($callable, $parameters);
                })
            );
        } catch (HttpResponseException $e) {
            return $e->getResponse();
        }
    }
}