<?php

	ob_start();
	require_once(dirname(__FILE__).'/../zui/zui_query.inc.php');
	require_once("rokko.inc.php");
	ob_end_clean();

	session_start();
	set_time_limit(0);

    $html = <<<EOD
<html><head><title>Rokko Debug Info</title></head><body>
<style type="text/css">
<!--
.style1 {font-family: Arial, Helvetica, sans-serif}
.title {
	FONT-Size: 14px;
	font-weight: bold;
}
img.thumbh {
	padding: 3px;
	border:0;
	width: 160px;
    height: 120px;
	display:inline;
}
.albumart{
	width: 120px;
	border:0;
}
.redborder {
	border-top-color: #FF0000;
	border-right-color: #FF0000;
	border-bottom-color: #FF0000;
	border-left-color: #FF0000;
}

.greenborder {
	border-top-color: #00AA00;
	border-right-color: #00AA00;
	border-bottom-color: #00AA00;
	border-left-color: #00AA00;
}
-->
</style>
<h1>Rokko Debug Info</h1>
<form id="form1" name="form1" method="post" action="rokko_debug.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="15%"><span class="style1">Admin Password:</span></td>
      <td width="85%">
		<input name="passwd" type="password" id="passwd" size="30" maxlength="60" />&nbsp;&nbsp;
		<input type="submit" name="login" id="login" value="Login" />&nbsp;&nbsp;
		<input type="submit" name="logout" id="logout" value="Logout" />&nbsp;&nbsp;
		<input type="submit" name="reboot" id="reboot" value="Reboot" class="redborder" />&nbsp;&nbsp;
        <input type="submit" name="zui_cleardb" id="zui_cleardb" value="Clear Zui Database" class="redborder" />&nbsp;&nbsp;
        <input type="submit" name="zui_deletedb" id="zui_deletedb" value="Delete Zui Database" class="redborder" />
	  </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><hr>
		<input type="submit" name="zui_info_status" id="zui_info_status" value="Zui Info Status" />&nbsp;&nbsp;
		<input type="submit" name="zui_get_status" id="zui_get_status" value="Zui Scan Status" />&nbsp;&nbsp;
        <input type="submit" name="zui_rescan" id="zui_rescan" value="Rescan All" />&nbsp;&nbsp;
        <input type="submit" name="zui_scanlocal" id="zui_scanlocal" value="Scan Local" />&nbsp;&nbsp;
        <input type="submit" name="zui_videocovers" id="zui_videocovers" value="Make M/V Thumbnails" /><br>
        <input type="submit" name="zui_restart" id="zui_restart" value="Zui CP Restart" />&nbsp;&nbsp;
        <input type="submit" name="twonky_restart" id="twonky_restart" value="Twonky Restart" />&nbsp;&nbsp;
        <input type="submit" name="twonky_rescan" id="twonky_rescan" value="Twonky Rescan" />&nbsp;&nbsp;
        <input type="submit" name="twonky_rebuild" id="twonky_rebuild" value="Twonky Rebuild" />&nbsp;&nbsp;
        <input type="submit" name="zui_kill_crawler" id="zui_kill_crawler" value="Kill Crawler" />&nbsp;&nbsp;<br>
        <input type="submit" name="ps" id="ps" value="ps" />&nbsp;&nbsp;
        <input type="submit" name="ls_share" id="ls_share" value="/share/.system/" />&nbsp;&nbsp;
        <input type="submit" name="rm_cache" id="rm_cache" value="Delete Thumbnail Cache" />&nbsp;&nbsp;
        <input type="submit" name="show_xml" id="show_xml" value="nas_conf_db.xml" />&nbsp;&nbsp;
		<input type="submit" name="check_swap" id="check_swap" value="Check Swap" />&nbsp;&nbsp;
		<br><hr>
		<input type="submit" name="system_get_infoall2" id="system_get_infoall2" value="system_get_infoall2" />&nbsp;&nbsp;
		<input type="submit" name="system_get_mvpage1" id="system_get_mvpage1" value="system_get_mvpage1" />&nbsp;&nbsp;
		<input type="submit" name="system_get_mvpage2" id="system_get_mvpage2" value="system_get_mvpage2" />&nbsp;&nbsp;
		<input type="submit" name="system_get_mvpage3" id="system_get_mvpage3" value="system_get_mvpage3" />&nbsp;&nbsp;
		<input type="submit" name="system_get_mvpage4" id="system_get_mvpage4" value="system_get_mvpage4" />&nbsp;&nbsp;
		<br><hr>
		<input type="submit" name="system_run_dropbear" id="system_run_dropbear" value="system_run_dropbear" />&nbsp;&nbsp;
		<input type="submit" name="run_shell_cmd" id="run_shell_cmd" value="run_shell_cmd" />&nbsp;&nbsp;
		cmd=<input name="run_cmd" type="text" id="run_cmd" size="30" maxlength="512" value="@@run_cmd@@" />&nbsp;
		<br><hr>
		<input type="submit" name="twonky_browse" id="twonky_browse" value="Twonky Browse" />&nbsp;&nbsp;
		<input type="submit" name="ServerGetList" id="ServerGetList" value="ServerGetList" />&nbsp;&nbsp;
		<input type="submit" name="ServerBrowse" id="ServerBrowse" value="ServerBrowse" />&nbsp;
			server=<input name="ServerBrowse_s" type="text" id="ServerBrowse_s" size="1" maxlength="2" value="@@ServerBrowse_s@@" />&nbsp;
			browse=<input name="ServerBrowse_b" type="text" id="ServerBrowse_b" size="30" maxlength="512" value="@@ServerBrowse_b@@" />&nbsp;
			start=<input name="ServerBrowse_start" type="text" id="ServerBrowse_start" size="3" maxlength="6" value="@@ServerBrowse_start@@" />&nbsp;
			count=<input name="ServerBrowse_count" type="text" id="ServerBrowse_count" size="3" maxlength="6" value="@@ServerBrowse_count@@" />&nbsp;<br>
		<!--<input type="submit" name="ZuiProxy" id="ZuiProxy" value="ZuiProxy" />&nbsp;
			url=<input name="ZuiProxy_u" type="text" id="ZuiProxy_u" size="30" maxlength="512" value="@@ZuiProxy_u@@" />&nbsp;-->
      </td>
    </tr>
  </table>
