#!/bin/bash
docker run --name mongo -p 27017:27017 -v ${PWD}/mongodb/conf:/data/configdb -v ${PWD}/mongodb/data:/data/db -d mongo