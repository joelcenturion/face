#!/bin/bash
. config.conf
docker run -v /home/ubuntu/face/OpenFace/scripts:/app -p 8080:8080 -it --entrypoint "./app/init.sh" bamos/openface