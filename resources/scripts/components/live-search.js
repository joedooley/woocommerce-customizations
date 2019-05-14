import debounce from 'lodash-es/debounce'
import Isotope from 'isotope-layout'



let qsRegex
let isotope


function getAbsoluteHeight (el) {
	el = (typeof el === 'string') ? document.querySelector(el) : el

	const styles = window.getComputedStyle(el)
	const margin = parseFloat(styles['marginTop']) + parseFloat(styles['marginBottom'])
	const padding = parseFloat(styles['paddingTop']) + parseFloat(styles['paddingBottom'])
	const height = Math.ceil(el.offsetHeight + margin + padding)

	return `${height}px`
}


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
		if (!matchesSelector(event.target, 'a')) {
			return
		}

		event.preventDefault()

		buttonGroup.querySelector('.is-checked').classList.remove('is-checked')

		event.target.classList.add('is-checked')
	})
}


const toggleIsCheckedClass = () => {
	const filterLinkGroups = document.querySelectorAll('.filter-link-group')

	if (!filterLinkGroups || !filterLinkGroups.length) {
		return
	}

	filterLinkGroups.forEach(group => {
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
//			target.style.height = getAbsoluteHeight(target)
			target.classList.remove('is-hidden')
			this.classList.add('active')
			icon.classList.remove('fa-caret-down')
			icon.classList.add('fa-caret-up')
		} else {
//			target.style.height = 0
			target.classList.add('is-hidden')
			this.classList.remove('active')
			icon.classList.remove('fa-caret-up')
			icon.classList.add('fa-caret-down')
		}
	})
}


const setup = () => {
	const filterLinkGroups = document.querySelectorAll('.filter-link-group')
	const el = document.querySelector('ul.products')

	if (!filterLinkGroups || !filterLinkGroups.length || !el) {
		return
	}

	isotope = new Isotope(el, { itemSelector: '.product' })

	filterLinkGroups.forEach(group => {
		group.addEventListener('click', function (event) {
			console.log(event)
			console.log(event.target)

			if (!matchesSelector(event.target, 'a')) {
				return
			}

			let filterValue = event.target.getAttribute('data-filter')

			isotope.arrange({ filter: filterValue })
		})
	})
}



export const initIsotope = () => {
	setup()
	initSearch()
	toggleIsCheckedClass()
	toggleIsHiddenClassForFilters()
}
