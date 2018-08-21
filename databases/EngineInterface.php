<?php
namespace deluxcms\sqldebug\databases;

interface EngineInterface
{
    /**
     * 保存数据
    */
    public function save($routeId, $data);

    /**
     * 监听数据
    */
    public function listen();
}
