@extends('layouts.app')

@section('title', 'مستند مطلوب')

@section('content')

<div class="public-page">

    <div class="container py-5">

        <div class="public-card">

            <div class="public-logo">

                <i class="fa-solid fa-school"></i>

            </div>

            <h2>
                نظام المستندات المدرسية
            </h2>

            <p class="text-muted">
                مستند مطلوب للمراجعة والإجراء
            </p>


            <div class="document-summary">

                <h4>
                    {{ $document->title }}
                </h4>

                @if($document->description)

                    <p>
                        {{ $document->description }}
                    </p>

                @endif

                <div class="recipient-box">

                    <span>
                        المستفيد:
                    </span>

                    <strong>
                        {{ $document->recipient_name }}
                    </strong>

                </div>

            </div>


            <a
                href="{{ route('public.document.download', $document->access_token) }}"
                class="btn btn-outline-primary w-100 mb-4">

                <i class="fa-solid fa-download"></i>

                تحميل المستند

            </a>


            @if($document->status === 'pending')

            <form
                action="{{ route('public.document.sign', $document->access_token) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf


                <div class="text-start">

                    <label class="form-label">

                        الاسم

                    </label>

                    <input
                        type="text"
                        name="signature_name"
                        class="form-control"
                        placeholder="اكتبي اسمك الكامل"
                        required>

                </div>


                <div class="form-check text-start my-4">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="agree"
                        value="1"
                        id="agree"
                        required>

                    <label
                        class="form-check-label"
                        for="agree">

                        أقر بأنني اطلعت على المستند
                        وأوافق على إتمام الإجراء إلكترونيًا.

                    </label>

                </div>


                <div class="text-start">

                    <label class="form-label">

                        إرفاق المستند بعد التوقيع

                    </label>

                    <input
                        type="file"
                        name="signed_file"
                        class="form-control"
                        accept=".pdf,.jpg,.jpeg,.png"
                        required>

                    <small class="text-muted">

                        يسمح PDF أو JPG أو PNG بحد أقصى 10MB.

                    </small>

                </div>


                <button
                    type="submit"
                    class="btn school-btn w-100 mt-4">

                    <i class="fa-solid fa-signature"></i>

                    اعتماد وإرسال

                </button>

            </form>

            @else

                <div class="alert alert-success">

                    <i class="fa-solid fa-circle-check"></i>

                    تم استلام هذا المستند مسبقًا.

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
