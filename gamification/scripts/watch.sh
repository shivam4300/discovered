#!/bin/bash
base_path=../repo/gamification
rm -rf ./${base_path}/assets/built
npm install
webpack --config webpack.dev.config.js --env base_path="${base_path}"
