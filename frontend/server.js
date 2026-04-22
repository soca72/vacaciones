const http = require("http");

const html = `
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Vacaciones - Nueva versión</title>
</head>
<body>
  <h1>Vacaciones - Nueva versión</h1>
  <p>Frontend base levantado correctamente.</p>
</body>
</html>
`;

const server = http.createServer((req, res) => {
  res.writeHead(200, { "Content-Type": "text/html; charset=utf-8" });
  res.end(html);
});

server.listen(3000, "0.0.0.0", () => {
  console.log("Frontend escuchando en puerto 3000");
});