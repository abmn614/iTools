<?php 

/**
 * 图片处理类
 */

Class Image
{
    // 图片信息数组
    private $info;
    private $tmp_img;

    /**
     * 构造函数
     * @param string $src 源文件名
     */
    function __construct($src){
        $info = getimagesize($src);
        $this->info = array(
            'width'     => $info[0],
            'height'    => $info[1],
            'ext'       => image_type_to_extension($info[2], false),
            'mime'      => $info['mime'],
            );
        /*打开*/
            // 加载图片到内存
            $tmp = "imagecreatefrom{$this->info['ext']}";
            $this->tmp_img = $tmp($src);
    }

    /**
     * 图片按比例缩放
     * @param  string $option   指定条件，按宽 or 高等比例缩放
     * @param  int $max         最大值，宽 or 高
     * @return object           返回对象
     */
    function thumb($option, $max)
    {
    /*操作*/
        // 等比例计算
        switch ($option) {
            case 'width':
                $this->width = $max;
                $this->height = $this->info['height'] * ($this->width / $this->info['width']);
                break;
            case 'height':
                $this->height = $max;
                $this->width = $this->info['width'] * ($this->height / $this->info['height']);
                break;
        }
        // 创建缩略图
        $thumb_img = imagecreatetruecolor($this->width, $this->height);
        // 将内存中的图copy到缩略图
        imagecopyresampled($thumb_img, $this->tmp_img, 0, 0, 0, 0, $this->width, $this->height, $this->info['width'], $this->info['height']);

        // 销毁临时文件
        imagedestroy($this->tmp_img);
        // 将缩略图加载到临时文件，以供调用
        $this->tmp_img = $thumb_img;

        // 返回对象，方便链式操作
        return $this;
    }

    /**
     * 字体水印
     * @param  string $position 水印的位置
     * @param  int $size        字体大小，建议用等宽字体
     * @param  int $angle       倾斜角度
     * @param  string $color    颜色
     * @param  int $alpha       透明度
     * @param  string $fontsrc  字体路径
     * @param  string $text     水印文字内容
     * @return object           返回对象
     */
    function fontmark($position, $size, $angle, $color, $alpha, $fontsrc, $text){
        // 获取文本范围
        $ttfbox = imagettfbbox($size, $angle, $fontsrc, $text);
        // 计算5个位置的坐标
        $width = $ttfbox[2]; // 文本宽度
        $height = $ttfbox[1] + $size; // 文本高度
        // 调用函数获取坐标系
        $xy = $this->getPosition($position, $width, $height);
        // 将文本加入图片
        imagettftext($this->tmp_img, $size, $angle, $xy['x'], $xy['y'], $this->getColor($color, $alpha), $fontsrc, $text);
        
        // 返回对象，方便链式操作
        return $this;
    }

    /**
     * 封装 - 获取水印的5个常用坐标
     * @param  string $position 水印位置
     * @param  int $width       文字 or 图片的宽度
     * @param  int $height      文字 or 图片的高度
     * @return array            坐标系数组
     */
    function getPosition($position, $width, $height){
        // 计算五个坐标
        switch ($position) {
            case 'top-left':
                $x = 10;
                $y = $height + 10;
                return array('x' => $x, 'y' => $y);
                break;
            case 'top-right':
                $x = $this->width - $width - 10;
                $y = $height + 10;
                return array('x' => $x, 'y' => $y);
                break;
            case 'bottom-left':
                $x = 10;
                $y = $this->height - $height;
                return array('x' => $x, 'y' => $y);
                break;
            case 'bottom-right':
                $x = $this->width - $width - 10;
                $y = $this->height - $height - 10;
                return array('x' => $x, 'y' => $y);
                break;
            case 'center':
                $x = $this->width / 2 - $width / 2;
                $y = $this->height / 2 - $height / 2;
                return array('x' => $x, 'y' => $y);
                break;
        }
    }

    /**
     * 封装 - 取字体颜色
     * @param  string $color    颜色
     * @param  int $alpha       透明度
     * @return 返回色值
     */
    function getColor($color, $alpha){
        switch ($color) {
            case 'white':
                return imagecolorallocatealpha($this->tmp_img, 255, 255, 255, $alpha);
                break;
            case 'black':
                return imagecolorallocatealpha($this->tmp_img, 0, 0, 0, $alpha);
                break;
            case 'red':
                return imagecolorallocatealpha($this->tmp_img, 255, 0, 0, $alpha);
                break;
            case 'grey':
                return imagecolorallocatealpha($this->tmp_img, 128, 128, 128, $alpha);
                break;
            default:
                return imagecolorallocatealpha($this->tmp_img, 255, 255, 255, $alpha);
                break;

        }
    }

    /**
     * 图片水印
     * @param  string $position     水印位置
     * @param  string $mark_img_src 水印路径
     * @param  int $alpha           水印透明度
     * @return 返回对象
     */
    function imagemark($position, $mark_img_src, $alpha){
        // 将水印图片加载到内存
        $mark_img_info = getimagesize($mark_img_src);
        $mark_img_type = image_type_to_extension($mark_img_info[2], false);
        $tmp_img = "imagecreatefrom{$mark_img_type}";
        $mark_tmp_img = $tmp_img($mark_img_src);
        // 将水印图片加入主图片
        $width = $mark_img_info[0];
        $height = $mark_img_info[1];
        $xy = $this->getPosition($position, $width, $height);
        imagecopymerge($this->tmp_img, $mark_tmp_img, $xy['x'], $xy['y'], 0, 0, $mark_img_info[0], $mark_img_info[1], $alpha);

        // 销毁水印图片
        imagedestroy($mark_tmp_img);

        return $this;
    }


    /**
     * 输出
     * @param  string $option 输出方式，show为输出到浏览器，save为保存到文件
     * @param  string $newsrc 保存的路径
     */
    function output($option, $newsrc = ''){
        switch ($option) {
            // 输出到浏览器
            case 'show':
                header("content-type:" . $this->info['mime']);
                $tmp_output = "image{$this->info['ext']}";
                $tmp_output($this->tmp_img);
                break;
            // 保存到文件
            case 'save':
                $tmp_output = "image{$this->info['ext']}";
                $tmp_output($this->tmp_img, $newsrc . '.' . $this->info['ext']);
                break;
        }
    }

    /**
     * 析构函数，销毁临时图片
     */
    function __destruct(){
        imagedestroy($this->tmp_img);
    }

}