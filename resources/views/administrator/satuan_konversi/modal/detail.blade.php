<!-- Modal Detail Satuan Konversi -->
<div class="modal fade" id="detailSatuanKonversi" tabindex="-1" aria-labelledby="detailSatuanKonversiLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailSatuanKonversiLabel">Detail Satuan Konversi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailSatuanKonversiBody">

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
        $('#detailSatuanKonversi').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modalBody = $('#detailSatuanKonversiBody');
            modalBody.html('<div id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
                '</div>');
            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.satuan_konversi.getDetail', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    var data = response.data;

                    modalBody.html(
                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">ID</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.id + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Produk</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.produk.nama + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Status</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + (data.status === 1 ? 'Aktif' : 'Tidak Aktif') +
                        '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Keterangan</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.keterangan + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<br>' +
                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Konversi</div>' +
                        '</div>' +
                        '<div class="col-12">' +
                        '<div class="row">' +
                        '<div class="col-2">' +
                        '<div class="data text-end">' + parseFloat(data.kuantitas_konversi) + '</div>' +
                        '</div>' +
                        '<div class="col-2">' +
                        '<div class="data">' + data.nama_konversi + '</div>' +
                        '</div>' +
                        '<div class="col-1 text-center">' +
                        '<div class="data ms-2">=</div>' +
                        '</div>' +
                        '<div class="col-2">' +
                        '<div class="data text-end">' + parseFloat(data.kuantitas_satuan) + '</div>' +
                        '</div>' +
                        '<div class="col-2">' +
                        '<div class="data">' + data.produk.satuan.nama + '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );

                    loadingSpinner.hide(); // Sembunyikan elemen animasi setelah data dimuat
                }
            });
        });
    </script>
@endpush
