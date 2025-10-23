<?php

namespace Deployer;

require 'recipe/composer.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/mittwald/deployer-recipes/recipes/deploy.php';

// github repo
set('repository', 'https://github.com/ECidno/ID-No.com.git');

// staging
mittwald_app('34220303-cb87-4592-8a95-2eb20a97b2ac')
    ->set('branch', 'staging')
    ->set('keep_releases', 3)
    ->set('public_path', '/html/php-id-no-staging/');

// task | deplay:done
task(
    'deploy:done',
    function () {
        #writeln('Deploy done!');
        run('composer install --optimize-autoloader --apcu-autoloader');
        run('composer dump-env staging');

        run('bin/console cache:clear');
        run('bin/console cache:warmup');

        run('yarn');
        run('yarn build');

    }
)->desc('composer');

// exec task
after('deploy', 'deploy:done');