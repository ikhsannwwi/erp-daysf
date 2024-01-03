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
