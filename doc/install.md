
#### nginx

nginx.conf
```
 server {
    listen 80;
    server_name _;
    root /path-to-wxopen;
    index index.php;
    location ~ index\.php(/|$) {
      #fastcgi_pass remote_php_ip:9000;
      fastcgi_pass unix:/dev/shm/php-cgi.sock;
      fastcgi_index index.php;
      include fastcgi.conf;
    }
 }
```

