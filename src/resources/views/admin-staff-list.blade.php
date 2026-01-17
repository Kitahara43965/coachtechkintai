@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-staff-list.css') }}">
@endsection

@php
    use App\Models\User;
    $users = User::all();
@endphp

@section('content')

<div class="admin-staff-list-board">
    <div class="list-title-container">
        <div class="list-title">スタッフ一覧</div>
    </div>
    <div class="list-table-container">
        <table class="list-table">
            <thead>
                <tr>
                    <th class="list-table-header">
                        名前
                    </th>
                    <th  class="list-table-header">
                        メールアドレス
                    </th>
                    <th class="list-table-header">
                        月次勤怠
                    </th>
                </tr>
            </thead>
            <tbody>
                @if($users)
                    @foreach($users as $user)
                        <tr>
                            <td class="list-table-content">
                                {{$user->name}}
                            </td>
                            <td  class="list-table-content">
                                {{$user->email}}
                            </td>
                            <td  class="list-table-content">
                                <a class ="list-table-detail" href="{{route('admin.attendanceStaff.id',['id'=>$user->id])}}">
                                    詳細
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection