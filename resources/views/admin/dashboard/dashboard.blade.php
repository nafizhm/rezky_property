@extends('admin.layout_admin')
@section('content')
    @php
        $logo = \App\Models\PengaturanMedia::where('jenis_data', 'logo website')->first();
    @endphp

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold ">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $totalKavling }} / {{ $totalKavling }}</h3>
                                <p>Total Unit</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>

                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $merah }}</h3>
                                <p>Booking</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $hijau }}</h3>

                                <p>Wawancara</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-indigo">
                            <div class="inner">
                                <h3>{{ $ungu }}</h3>

                                <p>Akad</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>

                <!-- Start statistik Marketing -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-widget widget-user-2 shadow-sm">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-lightblue">
                                <div class="widget-user-image">
                                    <img class="mt-3" style="max-width: 70px; height: auto;"
                                        src="{{ asset('config_media/' . ($logo->nama_file ?? 'default.png')) }}"
                                        alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">Statistik Penjualan per Lokasi</h3>
                                <h5 class="widget-user-desc">Per {{ $tglSekarang }}</h5>
                            </div>
                            <div class="card-footer p-0">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th width="7%" class="text-center">KODE</th>
                                            <th width="30%">Perumahan</th>
                                            <th width="6%" class="text-center">Jumlah</th>
                                            @foreach ($kolomStatus as $status)
                                                <th class="text-center">{{ strtoupper($status->short_name) }}</th>
                                                @if ($status->id == 1)
                                                    <th class="text-center">HOLD</th>
                                                @endif
                                            @endforeach
                                            <th class="text-center">ACTION</th>
                                            <th class="text-center">KPR</th>
                                            <th class="text-center">CASH</th>
                                            <th class="text-center">KREDIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataLokasi as $lokasi)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td align="center">{{ $lokasi['nama_singk_1'] }}</td>
                                                <td>{{ $lokasi['nama'] }}</td>
                                                <td align="center" class="table-warning">{{ $lokasi['jumlah'] }}</td>
                                                @foreach ($kolomStatus as $status)
                                                    @php $key = strtolower(str_replace(' ', '_', $status->short_name)); @endphp
                                                    <td align="center">{{ $lokasi[$key] ?? 0 }}</td>
                                                    @if ($status->id == 1)
                                                        <td align="center">{{ $lokasi['hold'] ?? 0 }}</td>
                                                    @endif
                                                @endforeach
                                                <td align="center" class="table-primary">
                                                    <a href="{{ route('dashboard.lokasi-penjualan-show', $lokasi['id']) }}"
                                                        class="btn btn-primary btn-xs btn-detail">
                                                        Detail
                                                    </a>
                                                </td>
                                                <td align="center">{{ $lokasi['kpr'] }}</td>
                                                <td align="center">{{ $lokasi['cash'] }}</td>
                                                <td align="center">{{ $lokasi['kredit'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-center">TOTAL</th>
                                            <th class="text-center">{{ $totalSemua['jumlah'] }}</th>
                                            @foreach ($kolomStatus as $status)
                                                @php $key = strtolower(str_replace(' ', '_', $status->short_name)); @endphp
                                                <th class="text-center">{{ $totalSemua[$key] ?? 0 }}</th>
                                                @if ($status->id == 1)
                                                    <th class="text-center">{{ $totalSemua['hold'] }}</th>
                                                @endif
                                            @endforeach
                                            <th class="text-center table-primary">{{ $totalSemua['total_customer'] }}</th>
                                            <th class="text-center">{{ $totalSemua['kpr'] }}</th>
                                            <th class="text-center">{{ $totalSemua['cash'] }}</th>
                                            <th class="text-center">{{ $totalSemua['kredit'] }}</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <!-- Widget: user widget style 2 -->
                        <div class="card card-widget widget-user-2 shadow-sm">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-maroon">
                                <div class="widget-user-image">
                                    <img class="mt-3" style="max-width: 70px; height: auto;"
                                        src="{{ asset('config_media/' . ($logo->nama_file ?? 'default.png')) }}"
                                        alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">Statistik Status Progres</h3>
                                <h5 class="widget-user-desc">Per {{ $tglSekarang }}</h5>
                            </div>

                            <div class="card-footer p-0">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col" width="5%">No</th>
                                            <th scope="col" width="45%">Jenis Progres</th>
                                            <th scope="col" width="10%">Jumlah Progres</th>
                                            <th scope="col" width="10%">Persentase</th>
                                            <th scope="col" width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataProgres as $item)
                                            <tr>
                                                <td class="text-center">{{ $item['no'] }}</td>
                                                <td>{{ $item['status_progres'] }}</td>
                                                <td align="right">{{ $item['jumlah'] }}</td>
                                                <td align="right">{{ $item['persentase'] }} %</td>
                                                <td align="center">
                                                    <a href="{{ route('dashboard.customer-status-progres-show', $item['id_status_progres']) }}"
                                                        class="btn bg-maroon btn-xs">Detail Data</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Widget: user widget style 2 -->
                        <div class="card card-widget widget-user-2 shadow-sm">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-indigo">
                                <div class="widget-user-image">
                                    <img class="mt-3" style="max-width: 70px; height: auto;"
                                        src="{{ asset('config_media/' . ($logo->nama_file ?? 'default.png')) }}"
                                        alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">Statistik Penggunaan Bank</h3>
                                <h5 class="widget-user-desc">Per {{ $tglSekarang }}</h5>
                            </div>

                            <div class="card-footer p-0">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col" width="5%">No</th>
                                            <th scope="col" width="45%">Nama Bank</th>
                                            <th scope="col" width="10%">Jumlah Nasabah</th>
                                            <th scope="col" width="10%">Persentase</th>
                                            <th scope="col" width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataBank as $bank)
                                            <tr>
                                                <td class="text-center">{{ $bank['no'] }}</td>
                                                <td>{{ $bank['bank'] }}</td>
                                                <td align="right">{{ $bank['jumlah'] }}</td>
                                                <td align="right">{{ $bank['persentase'] }} %</td>
                                                <td align="center">
                                                    <a href="{{ route('dashboard.customer-bank-show', $bank['id_bank']) }}"
                                                        class="btn bg-indigo btn-xs">Detail Data</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-widget widget-user-2 shadow-sm">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-teal">
                                <div class="widget-user-image">
                                    <img class="mt-3" style="max-width: 70px; height: auto;"
                                        src="{{ asset('config_media/' . ($logo->nama_file ?? 'default.png')) }}"
                                        alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">Statistik Penjualan Marketing</h3>
                                <h5 class="widget-user-desc">Per {{ $tglSekarang }}</h5>
                            </div>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col" width="5%">No</th>
                                        <th scope="col" width="45%">Nama Marketing</th>
                                        <th scope="col" width="10%">Penjualan</th>
                                        <th scope="col" width="10%">Persentase</th>
                                        <th scope="col" width="15%">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataMarketing as $marketing)
                                        <tr>
                                            <td class="text-center">{{ $marketing['no'] }}</td>
                                            <td>{{ $marketing['marketing'] }}</td>
                                            <td align="right">{{ $marketing['jumlah'] }}</td>
                                            <td align="right">{{ $marketing['persentase'] }} %</td>
                                            <td align="center">
                                                <a href="{{ route('dashboard.customer-marketing-show', $marketing['id_marketing']) }}"
                                                    class="btn bg-teal btn-xs">Detail Data</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
            <!-- /.row -->
    </div><!-- /.container-fluid -->
    </section>
@endsection
