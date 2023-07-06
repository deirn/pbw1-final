## Running Locally using XAMPP in Windows

1. Install [XAMPP](https://www.apachefriends.org/) and [Composer](https://getcomposer.org/).
2. Add virtual host, you can use different directory and/or domain,
   just make sure to set the document root to the `public` folder.

   In the example, the repo is located in `C:\xampp\htdocs\final`
   and the domain is `final_0013.test`.
   ```apacheconf
   # C:\xampp\apache\conf\extra\httpd-vhosts.conf
   <VirtualHost final_0013.test>
   DocumentRoot "C:/xampp/htdocs/final/public"
   ServerName final_0013.test
       <Directory "C:/xampp/htdocs/final/public">
           Options FollowSymLinks
           AllowOverride All
   
           Order allow,deny
           Allow from all
       </Directory>
   </VirtualHost>
   ```
   ```
   # C:\Windows\System32\drivers\etc\hosts
   127.0.0.1       final_0013.test
   ```
3. Start Apache and MySQL module on XAMPP control panel.
4. Copy `.env_example` to `.env` and modify if needed.
5. Install Dependencies and migrate database.
   ```
   composer install
   php database/migration/0000_all.php
   ```
6. Go to `final_0013.test`.

## Libraries

- [PHP dotenv](https://github.com/vlucas/phpdotenv)
- [Faker](https://github.com/FakerPHP/Faker)
- [JQuery](https://jquery.com/)
- [Bootstrap](https://getbootstrap.com/)
- [Font Awesome](https://fontawesome.com/)

## References

[How to Build a Routing System for a PHP App from Scratch](https://www.freecodecamp.org/news/how-to-build-a-routing-system-in-php/)