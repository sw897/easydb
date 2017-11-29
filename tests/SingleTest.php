<?php
declare(strict_types=1);

namespace EasyDB\Tests;

use EasyDB\EasyDB;

class SingleTest extends CellTest
{
    protected function getResultForMethod(EasyDB $db, $statement, $offset, $params)
    {
        return $db->single($statement, $params);
    }
}
