const Header = title => {
	return `
		<div class="content-header">
			<h1>${title}</h1>
		</div>
	`
}

const Home = () => {

	return `
		${Header('Bienvenido')}

		<div class="content-body">
			<p>
				Por favor seleccione una opción del menú.
			</p>

			<div class="row">
				<div class="col-md-12">
					<div class="alert bg-info">
						<p class="m-0 d-flex">
							<ion-icon class="fs-3" name="information-circle-outline"></ion-icon>
							<strong>Configuración:</strong>
						</p>
						El proyecto esta desarrollado para ser usado mediante una ruta virtual.
						<strong>http://pruebasoc.com</strong>
					</div>
				</div>
			</div>
		</div>
	`

}

const AddItem = () => {

	const $content_items = document.getElementById('content-items')
	const $item = $content_items.querySelector('.item-ingresos')
	const $clone = $item.cloneNode(true)

	$clone.querySelector('input').value = ''
	$clone.querySelector('select').value = ''
	$clone.querySelector('.empresa').value = ''
	$clone.querySelector('.salario_bruto').value = null
	$clone.querySelector('.salario_neto').value = null
	$clone.querySelector('.fecha_inicio').value = null

	const count_items = $content_items.querySelectorAll('.item-ingresos').length
	const $count = $clone.querySelector('.count')

	$count.innerHTML = 'Ingreso #' + ( count_items + 1 )

	$content_items.appendChild($clone)

	return $clone

}

const RemoveItem = element => {

	const count_items = document.querySelectorAll('.item-ingresos').length

	if ( count_items === 1 ) {

		const $item = element.closest('.item-ingresos')
		const $inputs = $item.querySelectorAll('.form-control')

		return Array.from($inputs).map(input => input.value = '')

	}

	element.closest('.item-ingresos').remove()

	ResetCount()

}

const ResetCount = () => {

	const $items = document.getElementById('content-items').querySelectorAll('.item-ingresos')
	Array.from($items).map((item, index) => {
		const $count = item.querySelector('.count')
		$count.innerHTML = 'Ingreso #' + ( index + 1 )
	}).join('')

}

const msgError = (err) => {

	return msg({ type: 'error', message: 'Lo sentimos ocurrio un problema. Por favor intentalo más tarde.', err })

}

const ValidateEmail = email => {

	const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
	
	return re.test(String(email).toLowerCase())

}