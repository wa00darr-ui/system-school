<!DOCTYPE html>

<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'نظام المستندات المدرسية')
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet"
          href="{{ asset('css/school.css') }}">

</head>

<body>

@if(auth()->check())

<nav class="navbar navbar-expand-lg school-navbar">

    <div class="container">

        <a class="navbar-brand"
           href="{{ route('dashboard') }}">

            <i class="fa-solid fa-school"></i>

            نظام المستندات المدرسية

        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div
            class="collapse navbar-collapse"
            id="mainNavbar">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">

                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">

                        الرئيسية

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->routeIs('documents.index') ? 'active' : '' }}"
                       href="{{ route('documents.index') }}">

                        الأرشيف

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->routeIs('documents.create') ? 'active' : '' }}"
                       href="{{ route('documents.create') }}">

                        مستند جديد

                    </a>

                </li>

            </ul>

            <div class="d-flex align-items-center gap-3">

                <span class="director-name">

                    <i class="fa-solid fa-user-tie"></i>

                    {{ auth()->user()->name }}

                </span>

                <form
                    action="{{ route('logout') }}"
                    method="POST">

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-light btn-sm">

                        تسجيل الخروج

                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>

@endif


<main>

    @if(session('success'))

        <div class="container mt-4">

            <div class="alert alert-success">

                <i class="fa-solid fa-circle-check"></i>

                {{ session('success') }}

            </div>

        </div>

    @endif

    @if($errors->any())

        <div class="container mt-4">

            <div class="alert alert-danger">

                <strong>
                    يرجى تصحيح الأخطاء التالية:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    @yield('content')

</main>


<footer class="school-footer">

    <div class="container">

        <div class="row">

            <div class="col-md-8">

                <h5>
                    نظام المستندات المدرسية الإلكتروني
                </h5>

                <p>
                    منصة إلكترونية لتنظيم المستندات
                    والتوقيعات والأرشفة داخل المدرسة.
                </p>

            </div>

            <div class="col-md-4 text-md-start">

                <p class="mb-0">

                    جميع الحقوق محفوظة للمدرسة
                    © {{ date('Y') }}

                </p>

            </div>

        </div>

    </div>

</footer>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<script src="{{ asset('js/school.js') }}"></script>

</body>

</html>
