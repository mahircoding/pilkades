<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreElectionPeriodRequest;
use App\Http\Requests\Admin\UpdateElectionPeriodRequest;
use App\Models\ElectionPeriod;
use App\Services\ElectionPeriodService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ElectionPeriodController extends Controller
{
    public function __construct(
        private readonly ElectionPeriodService $service
    ) {}

    public function index(): View
    {
        return view('admin.election-periods.index', [
            'periods' => ElectionPeriod::latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.election-periods.create');
    }

    public function store(StoreElectionPeriodRequest $request): RedirectResponse
    {
        $this->service->createPeriod($request->validated());

        return redirect()->route('admin.election-periods.index')
            ->with('status', 'Periode pemilihan berhasil ditambahkan.');
    }

    public function edit(ElectionPeriod $periode_pemilihan): View
    {
        return view('admin.election-periods.edit', [
            'period' => $periode_pemilihan,
        ]);
    }

    public function update(UpdateElectionPeriodRequest $request, ElectionPeriod $periode_pemilihan): RedirectResponse
    {
        $this->service->updatePeriod($periode_pemilihan, $request->validated());

        return redirect()->route('admin.election-periods.index')
            ->with('status', 'Periode pemilihan berhasil diperbarui.');
    }
}
