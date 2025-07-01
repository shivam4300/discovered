#!/bin/bash
base_path=../repo/gamification
rm -rf ./${base_path}/assets/built
npm ci
webpack --config webpack.prod.config.js --env base_path="${base_path}"
