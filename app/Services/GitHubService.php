<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService
{
    private string $token;
    private string $baseUrl = 'https://api.github.com';

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get user repositories
     */
    public function getRepositories(): array
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/user/repos");

        return $response->json();
    }

    /**
     * Link PR to task
     */
    public function linkPullRequest(string $prUrl, $task): void
    {
        $task->links()->create([
            'type' => 'github_pr',
            'url' => $prUrl,
            'title' => 'GitHub Pull Request',
        ]);
    }

    /**
     * Create comment on PR
     */
    public function commentOnPR(string $owner, string $repo, int $prNumber, string $comment): void
    {
        Http::withToken($this->token)
            ->post(
                "{$this->baseUrl}/repos/{$owner}/{$repo}/issues/{$prNumber}/comments",
                ['body' => $comment]
            );
    }
}