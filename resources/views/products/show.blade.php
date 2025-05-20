@extends('layouts.app')

@section('content')
<div class="container pt-2">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Breadcrumb -->
            <nav class="mb-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('top') }}">トップ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>

            <!-- Product Image and Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <img src="{{ asset($product->image ?? 'img/dummy.png') }}" class="img-thumbnail samuraimart-product-img-detail">
                </div>
                <div class="col">
                    <h1>{{ $product->name }}</h1>

                    <!-- Star Rating -->
                    @php
                        $rawAverage = $product->reviews->avg('score');
                        $average = $rawAverage ? round($rawAverage * 2) / 2 : 0;
                        $ratePercent = ($average / 5) * 100;
                    @endphp
                    <div class="d-flex align-items-center mb-2">
                        <div class="star-rating">
                            <div class="star-rating-back">☆☆☆☆☆</div>
                            <div class="star-rating-front" style="width: {{ $ratePercent }}%">★★★★★</div>
                        </div>
                        <span class="ms-2">{{ $rawAverage !== null ? number_format($rawAverage, 1) : '評価なし' }}</span>
                    </div>

                    <p>{{ $product->description }}</p>

                    <hr>
                    <p class="fs-4 fw-bold">￥{{ number_format($product->price) }} <small class="text-muted">(税込)</small></p>
                    <hr>

                    @auth
                        <form method="POST" action="{{ route('carts.store') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="image" value="{{ $product->image }}">
                            <input type="hidden" name="carriage" value="{{ $product->carriage_flag }}">
                            <input type="hidden" name="weight" value="0">

                            <div class="mb-3">
                                <label for="quantity">数量</label>
                                <input type="number" name="qty" value="1" min="1" class="form-control w-25">
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <button class="btn samuraimart-submit-button w-100 text-white">
                                        <i class="fas fa-shopping-cart"></i> カートに追加
                                    </button>
                                </div>
                                <div class="col">
                                    @if(Auth::user()->favorite_products()->where('product_id', $product->id)->exists())
                                        <a href="{{ route('favorites.destroy', $product->id) }}" onclick="event.preventDefault(); document.getElementById('favorites-destroy-form').submit();" class="btn samuraimart-favorite-button w-100 text-favorite">
                                            <i class="fa fa-heart"></i> お気に入り解除
                                        </a>
                                    @else
                                        <a href="{{ route('favorites.store', $product->id) }}" onclick="event.preventDefault(); document.getElementById('favorites-store-form').submit();" class="btn samuraimart-favorite-button w-100 text-favorite">
                                            <i class="fa fa-heart"></i> お気に入り
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <form id="favorites-destroy-form" action="{{ route('favorites.destroy', $product->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                        <form id="favorites-store-form" action="{{ route('favorites.store', $product->id) }}" method="POST" class="d-none">@csrf</form>
                    @endauth
                </div>
            </div>

            <!-- Reviews Section -->
            <hr>
            <h3 class="mb-3">カスタマーレビュー</h3>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <p>{{ number_format($reviews->total()) }}件のレビュー</p>
                    @auth
                        <form method="POST" action="{{ route('reviews.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="mb-3">
                                <label>評価</label>
                                <select name="score" class="form-select review-score-color">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ str_repeat('★', $i) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>タイトル</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror">
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label>レビュー内容</label>
                                <textarea name="content" rows="4" class="form-control @error('content') is-invalid @enderror"></textarea>
                                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button class="btn samuraimart-submit-button w-100 text-white">レビューを追加</button>
                        </form>
                    @endauth
                </div>
                <div class="col-md-8">
                    @forelse ($reviews as $review)
                        <div class="mb-5 border-bottom pb-3">
                            <h4>{{ $review->title }}</h4>
                            <div class="fs-5 mb-2">
                                <span class="review-score-color">{{ str_repeat('★', $review->score) }}</span>
                                <span class="review-score-blank-color">{{ str_repeat('★', 5 - $review->score) }}</span>
                            </div>
                            <p>{{ $review->content }}</p>
                            <p class="text-muted">{{ $review->user->name ?? '匿名' }} - {{ $review->created_at->format('Y年m月d日') }}</p>
                        </div>
                    @empty
                        <p>レビューはまだありません。</p>
                    @endforelse

                    <div class="mt-3">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
