#!/bin/bash
#Script shell untuk daemon SMS 
#@khayate 06/04/2011 09:51

#/usr/bin/php5 /var/www/clients/client1/demo.borneoclimate.info/web/extensions/daemon.php
#/usr/bin/php5 /var/www/clients/client1/smsburuhmigran.infest.or.id/web/scripts/daemon.php
curl http://localhost/borneoclimate/index.php/daemon/message_routine
