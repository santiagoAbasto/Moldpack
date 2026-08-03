@extends('layouts.app')

@section('styles')
<style>
  :root {
    --login-dark: #070A12;
    --login-panel: #101522;
    --login-blue: #0B35B7;
    --login-blue-strong: #002FA7;
    --login-pink: #D83282;
    --login-red: #E4002B;
    --login-line: #D8DEE9;
    --login-muted: #667085;
    --login-ink: #111827;
    --login-bg: #F4F6FA;
  }

  html,
  body {
    min-height: 100%;
    background: var(--login-dark);
    color: var(--login-ink);
    font-family: "Helvetica Neue", Arial, sans-serif;
  }

  body {
    overflow-x: hidden;
  }

  #app,
  main.py-4 {
    min-height: 100vh;
  }

  main.py-4 {
    padding: 0 !important;
  }

  .login-shell {
    min-height: 100vh;
    display: grid;
    grid-template-columns: minmax(360px, 420px) minmax(420px, 620px);
    align-content: center;
    justify-content: center;
    padding: 40px;
    background:
      linear-gradient(135deg, rgba(11, 53, 183, 0.12), transparent 36%),
      linear-gradient(315deg, rgba(216, 50, 130, 0.13), transparent 36%),
      #F3F6FB;
  }

  .login-panel {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 560px;
    padding: 46px 40px;
    background:
      linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(244, 246, 250, 0.98));
    border-right: 1px solid rgba(216, 222, 233, 0.82);
    border-radius: 8px 0 0 8px;
    box-shadow: 0 24px 64px rgba(16, 24, 40, 0.14);
  }

  .login-brand {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 38px;
    color: var(--login-ink);
    font-size: 16px;
    font-weight: 900;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .login-brand-mark {
    display: block;
    width: 148px;
    max-width: 100%;
    height: auto;
    object-fit: contain;
  }

  .login-card {
    width: 100%;
    max-width: 340px;
    padding: 0;
    border: 0;
    border-radius: 0;
    background: transparent;
    box-shadow: none;
  }

  .login-title {
    margin: 0 0 8px;
    color: var(--login-ink);
    font-size: 30px;
    line-height: 1.08;
    font-weight: 900;
    letter-spacing: 0;
  }

  .login-copy {
    margin: 0 0 28px;
    color: var(--login-muted);
    font-size: 15px;
    line-height: 1.45;
  }

  .login-label {
    display: block;
    margin-bottom: 8px;
    color: #344054;
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
  }

  .login-card .form-group {
    position: relative;
  }

  .login-input-shell {
    position: relative;
  }

  .login-password-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 8px;
  }

  .login-password-head .login-label {
    margin-bottom: 0;
  }

  .login-input {
    height: 50px;
    border: 1px solid #C8D0DD;
    border-radius: 8px;
    background: #FFFFFF;
    color: var(--login-ink);
    font-size: 15px;
    font-weight: 700;
    padding-right: 14px;
    box-shadow: none;
    transition: border-color 180ms ease, box-shadow 180ms ease, background 180ms ease;
  }

  .login-input:focus {
    border-color: var(--login-blue);
    box-shadow: 0 0 0 4px rgba(11, 53, 183, 0.12);
  }

  .password-toggle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-width: 94px;
    height: 32px;
    padding: 0 10px;
    border: 1px solid #C8D0DD;
    border-radius: 6px;
    background: #FFFFFF;
    color: #344054;
    font-size: 12px;
    font-weight: 900;
    cursor: pointer;
    transition: border-color 180ms ease, color 180ms ease, background 180ms ease, box-shadow 180ms ease;
  }

  .password-toggle:hover,
  .password-toggle:focus {
    border-color: var(--login-blue);
    background: #F4F7FF;
    color: var(--login-blue);
    box-shadow: 0 0 0 3px rgba(11, 53, 183, 0.10);
    outline: none;
  }

  .password-toggle-icon {
    position: relative;
    width: 14px;
    height: 9px;
    border: 2px solid currentColor;
    border-radius: 50%;
  }

  .password-toggle-icon::after {
    content: "";
    position: absolute;
    width: 4px;
    height: 4px;
    top: 50%;
    left: 50%;
    border-radius: 50%;
    background: currentColor;
    transform: translate(-50%, -50%);
  }

  .password-toggle[aria-pressed="true"] .password-toggle-icon::before {
    content: "";
    position: absolute;
    width: 18px;
    height: 2px;
    top: 50%;
    left: 50%;
    border-radius: 999px;
    background: currentColor;
    transform: translate(-50%, -50%) rotate(-35deg);
  }

  .login-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 24px;
  }

  .login-button {
    min-width: 150px;
    height: 50px;
    border: 1px solid var(--login-blue);
    border-radius: 8px;
    background: var(--login-blue);
    color: #FFFFFF;
    font-weight: 900;
    letter-spacing: 0;
    box-shadow: 0 18px 32px rgba(11, 53, 183, 0.24);
    transition: background 180ms ease, border-color 180ms ease, transform 180ms ease, box-shadow 180ms ease;
  }

  .login-button:hover {
    background: #082A91;
    border-color: #082A91;
    color: #FFFFFF;
    transform: translateY(-1px);
    box-shadow: 0 22px 38px rgba(11, 53, 183, 0.30);
  }

  .login-aside {
    position: relative;
    min-height: 560px;
    padding: 44px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    background:
      linear-gradient(90deg, rgba(255, 255, 255, 0.055) 1px, transparent 1px),
      linear-gradient(0deg, rgba(255, 255, 255, 0.055) 1px, transparent 1px),
      linear-gradient(135deg, rgba(216, 50, 130, 0.20) 0%, transparent 38%),
      linear-gradient(315deg, rgba(11, 53, 183, 0.28) 0%, transparent 36%),
      var(--login-dark);
    background-size: 54px 54px, 54px 54px, auto, auto, auto;
    overflow: hidden;
    border-radius: 0 8px 8px 0;
    box-shadow: 0 24px 64px rgba(16, 24, 40, 0.16);
  }

  .login-aside::before {
    content: "";
    position: absolute;
    inset: 24px;
    border: 1px solid rgba(255, 255, 255, 0.10);
    border-radius: 8px;
    pointer-events: none;
  }

  .login-aside-content {
    position: relative;
    width: min(420px, 100%);
    margin-top: auto;
    margin-bottom: 28px;
  }

  .login-number {
    display: block;
    margin-bottom: 12px;
    color: #FFFFFF;
    font-size: 72px;
    line-height: 0.9;
    font-weight: 900;
    font-variant-numeric: tabular-nums;
    text-shadow: 6px 6px 0 rgba(216, 50, 130, 0.36);
  }

  .login-aside h2 {
    margin: 0;
    color: #FFFFFF;
    font-size: 28px;
    line-height: 1.12;
    font-weight: 900;
    letter-spacing: 0;
    max-width: 680px;
  }

  .login-status {
    position: relative;
    width: min(470px, 100%);
    display: grid;
    grid-template-columns: 1fr;
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
  }

  .login-status span {
    min-height: 64px;
    padding: 12px 16px;
    border-right: 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
  }

  .login-status span:last-child {
    border-bottom: 0;
  }

  .login-status small,
  .login-status strong {
    display: block;
  }

  .login-status small {
    color: #AEB8CA;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
  }

  .login-status strong {
    margin-top: 8px;
    color: #FFFFFF;
    font-size: 16px;
    font-weight: 900;
  }

  .login-error {
    border: 1px solid rgba(228, 0, 43, 0.25);
    border-radius: 8px;
    background: rgba(228, 0, 43, 0.08);
    color: #B20D30;
    font-size: 14px;
  }

  @media (max-width: 900px) {
    .login-shell {
      grid-template-columns: 1fr;
      padding: 24px;
    }

    .login-panel {
      border-right: 0;
      padding: 42px 24px;
      box-shadow: none;
      min-height: auto;
      border-radius: 8px 8px 0 0;
    }

    .login-card {
      max-width: 420px;
      padding: 28px;
      margin: 0 auto;
    }

    .login-aside {
      min-height: 260px;
      padding: 32px 24px;
      border-radius: 0 0 8px 8px;
    }

    .login-number {
      font-size: 54px;
    }

    .login-aside h2 {
      font-size: 24px;
    }

    .login-status {
      grid-template-columns: 1fr;
    }

    .login-status span {
      min-height: auto;
      border-right: 0;
      border-bottom: 1px solid #D9DEE8;
    }

    .login-status span:last-child {
      border-bottom: 0;
    }
  }
