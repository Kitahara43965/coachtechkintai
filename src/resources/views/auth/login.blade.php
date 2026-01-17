@extends('layouts.app')
   
@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@php
  use App\Constants\UserRole;
  use App\Constants\showFunctionKinds\showFunctionKind;
@endphp


@section('content')

<div class="login-board">
    <div class="gravity-center-child">
        @if($showFunctionKind === ShowFunctionKind::ADMIN_LOGIN)
          <h1>管理者ログイン</h1>
        @else
          <h1>ログイン</h1>
        @endif
    </div>
    <form method="POST" action="{{ route('loginStore') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">メールアドレス</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--text">
              <input type="email" name="email" value="{{ old('email') }}" />
            </div>
            <div class="form__error">
              @error('email')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">パスワード</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--text">
              <input type="password" name="password" />
            </div>
            <div class="form__error">
              @error('password')
              {{ $message }}
              @enderror
            </div>
          </div>
        </div>
        <div class="login-upper-form-button-blank"></div>
        <div class="form__button">
            @if($showFunctionKind === ShowFunctionKind::ADMIN_LOGIN)
                <button class="form__button-submit" type="submit">管理者ログインする</button>
            @else
                <button class="form__button-submit" type="submit">ログインする</button>
            @endif
            
        </div>
    </form>

    @if($bladeUserRole === UserRole::USER)
      <div class="register__link">
          <a class="link-no-decoration" href="{{ route('register') }}">
              会員登録はこちら
          </a>
      </div>
    @endif

    <div class="login-form-bottom-blank"></div>
</div>

@endsection
