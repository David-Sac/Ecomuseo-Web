{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<form id="send-verification" method="post" action="{{ route('verification.send') }}">
  @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
  @csrf
  @method('patch')

  <div>
    <x-input-label for="name" :value="__('Nombre')" />
    <x-text-input id="name" name="name" type="text"
      class="mt-1 block w-full"
      :value="old('name', $user->name)"
      required autofocus autocomplete="name" />
    <x-input-error class="mt-2" :messages="$errors->get('name')" />
  </div>

  <div>
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input id="email" name="email" type="email"
      class="mt-1 block w-full"
      :value="old('email', $user->email)"
      required autocomplete="username" />
    <x-input-error class="mt-2" :messages="$errors->get('email')" />

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
      <p class="text-sm mt-2 text-red-700">
        {{ __('Su dirección de correo electrónico no está verificada.') }}
        <button form="send-verification"
          class="underline text-sm hover:text-gray-900">
          {{ __('Haga clic aquí para volver a enviar el correo electrónico de verificación.') }}
        </button>
      </p>
      @if (session('status') === 'verification-link-sent')
        <p class="mt-2 font-medium text-sm text-green-600">
          {{ __('Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.') }}
        </p>
      @endif
    @endif
<div class="mb-3">
  <label for="phone" class="form-label">Teléfono</label>
  <input
    id="phone"
    name="phone"
    type="tel"
    class="form-control @error('phone') is-invalid @enderror"
    value="{{ old('phone', $user->phone) }}"
    autocomplete="tel"
    inputmode="numeric"
    pattern="[0-9]*"
    title="Sólo números"
    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
  >
  @error('phone') 
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>
  <div class="flex items-center gap-4">
    <x-primary-button>{{ __('Guardar') }}</x-primary-button>
    @if (session('status') === 'profile-updated')
      <p class="text-sm text-green-600" x-data="{show:true}"
         x-show="show" x-init="setTimeout(()=>show=false,2000)">
        {{ __('Saved.') }}
      </p>
    @endif
  </div>
</form>
