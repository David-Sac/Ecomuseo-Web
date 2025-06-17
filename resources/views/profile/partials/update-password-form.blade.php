{{-- resources/views/profile/partials/update-password-form.blade.php --}}
<form method="post" action="{{ route('password.update') }}" class="space-y-6">
  @csrf
  @method('put')

  <div>
    <x-input-label for="current_password" :value="__('Contraseña actual')" />
    <x-text-input id="current_password" name="current_password" type="password"
      class="mt-1 block w-full" autocomplete="current-password" />
    <x-input-error class="mt-2"
      :messages="$errors->updatePassword->get('current_password')" />
  </div>

  <div>
    <x-input-label for="password" :value="__('Nueva contraseña')" />
    <x-text-input id="password" name="password" type="password"
      class="mt-1 block w-full" autocomplete="new-password" />
    <x-input-error class="mt-2"
      :messages="$errors->updatePassword->get('password')" />
  </div>

  <div>
    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
    <x-text-input id="password_confirmation" name="password_confirmation"
      type="password" class="mt-1 block w-full" autocomplete="new-password" />
    <x-input-error class="mt-2"
      :messages="$errors->updatePassword->get('password_confirmation')" />
  </div>

  <div class="flex items-center gap-4">
    <x-primary-button>{{ __('Guardar') }}</x-primary-button>
    @if (session('status') === 'password-updated')
      <p class="text-sm text-green-600" x-data="{show:true}"
         x-show="show" x-init="setTimeout(()=>show=false,2000)">
        {{ __('Guardado.') }}
      </p>
    @endif
  </div>
</form>
