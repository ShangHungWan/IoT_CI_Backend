<?php

namespace App\Services;

use App\Events\BinaryUploaded;
use App\Events\CodeUploaded;
use App\Models\Analysis;
use App\Repositories\Interfaces\AnalysisRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AnalysisService
{
    private $analysis_repo;

    public function __construct(AnalysisRepositoryInterface $analysis_repo)
    {
        $this->analysis_repo = $analysis_repo;
    }

    public function index(array $conditions)
    {
        $user = Auth::user();
        if ("admin" === $user->type) {
            return $conditions['os_less'] ?
            $this->analysis_repo->all(['*'], ['static_status' => 'n/a']) :
            $this->analysis_repo->all(['*'], ['fuzzing_status' => 'n/a']);
        } else if ("user" === $user->type) {
            return $conditions['os_less'] ?
            $user->os_less_analyses() :
            $user->linux_based_analyses();
        }
    }

    public function create(array $attrributes)
    {
        $uuid = Str::uuid();

        if (isset($attrributes['files'])) {
            $model = $this->analysis_repo->create([
                'uuid' => $uuid,
                'user_id' => Auth::user()->id,
                'device_id' => $attrributes['device_id'],
                'static_status' => config('enum.analysis.status.TESTING'),
                'dynamic_status' => config('enum.analysis.status.TESTING'),
                'fuzzing_status' => config('enum.analysis.status.N/A'),
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
            CodeUploaded::dispatch($model);
        } else {
            $model = $this->analysis_repo->create([
                'uuid' => $uuid,
                'user_id' => Auth::user()->id,
                'device_id' => $attrributes['device_id'],
                'static_status' => config('enum.analysis.status.N/A'),
                'dynamic_status' => config('enum.analysis.status.N/A'),
                'fuzzing_status' => config('enum.analysis.status.TESTING'),
                'exploits_time' => -1,
                'creds_time' => -1,
            ]);
            $attrributes['binary']->storeAs("file", $uuid);
            BinaryUploaded::dispatch($model);
        }
        return $model;
    }

    public function createManyDynamic(Analysis $analysis, array $attrributes)
    {
        $this->analysis_repo->update($analysis, [
            'dynamic_status' => config('enum.analysis.status.SUCCESS'),
            'exploits_time' => $attrributes['exploits']['time'],
            'creds_time' => $attrributes['creds']['time'],
        ]);

        $this->analysis_repo->createManyExploits($analysis, $attrributes['exploits']['details']);
        $this->analysis_repo->createManyCreds($analysis, $attrributes['creds']['details']);
    }

    public function updateFuzzing(Analysis $analysis, array $attrributes)
    {
        $this->analysis_repo->update($analysis, [
            'fuzzing_status' => $attrributes['status'],
            'crashes_number' => $attrributes['crashes_number'],
            'hangs_number' => $attrributes['hangs_number'],
            'function_coverage_rate' => $attrributes['function_coverage_rate'],
        ]);

        if (isset($attrributes['message'])) {
            Log::debug($attrributes['message']);
        }
    }
}
