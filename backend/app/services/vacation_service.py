from datetime import datetime
from typing import Any

import oracledb

from ..db_oracle import get_connection


def obtener_resumen_vacaciones(idusuario: int) -> dict[str, Any]:
    conn = None
    cur = None

    try:
        conn = get_connection()
        cur = conn.cursor()

        datos = _obtener_datos_base(cur, idusuario)
        total_dias_tomados = _obtener_total_dias_tomados(
            cur,
            idusuario=idusuario,
            antiguedad=datos["antiguedad"],
        )
        fecha_referencia = _obtener_fecha_referencia(cur, idusuario)
        dias_derecho = _obtener_dias_derecho(
            cur,
            antiguedad=datos["antiguedad"],
            fecha_referencia=fecha_referencia,
        )
        dias_laborables = _obtener_dias_laborables(
            cur,
            idusuario=idusuario,
            fecha_ingreso=datos["fecha_ingreso"],
        )

        return {
            "idusuario": idusuario,
            "idusuariodata": datos["idusuariodata"],
            "usuario": datos["usuario"],
            "nombre": datos["nombre"],
            "departamento": datos["departamento"],
            "antiguedad": datos["antiguedad"],
            "fecha_ingreso": datos["fecha_ingreso"],
            "fecha_cumple": datos["fecha_cumple"],
            "fecha_referencia": fecha_referencia,
            "dias_derecho": dias_derecho,
            "dias_tomados": total_dias_tomados,
            "dias_restantes": dias_derecho - total_dias_tomados,
            "dias_laborables": dias_laborables,
            "solicitudes_pendientes": datos["solicitudes_pendientes"],
        }
    finally:
        if cur:
            cur.close()
        if conn:
            conn.close()


def _obtener_datos_base(cursor, idusuario: int) -> dict[str, Any]:
    nombre = cursor.var(str, size=100)
    apaterno = cursor.var(str, size=100)
    amaterno = cursor.var(str, size=100)
    departamento = cursor.var(str, size=150)
    antiguedad = cursor.var(oracledb.DB_TYPE_NUMBER)
    fecha_ingreso = cursor.var(oracledb.DB_TYPE_DATE)
    fecha_cumple = cursor.var(oracledb.DB_TYPE_DATE)
    dias = cursor.var(oracledb.DB_TYPE_NUMBER)
    dias_laborables = cursor.var(str, size=64)
    idusuariodata = cursor.var(oracledb.DB_TYPE_NUMBER)
    solicitudes_pendientes = cursor.var(oracledb.DB_TYPE_NUMBER)
    direccion = cursor.var(str, size=200)
    celular = cursor.var(str, size=32)
    email = cursor.var(str, size=64)
    usuario = cursor.var(str, size=64)
    clave = cursor.var(str, size=64)
    idsucursal = cursor.var(str, size=64)

    cursor.execute(
        """
        BEGIN
            VAC_PRC_CONTARDIASRESTANTES(
                :idu,
                :nombre,
                :apaterno,
                :amaterno,
                :departamento,
                :antiguedad,
                :fechaIngreso,
                :fechaCumple,
                :dias,
                :diasLaborables,
                :idud,
                :solPendientes,
                :direccionOut,
                :celularOut,
                :emailOut,
                :usuarioOut,
                :claveOut,
                :idsucursal
            );
        END;
        """,
        {
            "idu": idusuario,
            "nombre": nombre,
            "apaterno": apaterno,
            "amaterno": amaterno,
            "departamento": departamento,
            "antiguedad": antiguedad,
            "fechaIngreso": fecha_ingreso,
            "fechaCumple": fecha_cumple,
            "dias": dias,
            "diasLaborables": dias_laborables,
            "idud": idusuariodata,
            "solPendientes": solicitudes_pendientes,
            "direccionOut": direccion,
            "celularOut": celular,
            "emailOut": email,
            "usuarioOut": usuario,
            "claveOut": clave,
            "idsucursal": idsucursal,
        },
    )

    nombre_completo = " ".join(
        part.strip()
        for part in (nombre.getvalue(), apaterno.getvalue(), amaterno.getvalue())
        if part and part.strip()
    )

    return {
        "nombre": nombre_completo,
        "departamento": _clean_text(departamento.getvalue()),
        "antiguedad": _to_int(antiguedad.getvalue()),
        "fecha_ingreso": _format_oracle_date(fecha_ingreso.getvalue()),
        "fecha_cumple": _format_oracle_date(fecha_cumple.getvalue()),
        "idusuariodata": _to_int(idusuariodata.getvalue()),
        "solicitudes_pendientes": _to_int(solicitudes_pendientes.getvalue()),
        "usuario": _clean_text(usuario.getvalue()),
    }


