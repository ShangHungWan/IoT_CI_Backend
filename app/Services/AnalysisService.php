<?php

namespace App\Services;

use App\Events\FileUploaded;
use App\Models\Analysis;
use App\Repositories\Interfaces\AnalysisRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AnalysisService
{
    private $analysis_repo;

    public function __construct(AnalysisRepositoryInterface $analysis_repo)
    {
        $this->analysis_repo = $analysis_repo;
    }

    public function index()
    {
        $user = Auth::user();
        if ("admin" === $user->type) {
            return $this->analysis_repo->all();
        } else if ("user" === $user->type) {
            return Auth::user()->analyses;
        }
    }

    public function create(array $attrributes)
    {
        $uuid = Str::uuid();

        $model = $this->analysis_repo->create([
            'uuid' => $uuid,
            'user_id' => Auth::user()->id,
            'device_id' => $attrributes['device_id'],
            'static_status' => config('iotci.analysis.status.TESTING'),
            'dynamic_status' => config('iotci.analysis.status.TESTING'),
            'exploits_time' => -1,
            'creds_time' => -1,
        ]);

        $files = [];
        foreach ($attrributes['files'] as $key => $file) {
            $origin_path = explode('/', $attrributes['filepath'][$key], 2)[1];
            $file->storeAs("file/{$uuid}", $origin_path);
            $files[] = [
                'analysis_uuid' => $uuid,
                'path' => "file/{$uuid}/{$origin_path}",
            ];
        }

        $this->analysis_repo->createManyFiles($model, $files);
        FileUploaded::dispatch($model);
        return $model;
    }

    public function createManyDynamic(Analysis $analysis, array $attrributes)
    {
        $this->analysis_repo->update($analysis, [
            'dynamic_status' => config('iotci.analysis.status.SUCCESS'),
            'exploits_time' => $attrributes['exploits']['time'],
            'creds_time' => $attrributes['creds']['time'],
        ]);

        $this->analysis_repo->createManyExploits($analysis, $attrributes['exploits']['details']);
        $this->analysis_repo->createManyCreds($analysis, $attrributes['creds']['details']);
    }
}
