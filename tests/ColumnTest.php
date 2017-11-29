<?php
declare(strict_types=1);

namespace EasyDB\Tests;

use EasyDB\EasyDB;

class ColumnTest extends ColTest
{
    protected function getResultForMethod(EasyDB $db, $statement, $offset, $params)
    {
        return $db->column($statement, $params, $offset);
    }
}
