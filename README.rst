Mixtracks
=========

Drop-in script to provide a frontend for playing a directory of mp3 files.

Development
-----------

Available commands: ``build``, ``up``, ``down``, ``logs``, ``shell``

.. code-block:: bash

    just build
    just up

Caddy configuration
-------------------

.. code-block:: caddyfile

    mixtracks {
        root * /var/www/html/mixtracks
        tls shmup@xom.world
        php_fastcgi unix//run/php-fpm/www.sock
        log {
            output file /var/log/caddy/mixtracks.log {
                roll_size 10mb
                roll_keep 20
                roll_keep_for 720h
            }
        }
    }

If using docker you just need a reverse proxy:

.. code-block:: caddyfile

    mixtracks {
        reverse_proxy :8000
        tls shmup@smell.flowers
        log {
            output file /var/log/caddy/mixtracks.log {
                roll_size 10mb
                roll_keep 20
                roll_keep_for 720h
            }
        }
    }
