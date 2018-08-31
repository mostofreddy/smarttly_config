#! /bin/bash

set -o errexit

rm -rf ./docs/_site

# config
git config --global user.email "nobody@nobody.org"
git config --global user.name "Travis CI"

# build docs
jekyll build --source ./docs --destination ./docs/_site
