@extends('layouts.app')

@section('title', 'إنشاء مستند')

@section('content')

<div class="container py-5">

    <div class="page-header">

        <div>

            <h1>
                إنشاء مستند جديد
            </h1>

            <p>
                أرفقي المستند وحددي الفئة والمستفيد.
            </p>

        </div>

    </div>


    <div class="content-card">

        <form
            action="{{ route('documents.store') }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf


            <div class="row g-4">

                <div class="col-md-8">

                    <label class="form-label">
                        اسم المستند
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        placeholder="مثال: تعميم الحضور والانصراف"
                        value="{{ old('title') }}"
                        required>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        الفئة المستهدفة
                    </label>

                    <select
                        name="category"
                        class="form-select"
                        required>

                        <option value="">
                            اختاري الفئة
                        </option>

                        <option value="teachers">
                            المعلمات
                        </option>

                        <option value="administrators">
                            الإداريات
                        </option>

                        <option value="parents">
                            أولياء الأمور
                        </option>

                    </select>

                </div>


                <div class="col-12">

                    <label class="form-label">
                        وصف المستند
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="3"
                        placeholder="وصف مختصر للمستند...">{{ old('description') }}</textarea>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        اسم المستفيد
                    </label>

                    <input
                        type="text"
                        name="recipient_name"
                        class="form-control"
                        placeholder="الاسم الكامل"
                        value="{{ old('recipient_name') }}"
                        required>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        رقم الجوال
                    </label>

                    <input
                        type="text"
                        name="recipient_phone"
                        class="form-control"
                        placeholder="05xxxxxxxx"
                        value="{{ old('recipient_phone') }}">

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        البريد الإلكتروني
                    </label>

                    <input
                        type="email"
                        name="recipient_email"
                        class="form-control"
                        placeholder="example@email.com"
                        value="{{ old('recipient_email') }}">

                </div>


                <div class="col-12">

                    <label class="form-label">
                        المستند الأصلي
                    </label>

                    <div class="upload-box">

                        <i class="fa-solid fa-cloud-arrow-up"></i>

                        <p>
                            اسحبي الملف هنا أو اختاريه من الجهاز
                        </p>

                        <small>
                            PDF / Word / JPG / PNG — الحد الأقصى 10MB
                        </small>

                        <input
                            type="file"
                            name="original_file"
                            class="form-control mt-3"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            required>

                    </div>

                </div>

            </div>


            <div class="mt-4 text-start">

                <button
                    type="submit"
                    class="btn school-btn">

                    <i class="fa-solid fa-paper-plane"></i>

                    إنشاء وإرسال الرابط

                </button>

                <a
                    href="{{ route('dashboard') }}"
                    class="btn btn-light">

                    إلغاء

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
