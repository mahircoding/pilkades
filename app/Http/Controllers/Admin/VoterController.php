<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportVotersRequest;
use App\Http\Requests\Admin\PromoteVotersToDptRequest;
use App\Models\ElectionPeriod;
use App\Models\Voter;
use App\Models\VoterImportBatch;
use App\Services\VoterImportService;
use App\Services\VoterListService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VoterController extends Controller
{
    public function __construct(
        private readonly VoterImportService $importService,
        private readonly VoterListService $listService,
    ) {}

    public function index(): View
    {
        $activePeriod = ElectionPeriod::where('is_active', true)->first();

        $voters = Voter::with('electionPeriod')
            ->when($activePeriod, fn ($q) => $q->where('election_period_id', $activePeriod->id))
            ->latest()
            ->paginate(20);

        return view('admin.voters.index', compact('voters', 'activePeriod'));
    }

    public function importCreate(): View
    {
        return view('admin.voters.import', [
            'periods' => ElectionPeriod::orderByDesc('id')->get(),
        ]);
    }

    public function importStore(ImportVotersRequest $request): RedirectResponse
    {
        $periodId = $request->input('election_period_id') ?? ElectionPeriod::where('is_active', true)->value('id');
        $period = ElectionPeriod::findOrFail($periodId);

        $batch = $this->importService->import($period, $request->file('file'), $request->user()->id);

        return redirect()->route('admin.voters.import-show', $batch)
            ->with('status', "Impor selesai: {$batch->accepted_rows} diterima, {$batch->duplicate_rows} duplikat, {$batch->invalid_rows} tidak valid.");
    }

    public function importShow(VoterImportBatch $batch): View
    {
        return view('admin.voters.import-show', [
            'batch'  => $batch->load(['electionPeriod', 'uploader']),
            'issues' => $batch->issues()->latest()->paginate(30),
        ]);
    }

    public function promoteToDpt(PromoteVotersToDptRequest $request): RedirectResponse
    {
        $period = ElectionPeriod::findOrFail($request->validated('election_period_id'));
        $count  = $this->listService->promoteToDpt($period);

        return redirect()->route('admin.voters.index')
            ->with('status', "{$count} pemilih DPS berhasil dipromosikan ke DPT.");
    }
}
