<?php
namespace package;
/**
 * Class CatchWeb
 */
class CatchWeb
{
    /**
     * 股票代码
     * @var string
     */
    private $_code;
    /**
     * @var \DOMComment
     */
    private static $_domObj;

    /**
     * 入口
     * @param $conf
     * @return mixed
     */
    public function start($conf)
    {
        //xpath规则
        $data = $this->XpathData($conf);
        return $data;
    }
    /**
     * Xpath抓取数据
     * @param $conf
     * @return array
     */
    protected function XpathData($conf)
    {
        $url = isset($conf["url"])?$conf["url"]:"";
        if (!$url) {
            exit("url为空！");
        }
        $html = file_get_contents($url);
        $status = current($http_response_header);
        $state = explode(" ", $status);
        if (!in_array($state[1], ["200","301","302"])) {
            $msg = date("Y-m-d H:i:s") . " [Message] curl {$url} failed! [Status] {$status}\n";
            echo $msg;
            return [];
        }
        if (!isset($conf["xpath"]) || !$conf["xpath"]) return ["data"=>$html];
        $domObj = self::getDomDocumentInstance();
        $domObj->loadHTML($html);
        $domObj->normalize();
        $xpathObj = new DOMXPath($domObj);
        if (!$xpathObj || !$url || !$conf) {
            return [];
        }
        //Xpath规则
        $xpath = $conf["xpath"];
        //过滤规则
        $filter = isset($conf["filter"])?$conf["filter"]:"";
        $dataObj = $xpathObj->query($xpath);
        $result = [];
        if ($dataObj) {
            //数据
            foreach ($dataObj as $key => $val) {
                $tmp = self::_clearSpace(trim($val->nodeValue));
                if ($filter) {
                    $tmp = str_replace($filter, "", $tmp);
                }
                if ($tmp) $result[] = $tmp;
            }
        }
        return ["data"=>$result];
    }

    /**
     * 单例模式
     * @return DOMDocument
     */
    public static function getDomDocumentInstance()
    {
        if (!self::$_domObj) {
            self::$_domObj = new DOMDocument();
        }
        return self::$_domObj;
    }

    /**
     * 清除空格
     * @param $string
     * @return string
     */
    private static function _clearSpace($string)
    {
        $string = strip_tags($string);
        $string = preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/", "", $string);
        return trim($string);
    }
}