<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen font-sans">

    <div class="w-full max-w-md">
        <div class="bg-white shadow-xl rounded-lg px-8 pt-10 pb-8 mb-4 border border-gray-100">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Booking System</h1>
                <p class="text-gray-500 text-sm mt-2">Welcome back! Please enter your details.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email Address
                    </label>
                    <input class="appearance-none border @error('email') border-red-500 @else border-gray-300 @enderror rounded-md w-full py-2.5 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                           id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                    
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="appearance-none border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md w-full py-2.5 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors" 
                           id="password" type="password" name="password" required placeholder="••••••••">
                    
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-8">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800 transition-colors" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <div>
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out shadow-sm" type="submit">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
        
        <p class="text-center text-gray-400 text-xs font-medium">
            &copy; {{ date('Y') }} Booking System. All rights reserved.
        </p>
    </div>

</body>
</html>