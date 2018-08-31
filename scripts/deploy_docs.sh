#! /bin/bash

set -o errexit

if [ "$TRAVIS_PULL_REQUEST" != "false" -o "$TRAVIS_BRANCH" != "master" ]; then
    echo "Skipped updating gh-pages, because build is not triggered from the master branch."
    exit 0;
fi

echo "Starting to update gh-pages\n"

# config
git config --global user.email "travis@travis-ci.org"
git config --global user.name "Travis CI"

# docs
cd ./docs

# build docs
jekyll build

# clone gh-pages
git clone -b gh-pages --single-branch "https://${GITHUB_TOKEN}@github.com/${GITHUB_REPO}.git" gh-pages

# clean gh-pages
rm -rf gh-pages/*

# copy new docs
cp -Rf _site/* gh-pages

#add, commit and push files
cd gh-pages
git add -f .
git commit -m "Travis build $TRAVIS_BUILD_NUMBER"
git push -fq origin gh-pages > /dev/null

echo "Done updating gh-pages\n"
