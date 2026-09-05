@extends('layouts.app')

@section('title', 'تم الإرسال')

@section('content')

<div class="public-page">

<div class="container py-5">

<div class="public-card text-center">

    <div class="success-icon">

        <i class="fa-solid fa-check"></i>

    </div>


    <h2>
        تم إرسال المستند بنجاح
    </h2>


    <p class="text-muted">

        تم استلام المستند والتوقيع الإلكتروني
        وحفظهما في النظام.

    </p>


    <div class="completed-info text-start">

        <p>

            <strong>
                المستند:
            </strong>

            {{ $document->title }}

        </p>


        <p>

            <strong>
                الموقّع:
            </strong>

            {{ $document->signature_name }}

        </p>


        <p>

            <strong>
                وقت التوقيع:
            </strong>

            {{ $document->signed_at->format('Y/m/d H:i:s') }}

        </p>

    </div>


    <div class="alert alert-success mt-4">

        تم حفظ العملية في الأرشيف.

    </div>

</div>

</div>

</div>

@endsection
