<?php
declare(strict_types=1);

namespace EasyDB\Tests;

use EasyDB\EasyDB;

class QTest extends RunTest
{
    protected function getResultForMethod(EasyDB $db, $statement, $offset, $params)
    {
        $args = $params;
        array_unshift($args, $statement);

        return call_user_func_array([$db, 'q'], $args);
    }
}
