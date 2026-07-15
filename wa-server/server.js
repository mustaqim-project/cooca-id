const express = require('express');
const bodyParser = require('body-parser');
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');

const app = express();
app.use(bodyParser.json());

const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    }
});

let qrCodeData = null;
let isReady = false;

client.on('qr', (qr) => {
    qrCodeData = qr;
    qrcode.generate(qr, { small: true });
    console.log('QR Code received, scan please!');
});

client.on('ready', () => {
    isReady = true;
    qrCodeData = null;
    console.log('WhatsApp Client is ready!');
});

client.on('authenticated', () => {
    console.log('Authenticated successfully!');
});

client.on('auth_failure', msg => {
    console.error('Authentication failure', msg);
    isReady = false;
});

client.on('disconnected', (reason) => {
    console.log('Client was disconnected', reason);
    isReady = false;
});

client.initialize();

// API Endpoint to get QR Code
app.get('/qr', (req, res) => {
    if (isReady) {
        return res.json({ status: 'ready', message: 'Client is already authenticated and ready.' });
    }
    if (qrCodeData) {
        return res.json({ status: 'pending', qr: qrCodeData });
    }
    res.json({ status: 'loading', message: 'Generating QR code, please wait...' });
});

// API Endpoint to send message
app.post('/send', async (req, res) => {
    if (!isReady) {
        return res.status(503).json({ error: 'WhatsApp client is not ready yet.' });
    }

    const { number, message } = req.body;
    if (!number || !message) {
        return res.status(400).json({ error: 'Missing number or message' });
    }

    try {
        // Format number: remove leading + or 0, append @c.us
        let formattedNumber = number.replace(/\D/g, '');
        if (formattedNumber.startsWith('0')) {
            formattedNumber = '62' + formattedNumber.substring(1);
        }
        
        const chatId = formattedNumber + '@c.us';
        await client.sendMessage(chatId, message);
        
        res.json({ success: true, message: 'Message sent successfully.' });
    } catch (error) {
        console.error('Failed to send message:', error);
        res.status(500).json({ error: 'Failed to send message', details: error.toString() });
    }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`WhatsApp API Server running on port ${PORT}`);
});
