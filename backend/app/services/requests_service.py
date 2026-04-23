from datetime import date
from typing import Any

from ..db_oracle import get_connection
from .users_service import _rows_to_dicts


def buscar_solicitudes(
    idusuariodata_autorizador: int,
    idusuariodata: int | None = None,
    estado: str | None = None,
    fi: date | None = None,
    ff: date | None = None,
    tipo_fecha: str = "permiso",
    limit: int = 50,
) -> list[dict[str, Any]]:
    conn = None
    cur = None

    where_clauses = [
        "vsv.fechainicio > DATE '1990-12-31'",
        "vu.idusuario = :idusuariodata_autorizador",
    ]
    params: dict[str, Any] = {
        "idusuariodata_autorizador": idusuariodata_autorizador,
        "limit": limit,
    }

    if idusuariodata is not None:
        where_clauses.append("u.idusuariodata = :idusuariodata")
        params["idusuariodata"] = idusuariodata

    if estado:
        estado_clause = _build_estado_clause(estado)
        if estado_clause:
            where_clauses.append(estado_clause)

    fecha_clause = _build_fecha_clause(fi=fi, ff=ff, tipo_fecha=tipo_fecha, params=params)
    if fecha_clause:
        where_clauses.append(fecha_clause)

    sql = f"""
        SELECT *
        FROM (
            SELECT
                vsv.idsolicitud AS idsolicitud,
                vsv.idusuario AS idusuario,
                u.idusuariodata AS idusuariodata,
                u.usuario AS usuario,
                INITCAP(vsv.nombre) AS nombre,
                s.idsucursal AS idsucursal,
                s.abrev AS sucursal,
                TO_CHAR(vsv.fechainicio, 'YYYY-MM-DD HH24:MI:SS') AS fechainicio,
                TO_CHAR(vsv.fechafin, 'YYYY-MM-DD HH24:MI:SS') AS fechafin,
                TO_CHAR(vsv.fechaalta, 'YYYY-MM-DD HH24:MI:SS') AS fechaalta,
                CASE
                    WHEN vsv.tipodepermiso = 'Horas extras' THEN 0
                    ELSE vsv.totaldias
                END AS totaldias,
                CASE
                    WHEN vsv.tipodepermiso = 'Horas extras'
                    THEN LPAD(TRUNC(vsv.horas), 2, '0') || ':' || LPAD(TRUNC((vsv.horas - TRUNC(vsv.horas)) * 60), 2, '0')
                    ELSE '00:00'
                END AS horas,
                vsv.tipodepermiso AS tipodepermiso,
                vsv.opcionpago AS opcionpago,
                vsv.tipoaccion AS tipoaccion,
                CASE
                    WHEN vsv.tipoaccion = 'den' THEN 'cancelada'
                    WHEN vsv.tipoaccion = 'au' AND vsv.fechaautorizorecursosh IS NOT NULL THEN 'autorizada'
                    WHEN vsv.tipoaccion = 'ag' AND vsv.fechaautorizogerente IS NOT NULL THEN 'autorizada_gerente'
                    WHEN vsv.tipoaccion = 'pa' AND vsv.fechaautorizojefearea IS NOT NULL THEN 'aprobada_jefe'
                    WHEN vsv.tipoaccion = 'alta' THEN 'pendiente'
                    WHEN vsv.tipoaccion = 'pa' AND vsv.fechaautorizojefearea IS NULL THEN 'pendiente'
                    WHEN vsv.tipoaccion = 'ag' AND vsv.fechaautorizogerente IS NULL THEN 'pendiente'
                    ELSE 'desconocido'
                END AS estado,
                REPLACE(REPLACE(NVL(vsv.comentario, ' '), CHR(10), '<br>'), CHR(13), '') AS comentario
            FROM vac_solicitudesvacaciones vsv
            INNER JOIN usuarios u ON u.idusuario = vsv.idusuario
            INNER JOIN sucursales s ON s.idsucursal = u.idsucursal
            LEFT JOIN vac_usrautdpto vu
                ON vu.iddpto = vsv.iddepartamento
                AND vu.idsucursal = s.idsucursal
            WHERE {" AND ".join(where_clauses)}
            ORDER BY vsv.idsolicitud DESC
        )
        WHERE ROWNUM <= :limit
    """

    try:
        conn = get_connection()
        cur = conn.cursor()
        cur.execute(sql, params)
        return _rows_to_dicts(cur)
    finally:
        if cur:
            cur.close()
        if conn:
            conn.close()


