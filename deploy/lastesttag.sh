#!/bin/sh
# 
# lastesttag.sh
#
# author: José Francisco <silvaivctd@gmail.com)
# date: 2016/10/31
#
# Get latest tag in repositorio

# Usage message
usage()
{
  echo "Usage: $0"
  exit 1
}

# Get new tags from remote
cd ../

git fetch --tags

# Get latest tag name
latestTag=$(git describe --tags `git rev-list --tags --max-count=1`)

# Checkout latest tag
git checkout -b $latestTag