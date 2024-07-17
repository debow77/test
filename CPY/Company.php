<?php

/**
 * Class Company | Remedy/CPY/Company.php
 */

namespace Remedy\CPY;

use JsonSerializable;

/**
 * Represents a Remedy Company
 */
class Company implements JsonSerializable
{
    /**
     * Creates a new Company object
     *
     * @param object $companyResponse Company API response to use to construct the Company object
     */
    public function __construct(object $companyResponse = null)
    {
        if (isset($companyResponse)) {
            foreach ($companyResponse as $k => $v) {
                $this->$k = $v;
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
        return get_object_vars($this);
    }
}
