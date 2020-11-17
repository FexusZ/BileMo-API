<?php

namespace App;

use App\DependencyInjection\Compiler\ExceptionNormalizerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
    	dump('test');
        $container->addCompilerPass(new ExceptionNormalizerPass());
    }
}
