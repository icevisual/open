<?php

namespace App\Extensions\Verify;

/**
 * 验证码
 *
 * @author king
 */
class XmasCaptcha
{

    /**
     * 传递参数的标签
     *
     * @var string
     */
    protected $tag = 'xmascaptcha';

    /**
     * 验证码图片宽度
     *
     * @var int
     */
    public $width = 100;

    /**
     * 验证码图片宽度
     *
     * @var int
     */
    public $height = 32;

    /**
     * 验证码前景色(字符) RGB 16位表示
     *
     * @var string
     */
    public $foregroundColor = '#000000';

    /**
     * 验证码背景色 RGB 16位表示
     *
     * @var string
     */
    public $backgroundColor = '#FFFFFF';

    /**
     * 字体大小
     *
     * @var float
     */
    public $fontSize = 16;

    /**
     * 字符长度
     *
     * @var int
     */
    public $charSize = 5;

    /**
     * 校验码过期时间, 单位:秒
     *
     * @var int
     */
    public $expire = 1800;

    public function __construct(array $config = [])
    {
        if ($config) {
            $this->parseConfig($config);
        }
    }

    public function session($key = '', $value = '')
    {
        if ($key) {
            if ('' != $value || is_null($value)) {
                return session([
                    $key => $value
                ]);
            } else {
                return session($key);
            }
        }
        return session();
    }

    public function parseConfig($config)
    {
        if (!empty($config)) {
            foreach ($config as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    /**
     * 解析RGB颜色
     *
     * @param string $color
     * @return array
     */
    private function parseRGB($color)
    {
        if (strlen($color) < 7) {
            $color = '#FFFFFF';
        }
        $r = hexdec($color[1] . $color[2]);
        $g = hexdec($color[3] . $color[4]);
        $b = hexdec($color[5] . $color[6]);
        return array(
            $r,
            $g,
            $b
        );
    }

    /**
     * 生成问题图片
     *
     * @param string $data
     *            问题内容
     *            $return void
     */
    private function generateImage($data)
    {
        $width = $this->width;
        $height = $this->height;
        $len = strlen($data);
        // 文字宽度 $len*9+20
        $im = imageCreateTrueColor($width, $height);
        // 字体颜色
        list ($r, $g, $b) = $this->parseRGB($this->foregroundColor);
        $foregroundColor = imageColorAllocate($im, $r, $g, $b);
        list ($r, $g, $b) = $this->parseRGB($this->backgroundColor);
        $background = imageColorAllocate($im, $r, $g, $b);
        // 背景填充
        imageFill($im, 0, 0, $background);
        // 文字填充
        $charWidth = ($this->fontSize + 2) * $this->charSize;
        if ($charWidth < $this->width) {
            // 文字的随机角度
            $angle = rand(-5, 5);
            $sin = sin(deg2rad($angle));
            // 计算偏移量避免字符超出图片
            $xOffset = floor($this->width - $charWidth * (1 + abs($sin)));
            $yOffset = floor($this->height - $this->fontSize * 2 * (1 + abs($sin))) + $this->fontSize + 2;
            if ($yOffset < $this->fontSize + 2) {
                $yOffset = $this->fontSize + 2;
            }
        } else {
            $angle = 0;
            $xOffset = 0;
            $yOffset = $this->fontSize + 2;
        }


        imagettftext($im, $this->fontSize, $angle, $xOffset, $yOffset, $foregroundColor, __DIR__ . '/fonts/1.ttf', $data);


        $counter = 4;
        // 生成随机颜色
        while ($counter > 0) {
            $rColor = rand(0, 255);
            $gColor = rand(0, 255);
            $bColor = rand(0, 255);
            $bColor = 0;
            $sixColors[] = imageColorAllocate($im, $rColor, $gColor, $bColor);
            $counter--;
        }
        while ($counter < 4) {
            $rArc = rand(2, 4);
            $xArc = rand(5, $width - 5);
            $yArc = rand(5, $height - 5);
            // 随机点生成
            imageArc($im, $xArc, $yArc, $rArc, $rArc, 0, 360, $sixColors[$counter % 8]);
            // 随机线条
            if (($counter + 1) % 3 === 0) {
                $lineXStart = rand(1, $width);
                $lineXEnd = rand(1, $width);
                $lineYStart = rand(10, $height);
                $lineYEnd = rand(10, $height);
                imageline($im, $lineXStart, $lineYStart, $lineXEnd, $lineYEnd, $sixColors[$counter % 8]);
            }
            $counter++;
        }
        header('Content-type: image/png');
        imagePng($im);

        imageDestroy($im);
    }

    /**
     * 保存数据
     *
     * @param string $data
     * @return void
     */
    private function save($data, $id)
    {
        $key = $this->authcode($this->tag) . $id;
        // 验证码不能为空
        $data = $this->authcode(strtoupper($data));
        $secode = array();
        $secode['verify_code'] = $data; // 把校验码保存到session
        $secode['verify_time'] = time(); // 验证码创建时间
        $this->session($key, $secode);
    }

    /**
     * 生成问题
     *
     * @return void
     */
    public function entry($id = '')
    {
        $data = array_merge(range(1, 9), range('A', 'Z'), range('a', 'z'));
        $data = array_rand(array_flip($data), $this->charSize);
        $data = implode('', $data);

        if (\App::environment('local', 'testing')) {
            \Com::debug('captche', [$data, \Session::getId()]);
            $open = new \App\Services\Open\OpenServices();
            \LRedis::SETEX($open->getCacheKey(\Session::getId(), \App\Services\Open\OpenServices::KEY_REGISTER_CAPTACH), 120, $data);
        }

        $this->save(strtoupper($data), $id);
        $this->generateImage($data);
    }


    public static function checkCaptcha($code, $id = '')
    {
        static $_instance = null;
        if (!$_instance) {
            $_instance = new static;
        }
        return $_instance->check($code, $id);
    }

    /**
     * 验证验证码是否正确
     *
     * @access public
     * @param string $code
     *            用户验证码
     * @param string $id
     *            验证码标识
     * @return bool 用户验证码是否正确
     */
    public function check($code, $id = '')
    {
        $key = $this->authcode($this->tag) . $id;
        // 验证码不能为空
        $secode = $this->session($key);
        if (empty($code) || empty($secode)) {
            return false;
        }
        // session 过期
        if (time() - $secode['verify_time'] > $this->expire) {
            $this->session($key, null);
            return false;
        }

        if ($this->authcode(strtoupper($code)) == $secode['verify_code']) {
            $this->session($key, null);
            return true;
        }
        $this->session($key, null);
        return false;
    }

    /* 加密验证码 */
    private function authcode($str)
    {
        $key = substr(md5($this->tag), 5, 8);
        $str = substr(md5($str), 8, 10);
        return md5($key . $str);
    }
}
