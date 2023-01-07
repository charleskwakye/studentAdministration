{{--<nav class="container mx-auto p-4 flex justify-between">--}}
{{--    <div class="flex items-center space-x-2">--}}
{{--        <x-sta.nav_link href="{{route('home')}}" :active="request()->routeIs('home')">--}}
{{--            Home--}}
{{--        </x-sta.nav_link>--}}
{{--        <x-sta.nav_link href="{{route('courses')}}" :active="request()->routeIs('courses')">--}}
{{--            Courses--}}
{{--        </x-sta.nav_link>--}}
{{--        @auth--}}
{{--        @if(auth()->user()->admin)--}}
{{--        <x-sta.nav_link href="{{route('home')}}">--}}
{{--            Programmes--}}
{{--        </x-sta.nav_link>--}}
{{--        @endif--}}
{{--        @endauth--}}
{{--    </div>--}}

{{--    <div class="relative flex items-center space-x-2">--}}
{{--        @guest--}}
{{--            <x-jet-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">--}}
{{--                Login--}}
{{--            </x-jet-nav-link>--}}
{{--            <x-jet-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">--}}
{{--                Register--}}
{{--            </x-jet-nav-link>--}}
{{--        @endguest--}}
{{--            --}}{{-- dropdown navigation--}}
{{--            @auth--}}
{{--            <x-jet-dropdown align="right" width="48">--}}
{{--                --}}{{-- avatar --}}
{{--                <x-slot name="trigger">--}}
{{--                    <img class="rounded-full h-8 w-8 cursor-pointer"--}}
{{--                         src="https://ui-avatars.com/api/?name={{urlencode(auth()->user()->name)}}"--}}
{{--                         alt="{{auth()-> user()-> name}}">--}}
{{--                </x-slot>--}}
{{--                <x-slot name="content">--}}
{{--                    --}}{{-- all users --}}
{{--                    <div class="block px-4 py-2 text-xs text-gray-400">{{auth()->user()->name}}</div>--}}
{{--                    <x-jet-dropdown-link href="{{ route('dashboard') }}">Dashboard</x-jet-dropdown-link>--}}
{{--                    <x-jet-dropdown-link href="{{ route('profile.show') }}">Update Profile</x-jet-dropdown-link>--}}

{{--                    <form method="POST" action="{{ route('logout') }}">--}}
{{--                        @csrf--}}
{{--                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">Logout</button>--}}
{{--                    </form>--}}

{{--                </x-slot>--}}
{{--            </x-jet-dropdown>--}}
{{--            @endauth--}}
{{--    </div>--}}
{{--</nav>--}}
