@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            @if (session('register_message'))
                <h1 class="text-center mb-3">会員登録ありがとうございます！</h1>
                <p class="text-center lh-lg mb-5">
                    現在、仮会員の状態です。<br>
                    ただいま、ご入力いただいたメールアドレス宛にご本人様確認用のメールをお送りしました。<br>
                    メール本文内のURLをクリックすると本会員登録が完了となります。
                </p>
            @else
                <h1 class="text-center mb-3">メールアドレスの確認が必要です</h1>
                <p class="text-center lh-lg mb-5">
                    ご登録いただいたメールアドレスに確認メールを送信しました。<br>
                    本会員登録を完了するには、メール内のリンクをクリックしてください。
                </p>
            @endif

            <div class="text-center">
                <a href="{{ url('/') }}" class="btn samuraimart-submit-button w-75 text-white">トップページへ</a>
            </div>
        </div>
    </div>
</div>
@endsection