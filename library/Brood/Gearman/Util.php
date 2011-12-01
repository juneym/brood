<?php

namespace Brood\Gearman;

class Util
{
    const BROOD_PROTOCOL_MAGIC = 'brood';
    const BROOD_PROTOCOL_VERSION = 1;

    public static function getFunctionName($host)
    {
        // truncate hash to 20 chars to work around a bug in gearman where workloads
        // would have extra bytes prepended to them if we use a long job name
        return substr(sha1($host), 0, 20);
    }

    public static function encodeWorkload($workload)
    {
        return serialize(array(
            self::BROOD_PROTOCOL_MAGIC,
            self::BROOD_PROTOCOL_VERSION,
            $workload
        ));
    }

    public static function decodeWorkload($encodedWorkload)
    {
        list($magic, $version, $workload) = @unserialize($encodedWorkload);

        if ($magic != self::BROOD_PROTOCOL_MAGIC) {
            throw new \RuntimeException('Job workload does not start with brood magic marker; only cerebrates and overlords may control zerg minions');
        }

        if ($version != self::BROOD_PROTOCOL_VERSION) {
            throw new \RuntimeException(sprintf('Job workload brood protocol version was %d, expected %d', $version, self::BROOD_PROTOCOL_VERSION));
        }

        return $workload;
    }
}
