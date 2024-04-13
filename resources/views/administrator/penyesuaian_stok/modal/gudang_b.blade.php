<!-- Modal Detail Gudang -->
<div class="modal fade" id="ModalGudangB" tabindex="-1" aria-labelledby="ModalGudangBLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalGudangBLabel">Data Gudang</h5>
                <button type="button" id="buttonCloseGudangBModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="ModalGudangBBody">
                <table class="table" id="datatableGudangBModal">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="">Nama</th>
                            <th width="">Penanggung Jawab</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectDataGudangB">Pilih Data</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        var optionToast = {
            classname: "toast",
            transition: "fade",
            insertBefore: true,
            duration: 4000,
            enableSounds: true,
            autoClose: true,
            progressBar: true,
            sounds: {
                info: toastMessages.path + "/sounds/info/1.mp3",
                // path to sound for successfull message:
                success: toastMessages.path + "/sounds/success/1.mp3",
                // path to sound for warn message:
                warning: toastMessages.path + "/sounds/warning/1.mp3",
                // path to sound for error message:
                error: toastMessages.path + "/sounds/error/1.mp3",
            },

            onShow: function(type) {
                console.log("a toast " + type + " message is shown!");
            },
            onHide: function(type) {
                console.log("the toast " + type + " message is hidden!");
            },

            // the placement where prepend the toast container:
            prependTo: document.body.childNodes[0],
        };

        function addSelectedClassByGudangB(id) {
            var table = $('#datatableGudangBModal').DataTable();

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

        $('#ModalGudangB').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            // Now, you can initialize a new DataTable on the same table.
            $("#datatableGudangBModal").DataTable().destroy();
            $('#datatableGudangBModal tbody').remove();
            var data_table = $('#datatableGudangBModal').DataTable({
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
                    url: '{{ route('admin.penyesuaian_stok.getDataGudang') }}',
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
                        data: 'penanggung_jawab',
                        name: 'penanggung_jawab'
                    },
                ],
                drawCallback: function(settings) {
                    // Add 'selected' class based on the content of the input fields
                    var id = $("#inputGudangB").val();
                    addSelectedClassByGudangB(id);
                },
            });

            // click di baris tabel member
            $('#datatableGudangBModal tbody').on('click', 'tr', function() {
                var $row = $(this);

                // Remove 'selected' class from all rows
                $('#datatableGudangBModal tbody tr').removeClass('selected');

                // Add 'selected' class to the clicked row
                $row.addClass('selected');

                // Get selected row data
                var selectedRow = data_table.row('.selected').data();

                // if (selectedRow) {
                //     // Set input values based on the selected row
                //     $("#inputGudangB").val(selectedRow.id);
                //     $("#inputGudangBName").val(selectedRow.nama);
                // }
            });
            // end click di baris tabel member

            // click Select button
            $('#selectDataGudangB').off().on('click', function() {
                // Get selected row data
                var selectedRow = data_table.row('.selected').data();

                if (selectedRow) {
                    if (selectedRow.id !== parseInt($("#inputGudang").val())) {
                        $("#inputGudangB").val(selectedRow.id);
                        $("#inputGudangBName").val(selectedRow.nama);
                    } else {
                        $("#inputGudangB").val('');
                        $("#inputGudangBName").val('');

                        var toasty = new Toasty(optionToast);
                        toasty.configure(optionToast);
                        toasty.error('Gudang tidak boleh sama');
                    }
                }

                $('#buttonCloseGudangBModal').click();
            });
            // end click Select button
        });
    </script>
@endpush
