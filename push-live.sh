#!/bin/sh

rsync -avz --exclude=".git" ./ -e ssh p182917@p182917.mittwaldserver.info:/html/adu