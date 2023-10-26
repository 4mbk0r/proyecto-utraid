const app = require('express')();
const server = require('http').createServer(app);
const io = require('socket.io')(server);

const PORT = process.env.PORT || 3000;

server.listen(PORT, () => {
    console.log(`Servidor Socket.io en funcionamiento en el puerto ${PORT}`);
});

io.on('connection', (socket) => {
    console.log('Cliente conectado:', socket.id);

    // Maneja la autenticación del usuario
    socket.on('auth', (data) => {
        // Verifica las credenciales del usuario aquí (por ejemplo, utilizando un token de autenticación)
        if (data.tokenValid) {
            // Autenticación exitosa, permite que el usuario acceda al canal
            socket.join(`saludo-usuario.${data.userId}`);
        } else {
            // Autenticación fallida, deniega el acceso
            socket.emit('auth_error', { message: 'Autenticación fallida' });
        }
    });

    // Configura la lógica para manejar eventos cuando se reciban desde el cliente
    socket.on('saludo', (data) => {
        console.log('Evento recibido:', data);
        // Realiza acciones según el evento recibido
        // Puedes emitir eventos de vuelta al cliente si es necesario
        // socket.emit('respuesta_evento', { mensaje: 'Respuesta al evento' });
    });

    // Configura la lógica para manejar la desconexión de clientes
    socket.on('disconnect', () => {
        console.log('Cliente desconectado:', socket.id);
    });
});
