<?php
/**
 * lel since 2019-01-25
 */

return [
    'foo' => [
        'url' => 'https://www.foobar.com',
        'paths' => [
            // Item location in the input feed
            'items' => '*',
            // Tag location per item
            'tags' => [
                'guid' => 'ID',
                'title' => ['Game', ' ', 'Merchant', ' ', 'Name', ' ', 'LastName', ' ', 'Date'],
                'description' => 'Description',
                'image' => ['https://foobar.com', 'Image'],
                'link' => '',
                'pubDate' => 'Date',
            ],
            // Content location per item (will be supplied as template vars)
            'content' => null,
        ],
        'contentTemplate' => 'foo',
    ]
];
