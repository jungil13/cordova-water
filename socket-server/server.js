const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*", // Adjust for production
        methods: ["GET", "POST"]
    }
});

const userSockets = new Map();

io.on('connection', (socket) => {
    console.log('A user connected:', socket.id);

    socket.on('register', (userId) => {
        userSockets.set(userId, socket.id);
        console.log(`User ${userId} registered with socket ${socket.id}`);
    });

    socket.on('disconnect', () => {
        // Cleanup
        for (let [userId, socketId] of userSockets.entries()) {
            if (socketId === socket.id) {
                userSockets.delete(userId);
                break;
            }
        }
        console.log('User disconnected');
    });
});

// Endpoint for PHP to send notifications
app.post('/notify', (req, res) => {
    const { userId, title, message } = req.body;

    if (userId) {
        const socketId = userSockets.get(userId);
        if (socketId) {
            io.to(socketId).emit('notification', { title, message });
            return res.json({ success: true, delivered: true });
        }
        return res.json({ success: true, delivered: false, reason: 'User not connected' });
    }

    // Broadcast to all if no userId
    io.emit('notification', { title, message });
    res.json({ success: true, delivered: true, type: 'broadcast' });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Socket.IO server running on port ${PORT}`);
});
