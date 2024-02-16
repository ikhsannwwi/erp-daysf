<!-- Modal Detail Produksi -->
<div class="modal fade" id="detailProduksi" tabindex="-1" aria-labelledby="detailProduksiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailProduksiLabel">Detail Produksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailProduksiBody">

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
        $('#detailProduksi').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modalBody = $('#detailProduksiBody');
            modalBody.html('<div id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
                '</div>');
            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.produksi.getDetail', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    var data = response.data;

                    var detail = data.detail;
                    console.log(detail)

                    var detailTableHTML =
                        '<table id="table-detail" class="compact table table-bordered" width="100%">' +
                        '<thead>' +
                        '<tr>' +
                        '<th style="width:50px">No</th>' +
                        '<th>Produk</th>' +
                        '<th>Jumlah Unit</th>' +
                        '<th>Satuan</th>' ;

                    detailTableHTML += '</tr></thead><tbody>';

                    for (var i = 0; i < detail.length; i++) {
                        var detail = detail[i];

                        detailTableHTML += '<tr class="detail-list">' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + (detail.formula_detail.produk ? detail.formula_detail.produk.nama : 'Not found') + '</td>' +
                            '<td class="text-end">' + formatNumber(detail.jumlah_unit) + '</td>' +
                            '<td>' + ((detail.formula_detail.satuan_id === 0) ? detail.formula_detail.produk.satuan.nama : detail.formula_detail.satuan_konversi.nama_konversi) + '</td>' +
                            '</tr>';
                    }

                    // Now you can use nominal_pembayaran and nominal_kembalian outside the if block
                    detailTableHTML +=
                        '</tbody></table>';

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
                        '<div class="title">Nomor Produksi</div>' +
                        '</div>' +
                        '<div class="col-md-9 col-7">' +
                        '<div class="data">: ' + data.no_produksi + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-3 col-5">' +
                        '<div class="title">Gudang</div>' +
                        '</div>' +
                        '<div class="col-md-9 col-7">' +
                        '<div class="data">: ' + data.gudang.nama + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-3 col-5">' +
                        '<div class="title">Produk</div>' +
                        '</div>' +
                        '<div class="col-md-9 col-7">' +
                        '<div class="data">: ' + data.produk.nama + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-3 col-5">' +
                        '<div class="title">Formula</div>' +
                        '</div>' +
                        '<div class="col-md-9 col-7">' +
                        '<div class="data">: ' + data.formula.nama + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-md-3 col-5">' +
                        '<div class="title">Jumlah Produksi</div>' +
                        '</div>' +
                        '<div class="col-md-9 col-7">' +
                        '<div class="data">: ' + formatNumber(data.jumlah_unit) + '</div>' +
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
                        '<div class="title"><b>Produksi Detail :</b></div>' +
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
