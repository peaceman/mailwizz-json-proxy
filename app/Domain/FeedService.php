<?php
/**
 * lel since 2019-01-25
 */

namespace App\Domain;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use function \GuzzleHttp\json_decode;

class FeedService
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $config;

    /**
     * FeedService constructor.
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function fetch(string $feedName): array
    {
        $feedConfig = $this->config[$feedName];
        $feedUrl = new Uri($feedConfig['url']);

        $response = $this->httpClient->get($feedUrl, ['verify' => false]);

        $jsonData = json_decode((string)$response->getBody(), true);

        return $this->parseItems(data_get($jsonData, data_get($feedConfig, 'paths.items')), $feedConfig);
    }

    protected function parseItems(array $items, array $feedConfig): array
    {
        return collect($items)
            ->map(function (array $item) use ($feedConfig) {
                return $this->parseItem($item, $feedConfig);
            })
            ->all();
    }

    protected function parseItem(array $item, array $feedConfig): array
    {
        $result = [];

        $result = array_merge($result, $this->generateTagsFromItem($item, $feedConfig));
        $result = array_merge($result, ['content' => $this->generateContentFromItem($item, $feedConfig)]);

        return $result;
    }

    protected function generateTagsFromItem(array $item, array $feedConfig): array
    {
        $tagPaths = data_get($feedConfig, 'paths.tags');

        return collect($tagPaths)
            ->map(function ($paths) use ($item) {
                return collect($paths)
                    ->map(function ($path) use ($item) {
                        return data_get($item, $path, $path);
                    })
                    ->implode('');
            })
            ->all();
    }

    protected function generateContentFromItem(array $item, array $feedConfig): string
    {
        $data = data_get($item, $feedConfig['paths']['content']);
        $view = view($feedConfig['contentTemplate'], ['data' => $data]);

        return $view->render();
    }
}
