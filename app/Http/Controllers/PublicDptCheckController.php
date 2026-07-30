<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicDptCheckRequest;
use App\Services\VoterListService;
use Illuminate\View\View;

class PublicDptCheckController extends Controller
{
    public function __construct(
        private readonly VoterListService $listService
    ) {}

    public function index(): View
    {
        return view('public.dpt-check');
    }

    public function store(PublicDptCheckRequest $request): View
    {
        $voter = $this->listService->findDptVoter($request->validated('nik'));
        $submitted = true;

        return view('public.dpt-check', compact('voter', 'submitted'));
    }
}
