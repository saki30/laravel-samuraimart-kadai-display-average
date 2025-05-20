@extends('layouts.app')

@section('content')
<div class="container pt-2">
    @if (session('flash_message'))
        <div class="row mb-2">
            <div class="col-12">
                <div class="alert alert-light">
                    {{ session('flash_message') }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-2">
            @component('components.sidebar', ['categories' => $categories, 'major_categories' => $major_categories])
            @endcomponent
        </div>
        <div class="col">
            <div class="mb-4">
                <h2>おすすめ商品</h2>
                <div class="row">
                    @foreach ($recommend_products as $recommend_product)
                        <div class="col-md-4">
                            <a href="{{ route('products.show', $recommend_product) }}">
                                @if ($recommend_product->image !== "")
                                    <img src="{{ asset($recommend_product->image) }}" class="img-thumbnail samuraimart-product-img-recommend">
                                @else
                                    <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-recommend">
                                @endif
                            </a>
                            <div class="row">
                                <div class="col-12">
                                    <p class="samuraimart-product-label mt-2">
                                        <a href="{{ route('products.show', $recommend_product) }}" class="link-dark">{{ $recommend_product->name }}</a>
                                        <br>
                                        <label>￥{{ number_format($recommend_product->price) }}</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <h2>新着商品</h2>
                <div class="row">
                    @foreach ($recently_products as $recently_product)
                        <div class="col-md-3">
                            <a href="{{ route('products.show', $recently_product) }}">
                                @if ($recently_product->image !== "")
                                    <img src="{{ asset($recently_product->image) }}" class="img-thumbnail samuraimart-product-img-products">
                                @else
                                    <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-products">
                                @endif
                            </a>
                            <div class="row">
                                <div class="col-12">
                                    <p class="samuraimart-product-label mt-2">
                                        <a href="{{ route('products.show', $recently_product) }}" class="link-dark">{{ $recently_product->name }}</a>
                                        <br>
                                        <label>￥{{ number_format($recently_product->price) }}</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <h2>注目商品</h2>
                <div class="row">
                    @foreach ($featured_products as $featured_product)
                        <div class="col-md-3">
                            <a href="{{ route('products.show', $featured_product) }}">
                                @if ($featured_product->image !== "")
                                    <img src="{{ asset($featured_product->image) }}" class="img-thumbnail samuraimart-product-img-products">
                                @else
                                    <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail samuraimart-product-img-products">
                                @endif
                            </a>
                            <div class="row">
                                <div class="col-12">
                                    <p class="samuraimart-product-label mt-2">
                                        <a href="{{ route('products.show', $featured_product) }}" class="link-dark">{{ $featured_product->name }}</a>
                                        <br>
                                        <label>￥{{ number_format($featured_product->price) }}</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

<div class="row">
   <div class="col-2">
       @component('components.sidebar', ['categories' => $categories, 'major_categories' => $major_categories])
       @endcomponent
   </div>
   <div class="col-9">
       <h1>おすすめ商品</h1>
       <div class="row">
            @foreach ($recommend_products as $recommend_product)
            <div class="col-4">
                <a href="{{ route('products.show', $recommend_product) }}">
                    @if ($recommend_product->image !== "")
                    <img src="{{ asset($recommend_product->image) }}" class="img-thumbnail">
                    @else
                    <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail">
                    @endif
                </a>
                <div class="row">
                    <div class="col-12">
                        <p class="samuraimart-product-label mt-2">
                            {{ $recommend_product->name }}<br>
                            <label>￥{{ $recommend_product->price }}</label>
                        </p>
                        
                        @php
                            $rawAverage  = $recommend_product->reviews->avg('score');
                            $average     = $rawAverage ? round($rawAverage * 2) / 2 : 0;
                            $ratePercent = ($average / 5) * 100;
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            <div class="star-rating">
                                <div class="star-rating-back">☆☆☆☆☆</div>
                                <div class="star-rating-front" style="width: {{ $ratePercent }}%">★★★★★</div>
                            </div>
                            @if ($rawAverage !== null)
                                <span class="ml-2 font-weight-bold">
                                    {{ number_format($rawAverage, 1) }}
                                </span>
                            @endif
                        </div>  
                          
                    </div>
                </div>
            </div>
            @endforeach
       </div>

        <div class="d-flex justify-content-between">
            <h1>新着商品</h1>
            <a href="{{ route('products.index', ['sort' => 'id', 'direction' => 'desc']) }}">もっと見る</a>
        </div>
       <div class="row">
            @foreach ($recently_products as $recently_product)
                <div class="col-3">
                    <a href="{{ route('products.show', $recently_product) }}">
                        @if ($recently_product->image !== "")
                            <img src="{{ asset($recently_product->image) }}" class="img-thumbnail">
                        @else
                            <img src="{{ asset('img/dummy.png')}}" class="img-thumbnail">
                        @endif
                    </a>
                    <div class="row">
                        <div class="col-12">
                            <p class="samuraimart-product-label mt-2">
                                {{ $recently_product->name }}<br>                              

                                <label>￥{{ $recently_product->price }}</label>
                            </p>

                            
                            @php
                                // ★ レビューから0.1刻みの平均を取得
                                $rawAverage  = $recently_product->reviews->avg('score');
                                // ★ 星用に0.5刻みで丸める（nullなら0）
                                $average     = $rawAverage ? round($rawAverage * 2) / 2 : 0;
                                // ★ 5点満点を100%に換算
                                $ratePercent = ($average / 5) * 100;
                            @endphp
                            <div class="d-flex align-items-center mb-3">
                                <div class="star-rating">
                                    <div class="star-rating-back">☆☆☆☆☆</div>
                                    <div class="star-rating-front" style="width: {{ $ratePercent }}%">★★★★★</div>
                                </div>
                                @if ($rawAverage !== null)
                                    <span class="ml-2 font-weight-bold">
                                        {{ number_format($rawAverage, 1) }}
                                    </span>
                                @endif                              
                            </div>
                     
                        </div>
                    </div>
                </div>
            @endforeach
       </div>
   </div>
</div>
@endsection