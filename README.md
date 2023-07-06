## Running

1. Add virtual host, you can use different directory and/or domain
   ```apacheconf
   # httpd-vhosts.conf
   <VirtualHost final_0013.test>
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
2. Make sure the database service is running
3. Copy `.env_example` to `.env` and populate it
4. Install Dependencies and migrate database
   ```
   composer install
   php migration/0000_all.php
   ```
5. Go to `final_0013.test`

## Libraries

- [PHP dotenv](https://github.com/vlucas/phpdotenv)
- [Faker](https://github.com/FakerPHP/Faker)
- [JQuery](https://jquery.com/)
- [Bootstrap](https://getbootstrap.com/)
- [Font Awesome](https://fontawesome.com/)

## References

[How to Build a Routing System for a PHP App from Scratch](https://www.freecodecamp.org/news/how-to-build-a-routing-system-in-php/)