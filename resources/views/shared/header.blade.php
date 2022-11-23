<header class="header pt-6 xl:pt-12">
    <div class="container">
        <div class="header-inner flex items-center justify-between lg:justify-start">
            <div class="header-logo shrink-0">
                <a href="{{ route('home') }}" rel="home">
                    <img src="{{ Vite::image('logo.svg') }}"
                         class="w-[120px] xs:w-[148px] md:w-[201px] h-[30px] xs:h-[36px] md:h-[50px]" alt="CutCode">
                </a>
            </div><!-- /.header-logo -->
            <div class="header-menu grow hidden lg:flex items-center ml-8 mr-8 gap-8">
                <form action="{{ route('catalog') }}" class="hidden lg:flex gap-3">
                    <input name="s"
                           value="{{ request('s') }}"
                           type="search"
                           class="w-full h-12 px-4 rounded-lg border border-body/10 focus:border-pink focus:shadow-[0_0_0_3px_#EC4176] bg-white/5 text-white text-xs shadow-transparent outline-0 transition"
                           placeholder="Поиск..."
                           required
                    >
                    <button type="submit" class="shrink-0 w-12 !h-12 !px-0 btn btn-pink">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 52 52">
                            <path
                                    d="M50.339 47.364 37.963 34.492a20.927 20.927 0 0 0 4.925-13.497C42.888 9.419 33.47 0 21.893 0 10.317 0 .898 9.419.898 20.995S10.317 41.99 21.893 41.99a20.77 20.77 0 0 0 12.029-3.8l12.47 12.97c.521.542 1.222.84 1.973.84.711 0 1.386-.271 1.898-.764a2.742 2.742 0 0 0 .076-3.872ZM21.893 5.477c8.557 0 15.518 6.961 15.518 15.518s-6.96 15.518-15.518 15.518c-8.556 0-15.518-6.961-15.518-15.518S13.337 5.477 21.893 5.477Z"/>
                        </svg>
                    </button>
                </form>

                @include('shared.menu')
                
            </div><!-- /.header-menu -->
            <div class="header-actions flex items-center gap-3 md:gap-5">
                @auth
                    <div x-data="{dropdownProfile: false}" class="profile relative">
                        <button @click="dropdownProfile = ! dropdownProfile"
                                class="flex items-center text-white hover:text-pink transition">
                            <span class="sr-only">Профиль</span>
                            <img src="{{ auth()->user()->avatar }}" class="shrink-0 w-7 md:w-9 h-7 md:h-9 rounded-full"
                                 alt="{{ auth()->user()->name }}">
                            <span class="hidden md:block ml-2 font-medium">{{ auth()->user()->name }}</span>
                            <svg class="shrink-0 w-3 h-3 ml-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                 viewBox="0 0 30 16">
                                <path fill-rule="evenodd"
                                      d="M27.536.72a2 2 0 0 1-.256 2.816l-12 10a2 2 0 0 1-2.56 0l-12-10A2 2 0 1 1 3.28.464L14 9.397 24.72.464a2 2 0 0 1 2.816.256Z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div
                                x-show="dropdownProfile"
                                @click.away="dropdownProfile = false"
                                x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute z-50 top-0 -right-20 xs:-right-8 sm:right-0 w-[280px] sm:w-[300px] mt-14 p-4 rounded-lg shadow-xl bg-card"
                        >
                            <h5 class="text-body text-xs">Мой профиль</h5>
                            <div class="flex items-center mt-3">
                                <img src="{{ auth()->user()->avatar }}" class="w-11 h-11 rounded-full"
                                     alt="{{ auth()->user()->name }}">
                                <span class="ml-3 text-xs md:text-sm font-bold">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="mt-4">
                                <ul class="space-y-2">
                                    <li><a href="#" class="text-body hover:text-white text-xs font-medium">Мои
                                            заказы</a></li>
                                    <li><a href="#"
                                           class="text-body hover:text-white text-xs font-medium">Редактировать
                                            профиль</a></li>
                                </ul>
                            </div>
                            <div class="mt-6">
                                <x-profiles.logout-button>Выйти</x-profiles.logout-button>
                            </div>
                        </div>
                    </div>
                @elseguest
                    <x-profiles.login-button>Войти</x-profiles.login-button>
                @endauth
                <a href="#" class="flex items-center gap-3 text-pink hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 md:w-7 w-6 md:h-7" fill="currentColor"
                         viewBox="0 0 52 52">
                        <path
                                d="M26 0a10.4 10.4 0 0 0-10.4 10.4v1.733h-1.439a5.668 5.668 0 0 0-5.668 5.408L7.124 46.055A5.685 5.685 0 0 0 12.792 52h26.416a5.686 5.686 0 0 0 5.668-5.945l-1.37-28.514a5.668 5.668 0 0 0-5.667-5.408H36.4V10.4A10.4 10.4 0 0 0 26 0Zm-6.933 10.4a6.934 6.934 0 0 1 13.866 0v1.733H19.067V10.4Zm-2.843 8.996a1.734 1.734 0 1 1 3.468 0 1.734 1.734 0 0 1-3.468 0Zm16.085 0a1.733 1.733 0 1 1 3.467 0 1.733 1.733 0 0 1-3.467 0Z"/>
                    </svg>
                    <div class="hidden sm:flex flex-col gap-2">
                        <span class="text-body text-xxs leading-none">3 шт.</span>
                        <span class="text-white text-xxs 2xl:text-xs font-bold !leading-none">57 900 ₽</span>
                    </div>
                </a>
                <button id="burgerMenu" class="flex 2xl:hidden text-white hover:text-pink transition">
                    <span class="sr-only">Меню</span>
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div><!-- /.header-actions -->
        </div><!-- /.header-inner -->
    </div><!-- /.container -->
</header>
