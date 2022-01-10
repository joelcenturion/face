#!/bin/bash
. config.conf
docker run -v ${hostshare}:/app -p 8080:8080 --rm --name bamos -t -i bamos/openface "./app/init.sh"