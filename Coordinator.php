<?php

/**
 * Class Coordinator | Remedy/CRQ/Coordinator.php
 */

namespace Remedy\CRQ;

/**
 * Stores Remedy change coordinator data
 */
class Coordinator extends Data
{
    /**
     * Associative array of valid fields mapped to the accompanying Remedy field name
     *
     * @var array
     */
    protected $remedyFields = [
        'company' => 'coordinatorCompany',
        'organization' => 'coordinatorSupportOrganization',
        'group' => 'coordinatorSupportGroup',
        'groupId' => 'coordinatorSupportGroupId',
        'name' => 'coordinator',
        'loginId' => 'coordinatorLoginId',
    ];

    /**
     * Coordinator company
     *
     * @var string
     */
    protected $company;

    /**
     * Coordinator organization
     *
     * @var string
     */
    protected $organization;

    /**
     * Coordinator group
     *
     * @var string
     */
    protected $group;

    /**
     * Coordinator group ID
     *
     * @var string
     */
    protected $groupId;

    /**
     * Coordinator name
     *
     * @var string
     */
    protected $name;

    /**
     * Coordinator login ID (associate ID)
     *
     * @var string
     */
    protected $loginId;
}
