<?php
namespace package;

use library\Middleware\Handle;

class Helper
{
    public static function middleware(...$middle)
    {
        if (!$middle) return;
        $m = new Handle();
        foreach ($middle as $mid) {
            if (is_object($mid) && get_parent_class($mid) == 'library\Middleware\Interfacer') {
                $m->addMiddleware($mid);
            }
        }
        $m->handle();
    }

    /**
     * 检查数据深度
     * @param $array
     * @param $limit
     */
    public static function CheckPool(&$array,$limit = 10)
    {
        if (count($array) > $limit) {
            array_shift($array);
        }
    }

    /**
     * 功能：二维数组diff
     * 说明：找出数组$arr1中存在的value值，
     *	  	  $arr2不存在的值value
     * 举例：$arr1=['a'=>1,'b'=>2];$arr2=['c'=>2,'d'=>4];
     * 输出：['a'=>1];
     * @param $arr1
     * @param $arr2
     * @return array
     */
    public static function deepDiffArray($arr1, $arr2)
    {
        try {
            return array_filter($arr1, function ($v) use ($arr2) {
                return !in_array($v, $arr2);
            });
        } catch (\Exception $exception) {
            return $arr1;
        }
    }

    /**
     * 功能：二维数组多字段排序
     * 说明：可以根据字段不同维度给数组排序
     * 举例：dataSortByMoreField($arr,'k1',SORT_DESC,'k2','SORT_ASC')
     * @return bool|mixed
     */
    public static function dataSortByMoreField()
    {
        $param = func_get_args();
        if (empty($param)) {
            return false;
        }
        $data = array_shift($param);
        if (!is_array($data)) {
            return false;
        }
        foreach ($param as $key=>$field) {
            if (is_string($field)) {
                $tmp = [];
                foreach ($data as $idx=>$row) {
                    $tmp[$idx] = $row[$field];
                }
                $param[$key] = $tmp;
            }
        }
        $param[] = &$data;
        @call_user_func_array('array_multisort', $param);
        return array_pop($param);
    }

    /**
     * 功能：递归创建文件夹
     * 说明：此方法跟命令 mkdir -p $dir效果相同，
     *		 在上级目录不存在时自动创建上级目录
     * 举例：$dir='/var/local/tang/zhi/qin'
     * @param $dir
     */
    public static function createDir($dir)
    {
        $dirname = dirname($dir);
        //判断上一个文件夹是否存在
        if (!file_exists($dirname)) {
            self::createDir($dirname);
        }
        mkdir($dir, 0777);
    }

    /**
     * 功能：倒计时
     * 说明：$begin_time开始时间，$end_time,结束时间
     * 举例：输入$begin_time=time();$end_time="2019-05-01";
     * @param $begin_time
     * @param $end_time
     * @return array
     */
    public static function timeDiff( $begin_time, $end_time )
    {
        if ( $begin_time < $end_time ) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        $timediff = $endtime - $starttime;
        $days = intval( $timediff / 86400 );
        $remain = $timediff % 86400;
        $hours = intval( $remain / 3600 );
        $remain = $remain % 3600;
        $mins = intval( $remain / 60 );
        $secs = $remain % 60;
        $res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
        return $res;
    }

