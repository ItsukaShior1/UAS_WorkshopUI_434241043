@extends('layouts.dashboard')

@section('page-title', 'Komunitas')

@section('dashboard-content')
<!-- Community Content -->
<div id="communityContent" class="page-content active">
    <div class="page-header">
        <h2>Komunitas</h2>
        <button class="btn btn-primary" onclick="alert('Fitur tulis postingan sedang dalam pengembangan')">+ Tulis</button>
    </div>

    <div class="community-section">
        <div class="community-search">
            <input type="text" placeholder="Cari postingan..." class="search-input">
        </div>

        <div class="community-tabs">
            <button class="tab-btn active">Terbaru</button>
            <button class="tab-btn">Populer</button>
            <button class="tab-btn">Terdekat</button>
        </div>

        <div class="posts-list">
            @foreach($posts as $post)
            <div class="post-card">
                <div class="post-header">
                    <div class="post-author">
                        <img src="https://via.placeholder.com/40" alt="{{ $post['author'] }}" class="author-avatar">
                        <div>
                            <h4>{{ $post['author'] }}</h4>
                            <p class="post-category">{{ $post['category'] }}</p>
                        </div>
                    </div>
                    <button class="post-menu">⋯</button>
                </div>

                <div class="post-content">
                    <h3>{{ $post['title'] }}</h3>
                    <p>{{ $post['content'] }}</p>
                </div>

                <div class="post-footer">
                    <button class="post-action">
                        <span>👍</span>
                        <span>{{ $post['likes'] }} Like</span>
                    </button>
                    <button class="post-action">
                        <span>💬</span>
                        <span>{{ $post['comments'] }} Komentar</span>
                    </button>
                    <button class="post-action">
                        <span>↗️</span>
                        <span>Bagikan</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.community-search {
    margin-bottom: 16px;
}
.search-input {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
}
.community-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
    border-bottom: 1px solid #e0e0e0;
}
.tab-btn {
    padding: 12px 16px;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    font-size: 14px;
    color: #666;
    font-weight: 500;
}
.tab-btn.active {
    color: #1b5e20;
    border-bottom-color: #1b5e20;
}
.posts-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.post-card {
    background: white;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    padding: 16px;
}
.post-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}
.post-author {
    display: flex;
    gap: 12px;
}
.author-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;;
}
.post-author h4 {
    margin: 0;
    font-size: 14px;
}
.post-category {
    margin: 2px 0 0 0;
    font-size: 12px;
    color: #999;
}
.post-menu {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
}
.post-content {
    margin-bottom: 12px;
}
.post-content h3 {
    margin: 0 0 8px 0;
    font-size: 15px;
    font-weight: 600;
}
.post-content p {
    margin: 0;
    font-size: 13px;
    color: #333;
    line-height: 1.5;
}
.post-footer {
    display: flex;
    gap: 16px;
    border-top: 1px solid #f0f0f0;
    padding-top: 12px;
}
.post-action {
    display: flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
    font-size: 13px;
}
.post-action:hover {
    color: #1b5e20;
}

@media (max-width: 768px) {
    .post-card {
        padding: 12px;
    }
    .post-footer {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
@endpush
