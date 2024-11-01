<?php
/*
Plugin Name: WP2DiguHome
Plugin URI: http://www.liqiu.info/WP2DiguHome
Description: 同步发表 WordPress 博客日志到 嘀咕 ,初次安装必须设置后才能使用。加入可选短地址提供商,加入是否发送标签(Tags),加入是否使用短地址
Version: 1.0.1
Author: Paul , askinglee
Author URI: http://liqiu.info/
*/
/*  Copyright 2010  Paul   (email : askinglee@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2.

*/

// Hook for adding admin menus
add_action('publish_post', 'publish_post_2_digu');
add_action('xmlrpc_public_post', 'publish_post_2_digu');
add_action('admin_menu', 'mt_add_digu_pages');

// action function for above hook
function mt_add_digu_pages() {
    //call register settings function
	add_action( 'admin_init', 'register_wpdigu_settings' );
	// Add a new submenu under Options:
    add_options_page('WP2DiguHome Options', 'WP2DiguHome', 'administrator', 'wpdigu', 'mt_wpdigu_page');

}

function register_wpdigu_settings() {
	//register our settings
	register_setting( 'WP2Digu-settings-group', 'wp2diguuser' );
	register_setting( 'WP2Digu-settings-group', 'wp2digupass' );
	register_setting( 'WP2Digu-settings-group', 'shortprovider' );
	register_setting( 'WP2Digu-settings-group', 'digu_needTags' );
	register_setting( 'WP2Digu-settings-group', 'digu_short' );
}


// mt_options_page() displays the page content for the Test Options submenu
function mt_wpdigu_page() {

 if (!function_exists("curl_init"))
 {
?>
<style type="text/css">
<!--
.STYLE1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>


<div class="wrap">
  <h2>您的服务器不支持cURL库，插件WP2DiguHome无法工作，请禁用该插件。</h2>
  <br />
</div>
<?php
 }
 else
 {
?>
<div class="wrap">
  <h2>WP2DiguHome 选项</h2>
  <table border="1px" cellpadding="1" cellspacing="4" bordercolor="#999999" style="border-width:1px; border-color:#999999;" >
  <tr valign="top">
  	<th width="117px" scope="row">设置</th>
  	<td >设置仅适用于嘀咕，支持将Wordpress日志<b>标题、标签、地址或短地址</b>发布到嘀咕。</td>
  </tr>
  <tr valign="top">
  	<th scope="row">加入标签示例</th><td>日志标题-标签1,标签2-http://bit.ly/glzIsz (如果没有标签将获取分类)<br /> </td>
  </tr>
  <tr valign="top">
  	<th scope="row"> <span style="color:red;">重要</span></th>
  	<td>经本人测试由Godaddy提供的免费空间会报Warning，这个不会影响其他功能只是短地址转换不可用，只会发送源地址。建议使用其他空间或Godaddy收费空间，可以从此处：<a href="http://www.godaddy.com/hosting/hosting.aspx?ci=22124&isc=IAPtno138" title="购买godaddy主机" target="_blank">购买Godaddy美国主机</a><br /></td>
  </tr>
  <tr valign="top">
  	<th scope="row">本插件地址</th>
  	<td>作者官方网站地址： <a href="http://www.liqiu.info/2010/12/wp2diguhome/" title="wp2diguhome" target="_blank">http://www.liqiu.info/2010/12/wp2diguhome/</a><br />
  	  WordPress官方地址：<a href="http://wordpress.org/extend/plugins/wp2diguhome/" title="WordPress-wp2diguhome" target="_blank">http://wordpress.org/extend/plugins/wp2diguhome/</a><br /></td>
  </tr>
  <tr valign="top">
  	<th scope="row">约定</th><td>默认情况下短地址服务商：http://bit.ly/<br />
  默认情况下不发送标签<br />
  默认情况下发送短地址</td>
  </tr>
  </table>
  <br/>
  <form method="post" action="options.php">
    <?php settings_fields( 'WP2Digu-settings-group' ); ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">嘀咕的登录名</th>
        <td><input name="wp2diguuser" type="text" id="wp2diguuser" value="<?php form_option('wp2diguuser'); ?>" class="regular-text" />
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">嘀咕的登录密码</th>
        <td><input name="wp2digupass" type="password" id="wp2digupass" value="<?php form_option('wp2digupass'); ?>" class="regular-text" />
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">短地址提供商</th>
        <td><label for="prov-bitly">
          <input id="prov-bitly" type="radio" <?php checked('bitly',get_option('shortprovider')) ?> value="bitly" name="shortprovider"/>
          bit.ly (<a href="http://bit.ly/" target="_blank" style="font-size:10px">Visit Homepage</a>) </label>
          <br/>
          <label for="prov-isgd">
          <input id="prov-isgd" type="radio" <?php checked('vgd',get_option('shortprovider')) ?> value="vgd" name="shortprovider"/>
          v.gd (<a href="http://v.gd/" target="_blank" style="font-size:10px">Visit Homepage</a>) </label>
          <br/>
          <label for="prov-tiny">
          <input id="prov-tiny" type="radio" <?php checked('tiny',get_option('shortprovider')) ?> value="tiny" name="shortprovider"/>
          tinyurl (<a href="http://tinyurl.com/" target="_blank" style="font-size:10px">Visit Homepage</a>) </label>
        </td>
      </tr>
	  <tr valign="top">
        <th scope="row">是否将标签(Tags)加入</th>
		<td>
			<input name="digu_needTags"  value="0" <?php checked(0, get_option('digu_needTags')); ?> id="digu_needTags0" type="radio"><label for="digu_needTags0">不需要</label>
			<input name="digu_needTags" value="1" <?php checked(1, get_option('digu_needTags')); ?> id="digu_needTags1" type="radio">
			<label for="digu_needTags1">需要</label>
		</td>
	  </tr>
	  	  <tr valign="top">
        <th scope="row">发送链接设置</th>
		<td>
			<input name="digu_short"  value="1" <?php checked(1, get_option('digu_short')); ?> id="digu_short1" type="radio"><label for="digu_short1">短地址</label>
			<input name="digu_short" value="0" <?php checked(0, get_option('digu_short')); ?> id="digu_short0" type="radio">
			<label for="digu_short0">原地址</label>
		</td>
	  </tr>
    </table>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
  </form>
  <br/>
</div>
<?php
 }
}

