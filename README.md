# oxid_webp
Webp support for oxid - image generation on the fly 

1. Install cwebp encoder from https://developers.google.com/speed/webp/docs/cwebp

2. add the following rules before org. getimg.php rule

```bash
    RewriteCond %{HTTP_ACCEPT} image/webp 
    RewriteCond %{REQUEST_FILENAME} (.*)\.(jpe?g|png)$ 
    RewriteCond %1\.png -f [or]
    RewriteCond %1\.jpg -f [or]
    RewriteCond %1\.jpeg -f
    RewriteCond %1\.webp !-f
    RewriteRule (\.jpe?g|\.gif|\.png|\.svg)$ getWebp.php [NC]

    RewriteCond %{HTTP_ACCEPT} image/webp 
    RewriteCond %{REQUEST_FILENAME} (.*)\.(jpe?g|png)$ 
    RewriteCond %1\.webp -f 
    RewriteRule ^(.*)\.(jpg|png)$ $1.webp [L,T=image/webp]
    
    ##org starts here
    RewriteCond %{REQUEST_URI} (\/out\/pictures\/generated\/)
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule (\.jpe?g|\.gif|\.png|\.svg)$ getimg.php [NC]
```

3. put getWebp.php into source dir. check encoder path at the begin of file:
   
   ```php
   define("CWEBP", "/usr/bin/cwebp");
   ```
4. inspect tail -f source/log/getWebp.log for errors, i had not right to generate pics on certain places

5. optional: use convert.sh to convert all images, or just wait for it to happen... 


Feedback/pull requests welcome
