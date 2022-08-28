// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::: SOLICITANTES ::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

const GetSolicitantes = async () => {

	try {
		
		// const URL = 'http://pruebasoc.com/solicitantes'
		const URL = 'https://lamf-service.com/PruebaSOC/Api/index.php/solicitantes'
	
		const resp = await fetch(URL)
		const { status, data, message } = await resp.json()
	
		if ( status !== 200 ) throw new Error(message)
	
		return data

	} catch (error) {
		
		msgError(error)
		return false

	}

}

const GetSolicitante = async (id) => {

	try {
		
		// const URL = `http://pruebasoc.com/solicitantes/solicitante?column=id&value=${id}`
		const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/solicitantes/solicitante?column=id&value=${id}`
	
		const resp = await fetch(URL)
		const { status, data, message } = await resp.json()
	
		if ( status !== 200 ) throw new Error(message)

		return data

	} catch (error) {
		
		msgError(error)
		return false

	}

}

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::::: INGRESOS ::::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

const GetIngresos = async id_solicitante => {

	try {
		
		// const URL = `http://pruebasoc.com/ingresos?column=id_solicitante&value=${id_solicitante}`
		const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/ingresos?column=id_solicitante&value=${id_solicitante}`
	
		const resp = await fetch(URL)
		const { status, data, message } = await resp.json()
	
		if ( status !== 200 ) throw new Error(message)

		return data

	} catch (error) {
		
		msgError(error)
		return false

	}

}

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::: COMPROBANTES ::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

const GetComprobantes = async () => {

	try {
		
		// const URL = 'http://pruebasoc.com/comprobantes'
		const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/comprobantes`
	
		const resp = await fetch(URL)
		const { status, data, message } = await resp.json()
	
		if ( status !== 200 ) throw new Error(message)

		return data

	} catch (error) {
		
		msgError(error)
		return false

	}

}

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::::: EMPLEOS :::::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

const GetEmpleos = async () => {

	try {
		
		// const URL = 'http://pruebasoc.com/empleos'
		const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/empleos`
	
		const resp = await fetch(URL)
		const { status, data, message } = await resp.json()
	
		if ( status !== 200 ) throw new Error(message)

		return data

	} catch (error) {
		
		msgError(error)
		return false

	}

}

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //
// :::::::::::::::::::::::::: PEDIDOS :::::::::::::::::::::::::::: //
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: //

const GetPedidos = async () => {

	try {
		
		// const URL = 'http://pruebasoc.com/pedidos'
		const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/pedidos`
	
		const resp = await fetch(URL)
		const { status, data, message } = await resp.json()
	
		if ( status !== 200 ) throw new Error(message)

		return data

	} catch (error) {
		
		msgError(error)
		return false

	}

}

const GetPedido = async (id) => {

	try {
		
		// const URL = `http://pruebasoc.com/pedidos/pedido?column=id&value=${id}`
		const URL = `https://lamf-service.com/PruebaSOC/Api/index.php/pedidos/pedido?column=id&value=${id}`
	
		const resp = await fetch(URL)
		const { status, data, message } = await resp.json()
	
		if ( status !== 200 ) throw new Error(message)

		return data

	} catch (error) {
		
		msgError(error)
		return false

	}

}