<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Traits\InteractWithProviders;

#[SoftPersonalization('slide')]
class Slide extends BaseComponent implements Personalization
{
    use InteractWithProviders;

    public function __construct(
        public ?string $id = 'modal',
        public ?string $zIndex = null,
        public string|bool|null $wire = null,
        public ?string $title = null,
        public ?string $footer = null,
        public ?bool $blur = null,
        public ?bool $left = null,
        public ?bool $persistent = null,
        public ?string $size = null,
        public string $entangle = 'slide',
    ) {
        $this->configurations(); // TODO remove this

        $this->entangle = is_string($this->wire) ? $this->wire : (! is_bool($this->wire) ? $this->entangle : 'slide');
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.slide');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'fixed inset-0 bg-gray-400 bg-opacity-50 transition-opacity',
                'second' => 'fixed inset-0 overflow-hidden',
                'third' => 'absolute inset-0 overflow-hidden',
                'fourth' => 'pointer-events-none fixed inset-y-0 flex max-w-full',
                'fifth' => 'flex h-full flex-col overflow-y-auto bg-white py-6 shadow-xl soft-scrollbar dark:bg-dark-700',
            ],
            'title' => [
                'text' => 'whitespace-normal font-medium text-md text-secondary-600 dark:text-dark-300',
                'close' => 'h-5 w-5 cursor-pointer text-secondary-300',
            ],
            'body' => 'grow rounded-b-xl px-6 py-5 text-gray-700 dark:text-dark-300',
            'footer' => 'flex border-t border-t-gray-200 px-4 pt-6 dark:border-t-dark-600',
        ]);
    }

    protected function validate(): void
    {
        if (is_string($this->wire) && empty($this->wire)) {
            throw new InvalidArgumentException('The [wire] property cannot be an empty string');
        }

        $configuration = config('tallstackui.settings.slide');

        $size = $this->size ?? $configuration['size'];
        $zIndex = $this->zIndex ?? $configuration['z-index'];
        $position = $this->left ? 'left' : $configuration['position'];

        $sizes = ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl', 'full'];
        $positions = ['right', 'left'];

        if (! in_array($size, $sizes)) {
            throw new InvalidArgumentException('The slide size must be one of the following: ['.implode(', ', $sizes).']');
        }

        if (! str_starts_with($zIndex, 'z-')) {
            throw new InvalidArgumentException('The slide z-index must start with z- prefix');
        }

        if (! in_array($position, $positions)) {
            throw new InvalidArgumentException('The slide position must be one of the following: ['.implode(', ', $positions).']');
        }
    }
}
