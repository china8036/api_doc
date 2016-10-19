<?php

/**
 * @copyright (c) 2016, ryan [CHAOMA.ME]
 * 文档注释分析提取
 */

namespace Ryanc\ApiDoc;

use phpDocumentor\Reflection\DocBlock;

class MethodDocExtractor
{

    /**
     * 传入参数
     */
    const PARAM_TAG = 'param';

    /**
     * 返回
     */
    const RETURN_TAG = 'return';

    /**
     * 获取方法描述
     * @param string $class
     * @param string $method
     * @return string
     */
    public function getDoc($class, $method)
    {
        $rMethod = new \ReflectionMethod($class, $method);
        $phpDoc = new DocBlock($rMethod);
        $data ['description'] = $phpDoc->getShortDescription();
        $params = $phpDoc->getTagsByName(self::PARAM_TAG);
        $data['params'] = $this->formatParams($params);
        $return = $phpDoc->getTagsByName(self::RETURN_TAG);
        $data['return'] = $this->formatReturn($return);
        return $data;
    }

    /**
     * 格式化参数
     * @param array $params
     * @return array
     */
    public function formatParams(array $params)
    {
        $data = [];
        foreach ($params as $tag) {
            $desc = $tag->getDescription();
            $needed = true;
            if(stripos($desc, '[false]')){
                $needed = false;
            }
            $fdesc = trim(str_replace(['[false]', '[true]'], '', $desc));
            $type = $tag->getType();
            $data[] = [
                'type' => $type,
                'name' => $tag->getVariableName(),
                'desc' => $fdesc,
                'is_object' => (strpos($type, '\\') === 0),
                'needed' => $needed
            ];
        }
        return $data;
    }

    /**
     * 格式化返回数据
     * @param array $return
     * @return array
     */
    public function formatReturn(array $return)
    {
        $data = [];
        foreach ($return as $rtags) {
            $data[] = [
                'type' => $rtags->getType(),
                'is_object' => (strpos($rtags->getType(), '\\') === 0),
                'desc' => $rtags->getDescription()
            ];
        }
        return $data;
    }

}
