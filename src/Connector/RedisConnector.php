<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/redis-adapter project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Redis\Connector;

use Daikon\Dbal\Connector\ConnectorInterface;
use Daikon\Dbal\Connector\ConnectorTrait;
use Redis;

final class RedisConnector implements ConnectorInterface
{
    use ConnectorTrait;

    private function connect(): Redis
    {
        $redis = new Redis;
        $redis->connect($this->settings['host'], $this->settings['port']);
        $redis->auth($this->settings['password'] ?? '');
        $redis->select((int)($this->settings['database'] ?? 0));
        return $redis;
    }
}
