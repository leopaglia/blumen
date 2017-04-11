<?php

namespace App\Aspects\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class InvalidatesCache extends Annotation {}