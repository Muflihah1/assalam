#!/usr/bin/env bash
# Helper script to run artisan commands inside Docker container
docker compose exec app php artisan "$@"
