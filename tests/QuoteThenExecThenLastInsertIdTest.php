<?php
declare(strict_types=1);

namespace EasyDB\Tests;

/**
 * Class ExecTest
 * @package EasyDB\Tests
 */
class QuoteThenExecThenLastInsertIdTest extends EasyDBWriteTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2EasyDBInsertManyProvider
     * @depends      EasyDB\Tests\QuoteTest::testQuote
     * @depends      EasyDB\Tests\EscapeIdentifierTest::testEscapeIdentifier
     * @depends      EasyDB\Tests\EscapeIdentifierTest::testEscapeIdentifierThrowsSomething
     * @depends      EasyDB\Tests\QuoteThenExecTest::testExec
     * @param callable $cb
     * @param array $maps
     */
    public function testLastInsertId(callable $cb, array $maps)
    {
        $db = $this->easyDBExpectedFromCallable($cb);
        $table = 'irrelevant_but_valid_tablename';

        $first = $maps[0];

        // Let's make sure our keys are escaped.
        $keys = \array_keys($first);
        foreach ($keys as $i => $v) {
            $keys[$i] = $db->escapeIdentifier($v);
        }

        foreach ($maps as $params) {
            $queryString = "INSERT INTO " . $db->escapeIdentifier($table) . " (";

            // Now let's append a list of our columns.
            $queryString .= \implode(', ', $keys);

            // This is the middle piece.
            $queryString .= ") VALUES (";

            // Now let's concatenate the ? placeholders
            $queryString .= \implode(
                ', ',
                \array_map(
                    function ($val) use ($db) {
                        return $db->quote($val);
                    },
                    $params
                )
            );

            // Necessary to close the open ( above
            $queryString .= ");";

            $db->exec($queryString);

            $this->assertSame($db->lastInsertId(), current($params));
        }
    }
}
