<?php

/**
 * Class CompanyQueryClient | Remedy/CPY/CompanyQueryClient.php
 */

namespace Remedy\CPY;

use Rapid\RapidClient;

/**
 * CompanyQueryClient interacts with the Remedy Company API
 */
class CompanyQueryClient extends RapidClient
{
    protected function getPath()
    {
        return "remedy-company-query-svc/v1/";
    }

    /**
     * Filters out the latest paginated response
     *
     * @param array $responses array of response objects provided by reference
     * @param array $filters   filter keys and values provided as an associative array
     *                         Example: ["mnemonic" => "_"]
     * @return void
     */
    private function filterPaginatedResponse(&$responses, $filters = [])
    {
        if (empty($filters)) {
            return;
        }

        $lastIndex = count($responses) - 1;

        $clientCount = count($responses[$lastIndex]->content);
        for ($i = ($clientCount - 1); $i >= 0; $i--) {
            foreach ($filters as $filterKey => $filterValue) {
                if (strpos($responses[$lastIndex]->content[$i]->$filterKey, $filterValue) === false) {
                    // Could not find the filter value, remove the client
                    array_splice($responses[$lastIndex]->content, $i, 1);
                }
            }
        }
    }

    /**
     * Perform paginated HTTP GET against the base API URL for the company client
     * Implements pagination specific to the company client
     *
     * @param string $url     API URL to GET relative to the base API URL ("cr/1234567")
     * @param array  $params  GET parameters provided as an associative array
     *                        Example: ["key" => "value"]
     * @param array  $filters filter keys and values provided as an associative array
     *                        Example: ["mnemonic" => "_"]
     * @param int    $retries Number of times to retry a failed transaction
     *                        If -1 is provided, the default number of retries on the client will be used
     * @return object[]       array of objects containing the body of each response page
     */
    public function getPaginated(string $url = "", $params = [], $filters = [], int $retries = -1)
    {
        // Add the pagination data into the params
        $params["size"] = 1000;
        $params["page"] = 0;
        $responses = [];

        $responses[] = $this->get($url, $params, $retries);
        $this->filterPaginatedResponse($responses, $filters);
        $totalPages = $responses[0]->totalPages;

        while (++$params["page"] < $totalPages) {
            // Filter out responses that do not qualify for the filters
            $responses[] = $this->get($url, $params, $retries);
            $this->filterPaginatedResponse($responses, $filters);
        }

        return $responses;
    }

    /**
     * Gathers client companies
     *
     * @param array|string $mnemonics optional mnemonic or list of mnemonics to filter by
     * @param array        $filters   optional filters provided to filter company output
     *                                Example: ["mnemonic" => "_"]
     * @return \Remedy\CPY\Company[] gathered companies
     */
    public function getClientCompanies($mnemonics = [], $filters = [])
    {
        $params = ["companyTypeIn" => "Customer", "statusIn" => 1];

        if (!empty($mnemonics)) {
            if (!is_array($mnemonics)) {
                $mnemonics = [$mnemonics];
            }
            $params["mnemonicIn"] = implode("|", $mnemonics);
        }

        $companyResponses = $this->getPaginated("companies", $params, $filters);

        // Initialize Company objects from the paginated responses
        $companyArrays = array_map(function ($response) {
            return array_map(function ($company) {
                return new Company($company);
            }, $response->content);
        }, $companyResponses);

        // Flatten all of the paginated responses into the resulting companies array
        $companies = [];
        foreach ($companyArrays as $companyArray) {
            $companies = array_merge($companies, $companyArray);
        }

        return $companies;
    }
}
