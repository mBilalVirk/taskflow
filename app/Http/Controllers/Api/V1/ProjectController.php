<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/projects",
     *     summary="List all projects",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="team_id",
     *         in="query",
     *         description="Filter by team ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search project name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Projects retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Project")),
     *             @OA\Property(property="pagination", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->has('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $projects = $query->paginate($request->get('per_page', 15));

        return $this->paginated($projects, 'Projects retrieved');
    }

    /**
     * @OA\Post(
     *     path="/projects",
     *     summary="Create new project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"team_id", "name", "visibility"},
     *             @OA\Property(property="team_id", type="string", format="uuid"),
     *             @OA\Property(property="name", type="string", maxLength=255),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="color", type="string"),
     *             @OA\Property(property="visibility", type="string", enum={"private", "public"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|uuid|exists:teams,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'visibility' => 'required|in:private,public',
        ]);

        $project = Project::create([
            'team_id' => $validated['team_id'],
            'created_by' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'color' => $validated['color'],
            'visibility' => $validated['visibility'],
        ]);

        return $this->success($project, 'Project created', 201);
    }

    /**
     * @OA\Get(
     *     path="/projects/{id}",
     *     summary="Get project details",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project details",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(response=404, description="Project not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show(Project $project)
    {
        return $this->success($project->load('tasks', 'team'), 'Project retrieved');
    }

    /**
     * @OA\Put(
     *     path="/projects/{id}",
     *     summary="Update project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="color", type="string"),
     *             @OA\Property(property="visibility", type="string", enum={"private", "public"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Project updated"),
     *     @OA\Response(response=404, description="Project not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            'visibility' => 'in:private,public',
        ]);

        $project->update($validated);

        return $this->success($project, 'Project updated');
    }

    /**
     * @OA\Delete(
     *     path="/projects/{id}",
     *     summary="Delete project",
     *     tags={"Projects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(response=200, description="Project deleted"),
     *     @OA\Response(response=404, description="Project not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return $this->success(null, 'Project deleted');
    }
}