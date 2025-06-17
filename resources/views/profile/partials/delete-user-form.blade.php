{{-- resources/views/profile/partials/delete-user-form.blade.php --}}
<x-danger-button
    class="btn btn-danger"
    x-data=""
    x-on:click.prevent="$dispatch('open-modal','confirm-user-deletion')"
>
  {{ __('Eliminar Cuenta') }}
</x-danger-button>

<x-modal name="confirm-user-deletion"
         :show="$errors->userDeletion->isNotEmpty()" focusable>
  <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
    @csrf
    @method('delete')

    <p class="text-gray-800">
      {{ __("Una vez eliminada su cuenta, todos sus recursos y datos se eliminarán permanentemente. Ingrese su contraseña para confirmar.") }}
    </p>

    <div>
      <x-input-label for="password" class="sr-only" :value="__('Contraseña')" />
      <x-text-input id="password" name="password" type="password"
        class="mt-1 block w-full" placeholder="{{ __('Contraseña') }}" />
      <x-input-error class="mt-2"
        :messages="$errors->userDeletion->get('password')" />
    </div>

    <div class="flex justify-end gap-2">
      <x-secondary-button x-on:click="$dispatch('close')">
        {{ __('Cancelar') }}
      </x-secondary-button>
      <x-danger-button class="btn btn-danger">
        {{ __('Eliminar Cuenta') }}
      </x-danger-button>
    </div>
  </form>
</x-modal>
