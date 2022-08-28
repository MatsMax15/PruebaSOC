// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::::::: TABLE :::::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

// :: Cargar Solicitantes :: //
const Solicitantes = async () => {

	const data = await GetSolicitantes()
	if (!data) return

	const { total, results } = data
	const $results = document.getElementById('results')
	$results.innerHTML = `Mostrando ${total} ${total === 1 ? 'resultado' : 'resultados'}`

	const $tbody = document.getElementById('tbody')

	if ( total === 0 ) {

		$results.innerHTML = 'No hay resultados'
		$tbody.innerHTML = `
			<tr>
				<td class="text-center" colspan="10">No hay resultados</td>
			</tr>
		`
		return
		
	}

	$tbody.innerHTML = results.map(x => {
		return `
			<tr>
				<td>${x.nombre} ${x.apellido_paterno} ${x.apellido_materno}</td>
				<td class="d-none d-lg-table-cell">${x.curp}</td>
				<td class="d-none d-md-table-cell">${x.email}</td>
				<td>${x.edad}</td>
				<td class="text-center">
					<div class="btn-group" role="group" aria-label="Basic example">
						<button class="btn btn-primary btn-small" onclick="Update(${ x.id })">
							<ion-icon name="create-outline"></ion-icon>
						</button>
						<button class="btn btn-danger btn-small" onclick="MSG_DeleteSolicitante(${ x.id })">
							<ion-icon name="trash-outline"></ion-icon>
						</button>
					</div>
				</td>
			</tr>
		`
	}).join('')

}

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::::::: FORMS :::::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

// :: Cargar Contenido en Formulario :: //
const UploadContent = async () => {

	const $anio = document.getElementById('anio')
	const $mes = document.getElementById('mes')

	$anio.innerHTML = `
		<option value="">Año *</option>
		${Array
			.from(Array(new Date().getFullYear() - 1900 + 1).keys())
			.map(x => `<option value="${x + 1950}">${x + 1950}</option>`)
			.join('')
		}
	`

	$mes.innerHTML = `
		<option value="">Mes *</option>
		${Array
			.from(Array(12).keys())
			.map(x => `<option value="${x + 1}">${x + 1}</option>`)
			.join('')
		}
	`

	const data_comprobantes = await GetComprobantes()
	if (!data_comprobantes) return

	const { results } = data_comprobantes

	const $tipo_comprobante = document.querySelector('.tipo_comprobante')
	$tipo_comprobante.innerHTML = `
		<option value="">Tipo de comprobante *</option>
		${
			results
				.map(x => `<option value="${x.id}">${x.tipo}</option>`)
				.join('')
		}
	`

	const data_empleos = await GetEmpleos()
	if (!data_empleos) return

	const { results: empleos } = data_empleos

	const $tipo_empleo = document.querySelector('.tipo_empleo')
	$tipo_empleo.innerHTML = `
		<option value="">Empleo *</option>
		${
			empleos
				.map(x => `<option value="${x.id}">${x.tipo}</option>`)
				.join('')
		}
	`

}

// :: Cargar Contenido en Formulario Modificar Solicitante :: //
const UploadSolicitante = async id => {

	const data_solicitante = await GetSolicitante(id)
	const data_ingresos = await GetIngresos(id)

	// Datos del Solicitante
	for (const key in data_solicitante) {

		if ( key !== 'id' && key !== 'fecha_registro' ) {

			if ( key === 'fecha_nacimiento' ) {

				const anio = data_solicitante[key].split('-')[0]
				const mes = data_solicitante[key].split('-')[1]
				const dia = data_solicitante[key].split('-')[2]

				const $anio = document.getElementById('anio')
				const $mes = document.getElementById('mes')
				const $dia = document.getElementById('dia')

				$anio.value = anio
				$mes.value = parseInt(mes)
				GetDays()
				$dia.value = parseInt(dia)

			}
			else {

				document.getElementById(key).value = data_solicitante[key]

			}
		}

	}

	// Datos de ingresos
	data_ingresos.results.map((x, i) => {
		
		const clone = AddItem()

		for (const key in x) {

			if ( key !== 'fecha_registro' && key !== 'id_solicitante' ) {

				clone.querySelector(`.${ key }`).value = x[key]

			}

		}

	})

	const $first_delete = document.querySelector('.btn-remove-ingreso')
	$first_delete.click()

}

