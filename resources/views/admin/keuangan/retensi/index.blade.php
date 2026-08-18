@extends('admin.layout_admin')
@section('content')
    <div class="content-wrapper">
        <style>
            .col-pencairan {
                background-color: #fff3cd;
            }

            .col-total-retensi {
                background-color: #f8d7da;
                color: #b02a37;
            }
        </style>
        <section class="content-header">
            <div class="container-fluid">
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="font-weight-bold text-lg mb-0">Rekap Retensi Pencairan</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th width="50px">No</th>
                                            <th>Lokasi + Unit</th>
                                            <th class="text-center">Plafon</th>
                                            <th class="text-center col-pencairan">Pencairan</th>
                                            @foreach ($retensis as $retensi)
                                                <th class="text-center">{{ $retensi->nama_retensi }}</th>
                                            @endforeach
                                            <th class="text-center col-total-retensi">Total Retensi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($rows as $index => $row)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $row['lokasi_unit'] }}</td>
                                                <td class="text-right">{{ number_format($row['plafon'], 0, ',', '.') }}</td>
                                                <td class="text-right col-pencairan">{{ number_format($row['pencairan'], 0, ',', '.') }}</td>
                                                @foreach ($retensis as $retensi)
                                                    <td class="text-right">{{ number_format($row['retensi'][$retensi->id] ?? 0, 0, ',', '.') }}</td>
                                                @endforeach
                                                <td class="text-right font-weight-bold col-total-retensi">{{ number_format($row['total_retensi'], 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 5 + $retensis->count() }}" class="text-center text-muted">
                                                    Belum ada data unit yang sudah cair.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold bg-light">
                                            <td colspan="2" class="text-center">TOTAL</td>
                                            <td class="text-right">{{ number_format($totals['plafon'], 0, ',', '.') }}</td>
                                            <td class="text-right col-pencairan">{{ number_format($totals['pencairan'], 0, ',', '.') }}</td>
                                            @foreach ($retensis as $retensi)
                                                <td class="text-right">{{ number_format($totals['retensi'][$retensi->id] ?? 0, 0, ',', '.') }}</td>
                                            @endforeach
                                            <td class="text-right col-total-retensi">{{ number_format($totals['total_retensi'], 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
