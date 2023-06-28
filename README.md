# Running

Add Virtual Host:

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

# Test Users
```
Username: johndoe
Password: password123
Name: John Doe

Username: emily87
Password: ilovecats
Name: Emily Johnson

Username: samsmith
Password: soccer23
Name: Sam Smith

Username: lilyjones
Password: flowerpower
Name: Lily Jones

Username: maxbrown
Password: sunshine99
Name: Max Brown

Username: sarahmiller
Password: chocolate1
Name: Sarah Miller

Username: daveharris
Password: guitarrock
Name: Dave Harris

Username: sophiewang
Password: starlight12
Name: Sophie Wang

Username: alexturner
Password: musiclover
Name: Alex Turner

Username: annasmith
Password: butterfly5
Name: Anna Smith
```

# Libraries

[PHP dotenv](https://github.com/vlucas/phpdotenv)

# References

[How to Build a Routing System for a PHP App from Scratch](https://www.freecodecamp.org/news/how-to-build-a-routing-system-in-php/)