<?php

/**
 * Class RemedyClient | Remedy/RemedyClient.php
 */

namespace Remedy;

use Remedy\CPY\CompanyClientGroup;
use Remedy\CRQ\CrClientGroup;

/**
 * Main Remedy client (group) for all transactions
 */
class RemedyClient
{

    /**
     * Client for all Change Request transactions
     *
     * @var \Remedy\CRQ\CrClientGroup
     */
    public $changeRequests;
    public $cr;


    /**
     * Client for all Company transactions
     *
     * @var \Remedy\CPY\CompanyClientGroup
     */
    public $companies;


    /**
     * RemedyClient constructor
     *
     * @param string $rapidUrl RAPID API URL (https://rapid.cerner.com:8243)
     * @param string $key Consumer key for subscribed RAPID application
     * @param string $secret Consumer secret for subscribed RAPID application
     * @param int $retries Numbers of times to retry a failed transaction
     */
    public function __construct(string $rapidUrl = "", string $key = "", string $secret = "", $retries = 3)
    {
        // Instantiate clients
        $this->changeRequests = new CrClientGroup($this, $rapidUrl, $key, $secret, $retries);
        $this->companies = new CompanyClientGroup($rapidUrl, $key, $secret, $retries);

        // Legacy aliases
        $this->cr = $this->changeRequests;
    }

    /**
     * Apply a user supplied function to each client
     *
     * @param callable $callback callback function to apply to each client,
     *                           should accept a single Rapid\RapidClient parameter
     * @return boolean returns true
     */
    public function walkClients(callable $callback)
    {
        $clients = $this->getClients();
        return array_walk($clients, $callback);
    }

    /**
     * Gets all Rapid clients associated with the Remedy client
     *
     * @return array array of associated Rapid clients
     */
    public function getClients()
    {
        return array_merge(
            [],
            $this->changeRequests->getClients(),
            $this->companies->getClients(),
        );
    }
}
