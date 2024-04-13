@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Transaksi Penjualan
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.transaksi_penjualan') }}">Transaksi Penjualan</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.transaksi_penjualan.update') }}" method="post"
                        enctype="multipart/form-data" class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="id" value="{{ $data->id }}">

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-4 col-12">
                                    <label for="button_member" class="form-label">Member</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchMember"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputMemberName" value="{{ $data->member ? $data->member->nama : '' }}" readonly>
                                        <input type="text" class="d-none" name="toko" id="inputMember" value="{{ $data->member ? $data->member->id : '' }}">
                                        <div class="input-group-append">
                                            <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                                            <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#modalMember" id="button_member">
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
                                    <label for="triggerToko" class="form-label">Toko</label>
                                    <div class="input-group">
                                        <span class="input-group-text pb-3" id="searchToko"><i
                                                class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="inputTokoName" value="{{!empty($data->toko) ? $data->toko->nama : '-'}}"
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

                        <div id="interactive" style="width: 100%;"></div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputNama" class="form-label">Detail</label>
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            <button class="more-item btn btn-primary btn-sm" type="button"
                                                data-bs-toggle="modal" data-bs-target="#ModalProduk"><i
                                                    class="fa fa-plus"></i> Tambah Item</button>
                                        </div>
                                    </div>
                                    <table class="table" id="daftar_detail">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Item</th>
                                                <th scope="col" width="100px">Jumlah</th>
                                                <th scope="col">Harga Satuan</th>
                                                <th scope="col">Harga Total</th>
                                                <th scope="col" width="25px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $index = 0;
                                            @endphp
                                            @if (!empty($data->item))
                                                @foreach ($data->item as $row)
                                                    <tr class="detail-list" childidx="{{ $index }}"
                                                        style="position: relative;">
                                                        <td class="no-item text-center" style="vertical-align:middle;">
                                                            {{ $index + 1 }}</td>
                                                        <td class="nama-item" style="vertical-align:middle;">
                                                            {{ !empty($row->produk) ? $row->produk->nama : '-' }}</td>
                                                        <input type="hidden" class="input_id-item"
                                                            name="detail[{{ $index }}][input_id]" id="input_id-item"
                                                            value="{{ $row->produk_id }}">
                                                        <input type="hidden" class="transaksi_stok_id-item"
                                                            name="detail[{{ $index }}][transaksi_stok_id]" id="transaksi_stok_id-item"
                                                            value="{{ $row->transaksi_stok_id }}">
                                                        <td class="jumlah-item" style="vertical-align:middle;">
                                                            <input type="text" class="input_jumlah-item form-control"
                                                                name="detail[{{ $index }}][input_jumlah]"
                                                                id="input_jumlah-item" data-parsley-required="true"
                                                                autocomplete="off" value="{{ $row->jumlah }}"
                                                                data-parsley-required-message="Field ini wajib diisi"
                                                                data-parsley-type="number"
                                                                data-parsley-type-message="Field ini hanya boleh diisi dengan angka"
                                                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                        </td>
                                                        <td class="harga_satuan-item text-end"
                                                            style="vertical-align:middle;">
                                                            {{ 'Rp ' . number_format(intval($row->harga_satuan), 0, ',', '.') }}
                                                        </td>
                                                        <input type="hidden" class="input_harga_satuan-item"
                                                            name="detail[{{ $index }}][input_harga_satuan]"
                                                            value="{{ intval($row->harga_satuan) }}"
                                                            id="input_harga_satuan-item">
                                                        <td class="harga_total-item text-end"
                                                            style="vertical-align:middle;">
                                                            {{ 'Rp ' . number_format(intval($row->harga_total), 0, ',', '.') }}
                                                        </td>
                                                        <input type="hidden" class="input_harga_total-item"
                                                            name="detail[{{ $index }}][input_harga_total]"
                                                            value="{{ intval($row->harga_total) }}"
                                                            id="input_harga_total-item">
                                                        <input type="hidden" class="id-item"
                                                            name="detail[{{ $index }}][id]" id="id-item"
                                                            value="{{ $row->id }}">
                                                        <td>
                                                            <button class='btn btn-danger removeData'
                                                                data-ix="{{ $row->id }}"
                                                                data-transaksi_id="{{ $row->transaksi_id }}"
                                                                type='button'><i class='fa fa-times'
                                                                    style="color:#fff!important;"></i>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $index++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4">Total</td>
                                                <td class="text-end" id="jumlah_total_transaksi-item-tfoot">
                                                    {{ 'Rp ' . number_format(intval($data->jumlah_total), 0, ',', '.') }}
                                                </td>
                                                <td><input type="hidden" name="jumlah_total_transaksi"
                                                        id="jumlah_total_transaksi"
                                                        value="{{ intval($data->jumlah_total) }}"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">Pembayaran</td>
                                                <td> <input type="text" name="input_jumlah_total_pembayaran_transaksi"
                                                        value="{{ $data->pembayaran ? 'Rp ' . number_format(intval($data->pembayaran[0]->nominal_pembayaran), 0, ',', '.') : '' }}"
                                                        id="input_jumlah_total_pembayaran_transaksi"
                                                        placeholder="Masukan Nominal Pembayaran"
                                                        class="form-control text-end" data-parsley-required="true"
                                                        autocomplete="off"
                                                        data-parsley-required-message="Field ini wajib diisi"
                                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                </td>
                                                <td class="text-end" id="total_bayar-item-tfoot">
                                                    {{ $data->pembayaran ? 'Rp ' . number_format(intval($data->pembayaran[0]->nominal_pembayaran), 0, ',', '.') : '' }}
                                                </td>
                                                <td><input type="hidden" name="jumlah_total_pembayaran_transaksi"
                                                        value="{{ $data->pembayaran ? intval($data->pembayaran[0]->nominal_kembalian) : '' }}"
                                                        id="jumlah_total_pembayaran_transaksi"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">Kembalian</td>
                                                <td class="text-end" id="total_kembalian-item-tfoot">
                                                    {{ $data->pembayaran ? 'Rp ' . number_format(intval($data->pembayaran[0]->nominal_kembalian), 0, ',', '.') : '' }}
                                                </td>
                                                <td><input type="hidden" name="jumlah_total_kembalian_transaksi"
                                                        id="jumlah_total_kembalian_transaksi"></td>
                                            </tr>
                                        </tfoot>
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
                                <a href="{{ route('admin.transaksi_penjualan') }}"
                                    class="btn btn-danger me-1 mb-1">Cancel</a>
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
            <td class="no-item text-center" style="vertical-align:middle;"></td>
            <td class="nama-item" style="vertical-align:middle;"></td>
            <input type="hidden" class="input_id-item" name="detail[0][input_id]" id="input_id-item">
            <td class="jumlah-item" style="vertical-align:middle;">
                <input type="text" class="input_jumlah-item form-control" name="detail[0][input_jumlah]"
                    id="input_jumlah-item" data-parsley-required="true" autocomplete="off"
                    data-parsley-required-message="Field ini wajib diisi" data-parsley-type="number"
                    data-parsley-type-message="Field ini hanya boleh diisi dengan angka"
                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
            </td>
            <td class="harga_satuan-item text-end" style="vertical-align:middle;"></td>
            <input type="hidden" class="input_harga_satuan-item" name="detail[0][input_harga_satuan]"
                id="input_harga_satuan-item">
            <td class="harga_total-item text-end" style="vertical-align:middle;"></td>
            <input type="hidden" class="input_harga_total-item" name="detail[0][input_harga_total]"
                id="input_harga_total-item">
            <input type="hidden" class="id-item" name="detail[0][id]" id="id-item">
            <td>
                <button class='btn btn-danger removeData' type='button'><i class='fa fa-times'
                        style="color:#fff!important;"></i>
            </td>
        </tr>
    </table>

    @include('administrator.transaksi_penjualan.modal.produk')
    @include('administrator.transaksi_penjualan.modal.member')
    @include('administrator.transaksi_penjualan.modal.toko')
