@extends('admin.layout_admin')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header p-3">
                                <div class="d-flex align-content-center justify-content-between">
                                    <h3 class="font-weight-bold text-lg">Log Aktivitas Pengguna</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Date Filter -->
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="start_date">Dari Tanggal</label>
                                        <input type="date" class="form-control" id="start_date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="end_date">Sampai Tanggal</label>
                                        <input type="date" class="form-control" id="end_date">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-primary" id="btn-filter">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <button type="button" class="btn btn-secondary ml-2" id="btn-reset">
                                            <i class="fas fa-sync"></i> Reset
                                        </button>
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped data-table">
                                    <thead>
                                        <tr>
                                            <th width="50px">No</th>
                                            <th>User Name</th>
                                            <th>Aktivitas</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                responsive: true,
                ajax: {
                    url: "{{ route('log-aktivitas.index') }}",
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'aktivitas',
                        name: 'aktivitas',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jam',
                        name: 'jam',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ]
            });

            // Filter button click
            $('#btn-filter').on('click', function() {
                table.ajax.reload();
            });

            // Reset button click
            $('#btn-reset').on('click', function() {
                $('#start_date').val('');
                $('#end_date').val('');
                table.ajax.reload();
            });
        });
    </script>
@endpush
