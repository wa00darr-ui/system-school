@extends('layouts.app')

@section('title', 'أرشيف المستندات')

@section('content')

<div class="container py-5">

    <div class="page-header">

        <div>

            <h1>
                أرشيف المستندات
            </h1>

            <p>
                جميع المستندات والمعاملات الإلكترونية.
            </p>

        </div>

        <a
            href="{{ route('documents.create') }}"
            class="btn school-btn">

            <i class="fa-solid fa-plus"></i>

            مستند جديد

        </a>

    </div>


    <div class="content-card">

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

                        <th>
                            إجراء
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($documents as $document)

                    <tr>

                        <td>
                            {{ $document->title }}
                        </td>

                        <td>
                            {{ $document->recipient_name }}
                        </td>

                        <td>

                            @switch($document->category)

                                @case('teachers')
                                    المعلمات
                                    @break

                                @case('administrators')
                                    الإداريات
                                    @break

                                @case('parents')
                                    أولياء الأمور
                                    @break

                            @endswitch

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

                        <td
                            colspan="6"
                            class="text-center py-5">

                            لا توجد مستندات.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="mt-4">

            {{ $documents->links() }}

        </div>

    </div>

</div>

@endsection
