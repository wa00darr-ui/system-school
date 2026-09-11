@extends('layouts.app')

@section('title', 'أرشيف المستندات')

@section('content')

@php
    $hasFilters = collect($filters ?? [])->filter(fn ($value) => filled($value))->isNotEmpty();
@endphp

<div class="container py-5">

    <div class="page-header">

        <div>

            <h1>
                أرشيف المستندات
            </h1>

            <p>
                ابحثي وصنّفي المستندات حسب الفئة والحالة والتاريخ.
            </p>

        </div>

        <a
            href="{{ route('documents.create') }}"
            class="btn school-btn">

            <i class="fa-solid fa-plus"></i>

            مستند جديد

        </a>

    </div>


    <div class="content-card documents-filter-card mb-4">

        <form
            method="GET"
            action="{{ route('documents.index') }}"
            class="documents-filter">

            <div class="row g-3 align-items-end">

                <div class="col-lg-4">

                    <label class="form-label" for="filter-q">
                        بحث
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>

                        <input
                            id="filter-q"
                            type="search"
                            name="q"
                            class="form-control"
                            value="{{ $filters['q'] ?? '' }}"
                            placeholder="اسم المستند أو المستفيد أو الجوال أو البريد">

                    </div>

                </div>

                <div class="col-md-6 col-lg-2">

                    <label class="form-label" for="filter-category">
                        الفئة
                    </label>

                    <select
                        id="filter-category"
                        name="category"
                        class="form-select">

                        <option value="">
                            الكل
                        </option>

                        <option
                            value="teachers"
                            @selected(($filters['category'] ?? '') === 'teachers')>
                            المعلمات
                        </option>

                        <option
                            value="administrators"
                            @selected(($filters['category'] ?? '') === 'administrators')>
                            الإداريات
                        </option>

                        <option
                            value="parents"
                            @selected(($filters['category'] ?? '') === 'parents')>
                            أولياء الأمور
                        </option>

                    </select>

                </div>

                <div class="col-md-6 col-lg-2">

                    <label class="form-label" for="filter-status">
                        الحالة
                    </label>

                    <select
                        id="filter-status"
                        name="status"
                        class="form-select">

                        <option value="">
                            الكل
                        </option>

                        <option
                            value="pending"
                            @selected(($filters['status'] ?? '') === 'pending')>
                            بانتظار الإجراء
                        </option>

                        <option
                            value="signed"
                            @selected(($filters['status'] ?? '') === 'signed')>
                            تم التوقيع
                        </option>

                        <option
                            value="completed"
                            @selected(($filters['status'] ?? '') === 'completed')>
                            مكتمل
                        </option>

                        <option
                            value="cancelled"
                            @selected(($filters['status'] ?? '') === 'cancelled')>
                            ملغي
                        </option>

                    </select>

                </div>

                <div class="col-md-6 col-lg-2">

                    <label class="form-label" for="filter-from">
                        من تاريخ
                    </label>

                    <input
                        id="filter-from"
                        type="date"
                        name="from"
                        class="form-control"
                        value="{{ $filters['from'] ?? '' }}">

                </div>

                <div class="col-md-6 col-lg-2">

                    <label class="form-label" for="filter-to">
                        إلى تاريخ
                    </label>

                    <input
                        id="filter-to"
                        type="date"
                        name="to"
                        class="form-control"
                        value="{{ $filters['to'] ?? '' }}">

                </div>

            </div>

            <div class="documents-filter-actions">

                <button type="submit" class="btn school-btn">

                    <i class="fa-solid fa-filter"></i>

                    تطبيق الفلتر

                </button>

                @if($hasFilters)

                    <a
                        href="{{ route('documents.index') }}"
                        class="btn btn-light">

                        <i class="fa-solid fa-rotate-left"></i>

                        إعادة تعيين

                    </a>

                @endif

            </div>

        </form>

    </div>


    <div class="content-card">

        <div class="card-header-custom">

            <div>

                <h4>
                    نتائج البحث
                </h4>

                <p class="documents-count mb-0">
                    {{ $documents->total() }} مستند
                </p>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table align-middle documents-table">

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

                        <th>
                            إجراء
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($documents as $document)

                    <tr>

                        <td>

                            <div class="document-cell">

                                <span class="document-icon">
                                    <i class="fa-solid fa-file-lines"></i>
                                </span>

                                <div>

                                    <strong>
                                        {{ $document->title }}
                                    </strong>

                                    @if($document->description)

                                        <small>
                                            {{ \Illuminate\Support\Str::limit($document->description, 60) }}
                                        </small>

                                    @endif

                                </div>

                            </div>

                        </td>

                        <td>

                            <div class="recipient-cell">

                                <strong>
                                    {{ $document->recipient_name }}
                                </strong>

                                @if($document->recipient_phone)

                                    <small>
                                        {{ $document->recipient_phone }}
                                    </small>

                                @endif

                            </div>

                        </td>

                        <td>

                            <span class="category-pill">
                                {{ $document->categoryLabel() }}
                            </span>

                        </td>

                        <td>

                            <span class="badge {{ $document->statusBadgeClass() }}">
                                {{ $document->statusLabel() }}
                            </span>

                        </td>

                        <td>
                            {{ $document->created_at->format('Y/m/d') }}
                        </td>

                        <td>

                            <a
                                href="{{ route('documents.show', $document) }}"
                                class="btn btn-sm btn-outline-primary">

                                عرض

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6">

                            <div class="empty-state">

                                <i class="fa-solid fa-folder-open"></i>

                                <h5>
                                    لا توجد مستندات مطابقة
                                </h5>

                                <p>
                                    @if($hasFilters)
                                        جرّبي تعديل الفلتر أو إعادة تعيين البحث.
                                    @else
                                        ابدئي بإنشاء مستند جديد ليظهر في الأرشيف.
                                    @endif
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($documents->hasPages())

            <div class="mt-4">

                {{ $documents->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
