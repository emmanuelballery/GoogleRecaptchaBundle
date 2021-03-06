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
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();

        $root = $tb->root('google_recaptcha');

        $root->children()->scalarNode('api_endpoint')->cannotBeEmpty()->info('Google Recaptcha API endpoint')->defaultValue('https://www.google.com/recaptcha/api/siteverify');
        $root->children()->scalarNode('site_key')->isRequired()->cannotBeEmpty()->info('Google Recaptcha site key');
        $root->children()->scalarNode('secret_key')->isRequired()->cannotBeEmpty()->info('Google Recaptcha secret key');
        $root->children()->scalarNode('form_field_name')->cannotBeEmpty()->info('Google Recaptcha form field name')->defaultValue('g-recaptcha-response');

        return $tb;
    }
}
