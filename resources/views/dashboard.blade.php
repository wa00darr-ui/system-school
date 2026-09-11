@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')

<div class="container py-5">

    <div class="page-header">

        <div>

            <h1>
                لوحة التحكم
            </h1>

            <p>
                مرحبًا بك في نظام المستندات المدرسية
            </p>

        </div>

        <a
            href="{{ route('documents.create') }}"
            class="btn school-btn">

            <i class="fa-solid fa-plus"></i>

            مستند جديد

        </a>

    </div>


    <div class="row g-4 mt-2">

        <div class="col-md-3">

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-file-lines"></i>

                </div>

                <h3>
                    {{ $total }}
                </h3>

                <p>
                    إجمالي المستندات
                </p>

            </div>

        </div>


        <div class="col-md-3">

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-clock"></i>

                </div>

                <h3>
                    {{ $pending }}
                </h3>

                <p>
                    بانتظار الإجراء
                </p>

            </div>

        </div>


        <div class="col-md-3">

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-pen"></i>

                </div>

                <h3>
                    {{ $signed }}
                </h3>

                <p>
                    تم التوقيع
                </p>

            </div>

        </div>


        <div class="col-md-3">

            <div class="stat-card">

                <div class="stat-icon">

                    <i class="fa-solid fa-box-archive"></i>

                </div>

                <h3>
                    {{ $completed }}
                </h3>

                <p>
                    في الأرشيف
                </p>

            </div>

        </div>

    </div>


    <div class="content-card mt-5">

        <div class="card-header-custom">

            <h4>
                آخر المستندات
            </h4>

            <a href="{{ route('documents.index') }}">
                عرض الكل
            </a>

        </div>


        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            المستند
                        </th>

                        <th>
                            المستفيد
                        </th>

                        <th>
                            الفئة
                        </th>

                        <th>
                            الحالة
                        </th>

                        <th>
                            التاريخ
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($recentDocuments as $document)

                    <tr>

                        <td>

                            <a
                                href="{{ route('documents.show', $document) }}">

                                {{ $document->title }}

                            </a>

                        </td>

                        <td>
                            {{ $document->recipient_name }}
                        </td>

                        <td>

                            @if($document->category === 'teachers')
                                المعلمات
                            @elseif($document->category === 'administrators')
                                الإداريات
                            @else
                                أولياء الأمور
                            @endif

                        </td>

                        <td>

                            @if($document->status === 'pending')

                                <span class="badge bg-warning">
                                    بانتظار الإجراء
                                </span>

                            @elseif($document->status === 'completed')

                                <span class="badge bg-success">
                                    مكتمل
                                </span>

                            @elseif($document->status === 'signed')

                                <span class="badge bg-info">
                                    تم التوقيع
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    ملغي
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $document->created_at->format('Y/m/d') }}
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center py-4">

                            لا توجد مستندات حتى الآن.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
