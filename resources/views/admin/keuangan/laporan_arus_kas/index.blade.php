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
                            <div class="card-header">
                                <div class="d-flex align-content-center justify-content-between">
                                    <h3 class="font-weight-bold text-lg">Laporan Arus Kas</h3>
                                    <div>
                                        <a href="#" id="export-pdf" target="_blank"
                                            class="btn btn-sm mr-1 btn-danger">
                                            <i class="fa-solid fa-file-pdf mr-1"></i> Export PDF
                                        </a>

                                        <a href="#" id="export-excel" class="btn btn-sm btn-success">
                                            <i class="fa-solid fa-file-excel mr-1"></i> Export Excel
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="formFilter">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <h6 class="font-weight-bold">Tahun</h6>
                                            <fieldset class="form-group">
                                                <select class="form-control select-tahun" id="tahun" name="tahun"
                                                    required>
                                                    <option value=""></option>
                                                    @for ($i = now()->year; $i >= now()->year - 5; $i--)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-3">
                                            <h6 class="font-weight-bold">Bulan</h6>
                                            <fieldset class="form-group">
                                                <select name="bulan" class="form-control select-bulan" id="bulan"
                                                    required>
                                                    <option value=""></option>
                                                    <option value="0">Semua</option>
                                                    @foreach ($monthList as $num => $name)
                                                        <option value="{{ $num }}">
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-3">
                                            <h6 class="font-weight-bold">Rekening</h6>
                                            <fieldset class="form-group">
                                                <select name="rekening" class="form-control select-rekening" id="rekening"
                                                    required>
                                                    <option value=""></option>
                                                    @foreach ($rekeningList as $p)
                                                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-1 pt-4">
                                            <div class="w-100">
                                                <button class="btn btn-primary w-100">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table class="table data-table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th width="50px">No</th>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Debit</th>
                                            <th>Kredit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="row mb-3 mt-3">
                                    <label for="pemasukan" class="col-sm-2 col-form-label">Total Pemasukan</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="pemasukan" name="pemasukan" class="form-control"
                                                disabled>
                                        </div>
                                    </div>
                                    <label for="pengeluaran" class="col-sm-2 col-form-label">Total Pengeluaran</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="pengeluaran" name="pengeluaran" class="form-control"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        $(document).ready(function() {
            $('.select-tahun').select2({
                theme: 'bootstrap4',
                dropdownParent: $('body'),
                width: '100%',
                placeholder: 'Pilih Tahun',
                allowClear: true,
            });
            $('.select-bulan').select2({
                theme: 'bootstrap4',
                dropdownParent: $('body'),
                width: '100%',
                placeholder: 'Pilih Bulan',
                allowClear: true,
            });
            $('.select-rekening').select2({
                theme: 'bootstrap4',
                dropdownParent: $('body'),
                width: '100%',
                placeholder: 'Pilih Rekening',
                allowClear: true,
            });
        });

        var audio = new Audio('{{ asset('audio/notification.ogg') }}');

        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: false,
                ordering: false,
                responsive: true,
                searching: false,
                ajax: {
                    url: "{{ route('laporan-arus-kas.filter') }}",
                    type: "GET",
                    data: function(d) {
                        d.tahun = $('#tahun').val();
                        d.bulan = $('#bulan').val();
                        d.rekening = $('#rekening').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'debit',
                        name: 'debit'
                    },
                    {
                        data: 'kredit',
                        name: 'kredit'
                    },
                ]
            });

            table.on('xhr.dt', function(e, settings, json, xhr) {
                $('#pemasukan').val(new Intl.NumberFormat('id-ID').format(json.total_pemasukan));
                $('#pengeluaran').val(new Intl.NumberFormat('id-ID').format(json.total_pengeluaran));
            });

            $('#formFilter').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            $('#export-pdf').on('click', function(e) {
                e.preventDefault();
                let tahun = $('#tahun').val();
                let bulan = $('#bulan').val();
                let rekening = $('#rekening').val();
                if (tahun && bulan && rekening) {
                    let url =
                        `{{ route('laporan-arus-kas.exportPDF') }}?tahun=${tahun}&bulan=${bulan}&rekening=${rekening}`;
                    window.open(url, '_blank');
                } else {
                    alert('Silakan pilih tahun, bulan, dan rekening terlebih dahulu.');
                }
            });

            $('#export-excel').on('click', function(e) {
                e.preventDefault();
                let tahun = $('#tahun').val();
                let bulan = $('#bulan').val();
                let rekening = $('#rekening').val();
                if (tahun && bulan && rekening) {
                    let url =
                        `{{ route('laporan-arus-kas.exportExcel') }}?tahun=${tahun}&bulan=${bulan}&rekening=${rekening}`;
                    window.location.href = url;
                } else {
                    alert('Silakan pilih tahun, bulan, dan rekening terlebih dahulu.');
                }
            });
        });
    </script>
@endpush
