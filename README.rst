
.. contents:: 目录


插件使用
==========


第一步：下载安装包
---------------------

1. 在本页面右侧下方点击“Download ZIP”按钮下载插件安装包
#. 解压安装包后将压缩包名称“gt-discuz-x2.5-x3.2-master”改为“geetest”
#. 将该文件夹放置在Discuz插件目录下（/upload/source/plugin）
    
第二步：后台设置
------------------------------------
1. 进入Discuz管理中心，点击头部导航栏“防灌水”->左侧导航栏“验证设置”，在“规则设置”中选择“不启用”或“否”来关闭普通验证
#. 点击头部导航栏“应用”，在未安装的插件中找到“极验验证码”，点击安装并启用
#. 在“极验验证码”插件下方点击“设置”进行设置插件的使用，点击“极验云”可修改验证ID和KEY


文件描述
==========


注意：本项目提供的极验验证的前端实现方法均是面向PC端的。
如果需要移动端的验证功能，请参考移动端的前端文档。

`PC端前端文档 <http://www.geetest.com/install/sections/idx-client-sdk.html#pcweb>`_

`移动端前端文档 <http://www.geetest.com/install/sections/idx-client-sdk.html#web>`_



核心库(Discuz嵌入点)
---------------------

1. geetest.class.php
    geetest插件PC端入口文件，获取插件设置参数，定义全局嵌入点类
#. geetest_mobile.class.php
    geetest插件移动端入口文件，获取插件设置参数，定义全局嵌入点类
#. /plugin_class
    1. plugin_geetest_forum.class.php
        forum脚本嵌入点类
    #. plugin_geetest_group.class.php
        group脚本嵌入点类
    #. plugin_geetest_home.class.php
        home脚本嵌入点类
    #. plugin_geetest_member.class.php
        member脚本嵌入点类
#. /template
    1. forum.htm
        forum/group脚本嵌入点输出内容模板
    #. home.htm
        home脚本嵌入点输出内容模板
    #. member.htm
        member脚本嵌入点输出内容模板
    #. module.htm
        全局嵌入点输出内容模板


    
极验验证码后台模块页面
------------------------------------

1. geetestcloud.inc.php
	后台“极验云”程序模块:用户配置文件,此处填写用户自己申请的验证模块ID/KEY
#. advance.inc.php
	后台“高级功能”程序模块
#. feedback.inc.php
	后台“帮助反馈”程序模块
#. about.inc.php
	后台“常见问题”程序模块
	


其他
-------------------------------------------------

1. gt_check_server.php
	向极验验证码服务器发送请求，用户判断极验服务器是否Down机,获取加载验证码所需参数
#. /lib
	1. config.php
	    captchaid和privatekey配置文件
	2. geetestlib.php
	    极验的PHP SDK库,提供只带拼图行为验证的功能
#. /js
	1. geetest_mobile.js
	    移动端加载极验验证码以及调用回调函数
	2. gt_core.js
	    PC端加载极验验证码
	3. gt_init.js
	    用于PC端引入验证的前端 gt_lib 库
	4. mobile_keyset.js
	    插件后台“极验云”模块用户修改移动端验证ID/KEY时生成ajax请求
	5. web_keyset.js
	    插件后台“极验云”模块用户修改Web端验证ID/KEY时生成ajax请求
#. install.php
	安装插件时动态获取验证ID/KEY
#. upgrade.php
	更新插件时执行
#. discuz_plugin_geetest_SC_GBK.xml
        编码为GBK简体的插件语言包
#. discuz_plugin_geetest_SC_UTF8.xml
        编码为UTF8简体的插件语言包
#. discuz_plugin_geetst_TC_BIG5.xml
        编码为BIG5繁体的插件语言包
#. discuz_plugin_geetest_TC_UTF8.xml
        编码为UTF8繁体的插件语言包



联系作者
=============

Email:dreamzsm@gmail.com


发布日志（由新到旧）
===================================



1.0
--------------------



