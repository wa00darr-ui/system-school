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

        <div class="card-header-custom">

            <div>

                <h4>
                    الملفات الموقعة
                </h4>

                <p class="text-muted mb-0">
                    {{ $document->signedFiles->count() }} ملف مرفوع
                </p>

            </div>

        </div>

        <div class="signed-files-list">

            @forelse($document->signedFiles as $signedFile)

                <div class="signed-file-item">

                    <div class="signed-file-details">

                        <span class="signed-file-icon">
                            <i class="fa-solid fa-file-arrow-down"></i>
                        </span>

                        <div>

                            <strong>
                                {{ $signedFile->original_name }}
                            </strong>

                            <small>
                                رفعه {{ $signedFile->signature_name }}
                                في {{ $signedFile->created_at->format('Y/m/d H:i') }}
                                — {{ number_format($signedFile->size / 1024, 1) }} KB
                            </small>

                        </div>

                    </div>

                    <a
                        href="{{ route('signed-files.download', $signedFile) }}"
                        class="btn btn-sm btn-outline-primary">

                        <i class="fa-solid fa-download"></i>

                        تنزيل

                    </a>

                </div>

            @empty

                <div class="empty-state py-4">

                    <i class="fa-solid fa-folder-open"></i>

                    <h5>
                        لم يتم رفع ملفات بعد
                    </h5>

                    <p class="mb-0">
                        ستظهر الملفات هنا بعد إرسالها من رابط المستفيد.
                    </p>

                </div>

            @endforelse

        </div>

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
