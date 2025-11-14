#!/bin/bash

$id=$(xpra info | grep "server.id=")
echo $id
kill -SIGINT $id
