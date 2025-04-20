# Server Setup

## Helpful Links
Laravel Forge (Management): https://forge.laravel.com/
Digital Ocean (Host): https://cloud.digitalocean.com/login

## Adding SSH Keys (Globally To All Servers)
On the Forge profile [SSH Keys](https://forge.laravel.com/user/profile) page. You can add keys to each individual server in servers management SSH Key section.
1. Add your named SSH key(s)

## Server Creation (General)
On the the Forge [Create Server](https://forge.laravel.com/servers) page.

1. Choose Digital Ocean as the server host
1. Choose Firm Exchange as the credentials
1. Give the server a readable environment name prefiexed with **fe-** and suffixed with the server number if adding to a load balancer
1. Choose the suitable/agreed on size. 1GB ($10) is usually sufficent for testing environments. For other types of servers consult their environment specific setup details.
1. Choose the latest stable version of PHP or match the version of PHP if setting up an application server.
1. Choose the latest stable version of MySQL.
1. Set the database name to **firmexchange** for testing and application servers
1. Create server
1. Store server name, IP Address, sudo password and database password in the sites document. This will also be emailed to the account owner once the server is fully setup.

## Server Configuration (General)
In the new servers management section

### Removing The Default Site
1. Click on the **default** domain in the list of active sites.
1. Click on the **X** in the lower right hand corner and accept the confirmation prompt

### Sites Section
- To add a site use the full URL with out the protocol (http/https) as the Root Domain, Project Type as General PHP/Laravel and Web Directory as public

### PHP Section
- Set the **Max File Upload Size** to 100

### Scheduler Section
- Add a new command `php /home/forge/YOUR-SITE-DOMAIN/artisan schedule:run` to run every minute as the user **forge**.

### Daemons Section
- Add a new command `php artisan horizon` with the user **forge** and directory `/home/forge/YOUR-SITE-DOMAIN`.
- Add a new command `php artisan queue:listen` with the user **forge** and directory `/home/forge/YOUR-SITE-DOMAIN`.
- **If not using a central cache server.** Add a new command `redis-server --port 3001` with the user **forge** and no directory.

### Accessing The Server Via SSH
- `ssh forge@SERVER-IP-ADDRESS` You will need to make sure your SSH key is added to the server see **Adding SSH Keys (Globally To All Servers)** above

### Accessing The Server Via SFTP
- TODO: outline

### Accessing The Database Via Externally
- TODO: outline

### Installing imagemagick
- On the server execute `sudo apt-get update && sudo apt-get install -y imagemagick php-imagick && sudo service php7.*-fpm restart`

### Setting Up Basic Authentication (Testing Servers)
- More extensive documentation can be found [here](https://www.digitalocean.com/community/tutorials/how-to-set-up-password-authentication-with-apache-on-ubuntu-16-04)
- Everything below should be completed on via SSH on the server you will need the server sudo password handy.
    1. `sudo apt-get update`
    1. `sudo apt-get install apache2-utils`
    1. `sudo htpasswd -c /etc/nginx/.htpasswd demo`
        - You will need to use the general basic authentication password. Ask if you are not sure what that is.
    1. `cat /etc/nginx/.htpasswd` to make sure the file was created and the user added
- Everything below should be completed in the Forge Server Panel under each Site
    1. In the bottom right click on the **Edit Files** button and select **Edit Nginx Configuration**
    2. Add the following to the end of the `location /` block
    ```
    auth_basic "Restricted Access";
    auth_basic_user_file /etc/nginx/.htpasswd;
    ```

## Adding Testing Server
- Follow the **Server Creation (General)** and **Server Configuration (General)**

## Site Configuration (General)

## Apps Section
- Set the **Deploy Script** (Update php7.1-fpm to the server PHP version)
```
cd /home/forge/YOUR-SITE-DOMAIN
git pull origin develop
composer install --no-interaction --prefer-dist --optimize-autoloader
echo "" | sudo -S service php-fpm reload

npm install --no-save
npm run production

if [ -f artisan ]
then
    php artisan migrate --force
    php artisan view:clear
    php artisan horizon:terminate
fi
```
- Set **Deployment Branch** to `develop` for testing servers and `master` for production servers.

### Environment Section
1. Copy the contents of `.env.example`
1. Set the **at minimum** following values
    - `APP_ENV` to the enviroment `development` for testing servers and  `production` for production servers.
    - `APP_KEY` to a generated key for testing servers and the shared application key for production servers
        - To get a new key you can execute `php artisan key:generate --show` in your local install directory to get a key to paste in or `php artisan key:generate` on the server to add it to the `.env` automatically.
    - `APP_DEBUG` to `true` for testing servers and  `false` for production servers.
    - `APP_URL` to YOUR-SITE-DOMAIN
    - `DB_PASSWORD` to the auto generated database password.
    - `CACHE_DRIVER` to redis
    - `QUEUE_DRIVER` to redis
    - Mail (Testing)
        - `MAIL_HOST` to `smtp.mailtrap.io`
        - `MAIL_USERNAME` to the [Mailtrap](https://mailtrap.io/) username.
        - `MAIL_PASSWORD` to the [Mailtrap](https://mailtrap.io/) password.
    - Mail (Production)
        - TODO: outline
    - `AUTHY_SECRET` to the Authy API secret
    - `STRIPE_KEY`  to the Stripe API account key
    - `STRIPE_SECRET`  to the Stripe API secret
    - `BLOG_URL` to http://mdgfeb.staging.wpengine.com for testing servers and  https://blog.firmexchange.com/  for production servers
    - `ALGOLIA_APP_ID` to the Algolia API account id
    - `ALGOLIA_SECRET` to the Algolia API secrect
    - `SCOUT_QUEUE` to `true`

### SSL Section
- Use LetsEncrypt SSL for testing servers
- Supplied FirmExchange SSL for public facing production servers should only be needed for load balancers. You should be able use the **Clone Certificate Option**.

### Linking Storage Directory
- On the server
1. `cd /home/forge/YOUR-SITE-DOMAIN`
1. `php artisan storage:link`
## Setting Up Load Balancer
- TODO: outline

## Adding Application Server
- TODO: outline

### Setting Up Cache Server
- TODO: outline

### Setting Up Database Server
- TODO: outline
