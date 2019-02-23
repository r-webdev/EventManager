<?php

return [
    'stats' => [
        'microtime' => microtime(true),
    ],
    'app' => [
        'version' => '0.1.2',
        'environment' => env('APP_ENV', 'unknown'),
        'name' => [
            'short' => env('APP_NAME_SHORT', 'UNKNOWN'),
            'long' => env('APP_NAME_LONG', 'Unknown')
        ],
        'host' => [
            'protocol' => env('APP_HOST_PROTOCOL', 'http'),
            'sub' => env('APP_HOST_SUB', null),
            'domain' => env('APP_HOST_DOMAIN', 'localhost:8080'),
        ]
    ],
    'debug' => [
        'enabled' => filter_var(env('DEBUG_ENABLE', 'false'), FILTER_VALIDATE_BOOLEAN),
        'slack' => [
            'key' => env('DEBUG_SLACK_KEY', null),
            'channel' => env('DEBUG_SLACK_CHANNEL', null),
            'botname' => env('DEBUG_SLACK_BOTNAME', null)
        ]
    ],
    'database' => [
        'mysql' => [
            'hostname' => env('DB_MYSQL_HOSTNAME', null),
            'database' => env('DB_MYSQL_DATABASE', null),
            'username' => env('DB_MYSQL_USERNAME', null),
            'password' => env('DB_MYSQL_PASSWORD', null)
        ]
    ],
    'mailgun' => [
        'api' => [
            'key' => env('MAILGUN_API_KEY', null),
            'domain' => env('MAILGUN_API_DOMAIN', null)
        ],
        'defaults' => [
            'attributes' => [
                'subject' => env('MAILGUN_DEFAULTS_ATTRIBUTES_SUBJECT', null),
                'from' => env('MAILGUN_DEFAULTS_ATTRIBUTES_FROM', null)
            ],
            'recipients' => [
                'to' => explode(',', env('MAILGUN_DEFAULTS_RECIPIENTS_TO', '')),
                'cc' => explode(',', env('MAILGUN_DEFAULTS_RECIPIENTS_CC', '')),
                'bcc' => explode(',', env('MAILGUN_DEFAULTS_RECIPIENTS_BCC', ''))
            ],
            'contents' => [
                'text' => explode(',', env('MAILGUN_DEFAULTS_CONTENTS_TEXT', '')),
                'html' => explode(',', env('MAILGUN_DEFAULTS_CONTENTS_HTML', ''))
            ]
        ]
    ],
    'aws' => [
        'sqs' => [
            'credentials' => [
                'key' => env('AWS_SQS_CREDENTIALS_KEY', null),
                'secret' => env('AWS_SQS_CREDENTIALS_SECRET', null),
            ],
            'region' => env('AWS_SQS_REGION', null),
            'queues' => [
                'default' => env('AWS_SQS_QUEUES_DEFAULT', null)
            ]
        ],
        's3' => [
            'credentials' => [
                'key' => env('AWS_S3_CREDENTIALS_KEY', null),
                'secret' => env('AWS_S3_CREDENTIALS_SECRET', null),
            ],
            'region' => env('AWS_S3_REGION', null),
            'bucket' => env('AWS_S3_BUCKET', null)
        ]
    ],
    'defaults' => [
        'eloquent' => [
            'skip' => env('DEFAULTS_ELOQUENT_SKIP', 0),
            'take' => env('DEFAULTS_ELOQUENT_TAKE', 30),
        ],
        'token' => [
            'refresh' => [
                'expiration' => env('DEFAULTS_TOKEN_REFRESH_EXPIRE', 720)
            ],
            'auth' => [
                'expiration' => env('DEFAULTS_TOKEN_AUTH_EXPIRE', 4)
            ]
        ]
    ],
    'blade' => [
        'paths' => [
            'views' => env('BLADE_PATH_VIEWS', '../views'),
            'cache' => env('BLADE_PATH_CACHE', '../cache')
        ]
    ]
];