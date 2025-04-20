#!/usr/bin/env bash

# git hook to run a command after `git pull` if a specified file was changed
# Run `chmod +x post-merge` to make it executable then put it into `.git/hooks/`.

changed_files="$(git diff-tree -r --name-only --no-commit-id ORIG_HEAD HEAD)"

check_run() {
	echo "$changed_files" | grep --quiet "$1" && eval "$2"
}

# NPM Packages
check_run package-lock.json "npm install"

# Composer Packages
check_run composer.lock "composer install"

# Assets
check_run resources "npm run dev"
