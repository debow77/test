<?php

/**
 * Class ChangeRequest | Remedy/CRQ/ChangeRequest.php
 */

namespace Remedy\CRQ;

use Carbon\Carbon;
use JsonSerializable;
use Remedy\CWL\ChangeWorklog;
use Remedy\PPL\Person;

/**
 * Represents a Remedy Change Request
 *
 * Available CR properties:
 * @property array $computerSystems associated computer systems
 * @property string[] $businessServices associated business services
 * @property array $instanceIds associated CI IDs
 * @property \Carbon\Carbon $actualEndDate actual end date
 * @property \Carbon\Carbon $actualStartDate actual start date
 * @property \Remedy\PPL\Person $alternateContact alternate contact
 * @property string $changeClass change class
 * @property string $id change ID (CRQ0000000000)
 * @property string $changeId change ID (CRQ0000000000)
 * @property string $number change ID (CRQ0000000000)
 * @property \Remedy\CRQ\Manager $changeManager change manager
 * @property \Remedy\CRQ\Manager $manager change manager
 * @property string $changeTiming change timing
 * @property int $changeTimingId change timing ID
 * @property string $changeType change type
 * @property int $changeTypeId change type ID
 * @property string $clientReferenceId client reference ID
 * @property string $clientViewable whether CR is client viewable
 * @property int $clientViewableId client viewable ID
 * @property \Carbon\Carbon $closedDate close date
 * @property \Remedy\CRQ\Coordinator $coordinator change coordinator
 * @property \Remedy\CRQ\Coordinator $changeCoordinator change coordinator
 * @property string $company company
 * @property \Carbon\Carbon $completedDate completion date
 * @property \Remedy\PPL\Person $contact contact
 * @property string $corporateId corporate ID
 * @property string $environment environment
 * @property string $impact impact
 * @property int $impactId impact ID
 * @property string $integrationId integration ID
 * @property string $lastModifiedBy last person to modify the CR
 * @property \Carbon\Carbon $lastModifiedDate last date the CR was modified
 * @property string $leadTime lead time
 * @property string $locationCompany location company
 * @property string $locationSite location site
 * @property string $manufacturer manufacturer
 * @property string $modelVersion model version
 * @property string $notes notes
 * @property string $operationalCategorizationTier1 operational categorization tier 1
 * @property string $operationalCategorizationTier2 operational categorization tier 2
 * @property string $operationalCategorizationTier3 operational categorization tier 3
 * @property \Carbon\Carbon $originationDate origination date
 * @property string $performanceRating performance rating
 * @property string $portalSolution portal solution
 * @property string $portalSolutionFamily portal solution family
 * @property string $previousStatus previous CR status
 * @property int $previousStatusId previous CR status ID
 * @property string $priority priority
 * @property int $priorityId priority ID
 * @property string $productCategorizationTier1 production categorization tier 1
 * @property string $productCategorizationTier2 production categorization tier 2
 * @property string $productCategorizationTier3 production categorization tier 3
 * @property string $productName product name
 * @property string $requestedBy requestor
 * @property \Carbon\Carbon $requestedEndDate requested end date
 * @property \Carbon\Carbon $requestedStartDate requested start date
 * @property string $requestId request ID
 * @property string $reviewer reviewer name
 * @property string $reviewerLogin reviewer login ID
 * @property bool $reviewForPHI whether the CR should be reviewed for PHI (defaults to false)
 * @property string $riskLevel risk level
 * @property \Carbon\Carbon $scheduledEndDate scheduled end date
 * @property \Carbon\Carbon $scheduledStartDate scheduled start date
 * @property string $stage stage
 * @property string $status status
 * @property int $statusId status ID
 * @property \Carbon\Carbon $submitDate submission date
 * @property string $submitter submitter
 * @property string $summary summary
 * @property \Carbon\Carbon $targetDate target date
 * @property string $template template name
 * @property string $universalTicketNumber universal ticket number (UTN)
 * @property string $urgency urgency
 * @property int $urgencyId urgency ID
 * @property string $vendorCompany vendor company
 * @property string $vendorGroup vendor group
 * @property string $vendorOrganization vendor organization
 * @property string $vendorTicketNumber vendor ticket number
 * @property \Remedy\CWL\ChangeWorklog[] $worklogs array of worklogs associated with the CR
 *
 */