def obtener_historial_solicitud(idsolicitud: int) -> list[dict[str, Any]]:
    conn = None
    cur = None
    history_cursor = None

    try:
        conn = get_connection()
        cur = conn.cursor()
        history_cursor = conn.cursor()

        cur.execute(
            "BEGIN VAC_PRC_HISTORIALSOLICITUD(:idsol, :historial); END;",
            {
                "idsol": idsolicitud,
                "historial": history_cursor,
            },
        )

        items = _rows_to_dicts(history_cursor)
        for item in items:
            item["accion_legible"] = _resolve_action_label(item)

        return items
    finally:
        if history_cursor:
            history_cursor.close()
        if cur:
            cur.close()
        if conn:
            conn.close()


def _build_estado_clause(estado: str) -> str | None:
    normalized = (estado or "").strip().lower()

    estado_map = {
        "autorizada": "(vsv.tipoaccion = 'au' AND vsv.fechaautorizorecursosh IS NOT NULL)",
        "aprobada_jefe": "(vsv.tipoaccion = 'pa' AND vsv.fechaautorizojefearea IS NOT NULL)",
        "autorizada_gerente": "(vsv.tipoaccion = 'ag' AND vsv.fechaautorizogerente IS NOT NULL)",
        "cancelada": "(vsv.tipoaccion = 'den')",
        "pendiente": "(vsv.tipoaccion = 'alta' OR (vsv.tipoaccion = 'pa' AND vsv.fechaautorizojefearea IS NULL) OR (vsv.tipoaccion = 'ag' AND vsv.fechaautorizogerente IS NULL))",
    }

    return estado_map.get(normalized)


def _resolve_action_label(item: dict[str, Any]) -> str:
    raw_action = None

    for key in ("tipoaccion", "TIPOACCION", "accion", "ACCION"):
        if key in item and item[key]:
            raw_action = str(item[key]).strip().lower()
            break

    action_map = {
        "alta": "Alta",
        "au": "Aprobacion",
        "den": "Cancelacion",
        "mod": "Modificacion",
        "pa": "Aprobacion jefe",
        "ag": "Aprobacion gerente",
    }

    return action_map.get(raw_action, raw_action or "Desconocida")


def _build_fecha_clause(
    fi: date | None,
    ff: date | None,
    tipo_fecha: str,
    params: dict[str, Any],
) -> str | None:
    if fi is None and ff is None:
        return None

    normalized = (tipo_fecha or "permiso").strip().lower()

    if fi is not None:
        params["fi"] = fi
    if ff is not None:
        params["ff"] = ff

    if normalized == "alta":
        if fi is not None and ff is not None:
            return "TRUNC(vsv.fechaalta) BETWEEN :fi AND :ff"
        if fi is not None:
            return "TRUNC(vsv.fechaalta) = :fi"
        return "TRUNC(vsv.fechaalta) = :ff"

    if fi is not None and ff is not None:
        return """
            (
                (vsv.fechainicio >= :fi AND (vsv.fechainicio <= :ff OR vsv.fechafin <= :ff))
                OR (vsv.fechafin >= :fi AND vsv.fechafin <= :ff)
                OR (vsv.fechainicio <= :fi AND vsv.fechafin >= :ff)
            )
        """
    if fi is not None:
        return "(vsv.fechainicio >= :fi OR vsv.fechafin >= :fi)"
    return "(vsv.fechafin <= :ff OR vsv.fechainicio <= :ff)"
