<?php

namespace App\Console\Commands\Search;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Exception;
use Illuminate\Console\Command;


class InitCommand extends Command
{
    protected $signature = 'search:init';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle(): bool
    {
        $this->initAdverts();

        return true;
    }

    private function initAdverts(): void
    {
        try {
            $this->client->indices()->delete(['index' => 'adverts']);
            $this->info('Індекс adverts успішно видалено');
        } catch (ClientResponseException $e) {
            if ($e->getCode() === 404) {
                $this->warn('Індекс adverts не існує');
            } else {
                $this->error('Помилка видалення індексу: ' . $e->getMessage());
            }
        }


        $this->client->indices()->create([
            'index' => 'adverts',
            'body' => [
                'settings' => [
                    'index' => [
                        'max_ngram_diff' => 2,
                    ],
                    'analysis' => [
                        'char_filter' => [
                            'replace' => [
                                'type' => 'mapping',
                                'mappings' => ['&=> and '],
                            ],
                        ],
                        'filter' => [
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_numbers' => true,
                            ],
                            'trigrams' => [
                                'type' => 'ngram',
                                'min_gram' => 4,
                                'max_gram' => 6,
                            ],
                        ],
                        'analyzer' => [
                            'default' => [
                                'type' => 'custom',
                                'char_filter' => ['html_strip', 'replace'],
                                'tokenizer' => 'whitespace',
                                'filter' => ['lowercase', 'word_delimiter', 'trigrams'],
                            ],
                        ],
                    ],
                ],
                'mappings' => [
                    '_source' => ['enabled' => true],
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'published_at' => ['type' => 'date'],
                        'title' => ['type' => 'text'],
                        'content' => ['type' => 'text'],
                        'price' => ['type' => 'integer'],
                        'status' => ['type' => 'keyword'],
                        'categories' => ['type' => 'integer'],
                        'regions' => ['type' => 'integer'],
                        'values' => [
                            'type' => 'nested',
                            'properties' => [
                                'attribute' => ['type' => 'integer'],
                                'value_string' => ['type' => 'keyword'],
                                'value_int' => ['type' => 'integer'],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