class ChangeRequest implements JsonSerializable
{
    /**
     * List of valid impacts
     */
    public const VALID_IMPACTS = [
        "4-Minor/Localized",
        "3-Moderate/Limited",
        "2-Significant/Large",
        "1-Extensive/Widespread"
    ];

    /**
     * List of valid urgencies
     */
    public const VALID_URGENCIES = [
        "4-Low",
        "3-Medium",
        "2-High",
        "1-Critical"
    ];

    /**
     * List of valid change timings
     */
    public const VALID_CHANGE_TIMINGS = [
        "Standard",
        "Normal",
        "Emergency"
    ];

    /**
     * List of valid statuses
     */
    public const VALID_STATUSES = [
        "Cancelled",
        "Closed",
        "Completed",
        "Draft",
        "Implementation In Progress",
        "Pending",
        "Planning In Progress",
        "Rejected",
        "Request For Authorization",
        "Scheduled",
        "Scheduled For Approval",
        "Scheduled For Review",
    ];

    /**
     * List of all properties and their types
     */
    public const PROPERTY_TYPES = [
        "computerSystems" => "relationship",
        "businessServices" => "relationship",
        "instanceIds" => "relationship",
        "actualEndDate" => "date",
        "actualStartDate" => "date",
        "alternateContact" => "contact",
        "changeClass" => "",
        "changeId" => "",
        "id" => "changeId",
        "number" => "changeId",
        "changeManager" => "manager",
        "manager" => "manager",
        "changeTiming" => "string",
        "changeTimingId" => "id",
        "changeType" => "string",
        "changeTypeId" => "id",
        "clientReferenceId" => "",
        "clientViewable" => "string",
        "clientViewableId" => "id",
        "closedDate" => "date",
        "coordinator" => "coordinator",
        "changeCoordinator" => "coordinator",
        "company" => "",
        "completedDate" => "date",
        "contact" => "contact",
        "corporateId" => "",
        "environment" => "",
        "impact" => "string",
        "impactId" => "id",
        "integrationId" => "",
        "lastModifiedBy" => "",
        "lastModifiedDate" => "date",
        "leadTime" => "",
        "locationCompany" => "",
        "locationSite" => "",
        "manufacturer" => "",
        "modelVersion" => "",
        "notes" => "",
        "operationalCategorizationTier1" => "",
        "operationalCategorizationTier2" => "",
        "operationalCategorizationTier3" => "",
        "originationDate" => "date",
        "performanceRating" => "",
        "portalSolution" => "",
        "portalSolutionFamily" => "",
        "previousStatus" => "string",
        "previousStatusId" => "id",
        "priority" => "string",
        "priorityId" => "id",
        "productCategorizationTier1" => "",
        "productCategorizationTier2" => "",
        "productCategorizationTier3" => "",
        "productName" => "",
        "requestedBy" => "",
        "requestedEndDate" => "date",
        "requestedStartDate" => "date",
        "requestId" => "",
        "reviewer" => "",
        "reviewerLogin" => "",
        "reviewForPHI" => "bool",
        "riskLevel" => "",
        "scheduledEndDate" => "date",
        "scheduledStartDate" => "date",
        "stage" => "",
        "status" => "string",
        "statusId" => "id",
        "submitDate" => "date",
        "submitter" => "",
        "summary" => "",
        "targetDate" => "date",
        "template" => "template",
        "universalTicketNumber" => "",
        "urgency" => "string",
        "urgencyId" => "id",
        "vendorCompany" => "",
        "vendorGroup" => "",
        "vendorOrganization" => "",
        "vendorTicketNumber" => "",
        "worklogs" => "worklogs",
    ];

    /**
     * List of all read-only properties
     */
    public const READ_ONLY_PROPERTIES = [
        "changeId",
        "id",
        "number",
        "changeManager",
        "manager",
        "changeTimingId",
        "changeTypeId",
        "clientViewableId",
        "closedDate",
        "coordinator",
        "changeCoordinator",
        "completedDate",
        "impactId",
        "lastModifiedBy",
        "lastModifiedDate",
        "locationCompany",
        "locationSite",
        "originationDate",
        "previousStatus",
        "previousStatusId",
        "priority",
        "priorityId",
        "requestId",
        "reviewer",
        "reviewerLogin",
        "stage",
        "status",
        "statusId",
        "submitDate",
        "submitter",
        "universalTicketNumber",
        "urgencyId",
        "vendorOrganization",
        "worklogs",
    ];

