<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;
use Illuminate\View\View;

class PeminjamanController extends Controller
{

    public function index(): View
    {
        return view('peminjamen.index', ['peminjamen' => Auth::user()->activePeminjamen()]);
    }

    public function store(Buku $buku, Request $request): RedirectResponse
    {
        $validator = ValidatorFacade::make($request->all(), [
            'number_borrowed' => 'required|int',
            'return_date'     => 'required',
        ]);

        $validator->after(function (Validator $validator) use ($buku) {
            $numberBorrowed = $validator->safe()->number_borrowed;
            $availableCopies = $buku->availableCopies();
            if ($numberBorrowed > $availableCopies) {
                $validator->errors()->add(
                    'number_borrowed',
                    "Kamu tidak bisa meminjam lebih dari {$availableCopies} buku"
                );
            }
        });

        if ($validator->fails()) {
            return to_route('peminjamen.create', ['buku' => $buku])
                ->withErrors($validator)
                ->withInput();
        }

        $peminjamanDetails = $validator->safe()->only([
            'number_borrowed',
            'return_date',
        ]);

        $peminjamanDetails['buku_id'] = $buku->id;
        $peminjamanDetails['user_id'] = Auth::user()->id;

        Peminjaman::create($peminjamanDetails);

        return to_route('peminjamen.index')
            ->with('status', 'Buku berhasil di pinjam');
    }

    public function create(Buku $buku): View
    {
        return view('peminjamen.create', ['buku' => $buku]);
    }

    public function terminate(Peminjaman $peminjaman): RedirectResponse
    {
        $peminjaman->terminate();

        return to_route('peminjamen.index')
            ->with('status', 'Buku berhasil dikembalikan');
    }
}
