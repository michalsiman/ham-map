#!/bin/sh
set -e

# cron jobs don't inherit `docker run`/compose environment variables by
# default, so we dump the current environment (as provided via docker-compose)
# into /etc/environment where cron picks it up for every job it runs.
printenv | grep -v "no_proxy" > /etc/environment

exec cron -f
