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
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.produk.save') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('POST')
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
                                    <input type="text" id="inputNama" class="form-control" placeholder="Masukan Nama"
                                        name="nama" autocomplete="off" data-parsley-required="true">
                                    <div class="" style="color: #dc3545" id="accessErrorNama"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputHarga" class="form-label">Harga</label>
                                    <input type="text" id="inputHarga" class="form-control" placeholder="Masukkan Harga"
                                        autocomplete="off" name="harga" data-parsley-required="true">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mandatory">
                                <div class="col-md-4 col-12">
                                    <label for="triggerSatuan" class="form-label">Satuan</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchSatuan"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputSatuanName"
                                            data-parsley-required="true" readonly>
                                        <input type="text" class="d-none" name="satuan" id="inputSatuan">
                                        <div class="input-group-append">
                                            <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                            <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#ModalSatuan" id="triggerSatuan">
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
                                    <label for="inputDeskripsi" class="form-label">Deskripsi</label>
                                    <textarea id="inputDeskripsi" class="form-control" placeholder="Masukkan Deskripsi" name="deskripsi"
                                        style="height: 150px;" data-parsley-required="true"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputFotoProduk" class="form-label">Gambar</label>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Preview</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fileinput-preview-foto_produk">
                                                <!-- Tampilkan preview gambar-gambar yang diunggah di sini -->
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <label for="inputFotoProduk" class="btn btn-light btn-file">
                                                <span class="fileinput-new">Select image</span>
                                                <input type="file" class="d-none" id="inputFotoProduk"
                                                    data-parsley-required="true" name="img[]" accept="image/*"
                                                    multiple>
                                                <!-- Tambahkan atribut "multiple" di sini -->
                                            </label>
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
                                            <input class="form-check-input" type="radio" name="status" value="1"
                                                id="inputStatus" checked data-parsley-required="true">
                                            <label class="form-check-label form-label" for="inputStatus">
                                                Aktif
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
                                                        value="1" id="inputPembelian">
                                                    <label class="form-check-label form-label" for="inputPembelian">
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
                                                        value="1" id="inputFormula">
                                                    <label class="form-check-label form-label" for="inputFormula">
                                                        Ya
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class='form-group'>
                                            <fieldset>
                                                <label class="form-label">
                                                    Tampilkan di e-commerce
                                                </label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="e_commerce"
                                                        value="1" id="inputECommerce">
                                                    <label class="form-check-label form-label" for="inputECommerce">
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
                                                        value="1" id="inputProduksi">
                                                    <label class="form-check-label form-label" for="inputProduksi">
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
                                                        value="1" id="inputPenjualan">
                                                    <label class="form-check-label form-label" for="inputPenjualan">
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
    @include('administrator.produk.modal.satuan')
@endsection

