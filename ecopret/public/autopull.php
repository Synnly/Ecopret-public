<!DOCTYPE html>
    <html>
    </head>
    <body>
        <?php
            $path = "/var/www/html/PPIL-hivemind";

            echo shell_exec("git stash && git stash clear && cd {$path} && /usr/bin/git pull 2>&1");
        ?>
    </body>
    </html>
