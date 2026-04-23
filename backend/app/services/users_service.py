from typing import Any

from ..db_oracle import get_connection


def _normalize_column_name(name: str) -> str:
    name = (name or "").strip().lower()

    aliases = {
        "initcap(u.nombre)": "nombre",
    }

    return aliases.get(name, name)


def _rows_to_dicts(cursor) -> list[dict[str, Any]]:
    if cursor.description is None:
        return []

    columns = [_normalize_column_name(col[0]) for col in cursor.description]
    rows = cursor.fetchall()
    return [dict(zip(columns, row)) for row in rows]


def obtener_argumentos_procedimiento(nombre: str) -> list[dict[str, Any]]:
    conn = None
    cur = None

    try:
        conn = get_connection()
        cur = conn.cursor()

        cur.execute(
            """
            SELECT
                owner,
                package_name,
                object_name,
                overload,
                argument_name,
                position,
                sequence,
                data_type,
                in_out
            FROM all_arguments
            WHERE object_name = UPPER(:nombre)
            ORDER BY owner, package_name, object_name, overload, sequence
            """,
            {"nombre": nombre},
        )

        return _rows_to_dicts(cur)

    finally:
        if cur:
            cur.close()
        if conn:
            conn.close()


def buscar_usuarios_por_usuario(texto: str, exacto: bool = False) -> list[dict[str, Any]]:
    texto = (texto or "").strip()

    if len(texto) < 2:
        return []

    conn = None
    cur = None

    try:
        conn = get_connection()
        cur = conn.cursor()

        if exacto:
            query = """
                SELECT
                    u.idusuario AS idusuario,
                    u.usuario AS usuario,
                    INITCAP(u.nombre) AS nombre,
                    u.idusuariodata AS idusuariodata
                FROM usuarios u
                WHERE LOWER(u.usuario) = LOWER(:texto)
                ORDER BY u.usuario
            """
            params = {"texto": texto}
        else:
            query = """
                SELECT
                    u.idusuario AS idusuario,
                    u.usuario AS usuario,
                    INITCAP(u.nombre) AS nombre,
                    u.idusuariodata AS idusuariodata
                FROM usuarios u
                WHERE LOWER(u.usuario) LIKE LOWER(:texto)
                ORDER BY u.usuario
            """
            params = {"texto": f"%{texto}%"}

        cur.execute(query, params)

        return _rows_to_dicts(cur)

    finally:
        if cur:
            cur.close()
        if conn:
            conn.close()
