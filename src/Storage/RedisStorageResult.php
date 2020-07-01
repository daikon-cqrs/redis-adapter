<?php declare(strict_types=1);
/**
 * This file is part of the daikon-cqrs/redis-adapter project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Daikon\Redis\Storage;

use Daikon\Metadata\Metadata;
use Daikon\Metadata\MetadataInterface;
use Daikon\ReadModel\Projection\ProjectionInterface;
use Daikon\ReadModel\Projection\ProjectionMap;
use Daikon\ReadModel\Storage\StorageResultInterface;

final class RedisStorageResult implements StorageResultInterface
{
    private ProjectionMap $projectionMap;

    private MetadataInterface $metadata;

    public function __construct(ProjectionMap $projectionMap, MetadataInterface $metadata = null)
    {
        $this->projectionMap = $projectionMap;
        $this->metadata = $metadata ?? Metadata::makeEmpty();
    }

    public function getProjectionMap(): ProjectionMap
    {
        return $this->projectionMap;
    }

    public function getMetadata(): MetadataInterface
    {
        return $this->metadata;
    }

    public function getFirst(): ?ProjectionInterface
    {
        if ($this->projectionMap->isEmpty()) {
            return null;
        }
        return $this->projectionMap->first();
    }

    public function getLast(): ?ProjectionInterface
    {
        if ($this->projectionMap->isEmpty()) {
            return null;
        }
        return $this->projectionMap->last();
    }

    public function isEmpty(): bool
    {
        return $this->projectionMap->isEmpty();
    }

    public function getIterator(): ProjectionMap
    {
        return $this->projectionMap;
    }

    public function count(): int
    {
        return $this->projectionMap->count();
    }

    private function __clone()
    {
        $this->projectionMap = clone $this->projectionMap;
        $this->metadata = clone $this->metadata;
    }
}