    /**
     * 功能：单位换算
     * 说明：$num输入数字，$unit输入的单位(默认为空)，
     *	 	 $point保留小数(默认两位)
     * 举例：输入:$num=10000;$unit='';$point=3;
     *		 输出:['value'=>1.000,'unit'=>'万']
     * @param $num
     * @param string $unit
     * @param int $point
     * @return array|bool
     */
    public static function unitConvert($num, $unit='', $point = 2)
    {
        if (!$num) {
            return false;
        }
        $neg = $num < 0?true:false;
        if ($neg) {
            $num = $num*pow(-1, 1);
        }
        if (empty($unit) || $unit == '万' || $unit == '亿' || $unit == '万亿') {
            $tmp = explode('.', $num);
            while(strlen(array_shift($tmp))>4) {
                $num = $num/10000;
                $tmp = explode('.', $num);
                if (empty($unit)) {
                    $unit = '万';
                } elseif ($unit == '万') {
                    $unit = '亿';
                } elseif ($unit == '亿') {
                    $unit = '万亿';
                } elseif ($unit == '万亿') {
                    $unit = '万兆';
                } else {
                    $num = $num*10000;
                    break;
                }
            }
            $num = sprintf('%01.'.$point.'f', $num);
        } else {
            $num = sprintf('%01.'.$point.'f', $num);
        }
        return array(
            'value'=> $neg?$num*pow(-1, 1):$num,
            'unit' => $unit
        );
    }
    /**
     * 功能：快速排序
     * 说明：$arr数字数组，输出结果顺序为ASC
     * 举例：输入:[3,2,7,9,1]
     *		 输出:[1,2,3,7,9]
     * @param $arr
     * @return array|bool
     */
    public static function quickSort($arr)
    {
        if(!is_array($arr)) {
            return false;
        }

        $len = count($arr);
        if ( $len<= 1) {
            return $arr;
        }
        //选取分界点值
        $middle = $arr[0];
        //接受左右值
        $left=$right = array();
        // 循环比较
        for ($i=1; $i < $len; $i++) {
            if ($middle < $arr[$i]) {
                // 大于分界点值
                $right[] = $arr[$i];
            } else {
                // 小于分界点值
                $left[] = $arr[$i];
            }
        }
        // 递归排序划分好的2边
        $left = self::quickSort($left);
        $right = self::quickSort($right);

        // 合并排序后的数据
        return array_merge($left, [$middle], $right);
    }
    /**
     *获取真实IP
     */
    public static function getRealIp()
    {
        static $realIp;
        if(isset($_SERVER)){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $realIp=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }else if(isset($_SERVER['HTTP_CLIENT_IP'])){
                $realIp=$_SERVER['HTTP_CLIENT_IP'];
            }else{
                $realIp=$_SERVER['REMOTE_ADDR'];
            }
        }else{
            if(getenv('HTTP_X_FORWARDED_FOR')){
                $realIp=getenv('HTTP_X_FORWARDED_FOR');
            }else if(getenv('HTTP_CLIENT_IP')){
                $realIp=getenv('HTTP_CLIENT_IP');
            }else{
                $realIp=getenv('REMOTE_ADDR');
            }
        }
        return $realIp;
    }

    /**
     * 伪造ip
     * @return string
     */
    public static function getRandIp()
    {
        $arr = range(1, 255);
        $randArr = array_rand($arr, 4);
        shuffle($randArr);
        return $randArr[0].'.'.$randArr[1].'.'.$randArr[2].'.'.$randArr[3];
    }
    /**
     *获取设备
     */
    public static function getClientDevice()
    {
        //获取USER AGENT
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        //分析数据
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;

        //输出数据
        if($is_pc){
            return 'pc';
        }
        if($is_iphone){
            return 'iPhone';
        }
        if($is_ipad){
            return "iPad";
        }
        if($is_android){
            return "Android";
        }
        return "No Agent";
    }

    /**
     * 功能：curl_get_post请求
     * 说明：$url为链接，$post为字符串(例:"a=22&b=44")
     * @param $url
     * @param string $post
     * @param $ip
     * @return bool|string
     */
    public static function curlGet($url, $ip='', $post='')
    {
        $header = [
            'Accept: application/json, text/javascript, */*; q=0.01',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: zh-CN,zh;q=0.9,zh-TW;q=0.8,en;q=0.7',
            'Connection: keep-alive',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)".
                        " Chrome/70.0.3538.102 Safari/537.36',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        if ($ip) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($header,['X-FORWARDED-FOR:'.$ip,'CLIENT-IP:'.$ip]));
        }
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode($post));
        }
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    /**
     * 功能：保持长连接post
     * 说明：$url为链接，$param为key-value数组
     * @param $url
     * @param $param
     * @return mixed
     */
    public static function curlPost($url, $param)
    {
        $post = array();
        if ($param) {
            foreach($param as $k=>$v) {
                if ($v != '') {
                    $post[] = urlencode($k).'='.urlencode($v);
                }
            }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $header = array(
            'Connection:Keep-Alive'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $post));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = unserialize(curl_exec($ch));
        curl_close($ch);
        return $result;
    }

    /**
     * 功能：读取配置文件，以数组形式输出
     * @param $filename
     * @return array|bool
     */
    public static function getIniConfig($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }
        $result = parse_ini_file($filename, true);
        return $result;
    }
    /**
     * 功能：获取目录下文件列表
     * 说明：$type=all/linux,all所有系统可用
     * @param $path
     * @param $type
     * @return array|bool
     */
    public static function getFileList($path, $type='all')
    {
        if (!is_dir($path)) {
            return false;
        }
        $dir = array();
        if ($type == 'all') {
            if ($handle = opendir($path)) {
                while ($file = readdir($handle)) {
                    if ($file != '.' && $file != '..') {
                        $dir[] = $file;
                    }
                }
            }
        }
        if ($type == 'linux') {
            ob_start();
            system("ls -m " . $path);
            $dir = explode(",", preg_replace("/\s*(.*?)\s*,/", "$1,", ob_get_contents() . ','));
            array_pop($dir);
            ob_end_clean();
        }
        return $dir;
    }
    /**
     * 功能：多进程回调执行函数
     * 说明：$p是进程数，$func 传入的回调函数；
     * @param $func
     * @param $p
     */
    public static function multiProcess($func, $p = 4)
    {
        if (gettype($func) !== "object") {
            die("it's not a function!");
        }
        //默认创建4个进程
        for ($i=0;$i<$p;$i++) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                die("could not fork");
            } elseif ($pid) {
                pcntl_waitpid($pid, $status);
            } else {
                //需要执行的任务
                call_user_func($func);
                exit(0);
            }
        }
        unset($pid);
        $pid = NULL;
        unset($p);
        $p = NULL;
    }
    /**
     * 功能：十六进制颜色转RGB
     * 举例：$c = "#FF00D4"
     * @param $c
     * @return string
     */
    public static function hex2color($c){
        $len = strlen($c);
        if(!in_array($len,[4,7])) {
            return false;
        }
        $color = str_replace('#', '', $c);
        if ($len == 4) {
            $r = hexdec(substr($color,0,1).substr($color,0,1));
            $g = hexdec(substr($color,1,1).substr($color,1,1));
            $b = hexdec(substr($color,2,1).substr($color,2,1));
            return "R:".$r." G:".$g." B:".$b;
        }
        $r = hexdec(substr($color,0,2));
        $g = hexdec(substr($color,2,2));
        $b = hexdec(substr($color,4,2));
        return "R:".$r." G:".$g." B:".$b;
    }
    /**
     * 功能：格式化文件单位
     * @param $file
     * @return string
     */
    public static function getFileUnit($file){
        if (!file_exists($file)) {
            return false;
        }
        $f_b = filesize($file);
        //$f_b = '106020400';
        $tmp = explode('.', $f_b);
        $unit = 'b';
        while(strlen(array_shift($tmp))>3) {
            $f_b = $f_b/1024;
            $tmp = explode('.', $f_b);
            if ($unit=='b') {
                $unit = 'kb';
            } elseif ($unit == 'kb') {
                $unit = 'm';
            } elseif ($unit == 'm') {
                $unit = 'gb';
            } else {
                $f_b = $f_b*1024;
                break;
            }
        }
        return $f_b.$unit;
    }
}