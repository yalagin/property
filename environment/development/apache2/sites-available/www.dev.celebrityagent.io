<VirtualHost *:80>
  ServerName www.dev.celebrityagent.io

  <Location />
    Redirect permanent / https://www.dev.celebrityagent.io/
  </Location>
</VirtualHost>

<VirtualHost *:443>

  ServerName www.dev.celebrityagent.io
  DocumentRoot /var/www/celebrityagent.io/public

  ServerAdmin webmaster@celebrityagent.io

  ErrorLog ${APACHE_LOG_DIR}/www-dev-celebrityagent-io.error.log

  LogLevel notice

  CustomLog ${APACHE_LOG_DIR}/www-dev-celebrityagent-io.access.log combined

  SSLEngine on
  SSLCertificateFile /var/www/celebrityagent.io/environment/development/apache2/certificates/dev-celebrityagent-io.pem
  SSLCertificateKeyFile /var/www/celebrityagent.io/environment/development/apache2/certificates/dev-celebrityagent-io.key

  <Directory /var/www/celebrityagent.io/public>
    AllowOverride All
    Order allow,deny
    Allow from All
  </Directory>

</VirtualHost>
