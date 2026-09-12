document.addEventListener(
    'DOMContentLoaded',
    function () {

        const canvas =
            document.getElementById(
                'signatureCanvas'
            );

        const form =
            document.getElementById(
                'signatureForm'
            );

        const clearButton =
            document.getElementById(
                'clearSignature'
            );

        const signatureData =
            document.getElementById(
                'signatureData'
            );


        if (
            !canvas ||
            !form ||
            !clearButton ||
            !signatureData
        ) {
            return;
        }


        function resizeCanvas() {

            const ratio =
                Math.max(
                    window.devicePixelRatio || 1,
                    1
                );

            const rect =
                canvas.getBoundingClientRect();


            canvas.width =
                rect.width * ratio;

            canvas.height =
                rect.height * ratio;


            canvas
                .getContext('2d')
                .scale(ratio, ratio);
        }


        resizeCanvas();


        const signaturePad =
            new SignaturePad(canvas, {
                backgroundColor: 'rgb(255,255,255)',
                penColor: 'rgb(0,0,0)'
            });


        window.addEventListener(
            'resize',
            function () {

                const data =
                    signaturePad.toData();

                resizeCanvas();

                signaturePad.clear();

                signaturePad.fromData(data);

            }
        );


        clearButton.addEventListener(
            'click',
            function () {

                signaturePad.clear();

                signatureData.value = '';

            }
        );


        form.addEventListener(
            'submit',
            function (event) {

                if (signaturePad.isEmpty()) {

                    event.preventDefault();

                    alert(
                        'يرجى إضافة التوقيع قبل الإرسال.'
                    );

                    return;
                }


                signatureData.value =
                    signaturePad.toDataURL(
                        'image/png'
                    );

            }
        );

    }
);


document.addEventListener(
    'click',
    function (event) {

        const button =
            event.target.closest(
                '.signed-file-preview-button'
            );


        if (!button) {
            return;
        }


        openSignedFilePreview(
            button.dataset.previewUrl,
            button.dataset.downloadUrl,
            button.dataset.fileName
        );

    }
);


function openSignedFilePreview(
    previewUrl,
    downloadUrl,
    fileName
) {

    const modalElement =
        document.getElementById(
            'signedFilePreviewModal'
        );

    const frame =
        document.getElementById(
            'signedFilePreviewFrame'
        );

    const label =
        document.getElementById(
            'signedFilePreviewLabel'
        );

    const downloadLink =
        document.getElementById(
            'signedFilePreviewDownload'
        );


    if (
        !modalElement ||
        !frame ||
        !label ||
        !downloadLink
    ) {
        return;
    }


    label.textContent = fileName;

    frame.src = previewUrl;

    downloadLink.href = downloadUrl;


    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );


    modalElement.addEventListener(
        'hidden.bs.modal',
        function () {

            frame.src = 'about:blank';

        },
        { once: true }
    );


    modal.show();
}


function copyDocumentLink() {

    const input =
        document.getElementById(
            'documentLink'
        );

    const message =
        document.getElementById(
            'copyMessage'
        );


    navigator.clipboard
        .writeText(input.value)
        .then(function () {

            message.classList.remove(
                'd-none'
            );

            setTimeout(
                function () {

                    message.classList.add(
                        'd-none'
                    );

                },
                2500
            );

        })
        .catch(function () {

            input.select();

            document.execCommand('copy');

            message.classList.remove(
                'd-none'
            );

        });
}
