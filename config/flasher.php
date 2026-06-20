<?php

declare(strict_types=1);

use Flasher\Prime\Configuration;

return Configuration::from([
    'default' => 'flasher',

    'main_script' => '/vendor/flasher/flasher.min.js',

    'styles' => [
        '/vendor/flasher/flasher.min.css',
    ],

    'options' => [
        // 'timeout' => 5000,
        // 'position' => 'top-right',
    ],

    'inject_assets' => true,

    'translate' => true,

    'excluded_paths' => [],

    'flash_bag' => [
        'success' => ['success'],
        'error' => ['error', 'danger'],
        'warning' => ['warning', 'alarm'],
        'info' => ['info', 'notice', 'alert'],
    ],

    'filter' => [
        // 'limit' => 5,
    ],

    'presets' => [
        // 'entity_saved' => [
        //     'type' => 'success',
        //     'title' => 'Entity saved',
        //     'message' => 'Entity saved successfully',
        // ],
    ],
]);
