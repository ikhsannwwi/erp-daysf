@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Karyawan
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Karyawan</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-6">
                        @if (isallowed('karyawan', 'add'))
                            <a href="{{ route('admin.karyawan.add') }}" class="btn btn-primary mx-3 float-end">Tambah
                                Data</a>
                        @endif
                        {{-- <a href="javascript:void(0)" class="btn btn-primary float-end" id="filterButton">Filter</a> --}}
                    </div>
                </div>
            </div>
            {{-- @include('administrator.karyawan.filter.main') --}}
            <div class="card-body">
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th width="15px">No</th>
                            <th width="100px">Departemen</th>
                            <th width="100px">Kode</th>
                            <th width="50%">Nama</th>
                            <th width="50%">Email</th>
                            <th width="100px">Status</th>
                            <th width="225px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->

    @include('administrator.karyawan.modal.detail')
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var data_table = $('#datatable').DataTable({
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
                scrollX: true, // Enable horizontal scrolling
                ajax: {
                    url: '{{ route('admin.karyawan.getData') }}',
                    dataType: "JSON",
                    type: "GET",
                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'departemen.nama',
                        name: 'departemen.nama'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
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
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false,
                        class: 'text-center'
                    },
                ],
            });


            $(document).on('click', '.delete', function(event) {
                var id = $(this).data('id');
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-4',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Apakah anda yakin ingin menghapus data ini',
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Saya yakin!',
                    cancelButtonText: 'Tidak, Batalkan!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('admin.karyawan.delete') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE",
                                "id": id,
                            },
                            success: function() {
                                // data_table.ajax.url(
                                //         '{{ route('admin.karyawan.getData') }}')
                                //     .load();
                                data_table.ajax.reload(null, false);
                                swalWithBootstrapButtons.fire({
                                    title: 'Berhasil!',
                                    text: 'Data berhasil dihapus.',
                                    icon: 'success',
                                    timer: 1500, // 2 detik
                                    showConfirmButton: false,
                                });

                                // Remove the deleted row from the DataTable without reloading the page
                                // data_table.row($(this).parents('tr')).remove().draw();
                            }
                        });
                    }
                });
            });

            
            //Change Status Confirmation
            $(document).on('click', '.changeStatus', function(event) {
                var ix = $(this).data('ix');
                if ($(this).is(':checked')) {
                    var status = "Tidak Aktif";
                    var changeto = "Aktif";
                    var message = "";
                } else {
                    var status = "Aktif"
                    var changeto = "Tidak Aktif";
                    var message = "";
                }

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-4',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    html: 'Apakah anda yakin ingin mengubah status ke ' + changeto + '?' + message,
                    icon: "info",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: "Ya, saya yakin!",
                    cancelButtonText: 'Tidak, batalkan',
                    reverseButtons: true

                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.karyawan.changeStatus') }}",
                            data: ({
                                "_token": "{{ csrf_token() }}",
                                "_method": "POST",
                                ix: ix,
                                status: changeto,

                            }),
                            success: function() {
                                data_table.ajax.reload(null, false);
                                swalWithBootstrapButtons.fire({
                                    title: 'Berhasil!',
                                    text: 'Status berhasil diubah ke ' + changeto,
                                    icon: 'success',
                                    timer: 1500, // 2 detik
                                    showConfirmButton: false
                                });
                            }
                        });

                    } else {
                        if (status == "Aktif") {
                            $(this).prop("checked", true);
                        } else {
                            $(this).prop("checked", false);
                        }
                    }
                });
            });
        });
    </script>
@endpush