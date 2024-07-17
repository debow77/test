<?php

/**
 * Class RelationshipQueryClient | Remedy/CI/RelationshipQueryClient.php
 */

namespace Remedy\CI;

/**
 * RelationshipQueryClient interacts with the Remedy CI Relationship API
 */
class RelationshipQueryClient extends CiQueryClientBase
{
    protected function getPath()
    {
        return "remedy-asset-query-svc/{$this->getApiVersion()}/";
    }

    /**
     * Determine whether a domain has a given usage
     *
     * @param string $company Client company (ABC_DE-1234)
     * @param string $domain Remedy domain (p123)
     * @param string $usage Remedy usage ('Backend')
     * @return bool whether the domain has the given usage
     */
    public function domainHasUsage(string $company, string $domain, string $usage)
    {
        $params = [
            "relationship.markAsDeleted" => "No",
            "source.name" => $domain,
            "source.company" => $company,
            "destination.primaryUsageLike" => $usage
        ];
        $response = $this->getRelationship("assets/-/relationships", $params);
        return !empty($response);
    }

    /**
     * Gets the company for a given computer system
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return string Client company
     */
    public function getComputerSystemCompany(string $fqdn)
    {
        $relationships = $this->getComputerSystemDomains($fqdn);

        if (empty($relationships)) {
            return "";
        }

        // Select the company that has the most relationships
        $relatedCompanies = array_map(function ($relationship) {
            return $relationship->source->company;
        }, $relationships);

        // Reject null values
        $relatedCompanies = array_filter($relatedCompanies, function ($v) {
            return !is_null($v);
        });

        $relatedCompanies = array_count_values($relatedCompanies);
        return array_keys($relatedCompanies, max($relatedCompanies))[0];
    }

    /**
     * Gets the mnemonic for a given computer system
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return string Client mnemonic
     */
    public function getComputerSystemMnemonic(string $fqdn)
    {
        $mnemonic = $this->getComputerSystemCompany($fqdn);

        if (!empty($mnemonic)) {
            // Company will be specified as ABC_DE-1234, get only the mnemonic
            $mnemonic = explode('-', $mnemonic)[0];
        }

        return $mnemonic;
    }

    /**
     * Gets the Remedy domains associated with given computer system
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return \Remedy\CI\Relationship[] array of Remedy domain relationship objects
     */
    public function getComputerSystemDomains(string $fqdn)
    {
        foreach (["hostName", "name"] as $fqdnField) {
            $params = [
                "relationship.markAsDeleted" => "No",
                "source.classId" => "BMC.CORE:CERN_DOMAIN",
                "destination.{$fqdnField}" => $fqdn
            ];

            $relationships = $this->getRelationships("assets/-/relationships", $params);

            if (!empty($relationships)) {
                return array_values($relationships);
            }
        }

        return [];
    }

    /**
     * Gets the groups associated with a given computer system
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return \Remedy\CI\Relationship[] array of Remedy group relationship objects
     */
    public function getComputerSystemGroups(string $fqdn)
    {
        $params = [
            "asset.markAsDeleted" => "No",
            "asset.name" => $fqdn,
            "asset.type" => "Server",
            "assetPeople.formType" => "Support Group",
        ];

        return $this->getRelationships("assets-people/-/relationships", $params);
    }

    /**
     * Gets the site for a given company and Remedy domain
     *
     * @param string $company Client company (ABC_DE)
     * @param string $domain Remedy domain (p123)
     * @return string domain site (empty string if it cannot be found)
     */
    public function getDomainSite(string $company, string $domain)
    {
        $params = [
            "relationship.markAsDeleted" => "No",
            "source.classId" => "BMC.CORE:CERN_DOMAIN",
            "source.company" => $company,
            "source.name" => $domain,
            "destination.hostNameExists" => "true",
        ];

        $relationships = $this->getRelationships("assets/-/relationships", $params);

        if (empty($relationships)) {
            return "";
        }

        // return the most common site
        $relatedSites = array_map(function ($relationship) {
            return $relationship->destination->site;
        }, $relationships);

        // Reject null values
        $relatedSites = array_filter($relatedSites, function ($v) {
            return !is_null($v);
        });

        $relatedSites = array_count_values($relatedSites);
        return array_keys($relatedSites, max($relatedSites))[0];
    }

    /**
     * Gets associated computer systems for a given company
     *
     * @param string $company Client company (ABC_DE)
     * @param array $queryFilters associative array of optional filter parameters.
     * ```php
     * "domain"   => "domain"
     * "os"       => "OS"
     * "fqdn"     => "FQDN"
     * "usage"    => "Usage"
     * "notUsage" => "Excluded usage"
     * ```
     * Example:
     * ```php
     * "domain"   => "p123"
     * "os"       => "linux"
     * "fqdn"     => "abcdeapp1"
     * "usage"    => "Backend"
     * "notUsage" => "Frontend"
     * ```
     *
     * @return \Remedy\CI\ConfigurationItem[] associated computer systems
     */
    public function getComputerSystems(string $company, array $queryFilters = [])
    {
        $params = [
            "relationship.markAsDeleted" => "No",
            "source.classId" => "BMC.CORE:CERN_DOMAIN",
            "source.company" => $company,
            "destination.hostNameExists" => "true",
        ];

        // Optional parameter parsing, may want to pass these in a config object in the future
        if (!empty($queryFilters["domain"])) {
            $params["source.name"] = $queryFilters["domain"];
        }

        if (!empty($queryFilters["os"])) {
            $params["destination.operatingSystemLike"] = $queryFilters["os"];
        }

        if (!empty($queryFilters["fqdn"])) {
            $params["destination.hostNameLike"] = $queryFilters["fqdn"];
        }

        if (!empty($queryFilters["usage"])) {
            $params["destination.primaryUsageLike"] = $queryFilters["usage"];
        }

        if (!empty($queryFilters["notUsage"])) {
            $notUsages = $queryFilters["notUsage"];
            if (is_array($notUsages)) {
                $notUsages = implode("|", $notUsages);
            }
            $params["destination.primaryUsageNotIn"] = $notUsages;
        }

        return $this->getRelationships("assets/-/relationships", $params);
    }
}
