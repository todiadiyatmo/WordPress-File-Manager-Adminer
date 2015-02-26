<?php
/*
Plugin Name: File Browser, Manager and Backuper (+ Database)
Description: View, Edit, Browser , Zip and Unzip files and folders.. Make Backups of files and databases + RESTORE them easily. (STANDALONE PHP VERSI0N here: https://github.com/tazotodua/Simple-PHP-file-browser-manager/ ). Use at your own risk.
Author: selnomeria, must@fa#
Version: 1.1
License: GPLv2 free
*/

//Wordpress File Manager & Browser
function get_wfmb_remote_data($url)	{
	$c = curl_init();	curl_setopt($c, CURLOPT_URL, $url);	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);	curl_setopt($c, CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);	curl_setopt($c, CURLOPT_MAXREDIRS, 10);	curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);	$data = curl_exec($c);	curl_close($c);
	return $data;
}



add_action('admin_menu', 'wfmb_regist'); function wfmb_regist() {add_menu_page('File Manager and Backuper', 'File Manager and Backuper', 'administrator','wfmb-page', 'wfmb_output_func'); }
function wfmb_output_func()
{
	//============= check version updates ========
	$current_version_last_checked= get_option('wfmb_version_last_check_time');
	preg_match("/\nVersion\:(.*?)\n/", file_get_contents(__FILE__) ,$n);
	$current_version=trim($n[1]);
	$target_version =$current_version;
	//check every 7 days
	if (time() > $current_version_last_checked + 7*86400) { 
		update_option('wfmb_version_last_check_time', time() );
		$t = get_wfmb_remote_data('http://plugins.svn.wordpress.org/file-manager-database-backup/trunk/index.php');
		preg_match("/\nVersion\:(.*?)\n/", $t ,$p);
		$target_version= trim($p[1]);
	}
	//============= ######check version updates #####========
	
	$start_path = '/'.basename(dirname(dirname(dirname(dirname(__FILE__)))));
	$tmp_filee	= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, '7') .'pmp';
	?>
	
	
	<style>
	.br_window_div	{	width: 100%;	height:100%;	}
	iframe.fr_window{	width: 100%;	height:100%;	}
	.version_message{background-color: #F00;float: left;padding: 10px;margin: 10px;font-size: 2em;}
	</style>
	
	<?php if ($target_version != $current_version) : ?>
	<div class="versionn">
		<div class="version_message">Plugin Update is available. Please, re-install the <a href="http://localhost/wp3/wp-admin/plugins.php" target="_blank">latest version</a>.</div>
	</div>
	<?php exit;  endif; ?>
	
	<div class="br_window_div">
		<iframe onLoad="autoResize('ifrm1');" id="ifrm1" src="<?php echo plugins_url('',__FILE__); ?>/filemanager.php?do=login&path=.<?php echo $start_path;?>&temp_pass_file=<?php echo $tmp_filee;?>"  class="fr_window" scrolling="none"  frameborder="0"></iframe>
	</div>

	<script language="JavaScript">
	function autoResize(id)
	{
		var newheight;
		if( document.getElementById(id) ){
			newheight=document.getElementById(id).contentWindow.document.body.scrollHeight;
		}
		if (newheight < 400) {newheight = 400;}
		document.getElementById(id).style.height= parseInt(newheight+40) + "px";
	}
	</script>



	<?php
}
?>