@extends('layouts.admin')

@section('nav')
    <header class="admin-nav">
        <span class="admin-nav__brand">{{ config('app.name') }} — دخول الإدارة</span>
        <div class="admin-nav__actions">
            <a href="{{ route('home') }}">العودة للموقع</a>
        </div>
    </header>
@endsection

@section('title', 'تسجيل الدخول')

@push('styles')
<style>
    .login-wrap { max-width: 400px; margin: 3rem auto; }
    .login-card h1 { margin: 0 0 0.35rem; font-size: 1.25rem; }
    .login-card .muted { margin-bottom: 1.25rem; display: block; }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-weight: 600; margin-bottom: 0.35rem; font-size: 0.88rem; }
    .field input[type="email"], .field input[type="password"] {
        width: 100%; padding: 0.6rem 0.75rem; border-radius: 8px;
        border: 1px solid var(--border); font-family: inherit; font-size: 0.95rem;
    }
    .field-check { display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; }
    .btn-submit {
        width: 100%; margin-top: 0.5rem; padding: 0.72rem;
        border: none; border-radius: 999px; cursor: pointer; font-weight: 700;
        font-family: inherit; font-size: 0.95rem;
        background: linear-gradient(135deg, #d4b04a 0%, #b8922e 55%, #9a761f 100%);
        color: #fff;
    }
    .errors { background: rgba(196, 68, 68, 0.09); border: 1px solid rgba(196, 68, 68, 0.35);
        color: #7a1f1f; padding: 0.65rem 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.88rem; }
</style>
@endpush

@section('content')
<div class="login-wrap">
    <div class="admin-card login-card">
        <h1>تسجيل الدخول</h1>
        <span class="muted">لوحة تحكم الأدمن — أدخل البريد وكلمة المرور</span>

        @if ($errors->any())
            <div class="errors">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="post" action="{{ route('admin.login.store') }}">
            @csrf
            <div class="field">
                <label for="email">البريد الإلكتروني</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>
            <div class="field">
                <label for="password">كلمة المرور</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>
            <div class="field field-check">
                <input id="remember" type="checkbox" name="remember" value="1">
                <label for="remember" style="margin:0;font-weight:500;">تذكرني</label>
            </div>
            <button type="submit" class="btn-submit">دخول</button>
        </form>
    </div>
</div>
@endsection
