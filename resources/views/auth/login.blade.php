@extends('layouts.app')

@section('title', 'تسجيل دخول المديرة')

@section('content')

<div class="login-page">

    <div class="login-card">

        <div class="login-icon">

            <i class="fa-solid fa-school"></i>

        </div>

        <h2>
            نظام المستندات المدرسية
        </h2>

        <p class="text-muted">
            تسجيل دخول إدارة المدرسة
        </p>

        <form
            action="{{ route('login.store') }}"
            method="POST">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    البريد الإلكتروني
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="البريد الإلكتروني"
                    value="{{ old('email') }}"
                    required>

            </div>


            <div class="mb-3">

                <label class="form-label">
                    كلمة المرور
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="كلمة المرور"
                    required>

            </div>


            <div class="form-check mb-3">

                <input
                    class="form-check-input"
                    type="checkbox"
                    name="remember"
                    value="1"
                    id="remember">

                <label
                    class="form-check-label"
                    for="remember">

                    تذكرني

                </label>

            </div>


            <button
                type="submit"
                class="btn school-btn w-100">

                <i class="fa-solid fa-right-to-bracket"></i>

                دخول

            </button>

        </form>

    </div>

</div>

@endsection
