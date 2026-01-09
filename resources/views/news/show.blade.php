@extends('layouts.app')

@section('title', $news->title)
@section('meta-description', Str::limit(strip_tags($news->excerpt ?? $news->content), 160))

@section('content')
<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-xl">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('news.index') }}">News</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($news->title, 30) }}</li>
                </ol>
            </nav>

            <!-- Featured Image -->
            @if($news->image)
                <div class="rounded-3 overflow-hidden shadow-sm mb-4">
                    <img src="{{ asset('storage/'.$news->image) }}" 
                         class="w-100" 
                         alt="{{ $news->title }}"
                         loading="lazy"
                         style="max-height: 400px; object-fit: cover;">
                </div>
            @endif

            <!-- Title & Meta -->
            <h1 class="mb-2 fw-bold">{{ $news->title }}</h1>
            <p class="text-muted mb-4">
                <i class="far fa-calendar me-1"></i> {{ $news->created_at->format('F j, Y') }}
                @if($news->admin)
                    • By <strong>{{ $news->admin->full_name }}</strong>
                @endif
            </p>

            <!-- Engagement Bar (Improved) -->
            <div class="d-flex flex-wrap align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                <!-- Views -->
                <span class="text-muted d-flex align-items-center">
                    <i class="fas fa-eye me-1"></i> {{ number_format($news->views) }} views
                </span>

                @auth
                <div class="d-flex align-items-center">
                    <div 
                        class="like-button {{ $news->likedByUser() ? 'liked' : '' }}" 
                        id="like-heart"
                        data-news-id="{{ $news->id }}"
                        role="button"
                        aria-label="{{ $news->likedByUser() ? 'Unlike' : 'Like' }} this news"
                        title="{{ $news->likedByUser() ? 'Click to unlike' : 'Click to like' }}"
                    >
                        <div class="like-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                    <span class="ms-2 text-muted" id="like-count">{{ number_format($news->likes_count) }}</span>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center">
                    <i class="fas fa-heart me-1"></i> Login to Like
                </a>
                @endauth

            <!-- Enhanced Share Dropdown -->
            <div class="dropdown">
                <button 
                    class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                    type="button" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false"
                >
                    <i class="fas fa-share-alt me-1"></i> Share
                </button>
                <ul class="dropdown-menu shadow">
                    <li>
                        <a class="dropdown-item text-primary" 
                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->full()) }}" 
                        target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-f me-2"></i> Facebook
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-info" 
                        href="https://twitter.com/intent/tweet?url={{ urlencode(url()->full()) }}&text={{ urlencode($news->title) }}" 
                        target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-twitter me-2"></i> Twitter
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-primary" 
                        href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->full()) }}" 
                        target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-linkedin-in me-2"></i> LinkedIn
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-success" 
                        href="https://wa.me/?text={{ urlencode($news->title . ' - ' . url()->full()) }}" 
                        target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-whatsapp me-2"></i> WhatsApp
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button class="dropdown-item" onclick="copyNewsLink(event)">
                            <i class="fas fa-copy me-2 text-muted"></i> Copy Link
                        </button>
                    </li>
                </ul>
            </div>
            </div>

            <!-- News Content -->
            <article class="news-content mb-4">
                {!! $news->content !!}
            </article>

            <!-- Back Button -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">
                    &larr; All News
                </a>
                
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete permanently?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</a>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

        </div>
    </div>
</div>

<style>
/* === Enhanced Animated Heart - No Clipping === */
.like-button {
    position: relative;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s;
    border-radius: 50%;
    /* Ensure it can overflow without clipping */
    overflow: visible !important;
    z-index: 1;
}

.like-button:hover {
    transform: scale(1.1);
}

.like-icon {
    font-size: 1.25rem;
    color: #adb5bd; /* gray-500 */
    transition: color 0.2s;
    z-index: 2;
    position: relative;
}

.like-button.liked .like-icon {
    color: #e5254b; /* vibrant red */
    animation: pulseHeart 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}

/* Bubble background */
.like-button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 5 p%50%;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(229, 37, 75, 0.3);
    transform: translate(-50%, -50%) scale(0);
    opacity: 0;
    z-index: 1;
    pointer-events: none;
}