    /**
     * Raw CR web service response
     *
     * @var object
     */
    private $raw;

    /**
     * Uncommitted changes
     *
     * @var object
     */
    private $changes;

    /**
     * Associated Remedy Client
     *
     * @var \Remedy\RemedyClient
     */
    private $client;

    /**
     * Creates a new ChangeRequest object
     *
     * @param \Remedy\RemedyClient $client Remedy CR client to use for API requests
     * @param object $crResponse CR API response to use to construct the ChangeRequest object
     */
    public function __construct($client = null, $crResponse = null)
    {
        $this->client = $client;

        // Set logical default values for most CRs
        $this->raw = (object) [
            'changeClass' => 'Normal',
            'changeTypeString' => 'Change',
            'changeManagerSupportCompany' => 'Cerner',
            'clientViewableString' => 'No',
            'coordinatorCompany' => 'Cerner',
            'impactString' => '4-Minor/Localized',
            'company' => 'Cerner',
            'statusString' => 'Draft',
            'summary' => '',
            'reviewForPHI' => "False",
            'urgencyString' => '4-Low',
            'riskLevel' => '1',
        ];
        $this->changes = (object) [];

        // If a CR web service response is provided, parse it into the class
        if (!empty($crResponse)) {
            $this->raw = $crResponse;
        }
    }

    /**
     * Retrieves values for inaccessible properties
     *
     * @param string $name property name
     * @return mixed property value
     */
    public function __get($name)
    {
        // Alias all available public methods
        if (method_exists($this, $name)) {
            return $this->$name();
        }

        // Get value by aliased type
        if (!array_key_exists($name, self::PROPERTY_TYPES)) {
            throw new ChangeRequestException("{$name} is not a valid property.");
        }

        // If we do not have a specific type, return a generic value
        if (empty(self::PROPERTY_TYPES[$name])) {
            return $this->getValue($name);
        }

        // Return a different value based on the property type
        $propType = self::PROPERTY_TYPES[$name];
        if ($propType === "id") {
            return $this->getIdValue(str_replace("Id", "", $name));
        } elseif ($propType === "bool") {
            return $this->getBoolValue($name);
        } elseif ($propType === "date") {
            return $this->getDateValue($name);
        } elseif ($propType === "relationship") {
            return $this->getRelationshipValue($name);
        } elseif ($propType === "string") {
            return $this->getStringValue($name);
        } elseif ($propType === "contact") {
            return $this->getContactValue($name);
        } elseif ($propType === "changeId") {
            return $this->getValue("changeId");
        } elseif ($propType === "manager") {
            return $this->getManagerValue();
        } elseif ($propType === "coordinator") {
            return $this->getCoordinatorValue();
        } elseif ($propType === "template") {
            return $this->getTemplateValue();
        } elseif ($propType === "worklogs") {
            return $this->getWorklogsValue();
        }

        return null;
    }

    /**
     * Sets values for inaccessible properties
     *
     * @param string $name property name
     * @param mixed  $value property value
     */
    public function __set($name, $value)
    {
        if (in_array($name, self::READ_ONLY_PROPERTIES)) {
            throw new ChangeRequestException("{$name} is a read-only property.");
        }

        // Alias all available public set methods
        $setMethod = "set$name";
        if (method_exists($this, $setMethod)) {
            return $this->$setMethod($value);
        }

        // Set value by aliased type
        if (!array_key_exists($name, self::PROPERTY_TYPES)) {
            throw new ChangeRequestException("{$name} is not a valid property.");
        }

        // Set a different value based on the property type
        $propType = self::PROPERTY_TYPES[$name];
        if ($propType === "relationship") {
            return $this->setRelationshipValue($name, $value);
        } elseif ($propType === "contact") {
            return $this->setContactValue($name, $value);
        } elseif ($propType === "string") {
            return $this->setStringValue($name, $value);
        } elseif ($propType === "bool") {
            return $this->setBoolValue($name, $value);
        }

        // If we do not have a specific type, set a generic value
        return $this->setValue($name, $value);
    }

