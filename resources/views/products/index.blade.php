@extends('layouts.app')

@section('content')
<div class="container pt-2">
    <div class="row">
        <div class="col-md-2">
            @component('components.sidebar', ['categories' => $categories, 'major_categories' => $major_categories])
            @endcomponent
        </div>
        <div class="col">
            @if ($category !== null)
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('top') }}">トップ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                    </ol>
                </nav>
                <h1>{{ $category->name }}の商品一覧<span class="ms-3">{{ number_format($total_count) }}件</span></h1>
            @elseif ($keyword !== null)
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('top') }}">トップ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">商品一覧</li>
                    </ol>
                </nav>
                <h1>"{{ $keyword }}"の検索結果<span class="ms-3">{{ number_format($total_count) }}件</span></h1>
            @else
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('top') }}">トップ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">商品一覧</li>
                    </ol>
                </nav>
                <h1>商品一覧<span class="ms-3">{{ number_format($total_count) }}件</span></h1>
            @endif

            <div class="d-flex align-items-center mb-4">
                <span class="small me-2">並べ替え</span>
                <form method="GET" action="{{ route('products.index') }}">
                    @if ($category)
                        <input type="hidden" name="category" value="{{ $category->id }}">
                    @endif
                    @if ($keyword)
                        <input type="hidden" name="keyword" value="{{ $keyword }}">
                    @endif
                    <select class="form-select form-select-sm" name="select_sort" onChange="this.form.submit();">
                        @foreach ($sorts as $key => $value)
                            <option value="{{ $value }}" {{ $sorted === $value ? 'selected' : '' }}>{{ $key }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="row">
                @foreach($products as $product)
                <div class="col-md-3 mb-3">
                    <a href="{{ route('products.show', $product) }}">
                        <img src="{{ asset($product->image ?: 'img/dummy.png') }}" class="img-thumbnail samuraimart-product-img-products">
                    </a>
                    <div class="row">
                        <div class="col-12">
                            <p class="samuraimart-product-label mt-2">
                                <a href="{{ route('products.show', $product) }}" class="link-dark">{{ $product->name }}</a><br>
                                <label>￥{{ number_format($product->price) }}</label>
                            </p>
                            @php
                                $rawAverage = $product->reviews->avg('score');
                                $average = $rawAverage ? round($rawAverage * 2) / 2 : 0;
                                $ratePercent = ($average / 5) * 100;
                            @endphp
                            <div class="d-flex align-items-center mb-3">
                                <div class="star-rating">
                                    <div class="star-rating-back">☆☆☆☆☆</div>
                                    <div class="star-rating-front" style="width: {{ $ratePercent }}%">★★★★★</div>
                                </div>
                                <span class="ms-2 fw-bold">{{ $rawAverage !== null ? number_format($rawAverage, 1) : '0' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mb-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
