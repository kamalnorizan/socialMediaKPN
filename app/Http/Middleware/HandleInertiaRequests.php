<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'notifications' => function () use ($request) {
                if ($request->user()) {
                    return $request->user()->unreadNotifications->map(function ($notification) {
                        return [
                            'id' => $notification->id,
                            'post_id' => $notification->data['post_id'] ?? null,
                            'post_content' => $notification->data['post_content'] ?? null,
                            'post_uuid' => $notification->data['post_uuid'] ?? null,
                            'user_id' => $notification->data['user_id'] ?? null,
                            'user_name' => $notification->data['user_name'] ?? null,
                            'read_at' => $notification->read_at,
                            'type' => $notification->type,
                            'created_at' => $notification->created_at->diffForHumans(),
                        ];
                    });
                }else{
                    return [
                        'unread_count' => 0,
                        'items' => [],
                    ];
                }
            },
        ];
    }
}
