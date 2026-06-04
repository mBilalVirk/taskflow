<?php

namespace App\Http\Controllers\Api\V1;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "TaskFlow API",
    version: "1.0.0",
    description: "RESTful API for TaskFlow - Project Management SaaS Platform",
    contact: new OA\Contact(
        name: "TaskFlow Support",
        email: "support@taskflow.app",
        url: "https://taskflow.app"
    ),
    license: new OA\License(
        name: "MIT",
        url: "https://opensource.org/licenses/MIT"
    )
)]

#[OA\Server(url: "http://localhost:8000/api/v1", description: "Development Server")]
#[OA\Server(url: "https://taskflow.app/api/v1", description: "Production Server")]

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    name: "bearerAuth",
    in: "header",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Enter your JWT token in the format: Bearer {token}"
)]

// --- Global API Tags ---
#[OA\Tag(name: "Auth", description: "Authentication endpoints")]
#[OA\Tag(name: "Tasks", description: "Task management endpoints")]
#[OA\Tag(name: "Projects", description: "Project management endpoints")]
#[OA\Tag(name: "Users", description: "User management endpoints")]

// --- Schemas ---
#[OA\Schema(
    schema: "Task",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "string", format: "uuid"),
        new OA\Property(property: "project_id", type: "string", format: "uuid"),
        new OA\Property(property: "created_by", type: "string", format: "uuid"),
        new OA\Property(property: "assigned_to", type: "string", format: "uuid", nullable: true),
        new OA\Property(property: "title", type: "string"),
        new OA\Property(property: "description", type: "string", nullable: true),
        new OA\Property(property: "status", type: "string", enum: ["todo", "in_progress", "done"]),
        new OA\Property(property: "priority", type: "string", enum: ["low", "medium", "high"]),
        new OA\Property(property: "due_date", type: "string", format: "date", nullable: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]

#[OA\Schema(
    schema: "Project",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "string", format: "uuid"),
        new OA\Property(property: "team_id", type: "string", format: "uuid"),
        new OA\Property(property: "created_by", type: "string", format: "uuid"),
        new OA\Property(property: "name", type: "string"),
        new OA\Property(property: "description", type: "string", nullable: true),
        new OA\Property(property: "color", type: "string", nullable: true),
        new OA\Property(property: "visibility", type: "string", enum: ["private", "public"]),
        new OA\Property(property: "created_at", type: "string", format: "date-time")
    ]
)]

#[OA\Schema(
    schema: "Team",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "string", format: "uuid"),
        new OA\Property(property: "user_id", type: "string", format: "uuid"),
        new OA\Property(property: "name", type: "string"),
        new OA\Property(property: "slug", type: "string"),
        new OA\Property(property: "description", type: "string", nullable: true),
        new OA\Property(property: "logo", type: "string", nullable: true),
        new OA\Property(property: "subscription_plan", type: "string", enum: ["free", "pro", "enterprise"]),
        new OA\Property(property: "created_at", type: "string", format: "date-time")
    ]
)]

#[OA\Schema(
    schema: "User",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "string", format: "uuid"),
        new OA\Property(property: "name", type: "string"),
        new OA\Property(property: "email", type: "string", format: "email"),
        new OA\Property(property: "avatar", type: "string", nullable: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time")
    ]
)]

#[OA\Schema(
    schema: "Webhook",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "string", format: "uuid"),
        new OA\Property(property: "team_id", type: "string", format: "uuid"),
        new OA\Property(property: "url", type: "string", format: "uri"),
        new OA\Property(property: "events", type: "array", items: new OA\Items(type: "string")),
        new OA\Property(property: "is_active", type: "boolean"),
        new OA\Property(property: "last_triggered_at", type: "string", format: "date-time", nullable: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time")
    ]
)]
class SwaggerController
{
    #[OA\Get(
        path: "/tasks",
        summary: "Get list of tasks",
        tags: ["Tasks"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation"
            )
        ]
    )]
    public function getTasksDummy()
    {
        // This is a placeholder method just so Swagger sees a valid path item
    }
}