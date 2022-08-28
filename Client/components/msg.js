const msg = ({ type, message, err = '' }) => {

	err && console.error(err)

	const $container_msg = document.querySelector('.container-msg')
	if ( $container_msg ) $container_msg.remove()

	let icon
	const $body = document.querySelector('body')
	const $msg = document.createElement('div')
	$msg.classList.add('container-msg')

	if ( type === 'success' ) icon = 'checkmark-circle-outline'
	if ( type === 'warning' ) icon = 'warning-outline'
	if ( type === 'error' ) icon = 'bug-outline'
	if ( type === 'info' ) icon = 'information-circle-outline'

	const content = `
		<div class="msg-info bg-${ type } animate__animated animate__slideInDown">
			<span>
				<ion-icon name="${ icon }"></ion-icon>
			</span>

			<label for="msg">${ message }</label>

			<button type="button" role="button" class="dissmis">
				<ion-icon name="close-circle-outline"></ion-icon>
			</button>
		</div>
	`

	$msg.innerHTML = content
	$body.appendChild($msg)

	setTimeout(() => {

		const $content_msg = document.querySelector('.msg-info')
		$content_msg.classList.add('animate__slideOutUp')
		
		setTimeout(() => $msg.remove(), 4000)

	}, 4000)

}

BeforeSend = () => {

	const $container_msg = document.querySelector('.container-msg')
	if ( $container_msg ) $container_msg.remove()

	const $body = document.querySelector('body')
	const $msg = document.createElement('div')
	$msg.classList.add('container-msg')

	const content = `
		<div class="msg-info bg-info animate__animated animate__slideInDown">
			<img src="./assets/img/beforesend.gif" alt="loading" width="25">

			<label for="msg">Procesando...</label>
		</div>
	`

	$msg.innerHTML = content
	$body.appendChild($msg)

}