    /**
     * Checks if inaccessible property is set
     *
     * @param string $name property name
     * @return bool whether the property is set
     */
    public function __isset($name)
    {
        $value = null;
        try {
            $value = $this->__get($name);
        } catch (\Exception $ex) {
            return false;
        }

        return isset($value);
    }

    /**
     * Raw Change Request from the API
     *
     * @return object
     */
    public function raw()
    {
        return $this->raw;
    }

    /**
     * Uncommitted changes for the Change Request
     *
     * @return object
     */
    public function changes()
    {
        return $this->changes;
    }

    /**
     * Generates API parameters used to create a new CR
     *
     * @return array associative array containing all creation parameters
     */
    private function createParams()
    {
        // Reject all pending relationship changes from $this->changes
        $changeParams = (array) $this->changes;
        unset($changeParams['relationships']);

        $params = array_replace_recursive((array) $this->raw, $changeParams);

        // Translate all baseString keys to base
        foreach ($params as $k => $v) {
            if (substr($k, -6) == 'String') {
                $params[substr($k, 0, strlen($k) - 6)] = $v;
                unset($params[$k]);
            }
        }

        return $params;
    }

    /**
     * Generates API parameters used to update an existing CR
     *
     * @return array associative array containing all update parameters
     */
    private function updateParams()
    {
        $params = (array) $this->changes;

        // Reject all pending relationship changes from $this->changes
        unset($params['relationships']);

        // Translate all baseString keys to base
        foreach ($params as $k => $v) {
            if (substr($k, -6) == 'String') {
                $params[substr($k, 0, strlen($k) - 6)] = $v;
                unset($params[$k]);
            }
        }

        return $params;
    }

    /**
     * Saves all changes to the current CR object.
     * If the CR is not yet created, a request will be sent to create the new CR.
     * If the CR is already created, a request will be sent to update the existing CR.
     *
     * @param \Remedy\RemedyClient $client API client
     * @return \Remedy\CRQ\ChangeRequest the current ChangeRequest object
     */
    public function save($client = null)
    {
        $this->validateClient($client);

        // If we do not yet have a changeId set, create the CR
        if (!isset($this->raw->changeId)) {
            $id = $this->client->cr->create($this->createParams());
            $this->raw->changeId = $id;

            // Handle all pending relationship updates
            $this->updateRelationships();

            // Merge all updates over the current settings
            $this->commitChanges();
            return $this;
        }

        // Skip update if no update is necessary
        if (empty((array) $this->changes)) {
            return $this;
        }

        // update the CR
        $updateParams = $this->updateParams();
        if (!empty($updateParams)) {
            $this->client->cr->update($this->raw->changeId, $updateParams);
        }

        // Handle all pending relationship updates
        $this->updateRelationships();

        // Merge all updates over the current settings
        $this->commitChanges();
        return $this;
    }

    /**
     * Retrieve a fresh version of the current CR from the API
     *
     * @param bool $resetChanges whether to reset current changes on the CR
     * @param \Remedy\RemedyClient $client API client
     * @return \Remedy\CRQ\ChangeRequest the current ChangeRequest object with updated data
     */
    public function fresh(bool $resetChanges = true, $client = null)
    {
        if (!isset($this->raw->changeId)) {
            throw new ChangeRequestException("CR ID is not set for this CR! Cannot retrieve CR data from the API.");
        }

        $this->validateClient($client);
        $this->raw = $this->client->cr->get($this->raw->changeId)->raw;

        if ($resetChanges) {
            $this->changes = (object) [];
        }

        return $this;
    }

    /**
     * Transitions the CR to Draft status
     *
     * @param int $timeout       Amount of time to wait for transition
     * @param int $checkInterval Interval between checks
     * @return \Remedy\CRQ\ChangeRequest updated CR
     */
    public function toDraft(int $timeout = 600, int $checkInterval = 5)
    {
        return $this->toStatus(
            "Draft",
            ["Cancelled"],
            ["Draft"],
            $timeout,
            $checkInterval
        );
    }

