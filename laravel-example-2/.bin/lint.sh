#!/bin/sh
PATH=$PATH:/usr/local/bin


if hash nvm 2>/dev/null
then
    export NVM_DIR=~/.nvm
    source ~/.nvm/nvm.sh
    nvm use 10
fi

if hash fnm 2>/dev/null
then
    export PATH=~/.fnm:$PATH
    eval "`fnm env --multi`"
    fnm use 10
fi

# PHPCS
echo "Running PHPCS..."
./vendor/bin/phpcs ./app/**/*.php ./bootstrap/helpers.php --standard=ruleset.xml
if [ $? != 0 ]
then
    echo "PHPCS errors found!"
    exit 1
fi

# Stlyelint
echo "Running stylelint..."
node ./node_modules/stylelint/bin/stylelint.js ./resources/**/*.css --config .stylelintrc.js
if [[ "$?" != 0 ]]; then
    echo "stylelint errors found!"
    exit 1
fi

# eslint
echo "Linting JS with eslint..."
node ./node_modules/eslint/bin/eslint.js -c .eslintrc.js ./resources/js
if [[ "$?" != 0 ]]; then
    echo "ESLint errors found!"
    exit 1
fi

echo  "\nNo lint errors found!\n"
