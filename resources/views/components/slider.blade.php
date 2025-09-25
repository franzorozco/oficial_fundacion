<div x-data="{ activeSlide: 0, slides: ['slide1.jpg', 'slide2.jpg', 'slide3.jpg'] }" class="mi-slider relative w-full h-screen overflow-hidden">
    <!-- Slides -->
        <template x-for="(slide, index) in slides" :key="index">
            <div
                x-show="activeSlide === index"
                x-transition:enter="transition-opacity duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                class="slide bg-center bg-cover flex items-center justify-center"
                :style="'background-image: url(/storage/slider/' + slide + ')'"
            >
                <!-- Text/Content Over Image -->
                <div class="content">
                    <h2 class="text-4xl font-extrabold text-shadow mb-6">¡Bienvenidos a la Fundación UNIFRANZ!</h2>
                    <p class="text-lg mb-6 text-shadow">Aquí transformamos vidas con tu ayuda.</p>
                    <a href="{{ route('info-activity.donaciones') }}" class="btn-primary">Haz tu donación</a>

                </div>
            </div>
        </template>

        <!-- Navigation Arrows -->
        <button @click="activeSlide = (activeSlide - 1 + slides.length) % slides.length" class="navigation-btn left">
            ‹
        </button>
        <button @click="activeSlide = (activeSlide + 1) % slides.length" class="navigation-btn right">
            ›
        </button>

        <!-- Dots -->
        <div class="dots">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="activeSlide = index" :class="{'active': activeSlide === index}" class="w-4 h-4 rounded-full bg-white opacity-70 hover:opacity-100 transition-all"></button>
            </template>
        </div>
    </div>