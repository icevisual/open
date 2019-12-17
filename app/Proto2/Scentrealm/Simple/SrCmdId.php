<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * SrCmdId enum
 */
final class SrCmdId
{
    const SCI_req_sleep = 1;
    const SCI_resp_sleep = 2;
    const SCI_req_wakeup = 3;
    const SCI_resp_wakeup = 4;
    const SCI_req_usedSeconds = 5;
    const SCI_resp_usedSeconds = 6;
    const SCI_req_playSmell = 7;
    const SCI_resp_playSmell = 8;
    const SCI_req_getDevAttr = 9;
    const SCI_resp_getDevAttr = 10;
    const SCI_req_setDevAttr = 11;
    const SCI_resp_setDevAttr = 12;
    const SCI_req_featureReport = 13;
    const SCI_resp_featureReport = 14;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'SCI_req_sleep' => self::SCI_req_sleep,
            'SCI_resp_sleep' => self::SCI_resp_sleep,
            'SCI_req_wakeup' => self::SCI_req_wakeup,
            'SCI_resp_wakeup' => self::SCI_resp_wakeup,
            'SCI_req_usedSeconds' => self::SCI_req_usedSeconds,
            'SCI_resp_usedSeconds' => self::SCI_resp_usedSeconds,
            'SCI_req_playSmell' => self::SCI_req_playSmell,
            'SCI_resp_playSmell' => self::SCI_resp_playSmell,
            'SCI_req_getDevAttr' => self::SCI_req_getDevAttr,
            'SCI_resp_getDevAttr' => self::SCI_resp_getDevAttr,
            'SCI_req_setDevAttr' => self::SCI_req_setDevAttr,
            'SCI_resp_setDevAttr' => self::SCI_resp_setDevAttr,
            'SCI_req_featureReport' => self::SCI_req_featureReport,
            'SCI_resp_featureReport' => self::SCI_resp_featureReport,
        );
    }
}
}