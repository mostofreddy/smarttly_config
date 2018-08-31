#! /bin/bash

set -o errexit

rm -rf ./docs/_site

# config
git config --global user.email "nobody@nobody.org"
git config --global user.name "Travis CI"

# build docs
jekyll build --source ./docs --destination ./docs/_site

# deploy
cd ./docs/_site
git init
git add .
git commit -m "Deploy to Github Pages"
git push --force --quiet "https://${GITHUB_TOKEN}@$github.com/${GITHUB_REPO}.git" master:gh-pages > /dev/null 2>&1
