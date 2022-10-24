<?php

declare(strict_types=1);

namespace App\Fake;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FakerImageProvider extends Base
{
    public function addFakeImagePath($srcPath, $storagePath): string
    {
        if (Storage::missing($storagePath)) {
            Storage::makeDirectory($storagePath);
        }
        $fileName = Str::random(6) . '.jpg';
        $nameFakeFile = Storage::path($storagePath) . '/' . $fileName;
        $files = glob(base_path($srcPath) . '/*.*');
        $fileIndex = array_rand($files);
        copy($files[$fileIndex], $nameFakeFile);
        return "/storage/$storagePath/$fileName";
    }
}
