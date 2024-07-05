Mixtracks
=========

Drop-in script to provide a frontend for playing a directory of mp3 files.


Caddy configuration
-------------------

.. code-block:: caddyfile

    mixtracks {
        root * /var/www/html/mixtracks
        encode gzip
        tls shmup@xom.world
        php_fastcgi unix//run/php-fpm/www.sock
        file_server
        log {
            output file /var/log/caddy/mixtracks.log {
                roll_size 10mb
                roll_keep 20
                roll_keep_for 720h
            }
        }
    }