const GetDays = () => {

	const $year = document.getElementById('anio')
	const $month = document.getElementById('mes')
	const $day = document.getElementById('dia')

	if (!$year.value || !$month.value) $day.value = ''

	const daysMonth = new Date($year.value, $month.value, 0).getDate()
	let options = '<option value="">Día *</option>'
	for (let day = 1; day <= daysMonth; day++) {
		options += `<option value="${day}">${day}</option>`
	}

	$day.innerHTML = options

	Age({ year: $year.value, month: $month.value, day: $day.value })

}

const Age = ({ year, month, day }) => {

	const date = `${ year }-${ month < 10 ? '0' + month : month}-${ day < 10 ? '0' + day : day}`
	const age = GetAge(date)

	const edad = document.getElementById('edad')	

	edad.value = age

}

const GetAge = ( date ) => {

	const arrayDate = date.split('-')
	const age = new Date( arrayDate[0], parseInt(arrayDate[1]) - 1, arrayDate[2] )
	const corrent_date = new Date()

	if ( !date || isNaN( age ) ) return
	if (corrent_date - age < 0) return
	
	let months = corrent_date.getUTCMonth() - age.getUTCMonth()
	let years = corrent_date.getUTCFullYear() - age.getUTCFullYear()

	if ( months < 0 ) {
		years--
	}

	return years
}

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::::: FUNCTIONS :::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

