@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Operator Kasir
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.operator_kasir') }}">Operator Kasir</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.operator_kasir.update') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="inputId" name="id" value="{{ $data->id }}">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                Moderator <input type="checkbox" class="form-check-input" name="user_group" value="0" id="modCheckbox" {{$data->user_group_id === "0" ? 'checked' : ''}}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="userGroupField" class="form-label">User Group</label>
                                    <select class="form-select form-select-solid" name="user_group" id="userGroupField" {{$data->user_group_id === "0" ? 'disabled' : ''}}
                                        data-parsley-required="true">

                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group mandatory">
                                <div class="col-md-4 col-12">
                                    <label for="triggerToko" class="form-label">Toko</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchToko"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputTokoName" value="{{$data->toko ? $data->toko->nama : ''}}"
                                            data-parsley-required="true" readonly>
                                        <input type="text" class="d-none" name="toko" id="inputToko" value="{{$data->toko_id}}">
                                        <div class="input-group-append">
                                            <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                            <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#ModalToko" id="triggerToko">
                                                Search
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="nameField" class="form-label">Nama</label>
                                    <input type="text" id="nameField" class="form-control" placeholder="Masukan Nama"
                                        value="{{ $data->name }}" name="name" autocomplete="off"
                                        data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="emailField" class="form-label">Email</label>
                                    <input type="text" id="emailField" class="form-control" placeholder="Masukan Email"
                                        value="{{ $data->email }}" name="email" autocomplete="off"
                                        data-parsley-required="true">
                                    <div class="" style="color: #dc3545" id="accessErrorEmail"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="kodeField" class="form-label">Kode</label>
                                    <div class="row">
                                        <div class="col-8">
                                            <input type="text" id="kodeField" class="form-control"
                                                placeholder="Masukan Kode" name="kode" autocomplete="off"
                                                data-parsley-required="true" value="{{ $data->kode }}">
                                            <div class="" style="color: #dc3545" id="accessErrorKode"></div>
                                        </div>
                                        <div class="col-2">
                                            <a href="javascript:void(0)" class="btn btn-primary"
                                                id="buttonGenerateKode"><span class="indicator-label-kode">Generate</span>
                                                <span class="indicator-progress-kode" style="display: none;">
                                                    <div class="d-flex">
                                                        Generate...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2 mt-1"></span>
                                                    </div>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="passwordField" class="form-label">Password</label>
                                    <input type="text" id="passwordField" class="form-control"
                                        placeholder="Masukan Password" name="password" autocomplete="off">
                                    <div class="" style="color: #dc3545" id="accessErrorPasssword"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="konfirmasiPasswordField" class="form-label">Konfirmasi Password</label>
                                    <input type="text" id="konfirmasiPasswordField" class="form-control"
                                        placeholder="Masukan Konfirmasi Password" name="konfirmasi_password"
                                        autocomplete="off">
                                    <div class="" style="color: #dc3545" id="accessErrorKonfirmasiPasssword"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class='form-group mandatory'>
                                    <fieldset>
                                        <label class="form-label">
                                            Status
                                        </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="flexRadioDefault1" {{ $data->status ? 'checked' : '' }}
                                                value="1">
                                            <label class="form-check-label form-label" for="flexRadioDefault1">
                                                Aktif
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="flexRadioDefault2" {{ !$data->status ? 'checked' : '' }}
                                                value="0">
                                            <label class="form-check-label form-label" for="flexRadioDefault2">
                                                Tidak Aktif
                                            </label>
                                        </div>
                                    </fieldset>
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
                                <a href="{{ route('admin.operator_kasir') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    @include('administrator.operator_kasir.modal.toko')
    <!-- Basic Tables end -->
@endsection



