<!DOCTYPE html>
    <html>
    </head>
    <body>
        <?php
            $path = "/var/www/html/PPIL-hivemind";

            echo shell_exec("cd {$path} && /usr/bin/git pull 2>&1");
        ?>
    </body>
    </html>