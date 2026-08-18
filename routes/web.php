<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\Customer\ArsipCustomerController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\UploudFileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Keuangan\HutangController;
use App\Http\Controllers\Keuangan\KategoriTransaksiController;
use App\Http\Controllers\Keuangan\LaporanArusKasController;
use App\Http\Controllers\Keuangan\MutasiSaldoController;
use App\Http\Controllers\Keuangan\PemasukanController;
use App\Http\Controllers\Keuangan\PengeluaranController;
use App\Http\Controllers\Keuangan\PiutangController;
use App\Http\Controllers\Keuangan\RetensiController as KeuanganRetensiController;
use App\Http\Controllers\Legal\BphtbSSPController;
use App\Http\Controllers\Legal\BerkasPengajuanController;
use App\Http\Controllers\Legal\ListrikAirController;
use App\Http\Controllers\Marketing\MarketingOfflineController;
use App\Http\Controllers\Master\BankKPRController;
use App\Http\Controllers\Master\BankTransaksiController;
use App\Http\Controllers\Master\KavlingController;
use App\Http\Controllers\Master\LokasiKavlingController;
use App\Http\Controllers\Master\NotarisController;
use App\Http\Controllers\Master\PerusahaanController;
use App\Http\Controllers\Master\RetensiController;
use App\Http\Controllers\Master\UploadTemplateController;
use App\Http\Controllers\PanduanAplikasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengajuanHoldController;
use App\Http\Controllers\Pengaturan\HakAksesController;
use App\Http\Controllers\Pengaturan\KontenController;
use App\Http\Controllers\Pengaturan\ListPenjualanController;
use App\Http\Controllers\Pengaturan\LogAktivitasController;
use App\Http\Controllers\Pengaturan\PengaturanMediaController;
use App\Http\Controllers\Pengaturan\PengaturanPenggunaController;
use App\Http\Controllers\Pengaturan\PengaturanProfilController;
use App\Http\Controllers\Pengaturan\RoleUserController;
use App\Http\Controllers\Siteplan\SiteplanPenjualanController;
use App\Http\Controllers\Siteplan\SiteplanListrikController;
use App\Http\Controllers\Siteplan\SiteplanBphtbSSPController;
use App\Http\Controllers\Siteplan\SiteplanAirController;
use App\Http\Controllers\Siteplan\BalikNamaController;
use App\Http\Controllers\Siteplan\SiteplanBalikNamaController;
use App\Http\Controllers\Siteplan\PublicSiteplanController;
use App\Http\Controllers\Transaksi\AccBankController;
use App\Http\Controllers\Transaksi\AkadController;
use App\Http\Controllers\Transaksi\BastController;
use App\Http\Controllers\Transaksi\GantiNamaController;
use App\Http\Controllers\Transaksi\SPPRController;
use App\Http\Controllers\Transaksi\PembelianCancelController;
use App\Http\Controllers\Transaksi\PindahUnitController;
use App\Http\Controllers\Transaksi\PPJBController;
use App\Http\Controllers\Transaksi\WawancaraController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $frontPage = DB::table('konfigurasi')->value('front_page');

    if ($frontPage == 1) {
        return redirect()->route('homepage');
    }

    if (! Auth::check()) {
        return redirect()->route('login');
    }

    $menus = session('getmenus');

    if (! $menus || $menus->isEmpty()) {
        Auth::logout();

        return redirect()->route('login')->withErrors(['msg' => 'Anda tidak punya akses menu.']);
    }

    $menus = $menus->filter(function ($menu) {
        $menu->setRelation('children', $menu->children->filter(fn ($child) => Route::has($child->route_name))->values());

        return $menu->children->isNotEmpty() || Route::has($menu->route_name);
    })->values();

    session(['getmenus' => $menus]);

    $firstMenu = $menus->first();

    if ($firstMenu) {
        if ($firstMenu->children && $firstMenu->children->isNotEmpty()) {
            return redirect()->route($firstMenu->children->first()->route_name);
        }

        return redirect()->route($firstMenu->route_name);
    }

    return redirect()->route('login');
});

