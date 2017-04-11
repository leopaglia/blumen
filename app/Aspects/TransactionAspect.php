<?php

namespace App\Aspects;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;

/**
 * Transaction aspect
 */
class TransactionAspect implements Aspect
{
    /**
     * Wrap 'Transactional' annotated method in a db transaction
     *
     * @param MethodInvocation $invocation Invocation
     * @Around("@execution(App\Aspects\Annotations\Transactional)")
     */
    public function beforeMethodExecution(MethodInvocation $invocation)
    {
        return app('db')->transaction(function() use($invocation) {
            return $invocation->proceed();
        });
    }
}