.like-button.liked::before {
    animation: bubbleExpand 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}

/* Sparkles */
.like-button::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 60px;
    height: 60px;
    transform: translate(-50%, -50%) scale(0);
    opacity: 0;
    background-image: 
        radial-gradient(circle at 20% 30%, #ff8080 2px, transparent 2px),
        radial-gradient(circle at 80% 20%, #ffed80 2px, transparent 2px),
        radial-gradient(circle at 90% 60%, #a4ff80 2px, transparent 2px),
        radial-gradient(circle at 70% 90%, #80ffc8 2px, transparent 2px),
        radial-gradient(circle at 30% 85%, #80c8ff 2px, transparent 2px),
        radial-gradient(circle at 15% 50%, #a480ff 2px, transparent 2px),
        radial-gradient(circle at 40% 10%, #ff80ed 2px, transparent 2px);
    background-repeat: no-repeat;
    z-index: 0;
    pointer-events: none;
}

.like-button.liked::after {
    animation: sparklesBurst 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.1s forwards;
}

/* Animations */
@keyframes pulseHeart {
    0%, 40% { transform: scale(1.2); }
    60%, 100% { transform: scale(1); }
}

@keyframes bubbleExpand {
    0% {
        transform: translate(0%, -50%) scale(0);
        opacity: 0.7;
    }
    70% {
        transform: translate(0%, -50%) scale(1.5);
        opacity: 0.4;
    }
    100% {
        transform: translate(0%, -50%) scale(1);
        opacity: 0;
    }
}

@keyframes sparklesBurst {
    0% {
        transform: translate(-50%, -50%) scale(0);
        opacity: 0;
    }
    30% {
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(1.9);
        opacity: 0;
    }
}
.news-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}
.news-content h2,
.news-content h3 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    color: #222;
}
.news-content p {
    margin-bottom: 1.2rem;
}
.news-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.news-content ul,
.news-content ol {
    padding-left: 1.5rem;
    margin-bottom: 1.2rem;
}
.news-content blockquote {
    border-left: 4px solid #0d6efd;
    padding-left: 1rem;
    margin: 1.5rem 0;
    color: #555;
    font-style: italic;
}
</style>

@auth
@push('scripts')
<script>
function copyNewsLink(e) {
    e.preventDefault();
    navigator.clipboard.writeText('{{ url()->full() }}')
        .then(() => {
            const btn = e.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-2"></i> Copied!';
            setTimeout(() => btn.innerHTML = originalHTML, 2000);
        })
        .catch(err => alert('Failed to copy link.'));
}

document.addEventListener('DOMContentLoaded', function () {
    const likeHeart = document.getElementById('like-heart');
    if (!likeHeart) return;

    likeHeart.addEventListener('click', function () {
        const newsId = this.dataset.newsId;
        const heart = this;
        const countEl = document.getElementById('like-count');
        const wasLiked = heart.classList.contains('liked');

        // Optimistic UI: toggle animation immediately
        if (wasLiked) {
            heart.classList.remove('liked');
        } else {
            heart.classList.add('liked');
        }

        // Update count optimistically
        let currentCount = parseInt(countEl.textContent.replace(/,/g, ''));
        currentCount = wasLiked ? currentCount - 1 : currentCount + 1;
        countEl.textContent = new Intl.NumberFormat().format(currentCount);

        // Send AJAX request
        fetch(`/news/${newsId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                // Revert on error
                if (wasLiked) {
                    heart.classList.add('liked');
                    countEl.textContent = new Intl.NumberFormat().format(currentCount + 1);
                } else {
                    heart.classList.remove('liked');
                    countEl.textContent = new Intl.NumberFormat().format(currentCount - 1);
                }
                alert('Action failed. Please try again.');
            }
            // On success, UI is already updated
        })
        .catch(err => {
            console.error('Like failed:', err);
            // Revert on network error
            if (wasLiked) {
                heart.classList.add('liked');
                countEl.textContent = new Intl.NumberFormat().format(currentCount + 1);
            } else {
                heart.classList.remove('liked');
                countEl.textContent = new Intl.NumberFormat().format(currentCount - 1);
            }
        });
    });
});
</script>
@endpush
@endauth
@endsection