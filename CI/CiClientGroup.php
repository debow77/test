<?php

/**
 * Class CiClientGroup | Remedy/CI/CiClientGroup.php
 */

namespace Remedy\CI;

/**
 * CiClientGroup contains the Remedy Configuration Item clients
 */
class CiClientGroup
{
    /**
     * Client to modify configuration items
     *
     * @var \Remedy\CI\CiModifyClient
     */
    private $ciModify;

    /**
     * Client to get configuration items
     *
     * @var \Remedy\CI\CiQueryclient
     */
    private $ciQuery;

    /**
     * Client to modify relationships
     *
     * @var \Remedy\CI\RelationshipModifyClient
     */
    private $relationshipModify;

    /**
     * Client to get relationships
     *
     * @var \Remedy\CI\RelationshipQueryclient
     */
    private $relationshipQuery;

    /**
     * CiClientGroup constructor
     *
     * @param string $rapidUrl RAPID API URL (https://rapid.cerner.com:8243)
     * @param string $key Consumer key for subscribed RAPID application
     * @param string $secret Consumer secret for subscribed RAPID application
     * @param int    $retries number of times to retry a failed transaction
     */
    public function __construct(string $rapidUrl = "", string $key = "", string $secret = "", int $retries = 3)
    {
        // CI clients
        $this->ciQuery = new CiQueryClient($rapidUrl, $key, $secret, $retries);
        $this->ciModify = new CiModifyClient($rapidUrl, $key, $secret, $retries);
        $this->relationshipQuery = new RelationshipQueryClient($rapidUrl, $key, $secret, $retries);
        $this->relationshipModify = new RelationshipModifyClient($rapidUrl, $key, $secret, $retries);
    }

    /**
     * Returns an array of all grouped Rapid clients
     *
     * @return array grouped clients
     */
    public function getClients()
    {
        return [
            $this->ciQuery,
            $this->ciModify,
            $this->relationshipQuery,
            $this->relationshipModify
        ];
    }

    /**
     * Get a business service CIs by company
     *
     * @param string $company Company name (ABC_DE)
     * @param string $name    Business service name matcher (_HEALTH_CLINICAL), optional
     * @return \Remedy\CI\ConfigurationItem[] Configuration items representing the companies business services
     */
    public function getBusinessServices(string $company, string $name = "")
    {
        return $this->ciQuery->getBusinessServices($company, $name);
    }

    /**
     * Get a business service CI by company and business service name
     *
     * @param string $company Company name (ABC_DE)
     * @param string $name    Business service name (HOSTED_HEALTH_CLINICAL)
     * @return \Remedy\CI\ConfigurationItem Configuration item representing a business service
     */
    public function getBusinessService(string $company, string $name)
    {
        return $this->ciQuery->getBusinessServiceByName($company, $name);
    }

    /**
     * Get domains for a given company
     *
     * @param string $company Client company (ABC_DE)
     * @return \Remedy\CI\ConfigurationItem[] Configuration items representing the found domains
     */
    public function getDomains(string $company)
    {
        return $this->ciQuery->getDomains($company);
    }

    /**
     * Get computer system by its FQDN
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return \Remedy\CI\ConfigurationItem Configuration item representing the computer system
     */
    public function getComputerSystemByFqdn(string $fqdn)
    {
        return $this->ciQuery->getComputerSystemByFqdn($fqdn);
    }

    /**
     * Get computer system by instance ID
     *
     * @param string $id CI instance ID
     * @return \Remedy\CI\ConfigurationItem Configuration item representing the computer system
     */
    public function getComputerSystemById(string $id)
    {
        return $this->ciQuery->getComputerSystemById($id);
    }

    /**
     * Determine whether a computer system is deployed
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return bool whether the computer system is deployed
     */
    public function computerSystemIsDeployed(string $fqdn)
    {
        return $this->ciQuery->computerSystemIsDeployed($fqdn);
    }

    /**
     * Updates a given computer system's primary usage
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @param string $primaryUsage New primary usage ('Backend')
     * @param string $dataset Remedy dataset
     * @return mixed CI update response
     * @throws CiException
     */
    public function updateComputerSystemPrimaryUsage(string $fqdn, string $primaryUsage, string $dataset)
    {
        $ciData = $this->getComputerSystemByFqdn($fqdn);

        if (!isset($ciData)) {
            throw new CiException("Could not find CI data for {$fqdn} when performing usage updates to {$fqdn}");
        }

        return $this->ciModify->updateComputerSystemPrimaryUsage($ciData, $primaryUsage, $dataset);
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
        return $this->relationshipQuery->domainHasUsage($company, $domain, $usage);
    }

    /**
     * Gets the company for a given computer system
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return string Client company
     */
    public function getComputerSystemCompany(string $fqdn)
    {
        return $this->relationshipQuery->getComputerSystemCompany($fqdn);
    }

    /**
     * Gets the mnemonic for a given computer system
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return string Client mnemonic
     */
    public function getComputerSystemMnemonic(string $fqdn)
    {
        return $this->relationshipQuery->getComputerSystemMnemonic($fqdn);
    }

    /**
     * Gets the Remedy domains associated with given computer system
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return \Remedy\CI\Relationship[] array of Remedy domain relationship objects
     */
    public function getComputerSystemDomains(string $fqdn)
    {
        return $this->relationshipQuery->getComputerSystemDomains($fqdn);
    }

    /**
     * Gets the groups associated with a given computer system
     *
     * @param string $fqdn Computer system FQDN ('abcdeapp1.domain.com')
     * @return \Remedy\CI\Relationship[] array of Remedy group relationship objects
     */
    public function getComputerSystemGroups(string $fqdn)
    {
        return $this->relationshipQuery->getComputerSystemGroups($fqdn);
    }

    /**
     * Gets the site for a given company and Remedy domain
     *
     * @param string $company Client company (ABC_DE-1234)
     * @param string $domain Remedy domain (p123)
     * @return string domain site
     */
    public function getDomainSite(string $company, string $domain)
    {
        return $this->relationshipQuery->getDomainSite($company, $domain);
    }

    /**
     * Gets associated computer systems for a given company
     *
     * @param string $company Client company (ABC_DE-1234)
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
        return $this->relationshipQuery->getComputerSystems($company, $queryFilters);
    }

    /**
     * Associates a change request to a given CI by instance ID
     *
     * @param string $changeId change request ID (CRQ0123456789)
     * @param string $instanceId CI instance ID
     * @return mixed attachment response
     */
    public function relateToCr(string $changeId, string $instanceId)
    {
        return $this->relationshipModify->relateCiToCr($changeId, $instanceId);
    }
}
