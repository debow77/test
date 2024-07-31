<?php

/**
 * Class CrModifyClient | Remedy/CRQ/CrModifyClient.php
 */

namespace Remedy\CRQ;

use Rapid\RapidClient;

/**
 * CrModifyClient interacts with the Remedy change request API
 */
class CrModifyClient extends RapidClient
{
    protected function getPath()
    {
        return "remedy-change-svc/v2/";
    }
}
