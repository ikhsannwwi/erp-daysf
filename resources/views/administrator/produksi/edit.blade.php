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
                Produksi
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.produksi') }}">Produksi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.produksi.update') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" id="inputId" value="{{$data->id}}">

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
                            <div class="form-group mandatory">
                                <div class="col-md-4 col-12">
                                    <label for="triggerGudang" class="form-label">Gudang</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchGudang"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputGudangName" value="{{$data->gudang->nama}}"
                                            data-parsley-required="true" readonly>
                                        <input type="text" class="d-none" name="gudang" id="inputGudang" value="{{$data->gudang_id}}">
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

                        <div class="row">
                            <div class="form-group mandatory">
                                <div class="col-md-4 col-12">
                                    <label for="triggerProduk" class="form-label">Produk</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchProduk"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputProdukName" value="{{$data->produk->nama}}"
                                            data-parsley-required="true" readonly>
                                        <input type="text" class="d-none" name="produk" id="inputProduk" value="{{$data->produk_id}}">
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
                            <div class="form-group mandatory">
                                <div class="col-md-4 col-12">
                                    <label for="triggerFormula" class="form-label">Formula</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchFormula"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputFormulaName" value="{{$data->formula->nama}}"
                                            data-parsley-required="true" readonly>
                                        <input type="text" class="d-none" name="formula" id="inputFormula" value="{{$data->formula_id}}">
                                        <div class="input-group-append">
                                            <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                            <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#ModalFormula" id="triggerFormula">
                                                Search
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mandatory">
                                            <label for="inputJumlahProduksi" class="form-label">Jumlah Produksi</label>
                                            <input type="text" id="inputJumlahProduksi" class="form-control text-end"
                                                placeholder="Masukan Jumlah Produksi" name="jumlah_produksi" value="{{$data->jumlah_unit}}"
                                                autocomplete="off" data-parsley-required="true">
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
                                        style="height: 150px;">{{$data->keterangan}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputNama" class="form-label">Detail</label>
                                        </div>
                                    </div>
                                    <table class="table" id="daftar_detail">
                                        <thead>
                                            <tr>
                                                <th width="15px">No</th>
                                                <th width="25%">Produk</th>
                                                <th width="100px">Jumlah Unit</th>
                                                <th width="25%">Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->detail as $key => $row)
                                                <tr class="detail-list" childidx="{{$key}}" style="position: relative;">
                                                    <input type="hidden" class="input_id-item" name="detail[{{$key}}][id]" id="input_id-item" value="{{$row->id}}">
                                                    <input type="hidden" class="formula_detail_id-item" name="detail[{{$key}}][formula_detail_id]" id="formula_detail_id-item" value="{{$row->formula_detail_id}}">
                                                    <input type="hidden" class="transaksi_stok_id-item" name="detail[{{$key}}][transaksi_stok_id]" id="transaksi_stok_id-item" value="{{$row->transaksi_stok_id}}">
                                                    <td class="no-item text-center">{{$key + 1}}</td>
                                                    <td>
                                                        <span class="nama_produk-item">{{$row->produk->nama}}</span>
                                                        <input type="hidden" name="detail[{{$key}}][produk]" class="produk_id-item" value="{{$row->produk_id}}">
                                                    </td>
                                                    <td><input type="text" name="detail[{{$key}}][jumlah_unit]" class="form-control text-end jumlah_unit-item" value="{{number_format($row->jumlah_unit, 0, '.', ',')}}"
                                                            data-parsley-required="true" autocomplete="off" id="inputJumlahUnit" readonly></td>
                                                    <td>
                                                        <span class="nama_satuan-item">{{$row->formula_detail->satuan_id === 0 ? $row->formula_detail->produk->satuan->nama : $row->formula_detail->satuan_konversi->nama_konversi}}</span>
                                                        <input type="hidden" name="detail[{{$key}}][satuan]" class="satuan_id-item" data-parsley-required="true" value="{{$row->formula_detail->satuan_id}}">
                                                        <input type="hidden" name="detail[{{$key}}][jumlah_unit_formula]" class="jumlah_unit_formula-item" value="{{$row->formula_detail->jumlah_unit}}" data-parsley-required="true">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
                                <a href="{{ route('admin.produksi') }}" class="btn btn-danger me-1 mb-1">Cancel</a>
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
            <input type="hidden" class="input_id-item" name="detail[0][id]" id="input_id-item">
            <input type="hidden" class="formula_detail_id-item" name="detail[0][formula_detail_id]" id="formula_detail_id-item">
            <td class="no-item text-center"></td>
            <td>
                <span class="nama_produk-item"></span>
                <input type="hidden" name="detail[0][produk]" class="produk_id-item">
            </td>
            <td><input type="text" name="detail[0][jumlah_unit]" class="form-control text-end jumlah_unit-item"
                    data-parsley-required="true" autocomplete="off" id="inputJumlahUnit" readonly></td>
            <td>
                <span class="nama_satuan-item">Please Search</span>
                <input type="hidden" name="detail[0][satuan]" class="satuan_id-item" data-parsley-required="true">
                <input type="hidden" name="detail[0][jumlah_unit_formula]" class="jumlah_unit_formula-item" data-parsley-required="true">
            </td>
        </tr>
    </table>

    <!-- Modal Detail Formula -->
    <div class="modal fade" id="ModalFormula" tabindex="-1" aria-labelledby="ModalFormulaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalFormulaLabel">Filter Formula</h5>
                    <button type="button" id="buttonCloseFormulaModal" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalFormulaBody">
                    <table class="table" id="datatableFormulaModal">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="">Tanggal</th>
                                <th width="">Nomor Formula</th>
                                <th width="">Nama</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="selectDataFormula">Pilih Data</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
        </div>
    </div>


    @include('administrator.produksi.modal.produk')
    @include('administrator.produksi.modal.gudang')
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

            $('#inputJumlahProduksi').inputmask('currency', {
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

            $('#inputJumlahProduksi').on('keyup', function(){
                var is = this;
                $(".detail-list").each(function() {
                    var another = this;
                    let jumlah = parseNumber($(another).find('.jumlah_unit_formula-item').val()) * parseNumber($(is).val())
                    $(this).find('.jumlah_unit-item').val(formatNumber(jumlah))
                })
            })

            function addSelectedClassByFormula(id) {
                var table = $('#datatableFormulaModal').DataTable();

                // Check if the 'select' extension is available
                if ($.fn.dataTable.Select) {
                    // Check if the 'select' extension is initialized for the table
                    if (table.select) {
                        // Deselect all rows first
                        table.rows().deselect();
                    }
                }

                table.rows().nodes().to$().removeClass('selected'); // Remove 'selected' class from all rows

                if (id) {
                    table.rows().every(function() {
                        var rowData = this.data();
                        if (rowData.id === parseInt(id)) {
                            // Check if the 'select' extension is available before using 'select' method
                            if ($.fn.dataTable.Select && table.select) {
                                this.select(); // Select the row
                            }
                            $(this.node()).addClass('selected'); // Add 'selected' class
                            return false; // Break the loop
                        }
                    });
                }
            }

            $('#ModalFormula').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);

                // Now, you can initialize a new DataTable on the same table.
                $("#datatableFormulaModal").DataTable().destroy();
                $('#datatableFormulaModal tbody').remove();
                var data_table = $('#datatableFormulaModal').DataTable({
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
                        url: '{{ route('admin.produksi.getDataFormula') }}',
                        dataType: "JSON",
                        type: "GET",
                        data : function(d){
                            d.produk_id = inputProduk();
                        }
                    },
                    columns: [{
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            },
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'no_formula',
                            name: 'no_formula'
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                    ],
                    drawCallback: function(settings) {
                        // Add 'selected' class based on the content of the input fields
                        var id = $("#inputFormula").val();
                        addSelectedClassByFormula(id);
                    },
                });

                // click di baris tabel member
                $('#datatableFormulaModal tbody').on('click', 'tr', function() {
                    var $row = $(this);

                    // Remove 'selected' class from all rows
                    $('#datatableFormulaModal tbody tr').removeClass('selected');

                    // Add 'selected' class to the clicked row
                    $row.addClass('selected');

                    // Get selected row data
                    var selectedRow = data_table.row('.selected').data();

                });
                // end click di baris tabel member

                // click Select button
                $('#selectDataFormula').off().on('click', function() {
                    // Get selected row data
                    var selectedRow = data_table.row('.selected').data();

                    if (selectedRow) {
                        $("#inputFormula").val(selectedRow.id);
                        $("#inputFormulaName").val(selectedRow.nama);

                        $.ajax({
                            type: "GET",
                            url: "{{ route('admin.produksi.getFormulaDetail') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "GET",
                                "id": selectedRow.id,
                            },
                            success: function(response) {
                                let datas = response.data;
                                $("#daftar_detail tbody").empty()
                                $.each(datas, function(i, data) {
                                    var tr_clone = $(".template-detail-list")
                                        .clone();
                                    const no = 1;

                                    tr_clone.find(".no-item").text(no);
                                    tr_clone.find(".formula_detail_id-item").val(data
                                    .id);
                                    tr_clone.find(".produk_id-item").val(data
                                        .produk_id);
                                    tr_clone.find(".nama_produk-item").text(data
                                        .produk.nama);
                                    tr_clone.find(".satuan_id-item").val(data
                                        .satuan_id);
                                    tr_clone.find(".nama_satuan-item").text((data.satuan_id !== 0 ? data
                                        .satuan_konversi.nama_konversi : data.produk.satuan.nama));
                                    tr_clone.find(".jumlah_unit-item").val((data
                                        .jumlah_unit * ($('#inputJumlahProduksi').val() === '' ? 0 : parseNumber($('#inputJumlahProduksi').val()))));
                                    tr_clone.find(".jumlah_unit_formula-item").val(data
                                        .jumlah_unit );

                                    tr_clone.removeClass(
                                    "template-detail-list");
                                    tr_clone.addClass("detail-list");

                                    $("#daftar_detail").append(tr_clone);
                                    resetData();
                                });
                            }

                        });
                    }

                    $('#buttonCloseFormulaModal').click();
                });
                // end click Select button
            });

            function resetData() {

                var index = 0;
                $(".detail-list").each(function() {

                    var another = this;

                    $(this).find(".no-item").text(index + 1)

                    search_index = $(this).attr("childidx");
                    $(this).find('input,select').each(function() {
                        this.name = this.name.replace('[' + search_index + ']',
                            '[' + index + ']');
                        $(another).attr("childidx", index);
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
            
            function inputProduk(){
                return $('#inputProduk').val()
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
                const formattedNumber = Number(number).toLocaleString('id-ID');

                return formattedNumber.replace('.', ',');
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
