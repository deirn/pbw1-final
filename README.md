# Running

- Add virtual host
  ```apacheconf
  # httpd-vhosts.conf
  <VirtualHost *>
  DocumentRoot "C:/xampp/htdocs/final"
  ServerName final_0013.test
      <Directory "C:/xampp/htdocs/final">
          Options FollowSymLinks
          AllowOverride All
  
          Order allow,deny
          Allow from all
      </Directory>
  </VirtualHost>
  ```
  ```
  # hosts file
  127.0.0.1       final_0013.test
  ```
- Make sure the database is running
- Install Dependencies and migrate database
  ```
  composer install
  php migration/0000_all.php
  ```
- Go to `final_0013.test`

# Libraries

[PHP dotenv](https://github.com/vlucas/phpdotenv)

# References

[How to Build a Routing System for a PHP App from Scratch](https://www.freecodecamp.org/news/how-to-build-a-routing-system-in-php/)