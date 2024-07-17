<?php

/**
 * Class RelationshipClient | Remedy/RelationshipClient.php
 */

namespace Remedy;

use Rapid\RapidClient;

/**
 * RemedyRelationshipService interacts with the Remedy relationship API
 * This API interacts with multiple Remedy entities e.g. CRQ, INC, KBA, PRB
 * https://rapid.cerner.com/store/apis/info?name=RemedyRelationshipService&version=v2
 */
class RelationshipClient extends RapidClient
{
    protected function getPath()
    {
        return "remedy-relationship-svc/v2/";
    }

    /**
     * Relate 2 records (e.g. CRQ, INC, KBA, PRB) to each other
     *
     * @param string $sourceRecord Source record to relate something to
     * @param string $relateRecord Record to relate to the source record
     * @param string $relationshipType The relationship type field (Related to|Resolved by)
     * @return object
     * @throws \Rapid\ClientException
     */
    public function createTicketRelationship(
        string $sourceRecord,
        string $relateRecord,
        string $relationshipType = "Related to"
    ) {
        $body = [
            "relatedToTicketId" => $relateRecord,
            "relationshipType" => $relationshipType
        ];
        return $this->post("tickets/$sourceRecord/relationships", $body);
    }

    /**
     * Delete relationship between 2 records (e.g. CRQ, INC, KBA, PRB)
     *
     * @param string $sourceRecord Source record of relationship
     * @param string $relateRecord Record related to the source record
     * @param string $relationshipType The relationship type field (Related to|Resolved by)
     * @return object
     * @throws \Rapid\ClientException
     */
    public function removeTicketRelationship(
        string $sourceRecord,
        string $relateRecord,
        string $relationshipType = "Related to"
    ) {
        $body = [
            "relatedToTicketId" => $relateRecord,
            "relationshipType" => $relationshipType
        ];
        return $this->request('DELETE', "tickets/$sourceRecord/relationships", $body);
    }
}
