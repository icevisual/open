<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * SrDevAttrType enum
 */
final class SrDevAttrType
{
    const SDST_deviceID = 1;
    const SDST_deviceName = 2;
    const SDST_deviceType = 3;
    const SDST_mac = 4;
    const SDST_wifiSsid = 5;
    const SDST_wifiPwd = 6;
    const SDST_netConnectState = 7;
    const SDST_bleConnectState = 8;
    const SDST_logState = 9;
    const SDST_datetime = 10;
    const SDST_uptime = 11;
    const SDST_downtime = 12;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'SDST_deviceID' => self::SDST_deviceID,
            'SDST_deviceName' => self::SDST_deviceName,
            'SDST_deviceType' => self::SDST_deviceType,
            'SDST_mac' => self::SDST_mac,
            'SDST_wifiSsid' => self::SDST_wifiSsid,
            'SDST_wifiPwd' => self::SDST_wifiPwd,
            'SDST_netConnectState' => self::SDST_netConnectState,
            'SDST_bleConnectState' => self::SDST_bleConnectState,
            'SDST_logState' => self::SDST_logState,
            'SDST_datetime' => self::SDST_datetime,
            'SDST_uptime' => self::SDST_uptime,
            'SDST_downtime' => self::SDST_downtime,
        );
    }
}
}