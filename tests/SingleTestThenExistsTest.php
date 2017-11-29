<?php
declare(strict_types=1);

namespace EasyDB\Tests;

use EasyDB\EasyDB;

class SingleTestThenExistsTest extends EasyDBWriteTest
{
    protected function getResultForMethod(EasyDB $db, $statement, $params)
    {
        $args = $params;
        array_unshift($args, $statement);
        return call_user_func_array([$db, 'exists'], $args);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2EasyDBInsertManyProvider
     * @depends      EasyDB\Tests\Is1DArrayThenDeleteReadOnlyTest::testDeleteThrowsException
     * @depends      EasyDB\Tests\Is1DArrayThenDeleteReadOnlyTest::testDeleteTableNameEmptyThrowsException
     * @depends      EasyDB\Tests\Is1DArrayThenDeleteReadOnlyTest::testDeleteTableNameInvalidThrowsException
     * @depends      EasyDB\Tests\Is1DArrayThenDeleteReadOnlyTest::testDeleteConditionsReturnsNull
     * @depends      EasyDB\Tests\InsertManyTest::testInsertMany
     * @depends      EasyDB\Tests\SingleTest::testMethod
     * @param callable $cb
     * @param array $insertMany
     */
    public function testExists(callable $cb, array $insertMany)
    {
        $db = $this->easyDBExpectedFromCallable($cb);
        $this->assertFalse(
            $db->exists('SELECT COUNT(*) FROM irrelevant_but_valid_tablename')
        );
        $db->insertMany('irrelevant_but_valid_tablename', $insertMany);
        $this->assertTrue(
            $db->exists('SELECT COUNT(*) FROM irrelevant_but_valid_tablename')
        );
        foreach ($insertMany as $insertVal) {
            $this->assertTrue(
                $this->getResultForMethod(
                    $db,
                    'SELECT COUNT(*) FROM irrelevant_but_valid_tablename WHERE foo = ?',
                    array_values($insertVal)
                )
            );
            $db->delete('irrelevant_but_valid_tablename', $insertVal);
            $this->assertFalse(
                $this->getResultForMethod(
                    $db,
                    'SELECT COUNT(*) FROM irrelevant_but_valid_tablename WHERE foo = ?',
                    array_values($insertVal)
                )
            );
        }
        $this->assertFalse(
            $db->exists('SELECT COUNT(*) FROM irrelevant_but_valid_tablename')
        );
    }
}
