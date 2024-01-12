# SQL2P

> 🔔 Subscribe to the [newsletter](https://chv.to/chevere-newsletter) to don't miss any update regarding Chevere.

![Chevere](chevere.svg)

[![Build](https://img.shields.io/github/actions/workflow/status/chevere/sql2p/test.yml?branch=1.0&style=flat-square)](https://github.com/chevere/sql2p/actions)
![Code size](https://img.shields.io/github/languages/code-size/chevere/sql2p?style=flat-square)
[![Apache-2.0](https://img.shields.io/github/license/chevere/sql2p?style=flat-square)](LICENSE)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%209-blueviolet?style=flat-square)](https://phpstan.org/)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat-square&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fchevere%2Fsql2p%2F1.0)](https://dashboard.stryker-mutator.io/reports/github.com/chevere/sql2p/1.0)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=chevere_sql2p&metric=alert_status)](https://sonarcloud.io/dashboard?id=chevere_sql2p)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=chevere_sql2p&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=chevere_sql2p)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=chevere_sql2p&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=chevere_sql2p)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=chevere_sql2p&metric=security_rating)](https://sonarcloud.io/dashboard?id=chevere_sql2p)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=chevere_sql2p&metric=coverage)](https://sonarcloud.io/dashboard?id=chevere_sql2p)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=chevere_sql2p&metric=sqale_index)](https://sonarcloud.io/dashboard?id=chevere_sql2p)
[![CodeFactor](https://www.codefactor.io/repository/github/chevere/sql2p/badge)](https://www.codefactor.io/repository/github/chevere/sql2p)

## Quick start

```php
use Chevere\SQL2P\SQL2P;
use Chevere\Writer\StreamWriter;
use function Chevere\Filesystem\fileForPath;
use function Chevere\Writer\streamFor;

$sql = fileForPath(__DIR__ . '/schema.sql')->getContents();
$output = fileForPath(__DIR__ . '/sql2p.php');
$output->createIfNotExists();
$stream = streamFor($output->path()->__toString(), 'w');
$writer = new StreamWriter($stream);
$head = <<<PHP
declare(strict_types=1);

namespace SmartCrop\Schema;
PHP;
$sql2p = new SQL2P($sql, $writer, $head);
$count = count($sql2p);
echo <<<PLAIN
[{$count} tables] {$output->path()}

PLAIN;
```

## Documentation

Documentation is available at [chevere.org](https://chevere.org/).

## License

Copyright [Rodolfo Berrios A.](https://rodolfoberrios.com/)

Chevere is licensed under the Apache License, Version 2.0. See [LICENSE](LICENSE) for the full license text.

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
