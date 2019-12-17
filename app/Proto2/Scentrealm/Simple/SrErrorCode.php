<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * SrErrorCode enum
 */
final class SrErrorCode
{
    const SEC_success = 1;
    const SEC_accept = 2;
    const SEC_error = -1;
    const SEC_reject = -2;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'SEC_success' => self::SEC_success,
            'SEC_accept' => self::SEC_accept,
            'SEC_error' => self::SEC_error,
            'SEC_reject' => self::SEC_reject,
        );
    }
}
}