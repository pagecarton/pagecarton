server {
server_name  account.comeriver.com www.account.comeriver.com;   
access_log   /ayoola/log/s10102/account.comeriver.com.error.log;  
error_log   /ayoola/log/s10102/account.comeriver.com.error.log;              
root         /home/s10102/www/account.comeriver.com/public_html;
listen 443 ssl;
ssl_certificate /etc/letsencrypt/live/account.comeriver.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/account.comeriver.com/privkey.pem;        
location /phpmyadmin {
root /usr/share;
index index.php index.html index.htm;
location ~* ^/phpmyadmin/(.+\.(jpg|jpeg|gif|css|png|js|ico|html|xml|txt))$ {
root /usr/share;
}
}
location /phpMyAdmin {
rewrite ^/* /phpmyadmin last;
}
try_files $uri $uri/ /index.php;            
if (!-e $request_filename) {
rewrite ^.*$ /index.php last;         
}                                           
location / {
index  index.html index.htm index.php;
}
location ~ \.php$ {
include        fastcgi_params;
#fastcgi_pass   unix:/var/run/php5-fpm.s10102.sock;
fastcgi_pass   unix:/var/run/php.s10102.sock;
fastcgi_index index.php;
fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
}
client_max_body_size 200M;
}
server {
listen 80;
server_name account.comeriver.com www.account.comeriver.com;
return 301 https://$host$request_uri;
client_max_body_size 200M;
}
