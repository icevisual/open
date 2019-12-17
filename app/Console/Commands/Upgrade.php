<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class Upgrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'upgrade project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sqls =<<<EOL
        
DROP TABLE IF EXISTS `op_developer_device_blind`;
CREATE TABLE `op_developer_device_blind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `developer_id` int(11) NOT NULL COMMENT '开发者ID',
  `device_access_key` varchar(100) NOT NULL COMMENT '设备access_key',
  `bind_at` timestamp NULL DEFAULT NULL COMMENT '绑定时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='开发者和设备绑定表';

ALTER TABLE op_request_log ADD COLUMN `http_status` SMALLINT(5) DEFAULT NULL COMMENT 'HTTP 状态码' AFTER params;

EOL;
        \DB::update($sqls);
        
        /**
         * 
         * show create table 
         * 
         * check structure
         * 
         * create table
         * alter table add column
         * alter table modify 
         * alter table drop 
         * 
         * data process
         */
        $this->comment('--END--');
    }
    
    
    
    
    
    
    
    
    
}
