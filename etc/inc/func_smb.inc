<?php
/* (c) Copyright ©  2006 Radlan Computer Communications, Ltd. All Rights Reserved. */

require_once("/etc/inc/globals.inc");
require_once("/etc/inc/global_defs.inc");
require_once("/etc/inc/util.inc");
require_once("/etc/inc/func_system.inc");
require_once("/etc/inc/func_config.inc");
require_once("/etc/inc/func_group.inc");
require_once("/etc/inc/func_user.inc");
require_once("/etc/inc/func_share.inc");

function smbfunc_daemon_start() {

    $ret = smbfunc_conf_commit(true);

    return $ret;
}

function smbfunc_conf_commit($killall=false)
{
    global $config, $volatileConfig, $g;

    if ($g['booting'])
        echo "Starting Samba... ";

    /* generate smb.conf */
	$fd = fopen("/etc/samba/smb.conf", "w");
    if (!$fd) {
        logError(__FILE__, __LINE__, "Error: cannot open smb.conf in smbfunc_conf_commit().", LOG_WARNING);
        return FAIL_E;
    }

    /* If no share configured, exit */
    if (!is_array($config['mounts']['mount']))
    {
       logError(__FILE__, __LINE__, "smbfunc_conf_commit: mountpoint not defined!", LOG_WARNING);
       return FAIL_E;
    }

    /* TBD: possibly support more the one mount points */
    foreach($config['mounts']['mount'] as $mountent) {
        if($mountent['fstype'] != 'swap')
            break;
    }

    $mountpoint= $mountent['mountpoint'];

    if (is_dir($mountpoint) == false) {
        logError(__FILE__, __LINE__, "smbfunc_conf_commit: mountpoint not avaliable ({$mountpoint})!", LOG_WARNING);
        return FAIL_E;
    }

    $realm = strtoupper($config['samba']['domain']);
    $sambaconf = <<<EOD
[global]
netbios name = {$config['samba']['netbiosname']}
workgroup = {$config['samba']['workgroup']}
server string = {$config['samba']['serverdesc']}
# This will prevent nmbd to search for NetBIOS names through DNS.
printer admin = root, guest
show add printer wizard = no
load printers = no
printing = bsd
printcap name = /dev/null
disable spoolss = yes
encrypt passwords = yes
security = {$config['samba']['security']}
map to guest = bad password
use mmap = yes
use sendfile = yes
min receive size = 2048
aio read size = 2048
aio write size = 2048
write cache size = 1024000
oplocks = yes
large readwrite = yes
dns proxy = no
max log size = 200
log level = 0
socket options = TCP_NODELAY IPTOS_LOWDELAY SO_KEEPALIVE SO_RCVBUF=131072 SO_SNDBUF=131072 
local master = yes
dns proxy = no
ldap ssl = no
smb ports = 445 139
read raw = Yes
write raw = Yes
deadtime = 5
getwd cache = Yes
kernel change notify = Yes
max open files = 10000
use mmap = Yes
hostname lookups = No
name cache timeout = 660
qos enable = yes
read prediction = yes
map archive = yes
level1 file extensions = avi wmv wma
level2 file extensions = mpeg mpg
; disable spoolss = Yes
; guest account = nobody
;create mask = 0666
directory mask = 0777
create mask = 0777
force create mode = 0777

EOD;
    /* add windows domains support */
    if($config['samba']['security'] == 'ADS') {
        $sambaconf .= "password server = {$config['samba']['passwordserver']}\n";
        $sambaconf .= "realm = {$realm}\n";
        $sambaconf .= "idmap uid = 10000-20000\n";
        $sambaconf .= "idmap gid = 10000-20000\n";
        $sambaconf .= "auth methods = winbind\n";
        $sambaconf .= "winbind separator = +\n";
    }

    $sambaconf .= "\n\n";

    $all_user_list = ' ';
    if (isset($config['access']['users']['user']))
    {
        foreach ($config['access']['users']['user'] as $user_ent)
        {
            /*$userinfo['username'] = $user_ent['username'];*/
            /*array_push($userlist, $userinfo);*/
            $all_user_list .= $user_ent['username'] . ' ';
        } /* foreach: user_ent */
    }

    /* unify share info from $config & $volatileConfig */
    if (isset($volatileConfig['shares']['share'])) {
        $allShares = array_merge($config['shares']['share'], $volatileConfig['shares']['share']);
    } else {
        $allShares = array_merge($config['shares']['share']);
    }

    foreach ($allShares as $share_ent)
    {
        /* make sure share is valid */
        if (!$share_ent['sharename']) {
            continue;
        }

        /* check if share is active */
        if ($share_ent['sharestatus'] != 'active' || (isset($share_ent['afp']) && $share_ent['afp'] == 'true')) {
            continue;
        }

        /* make sure share directory exist */
        if ($share_ent['sharelocation'] == SH_LOC_INT)
        {
            $full_share_path = $mountpoint . '/' . $share_ent['sharename'];
            if  (is_dir($full_share_path) == false)
            {
                if (mkdir($full_share_path) == false)
                {
                    logError(__FILE__, __LINE__, "smbfunc_conf_commit: cannot create share directory({$full_share_path})!", LOG_WARNING);
                   continue;
                }
                chmod($full_share_path, 0777);
                // make links for private share
               //if( isset($share_ent['sharecheck']) && $share_ent['sharecheck'] == '1' ){
               //	exec("ln -s /share/public " . escapeshellarg($full_share_path) );
               //	exec("ln -s /share/music " . escapeshellarg($full_share_path) );
               //	exec("sudo mkdir -p /share/photos/" . escapeshellarg($share_ent['sharename']) );
               //	exec("sudo chmod 777 /share/photos");
               //	exec("sudo chmod 777 /share/photos/" . escapeshellarg($share_ent['sharename']) );
               //	exec("ln -s /share/photos/" . escapeshellarg($share_ent['sharename']) . " " . escapeshellarg($full_share_path) . "/photos");
               //	exec("ln -s /share/software " . escapeshellarg($full_share_path) );
               //	exec("ln -s /share/videos " . escapeshellarg($full_share_path) );
               //}
            }
        }
        else if ($share_ent['sharelocation'] == SH_LOC_EXT)
        {
            $full_share_path = $share_ent['sharemount'];

            /* make sure external share point exist */
            if (is_dir($full_share_path) == false)
            {
                if (mkdir($full_share_path) == false)
                {
                    logError(__FILE__, __LINE__, "smbfunc_conf_commit: cannot create share directory({$full_share_path})!", LOG_WARNING);
                    continue;
                }
                chmod($full_share_path, 0777);
            }
        }
        else
        {
            /* unknown share type ext/int */
            continue;
        }

        if ($share_ent['sharetype'] == 'public') {
            /* generate share section */
            $sambaconf .= <<<EOD
[{$share_ent['sharename']}]
comment = {$share_ent['sharename']}
path = {$full_share_path}
writeable = yes
printable = no
public = yes
EOD;
            $sambaconf .= "\n\n";
        } /* if: public share */
        else if ($share_ent['sharetype'] == 'private')
        {
            $share_ro_list = '';
            $share_rw_list = '';
            $share_inv_list = $all_user_list; /* invalid user list */

            $share_fld_name = $share_ent['sharename'];

            /* check if this share has a user list */
            if (isset($share_ent['access']['users']['user']))
            {
                foreach ($share_ent['access']['users']['user'] as $share_user_ent)
                {
                    if (!$share_user_ent['username']) {
                        continue;
                    }

                    /* make sure user is present in user list (i.e. inconsistent user - exists
                       in share acces list but not in system user list) & remove it from
                       invalid users */
                    $pos = strpos($share_inv_list, ' ' . $share_user_ent['username'] . ' ');
                    if (is_bool($pos) && ($pos == false)) {
                        continue;
                    }

                    /* remove user from invalid user list */
                    $share_inv_list = substr_replace($share_inv_list, '',
                                                     $pos,
                                                     strlen($share_user_ent['username']) + 1);
                    switch ($share_user_ent['accesstype']) {

                    case 'full':
                        $share_rw_list .= " " . $share_user_ent['username'];
                        break;

                    case 'readonly':
                        $share_ro_list .= " " . $share_user_ent['username'];
                        break;
                    }
                } /* foreach: share_user_ent */
            } /* if there is user list for the share */

            /* check if this share has a group list */
            if (isset($share_ent['access']['groups']['group'])) {
                /* walk through the group list */
                foreach ($share_ent['access']['groups']['group'] as $share_group_ent) {
                    if (!$share_group_ent['groupname']) {
                        continue;
                    }

                    /* find access group with the same name */
                    if (groupfunc_find_group($share_group_ent['groupname'], $sys_grp_key) == null) {
                        logError(__FILE__, __LINE__, "sharefunc_smbfunc_conf_commit: no such system group ({$share_group_ent['groupname']})!", LOG_WARNING);
                        continue;
                    }

                    /* walk through the system group members list */
                    if(isset($config['access']['groups']['group'][$sys_grp_key]['members']['member'])){
                        /* handle members of this group (if any) */
                        foreach ($config['access']['groups']['group'][$sys_grp_key]['members']['member'] as $share_member) {
                            $pos = strpos($share_inv_list, ' ' . $share_member . ' ');
                            if (is_bool($pos) && ($pos == false)) {
                                    continue;
                            }

                            /* remove group user from invalid users list */
                            $share_inv_list = substr_replace($share_inv_list, '',
                                                             $pos,
                                                             strlen($share_member) + 1);

                        /* TODO - remove group members from both RO and RW user lists */

                        } /* foreach: share_member */
                     } /* handle members of this group (if any) */

                    switch ($share_group_ent['accesstype']) {

                    case 'full':
                        $share_rw_list .= " @" . $share_group_ent['groupname'];
                        break;

                    case 'readonly':
                        $share_ro_list .= " @" . $share_group_ent['groupname'];
                        break;
                                }
                } /* foreach: share_group_ent */
            } /* if there is group list for the share */

            /* generate share section */
            $sambaconf .= <<<EOD
[{$share_ent['sharename']}]
comment = {$share_ent['sharename']}
path = {$full_share_path}
invalid users = {$share_inv_list}
read list = {$share_ro_list}
write list = {$share_rw_list}
#read only = no
#writable = no
printable = no
EOD;
            $sambaconf .= "\n\n";
        } /* if: private share */
    } /* foreach: share_ent */
   

$sambaconf .= <<<EOD
[printers]
comment = All Printers
browseable = no
public = Yes
printable = Yes
path = /var/spool/samba
use client driver = Yes
printer admin = root
writable = no

EOD;
    $sambaconf .= "\n\n";
    /* write smb.conf */
    if (fwrite($fd, $sambaconf) == FALSE) {
        logError(__FILE__, __LINE__, "cannot write conf file.", LOG_WARNING);
    }
    fclose($fd);

    /* run smbd and nmbd */
    if ($g['booting'])
    {
        if($config['samba']['security'] == 'ADS')
            mwexec("/usr/sbin/winbindd");

        mwexec("/sbin/smbd -D -l {$g['varlog_path']}/samba");
        mwexec("/sbin/nmbd -D -l {$g['varlog_path']}/samba");
    }
    else
    {
        if ($killall)
        {
            killbyname("smbd");
            killbyname("nmbd");

            if($config['samba']['security'] == 'ADS') {
                killbyname("winbindd");
                mwexec("/usr/sbin/winbindd");
            }

            mwexec("/sbin/smbd -D -l {$g['varlog_path']}/samba");
            mwexec("/sbin/nmbd -D -l {$g['varlog_path']}/samba");
        }
        else
        {
            mwexec("/usr/bin/smbcontrol  smbd reload-config");
            mwexec("/usr/bin/smbcontrol  nmbd reload-config");
        }
    }

    if ($g['booting'])
        echo "done\n";

    return OK_E;
} /* end of func: smbfunc_conf_commit */

?>
