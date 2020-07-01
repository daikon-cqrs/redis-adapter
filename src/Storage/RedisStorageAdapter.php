<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/redis-adapter project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Redis\Storage;

use Daikon\ReadModel\Projection\ProjectionMap;
use Daikon\Redis\Connector\RedisConnector;
use Daikon\ReadModel\Storage\StorageAdapterInterface;
use Daikon\ReadModel\Storage\StorageResultInterface;

final class RedisStorageAdapter implements StorageAdapterInterface
{
    private RedisConnector $connector;

    private array $settings;

    public function __construct(RedisConnector $connector, array $settings = [])
    {
        $this->connector = $connector;
        $this->settings = $settings;
    }

    public function read(string $identifier): StorageResultInterface
    {
        $projections = [];
        //@todo handle errors
        $hashMap = $this->connector->getConnection()->hGetAll($identifier);

        if (!empty($hashMap)) {
            $projectionClass = $hashMap['@type'];
            $projections = [$projectionClass::fromNative($hashMap)];
        }

        return new RedisStorageResult(
            new ProjectionMap($projections)
        );
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
