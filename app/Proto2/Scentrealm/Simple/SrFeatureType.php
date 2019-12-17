<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * SrFeatureType enum
 */
final class SrFeatureType
{
    const SFT_switch = 1;
    const SFT_scrollBar = 2;
    const SFT_textbox = 3;
    const SFT_rotation = 4;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'SFT_switch' => self::SFT_switch,
            'SFT_scrollBar' => self::SFT_scrollBar,
            'SFT_textbox' => self::SFT_textbox,
            'SFT_rotation' => self::SFT_rotation,
        );
    }
}
}