#!/bin/bash
. config.conf
docker run -v ${hostshare}:/app -p 8081:8081 -it --entrypoint "/app/init.sh" --rm --name algebr algebr/openface2