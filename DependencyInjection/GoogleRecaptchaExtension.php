<?php

namespace GoogleRecaptchaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class GoogleRecaptchaExtension
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class GoogleRecaptchaExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('google_recaptcha.api_endpoint', $config['api_endpoint']);
        $container->setParameter('google_recaptcha.site_key', $config['site_key']);
        $container->setParameter('google_recaptcha.secret_key', $config['secret_key']);
        $container->setParameter('google_recaptcha.form_field_name', $config['form_field_name']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('form.yml');
        $loader->load('validator.yml');
    }
}
