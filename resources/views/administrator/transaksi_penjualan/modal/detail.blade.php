<!-- Modal Detail Transaksi -->
<div class="modal fade" id="detailTransaksi" tabindex="-1" aria-labelledby="detailTransaksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailTransaksiLabel">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailTransaksiBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $('#detailTransaksi').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modalBody = $('#detailTransaksiBody');
            modalBody.html('<div id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
                '</div>');
            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.transaksi_penjualan.getDetail', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    var data = response.data;

                    // Gunakan fungsi formatRupiah di dalam kode Anda
                    var hargaFormatted = parseFloat(data.jumlah_total).toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    });

                    var items = data.item;

                    var itemTableHTML =
                        '<table id="table-item" class="compact table table-bordered" width="100%">' +
                        '<thead>' +
                        '<tr>' +
                        '<th style="width:50px">No</th>' +
                        '<th>Item</th>' +
                        '<th>Jumlah</th>' +
                        '<th>Harga Satuan</th>' +
                        '<th>Harga Total</th>';



                    itemTableHTML += '</tr></thead><tbody>';

                    for (var i = 0; i < items.length; i++) {
                        var item = items[i];

                        const harga = parseFloat(item.harga_satuan).toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });

                        const total = parseFloat(item.harga_total).toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        });

                        itemTableHTML += '<tr class="item-list">' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + (item.produk ? item.produk.nama : 'Not found') + '</td>' +
                            '<td>' + (item ? item.jumlah : '-') + '</td>' +
                            '<td class="text-end">' + harga + '</td>' +
                            '<td class="text-end">' + total + '</td>' +
                            '</tr>';
                    }

                    let nominal_pembayaran, nominal_kembalian;

                    if (data.pembayaran.length != 0) {
                        nominal_pembayaran = parseFloat(data.pembayaran[0].nominal_pembayaran)
                            .toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            });
                        nominal_kembalian = parseFloat(data.pembayaran[0].nominal_kembalian)
                            .toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            });
                    } else {
                        nominal_pembayaran = 'Rp 0';
                        nominal_kembalian = 'Rp 0';
                    }

                    // Now you can use nominal_pembayaran and nominal_kembalian outside the if block
                    itemTableHTML +=
                        '</tbody><tfoot><tr><td class="text-end" colspan="4">Total</td><td class="text-end">' +
                        hargaFormatted +
                        '</td></tr><tr><td class="text-end" colspan="4">Pembayaran</td><td class="text-end">' +
                        nominal_pembayaran +
                        '</td></tr><tr><td class="text-end" colspan="4">Kembalian</td><td class="text-end">' +
                        nominal_kembalian + '</td></tr></tfoot></table>';

                    modalBody.html(
                        '<div class="row">' +
                        '<div class="col-md-2 col-5">' +
                        '<div class="title">ID</div>' +
                        '</div>' +
                        '<div class="col-md-10 col-7">' +
                        '<div class="data">: ' + data.id + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-2 col-5">' +
                        '<div class="title">No Transaksi</div>' +
                        '</div>' +
                        '<div class="col-md-10 col-7">' +
                        '<div class="data">: ' + data.no_transaksi + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-2 col-5">' +
                        '<div class="title">Tanggal</div>' +
                        '</div>' +
                        '<div class="col-md-10 col-7">' +
                        '<div class="data">: ' + data.tanggal_transaksi + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-2 col-5">' +
                        '<div class="title">Member</div>' +
                        '</div>' +
                        '<div class="col-md-10 col-7">' +
                        '<div class="data">: ' + (data.member_id !== 0 ? data.member.nama :
                            'non-member') + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-2 col-5">' +
                        '<div class="title">Jumlah Total</div>' +
                        '</div>' +
                        '<div class="col-md-10 col-7">' +
                        '<div class="data">: ' + hargaFormatted + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<br>' +
                        '<br>' +
                        '<div class="row">' +
                        '<div class="col-md-2 col-5">' +
                        '<div class="title"><b>Detail Produk :</b></div>' +
                        '</div>' +
                        '<div class="col-12 mt-3">' + itemTableHTML +
                        '</div>' +
                        '</div>'

                    );

                    loadingSpinner.hide(); // Sembunyikan elemen animasi setelah data dimuat
                }
            });

            function formatRupiah(angka) {
                var reverse = angka.toString().split('').reverse().join('');
                var ribuan = reverse.match(/\d{1,3}/g);
                ribuan = ribuan.join('.').split('').reverse().join('');
                return 'Rp ' + ribuan + ',00';
            }
        });
    </script>
@endpush
