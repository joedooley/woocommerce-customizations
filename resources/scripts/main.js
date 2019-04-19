import { initSwiperSliders } from './components/product-cat-slider-swiper'
import { initFlickity } from './components/product-cat-slider-flickity'



const initSliders = () => {
	initSwiperSliders(document.querySelectorAll('.product-slider-container'))
	initFlickity(document.querySelectorAll('.flickity-slider'))
}


window.addEventListener(
	'load',
	() => initSliders()
)
