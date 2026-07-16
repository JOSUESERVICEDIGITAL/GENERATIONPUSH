<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryResource extends Model
{
    use HasFactory;

    protected $table = 'library_resources';

    protected $fillable = [
        'title',
        'description',
        'type',
        'url',
        'downloads',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'downloads' => 'integer',
        ];
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'pdf' => 'PDF',
            'doc' => 'Document',
            'video' => 'Vidéo',
            'link' => 'Lien',
            default => $this->type,
        };
    }

    public function statusLabel(): string
    {
        return $this->status === 'published' ? 'Publié' : 'Brouillon';
    }
}
