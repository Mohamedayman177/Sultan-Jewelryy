@extends('layouts.admin')

@section('title', 'تعديل خدمة')

@section('content')
<div class="admin-card">
    <h1 class="admin-h1">تعديل الخدمة</h1>
    <form method="post" action="{{ route('admin.services.update', $service) }}">
        @csrf
        @method('PUT')
        @include('admin.services._form', ['service' => $service])
        <div class="admin-actions" style="margin-top:1rem;">
            <button type="submit" class="btn-admin btn-admin--primary">تحديث</button>
            <a href="{{ route('admin.services.index') }}" class="btn-admin btn-admin--muted">إلغاء</a>
        </div>
    </form>
</div>
@endsection
