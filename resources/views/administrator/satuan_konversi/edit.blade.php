@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Satuan Konversi
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.satuan_konversi') }}">Satuan Konversi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.satuan_konversi.update') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" id="inputId" value="{{$data->id}}">

                        <div class="row">
                            <div class="form-group mandatory">
                                <div class="col-md-4 col-12">
                                    <label for="triggerProduk" class="form-label">Produk</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchProduk"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputProdukName" value="{{$data->produk->nama}}"
                                            data-parsley-required="true" readonly>
                                        <input type="text" class="d-none" name="produk" id="inputProduk"  value="{{$data->produk_id}}">
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
                            <div class="col-12">
                                <label for="inputNamaKonversi" class="form-label">Satuan <i class="bi bi-info-circle-fill ms-1 pt-3" style="cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Kuantitas Konversi "></i></label>
                                <div class="row">
                                    <div class="col-md-2 col-2">
                                        <div class="form-group mandatory">
                                            <input type="text" id="inputKuantitasKonversi" class="form-control text-end"
                                                placeholder="Kuantitas Konversi" name="kuantitas_konversi" autocomplete="off"
                                                data-parsley-required="true" readonly value="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <div class="form-group mandatory">
                                            <input type="text" id="inputNamaKonversi" class="form-control"
                                                placeholder="Nama Satuan Konversi" name="nama_konversi"
                                                autocomplete="off" data-parsley-required="true"  value="{{$data->nama_konversi}}">
                                        </div>
                                    </div>
                                    <div class="col-1 text-center p-2">
                                        <span>=</span>
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <div class="form-group mandatory">
                                            <input type="text" id="inputKuantitasSatuan" class="form-control text-end"
                                                placeholder="Kuantitas Satuan" name="kuantitas_satuan" autocomplete="off"  value="{{$data->kuantitas_satuan}}"
                                                data-parsley-required="true">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <div class="form-group mandatory">
                                            <input type="text" id="inputNamaSatuan" class="form-control"
                                                placeholder="Nama Satuan" name="nama_satuan" autocomplete="off"  value="{{$data->produk->satuan->nama}}"
                                                data-parsley-required="true" readonly>
                                                <input type="hidden" id="inputSatuanId" name="satuan_id" value="{{$data->produk->satuan_id}}">
                                        </div>
                                    </div>
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
                                <a href="{{ route('admin.satuan_konversi') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->
    @include('administrator.satuan_konversi.modal.produk')
@endsection

@push('js')
    <script src="{{ asset('templateAdmin/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/parsley.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"
        integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                '[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            $('#inputKuantitasSatuan').inputmask('currency', {
                rightAlign: false,
                prefix: '',
                digits: 4,
                groupSeparator: ',',
                radixPoint: '.',
                allowMinus: false,
                autoGroup: true,
                onBeforeMask: function(value, opts) {
                    return value.replace(' ', '');
                }
            });

            //validate parsley form
            const form = document.getElementById("form");
            const validator = $(form).parsley();

            const submitButton = document.getElementById("formSubmit");

            submitButton.addEventListener("click", async function(e) {
                e.preventDefault();

                indicatorBlock();

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
        });
    </script>
@endpush
