<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Services\AnalysisService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnalysisController extends Controller
{
    private $service;

    public function __construct(AnalysisService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $conditions = $request->validate([
            'os_less' => ['required', 'boolean'],
        ]);

        return $this->service->index($conditions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attrributes = $request->validate([
            'files' => ['required_without:binary', 'array'],
            'files.*' => ['required_without:binary', 'file'], // TODO: size
            'filepath' => ['required_without:binary', 'array'],
            'filepath.*' => ['required_without:binary', 'string'],
            'device_id' => ['required:binary', 'exists:devices,id'],
            'binary' => ['required_without:files', 'file'],
        ]);

        return $this->service->create($attrributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Analysis  $analysis
     * @return \Illuminate\Http\Response
     */
    public function show(Analysis $analysis)
    {
        $analysis->load([
            'exploitsLogs',
            'credsLogs',
            'files.staticLogs',
            'device',
        ])
            ->loadCount([
                'credsLogs',
                'exploitsLogs' => function ($query) {
                    $query->where('status', 'vulnerable');
                },
            ]);
        return $analysis;
    }

    public function storeDynamic(Request $request, Analysis $analysis)
    {
        $attrributes = $request->validate([
            'exploits' => ['required'],
            'exploits.time' => ['required', 'numeric'],
            'exploits.details' => ['required', 'array'],
            'exploits.details.*.name' => ['required'],
            'exploits.details.*.port' => ['required', 'integer'],
            'exploits.details.*.service' => ['required'],
            'exploits.details.*.status' => ['required', Rule::in(config('enum.exploits_logs.status'))],
            'creds' => ['required'],
            'creds.time' => ['required', 'numeric'],
            'creds.details' => ['nullable', 'array'],
            'creds.details.*.service' => ['required'],
            'creds.details.*.port' => ['required', 'integer'],
            'creds.details.*.username' => ['required'],
            'creds.details.*.password' => ['required'],
        ]);

        return $this->service->createManyDynamic($analysis, $attrributes);
    }

    public function storeFuzzing(Request $request, Analysis $analysis)
    {
        $attrributes = $request->validate([
            'status' => ['required', Rule::in(config('enum.analysis.status'))],
            'message' => ['nullable', 'string'],
            'crashes_number' => ['required', 'numeric'],
            'hangs_number' => ['required', 'numeric'],
            'function_coverage_rate' => ['nullable', 'string', 'min:0'],
        ]);

        return $this->service->updateFuzzing($analysis, $attrributes);
    }
}
