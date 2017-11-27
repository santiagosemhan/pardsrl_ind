// var express = require('express');
// var app = express();

var app = require('express')()
var watch = require('node-watch')
var fs = require('fs')
var compression = require('compression')
var request = require('request')

const moment = require('moment-timezone')

var config = require('./config')

var server
var port

if (!config.server.ssl.enable) {
  server = require('http').Server(app)
  port = config.server.port
} else {
  // This line is from the Node.js HTTPS documentation.
  //
  var keyfile = config.server.ssl.keyfile
  var certfile = config.server.ssl.certfile

  var options = {
    key: fs.readFileSync(keyfile, 'utf8'),
    cert: fs.readFileSync(certfile, 'utf8')
  }

  server = require('https').Server(options, app)
  port = config.server.ssl.port
}

var io = require('socket.io')(server)

app.use(function (req, res, next) {
  res.header('Access-Control-Allow-Origin', '*')
  res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept')
  next()
})

// comprimir las respuestas
app.use(compression())

/**
 * Controlador que retorna los puntos de los historicos
 *
 * @param equipo El equipo que se desea obtener la grafica
 * @param grafica El tipo de grafica que se busca
 *
 */
app.get('/historico', function (req, res) {
  res.setHeader('Content-Type', 'application/json')
  res.setHeader('Cache-Control', 'no-cache')

  let filters = {
    metrics: [],
    from: parseInt(req.query.desde),
    to: parseInt(req.query.hasta)
  }

  let histogramType = parseInt(req.query.tipo)

  if( histogramType === 0){ //es histórico de plumas
      filters.metrics =  ['hta','anem','bpozo','llave','haparejo']
  }else if (histogramType === 1) { //histórico de maniobras
      filters.metrics =  ['adef','aexe','mtv','pbp','ppel','tmay','vto']
  }else{
    error = 'Estamos actualizando nuestro servicio de datos. En breve volverá a tener información'

    res.status(500).send('{ "status": "error" , "detail" : "' + error + '" }')

    return
  }

  let uri = `${config.historico_script}${req.query.equipo}?filters=${JSON.stringify(filters)}&resolution=${req.query.resolucion}`

  request({
    uri
  }, function (error, response, body) {

    if (!error && response.statusCode === 200) {

      data = JSON.parse(body)

      let jsonData = {
        datos:{}
      }

      data.forEach((item) => {
          rows = []

          item.rows.forEach((row) => {
            rows.push([
              moment(row.time).tz(config.timezone).valueOf(),
              row.max
            ])

          })

          jsonData.datos[item.name] = rows
      })

      res.send(jsonData)
    } else {
      error = error || 'Error interno del servidor de datos.'

      res.status(500).send('{ "status": "error" , "detail" : "' + error + '" }')
    }
  })


})

/**
   * WEBSOCKETS - TIEMPO REAL
   */

var sockets = config.sockets

var aSocketIO = [] // array con las instancias de socket IO.
var aWatchFile = [] // array con los archivos a evaluar
var aEmitter = [] // array con la configuracion de emision

Object.keys(sockets).forEach(function (key) {
  if (sockets[key].enable) {
    aSocketIO[key] = io.of(sockets[key].namespace) // guardo la instancia socket.IO

    var channels = sockets[key].channels

    for (let c in channels) {
      var channel = channels[c]

      if (channel.watch) {
        aWatchFile.push(channel.filename)
        aEmitter[channel.filename] = {
          socket: aSocketIO[key],
          channel: channel.alias
        }
      }
    }
  }
})

// console.log(aSocketIO);
// console.log(aWatchFile);
// console.log(aEmitter);

try {
  watch(aWatchFile, function (filename) {
    // console.log(filename, ' changed.');
    push(filename)
  })
} catch (err) {
  // handle the error safely
  console.log(err)
}

function push ($filename) {
  fs.readFile($filename, 'utf-8', function (err, data) {
    if (err) { console.log(err) }

    var socket = aEmitter[$filename].socket
    var channel = aEmitter[$filename].channel

    socket.emit(channel, data)
  })
}

server.listen(port, function () {
  console.log(`
    Node Server start ...... OK!
    Details:
    - Secure: ${config.server.ssl.enable}
    - Certfile: ${config.server.ssl.certfile}
    - Keyfile: ${config.server.ssl.keyfile}
    - Port: ${port}`
  )
})
