# 物联网发送指令

|参数   | 类型   | 允许空| 说明 |
|:---- |:------ |:----|:--- |
|topic |NSString| 否  |设备的订阅号,即设备的/(device_access) 示例: @"/cc5196bfcc33"|
### 示例

    SJScentking *scentking = [[SJScentking alloc]init];
    [scentking sendWiFiDataWithDict:nil SrCmdId:SCI_REQ_DEVICE_MODEL ToTopic:topic];
