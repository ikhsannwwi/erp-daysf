@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Transaksi Stok
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.transaksi_stok') }}">Transaksi Stok</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $gudang->nama }}</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $data->nama }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-6">
                    </div>
                </div>
            </div>
            {{-- @include('administrator.supplier.filter.main') --}}
            <div class="card-body">
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th width="15px">No</th>
                            <th width="50%">Metode</th>
                            <th width="50%">Jenis</th>
                            <th width="150px">Jumlah Unit</th>
                            <th width="200px">Tanggal</th>
                            <th width="225px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            function ubahFormatTanggal(tanggalAwal) {
                var tanggal = new Date(tanggalAwal);

                var day = ('0' + tanggal.getDate()).slice(-2);
                var month = ('0' + (tanggal.getMonth() + 1)).slice(-2);
                var year = tanggal.getFullYear();
                var hours = ('0' + tanggal.getHours()).slice(-2);
                var minutes = ('0' + tanggal.getMinutes()).slice(-2);
                var seconds = ('0' + tanggal.getSeconds()).slice(-2);

                return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ':' + seconds;
            }

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
                    [4, 'desc']
                ],
                scrollX: true, // Enable horizontal scrolling
                ajax: {
                    url: '{{ route('admin.transaksi_stok.getData') }}',
                    dataType: "JSON",
                    type: "GET",
                    data: {
                        produk_id: {{ $data->id }},
                        gudang_id: {{ $gudang->id }}
                    },
                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'metode_transaksi',
                        name: 'metode_transaksi'
                    },
                    {
                        data: 'jenis_transaksi',
                        name: 'jenis_transaksi'
                    },
                    {
                        data: 'jumlah_unit',
                        name: 'jumlah_unit',
                        class: 'text-end'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            return ubahFormatTanggal(data);
                        }
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
                            url: "{{ route('admin.supplier.delete') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE",
                                "id": id,
                            },
                            success: function() {
                                // data_table.ajax.url(
                                //         '{{ route('admin.supplier.getData') }}')
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
