@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        User Activity
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">User Activity</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-6">
                        <a href="javascript:void(0)" class="btn btn-primary float-end" id="filterButton">Filter</a>
                    </div>
                </div>
            </div>
            @include('administrator.user_activity.filter.main')
            <div class="card-body">
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th width="15px">No</th>
                            <th width="200px">User Group</th>
                            <th width="50%">Nama</th>
                            <th width="50%">Email</th>
                            <th width="75px">Last Seen</th>
                            <th width="75px">Status</th>
                            <th width="225px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->

    @include('administrator.user_activity.modal.detail')
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
                    url: '{{ route('admin.user_activity.getData') }}',
                    dataType: "JSON",
                    type: "GET",
                    data: function(d) {
                        d.status = getStatus();
                        d.usergroup = getUserGroup();
                    }

                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'user_group.name',
                        name: 'user_group.name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'lastseen',
                        name: 'lastseen'
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
                    }
                ],
            });

            $('#filterButton').on('click', function() {
                $('#filter_section').slideToggle();

            });

            var optionUserGroup = $('#filterusergroup');


            optionUserGroup.html(
                '<option id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin">' +
                '</i> Sedang memuat...</option>'
            );

            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.user_activity.getUserGroup') }}',
                method: 'GET',
                success: function(response) {
                    var data = response.usergroup;
                    var optionsHtml = ''; // Store the generated option elements

                    // Iterate through each user group in the response data
                    for (var i = 0; i < data.length; i++) {
                        var userGroup = data[i];
                        optionsHtml += '<option value="' + userGroup.id + '">' + userGroup
                            .name + '</option>';
                    }

                    // Construct the final dropdown HTML
                    var finalDropdownHtml = '<option value="">Semua</option>' + optionsHtml;

                    optionUserGroup.html(finalDropdownHtml);

                    loadingSpinner.hide(); // Hide the loading spinner after data is loaded
                },
                error: function() {
                    // Handle the error case if the AJAX request fails
                    console.error('Gagal memuat data User Group.');
                    optionUserGroup.html('<option>Gagal memuat data</option>')
                    loadingSpinner
                        .hide(); // Hide the loading spinner even if there's an error
                }
            });

            $('#filter_submit').on('click', function(event) {
                event.preventDefault(); // Prevent the default form submission behavior

                // Get the filter value using the getStatus() function
                var filterStatus = getStatus();
                var filterUserGroup = getUserGroup();

                // Update the DataTable with the filtered data
                data_table.ajax.url('{{ route('admin.user_activity.getData') }}?status=' + filterStatus +
                        '|usergroup=' + filterUserGroup)
                    .load();
            });

            function getStatus() {
                return $("#filterstatus").val();
            }

            function getUserGroup() {
                return $("#filterusergroup").val();
            }
        });
    </script>
@endpush
