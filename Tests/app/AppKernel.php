<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AppKernel.
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     *
     * @return array|\Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),

            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),

            new \FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new ONGR\ElasticsearchBundle\ONGRElasticsearchBundle(),
            new Tedivm\StashBundle\TedivmStashBundle(),

            new ONGR\PagerBundle\ONGRPagerBundle(),
            new ONGR\CookiesBundle\ONGRCookiesBundle(),
            new ONGR\SettingsBundle\Tests\Fixtures\Acme\TestBundle\AcmeTestBundle(),
            new ONGR\SettingsBundle\ONGRSettingsBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
        $loader->load(__DIR__ . '/config/sessionless_authentication.yml');
    }
}
