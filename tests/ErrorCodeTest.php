<?php
declare(strict_types=1);

namespace EasyDB\Tests;

/**
 * Class EasyDBTest
 * @package EasyDB\Tests
 */
class ErrorCodeTest extends EasyDBTest
{

    /**
     * @param callable $cb
     * @dataProvider goodFactoryCreateArgument2EasyDBProvider
     */
    public function testNoError(callable $cb)
    {
        $db = $this->easyDBExpectedFromCallable($cb);

        $this->assertSame($db->errorCode(), '00000');
    }
}
