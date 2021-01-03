<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tech in Asia - Connecting Asia's startup ecosystem</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen dark:bg-gray-900 sm:items-center sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/home') }}" class="text-sm text-gray-700 underline">Home</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                    <img alt="Tech in Asia" src="data:image/png;base64,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"/>
                </div>

                <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                    <div class="text-center text-sm text-gray-500 sm:text-left">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="ml-4 -mt-px w-5 h-5 text-gray-400">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>

                            <a href="https://www.techinasia.com/" class="ml-1 underline">
                                Home
                            </a>
                        </div>
                    </div>

                    <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                        &copy; Tech in Asia
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
