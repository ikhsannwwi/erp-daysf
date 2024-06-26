@extends('administrator.layouts.main')
@push('css')
    <style>
        .data_disabled {
            border: 1px solid #6c757d!important;
            background-color: #6c757d!important;
            color: #fff!important;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
@endpush
@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Penyesuaian Stok
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.penyesuaian_stok_toko') }}">Penyesuaian Stok</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.penyesuaian_stok_toko.save') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label for="inputTanggal" class="form-label">Tanggal</label>
                                            <input type="text" id="inputTanggal" class="form-control datepicker"
                                                placeholder="Masukan Tanggal" name="tanggal" autocomplete="off"
                                                data-parsley-required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group mandatory">
                                    <label for="triggerToko" class="form-label">Toko</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchToko"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputTokoName"
                                            data-parsley-required="true" readonly>
                                        <input type="text" class="d-none" name="toko" id="inputToko">
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
                            <div class="col-md-4 col-12 d-flex d-none" id="migrasiTokoSection">
                                <div class="col-2 text-center pt-4"><span>=></span></div>
                                <div class="col-10">
                                    <div class="form-group mandatory">
                                        <label for="triggerTokoB" class="form-label">Toko</label>
                                        <div class="input-group">
                                            <span class="input-group-text pb-3" id="searchTokoB"><i
                                                    class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" id="inputTokoBName" readonly>
                                            <input type="text" class="d-none" name="migrasi_toko" id="inputTokoB">
                                            <div class="input-group-append">
                                                <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                                <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                    data-bs-target="#ModalTokoB" id="triggerTokoB">
                                                    Search
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 d-flex d-none" id="migrasiKeGudangSection">
                                <div class="col-2 text-center pt-4"><span>=></span></div>
                                <div class="col-10">
                                    <div class="form-group mandatory">
                                        <label for="triggerGudang" class="form-label">Gudang</label>
                                        <div class="input-group">
                                            <span class="input-group-text pb-3" id="searchGudang"><i
                                                    class="bi bi-search"></i></span>
                                            <input type="text" class="form-control" id="inputGudangName" readonly>
                                            <input type="text" class="d-none" name="migrasi_ke_gudang" id="inputGudang">
                                            <div class="input-group-append">
                                                <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                                <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                    data-bs-target="#ModalGudang" id="triggerGudang">
                                                    Search
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mandatory">
                                <div class="col-md-4 col-12">
                                    <label for="triggerProduk" class="form-label">Produk</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchProduk"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputProdukName" data-parsley-required="true" readonly>
                                        <input type="text" class="d-none" name="produk" id="inputProduk">
                                        <div class="input-group-append">
                                            <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                            <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#ModalProduk" id="triggerProduk">
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
                                    <label for="inputMetodeTransaksi" class="form-label">Metode</label>
                                    <select class="wide mb-2" name="metode" id="inputMetodeTransaksi"
                                        data-parsley-required="true">
                                        <option value="">Please Select</option>
                                        <option value="masuk">Masuk</option>
                                        <option value="keluar">Keluar</option>
                                        <option value="migrasi_toko">Migrasi Toko</option>
                                        <option value="migrasi_ke_gudang">Migrasi ke Gudang</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="form-group mandatory">
                                            <label for="inputJumlah" class="form-label">Jumlah Unit</label>
                                            <input type="text" id="inputJumlah" class="form-control"
                                                placeholder="Masukan Jumlah Unit" name="jumlah" autocomplete="off"
                                                data-parsley-required="true">
                                            <div class="" style="color: #dc3545" id="accessErorrJumlah"></div>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="form-group mandatory">
                                            <label for="triggerSatuan" class="form-label">Satuan</label>
                                            <div class="input-group">
                                                <span class="input-group-text pb-3" id="searchSatuan"><i
                                                        class="bi bi-search"></i></span>
                                                <input type="text" class="form-control" id="inputSatuanName"
                                                    data-parsley-required="true" readonly>
                                                <input type="text" class="d-none" name="satuan" id="inputSatuan">
                                                <div class="input-group-append">
                                                    <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                                    <a href="#" class="btn btn-outline-secondary data_disabled"
                                                        data-bs-toggle="modal" data-bs-target="#ModalSatuan"
                                                        id="triggerSatuan">
                                                        Search
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="inputKeterangan" class="form-label">Keterangan</label>
                                    <textarea id="inputKeterangan" class="form-control" placeholder="Masukkan Keterangan" name="keterangan"
                                        style="height: 150px;"></textarea>
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
                                <a href="{{ route('admin.penyesuaian_stok_toko') }}"
                                    class="btn btn-danger me-1 mb-1">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->

    @include('administrator.penyesuaian_stok_toko.modal.produk')
    @include('administrator.penyesuaian_stok_toko.modal.toko')
    @include('administrator.penyesuaian_stok_toko.modal.toko_b')
    @include('administrator.penyesuaian_stok_toko.modal.satuan')
    @include('administrator.penyesuaian_stok_toko.modal.gudang')
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset_administrator('assets/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('js')

    <script src="{{ asset_administrator('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script src="{{ asset('templateAdmin/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/parsley.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"
        integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#inputTanggal').daterangepicker({
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
                // showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'), 10)
            });

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
                    const remoteValidationCheckStock = await validateRemoteCheckStock();
                    const inputJumlah = $("#inputJumlah");
                    const accessErorrJumlah = $("#accessErorrJumlah");
                    if (!remoteValidationCheckStock.valid) {
                        // Remote validation failed, display the error message
                        accessErorrJumlah.addClass('invalid-feedback');
                        inputJumlah.addClass('is-invalid');

                        accessErorrJumlah.text(remoteValidationCheckStock
                            .errorMessage); // Set the error message from the response
                        indicatorNone();

                        return;
                    } else {
                        accessErorrJumlah.removeClass('invalid-feedback');
                        inputJumlah.removeClass('is-invalid');
                        accessErorrJumlah.text('');
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

            async function validateRemoteCheckStock() {
                const inputJumlah = $('#inputJumlah');
                const inputToko = $('#inputToko');
                const inputProduk = $('#inputProduk');
                const inputMetodeTransaksi = $('#inputMetodeTransaksi');
                const remoteValidationUrl = "{{ route('admin.penyesuaian_stok_toko.checkStock') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            jumlah: inputJumlah.val(),
                            toko: inputToko.val(),
                            produk: inputProduk.val(),
                            metode: inputMetodeTransaksi.val()
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

            $('#inputMetodeTransaksi').on('change', function() {
                if ($(this).val() === 'migrasi_toko') {
                    $('#migrasiTokoSection').removeClass('d-none')
                    $('#inputTokoBName').attr('data-parsley-required', true)

                    $('#migrasiKeGudangSection').addClass('d-none')
                    $('#inputGudangName').attr('data-parsley-required', false)
                    $('#inputGudangName').val('')
                    $('#inputGudang').val('')
                } else if ($(this).val() === 'migrasi_ke_gudang') {
                    $('#migrasiKeGudangSection').removeClass('d-none')
                    $('#inputGudangName').attr('data-parsley-required', true)

                    $('#migrasiTokoSection').addClass('d-none')
                    $('#inputTokoBName').attr('data-parsley-required', false)
                    $('#inputTokoBName').val('')
                    $('#inputTokoB').val('')
                } else {
                    $('#migrasiTokoSection').addClass('d-none')
                    $('#inputTokoBName').attr('data-parsley-required', false)
                    $('#inputTokoBName').val('')
                    $('#inputTokoB').val('')

                    $('#migrasiKeGudangSection').addClass('d-none')
                    $('#inputGudangName').attr('data-parsley-required', false)
                    $('#inputGudangName').val('')
                    $('#inputGudang').val('')
                }
            })

            var options = {
                searchable: true,
                placeholder: 'select',
                searchtext: 'search',
                selectedtext: 'dipilih'
            };
            var optionKategori = $('#inputMetodeTransaksi');
            var selectKategori = NiceSelect.bind(document.getElementById('inputMetodeTransaksi'), options);

            $('#inputJumlah').inputmask('currency', {
                rightAlign: false,
                prefix: '',
                digits: 0,
                groupSeparator: ',',
                radixPoint: '.',
                allowMinus: false,
                autoGroup: true,
                onBeforeMask: function(value, opts) {
                    return value.replace('', '');
                }
            });

            function formatRupiah(amount) {
                // Use Number.prototype.toLocaleString() to format the number as currency
                return 'Rp ' + Number(amount).toLocaleString('id-ID');
            }

            function parseRupiah(rupiahString) {
                // Remove currency symbol, separators, and parse as integer
                const parsedValue = parseInt(rupiahString.replace(/[^\d]/g, ''));
                return isNaN(parsedValue) ? 0 : parsedValue;
            }

            function formatNumber(number) {
                // Use Number.prototype.toLocaleString() to format the number as currency
                return Number(number).toLocaleString('id-ID');
            }

            function parseNumber(number) {
                // Remove currency symbol, separators, and parse as integer
                // Replace dot only if it exists in the number
                const parsedValue = parseInt(number.replace(/[^\d]/g, ''));
                return isNaN(parsedValue) ? 0 : parsedValue;
            }

        });
    </script>
@endpush
