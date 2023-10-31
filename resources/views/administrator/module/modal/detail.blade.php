<!-- Modal Detail Module -->
<div class="modal fade" id="detailModule" tabindex="-1" aria-labelledby="detailModuleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModuleLabel">Detail Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailModuleBody">

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
        $('#detailModule').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');

            var modalBody = $('#detailModuleBody');
            modalBody.html('<div id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
                '</div>');
            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
    url: '{{ route('admin.module.getDetail', ':id') }}'.replace(':id', id),
    method: 'GET',
    success: function(response) {
        var data = response.data;
        var permissions = response.access; // Asumsikan ini adalah array objek izin

        var permissionTableHTML = '<table class="compact table table-bordered" width="100%">' +
            '<thead>' +
            '<tr>' +
            '<th>Name</th>' +
            '<th>Identifiers</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>';

        for (var i = 0; i < permissions.length; i++) {
            var permission = permissions[i];
            permissionTableHTML += '<tr>' +
                '<td>' + permission.name + '</td>' +
                '<td>' + permission.identifiers + '</td>' +
                '</tr>';
        }

        permissionTableHTML += '</tbody></table>';

        modalBody.html(
            '<p>ID: ' + data.id + '</p>' +
            '<p>Nama Modul: ' + data.name + '</p>' +
            '<p>Identifiers: ' + data.identifiers + '</p>' +
            '<p><strong>Modul Akses:</strong></p>' + (permissions.length > 0 ? permissionTableHTML : 'No access permissions for any module')
        );

        loadingSpinner.hide(); // Sembunyikan elemen animasi setelah data dimuat
    }
});




        });
    </script>
@endpush
