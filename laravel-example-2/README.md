# Fanatics VMS

**Important:** If something needs updating please update it

## Requirements

- [A Mac or other *nix](https://www.apple.com/)
- [Valet](https://laravel.com/docs/5.8/valet#installation)
- [Homebrew](https://brew.sh/)
- [NPM (or Yarn)](https://www.npmjs.com/)
- [Composer](https://getcomposer.org/)
- [Laravel Nova License](https://nova.laravel.com)
- PHP 7.3+
```bash
brew install php@7.3
valet stop
# You will need to unlink all php versions you have installed
brew unlink php@7.2 #php@7.0 #php@7.1 (you can get this in terminal from `brew list` anything php@x.x should be added)
brew link --force --overwrite php@7.3
brew services start php@7.3
composer global update
valet install
```
- PostgreSQL 11
```bash
# Install via Homebrew
brew install postgresql && brew services start postgresql
psql postgres
CREATE USER postgres SUPERUSER;
# If you see "ERROR:  role "postgres" already exists" you can skip CREATE DATABASE
CREATE DATABASE postgres WITH OWNER postgres;
\q # Exist psql

# Default Connection Details
# HOST: 127.0.0.1
# PORT: 5432
# USERNAME: postgres
# PASSWORD:

# If you have issues you can uninstall via Homebrew and use DBngin https://dbngin.com/
brew services stop postgresql && brew uninstall postgresql
```
- Redis `brew install redis && brew services start redis`
- [Image Optimization Tools - Used for Spatie Media Library](https://docs.spatie.be/laravel-medialibrary/v7/installation-setup#optimization-tools)

## Setup

1. `cp .env.example .env`
1. `php artisan key:generate`
1. `composer install` **You will need the Laravel Nova login credentials if this is your first install.**
1. `npm install`
1. `npm run dev`
1. Create database named **fanatics_vms**
1. Add you dev admin account to `database/seeds/LocalAccountSeeder.php`
```php
factory(User::class)->create([
    'name' => 'John Doe',
    'email' => 'jdoe@matchboxdesigngroup.com',
    'password' => bcrypt('password'),
]);
```
1. `php artisan migrate --seed`

## PHPUnit Setup - [docs](https://laravel.com/docs/testing#creating-and-running-tests)

1. `cp .env.testing.example .env.testing`
1. Create database named **fanatics_vms_testing**
1. Run test suite `composer test`

## Laravel Dusk Setup - [docs](https://laravel.com/docs/dusk#getting-started)

1. `cp .env.dusk.example .env.dusk`
1. Create database named **fanatics_vms_dusk**
1. `php artisan dusk` To run all tests via command line.
  - `php artisan dusk:dashboard` To run all tests in the [Dusk Dashboard](https://github.com/beyondcode/dusk-dashboard)

## Frameworks

[Laravel](https://laravel.com/docs/)
[Vue.js](https://vuejs.org/v2/guide/)

## Services

- [Laravel Forge](https://forge.laravel.com/) - Server Management
- [Laravel Envoyer](https://envoyer.io/) - Deployments
- [Pusher](https://pusher.com/) - Websockets
- [Digital Ocean](https://www.digitalocean.com/) - Hosting
- TBD [Amazon S3](https://aws.amazon.com/s3/) or [Digital Ocean Spacers](https://www.digitalocean.com/products/spaces/) - Media Storage
- TBD [Codeship](http://codeship.com/), [CircleCI](https://circleci.com/) or [Chipper CI](https://chipperci.com/) - Automated test runner
- TBD [Bug Snag](https://www.bugsnag.com/) or [Rollbar](https://rollbar.com/) - Bug monitoring
- [New Relic](https://newrelic.com/) - Application Profiling

## JS Package Documentation

- [Collect.js](https://github.com/ecrmnn/collect.js) - Laravel Collections in JS
- [Vuex](https://vuex.vuejs.org/) - Global state management.

## PHP Package Documentation

This is not everything mainly the more complex packages.

- [Spatie Media Library](https://docs.spatie.be/laravel-medialibrary/v7/introduction)
- [Spatie Laravel Permission](https://github.com/spatie/laravel-permission)
- [Spatie Laravel Event Projector](https://docs.spatie.be/laravel-event-projector/v2/introduction)
- [Laravel Horizon](https://laravel.com/docs/horizon)
- [Laravel Telescope](http://laravel.com/docs/telescope)
- [Pipe Dream - Laravel](https://github.com/pipe-dream/laravel)
- [Laravel Nova](https://nova.laravel.com/)

## General

- Add all custom configuration to `config/fanatics-vms.php` unless it has a better configuration file.
- **All** PHP code should include PHPUnit tests
- **As much as possible** all front end code should include Laravel Dusk tests.
- **When possible** use Laravel framework tools and conventions. If not try to find the most widely used community tool.
- **When possible** use Vue.js tools and conventions. If not try to find the most widely used community tool.
- [Tailwind](https://tailwindcss.com/) used for styling.
- As of now we are ditching Sass in favor of PostCSS since it's Tailwind's recommended approach
  - To import files use `@import ./path/to/file.css` [example](https://tailwindcss.com/docs/using-with-preprocessors/#build-time-imports).
  - For variables we can leverage CSS variables aka CSS custom properties [example](https://tailwindcss.com/docs/using-with-preprocessors/#variables).
  - For nesting it should work much like Sass with using the PostCSS Nested plugin [example](https://github.com/postcss/postcss-nested#postcss-nested-).
- New package choices should be reviewed by a senior developer or lead.

## Code Guidelines

- For all CSS styles use Tailwind classes before custom selectors. It is preferred to keep the selectors in the HTML when possible. If not then move all possible selectors into the stylesheet.
- For all PHP and Laravel we should follow PSR-1/PSR-2 PHPCS is used to help automatically enforce this. Along with the [Spatie PHP guidelines](https://guidelines.spatie.be/code-style/laravel-php) with a few exceptions/additions below.
  - **Change** Parameter types are encouraged but not mandatory.
  - **Change** All `.blade.php` files should be kebab case not camel case. `my-view.blade.php` instead of `myView.blade.php`.
  - All
- For all Vue.js and JavaScript it should follow the [Vue.js guidelines](https://vuejs.org/v2/style-guide/) Priority A, B and C. We use ESLint to help with conforming to these guidelines automatically.
  - All Vue properties should be prefixed with data when they are going to change internally to simplify naming.
  - All global component communitcation should be handled using [Vuex](https://vuex.vuejs.org/). **Only** parent/child communication should be handled via events.
  - [ES6+ Learing Guide](https://babeljs.io/docs/en/learn)
