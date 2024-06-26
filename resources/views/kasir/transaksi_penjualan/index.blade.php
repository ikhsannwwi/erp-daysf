@extends('kasir.layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Transaksi Penjualan</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('kasir.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">Transaksi</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('kasir.transaksi.history') }}" class="btn btn-primary" title="History"><i
                                        class="fa-solid fa-clock-rotate-left"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('kasir.transaksi.save') }}" method="post" enctype="multipart/form-data"
                                class="form" id="form" data-parsley-validate>
                                @csrf
                                @method('POST')

                                @include('kasir.transaksi_penjualan.modal.member')

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

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4">Total</td>
                                                        <td class="text-end" id="jumlah_total_transaksi-item-tfoot">Rp 0
                                                        </td>
                                                        <td><input type="hidden" name="jumlah_total_transaksi"
                                                                id="jumlah_total_transaksi"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">Pembayaran</td>
                                                        <td> <input type="text"
                                                                name="input_jumlah_total_pembayaran_transaksi"
                                                                id="input_jumlah_total_pembayaran_transaksi"
                                                                placeholder="Masukan Nominal Pembayaran"
                                                                class="form-control text-end" data-parsley-required="true"
                                                                autocomplete="off"
                                                                data-parsley-required-message="Field ini wajib diisi"
                                                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                                            <div class="" style="color: #dc3545"
                                                                id="accessErrorPembayaran"></div>
                                                        </td>
                                                        <td class="text-end" id="total_bayar-item-tfoot">Rp 0</td>
                                                        <td><input type="hidden" name="jumlah_total_pembayaran_transaksi"
                                                                id="jumlah_total_pembayaran_transaksi"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4">Kembalian</td>
                                                        <td class="text-end" id="total_kembalian-item-tfoot">Rp 0</td>
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
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <!-- Basic Tables end -->

    <!-- Template Detail -->
    <table class="template-detail" style="display: none;">
        <tr class="template-detail-list" childidx="0" style="position: relative;">
            <td class="no-item text-center" style="vertical-align:middle;"></td>
            <td class="nama-item" style="vertical-align:middle;"></td>
            <input type="hidden" class="input_id-item" name="detail[0][input_id]" id="input_id-item">
            <td class="jumlah-item" style="vertical-align:middle;">
                <input type="text" class="input_jumlah-item form-control text-end" name="detail[0][input_jumlah]"
                    placeholder="Masukan jumlah item" id="input_jumlah-item" data-parsley-required="true"
                    autocomplete="off" data-parsley-required-message="Field ini wajib diisi" data-parsley-type="number"
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
            var optionToast = {
                classname: "toast",
                transition: "fade",
                insertBefore: true,
                duration: 4000,
                enableSounds: true,
                autoClose: true,
                progressBar: true,
                sounds: {
                    info: toastMessages.path + "/sounds/info/1.mp3",
                    // path to sound for successfull message:
                    success: toastMessages.path + "/sounds/success/1.mp3",
                    // path to sound for warn message:
                    warning: toastMessages.path + "/sounds/warning/1.mp3",
                    // path to sound for error message:
                    error: toastMessages.path + "/sounds/error/1.mp3",
                },

                onShow: function(type) {
                    console.log("a toast " + type + " message is shown!");
                },
                onHide: function(type) {
                    console.log("the toast " + type + " message is hidden!");
                },

                // the placement where prepend the toast container:
                prependTo: document.body.childNodes[0],
            };

            // function scanBarcode() {
            //     // Get the file input element
            //     var input = document.getElementById('fileInput');

            //     // Check if a file is selected
            //     if (input.files.length > 0) {
            //         // Get the selected file
            //         var file = input.files[0];

            //         // Create a FileReader to read the file
            //         var reader = new FileReader();

            //         // Define the function to be executed when the file is loaded
            //         reader.onload = function(e) {
            //             // Convert the file data to base64
            //             var imageData = e.target.result.split(',')[1];

            //             // Initialize Quagga
            //             Quagga.decodeSingle({
            //                 decoder: {
            //                     readers: ['ean_reader'],
            //                 },
            //                 locate: true,
            //                 src: imageData,
            //             }, function(result) {
            //                 if (result && result.codeResult) {
            //                     // Barcode successfully decoded
            //                     var code = result.codeResult.code;
            //                     alert('Barcode: ' + code);
            //                 } else {
            //                     // No barcode found
            //                     alert('No barcode found');
            //                 }
            //             });
            //         };

            //         // Read the file as a data URL
            //         reader.readAsDataURL(file);
            //     } else {
            //         alert('Please select a file');
            //     }
            // }

            // $('.upload-barcode').on('click', function() {
            //     $('#fileInput').click();
            // });

            // // $('#fileInput').on('change', function(event) {
            // //     var file = event.target.files[0];
            // //     var formData = new FormData();
            // //     formData.append('barcode', file);
            // //     formData.append('_token', "{{ csrf_token() }}"); // Tambahkan token CSRF ke FormData

            // //     $.ajax({
            // //         url: '{{ route('kasir.transaksi.uploadBarcode') }}',
            // //         method: 'POST',
            // //         data: formData, // Gunakan objek FormData sebagai data permintaan
            // //         processData: false,
            // //         contentType: false,
            // //         success: function(response) {
            // //             // Tindakan yang sesuai setelah pengunggahan berhasil
            // //             console.log(response);
            // //         },
            // //         error: function(response) {
            // //             console.log(response);
            // //             // Tindakan yang sesuai jika permintaan AJAX gagal
            // //             console.error('Gagal Post Data.');
            // //             // optionMember.html('<option>Gagal post data</option>'); // Pastikan optionMember didefinisikan di tempat yang sesuai
            // //         }
            // //     });
            // //     console.log("Uploaded file:", file);
            // // });
            // $('#fileInput').on('change', function(event) {
            //     scanBarcode();
            // });

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
                        url: '{{ route('kasir.transaksi.getDataProduk') }}',
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
                            updateTotalHarga();
                            updateTotalPembayaran($(
                                '#input_jumlah_total_pembayaran_transaksi'
                            ).val());
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
                $(".detail-list").each(function() {

                    var another = this;

                    $(this).find(".no-item").text(index + 1)

                    search_index = $(this).attr("childidx");
                    $(this).find('input,select').each(function() {
                        this.name = this.name.replace('[' + search_index + ']',
                            '[' + index + ']');
                        $(another).attr("childidx", index);
                    });

                    $(another).find('.input_jumlah-item').on('keyup', async function() {
                        let jumlah_item = $(this).val()
                        let produk = $(another).find('.input_id-item').val()
                        let remoteValidationCheckStock = await validateRemoteCheckStock(
                            jumlah_item, produk)

                        let accessErorrJumlah = $(another).find('.error_message_jumlah-item')
                        if (!remoteValidationCheckStock.valid) {
                            // Remote validation failed, display the error message
                            accessErorrJumlah.addClass('invalid-feedback');
                            $(another).find('.input_jumlah-item').addClass('is-invalid');

                            var toasty = new Toasty(optionToast);
                            toasty.configure(optionToast);
                            toasty.error(remoteValidationCheckStock.errorMessage);

                            return;
                        } else {
                            $(another).find('.input_jumlah-item').removeClass('is-invalid');
                        }
                    })

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

                    var toasty = new Toasty(optionToast);
                    toasty.configure(optionToast);
                    toasty.error('Setidaknya harus ada salah satu detail transaksi');

                    console.log("Table body is empty");
                    indicatorNone();
                    return;
                } else {
                    inputDetail.css("color", ""); // Menghapus properti warna menggunakan jQuery
                    accessErrorDetail.removeClass('invalid-feedback');
                    inputDetail.removeClass('is-invalid');
                    accessErrorDetail.text('');
                }

                const inputPembayaran = $("#input_jumlah_total_pembayaran_transaksi");
                const jumlahPembayaran = parseRupiah(inputPembayaran.val())
                const jumlahTotal = parseRupiah($('#jumlah_total_transaksi').val())
                const accessErrorPembayaran = $("#accessErrorPembayaran");
                if (jumlahPembayaran === 0 || (jumlahPembayaran < jumlahTotal)) {
                    inputPembayaran.css("color",
                    "#dc3545"); // Mengatur warna langsung menggunakan jQuery
                    accessErrorPembayaran.addClass('invalid-feedback');
                    inputPembayaran.addClass('is-invalid');
                    accessErrorPembayaran.text(
                        'Pembayaran tidak boleh kurang dari ' + formatRupiah(jumlahTotal)
                    ); // Set the error message from the response
                    console.log('Pembayaran tidak boleh kurang dari ' + formatRupiah(jumlahTotal));

                    var toasty = new Toasty(optionToast);
                    toasty.configure(optionToast);
                    toasty.error('Pembayaran tidak boleh kurang dari ' + formatRupiah(jumlahTotal));

                    indicatorNone();
                    return;
                } else {
                    inputPembayaran.css("color", ""); // Menghapus properti warna menggunakan jQuery
                    accessErrorPembayaran.removeClass('invalid-feedback');
                    inputPembayaran.removeClass('is-invalid');
                    accessErrorPembayaran.text('');
                }

                // Check if all input_jumlah-item are still invalid
                let allInputInvalid = true;
                $(".input_jumlah-item").each(function() {
                    if ($(this).hasClass('is-invalid')) {
                        allInputInvalid = false;
                        return false; // Break the loop
                    }
                });

                if (allInputInvalid === false) {
                    var toasty = new Toasty(optionToast);
                    toasty.configure(optionToast);
                    toasty.error('Ada produk yang stok nya tidak tersedia');

                    indicatorNone();
                    return;
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
                    var toasty = new Toasty(optionToast);
                    toasty.configure(optionToast);
                    toasty.error(validationErrors.join('\n'));
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

            async function validateRemoteCheckStock(inputJumlah, inputProduk) {
                const remoteValidationUrl = "{{ route('kasir.transaksi.checkStock') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            jumlah: inputJumlah,
                            produk: inputProduk,
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
