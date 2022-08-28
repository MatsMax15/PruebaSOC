// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::::::: TABLE :::::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

// :: Cargar Pedidos :: //
const Pedidos = async () => {

	const data = await GetPedidos()
	if (!data) return

	const { total, results } = data
	const $results = document.getElementById('results')
	$results.innerHTML = `Mostrando ${total} ${total === 1 ? 'resultado' : 'resultados'}`
	const $tbody = document.getElementById('tbody')

	if (total === 0) {

		$results.innerHTML = 'No hay resultados'
		$tbody.innerHTML = `
			<tr>
				<td class="text-center" colspan="6">No hay resultados</td>
			</tr>
		`
		return

	}

	const content = await Promise.all(
		results.map(async x => {
			const data_solicitante = await GetSolicitante(x.id_solicitante)

			return `
				<tr>
					<td>${x.folio}</td>
					<td>${data_solicitante.nombre} ${data_solicitante.apellido_paterno} ${data_solicitante.apellido_materno}</td>
					<td class="d-none d-md-table-cell">
						${x.destino === 'casa' ? 'Casa' :
					x.destino === 'auto' ? 'Auto' :
						x.destino === 'prestamo' ? 'Préstamo' :
							x.destino === 'tarjeta' ? 'Targeta de Crédito' : ''
				}
					</td>
					<td class="d-none d-md-table-cell">${x.fecha}</td>
					<td class="text-center">
						<div class="btn-group" role="group" aria-label="Basic example">
							<button class="btn btn-primary btn-small" onclick="SelectUpdate(${x.id})">
								<ion-icon name="create-outline"></ion-icon>
							</button>
							<button class="btn btn-danger btn-small" onclick="MSG_DeletePedido(${x.id})">
								<ion-icon name="trash-outline"></ion-icon>
							</button>
						</div>
					</td>
				</tr>
			`
		})
	)

	$tbody.innerHTML = content.join('')
}

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::::::: FORMS :::::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

// :: Cargar Contenido en Formulario :: //
const Pedidos_UploadContent = async () => {

	const data_solicitantes = await GetSolicitantes()
	if (!data_solicitantes) return

	const { total, results } = data_solicitantes

	const $id_solicitante = document.getElementById('id_solicitante')
	$id_solicitante.innerHTML = '<option value="">Seleccione un Solicitante</option>' + results.map(x => {
		return `<option value="${x.id}">${x.nombre} ${x.apellido_paterno} ${x.apellido_materno}</option>`
	}).join('')

	const $fecha = document.getElementById('fecha')

	const date = new Date()
	const day = date.getDate()
	const month = date.getMonth() + 1
	const year = date.getFullYear()
	const fecha = `${year}-${month < 10 ? '0' + month : month}-${day < 10 ? '0' + day : day}`
	$fecha.value = fecha

}

const UploadPedido = async () => {

	const data_pedido = await GetPedido(localStorage.getItem('id'))
	if (!data_pedido) return

	// Cargar Contenido en Formulario
	for (const key in data_pedido) {
		if (key !== 'id' && key !== 'folio') {

			const $input = document.getElementById(key)
			$input.value = data_pedido[key]

		}

		if (key === 'folio') {
			const $folio = document.getElementById(key)
			$folio.innerHTML = data_pedido[key]
		}
	}

	const { table } = await TableSolicitante()
	const $details_solicitante = document.getElementById('details-solicitante')
	$details_solicitante.innerHTML = table

}

// :: Obtener datos del Solicitante :: //
const SelectSolicitante = async () => {

	const $msg_denied = document.getElementById('msg-denied')
	const $details_solicitante = document.getElementById('details-solicitante')
	const $id_solicitante = document.getElementById('id_solicitante')
	const $inputs_disabled = document.querySelectorAll('.disabled')
	const $newPedido = document.getElementById('newPedido')

	$msg_denied.classList.add('d-none')

	if (!$id_solicitante.value) {

		$newPedido.disabled = true
		Array.from($inputs_disabled).map(x => x.disabled = true)

		return $details_solicitante.innerHTML = '<p class="text-center m-0">Seleccione un Solicitante</p>'
	}

	const { table, total_salario_neto } = await TableSolicitante()
	$details_solicitante.innerHTML = table

	if (total_salario_neto < 20000) {
		$msg_denied.classList.remove('d-none')
		return msg({ type: 'warning', message: 'Se requiere un salario neto superior a $20,000.00 para realizar un pedido' })
	}

	Array.from($inputs_disabled).map(x => x.disabled = false)
	$newPedido.disabled = false

}

