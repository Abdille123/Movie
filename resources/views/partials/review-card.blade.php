<article class="review-card">
    <div class="review-head">
        <strong>{{ $review->author_name }}</strong>
        <span>{{ str_repeat('★', $review->rating) }}</span>
    </div>
    <p>{{ $review->comment }}</p>
    <div class="review-foot">
        <span>{{ $review->favourite_scene ?: 'No favourite scene added' }}</span>
        <strong>{{ $review->would_rewatch ? 'Would rewatch' : 'One-time watch' }}</strong>
    </div>
</article>
