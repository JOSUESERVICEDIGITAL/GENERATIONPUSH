@php $user = $user ?? null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" value="Nom complet" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user?->email)" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password" :value="$user ? 'Nouveau mot de passe (optionnel)' : 'Mot de passe'" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" :required="! $user" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Confirmation du mot de passe" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" :required="! $user" />
    </div>

    <div>
        <x-input-label for="phone" value="Téléphone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user?->phone)" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="role" value="Rôle" />
        <select id="role" name="role" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
            @foreach (['Member', 'Leader', 'Coach', 'Speaker', 'Trainer', 'Admin'] as $role)
                <option value="{{ $role }}" @selected(old('role', $user?->role) === $role)>{{ $role }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="country" value="Pays" />
        <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $user?->country)" />
        <x-input-error :messages="$errors->get('country')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="city" value="Ville" />
        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $user?->city)" />
        <x-input-error :messages="$errors->get('city')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Statut" />
        <select id="status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
            @foreach (['active' => 'Actif', 'inactive' => 'Inactif', 'suspended' => 'Suspendu'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $user?->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
</div>
