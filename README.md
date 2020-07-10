
# Celebrity Agent Platform
This is the public portal and associated back office for the Celebrity Agent platform.

## Prerequisites
* Debian or Ubuntu Linux
* Apache 2.4+
* PHP 7.3+
* NodeJS (with npm and yarn installed globally)
* Composer
* Samba (optional)

## Deploying Locally for Development
As root, make space for the code:

    cd /var/www
    mkdir celebrityagent.io
    chmod youruser:youruser celebrityagent.io

If you are working in a virtual environment (e.g. a Linux virtual machine in a Windows- or Mac-based host) you likely want to share your files out to the host machine, which can be done with Samba. Edit the Samba configuration to share out that directory (you should have already configured Samba with user-level security and created the proper user account for access):

    vi /etc/samba/smb.conf

At the bottom of the file, add a share definition like:

    [CelebrityAgent]
      comment = Celebrity Agent
      path = /var/www/celebrityagent.io
      create mask = 0644
      directory mask = 0755
      writeable = yes

Restart Samba:

    /etc/init.d/samba restart

Return to your user account and check out the platform (for this to work you should have already added your public key to GitHub):

    cd /var/www/celebrityagent.io
    git clone git@github.com:celebrityagent/celebrityagent.git

Return to root and link the available sites:

    cd /etc/apache2/sites-available
    ln -s /var/www/celebrityagent.io/environment/apache2/development/sites-available/backoffice.dev.celebrityagent.io backoffice.dev.celebrityagent.io
    ln -s /var/www/celebrityagent.io/environment/apache2/development/sites-available/www.dev.celebrityagent.io www.dev.celebrityagent.io

Enable them:

    cd /etc/apache2/sites-enabled
    ln -s ../sites-available/backoffice.dev.celebrityagent.io 001-backoffice.dev.celebrityagent.io
    ln -s ../sites-available/www.dev.celebrityagent.io 002-www.dev.celebrityagent.io

Restart Apache:

    /etc/init.d/apache2 restart

Return to your user account and deploy the platform:

    cd /var/www/celebrityagent.io
    composer install
    yarn install

To run Webpack Encore such that it watches your file system for changes, open a shell and leave this running:

    yarn encore dev --watch

You will need to update your OS local hosts file to point the domains to your development machine. Assuming that your site is running on a machine at `192.168.186.128`, this might look like:

    192.168.186.128     backoffice.dev.celebrityagent.io
    192.168.186.128     www.dev.celebrityagent.io

In order to prevent a browser security warning for the dev sites, you will need to import the certificate at `/var/www/celebrityagent.io/environment/development/apache2/certificates/dev-celebrityagent-io.pem` into your browser as a trusted CA root certificate.

## Running Tests
You must have a test database configured, usually `celebrityagent_test`, and specified in the `phpunit.xml` file.  Begin with the defaults and then modify to suit your needs:

    cp phpunit.xml.dist phpunit.xml

The Symfony PHPUnit bridge handles the installation of PHPUnit locally, so the first time that this is run the PHPUnit dependencies will be installed via Composer.  To run the tests from the project directory, just type:

    ./phpunit

To run all tests from a single test class, use the `--filter` option with the class name:

    ./phpunit --filter DashboardControllerTest

And to run a specific test, use the `--filter` option with both the class and test names:

    ./phpunit --filter DashboardControllerTest::testIndexSuccess

