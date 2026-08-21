<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\HakAkses;
use App\Models\Menu;
use App\Models\User;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AuthController extends Controller
{
    use LogAktivitasTrait;

    public function getLogin(): View
    {
        return view('admin.login');
    }

    public function postLogin(Request $request)
    {
        $rules = [
            'username' => 'required|exists:users,username',
            'password' => 'required',
        ];

        $messages = [
            'username.required' => 'Username wajib diisi.',
            'username.exists' => 'Username belum terdaftar.',
            'password.required' => 'Password wajib diisi.',
        ];

        $request->validate($rules, $messages);

        $user = User::where('username', $request->username)->first();

        if (! Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['Password salah.'],
                ],
            ], 422);
        }
        if ($user->status === 'BLOKIR') {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'username' => ['Akun anda telah diblokir.'],
                ],
            ], 422);
        }

        $hakAkses = HakAkses::where('id_user', $user->id)
            ->where('lihat', 1)
            ->get();

        $allowedMenuIds = $hakAkses->pluck('id_menu')->toArray();

        $getmenus = Menu::where('id_parent', 0)
            ->whereIn('id', $allowedMenuIds)
            ->orderBy('urutan')
            ->with(['children' => function ($query) use ($allowedMenuIds) {
                $query->whereIn('id', $allowedMenuIds);
            }])
            ->get()
            ->filter(function ($menu) {
                $menu->setRelation('children', $menu->children->filter(fn ($child) => Route::has($child->route_name))->values());

                return $menu->children->isNotEmpty() || Route::has($menu->route_name);
            })
            ->values();

        session([
            'getmenus' => $getmenus,
        ]);

        $this->logLogin();

        return response()->json([
            'status' => 'success',
            'redirect_url' => route('beranda.index'),
        ]);
    }

    public function logout(): RedirectResponse
    {
        $this->logLogout();
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout Berhasil.');
    }
}
