{{-- resources/views/partials/header_new.blade.php --}}
<header>
  <div class="header-container">
    <nav class="main-navigation">
      <img src="{{ asset('images/logo_vectorizado.png') }}" alt="Logo" class="logo-svg">
      <div class="nav-links">
        <a href="/" class="nav-item">Inicio</a>
        <a href="/tour" class="nav-item">Visitas</a>
        <a href="/blog" class="nav-item">Blogs</a>
        <a href="/donations" class="nav-item">Donar</a>
        <a href="/volunteers" class="nav-item">Voluntariado</a>
      </div>
      <div class="user-actions" id="auth-buttons">
        @auth
          {{-- enlaces intranet / logout --}}
          <a href="{{ route('profile.edit') }}" class="profile-btn">Perfil</a>
          <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="logout-btn">Cerrar sesión</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="login-btn">Iniciar sesión</a>
          @if(Route::has('register'))
            <a href="{{ route('register') }}" class="register-btn">Registrarse</a>
          @endif
        @endauth
      </div>
    </nav>
  </div>
</header>
