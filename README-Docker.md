
docker-compose up -d



docker build -t candh-web .
docker run -d --name spaceman candh-web -p 8484:80

# Running a docker
docker system prune 
docker buildx build -t hobbes .
docker run --rm --name hobbes -ditp 8484:80 --mount type=bind,source=/Users/shaunsund/Local/Repos/sundsites/spacemanspiff.site/htdocs,target=/var/www/html/ hobbes

# build with no cache
sudo docker build --no-cache -t candh-web . 2>&1 | tee build.log

# build with cache
sudo docker build -t candh-web .

# daemon mode
docker run -d \
    --name candh-web \
    -p 8484:80/tcp \
    -e TZ='America/New_York' \
    candh-web

# interactive
docker run --rm -it \
    --name candh-web \
    -p 8484:80/tcp \
    -e TZ='America/New_York' \
    candh-web /bin/bash

# docker build with tags and push
sudo docker build --no-cache -t shaunsund/candh-web:latest -t shaunsund/candh-web:0.1 .
sudo docker push -a shaunsund/candh-web

# remove the container after
sudo docker rm candh-web

# More cleanup
# Kill all running containers:
sudo docker kill $(docker ps -q)

# Delete all stopped containers
sudo docker rm $(docker ps -a -q)

# Delete all images
sudo docker rmi $(docker images -q)

# Remove unused data
sudo docker system prune

# And some more
sudo docker system prune -af


docker exec -ti spaceman sh