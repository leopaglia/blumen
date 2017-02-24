<?php

namespace App\Aspects;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\After;

use Illuminate\Contracts\Cache\Repository as Cache;


/**
 * Cache aspect
 */
class RepositoryCacheAspect implements Aspect
{
    /**
     * @var Cache $cache
     */
    protected $cache;

    /**
     * Get from cache if exists and set if not on find methods
     *
     * @param MethodInvocation $invocation Invocation
     *
     * @Around("@execution(Annotation\Cacheable)")
     * @return mixed
     */
    public function aroundCacheable(MethodInvocation $invocation)
    {
        dd("sisisiis");
        $class_name = get_class($invocation->getThis());
        $method_name = $invocation->getMethod()->getName();
        $args = $invocation->getArguments();
        
        $key = $this->buildKey($method_name, implode("", $args));

        return $this->cache->tags($class_name)->rememberForever($key, function() use($invocation) {
            return $invocation->proceed();
        });
    }

    /**
     * Invalidate cache on create, update and delete methods
     *
     * @param MethodInvocation $invocation Invocation
     *
     * @After("@execution(Annotation\InvalidatesCache)")
     * @return mixed
     */
    public function afterInvalidator(MethodInvocation $invocation)
    {
        $class_name = get_class($invocation->getThis());
        $this->cache->tags($class_name)->flush();
    }

    /**
     * CacheAspect constructor.
     * @param $cache
     */
    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    /**
     * Build a key to reference the cache
     *
     * @param $method
     * @param $identifier
     * @return string
     */
    private function buildKey($method, $identifier = null)
    {
        return md5($method . $identifier);
    }

}