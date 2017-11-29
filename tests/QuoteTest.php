<?php
declare(strict_types=1);

namespace EasyDB\Tests;

/**
 * Class ExecTest
 * @package EasyDB\Tests
 */
class QuoteTest extends EasyDBTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2EasyDBQuoteProvider
     * @depends      EasyDB\Tests\EscapeIdentifierTest::testEscapeIdentifier
     * @depends      EasyDB\Tests\EscapeIdentifierTest::testEscapeIdentifierThrowsSomething
     * @param callable $cb
     * @param $quoteThis
     * @param array $expectOneOfThese
     */
    public function testQuote(callable $cb, $quoteThis, array $expectOneOfThese)
    {
        $db = $this->easyDBExpectedFromCallable($cb);

        $this->assertTrue(count($expectOneOfThese) > 0);

        $matchedOneOfThose = false;
        $quoted = $db->quote((string)$quoteThis);

        foreach ($expectOneOfThese as $expectThis) {
            if ($quoted === $expectThis) {
                $this->assertSame($quoted, $expectThis);
                $matchedOneOfThose = true;
            }
        }
        if (!$matchedOneOfThose) {
            $this->assertTrue(
                false,
                'Did not match ' . $quoted . ' against any of ' . implode('; ', $expectOneOfThese)
            );
        }
    }
}
