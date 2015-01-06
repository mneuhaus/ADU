#!/bin/sh

rsync -avz --exclude='.DS_Store' --exclude='Data' --exclude='.git' -e ssh p182917@p182917.mittwaldserver.info:/html/dev/ ./
