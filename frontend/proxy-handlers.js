const API_BASE_URL = "http://backend:8000";

function sendJson(res, statusCode, payload) {
  res.writeHead(statusCode, {
    "Content-Type": "application/json; charset=utf-8",
    "Cache-Control": "no-store, no-cache, must-revalidate, proxy-revalidate",
    Pragma: "no-cache",
    Expires: "0",
  });
  res.end(JSON.stringify(payload));
}

function sendHtml(res, html) {
  res.writeHead(200, {
    "Content-Type": "text/html; charset=utf-8",
    "Cache-Control": "no-store, no-cache, must-revalidate, proxy-revalidate",
    Pragma: "no-cache",
    Expires: "0",
  });
  res.end(html);
}

function sendText(res, statusCode, message) {
  res.writeHead(statusCode, { "Content-Type": "text/plain; charset=utf-8" });
  res.end(message);
}

async function proxyJson(res, backendUrl, endpointName) {
  try {
    const backendResponse = await fetch(backendUrl);
    const bodyText = await backendResponse.text();

    res.writeHead(backendResponse.status, {
      "Content-Type": "application/json; charset=utf-8",
      "Cache-Control": "no-store, no-cache, must-revalidate, proxy-revalidate",
      Pragma: "no-cache",
      Expires: "0",
    });
    res.end(bodyText);
  } catch (error) {
    sendJson(res, 502, {
      status: "error",
      endpoint: endpointName,
      message: `No fue posible conectar con el backend: ${error.message}`,
    });
  }
}

async function handleApiBuscar(req, res) {
  const requestUrl = new URL(req.url, `http://${req.headers.host}`);
  const query = requestUrl.searchParams.get("q");
  const exacto = requestUrl.searchParams.get("exacto");

  if (!query || query.trim().length < 2) {
    sendJson(res, 400, {
      status: "error",
      endpoint: "/api/usuarios/buscar",
      message: "El parametro q debe tener al menos 2 caracteres.",
    });
    return;
  }

  const backendUrl = new URL("/usuarios/buscar", API_BASE_URL);
  backendUrl.searchParams.set("q", query.trim());

  if (exacto === "true") {
    backendUrl.searchParams.set("exacto", "true");
  }

  await proxyJson(res, backendUrl, "/api/usuarios/buscar");
}

async function handleApiVacacionesResumen(req, res) {
  const requestUrl = new URL(req.url, `http://${req.headers.host}`);
  const idusuario = requestUrl.searchParams.get("idusuario");

  if (!idusuario) {
    sendJson(res, 400, {
      status: "error",
      endpoint: "/api/vacaciones/resumen",
      message: "El parametro idusuario es obligatorio.",
    });
    return;
  }

  const backendUrl = new URL("/vacaciones/resumen", API_BASE_URL);
  backendUrl.searchParams.set("idusuario", idusuario);

  await proxyJson(res, backendUrl, "/api/vacaciones/resumen");
}

async function handleApiSolicitudes(req, res) {
  const requestUrl = new URL(req.url, `http://${req.headers.host}`);
  const autorizador = requestUrl.searchParams.get("idusuariodata_autorizador");
  const idsolicitud = requestUrl.searchParams.get("idsolicitud");
  const idusuariodata = requestUrl.searchParams.get("idusuariodata");

  if (!autorizador) {
    sendJson(res, 400, {
      status: "error",
      endpoint: "/api/solicitudes/consultar",
      message: "El parametro idusuariodata_autorizador es obligatorio.",
    });
    return;
  }

  if (!idusuariodata && !idsolicitud) {
    sendJson(res, 400, {
      status: "error",
      endpoint: "/api/solicitudes/consultar",
      message: "Debes enviar idusuariodata o idsolicitud.",
    });
    return;
  }

  const backendUrl = new URL("/solicitudes/consultar", API_BASE_URL);
  const passthroughParams = [
    "idusuariodata_autorizador",
    "idsolicitud",
    "idusuariodata",
    "estado",
    "fi",
    "ff",
    "tipo_fecha",
    "limit",
  ];

  for (const param of passthroughParams) {
    const value = requestUrl.searchParams.get(param);
    if (value) {
      backendUrl.searchParams.set(param, value);
    }
  }

  await proxyJson(res, backendUrl, "/api/solicitudes/consultar");
}

async function handleApiSolicitudDetalle(req, res) {
  const requestUrl = new URL(req.url, `http://${req.headers.host}`);
  const idsolicitud = requestUrl.searchParams.get("idsolicitud");

  if (!idsolicitud) {
    sendJson(res, 400, {
      status: "error",
      endpoint: "/api/solicitudes/detalle",
      message: "El parametro idsolicitud es obligatorio.",
    });
    return;
  }

  const backendUrl = new URL("/solicitudes/detalle", API_BASE_URL);
  backendUrl.searchParams.set("idsolicitud", idsolicitud);

  await proxyJson(res, backendUrl, "/api/solicitudes/detalle");
}

module.exports = {
  handleApiBuscar,
  handleApiSolicitudDetalle,
  handleApiSolicitudes,
  handleApiVacacionesResumen,
  sendHtml,
  sendJson,
  sendText,
};
