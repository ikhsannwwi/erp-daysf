<div class="col-md-6 col-12">
    <label for="inputModule">Module</label>
    <div class="row">
        <div class="col-8" style="padding-right: 0;"> <!-- Menggunakan col-8 agar input lebih lebar dan menghapus padding kanan -->
            <input type="text" class="form-control" id="inputModuleName" readonly>
            <input type="text" class="d-none" name="module" id="inputModule">
        </div>
        <div class="col-4" style="padding-left: 0;"> <!-- Menggunakan col-4 agar tombol "Search" lebih kecil dan menghapus padding kiri -->
            <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModuleLogSystem">
                Search
            </a>
        </div>
    </div>
</div>



<!-- Modal Detail Module -->
<div class="modal fade" id="filterModuleLogSystem" tabindex="-1" aria-labelledby="filterModuleLogSystemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModuleLogSystemLabel">Filter Module</h5>
                <button type="button" id="buttonCloseModuleModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="filterModuleLogSystemBody">
                <table class="table" id="datatableModuleModal">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="">Identifiers</th>
                            <th width="">Nama</th>
                        </tr>
                    </thead>
                </table>
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
        $('#filterModuleLogSystem').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            // Now, you can initialize a new DataTable on the same table.
            $("#datatableModuleModal").DataTable().destroy(); 
            $('#datatableModuleModal tbody').remove(); 
            var data_table_module = $('#datatableModuleModal').DataTable({
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
                    url: '{{ route('admin.logSystems.getDataModule') }}',
                    dataType: "JSON",
                    type: "GET",
                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'identifiers',
                        name: 'identifiers'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                ],
            });
            //click di baris tabel barang
        $('#datatableModuleModal tbody').on('click', 'tr', function () {
                    
            var data = data_table_module.row(this).data();
			
			$("#inputModule").val(data.identifiers);
			$("#inputModuleName").val(data.name);
                    
            $('#buttonCloseModuleModal').click();

        }); 
		//end click di baris tabel barang
        });
    </script>
@endpush
