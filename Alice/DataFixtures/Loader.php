<?php

/*
 * This file is part of the Hautelook\AliceBundle package.
 *
 * (c) Baldur Rensch <brensch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hautelook\AliceBundle\Alice\DataFixtures;

use Hautelook\AliceBundle\Alice\ProcessorChain;
use Hautelook\AliceBundle\Faker\Provider\ProviderChain;
use Nelmio\Alice\Fixtures;
use Nelmio\Alice\ProcessorInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Baldur Rensch <brensch@gmail.com>
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class Loader implements LoaderInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var ProcessorInterface[]
     */
    protected $processors;

    /**
     * @param ProcessorChain  $processorChain
     * @param ProviderChain   $providerChain
     * @param string          $locale
     * @param int             $seed
     * @param bool            $persistOnce
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProcessorChain $processorChain,
        ProviderChain $providerChain,
        $locale,
        $seed,
        $persistOnce,
        LoggerInterface $logger = null
    ) {
        $this->processors = $processorChain->getProcessors();

        $options = [];
        $options['providers'] = $providerChain->getProviders();
        $options['locale'] = $locale;
        $options['seed'] = $seed;
        $options['persist_once'] = $persistOnce;

        if (null !== $logger) {
            $options['logger'] = $logger;
        }

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function load($persister, array $fixtures)
    {
        return Fixtures::load(
            $fixtures,
            $persister,
            $this->options,
            $this->processors
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessors()
    {
        return $this->processors;
    }
}
