<?php

namespace App\Http\Middleware;

use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;

class SetFilamentColor
{
    public static function userColor(): array
    {
        $colors = [
            'primary' => Color::Slate,
            'secondary' => Color::Pink,
        ];
        if ($user = Auth::user()) {
            dump(123);
            if ($colorSelected = $user->color_primary) {
                $color = self::findConstantByValue($colorSelected);
                dump($color);
                if ($color) {
                    dump($colorSelected, $colors['primary']);
                    $colors['primary'] = $color;
                }
            }
            if ($colorSelected = $user->color_seconday) {
                $color = self::findConstantByValue($colorSelected);
                if ($color) {
                    $colors['secondary'] = $color;
                }
            }
        }

        return $colors;
    }

    public static function colors(): array
    {
        return [
            '' => 'Défaut',
            'slate' => 'Ardoise',
            'gray' => 'Gris',
            'zinc' => 'Zinc',
            'neutral' => 'Neutre',
            'stone' => 'Pierre',
            'red' => 'Rouge',
            'orange' => 'Orange',
            'amber' => 'Ambre',
            'yellow' => 'Jaune',
            'lime' => 'Citron vert',
            'green' => 'Vert',
            'emerald' => 'Emeraude',
            'teal' => 'Sarcelle',
            'cyan' => 'Cyan',
            'sky' => 'Ciel',
            'blue' => 'Bleu',
            'indigo' => 'Indigo',
            'violet' => 'Violet',
            'purple' => 'Pourpre',
            'fuchsia' => 'Fuchsia',
            'pink' => 'Rose',
            'rose' => 'Rose',
        ];
    }

    public static function findConstantByValue(string $value): ?array
    {
        $reflection = new \ReflectionClass(Color::class);
        foreach ($reflection->getConstants() as $name => $colors) {
            if ($name === ucfirst($value)) {
                return $colors;
            }
        }

        return null;
    }
}