def _obtener_total_dias_tomados(cursor, idusuario: int, antiguedad: int) -> int:
    total_dias = cursor.var(oracledb.DB_TYPE_NUMBER)

    cursor.execute(
        "BEGIN VAC_PRC_DIASTOMADOS_ANTIGUEDAD(:idu,:totald,:ant); END;",
        {
            "idu": idusuario,
            "totald": total_dias,
            "ant": antiguedad,
        },
    )

    return _to_int(total_dias.getvalue())


def _obtener_fecha_referencia(cursor, idusuario: int) -> str:
    fecha_referencia = cursor.var(str, size=32)

    cursor.execute(
        "BEGIN VAC_PRC_FECHAREFERENCIA(:idu,:fecharef); END;",
        {
            "idu": idusuario,
            "fecharef": fecha_referencia,
        },
    )

    return _clean_text(fecha_referencia.getvalue())


def _obtener_dias_derecho(cursor, antiguedad: int, fecha_referencia: str) -> int:
    antiguedad_calculo = antiguedad
    antiguedad_original = antiguedad
    aplica_extra = False

    if antiguedad_calculo > 16:
        antiguedad_calculo = 16
        aplica_extra = True

    dias_derecho = cursor.var(oracledb.DB_TYPE_NUMBER)
    cursor.execute(
        "BEGIN VAC_PRC_DIASDERECHO(:antiguedad,:fechareferencia,:diasderecho); END;",
        {
            "antiguedad": antiguedad_calculo,
            "fechareferencia": fecha_referencia,
            "diasderecho": dias_derecho,
        },
    )

    resultado = _to_int(dias_derecho.getvalue())

    if aplica_extra:
        dias_extra = 25 if _fecha_referencia_es_2011_03_31(fecha_referencia) else 18
        for _ in range(16, antiguedad_original):
            resultado += dias_extra

    return resultado


def _obtener_dias_laborables(cursor, idusuario: int, fecha_ingreso: str) -> str:
    dias_laborables = cursor.var(str, size=64)

    cursor.execute(
        "BEGIN VAC_PRC_revisar_fi_dl_pr(:idu,:fi,:diaslaborados); END;",
        {
            "idu": idusuario,
            "fi": fecha_ingreso,
            "diaslaborados": dias_laborables,
        },
    )

    return _clean_text(dias_laborables.getvalue())


def _fecha_referencia_es_2011_03_31(fecha_referencia: str) -> bool:
    if not fecha_referencia:
        return False

    parts = fecha_referencia.split("/")
    if len(parts) != 3:
        return False

    dia, mes, anio = parts
    if len(anio) == 2:
        anio = f"20{anio}"

    return f"{anio}-{mes}-{dia}" == "2011-03-31"


def _to_int(value: Any) -> int:
    if value in (None, ""):
        return 0
    return int(value)


def _clean_text(value: Any) -> str:
    if value is None:
        return ""
    return str(value).strip()


def _format_oracle_date(value: Any) -> str:
    if value is None:
        return ""
    if isinstance(value, datetime):
        return value.strftime("%Y-%m-%d")
    return _clean_text(value)
