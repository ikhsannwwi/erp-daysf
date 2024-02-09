@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Produk
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.produk') }}">Produk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.produk.update') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="inputId" value="{{$data->id}}">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputKategori" class="form-label">Kategori</label>
                                    <select class="wide mb-2" name="kategori" id="inputKategori"
                                        data-parsley-required="true">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputNama" class="form-label">Nama</label>
                                    <input type="text" id="inputNama" class="form-control" placeholder="Masukan Nama" value="{{$data->nama}}"
                                        name="nama" autocomplete="off" data-parsley-required="true">
                                    <div class="" style="color: #dc3545" id="accessErrorNama"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputHarga" class="form-label">Harga</label>
                                    <input type="text" id="inputHarga" class="form-control" placeholder="Masukkan Harga" autocomplete="off" value="{{ rtrim(number_format($data->harga, 2, ',', '.'), '0') }}"
                                        name="harga" data-parsley-required="true">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputDeskripsi" class="form-label">Deskripsi</label>
                                    <textarea id="inputDeskripsi" class="form-control" placeholder="Masukkan Deskripsi" name="deskripsi"
                                        style="height: 150px;" data-parsley-required="true">{{$data->deskripsi}}</textarea>
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
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3">
                                        <div class='form-group'>
                                            <fieldset>
                                                <label class="form-label">
                                                    Pembelian
                                                </label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="pembelian"
                                                        value="1" id="flexRadioDefault1" {{ $data->pembelian === 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label form-label" for="flexRadioDefault1">
                                                        Ya
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class='form-group'>
                                            <fieldset>
                                                <label class="form-label">
                                                    Formula
                                                </label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="formula"
                                                        value="1" id="flexRadioDefault1" {{ $data->formula === 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label form-label" for="flexRadioDefault1">
                                                        Ya
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class='form-group'>
                                            <fieldset>
                                                <label class="form-label">
                                                    Produksi
                                                </label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="produksi"
                                                        value="1" id="flexRadioDefault1" {{ $data->produksi === 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label form-label" for="flexRadioDefault1">
                                                        Ya
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class='form-group'>
                                            <fieldset>
                                                <label class="form-label">
                                                    Penjualan
                                                </label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="penjualan"
                                                        value="1" id="flexRadioDefault1" {{ $data->penjualan === 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label form-label" for="flexRadioDefault1">
                                                        Ya
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
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
                                <a href="{{ route('admin.produk') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js" integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#inputHarga').inputmask('currency', {
                rightAlign: false,
                prefix: 'Rp ',
                digits: 0,
                groupSeparator: '.',
                radixPoint: ',',
                allowMinus: false,
                autoGroup: true,
                onBeforeMask: function(value, opts) {
                    return value.replace('Rp ', '');
                }
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
                const remoteValidationResultNama = await validateRemoteNama();
                const inputNama = $("#inputNama");
                const accessErrorNama = $("#accessErrorNama");
                if (!remoteValidationResultNama.valid) {
                    // Remote validation failed, display the error message
                    accessErrorNama.addClass('invalid-feedback');
                    inputNama.addClass('is-invalid');

                    accessErrorNama.text(remoteValidationResultNama
                        .errorMessage); // Set the error message from the response
                    indicatorNone();

                    return;
                } else {
                    accessErrorNama.removeClass('invalid-feedback');
                    inputNama.removeClass('is-invalid');
                    accessErrorNama.text('');
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

            async function validateRemoteNama() {
                const inputNama = $('#inputNama');
                const inputId = $('#inputId');
                const remoteValidationUrl = "{{ route('admin.produk.checkNama') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            nama: inputNama.val(),
                            id: inputId.val(),
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



            var options = {
                searchable: true,
                placeholder: 'select',
                searchtext: 'search',
                selectedtext: 'dipilih'
            };
            var optionKategori = $('#inputKategori');
            var selectKategori = NiceSelect.bind(document.getElementById('inputKategori'), options);


            optionKategori.html(
                '<option id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin">' +
                '</i> Sedang memuat...</option>'
            );

            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.produk.getKategori') }}',
                method: 'GET',
                success: function(response) {
                    var data = response.kategori;
                    var optionsHtml = ''; // Store the generated option elements

                    // Iterate through each Data in the response data
                    for (var i = 0; i < data.length; i++) {
                        var dataKategori = data[i];
                        optionsHtml += '<option value="' + dataKategori.id + '">' + dataKategori
                            .nama + '</option>';
                    }

                    // Construct the final dropdown HTML
                    var finalDropdownHtml = '<option value="">Pilih Data</option>' + optionsHtml;

                    optionKategori.html(finalDropdownHtml);

                    // Set the selected option based on the value of $data->id
                    if ('{{ $data->kategori }}') {
                        optionKategori.val('{{ $data->kategori->id ?? '' }}');
                    } else {
                        optionKategori.prepend('<option value="" selected>Pilih Data</option>');
                    }

                    selectKategori.update();

                    loadingSpinner.hide(); // Hide the loading spinner after data is loaded
                },
                error: function() {
                    // Handle the error case if the AJAX request fails
                    console.error('Gagal memuat data Data.');
                    optionKategori.html('<option>Gagal memuat data</option>')
                    loadingSpinner
                        .hide(); // Hide the loading spinner even if there's an error
                }
            });

        });
    </script>
@endpush
