<?php

namespace GoogleRecaptchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();

        $root = $tb->root('google_recaptcha');

        $root->children()->scalarNode('api_endpoint')->cannotBeEmpty()->info('')->defaultValue('https://www.google.com/recaptcha/api/siteverify');
        $root->children()->scalarNode('site_key')->isRequired()->cannotBeEmpty()->info('Google Recaptcha site key');
        $root->children()->scalarNode('secret_key')->isRequired()->cannotBeEmpty()->info('Google Recaptcha secret key');
        $root->children()->scalarNode('form_field_name')->cannotBeEmpty()->info('g-recaptcha-response')->defaultValue('');

        return $tb;
    }
}
