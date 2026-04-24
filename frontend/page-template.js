const html = `<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Vacaciones | Usuarios y solicitudes</title>
  <style>
    :root {
      color-scheme: light;
      --panel: rgba(255, 250, 242, 0.92);
      --panel-strong: #ffffff;
      --text: #1f2937;
      --muted: #6b7280;
      --primary: #0f766e;
      --primary-dark: #115e59;
      --border: #d6c7af;
      --shadow: 0 20px 45px rgba(68, 54, 35, 0.14);
      --error: #b42318;
      --accent: #b45309;
      --ok: #166534;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text);
      background:
        radial-gradient(circle at top left, rgba(255, 255, 255, 0.85), transparent 34%),
        linear-gradient(135deg, #e8d9bf 0%, #f4efe5 46%, #d8ece9 100%);
      min-height: 100vh;
    }

    main {
      width: min(1080px, calc(100% - 32px));
      margin: 40px auto;
      display: grid;
      gap: 20px;
    }

    .hero,
    .panel {
      background: var(--panel);
      border: 1px solid rgba(214, 199, 175, 0.9);
      border-radius: 24px;
      box-shadow: var(--shadow);
      backdrop-filter: blur(6px);
    }

    .hero,
    .panel {
      padding: 24px;
    }

    .eyebrow {
      margin: 0 0 8px;
      color: var(--primary-dark);
      font-size: 0.85rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
    }

    h1, h2 {
      margin: 0;
      line-height: 1.05;
    }

    h1 {
      font-size: clamp(2rem, 4vw, 3.2rem);
    }

    h2 {
      font-size: 1.4rem;
    }

    .lead {
      margin: 14px 0 0;
      max-width: 60ch;
      color: var(--muted);
      line-height: 1.5;
    }

    .section-head {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      align-items: end;
      margin-bottom: 18px;
    }

    .section-copy {
      color: var(--muted);
      margin: 8px 0 0;
    }

    form {
      display: grid;
      gap: 16px;
    }

    .row {
      display: grid;
      gap: 12px;
      grid-template-columns: minmax(0, 1fr) auto;
    }

    .filters {
      display: grid;
      gap: 12px;
      grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    label {
      display: grid;
      gap: 8px;
      font-weight: 600;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    select {
      width: 100%;
      padding: 14px 16px;
      border-radius: 14px;
      border: 1px solid var(--border);
      background: var(--panel-strong);
      font-size: 1rem;
    }

    .toggle {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-weight: 500;
      color: var(--muted);
    }

    button {
      padding: 14px 18px;
      border: 0;
      border-radius: 14px;
      background: linear-gradient(135deg, var(--primary) 0%, #155e75 100%);
      color: #fff;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
    }

    button:hover {
      background: linear-gradient(135deg, var(--primary-dark) 0%, #164e63 100%);
    }

    button.secondary {
      background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    }

    button.secondary:hover {
      background: linear-gradient(135deg, #92400e 0%, #b45309 100%);
    }

    .meta {
      min-height: 24px;
      color: var(--muted);
      font-size: 0.95rem;
    }

    .error {
      color: var(--error);
      font-weight: 600;
    }

    .success {
      color: var(--ok);
      font-weight: 600;
    }

    .results,
    .requests {
      display: grid;
      gap: 12px;
      margin-top: 18px;
    }

    .card {
      padding: 16px;
      border-radius: 18px;
      background: var(--panel-strong);
      border: 1px solid rgba(214, 199, 175, 0.7);
    }

    .card button {
      margin-top: 14px;
      width: 100%;
    }

    .user {
      margin: 0;
      font-size: 1.1rem;
      font-weight: 700;
      color: #0f172a;
    }

    .name {
      margin: 6px 0 0;
      color: var(--muted);
    }

    .tags {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 12px;
    }

    .tag {
      padding: 6px 10px;
      border-radius: 999px;
      background: #efe6d4;
      color: #5b4632;
      font-size: 0.82rem;
      font-weight: 700;
    }

    .request-card {
      display: grid;
      gap: 10px;
    }

    .request-top {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
      align-items: center;
    }

    .request-title {
      margin: 0;
      font-size: 1rem;
      font-weight: 700;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 7px 10px;
      border-radius: 999px;
      font-size: 0.8rem;
      font-weight: 800;
      letter-spacing: 0.03em;
      text-transform: uppercase;
    }

    .badge.pendiente {
      background: #fef3c7;
      color: #92400e;
    }

    .badge.autorizada,
    .badge.autorizada_gerente,
    .badge.aprobada_jefe {
      background: #dcfce7;
      color: #166534;
    }

    .badge.cancelada {
      background: #fee2e2;
      color: #991b1b;
    }

    .request-grid {
      display: grid;
      gap: 10px;
      grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .request-field {
      padding: 12px;
      border-radius: 14px;
      background: #fbf7f1;
      border: 1px solid rgba(214, 199, 175, 0.6);
    }

    .request-field strong {
      display: block;
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: var(--muted);
      margin-bottom: 4px;
    }

    .comment {
      margin: 0;
      padding: 12px 14px;
      border-radius: 14px;
      background: #fff7ed;
      color: #7c2d12;
    }

    .selected-user {
      padding: 14px 16px;
      border-radius: 16px;
      background: #ecfdf5;
      border: 1px solid #a7f3d0;
      color: #065f46;
    }

    .summary-grid {
      display: grid;
      gap: 10px;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      margin-top: 18px;
    }

    .inline-detail {
      margin-top: 14px;
      padding-top: 14px;
      border-top: 1px dashed rgba(180, 83, 9, 0.35);
    }

    @media (max-width: 860px) {
      .filters,
      .request-grid,
      .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 720px) {
      main {
        margin: 20px auto;
      }

      .row,
      .filters,
      .request-grid,
      .summary-grid {
        grid-template-columns: 1fr;
      }

      .section-head {
        display: grid;
      }
    }
  </style>
</head>
<body>
  <main>
    <section class="hero">
      <p class="eyebrow">Nueva version</p>
      <h1>Usuarios y solicitudes</h1>
      <p class="lead">
        Flujo minimo para validar frontend -> backend FastAPI -> Oracle 11g.
        Primero se busca por <strong>usuarios.usuario</strong> y luego se consultan solicitudes en modo solo lectura.
      </p>
    </section>

    <section class="panel">
      <div class="section-head">
        <div>
          <h2>1. Buscar usuario</h2>
          <p class="section-copy">Selecciona un usuario encontrado para usar su <strong>idusuariodata</strong> en la consulta siguiente.</p>
        </div>
      </div>

      <form id="search-form">
        <div class="row">
          <label>
            Usuario
            <input
              id="q"
              name="q"
              type="text"
              minlength="2"
              placeholder="Ejemplo: scardenas"
              required
            />
          </label>
          <button type="submit">Buscar</button>
        </div>

        <label class="toggle">
          <input id="exacto" name="exacto" type="checkbox" />
          Buscar coincidencia exacta
        </label>
      </form>

      <p id="meta" class="meta">Ingresa al menos 2 caracteres para consultar usuarios.</p>
      <div id="results" class="results"></div>
    </section>

    <section class="panel">
      <div class="section-head">
        <div>
          <h2>2. Resumen de vacaciones</h2>
          <p class="section-copy">Resumen base del saldo de vacaciones usando procedimientos Oracle del sistema legado.</p>
        </div>
      </div>

      <p id="summary-meta" class="meta">Selecciona un usuario para consultar su saldo de vacaciones.</p>
      <div id="summary-results" class="summary-grid"></div>
    </section>

    <section class="panel">
      <div class="section-head">
        <div>
          <h2>3. Consultar solicitudes</h2>
          <p class="section-copy">Esta consulta usa el endpoint nuevo <code>/solicitudes/consultar</code> en modo solo lectura.</p>
        </div>
      </div>

      <div id="selected-user" class="selected-user">Aun no has seleccionado un usuario.</div>

      <form id="requests-form">
        <div class="filters">
          <label>
            Id autorizador
            <input id="idusuariodata_autorizador" name="idusuariodata_autorizador" type="number" min="1" value="1" required />
          </label>

          <label>
            Id solicitud
            <input id="idsolicitud" name="idsolicitud" type="number" min="1" placeholder="Opcional" />
          </label>

          <label>
            Estado
            <select id="estado" name="estado">
              <option value="">Todos</option>
              <option value="pendiente">Pendiente</option>
              <option value="aprobada_jefe">Aprobada por jefe</option>
              <option value="autorizada">Autorizada RH</option>
              <option value="autorizada_gerente">Autorizada gerente</option>
              <option value="cancelada">Cancelada</option>
            </select>
          </label>

          <label>
            Fecha inicial
            <input id="fi" name="fi" type="date" />
          </label>

          <label>
            Fecha final
            <input id="ff" name="ff" type="date" />
          </label>

          <label>
            Tipo de fecha
            <select id="tipo_fecha" name="tipo_fecha">
              <option value="permiso">Permiso</option>
              <option value="alta">Alta</option>
            </select>
          </label>

          <label>
            Limite
            <input id="limit" name="limit" type="number" min="1" max="200" value="10" />
          </label>
        </div>

        <div class="row">
          <div></div>
          <button class="secondary" type="submit">Consultar solicitudes</button>
        </div>
      </form>

      <p id="requests-meta" class="meta">Selecciona un usuario arriba y luego consulta sus solicitudes.</p>
      <div id="requests-results" class="requests"></div>
    </section>

    <section class="panel">
      <div class="section-head">
        <div>
          <h2>4. Detalle de solicitud</h2>
          <p class="section-copy">Usa el boton <code>Ver detalle</code> para consultar el historial detallado de una solicitud.</p>
        </div>
      </div>

      <p id="detail-meta" class="meta">Aun no se ha consultado el detalle de ninguna solicitud.</p>
      <div id="detail-results" class="requests"></div>
    </section>
  </main>

  <script>
    const searchForm = document.getElementById("search-form");
    const meta = document.getElementById("meta");
    const results = document.getElementById("results");
    const input = document.getElementById("q");
    const exacto = document.getElementById("exacto");

    const requestsForm = document.getElementById("requests-form");
    const summaryMeta = document.getElementById("summary-meta");
    const summaryResults = document.getElementById("summary-results");
    const requestsMeta = document.getElementById("requests-meta");
    const requestsResults = document.getElementById("requests-results");
    const selectedUser = document.getElementById("selected-user");
    const detailMeta = document.getElementById("detail-meta");
    const detailResults = document.getElementById("detail-results");
    const autorizadorInput = document.getElementById("idusuariodata_autorizador");
    const idSolicitudInput = document.getElementById("idsolicitud");
    const estadoInput = document.getElementById("estado");
    const fiInput = document.getElementById("fi");
    const ffInput = document.getElementById("ff");
    const tipoFechaInput = document.getElementById("tipo_fecha");
    const limitInput = document.getElementById("limit");

    let currentUser = null;

    function escapeHtml(text) {
      return String(text)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#39;");
    }

    function setRequestsMessage(message, kind) {
      requestsMeta.textContent = message;
      requestsMeta.className = kind ? \`meta \${kind}\` : "meta";
    }

    function setSummaryMessage(message, kind) {
      summaryMeta.textContent = message;
      summaryMeta.className = kind ? \`meta \${kind}\` : "meta";
    }

    function setDetailMessage(message, kind) {
      detailMeta.textContent = message;
      detailMeta.className = kind ? \`meta \${kind}\` : "meta";
    }

    function setSelectedUser(user) {
      currentUser = user;

      if (!user) {
        selectedUser.innerHTML = "Aun no has seleccionado un usuario.";
        return;
      }

      selectedUser.innerHTML = \`
        <strong>Usuario seleccionado:</strong> \${escapeHtml(user.usuario)}
        | nombre: \${escapeHtml(user.nombre || "")}
        | idusuario: \${escapeHtml(user.idusuario)}
        | idusuariodata: \${escapeHtml(user.idusuariodata)}
      \`;
    }

    function renderUsers(items) {
      if (!items.length) {
        results.innerHTML = "";
        return;
      }

      results.innerHTML = items
        .map((item) => \`
          <article class="card">
            <p class="user">\${escapeHtml(item.usuario)}</p>
            <p class="name">\${escapeHtml(item.nombre || "")}</p>
            <div class="tags">
              <span class="tag">idusuario: \${escapeHtml(item.idusuario)}</span>
              <span class="tag">idusuariodata: \${escapeHtml(item.idusuariodata)}</span>
            </div>
            <button type="button" data-user='\${escapeHtml(JSON.stringify(item))}'>Usar este usuario</button>
          </article>
        \`)
        .join("");

      for (const button of results.querySelectorAll("button[data-user]")) {
        button.addEventListener("click", () => {
          const user = JSON.parse(button.dataset.user);
          setSelectedUser(user);
          summaryResults.innerHTML = "";
          requestsResults.innerHTML = "";
          detailResults.innerHTML = "";
          loadResumenVacaciones(user);
          setDetailMessage("Aun no se ha consultado el detalle de ninguna solicitud.", null);
          setRequestsMessage(
            \`Usuario listo para consultar solicitudes: \${user.usuario} (idusuariodata \${user.idusuariodata}).\`,
            "success"
          );
        });
      }
    }

    function renderRequests(items) {
      if (!items.length) {
        requestsResults.innerHTML = "";
        return;
      }

      requestsResults.innerHTML = items
        .map((item) => \`
          <article class="card request-card">
            <div class="request-top">
              <p class="request-title">Solicitud #\${escapeHtml(item.idsolicitud)} | \${escapeHtml(item.usuario)}</p>
              <span class="badge \${escapeHtml(item.estado || "desconocido")}">\${escapeHtml(item.estado || "desconocido")}</span>
            </div>
            <div class="request-grid">
              <div class="request-field">
                <strong>Tipo permiso</strong>
                <span>\${escapeHtml(item.tipodepermiso || "-")}</span>
              </div>
              <div class="request-field">
                <strong>Inicio</strong>
                <span>\${escapeHtml(item.fechainicio || "-")}</span>
              </div>
              <div class="request-field">
                <strong>Fin</strong>
                <span>\${escapeHtml(item.fechafin || "-")}</span>
              </div>
              <div class="request-field">
                <strong>Alta</strong>
                <span>\${escapeHtml(item.fechaalta || "-")}</span>
              </div>
              <div class="request-field">
                <strong>Total dias</strong>
                <span>\${escapeHtml(item.totaldias || 0)}</span>
              </div>
              <div class="request-field">
                <strong>Horas</strong>
                <span>\${escapeHtml(item.horas || "00:00")}</span>
              </div>
              <div class="request-field">
                <strong>Sucursal</strong>
                <span>\${escapeHtml(item.sucursal || "-")}</span>
              </div>
              <div class="request-field">
                <strong>Opcion pago</strong>
                <span>\${escapeHtml(item.opcionpago || "-")}</span>
              </div>
            </div>
            <p class="comment">Comentario: \${escapeHtml(item.comentario || "-")}</p>
            <button type="button" class="secondary" data-detail-id="\${escapeHtml(item.idsolicitud)}">Ver detalle</button>
            <div class="inline-detail" id="inline-detail-\${escapeHtml(item.idsolicitud)}"></div>
          </article>
        \`)
        .join("");

      for (const button of requestsResults.querySelectorAll("button[data-detail-id]")) {
        button.addEventListener("click", () => {
          loadDetalleSolicitud(button.dataset.detailId);
        });
      }
    }

    function renderResumenVacaciones(item) {
      if (!item) {
        summaryResults.innerHTML = "";
        return;
      }

      summaryResults.innerHTML = [
        { label: "Nombre", value: item.nombre || "-" },
        { label: "Departamento", value: item.departamento || "-" },
        { label: "Antiguedad", value: \`\${item.antiguedad || 0} anos\` },
        { label: "Fecha ingreso", value: item.fecha_ingreso || "-" },
        { label: "Fecha cumple", value: item.fecha_cumple || "-" },
        { label: "Fecha referencia", value: item.fecha_referencia || "-" },
        { label: "Dias derecho", value: item.dias_derecho || 0 },
        { label: "Dias tomados", value: item.dias_tomados || 0 },
        { label: "Dias restantes", value: item.dias_restantes || 0 },
        { label: "Dias laborables", value: item.dias_laborables || "-" },
        { label: "Solicitudes pendientes", value: item.solicitudes_pendientes || 0 },
        { label: "Usuario", value: item.usuario || "-" },
      ]
        .map((field) => \`
          <div class="request-field">
            <strong>\${escapeHtml(field.label)}</strong>
            <span>\${escapeHtml(field.value)}</span>
          </div>
        \`)
        .join("");
    }

    function renderDetalle(items, idsolicitud) {
      if (!items.length) {
        detailResults.innerHTML = "";
        return;
      }

      detailResults.innerHTML = items
        .map((item) => \`
          <article class="card request-card">
            <div class="request-top">
              <p class="request-title">Historial de solicitud #\${escapeHtml(idsolicitud)}</p>
              <span class="badge \${escapeHtml((item.tipoaccion || "desconocido").toLowerCase())}">\${escapeHtml(item.accion_legible || item.tipoaccion || "Desconocida")}</span>
            </div>
            <div class="request-grid">
              <div class="request-field"><strong>Nombre</strong><span>\${escapeHtml(item.nombre || "-")}</span></div>
              <div class="request-field"><strong>Solicito</strong><span>\${escapeHtml(item.usuariosolicito || "-")}</span></div>
              <div class="request-field"><strong>Departamento</strong><span>\${escapeHtml(item.depto || "-")}</span></div>
              <div class="request-field"><strong>Tipo permiso</strong><span>\${escapeHtml(item.tipodepermiso || "-")}</span></div>
              <div class="request-field"><strong>Inicio</strong><span>\${escapeHtml(item.fechainicio || "-")}</span></div>
              <div class="request-field"><strong>Fin</strong><span>\${escapeHtml(item.fechafin || "-")}</span></div>
              <div class="request-field"><strong>Alta</strong><span>\${escapeHtml(item.fechaalta || "-")}</span></div>
              <div class="request-field"><strong>Fecha accion</strong><span>\${escapeHtml(item.fechaaccion || "-")}</span></div>
              <div class="request-field"><strong>Jefe area</strong><span>\${escapeHtml(item.jefearea || "-")}</span></div>
              <div class="request-field"><strong>Fecha jefe</strong><span>\${escapeHtml(item.fechaautorizojefearea || "-")}</span></div>
              <div class="request-field"><strong>RH</strong><span>\${escapeHtml(item.rh || "-")}</span></div>
              <div class="request-field"><strong>Fecha RH</strong><span>\${escapeHtml(item.fechaautorizorecursosh || "-")}</span></div>
              <div class="request-field"><strong>Gerente</strong><span>\${escapeHtml(item.gerente || "-")}</span></div>
              <div class="request-field"><strong>Fecha gerente</strong><span>\${escapeHtml(item.fechaautorizogerente || "-")}</span></div>
              <div class="request-field"><strong>Total dias</strong><span>\${escapeHtml(item.totaldias || 0)}</span></div>
              <div class="request-field"><strong>Horas</strong><span>\${escapeHtml(item.horas || 0)}</span></div>
            </div>
            <p class="comment">Comentario: \${escapeHtml(item.comentario || "-")}</p>
          </article>
        \`)
        .join("");
    }

    async function buscarUsuarios(event) {
      event.preventDefault();

      const query = input.value.trim();
      if (query.length < 2) {
        meta.textContent = "Escribe al menos 2 caracteres.";
        meta.className = "meta error";
        results.innerHTML = "";
        return;
      }

      const params = new URLSearchParams({ q: query });
      if (exacto.checked) {
        params.set("exacto", "true");
      }

      meta.textContent = "Consultando usuarios...";
      meta.className = "meta";
      results.innerHTML = "";

      try {
        const response = await fetch(\`/api/usuarios/buscar?\${params.toString()}\`);
        const data = await response.json();

        if (!response.ok || data.status !== "ok") {
          throw new Error(data.message || "No fue posible consultar usuarios.");
        }

        meta.textContent = \`Consulta "\${data.query}" | exacto: \${data.exacto ? "si" : "no"} | resultados: \${data.count}\`;
        meta.className = "meta";
        renderUsers(data.items || []);

        if (!data.count) {
          meta.textContent += " | sin coincidencias";
          setSelectedUser(null);
        }
      } catch (error) {
        meta.textContent = error.message;
        meta.className = "meta error";
        results.innerHTML = "";
      }
    }

    async function consultarSolicitudes(event) {
      event.preventDefault();

      if (!currentUser) {
        setRequestsMessage("Primero selecciona un usuario en la seccion de busqueda.", "error");
        requestsResults.innerHTML = "";
        return;
      }

      const autorizador = autorizadorInput.value.trim();
      if (!autorizador) {
        setRequestsMessage("El id del autorizador es obligatorio.", "error");
        requestsResults.innerHTML = "";
        return;
      }

      const params = new URLSearchParams({
        idusuariodata_autorizador: autorizador,
        idusuariodata: String(currentUser.idusuariodata),
        tipo_fecha: tipoFechaInput.value,
        limit: limitInput.value || "10",
      });

      if (idSolicitudInput.value) {
        params.set("idsolicitud", idSolicitudInput.value);
      }
      if (estadoInput.value) {
        params.set("estado", estadoInput.value);
      }
      if (fiInput.value) {
        params.set("fi", fiInput.value);
      }
      if (ffInput.value) {
        params.set("ff", ffInput.value);
      }

      setRequestsMessage(
        \`Consultando solicitudes de \${currentUser.usuario} con autorizador \${autorizador}...\`,
        null
      );
      requestsResults.innerHTML = "";

      try {
        const response = await fetch(\`/api/solicitudes/consultar?\${params.toString()}\`);
        const data = await response.json();

        if (!response.ok || data.status !== "ok") {
          throw new Error(data.message || "No fue posible consultar solicitudes.");
        }

        setRequestsMessage(
          \`Solicitudes de \${currentUser.usuario} | resultados: \${data.count} | estado: \${data.filters.estado || "todos"} | solicitud: \${data.filters.idsolicitud || "todas"}\`,
          data.count ? "success" : null
        );
        renderRequests(data.items || []);

        if (!data.count) {
          setRequestsMessage(
            \`No se encontraron solicitudes para \${currentUser.usuario} con los filtros actuales.\`,
            null
          );
        }
      } catch (error) {
        setRequestsMessage(error.message, "error");
        requestsResults.innerHTML = "";
      }
    }

    async function loadResumenVacaciones(user) {
      setSummaryMessage(\`Consultando resumen de vacaciones de \${user.usuario}...\`, null);
      summaryResults.innerHTML = "";

      try {
        const response = await fetch(\`/api/vacaciones/resumen?idusuario=\${encodeURIComponent(user.idusuario)}\`);
        const data = await response.json();

        if (!response.ok || data.status !== "ok") {
          throw new Error(data.message || "No fue posible consultar el resumen de vacaciones.");
        }

        renderResumenVacaciones(data.item || null);
        setSummaryMessage(
          \`Resumen cargado para \${user.usuario} | dias restantes: \${data.item?.dias_restantes ?? 0}\`,
          "success"
        );
      } catch (error) {
        setSummaryMessage(error.message, "error");
        summaryResults.innerHTML = "";
      }
    }

    async function loadDetalleSolicitud(idsolicitud) {
      setDetailMessage(\`Consultando detalle de la solicitud \${idsolicitud}...\`, null);
      detailResults.innerHTML = "";
      for (const container of requestsResults.querySelectorAll(".inline-detail")) {
        container.innerHTML = "";
      }

      const inlineTarget = document.getElementById(\`inline-detail-\${idsolicitud}\`);
      if (inlineTarget) {
        inlineTarget.innerHTML = "<p class='meta'>Cargando detalle...</p>";
      }

      try {
        const response = await fetch(\`/api/solicitudes/detalle?idsolicitud=\${encodeURIComponent(idsolicitud)}\`);
        const data = await response.json();

        if (!response.ok || data.status !== "ok") {
          throw new Error(data.message || "No fue posible consultar el detalle.");
        }

        setDetailMessage(
          \`Detalle cargado para la solicitud \${data.idsolicitud} | registros: \${data.count}\`,
          data.count ? "success" : null
        );
        renderDetalle(data.items || [], data.idsolicitud);
        if (inlineTarget) {
          inlineTarget.innerHTML = detailResults.innerHTML;
        }

        if (!data.count) {
          setDetailMessage(\`La solicitud \${data.idsolicitud} no devolvio historial.\`, null);
          if (inlineTarget) {
            inlineTarget.innerHTML = "<p class='meta'>La solicitud no devolvio historial.</p>";
          }
        }
      } catch (error) {
        setDetailMessage(error.message, "error");
        detailResults.innerHTML = "";
        if (inlineTarget) {
          inlineTarget.innerHTML = \`<p class="meta error">\${escapeHtml(error.message)}</p>\`;
        }
      }
    }

    searchForm.addEventListener("submit", buscarUsuarios);
    requestsForm.addEventListener("submit", consultarSolicitudes);
  </script>
</body>
</html>
`;

module.exports = {
  html,
};
