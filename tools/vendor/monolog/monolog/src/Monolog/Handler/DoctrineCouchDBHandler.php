<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Modified by __root__ on 31-May-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Deconf\AIWP\Monolog\Handler;

use Deconf\AIWP\Monolog\Logger;
use Deconf\AIWP\Monolog\Formatter\NormalizerFormatter;
use Doctrine\CouchDB\CouchDBClient;

/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends AbstractProcessingHandler
{
    private $client;

    public function __construct(CouchDBClient $client, $level = Logger::DEBUG, $bubble = true)
    {
        $this->client = $client;
        parent::__construct($level, $bubble);
    }

    /**
     * {@inheritDoc}
     */
    protected function write(array $record)
    {
        $this->client->postDocument($record['formatted']);
    }

    protected function getDefaultFormatter()
    {
        return new NormalizerFormatter;
    }
}
