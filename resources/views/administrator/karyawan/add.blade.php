@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Karyawan
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.karyawan') }}">Karyawan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.karyawan.save') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('POST')
                        
                        <div class="row">
                            <div class="form-group mandatory">
                                <div class="col-md-4 col-12">
                                    <label for="triggerDepartemen" class="form-label">Departemen</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchDepartemen"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputDepartemenName"
                                            data-parsley-required="true" readonly>
                                        <input type="text" class="d-none" name="departemen" id="inputDepartemen">
                                        <div class="input-group-append">
                                            <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                            <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#ModalDepartemen" id="triggerDepartemen">
                                                Search
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-6">
                                <div class="form-group mandatory">
                                    <label for="inputNamaDepan" class="form-label">Nama Depan</label>
                                    <input type="text" id="inputNamaDepan" class="form-control" placeholder="Masukan Nama Depan"
                                        name="nama_depan" autocomplete="off" data-parsley-required="true">
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group">
                                    <label for="inputNamaBelakang" class="form-label">Nama Belakang</label>
                                    <input type="text" id="inputNamaBelakang" class="form-control" placeholder="Masukan Nama Belakang"
                                        name="nama_belakang" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputNama" class="form-label">Nama</label>
                                    <input type="text" id="inputNama" class="form-control" placeholder="Masukan Nama Lengkap"
                                        name="nama" autocomplete="off" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputEmail" class="form-label">Email</label>
                                    <input type="text" id="inputEmail" class="form-control" placeholder="Masukan Email"
                                        name="email" autocomplete="off" data-parsley-required="true">
                                    <div class="" style="color: #dc3545" id="accessErrorEmail"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputTelepon" class="form-label">Telepon</label>
                                    <input type="text" id="inputTelepon" class="form-control" placeholder="Masukan Telepon"
                                        name="telepon" autocomplete="off" data-parsley-required="true">
                                    <div class="" style="color: #dc3545" id="accessErrorTelepon"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label for="inputTanggalLahir" class="form-label">Tanggal Lahir</label>
                                            <input type="text" id="inputTanggalLahir" class="form-control datepicker"
                                                placeholder="Masukan Tanggal Lahir" name="tanggal_lahir" autocomplete="off"
                                                data-parsley-required="true">
                                        </div>
                                    </div>
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
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label for="inputTanggalBergabung" class="form-label">Tanggal Bergabung</label>
                                            <input type="text" id="inputTanggalBergabung" class="form-control datepicker"
                                                placeholder="Masukan Tanggal Bergabung" name="tanggal_bergabung" autocomplete="off"
                                                data-parsley-required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputJabatan" class="form-label">Jabatan</label>
                                    <input type="text" id="inputJabatan" class="form-control" placeholder="Masukan Jabatan"
                                        name="jabatan" autocomplete="off" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="inputKeterangan" class="form-label">Keterangan</label>
                                    <textarea id="inputKeterangan" class="form-control" placeholder="Masukkan Keterangan" name="keterangan" style="height: 150px;"></textarea>
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
                                            <input class="form-check-input" type="radio" name="status" value="1"
                                                id="flexRadioDefault1" checked data-parsley-required="true">
                                            <label class="form-check-label form-label" for="flexRadioDefault1">
                                                Aktif
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
                                <a href="{{ route('admin.karyawan') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->
    @include('administrator.karyawan.modal.departemen')
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset_administrator('assets/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('js')
    <script src="{{ asset_administrator('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/parsley.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            let optionDate = {
                    "locale": {
                        "format": "DD-MM-YYYY",
                        "separator": " - ",
                        "daysOfWeek": [
                            "Su",
                            "Mo",
                            "Tu",
                            "We",
                            "Th",
                            "Fr",
                            "Sa"
                        ],
                        "monthNames": [
                            "Januari",
                            "Februari",
                            "Maret",
                            "April",
                            "Mei",
                            "Juni",
                            "Juli",
                            "Agustus",
                            "September",
                            "Oktober",
                            "November",
                            "Desember"
                        ],
                        "firstDay": 1
                    },
                    singleDatePicker: true,
                    autoApply: true,
                    showDropdowns: true,
                    minYear: 1901,
                    maxYear: parseInt(moment().format('YYYY'), 10)
                }

            $('#inputTanggalLahir').daterangepicker(optionDate);
            $('#inputTanggalBergabung').daterangepicker(optionDate);

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
                const remoteValidationUrl = "{{ route('admin.karyawan.checkEmail') }}";
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
                const remoteValidationUrl = "{{ route('admin.karyawan.checkTelepon') }}";
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
