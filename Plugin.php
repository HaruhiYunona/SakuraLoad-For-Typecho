<?php

/**
 * 一个简洁好用的页面加载条插件,允许自定义文字和图片等功能
 * 
 * @package SakuraLoad
 * @author HaruhiYunona
 * @version 1.0.0
 * @link https://mdzz.pro
 */
class SakuraLoad_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->footer = array('SakuraLoad_Plugin', 'footer');
        Typecho_Plugin::factory('Widget_Archive')->header = array('SakuraLoad_Plugin', 'header');
        return '启用成功！请前往管理面板设置,否则该插件将以默认配置运行';
    }
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        return '禁用成功！插件已经停用。遇到问题了?去作者博客https://mdzz.pro看看吧!';
    }

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        //jQuery支持
        $jqsupport = new Typecho_Widget_Helper_Form_Element_Radio(
            'jqsupport',
            array(
                'on' => _t('开启jQuery支持'),
                'off' => _t('关闭jQuery支持'),
            ),
            'off',
            _t('jQuery支持(插件运行需要)'),
            _t('请确认您已经在模板的header.php文件手动插入了jQurey，否则请您打开jQurey支持。插件自带的jQuery版本为3.6.0，CDN服务依托于猫云。')
        );
        $form->addInput($jqsupport);
        //加载条内容
        $content = new Typecho_Widget_Helper_Form_Element_Text('content', null, '加载中...', _t('加载内容'), '加载框的提示内容');
        $form->addInput($content);
        //加载条类型
        $type = new Typecho_Widget_Helper_Form_Element_Radio(
            'type',
            array(
                'default' => _t('默认(小圆圈)'),
                'picture' => _t('图片/动图'),
            ),
            'default',
            _t('自定义类型'),
            _t('自定义加载条类型')
        );
        $form->addInput($type);
        //加载条颜色
        $color = new Typecho_Widget_Helper_Form_Element_Text('color', null, '#FF77AA', _t('加载条颜色'), '加载条的颜色，请使用16进制颜色码。不懂可以百度');
        $form->addInput($color);
        //加载条时延
        $timeout = new Typecho_Widget_Helper_Form_Element_Text('timeout', null, '2000', _t('加载条关闭时延'), '加载条的关闭延时，如果您的网站打开速度非常好可能会看不到加载条，您可以按需调整延时关闭，单位是毫秒。');
        $form->addInput($timeout);
        //加载条图片
        $link = new Typecho_Widget_Helper_Form_Element_Text('link', null, '', _t('对应资源的完整直链'), '加载条的动图/图片的直接链接(以jpg，png，gif结尾这种)');
        $form->addInput($link);
        //加载条位置
        $position = new Typecho_Widget_Helper_Form_Element_Radio(
            'position',
            array(
                'center' => _t('中间'),
                'topleft' => _t('左上角'),
                'bottomleft' => _t('左下角'),
                'topright' => _t('右上角'),
                'bottomright' => _t('右下角'),
            ),
            'center',
            _t('加载条位置'),
            _t('自定义加载条位置')
        );
        $form->addInput($position);
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render()
    {
    }
    public static function header()
    {
        //jQuery支持
        echo '<link href="' . Helper::options() -> pluginUrl .'/SakuraLoad/load.css" rel="stylesheet" type="text/css"/>';
        $jqsup = trim(Typecho_Widget::widget('Widget_Options')->Plugin('SakuraLoad')->jqsupport);
        if ($jqsup == 'on') {
            echo '<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>';
        }
        //加载条颜色控制
        $sakuraSvg='<style>';
        $sakuraColor = Typecho_Widget::widget('Widget_Options')->Plugin('SakuraLoad')->color;
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $sakuraColor)) {
            $sakuraSvg = '<style>svg path,svg rect {fill:' . $sakuraColor . '}';
        }
        //加载条位置控制
        $sakuraPosition = Typecho_Widget::widget('Widget_Options')->Plugin('SakuraLoad')->position;
        switch ($sakuraPosition) {
            case 'center':
                $sakuraCss = '.sakura-loader,.sakura-loader-picture{top: calc(50% - 70px);left: calc(50% - 70px);}';
                break;
            case 'topleft':
                $sakuraCss = '.sakura-loader,.sakura-loader-picture{top: 15px;left: 15px;}';
                break;
            case 'bottomleft':
                $sakuraCss = '.sakura-loader,.sakura-loader-picture{bottom: 15px;left: 15px;}';
                break;
            case 'topright':
                $sakuraCss = '.sakura-loader,.sakura-loader-picture{top: 15px;right: 15px;}';
                break;
            case 'bottomright':
                $sakuraCss = '.sakura-loader,.sakura-loader-picture{bottom: 15px;right: 15px;}';
                break;
            default:
                $sakuraCss = '.sakura-loader,.sakura-loader-picture{top: calc(50% - 70px);left: calc(50% - 70px);}';
        }
        echo $sakuraSvg . $sakuraCss . '</style>';
    }


    public static function footer()
    {
        //获取插件配置信息
        $content = Typecho_Widget::widget('Widget_Options')->Plugin('SakuraLoad')->content;
        $type = Typecho_Widget::widget('Widget_Options')->Plugin('SakuraLoad')->type;
        $link = Typecho_Widget::widget('Widget_Options')->Plugin('SakuraLoad')->link;
        $timeout = Typecho_Widget::widget('Widget_Options')->Plugin('SakuraLoad')->timeout;
        if($timeout==null||$timeout==""){
            $timeout=1;
        }
        //输出插件工作内容
        echo '<script src="'  . Helper::options() -> pluginUrl . '/SakuraLoad/load.js" " type="text/javascript" charset="utf-8"></script>';
        echo <<<EOF

<script>
    $('doucument').ready(function(){
    SakuraLoad('$content','$type','$link');
    });
    window.onload=function(){
    setTimeout(function(){
    $('.sakura-load').hide();
    }, $timeout);
    }

</script>  

EOF;
    }
}
