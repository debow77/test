<?php

/**
 * Class CrQueryClient | Remedy/CRQ/CrQueryClient.php
 */

namespace Remedy\CRQ;

use Rapid\RapidClient;

/**
 * CrQueryClient interacts with the Remedy change request query API
 */
class CrQueryClient extends RapidClient
{
    protected function getPath()
    {
        return "remedy-change-query-svc/v1/";
    }
}
