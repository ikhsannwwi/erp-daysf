@extends('kasir.layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Transaksi Penjualan</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('kasir.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item"><a href="{{ route('kasir.transaksi') }}">Transaksi</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">History</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-6 text-end">
                                {{-- <a href="javascript:void(0)" class="btn btn-primary">History</a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
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
                </div>
            </div>
    </div>

    <!-- Basic Tables end -->
    @include('kasir.transaksi_penjualan.modal.detail')
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"
        integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
                    url: '{{ route('kasir.transaksi.history.getData') }}',
                    dataType: "JSON",
                    type: "GET"
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

            function formatRupiah(amount) {
                // Use Number.prototype.toLocaleString() to format the number as currency
                return 'Rp ' + Number(amount).toLocaleString('id-ID');
            }

            function parseRupiah(rupiahString) {
                // Remove currency symbol, separators, and parse as integer
                const parsedValue = parseInt(rupiahString.replace(/[^\d]/g, ''));
                return isNaN(parsedValue) ? 0 : parsedValue;
            }

            function formatNumber(number) {
                // Use Number.prototype.toLocaleString() to format the number as currency
                return Number(number).toLocaleString('id-ID');
            }

            function parseNumber(number) {
                // Remove currency symbol, separators, and parse as integer
                // Replace dot only if it exists in the number
                const parsedValue = parseInt(number.replace(/[^\d]/g, ''));
                return isNaN(parsedValue) ? 0 : parsedValue;
            }
        });
    </script>
@endpush