// :: Tabla detalles del Soliciante :: //
const TableSolicitante = async () => {

	const $id_solicitante = document.getElementById('id_solicitante')
	const data_solicitante = await GetSolicitante($id_solicitante.value)
	const data_ingresos = await GetIngresos($id_solicitante.value)

	const { results } = data_ingresos

	// Total salario bruto
	const total_salario_bruto = results.reduce((total, x) => total + parseFloat(x.salario_bruto), 0)
	// Total salario neto
	const total_salario_neto = results.reduce((total, x) => total + parseFloat(x.salario_neto), 0)

	const table = `
		<table class="table">
			<tbody>
				<tr>
					<th>CURP</th>
					<td class="text-center">${data_solicitante.curp}</td>
				</tr>
				<tr>
					<th>Correo</th>
					<td class="text-center">${data_solicitante.email}</td>
				</tr>
				<tr>
					<th>Salario Bruto</th>
					<td class="text-center">$ ${total_salario_bruto.toFixed(2)}</td>
				</tr>
				<tr>
					<th>Salario Neto</th>
					<td class="text-center align-middle">
						$ ${total_salario_neto.toFixed(2)} 
						${total_salario_neto > 20000 ? '<ion-icon class="text-success fs-5" name="checkmark-circle-outline"></ion-icon>' : '<ion-icon class="text-danger fs-5" name="close-circle-outline"></ion-icon>'}
					</td>
				</tr>
			</tbody>
		</table>
	`

	return { table, total_salario_neto }

}

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::::: FUNCTIONS :::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

const NewPedido = async e => {

	e.preventDefault()

	// :: Validar campos obligatorios :: //
	const $inputs = document.querySelectorAll('.form-control')
	for (const input of $inputs) {

		input.classList.remove('is-invalid')
		const required = input.getAttribute('required')

		if (required !== null && !input.value) {

			msg({ type: 'warning', message: 'Debes completar los campos obligatorios' })
			input.classList.add('is-invalid')
			return input.focus()

		}

	}

	const $id_solicitante = document.getElementById('id_solicitante')
	const data_ingresos = await GetIngresos($id_solicitante.value)
	const { results } = data_ingresos
	const total_salario_neto = results.reduce((total, x) => total + parseFloat(x.salario_neto), 0)

	if (total_salario_neto < 20000) {
		return msg({ type: 'warning', message: 'Se requiere un salario neto superior a $20,000.00 para realizar un pedido' })
	}

	const $destino = document.getElementById('destino')
	const $monto = document.getElementById('monto')
	const monto = parseFloat($monto.value)

	if ($destino.value === 'casa') {
		if (monto > 200000) {
			return msg({ type: 'warning', message: 'El monto máximo para la casa es $200,000.00' })
		}

		if (total_salario_neto < 50000) {
			return msg({ type: 'warning', message: 'El salario neto mínimo para la casa es de $50,000.00' })
		}
	}

	if ($destino.value === 'auto') {
		if (monto > 100000) {
			return msg({ type: 'warning', message: 'El monto máximo para el auto es de $100,000.00' })
		}

		if (total_salario_neto < 30000) {
			return msg({ type: 'warning', message: 'El salario neto mínimo para el auto es de $30,000.00' })
		}
	}

	if ($destino.value === 'prestamo') {
		if (monto > 50000) {
			return msg({ type: 'warning', message: 'El monto máximo para el préstamo es de $50,000.00' })
		}

		if (total_salario_neto < 20000) {
			return msg({ type: 'warning', message: 'El salario neto mínimo para el préstamo es de $20,000.00' })
		}
	}

	if ($destino.value === 'tarjeta') {
		if (monto > 200000) {
			return msg({ type: 'warning', message: 'El monto máximo para la tarjeta de crédito es de $20,000.00' })
		}

		if (total_salario_neto < 20000) {
			return msg({ type: 'warning', message: 'El salario neto mínimo para la tarjeta de crédito es de $20,000.00' })
		}
	}

	const formData = new FormData()
	formData.append('id_solicitante', $id_solicitante.value)
	formData.append('fecha', document.getElementById('fecha').value)
	formData.append('destino', $destino.value)
	formData.append('monto', monto)
	formData.append('plazo', document.getElementById('plazo').value)

	BeforeSend()

	// const URL = 'http://pruebasoc.com/pedidos/createPedido'
	const URL = 'https://lamf-service.com/PruebaSOC/Api/index.php/pedidos/createPedido'

	const response = await fetch(URL, {
		method: 'POST',
		body: formData
	})

	const { status, message, data } = await response.json()

	if (status !== 200) return msg({ type: 'error', message })

	msg({ type: 'success', message })

	setTimeout(() => {
		const buttons = document.querySelectorAll('.menu button')
		Array.from(buttons).map(button => {
			const target = button.getAttribute('data-target')
			target === 'pedidos' ? button.classList.add('active') : button.classList.remove('active')
		})

		$('#content').load('./pages/Pedidos/Pedidos.html')
	}, 2000)

}

