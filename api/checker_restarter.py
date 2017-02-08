import os
import requests
import subprocess
import sys

# import settings:
try:
    dir_path = os.path.dirname(os.path.realpath(__file__))
except:
    dir_path = os.path.realpath('.')
sys.path.insert(0, dir_path)

import settings


def restart_service(name):
    command = ['/usr/sbin/service', name, 'restart']
    # shell=FALSE for sudo to work.
    subprocess.call(command, shell=False)

r = requests.get(settings.SERVER_NAME + "votes?id=eq.1")
if r.status_code > 299:
    restart_service(settings.API_SERVICE)
    requests.get(settings.NOTIFY_WEBHOOK)