    /**
     * Transitions the CR to Planning In Progress status
     *
     * @param int $timeout       Amount of time to wait for transition
     * @param int $checkInterval Interval between checks
     * @return \Remedy\CRQ\ChangeRequest updated CR
     */
    public function toPlanningInProgress(int $timeout = 600, int $checkInterval = 5)
    {
        return $this->toStatus(
            "Request For Authorization",
            ["Draft", "Request For Authorization"],
            ["Planning In Progress", "Scheduled"],
            $timeout,
            $checkInterval
        );
    }

    /**
     * Transitions the CR to Scheduled status
     *
     * @param int $timeout       Amount of time to wait for transition
     * @param int $checkInterval Interval between checks
     * @return \Remedy\CRQ\ChangeRequest updated CR
     */
    public function toScheduled(int $timeout = 600, int $checkInterval = 5)
    {
        // Validate scheduled start and end dates
        if (empty($this->getDateValue("scheduledStartDate")) || empty($this->getDateValue("scheduledEndDate"))) {
            throw new ChangeRequestException(
                "A scheduledStartDate and scheduledEndDate must be set prior to transitioning to scheduled status."
            );
        }

        return $this->toStatus(
            "Scheduled For Review",
            ["Planning In Progress"],
            ["Scheduled For Review", "Scheduled For Approval", "Scheduled"],
            $timeout,
            $checkInterval
        );
    }

    /**
     * Transitions the CR to Implementation In Progress status
     *
     * @param int $timeout       Amount of time to wait for transition
     * @param int $checkInterval Interval between checks
     * @return \Remedy\CRQ\ChangeRequest updated CR
     */
    public function toImplementationInProgress(int $timeout = 600, int $checkInterval = 5)
    {
        $this->toStatus(
            "Implementation In Progress",
            ["Scheduled"],
            ["Implementation In Progress"],
            $timeout,
            $checkInterval
        );

        // Set the actual start date
        $this->setValue("actualStartDate", Carbon::now());
        $this->save();

        return $this;
    }

    /**
     * Transitions the CR to Completed status
     *
     * @param int $timeout       Amount of time to wait for transition
     * @param int $checkInterval Interval between checks
     * @return \Remedy\CRQ\ChangeRequest updated CR
     */
    public function toCompleted(int $timeout = 600, int $checkInterval = 5)
    {
        // Validate actual start date
        if (empty($this->getDateValue("actualStartDate"))) {
            throw new ChangeRequestException("actualStartDate must be set prior to transitioning to completed status.");
        }

        // Populate actual end date if it is not populated
        if (empty($this->getDateValue("actualEndDate"))) {
            $this->setValue("actualEndDate", Carbon::now());
            $this->save();
        }

        return $this->toStatus(
            "Completed",
            ["Implementation In Progress"],
            ["Completed", "Closed"],
            $timeout,
            $checkInterval
        );
    }

    /**
     * Transitions the CR to Cancelled status
     *
     * @param int $timeout       Amount of time to wait for transition
     * @param int $checkInterval Interval between checks
     * @return \Remedy\CRQ\ChangeRequest updated CR
     */
    public function toCancelled(int $timeout = 600, int $checkInterval = 5)
    {
        $cancellableStatuses = [
            "Draft",
            "Implementation In Progress",
            "Pending",
            "Planning In Progress",
            "Rejected",
            "Request For Authorization",
            "Scheduled",
            "Scheduled For Approval",
            "Scheduled For Review",
        ];

        return $this->toStatus(
            "Cancelled",
            $cancellableStatuses,
            ["Cancelled"],
            $timeout,
            $checkInterval
        );
    }

    /**
     * Get worklogs associated to the CR
     *
     * @return \Remedy\CWL\ChangeWorklog[] array of associated worklogs
     */
    public function getWorklogsValue()
    {
        if (!isset($this->raw->workLogs) || empty($this->raw->workLogs)) {
            return [];
        }

        // parse each worklog and return them
        return array_map(function ($worklog) {
            return new ChangeWorklog($worklog);
        }, $this->raw->workLogs);
    }

    /**
     * Add a new worklog to the CR
     *
     * @param \Remedy\CWL\ChangeWorklog $worklog worklog to add
     * @param \Remedy\RemedyClient $client API client
     * @return mixed response
     */
    public function addWorklog(ChangeWorklog $worklog, $client = null)
    {
        if (!isset($this->raw->changeId)) {
            throw new ChangeRequestException("CR ID is not set for this CR! Cannot add worklog data to uncreated CR.");
        }

        $this->validateClient($client);
        return $this->client->cr->addWorklog($this->raw->changeId, $worklog);
    }

