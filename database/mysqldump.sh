#備份數據庫

PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:/www/wdlinux/mysql-5.1.56/bin

export PATH

#數據庫用戶名

dbuser='psbeauty_mobile'

#數據庫密碼

dbpasswd='mobile1qazMobile'

#數據庫名,可以定義多個數據庫，中間以空格隔開，如：test test1 test2

dbname='psbeauty_mobile_app_20190507'

#備份時間
 
backtime=`date +%Y%m%d%H%M%S`

#日誌備份路徑

logpath='/var/www/logs'

#數據備份路徑

datapath='/var/www/backup'

#日誌記錄頭部

echo ‘"備份時間為${backtime},備份數據庫表 ${dbname} 開始" >> ${logpath}/mysqllog.log

#正式備份數據庫

for table in $dbname; do

source=`mysqldump -u ${dbuser} -p${dbpasswd} ${table}> ${datapath}/${backtime}.sql` 2>> ${logpath}/mysqllog.log;

#備份成功以下操作

if [ "$?" == 0 ];then

cd $datapath

#為節約硬盤空間，將數據庫壓縮

tar jcf ${table}${backtime}.tar.bz2 ${backtime}.sql > /dev/null

 
#刪除原始文件，只留壓縮後文件

rm -f ${datapath}/${backtime}.sql

echo "數據庫表 ${dbname} 備份成功!!" >> ${logpath}/mysqllog.log

else

#備份失敗則進行以下操作

echo "數據庫表 ${dbname} 備份失敗!!" >> ${logpath}/mysqllog.log

fi

done