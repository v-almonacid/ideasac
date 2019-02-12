#!/bin/bash


# POST message (from tweet data)
url=http://ideasac.localhost/api/mensajes
curl -i -X POST -H 'Content-Type: application/json' -d '{"nombre": "Nombre", "texto": "Texto", "is_tweet": "true","date": "Mon Sep 24 03:35:21 +0000 2012", "tweet_id": "250075927172759552", "tweet_username":"user" }' $url

# POST Login.php
url=http://ideasac.localhost/login.php
curl --data "email=pedro@mail.com&pass=pass" $url

# POST vote
url=http://ideasac.localhost/api/votos
curl -i -X POST -H 'Content-Type: application/json' -d '{"mid": "1", "type": "like"}' $url


