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
    public function index()
    {
        return $this->service->index();
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
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file'], // TODO: size
            'filepath' => ['required', 'array'],
            'filepath.*' => ['required', 'string'],
            'device_id' => ['required', 'exists:devices,id'],
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
            'exploits.details.*.status' => ['required', Rule::in(config('iotci.exploits_logs.status'))],
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
}