// :: Nuevo Solicitante :: //
const NewSolicitante = async e => {

	e.preventDefault()
	
	// :: Validar campos obligatorios :: //
	const $inputs = document.querySelectorAll('.form-control')
	for (const input of $inputs) {
		
		input.classList.remove('is-invalid')
		const required = input.getAttribute('required')

		if ( required !== null && !input.value ) {

			msg({ type: 'warning', message: 'Debes completar los campos obligatorios' })
			input.classList.add('is-invalid')
			return input.focus()

		}

	}

	const $items_ingresos = document.querySelectorAll('.item-ingresos')
	const data_ingresos = Array.from($items_ingresos).map((x, i) => {

		return {
			posicion: i + 1,
			empresa: x.querySelector('.empresa').value,
			tipo_comprobante: x.querySelector('.tipo_comprobante').value,
			tipo_empleo: x.querySelector('.tipo_empleo').value,
			salario_bruto: x.querySelector('.salario_bruto').value,
			salario_neto: x.querySelector('.salario_neto').value,
			fecha_inicio: x.querySelector('.fecha_inicio').value
		}

	}).filter(x => x.empresa)

	// Validar que existan ingresos
	if ( !data_ingresos.length ) {
		return msg({ type: 'warning', message: 'Debes ingresar al menos una forma de ingreso' })
	}

	// Validar ingresos repetidos 
	const unique = data_ingresos.filter((x, i) => data_ingresos.findIndex(y => y.empresa === x.empresa) === i)
	if ( data_ingresos.length !== unique.length ) {
		return msg({ type: 'warning', message: `La empresa ${ unique[0].empresa } ya se encuentra en la posición ${ unique[0].posicion }` })
	}

	// Validar salarios
	const salarios = data_ingresos.map(x => { 
		return x.salario_bruto < 1 || x.salario_neto < 1 && x
	}).filter(x => x)

	if ( salarios.length ) {

		const { empresa } = salarios[0]
		return msg({ type: 'warning', message: `El salario bruto de la empresa ${ empresa } debe ser mayor a 0` })

	}

	// Validar email
	const $email = document.getElementById('email')
	if ( !ValidateEmail($email.value) ) {

		$email.classList.add('is-invalid')
		return $email.focus()

	}

	const formData = new FormData()
	formData.append('nombre', document.getElementById('nombre').value)
	formData.append('apellido_paterno', document.getElementById('apellido_paterno').value)
	formData.append('apellido_materno', document.getElementById('apellido_materno').value)
	formData.append('anio', document.getElementById('anio').value)
	formData.append('mes', document.getElementById('mes').value)
	formData.append('dia', document.getElementById('dia').value)
	formData.append('edad', document.getElementById('edad').value)
	formData.append('genero', document.getElementById('genero').value)
	formData.append('curp', document.getElementById('curp').value)
	formData.append('email', document.getElementById('email').value)
	formData.append('cp', document.getElementById('cp').value)
	formData.append('calle', document.getElementById('calle').value)
	formData.append('no_exterior', document.getElementById('no_exterior').value)
	formData.append('colonia', document.getElementById('colonia').value)
	formData.append('municipio', document.getElementById('municipio').value)
	formData.append('estado', document.getElementById('estado').value)
	formData.append('ingresos', JSON.stringify(data_ingresos))
	
	BeforeSend()

	// const URL = 'http://pruebasoc.com/solicitantes/createSolicitante'
	const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/solicitantes/createSolicitante`

	const response = await fetch(URL, {
		method: 'POST',
		body: formData
	})

	const { status, message, data } = await response.json()

	if ( status !== 200 ) return msg({ type: 'error', message })

	msg({ type: 'success', message: `Se registro al solicitante ${ data.nombre }` })

	setTimeout(() => {
		const buttons = document.querySelectorAll('.menu button')
		Array.from( buttons ).map( button => {
			const target = button.getAttribute('data-target')
			target === 'solicitantes' ? button.classList.add('active') : button.classList.remove('active')
		})

		$('#content').load('./pages/Solicitantes/Solicitantes.html')
	}, 2000)

}

// :: Cargar Formulario Editar Solicitante :: //
const Update = id => {

	localStorage.setItem('id', id)
	$('#content').load('./pages/Solicitantes/forms/ModificarSolicitante.html')

}

// :: Editar Solicitante :: //
const UpdateSolicitante = async e => {

	e.preventDefault()
	
	// :: Validar campos obligatorios :: //
	const $inputs = document.querySelectorAll('.form-control')
	for (const input of $inputs) {
		
		input.classList.remove('is-invalid')
		const required = input.getAttribute('required')

		if ( required !== null && !input.value ) {

			msg({ type: 'warning', message: 'Debes completar los campos obligatorios' })
			input.classList.add('is-invalid')
			return input.focus()

		}

	}

	const $items_ingresos = document.querySelectorAll('.item-ingresos')
	const data_ingresos = Array.from($items_ingresos).map((x, i) => {

		return {
			posicion: i + 1,
			id: x.querySelector('.id').value,
			empresa: x.querySelector('.empresa').value,
			tipo_comprobante: x.querySelector('.tipo_comprobante').value,
			tipo_empleo: x.querySelector('.tipo_empleo').value,
			salario_bruto: x.querySelector('.salario_bruto').value,
			salario_neto: x.querySelector('.salario_neto').value,
			fecha_inicio: x.querySelector('.fecha_inicio').value
		}

	}).filter(x => x.empresa)

	// Validar que existan ingresos
	if ( !data_ingresos.length ) {
		return msg({ type: 'warning', message: 'Debes ingresar al menos una forma de ingreso' })
	}

	// Validar ingresos repetidos 
	const unique = data_ingresos.filter((x, i) => data_ingresos.findIndex(y => y.empresa === x.empresa) === i)
	if ( data_ingresos.length !== unique.length ) {
		return msg({ type: 'warning', message: `La empresa ${ unique[0].empresa } ya se encuentra en la posición ${ unique[0].posicion }` })
	}

	// Validar salarios
	const salarios = data_ingresos.map(x => { 
		return x.salario_bruto < 1 || x.salario_neto < 1 && x
	}).filter(x => x)

	if ( salarios.length ) {

		const { empresa } = salarios[0]
		return msg({ type: 'warning', message: `El salario bruto de la empresa ${ empresa } debe ser mayor a 0` })

	}

	// Validar email
	const $email = document.getElementById('email')
	if ( !ValidateEmail($email.value) ) {

		$email.classList.add('is-invalid')
		return $email.focus()

	}

	const data_update = new URLSearchParams()
	data_update.append('nombre', document.getElementById('nombre').value)
	data_update.append('apellido_paterno', document.getElementById('apellido_paterno').value)
	data_update.append('apellido_materno', document.getElementById('apellido_materno').value)
	data_update.append('anio', document.getElementById('anio').value)
	data_update.append('mes', document.getElementById('mes').value)
	data_update.append('dia', document.getElementById('dia').value)
	data_update.append('edad', document.getElementById('edad').value)
	data_update.append('sexo', document.getElementById('sexo').value)
	data_update.append('curp', document.getElementById('curp').value)
	data_update.append('email', document.getElementById('email').value)
	data_update.append('cp', document.getElementById('cp').value)
	data_update.append('calle', document.getElementById('calle').value)
	data_update.append('numero_exterior', document.getElementById('numero_exterior').value)
	data_update.append('colonia', document.getElementById('colonia').value)
	data_update.append('municipio', document.getElementById('municipio').value)
	data_update.append('estado', document.getElementById('estado').value)
	data_update.append('ingresos', JSON.stringify(data_ingresos))
	
	BeforeSend()

	// const URL = `http://pruebasoc.com/solicitantes/updateSolicitante?column=id&value=${ localStorage.getItem('id') }`
	const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/solicitantes/updateSolicitante?column=id&value=${ localStorage.getItem('id') }`

	const response = await fetch(URL, {
		method: 'PUT',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
		},
		body: data_update
	})

	const { status, message, data } = await response.json()

	if ( status !== 200 ) return msg({ type: 'error', message })

	msg({ type: 'success', message })

	setTimeout(() => {
		const buttons = document.querySelectorAll('.menu button')
		Array.from( buttons ).map( button => {
			const target = button.getAttribute('data-target')
			target === 'solicitantes' ? button.classList.add('active') : button.classList.remove('active')
		})

		$('#content').load('./pages/Solicitantes/Solicitantes.html')
	}, 2000)

}

const MSG_DeleteSolicitante = id => {

	Swal.fire({
		title: '¿Deseas eliminar al solicitante?',
		text: "Toda su información será eliminada permanentemente",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#f47028',
		cancelButtonColor: '#1c273a',
		confirmButtonText: 'Sí, Eliminar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {

		if (result.isConfirmed) DeleteSolicitante(id)

	})

}

const DeleteSolicitante = async id => {

	// const URL = `http://pruebasoc.com/solicitantes/deleteSolicitante?column=id&value=${ id }`
	const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/solicitantes/deleteSolicitante?column=id&value=${ id }`

	const response = await fetch(URL, {
		method: 'DELETE',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
		}
	})

	// const data = await response.json()
	// console.log(data)

	const { status, message } = await response.json()

	if ( status !== 200 ) return msg({ type: 'error', message })

	msg({ type: 'success', message })

	setTimeout(() => {
		const buttons = document.querySelectorAll('.menu button')
		Array.from( buttons ).map( button => {
			const target = button.getAttribute('data-target')
			target === 'solicitantes' ? button.classList.add('active') : button.classList.remove('active')
		})

	} , 2000)
	
	$('#content').load('./pages/Solicitantes/Solicitantes.html')
	
}