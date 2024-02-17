@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Formula
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.formula') }}">Formula</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.formula.update') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" id="inputId" value="{{ $data->id }}">

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label for="inputTanggal" class="form-label">Tanggal</label>
                                            <input type="text" id="inputTanggal" class="form-control datepicker" value="{{date('d-m-Y', strtotime($data->tanggal))}}"
                                                placeholder="Masukan Tanggal" name="tanggal" autocomplete="off"
                                                data-parsley-required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputNama" class="form-label">Nama</label>
                                    <input type="text" id="inputNama" class="form-control" placeholder="Masukan Nama Formula" value="{{$data->nama}}"
                                        name="nama" autocomplete="off" data-parsley-required="true">
                                    <div class="" style="color: #dc3545" id="accessErrorNama"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mandatory">
                                <div class="col-md-4 col-12">
                                    <label for="triggerProdukProduksi" class="form-label">Produk</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchProdukProduksi"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputProdukProduksiName"
                                            data-parsley-required="true" value="{{ $data->produk->nama }}" readonly>
                                        <input type="text" class="d-none" name="produk" id="inputProdukProduksi"
                                            value="{{ $data->produk_id }}">
                                        <div class="input-group-append">
                                            <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                            <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#ModalProdukProduksi" id="triggerProdukProduksi">
                                                Search
                                            </a>
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
                                        style="height: 150px;">{{ $data->keterangan }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputNama" class="form-label">Formula</label>
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            <button class="more-item btn btn-primary btn-sm" type="button"
                                                data-bs-toggle="modal" data-bs-target="#ModalProduk"><i
                                                    class="fa fa-plus"></i> Tambah Item</button>
                                        </div>
                                    </div>
                                    <div class="main--overflow-y">
                                        <table class="table" id="daftar_detail">
                                            <thead>
                                                <tr>
                                                    <th width="15px">No</th>
                                                    <th width="25%">Produk</th>
                                                    <th width="100px">Jumlah Unit</th>
                                                    <th width="25%">Satuan</th>
                                                    <th width="2%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data->detail as $key => $row)
                                                    <tr class="detail-list" childidx="{{ $key }}"
                                                        style="position: relative;">
                                                        <input type="hidden" class="id-item"
                                                            name="detail[{{ $key }}][id]" id="id-item"
                                                            value="{{ $row->id }}">
                                                        <input type="hidden" class="input_id-item"
                                                            name="detail[{{ $key }}][input_id]" id="input_id-item"
                                                            value="{{ $row->produk_id }}">
                                                        <input type="hidden" class="transaksi_stok_id-item"
                                                            name="detail[{{ $key }}][transaksi_stok_id]"
                                                            id="transaksi_stok_id-item"
                                                            value="{{ $row->transaksi_stok_id }}">
                                                        <td class="no-item text-center">{{ $key + 1 }}</td>
                                                        <td>
                                                            <span class="nama_produk-item">{{ $row->produk->nama }}</span>
                                                            <input type="hidden" name="detail[{{ $key }}][produk]"
                                                                class="produk_id-item" value="{{ $row->produk_id }}">
                                                        </td>
                                                        <td><input type="text"
                                                                name="detail[{{ $key }}][jumlah_unit]"
                                                                class="form-control text-end jumlah_unit-item"
                                                                value="{{ $row->jumlah_unit }}" data-parsley-required="true"
                                                                autocomplete="off" id="inputJumlahUnit"></td>
                                                        <td>
                                                            <span
                                                                class="nama_satuan-item">{{ $row->satuan_id === 0 ? $row->produk->satuan->nama : $row->satuan_konversi->nama_konversi }}</span>
                                                            <a class="btn btn-outline-primary btn-sm float-end searchSatuan"
                                                                data-bs-toggle="modal" data-bs-target="#ModalSatuan"><i
                                                                    class="bi bi-search"></i></a>
                                                            <input type="hidden" name="detail[{{$key}}][satuan]"
                                                                value="{{ $row->satuan_id }}" class="satuan_id-item"
                                                                data-parsley-required="true">
                                                        </td>
                                                        <td class="text-center"><a href="javascript:void(0)"
                                                                class="btn btn-outline-danger removeData" style="display: {{count($data->detail) > 1 ? 'block' : 'none'}}"
                                                                data-ix="{{ $row->id }}"
                                                                data-formula_id="{{ $row->formula_id }}"><i
                                                                    class='fa fa-times'></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="" style="color: #dc3545" id="accessErrorDetail"></div>
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
                                <a href="{{ route('admin.formula') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->

    <!-- Template Detail -->
    <table class="template-detail" style="display: none;">
        <tr class="template-detail-list" childidx="0" style="position: relative;">
            <input type="hidden" class="id-item" name="detail[0][id]" id="id-item">
            <input type="hidden" class="input_id-item" name="detail[0][input_id]" id="input_id-item">
            <input type="hidden" class="transaksi_stok_id-item" name="detail[0][transaksi_stok_id]"
                id="transaksi_stok_id-item">
            <td class="no-item text-center"></td>
            <td>
                <span class="nama_produk-item"></span>
                <input type="hidden" name="detail[0][produk]" class="produk_id-item">
            </td>
            <td><input type="text" name="detail[0][jumlah_unit]" class="form-control text-end jumlah_unit-item"
                    data-parsley-required="true" autocomplete="off" id="inputJumlahUnit"></td>
            <td>
                <span class="nama_satuan-item">Please Search</span>
                <a class="btn btn-outline-primary btn-sm float-end searchSatuan" data-bs-toggle="modal"
                    data-bs-target="#ModalSatuan"><i class="bi bi-search"></i></a>
                <input type="hidden" name="detail[0][satuan]" class="satuan_id-item" data-parsley-required="true">
            </td>
            <td class="text-center"><a href="javascript:void(0)" class="btn btn-outline-danger removeData" data-ix=""><i
                        class='fa fa-times'></a></td>
        </tr>
    </table>

    <!-- Modal Detail Produk -->
    <div class="modal fade" id="ModalProduk" tabindex="-1" aria-labelledby="ModalProdukLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalProdukLabel">Filter Produk</h5>
                    <button type="button" id="buttonCloseProdukModal" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalProdukBody">
                    <table class="table" id="datatableProdukModal">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="">Kategori</th>
                                <th width="">Kode</th>
                                <th width="">Nama</th>
                                <th width="">Harga</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="selectDataProduk">Pilih Data</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
        </div>
    </div>


    @include('administrator.formula.modal.produk')
    @include('administrator.formula.modal.satuan')
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

            resetData()

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

            submitButton.addEventListener("click", async function(e) {
                e.preventDefault();

                indicatorBlock();

                const tbody = document.querySelector("#daftar_detail tbody");
                const inputDetail = $("#daftar_detail");
                const accessErrorDetail = $("#accessErrorDetail");
                if (!tbody || !tbody.childElementCount) {
                    inputDetail.css("color", "#dc3545"); // Mengatur warna langsung menggunakan jQuery
                    accessErrorDetail.addClass('invalid-feedback');
                    inputDetail.addClass('is-invalid');
                    accessErrorDetail.text(
                        'Setidaknya harus ada salah satu detail formula'
                    ); // Set the error message from the response
                    console.log("Table body is empty");
                    indicatorNone();
                    return;
                } else {
                    inputDetail.css("color", ""); // Menghapus properti warna menggunakan jQuery
                    accessErrorDetail.removeClass('invalid-feedback');
                    inputDetail.removeClass('is-invalid');
                    accessErrorDetail.text('');
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

            $('#daftar_detail').on('click', '.removeData', function() {
                let another = this;
                let ix = $(this).data('ix');
                let formula_id = $(this).data('formula_id');
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-4',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Apakah anda yakin ingin menghapus data ini',
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Saya yakin!',
                    cancelButtonText: 'Tidak, Batalkan!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (ix != '') {
                            $.ajax({
                                type: "DELETE",
                                url: "{{ route('admin.formula.deleteDetail') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "_method": "DELETE",
                                    "id": ix,
                                },
                                success: function() {
                                    $(another).closest('.detail-list')
                                        .remove();
                                    resetData();

                                    swalWithBootstrapButtons
                                        .fire({
                                            title: 'Berhasil!',
                                            text: 'Data berhasil diupdate.',
                                            icon: 'success',
                                            timer: 1500, // 2 detik
                                            showConfirmButton: false
                                        });
                                }
                            });
                        }
                    }
                });
            });

            $('#ModalProduk').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var rows_selected = [];
                var data_selected = [];
                console.log(data_selected);

                // Now, you can initialize a new DataTable on the same table.
                $("#datatableProdukModal").DataTable().destroy();
                $('#datatableProdukModal tbody').remove();
                var data_table_produk = $('#datatableProdukModal').DataTable({
                    "oLanguage": {
                        "oPaginate": {
                            "sFirst": "<i class='ti-angle-left'></i>",
                            "sPrevious": "&#8592;",
                            "sNext": "&#8594;",
                            "sLast": "<i class='ti-angle-right'></i>"
                        }
                    },
                    processing: true,
                    serverSide: true,
                    order: [
                        [0, 'asc']
                    ],
                    // scrollX: true, // Enable horizontal scrolling
                    ajax: {
                        url: '{{ route('admin.formula.getDataProduk') }}',
                        dataType: "JSON",
                        type: "GET",
                    },
                    'columnDefs': [{
                        'targets': 0,
                        'className': 'text-center',
                        'orderable': false,
                    }],
                    select: {
                        style: 'multi', // Ganti 'os' dengan 'multi' jika Anda ingin memilih banyak baris
                    },
                    columns: [{
                            // Mengubah fungsi render menjadi kolom checkbox
                            render: function(data, type, row, meta) {
                                return '<input type="checkbox" class="select-checkbox">';
                            },
                            // Menambahkan properti orderable agar kolom tidak dapat diurutkan
                            orderable: false
                        },
                        {
                            data: 'kategori.nama',
                            name: 'kategori.nama'
                        },
                        {
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'harga',
                            name: 'harga',
                            render: function(data, type, row, meta) {
                                // Assuming data is a number, you can use toLocaleString to format it
                                return formatRupiah(data);
                            },
                            class: 'text-end'
                        },
                    ],
                    'rowCallback': function(row, data, dataIndex) {
                        // Get row ID
                        var rowId = data.id;

                        // If row ID is in the list of selected row IDs
                        if ($.inArray(rowId, rows_selected) !== -1) {
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                            $(row).addClass('selected');
                        }

                        // Disable rows that are already cloned
                        var clonedDataIds = [];
                        $('#daftar_detail').find('.input_id-item').each(function() {
                            clonedDataIds.push($(this).val());
                        });

                        if ($.inArray(rowId.toString(), clonedDataIds) !== -1) {
                            $(row).find('input[type="checkbox"]').prop('disabled', true);
                            $(row).find('input[type="checkbox"]').prop('checked', true);
                        }
                    }
                });

                $('#datatableProdukModal tbody').on('click', 'input[type="checkbox"]', function(e) {
                    var row = $(this).closest('tr');
                    var data = data_table_produk.row(row).data();
                    var rowId = data.id;
                    var data_index = findDataIndexById(rowId);

                    if (this.checked && data_index === -1) {
                        rows_selected.push(rowId);
                        if (!isDataSelected(data)) {
                            data_selected.push(data);
                        }
                    } else if (!this.checked && data_index !== -1) {
                        rows_selected.splice(data_index, 1);
                        data_selected.splice(data_index, 1);
                    }

                    if (this.checked) {
                        row.addClass('selected');
                    } else {
                        row.removeClass('selected');
                    }

                    e.stopPropagation();
                });

                $('#datatableProdukModal tbody').on('click', 'tr', function(e) {
                    var checkbox = $(this).find('input[type="checkbox"]');
                    if (!checkbox.is(':disabled')) {
                        var isChecked = checkbox.prop('checked');
                        checkbox.prop('checked', !isChecked).trigger('change');

                        var row = $(this).closest('tr');
                        var data = data_table_produk.row(row).data();
                        var rowId = data.id;
                        var data_index = findDataIndexById(rowId);

                        if (!isChecked && data_index === -1) {
                            rows_selected.push(rowId);
                            if (!isDataSelected(data)) {
                                data_selected.push(data);
                            }
                        } else if (isChecked && data_index !== -1) {
                            rows_selected.splice(data_index, 1);
                            data_selected.splice(data_index, 1);
                        }

                        if (!isChecked) {
                            row.addClass('selected');
                        } else {
                            row.removeClass('selected');
                        }

                        e.stopPropagation();
                    }
                });

                $('#selectDataProduk').off().on('click', function() {
                    if ($('#datatableProdukModal').DataTable().rows().nodes().to$().find(
                            'input[type="checkbox"]:not(:disabled)').is(':checked')) {
                        $('#datatableProdukModal tbody tr.selected').each(function() {
                            const data_i = data_table_produk.row($(this)).data();
                            if (data_i && data_i.id) {
                                console.log(data_i);

                                var daftar_detail_ids = [];
                                $('#daftar_detail').find('.input_id-item').each(function() {
                                    daftar_detail_ids.push($(this).val());
                                });

                                if (!daftar_detail_ids.includes(data_i.id.toString())) {
                                    var tr_clone = $(".template-detail-list").clone();
                                    const no = 1;

                                    tr_clone.find(".no-item").text(no);
                                    tr_clone.find(".id-item").val(0);
                                    tr_clone.find(".input_id-item").val(data_i.id);
                                    tr_clone.find(".produk_id-item").val(data_i.id);
                                    tr_clone.find(".nama_produk-item").text(data_i.nama);
                                    tr_clone.find(".jumlah_unit-item").val('');

                                    tr_clone.removeClass("template-detail-list");
                                    tr_clone.addClass("detail-list");

                                    var selectedRow = $('#datatableProdukModal').find(
                                        'tr[data-id="' + data_i.id + '"]');
                                    selectedRow.find('input[type="checkbox"]').prop(
                                        'checked', true);
                                    selectedRow.find('input[type="checkbox"]').prop(
                                        'disabled', true);

                                    $("#daftar_detail").append(tr_clone);

                                    resetData();

                                    $('#buttonCloseProdukModal').click();
                                    $('#datatableProdukModal').DataTable().rows('.selected')
                                        .nodes().to$().find('input[type="checkbox"]').prop(
                                            'disabled', true);
                                }
                            }
                        });
                    } else {
                        Swal.fire(
                            'Warning!',
                            'Harap pilih setidaknya satu item untuk melanjutkan!',
                            'warning'
                        );
                    }
                });


                $('#daftar_detail').on('click', '.removeData', function() {
                    let another = this;
                    let ix = $(this).data('ix');
                    let formula_id = $(this).data('formula_id');
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success mx-4',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });

                    swalWithBootstrapButtons.fire({
                        title: 'Apakah anda yakin ingin menghapus data ini',
                        icon: 'warning',
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Saya yakin!',
                        cancelButtonText: 'Tidak, Batalkan!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (ix != '') {
                                $.ajax({
                                    type: "DELETE",
                                    url: "{{ route('admin.formula.deleteDetail') }}",
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "_method": "DELETE",
                                        "id": ix,
                                    },
                                    success: function() {
                                        $(another).closest('.detail-list')
                                            .remove();
                                        resetData();

                                        swalWithBootstrapButtons
                                            .fire({
                                                title: 'Berhasil!',
                                                text: 'Data berhasil diupdate.',
                                                icon: 'success',
                                                timer: 1500, // 2 detik
                                                showConfirmButton: false
                                            });
                                    }
                                });
                            } else {
                                $(this).closest('.detail-list').remove();
                                var item_id = $(this).closest('.detail-list').find(
                                    '.input_id-item').val();
                                var indexToRemove = isDataSelected(item_id);
                                console.log(indexToRemove);
                                console.log(data_selected);
                                if (indexToRemove === false) {
                                    console.log(item_id);
                                    data_selected.splice(indexToRemove, 1);
                                    rows_selected.splice(indexToRemove, 1);
                                }
                                resetData();
                            }
                        }
                    });
                });


                function findDataIndexById(id) {
                    for (var i = 0; i < rows_selected.length; i++) {
                        if (rows_selected[i] === id) {
                            return i;
                        }
                    }
                    return -1;
                }

                function isDataSelected(data) {
                    return data_selected.some(function(item) {
                        return item.id === data.id;
                    });
                }

                //end click di baris tabel barang
            });

            function resetData() {

                var index = 0;
                if ($('#daftar_detail tbody tr').length === 1) {
                    $('.removeData').css('display', 'none');
                }else{
                    $('.removeData').css('display', 'block');
                }
                
                $(".detail-list").each(function() {

                    var another = this;

                    $(this).find(".no-item").text(index + 1)

                    search_index = $(this).attr("childidx");
                    $(this).find('input,select').each(function() {
                        this.name = this.name.replace('[' + search_index + ']',
                            '[' + index + ']');
                        $(another).attr("childidx", index);
                    });

                    $(this).find('.searchSatuan').attr("data-childidx", index);
                    $(this).find('.searchSatuan').attr("data-produk_id", $(another).find('.produk_id-item')
                        .val());
                    $(this).find('.searchSatuan').off().on('click', function() {
                        let key = $(this).data(
                            'childidx'); // Use 'this' to refer to the clicked element
                        console.log(key);
                        $('#selectDataSatuan').removeData('childidx');
                        $('#selectDataSatuan').attr("data-childidx", key);

                        $('#selectDataSatuan').removeData('produk_id');
                        $('#selectDataSatuan').attr("data-produk_id", $(another).find(
                            '.produk_id-item').val());
                    });

                    $(this).find('.jumlah_unit-item').inputmask('currency', {
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
                    index++;
                });
            }

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
