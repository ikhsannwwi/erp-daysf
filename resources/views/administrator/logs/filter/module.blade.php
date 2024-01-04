<div class="col-md-6 col-12">
    <label for="inputModule">Module</label>
    <div class="row">
        <div class="col-8" style="padding-right: 0;">
            <!-- Menggunakan col-8 agar input lebih lebar dan menghapus padding kanan -->
            <input type="text" class="form-control" id="inputModuleName" readonly>
            <input type="text" class="d-none" name="module" id="inputModule">
        </div>
        <div class="col-4" style="padding-left: 0;">
            <!-- Menggunakan col-4 agar tombol "Search" lebih kecil dan menghapus padding kiri -->
            <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                data-bs-target="#filterModuleLogSystem">
                Search
            </a>
        </div>
    </div>
</div>



<!-- Modal Detail Module -->
<div class="modal fade" id="filterModuleLogSystem" tabindex="-1" aria-labelledby="filterModuleLogSystemLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModuleLogSystemLabel">Filter Module</h5>
                <button type="button" id="buttonCloseModuleModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
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
                <button type="button" class="btn btn-primary" id="selectData-Module">Pilih Data</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        // Function to add 'selected' class to the row based on the module identifiers
        function addSelectedClassByModuleIdentifiers(moduleIdentifiers) {
            var table = $('#datatableModuleModal').DataTable();

            // Check if the 'select' extension is available
            if ($.fn.dataTable.Select) {
                // Check if the 'select' extension is initialized for the table
                if (table.select) {
                    // Deselect all rows first
                    table.rows().deselect();
                }
            }

            table.rows().nodes().to$().removeClass('selected'); // Remove 'selected' class from all rows

            if (moduleIdentifiers) {
                table.rows().every(function() {
                    var rowData = this.data();
                    if (rowData.identifiers === moduleIdentifiers) {
                        // Check if the 'select' extension is available before using 'select' method
                        if ($.fn.dataTable.Select && table.select) {
                            this.select(); // Select the row
                        }
                        $(this.node()).addClass('selected'); // Add 'selected' class
                        return false; // Break the loop
                    }
                });
            }
        }



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
                drawCallback: function(settings) {
                    // Add 'selected' class based on the content of the input fields
                    var moduleIdentifiers = $("#inputModule").val();
                    addSelectedClassByModuleIdentifiers(moduleIdentifiers);
                },
            });

            // click di baris tabel module
            $('#datatableModuleModal tbody').on('click', 'tr', function() {
                // Remove the 'selected' class from all rows
                $('#datatableModuleModal tbody tr').removeClass('selected');

                // Add the 'selected' class to the clicked row
                $(this).addClass('selected');

                var data = data_table_module.row(this).data();
            });

            // click di tombol Pilih Module
            $('#selectData-Module').on('click', function() {
                // Get the selected row data
                var selectedRowData = data_table_module.rows('.selected').data()[0];

                // Check if any row is selected
                if (selectedRowData) {
                    // Use the selected row data
                    $("#inputModule").val(selectedRowData.identifiers);
                    $("#inputModuleName").val(selectedRowData.name);

                    // Close the modal
                    $('#buttonCloseModuleModal').click();
                } else {
                    // Handle the case where no row is selected
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success mx-4',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });

                    swalWithBootstrapButtons.fire({
                        title: 'Failed!',
                        text: 'Please select a row first.',
                        icon: 'error',
                        // timer: 1500, // 2 detik
                        showConfirmButton: true
                    });
                }
            });
            // end click di tombol Pilih Module
        });
    </script>
@endpush
