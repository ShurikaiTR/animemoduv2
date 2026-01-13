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
}
