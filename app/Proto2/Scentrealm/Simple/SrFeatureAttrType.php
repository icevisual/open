<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * SrFeatureAttrType enum
 */
final class SrFeatureAttrType
{
    const SFAT_name = 1;
    const SFAT_num = 2;
    const SFAT_state = 3;
    const SFAT_max = 4;
    const SFAT_min = 5;
    const SFAT_step = 6;
    const SFAT_default = 7;
    const SFAT_angle = 8;
    const SFAT_angular_speed = 9;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'SFAT_name' => self::SFAT_name,
            'SFAT_num' => self::SFAT_num,
            'SFAT_state' => self::SFAT_state,
            'SFAT_max' => self::SFAT_max,
            'SFAT_min' => self::SFAT_min,
            'SFAT_step' => self::SFAT_step,
            'SFAT_default' => self::SFAT_default,
            'SFAT_angle' => self::SFAT_angle,
            'SFAT_angular_speed' => self::SFAT_angular_speed,
        );
    }
}
}