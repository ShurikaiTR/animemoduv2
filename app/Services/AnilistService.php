<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AnilistService
{
  protected string $baseUrl = 'https://graphql.anilist.co';

  /**
   * Search for anime on AniList.
   */
  public function search(string $query): array
  {
    if (strlen($query) < 2) {
      return [];
    }

    $cacheKey = 'anilist_search_' . md5($query);

    return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($query) {
      $queryGraphql = <<<'GRAPHQL'
                query ($search: String) {
                  Page(page: 1, perPage: 5) {
                    media(search: $search, type: ANIME) {
                      id
                      title {
                        romaji
                        english
                        native
                      }
                      coverImage {
                        large
                      }
                    }
                  }
                }
            GRAPHQL;

      $response = Http::timeout(10)->post($this->baseUrl, [
        'query' => $queryGraphql,
        'variables' => ['search' => $query],
      ]);

      return $response->json('data.Page.media') ?? [];
    });
  }

  /**
   * Get airing schedule for an anime by its AniList ID.
   */
  public function getMediaSchedule(int $anilistId): ?array
  {
    $queryGraphql = <<<'GRAPHQL'
            query ($id: Int) {
              Media(id: $id) {
                nextAiringEpisode {
                  airingAt
                  episode
                }
                status
                airingSchedule(perPage: 1, notYetAired: false) {
                    nodes {
                        airingAt
                        episode
                    }
                }
              }
            }
        GRAPHQL;

    return Cache::remember("anilist_schedule_{$anilistId}", now()->addHours(1), function () use ($anilistId, $queryGraphql) {
      $response = Http::timeout(10)->post($this->baseUrl, [
        'query' => $queryGraphql,
        'variables' => ['id' => $anilistId],
      ]);

      return $response->json('data.Media') ?? null;
    });
  }

  /**
   * Get characters for an anime by its AniList ID.
   */
  public function getCharacters(int $anilistId): array
  {
    $queryGraphql = <<<'GRAPHQL'
            query ($id: Int) {
              Media(id: $id) {
                characters(sort: [ROLE, RELEVANCE]) {
                  edges {
                    role
                    node {
                      id
                      name {
                        full
                        native
                      }
                      image {
                        large
                        medium
                      }
                    }
                  }
                }
              }
            }
        GRAPHQL;

    return Cache::remember("anilist_chars_{$anilistId}", now()->addDays(7), function () use ($anilistId, $queryGraphql) {
      $response = Http::timeout(10)->post($this->baseUrl, [
        'query' => $queryGraphql,
        'variables' => ['id' => $anilistId],
      ]);

      $edges = $response->json('data.Media.characters.edges') ?? [];

      return collect($edges)->map(function ($edge) {
        return [
          'id' => $edge['node']['id'],
          'name' => $edge['node']['name']['full'] ?? ($edge['node']['name']['native'] ?? ''),
          'image' => $edge['node']['image']['large'] ?? ($edge['node']['image']['medium'] ?? ''),
          'role' => $edge['role'] ?? 'BACKGROUND',
        ];
      })->toArray();
    });
  }

  /**
   * Get season chain (PREQUEL/SEQUEL) for episode splitting.
   * Recursively traverses the entire chain.
   *
   * @return array<int, array{id: int, title: string, episodes: int}>
   */
  public function getSeasonChain(int $anilistId): array
  {
    $cacheKey = "anilist_chain_{$anilistId}";

    return Cache::remember($cacheKey, now()->addDays(1), function () use ($anilistId) {
      $visited = [];
      $seasons = collect();

      $this->traverseChain($anilistId, $visited, $seasons);

      return $seasons
        ->sortBy('start_date')
        ->values()
        ->map(fn($s) => [
          'id' => $s['id'],
          'title' => $s['title'],
          'episodes' => $s['episodes'],
        ])
        ->toArray();
    });
  }

  /**
   * Recursively traverse PREQUEL/SEQUEL chain.
   */
  protected function traverseChain(int $anilistId, array &$visited, &$seasons): void
  {
    if (in_array($anilistId, $visited)) {
      return;
    }
    $visited[] = $anilistId;

    $queryGraphql = <<<'GRAPHQL'
      query ($id: Int) {
        Media(id: $id) {
          id
          title { romaji }
          episodes
          format
          startDate { year month day }
          relations {
            edges {
              relationType
              node {
                id
                format
              }
            }
          }
        }
      }
    GRAPHQL;

    $response = Http::timeout(10)->post($this->baseUrl, [
      'query' => $queryGraphql,
      'variables' => ['id' => $anilistId],
    ]);

    $media = $response->json('data.Media');
    if (!$media) {
      return;
    }

    // Add current media if TV with episodes
    if ($media['format'] === 'TV' && $media['episodes']) {
      $seasons->push([
        'id' => $media['id'],
        'title' => $media['title']['romaji'],
        'episodes' => $media['episodes'],
        'start_date' => $this->parseStartDate($media['startDate']),
      ]);
    }

    // Recursively traverse PREQUEL/SEQUEL
    $relations = $media['relations']['edges'] ?? [];
    foreach ($relations as $edge) {
      $relationType = $edge['relationType'];
      $node = $edge['node'];

      if (!in_array($relationType, ['PREQUEL', 'SEQUEL'])) {
        continue;
      }
      if ($node['format'] !== 'TV') {
        continue;
      }

      $this->traverseChain($node['id'], $visited, $seasons);
    }
  }


  protected function parseStartDate(?array $date): string
  {
    if (!$date || !$date['year']) {
      return '9999-01-01';
    }

    return sprintf(
      '%04d-%02d-%02d',
      $date['year'],
      $date['month'] ?? 1,
      $date['day'] ?? 1
    );
  }
}

