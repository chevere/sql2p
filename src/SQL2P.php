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

namespace Chevere\SQL2P;

use Chevere\Writer\Interfaces\WriterInterface;
use Countable;
use iamcal\SQLParser;
use LogicException;

final class SQL2P implements Countable
{
    public const TRANSLATION = [
        'DATE' => 'date',
        'TIME' => 'string',
        'DATETIME' => 'datetime',
        'BIGINT' => 'int',
        'DECIMAL' => 'float',
        'ENUM' => 'enum',
        'FLOAT' => 'float',
        'INT' => 'int',
        'JSON' => 'string',
        'MEDIUMINT' => 'int',
        'MEDIUMTEXT' => 'string',
        'SMALLINT' => 'int',
        'TEXT' => 'string',
        'TIMESTAMP' => 'int',
        'TINYINT' => 'int',
        'TINYTEXT' => 'string',
        'VARCHAR' => 'string',
        'POINT' => 'string',
        'LONGTEXT' => 'string',
        'CHAR' => 'string',
    ];

    public const HEADER = <<<'PHP'
    <?php

    %HEADER%

    use Chevere\Parameter\Interfaces\ArrayParameterInterface;
    use function Chevere\Parameter\arrayp;
    use function Chevere\Parameter\boolInt;
    use function Chevere\Parameter\date;
    use function Chevere\Parameter\datetime;
    use function Chevere\Parameter\enum;
    use function Chevere\Parameter\float;
    use function Chevere\Parameter\int;
    use function Chevere\Parameter\null;
    use function Chevere\Parameter\string;
    use function Chevere\Parameter\union;

    // @codeCoverageIgnoreStart

    PHP;

    private int $count = 0;

    /**
     * @param string $sql SQL to parse containing CREATE TABLE statements
     * @param string $header Header to use after the opening PHP tag `<?php`
     */
    public function __construct(
        string $sql,
        private WriterInterface $output,
        private string $header = '',
    ) {
        $header = str_replace('%HEADER%', $this->header, self::HEADER);
        $this->output->write($header);
        $parser = new SQLParser();
        $tables = $parser->parse($sql);
        foreach ($tables as $table) {
            $this->writeTable($table);
        }
        $this->output->write(
            <<<PHP

            // @codeCoverageIgnoreEnd

            PHP
        );
    }

    public function count(): int
    {
        return $this->count;
    }

    // @phpstan-ignore-next-line
    private function writeTable(array $table): void
    {
        $tableName = $table['name'];
        $nameCamel = $this->snakeToCamel($tableName) . 'Table';
        $this->output->write(
            <<<PHP

            function {$nameCamel}(): ArrayParameterInterface
            {
                return arrayp(
            PHP
        );
        $columns = $table['fields'];
        foreach ($columns as $pos => $column) {
            $this->writeColumn($tableName, $column);
            if ($pos !== count($columns) - 1) {
                $this->output->write(',');
            }
        }
        $this->output->write(
            <<<PHP

                );
            }

            PHP
        );
        $this->count++;
    }

    // @phpstan-ignore-next-line
    private function writeColumn(string $table, array $column): void
    {
        $arguments = [];
        $columnName = $column['name'];
        $columnType = $column['type'];
        $function = self::TRANSLATION[$column['type']] ?? null;
        if ($function === null) {
            throw new LogicException("Unsupported type {$columnType} for {$table}.{$columnName}");
        }
        if ($columnType === 'TINYINT' && $column['length'] === '1') {
            $function = 'boolInt';
        }
        if ($function === 'string' && isset($column['length'])) {
            $length = $column['length'];
            $arguments[] = <<<PHP
            regex: "/^.{0,{$length}}$/"
            PHP;
        }
        $isUnsigned = $column['unsigned'] ?? false;
        if ($isUnsigned && $function !== 'boolInt') {
            $arguments[] = <<<PHP
            min: 0
            PHP;
        }
        if ($function === 'enum') {
            foreach ($column['values'] as $value) {
                $value = var_export($value, true);
                $arguments[] = <<<PHP
                {$value}
                PHP;
            }
        }
        $arguments = implode(', ', $arguments);
        $code = match ($column['null'] ?? false) {
            true => <<<PHP
            union(
                        null(),
                        {$function}({$arguments})
                    )
            PHP,
            default => <<<PHP
            {$function}({$arguments})
            PHP
        };
        $this->output->write(
            <<<PHP

                    {$columnName}: {$code}
            PHP
        );
    }

    private function snakeToCamel(string $snakeCase): string
    {
        return lcfirst(
            str_replace(
                ' ',
                '',
                ucwords(
                    str_replace('_', ' ', $snakeCase)
                )
            )
        );
    }
}
