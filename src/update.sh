#!/usr/bin/env sh

CURRENT=$(cat $1)

NEXTVERSION=$(echo ${CURRENT} | awk -F. -v OFS=. '{$NF += 1 ; print}')

echo $NEXTVERSION > VERSION

echo Updating version $CURRENT to $NEXTVERSION