function publish_post_2_digu($post_ID){

	$digu = get_post_meta($post_ID, 'digu', true);
	if($digu) return;

	$post=get_post($post_ID);
	$oUrl = get_permalink($post_ID);
	$sUrl = '';
	$sendUrl = get_permalink($post_ID);
	$sendContext = $post->post_title;
	
	//判断是否发送短地址
	$sendShort = get_option('digu_short');
	if($sendShort!=0){
		switch(get_option('shortprovider')){
			   case "vgd": $sUrl = 'http://v.gd/create.php?format=simple&url=' . urlencode($oUrl); break;
			   case "tiny": $sUrl = 'http://tinyurl.com/api-create.php?url=' . urlencode($oUrl); break;
		}
		if(!$sUrl){$sUrl = 'http://bit.ly/api?url=' . urlencode($oUrl);}
		//获得短地址
		if($getURL = file_get_contents($sUrl)){
			$sendUrl = $getURL;
		}
		
	}
	//判断是否发送标签
	$needTags = get_option('digu_needTags');
	if($needTags == 1){
		if($tags = get_the_tags($post_ID)){
			if(count($tags) > 0){
				$tagsString = $tags[0]->name;
				for($i = 1 ; $i < count($tags) ; $i++){
					$tagsString .= ',' . $tags[$i]->name;
				}
				$sendContext .= '-' . $tagsString;	
			}
		}else if($categories = get_the_category($post_ID))
		{
			if(count($categories) > 0){
				$categoriesString = $categories[0]->cat_name;
				for($j = 1 ; $j < count($categories) ; $j++){
					$categoriesString .= ',' . $categories[$j]->cat_name;
				}
				$sendContext .= '-' . $categoriesString;	
			}
		}else{
		
		}
	}
	
	//组合需要发送的内容
	$sendContext .= '-原文地址:'.$sendUrl;
	
	update_digu($sendContext);
	add_post_meta($post_ID, 'digu', 'true', true);
}
function update_digu($sendContext){
	require_once(ABSPATH.WPINC.'/class-snoopy.php');
	$snoop = new Snoopy;
	$snoop->user = get_option('wp2diguuser');
	$snoop->pass = get_option('wp2digupass');
	$snoop->submit(
		'http://api.minicloud.com.cn/statuses/update.json'
		, array('content' => $sendContext , 'source' => 'wpthread')
		);
}
?>
