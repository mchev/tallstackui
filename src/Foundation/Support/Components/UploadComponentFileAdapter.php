<?php

namespace TallStackUi\Foundation\Support\Components;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

// The main objective of this class is to adapt the content received through
// the upload component mapped to two different formats: upload files or existing
// files. At the end, with this class we avoid possible errors in Blade that would
// occur without the correct formatting of the names used in Blade.
class UploadComponentFileAdapter
{
    public function __construct(
        private readonly bool $static,
        private readonly TemporaryUploadedFile|array $files,
        private ?Collection $collection = null,
    ) {
        $this->collection = collect(Arr::wrap($this->files));
    }

    /** @throws Exception */
    public function __invoke(): array
    {
        return ($this->static ? $this->static() : $this->upload())->toArray();
    }

    private function image(string $extension): bool
    {
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg']);
    }

    /** @throws Exception */
    private function static(): Collection
    {
        $required = ['name', 'extension', 'size', 'url'];

        // To avoid exceptions in Blade we ensure that the files
        // sent have a specific format, containing all these keys.
        $this->collection->each(function (array $file) use ($required) {
            foreach ($required as $key) {
                if (! array_key_exists($key, $file) || blank($file[$key])) {
                    throw new Exception("The [upload] as [static] requires the [{$key}] key to be present in all items.");
                }
            }
        });

        return $this->collection->map(function (array $file) {
            return [
                'temporary_name' => $file['name'],
                'real_name' => $file['name'],
                'extension' => $file['extension'],
                'size' => Number::fileSize($file['size']),
                'path' => $file['path'],
                'is_image' => $image = $this->image($file['extension']),
                'url' => ! $image ?: $file['url'],
            ];
        });
    }

    private function upload(): Collection
    {
        return $this->collection->map(function (TemporaryUploadedFile $file) {
            return [
                'temporary_name' => $file->getFilename(),
                'real_name' => $file->getClientOriginalName(),
                'extension' => $file->extension(),
                'size' => Number::fileSize($file->getSize()),
                'path' => $file->getPathname(),
                'is_image' => $image = $this->image($file->extension()),
                'url' => ! $image ?: $file->temporaryUrl(),
            ];
        });
    }
}
