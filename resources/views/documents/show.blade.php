@extends('layouts.app')

@section('title', 'تفاصيل المستند')

@section('content')

<div class="container py-5">

    <div class="page-header">

        <div>

            <h1>
                تفاصيل المستند
            </h1>

            <p>
                {{ $document->title }}
            </p>

        </div>

    </div>


    <div class="row g-4">


        <div class="col-lg-7">

            <div class="content-card">

                <h4>
                    معلومات المستند
                </h4>

                <hr>

                <div class="info-row">

                    <span>
                        اسم المستند
                    </span>

                    <strong>
                        {{ $document->title }}
                    </strong>

                </div>


                <div class="info-row">

                    <span>
                        المستفيد
                    </span>

                    <strong>
                        {{ $document->recipient_name }}
                    </strong>

                </div>


                <div class="info-row">

                    <span>
                        الفئة
                    </span>

                    <strong>

                        @if($document->category === 'teachers')
                            المعلمات
                        @elseif($document->category === 'administrators')
                            الإداريات
                        @else
                            أولياء الأمور
                        @endif

                    </strong>

                </div>


                <div class="info-row">

                    <span>
                        الحالة
                    </span>

                    <strong>

                        @if($document->status === 'completed')

                            <span class="badge bg-success">
                                مكتمل
                            </span>

                        @else

                            <span class="badge bg-warning">
                                بانتظار الإجراء
                            </span>

                        @endif

                    </strong>

                </div>

            </div>

        </div>


        <div class="col-lg-5">

            <div class="content-card link-card">

                <div class="link-icon">

                    <i class="fa-solid fa-link"></i>

                </div>

                <h4>
                    رابط المستفيد
                </h4>

                <p>
                    أرسلي هذا الرابط للشخص المحدد.
                    لا يحتاج المستفيد إلى تسجيل دخول.
                </p>


                <div class="input-group">

                    <input
                        type="text"
                        id="documentLink"
                        class="form-control"
                        value="{{ route('public.document', $document->access_token) }}"
                        readonly>

                    <button
                        class="btn btn-outline-secondary"
                        onclick="copyDocumentLink()">

                        <i class="fa-solid fa-copy"></i>

                    </button>

                </div>


                <div
                    id="copyMessage"
                    class="text-success mt-2 d-none">

                    تم نسخ الرابط.

                </div>


                <a
                    href="{{ route('public.document', $document->access_token) }}"
                    target="_blank"
                    class="btn school-btn w-100 mt-3">

                    فتح الرابط

                </a>

            </div>

        </div>


    </div>


    <div class="content-card mt-4">
    <h4>
        الإجراء بعد إرفاق المستند
    </h4>

    <p class="text-muted">
        • المستفيد من الملف استلام تم
    </p>

    <a href="{{ Storage::disk('public')->url($document->signed_file) }}"
       target="_blank"
       class="btn btn-primary">
        عرض المرفق
    </a>
</div>



    <div class="mt-4">

        <form
            action="{{ route('documents.destroy', $document) }}"
            method="POST"
            onsubmit="return confirm('هل أنت متأكدة من حذف المستند؟');">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="btn btn-outline-danger">

                <i class="fa-solid fa-trash"></i>

                حذف المستند

            </button>

        </form>

    </div>

</div>

@endsection
