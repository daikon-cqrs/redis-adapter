<?php
/**
 * This file is part of the daikon-cqrs/redis-adapter project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Redis\Storage;

use Daikon\Redis\Connector\RedisConnector;
use Daikon\ReadModel\Projection\ProjectionInterface;
use Daikon\ReadModel\Storage\StorageAdapterInterface;

final class RedisStorageAdapter implements StorageAdapterInterface
{
    /** @var RedisConnector */
    private $connector;

    /** @var array */
    private $settings;

    public function __construct(RedisConnector $connector, array $settings = [])
    {
        $this->connector = $connector;
        $this->settings = $settings;
    }

    public function read(string $identifier): ?ProjectionInterface
    {
        $hashMap = $this->connector->getConnection()->hGetAll($identifier);
        if (empty($hashMap)) {
            return null;
        }
        $projectionClass = $hashMap['@type'];
        return $projectionClass::fromNative($hashMap);
    }

    public function write(string $identifier, array $data): bool
    {
        return $this->connector->getConnection()->hMSet($identifier, $data);
    }

    public function delete(string $identifier): bool
    {
        return $this->connector->getConnection()->unlink($identifier);
    }
}
