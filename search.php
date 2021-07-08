<?php
$searchString = $argv[1];

if (strlen($searchString) < 2) {
    echo json_encode([
        'items' => [
            [
                'title' => 'Search string must be two characters long ..',
                'valid' => false
            ]
        ]
    ]);

    return;
}

$apiKey = getenv('NOTION_API_KEY');

if (empty($apiKey))
{
    echo json_encode([
        'items' => [
            [
                'title' => 'No API token configured.',
                'valid' => false
            ]
        ]
    ]);

    return;
}

$useDesktop = strtolower(getenv('useDesktop')) == 'true' || getenv('useDesktop') == 1;

$body = [
	'query' => $searchString,
	'sort'  => [
		'direction' => 'ascending',
		'timestamp' => 'last_edited_time'
	],
    'page_size' => 15
];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.notion.com/v1/search',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CUSTOMREQUEST  => 'POST',
  CURLOPT_POSTFIELDS     => json_encode($body),
  CURLOPT_HTTPHEADER     => [
    'Notion-Version: 2021-05-13',
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
  ],
));

$response = curl_exec($curl);

curl_close($curl);

$results = json_decode($response);

if (!property_exists($results, 'results') || empty($results->results))
{
    echo json_encode([
        'items' => [
            [
                'title' => 'No results found ..',
                'valid' => false
            ]
        ]
    ]);

    return;
}

$items = [];
foreach ($results->results as $result)
{
    $id = str_replace('-', '', $result->id);
    $url = sprintf('%s://www.notion.so/%s', $useDesktop ? 'notion' : 'https', $id);

    $title = sprintf('%s (%s)', $result->id, $result->object);

    if ($result->object == 'page') 
    {
        if (isset($result->properties->Name->title[0]->plain_text)) {
            $title = $result->properties->Name->title[0]->plain_text;
        } elseif (isset($result->properties->{"﻿Name"}->title[0]->plain_text)) {
            $title = $result->properties->{"﻿Name"}->title[0]->plain_text;
        }
    }
    elseif ($result->object == 'database')
    {
        if (isset($result->title[0]->plain_text)) {
            $title = $result->title[0]->plain_text;
        }
    }

    if (!empty($title)) {
        $items[] = [
            'title' => $title,
            'arg'   => $url,
        ];
    }
}

echo json_encode(['items' => $items], JSON_PRETTY_PRINT);
