import { initSwiperSliders } from './components/product-cat-slider-swiper'


const initSliders = () => initSwiperSliders(document.querySelectorAll('.product-slider-container'))


window.addEventListener(
	'load',
	() => initSliders()
)
