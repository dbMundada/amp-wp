#!/bin/bash

# Exit if any command fails.
set -e

# Include useful functions.
. "$(dirname "$0")/includes.sh"

# Check that Docker is installed.
if ! command_exists "docker"; then
	echo -e $(error_message "Docker doesn't seem to be installed. Please head on over to the Docker site to download it: $(action_format "https://www.docker.com/products/docker-desktop")")
	exit 1
fi

# Check that Docker is running.
if ! docker info >/dev/null 2>&1; then
	echo -e $(error_message "Docker isn't running. Please check that you've started your Docker app, and see it in your system tray.")
	exit 1
fi

# Stop existing containers.
echo -e $(status_message "Stopping Docker containers...")
dc down --remove-orphans >/dev/null 2>&1

# Build the Docker containers.
echo -e $(status_message "Building Docker containers...")
dc build wordpress >/dev/null

# Download image updates.
echo -e $(status_message "Downloading Docker image updates...")
dc pull

# Launch the containers.
echo -e $(status_message "Starting Docker containers...")
dc up -d >/dev/null
