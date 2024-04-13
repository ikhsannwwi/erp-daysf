<!-- Modal Detail Pembelian -->
<div class="modal fade" id="detailPembelian" tabindex="-1" aria-labelledby="detailPembelianLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPembelianLabel">Detail Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailPembelianBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"
        integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('#detailPembelian').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modalBody = $('#detailPembelianBody');
            modalBody.html('<div id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
                '</div>');
            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.pembelian.getDetail', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    var data = response.data;

                    var details = data.detail;
                    console.log(details)

                    var detailTableHTML =
                        '<table id="table-detail" class="compact table table-bordered" width="100%">' +
                        '<thead>' +
                        '<tr>' +
                        '<th style="width:50px">No</th>' +
                        '<th>Produk</th>' +
                        '<th>Gudang</th>' +
                        '<th>Jumlah Unit</th>' +
                        '<th>Satuan</th>' +
                        '<th>Harga Satuan</th>' +
                        '<th>Keterangan</th>' +
                        '<th>Sub Total</th>';

                    detailTableHTML += '</tr></thead><tbody>';

                    for (var i = 0; i < details.length; i++) {
                        var detail = details[i];

                        const harga = parseFloat(detail.harga_satuan).toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });

                        const subtotal = parseFloat(detail.sub_total).toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });

                        detailTableHTML += '<tr class="detail-list">' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + (detail.produk ? detail.produk.nama : 'Not found') + '</td>' +
                            '<td>' + (detail.gudang ? detail.gudang.nama : '-') + '</td>' +
                            '<td class="text-end">' + formatNumber(detail.jumlah_unit) + '</td>' +
                            '<td>' + ((detail.satuan_id === 0) ? (detail.produk ? (detail.produk.satuan ? detail.produk.satuan.nama : null) : '-') : detail.satuan_konversi.nama_konversi) + '</td>' +
                            '<td class="text-end">' + harga + '</td>' +
                            '<td>' + detail.keterangan + '</td>' +
                            '<td class="text-end">' + subtotal + '</td>' +
                            '</tr>';
                    }
                    
                    const total = parseFloat(data.total).toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });

                    // Now you can use nominal_pembayaran and nominal_kembalian outside the if block
                    detailTableHTML +=
                        '</tbody><tfoot><tr><td class="text-end" colspan="7">Total</td><td class="text-end">' +
                        total +
                        '</td></tr></tfoot></table>';

                    modalBody.html(
                        '<div class="row">' +
                        '<div class="col-md-3 col-5">' +
                        '<div class="title">ID</div>' +
                        '</div>' +
                        '<div class="col-md-9 col-7">' +
                        '<div class="data">: ' + data.id + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-3 col-5">' +
                        '<div class="title">Tanggal</div>' +
                        '</div>' +
                        '<div class="col-md-9 col-7">' +
                        '<div class="data">: ' + data.tanggal + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-3 col-5">' +
                        '<div class="title">Supplier</div>' +
                        '</div>' +
                        '<div class="col-md-9 col-7">' +
                        '<div class="data">: ' + data.supplier.nama + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-3 col-5">' +
                        '<div class="title">Keterangan</div>' +
                        '</div>' +
                        '<div class="col-md-9 col-7">' +
                        '<div class="data">: ' + data.keterangan + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<br>' +
                        '<br>' +
                        '<div class="row">' +
                        '<div class="col-md-2 col-5">' +
                        '<div class="title"><b>Pembelian Detail :</b></div>' +
                        '</div>' +
                        '<div class="col-12 mt-3">' + detailTableHTML +
                        '</div>' +
                        '</div>'

                    );

                    loadingSpinner.hide(); // Sembunyikan elemen animasi setelah data dimuat
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
