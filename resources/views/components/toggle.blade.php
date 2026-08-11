@props(['name', 'checked' => false, 'label' => ''])

<label class="flex items-center justify-between gap-4 cursor-pointer py-2" x-data="{ on: {{ $checked ? 'true' : 'false' }} }">
    <span class="text-sm text-foreground">{{ $label }}</span>
    <span class="flex items-center gap-2">
        <span class="text-xs font-semibold" :class="on ? 'text-accent' : 'text-muted-foreground'" x-text="on ? 'Activé' : 'Désactivé'"></span>
        <span class="relative inline-flex items-center">
            <!-- Champ caché : garantit qu'une valeur (0) est TOUJOURS envoyée, même si la case n'est pas cochée.
                 Placé AVANT la case à cocher dans le DOM : si elle est cochée, sa valeur (1) écrase le 0
                 du champ caché à la soumission (comportement standard des navigateurs pour les champs de même nom). -->
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox" name="{{ $name }}" value="1" {{ $checked ? 'checked' : '' }} x-model="on" class="peer sr-only">
            <span class="w-11 h-6 bg-gray-200 peer-checked:bg-accent rounded-full transition-colors duration-200 pointer-events-none"></span>
            <span class="absolute start-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-5 rtl:peer-checked:-translate-x-5 pointer-events-none"></span>
        </span>
    </span>
</label>
