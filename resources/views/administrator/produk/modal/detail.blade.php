<!-- Modal Detail User -->
<div class="modal fade" id="detailProduk" tabindex="-1" aria-labelledby="detailProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailProdukLabel">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailProdukBody">

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
        $('#detailProduk').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modalBody = $('#detailProdukBody');
            modalBody.html('<div id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
                '</div>');
            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.produk.getDetail', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    var data = response.data;

                    // Gunakan fungsi formatRupiah di dalam kode Anda
                    var hargaFormatted = formatRupiah(data.harga);

                    // Menghapus dua 0 di belakang
                    hargaFormatted = hargaFormatted.replace(",00", "");

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
                        '<div class="title">Kategori</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.kategori.nama + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Nama</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.nama + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Kode</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.kode + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Harga</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + hargaFormatted + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Satuan</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + (data.satuan ? data.satuan.nama : '') + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Deskripsi</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.deskripsi + '</div>' +
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

                        '<div class="row mt-4">' +
                        '<div class="col-12">' +
                        '<div class="title">Barcode  :</div>' +
                        '</div>' +
                        '<div class="col-12 text-center bg-white pt-3"></br><div class="d-flex justify-content-center" style="position:relative;">' +
                        data.barcode + '</div></br> <p class="text-black">' + data.kode + '</div>' +
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
