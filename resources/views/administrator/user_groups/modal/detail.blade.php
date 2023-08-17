<!-- Modal Detail User Group -->
<div class="modal fade" id="detailUserGroups" tabindex="-1" aria-labelledby="detailUserGroupsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailUserGroupsLabel">Detail User Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailUserGroupsBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@push('js')
<script>
    $('#detailUserGroups').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');

        var modalBody = $('#detailUserGroupsBody');
        modalBody.html('<div id="loadingSpinner" style="display: none;">' +
            '<i class="fas fa-spinner fa-spin"></i> Sedang memuat...' +
            '</div>');
        var loadingSpinner = $('#loadingSpinner');

        loadingSpinner.show(); // Tampilkan elemen animasi

        $.ajax({
            url: '{{ route('admin.user_groups.getDetail', ':id') }}'.replace(':id', id),
            method: 'GET',
            success: function(response) {
                var data = response.data;
                var modules = response.modules;
                var permission = response.permission[data.id];

                var permissionTableHTML =
                    '<table id="table-permissions" class="compact table table-bordered" width="100%">' +
                    '<thead>' +
                    '<tr>' +
                    '<th style="width:50px">No</th>' +
                    '<th>Module</th>';

                var hasAccessColumn = false; // To track if there is at least one module with access

                for (var i = 0; i < modules.length; i++) {
                    var module = modules[i];
                    var modulePermissions = permission[module.identifiers];
                    var hasAccess = false;

                    for (var key in modulePermissions) {
                        if (modulePermissions[key] === '1') {
                            hasAccess = true;
                            hasAccessColumn = true;
                            break;
                        }
                    }

                    if (hasAccess) {
                        permissionTableHTML += '<th>Access</th>';
                        break; // Only need one column
                    }
                }

                permissionTableHTML += '</tr></thead><tbody>';

                for (var i = 0; i < modules.length; i++) {
                    var module = modules[i];
                    var modulePermissions = permission[module.identifiers];
                    var hasAccess = false;

                    for (var key in modulePermissions) {
                        if (modulePermissions[key] === '1') {
                            hasAccess = true;
                            break;
                        }
                    }

                    if (hasAccess) {
                        permissionTableHTML += '<tr class="permission-list">' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + module.name + '</td>';

                        permissionTableHTML += '<td>';

                        for (var key in modulePermissions) {
                            if (modulePermissions[key] === '1') {
                                permissionTableHTML += key + '<br>';
                            }
                        }

                        permissionTableHTML += '</td></tr>';
                    }
                }

                permissionTableHTML += '</tbody></table>';

                modalBody.html(
                    '<p>ID: ' + data.id + '</p>' +
                    '<p>Nama User Group: ' + data.name + '</p>' +
                    '<p>Status: ' + (data.status === '1' ? 'Aktif' : 'Tidak Aktif') + '</p>' +
                    '<p><strong>Permission:</strong></p>' + (hasAccessColumn ? permissionTableHTML : 'No access permissions for any module')
                );

                loadingSpinner.hide(); // Sembunyikan elemen animasi setelah data dimuat
            }
        });
    });
</script>
@endpush
