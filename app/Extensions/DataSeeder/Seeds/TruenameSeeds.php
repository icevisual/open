<?php
namespace App\Extensions\DataSeeder\Seeds;

use App\Extensions\DataSeeder\SeedsFactory;

class TruenameSeeds implements SeedsFactory
{

    
    public function firstName (){
    
    
        $str = '赵	钱	孙	李	周	吴	郑	王	冯	陈	褚	卫	蒋	沈	韩	杨	朱	秦	尤	许
何	吕	施	张	孔	曹	严	华	金	魏	陶	姜	戚	谢	邹	喻	柏	水	窦	章
云	苏	潘	葛	奚	范	彭	郎	鲁	韦	昌	马	苗	凤	花	方	俞	任	袁	柳
酆	鲍	史	唐	费	廉	岑	薛	雷	贺	倪	汤	滕	殷	罗	毕	郝	邬	安	常
乐	于	时	傅	皮	卞	齐	康	伍	余	元	卜	顾	孟	平	黄	和	穆	萧	尹
姚	邵	湛	汪	祁	毛	禹	狄	米	贝	明	臧	计	伏	成	戴	谈	宋	茅	庞
熊	纪	舒	屈	项	祝	董	粱	杜	阮	蓝	闵	席	季	麻	强	贾	路	娄	危
江	童	颜	郭	梅	盛	林	刁	钟	徐	邱	骆	高	夏	蔡	田	樊	胡	凌	霍
虞	万	支	柯	昝	管	卢	莫	经	房	裘	缪	干	解	应	宗	丁	宣	贲	邓
郁	单	杭	洪	包	诸	左	石	崔	吉	钮	龚	程	嵇	邢	滑	裴	陆	荣	翁
荀	羊	於	惠	甄	麴	家	封	芮	羿	储	靳	汲	邴	糜	松	井	段	富	巫
乌	焦	巴	弓	牧	隗	山	谷	车	侯	宓	蓬	全	郗	班	仰	秋	仲	伊	宫
宁	仇	栾	暴	甘	钭	厉	戎	祖	武	符	刘	景	詹	束	龙	叶	幸	司	韶
郜	黎	蓟	薄	印	宿	白	怀	蒲	邰	从	鄂	索	咸	籍	赖	卓	蔺	屠	蒙
池	乔	阴	欎	胥	能	苍	双	闻	莘	党	翟	谭	贡	劳	逄	姬	申	扶	堵
冉	宰	郦	雍	舄	璩	桑	桂	濮	牛	寿	通	边	扈	燕	冀	郏	浦	尚	农
温	别	庄	晏	柴	瞿	阎	充	慕	连	茹	习	宦	艾	鱼	容	向	古	易	慎
戈	廖	庾	终	暨	居	衡	步	都	耿	满	弘	匡	国	文	寇	广	禄	阙	东
殴	殳	沃	利	蔚	越	夔	隆	师	巩	厍	聂	晁	勾	敖	融	冷	訾	辛	阚
那	简	饶	空	曾	毋	沙	乜	养	鞠	须	丰	巢	关	蒯	相	查	後	荆	红
游	竺	权	逯	盖	益	桓	公	万俟	司马	上官	欧阳	夏侯	诸葛
闻人	东方	赫连	皇甫	尉迟	公羊	澹台	公冶	宗政	濮阳
淳于	单于	太叔	申屠	公孙	仲孙	轩辕	令狐	钟离	宇文
长孙	慕容	鲜于	闾丘	司徒	司空	亓官	司寇	仉	督	子车
颛孙	端木	巫马	公西	漆雕	乐正	壤驷	公良	拓跋	夹谷
宰父	谷梁	晋	楚	闫	法	汝	鄢	涂	钦	段干	百里	东郭	南门
呼延	归	海	羊舌	微生	岳	帅	缑	亢	况	后	有	琴	梁丘	左丘
东门	西门	商	牟	佘	佴	伯	赏	南宫	墨	哈	谯	笪	年	爱	阳	佟
第五	言	福	';
        return preg_split('/\s+/', $str);
    }
    
    /**
     * Generate random chinese name
     *
     * @return string
     */
    public function randomChineseName($n = 3)
    {
        $firstname = $this->firstName();
        $word = $this->chineseWord();
        $count = count($word);
        $str = '';
        while (-- $n) {
            $str .= $word[random_int(0, $count - 1)];
        }
        return $firstname[array_rand($firstname)] . $str;
    }
    
