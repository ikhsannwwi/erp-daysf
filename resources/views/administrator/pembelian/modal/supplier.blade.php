<!-- Modal Detail Supplier -->
<div class="modal fade" id="ModalSupplier" tabindex="-1" aria-labelledby="ModalSupplierLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalSupplierLabel">Data Supplier</h5>
                <button type="button" id="buttonCloseSupplierModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="ModalSupplierBody">
                <table class="table" id="datatableSupplierModal">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="">Nama</th>
                            <th width="">Email</th>
                            <th width="">Telepon</th>
                            <th width="">Alamat</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectDataSupplier">Pilih Data</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        function addSelectedClassBySupplier(id) {
            var table = $('#datatableSupplierModal').DataTable();

            // Check if the 'select' extension is available
            if ($.fn.dataTable.Select) {
                // Check if the 'select' extension is initialized for the table
                if (table.select) {
                    // Deselect all rows first
                    table.rows().deselect();
                }
            }

            table.rows().nodes().to$().removeClass('selected'); // Remove 'selected' class from all rows

            if (id) {
                table.rows().every(function() {
                    var rowData = this.data();
                    if (rowData.id === parseInt(id)) {
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
        
        $('#ModalSupplier').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            
            // Now, you can initialize a new DataTable on the same table.
            $("#datatableSupplierModal").DataTable().destroy();
            $('#datatableSupplierModal tbody').remove();
            var data_table = $('#datatableSupplierModal').DataTable({
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
                    url: '{{ route('admin.pembelian.getDataSupplier') }}',
                    dataType: "JSON",
                    type: "GET",
                },
                columns: [{
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'telepon',
                        name: 'telepon'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                ],
                drawCallback: function(settings) {
                    // Add 'selected' class based on the content of the input fields
                    var id = $("#inputSupplier").val();
                    addSelectedClassBySupplier(id);
                },
            });

            // click di baris tabel member
            $('#datatableSupplierModal tbody').on('click', 'tr', function() {
                var $row = $(this);
                
                // Remove 'selected' class from all rows
                $('#datatableSupplierModal tbody tr').removeClass('selected');
                
                // Add 'selected' class to the clicked row
                $row.addClass('selected');
                
                // Get selected row data
                var selectedRow = data_table.row('.selected').data();
                
                // if (selectedRow) {
                //     // Set input values based on the selected row
                //     $("#inputSupplier").val(selectedRow.id);
                //     $("#inputSupplierName").val(selectedRow.nama);
                // }
            });
            // end click di baris tabel member

            // click Select button
            $('#selectDataSupplier').on('click', function() {
                // Get selected row data
                var selectedRow = data_table.row('.selected').data();

                if (selectedRow) {
                    $("#inputSupplier").val(selectedRow.id);
                    $("#inputSupplierName").val(selectedRow.nama);
                }

                $('#buttonCloseSupplierModal').click();
            });
            // end click Select button
        });
    </script>
@endpush
