const $content = document.getElementById('content')
const $menu = document.querySelector('.menu')

$menu.addEventListener( 'click', e => {

	const element = e.target
	const nodeName = element.nodeName

	if ( nodeName !== 'ION-ICON' && nodeName !== 'BUTTON' ) return

	const buttons = document.querySelectorAll('.menu button')
	Array.from( buttons ).map( button => button.classList.remove('active') )
	
	const parent = nodeName === 'BUTTON' ? element : element.closest('.item-menu')
	const target = parent.getAttribute('data-target')
	parent.classList.add('active')

	if ( target === 'inicio' ) $content.innerHTML = Home()

	if ( target === 'solicitantes' ) $('#content').load('./pages/Solicitantes/Solicitantes.html')
	
	if ( target === 'nuevo' ) $('#content').load('./pages/Solicitantes/forms/NuevoSolicitante.html')

	if ( target === 'pedidos' ) $('#content').load('./pages/Pedidos/Pedidos.html')

	if ( target === 'nuevo_pedido' ) $('#content').load('./pages/Pedidos/forms/NuevoPedido.html')

})

$content.innerHTML = Home()