@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Transaksi Stok Toko
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Transaksi Stok Toko</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="inputToko" class="form-label">Toko</label>
                                    <select class="wide mb-2" name="toko" id="inputToko">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @include('administrator.supplier.filter.main') --}}
            <div class="card-body">
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th width="15px">No</th>
                            <th width="100%">Produk</th>
                            <th width="150px">Jumlah Stok</th>
                            <th width="150px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->

    {{-- @include('administrator.supplier.modal.detail') --}}
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
                    url: '{{ route('admin.transaksi_stok_toko.getDataProduk') }}',
                    dataType: "JSON",
                    type: "GET",
                    data : function(d){
                        d.toko = getToko();
                    }
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
                        data: 'jumlah_stok',
                        name: 'jumlah_stok',
                        class: 'text-end'
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

            var options = {
                searchable: true,
                placeholder: 'select',
                searchtext: 'search',
                selectedtext: 'dipilih'
            };
            var optionKategori = $('#inputToko');
            var selectKategori = NiceSelect.bind(document.getElementById('inputToko'), options);


            optionKategori.html(
                '<option id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin">' +
                '</i> Sedang memuat...</option>'
            );

            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.transaksi_stok_toko.getToko') }}',
                method: 'GET',
                success: function(response) {
                    var data = response.toko;
                    var optionsHtml = ''; // Store the generated option elements

                    // Iterate through each Data in the response data
                    for (var i = 0; i < data.length; i++) {
                        var dataToko = data[i];
                        optionsHtml += '<option value="' + dataToko.id + '">' + dataToko
                            .nama + '</option>';
                    }

                    // Construct the final dropdown HTML
                    var finalDropdownHtml = optionsHtml;

                    optionKategori.html(finalDropdownHtml);

                    selectKategori.update();
                    data_table.ajax.reload(null, false);
                    
                    loadingSpinner.hide(); // Hide the loading spinner after data is loaded
                },
                error: function() {
                    // Handle the error case if the AJAX request fails
                    console.error('Gagal memuat data Data.');
                    optionKategori.html('<option>Gagal memuat data</option>')
                    loadingSpinner
                        .hide(); // Hide the loading spinner even if there's an error
                }
            });

            function getToko() {
                return $("#inputToko").val();
            }

            $('#inputToko').on('change', function(){
                data_table.ajax.reload(null, false);
            })
        });
    </script>
@endpush
