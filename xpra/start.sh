#!/bin/bash

xpra start --start=featherpad \
    --start-new-commands=no \
    --bind-tcp=0.0.0.0:8888 \
    --tcp-auth=password:value=YOURPASSWORD

# http://localhost:8888/?floating_menu=no

# --env=XPRA_CLIENT_CAN_SHUTDOWN=0 \

# --auth=password:value=YOURPASSWORD
