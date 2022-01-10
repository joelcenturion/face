#!/bin/bash
. config.conf
docker run -v /home/ubuntu/face/Landmark/app1:/app -p 8081:8081 -it --entrypoint "/app/init.sh" algebr/openface2