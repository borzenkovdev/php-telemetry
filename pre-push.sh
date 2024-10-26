#!/bin/sh
#

docker exec -t api sh phpcs.sh

RESULT=$?
if [ $RESULT -ne 0 ]
  then
    echo  "\033[31m Push was not executed due to failed lints \033[0m"
    exit 1
fi

exit 0

#
