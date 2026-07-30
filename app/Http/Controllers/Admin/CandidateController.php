<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCandidateDocumentRequest;
use App\Http\Requests\Admin\StoreCandidateRequest;
use App\Http\Requests\Admin\UpdateCandidateRequest;
use App\Http\Requests\Admin\VerifyCandidateRequest;
use App\Models\Candidate;
use App\Models\ElectionPeriod;
use App\Services\CandidateDocumentService;
use App\Services\CandidateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function __construct(
        private readonly CandidateService $candidateService,
        private readonly CandidateDocumentService $documentService,
    ) {}

    public function index(): View
    {
        $activePeriod = ElectionPeriod::where('is_active', true)->first();

        return view('admin.candidates.index', [
            'candidates' => Candidate::with(['electionPeriod'])
                ->when($activePeriod, fn ($q) => $q->where('election_period_id', $activePeriod->id))
                ->latest()
                ->paginate(15),
            'activePeriod' => $activePeriod,
            'periods' => ElectionPeriod::orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.candidates.create', [
            'periods' => ElectionPeriod::orderByDesc('id')->get(),
        ]);
    }

    public function store(StoreCandidateRequest $request): RedirectResponse
    {
        $period = ElectionPeriod::findOrFail($request->validated()['election_period_id'] ?? ElectionPeriod::where('is_active', true)->value('id'));
        $candidate = $this->candidateService->createCandidate($period, $request->validated());

        if ($request->hasFile('photo')) {
            $this->candidateService->storePhoto($candidate, $request->file('photo'));
        }

        return redirect()->route('admin.candidates.show', $candidate)
            ->with('status', 'Data calon berhasil disimpan.');
    }

    public function show(Candidate $candidate): View
    {
        return view('admin.candidates.show', [
            'candidate' => $candidate->load(['electionPeriod', 'documents', 'verifier']),
        ]);
    }

    public function edit(Candidate $candidate): View
    {
        return view('admin.candidates.edit', [
            'candidate' => $candidate->load(['electionPeriod']),
            'periods' => ElectionPeriod::orderByDesc('id')->get(),
        ]);
    }

    public function update(UpdateCandidateRequest $request, Candidate $candidate): RedirectResponse
    {
        $this->candidateService->updateCandidate($candidate, $request->validated());

        if ($request->hasFile('photo')) {
            $this->candidateService->storePhoto($candidate, $request->file('photo'));
        }

        return redirect()->route('admin.candidates.show', $candidate)
            ->with('status', 'Data calon berhasil diperbarui.');
    }

    public function verify(VerifyCandidateRequest $request, Candidate $candidate): RedirectResponse
    {
        $this->candidateService->verify(
            $candidate,
            $request->user(),
            $request->validated('verification_status'),
            $request->validated('verification_notes'),
        );

        return redirect()->route('admin.candidates.show', $candidate)
            ->with('status', 'Status verifikasi calon berhasil diperbarui.');
    }

    public function storeDocument(StoreCandidateDocumentRequest $request, Candidate $candidate): RedirectResponse
    {
        $this->documentService->store($candidate, $request->file('file'), $request->validated('type'));

        return redirect()->route('admin.candidates.show', $candidate)
            ->with('status', 'Dokumen berhasil diunggah.');
    }
}
