<?php

namespace App\Http\Controllers\Siteplan;

use App\Http\Controllers\Controller;
use App\Models\KavlingPeta;
use App\Models\LokasiKavling;
use App\Models\ProgresUnitReady;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use TCPDF;

class SiteplanUnitReadyController extends Controller
{
    public function index()
    {
        $lokasiKavling = LokasiKavling::orderBy('urutan', 'asc')->get();

        $legend = ProgresUnitReady::orderBy('id', 'asc')
            ->get();

        return view('admin.siteplan.siteplan_unit_ready.index', compact('lokasiKavling', 'legend'));
    }

    public function show($id)
    {
        $data = KavlingPeta::with(['lokasi', 'customer'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    private function generateSVG($id_lokasi, $width = 500, $height = 200)
    {
        $lokasi = LokasiKavling::with(['masterSvg', 'kavlingPeta.unitReady'])
            ->findOrFail($id_lokasi);

        if (! $lokasi->masterSvg) {
            abort(404, "Data master_svg tidak ditemukan");
        }

        ob_start();

        echo $lokasi->masterSvg->header_xml;
        $header = str_replace(['[[lebar]]', '[[tinggi]]'], [$width, $height], $lokasi->masterSvg->header_svg);
        echo $header;

        foreach ($lokasi->kavlingPeta as $pt) {
            $warna = '#ffffff';
            if ($pt->status_ready && $pt->unitReady) {
                $warna = $pt->unitReady->warna;
            }

            $replacements = [$pt->map, $warna, $pt->matrik, $pt->kode_kavling];

            if ($pt->jenis_map === 'polygon') {
                echo str_replace(['[[1]]', '[[2]]', '[[3]]', '[[4]]'], $replacements, $lokasi->masterSvg->polygon_svg);
            } elseif ($pt->jenis_map === 'path') {
                echo str_replace(['[[1]]', '[[2]]', '[[3]]', '[[4]]'], $replacements, $lokasi->masterSvg->path_svg);
            }
        }

        echo $lokasi->masterSvg->footer_svg;

        return ob_get_clean();
    }

    public function cetakJPG($id_lokasi)
    {
        $svgContent = $this->generateSVG($id_lokasi);

        $svgFilename = "siteplan_{$id_lokasi}.svg";

        $svgPath = public_path("svg/{$svgFilename}");
        if (! file_exists(dirname($svgPath))) {
            mkdir(dirname($svgPath), 0755, true);
        }
        file_put_contents($svgPath, $svgContent);

        $endpoint   = 'https://aplikasikavling.com/convert/proses.php';
        $clientName = 'rhabayu_ready';

        $response = Http::attach(
            'svg_file',
            file_get_contents($svgPath),
            $svgFilename
        )->post($endpoint, [
            'client' => $clientName,
        ]);

        if (! $response->successful()) {
            abort(500, "Gagal upload ke server convert: " . $response->body());
        }

        $data = $response->json();
        if (! isset($data['jpg_url'])) {
            abort(500, "Gagal convert ke JPG: " . json_encode($data));
        }

        $jpgUrl = $data['jpg_url'];

        $jpgContent  = Http::get($jpgUrl)->body();
        $jpgFilename = basename(parse_url($jpgUrl, PHP_URL_PATH));

        $jpgPath = public_path("hasil/{$jpgFilename}");
        if (! file_exists(dirname($jpgPath))) {
            mkdir(dirname($jpgPath), 0755, true);
        }
        file_put_contents($jpgPath, $jpgContent);

        return response()->download($jpgPath);
    }

    public function cetakPDF($id_lokasi)
    {
        $namaPerusahaan = DB::table('konfigurasi')->value('nama_perusahaan');
        $lokasi         = DB::table('lokasi_kavling')->where('id', $id_lokasi)->first();
        $namaKavling    = $lokasi->nama_kavling ?? '-';
        $periodeCetak   = now()->translatedFormat('d F Y');

        $svgContent  = $this->generateSVG($id_lokasi);
        $svgFilename = "siteplan_{$id_lokasi}.svg";
        $svgPath     = public_path("svg/{$svgFilename}");
        if (! file_exists(dirname($svgPath))) {
            mkdir(dirname($svgPath), 0755, true);
        }

        file_put_contents($svgPath, $svgContent);

        $endpoint   = 'https://aplikasikavling.com/convert/proses.php';
        $clientName = 'rhabayu_ready';
        $response   = Http::attach('svg_file', file_get_contents($svgPath), $svgFilename)
            ->post($endpoint, ['client' => $clientName]);
        if (! $response->successful()) {
            abort(500, "Gagal upload ke server convert: " . $response->body());
        }

        $data = $response->json();
        if (! isset($data['jpg_url'])) {
            abort(500, "Gagal convert ke JPG: " . json_encode($data));
        }

        $jpgUrl      = $data['jpg_url'];
        $jpgContent  = Http::get($jpgUrl)->body();
        $jpgFilename = basename(parse_url($jpgUrl, PHP_URL_PATH));
        $jpgPath     = public_path("hasil/{$jpgFilename}");
        if (! file_exists(dirname($jpgPath))) {
            mkdir(dirname($jpgPath), 0755, true);
        }

        file_put_contents($jpgPath, $jpgContent);

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($namaPerusahaan);
        $pdf->SetTitle("Site Plan Unit Ready - {$namaKavling}");
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 7, strtoupper($namaPerusahaan), 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 6, 'SITE PLAN UNIT READY ' . strtoupper($namaKavling), 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Periode Cetak : ' . $periodeCetak, 0, 1, 'C');

        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.7);
        $pdf->Line(10, $pdf->GetY() + 2, 200, $pdf->GetY() + 2);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(10, $pdf->GetY() + 3, 200, $pdf->GetY() + 3);

        $pdf->Ln(10);

        $pdf->Image($jpgPath, 25, $pdf->GetY(), 160, 0, 'JPG');

        $pdf->Output("siteplan_{$namaKavling}.pdf", 'I');
    }
}
