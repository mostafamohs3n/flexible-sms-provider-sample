<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env('APP_NAME')}}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

</head>
<body class="antialiased">
<div
    class="relative sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-red-500 selection:text-white">
    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
                <a href="{{ url('/home') }}"
                   class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
            @else
                <a href="{{ route('login') }}"
                   class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                    in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endif
            @endauth
        </div>
    @endif
    <div class="container mx-auto px-12">
        <div class="flex justify-center">
            <img src="https://sllm.sa/sllm.png" width="180px" alt="sllm.sa"/>
        </div>
        <div class="mt-4 w-full">
            <div
                class="w-full p-6 bg-white from-gray-700/50 via-transparent rounded-lg shadow-2xl shadow-gray-500/20 motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                <div>

                    <h2 class="mt-6 text-xl font-semibold text-gray-900">Send SMS</h2>
                    @if(session()->has('error'))
                        <div class="mt-4 bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4"
                             role="alert">
                            <p class="font-bold">Error</p>
                            <p>{{session()->get('error')}}</p>
                        </div>
                    @endif
                    @if(session()->has('success'))
                        <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4"
                             role="alert">
                            <p class="font-bold">Success</p>
                            <p>{{session()->get('success')}}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{route('sms.store')}}">
                        @csrf
                        <div class="w-full">
                            <div class="mt-4">
                                <label for="phoneNumber"
                                       class="block mb-2 text-sm font-medium text-gray-900">
                                    Phone Number
                                </label>
                                <input name="phoneNumber" type="text" id="phoneNumber"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
                                       placeholder="966000000" required
                                />
                            </div>
                            <div class="mt-4">
                                <label for="message"
                                       class="block mb-2 text-sm font-medium text-gray-900">Your
                                    message</label>
                                <textarea name="message" id="message" rows="4"
                                          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                          placeholder="Write your thoughts here..."></textarea>
                            </div>
                            <div class="mt-4">
                                <button type="submit"
                                        class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
            <div class="text-center text-sm text-gray-500 sm:text-left">
                <div class="flex items-center gap-4">
                    <a href="https://sllm.sa"
                       class="group inline-flex items-center hover:text-gray-700 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        <img src="https://sllm.sa/sllm.png" width="80" alt="sllm.sa"/>
                        sllm.sa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
