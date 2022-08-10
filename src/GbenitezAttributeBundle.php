<?php

namespace gbenitez\Bundle\AttributeBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GbenitezAttributeBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

}