    /**
     * Alias a property from a given value to a list of valid values
     * Substrings of a valid value will match and be returned as the full valid value
     *
     * @param array $validValues array of valid values
     * @param string $newValue   new value
     * @return string aliased valid value
     */
    private function aliasProperty(array $validValues, string $newValue)
    {
        if (empty($newValue)) {
            throw new \InvalidArgumentException("An empty string cannot be aliased to be a valid property value.");
        }

        foreach ($validValues as $validValue) {
            if (strpos(strtolower($validValue), strtolower($newValue)) !== false) {
                return $validValue;
            }
        }
        throw new \InvalidArgumentException("Could not alias {$newValue} to a valid property value.");
    }

    /**
     * Get a value of unspecified type
     * This method overlays the changes object on top of the raw object
     *
     * @param string $name
     * @return mixed returned value
     */
    private function getValue($name)
    {
        // Prefer changed fields that have not been saved over the existing fields
        if (isset($this->changes->$name)) {
            return $this->changes->$name;
        }

        if (isset($this->raw->$name)) {
            return $this->raw->$name;
        }

        return null;
    }

    /**
     * Set a value of unspecified type
     * This method sets values within the changes overlay object
     *
     * @param string $name
     * @param mixed $value
     */
    private function setValue($name, $value)
    {
        return $this->changes->$name = $value;
    }

    /**
     * Get a bool value (specific to CR values that represent booleans as a string)
     *
     * @param string $name
     * @return bool returned value
     */
    private function getBoolValue($name)
    {
        // Translate the uppercased string to a boolean
        return $this->getValue($name) == "True";
    }

    /**
     * Set a bool value (specific to CR values that represent booleans as a string)
     *
     * @param string $name
     * @param string $value
     */
    private function setBoolValue($name, $value)
    {
        // Translate the bool value to an uppercased string
        return $this->changes->$name = $value ? "True" : "False";
    }

    /**
     * Get a string value (specific to CR values that are split into base and baseString keys)
     *
     * @param string $name
     * @return string|null returned value
     */
    private function getStringValue($name)
    {
        return $this->getValue("{$name}String");
    }

    /**
     * Set a string value (specific to CR values that are split into base and baseString keys)
     *
     * @param string $name
     * @param string $value
     */
    private function setStringValue($name, $value)
    {
        $key = "{$name}String";

        // Handle setting the string value differently based upon the name
        if ($name === "changeTiming") {
            return $this->changes->$key = $this->aliasProperty(self::VALID_CHANGE_TIMINGS, $value);
        } elseif ($name === "clientViewable") {
            $value = is_bool($value) ? ($value ? 'Yes' : 'No') : $value;
            return $this->changes->$key = $value;
        } elseif ($name === "impact") {
            return $this->changes->$key = $this->aliasProperty(self::VALID_IMPACTS, $value);
        } elseif ($name === "status") {
            return $this->changes->$key = $this->aliasProperty(self::VALID_STATUSES, $value);
        } elseif ($name === "urgency") {
            return $this->changes->$key = $this->aliasProperty(self::VALID_URGENCIES, $value);
        }

        return $this->changes->$key = $value;
    }

    /**
     * Get an ID value (specific to CR values that are split into base and baseString keys)
     *
     * @param string $name
     * @return int|null returned value
     */
    private function getIdValue($name)
    {
        $value = $this->getValue($name);
        return is_null($value) ? null : intval($value);
    }

    /**
     * Get a date value
     *
     * @param string $name
     * @return \Carbon\Carbon|null returned value
     */
    private function getDateValue($name)
    {
        $value = $this->getValue($name);
        if (is_null($value)) {
            return null;
        }

        // Parse the date value differently if it is a timestamp
        if (is_numeric($value)) {
            $value = (int) $value;
        }
        return Carbon::parse($value);
    }

