<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevere.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Chevere\Tests;

use Chevere\SQL2P\SQL2P;
use Chevere\Writer\StreamWriter;
use LogicException;
use PHPUnit\Framework\TestCase;
use function Chevere\Writer\streamTemp;

final class SQL2PTest extends TestCase
{
    public function dataProviderUnsupportedTypes(): array
    {
        return [
            ['BINARY'],
            ['BIT'],
            ['BLOB'],
            ['BOOL'],
            ['BOOLEAN'],
            ['CHARACTER VARYING'],
            ['DEC'],
            ['DOUBLE PRECISION'],
            ['DOUBLE'],
            ['FIXED'],
            ['GEOMETRY'],
            ['GEOMETRYCOLLECTION'],
            ['LINESTRING'],
            ['LONGBLOB'],
            ['MEDIUMBLOB'],
            ['MULTILINESTRING'],
            ['MULTIPOINT'],
            ['MULTIPOLYGON'],
            ['NUMERIC'],
            ['POLYGON'],
            ['REAL'],
            ['SET'],
            ['TINYBLOB'],
            ['VARBINARY'],
            ['YEAR'],
        ];
    }

    public function testConstruct(): void
    {
        $sql = file_get_contents(__DIR__ . '/src/schema.sql');
        $stream = streamTemp();
        $writer = new StreamWriter($stream);
        $sql2p = new SQL2P(
            $sql,
            $writer,
            <<<PHP
            /** hello */
            PHP,
        );
        $expected = file_get_contents(__DIR__ . '/src/sql2p.php');
        $this->assertSame($expected, $writer->__toString());
        $this->assertCount(1, $sql2p);
    }

    /**
     * @dataProvider dataProviderUnsupportedTypes
     */
    public function testUnsupportedType(string $type): void
    {
        $sql = <<<MySQL
        CREATE TABLE `table` (
            `id` {$type}(),
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        MySQL;
        $stream = streamTemp();
        $writer = new StreamWriter($stream);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            <<<PLAIN
            Unsupported type {$type} for table.id
            PLAIN
        );
        new SQL2P($sql, $writer);
    }
}
