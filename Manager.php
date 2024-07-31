<?php

/**
 * Class Manager | Remedy/CRQ/Manager.php
 */

namespace Remedy\CRQ;

/**
 * Stores Remedy change manager data
 */
class Manager extends Data
{
    /**
     * Associative array of valid fields mapped to the accompanying Remedy field name
     *
     * @var array
     */
    protected $remedyFields = [
        'company' => 'changeManagerSupportCompany',
        'organization' => 'changeManagerSupportOrganization',
        'group' => 'changeManagerSupportGroup',
        'name' => 'changeManager',
        'loginId' => 'changeManagerLoginId',
    ];

    /**
     * Manager company
     *
     * @var string
     */
    protected $company;

    /**
     * Manager organization
     *
     * @var string
     */
    protected $organization;

    /**
     * Manager group
     *
     * @var string
     */
    protected $group;

    /**
     * Manager name
     *
     * @var string
     */
    protected $name;

    /**
     * Manager login ID (associate ID)
     *
     * @var string
     */
    protected $loginId;
}