    /**
     * Get a contact value
     *
     * @param string $name
     * @return \Remedy\PPL\Person
     */
    private function getContactValue($name)
    {
        $contact = new Person();

        $contact->company = $this->getValue("{$name}Company") ?: $this->getValue("company");
        $contact->department = $this->getValue("{$name}Department");
        $contact->email = $this->getValue("{$name}Email");
        $contact->firstName = $this->getValue("{$name}FirstName");
        $contact->loginId = $this->getValue("{$name}Id");
        $contact->lastName = $this->getValue("{$name}LastName");
        $contact->organization = $this->getValue("{$name}Organization");
        $contact->id = $this->getValue("{$name}PeopleId");
        $contact->phoneNumber = $this->getValue("{$name}PhoneNumber");
        $contact->site = $this->getValue("{$name}Site");
        $contact->siteGroup = $this->getValue("{$name}SiteGroup");
        $contact->siteId = $this->getValue("{$name}SiteId");

        if ($name === "contact") {
            $contact->loginId = $this->getValue("corporateId") ?: $this->getValue("{$name}LoginId");
        }

        return $contact;
    }

    /**
     * Set a contact value
     *
     * @param string $name
     * @param \Remedy\PPL\Person|string $value Person or login ID for the contact
     * @return void
     */
    private function setContactValue($name, $value)
    {
        if (isset($value->loginId)) {
            $value = $value->loginId;
        }

        if ($name === "contact") {
            return $this->changes->corporateId = strtolower($value);
        }

        $key = "{$name}Id";
        return $this->changes->$key = strtolower($value);
    }

    /**
     * Set corporate ID
     *
     * @param string $value
     * @return void
     */
    public function setCorporateId($value)
    {
        return $this->changes->corporateId = strtolower($value);
    }

    /**
     * Get a manager value
     *
     * @return \Remedy\CRQ\Manager
     */
    private function getManagerValue()
    {
        return new Manager($this);
    }

    /**
     * Get a coordinator value
     *
     * @return \Remedy\CRQ\Coordinator
     */
    private function getCoordinatorValue()
    {
        return new Coordinator($this);
    }

    /**
     * Get a template value
     *
     * @return string template value
     */
    private function getTemplateValue()
    {
        $template = $this->getValue("changeTemplateName");
        $template = $template ?: $this->getValue("template");
        return $template;
    }

    /**
     * Set template name
     *
     * @param string $value
     * @return void
     */
    public function setTemplate($value)
    {
        return $this->changes->changeTemplateName = $value;
    }

    /**
     * Get a relationship value
     *
     * @param string $name
     * @return array returned relationships
     */
    private function getRelationshipValue($name)
    {
        // Craft a merged list of relationships
        $relationships = [];

        // Check for data cached from last save
        if (isset($this->raw->relationships->$name)) {
            $relationships = array_merge($relationships, $this->raw->relationships->$name);
        }

        if ($name == 'businessServices' && isset($this->raw->impactedAreas)) {
            $currentServices = array_map(function ($v) {
                return $v->company;
            }, $this->raw->impactedAreas);

            $relationships = array_merge($relationships, $currentServices);
        }

        if ($name == 'computerSystems' && isset($this->raw->relationships) && is_array($this->raw->relationships)) {
            $currentComputerSystems = array_map(function ($v) {
                return $v->requestSummary;
            }, $this->raw->relationships);
            $relationships = array_merge($relationships, $currentComputerSystems);
        }

        if (isset($this->changes->relationships->$name)) {
            $relationships = array_merge($relationships, $this->changes->relationships->$name);
        }

        return $relationships;
    }

    /**
     * Set a relationship value
     *
     * @param string $name
     * @param string|array $value string or array representing the new relationship(s)
     * @return void
     */
    private function setRelationshipValue($name, $value)
    {
        if (!isset($this->changes->relationships)) {
            $this->changes->relationships = (object) [];
        }

        // Coerce value into array
        if (!is_array($value)) {
            $value = [$value];
        }

        $this->changes->relationships->$name = $value;
    }

    /**
     * Merge all changes into the request object and empty out the changes object
     *
     * @return void
     */
    private function commitChanges()
    {
        $this->raw = (object) array_replace_recursive((array) $this->raw, (array) $this->changes);
        $this->changes = (object) [];
    }

    /**
     * Update all relationships on the CR
     *
     * @return void
     */
    private function updateRelationships()
    {
        // Return early if there are no relationship changes
        if (!isset($this->changes->relationships) || empty($this->changes->relationships)) {
            return;
        }

        $this->aliasBusinessServices();
        $this->aliasComputerSystems();
        $this->relateInstanceIds();
    }

