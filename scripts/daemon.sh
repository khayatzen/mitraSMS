#!/bin/sh

# Configure this (use absolute path)
PHP=/usr/bin/php # php cli path
DAEMON=/home/infest/webapps/borneoclimate/scripts/daemon.php # daemon.php path

# Execute
#$PHP -q $DAEMON

curl http://localhost/borneoclimate/index.php/daemon/message_routine #>> /home/infest/.borneoclimate/daemon.log
