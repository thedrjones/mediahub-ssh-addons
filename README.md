# mediahub-ssh-addons
This is a handful of files to add to a Linksys MediaHub Root filesystem to allow access to the device via SSH. Includes a static pre-compiled version of [dropbear](https://matt.ucc.asn.au/dropbear/dropbear.html) that is suitable for the device. I've made these available should anyone else decide to have a play with their device for education purposes. Obviously you should not be using hacked-up devices in production. (In fact, given how easy it is to break into, I wouldn't use one in production at all).

I mainly wanted to do this because I like to keep my NAS devices somewhere out of the way, I don't want to be attempting to use 3 wire serial connections to be able to experiment with my own hardware's operarting systems.

I've also included my special extra line in http://mediahub/fw/rokko_debug.php that I originally used to start hacking on there as well as a *slightly* improved smb.conf generator which brings my transfers from 7MB/sec to 10MB/sec (your mileage may vary).

## SSH Installation
Pop your (fresh ideally, but shouldn't matter) disk or disks into the MediaHub and allow the initial installation to finish - this can take a while. If you are using 2TB disks, I would recommend pre-partitioning them according to this [Linksys community thread](http://community.linksys.com/t5/Media-Hub/2TB-HDD-for-NMH305/td-p/571796).

Once your disks are set up and the MediaHub has booted, place the disk or disks into your Linux machine and mount the second partition (e.g. sdb2) on each disk.

The files you will need to copy over are below. Most of the `/bin` and `/sbin` files, apart from `/bin/dropbearmulti` are symlinks to /bin/dropbearmulti.

* `/etc/init.d/*`
* `/bin/*`
* `/sbin/*`

### SSH secrets
You should probably generate your own secrets for /etc/dropbear on your host machine before copying them over, installl dropbear using your package installation method of choice, then use the below commands. My MediaHub is never going to be on the public internet, so I don't care if you know them!
* `dropbearkey -t dss -f /etc/dropbear/dropbear_dss_host_key`
* `dropbearkey -t rsa -f /etc/dropbear/dropbear_rsa_host_key`
* `dropbearkey -t ecdsa -f /etc/dropbear/dropbear_ecdsa_host_key`

### Root Password
The root password on the MediaHub is `giveit2me` (see this thread)[https://forum.nas-central.org/viewtopic.php?f=26&t=2059]. SCP does work on this dropbear config, but any files you copy in as root will not always be usable via other methods such as Samba or FTP.

## Bonus Files
### func_smb.conf
This just has a few little tweaks I use to speed up Samba. mainly buffer size tweaks. There is also some Async I/O config lines, but they might not any effect as I've not tested them in anger.

### rokko_debug.php
The login for this page is either the stock admin or whatever config password you have set in the Flash UI.
This scipt, available on the MediaHub webserver at http://mediahub/fw/rokko_debug.php contains quite a bit of stuff, not all of it useful. You will notice the addition of a new row with system_run_dropbear (which does /etc/init.d/sshd start) and run_shell_cmd. This basically wraps the php exec() function around the cmd= text box to the side of it.

## Licence
This repository and the files contained within it are from various sources, almost nothing in here is my original work bar a little PHP or Bash hacking to add the lines we need to do the new things. Keep in mind the original licence terms from each file (although I doubt Cisco/Linksys/Belkin care too much about their MediaHub config files by now).
