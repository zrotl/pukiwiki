#!/bin/sh

USERNAME=$(whoami)
chown -R $USERNAME.$USERNAME *
chmod 777 attach backup cache counter diff log log/update htmlinsert wiki wiki.en
chmod 755 image image/face lib plugin skin
chmod 666 cache/*.dat cache/*.ref cache/*.rel skin/*.js wiki/*.txt wiki.en/*.txt
