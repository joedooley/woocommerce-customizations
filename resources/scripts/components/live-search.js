import Isotope from 'isotope-layout'



const filtersElem = document.querySelector('.product-cat-terms')
const buttonGroups = document.querySelectorAll('.button-group')

const isotope = new Isotope(document.querySelector('.wc-isotope-product-grid'), {
		itemSelector: '.product',
//		layoutMode: 'fitRows'
	}
)


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


filtersElem.addEventListener('click', function (event) {
	if (!matchesSelector(event.target, 'button')) {
		return
	}

	let filterValue = event.target.getAttribute('data-filter')

	filterValue = filterFns[filterValue] || filterValue

	isotope.arrange({ filter: filterValue })
})



let i = 0
let len = buttonGroups.length

// change is-checked class on buttons
for ( ; i < len; i++) {
	const buttonGroup = buttonGroups[i]

	radioButtonGroup(buttonGroup)
}


