<?php

/**
 * Class CompanyClientGroup | Remedy/CPY/CompanyClientGroupGroup.php
 */

namespace Remedy\CPY;

/**
 * CompanyClientGroup contains the Remedy company clients
 */
class CompanyClientGroup
{
    /**
     * Client to get companies
     *
     * @var \Remedy\CPY\CompanyQueryClient
     */
    private $companyQuery;

    /**
     * CompanyClientGroup constructor
     *
     * @param string $rapidUrl RAPID API URL (https://rapid.cerner.com:8243)
     * @param string $key Consumer key for subscribed RAPID application
     * @param string $secret Consumer secret for subscribed RAPID application
     * @param int    $retries number of times to retry a failed request
     */
    public function __construct(string $rapidUrl = "", string $key = "", string $secret = "", $retries = 3)
    {
        // Company clients
        $this->companyQuery = new CompanyQueryClient($rapidUrl, $key, $secret, $retries);
    }

    /**
     * Returns an array of all grouped Rapid clients
     *
     * @return array grouped clients
     */
    public function getClients()
    {
        return [
            $this->companyQuery
        ];
    }

    /**
     * Gathers client companies
     *
     * @param array|string $mnemonics optional mnemonic or list of mnemonics to filter by
     * @return \Remedy\CPY\Company[] gathered companies
     */
    public function get($mnemonics = [])
    {
        return $this->companyQuery->getClientCompanies($mnemonics);
    }

    /**
     * Gathers CernerWorks companies
     *
     * @param array|string $mnemonics optional mnemonic or list of mnemonics to filter by
     * @return \Remedy\CPY\Company[] gathered companies
     */
    public function getCernerworks($mnemonics = [])
    {
        return $this->companyQuery->getClientCompanies($mnemonics, ["mnemonic" => "_"]);
    }
}
