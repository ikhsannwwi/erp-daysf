@extends('administrator.authentication.main')

@section('content')
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    @include('administrator.authentication.logo')
                    <p class="auth-subtitle mb-5">Input your email and we will send you reset password link.</p>

                    <form action="{{ route('admin.accounts.reset.sendlink') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('POST')

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="Email"
                                autocomplete="off" name="email" id="email" tabindex="1" data-parsley-required="true"
                                data-parsley-type="email" data-parsley-trigger="change"
                                data-parsley-error-message="Masukan alamat email yang valid." autofocus>
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="" style="color: #dc3545" id="accessErrorEmail"></div>
                        </div>
                        <button type="submit" id="formSubmit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
                            <span class="indicator-label">Send</span>
                            <span class="indicator-progress" style="display: none;">
                                Tunggu Sebentar...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>

    </div>
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


            submitButton.addEventListener("click", async function(e) {
                e.preventDefault();
                indicatorBlock();

                // Perform remote validation
                const remoteValidationResultEmail = await validateRemoteEmail();
                const email = $("#email");
                const accessErrorEmail = $("#accessErrorEmail");
                if (!remoteValidationResultEmail.valid) {
                    // Remote validation failed, display the error message
                    accessErrorEmail.addClass('invalid-feedback');
                    email.addClass('is-invalid');

                    accessErrorEmail.text(remoteValidationResultEmail
                        .errorMessage); // Set the error message from the response
                    indicatorNone();

                    return;
                } else {
                    accessErrorEmail.removeClass('invalid-feedback');
                    email.removeClass('is-invalid');
                    accessErrorEmail.text('');
                }

                // Validate the form using Parsley
                if ($(form).parsley().validate()) {
                    indicatorSubmit();
                    form.submit();
                } else {
                    // Handle validation errors
                    const validationErrors = [];
                    $(form).find(':input').each(function() {
                        const field = $(this);
                        if (!field.parsley().isValid()) {
                            indicatorNone();
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
                    'inline-block';
                submitButton.querySelector('.indicator-progress').style.display =
                    'none';
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
                const email = $('#email');
                const remoteValidationUrl = "{{ route('admin.accounts.checkEmail') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            email: email.val()
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