</style>
@endsection

@section('content')

<div class="login-shell">
  <section class="login-panel">
    <div class="login-card">
      <h1 class="login-brand">
        <img
          class="login-brand-mark"
          src="{{ asset('storage/logos/MwllbreCt8Pu62sEfGvKSyvip4KKgKZxqC4NEpaP.svg') }}"
          alt="Moldpack"
          onerror="this.onerror=null;this.src='{{ asset('img/logo2.jpg') }}';"
        >
      </h1>
      <h2 class="login-title">Acceso al panel</h2>
      <p class="login-copy">Ingrese con su usuario interno o correo registrado.</p>

      @if ($errors->any())
        <div class="alert login-error">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <label class="login-label" for="username">Usuario o correo</label>
          <input
            id="username"
            type="text"
            name="username"
            value="{{ old('username') }}"
            required
            autocomplete="username"
            autofocus
            class="form-control login-input @error('username') is-invalid @enderror"
          >
        </div>

        <div class="form-group">
          <div class="login-password-head">
            <label class="login-label" for="password">Contraseña</label>
            <button
              type="button"
              class="password-toggle"
              data-password-toggle
              onclick="return toggleLoginPassword(event)"
              aria-controls="password"
              aria-pressed="false"
              aria-label="Mostrar contraseña"
            >
              <span class="password-toggle-icon" aria-hidden="true"></span>
              <span data-password-toggle-label>Mostrar</span>
            </button>
          </div>
          <input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            class="form-control login-input @error('password') is-invalid @enderror"
          >
        </div>

        <div class="login-actions">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Recordarme</label>
          </div>
          <button type="submit" class="btn login-button">Ingresar</button>
        </div>
      </form>
    </div>
  </section>

  <section class="login-aside">
    <div class="login-status">
      <span>
        <small>Panel</small>
        <strong>Administracion</strong>
      </span>
      <span>
        <small>Entorno</small>
        <strong>{{ in_array(request()->getHost(), ['localhost', '127.0.0.1']) ? 'Pruebas' : 'Producción' }}</strong>
      </span>
      <span>
        <small>Fecha</small>
        <strong>{{ now()->format('d/m/Y') }}</strong>
      </span>
    </div>
    <div class="login-aside-content">
      <span class="login-number">{{ now()->format('d') }}</span>
      <h2>Panel operativo de pedidos, clientes y facturación.</h2>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script>
  function toggleLoginPassword(event) {
    if (event) {
      event.preventDefault();
      event.stopPropagation();
    }

    var button = document.querySelector('[data-password-toggle]');
    var label = document.querySelector('[data-password-toggle-label]');
    var input = document.getElementById('password');

    if (!button || !input) {
      return false;
    }

    var shouldShow = input.getAttribute('type') !== 'text';
    input.setAttribute('type', shouldShow ? 'text' : 'password');
    button.setAttribute('aria-pressed', shouldShow ? 'true' : 'false');
    button.setAttribute('aria-label', shouldShow ? 'Ocultar contraseña' : 'Mostrar contraseña');

    if (label) {
      label.textContent = shouldShow ? 'Ocultar' : 'Mostrar';
    }

    input.focus({ preventScroll: true });
    return false;
  }

  (function () {
    var button = document.querySelector('[data-password-toggle]');
    var input = document.getElementById('password');

    if (!button || !input) {
      return;
    }

    button.addEventListener('click', function (event) {
      if (event.defaultPrevented) {
        return;
      }

      toggleLoginPassword(event);
    });
  })();
</script>
@endpush
