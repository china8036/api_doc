<?php

/**
 * @copyright (c) 2016, ryan [CHAOMA.ME]
 * 文档注释分析提取
 */

namespace Ryanc\ApiDoc;

use phpDocumentor\Reflection\DocBlock;

class VoDocExtractor
{

    /**
     * 解析一个类的所有公共属性
     * @param class $class
     * @return array
     */
    public function getDoc($class)
    {
        $rf = new \ReflectionClass($class);
        $pp = $rf->getProperties();
        $return = [];
        foreach ($pp as $p) {
            if (!$p->isPublic()) {
                continue;
            }
            $return[] = $this->parseProperties($p);
        }
        return $return;
    }

    /**
     * 解析一个属性
     * @param \ReflectionProperty $rp
     * @return type
     */
    public function parseProperties(\ReflectionProperty $rp)
    {
        $phpDoc = new DocBlock($rp);
        $tag = current($phpDoc->getTagsByName('var'));
        return [
            'desc' => $phpDoc->getShortDescription(),
            'name' => $rp->getName(),
            'type' => $tag->getType(),
        ];
    }

}
