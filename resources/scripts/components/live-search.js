import debounce from 'lodash-es/debounce'
import Isotope from 'isotope-layout'



let qsRegex
let isotope


const filterFns = {
	matchesClass (itemElem) {
		return itemElem
	}
}


let initSearch = function () {
	const quicksearch = document.querySelector('#wc-isotope-search')

	if (!quicksearch) {
		return
	}

	quicksearch.addEventListener('keyup', debounce(() => {
		qsRegex = new RegExp(quicksearch.value, 'gi')

		isotope.arrange({
			filter: function (arg1, el) {
				const title = el.querySelector('.woocommerce-loop-product__title')

				return qsRegex ? title.innerText.match(qsRegex) : true
			}
		})
	}, 200))
}


function radioButtonGroup (buttonGroup) {
	buttonGroup.addEventListener('click', function (event) {
		if (!matchesSelector(event.target, 'button')) {
			return
		}

		buttonGroup.querySelector('.is-checked').classList.remove('is-checked')

		event.target.classList.add('is-checked')
	})
}


const toggleIsCheckedClass = () => {
	const buttonGroups = document.querySelectorAll('.button-group')

	if (!buttonGroups || !buttonGroups.length) {
		return
	}

	buttonGroups.forEach(group => {
		radioButtonGroup(group)
	})
}


const toggleIsHiddenClassForFilters = () => {
	const toggle = document.querySelector('#filter-toggle')

	if (!toggle) {
		return
	}

	toggle.addEventListener('click', function (event) {
		const icon = toggle.querySelector('#arrow-icon')
		const target = document.querySelector('.wc-isotope-filters')

		event.preventDefault()

		this.__toggle = !this.__toggle

		if (!target) {
			return
		}

		if (this.__toggle) {
			target.style.height = `${target.scrollHeight}px`
			this.classList.add('active')
			icon.classList.remove('fa-caret-down')
			icon.classList.add('fa-caret-up')
		} else {
			target.style.height = 0
			this.classList.remove('active')
			icon.classList.remove('fa-caret-up')
			icon.classList.add('fa-caret-down')
		}
	})
}


const setup = () => {
	const filtersElem = document.querySelector('.product-cat-terms')
	const el = document.querySelector('ul.products')

	if (!filtersElem || !el) {
		return
	}

	isotope = new Isotope(el, { itemSelector: '.product' })

	filtersElem.addEventListener('click', function (event) {
		if (!matchesSelector(event.target, 'button')) {
			return
		}

		let filterValue = event.target.getAttribute('data-filter')

		isotope.arrange({ filter: filterValue })
	})
}



export const initIsotope = () => {
	setup()
	initSearch()
	toggleIsCheckedClass()
	toggleIsHiddenClassForFilters()
}
