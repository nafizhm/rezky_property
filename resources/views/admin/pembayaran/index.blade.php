<!-- resources/views/admin/bank/index.blade.php -->
@extends('admin.layout_admin')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </section>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header p-3">
                    <div class="d-flex align-content-center justify-content-between">
                        <h3 class="font-weight-bold text-lg">Data Pembayaran</h3>
                        <div class="d-flex align-items-center" style="gap: 8px">
                            <a class="btn btn-info btn-tambah" style="height: 38px; padding: 0.375rem 0.75rem;"
                                href="{{ route('pembayaran.rekap') }}">
                                Rekap Pembayaran
                            </a>
                            <select class="form-control select-1" name="status" id="status">
                                <option value="">Semua</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Terhutang">Terhutang</option>
                            </select>

                            <select class="form-control select-2" name="progres" id="progres">
                                <option value="">Semua</option>
                                @foreach ($progreslists as $list)
                                    <option value="{{ $list->id }}">{{ $list->status_progres }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Customer</th>
                                <th width="20%">Lokasi Unit</th>
                                <th width="10%">Status</th>
                                <th width="18%">Jumlah Tagihan</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select-1').select2({
                theme: "bootstrap4",
                minimumResultsForSearch: Infinity,
            });
            $('.select-2').select2({
                theme: "bootstrap4",
            });
        });

        $(function() {
            var permissions = @json($permissions);
            var showActionColumn = (permissions['edit'] == 1 || permissions['hapus'] == 1);

            var table = $('.data-table').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
                ordering: false,
                ajax: {
                    url: "{{ route('pembayaran.index') }}",
                    data: function(d) {
                        d.status = $('#status').val();
                        d.progres = $('#progres').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'lokasi_unit',
                        name: 'lokasi_unit',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jumlah_tagihan',
                        name: 'jumlah_tagihan',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        visible: showActionColumn,
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }]
            });

            $('#status, #progres').change(function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