Route::get('/booking', [PengajuanHoldController::class, 'booking'])->name('booking');
Route::get('/booking-sukses', [PengajuanHoldController::class, 'bookingSukses'])->name('booking.sukses');
Route::get('siteplan', [PublicSiteplanController::class, 'index'])->name('public.siteplan.index');
Route::get('siteplan/detail/{id}', [PublicSiteplanController::class, 'show'])->name('public.siteplan.show');

Route::get('/get-kavling-hold/{id_lokasi}', [PengajuanHoldController::class, 'getKavlingHold'])->name('pengajuan-hold.getKavling');
Route::get('/get-harga-kavling-hold/{id_kavling}', [PengajuanHoldController::class, 'getHargaKavlingHold'])->name('pengajuan-hold.getHargaKavling');
Route::get('/get-kavling/{id_lokasi}', [PengajuanHoldController::class, 'getKavling'])->name('booking.getKavling');
Route::get('/get-harga-kavling/{id_kavling}', [PengajuanHoldController::class, 'getHargaKavling'])->name('booking.getHargaKavling');
Route::post('booking/store', [PengajuanHoldController::class, 'bookingStore'])->name('store.booking');
Route::get('/homepage', [KontenController::class, 'homepage'])->name('homepage');
Route::get('/agents', [KontenController::class, 'marketing'])->name('marketing');
Route::get('/aboutus', [KontenController::class, 'aboutus'])->name('aboutus');
Route::get('/progres', [KontenController::class, 'progres'])->name('progres');
Route::get('/siteplan/{id}', [KontenController::class, 'siteplan'])->name('konten.siteplan');

Route::group(['middleware' => 'guest'], function () {
    Route::get('admin/login', [AuthController::class, 'getLogin'])->name('login');
    Route::post('admin/post-login', [AuthController::class, 'postLogin'])->name('admin.loginPost');
});

Route::get('/hutang/sisa-bayar/{id}', [HutangController::class, 'getSisaBayar']);
Route::get('/piutang/sisa-bayar/{id}', [PiutangController::class, 'getSisaBayar']);