</form>
<hr>
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
@@status@@
</td></tr></table>
</body></html>
EOD;

	if (!isset($_REQUEST["run_cmd"]))
		$_REQUEST["run_cmd"] = "cat /proc/cpuinfo";
	$html = str_replace("@@run_cmd@@",$_REQUEST["run_cmd"],$html);

	if (!isset($_REQUEST["ServerBrowse_s"]))
		$_REQUEST["ServerBrowse_s"] = "0";
	$html = str_replace("@@ServerBrowse_s@@",$_REQUEST["ServerBrowse_s"],$html);

	if (!isset($_REQUEST["ServerBrowse_b"]))
		$_REQUEST["ServerBrowse_b"] = "0";
	$html = str_replace("@@ServerBrowse_b@@",$_REQUEST["ServerBrowse_b"],$html);

	if (!isset($_REQUEST["ServerBrowse_start"]))
		$_REQUEST["ServerBrowse_start"] = "0";
	$html = str_replace("@@ServerBrowse_start@@",$_REQUEST["ServerBrowse_start"],$html);

	if (!isset($_REQUEST["ServerBrowse_count"]))
		$_REQUEST["ServerBrowse_count"] = "100";
	$html = str_replace("@@ServerBrowse_count@@",$_REQUEST["ServerBrowse_count"],$html);

	if (!isset($_REQUEST["ZuiProxy_u"]))
		$_REQUEST["ZuiProxy_u"] = "";
	$html = str_replace("@@ZuiProxy_u@@",$_REQUEST["ZuiProxy_u"],$html);

	if (!isset($_REQUEST["download_file"]) || !preg_match("/^.*\.db$/",$_REQUEST["download_file"]))
        echo substr($html,0,strpos($html,"@@status@@"));

	$ctx = stream_context_create(array('http' => array('timeout' => $zui_timeout)));

	//////////

	$status = "";
	if (isset($_REQUEST["login"]))
	{
		if (!empty($_REQUEST['passwd']) && login("admin",md5($_REQUEST['passwd'])))
		{
			$_SESSION['login'] = 2;
			$status = "OK";
		}
		else
			$status = "NOK";
	}
	else if (isset($_REQUEST["logout"]))
	{
	    unset($_SESSION['login']);
	    session_destroy();
		$status = "Login first";
		$status = "<pre>".$status."</pre>";
	}
	else
	{
		if (!isset($_SESSION['login']) || $_SESSION['login'] < 2)
		{
			$status = "Login first";
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["reboot"]))
		{
			reboot();
			$status = "<pre>Rebooting...</pre>";
		}
		else if (isset($_REQUEST["zui_info_status"]))
		{
			$status = funcDump(zui_info_status);
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["zui_get_status"]))
		{
			$rc = zui_get_status();
			$status = "Running : ".($rc[0] ? 1 : 0)."\nDBVersion : ".$rc[1]."\nServer : ".$rc[2]."\nPercent : ".$rc[3]."\nIP : ".$rc[4];
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["zui_rescan"]))
		{
			zui_rescan();
			$status = "OK";
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["zui_scanlocal"]))
		{
			zui_exec_bg(get_absolute_path($php_path).' "'.dirname(__FILE__).'/../zui/zui_cmd.php'.'" scan local');
			$status = "OK";
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["zui_videocovers"]))
		{
            $status = 'OK';
            zui_exec_bg(get_absolute_path($php_path).' "'.dirname(__FILE__).'/../zui/zui_cmd.php'.'" allcovers local');
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["zui_cleardb"]))
		{
			zui_cleardb();
			$status = "OK";
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["zui_deletedb"]))
		{
			if (zui_deletedb())
			{
				$status = "OK";
			}
			else
				$status = "NOK";
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["zui_restart"]))
		{
            zui_killprocess("zui_cmd.php");
            zui_killprocess("zui");
	    	zui_gen_unlock(SPIDER_LOCK_VIDEOTHUMB);
	    	zui_gen_unlock(SPIDER_LOCK_GENERAL);
			sleep(2);
			zfw_exec("/usr/spbin/zui -f /var/www/htdocs/zui/zui.conf");
			$status = "OK";
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["run_shell_cmd"]))
		{
			$status = shell_exec($_REQUEST["run_cmd"]);
                        $list = explode("\n",$status);
                        foreach ($list as $key => $value)
                        {
                        	$l = explode(" ",$value);
                        	$str = trim($l[count($l)-1]);
                        	if (!empty($str) && $value[0] != 'd')
                        	{
            				$status = str_replace(" ".$str."\n"," <a href=\"rokko_debug.php?download_file=$str\">".$str."</a>\n",$status);
                        	}
                        }
			$status = "<pre>".$status."</pre>";
                }
		else if (isset($_REQUEST["system_run_dropbear"]))
		{
			zfw_exec("/etc/init.d/sshd start");
			$status = "OK";
			$status = "<pre>".$status."</pre>";
		}

		else if (isset($_REQUEST["twonky_restart"]))
		{
			zfw_exec("/usr/local/TwonkyVision/twonkymedia.sh stop");
			sleep(5);
			zfw_exec("/usr/local/TwonkyVision/twonkymedia.sh start");
			$status = "OK";
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["twonky_rescan"]))
		{
            if ($data = zuiServerGetList())
            {
                $zui = json_decode($data,true);
                if (($ip = GetServerLocalTwonkyIP($zui['Servers'])) != "")
                {
        			$status = @file_get_contents("http://$ip/rpc/rescan",false,$ctx);
                }
                else
                    $status = "NOK";
            }
            else
                $status = "NOK";

			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["twonky_rebuild"]))
		{
            if ($data = zuiServerGetList())
            {
                $zui = json_decode($data,true);
                if (($ip = GetServerLocalTwonkyIP($zui['Servers'])) != "")
                {
        			$status = @file_get_contents("http://$ip/rpc/rebuild",false,$ctx);
                }
                else
                    $status = "NOK";
            }
            else
                $status = "NOK";

			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["zui_kill_crawler"]))
		{
	        //@killprocess("zui_cmd.php");
            zui_killprocess("zui_cmd.php");
	    	zui_gen_unlock(SPIDER_LOCK_VIDEOTHUMB);
	    	zui_gen_unlock(SPIDER_LOCK_GENERAL);
			$status = "OK";
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["ps"]))
		{
			$status = shell_exec($ps_flag);
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["ls_share"]))
		{
            if ($os_running == OS_WIN32)
            {
                if ($dh = opendir($db_dir))
                {
                    while (($file = readdir($dh)) !== false)
                    {
                        if ($file == '.' || $file == '..') continue;

                        if (is_dir($file))
                        {
                            $status .= $file."\n";
                        }
                        else
                        {
                            $status .= "<a href=\"rokko_debug.php?download_file=$file\">".$file."</a>\n";
                        }
                    }
                    closedir($dh);
                }
            }
            else
            {
                $status = shell_exec("ls -l $db_dir");
				$list = explode("\n",$status);
				foreach ($list as $key => $value)
				{
					$l = explode(" ",$value);
					$str = trim($l[count($l)-1]);
					if (!empty($str) && $value[0] != 'd')
					{
						$status = str_replace(" ".$str."\n"," <a href=\"rokko_debug.php?download_file=$str\">".$str."</a>\n",$status);
					}
				}
            }

			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["rm_cache"]))
        {
            $status = "OK";
            if ($dh = @opendir($db_dir.$db_dir_cache))
            {
                while (($file = @readdir($dh)) !== false)
                {
                    if ($file == '.' || $file == '..') continue;

                    if (!is_dir($file))
                    {
                        @unlink($db_dir.$db_dir_cache.$file);
                    }
                }
                @closedir($dh);
            }
			$status = "<pre>".$status."</pre>";
        }
		else if (isset($_REQUEST["show_xml"]))
		{
            $status = file_get_contents("/etc/conf/nas_conf_db.xml");
			$status = "<plaintext>".$status."</plaintext>";
		} else if (isset($_REQUEST["check_swap"])) {
			$status = shell_exec("swapon -s");
			$status = "<pre>".$status."</pre>";
		}

		else if (isset($_REQUEST["download_file"]))
		{
            if (preg_match("/^.*\.db$/",$_REQUEST["download_file"]))
            {
                if (($fp = @fopen($db_dir.$_REQUEST["download_file"],"r")))
                {
                    header("Content-type: application/x-msdownload");
                    header("Content-Disposition: attachment; filename=\"{$_REQUEST["download_file"]}\"");
                    header("Content-Length: ".filesize($db_dir.$_REQUEST["download_file"]));
                    while (!feof($fp))
                    {
                        print(fread($fp,8192));
                        flush();
                        ob_flush();
                    }

                    fclose($fp);
                }
            }
            else
            {
                if (($fp = fopen($db_dir.$_REQUEST["download_file"],"r")))
                {
                    echo "<pre><b>".$_REQUEST["download_file"]."</b><br><br>";
                    while (($buf = fread($fp,8192)))
                    {
                        echo $buf;
                    }

                    fclose($fp);
                    echo "</pre>";
                }
            }
		}
		else if (isset($_REQUEST["system_get_info"]))
		{
			exec($execprefix.$php_path." ".dirname(__FILE__)."/rokko.php cmd=system_get_info",$status);
			$status = substr($status,strpos($status,'{'));
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["system_get_infoall2"]))
		{
			$status = shell_exec("php-cgi /var/www/htdocs/fw/rokko.php cmd=system_get_infoall2");
			$status = substr($status,strpos($status,'{'));
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["system_get_mvpage1"]))
		{
			$status = shell_exec("php-cgi /var/www/htdocs/fw/rokko.php cmd=system_get_mvpage1");
			$status = substr($status,strpos($status,'{'));
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["system_get_mvpage2"]))
		{
			$status = shell_exec("php-cgi /var/www/htdocs/fw/rokko.php cmd=system_get_mvpage2");
			$status = substr($status,strpos($status,'{'));
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["system_get_mvpage3"]))
		{
			$status = shell_exec("php-cgi /var/www/htdocs/fw/rokko.php cmd=system_get_mvpage3");
			$status = substr($status,strpos($status,'{'));
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["system_get_mvpage4"]))
		{
			$status = shell_exec("php-cgi /var/www/htdocs/fw/rokko.php cmd=system_get_mvpage4");
			$status = substr($status,strpos($status,'{'));
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["twonky_browse"]))
		{
            if ($data = zuiServerGetList())
            {
                $zui = json_decode($data,true);
                if (($ip = GetServerLocalTwonkyIP($zui['Servers'])) == "")
                    $ip = $_SERVER['SERVER_ADDR'].':9000';
            }
            else
                $ip = $_SERVER['SERVER_ADDR'].':9000';

			$status = file_get_contents(isset($_REQUEST["twonky_browse_url"]) ? $_REQUEST["twonky_browse_url"] : 'http://'.$ip.'/webbrowse',false,$ctx);

			if (strpos($status,'</div><hr><div') !== false)
				$status = substr($status,strpos($status,'</div><hr><div')+strlen('</div><hr>'));
			$status = str_replace('</body></html>','',$status);
			/*$status = preg_replace('/<a class="play".*?><img.*?><\/a>/i','',$status);*/
			$status = preg_replace('/<img class="play".*?>/i','',$status);
			/*$status = preg_replace('/<div class="title">(.*?)<\/div>/i','<div class="title"><b>${1}</b></div>',$status);*/

			//$status = preg_replace('/<a class="playcontainer" href="(.*)">/','<a class="playcontainer" href="rokko_debug.php?twonky_browse&twonky_browse_url=${1}">',$status);
			//$status = preg_replace('/<a class="container" href="(.*)">/','<a class="container" href="rokko_debug.php?twonky_browse&twonky_browse_url=${1}">',$status);
			$status = preg_replace('/<a class="(.*?container)" href="(.*?)">/i','<a class="${1}" href="rokko_debug.php?twonky_browse&twonky_browse_url=${2}">',$status,-1,$count);
			//$status = preg_replace('/<a class="container" href="(.*)">/i','<a class="container" href="rokko_debug.php?twonky_browse&url='.urlencode('$1').'">',$status);
			//$status = str_replace('<a class="container" href="','<a class="container" href="http',$status);

			$status = preg_replace('/<a class="play" href="(.*?)"/i','<a class="play" target="_blank" href="/ra_public/zui_proxy.cgi?url=${1}"',$status,-1,$count);
			$status = preg_replace('/<img class="albumart" src="(.*?)"/i','<img class="albumart" src="/ra_public/zui_proxy.cgi?url=${1}"',$status,-1,$count);
			$status = preg_replace('/<img class="thumbv" src="(.*?)"/i','<img class="thumbv" src="/ra_public/zui_proxy.cgi?url=${1}"',$status,-1,$count);
			$status = preg_replace('/<img class="thumbh" src="(.*?)"/i','<img class="thumbh" src="/ra_public/zui_proxy.cgi?url=${1}"',$status,-1,$count);

			//$status = varDump($_REQUEST);
		}
		else if (isset($_REQUEST["ServerGetList"]))
		{
			$status = funcDump(zui_proxy,array('z' => 'ServerGetList'));
			$status = preg_replace('/http:(.*?)"/i','<a href="/ra_public/zui_proxy.cgi?url=http:${1}" target="_blank">http:${1}</a>"',$status,-1,$count);
            $status = preg_replace('/"id"\s*?:\s*?(\d+),/i','"id" : <a href="rokko_debug.php?ServerBrowse=1&ServerBrowse_s=${1}&ServerBrowse_b=0">${1}</a>,',$status,-1,$count);
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["ServerBrowse"]))
		{
			$status = funcDump(zui_proxy,array('z'=>'ServerBrowse','server'=>$_REQUEST["ServerBrowse_s"],'browse'=>$_REQUEST["ServerBrowse_b"],
											   'start'=>$_REQUEST["ServerBrowse_start"],'count'=>$_REQUEST["ServerBrowse_count"]));
			$status = preg_replace('/http:(.*?)"/i','<a href="/ra_public/zui_proxy.cgi?url=http:${1}" target="_blank">http:${1}</a>"',$status,-1,$count);
			if (strpos($status,'"Container":"1"') !== FALSE)
                $status = preg_replace('/"id":"(.*?)"/i','"id":"<a href="rokko_debug.php?ServerBrowse=1&ServerBrowse_s='.$_REQUEST["ServerBrowse_s"].'&ServerBrowse_b=${1}">${1}</a>"',$status,-1,$count);
			$status = "<pre>".$status."</pre>";
		}
		else if (isset($_REQUEST["ZuiProxy"]))
		{
		}
		//else
		//	$status = varDump($_REQUEST);
	}

    //echo str_replace("@@status@@",$status,$html);

   	if (!isset($_REQUEST["download_file"]) || !preg_match("/^.*\.db$/",$_REQUEST["download_file"]))
    {
    	if (!empty($status)) echo $status;
    	echo substr($html,strpos($html,"@@status@@")+strlen("@@status@@"));
    }

    exit;

    function varDump($data)
	{
		ob_start();
		var_dump($data);
		$ret_val = ob_get_contents();
		ob_end_clean();
		return $ret_val;
	}

    function funcDump($func,$param1=null,$param2=null)
	{
		ob_start();
		$func($param1,$param2);
		$ret_val = ob_get_contents();
		ob_end_clean();
		return $ret_val;
	}

?>