    /**
     * Get chinese word resource
     *
     * @return multitype:string
     */
    public function chineseWord()
    {
        $str = '苗疆素来以蛊毒瘴气闻名多鬼狐精怪之事而其核心地十万大山更是神秘无比人迹罕至处古树高耸老藤如龙岳巍峨河流壮阔一派蛮荒的风貌深座脚下此时阵异歌声飘荡出：“王叫我巡喽完南北吆仿佛踏行道上现了个獐头鼠脑干瘦少年欢快唱着那谣眼珠子滴溜旋转给种极度明感觉令骇然他并非徒步胯有纯黑色皮毛豹副蔫样垂丧驮赶路许这扰兴致止住恶狠俯瞰身你懒散货前方便最后要查寨刻钟看到门否则会禀报想烧烤只幽灵墨趣呢两字似乎某魔力原本进浑忍不颤抖眸顿显惶恐态形纵已化作残影消失在留连串咒骂回虚空畜生慢点家铁柱爷掉足遍布盆内四平八稳端坐整理皱巴衣衫才倨傲喝葛些滚早等候伴随嘎吱打开中鱼贯群为首乃袍肥胖者毫犹豫率领众跪伏讨好九天青羽主拜见使祝敌岁咧嘴笑莫废话月们诸帮提供贡品可曾准备充？切妥当请放小意您务必纳恭敬拿物双手奉睛亮脸露满伸取将入怀安得伙果亏待定面说几句言诚模很激动真太客数直混乱寇飞贼都啊若统实施仁慈政策何能走劳酬没间马屁腿夹再速踪确所尽闭塞始仅活也盘踞强盗却因发改变自幼被养育屠戮带妹侥幸逃凭借与狡诈辣性格女孩加日过刀肉般虽堪称胎食牛穷久展计做成立雄略断扩张杀股匪死五囊知晓奇术妖管什么邪竟奈震撼情鼎凶响彻近千碧祖二暴虐代表瑶善良她劝皆祥和需隔腹片澄净清澈湖泊畔壁株松苍翠条虬绿草茵尊香巨型铜三耳符文膝富正屈指弹缕火焰尖涌汇聚于悸热浪严丝合缝掩盖依旧扑鼻让孔舒通体服远站滔息望威猛霸别赫沉吟摆谱音刚落视跑谄媚潮汹念经传颂功德采对崇江水绵绝穿挪硕骚包抹额滋润分颜悦交办吩咐敢阿谀揖答案错起观烹煮美味吧七炉药金狮期嘿希突破瓶颈饕餮吞噬段引轩波存横法惊沦愿偿口又欲仙爽坏哥应今陪儿去玉菇怎崖就脆寒恨淡紫织锦腰束盈握肢乌秀编俏辫插枚桃花簪雪巧虹鞋皓白腕挂银圈尴尬乖顺像羊缩脖弱根据猜测关重底哼琼饶郁闷差哭哀怨怕宠溺噗嗤轻吐兰啦次算例听痛涕暖鸟由轰隆雷蒸腾霞光瑞彩澎湃紧跟氤氲赤雾冲同黄铸慑鬓耀爆炸冰冷宛祇降怒吼哈象丹袖掀璀璨燃挥抓摄呈终炼制浓汁淌简骨酥麻浩瀚粹量升脱换蜕越诞伦元相己枷锁谁隐晦闪烁陷暗锅倒扣振聋聩霆蜿蜒际电籍记载劫还罚呼瘫软茫渺沧海粟玩';
        $chineseWord = [];
        for ($i = 0; $i < mb_strlen($str); $i ++) {
            $sub = mb_substr($str, $i, 1);
            $chineseWord[] = $sub;
        }
        return $chineseWord;
    }
    
    public function en_familyname(){
        $source = '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';
        $source = gzinflate(base64_decode($source));
        $source = explode('#', $source);
        return $source[array_rand($source)];
    }
    
    public function en_name(){
        $source = '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';
        $source = gzinflate(base64_decode($source));
        $source = explode('#', $source);
        return $source[array_rand($source)];
    }
    
    public function newSeed()
    {
        if(random_int(1, 10) > 6 ){
            return $this->en_name().' '.$this->en_familyname();
        }
        return $this->randomChineseName();
    }

    public function destoryAllSeed(array $seeds)
    {
        
    }
}