import os
import sys

from fabric.api import cd, env, lcd, local, parallel, roles, run
from fabric.context_managers import settings
from fabric.contrib.console import confirm
from fabric.decorators import runs_once

env.use_ssh_config = True

env.roledefs.update({
    'webserver': ['web1'],
})


# CONSTANTS
BUILD_IMAGE = 'ownbite/helsingborg-test'

def make_envs_command():
    envs = [
        'WORDPRESS_DB_HOST=10.0.0.41',
        'WORDPRESS_DB_USER=wordpress',
        'WORDPRESS_DB_PASSWORD=test2',
        'WORDPRESS_DB_NAME=helsingborg',
    ]
    return ' '.join(map('-e {}'.format, envs))

def make_mount_command():
    mounts = [
        '/var/www/html/wp-content/uploads:/var/www/html/wp-content/uploads',
        '/var/www/html/wp-content/cache/page_enhanced:/var/www/html/wp-content/cache/page_enhanced',
    ]
    return ' '.join(map('-v {}'.format, mounts))


# DEPLOY LOCAL
def deploylocal():
    rebuildlocal()
    restartlocal()

@runs_once
def rebuildlocal():
    with settings(warn_only=True):
        local('docker rmi -f $(docker images | grep "^<none>" | awk "{print $3}")')

    local('docker build -t {} .'.format(BUILD_IMAGE))

def restartlocal():
    with settings(warn_only=True):
        local('docker stop web')
        local('docker rm web')
    local('docker run --name web {} -p 127.0.0.1:8080:80 -d {}'.format(make_envs_command(), BUILD_IMAGE))
    local('docker exec -it web bash')


# DEPLOY
@roles('webserver')
def deploy():
    rebuild()
    pull()
    restart()

@roles('webserver')
@runs_once
def rebuild():
    local('docker build -t {} .'.format(BUILD_IMAGE))
    local('docker push {}'.format(BUILD_IMAGE))

@roles('webserver')
@parallel
def pull():
    run('sudo docker pull {}'.format(BUILD_IMAGE))

@roles('webserver')
def restart():
    with settings(warn_only=True):
        run('sudo docker stop web')
        run('sudo docker rm web')
    run('sudo docker run --name web {} {} -p 80:80 -d {}'.format(make_envs_command(), make_mount_command(), BUILD_IMAGE))
