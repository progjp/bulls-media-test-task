<?php

return [
    'credentials_path' => storage_path('app/google-credentials.json'),
    'sheets' => [
        [
            'id' => '1AP2vIb6tPqVl62gn_MrOoKV2cYb6_0ymTbyHSfvkTRo',
            'tabs' => [
                [
                    'name' => 'SalesData',
                    'db_table' => 'sales',
                    'fields' => [
                        'date' => [
                            'db_column' => 'date',
                            'db_type' => 'datetime',
                            'date_format' => 'd.m.Y',
                            'additional_modifiers' => [
                                'nullable'
                            ],
                        ],
                        'product_name' => [
                            'db_column' => 'name',
                            'db_type' => 'string',
                        ],
                        'price' => [
                            'db_column' => 'price',
                            'db_type' => 'decimal',
                            'precision' => '10',
                        ],
                        'amount' => [
                            'db_column' => 'amount',
                            'db_type' => 'decimal',
                            'precision' => '10'
                        ]
                    ]
                ]
            ],
        ],
        [
            'id' => '1AP2vIb6tPqVl62gn_MrOoKV2cYb6_0ymTbyHSfvkTRo',
            'tabs' => [
                [
                    'name' => 'SalesData',
                    'db_table' => 'templates',
                    'fields' => [
                        'date' => [
                            'db_column' => 'date',
                            'db_type' => 'datetime',
                            'date_format' => 'd.m.Y',
                            'additional_modifiers' => [
                                'nullable'
                            ],
                        ],
                        'product_name' => [
                            'db_column' => 'name',
                            'db_type' => 'string',
                        ],
                        'price' => [
                            'db_column' => 'price',
                            'db_type' => 'decimal',
                            'precision' => '10',
                        ],
                        'amount' => [
                            'db_column' => 'amount',
                            'db_type' => 'decimal',
                            'precision' => '10'
                        ]
                    ]
                ]
            ],
        ],
    ]
];
