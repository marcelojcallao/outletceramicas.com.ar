import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ac24e4e6307b1e65a4b1',
    cluster: 'sa1',
    encrypted: true
    
});


window.Echo.private('pedidoClienteChannel').listen('PedidoClienteCreated', (pedido) => {
	
	console.log("🚀 ~ f############################", )
	console.log("🚀 ~ file: app.js:10 ~ Echo.channel ~ e:", pedido)
	console.log("🚀 ~ f############################", )
})