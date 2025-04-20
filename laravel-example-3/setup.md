## Required tools
- [Composer](https://getcomposer.org/doc/00-intro.md#globally)
    - [Download Link](https://getcomposer.org/download/)
    - Verify that everything works via `$ composer` you should see a list of available commands.
- [Homebrew](https://brew.sh/)
- [Node/NPM](https://nodejs.org/en/)
- [Optional: Yarn](https://yarnpkg.com/en/docs/install)
    - Adds functionality to NPM such as a lockfile.
- [Virtual Box](https://www.virtualbox.org/wiki/Downloads)
- [Vagrant](https://www.vagrantup.com/downloads.html)
- [Vagrant Hosts Updater](https://github.com/cogitatio/vagrant-hostsupdater)
  - Handles updating your hosts file automagically.
- [Optional: Vagrant Manager](http://vagrantmanager.com/)
  - Menu bar item that makes control the Vagrant box easier.

## Helpful Links
- [Laravel Spark Documentation](https://spark.laravel.com/docs/)
- [Laravel Documentation](https://laravel.com/docs/)
- [Laracasts](https://laracasts.com/)

## General Information
- Install path is `~/Code/firmexchange.com` if yours is different it will need to be updated where ever used.
- All terminal commands are prefixed with `$ ` and must be run in the project root `~/Code/firmexchange.com` unless otherwise stated.
- If there is anything that you find is incorrect or incomplete please update the setup guide to help future developers.
- Ignore all tildes if not viewing as raw Markdown
- If things seem to be missing run `$ composer install && npm install && npm run dev` it may be a good idea to run this from time to time after pulling down from Git.
- To get the latest database columns
    - `$ vagrant ssh`
    - `$ cd ~/Code/firmexchange.com`
    - $ php artisan migrate`
- Styles are located at `/resources/assets/scss`
- JS is located at `/resources/assets/js`
- Marketing site views are located at `/resources/views`

## Git Setup
- You must be added to the Firm Exchange Organization
- Your SSH Key must be added to your Github account [Documentation](https://help.github.com/articles/adding-a-new-ssh-key-to-your-github-account/).

## Local PHP Setup
- `$ php -v` If your PHP version is 7.* you are good to go.
    - If not you can install it via `$ brew install php71` **PHP 7.1 was the latest when this document you may want a different version now**
- `$ brew install php71-imagick` **php71 should correspond with your PHP version so php71 === PHP 7.1 if yours is different you will need to alter it.**

## Application setup
1. `$ cd ~/Code/firmexchange.com`
1. copy `.env.example` to `.env`
    - `$ cp .env.example .env`
1. `$ composer install`
1. `$ php artisan key:generate`
1. Add your email address to the `$developers` array in `app/Providers/SparkServiceProvider.php` this will allow access to the developer kiosk and super admin area.

## Application webpack setup (CSS/JS/Other Assets)
1. `$ npm install`
2. `$ npm run dev`

## Homestead Setup (Development Server)
- [Documentation (Per Project Installation)](https://laravel.com/docs/homestead)
1. `$ vagrant box add laravel/homestead`
    - **Select 2) virtualbox**
1. `$ php vendor/bin/homestead make`
1. In `Homestead.yaml` under the `sites` map change .app to .test
1. `$ vagrant up`
1. Add `192.168.10.10 firmexchange.test` to your your hosts file if you are not using the Vagrant Hosts updater plugin.
1. You can now access the site at [firmexchange.test](http://firmexchange.test)

## Homestead Environment Setup
1. `$ vagrant ssh`
1. `$ cd ~/Code/firmexchange-com` **Do not need to replace with your directory.**
1. `$ php artisan migrate --seed`.
    - You can use `$ php artisan db:seed` in the future to replace the test data.
1. `$ php artisan storage:link`
1. `$ exit`

## PHPUnit setup
1. `$ vendor/bin/phpunit` runs the tests

## PHPUnit (Laravel Dusk/Acceptance)
1. `$ touch database/testing-phpunit-dusk.sqlite`
1. `$ php artisan migrate --database=phpunit-dusk`
1. `$ cp .env.dusk.example .env.dusk`
    1. Set `AUTHY_SECRET`
    1. Set `STRIPE_KEY`
    1. Set `STRIPE_SECRET`
1. `$ php artisan dusk` runs the tests

## Install Supervisor (Required for Redis/Queue)
1. `$ sudo apt-get install supervisor`
1. `$ sudo service supervisor restart`

## Redis (Optional)
1. Set `CACHE_DRIVER=redis` in your `.env`
1. `$ vagrant ssh`
1. `$ sudo nano /etc/supervisor/conf.d/redis.conf`
1. Add to `redis.conf`

    ```
[program:redis]
command=redis-server --port 3001
autostart=true
autorestart=true
stderr_logfile=/var/log/redis.err.log
stdout_logfile=/var/log/redis.out.log
    ```
1. `ctrl + x` to exit nano
1. `sudo supervisorctl reread`
1.  `sudo supervisorctl update`

## Queue (Optional if queue is not set to sync)
1. `$ vagrant ssh`
1. `$ sudo nano /etc/supervisor/conf.d/queue-listen.conf`
1. Add to `queue-listen.conf`

    ```
[program:queue-listen]
command=php /home/vagrant/Code/firmexchange-com/artisan queue:listen
autostart=true
autorestart=true
stderr_logfile=/var/log/queue-listen.err.log
stdout_logfile=/var/log/queue-listen.out.log
    ```
1. `ctrl + x` to exit nano
1. `sudo supervisorctl reread`
1.  `sudo supervisorctl update`

## Services Setup
- Stripe requires a key and secret in your `.env`
- Authy requires a key in your `.env`
    - "Auth Token" on the Authy dashboad

### Miscellaneous `.env` Setup
- Set `BLOG_URL` to your blog URL by default it is `http://blog.firmexchange.test`.

**Compiling Assets (via NPM & webpack)**
- `$ npm run dev` **If you need to just compile everything**
- `$ npm run watch` **Watches files and compiles**
- `$ npm run hot` **Watches files, compiles and hot module reloading**
- `$ npm run production` **Compiles and minifies all assets**

## Homestead Usage
- `$ vagrant up` Installs the box if not already installed or turns it on
- `$ vagrant halt` Turns off the box.
- `$ vagrant ssh` SSH on to the box.
- `$ vagrant destroy` Destroys the box
- `$ exit` to logout of the box

## Try to update plugins if Homestead box has an issue.
1. `vagrant plugin repair`

## Rebuilding Homestead box if there is an issue not fixed by plugin repair.
1. `$ vagrant destroy`
1. `$ vagrant up`
1. `$ vagrant ssh`
1. `$ cd ~/Code/firmexchange-com` **Do not need to replace with your directory.**
1. `$ php artisan migrate`.
1. `$ php artisan db:seed --class=BusinessCategoriesSeeder`
1. `$ php artisan storage:link`
1. `$ exit`
