#!/bin/bash

/usr/bin/mysqld_safe > /dev/null 2>&1 &

RET=1
while [[ RET -ne 0 ]]; do
    echo "=> Waiting for confirmation of MySQL service startup"
    sleep 5
    mysql -uroot -e "status" > /dev/null 2>&1
    RET=$?
done
mysql -u root -e "CREATE database botsarena;"
mysql -u root -e "GRANT ALL on botsarena.* TO 'botsarena'@'localhost' IDENTIFIED BY 'botsPwd';"
mysql -u root botsarena < /install.sql


mysqladmin -uroot shutdown
