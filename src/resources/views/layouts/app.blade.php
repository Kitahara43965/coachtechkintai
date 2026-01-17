<!DOCTYPE html>
<html lang="ja">

<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>coachtech勤怠管理アプリ</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  <link rel="stylesheet" href="{{ asset('css/draft-timetable-modal.css') }}">
  <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
  <style>
    input, textarea {
      font-family: "Hiragino Kaku Gothic Pro", "Yu Gothic", Meiryo, sans-serif;
    }
  </style>
  @yield('css')
</head>

@php
  use Illuminate\Support\Facades\Auth;
  use App\Constants\UserRole;
  use App\Constants\ShowFunctionKinds\ShowFunctionKind;

  $authUser = Auth::user();
  $authUserRole = $authUser ? $authUser->role : null;

  if(isset($isMultipleFunctionHeader)){
    $newIsMultipleFunctionHeader = $isMultipleFunctionHeader;
  }else{
    $newIsMultipleFunctionHeader = false;
  }

  if(isset($bladeUserRole)){
    $newBladeUserRole = $bladeUserRole;
  }else{
    $newBladeUserRole = UserRole::UNDEFINED;
  }

  $newShowFunctionKind = ShowFunctionKind::UNDEFINED;
  if(isset($showFunctionKind)){
      $newShowFunctionKind = $showFunctionKind;
  }

  $stringNameBody = null;
  if($newShowFunctionKind === ShowFunctionKind::USER_LOGIN){
      $stringNameBody = "body-white";
  }else if($newShowFunctionKind === ShowFunctionKind::ADMIN_LOGIN){
      $stringNameBody = "body-white";
  }else if($newShowFunctionKind === ShowFunctionKind::REGISTER){
      $stringNameBody = "body-white";
  }else if($newShowFunctionKind === ShowFunctionKind::SHOW_EMAIL_VERIFICATION_FOR_USER){
      $stringNameBody = "body-white";
  }else if($newShowFunctionKind === ShowFunctionKind::SHOW_EMAIL_VERIFICATION_FOR_ADMIN){
      $stringNameBody = "body-white";
  }else{//$newShowFunctionKind
      $stringNameBody = "body-white-gray";
  }//$newShowFunctionKind

@endphp

<body class="{{$stringNameBody}}">

  <header class="header">
    <div class="header-left-block">
      <a href="{{route('index')}}" class="header-index">
        <img src="{{ asset('storage/svg/logo.svg') }}" alt="テストSVG" class="header-svg-logo">
      </a>
    </div>


    <div class="header-right-block">
        <ul class="header-right-block-child">
          @if($newIsMultipleFunctionHeader)

              @if($newBladeUserRole === UserRole::USER)
                <li>
                    <a href="{{route('index')}}" class="header-right-block-button">
                    勤怠
                    </a>
                </li>
                <li>
                    <a href="{{route('attendanceList')}}" class="header-right-block-button">
                      勤怠一覧
                    </a>
                </li>
                <li>
                    <a href="{{route('stampCorrectionRequestList')}}" class="header-right-block-button">
                        申請
                    </a>
                </li>
            @elseif($newBladeUserRole === UserRole::ADMIN)
                <li >
                    <a href="{{route('admin.attendanceList')}}" class="header-right-block-button">
                    勤怠一覧
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.staffList')}}" class="header-right-block-button">
                      スタッフ一覧
                    </a>
                </li>
                <li>
                    <a href="{{route('stampCorrectionRequestList')}}" class="header-right-block-button">
                        申請一覧
                    </a>
                </li>
            @endif

            <li class="header-log">
              @if (Auth::check())
                <form action="{{route('logout')}}" method="post" class="header-form">
                  @csrf
                  <button class="header-log-button">ログアウト</button>
                </form>
              @else
                <button
                  type="button"
                  class="header-log-button"
                  onclick="window.location='{{ route('login') }}'">
                  ログイン
                </button>
              @endif
            </li>
          @endif
        </ul>
    </div>
  </header>
  
  <main>
    @yield('content')
  </main>

</body>

</html>