@push('js')
    <script src="{{ asset('templateAdmin/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/parsley.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"
        integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Fungsi untuk menangani perubahan pada file input
        function handleFileInputChange() {
            const newInput = this; // 'this' mengacu pada elemen file input yang dipicu oleh perubahan

            // Mendapatkan file yang baru dipilih
            const newFiles = newInput.files;

            // Lakukan sesuatu dengan file yang baru dipilih
            for (let i = 0; i < newFiles.length; i++) {
                const newFile = newFiles[i];

                // Lakukan sesuatu dengan setiap file, misalnya, tampilkan informasi di konsol
                console.log(`File Baru: ${newFile.name}, Tipe: ${newFile.type}, Ukuran: ${newFile.size} bytes`);
            }

            // Anda dapat menambahkan logika lain sesuai kebutuhan Anda di sini
        }

        // Variabel untuk menyimpan array file
        let filesArray = [];

        const inputFotoProduk = document.getElementById("inputFotoProduk");
        const previewContainerGambarLainnya = document.querySelector(".fileinput-preview-foto_produk");

        inputFotoProduk.addEventListener("change", function() {
            const files = this.files;

            // Set your desired maximum limit
            let maxLimit = 10;

            // Check if the number of selected files exceeds the limit
            if (files.length > maxLimit || filesArray.length > maxLimit || files.length > (maxLimit - filesArray.length)) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-4',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                let content = ''
                if (files.length > maxLimit) {
                    maxLimit = maxLimit
                    content = 'Tidak boleh lebih dari ' + maxLimit + ' Image.'
                } else if(filesArray.length > maxLimit) {
                    maxLimit = maxLimit - filesArray.length
                    content = 'Tidak boleh lebih dari ' + maxLimit + ' Image.'
                } else if(files.length > (maxLimit - filesArray.length)) {
                    maxLimit = maxLimit - filesArray.length
                    content = 'Tidak boleh lebih dari ' + maxLimit + ' Image.'
                }

                swalWithBootstrapButtons.fire({
                    title: 'Gagal!',
                    text: content,
                    icon: 'error',
                    timer: 2500, // 2 detik
                    showConfirmButton: false
                });
                // You may want to clear the selected files or take other actions here
                return;
            }

            // Loop melalui semua file yang dipilih
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const imageType = /^image\//;

                if (!imageType.test(file.type)) {
                    continue;
                }

                const tableRow = document.createElement("tr");

                // No column
                const noCell = document.createElement("td");
                noCell.classList.add("text-center");
                noCell.textContent = $(".fileinput-preview-foto_produk").find('tr').length + 1;
                tableRow.appendChild(noCell);

                // Preview column
                const previewCell = document.createElement("td");
                const imgContainer = document.createElement("div");
                imgContainer.classList.add("img-thumbnail-container");
                const img = document.createElement("img");
                img.classList.add("img-thumbnail");
                img.width = 200; // Sesuaikan ukuran gambar sesuai kebutuhan
                img.src = URL.createObjectURL(file);
                imgContainer.appendChild(img);
                previewCell.appendChild(imgContainer);

                // Action column
                const actionCell = document.createElement("td");
                actionCell.classList.add("text-center");
                const deleteButton = document.createElement("a");
                deleteButton.classList.add("btn", "btn-danger", "btn-sm", "deleteImg");
                deleteButton.textContent = "Hapus";

                function refreshRowNumbers() {
                    const rows = previewContainerGambarLainnya.getElementsByTagName("tr");

                    for (let i = 0; i < rows.length; i++) {
                        const noCell = rows[i].getElementsByTagName("td")[0];
                        noCell.textContent = i + 1;
                    }
                }

                deleteButton.addEventListener("click", function() {

                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success mx-4',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });

                    swalWithBootstrapButtons.fire({
                        title: 'Apakah anda yakin ingin menghapus image ini',
                        icon: 'warning',
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Saya yakin!',
                        cancelButtonText: 'Tidak, Batalkan!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {

                            // Hapus gambar saat tombol "Hapus" diklik
                            const fileIndex = filesArray.indexOf(file);
                            if (fileIndex !== -1) {
                                filesArray.splice(fileIndex, 1);

                                // Buat objek DataTransfer baru
                                const newFilesList = new DataTransfer();

                                // Tambahkan file ke objek DataTransfer
                                filesArray.forEach(file => newFilesList.items.add(file));

                                // Set nilai baru untuk file input
                                inputFotoProduk.files = newFilesList.files;

                                // Tambahkan event listener ke file input baru
                                inputFotoProduk.addEventListener("change",
                                    handleFileInputChange);
                            }

                            tableRow.remove();

                            refreshRowNumbers();
                        }
                    });
                });

                actionCell.appendChild(deleteButton);

                tableRow.appendChild(noCell);
                tableRow.appendChild(previewCell);
                tableRow.appendChild(actionCell);

                // Append the table row to the tbody
                previewContainerGambarLainnya.appendChild(tableRow);

                // Tambahkan file ke dalam array
                filesArray.push(file);
                // Buat objek DataTransfer baru
                const newFilesList = new DataTransfer();

                // Tambahkan file ke objek DataTransfer
                filesArray.forEach(file => newFilesList.items.add(file));

                // Set nilai baru untuk file input
                inputFotoProduk.files = newFilesList.files;

                // Tambahkan event listener ke file input baru
                inputFotoProduk.addEventListener("change",
                    handleFileInputChange);
            }
        });
    </script>

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
                const remoteValidationUrl = "{{ route('admin.produk.checkNama') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            nama: inputNama.val()
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
