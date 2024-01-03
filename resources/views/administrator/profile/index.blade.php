@extends('administrator.layouts.main')

@section('content')
    <div class="container">
        <div class="main-body">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="main-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $data->user->kode }}</li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->

            <div class="row gutters-sm">

                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="{{ img_src($data->foto, 'profile') ? img_src($data->foto, 'profile') : '' }}"
                                    alt="Admin" class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h4>{{ $data->user->name ? $data->user->name : '' }}</h4>
                                    <p class="text-secondary mb-1">Full Stack Developer</p>
                                    <p class="text-muted font-size-sm">
                                        {{ $data->alamat ? $data->alamat : '' }}</p>
                                    <a href="javascript:void(0)" class="btn btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#fileinput-preview-profile">Ubah Foto Profile</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <form action="{{ route('admin.profile.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="kode" value="{{ $data->user->kode ? $data->user->kode : '' }}">
                            <input type="hidden" name="email" value="{{ $data->user->email ? $data->user->email : '' }}">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="#0077B5" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-linkedin mr-2 icon-inline">
                                            <path
                                                d="M4 0.5C4 0.223858 4.22386 0 4.5 0C4.77614 0 5 0.223858 5 0.5V23.5C5 23.7761 4.77614 24 4.5 24C4.22386 24 4 23.7761 4 23.5V0.5ZM0 0.5C0 0.223858 0.223858 0 0.5 0C0.776142 0 1 0.223858 1 0.5V23.5C1 23.7761 0.776142 24 0.5 24C0.223858 24 0 23.7761 0 23.5V0.5ZM20 7.875C20 7.58793 19.7911 7.36171 19.5162 7.31255C18.5465 7.17313 17.5841 7.59267 17.0599 8.4166L14.0059 13.4916C13.7208 13.9585 13.2431 14.25 12.7399 14.25C12.2366 14.25 11.7589 13.9585 11.4738 13.4916L8.41978 8.4166C7.89558 7.59267 6.93313 7.17313 5.96344 7.31255C5.68855 7.36171 5.47967 7.58793 5.47967 7.875C5.47967 8.18223 5.7077 8.44206 6.0137 8.49288C6.82898 8.60647 7.56207 8.25 8.09611 7.63741L11.7395 4.36408C12.1029 3.91619 12.8971 3.91619 13.2605 4.36408L16.9039 7.63741C17.4379 8.25 18.172 8.60647 18.9873 8.49288C19.2933 8.44206 19.5213 8.18223 19.5213 7.875V7.875ZM18 24V15.75H14V24H18ZM9 24V9H5V24H9ZM9 6.75C9 7.9931 10.0076 9 11.25 9C12.4924 9 13.5 7.9931 13.5 6.75C13.5 5.50736 12.4924 4.5 11.25 4.5C10.0076 4.5 9 5.50736 9 6.75Z"
                                                fill="#0077B5" />
                                        </svg>
                                        LinkedIn</h6>
                                    <input type="text" name="sosmed_linkedin" class="form-control" autocomplete="off"
                                        value="{{ $sosmed['linkedin'] }}">
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-twitter mr-2 icon-inline text-info">
                                            <path
                                                d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                                            </path>
                                        </svg>Twitter</h6>
                                    <input type="text" name="sosmed_twitter" class="form-control" autocomplete="off"
                                        value="{{ $sosmed['twitter'] }}">
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-instagram mr-2 icon-inline text-danger">
                                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                                            </rect>
                                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                        </svg>Instagram</h6>
                                    <input type="text" name="sosmed_instagram" class="form-control" autocomplete="off"
                                        value="{{ $sosmed['instagram'] }}">
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-facebook mr-2 icon-inline text-primary">
                                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z">
                                            </path>
                                        </svg>Facebook</h6>
                                    <input type="text" name="sosmed_facebook" class="form-control" autocomplete="off"
                                        value="{{ $sosmed['facebook'] }}">
                                </li>
                            </ul>
                            <button type="submit" class="btn btn-primary">Save changes</button>

                        </form>

                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.profile.update') }}" method="post" id="form"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="kode" id="kodeField"
                                    value="{{ $data->user->kode ? $data->user->kode : '' }}">
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Full Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input autocomplete="off" type="text" class="form-control" name="full_name"
                                            id="fullNameField" value="{{ $data->full_name ? $data->full_name : '' }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input autocomplete="off" type="text" class="form-control" name="email"
                                            id="emailField" value="{{ $data->user->email ? $data->user->email : '' }}">
                                        <div class="" style="color: #dc3545" id="accessErrorEmail"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">No Telepon</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input autocomplete="off" type="text" class="form-control" name="no_telepon"
                                            id="noTeleponField" value="{{ $data->no_telepon ? $data->no_telepon : '' }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Pendidikan Terakhir</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <select class="form-select" name="pendidikan_terakhir"
                                            id="pendidikanTerakhirField">
                                            <option value="">Pilih Pendidikan Terakhir</option>
                                            <option value="SD"
                                                {{ $data->pendidikan_terakhir == 'SD' ? 'selected' : '' }}>
                                                SD</option>
                                            <option value="SMP"
                                                {{ $data->pendidikan_terakhir == 'SMP' ? 'selected' : '' }}>SMP</option>
                                            <option value="SMA"
                                                {{ $data->pendidikan_terakhir == 'SMA' ? 'selected' : '' }}>SMA</option>
                                            <option value="Diploma"
                                                {{ $data->pendidikan_terakhir == 'Diploma' ? 'selected' : '' }}>Diploma
                                            </option>
                                            <option value="Sarjana"
                                                {{ $data->pendidikan_terakhir == 'Sarjana' ? 'selected' : '' }}>Sarjana
                                            </option>
                                            <!-- Tambahkan opsi lain sesuai kebutuhan -->
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Tempat Lahir</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input autocomplete="off" type="text" class="form-control"
                                            name="tempat_lahir" id="tempatLahirField"
                                            value="{{ $data->tempat_lahir ? $data->tempat_lahir : '' }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Tanggal Lahir</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input autocomplete="off" type="text" class="form-control tanggal_lahir_input"
                                            name="tanggal_lahir" id="tanggalLahirField"
                                            value="{{ $data->tanggal_lahir ? $data->tanggal_lahir : '' }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Alamat</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <textarea type="text" name="alamat" id="alamatField" class="form-control">{{ $data->alamat ? $data->alamat : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <button type="submit" id="formSubmit" class="btn btn-primary me-1 mb-1">
                                            <span class="indicator-label">Save Changes</span>
                                            <span class="indicator-progress" style="display: none;">
                                                Tunggu Sebentar...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('administrator.profile.modal.fileinput-preview')
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

            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });

            submitButton.addEventListener("click", async function(e) {
                e.preventDefault();
                indicatorBlock();


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
                        indicatorNone();

                    return;
                } else {
                    accessErrorEmail.removeClass('invalid-feedback');
                    emailField.removeClass('is-invalid');
                    accessErrorEmail.text('');
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
                        const field = $(this);
                        indicatorNone();
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
                const emailInput = $('#emailField');
                const kodeField = $('#kodeField');
                const remoteValidationUrl = "{{ route('admin.profile.checkEmail') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            email: emailInput.val(),
                            kode: kodeField.val()
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

            // Ambil data tanggal dari database (gantilah ini dengan logika pengambilan data sesuai dengan aplikasi Anda)
            const tanggalDariDatabase =
                "{{ $data->tanggal_lahir ? $data->tanggal_lahir : '2023-09-01' }}"; // Isi ini dengan tanggal dari database jika tersedia atau null jika tidak tersedia


            const options = {
                tooltips: {
                    today: 'Go to today',
                    clear: 'Clear selection',
                    close: 'Close the picker',
                    selectMonth: 'Select Month',
                    prevMonth: 'Previous Month',
                    nextMonth: 'Next Month',
                    selectYear: 'Select Year',
                    prevYear: 'Previous Year',
                    nextYear: 'Next Year',
                    selectDecade: 'Select Decade',
                    prevDecade: 'Previous Decade',
                    nextDecade: 'Next Decade',
                    prevCentury: 'Previous Century',
                    nextCentury: 'Next Century'
                },
                accentColor: '#0090FC', // You can customize the accent color
                isDark: true, // You can enable/disable dark mode
                zIndex: 9999, // You can set z-index, default is 1000
                customClass: ['font-poppins'], // You can add custom class to the calendarify element
                onChange: (calendarify) => console.log(
                    calendarify
                ), // You can trigger whatever function in this callback property (e.g. to fetch data with passed date parameter)
                quickActions: false, // You can enable/disable quick action (Today, Tomorrow, In 2 Days) buttons
                locale: { // You can set locale for calendar
                    format: "YYYY-MM-DD", // Set Custom Format with Moment JS
                    lang: {
                        code: 'id', // Set country code (e.g. "en", "id", etc)
                        months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                            'September', 'Oktober', 'November', 'Desember'
                        ], // Or you can use locale moment.months instead
                        weekdays: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',
                            'Sabtu'
                        ], // Or you can use locale moment.weekdays instead
                        ui: { // You can set locale text for quick action buttons
                            quickActions: {
                                today: "Hari Ini",
                                tomorrow: "Besok",
                                inTwoDays: "Lusa",
                            }
                        }
                    }
                },
                startDate: tanggalDariDatabase,

            }

            const calendarify = new Calendarify('.tanggal_lahir_input', options);


            calendarify.init();
        });
    </script>
@endpush

@push('css')
    <style>
        body {
            margin-top: 20px;
            text-align: left;
        }

        .main-body {
            padding: 15px;
        }

        .card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1rem;
        }

        .gutters-sm {
            margin-right: -8px;
            margin-left: -8px;
        }

        .gutters-sm>.col,
        .gutters-sm>[class*=col-] {
            padding-right: 8px;
            padding-left: 8px;
        }

        .mb-3,
        .my-3 {
            margin-bottom: 1rem !important;
        }

        .bg-gray-300 {
            background-color: #e2e8f0;
        }

        .h-100 {
            height: 100% !important;
        }

        .shadow-none {
            box-shadow: none !important;
        }
    </style>
@endpush