    /**
     * Alias the related business services into specific instance IDs
     *
     * @return void
     */
    private function aliasBusinessServices()
    {
        if (isset($this->changes->relationships->businessServices)) {
            // Find and relate each listed business service by company
            $svcName = "_HEALTH_CLINICAL";
            foreach ($this->changes->relationships->businessServices as $company) {
                $ci = $this->client->ci->getBusinessService($company, $svcName);
                if (!isset($ci)) {
                    // Failed to retrieve CI data, skip relationship
                    continue;
                }

                $noInstanceIds = empty($this->changes->relationships->instanceIds);
                if ($noInstanceIds || !array_key_exists($ci->instanceId, $this->changes->relationships->instanceIds)) {
                    $this->changes->relationships->instanceIds[] = $ci->instanceId;
                }
            }
        }
    }

    /**
     * Alias the related computer system into specific instance IDs
     *
     * @return void
     */
    private function aliasComputerSystems()
    {
        if (isset($this->changes->relationships->computerSystems)) {
            // Find and relate each listed computerSystem by FQDN
            foreach ($this->changes->relationships->computerSystems as $fqdn) {
                $ci = $this->client->ci->getComputerSystemByFqdn($fqdn);

                if (!isset($ci)) {
                    // Failed to retrieve CI data, skip relationship
                    continue;
                }

                $noInstanceIds = empty($this->changes->relationships->instanceIds);
                if ($noInstanceIds || !array_key_exists($ci->instanceId, $this->changes->relationships->instanceIds)) {
                    $this->changes->relationships->instanceIds[] = $ci->instanceId;
                }
            }
        }
    }

    /**
     * Relate modified instance IDs to this CR
     *
     * @return void
     */
    private function relateInstanceIds()
    {
        if (isset($this->changes->relationships->instanceIds)) {
            // Find and relate each listed CI instance ID
            foreach ($this->changes->relationships->instanceIds as $ciId) {
                $this->client->ci->relateToCr($this->raw->changeId, $ciId);
            }
        }
    }

    /**
     * Sets the CR to a provided status
     *
     * @param string $status Status to change to
     * @param string[] $startStatuses Valid starting statuses
     * @param string[] $endStatuses Valid ending statuses
     * @param int $timeout       Amount of time to wait for transition
     * @param int $checkInterval Interval between checks
     * @return void
     */
    private function toStatus($status, $startStatuses, $endStatuses, $timeout = 600, $checkInterval = 5)
    {
        // If the cached or fresh status indicates we are already in a target status, return without changes
        if (
            in_array($this->getStringValue("status"), $endStatuses) ||
            in_array($this->fresh(false)->getStringValue("status"), $endStatuses)
        ) {
            return $this;
        }

        // If we are already in a state that cannot transition
        if (!in_array($this->getStringValue("status"), $startStatuses)) {
            throw new ChangeRequestException(
                "{$this->getStringValue("status")} is not a valid initial status when moving to {$status}. " .
                "Valid statuses include: " . implode(", ", $startStatuses)
            );
        }

        $this->setStringValue("status", $status);
        $this->save();

        // Wait for the status to transition
        while (!in_array($this->fresh()->getStringValue("status"), $endStatuses)) {
            sleep($checkInterval);
            $timeout -= $checkInterval;
            if ($timeout < 0) {
                throw new ChangeRequestException(
                    "Timed out waiting for CR {$this->getValue("changeId")} to transition to {$status} status."
                );
            }
        }

        return $this;
    }

    /**
     * Validates that the CR has an associated client
     *
     * @param \Remedy\RemedyClient $client client to use, will replace any existing associated client
     * @return void
     */
    private function validateClient($client = null)
    {
        // Set the provided client (if any)
        if (isset($client)) {
            $this->client = $client;
        }

        if (!isset($this->client)) {
            throw new ChangeRequestException("Client is not set for this CR, cannot access the API!");
        }
    }

    /**
     * Serialize JSON
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $returnArray = [];
        foreach (array_keys(self::PROPERTY_TYPES) as $k) {
            $returnArray[$k] = $this->$k;
        }
        return $returnArray;
    }
}