@endsection

@push('js')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/scannerdetection/1.2.0/jquery.scannerdetection.min.js"
        integrity="sha512-ZmglXekGlaYU2nhamWrS8oGQDJQ1UFpLvZxNGHwLfT0H17gXEqEk6oQBgAB75bKYnHVsKqLR3peLVqMDVJWQyA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script src="{{ asset('templateAdmin/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/parsley.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.js"
        integrity="sha512-tkMtg2br+OytX7fpdDoK34wzSUc6JcJa7aOEYUKwlSAAtqTSYVLocV4BpLBIx3RS+h+Ch6W+2lVSzNxQx4yefw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js" integrity="sha512-bCsBoYoW6zE0aja5xcIyoCDPfT27+cGr7AOCqelttLVRGay6EKGQbR6wm6SUcUGOMGXJpj+jrIpMS6i80+kZPw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"
        integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#input_jumlah_total_pembayaran_transaksi').on('keyup', function() {
                let pembayaran = this.value;

                $(this).val(
                    formatRupiah(parseRupiah(pembayaran))
                );
                updateTotalPembayaran(pembayaran);
            });

            function updateTotalPembayaran(pembayaran) {
                $('#total_bayar-item-tfoot').text(
                    formatRupiah(parseRupiah(pembayaran))
                );
                $('#jumlah_total_pembayaran_transaksi').val(
                    parseRupiah(pembayaran)
                );

                $('#total_kembalian-item-tfoot').text(
                    formatRupiah(parseRupiah(pembayaran) - parseRupiah($('#jumlah_total_transaksi-item-tfoot')
                        .text()))
                );
                $('#jumlah_total_kembalian_transaksi').val(
                    parseRupiah(pembayaran) - parseRupiah($('#jumlah_total_transaksi-item-tfoot').text())
                );
            }

            $('#daftar_detail').on('click', '.removeData', function() {
                let another = this;
                let ix = $(this).data('ix');
                let transaksi_id = $(this).data('transaksi_id');
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
                                url: "{{ route('admin.transaksi_penjualan.deleteItem') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "_method": "DELETE",
                                    "id": ix,
                                },
                                success: function() {
                                    $(another).closest('.detail-list')
                                        .remove();
                                    updateTotalHarga();
                                    updateTotalPembayaran($(
                                        '#input_jumlah_total_pembayaran_transaksi'
                                    ).val());
                                    $.ajax({
                                        type: "POST",
                                        url: "{{ route('admin.transaksi_penjualan.updateTotal') }}",
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            "_method": "PUT",
                                            "id": transaksi_id,
                                            "jumlah_total": $(
                                                '#jumlah_total_transaksi'
                                            ).val(),
                                            "jumlah_total_kembalian_transaksi": $(
                                                '#jumlah_total_kembalian_transaksi'
                                            ).val(),
                                        },
                                        success: function() {
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
                        url: '{{ route('admin.transaksi_penjualan.getDataProduk') }}',
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
                                if (row.promo && row.promo.length > 0) {
                                    let respon = `<div><span class="text-sm mx-2" style="text-decoration: line-through;">${formatRupiah(data)}</span>${formatRupiah(row.promo[0].diskon)}</div>`
                                    return respon;
                                } else {
                                    return formatRupiah(data);
                                }
                            },
                            class: 'text-end'
                        }
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

                $('#selectDataProduk').on('click', function() {
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
                                    tr_clone.find(".input_id-item").val(data_i.id);
                                    tr_clone.find(".nama-item").text(data_i.nama);
                                    tr_clone.find(".input_jumlah-item").val('');
                                    var hargaSatuan = parseFloat(data_i.promo.length > 0 ? data_i
                                        .promo[0].diskon : data_i.harga);

                                    if (!isNaN(hargaSatuan)) {
                                        var formattedHargaSatuan = formatRupiah(
                                        hargaSatuan);
                                        if (data_i.promo.length > 0) {
                                            tr_clone.find(".harga_satuan-item").html(`<span class="text-sm mx-2" style="text-decoration: line-through;">${formatRupiah(data_i.harga)}</span>${formattedHargaSatuan}`
                                            );
                                        } else {
                                            tr_clone.find(".harga_satuan-item").text(
                                                formattedHargaSatuan);
                                        }
                                    } else {
                                        console.error('Harga tidak valid.');
                                    }

                                    tr_clone.find(".input_harga_satuan-item").val(data_i
                                        .promo.length > 0 ? data_i.promo[0].diskon : data_i.harga);

                                    tr_clone.find(".input_jumlah-item").on("input",
                                        function() {
                                            var jumlah = $(this).val();
                                            var hargaSatuan = $(this).closest(
                                                ".detail-list").find(
                                                ".input_harga_satuan-item").val();
                                            var hargaTotal = jumlah * hargaSatuan;
                                            $(this).closest(".detail-list").find(
                                                ".harga_total-item").text(
                                                formatRupiah(hargaTotal)
                                            );
                                            $(this).closest(".detail-list").find(
                                                ".input_harga_total-item").val(
                                                hargaTotal);
                                            updateTotalHarga();
                                            updateTotalPembayaran($(
                                                '#input_jumlah_total_pembayaran_transaksi'
                                            ).val());
                                        });

                                    tr_clone.find(".harga_total-item").text('Rp ' + 0);
                                    tr_clone.find(".input_harga_total-item").val(0);

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
                    let transaksi_id = $(this).data('transaksi_id');
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
                                    url: "{{ route('admin.transaksi_penjualan.deleteItem') }}",
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "_method": "DELETE",
                                        "id": ix,
                                    },
                                    success: function() {
                                        $(another).closest('.detail-list')
                                            .remove();
                                        var item_id = $(this).closest(
                                            '.detail-list').find(
                                            '.input_id-item').val();
                                        var indexToRemove = isDataSelected(
                                            item_id);
                                        console.log(indexToRemove);
                                        console.log(data_selected);
                                        if (indexToRemove === false) {
                                            console.log(item_id);
                                            data_selected.splice(indexToRemove,
                                                1);
                                            rows_selected.splice(indexToRemove,
                                                1);
                                        }
                                        updateTotalHarga();
                                        updateTotalPembayaran($(
                                            '#input_jumlah_total_pembayaran_transaksi'
                                        ).val());
                                        $.ajax({
                                            type: "POST",
                                            url: "{{ route('admin.transaksi_penjualan.updateTotal') }}",
                                            data: {
                                                "_token": "{{ csrf_token() }}",
                                                "_method": "PUT",
                                                "id": transaksi_id,
                                                "jumlah_total": $(
                                                    '#jumlah_total_transaksi'
                                                ).val(),
                                                "jumlah_total_kembalian_transaksi": $(
                                                    '#jumlah_total_kembalian_transaksi'
                                                ).val(),
                                            },
                                            success: function() {
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
                                updateTotalHarga();
                                updateTotalPembayaran($(
                                    '#input_jumlah_total_pembayaran_transaksi'
                                ).val());
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
            resetData();

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

                    $(this).find(".input_jumlah-item").on("input",
                        function() {
                            var jumlah = $(this).val();
                            var hargaSatuan = $(this).closest(
                                ".detail-list").find(
                                ".input_harga_satuan-item").val();
                            var hargaTotal = jumlah * hargaSatuan;
                            $(this).closest(".detail-list").find(
                                ".harga_total-item").text(
                                formatRupiah(hargaTotal)
                            );
                            $(this).closest(".detail-list").find(
                                ".input_harga_total-item").val(
                                hargaTotal);
                            updateTotalHarga();
                            updateTotalPembayaran($(
                                '#input_jumlah_total_pembayaran_transaksi'
                            ).val());
                        });

                    index++;
                });
            }

            function updateTotalHarga() {
                var total = 0;
                $(".detail-list").each(function() {
                    var hargaTotal = parseFloat($(this).find(".input_harga_total-item")
                        .val());
                    if (!isNaN(hargaTotal)) {
                        total += hargaTotal;
                    }
                });
                $("#daftar_detail tfoot td").eq(1).text(formatRupiah(total));
                $("#jumlah_total_transaksi").val(total);
            }


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
                        'Setidaknya harus ada salah satu detail transaksi'
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
                            const errorMessage = field.parsley()
                                .getErrorsMessages().join(
                                    ', ');
                            validationErrors.push(attrName + ': ' +
                                errorMessage);
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

            function addSelectedClassByModuleIdentifiers(id) {
                var table = $('#datatablemodalMember').DataTable();

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

            $('#modalMember').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);

                // Now, you can initialize a new DataTable on the same table.
                $("#datatablemodalMember").DataTable().destroy();
                $('#datatablemodalMember tbody').remove();
                var data_table = $('#datatablemodalMember').DataTable({
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
                        url: '{{ route('admin.transaksi_penjualan.getDataMember') }}',
                        dataType: "JSON",
                        type: "GET",
                    },
                    columns: [{
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            },
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'telepon',
                            name: 'telepon'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'alamat',
                            name: 'alamat'
                        },
                    ],
                    drawCallback: function(settings) {
                        // Add 'selected' class based on the content of the input fields
                        var id = $("#inputMember").val();
                        addSelectedClassByModuleIdentifiers(id);
                    },
                });

                // click di baris tabel member
                $('#datatablemodalMember tbody').on('click', 'tr', function() {
                    var $row = $(this);

                    // Remove 'selected' class from all rows
                    $('#datatablemodalMember tbody tr').removeClass('selected');

                    // Add 'selected' class to the clicked row
                    $row.addClass('selected');

                    // Get selected row data
                    var selectedRow = data_table.row('.selected').data();

                    if (selectedRow) {
                        // Set input values based on the selected row
                        $("#inputMember").val(selectedRow.id);
                        $("#inputMemberName").val(selectedRow.nama);
                    }
                });
                // end click di baris tabel member

                // click Select button
                $('#selectDataMember').on('click', function() {
                    // Get selected row data
                    var selectedRow = data_table.row('.selected').data();

                    if (selectedRow) {
                        $("#inputMember").val(selectedRow.id);
                        $("#inputMemberName").val(selectedRow.nama);
                    }

                    $('#buttonClosemodalMember').click();
                });
                // end click Select button
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
