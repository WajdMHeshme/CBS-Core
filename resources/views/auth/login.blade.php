<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CBS - Login</title>
<link rel="icon" type="image/png" href="{{ asset('/image.png') }}">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
html, body {
  height: 100%;
  font-family: 'Poppins', sans-serif;
  margin: 0;
  background: #f8fafc;
}

.cloned-image {
  position: fixed;
  z-index: 1000;
  object-fit: cover;
  pointer-events: none;
  transition: transform .6s cubic-bezier(.2,.9,.3,1), opacity .35s ease;
  filter: brightness(0.6) drop-shadow(0 0 2px rgba(0,0,0,0.25));
}

.spinner {
  position: relative;
  z-index: 20;
  width: 56px;
  height: 56px;
  border: 6px solid rgba(255, 255, 255, 0.25);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* subtle card backdrop blur for the form */
.form-card {
  background: linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.8));
  backdrop-filter: blur(6px);
  box-shadow: 0 10px 30px rgba(15,23,42,0.06);
}

/* password toggle button inside input */
.pwd-toggle {
  -webkit-tap-highlight-color: transparent;
}
</style>
</head>

<body class="bg-gray-50">

<div class="min-h-screen grid grid-cols-1 md:grid-cols-2">

  <!-- FORM -->
  <div class="flex items-center justify-center px-6 md:order-2">
    <div class="w-full max-w-md">

      <div class="flex flex-col items-center justify-center gap-3 mb-8">

                    {{-- Brand Name --}}
                    <h1 class="text-5xl font-extrabold tracking-tight text-gray-900">
                        Luxe<span class="text-black">Drive</span>
                    </h1>
        <p class="text-gray-500 text-sm mt-1">Manage cars, bookings & more</p>
      </div>

      <div class="form-card rounded-2xl p-6 md:p-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Welcome back</h3>
        <p class="text-xs text-gray-500 mb-6">Sign in to continue to your dashboard</p>

        <form id="loginForm" method="POST" action="{{ route('login') }}">
          @csrf

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input name="email" required
              class="mt-1 w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-300 shadow-sm"
              placeholder="you@example.com" autocomplete="username">
          </div>

          <div class="mb-2 relative">
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>

            <div class="relative">
              <input id="passwordInput" type="password" name="password" required
                class="mt-1 w-full border border-gray-200 rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-300 shadow-sm"
                placeholder="Enter your password" autocomplete="current-password" aria-describedby="pwd-toggle-desc">

              <!-- eye toggle -->
              <button type="button" id="pwdToggle" aria-label="Toggle password visibility" title="Show password"
                      class="pwd-toggle absolute inset-y-0 right-2 flex items-center px-2 text-gray-500 hover:text-black focus:outline-none">
                <!-- eye icon (closed) -->
                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>

                <!-- eye icon (closed) shown by default -->
                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.223-3.558M3 3l18 18"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.88 9.88A3 3 0 0014.12 14.12"/>
                </svg>
              </button>
            </div>

            <p id="pwd-toggle-desc" class="sr-only">Toggle password visibility</p>
          </div>

          <div class="flex items-center justify-between mt-4">
            <a href="{{ route('password.request') }}" class="text-sm text-black hover:underline">Forgot your password?</a>
          </div>

          <button id="loginBtn"
            class="w-full mt-6  bg-black  text-white py-3 rounded-xl font-semibold shadow-md focus:outline-none focus:ring-4 focus:ring-indigo-200">
            Login
          </button>
        </form>
      </div>

      <p class="text-center text-xs text-gray-400 mt-4">© {{ date('Y') }} CBS </p>
    </div>
  </div>

  <!-- IMAGE -->
  <div id="imageCol" class="relative hidden md:block overflow-hidden">
    <img id="sideImg" src="{{ asset('hostimage (1).webp') }}" class="absolute inset-0 w-full h-full object-cover brightness-60">
    <div class="absolute inset-0 flex items-center justify-center text-white text-4xl md:text-5xl font-extrabold z-10 px-6 text-center">
      Login To Manage Your Cars
    </div>
    <div class="absolute inset-0 bg-black/20 backdrop-blur-sm z-0"></div>
  </div>

</div>


</body>
</html>
