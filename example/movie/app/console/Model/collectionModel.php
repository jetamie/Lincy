<?php
namespace app\console\Model;

use library\Core\Model;
use library\Orm\Factory;
use package\Config;
use package\Helper;

/**
 * 单进程任务
 * Class collectionModel
 * @package app\console\Model
 */
class collectionModel extends Model
{
    /**
     * 缓存数据
     */
    const DETAIL_LIST_KEY_PRE = 'ok_zy_movie_list_';

    /**
     * 类型列表
     */
    const TYPE_LIST_KEY_PRE = 'ok_zy_type_list';

    /**
     *每个类型页码
     */
    const TYPE_LIST_PAGE_KEY_PRE = 'ok_zy_type_page_';

    /**
     *检索key
     */
    const SEARCH_LIST_KEY = 'ok_zy_search_list';
    /**
     * 脚本入口
     * @param $param
     */
   public function run($param)
   {
       $conf = $this->initConfig();
       $typeList = $this->getTypeList($conf['url_list']);
       foreach ($typeList as $item) {
           if (!isset($param['type'])) break;
           //爬取全部
           if ($param['type'] == 'all') {
               $this->makeDetailList($conf['url_detail'], $item['type_id']);
           }
           //爬取指定类型
           if ($param['type'] != 'all' && $param['type'] == $item['type_id']) {
               $this->makeDetailList($conf['url_detail'], $item['type_id']);
           }
       }
   }

    /**
     * 更新关键词查询
     * @param $param
     */
   public function updateWd($param)
   {
       $conf = $this->initConfig();
       $typeList = $this->getTypeList($conf['url_list']);
       $redis = $this->initRedis();
       foreach ($typeList as $item) {
           $type = $item['type_id'];
           $key = self::DETAIL_LIST_KEY_PRE.$type;
           $tempData = $redis->hGetAll($key);
           foreach ($tempData as $id=>$row) {
               $tmp = unserialize($row);
               $this->echoLog($tmp['name'].'_'.$type.'_'.$tmp['id']);
               $redis->hSet(self::SEARCH_LIST_KEY, $tmp['name'], $type.'_'.$tmp['id']);
           }
       }
   }
    /**
     * 获取详细信息
     * @param $url
     * @param string $type
     */
   private function makeDetailList($url, $type= '9')
   {
       $url .= '&t='.$type;
       //缓存列表
       $key = self::TYPE_LIST_PAGE_KEY_PRE.$type;
       $data = unserialize($this->initRedis()->get($key));
       if (!$data) {
           $res = file_get_contents($url);
           $data = @json_decode($res, true);
           $this->initRedis()->set($key, serialize($data), 3600*24);
       }
       $page = $data['page'];
       $pageCount = $data['pagecount'];
       //开始抓取数据，间隔200ms
       for ($i = $page;$i <= $pageCount; $i++) {
           $detailUrl = $url.'&pg='.$i;
           $this->echoLog('start request:'.$detailUrl);
           $res = file_get_contents($detailUrl);
           $tmpList = @json_decode($res, true);
           if (!isset($tmpList['list'])) continue;
           foreach ($tmpList['list'] as $item) {
               $playList = explode($item['vod_play_note'], $item['vod_play_url']);
               //过滤无用资源
               if (empty($playList)) continue;
               $playList = array_map(
                   function ($item) {
                       $tmp = explode('$', $item);
                       if (isset($tmp[1])) {
                           return $tmp[1];
                       }
                   },
                   $playList
               );
               $downloadUrl = explode('$', $item['vod_down_url']);
               $temp = [
                    'id' => $item['vod_id'],
                    'png' => $item['vod_pic'],
                    'name' => $item['vod_name'],
                    'time' => $item['vod_time'],
                    'play_url' => $playList,
                    'download_url' => isset($downloadUrl[1])?$downloadUrl[1]:'',
               ];
               //存储数据
               $this->saveData($temp, $type);
           }
           usleep(200000);
       }
   }

    /**
     * 获取影视列表
     * @param $url
     * @return array
     */
   private function getTypeList($url)
   {
       $res = Helper::curlGet($url);
       $list = @json_decode($res, true);
       $key = self::TYPE_LIST_KEY_PRE;
       $data = $this->initRedis()->get($key);
       if (empty($data)) {
           //得到列表
           if (isset($list['class'])) {
               $data = serialize($list['class']);
               $this->initRedis()->set($key, $data, 3600 * 24);
           }
       }
       return unserialize($data);
   }

    /**
     * 存储数据
     * @param $item
     * @param $type
     */
   private function saveData($item, $type)
   {
       $this->echoLog(json_encode($item));
       $key = self::DETAIL_LIST_KEY_PRE.$type;
       $isExists = $this->initRedis()->hExists($key, $item['id']);
       if (!$isExists) {
           $this->initRedis()->hSet($key, $item['id'], serialize($item));
       }
   }
   /**
    * 获取配置
    */

   private function initConfig()
   {
       return Config::get('collection.ok_zy');
   }

    /**
     * @return \Redis
     */
   private function initRedis()
   {
       return Factory::getRedisInstance();
   }

    /**
     * 打印消息
     * @param $msg
     */
   private function echoLog($msg)
   {
       echo $msg."\r\n";
   }
}