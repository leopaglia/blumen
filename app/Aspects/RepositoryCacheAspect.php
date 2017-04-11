<?php

namespace App\Aspects;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;

/**
 * Repository Cache aspect
 */
class RepositoryCacheAspect implements Aspect
{
    /**
     * @var int $minutes - default minutes to keep a cache record
     */
    static $minutes = 60;

    /**
     * @var \Illuminate\Cache\Repository
     */
    private $cache = null;

    public function __construct()
    {
        $this->cache = app('cache');
    }

    /**
     * Build a key to reference the cache
     *
     * @param string $method - method name
     * @param array $params
     * @return string
     */
    private function buildKey($method, $params = [])
    {
        return md5($method . implode(":", $params));
    }

    /**
     * @param MethodInvocation $invocation Invocation
     * @Around("@execution(App\Aspects\Annotations\Cacheable)")
     * @return mixed
     */
    public function aroundCacheable(MethodInvocation $invocation)
    {
        $obj = $invocation->getThis();
        $class = is_object($obj) ? get_class($obj) : $obj;

        $methodName = $invocation->getMethod()->name;
        $params = $invocation->getMethod()->getParameters();

        $key = $this->buildKey($methodName, $params);

        return $this->cache->tags($class)->remember($key, self::$minutes, function() use($invocation) {
            return $invocation->proceed();
        });
    }

    /**
     * @param MethodInvocation $invocation Invocation
     * @Around("@execution(App\Aspects\Annotations\InvalidatesCache)")
     * @return mixed
     */
    public function aroundInvalidator(MethodInvocation $invocation)
    {
        $obj = $invocation->getThis();
        $class = is_object($obj) ? get_class($obj) : $obj;
        $this->cache->tags($class)->flush();
        return $invocation->proceed();
    }
}