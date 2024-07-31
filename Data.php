<?php

/**
 * Class Data | Remedy/CRQ/Data.php
 */

namespace Remedy\CRQ;

use JsonSerializable;

/**
 * Abstract Remedy Change Request Data class
 * Handles setting and getting data from an associated CR
 */
abstract class Data implements JsonSerializable
{
    /**
     * Associative array of valid fields mapped to the accompanying Remedy field name
     *
     * @var array
     */
    protected $remedyFields = [];

    /**
     * Associated Change Request
     *
     * @var \Remedy\CRQ\ChangeRequest
     */
    protected $cr;

    /**
     * Remedy Change Request Data contructor
     *
     * @param \Remedy\CRQ\ChangeRequest $cr change request to associate to this data
     */
    public function __construct(ChangeRequest $cr = null)
    {
        // If a CR is passed in, notify it about any property changes and populate the values from the CR
        $this->cr = $cr;
        $this->parseCr();
    }

    /**
     * Magic getter, prevents getting of undefined properties
     *
     * @param string $name name of the property to retrieve
     * @return mixed value of the property
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->remedyFields)) {
            return $this->$name;
        }
        throw new \RuntimeException("{$name} is not a valid property.");
    }

    /**
     * Magic setter, prevents setting of invalid properties and updates
     * the associated CR (if any)
     *
     * @param string $name name of the property to set
     * @param mixed $value value to set on the property
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->remedyFields)) {
            // If there is an associated CR, update the field on the CR
            if (isset($this->cr)) {
                $remedyField = $this->remedyFields[$name];
                $this->cr->changes->$remedyField = $value;
            }
            return $this->$name = $value;
        }

        throw new \RuntimeException("{$name} is not a valid property.");
    }

    /**
     * Parses the CR and reads in its current data based on the remedyFields
     *
     * @return void
     */
    protected function parseCr()
    {
        if (isset($this->cr)) {
            foreach ($this->remedyFields as $dataField => $remedyField) {
                $this->$dataField = isset($this->cr->changes->$remedyField) ? $this->cr->changes->$remedyField : null;
                if (empty($this->$dataField)) {
                    $this->$dataField = isset($this->cr->raw->$remedyField) ? $this->cr->raw->$remedyField : null;
                }
            }
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
        foreach (array_keys($this->remedyFields) as $k) {
            $returnArray[$k] = $this->$k;
        }
        return $returnArray;
    }
}
