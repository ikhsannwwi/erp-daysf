<div class="row">
    <div class="form-group ">
        <div class="col-md-4 col-12">
            <label for="triggerMember" class="form-label">Member</label>
            <div class="input-group">
                <span class="input-group-text pb-3" id="searchMember"><i
                        class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="inputMemberName" readonly>
                <input type="text" class="d-none" name="member" id="inputMember">
                <div class="input-group-append">
                    <!-- Menggunakan input-group-append agar elemen berikutnya ditambahkan setelah input -->
                    <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#modalMember" id="triggerMember">
                        Search
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Module -->
<div class="modal fade" id="modalMember" tabindex="-1" aria-labelledby="modalMemberLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMemberLabel">Filter Module</h5>
                <button type="button" id="buttonClosemodalMember" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalMemberBody">
                <table class="table" id="datatablemodalMember">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="">Nama</th>
                            <th width="">Telepon</th>
                            <th width="">Email</th>
                            <th width="">Alamat</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectDataMember">Pilih Data</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        // Function to add 'selected' class to the row based on the module identifiers
        function addSelectedClassByModuleIdentifiers(id) {
            var table = $('#datatablemodalMember').DataTable();

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



        $('#modalMember').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            // Now, you can initialize a new DataTable on the same table.
            $("#datatablemodalMember").DataTable().destroy();
            $('#datatablemodalMember tbody').remove();
            var data_table_module = $('#datatablemodalMember').DataTable({
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
                    url: '{{ route('kasir.transaksi.getDataMember') }}',
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
                        data: 'telepon',
                        name: 'telepon'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                ],
                drawCallback: function(settings) {
                    // Add 'selected' class based on the content of the input fields
                    var id = $("#inputMember").val();
                    addSelectedClassByModuleIdentifiers(id);
                },
            });

            // click di baris tabel module
            $('#datatablemodalMember tbody').on('click', 'tr', function() {
                // Remove the 'selected' class from all rows
                $('#datatablemodalMember tbody tr').removeClass('selected');

                // Add the 'selected' class to the clicked row
                $(this).addClass('selected');

                var data = data_table_module.row(this).data();
            });

            // click di tombol Pilih Module
            $('#selectDataMember').off().on('click', function() {
                // Get the selected row data
                var selectedRowData = data_table_module.rows('.selected').data()[0];

                // Check if any row is selected
                if (selectedRowData) {
                    // Use the selected row data
                    $("#inputMember").val(selectedRowData.id);
                    $("#inputMemberName").val(selectedRowData.nama);

                    // Close the modal
                    $('#buttonClosemodalMember').click();
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
