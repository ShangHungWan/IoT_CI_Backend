<?php

namespace App\Services;

use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileService
{
    private $repo;

    public function __construct(FileRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create($file)
    {
        $path = $file->storeAs('file', Str::uuid() . "." . $file->extension());
        $attrributes = [
            'author_id' => Auth::user()->id,
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
        ];
        return $this->repo->create($attrributes);
    }
}