Route::middleware(['auth'])->group(function () {
    Route::get('admin/beranda', [BerandaController::class, 'index'])->name('beranda.index');

    Route::prefix('admin')->controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard.index');
        Route::get('/dashboard/lokasi-penjualan/{id}', 'showLokasiPenjualan')->name('dashboard.lokasi-penjualan-show');
        Route::get('/dashboard/customer-status-progres/{id}', 'showCustomer')->name('dashboard.customer-status-progres-show');
        Route::get('/dashboard/customer-bank/{id}', 'showCustomer')->name('dashboard.customer-bank-show');
        Route::get('/dashboard/customer-marketing/{id}', 'showCustomer')->name('dashboard.customer-marketing-show');
        Route::get('/total-unit', 'totalUnit')->name('dashboard.total-unit');
        Route::get('/booking-unit', 'booking')->name('dashboard.booking-unit');
        Route::get('/wawancara-unit', 'wawancara')->name('dashboard.wawancara-unit');
        Route::get('/akad-unit', 'akad')->name('dashboard.akad-unit');

        Route::prefix('/siteplan')->group(function () {
            Route::resource('balik-nama', BalikNamaController::class);

            Route::get('st-balik-nama/cetak/pdf/{id_lokasi}', [SiteplanBalikNamaController::class, 'cetakPDF'])->name('st-balik-nama.cetak.pdf');
            Route::get('st-balik-nama/cetak/jpg/{id_lokasi}', [SiteplanBalikNamaController::class, 'cetakJPG'])->name('st-balik-nama.cetak.jpg');
            Route::resource('st-balik-nama', SiteplanBalikNamaController::class);

            Route::get('st-bphtb-ssp/cetak/pdf/{id_lokasi}', [SiteplanBphtbSSPController::class, 'cetakPDF'])->name('st-bphtb-ssp.cetak.pdf');
            Route::get('st-bphtb-ssp/cetak/jpg/{id_lokasi}', [SiteplanBphtbSSPController::class, 'cetakJPG'])->name('st-bphtb-ssp.cetak.jpg');
            Route::resource('st-bphtb-ssp', SiteplanBphtbSSPController::class);

            Route::get('siteplan-air/cetak/pdf/{id_lokasi}', [SiteplanAirController::class, 'cetakPDF'])->name('siteplan-air.cetak.pdf');
            Route::get('siteplan-air/cetak/jpg/{id_lokasi}', [SiteplanAirController::class, 'cetakJPG'])->name('siteplan-air.cetak.jpg');
            Route::resource('siteplan-air', SiteplanAirController::class);

            Route::get('siteplan-listrik/cetak/pdf/{id_lokasi}', [SiteplanListrikController::class, 'cetakPDF'])->name('siteplan-listrik.cetak.pdf');
            Route::get('siteplan-listrik/cetak/jpg/{id_lokasi}', [SiteplanListrikController::class, 'cetakJPG'])->name('siteplan-listrik.cetak.jpg');
            Route::resource('siteplan-listrik', SiteplanListrikController::class);

            Route::resource('siteplan-penjualan', SiteplanPenjualanController::class);
            Route::get('siteplan-penjualan/cetak/pdf/{id_lokasi}', [SiteplanPenjualanController::class, 'cetakPDF'])->name('siteplan-penjualan.cetak.pdf');
            Route::get('siteplan-penjualan/cetak/jpg/{id_lokasi}', [SiteplanPenjualanController::class, 'cetakJPG'])->name('siteplan-penjualan.cetak.jpg');
            Route::post('siteplan-penjualan/cetak', [SiteplanPenjualanController::class, 'cetak'])->name('penjualan.cetak');

        });
    });

    Route::prefix('admin')->group(function () {
        Route::get('/pengajuan-hold/arsip', [PengajuanHoldController::class, 'viewArsip'])->name('pengajuan-hold.arsip');
        Route::post('/pengajuan-hold/{id}/upload', [PengajuanHoldController::class, 'upload'])->name('pengajuan-hold.upload');
        Route::post('/pengajuan-hold/{id}/delete-file', [PengajuanHoldController::class, 'deleteFile'])->name('pengajuan-hold.delete-file');
        Route::get('/pengajuan-hold/{id}/verifikasi', [PengajuanHoldController::class, 'verifikasi'])->name('pengajuan-hold.verifikasi');
        Route::post('/pengajuan-hold/{id}/verifikasi', [PengajuanHoldController::class, 'simpanVerifikasi'])->name('pengajuan-hold.verifikasi.simpan');
        Route::get('/pengajuan-hold/{id}/arsip-detail', [PengajuanHoldController::class, 'arsipDetail'])->name('pengajuan-hold.arsip.detail');
        Route::resource('pengajuan-hold', PengajuanHoldController::class);
    });

    Route::prefix('admin')->controller(PembayaranController::class)->group(function () {
        Route::get('pembayaran/{id}/detail', 'detail')->name('pembayaran.detail');
        Route::get('pembayaran/detail-tagihan/{id}', 'detailTagihan')->name('pembayaran.detail-tagihan');
        Route::get('pembayaran/detail-pemasukan/{id}', 'detailPemasukan')->name('pembayaran.detail-pemasukan');
        Route::put('pembayaran/update-harga-rumah/{id}', 'UpdateHargaRumah')->name('Pembayaran.update-harga-rumah');
        Route::put('pembayaran/update-estimasi-plafon/{id}', 'updateEstimasiPlafon')->name('Pembayaran.update-estimasi-plafon');
        Route::put('pembayaran/update-sbum/{id}', 'updateSbum')->name('Pembayaran.update-sbum');

        Route::get('Pembayaran/rekap-pembayaran', 'rekapPembayaran')->name('pembayaran.rekap');

        Route::post('pembayaran/tambah-tagihan/{id}', 'tambahTagihan')->name('pembayaran.tambah-tagihan');
        Route::post('pembayaran/tambah-pemasukan/{id}', 'tambahPemasukan')->name('pembayaran.tambah-pemasukan');
        Route::post('pembayaran/tambah-pencairan-kpr/{id}', 'tambahPencairanKpr')->name('pembayaran.tambah-pencairan-kpr');

        Route::delete('pembayaran/delete-tagihan/{id}', 'DeleteTagihan')->name('pembayaran.delete-tagihan');
        Route::put('pembayaran/update-tagihan/{id}', 'updateTagihan')->name('pembayaran.update-tagihan');
        Route::delete('pembayaran/delete-pemasukan/{id}', 'DeletePemasukan')->name('pembayaran.delete-pemasukan');
        Route::get('pembayaran/edit-pemasukan/{id}', 'editPemasukan')->name('pembayaran.edit-pemasukan');
        Route::put('pembayaran/update-pemasukan/{id}', 'updatePemasukan')->name('pembayaran.update-pemasukan');
        Route::get('/customer/cetak-rekap/{id}', 'cetakRekap')->name('customer.cetak-rekap');
        Route::get('/pembayaran/cetak/{id}', 'cetak')->name('pembayaran.cetak');
        Route::get('/pembayaran/print/{id}', 'print')->name('pembayaran.print');
        Route::resource('pembayaran', PembayaranController::class);
    });

    Route::prefix('admin/transaksi')->group(function () {
        Route::resource('wawancara', WawancaraController::class);
        Route::get('/wawancara/detail-customer/{id_customer}', [WawancaraController::class, 'detailCustomer'])->name('wawancara.detail-customer');
        Route::post('/wawancara/acc-bank/{id_wawancara}', [WawancaraController::class, 'simpanSp3k'])->name('wawancara.sp3k');
        Route::get('/wawancara/{id}/acc', [WawancaraController::class, 'acc'])->name('wawancara.acc');
        Route::get('/sp3k/data', [AccBankController::class, 'getDataSp3k'])->name('sp3k.data');

        Route::resource('acc-bank', AccBankController::class);
        Route::resource('akad', AkadController::class);
        Route::post('akad/seleksi-customer/{id_akad}', [AkadController::class, 'seleksiCustomer'])->name('akad.seleksi-customer');
        Route::get('akad/seleksi-customer/hadir/{id_detail}', [AkadController::class, 'showHadir'])->name('akad.seleksi-customer.get-hadir');
        Route::post('akad/seleksi-customer/hadir/{id_detail}', [AkadController::class, 'updateHadir'])->name('akad.seleksi-customer.update-hadir');

        Route::get('akad/detail-excel/{id}', [AkadController::class, 'cetakDetailExcel'])->name('akad.detail.excel');
        Route::delete('akad/detail/{id}', [AkadController::class, 'destroyDetail'])->name('akad.detail.destroy');
        Route::get('akad/detail-pdf/{id}', [AkadController::class, 'cetakDetailPDF'])->name('akad.detail.pdf');
        Route::get('/akad/{id}/seleksi', [AkadController::class, 'seleksi'])->name('akad.detail.seleksi');
        Route::get('akad/get-customer-detail/{id}', [AkadController::class, 'getCustomerDetail'])->name('akad.getCustomerDetail');
        Route::get('akad/detail/data/{id}', [AkadController::class, 'detailData'])->name('akad.detail.data');
        Route::get('/akad/download/{id}', [AkadController::class, 'downloadWord'])->name('akad.download');

        Route::get('cetak-bast/{id_customer}', [BastController::class, 'cetakBast'])
            ->name('bast.cetak');
        Route::get('/bast/generate-no', [BASTController::class, 'generateNoBAST'])->name('generateNoBAST');
        Route::resource('bast', BASTController::class);
        Route::get('cetak-ppjb/{id_customer}', [PPJBController::class, 'cetakPPJB'])
            ->name('ppjb.cetak');
        Route::resource('ppjb', PPJBController::class);

        Route::get('pindah-unit/kwitansi/{id}', [PindahUnitController::class, 'cetakKwitansi'])->name('pindah-unit.kwitansi');
        Route::get('pindah-unit/cetak-word/{id}', [PindahUnitController::class, 'cetakWord'])->name('pindah-unit.cetak-word');
        Route::get('pindah-unit/detail-customer/{id_customer}', [PindahUnitController::class, 'detailCustomer'])->name('pindah-unit.detail-customer');
        Route::get('pindah-unit/get-kavling-baru/{id_customer}', [PindahUnitController::class, 'getKavlingBaru'])->name('pindah-unit.getKavlingBaru');

        Route::get('pembatalan/kwitansi/{id}', [PembelianCancelController::class, 'cetakKwitansi'])->name('pembatalan.kwitansi');

        Route::resource('pindah-unit', PindahUnitController::class);
        Route::resource('pembelian-cancel', PembelianCancelController::class);
        Route::get('ganti-nama/cetak/{id}', [GantiNamaController::class, 'cetak'])->name('ganti-nama.cetak');
        Route::resource('ganti-nama', GantiNamaController::class);
        Route::get('ganti-nama/{id}/get-customer', [GantiNamaController::class, 'getCustomer'])->name('ganti-nama.get-customer');

        Route::resource('sppr', SPPRController::class);
        Route::get('sppr/{id}/cetak', [SPPRController::class, 'cetak'])->name('sppr.cetak');
        Route::get('sppr/get-customer-detail/{id}', [SPPRController::class, 'getCustomerDetail'])->name('sppr.get-customer-detail');
    });

    Route::prefix('admin/customer')->group(function () {
        Route::get('get-kavling/{idLokasi}', [CustomerController::class, 'getKavling'])->name('customer.getKavling');
        Route::get('get-harga-kavling/{id_kavling}', [CustomerController::class, 'getHargaKavling'])->name('customer.getHargaKavling');
        Route::get('customer/cetak', [CustomerController::class, 'cetakData'])->name('customer.cetak');
        Route::get('customer/{id_customer}/subsidi-cetak', [CustomerController::class, 'cetakFormSubsidi'])->name('subsidi.cetak');
        Route::get('customer/print-document/{template_code}/{id_customer}', [CustomerController::class, 'printDocument'])->name('customer.print-document');

        Route::get('customer/tempo', [CustomerController::class, 'getTempo'])->name('customer.tempo');
        Route::get('customer/tempo/{id}', [CustomerController::class, 'showTempo'])->name('customer.show-tempo');
        Route::put('customer/tempo/{id}', [CustomerController::class, 'postTempo'])->name('customer.post-tempo');
        Route::resource('customer', CustomerController::class);

        Route::resource('upload-file', UploudFileController::class);
        Route::resource('arsip-customer', ArsipCustomerController::class);
    });

    Route::prefix('admin/marketing')->group(function () {
        Route::resource('marketing-offline', MarketingOfflineController::class);
    });

    Route::prefix('admin/legal')->group(function () {
        Route::resource('bphtb-ssp', BphtbSSPController::class);
        Route::resource('listrik-air', ListrikAirController::class);
        Route::resource('pengajuan-berkas', BerkasPengajuanController::class);
    });

    Route::prefix('admin/keuangan')->group(function () {
        Route::get('/laporan-arus-kas/filter', [LaporanArusKasController::class, 'filter'])->name('laporan-arus-kas.filter');
        Route::get('laporan-arus-kas/export-pdf', [LaporanArusKasController::class, 'exportPdf'])->name('laporan-arus-kas.exportPDF');
        Route::get('laporan-arus-kas/export-excel', [LaporanArusKasController::class, 'exportExcel'])->name('laporan-arus-kas.exportExcel');

        Route::resource('pemasukan', PemasukanController::class);
        Route::resource('pengeluaran', PengeluaranController::class);
        Route::resource('hutang', HutangController::class);
        Route::resource('piutang', PiutangController::class);
        Route::resource('kategori-transaksi', KategoriTransaksiController::class);
        Route::resource('mutasi-saldo', MutasiSaldoController::class);
        Route::resource('laporan-arus-kas', LaporanArusKasController::class);
        Route::get('retensi', [KeuanganRetensiController::class, 'index'])->name('keuangan-retensi.index');
    });

    Route::prefix('admin/master')->group(function () {
        // Perusahaan
        Route::resource('perusahaan', PerusahaanController::class)->except('show');
        Route::put('lokasi-kavling/{id}/updateDetail', [LokasiKavlingController::class, 'updateDetail'])->name('LokasiKavling.updateDetail');
        Route::get('/lokasi-kavling/{id}/setting', [LokasiKavlingController::class, 'setting'])->name('LokasiKavling.setting');
        Route::put('/lokasi-kavling/{id}/setting', [LokasiKavlingController::class, 'updateSetting'])->name('LokasiKavling.updateSetting');
        Route::get('/lokasi-kavling/{id}/detail', [LokasiKavlingController::class, 'detail'])->name('LokasiKavling.detail');
        Route::get('/lokasi-kavling/export/{id}', [LokasiKavlingController::class, 'exportDetail'])->name('LokasiKavling.export');
        Route::post('/lokasi-kavling/upload-excel', [LokasiKavlingController::class, 'uploadExcel'])->name('LokasiKavling.uploadExcel');
        Route::get('/lokasi-kavling/{id}/edit-detail', [LokasiKavlingController::class, 'editDetail'])->name('LokasiKavling.editDetail');
        Route::put('/lokasi-kavling/{id}/update-detail', [LokasiKavlingController::class, 'updateDetail'])->name('LokasiKavling.updateDetail');
        Route::get('get-perusahaan', [LokasiKavlingController::class, 'getPerusahaan'])->name('getPerusahaan');

        Route::get('/kavling/cetak-excel/{id_lokasi}', [KavlingController::class, 'cetakExcel'])->name('kavling.cetakExcel');
        Route::get('kavling/cetak-pdf/{id_lokasi}', [KavlingController::class, 'cetakPdf'])->name('kavling.cetakPdf');
        Route::post('kavling/import-excel', [KavlingController::class, 'importExcel'])->name('kavling.importExcel');
        Route::post('kavling/uploud', [KavlingController::class, 'uploud'])->name('kavling.uploud');
        Route::get('kavling/{id}/lampiran', [KavlingController::class, 'lampiran'])->name('kavling.lampiran');
        Route::post('kavling/{id}/lampiran/upload', [KavlingController::class, 'uploadLampiran'])->name('kavling.lampiran.upload');

        Route::resource('lokasi-kavling', LokasiKavlingController::class);
        Route::put('lokasi-kavling-denah/{id}', [LokasiKavlingController::class, 'updateDenah'])->name('lokasi-kavling-denah.update');
        Route::resource('kavling', KavlingController::class);
        Route::put('kavling/foto/{id}', [KavlingController::class, 'updateFoto'])->name('kavling.foto-update');
        Route::resource('bank-transaksi', BankTransaksiController::class);
        Route::resource('bank-kpr', BankKPRController::class);
        Route::get('bank/data/list', [BankTransaksiController::class, 'getBankList'])->name('bank.list');
        Route::resource('retensi', RetensiController::class);
        Route::resource('notaris', NotarisController::class);
        Route::resource('upload-template', UploadTemplateController::class);
    });

    Route::prefix('admin/pengaturan')->group(function () {
        Route::resource('pengaturan-profil', PengaturanProfilController::class);
        Route::resource('pengaturan-media', PengaturanMediaController::class);
        Route::put('pengaturan-pengguna/user-update/{id}', [PengaturanPenggunaController::class, 'updateUser'])->name('pengaturan-pengguna.update-user');
        Route::resource('pengaturan-pengguna', PengaturanPenggunaController::class);
        Route::resource('list-penjualan', ListPenjualanController::class);
        Route::resource('hak-akses', HakAksesController::class);
        Route::get('get-hak-akses', [HakAksesController::class, 'getHakAkses'])->name('admin.getHakAkses');
        Route::put('updateHakAkses', [HakAksesController::class, 'updateHakAkses'])->name('admin.updateHakAkses');
        Route::resource('role-user', RoleUserController::class);
        Route::get('get-role-user', [RoleUserController::class, 'getRoleUser'])->name('admin.getRoleUser');
        Route::put('updateRoleUser', [RoleUserController::class, 'updateRoleUser'])->name('admin.updateRoleUser');
        Route::resource('log-aktivitas', LogAktivitasController::class)->only(['index']);
    });

    Route::get('/hutang/sisa-bayar/{id}', [HutangController::class, 'getSisaBayar']);
    Route::get('/piutang/sisa-bayar/{id}', [PiutangController::class, 'getSisaBayar']);

    Route::get('/panduan-aplikasi/menu', [PanduanAplikasiController::class, 'getMenuByRole']);
    Route::resource('panduan-aplikasi', PanduanAplikasiController::class);

    Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

Route::get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
})->name('refresh.csrf');

Route::get('/paksa-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/')->with('success', 'Anda telah logout.');
});
