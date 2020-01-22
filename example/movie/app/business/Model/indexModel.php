<?php
namespace app\business\Model;

use library\Core\Model;
use library\Orm\Factory;

class indexModel extends Model
{
    /**
     * 缓存数据
     */
    const DETAIL_LIST_KEY_PRE = 'ok_zy_movie_list_';

    /**
     *检索key
     */
    const SEARCH_LIST_KEY = 'ok_zy_search_list';

    /**
     * 获取数据
     * @param $param
     * @return array
     */
    public function getDetailList($param)
    {
        $type = isset($param['type'])?$param['type']:9;
        $page = isset($param['page'])?$param['page']:0;
        $limit = 20;
        $key = self::DETAIL_LIST_KEY_PRE.$type;
        $keys = $this->initRedis()->hKeys($key);
        $pageList = array_chunk($keys, $limit);
        if (isset($pageList[$page])) {
            $data = $this->initRedis()->hMGet($key, $pageList[$page]);
            if (!empty($data)) {
                $list = [];
                foreach ($data as $item) {
                    $list[] = unserialize($item);
                }
                return [count($pageList),$list];
            }
        }
        return [0,[]];
    }

    /**
     *
     * @param $param
     * @return array
     */
    public function search($param)
    {
        $wd = isset($param['wd'])?$param['wd']:'';
        if (!$wd) return [];
        $redis = $this->initRedis();
        $it = 0;
        $pattern = '*'.$wd.'*';
        $count = 10;
        $data = $redis->hScan(self::SEARCH_LIST_KEY, $it, $pattern, $count);
        var_dump($data);die;
        /*$data = $redis->hGetAll(self::SEARCH_LIST_KEY);
        $list = [];
        foreach ($data as $word=>$item) {
            if (mb_strpos($word, $wd) !== false) {
                $list[$word] = $item;
            }
        }*/
        return [];
    }

    /**
     * @return \Redis
     */
    private function initRedis()
    {
        return Factory::getRedisInstance();
    }
}