@push('js')
    <script src="{{ asset('templateAdmin/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/parsley.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#modCheckbox').on('click', function(){
                if ($(this).is(':checked')) {
                    $('#userGroupField').attr('disabled', true)
                    $('#userGroupField').attr('data-parsley-required', false)
                    $('#userGroupField').val(0)
                }else {
                    $('#userGroupField').attr('data-parsley-required', true)
                    $('#userGroupField').attr('disabled', false)
                }
            })
            
            // Add an event listener to the "Generate" button
            const generateKodeButton = document.getElementById("buttonGenerateKode");
            const kodeField = document.getElementById("kodeField");
            const indicatorLabelKode = document.querySelector(".indicator-label-kode");
            const indicatorProgressKode = document.querySelector(".indicator-progress-kode");
            const remoteGenerateKodeUrl = "{{ route('admin.operator_kasir.generateKode') }}";

            generateKodeButton.addEventListener("click", async function() {
                // Show the indicator when the button is clicked
                indicatorLabelKode.style.display = "none";
                indicatorProgressKode.style.display = "inline-block";

                // Make an AJAX request to generate the code
                try {
                    const response = await $.ajax({
                        method: "GET",
                        url: remoteGenerateKodeUrl,
                    });

                    // Assuming the response is JSON and contains a "generateKode" key
                    kodeField.value = response.generateKode;
                } catch (error) {
                    console.error("Generate error:", error);
                    // Handle errors as needed
                } finally {
                    // Hide the indicator when the AJAX request is complete
                    indicatorLabelKode.style.display = "inline-block";
                    indicatorProgressKode.style.display = "none";
                }
            });



            //validate parsley form
            const form = document.getElementById("form");
            const validator = $(form).parsley();

            const submitButton = document.getElementById("formSubmit");

            submitButton.addEventListener("click", async function(e) {
                e.preventDefault();

                // Perform remote validation
                const remoteValidationResult = await validateRemoteEmail();
                const emailField = $("#emailField");
                const accessErrorEmail = $("#accessErrorEmail");
                if (!remoteValidationResult.valid) {
                    // Remote validation failed, display the error message
                    accessErrorEmail.addClass('invalid-feedback');
                    emailField.addClass('is-invalid');

                    accessErrorEmail.text(remoteValidationResult
                        .errorMessage); // Set the error message from the response

                    return;
                } else {
                    accessErrorEmail.removeClass('invalid-feedback');
                    emailField.removeClass('is-invalid');
                    accessErrorEmail.text('');
                }

                const remoteValidationResultKode = await validateRemoteKode();
                const kodeField = $("#kodeField");
                const accessErrorKode = $("#accessErrorKode");
                if (!remoteValidationResultKode.valid) {
                    // Remote validation failed, display the error message
                    accessErrorKode.addClass('invalid-feedback');
                    kodeField.addClass('is-invalid');

                    accessErrorKode.text(remoteValidationResultKode
                        .errorMessage); // Set the error message from the response

                    return;
                } else {
                    accessErrorKode.removeClass('invalid-feedback');
                    kodeField.removeClass('is-invalid');
                    accessErrorKode.text('');
                }
                // Get the value from the kode field
                const kodeValue = kodeField.val().trim();

                // Validate the length and format of the kode
                if (kodeValue.length !== 17 || !kodeValue.startsWith('daysf-kasir-') || kodeValue.substring(
                        12).length !== 5) {
                    accessErrorKode.addClass('invalid-feedback');
                    kodeField.addClass('is-invalid');

                    accessErrorKode.text(
                        'Kode harus 12 characters dan diawali dengan daysf-kasir- lalu diakhiri oleh 5 uniqid.'
                    );
                    return;
                } else {
                    accessErrorKode.removeClass('invalid-feedback');
                    kodeField.removeClass('is-invalid');
                    accessErrorKode.text('');
                }

                const passwordField = $('#passwordField').val().trim();

                if (passwordField !== '') {
                    if (!validatePasswordConfirmation()) {
                        return;
                    }
                }



                // Validate the form using Parsley
                if ($(form).parsley().validate()) {
                    // Disable the submit button and show the "Please wait..." message
                    submitButton.querySelector('.indicator-label').style.display = 'none';
                    submitButton.querySelector('.indicator-progress').style.display =
                        'inline-block';

                    // Perform your asynchronous form submission here
                    // Simulating a 2-second delay for demonstration
                    setTimeout(function() {
                        // Re-enable the submit button and hide the "Please wait..." message
                        submitButton.querySelector('.indicator-label').style.display =
                            'inline-block';
                        submitButton.querySelector('.indicator-progress').style.display =
                            'none';

                        // Submit the form
                        form.submit();
                    }, 2000);
                } else {
                    // Handle validation errors
                    const validationErrors = [];
                    $(form).find(':input').each(function() {
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

            async function validateRemoteEmail() {
                const emailInput = $('#emailField');
                const inputId = $('#inputId');
                const remoteValidationUrl = "{{ route('admin.operator_kasir.checkEmail') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            email: emailInput.val(),
                            id: inputId.val()
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

            async function validateRemoteKode() {
                const kodeInput = $('#kodeField');
                const inputId = $('#inputId');
                const remoteValidationUrl = "{{ route('admin.operator_kasir.checkKode') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            kode: kodeInput.val(),
                            id: inputId.val()
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

            $('#passwordField, #konfirmasiPasswordField').on('input', function() {
                if ($('#passwordField').val().trim() !== '') {
                    validatePasswordConfirmation();
                } else {
                    // Clear validation messages when password field is empty
                    $('#passwordField').removeClass('is-invalid');
                    $('#accessErrorPasssword').text('');
                    $('#konfirmasiPasswordField').removeClass('is-invalid');
                    $('#accessErrorKonfirmasiPasssword').text('');
                    return true;
                }
            });

            function validatePasswordConfirmation() {
                const passwordField = $('#passwordField');
                const accessErrorPassword = $("#accessErrorPasssword");
                const konfirmasiPasswordField = $('#konfirmasiPasswordField');
                const accessErrorKonfirmasiPassword = $("#accessErrorKonfirmasiPasssword");

                if (passwordField.val().length < 8) {
                    passwordField.addClass('is-invalid');
                    accessErrorPassword.text('Password harus memiliki setidaknya 8 karakter');
                    return false;
                } else if (passwordField.val() !== konfirmasiPasswordField.val()) {
                    passwordField.removeClass('is-invalid');
                    accessErrorPassword.text('');
                    konfirmasiPasswordField.addClass('is-invalid');
                    accessErrorKonfirmasiPassword.text('Konfirmasi Password harus sama dengan Password');
                    return false;
                } else {
                    passwordField.removeClass('is-invalid');
                    accessErrorPassword.text('');
                    konfirmasiPasswordField.removeClass('is-invalid');
                    accessErrorKonfirmasiPassword.text('');
                    return true;
                }
            }



            var optionUserGroup = $('#userGroupField');


            optionUserGroup.html(
                '<option id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin">' +
                '</i> Sedang memuat...</option>'
            );

            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.operator_kasir.getUserGroup') }}',
                method: 'GET',
                success: function(response) {
                    var data = response.usergroup;
                    var optionsHtml = ''; // Store the generated option elements

                    // Iterate through each user group in the response data
                    for (var i = 0; i < data.length; i++) {
                        var userGroup = data[i];
                        optionsHtml += '<option value="' + userGroup.id + '">' + userGroup
                            .name + '</option>';
                    }

                    // Construct the final dropdown HTML
                    var finalDropdownHtml = optionsHtml;

                    optionUserGroup.html(finalDropdownHtml);

                    loadingSpinner.hide(); // Hide the loading spinner after data is loaded

                    // Set the selected option based on the value of $data->id
                    if ('{{ $data->user_group }}') {
                        optionUserGroup.val('{{ $data->user_group->id ?? '' }}');
                    } else {
                        optionUserGroup.prepend('<option value="" selected>Pilih Data</option>');
                    }
                },
                error: function() {
                    // Handle the error case if the AJAX request fails
                    console.error('Gagal memuat data User Group.');
                    optionUserGroup.html('<option>Gagal memuat data</option>')
                    loadingSpinner
                        .hide(); // Hide the loading spinner even if there's an error
                }
            });

        });
    </script>
@endpush
