@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Supplier
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.supplier') }}">Supplier</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.supplier.save') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label for="inputNama" class="form-label">Nama</label>
                                            <input type="text" id="inputNama" class="form-control"
                                                placeholder="Masukan Nama" name="nama" autocomplete="off"
                                                data-parsley-required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputEmail" class="form-label">Email</label>
                                    <input type="text" id="inputEmail" class="form-control" placeholder="Masukan Email"
                                        name="email" autocomplete="off" data-parsley-required="true"
                                        data-parsley-type="email" data-parsley-trigger="change"
                                        data-parsley-error-message="Masukan alamat email yang valid.">
                                    <div class="" style="color: #dc3545" id="accessErrorEmail"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputTelepon" class="form-label">Telepon</label>
                                    <input type="text" id="inputTelepon" class="form-control"
                                        placeholder="Masukan Telepon" name="telepon" autocomplete="off"
                                        data-parsley-required="true" data-parsley-pattern="^(628|08)[0-9]+$"
                                        data-parsley-length="[10,13]" data-parsley-trigger="change"
                                        data-parsley-error-message="Telepon harus dimulai dengan 628 atau 08, hanya boleh berisi angka, dan memiliki panjang antara 10 hingga 13 digit.">
                                    <div class="" style="color: #dc3545" id="accessErrorTelepon"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputAlamat" class="form-label">Alamat</label>
                                    <textarea id="inputAlamat" class="form-control" placeholder="Masukkan Alamat" name="alamat" style="height: 150px;"
                                        data-parsley-required="true"></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" id="formSubmit" class="btn btn-primary me-1 mb-1">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress" style="display: none;">
                                        Tunggu Sebentar...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                <a href="{{ route('admin.supplier') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->
@endsection

@push('js')
    <script src="{{ asset('templateAdmin/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/parsley.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            //validate parsley form
            const form = document.getElementById("form");
            const validator = $(form).parsley();

            const submitButton = document.getElementById("formSubmit");

            // form.addEventListener('keydown', function(e) {
            //     if (e.key === 'Enter') {
            //         e.preventDefault();
            //     }
            // });

            submitButton.addEventListener("click", async function(e) {
                e.preventDefault();

                indicatorBlock();


                // Perform remote validation
                const remoteValidationResultEmail = await validateRemoteEmail();
                const inputEmail = $("#inputEmail");
                const accessErrorEmail = $("#accessErrorEmail");
                if (!remoteValidationResultEmail.valid) {
                    // Remote validation failed, display the error message
                    accessErrorEmail.addClass('invalid-feedback');
                    inputEmail.addClass('is-invalid');

                    accessErrorEmail.text(remoteValidationResultEmail
                        .errorMessage); // Set the error message from the response
                    indicatorNone();

                    return;
                } else {
                    accessErrorEmail.removeClass('invalid-feedback');
                    inputEmail.removeClass('is-invalid');
                    accessErrorEmail.text('');
                }

                // Perform remote validation
                const remoteValidationResultTelepon = await validateRemoteTelepon();
                const inputTelepon = $("#inputTelepon");
                const accessErrorTelepon = $("#accessErrorTelepon");
                if (!remoteValidationResultTelepon.valid) {
                    // Remote validation failed, display the error message
                    accessErrorTelepon.addClass('invalid-feedback');
                    inputTelepon.addClass('is-invalid');

                    accessErrorTelepon.text(remoteValidationResultTelepon
                        .errorMessage); // Set the error message from the response
                    indicatorNone();

                    return;
                } else {
                    accessErrorTelepon.removeClass('invalid-feedback');
                    inputTelepon.removeClass('is-invalid');
                    accessErrorTelepon.text('');
                }

                // Validate the form using Parsley
                if ($(form).parsley().validate()) {
                    indicatorSubmit();
                    // Submit the form
                    form.submit();
                } else {
                    // Handle validation errors
                    const validationErrors = [];
                    $(form).find(':input').each(function() {
                        indicatorNone();
                        const field = $(this);
                        if (!field.parsley().isValid()) {
                            const attrName = field.attr('name');
                            const errorMessage = field.parsley().getErrorsMessages().join(
                                ', ');
                            validationErrors.push(attrName + ': ' + errorMessage);
                        }
                    });
                    console.log("Validation errors:", validationErrors.join('\n'));
                }
            });

            function indicatorSubmit() {
                submitButton.querySelector('.indicator-label').style.display =
                    'none';
                submitButton.querySelector('.indicator-progress').style.display =
                    'inline-block';
            }

            function indicatorNone() {
                submitButton.querySelector('.indicator-label').style.display =
                    'inline-block';
                submitButton.querySelector('.indicator-progress').style.display =
                    'none';
                submitButton.disabled = false;
            }

            function indicatorBlock() {
                // Disable the submit button and show the "Please wait..." message
                submitButton.disabled = true;
                submitButton.querySelector('.indicator-label').style.display = 'none';
                submitButton.querySelector('.indicator-progress').style.display =
                    'inline-block';
            }


            async function validateRemoteEmail() {
                const inputEmail = $('#inputEmail');
                const remoteValidationUrl = "{{ route('admin.supplier.checkEmail') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            email: inputEmail.val(),
                        }
                    });

                    // Assuming the response is JSON and contains a "valid" key
                    return {
                        valid: response.valid === true,
                        errorMessage: response.message
                    };
                } catch (error) {
                    console.error("Remote validation error:", error);
                    return {
                        valid: false,
                        errorMessage: "An error occurred during validation."
                    };
                }
            }

            async function validateRemoteTelepon() {
                const inputTelepon = $('#inputTelepon');
                const remoteValidationUrl = "{{ route('admin.supplier.checkTelepon') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            telepon: inputTelepon.val(),
                        }
                    });

                    // Assuming the response is JSON and contains a "valid" key
                    return {
                        valid: response.valid === true,
                        errorMessage: response.message
                    };
                } catch (error) {
                    console.error("Remote validation error:", error);
                    return {
                        valid: false,
                        errorMessage: "An error occurred during validation."
                    };
                }
            }

        });
    </script>
@endpush
