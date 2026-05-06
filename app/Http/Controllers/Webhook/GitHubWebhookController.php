<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitHubWebhookController extends Controller
{
    /**
     * Handle incoming GitHub webhook requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        $secret = config('services.github.webhook_secret');
        $signature = $request->header('X-Hub-Signature-256');

        // Verify signature if secret is set
        if ($secret && $signature) {
            $payload = $request->getContent();
            $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);

            if (!hash_equals($hash, $signature)) {
                Log::warning('GitHub Webhook: Invalid signature received.');
                return response()->json(['message' => 'Invalid signature'], 403);
            }
        }

        $event = $request->header('X-GitHub-Event');
        Log::info("GitHub Webhook received: {$event}");

        // Handle the specific event
        if ($event === 'push') {
            return $this->handlePush($request->all());
        }

        return response()->json(['message' => 'Event received: ' . $event]);
    }

    /**
     * Handle the GitHub 'push' event.
     *
     * @param  array  $payload
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handlePush(array $payload)
    {
        $ref = $payload['ref'] ?? '';
        $branch = str_replace('refs/heads/', '', $ref);
        
        Log::info("GitHub Webhook: Push detected on branch '{$branch}'");

        // Define which branch should trigger a pull (usually 'main' or 'master')
        $targetBranch = 'main'; // Change this if your default branch is different

        if ($branch === $targetBranch) {
            try {
                Log::info("GitHub Webhook: Starting deployment for branch '{$branch}'");
                
                // Fix for 'dubious ownership' error on some servers
                $repoPath = base_path();
                exec("git config --global --add safe.directory {$repoPath} 2>&1");

                // Execute git pull
                $output = [];
                $exitCode = 0;
                exec('git pull origin ' . $branch . ' 2>&1', $output, $exitCode);
                
                Log::info("GitHub Webhook Pull Output (Exit Code {$exitCode}):\n" . implode("\n", $output));

                if ($exitCode === 0) {
                    // Run additional deployment commands
                    exec('php artisan migrate --force');
                    // exec('npm run build');
                    return response()->json(['message' => 'Deployment successful', 'output' => $output]);
                } else {
                    return response()->json(['message' => 'Git pull failed', 'output' => $output], 500);
                }

            } catch (\Exception $e) {
                Log::error('GitHub Webhook Deployment Error: ' . $e->getMessage());
                return response()->json(['message' => 'Internal error during deployment'], 500);
            }
        }

        return response()->json(['message' => 'Push received, but branch ignored.']);
    }
}
