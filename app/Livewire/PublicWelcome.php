<?php

namespace App\Livewire;

use Livewire\Component;

class PublicWelcome extends Component
{
    public string $activeTab = 'development';
    public string $pricingBillingPeriod = 'monthly';

    public array $solutions = [
        'development' => [
            'title' => 'Software Development',
            'description' => 'Manage complex feature builds with AI-assisted breakdown and dependency mapping.',
            'icon' => 'fa-code',
            'metrics' => ['30% faster delivery', '25% fewer bugs', '40% better collaboration'],
            'color' => 'cyan',
        ],
        'marketing' => [
            'title' => 'Digital Marketing',
            'description' => 'Campaign planning, asset coordination, and deadline tracking at scale.',
            'icon' => 'fa-chart-line',
            'metrics' => ['50% faster delivery', '3x more campaigns', 'Real-time collaboration'],
            'color' => 'purple',
        ],
        'creative' => [
            'title' => 'Creative Agencies',
            'description' => 'Manage design sprints, client feedback loops, and asset versions seamlessly.',
            'icon' => 'fa-palette',
            'metrics' => ['80% revision reduction', 'Instant approvals', 'Version control built-in'],
            'color' => 'pink',
        ],
        'operations' => [
            'title' => 'Enterprise Operations',
            'description' => 'Large-scale project orchestration with advanced reporting and compliance.',
            'icon' => 'fa-cogs',
            'metrics' => ['Unlimited projects', 'Advanced reporting', 'SSO & compliance'],
            'color' => 'cyan',
        ],
    ];

    public array $features = [
        [
            'title' => 'Smart Prioritization',
            'description' => 'AI analyzes task complexity, dependencies, and team capacity to auto-prioritize.',
            'icon' => 'fa-brain',
            'color' => 'neon-cyan',
        ],
        [
            'title' => 'Auto-Generated Subtasks',
            'description' => 'Gemini breaks down epic tasks into actionable subtasks with estimates.',
            'icon' => 'fa-wand-magic-sparkles',
            'color' => 'neon-purple',
        ],
        [
            'title' => 'Real-time Bottleneck Analysis',
            'description' => 'Identify workflow slowdowns and get optimization recommendations.',
            'icon' => 'fa-chart-area',
            'color' => 'neon-pink',
        ],
        [
            'title' => 'Live Collaboration',
            'description' => 'Real-time updates, comments, and multi-user task management.',
            'icon' => 'fa-users',
            'color' => 'neon-cyan',
        ],
        [
            'title' => 'Kanban & Timeline Views',
            'description' => 'Flexible project visualization for any workflow style.',
            'icon' => 'fa-columns',
            'color' => 'neon-purple',
        ],
        [
            'title' => 'Native Integrations',
            'description' => 'Slack, GitHub, Jira, Google Workspace, and 100+ more.',
            'icon' => 'fa-plug',
            'color' => 'neon-pink',
        ],
    ];

    public array $pricing = [
        'free' => [
            'name' => 'Free',
            'price' => 0,
            'description' => 'Perfect for individuals and small teams',
            'features' => [
                'Up to 5 projects',
                'Up to 1 team member',
                'Basic task management',
                'Kanban board',
                'Email support',
            ],
            'cta' => 'Get Started Free',
            'highlighted' => false,
        ],
        'pro' => [
            'name' => 'Pro',
            'price' => 29,
            'description' => 'For growing teams and organizations',
            'features' => [
                'Unlimited projects',
                'Up to 5 team members',
                'AI-powered task breakdown',
                'Advanced analytics',
                'Real-time notifications',
                'Priority support',
                'Custom workflows',
            ],
            'cta' => 'Start Free Trial',
            'highlighted' => true,
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price' => 99,
            'description' => 'For large-scale operations',
            'features' => [
                'Everything in Pro',
                'Unlimited team members',
                'Advanced permissions',
                'SSO & compliance',
                'API access',
                'Dedicated support',
                'Custom integrations',
            ],
            'cta' => 'Contact Sales',
            'highlighted' => false,
        ],
    ];

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.public-welcome')
            ->layout('layouts.public');
    }
}