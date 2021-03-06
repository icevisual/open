# 产品概述

## 什么是气味物联网

> 物联网，Internet of things（IoT），是新一代信息技术的重要组成部分，也是“信息化”时代的重要发展阶段。物联网就是物物相连的互联网。这有两层意思：其一，物联网的核心和基础仍然是互联网，是在互联网基础上的延伸和扩展的网络；其二，其用户端延伸和扩展到了任何物品与物品之间，进行信息交换和通信，也就是物物相息。物联网通过智能感知、识别技术与普适计算等通信感知技术，广泛应用于网络的融合中，也因此被称为继计算机、互联网之后世界信息产业发展的第三次浪潮。物联网是互联网的应用拓展，与其说物联网是网络，不如说物联网是业务和应用。因此，应用创新是物联网发展的核心，以用户体验为核心的创新 2.0 是物联网发展的灵魂。

气味物联网是架构在物联网之上专门为气味设备服务的物联网，它负责气味设备和控制设备的接入、数据传输、认证授权等工作，是气味服务的底层构架。

### 什么是气味设备

气味设备，顾名思义，就是用于响应气味指令的设备，内含多种气味源，通过气味物联网接收气味指令，做出相应的气味播放动作，实现定时定点，多模式，高可控的气味体验。气味设备应用广泛，在气味早教、气味电影、虚拟现实、气味广告等方面有着巨大的发展前景。可以说，气味技术扩展了VR，AR，MR等技术，是未来技术的必然趋势。

### 气味物联网提供了哪些服务

物联网技术，融合气味技术，结合高度发展的软硬件技术，产生了气味物联网，加快了虚拟化感官的进程。当前的气味物联网，是一个气味技术的一个初级原型，提供了气味设备的接入、控制、协作，以及相应的数据采集、分析、可视化的功能。

#### 1.  设备接入

不同厂家的设备（气味设备或者其他设备）可通过气味物联网的接入，实现气味设备的自定义控制，以及不同设备和气味设备的协同工作等功能。气味开放平台为厂家可开发者提供了丰富的接入 SDK，多语言、多环境、简单易用。同时，气味物联网的高性能保证了物联网服务的可靠性

#### 2.  提供云端设备控制

气味开放平台的丰富 SDK 为设备的云控制提供了技术保障，分布式架构保证数据的实时性。

#### 3.  设备鉴权和数据安全

气味物联网结合接入验证、动态秘钥、多级权限、数据加密通道于一身，保护设备和数据安全。





## 名词解释

| 名词                  | 解释                                  |
|:-----------------     |:--------------------------------------| 
| SDK                   | 指气味开发平台接入 SDK               | 
| accessKey             | 用户在气味开放平台注册，申请为开发者后，系统为开发者分配的身份标识            |
| accessSecret          | 开发者授权秘钥，用于开发者身份的认证与授权            |
| MQTT          | MQTT（Message Queuing Telemetry Transport，消息队列遥测传输）是 IBM 开发的一个即时通讯协议，有可能成为物联网的重要组成部分。该协议支持所有平台，几乎可以把所有联网物品和外部连接起来，被用来当做传感器和致动器（比如通过 Twitter 让房屋联网）的通信协议。            |
| TOTP          | TOTP（Time-based One-time Password Algorithm）算法，是在 HOTP（HMAC-based One Time Password algorithm）算法的基础上加入时间因子而形成的一种算法|
| Protocol Buffer | Protocol Buffer （以下简称 PB）是 Google 的一种数据交换的格式，它独立于语言，独立于平台。Google 提供了多种语言的实现：Java、C#、C++、Go 和 Python，每一种实现都包含了相应语言的编译器以及库文件。由于它是一种二进制的格式，比使用 XML 进行数据交换快许多。可以把它用于分布式应用之间的数据通信或者异构环境下的数据交换。作为一种效率和兼容性都很优秀的二进制数据传输格式，可以用于诸如网络传输、配置文件、数据存储等诸多领域。|

