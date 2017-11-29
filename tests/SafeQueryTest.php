<?php
declare(strict_types=1);

namespace EasyDB\Tests;

use EasyDB\EasyDB;

class SafeQueryTest extends RunTest
{
    protected function getResultForMethod(EasyDB $db, $statement, $offset, $params)
    {
        return $db->safeQuery($statement, $params);
    }
}
