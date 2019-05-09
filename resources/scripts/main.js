import './components/wp-fly-menu'
import './components/live-search'
import { initIsotope } from './components/live-search'
import { initFlickity } from './components/product-cat-slider-flickity'



const initSliders = () => {
	const ids = []
	const sliders = document.querySelectorAll('.product-category-slider-flickity [id^="flickity-slider-"]')

	if (sliders.length) {
		sliders.forEach(slider => {
			const id = slider.getAttribute('id')
			ids.push(`#${id}`)
		})
	}

	initFlickity(ids)
}


const init = () => {
	initSliders()
	initIsotope()
}


window.addEventListener(
	'load',
	() => init()
)