const SelectUpdate = id => {

	localStorage.setItem('id', id)
	$('#content').load('./pages/Pedidos/forms/ModificarPedido.html')

}

const UpdatePedido = async e => {

	e.preventDefault()

	// :: Validar campos obligatorios :: //
	const $inputs = document.querySelectorAll('.form-control')
	for (const input of $inputs) {

		input.classList.remove('is-invalid')
		const required = input.getAttribute('required')

		if (required !== null && !input.value) {

			msg({ type: 'warning', message: 'Debes completar los campos obligatorios' })
			input.classList.add('is-invalid')
			return input.focus()

		}

	}

	const $id_solicitante = document.getElementById('id_solicitante')
	const data_ingresos = await GetIngresos($id_solicitante.value)
	const { results } = data_ingresos
	const total_salario_neto = results.reduce((total, x) => total + parseFloat(x.salario_neto), 0)

	if (total_salario_neto < 20000) {
		return msg({ type: 'warning', message: 'Se requiere un salario neto superior a $20,000.00 para realizar un pedido' })
	}

	const $destino = document.getElementById('destino')
	const $monto = document.getElementById('monto')
	const monto = parseFloat($monto.value)

	if ($destino.value === 'casa') {
		if (monto > 200000) {
			return msg({ type: 'warning', message: 'El monto máximo para la casa es $200,000.00' })
		}

		if (total_salario_neto < 50000) {
			return msg({ type: 'warning', message: 'El salario neto mínimo para la casa es de $50,000.00' })
		}
	}

	if ($destino.value === 'auto') {
		if (monto > 100000) {
			return msg({ type: 'warning', message: 'El monto máximo para el auto es de $100,000.00' })
		}

		if (total_salario_neto < 30000) {
			return msg({ type: 'warning', message: 'El salario neto mínimo para el auto es de $30,000.00' })
		}
	}

	if ($destino.value === 'prestamo') {
		if (monto > 50000) {
			return msg({ type: 'warning', message: 'El monto máximo para el préstamo es de $50,000.00' })
		}

		if (total_salario_neto < 20000) {
			return msg({ type: 'warning', message: 'El salario neto mínimo para el préstamo es de $20,000.00' })
		}
	}

	if ($destino.value === 'tarjeta') {
		if (monto > 200000) {
			return msg({ type: 'warning', message: 'El monto máximo para la tarjeta de crédito es de $20,000.00' })
		}

		if (total_salario_neto < 20000) {
			return msg({ type: 'warning', message: 'El salario neto mínimo para la tarjeta de crédito es de $20,000.00' })
		}
	}

	const data_update = new URLSearchParams()
	data_update.append('id', localStorage.getItem('id'))
	data_update.append('fecha', document.getElementById('fecha').value)
	data_update.append('destino', $destino.value)
	data_update.append('monto', monto)
	data_update.append('plazo', document.getElementById('plazo').value)

	BeforeSend()

	// const URL = 'http://pruebasoc.com/pedidos/updatePedido?column=id&value=' + localStorage.getItem('id')
	const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/pedidos/updatePedido?column=id&value=${localStorage.getItem('id')}`

	const response = await fetch(URL, {
		method: 'PUT',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
		},
		body: data_update
	})

	const { status, message, data } = await response.json()

	if (status !== 200) return msg({ type: 'error', message })

	msg({ type: 'success', message })

	setTimeout(() => {
		const buttons = document.querySelectorAll('.menu button')
		Array.from(buttons).map(button => {
			const target = button.getAttribute('data-target')
			target === 'pedidos' ? button.classList.add('active') : button.classList.remove('active')
		})

		$('#content').load('./pages/Pedidos/Pedidos.html')
	}, 2000)

}

const MSG_DeletePedido = id => {

	Swal.fire({
		title: '¿Deseas eliminar este pedido?',
		text: "No podrás revertir esta acción",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#f47028',
		cancelButtonColor: '#1c273a',
		confirmButtonText: 'Sí, Eliminar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {

		if (result.isConfirmed) DeletePedido(id)

	})

}

const DeletePedido = async id => {

	BeforeSend()

	// const URL = `http://pruebasoc.com/pedidos/deletePedido?column=id&value=${id}`
	const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/pedidos/deletePedido?column=id&value=${id}`

	const response = await fetch(URL, {
		method: 'DELETE',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
		}
	})

	const { status, message, data } = await response.json()

	if (status !== 200) return msg({ type: 'error', message })

	msg({ type: 'success', message })

	setTimeout(() => {
		const buttons = document.querySelectorAll('.menu button')
		Array.from(buttons).map(button => {
			const target = button.getAttribute('data-target')
			target === 'pedidos' ? button.classList.add('active') : button.classList.remove('active')
		})

	} , 2000)

	$('#content').load('./pages/Pedidos/Pedidos.html')

}