<!-- Modal Detail User -->
<div class="modal fade" id="detailLogSystem" tabindex="-1" aria-labelledby="detailLogSystemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailLogSystemLabel">Detail Log System</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailLogSystemBody">

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
        $('#detailLogSystem').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modalBody = $('#detailLogSystemBody');
            modalBody.html('<div id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
                '</div>');
            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.logSystems.getDetail', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    var data = response.data;
                    $dataArray = JSON.parse(data.data);

                    var dataJson = JSON.stringify($dataArray, null,
                        4); // Mengubah objek JSON menjadi string dengan indentasi

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
                        '<div class="title">User</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.user.name + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Module</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.module + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Action</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.action + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Tanggal</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.created_at + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Ip Adress</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.ip_address + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Device</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.device + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Browser Name</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.browser_name + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row">' +
                        '<div class="col-5">' +
                        '<div class="title">Browser Version</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.browser_version + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="row mb-4">' +
                        '<div class="col-5">' +
                        '<div class="title">Data ID</div>' +
                        '</div>' +
                        '<div class="col-7">' +
                        '<div class="data">: ' + data.data_id + '</div>' +
                        '</div>' +
                        '</div>' +

                        '<pre>Data JSON: ' + dataJson +
                        '</pre>' // Gunakan <pre> untuk melestarikan format JSON
                    );

                    loadingSpinner.hide(); // Sembunyikan elemen animasi setelah data dimuat
                }
            });
        });
    </script>
@endpush
