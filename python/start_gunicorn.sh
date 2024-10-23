#!/bin/bash
source /path/to/your/venv/bin/activate
exec gunicorn --config /var/www/html/ami/python/gunicorn_config.py apiColpensiones:app

