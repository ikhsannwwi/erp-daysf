@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Transaksi Penjualan
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Transaksi Penjualan</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-6">
                        @if (isallowed('transaksi_penjualan', 'add'))
                            <a href="{{ route('admin.transaksi_penjualan.add') }}"
                                class="btn btn-primary me-3 float-end mx-3">Tambah
                                Data</a>
                        @endif
                        <a href="javascript:void(0)" class="btn btn-primary float-end" id="filterButton">Filter</a>
                    </div>
                </div>
            </div>
            @include('administrator.transaksi_penjualan.filter.main')
            <div class="card-body">
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th width="15px">No</th>
                            <th width="200px">No Transaksi</th>
                            <th width="200px">Tanggal Transaksi</th>
                            <th width="50%">Member</th>
                            <th width="50%">Jumlah Total</th>
                            <th width="225px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->

    @include('administrator.transaksi_penjualan.modal.detail')
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
                    [2, 'desc']
                ],
                scrollX: true, // Enable horizontal scrolling
                ajax: {
                    url: '{{ route('admin.transaksi_penjualan.getData') }}',
                    dataType: "JSON",
                    type: "GET",
                    data: function(d) {
                        d.status = getStatus();
                        d.kategori = getKategori();
                    }

                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        searchable: false,
                        sortable: false,
                        class: 'text-center'
                    },
                    {
                        data: 'no_transaksi',
                        name: 'no_transaksi'
                    },
                    {
                        data: 'tanggal_transaksi',
                        name: 'tanggal_transaksi'
                    },
                    {
                        data: 'member.nama',
                        name: 'member.nama'
                    },
                    {
                        data: 'jumlah_total',
                        name: 'jumlah_total',
                        render: function(data, type, row) {
                            // Convert the number to currency format
                            return parseFloat(data).toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            });
                        },
                        class: 'text-end'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false,
                        class: 'text-center'
                    }
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
                            url: "{{ route('admin.transaksi_penjualan.delete') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE",
                                "id": id,
                            },
                            success: function() {
                                // data_table.ajax.url(
                                //         '{{ route('admin.transaksi_penjualan.getData') }}')
                                //     .load();
                                data_table.ajax.reload(null, false);
                                swalWithBootstrapButtons.fire({
                                    title: 'Berhasil!',
                                    text: 'Data berhasil dihapus.',
                                    icon: 'success',
                                    timer: 1500, // 2 detik
                                    showConfirmButton: false
                                });

                                // Remove the deleted row from the DataTable without reloading the page
                                // data_table.row($(this).parents('tr')).remove().draw();
                            }
                        });
                    }
                });
            });

            $('#filterButton').on('click', function() {
                $('#filter_section').slideToggle();

            });

            // var options = {
            //     searchable: true,
            //     placeholder: 'select',
            //     searchtext: 'search',
            //     selectedtext: 'dipilih'
            // };
            // var optionMember = $('#filterMember');
            // var selectMember = NiceSelect.bind(document.getElementById('filterMember'), options);
            // var selectStatus = NiceSelect.bind(document.getElementById('filterstatus'), options);


            // optionMember.html(
            //     '<option id="loadingSpinner" style="display: none;">' +
            //     '<i class="fas fa-spinner fa-spin">' +
            //     '</i> Sedang memuat...</option>'
            // );

            // var loadingSpinner = $('#loadingSpinner');

            // loadingSpinner.show(); // Tampilkan elemen animasi

            // $.ajax({
            //     url: '{{ route('admin.transaksi_penjualan.getMember') }}',
            //     method: 'GET',
            //     success: function(response) {
            //         var data = response.Member;
            //         var optionsHtml = ''; // Store the generated option elements

            //         // Iterate through each user group in the response data
            //         for (var i = 0; i < data.length; i++) {
            //             var dataMember = data[i];
            //             optionsHtml += '<option value="' + dataMember.id + '">' + dataMember.nama + '</option>';
            //         }

            //         // Construct the final dropdown HTML
            //         var finalDropdownHtml = '<option value="">Semua</option>' + optionsHtml;

            //         optionMember.html(finalDropdownHtml);
            //         selectMember.update();
            //         loadingSpinner.hide(); // Hide the loading spinner after data is loaded
            //     },
            //     error: function() {
            //         // Handle the error case if the AJAX request fails
            //         console.error('Gagal memuat data User Group.');
            //         optionMember.html('<option>Gagal memuat data</option>')
            //         loadingSpinner
            //             .hide(); // Hide the loading spinner even if there's an error
            //     }
            // });

            $('#filter_submit').on('click', function(event) {
                event.preventDefault(); // Prevent the default form submission behavior

                // Get the filter value using the getStatus() function
                var filterStatus = getStatus();
                var filterKategori = getKategori();

                // Update the DataTable with the filtered data
                data_table.ajax.url('{{ route('admin.transaksi_penjualan.getData') }}?status=' +
                        filterStatus +
                        '|kategori=' + filterKategori)
                    .load();
            });

            function getStatus() {
                return $("#filterstatus").val();
            }

            function getKategori() {
                return $("#filterKategori").val();
            }
        });
    </script>
@endpush
