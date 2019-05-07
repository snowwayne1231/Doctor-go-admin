#!/bin/bash
myhost="localhost"
myuser="psbeauty_mobile"
mypass="mobile1qazMobile"
mydb="psbeauty_mobile_app_20190507"
backup_path="/var/www/backup/"
sql_file=${backup_path}psbeauty_mobile_$(date +%Y%m%d).sql
echo $sql_file
echo "mysqldump.."
mysqldump -h $myhost -u $myuser -p${mypass} $mydb > $sql_file
echo "gzip..."
gzip -f $sql_file
echo "done."
