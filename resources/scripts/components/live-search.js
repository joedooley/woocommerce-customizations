import Isotope from 'isotope-layout'



const filterFns = {
	matchesClass (itemElem) {
		return itemElem
	}
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



const setup = () => {
	const filtersElem = document.querySelector('.product-cat-terms')
	const el = document.querySelector('.wc-isotope-product-grid')

	if (!filtersElem || !el) {
		return
	}

	const isotope = new Isotope(el, { itemSelector: '.product' })

	filtersElem.addEventListener('click', function (event) {
		if (!matchesSelector(event.target, 'button')) {
			return
		}

		let filterValue = event.target.getAttribute('data-filter')

		filterValue = filterFns[filterValue] || filterValue

		isotope.arrange({ filter: filterValue })
	})
}



export const initIsotope = () => {
	setup()
	toggleIsCheckedClass()
}
