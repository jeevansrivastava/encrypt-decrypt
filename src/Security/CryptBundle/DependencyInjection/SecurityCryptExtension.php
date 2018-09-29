<?php

namespace Security\CryptBundle\DependencyInjection;
 
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
 
class SecurityCryptExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
 
        $container->setParameter('security_crypt.secret', $config['secret']);
        $container->setParameter('security_crypt.algorithm', $config['algorithm']);
        $container->setParameter('security_crypt.mode', $config['mode']);
 
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
