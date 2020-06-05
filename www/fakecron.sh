#!/usr/bin/env bash

while [ true ]
do
	curl http://localhost:8888/cron
	sleep 60
done