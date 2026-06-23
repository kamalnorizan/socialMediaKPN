<!doctype html>
<html lang="en">

<head>
    <title>Social Feed – Posts</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }

        .post-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: box-shadow 0.2s ease;
        }

        .post-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.13);
        }

        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .post-content {
            font-size: 1rem;
            color: #1c1e21;
            line-height: 1.6;
        }

        .comment-box {
            background-color: #f7f8fa;
            border-radius: 10px;
            border: 1px solid #e4e6eb;
        }

        .comment-content {
            font-size: 0.9rem;
            color: #3a3b3c;
        }

        .meta-text {
            font-size: 0.8rem;
            color: #65676b;
        }

        .btn-action {
            font-size: 0.85rem;
            color: #65676b;
            border: none;
            background: transparent;
            padding: 6px 12px;
            border-radius: 6px;
            transition: background 0.15s;
        }

        .btn-action:hover {
            background-color: #e4e6eb;
            color: #1877f2;
        }

        .divider {
            border-color: #e4e6eb;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1c1e21;
        }

        .badge-comment {
            font-size: 0.75rem;
            background-color: #e4e6eb;
            color: #65676b;
            border-radius: 12px;
            padding: 2px 8px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-broadcast-pin me-1"></i> SocialFeed
            </a>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-primary fw-semibold">
                    <i class="bi bi-file-post me-1"></i>{{ $posts->count() }} Posts
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <h2 class="section-title mb-4">
                    <i class="bi bi-journal-richtext me-2 text-primary"></i>Latest Posts
                </h2>

                @forelse ($posts as $post)
                <div class="card post-card mb-4">
                    <div class="card-body p-4">

                        <!-- Post Author & Time -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">{{ $post->user->name }}</div>
                                <div class="meta-text">
                                    <i class="bi bi-clock me-1"></i>{{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <!-- Post Content -->
                        <p class="post-content mb-3">{{ $post->content }}</p>

                        <!-- Action Buttons -->
                        <hr class="divider my-2">
                        <div class="d-flex gap-1 mb-3">
                            <button class="btn btn-action">
                                <i class="bi bi-hand-thumbs-up me-1"></i>Like
                            </button>
                            <button class="btn btn-action">
                                <i class="bi bi-chat me-1"></i>Comment
                            </button>
                            <button class="btn btn-action">
                                <i class="bi bi-share me-1"></i>Share
                            </button>
                        </div>

                        <!-- Comments Section -->
                        @if ($post->comments->count() > 0)
                        <div class="mt-2">
                            <div class="d-flex align-items-center mb-2 gap-2">
                                <span class="fw-semibold text-dark" style="font-size:0.9rem">
                                    <i class="bi bi-chat-dots me-1 text-primary"></i>Comments
                                </span>
                                <span class="badge-comment">{{ $post->comments->count() }}</span>
                            </div>

                            @foreach ($post->comments as $comment)
                            <div class="comment-box p-3 mb-2 d-flex align-items-start gap-2">
                                <div class="avatar avatar-sm">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold" style="font-size:0.85rem">{{ $comment->user->name }}</span>
                                        <span class="meta-text">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="comment-content mb-0 mt-1">{{ $comment->content }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="meta-text mb-0"><i class="bi bi-chat me-1"></i>No comments yet. Be the first!</p>
                        @endif

                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                    <p class="fs-5">No posts yet. Check back later!</p>
                </div>
                @endforelse

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
