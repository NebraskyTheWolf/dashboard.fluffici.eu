const express = require('express')
const bwipJs= require('bwip-js');
const nodeBase64 = require('nodejs-base64-converter');
const path = require('path')
const fs = require("fs"),
    { createCanvas, loadImage, PNGStream} = require("canvas");

const http = require('http')
const bodyParser = require("body-parser");

const app = express()

app.use(express.static(path.join(__dirname, '../public')))
app.use(bodyParser())

app.post('/voucher/:price', async function (req, res) {
    const decoded = JSON.parse(JSON.stringify(nodeBase64.decode(req.body)))
    const id = nodeBase64.decode(decoded.data);

    console.log(id)

    if (decoded.signature === undefined || decoded.data === undefined) {
        return res.status(404).json({
            'status': false,
            'error': 'VOUCHER_ID_MISSING',
            'message': 'The voucher id is missing.'
        })
    }

    const canvas = createCanvas(806, 988),
        ctx = canvas.getContext("2d");

    await loadImage(path.join(__dirname, 'public', 'default_voucher_cards.png')).then(async img => {
        ctx.drawImage(img, 0, 0);

        ctx.font = '26px "Arial Bold"';
        ctx.fillStyle = "rgb(255,255,255)";
        ctx.fillText(id, 130,470);
        ctx.fillText(req.params.price + ' Kc', 368,580);

        bwipJs.toBuffer({
            bcid: 'datamatrix',
            text: JSON.stringify(req.body),
            barcolor: '#FFF'
        }).then(png => {
            const dout = fs.createWriteStream(path.join(__dirname, 'cache', id + '-datamatrix.png')),
                dstream = new PNGStream.from(png);
            dstream.pipe(dout);
        })

        setTimeout(async () => {
            await loadImage(path.join(__dirname, 'cache', id + '-datamatrix.png')).then(img => {
                ctx.drawImage(img, 612,803, 160, 160)
            })

            const out = fs.createWriteStream(path.join(__dirname, '../src/storage/app/public', id + '-code.png')),
                stream = canvas.createPNGStream();
            stream.pipe(out);
        }, 3000)

    })

    res.status(200).json({
        'status': true,
        'message': 'The was was created.',
        'path': path.join(__dirname, '../src/storage/app/public', id + '-code.png')
    })
});

http.createServer(app).listen(3900)

