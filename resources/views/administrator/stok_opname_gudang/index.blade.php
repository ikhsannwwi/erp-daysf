@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Stok Opname Gudang
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Stok Opname Gudang</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-6">
                        @if (isallowed('stok_opname_gudang', 'add'))
                            <a href="{{ route('admin.stok_opname_gudang.add') }}" class="btn btn-primary mx-3 float-end">Tambah
                                Data</a>
                        @endif
                        {{-- <a href="javascript:void(0)" class="btn btn-primary float-end" id="filterButton">Filter</a> --}}
                    </div>
                </div>
            </div>
            {{-- @include('administrator.stok_opname_gudang.filter.main') --}}
            <div class="card-body">
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th width="15px">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="25%">Nomor Stok Opname</th>
                            <th width="30%">Gudang</th>
                            <th width="30%">Karyawan</th>
                            <th width="225px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->

    @include('administrator.stok_opname_gudang.modal.detail')
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
                    url: '{{ route('admin.stok_opname_gudang.getData') }}',
                    dataType: "JSON",
                    type: "GET",
                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        mRender: function(data, type, row, meta) {
                            var date = new Date(row.tanggal);
                            var formattedDate = moment(date).format('DD-MM-YYYY');

                            return formattedDate;
                        }
                    },
                    {
                        data: 'no_stok_opname',
                        name: 'no_stok_opname'
                    },
                    {
                        data: 'gudang.nama',
                        name: 'gudang.nama'
                    },
                    {
                        data: 'karyawan.nama',
                        name: 'karyawan.nama'
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
                            url: "{{ route('admin.stok_opname_gudang.delete') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE",
                                "id": id,
                            },
                            success: function() {
                                // data_table.ajax.url(
                                //         '{{ route('admin.stok_opname_gudang.getData') }}')
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
        });
    </script>
@endpush
