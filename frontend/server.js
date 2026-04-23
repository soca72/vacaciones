const http = require("http");

const { html } = require("./page-template");
const {
  handleApiBuscar,
  handleApiSolicitudDetalle,
  handleApiSolicitudes,
  sendHtml,
  sendJson,
  sendText,
} = require("./proxy-handlers");

const PORT = 3000;

const server = http.createServer(async (req, res) => {
  if (!req.url) {
    sendText(res, 400, "Solicitud invalida");
    return;
  }

  if (req.method === "GET" && req.url.startsWith("/api/usuarios/buscar")) {
    await handleApiBuscar(req, res);
    return;
  }

  if (req.method === "GET" && req.url.startsWith("/api/solicitudes/consultar")) {
    await handleApiSolicitudes(req, res);
    return;
  }

  if (req.method === "GET" && req.url.startsWith("/api/solicitudes/detalle")) {
    await handleApiSolicitudDetalle(req, res);
    return;
  }

  if (req.method === "GET" && req.url === "/health") {
    sendJson(res, 200, { status: "ok", service: "frontend" });
    return;
  }

  if (req.method === "GET" && req.url === "/") {
    sendHtml(res, html);
    return;
  }

  sendText(res, 404, "No encontrado");
});

server.listen(PORT, "0.0.0.0", () => {
  console.log(`Frontend escuchando en puerto ${PORT}`);
});
