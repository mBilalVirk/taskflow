<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Webhook;
use Illuminate\Http\Request;

class WebhookController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/webhooks",
     *     summary="List webhooks",
     *     tags={"Webhooks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="team_id",
     *         in="query",
     *         required=true,
     *         description="Team ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Webhooks retrieved",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Webhook"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $webhooks = Webhook::where('team_id', $request->team_id)->get();
        return $this->success($webhooks, 'Webhooks retrieved');
    }

    /**
     * @OA\Post(
     *     path="/webhooks",
     *     summary="Create webhook",
     *     tags={"Webhooks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"team_id", "url", "events"},
     *             @OA\Property(property="team_id", type="string", format="uuid"),
     *             @OA\Property(property="url", type="string", format="uri"),
     *             @OA\Property(
     *                 property="events",
     *                 type="array",
     *                 @OA\Items(type="string", enum={"task.created", "task.updated", "task.completed", "team.member.added"})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Webhook created",
     *         @OA\JsonContent(ref="#/components/schemas/Webhook")
     *     ),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|uuid|exists:teams,id',
            'url' => 'required|url',
            'events' => 'required|array|min:1',
            'events.*' => 'in:task.created,task.updated,task.completed,team.member.added',
        ]);

        $webhook = Webhook::create([
            'team_id' => $validated['team_id'],
            'url' => $validated['url'],
            'events' => $validated['events'],
            'secret' => hash('sha256', uniqid() . time()),
        ]);

        return $this->success($webhook, 'Webhook created', 201);
    }

    /**
     * @OA\Delete(
     *     path="/webhooks/{id}",
     *     summary="Delete webhook",
     *     tags={"Webhooks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(response=200, description="Webhook deleted"),
     *     @OA\Response(response=404, description="Webhook not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy(Webhook $webhook)
    {
        $webhook->delete();
        return $this->success(null, 'Webhook deleted');
    }

    /**
     * @OA\Post(
     *     path="/webhooks/github",
     *     summary="Handle GitHub webhook",
     *     tags={"Webhooks"},
     *     @OA\Response(response=200, description="Webhook processed")
     * )
     */
    public function handleGithub(Request $request)
    {
        // Verify GitHub signature
        $signature = $request->header('X-Hub-Signature-256');
        $payload = $request->getContent();

        // TODO: Implement GitHub integration
        return response()->json(['success' => true]);
    }

    /**
     * @OA\Post(
     *     path="/webhooks/slack",
     *     summary="Handle Slack webhook",
     *     tags={"Webhooks"},
     *     @OA\Response(response=200, description="Webhook processed")
     * )
     */
    public function handleSlack(Request $request)
    {
        // TODO: Implement Slack integration
        return response()->json(['success' => true]);
    }
}