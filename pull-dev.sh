#!/bin/sh

rsync -avz --exclude='.DS_Store' --exclude='Data' -e ssh p182917@p182917.mittwaldserver.info:/html/dev/ ./
