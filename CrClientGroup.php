<?php

/**
 * Class CrClientGroup | Remedy/CRQ/CrClientGroup.php
 */

namespace Remedy\CRQ;

use GuzzleHttp\Exception\RequestException;
use Remedy\CWL\ChangeWorklog;
use Remedy\CWL\ChangeWorklogModifyClient;
use Remedy\RemedyClient;

/**
 * CrClientGroup contains the Remedy Change Request clients
 */
class CrClientGroup
{
    /**
     * Client to modify configuration items
     *
     * @var \Rapid\RapidClient
     */
    private $crModify;

    /**
     * Client to get configuration items
     *
     * @var \Rapid\RapidClient
     */
    private $crQuery;

    /**
     * Client to get configuration items
     *
     * @var \Rapid\RapidClient
     */
    private $crWorklogModify;

    /**
     * Parent client
     *
     * @var \Remedy\RemedyClient
     */
    private $parent;

    /**
     * List of Remedy group prefixes for discovery filtering
     *
     * @var array
     */
    private $remedyGroupPrefixes;

    /**
     * CompanyClientGroup constructor
     *
     * @param \Remedy\RemedyClient $parent parent RemedyClient
     * @param string $rapidUrl RAPID API URL (https://rapid.cerner.com:8243)
     * @param string $key Consumer key for subscribed RAPID application
     * @param string $secret Consumer secret for subscribed RAPID application
     * @param int    $retries number of retries
     */
    public function __construct(
        RemedyClient $parent = null,
        string $rapidUrl = "",
        string $key = "",
        string $secret = "",
        $retries = 3
    ) {
        $this->parent = $parent;
        $this->remedyGroupPrefixes = ['clientops_', 'ehosting_', 'cwx_'];

        // CR clients
        $this->crQuery = new CrQueryClient($rapidUrl, $key, $secret, $retries);
        $this->crModify = new CrModifyClient($rapidUrl, $key, $secret, $retries);
        $this->crWorklogModify = new ChangeWorklogModifyClient($rapidUrl, $key, $secret, $retries);
    }

    /**
     * Gets a CR
     *
     * @param string $changeId change request ID (CRQ0123456789)
     * @return \Remedy\CRQ\ChangeRequest|null retrieved change request or null if none can be found
     */
    public function get(string $changeId)
    {
        try {
            $crResponse = $this->crQuery->get("changes/{$changeId}/all");
        } catch (RequestException $e) {
            return null;
        }

        return new ChangeRequest($this->parent, $crResponse);
    }

    /**
     * Gets a CR by Utn
     *
     * @param string $changeUtn change request UTN (123456789)
     * @return object Api returned content
     */
    public function getByUtn(string $changeUtn)
    {
        try {
            $params = [
                "universalTicketNumber" => $changeUtn
            ];
            $crResponse = $this->crQuery->get("changes", $params);
        } catch (RequestException $e) {
            return null;
        }

        return $crResponse;
    }

    /**
     * Creates a new CR
     *
     * @param array $crParams CR parameters
     * @return string change request ID (CRQ0123456789)
     */
    public function create($crParams)
    {
        // Coerce the parameters into standard formats
        $crParams = $this->coerceParams($crParams);

        // Populate the change coordinator and change manager groups if they are unspecified
        $crParams = $this->populateRemedyGroups($crParams);

        $crResponse = $this->crModify->post('changes/', $crParams);
        return $crResponse->changeId;
    }

    /**
     * Updates an existing CR
     *
     * @param string $id change ID for the CR (CRQ0123456789)
     * @param array $crParams CR parameters
     * @return string change request ID (CRQ0123456789)
     */
    public function update($id, $crParams)
    {
        // Coerce the parameters into standard formats
        $crParams = $this->coerceParams($crParams);

        $crResponse = $this->crModify->put("changes/{$id}", $crParams);
        return $crResponse->changeId;
    }

    /**
     * Add a new worklog to an existing CR
     *
     * @param string $changeId change request ID (CRQ0123456789)
     * @param \Remedy\CWL\ChangeWorklog $worklog worklog to add
     * @return mixed response
     */
    public function addWorklog($changeId, ChangeWorklog $worklog)
    {
        $body = $worklog->json();
        return $this->crWorklogModify->post("changes/{$changeId}/worklogs", $body);
    }

    /**
     * Returns an array of all grouped Rapid clients
     *
     * @return array grouped clients
     */
    public function getClients()
    {
        return [
            $this->crQuery,
            $this->crModify,
            $this->crWorklogModify
        ];
    }

    /**
     * Populate the Remedy groups if they are not set
     *
     * @param array $crParams list of CR parameters
     * @return array updated list of CR parameters
     */
    private function populateRemedyGroups($crParams)
    {
        if (!isset($crParams['changeManagerSupportGroup'])) {
            $managerGroup = $this->gatherRemedyGroup($crParams['changeManagerLoginId']);
            if (isset($managerGroup)) {
                $crParams['changeManagerSupportGroup'] = $managerGroup->supportGroupName;
                $crParams['changeManagerSupportOrganization'] = $managerGroup->supportOrganization;
                $crParams['changeManagerSupportCompany'] = $managerGroup->company;
            }
        }

        if (!isset($crParams['coordinatorSupportGroup'])) {
            $coordinatorGroup = $this->gatherRemedyGroup($crParams['coordinatorLoginId']);
            if (isset($coordinatorGroup)) {
                $crParams['coordinatorSupportGroup'] = $coordinatorGroup->supportGroupName;
                $crParams['coordinatorSupportOrganization'] = $coordinatorGroup->supportOrganization;
                $crParams['coordinatorCompany'] = $coordinatorGroup->company;
            }
        }

        return $crParams;
    }

    /**
     * Gathers the Remedy group for a given login ID
     *
     * @param string $loginId (ab012345)
     * @return string Remedy group name
     */
    private function gatherRemedyGroup($loginId)
    {
        $groups = $this->parent->people()->getSupportGroupsByLoginId($loginId);

        // Filter groups by most general
        foreach ($this->remedyGroupPrefixes as $prefix) {
            $prefixLen = strlen($prefix);
            foreach ($groups as $group) {
                if (strlen($group->supportGroupName) >= $prefixLen) {
                    $groupPrefix = substr($group->supportGroupName, 0, $prefixLen);
                    if (strtolower($groupPrefix) === $prefix) {
                        return $group;
                    }
                }
            }
        }
    }

    /**
     * Coerces CR upsert parameters into valid data
     *
     * @param array $crParams CR parameters
     * @return array coerced CR parameters
     */
    private function coerceParams($crParams)
    {
        if (isset($crParams['coordinatorLoginId'])) {
            $crParams['coordinatorLoginId'] = strtolower($crParams['coordinatorLoginId']);
        }

        if (isset($crParams['changeManagerLoginId'])) {
            $crParams['changeManagerLoginId'] = strtolower($crParams['changeManagerLoginId']);
        }

        if (!isset($crParams['locationCompany']) && isset($crParams['company'])) {
            $crParams['locationCompany'] = $crParams['company'];
        }
        return $crParams;
    }
}
