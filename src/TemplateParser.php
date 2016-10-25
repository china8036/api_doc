<?php

/**
 * @copyright (c) 2016, ryan [CHAOMA.ME]
 * 文档注释分析提取
 */

namespace Ryanc\ApiDoc;

class TemplateParser
{

    /**
     * 
     * @param array $data
     * @param type $template
     * @return type
     */
    public static function getHtml(array $data, $template)
    {
        $file_path = __DIR__ . "/template/{$template}.html";
        ob_start();
        ob_implicit_flush(0);
        extract($data);
        include $file_path;
        $content = ob_get_clean();
        return $content;
    }

    /**
     * 获取vo解析
     * @param array $data
     * @return type
     */
    public static function getVoHtml(array $data)
    {
        return self::getHtml($data, 'vo');
    }

    /**
     * 获取api解析
     * @param array $data
     * @return type
     */
    public static function getApiHtml(array $data)
    {
        return self::getHtml($data, 'api');
    }

    /**
     * 获取首页解析
     * @param array $nav
     * @return type
     */
    public static function getNavHtml(array $nav)
    {
        $xnav = self::formatNav($nav);
        $pnav = self::reParseNav($xnav);
        return self::getHtml(['nav' => $pnav], 'nav');
    }

    /**
     * 格式化为目录结构
     * @param array $nav
     * @return array
     */
    public static function formatNav(array $nav)
    {
        $format_container = [];
        foreach ($nav as $api => $link) {
            $key = '[\'' . str_replace('.', '\'][\'', $api) . '\']';
            eval('$format_container' . $key . '=$link;');
        }
        return $format_container;
    }

    /**
     * 解析格式
     * @param array $nav
     */
    public static function reParseNav(array $nav)
    {
        foreach ($nav as $subkey => $subdata) {
            if (is_array($subdata)) {
                $str .= '<div class="treeNode"> <img src="./static/img/right.gif" onclick="expandCollapse(this.parentNode)" class="treeLinkImage"><span onclick="expandCollapse(this.parentNode)" class="category">' . $subkey . '</span>
              <div class="treeSubnodesHidden">';
                $str .= self::reParseNav($subdata);
                $str .= "</div></div>";
            } else {
                $str .= "<div class='treeNode'><a href='{$subdata}' target='main' class='treeUnselected' onclick='clickAnchor(this)'><img src='./static/img/right.gif'>{$subkey}</a></div>";
            }
        }
        return $str;
    }

    /**
     * 组建首页
     * @param array $data
     * @param type $path
     * @param \Ryanc\ApiDoc\callable $writefile
     */
    public static function buildIndexHtml(array $data, $path, callable $writefile)
    {
        $index_html = self::getHtml($data, 'index');
        $main_html = self::getHtml($data, 'main');
        $header_html = self::getHtml($data, 'header');
        $writefile($path . '/index.html', $index_html);
        $writefile($path . '/main.html', $main_html);
        $writefile($path . '/header.html', $header_html);
    }

}
